<?php

namespace App\Http\Controllers;

use App\Models\ActualResult;
use App\Models\Unit; // لتصفية الوحدات
use App\Models\UnitGoal; // لملء المهام المستهدفة
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule; // لتطبيق قواعد التحقق
use Illuminate\Support\Facades\Log; // للسجلات
use Illuminate\Support\Facades\Session; // للرسائل الومضية (Flash messages)

class ActualResultController extends Controller
{
    /**
     * عرض قائمة بسجلات النتائج الفعلية.
     */
    public function index(Request $request)
    {
        $query = ActualResult::query()->with(['unit', 'unitGoal']);

        // تطبيق الفلاتر
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }

        if ($request->filled('performance_level')) {
            $performanceLevel = $request->input('performance_level');
            switch ($performanceLevel) {
                case 'excellent':
                    $query->where('overall_performance_score', '>=', 90);
                    break;
                case 'good':
                    $query->whereBetween('overall_performance_score', [70, 89]);
                    break;
                case 'average':
                    $query->whereBetween('overall_performance_score', [50, 69]);
                    break;
                case 'poor':
                    $query->where('overall_performance_score', '<', 50);
                    break;
            }
        }

        // البحث (يمكن إضافة منطق بحث أكثر تعقيدًا هنا إذا لزم الأمر)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('unit', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('notes', 'like', '%' . $search . '%');
        }

        $actualResults = $query->orderBy('date', 'desc')->paginate(10); // 10 عناصر في الصفحة
        $units = Unit::all(); // لجلب الوحدات لفلتر الاختيار
        $performanceLevels = [
            'excellent' => 'ممتاز (90% فأكثر)',
            'good' => 'جيد (70-89%)',
            'average' => 'متوسط (50-69%)',
            'poor' => 'ضعيف (أقل من 50%)',
        ];

        return view('actual-results.index', compact('actualResults', 'units', 'performanceLevels'));
    }

    /**
     * عرض نموذج إنشاء سجل جديد.
     */
    public function create()
    {
        $units = Unit::all();
        // تمرير ActualResult فارغ لنموذج الإنشاء
        return view('actual-results.create', compact('units'));
    }

    /**
     * تخزين سجل جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'unit_id' => 'required|exists:units,id',
            'completed_tasks' => 'required|numeric|min:0',
            'working_hours' => 'required|numeric|min:0|max:24',
            'quality_rating' => 'nullable|numeric|min:1|max:5',
            'efficiency_score' => 'nullable|numeric|min:1|max:100',
            'notes' => 'nullable|string|max:1000',
            // 'target_tasks_display' لا يتم حفظه مباشرة
        ]);

        // جلب الهدف المرتبط لتحديد unit_goal_id
        $goal = UnitGoal::where('unit_id', $validatedData['unit_id'])
                        ->whereDate('date', $validatedData['date'])
                        ->first();
        if (!$goal) {
            Session::flash('error', 'لا يوجد هدف وحدة مرتبط لهذا التاريخ والوحدة. يرجى إضافة هدف وحدة أولاً.');
            return redirect()->back()->withInput();
        }

        // إنشاء السجل
        $actualResult = ActualResult::create(array_merge($validatedData, [
            'unit_goal_id' => $goal->id,
            'department_goal_id' => $goal->department_goal_id,
        ]));

        // إعادة حساب مقاييس جلبرت وتحديث السجل
        $this->calculateAndSetGilbertMetrics($actualResult);


        Session::flash('success', 'تم إنشاء سجل النتائج الفعلية بنجاح.');
        return redirect()->route('actual-results.index');
    }

    /**
     * عرض نموذج تعديل سجل موجود.
     */
    public function edit(ActualResult $actualResult)
    {
        $units = Unit::all();
        // جلب المهام المستهدفة لعرضها في الفورم
        $actualResult->target_tasks_display = $actualResult->unitGoal->target_tasks ?? 0;
        return view('actual-results.edit', compact('actualResult', 'units'));
    }

    /**
     * تحديث سجل موجود في قاعدة البيانات.
     */
    public function update(Request $request, ActualResult $actualResult)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'unit_id' => 'required|exists:units,id',
            'completed_tasks' => 'required|numeric|min:0',
            'working_hours' => 'required|numeric|min:0|max:24',
            'quality_rating' => 'nullable|numeric|min:1|max:5',
            'efficiency_score' => 'nullable|numeric|min:1|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // جلب الهدف المرتبط لتحديد unit_goal_id إذا تغير
        $goal = UnitGoal::where('unit_id', $validatedData['unit_id'])
                        ->whereDate('date', $validatedData['date'])
                        ->first();
        if (!$goal) {
            Session::flash('error', 'لا يوجد هدف وحدة مرتبط لهذا التاريخ والوحدة. يرجى إضافة هدف وحدة أولاً.');
            return redirect()->back()->withInput();
        }

        // تحديث السجل
        $actualResult->update(array_merge($validatedData, [
            'unit_goal_id' => $goal->id,
            'department_goal_id' => $goal->department_goal_id,
        ]));

        // إعادة حساب مقاييس جلبرت وتحديث السجل
        $this->calculateAndSetGilbertMetrics($actualResult);

        Session::flash('success', 'تم تحديث سجل النتائج الفعلية بنجاح.');
        return redirect()->route('actual-results.index');
    }

    /**
     * حذف سجل من قاعدة البيانات.
     */
    public function destroy(ActualResult $actualResult)
    {
        $actualResult->delete();
        Session::flash('success', 'تم حذف سجل النتائج الفعلية بنجاح.');
        return redirect()->route('actual-results.index');
    }

    /**
     * دالة مساعدة لحساب مقاييس جلبرت وتعيينها في السجل.
     * تُستخدم بعد إنشاء/تحديث سجل ActualResult
     */
    private function calculateAndSetGilbertMetrics(ActualResult $actualResult): void
    {
        // استدعاء الدالة الساكنة من الموديل التي تحسب وتحدث جميع المقاييس
        // هذا يضمن أن يتم تحديث السجل الحالي بالكامل بعد أي تغيير
        ActualResult::recalculateForUnitAndDate(
            $actualResult->unit_id,
            $actualResult->date->toDateString(),
            $actualResult->unit_goal_id
        );
    }

    /**
     * توليد/تحديث النتائج الفعلية اليومية لجميع الوحدات.
     * يمكن استدعاؤها من خلال إجراء يدوي أو جدولة (Cron Job).
     */
    public function generateDailyResults()
    {
        $units = Unit::all();
        $today = now()->format('Y-m-d');

        foreach ($units as $unit) {
            ActualResult::recalculateForUnitAndDate($unit->id, $today);
        }

        Session::flash('success', 'تم توليد / تحديث النتائج الفعلية لليوم بنجاح.');
        return redirect()->back(); // أو إلى صفحة النتائج الفعلية
    }

    /**
     * نقطة نهاية AJAX لجلب المهام المستهدفة وحساب مقاييس جلبرت
     * ديناميكيًا في الفورم.
     */
    public function getFormMetrics(Request $request)
    {
        $unitId = $request->input('unit_id');
        $date = $request->input('date');
        $completedTasks = (float) $request->input('completed_tasks', 0);
        $workingHours = (float) $request->input('working_hours', 1);
        $qualityRating = (float) $request->input('quality_rating', 0); // جلب تقييم الجودة

        $targetTasksDisplay = 0;
        $unitGoalId = null;
        $departmentGoalId = null;

        if ($unitId && $date) {
            $parsedDate = Carbon::parse($date)->format('Y-m-d');
            $goal = UnitGoal::where('unit_id', $unitId)
                ->whereDate('date', $parsedDate)
                ->first();
            if (!$goal) {
                $goal = UnitGoal::where('unit_id', $unitId)->latest()->first(); // fallback
            }
            if ($goal) {
                $targetTasksDisplay = $goal->target_tasks ?? 0;
                $unitGoalId = $goal->id;
                $departmentGoalId = $goal->department_goal_id;
            }
        }

        // ضمان عدم القسمة على صفر
        $targetTasksForCalculation = ($targetTasksDisplay === 0.0) ? 1 : $targetTasksDisplay;
        $workingHoursForCalculation = ($workingHours === 0.0) ? 1 : $workingHours;

        // حسابات مقاييس جلبرت (تكرار جزئي من منطق الموديل لغرض الواجهة الأمامية)
        $effectiveness = ($targetTasksForCalculation > 0) ? ($completedTasks / $targetTasksForCalculation) * 100 : 0;
        $efficiency = ($workingHoursForCalculation > 0) ? ($completedTasks / $workingHoursForCalculation) * 100 : 0;

        // منطق الملاءمة (Relevance) من نموذج ActualResult المحدث
        $relevance = 0;
        if ($targetTasksForCalculation > 0 && $workingHoursForCalculation > 0) {
            $optimal_hours_per_targeted_task = 0.5; // نفس القيمة من الموديل
            $actual_hours_per_targeted_task_for_relevance = $targetTasksForCalculation > 0 ? $workingHoursForCalculation / $targetTasksForCalculation : INF;

            $deviation = abs($actual_hours_per_targeted_task_for_relevance - $optimal_hours_per_targeted_task);
            $max_acceptable_deviation = $optimal_hours_per_targeted_task * 2;

            if ($max_acceptable_deviation > 0) {
                $relevance = 100 - min(100, ($deviation / $max_acceptable_deviation) * 100);
            } else {
                $relevance = 0;
            }
            $relevance = max(0, round($relevance, 2));
        } elseif ($targetTasksForCalculation == 0 && $workingHoursForCalculation == 0) {
            $relevance = 100;
        }

        // الأداء (Performance) يتم تمثيله بـ quality_rating
        $performance = min(100, max(0, round($qualityRating * 20, 2))); // تحويل تقييم 1-5 إلى 0-100

        // الأداء الإجمالي: متوسط الأبعاد الثلاثة
        $overallScoreSum = 0;
        $validMetricsCount = 0;

        if ($performance > 0 || ($performance === 0 && $qualityRating > 0)) {
            $overallScoreSum += $performance;
            $validMetricsCount++;
        }
        if ($effectiveness > 0 || ($effectiveness === 0 && $targetTasksForCalculation === 0 && $completedTasks === 0)) {
            $overallScoreSum += $effectiveness;
            $validMetricsCount++;
        }
        if ($efficiency > 0 || ($efficiency === 0 && $workingHoursForCalculation === 0 && $completedTasks === 0)) {
            $overallScoreSum += $efficiency;
            $validMetricsCount++;
        }

        $overallScore = ($validMetricsCount > 0) ? round($overallScoreSum / $validMetricsCount, 2) : 0;

        return response()->json([
            'target_tasks_display' => $targetTasksDisplay,
            'effectiveness' => round($effectiveness, 2),
            'efficiency' => round($efficiency, 2),
            'relevance' => round($relevance, 2),
            'overall_performance_score' => round($overallScore, 2),
            'unit_goal_id' => $unitGoalId,
            'department_goal_id' => $departmentGoalId,
        ]);
    }

    /**
     * Helper function to determine text color based on performance value.
     *
     * @param float $value
     * @return string
     */
    public static function getPerformanceColor(float $value): string
    {
        if ($value >= 90) return 'text-success';
        if ($value >= 70) return 'text-warning';
        if ($value >= 50) return 'text-info';
        return 'text-danger';
    }

    /**
     * Helper function to determine text color for overall performance score.
     *
     * @param float $value
     * @return string
     */
    public static function getOverallPerformanceColor(float $value): string
    {
        if ($value >= 85) return 'text-success';
        if ($value >= 65) return 'text-warning';
        return 'text-danger';
    }
}
