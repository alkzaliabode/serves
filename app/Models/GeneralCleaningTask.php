<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne; // ✅ تم استيراد HasOne
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

// تأكد من استيراد جميع الموديلات التي تستخدمها في هذا الموديل
use App\Models\User;
use App\Models\Unit;
use App\Models\UnitGoal;
use App\Models\EmployeeTask;
use App\Models\TaskImageReport;
use App\Models\ActualResult;
use App\Models\MonthlyGeneralCleaningSummary;

class GeneralCleaningTask extends Model
{
    // الأعمدة القابلة للتعبئة الجماعية (Mass Assignment)
    protected $fillable = [
        'date',
        'shift',
        'task_type',
        'location',
        'quantity',
        'status',
        'notes',
        'responsible_persons', // قد يكون هذا JSON أو array إذا كان متعدد الأشخاص
        'related_goal_id',
        'progress',
        'result_value',
        'resources_used',
        'verification_status',
        // 'before_images', // ❌ تم حذفها، تُدار الآن عبر TaskImageReport
        // 'after_images',  // ❌ تم حذفها، تُدار الآن عبر TaskImageReport
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
        'created_by', // ✅ إضافة created_by إلى fillable
        'updated_by', // ✅ إضافة updated_by إلى fillable
    ];

    // تحويل أنواع البيانات عند القراءة والكتابة
    protected $casts = [
        'date' => 'date', // لتحويل التاريخ إلى كائن Carbon تلقائياً
        'resources_used' => 'array', // إذا كان هذا العمود يخزن مصفوفة JSON
        // 'before_images' => 'array',  // ❌ تم حذفها، تُدار الآن عبر TaskImageReport
        // 'after_images' => 'array',   // ❌ تم حذفها، تُدار الآن عبر TaskImageReport
    ];

    /**
     * تعريف العلاقة: المهمة لها العديد من مهام الموظفين.
     */
    public function employeeTasks(): HasMany
    {
        return $this->hasMany(EmployeeTask::class, 'general_cleaning_task_id');
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
     * ستجلب تقرير الصورة المرتبط بهذه المهمة (من نوع 'cleaning').
     */
    public function imageReport(): HasOne
    {
        return $this->hasOne(TaskImageReport::class, 'task_id', 'id')
                    ->where('unit_type', 'cleaning'); // تحديد النوع لضمان جلب التقرير الصحيح
    }

    /**
     * يقوم بإرجاع عناوين URL للصور "قبل" المهمة من تقرير الصور المرتبط (Eager Loaded).
     *
     * @return array
     */
    public function getBeforeImagesUrlsAttribute(): array
    {
        // استخدام optional() لتجنب الأخطاء إذا لم يتم تحميل العلاقة (imageReport) أو كانت null
        // والوصول إلى الـ accessor في TaskImageReport الذي يعالج المسارات
        return optional($this->imageReport)->before_images_urls ?? [];
    }

    /**
     * يقوم بإرجاع عناوين URL للصور "بعد" المهمة من تقرير الصور المرتبط (Eager Loaded).
     *
     * @return array
     */
    public function getAfterImagesUrlsAttribute(): array
    {
        // استخدام optional() لتجنب الأخطاء إذا لم يتم تحميل العلاقة (imageReport) أو كانت null
        // والوصول إلى الـ accessor في TaskImageReport الذي يعالج المسارات
        return optional($this->imageReport)->after_images_urls ?? [];
    }

    /**
     * الأحداث التي يتم تشغيلها عند بدء تشغيل الموديل.
     * هنا نقوم بتعيين created_by و updated_by تلقائياً.
     */
    protected static function booted()
    {
        // عند إنشاء مهمة جديدة وقبل حفظها في قاعدة البيانات
        static::creating(function ($task) {
            // تعيين unit_id افتراضي إذا لم يتم تعيينه
            $task->unit_id = $task->unit_id ?? 1;

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
            self::recalculateSummaries($task);
            // 💡 ملاحظة: دالة handleTaskImageReport() يجب أن تستقبل 'before_images' و 'after_images'
            // من الـ Request أو من أي مصدر آخر، ثم تقوم بإنشاء أو تحديث TaskImageReport
            // بناءً على هذه البيانات. هذه البيانات لم تعد جزءًا من $fillable لهذا النموذج.
            // إذا كنت لا تزال تمررها عبر نموذج GeneralCleaningTask عند الإنشاء/التحديث، فستحتاج إلى معالجة ذلك.
            // مثال:
            // if ($task->isDirty('before_images') || $task->isDirty('after_images')) {
            //     self::handleTaskImageReport($task);
            // }
            // إعادة حساب النتائج الفعلية إذا كانت المهمة مكتملة ولها unit_id و date
            if ($task->status === 'مكتمل' && $task->unit_id && $task->date) {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });

        // بعد تحديث مهمة موجودة
        static::updated(function ($task) {
            self::recalculateSummaries($task);
            // نفس الملاحظة أعلاه بخصوص handleTaskImageReport
            // if ($task->isDirty('before_images') || $task->isDirty('after_images')) {
            //     self::handleTaskImageReport($task);
            // }
            // إعادة حساب النتائج الفعلية إذا تغيرت الحالة إلى 'مكتمل'
            if ($task->isDirty('status') && $task->status === 'مكتمل') {
                ActualResult::recalculateForUnitAndDate($task->unit_id, $task->date);
            }
        });

        // بعد حذف مهمة
        static::deleted(function ($task) {
            self::recalculateSummaries($task);
            self::cleanupTaskImages($task); // حذف الصور المرتبطة
            // إعادة حساب النتائج الفعلية بعد حذف المهمة
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
        // لا داعي لإعادة الحساب إذا لم يكن هناك unit_id
        if (!$task->unit_id || !$task->date) {
            return;
        }

        $unitId = $task->unit_id;
        $location = $task->location;
        $taskType = $task->task_type;
        $date = Carbon::parse($task->date);
        $month = $date->format('Y-m'); // تنسيق الشهر والسنة (مثال: 2025-06)

        // إنشاء معرف فريد للملخص الشهري
        $summaryId = md5("{$month}-{$location}-{$taskType}-{$unitId}"); // تم إضافة unit_id للفرادة

        // حساب الإجماليات من قاعدة البيانات للمهام المطابقة
        $totals = self::query() // استخدام query() لبناء الاستعلام
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
                COUNT(*) as total_tasks_count_for_summary
            ')
            ->first();

        // تحديث أو إنشاء سجل الملخص الشهري
        MonthlyGeneralCleaningSummary::updateOrCreate(
            [
                // يجب أن تتطابق هذه المفاتيح مع المفتاح الأساسي أو الفريد في جدول الملخص
                'id' => $summaryId, // إذا كان الـ ID هو مزيج من هذه الحقول
                'month' => $month,
                'location' => $location,
                'task_type' => $taskType,
                'unit_id' => $unitId, // تأكد أن هذا العمود موجود في جدول الملخص
            ],
            [
                // تعيين القيم المحسوبة، مع ضمان استخدام 0 إذا كانت null
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
            ]
        );
    }

    /**
     * يتعامل مع إنشاء/تحديث تقارير صور المهام.
     *
     * 💡 ملاحظة هامة: هذه الدالة تعتمد على أن البيانات 'before_images' و 'after_images'
     * قد تم توفيرها في الكائن $task أثناء عملية الإنشاء/التحديث، حتى لو لم تكن في $fillable.
     * إذا كنت تقوم بتحميل الصور بشكل منفصل في الكنترولر، ستحتاج إلى استدعاء TaskImageReport
     * مباشرة هناك، أو تمرير بيانات الصور بطريقة أخرى.
     *
     * @param GeneralCleaningTask $task
     * @return void
     */
    private static function handleTaskImageReport(self $task): void
    {
        // افترض أن before_images و after_images متاحان كـ properties على كائن $task
        // حتى لو لم تكن في الـ $fillable، قد يتم تعيينها مؤقتًا قبل الحفظ.
        $beforeImages = $task->getAttribute('before_images');
        $afterImages = $task->getAttribute('after_images');

        if (empty($beforeImages) && empty($afterImages)) {
            // إذا لم تكن هناك صور جديدة، تحقق مما إذا كان هناك تقرير موجود
            // وإذا كان موجودًا ولكنه فارغ، يمكنك حذفه.
            $existingReport = TaskImageReport::where('task_id', $task->id)
                                             ->where('unit_type', 'cleaning')
                                             ->first();
            if ($existingReport && empty($existingReport->before_images) && empty($existingReport->after_images)) {
                 $existingReport->delete(); // حذف التقرير الفارغ
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
            'before_images' => $beforeImages, // استخدام القيم المحمّلة أو الجديدة
            'after_images' => $afterImages,  // استخدام القيم المحمّلة أو الجديدة
        ];

        TaskImageReport::updateOrCreate(
            [
                'task_id' => $task->id,
                'unit_type' => 'cleaning',
            ],
            $reportData
        );
    }

    /**
     * يقوم بتنظيف الصور المرتبطة بالمهام عند حذفها.
     *
     * @param GeneralCleaningTask $task
     * @return void
     */
    private static function cleanupTaskImages(self $task): void
    {
        $report = TaskImageReport::where('task_id', $task->id)
            ->where('unit_type', 'cleaning')
            ->first();

        if ($report) {
            // تأكد أن هذه الدالة موجودة في TaskImageReport Model وتقوم بحذف الملفات فعلياً
            $report->deleteRelatedImages();
            $report->delete(); // حذف سجل التقرير من قاعدة البيانات
        }
    }
}