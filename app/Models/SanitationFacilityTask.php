<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Unit;
use App\Models\UnitGoal;
use App\Models\Employee;
use App\Models\EmployeeTask;
use App\Models\TaskImageReport;
use App\Models\ActualResult;
use App\Models\MonthlySanitationSummary;

class SanitationFacilityTask extends Model
{
    // الأعمدة القابلة للتعبئة الجماعية
    protected $fillable = [
        'unit_id',
        'date',
        'shift',
        'task_type',
        'facility_name',
        'details',
        'status',
        'notes',
        'related_goal_id',
        'progress',
        'result_value',
        'resources_used',
        'verification_status',
        'seats_count',
        'mirrors_count',
        'mixers_count',
        'doors_count',
        'sinks_count',
        'toilets_count',
        'working_hours',
        'created_by',
        'updated_by',
        // ✅ إضافة هذه الحقول للسماح بتعبئة بيانات الصور مؤقتًا قبل معالجتها
        'before_images',
        'after_images',
    ];

    // تحويل أنواع البيانات عند القراءة والكتابة
    protected $casts = [
        'resources_used' => 'array',
        'date' => 'date',
        // ✅ تحويل `before_images` و `after_images` إلى مصفوفات
        'before_images' => 'array',
        'after_images' => 'array',
    ];

    /**
     * تعريف العلاقة: المهمة لها العديد من مهام الموظفين (عبر جدول وسيط EmployeeTask).
     */
    public function employeeTasks(): HasMany
    {
        return $this->hasMany(EmployeeTask::class, 'sanitation_facility_task_id');
    }

    /**
     * تعريف العلاقة: المهمة تنتمي إلى وحدة معينة.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * تعريف العلاقة: المهمة مرتبطة بهدف وحدة معين.
     */
    public function relatedGoal(): BelongsTo
    {
        return $this->belongsTo(UnitGoal::class, 'related_goal_id');
    }

    /**
     * تعريف العلاقة: المهمة تم إنشاؤها بواسطة مستخدم.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * تعريف العلاقة: المهمة تم تعديلها بواسطة مستخدم.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * علاقة One-to-One مع TaskImageReport
     * ستجلب تقرير الصورة المرتبط بهذه المهمة (من نوع 'sanitation').
     */
    public function imageReport(): HasOne
    {
        return $this->hasOne(TaskImageReport::class, 'task_id', 'id')
                    ->where('unit_type', 'sanitation');
    }

    /**
     * يقوم بإرجاع عناوين URL للصور "قبل" المهمة من تقرير الصور المرتبط (Eager Loaded).
     * يستخدم هذا كـ accessor لـ $task->before_images
     *
     * @return array
     */
    public function getBeforeImagesAttribute(): array
    {
        return optional($this->imageReport)->before_images ?? [];
    }

    /**
     * يقوم بإرجاع عناوين URL للصور "بعد" المهمة من تقرير الصور المرتبط (Eager Loaded).
     * يستخدم هذا كـ accessor لـ $task->after_images
     *
     * @return array
     */
    public function getAfterImagesAttribute(): array
    {
        return optional($this->imageReport)->after_images ?? [];
    }

    /**
     * الأحداث التي يتم تشغيلها عند بدء تشغيل الموديل.
     */
    protected static function booted()
    {
        static::creating(function ($task) {
            $task->unit_id = $task->unit_id ?? 2;
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
            self::recalculateMonthlySummary($task);
            if ($task->status === 'مكتمل' && $task->unit_id && $task->date) {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
            // ✅ استدعاء إنشاء أو تحديث التقرير المصور بعد الإنشاء
            self::handleTaskImageReport($task);
        });

        static::updated(function ($task) {
            self::recalculateMonthlySummary($task);
            if ($task->isDirty('status') && $task->status === 'مكتمل') {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
            // ✅ استدعاء تحديث التقرير المصور عند التعديل
            self::handleTaskImageReport($task);
        });

        static::deleted(function ($task) {
            self::recalculateMonthlySummary($task);
            self::cleanupTaskImages($task);
            if ($task->unit_id && $task->date) {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });
    }

    /**
     * يعيد حساب الملخصات الشهرية لوحدة المنشآت الصحية.
     *
     * @param SanitationFacilityTask $task
     * @return void
     */
    protected static function recalculateMonthlySummary(self $task): void
    {
        if (!$task->unit_id || !$task->date || !$task->facility_name) {
            Log::warning("Skipping Monthly Sanitation Summary recalculation due to missing data for task ID: {$task->id}");
            return;
        }

        $facilityName = $task->facility_name;
        $taskType = $task->task_type;
        $date = Carbon::parse($task->date);
        $month = $date->format('Y-m');
        $unitId = $task->unit_id;

        $summaryId = md5("{$month}-{$facilityName}-{$taskType}-{$unitId}");

        $totals = self::query()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->where('unit_id', $unitId)
            ->where('facility_name', $facilityName)
            ->where('task_type', $taskType)
            ->selectRaw('
                COALESCE(SUM(seats_count), 0) as total_seats,
                COALESCE(SUM(mirrors_count), 0) as total_mirrors,
                COALESCE(SUM(mixers_count), 0) as total_mixers,
                COALESCE(SUM(doors_count), 0) as total_doors,
                COALESCE(SUM(sinks_count), 0) as total_sinks,
                COALESCE(SUM(toilets_count), 0) as total_toilets,
                COUNT(*) as total_tasks_count_for_summary
            ')
            ->first();

        MonthlySanitationSummary::updateOrCreate(
            [
                'id' => $summaryId,
                'month' => $month,
                'facility_name' => $facilityName,
                'task_type' => $taskType,
                'unit_id' => $unitId,
            ],
            [
                'total_seats' => $totals->total_seats,
                'total_mirrors' => $totals->total_mirrors,
                'total_mixers' => $totals->total_mixers,
                'total_doors' => $totals->total_doors,
                'total_sinks' => $totals->total_sinks,
                'total_toilets' => $totals->total_toilets,
                'total_tasks' => $totals->total_tasks_count_for_summary,
            ]
        );
        Log::info("Monthly Sanitation Summary recalculated for {$facilityName} - {$taskType} in {$month}.");
    }

    /**
     * يقوم بإنشاء أو تحديث تقرير الصور المرتبط بمهام المنشآت الصحية.
     *
     * @param SanitationFacilityTask $task
     * @return void
     */
    private static function handleTaskImageReport(self $task): void
    {
        // افترض أن 'before_images' و 'after_images' يتم تمريرها إلى النموذج
        // كجزء من البيانات عند الإنشاء أو التحديث (مثل Request::all())
        $beforeImages = $task->getAttribute('before_images');
        $afterImages = $task->getAttribute('after_images');

        // إذا لم تكن هناك صور "قبل" أو "بعد" وتم إرسالها (أو كانت فارغة)،
        // تحقق مما إذا كان هناك تقرير موجود مسبقًا بدون صور واحذفه.
        if (empty($beforeImages) && empty($afterImages)) {
            $existingReport = TaskImageReport::where('task_id', $task->id)
                                             ->where('unit_type', 'sanitation')
                                             ->first();
            if ($existingReport && empty($existingReport->before_images) && empty($existingReport->after_images)) {
                $existingReport->delete();
                Log::info("Deleted empty Task Image Report for SanitationFacilityTask ID: {$task->id}");
            }
            return; // لا يوجد صور للتعامل معها، لذا ننهي الدالة
        }

        // إعداد البيانات لإنشاء/تحديث TaskImageReport
        $reportData = [
            'task_id' => $task->id,
            'unit_type' => 'sanitation', // تحديد نوع الوحدة كـ 'sanitation'
            'date' => $task->date,
            'location' => $task->facility_name, // استخدام facility_name كـ location في التقرير
            'task_type' => $task->task_type,
            'status' => $task->status,
            'notes' => $task->notes,
            'before_images' => $beforeImages,
            'after_images' => $afterImages,
        ];

        // إنشاء أو تحديث TaskImageReport
        TaskImageReport::updateOrCreate(
            [
                'task_id' => $task->id,
                'unit_type' => 'sanitation',
            ],
            $reportData
        );
        Log::info("Task Image Report created/updated for SanitationFacilityTask ID: {$task->id}");
    }

    /**
     * يقوم بتنظيف الصور المرتبطة بمهام المنشآت الصحية عند حذفها.
     *
     * @param SanitationFacilityTask $task
     * @return void
     */
    protected static function cleanupTaskImages(self $task): void
    {
        $report = TaskImageReport::where('task_id', $task->id)
                                 ->where('unit_type', 'sanitation')
                                 ->first();

        if ($report) {
            $report->deleteRelatedImages(); // دالة في TaskImageReport لحذف ملفات الصور من التخزين
            $report->delete(); // حذف سجل التقرير من قاعدة البيانات
            Log::info("Cleaned up image report for SanitationFacilityTask ID: {$task->id}");
        }
    }
}