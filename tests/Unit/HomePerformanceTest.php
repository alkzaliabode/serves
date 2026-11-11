<?php

namespace Tests\Unit;

use App\Http\Controllers\HomeController;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class HomePerformanceTest extends TestCase
{
    private array $viewData = [];
    private ?Container $previousContainer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->previousContainer = Container::getInstance();
        $database = new Manager;
        $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $database->setAsGlobal();
        $database->bootEloquent();
        $container = $database->getContainer();
        Container::setInstance($container);
        $container['config']->set('app.timezone', 'Asia/Baghdad');
        $factory = $this->createMock(Factory::class);
        $factory->method('make')->willReturnCallback(function ($name, $data) {
            self::assertSame('home', $name);
            $this->viewData = $data;
            return $this->createMock(View::class);
        });
        $container->instance(Factory::class, $factory);
        $database->schema()->create('actual_results', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->float('overall_performance_score')->nullable();
            $table->float('quality_rating')->nullable();
        });
        $database->schema()->create('units', fn (Blueprint $table) => $table->id());
        // Baghdad is already December 1 while UTC is still November 30.
        Carbon::setTestNow(Carbon::parse('2026-11-30 21:30:00', 'UTC'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Container::setInstance($this->previousContainer);
        parent::tearDown();
    }

    public function test_home_uses_yesterdays_scores_and_previous_day_across_month_boundary(): void
    {
        Manager::table('actual_results')->insert([
            ['date' => '2026-11-30', 'overall_performance_score' => 80, 'quality_rating' => 4],
            ['date' => '2026-11-30', 'overall_performance_score' => 100, 'quality_rating' => 5],
            ['date' => '2026-11-29', 'overall_performance_score' => 70, 'quality_rating' => 3.5],
            ['date' => '2026-12-01', 'overall_performance_score' => 10, 'quality_rating' => 0.5],
        ]);
        (new HomeController)->index();
        self::assertSame('2026-11-30', $this->viewData['performanceDate']);
        self::assertSame('2026-11-29', $this->viewData['comparisonDate']);
        self::assertEquals(90, $this->viewData['yesterdayOverallPerformance']);
        self::assertEquals(70, $this->viewData['dayBeforeYesterdayOverallPerformance']);
        self::assertEquals(0.5, $this->viewData['todayQualityRating']);
    }

    public function test_missing_performance_is_distinct_from_recorded_zero(): void
    {
        (new HomeController)->index();
        self::assertNull($this->viewData['yesterdayOverallPerformance']);
        self::assertNull($this->viewData['dayBeforeYesterdayOverallPerformance']);
        Manager::table('actual_results')->insert(['date' => '2026-11-30', 'overall_performance_score' => 0]);
        (new HomeController)->index();
        self::assertEquals(0, $this->viewData['yesterdayOverallPerformance']);
        self::assertNotNull($this->viewData['yesterdayOverallPerformance']);
    }

    public function test_home_template_compiles_and_quick_reports_are_removed(): void
    {
        $source = file_get_contents(__DIR__.'/../../resources/views/home.blade.php');
        self::assertStringNotContainsString('data-animate-section="quick-reports"', $source);
        $compiler = new \Illuminate\View\Compilers\BladeCompiler(new \Illuminate\Filesystem\Filesystem, sys_get_temp_dir());
        self::assertNotEmpty(token_get_all($compiler->compileString($source), TOKEN_PARSE));
    }
}
