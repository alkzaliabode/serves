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
        // ✅ يجب أن تكون قبل وبعد الصور قابلة للتعبئة الجماعية أيضًا إذا كنت تقوم بتخزينها مباشرة
        // ومع ذلك، بما أنك تستخدم TaskImageReport، فلن تحتاجها هنا إذا كانت البيانات لا تُخزن مباشرة
        // في هذا النموذج، بل عبر علاقة
    ];

    // تحويل أنواع البيانات عند القراءة والكتابة
    protected $casts = [
        'resources_used' => 'array',
        'date' => 'date',
        // ✅ إضافة هذه الأسطر لتحويل `before_images` و `after_images` إلى مصفوفات
        // هذا أمر بالغ الأهمية لأنك تحاول عمل foreach عليها في Blade
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
     *
     * @return array
     */
    public function getBeforeImagesAttribute(): array // ✅ تم تغيير getNameUrlsAttribute إلى getBeforeImagesAttribute
    {
        // إذا كنت تخزن المسارات مباشرة في هذا الموديل (SanitationFacilityTask)
        // فستحتاج إلى التأكد من أن حقل `before_images` في قاعدة البيانات
        // يخزن كـ JSON وتحويله باستخدام $casts
        // وإلا، إذا كانت الصور تأتي من `TaskImageReport`، فأنت بحاجة إلى جلبها من هناك
        // بناءً على رسالة الخطأ الأصلية، يبدو أن `before_images` يتم الوصول إليها مباشرة على
        // SanitationFacilityTask وليس من خلال علاقة أو accessor مخصص.
        // لذا، إذا كان لديك عمود 'before_images' في جدول sanitation_facility_tasks
        // ولديه مسارات JSON، فإن $casts ستعالجه.
        // ولكن بناءً على الكود الخاص بك، يبدو أنك تستخدم `TaskImageReport` لتخزين الصور.
        //
        // إذا كنت تستخدم `TaskImageReport`، يجب أن تستدعي العلاقة `imageReport`
        // ومن ثم الـ accessor الخاص بـ `TaskImageReport`، كالتالي:
        return optional($this->imageReport)->before_images ?? []; // ✅ تأكد من أن TaskImageReport لديه accessor اسمه `before_images` أو أن هذا هو اسم العمود الذي يتم قراءته.
    }

    /**
     * يقوم بإرجاع عناوين URL للصور "بعد" المهمة من تقرير الصور المرتبط (Eager Loaded).
     *
     * @return array
     */
    public function getAfterImagesAttribute(): array // ✅ تم تغيير getNameUrlsAttribute إلى getAfterImagesAttribute
    {
        return optional($this->imageReport)->after_images ?? []; // ✅ تأكد من أن TaskImageReport لديه accessor اسمه `after_images` أو أن هذا هو اسم العمود الذي يتم قراءته.
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
        });

        static::updated(function ($task) {
            self::recalculateMonthlySummary($task);
            if ($task->isDirty('status') && $task->status === 'مكتمل') {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
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
     * يقوم بتنظيف الصور المرتبطة بمهام المنشآت الصحية عند حذفها.
     */
    protected static function cleanupTaskImages(self $task): void
    {
        $report = TaskImageReport::where('task_id', $task->id)
                                 ->where('unit_type', 'sanitation')
                                 ->first();

        if ($report) {
            $report->deleteRelatedImages();
            $report->delete();
            Log::info("Cleaned up image report for SanitationFacilityTask ID: {$task->id}");
        }
    }
}