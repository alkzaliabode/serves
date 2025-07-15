<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Unit;
use App\Models\UnitGoal;
use App\Models\EmployeeTask;
use App\Models\TaskImageReport;
use App\Models\ActualResult;
use App\Models\MonthlyGeneralCleaningSummary;

class GeneralCleaningTask extends Model
{
    protected $fillable = [
        'date',
        'shift',
        'task_type',
        'location',
        'quantity',
        'status',
        'notes',
        'responsible_persons',
        'related_goal_id',
        'progress',
        'result_value',
        'resources_used',
        'verification_status',
        'unit_id',
        'working_hours',
        'mats_count',
        'pillows_count',
        'fans_count',
        'windows_count',
        'carpets_count',
        'blankets_count',
        'beds_count',
        'beneficiaries_count',
        'filled_trams_count',
        'carpets_laid_count',
        'large_containers_count',
        'small_containers_count',
        'maintenance_details',
        'created_by',
        'updated_by',
        'external_partitions_count',
    ];

    protected $casts = [
        'date' => 'date',
        'resources_used' => 'array',
    ];

    public function employeeTasks(): HasMany
    {
        return $this->hasMany(EmployeeTask::class, 'general_cleaning_task_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function relatedGoal(): BelongsTo
    {
        return $this->belongsTo(UnitGoal::class, 'related_goal_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function imageReport(): HasOne
    {
        return $this->hasOne(TaskImageReport::class, 'task_id', 'id')
                    ->where('unit_type', 'cleaning');
    }

    public function getBeforeImagesUrlsAttribute(): array
    {
        return optional($this->imageReport)->before_images_urls ?? [];
    }

    public function getAfterImagesUrlsAttribute(): array
    {
        return optional($this->imageReport)->after_images_urls ?? [];
    }

    /**
     * Accessor لجمع أسماء الموظفين المنفذين في سلسلة نصية واحدة.
     * يتطلب تحميل العلاقة employeeTasks مسبقاً (eager loading).
     *
     * @return string
     */
    public function getEmployeeNamesAttribute(): string
    {
        // التحقق مما إذا كانت العلاقة محملة لتجنب N+1 query problem
        if ($this->relationLoaded('employeeTasks') && $this->employeeTasks->isNotEmpty()) {
            return $this->employeeTasks->pluck('employee.name')->filter()->implode(', ');
        }
        return '';
    }

    protected static function booted()
    {
        static::creating(function ($task) {
            $task->unit_id = $task->unit_id ?? 1;
            if (Auth::check()) {
                $task->created_by = Auth::id();
            }
        });

        static::saving(function ($task) {
            if (Auth::check()) {
                $task->updated_by = Auth::id();
            }
        });

        static::created(function ($task) {
            self::recalculateSummaries($task);
            if ($task->status === 'مكتمل' && $task->unit_id && $task->date) {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });

        static::updated(function ($task) {
            self::recalculateSummaries($task);
            if ($task->isDirty('status') && $task->status === 'مكتمل') {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });

        static::deleted(function ($task) {
            self::recalculateSummaries($task);
            self::cleanupTaskImages($task);
            if ($task->unit_id && $task->date) {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });
    }

    /**
     * يعيد حساب الملخصات الشهرية بناءً على المهام المحددة.
     *
     * @param GeneralCleaningTask $task
     * @return void
     */
    private static function recalculateSummaries(self $task): void
    {
        if (!$task->unit_id || !$task->date) {
            return;
        }

        $unitId = $task->unit_id;
        $location = $task->location;
        $taskType = $task->task_type;
        $date = Carbon::parse($task->date);
        $month = $date->format('Y-m');

        $summaryId = md5("{$month}-{$location}-{$taskType}-{$unitId}");

        $totals = self::query()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->where('unit_id', $unitId)
            ->where('location', $location)
            ->where('task_type', $taskType)
            ->selectRaw('
                COALESCE(SUM(mats_count), 0) as total_mats,
                COALESCE(SUM(pillows_count), 0) as total_pillows,
                COALESCE(SUM(fans_count), 0) as total_fans,
                COALESCE(SUM(windows_count), 0) as total_windows,
                COALESCE(SUM(carpets_count), 0) as total_carpets,
                COALESCE(SUM(blankets_count), 0) as total_blankets,
                COALESCE(SUM(beds_count), 0) as total_beds,
                COALESCE(SUM(beneficiaries_count), 0) as total_beneficiaries,
                COALESCE(SUM(filled_trams_count), 0) as total_trams,
                COALESCE(SUM(carpets_laid_count), 0) as total_laid_carpets,
                COALESCE(SUM(large_containers_count), 0) as total_large_containers,
                COALESCE(SUM(small_containers_count), 0) as total_small_containers,
                COALESCE(SUM(external_partitions_count), 0) as total_external_partitions,
                COUNT(*) as total_tasks_count_for_summary
            ')
            ->first();

        MonthlyGeneralCleaningSummary::updateOrCreate(
            [
                'id' => $summaryId,
                'month' => $month,
                'location' => $location,
                'task_type' => $taskType,
                'unit_id' => $unitId,
            ],
            [
                'total_mats' => $totals->total_mats,
                'total_pillows' => $totals->total_pillows,
                'total_fans' => $totals->total_fans,
                'total_windows' => $totals->total_windows,
                'total_carpets' => $totals->total_carpets,
                'total_blankets' => $totals->total_blankets,
                'total_beds' => $totals->total_beds,
                'total_beneficiaries' => $totals->total_beneficiaries,
                'total_trams' => $totals->total_trams,
                'total_laid_carpets' => $totals->total_laid_carpets,
                'total_large_containers' => $totals->total_large_containers,
                'total_small_containers' => $totals->total_small_containers,
                'total_tasks' => $totals->total_tasks_count_for_summary,
                'total_external_partitions' => $totals->total_external_partitions,
            ]
        );
    }

    private static function handleTaskImageReport(self $task): void
    {
        $beforeImages = $task->getAttribute('before_images');
        $afterImages = $task->getAttribute('after_images');

        if (empty($beforeImages) && empty($afterImages)) {
            $existingReport = TaskImageReport::where('task_id', $task->id)
                                             ->where('unit_type', 'cleaning')
                                             ->first();
            if ($existingReport && empty($existingReport->before_images) && empty($existingReport->after_images)) {
                 $existingReport->delete();
            }
            return;
        }

        $reportData = [
            'task_id' => $task->id,
            'unit_type' => 'cleaning',
            'date' => $task->date,
            'location' => $task->location,
            'task_type' => $task->task_type,
            'status' => $task->status,
            'notes' => $task->notes,
            'before_images' => $beforeImages,
            'after_images' => $afterImages,
        ];

        TaskImageReport::updateOrCreate(
            [
                'task_id' => $task->id,
                'unit_type' => 'cleaning',
            ],
            $reportData
        );
    }

    private static function cleanupTaskImages(self $task): void
    {
        $report = TaskImageReport::where('task_id', $task->id)
            ->where('unit_type', 'cleaning')
            ->first();

        if ($report) {
            $report->deleteRelatedImages();
            $report->delete();
        }
    }
}
