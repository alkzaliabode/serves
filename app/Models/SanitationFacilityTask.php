<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // للحفاظ على العلاقة إذا كنت تستخدمها
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage; // لاستخدام Storage facade
use Illuminate\Support\Facades\Auth;     // لاستخدام Auth facade

// استيراد جميع الموديلات المستخدمة في هذا الموديل
use App\Models\User;
use App\Models\Unit; // تم إضافته: لأنك تستخدم unit()
use App\Models\UnitGoal;
use App\Models\Employee; // تم إضافته: للعلاقة employees()
use App\Models\EmployeeTask; // تم إضافته: للعلاقة employeeTasks()
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
        'facility_name', // اسم المرفق (مثال: مسجد، مستشفى، دورة مياه)
        'details',       // تفاصيل إضافية للمهمة
        'status',
        'notes',
        'related_goal_id',
        'progress',      // نسبة التقدم
        'result_value',  // قيمة النتيجة (إذا كانت رقمية)
        'resources_used', // الموارد الأخرى المستخدمة (JSON)
        'verification_status', // حالة التحقق
        'before_images', // مسارات صور قبل التنفيذ (JSON)
        'after_images',  // مسارات صور بعد التنفيذ (JSON)
        'seats_count',   // عدد المقاعد
        'sinks_count',   // عدد الأحواض
        'mixers_count',  // عدد الخلاطات
        'mirrors_count', // عدد المرايا
        'doors_count',   // عدد الأبواب
        'toilets_count', // عدد المراحيض
        'working_hours', // ساعات العمل لهذه المهمة
        // 'created_by' و 'updated_by' يتم تعيينهما عبر أحداث الموديل تلقائياً لضمان الأمان
    ];

    // تحويل أنواع البيانات عند القراءة والكتابة
    protected $casts = [
        'resources_used' => 'array',
        'before_images' => 'array',
        'after_images' => 'array',
        'date' => 'date', // تحويل التاريخ إلى كائن Carbon
    ];

    /**
     * تعريف العلاقة: المهمة لها العديد من مهام الموظفين (عبر جدول وسيط EmployeeTask).
     * هذا هو الأكثر شيوعاً عند تخزين بيانات إضافية (مثل التقييم) على جدول الربط.
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
     * الأحداث التي يتم تشغيلها عند بدء تشغيل الموديل.
     * هنا نقوم بتعيين created_by و updated_by تلقائياً، ومعالجة الملخصات والصور.
     */
    protected static function booted()
    {
        // عند إنشاء مهمة جديدة وقبل حفظها في قاعدة البيانات
        static::creating(function ($task) {
            // تعيين unit_id افتراضي (مثال: 2 لوحدة المنشآت الصحية)
            $task->unit_id = $task->unit_id ?? 2;
            // تعيين created_by بمعرف المستخدم الحالي إذا كان هناك مستخدم مسجل الدخول
            if (Auth::check()) {
                $task->created_by = Auth::id();
            }
        });

        // عند حفظ المهمة (سواء كانت إنشاء لأول مرة أو تحديث موجود)
        static::saving(function ($task) {
            // تعيين updated_by بمعرف المستخدم الحالي إذا كان هناك مستخدم مسجل الدخول
            if (Auth::check()) {
                $task->updated_by = Auth::id();
            }
        });

        // بعد إنشاء مهمة جديدة
        static::created(function ($task) {
            self::recalculateMonthlySummary($task);
            self::handleTaskImageReport($task);
            // إعادة حساب النتائج الفعلية إذا كانت المهمة مكتملة ولها unit_id و date
            if ($task->status === 'مكتمل' && $task->unit_id && $task->date) {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });

        // بعد تحديث مهمة موجودة
        static::updated(function ($task) {
            self::recalculateMonthlySummary($task);
            self::handleTaskImageReport($task);
            // إعادة حساب النتائج الفعلية إذا تغيرت الحالة إلى 'مكتمل'
            if ($task->isDirty('status') && $task->status === 'مكتمل') {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });

        // بعد حذف مهمة
        static::deleted(function ($task) {
            self::recalculateMonthlySummary($task);
            self::cleanupTaskImages($task); // حذف الصور المرتبطة
            // إعادة حساب النتائج الفعلية بعد حذف المهمة
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
            return; // لا يمكن إعادة الحساب بدون هذه البيانات الأساسية
        }

        $facilityName = $task->facility_name;
        $taskType = $task->task_type;
        $date = Carbon::parse($task->date);
        $month = $date->format('Y-m');
        $unitId = $task->unit_id; // استخدم unit_id أيضاً في الملخص

        // إنشاء معرف فريد للملخص الشهري
        // تم إضافة unitId إلى الـ MD5 لضمان فرادة الملخص لكل وحدة ومنشأة ونوع مهمة
        $summaryId = md5("{$month}-{$facilityName}-{$taskType}-{$unitId}");

        // حساب الإجماليات من قاعدة البيانات للمهام المطابقة
        $totals = self::query()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->where('unit_id', $unitId) // تصفية بالوحدة أيضاً
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

        // تحديث أو إنشاء سجل الملخص الشهري
        MonthlySanitationSummary::updateOrCreate(
            [
                // يجب أن تتطابق هذه المفاتيح مع المفتاح الأساسي أو الفريد في جدول الملخص
                'id' => $summaryId, // إذا كان الـ ID هو مزيج من هذه الحقول
                'month' => $month,
                'facility_name' => $facilityName, // ✅ تم التعديل هنا: استخدام 'facility_name' بدلاً من 'id_facility_name'
                'task_type' => $taskType,
                'unit_id' => $unitId, // تأكد أن هذا العمود موجود في جدول الملخص
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
    }

    /**
     * يتعامل مع إنشاء/تحديث تقارير صور المهام لوحدة المنشآت الصحية.
     *
     * @param SanitationFacilityTask $task
     * @return void
     */
    protected static function handleTaskImageReport(self $task): void
    {
        // لا نحتاج لإنشاء تقرير إذا لم يكن هناك صور قبل أو بعد
        if (empty($task->before_images) && empty($task->after_images)) {
            return;
        }

        $reportData = [
            'task_id' => $task->id,
            'unit_type' => 'sanitation', // تم تغيير هذا ليكون أكثر وضوحاً لوحدة المنشآت الصحية
            'date' => $task->date,
            'location' => $task->facility_name, // استخدام facility_name كـ location
            'task_type' => $task->task_type,
            'status' => $task->status,
            'notes' => $task->notes,
        ];

        // إضافة الصور فقط إذا كانت موجودة
        if (!empty($task->before_images)) {
            $reportData['before_images'] = $task->before_images;
        }
        if (!empty($task->after_images)) {
            $reportData['after_images'] = $task->after_images;
        }

        TaskImageReport::updateOrCreate(
            [
                'task_id' => $task->id,
                'unit_type' => 'sanitation', // نوع الوحدة
            ],
            $reportData
        );
    }

    /**
     * يقوم بتنظيف الصور المرتبطة بمهام المنشآت الصحية عند حذفها.
     *
     * @param SanitationFacilityTask $task
     * @return void
     */
    protected static function cleanupTaskImages(self $task): void
    {
        // استخدام $task->id بدلاً من $this->id في الدوال static
        $report = TaskImageReport::where('task_id', $task->id)
                                 ->where('unit_type', 'sanitation')
                                 ->first();

        if ($report) {
            // تأكد أن هذه الدالة موجودة في TaskImageReport Model وتقوم بحذف الملفات فعلياً
            $report->deleteRelatedImages();
            $report->delete(); // حذف سجل التقرير من قاعدة البيانات
        }
    }

    /**
     * يقوم بإرجاع عناوين URL للصور "قبل" المهمة من تقرير الصور.
     *
     * @return array
     */
    public function getBeforeImagesUrlsAttribute(): array
    {
        $report = TaskImageReport::where('task_id', $this->id)
                                 ->where('unit_type', 'sanitation')
                                 ->first();

        // استخدام optional() لتجنب الأخطاء إذا لم يكن التقرير موجوداً
        return optional($report)->getOriginalUrlsForTable($report->before_images) ?? [];
    }

    /**
     * يقوم بإرجاع عناوين URL للصور "بعد" المهمة من تقرير الصور.
     *
     * @return array
     */
    public function getAfterImagesUrlsAttribute(): array
    {
        $report = TaskImageReport::where('task_id', $this->id)
                                 ->where('unit_type', 'sanitation')
                                 ->first();

        return optional($report)->getOriginalUrlsForTable($report->after_images) ?? [];
    }
}
