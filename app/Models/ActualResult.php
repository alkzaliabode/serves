<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log; // Added for debugging
use App\Models\GeneralCleaningTask;
use App\Models\SanitationFacilityTask;
use App\Models\UnitGoal;
use App\Models\DepartmentGoal;
use App\Models\ResourceTracking; // Assuming this model exists and is relevant

class ActualResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'completed_tasks',
        'quality_rating', // This will represent 'Performance' on the chart
        'efficiency_score', // Not explicitly used in Gilbert calcs, retained for flexibility
        'unit_id',
        'department_goal_id',
        'unit_goal_id',
        'working_hours',
        'effectiveness',
        'efficiency',
        'relevance',
        'overall_performance_score', // Overall average of Performance, Effectiveness, Efficiency
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'effectiveness' => 'float',
        'efficiency' => 'float',
        'relevance' => 'float',
        'overall_performance_score' => 'float',
        'quality_rating' => 'float',
    ];

    /**
     * العلاقة بين النتيجة والوحدة.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * العلاقة بين النتيجة وهدف الوحدة.
     */
    public function unitGoal(): BelongsTo
    {
        return $this->belongsTo(UnitGoal::class);
    }

    /**
     * العلاقة بين النتيجة وهدف القسم.
     */
    public function departmentGoal(): BelongsTo
    {
        return $this->belongsTo(DepartmentGoal::class);
    }

    /**
     * حساب معدل الإنجاز بناءً على الهدف المستهدف لهذه الوحدة والتاريخ.
     */
    public function getCompletionRateAttribute(): float
    {
        $targetTasks = $this->unitGoal?->target_tasks ?? 0;

        if ($targetTasks == 0) {
            return 0.0;
        }

        return round(($this->completed_tasks / $targetTasks) * 100, 2);
    }

    /**
     * تقوم تلقائياً بحساب إجمالي المهام المكتملة من الموديلات المرتبطة
     * وتحدّث/تنشئ سجل ActualResult، بما في ذلك مقاييس غيلبرت.
     *
     * @param int $unitId معرف الوحدة.
     * @param string|\Carbon\Carbon $date التاريخ.
     * @param int|null $relatedGoalId معرف الهدف المرتبط من المهمة (لتحديد UnitGoal المحدد).
     * @return void
     */
    public static function recalculateForUnitAndDate($unitId, $date, $relatedGoalId = null): void
    {
        if (!$unitId || !$date) {
            Log::warning("ActualResult recalculation skipped: Unit ID or date is missing.", ['unit_id' => $unitId, 'date' => $date]);
            return;
        }

        $parsedDate = Carbon::parse($date)->format('Y-m-d');

        // --- 1. جمع البيانات من GeneralCleaningTask ---
        $generalCleaningTasks = GeneralCleaningTask::where('unit_id', $unitId)
            ->whereDate('date', $parsedDate)
            ->where('status', 'مكتمل')
            ->with('employeeTasks') // جلب تقييمات الموظفين
            ->get();

        $totalGeneralCompletedItems = 0;
        $totalGeneralWorkingHours = 0;
        $generalQualityRatingSum = 0;
        $generalQualityRatingCount = 0;

        foreach ($generalCleaningTasks as $task) {
            // جمع كل الحقول العددية التي تمثل "إنجازات"
            $totalGeneralCompletedItems += $task->mats_count ?? 0;
            $totalGeneralCompletedItems += $task->pillows_count ?? 0;
            $totalGeneralCompletedItems += $task->fans_count ?? 0;
            $totalGeneralCompletedItems += $task->windows_count ?? 0;
            $totalGeneralCompletedItems += $task->carpets_count ?? 0;
            $totalGeneralCompletedItems += $task->blankets_count ?? 0;
            $totalGeneralCompletedItems += $task->beds_count ?? 0;
            $totalGeneralCompletedItems += $task->beneficiaries_count ?? 0;
            $totalGeneralCompletedItems += $task->filled_trams_count ?? 0;
            $totalGeneralCompletedItems += $task->carpets_laid_count ?? 0;
            $totalGeneralCompletedItems += $task->large_containers_count ?? 0;
            $totalGeneralCompletedItems += $task->small_containers_count ?? 0;
            $totalGeneralCompletedItems += $task->external_partitions_count ?? 0; // تأكد من وجود هذا العمود

            $totalGeneralWorkingHours += $task->working_hours ?? 0;

            // جمع تقييمات الموظفين
            foreach ($task->employeeTasks as $employeeTask) {
                if ($employeeTask->employee_rating !== null) {
                    $generalQualityRatingSum += $employeeTask->employee_rating;
                    $generalQualityRatingCount++;
                }
            }
        }

        // --- 2. جمع البيانات من SanitationFacilityTask ---
        $sanitationFacilityTasks = SanitationFacilityTask::where('unit_id', $unitId)
            ->whereDate('date', $parsedDate)
            ->where('status', 'مكتمل')
            ->with('employeeTasks') // جلب تقييمات الموظفين
            ->get();

        $totalSanitationCompletedItems = 0;
        $totalSanitationWorkingHours = 0;
        $sanitationQualityRatingSum = 0;
        $sanitationQualityRatingCount = 0;

        foreach ($sanitationFacilityTasks as $task) {
            // افتراض: كل مهمة مكتملة في SanitationFacilityTask تعتبر "وحدة إنجاز" واحدة.
            // إذا كان هناك حقول كمية محددة في SanitationFacilityTask، يجب جمعها هنا.
            $totalSanitationCompletedItems += 1; // كل مهمة مكتملة = 1 إنجاز

            $totalSanitationWorkingHours += $task->working_hours ?? 0;

            // جمع تقييمات الموظفين
            foreach ($task->employeeTasks as $employeeTask) {
                if ($employeeTask->employee_rating !== null) {
                    $sanitationQualityRatingSum += $employeeTask->employee_rating;
                    $sanitationQualityRatingCount++;
                }
            }
        }

        // --- 3. تجميع الإجماليات من كلا نوعي المهام ---
        $totalCompletedTasks = $totalGeneralCompletedItems + $totalSanitationCompletedItems;
        $totalWorkingHours = $totalGeneralWorkingHours + $totalSanitationWorkingHours;

        $overallQualityRatingSum = $generalQualityRatingSum + $sanitationQualityRatingSum;
        $overallQualityRatingCount = $generalQualityRatingCount + $sanitationQualityRatingCount;
        $avgQualityRating = ($overallQualityRatingCount > 0) ? $overallQualityRatingSum / $overallQualityRatingCount : 0;

        // --- 4. جلب هدف الوحدة المرتبط (UnitGoal) ---
        $goal = null;
        if ($relatedGoalId) {
            $goal = UnitGoal::find($relatedGoalId);
        }

        if (!$goal) {
            // الأولوية لهدف محدد بتاريخ الوحدة
            $goal = UnitGoal::where('unit_id', $unitId)
                ->whereDate('date', $parsedDate)
                ->first();
            if (!$goal) {
                // كخيار أخير، جلب أي هدف للوحدة
                $goal = UnitGoal::where('unit_id', $unitId)->latest()->first();
            }
        }

        // إذا لم يتم العثور على هدف، لا يمكننا إنشاء ActualResult لأن unit_goal_id مطلوب وفريد
        if (!$goal || !$goal->id) {
            Log::warning("ActualResult recalculation skipped: No valid UnitGoal found for unit ID: {$unitId} on date: {$parsedDate}.", ['unit_id' => $unitId, 'date' => $parsedDate]);
            return;
        }

        $targetTasksForCalculation = $goal->target_tasks ?? 0;

        // --- 5. جلب سجل ResourceTracking لساعات العمل (كمورد) - إذا كان يستخدم كمصدر أساسي ---
        // إذا كان ResourceTracking يستخدم لتخزين ساعات العمل الإجمالية للوحدة،
        // فيجب أن يتم تحديثه بشكل منفصل أو أن يكون له آلية لجمع ساعات العمل من المهام.
        // هنا، سنستخدم $totalWorkingHours المحسوبة من المهام مباشرة،
        // ويمكنك تعديل هذا الجزء إذا كان ResourceTracking هو المصدر الرئيسي لساعات العمل.
        // For now, let's prioritize the summed working hours from tasks.
        $totalResourceWorkingHours = $totalWorkingHours; 
        
        // --- حسابات مقاييس أداء غيلبرت ---

        // الأداء (Performance): جودة النتائج (من 0 إلى 100)
        // هذا هو المحور الأول للرادار: "الأداء"
        // يعتمد على $avgQualityRating (متوسط تقييمات الموظفين).
        $performance = min(100, max(0, round($avgQualityRating * 20, 2))); // تحويل من 1-5 إلى 0-100

        // الفاعلية (Effectiveness): (النتائج الفعلية / الأهداف المستهدفة) * 100
        // هذا هو المحور الثاني للرادار: "الفاعلية"
        $effectiveness = 0;
        if ($targetTasksForCalculation > 0) {
            $effectiveness = min(100, round(($totalCompletedTasks / $targetTasksForCalculation) * 100, 2));
        }

        // الكفاءة (Efficiency): (النتائج الفعلية / الموارد المستهلكة) * 100
        // هذا هو المحور الثالث للرادار: "الكفاءة"
        $efficiency = 0;
        if ($totalResourceWorkingHours > 0) {
            // عامل التحويل (50) هو مثال. يجب تعديله ليناسب بياناتك بحيث تكون القيمة في نطاق 0-100.
            // مثلاً، إذا كان إنجاز 100 مهمة بـ 2 ساعة عمل يعني كفاءة 100%، فالقيمة 50 ستكون: (100/2)*X = 100 -> X = 2
            $conversionFactor = 50; // افتراض: 0.5 ساعة عمل لكل مهمة بكفاءة 100%
            $efficiency = min(100, round(($totalCompletedTasks / $totalResourceWorkingHours) * $conversionFactor, 2));
        }

        // الملاءمة (Relevance): (الموارد / الأهداف) * 100
        // هذا ليس محورًا مباشرًا في مخطط الرادار ذي الثلاث نقاط، ولكنه يحسب للتخزين.
        // تعريف الملاءمة: "علاقة بين الموارد والأهداف: تقيس مدى ملاءمة الموارد للوصول للأهداف."
        $relevance = 0;
        if ($targetTasksForCalculation > 0 && $totalResourceWorkingHours > 0) {
            // تعريف نسبة مثالية للموارد لكل مهمة مستهدفة (ساعات/مهمة مستهدفة)
            // هذا الرقم هو معيار يجب تحديده بناءً على أفضل الممارسات أو الأهداف التشغيلية.
            $optimal_hours_per_targeted_task = 0.5; // مثال: 0.5 ساعة عمل لكل مهمة مستهدفة يعتبر مناسباً

            // حساب النسبة الفعلية للموارد المستخدمة لكل مهمة مستهدفة
            $actual_hours_per_targeted_task = $totalResourceWorkingHours / $targetTasksForCalculation;

            // حساب الانحراف عن النسبة المثالية
            $deviation = abs($actual_hours_per_targeted_task - $optimal_hours_per_targeted_task);

            // تعريف أقصى انحراف مقبول (على سبيل المثال، ضعف النسبة المثالية)
            // هذا لتحديد مدى تأثير الانحراف على درجة الملاءمة.
            $max_acceptable_deviation = $optimal_hours_per_targeted_task * 2; 

            if ($max_acceptable_deviation > 0) {
                // حساب درجة الملاءمة: 100% ناقص نسبة الانحراف من أقصى انحراف مقبول
                $relevance = 100 - min(100, ($deviation / $max_acceptable_deviation) * 100);
            } else {
                // تجنب القسمة على صفر، في حالات خاصة قد تكون النسبة المثالية صفر (لكن غير محتملة هنا)
                $relevance = 0; 
            }
            
            // التأكد من أن درجة الملاءمة لا تقل عن 0 وتدويرها
            $relevance = max(0, round($relevance, 2));

        } elseif ($targetTasksForCalculation == 0 && $totalResourceWorkingHours == 0) {
            $relevance = 100; // ملاءمة مثالية: لا أهداف ولا موارد مستخدمة، وهذا يعتبر متناسباً.
        } elseif ($targetTasksForCalculation == 0 && $totalResourceWorkingHours > 0) {
            $relevance = 0; // عدم ملاءمة: توجد موارد ولكن لا توجد أهداف لها.
        } elseif ($targetTasksForCalculation > 0 && $totalResourceWorkingHours == 0) {
            $relevance = 0; // عدم ملاءمة: توجد أهداف ولكن لا توجد موارد لتحقيقها.
        }

        // الأداء الإجمالي (Overall Performance Score): متوسط الثلاثة محاور الرئيسية (الأداء، الفاعلية، الكفاءة)
        $overallScoreSum = 0;
        $validMetricsCount = 0;
        
        // Include performance if it's valid or if the source (avgQualityRating) was zero
        if ($performance > 0 || ($performance === 0 && ($avgQualityRating !== null))) { // Modified condition for performance
            $overallScoreSum += $performance;
            $validMetricsCount++;
        }

        // Include effectiveness if it's valid or if target was zero (resulting in 0 effectiveness)
        if ($effectiveness > 0 || ($effectiveness === 0 && $targetTasksForCalculation === 0 && $totalCompletedTasks === 0)) {
            $overallScoreSum += $effectiveness;
            $validMetricsCount++;
        }

        // Include efficiency if it's valid or if resources were zero (resulting in 0 efficiency)
        if ($efficiency > 0 || ($efficiency === 0 && $totalResourceWorkingHours === 0 && $totalCompletedTasks === 0)) {
            $overallScoreSum += $efficiency;
            $validMetricsCount++;
        }
        
        $overallScore = ($validMetricsCount > 0) ? round($overallScoreSum / $validMetricsCount, 2) : 0;
        
        // 5. تحديث أو إنشاء سجل ActualResult
        // CRITICAL CHANGE: Use 'date' and 'unit_goal_id' as unique criteria as per migration
        self::updateOrCreate(
            [
                'date' => $parsedDate,
                'unit_goal_id' => $goal->id, // MUST use unit_goal_id for uniqueness as per schema
            ],
            [
                'unit_id' => $unitId, // This is a data field, not part of the unique key here
                'completed_tasks' => $totalCompletedTasks,
                'quality_rating' => $performance, // هذا هو 'الأداء' الذي سيظهر في الرسم البياني
                'efficiency_score' => null, // لم يتم استخدامه في حسابات جلبرت، احتفظ به كما هو أو من قيمة سابقة
                'department_goal_id' => $goal->department_goal_id,
                'working_hours' => round($totalResourceWorkingHours, 2),
                'effectiveness' => $effectiveness,
                'efficiency' => $efficiency,
                'relevance' => $relevance,
                'overall_performance_score' => $overallScore,
                'notes' => 'تم إعادة حساب مقاييس جلبرت تلقائياً في ' . Carbon::now()->toDateTimeString(),
            ]
        );

        Log::info("ActualResult recalculated for Unit {$unitId} on {$parsedDate} (Goal ID: {$goal->id}): Performance={$performance}%, Effectiveness={$effectiveness}%, Efficiency={$efficiency}%, Relevance={$relevance}%, Overall={$overallScore}%");
    }
}
