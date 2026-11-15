<?php

namespace Tests\Unit;

use App\Http\Controllers\GilbertTriangleController;
use App\Models\ActualResult;
use App\Models\ResourceTracking;
use App\Support\PerformanceTransfer;
use App\Support\SurveyWorkbook;
use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class PerformanceTransferTest extends TestCase
{
    private Manager $database;

    private PerformanceTransfer $transfer;

    private $previousResolver;

    private Container $previousContainer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->previousContainer = Container::getInstance();
        $this->previousResolver = Model::getConnectionResolver();
        $container = new Container;
        Container::setInstance($container);
        Facade::setFacadeApplication($container);
        Facade::clearResolvedInstances();
        $this->database = new Manager($container);
        $this->database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $this->database->bootEloquent();
        DB::swap($this->database->getDatabaseManager());
        Validator::swap(new Factory(new Translator(new ArrayLoader, 'en'), $container));
        Log::swap(new NullLogger);
        $responses = $this->createMock(ResponseFactory::class);
        $responses->method('json')->willReturnCallback(fn ($data) => new JsonResponse($data));
        $container->instance(ResponseFactory::class, $responses);
        $schema = $this->database->getConnection()->getSchemaBuilder();
        $schema->create('units', function ($table) {
            $table->id();
            $table->string('name');
        });
        $schema->create('unit_goals', function ($table) {
            $table->id();
            $table->integer('unit_id');
            $table->date('date');
            $table->integer('department_goal_id')->nullable();
            $table->integer('target_tasks');
        });
        $schema->create('resource_trackings', function ($table) {
            $table->id();
            $table->date('date');
            $table->integer('unit_id');
            $table->integer('working_hours');
            $table->float('total_supplies_and_tools_score');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['date', 'unit_id']);
        });
        $schema->create('actual_results', function ($table) {
            $table->id();
            $table->date('date');
            $table->integer('unit_id');
            $table->integer('unit_goal_id');
            $table->integer('department_goal_id')->nullable();
            $table->integer('completed_tasks');
            foreach (['working_hours', 'quality_rating', 'efficiency_score', 'effectiveness', 'efficiency', 'relevance', 'overall_performance_score'] as $key) {
                $table->float($key)->nullable();
            }
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['date', 'unit_goal_id']);
        });
        foreach (['general_cleaning_tasks', 'sanitation_facility_tasks'] as $name) {
            $schema->create($name, function ($table) {
                $table->id();
                $table->date('date');
                $table->integer('unit_id');
                $table->string('status');
            });
        }
        $this->database->getConnection()->table('units')->insert([['id' => 1, 'name' => 'النظافة العامة'], ['id' => 2, 'name' => 'المنشآت الصحية']]);
        $this->database->getConnection()->table('unit_goals')->insert(['id' => 1, 'unit_id' => 1, 'date' => '2026-11-15', 'target_tasks' => 20]);
        Carbon::setTestNow('2026-11-15 12:00:00');
        $this->transfer = new PerformanceTransfer;
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Facade::clearResolvedInstances();
        Container::setInstance($this->previousContainer);
        Facade::setFacadeApplication($this->previousContainer);
        if ($this->previousResolver) {
            Model::setConnectionResolver($this->previousResolver);
        } else {
            Model::unsetConnectionResolver();
        }
        parent::tearDown();
    }

    private function rows(string $kind, array $overrides = []): array
    {
        $data = array_fill_keys(array_keys($this->transfer::columns($kind)), '');
        $data = array_replace($data, [
            'date' => '15/11/2026', 'unit' => 'النظافة العامة', 'working_hours' => '8', 'notes' => 'نص عربي كامل',
        ], $kind === 'resources' ? ['total_supplies_and_tools_score' => '51.8'] : ['completed_tasks' => '10', 'quality_rating' => '80'], $overrides);

        return [1 => array_values($this->transfer::columns($kind)), 2 => array_values($data)];
    }

    public function test_resource_export_and_template_match_table_order_and_round_trip(): void
    {
        $this->assertSame(['التاريخ', 'الوحدة', 'ساعات العمل', 'إجمالي المستلزمات والمعدات (نقاط)', 'الكفاءة (مهمة/ساعة)', 'كفء؟', 'ملاحظات'], array_values($this->transfer::columns('resources')));
        $records = $this->transfer->validateRows('resources', $this->rows('resources', ['working_hours' => '78']));
        $this->transfer->persist('resources', $records, false);
        $export = $this->transfer->exportRows('resources', Request::create('/'));
        $this->assertSame('15/11/2026', $export[0][0]);
        $this->assertSame('النظافة العامة', $export[0][1]);
        $this->assertSame('نعم', $export[0][5]);
        $workbook = new SurveyWorkbook;
        $path = $workbook->write([array_values($this->transfer::columns('resources')), ...$export]);
        try {
            $import = $this->transfer->validateRows('resources', $workbook->read($path, 7), true);
            $this->assertEquals(78, $import[0]['working_hours']);
            $this->assertEquals(51.8, $import[0]['total_supplies_and_tools_score']);
        } finally {
            unlink($path);
        }
    }

    public function test_results_import_keeps_manual_inputs_resolves_goal_and_recalculates_metrics(): void
    {
        $records = $this->transfer->validateRows('results', $this->rows('results'));
        $this->transfer->persist('results', $records, false);
        $result = ActualResult::first();
        $this->assertEquals(10, $result->completed_tasks);
        $this->assertEquals(8, $result->working_hours);
        $this->assertEquals(1, $result->unit_goal_id);
        $this->assertEquals(50, $result->effectiveness);
        $this->assertEquals(62.5, $result->efficiency);
        $this->assertEquals(90, $result->relevance);
        $this->assertEquals(64.17, $result->overall_performance_score);
        $this->assertCount(1, $this->transfer->exportRows('results', Request::create('/')));
    }

    public function test_existing_rows_require_explicit_update_and_update_does_not_duplicate(): void
    {
        $this->transfer->persist('resources', $this->transfer->validateRows('resources', $this->rows('resources')), false);
        try {
            $this->transfer->validateRows('resources', $this->rows('resources'));
            $this->fail('An existing record must require explicit update.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('موجود مسبقاً', $exception->errors()['file'][0]);
        }
        $records = $this->transfer->validateRows('resources', $this->rows('resources', ['notes' => 'تم التعديل']), true);
        $this->transfer->persist('resources', $records, true);
        $this->assertSame(1, ResourceTracking::count());
        $this->assertSame('تم التعديل', ResourceTracking::first()->notes);
    }

    public function test_invalid_later_row_and_missing_goal_prevent_any_import(): void
    {
        $rows = $this->rows('results');
        $rows[7] = $rows[2];
        $rows[7][1] = 'وحدة غير موجودة';
        try {
            $this->transfer->validateRows('results', $rows);
            $this->fail('The entire file should be rejected.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('الصف 7', $exception->errors()['file'][0]);
            $this->assertSame(0, ActualResult::count());
        }
        $this->expectException(ValidationException::class);
        $this->transfer->validateRows('results', $this->rows('results', ['unit' => 'المنشآت الصحية']));
    }

    public function test_database_error_rolls_back_the_whole_file(): void
    {
        $records = $this->transfer->validateRows('resources', $this->rows('resources'));
        try {
            $this->transfer->persist('resources', [$records[0], $records[0]], false);
            $this->fail('The unique constraint should fail.');
        } catch (QueryException $exception) {
            $this->assertSame(0, ResourceTracking::count());
        }
    }

    public function test_export_keeps_filters_and_includes_all_pages(): void
    {
        for ($day = 1; $day <= 20; $day++) {
            ResourceTracking::create(['date' => '2026-11-'.sprintf('%02d', $day), 'unit_id' => 1, 'working_hours' => 8, 'total_supplies_and_tools_score' => 1, 'notes' => 'match']);
        }
        ResourceTracking::create(['date' => '2026-11-15', 'unit_id' => 2, 'working_hours' => 8, 'total_supplies_and_tools_score' => 1, 'notes' => 'match']);
        $request = Request::create('/', 'GET', ['unit_id' => 1, 'from_date' => '2026-11-05', 'to_date' => '2026-11-20', 'search' => 'match', 'page' => 2]);
        $rows = $this->transfer->exportRows('resources', $request);
        $this->assertCount(16, $rows);
        $this->assertSame(['النظافة العامة'], array_values(array_unique(array_column($rows, 1))));
    }

    public function test_chart_export_follows_axis_order_and_only_its_current_comparison(): void
    {
        $this->transfer->persist('gilbert', $this->transfer->validateRows('gilbert', $this->rows('gilbert')), false);
        $this->assertSame(['النتائج (%)', 'الفعالية (%)', 'الأهداف (%)', 'الملاءمة (%)', 'الموارد (%)', 'الكفاءة (%)'], array_slice(array_values($this->transfer::columns('gilbert')), 2, 6));
        $rows = $this->transfer->exportRows('gilbert', Request::create('/'));
        $this->assertCount(1, $rows);
        $this->assertEquals([10, 50, 40, 90, 100, 62.5], array_slice($rows[0], 2, 6));
        $chart = (new GilbertTriangleController)->getChartData(Request::create('/'))->getData(true);
        $this->assertEquals(array_slice($rows[0], 2, 6), $chart['datasets'][0]['data']);
    }

    public function test_native_excel_dates_and_invalid_calendar_dates(): void
    {
        $this->assertSame('2026-11-15', $this->transfer->normalizeDate('46341'));
        $this->expectException(ValidationException::class);
        $this->transfer->validateRows('resources', $this->rows('resources', ['date' => '31/02/2026']));
    }

    public function test_wrong_template_and_duplicate_rows_are_rejected(): void
    {
        $rows = $this->rows('resources');
        $rows[9] = $rows[2];
        try {
            $this->transfer->validateRows('resources', $rows);
            $this->fail('Duplicate rows must be rejected.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('الصف 9', $exception->errors()['file'][0]);
        }
        $this->expectException(ValidationException::class);
        $this->transfer->validateRows('results', $rows);
    }

    public function test_transfer_views_compile_to_valid_php_without_optional_app_providers(): void
    {
        $compiler = new \Illuminate\View\Compilers\BladeCompiler(new \Illuminate\Filesystem\Filesystem, sys_get_temp_dir());
        foreach (['transfers/buttons', 'transfers/import', 'transfers/pdf', 'resource-trackings/index', 'actual-results/index', 'charts/gilbert-triangle'] as $view) {
            $compiled = $compiler->compileString(file_get_contents(__DIR__.'/../../resources/views/'.$view.'.blade.php'));
            $this->assertNotEmpty(token_get_all($compiled, TOKEN_PARSE));
        }
    }
}
