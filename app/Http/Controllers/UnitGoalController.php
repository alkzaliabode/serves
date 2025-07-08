<?php

namespace App\Http\Controllers;

use App\Models\UnitGoal;
use App\Models\DepartmentGoal;
use App\Models\Unit; // لجلب الوحدات لفلتر الاختيار
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class UnitGoalController extends Controller
{
    /**
     * عرض قائمة بأهداف الوحدات.
     */
    public function index(Request $request)
    {
        $query = UnitGoal::query()->with(['unit', 'departmentGoal.mainGoal']);

        // تطبيق الفلاتر
        if ($request->filled('department_goal_id')) {
            $query->where('department_goal_id', $request->input('department_goal_id'));
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }

        // البحث عن طريق اسم الوحدة أو نص الهدف
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
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


        $unitGoals = $query->orderBy('date', 'desc')->paginate(10); // 10 عناصر في الصفحة
        $departments = DepartmentGoal::with('mainGoal')->get(); // لجلب أهداف الأقسام لفلتر الاختيار
        $units = Unit::all(); // لجلب الوحدات لفلتر الاختيار

        return view('unit-goals.index', compact('unitGoals', 'departments', 'units'));
    }

    /**
     * عرض نموذج إنشاء هدف وحدة جديد.
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
     */
    public function destroy(UnitGoal $unitGoal)
    {
        $unitGoal->delete();
        Session::flash('success', 'تم حذف هدف الوحدة بنجاح.');
        return redirect()->route('unit-goals.index');
    }
}
