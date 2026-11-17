<?php

namespace Tests\Unit;

use App\Http\Controllers\SurveyController;
use App\Models\Survey;
use App\Support\SurveyFields;
use App\Support\SurveyWorkbook;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use PharData;
use PHPUnit\Framework\TestCase;

class SurveyTransferTest extends TestCase
{
    private array $paths = [];

    private ?Container $previousContainer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->previousContainer = Container::getInstance();
        $container = new Container;
        Container::setInstance($container);
        $container->instance('request', Request::create('/'));
        Facade::setFacadeApplication($container);
        Validator::swap(new Factory(new Translator(new ArrayLoader, 'en'), $container));
    }

    protected function tearDown(): void
    {
        foreach ($this->paths as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }
        Facade::clearResolvedInstances();
        Container::setInstance($this->previousContainer);
        Facade::setFacadeApplication($this->previousContainer);
        parent::tearDown();
    }

    public function test_real_xlsx_round_trip_preserves_form_order_arabic_and_literal_formula_text(): void
    {
        $fields = SurveyFields::all();
        $row = array_map(fn ($field) => $field['options'] ? reset($field['options']) : '=1+1 & نص <عربي>', $fields);
        $rows = [array_column($fields, 'label'), array_values($row)];
        $workbook = new SurveyWorkbook;
        $path = $workbook->write($rows);
        $this->paths[] = $path;
        $this->assertSame("PK\x03\x04", file_get_contents($path, false, null, 0, 4));
        $this->assertSame($rows, array_values($workbook->read($path)));
        $zip = new PharData($path);
        $this->assertStringNotContainsString('<f>', $zip['xl/worksheets/sheet1.xml']->getContent());
    }

    public function test_excel_shared_strings_sparse_cells_and_real_row_numbers(): void
    {
        $workbook = new SurveyWorkbook;
        $path = $workbook->write([array_column(SurveyFields::all(), 'label')]);
        $this->paths[] = $path;
        $zip = new PharData($path);
        $zip['xl/sharedStrings.xml'] = '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><si><r><t>أنثى</t></r></si></sst>';
        $sheet = $zip['xl/worksheets/sheet1.xml']->getContent();
        $zip['xl/worksheets/sheet1.xml'] = str_replace('</sheetData>', '<row r="9"><c r="A9" t="s"><v>0</v></c><c r="R9" t="inlineStr"><is><t>اقتراح</t></is></c></row></sheetData>', $sheet);
        $zip->compressFiles(\Phar::GZ);
        unset($zip);
        $uploadPath = $path.'.xlsx';
        copy($path, $uploadPath);
        $this->paths[] = $uploadPath;
        $rows = $workbook->read($uploadPath);
        $this->assertSame('أنثى', $rows[9][0]);
        $this->assertSame('', $rows[9][1]);
        $this->assertSame('اقتراح', $rows[9][17]);
    }

    public function test_import_rejects_formulas_instead_of_using_cached_results(): void
    {
        $workbook = new SurveyWorkbook;
        $path = $workbook->write([['الجنس']]);
        $this->paths[] = $path;
        $zip = new PharData($path);
        $zip['xl/worksheets/sheet1.xml'] = '<worksheet><sheetData><row r="2"><c r="A2"><f>1+1</f><v>2</v></c></row></sheetData></worksheet>';
        unset($zip);
        $this->expectException(InvalidArgumentException::class);
        $workbook->read($path);
    }

    public function test_search_keeps_other_filters_for_both_listing_and_export(): void
    {
        $database = new Manager;
        $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $database->setAsGlobal();
        $database->bootEloquent();
        // Only this table is needed; never use the application's configured database.
        $database->schema()->create('surveys', function ($table) {
            $table->id();
            foreach (array_keys(SurveyFields::all()) as $key) {
                $table->text($key)->nullable();
            }
            $table->string('survey_number')->nullable();
            $table->timestamps();
        });
        Survey::create(['gender' => 'female', 'suggestions' => 'match']);
        Survey::create(['gender' => 'male', 'suggestions' => 'match']);
        $method = new \ReflectionMethod(SurveyController::class, 'filteredQuery');
        $query = $method->invoke(new SurveyController, Request::create('/', 'GET', ['gender' => 'female', 'search' => 'match']));
        $this->assertSame(['female'], $query->pluck('gender')->all());

        Survey::query()->update(['created_at' => '2026-11-17 23:59:59']);
        $database->table('surveys')->insert([
            ['gender' => 'female', 'suggestions' => 'match', 'created_at' => '2026-11-18 00:00:00'],
            ['gender' => 'female', 'suggestions' => 'match', 'created_at' => '2026-11-16 23:59:59'],
        ]);
        $query = $method->invoke(new SurveyController, Request::create('/', 'GET', [
            'gender' => 'female', 'search' => 'match',
            'from_date' => '2026-11-17', 'to_date' => '2026-11-17',
        ]));
        $this->assertSame(['2026-11-17 23:59:59'], $query->pluck('created_at')->map->format('Y-m-d H:i:s')->all());
    }

    public function test_transfer_routes_precede_the_survey_binding(): void
    {
        $routes = file_get_contents(__DIR__.'/../../routes/web.php');
        $binding = strpos($routes, "Route::get('/{survey}'");
        $this->assertLessThan($binding, strpos($routes, "Route::get('/export', [SurveyController"));
        $this->assertLessThan($binding, strpos($routes, "Route::get('/template', [SurveyController"));
    }

    private function importRows(array $rows): array
    {
        $method = new \ReflectionMethod(SurveyController::class, 'validateImportRows');

        return $method->invoke(new SurveyController, $rows);
    }

    private function sampleRows(): array
    {
        $fields = SurveyFields::all();

        return [1 => array_column($fields, 'label'), 2 => array_values(array_map(
            fn ($field) => $field['options'] ? reset($field['options']) : '', $fields
        ))];
    }

    public function test_import_translates_arabic_and_accepts_empty_optional_cells(): void
    {
        $rows = $this->sampleRows();
        $rows[2][0] = '';
        $rows[3] = array_fill(0, 18, '');
        $records = $this->importRows($rows);
        $this->assertCount(1, $records);
        $this->assertNull($records[0]['gender']);
        $this->assertSame('excellent', $records[0]['toilet_cleanliness']);
        $this->assertNull($records[0]['suggestions']);
    }

    public function test_invalid_later_row_rejects_the_entire_import_and_reports_its_number(): void
    {
        $rows = $this->sampleRows();
        $rows[7] = $rows[2];
        $rows[7][4] = 'خيار خاطئ';
        try {
            $this->importRows($rows);
            $this->fail('Invalid rows must prevent the batch from being returned for storage.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('الصف 7', $exception->errors()['file'][0]);
            $this->assertStringContainsString('نظافة دورات المياه', $exception->errors()['file'][0]);
        }
    }

    public function test_reordered_headers_are_rejected(): void
    {
        $rows = $this->sampleRows();
        [$rows[1][0], $rows[1][1]] = [$rows[1][1], $rows[1][0]];
        $this->expectException(ValidationException::class);
        $this->importRows($rows);
    }
}
