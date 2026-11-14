<?php

namespace Tests\Unit;

use App\Http\Controllers\GeneralCleaningTaskController;
use App\Http\Controllers\SanitationFacilityTaskController;
use App\Models\GeneralCleaningTask;
use App\Models\SanitationFacilityTask;
use App\Models\TaskImageReport;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

// Standalone SQLite tests also run when optional application providers are unavailable.
class TaskImagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $database = new Manager;
        $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:', 'foreign_key_constraints' => false]);
        $database->setEventDispatcher(new Dispatcher($database->getContainer()));
        $database->setAsGlobal();
        $database->bootEloquent();
        Facade::setFacadeApplication($database->getContainer());
        $database->getContainer()->instance('db.schema', $database->schema());
        Auth::swap(new class {
            public function check() { return false; }
        });
        Log::swap(new NullLogger);
        foreach ([
            '2025_05_31_120423_create_general_cleaning_tasks_table.php',
            '2025_05_31_131643_create_sanitation_facility_tasks_table.php',
            '2025_06_14_152541_create_monthly_general_cleaning_summary_table.php',
            '2025_06_14_152554_create_monthly_sanitation_summary_table.php',
        ] as $file) {
            (require __DIR__.'/../../database/migrations/'.$file)->up();
        }
        require_once __DIR__.'/../../database/migrations/2025_06_15_225454_create_task_image_reports_table.php';
        (new \CreateTaskImageReportsTable)->up();
    }

    protected function tearDown(): void
    {
        Model::clearBootedModels();
        Facade::clearResolvedInstances();
        parent::tearDown();
    }

    public function test_both_task_types_save_sync_preserve_and_clear_their_photos(): void
    {
        foreach ([GeneralCleaningTask::class => 'cleaning', SanitationFacilityTask::class => 'sanitation'] as $model => $unit) {
            $task = $model::create([
                'date' => '2026-11-11', 'shift' => 'صباحي', 'status' => 'قيد التنفيذ',
                'task_type' => 'إدامة', 'location' => 'قاعة', 'facility_name' => 'صحية', 'details' => '',
                'before_images' => ['before.heic'], 'after_images' => ['after.avif'],
            ]);
            $task->refresh();
            self::assertSame(['before.heic'], $task->before_images);
            self::assertSame(['after.avif'], $task->after_images);
            $report = TaskImageReport::where('unit_type', $unit)->where('task_id', $task->id)->firstOrFail();
            self::assertSame(1, $report->before_images_count);
            $task->update(['notes' => 'updated']);
            self::assertSame(['before.heic'], $report->fresh()->before_images);
            $task->update(['before_images' => ['replacement.tiff']]);
            self::assertSame(['replacement.tiff'], $task->fresh()->before_images);
            self::assertSame(['after.avif'], $report->fresh()->after_images);
            $task->update(['before_images' => [], 'after_images' => []]);
            self::assertSame([], $report->fresh()->before_images);
            self::assertSame([], $report->fresh()->after_images);
        }
        self::assertSame(2, TaskImageReport::count());
    }

    public function test_cleaning_images_ignore_empty_legacy_paths_and_accept_empty_retained_fields(): void
    {
        $task = new GeneralCleaningTask;
        $paths = [null, '', ' ', '/storage/', 'public/', 'before.jpg'];
        $task->setRawAttributes(['before_images' => json_encode($paths), 'after_images' => json_encode($paths)]);
        $task->setRelation('imageReport', null);
        self::assertSame(['before.jpg'], $task->before_images);
        self::assertSame(['before.jpg'], $task->after_images);

        $report = new TaskImageReport(['before_images' => $paths, 'after_images' => $paths]);
        $task->setRelation('imageReport', $report);
        self::assertSame(['before.jpg'], $task->before_images);
        self::assertSame(['before.jpg'], $task->after_images);

        $controller = new GeneralCleaningTaskController;
        $rules = (new \ReflectionMethod($controller, 'rules'))->invoke($controller);
        $rules = array_intersect_key($rules, array_flip([
            'existing_before_images', 'existing_before_images.*',
            'existing_after_images', 'existing_after_images.*',
        ]));
        $validator = new Factory(new Translator(new ArrayLoader, 'en'));
        self::assertTrue($validator->make([
            'existing_before_images' => [null, 'before.jpg'],
            'existing_after_images' => [null],
        ], $rules)->passes());
        self::assertFalse($validator->make(['existing_before_images' => [['invalid']]], $rules)->passes());
    }

    public function test_empty_report_images_do_not_hide_saved_task_images(): void
    {
        $task = new GeneralCleaningTask;
        $task->setRawAttributes([
            'before_images' => json_encode(['saved-before.jpg']),
            'after_images' => json_encode(['saved-after.jpg']),
        ]);
        $report = new TaskImageReport(['before_images' => [null, ''], 'after_images' => []]);
        $task->setRelation('imageReport', $report);
        self::assertSame(['saved-before.jpg'], $task->before_images);
        self::assertSame(['saved-after.jpg'], $task->after_images);

        $report->before_images = ['report-before.jpg'];
        self::assertSame(['report-before.jpg'], $task->before_images);
    }

    public function test_upload_rules_accept_image_mime_families_and_reject_disguised_files(): void
    {
        $validator = new Factory(new Translator(new ArrayLoader, 'en'));
        foreach ([GeneralCleaningTaskController::class, SanitationFacilityTaskController::class] as $controller) {
            $reflection = new \ReflectionClass($controller);
            $rules = $reflection->getMethod('rules')->invoke($reflection->newInstanceWithoutConstructor());
            $imageRules = array_intersect_key($rules, array_flip(['before_images', 'before_images.*', 'after_images', 'after_images.*']));
            foreach (['jpeg', 'png', 'webp', 'avif', 'heic', 'heif', 'tiff', 'bmp', 'svg+xml'] as $mime) {
                $file = UploadedFile::fake()->create('photo.bin', 10, 'image/'.$mime);
                self::assertTrue($validator->make(['before_images' => [$file], 'after_images' => [$file]], $imageRules)->passes(), $controller.' '.$mime);
            }
            $path = tempnam(sys_get_temp_dir(), 'task-image');
            try {
                file_put_contents($path, 'This is plain text, not a photo.');
                $fakeImage = new UploadedFile($path, 'photo.jpg', 'image/jpeg', null, true);
                self::assertFalse($validator->make(['before_images' => [$fakeImage]], $imageRules)->passes());
            } finally {
                unlink($path);
            }
            self::assertFalse($validator->make(['before_images' => 'not-an-array'], $imageRules)->passes());
        }
    }

    public function test_related_blade_templates_compile_to_valid_php(): void
    {
        $compiler = new \Illuminate\View\Compilers\BladeCompiler(new \Illuminate\Filesystem\Filesystem, sys_get_temp_dir());
        foreach ([
            'general_cleaning_tasks/create', 'general_cleaning_tasks/edit', 'general_cleaning_tasks/index',
            'sanitation_facility_tasks/create', 'sanitation_facility_tasks/edit', 'sanitation_facility_tasks/index',
            'monthly-cleaning-report/edit', 'photo_reports/create', 'photo_reports/edit',
            'partials/task-image-upload', 'partials/task-image-links',
        ] as $view) {
            $compiled = $compiler->compileString(file_get_contents(__DIR__.'/../../resources/views/'.$view.'.blade.php'));
            self::assertNotEmpty(token_get_all($compiled, TOKEN_PARSE), $view);
        }
    }
}
