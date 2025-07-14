<?php

namespace App\Http\Controllers;

use App\Models\UnitGoal;
use App\Models\DepartmentGoal;
use App\Models\MainGoal; // استيراد موديل الهدف الرئيسي
use App\Models\Unit;     // استيراد موديل الوحدة
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class UnitGoalController extends Controller
{
    /**
     * عرض قائمة بأهداف الوحدات مع الأهداف الرئيسية وأهداف الأقسام.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 1. جلب الهدف الرئيسي للمؤسسة
        $mainGoal = MainGoal::first();

        // 2. جلب أهداف الأقسام (الشعب) لعرضها في قسم البطاقات
        // يجب أن يحتوي موديل DepartmentGoal على accessor لحساب overall_progress_percentage
        // إذا لم يكن موجودًا، سيعرض 0%
        $departmentGoals = DepartmentGoal::with('mainGoal')->get();

        // 3. جلب أهداف الأقسام لفلتر البحث المنسدل
        $departmentGoalsForFilter = DepartmentGoal::with('mainGoal')->get()->mapWithKeys(function ($goal) {
            return [$goal->id => ($goal->mainGoal ? $goal->mainGoal->goal_text . ' - ' : '') . $goal->goal_text];
        });

        // 4. جلب الوحدات لفلتر البحث المنسدل
        $units = Unit::all();

        // 5. جلب أهداف الوحدات وتطبيق الفلاتر عليها
        $unitGoalsQuery = UnitGoal::query()->with(['unit', 'departmentGoal.mainGoal']);

        if ($request->filled('department_goal_id')) {
            $unitGoalsQuery->where('department_goal_id', $request->input('department_goal_id'));
        }

        if ($request->filled('unit_id')) {
            $unitGoalsQuery->where('unit_id', $request->input('unit_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $unitGoalsQuery->where(function ($q) use ($search) {
                $q->where('unit_name', 'like', '%' . $search . '%')
                    ->orWhere('goal_text', 'like', '%' . $search . '%')
                    ->orWhereHas('unit', function ($q_unit) use ($search) {
                        $q_unit->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('departmentGoal', function ($q_dept) use ($search) {
                        $q_dept->where('goal_text', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('departmentGoal.mainGoal', function ($q_main) use ($search) {
                        $q_main->where('goal_text', 'like', '%' . $search . '%');
                    });
            });
        }

        // الترتيب حسب department_goal_id لضمان تجميع الأهداف في الواجهة
        $unitGoals = $unitGoalsQuery->orderBy('department_goal_id')->orderBy('date', 'desc')->paginate(10);

        // 6. حساب مؤشرات الأداء الرئيسية (KPIs) والرسوم البيانية
        // ملاحظة: يتم جلب جميع الأهداف هنا لحساب KPIs الدقيقة بناءً على الـ accessors.
        // هذا قد يكون غير فعال لعدد كبير جداً من السجلات.
        $allGoals = UnitGoal::all();

        $totalGoals = $allGoals->count();
        $completedGoalsCount = $allGoals->filter(fn($goal) => $goal->progress_percentage >= 100)->count();
        $inProgressGoalsCount = $allGoals->filter(fn($goal) => $goal->progress_percentage > 0 && $goal->progress_percentage < 100)->count();
        $notStartedGoalsCount = $allGoals->filter(fn($goal) => $goal->progress_percentage == 0)->count();
        // لحساب المتأخرة، نستخدم عمود 'date' كـ 'due_date' إذا كان يعبر عن ذلك
        $overdueGoalsCount = $allGoals->filter(fn($goal) => $goal->progress_percentage < 100 && ($goal->date && $goal->date->isPast()))->count();

        // حساب النسبة المئوية للتقدم الإجمالي لجميع الأهداف
        $overallProgressPercentage = $totalGoals > 0 ? $allGoals->sum(function($goal) {
            return $goal->progress_percentage;
        }) / $totalGoals : 0;

        // 7. تمرير جميع المتغيرات إلى الواجهة
        return view('unit-goals.index', compact(
            'mainGoal',
            'departmentGoals',
            'departmentGoalsForFilter',
            'units',
            'unitGoals',
            'totalGoals',
            'completedGoalsCount',
            'inProgressGoalsCount',
            'overdueGoalsCount',
            'notStartedGoalsCount',
            'overallProgressPercentage'
        ));
    }

    /**
     * عرض نموذج إنشاء هدف وحدة جديد.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = DepartmentGoal::with('mainGoal')->get()->mapWithKeys(function ($goal) {
            return [$goal->id => ($goal->mainGoal ? $goal->mainGoal->goal_text . ' - ' : '') . $goal->goal_text];
        });
        $units = Unit::all();

        return view('unit-goals.create', compact('departments', 'units'));
    }

    /**
     * تخزين هدف وحدة جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department_goal_id' => 'required|exists:department_goals,id',
            'unit_id' => 'nullable|exists:units,id',
            'unit_name' => 'required|string|max:255',
            'goal_text' => 'required|string|max:65535',
            'target_tasks' => 'required|numeric|min:0',
            'date' => 'nullable|date',
        ]);

        UnitGoal::create($validatedData);

        Session::flash('success', 'تم إضافة هدف الوحدة بنجاح.');
        return redirect()->route('unit-goals.index');
    }

    /**
     * عرض نموذج تعديل هدف وحدة موجود.
     *
     * @param  \App\Models\UnitGoal  $unitGoal
     * @return \Illuminate\View\View
     */
    public function edit(UnitGoal $unitGoal)
    {
        $departments = DepartmentGoal::with('mainGoal')->get()->mapWithKeys(function ($goal) {
            return [$goal->id => ($goal->mainGoal ? $goal->mainGoal->goal_text . ' - ' : '') . $goal->goal_text];
        });
        $units = Unit::all();

        return view('unit-goals.edit', compact('unitGoal', 'departments', 'units'));
    }

    /**
     * تحديث هدف وحدة موجود في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnitGoal  $unitGoal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, UnitGoal $unitGoal)
    {
        $validatedData = $request->validate([
            'department_goal_id' => 'required|exists:department_goals,id',
            'unit_id' => 'nullable|exists:units,id',
            'unit_name' => 'required|string|max:255',
            'goal_text' => 'required|string|max:65535',
            'target_tasks' => 'required|numeric|min:0',
            'date' => 'nullable|date',
        ]);

        $unitGoal->update($validatedData);

        Session::flash('success', 'تم تحديث هدف الوحدة بنجاح.');
        return redirect()->route('unit-goals.index');
    }

    /**
     * حذف هدف وحدة من قاعدة البيانات.
     *
     * @param  \App\Models\UnitGoal  $unitGoal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UnitGoal $unitGoal)
    {
        $unitGoal->delete();
        Session::flash('success', 'تم حذف هدف الوحدة بنجاح.');
        return redirect()->route('unit-goals.index');
    }
}
