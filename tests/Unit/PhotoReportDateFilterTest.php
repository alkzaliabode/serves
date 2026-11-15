<?php

namespace Tests\Unit;

use App\Http\Controllers\ImageReportController;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class PhotoReportDateFilterTest extends TestCase
{
    public function test_date_range_includes_both_boundary_days_and_filters_report_date(): void
    {
        $this->assertSame([2, 3], $this->ids(['from_date' => '2026-11-10', 'to_date' => '2026-11-12']));
    }

    public function test_either_boundary_can_be_used_independently_and_reset_returns_everything(): void
    {
        $this->assertSame([2, 3, 4], $this->ids(['from_date' => '2026-11-10']));
        $this->assertSame([1, 2], $this->ids(['to_date' => '2026-11-10']));
        $this->assertSame([1, 2, 3, 4], $this->ids([]));
    }

    private function ids(array $filters): array
    {
        $previous = Model::getConnectionResolver();
        $database = new Manager;
        $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $database->bootEloquent();
        try {
            $database->getConnection()->getSchemaBuilder()->create('task_image_reports', function ($table) {
                $table->id();
                $table->dateTime('date');
                $table->dateTime('created_at');
            });
            foreach (['2026-11-09 23:59:59', '2026-11-10 00:00:00', '2026-11-12 23:59:59', '2026-11-13 00:00:00'] as $date) {
                $database->getConnection()->table('task_image_reports')->insert(['date' => $date, 'created_at' => '2026-01-01 00:00:00']);
            }
            $method = new \ReflectionMethod(ImageReportController::class, 'filteredReports');
            return $method->invoke(new ImageReportController, $filters)->orderBy('id')->pluck('id')->all();
        } finally {
            if ($previous) Model::setConnectionResolver($previous);
            else Model::unsetConnectionResolver();
        }
    }
}
