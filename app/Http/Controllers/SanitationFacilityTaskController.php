<?php

namespace App\Http\Controllers;

use App\Models\SanitationFacilityTask;
use App\Models\Unit; // افتراض أن لديك نموذج Unit
use App\Models\UnitGoal; // افتراض أن لديك نموذج UnitGoal
use App\Models\Employee; // افتراض أن لديك نموذج Employee
use App\Models\EmployeeTask; // النموذج الوسيط لربط الموظفين بالمهام
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // لاستخدام تخزين الملفات

class SanitationFacilityTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SanitationFacilityTask::with(['creator', 'editor', 'relatedGoal', 'unit', 'employeeTasks.employee']);

        // فلاتر البحث
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('facility_name', 'like', '%' . $search . '%')
                  ->orWhere('task_type', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%')
                  ->orWhereHas('relatedGoal', function ($sq) use ($search) {
                      $sq->where('goal_text', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('creator', function ($sq) use ($search) {
                      $sq->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('employeeTasks.employee', function ($sq) use ($search) {
                      $sq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // فلاتر الاختيار (Select Filters)
        if ($request->has('task_type') && $request->input('task_type') != '') {
            $query->where('task_type', $request->input('task_type'));
        }
        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('shift') && $request->input('shift') != '') {
            $query->where('shift', $request->input('shift'));
        }
        if ($request->has('facility_name') && $request->input('facility_name') != '') {
            $query->where('facility_name', $request->input('facility_name'));
        }
        if ($request->has('employee_id') && $request->input('employee_id') != '') {
            $employeeId = $request->input('employee_id');
            $query->whereHas('employeeTasks', function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            });
        }

        // فلتر نطاق التاريخ (Date Range Filter)
        if ($request->has('from_date') && $request->input('from_date') != '') {
            $query->whereDate('date', '>=', $request->input('from_date'));
        }
        if ($request->has('to_date') && $request->input('to_date') != '') {
            $query->whereDate('date', '<=', $request->input('to_date'));
        }

        // الفرز الافتراضي (يمكن توسيعه ليشمل أعمدة مختلفة)
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');
        // تأكد أن الأعمدة المتاحة للفرز موجودة في الجدول
        if (in_array($sortBy, ['date', 'facility_name', 'task_type', 'shift', 'status', 'working_hours'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('date', 'desc'); // الترتيب الافتراضي إذا كان الفرز غير صالح
        }

        $tasks = $query->paginate(10); // 10 عناصر في كل صفحة

        $employees = Employee::orderBy('name')->get(); // لجلب الموظفين لفلتر الموظفين المنفذين
        $goals = UnitGoal::all(); // لجلب الأهداف لفلتر الأهداف المرتبطة (إذا أردت إضافته)

        return view('sanitation_facility_tasks.index', compact('tasks', 'employees', 'goals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::all();
        $goals = UnitGoal::all();
        $employees = Employee::orderBy('name')->get(); // لجلب الموظفين لنموذج المنفذين

        return view('sanitation_facility_tasks.create', compact('units', 'goals', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->rules());

        // معالجة صور ما قبل التنفيذ
        $beforeImagePaths = [];
        if ($request->hasFile('before_images')) {
            foreach ($request->file('before_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/before', 'public');
                $beforeImagePaths[] = $path;
            }
        }

        // معالجة صور ما بعد التنفيذ
        $afterImagePaths = [];
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/after', 'public');
                $afterImagePaths[] = $path;
            }
        }

        // معالجة الموارد المستخدمة
        $resourcesUsed = $request->input('resources_used') ?: [];

        $task = SanitationFacilityTask::create(array_merge($validatedData, [
            // created_by و updated_by و unit_id يتم تعيينها في booted method في الموديل
            'before_images' => $beforeImagePaths,
            'after_images' => $afterImagePaths,
            'resources_used' => $resourcesUsed,
        ]));

        // حفظ الموظفين والتقييمات
        if ($request->has('employeeTasks')) {
            foreach ($request->input('employeeTasks') as $employeeTaskData) {
                $task->employeeTasks()->create([
                    'employee_id' => $employeeTaskData['employee_id'],
                    'employee_rating' => $employeeTaskData['employee_rating'],
                ]);
            }
        }

        return redirect()->route('sanitation-facility-tasks.index')->with('success', 'تم إنشاء مهمة المنشآت الصحية بنجاح!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SanitationFacilityTask $sanitationFacilityTask)
    {
        $units = Unit::all();
        $goals = UnitGoal::all();
        $employees = Employee::orderBy('name')->get();

        // تحميل علاقة employeeTasks مسبقاً
        $sanitationFacilityTask->load('employeeTasks');

        return view('sanitation_facility_tasks.edit', compact('sanitationFacilityTask', 'units', 'goals', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SanitationFacilityTask $sanitationFacilityTask)
    {
        $validatedData = $request->validate($this->rules());

        // معالجة صور ما قبل التنفيذ
        $beforeImagePaths = $sanitationFacilityTask->before_images ?: [];
        if ($request->hasFile('before_images')) {
            // حذف الصور القديمة إذا تم رفع صور جديدة
            foreach ($beforeImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $beforeImagePaths = []; // إعادة تعيين المسارات
            foreach ($request->file('before_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/before', 'public');
                $beforeImagePaths[] = $path;
            }
        } else if ($request->input('remove_before_images') == '1') { // إذا أرسل المستخدم طلب حذف كل الصور
             foreach ($beforeImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $beforeImagePaths = [];
        }


        // معالجة صور ما بعد التنفيذ
        $afterImagePaths = $sanitationFacilityTask->after_images ?: [];
        if ($request->hasFile('after_images')) {
            // حذف الصور القديمة
            foreach ($afterImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $afterImagePaths = [];
            foreach ($request->file('after_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/after', 'public');
                $afterImagePaths[] = $path;
            }
        } else if ($request->input('remove_after_images') == '1') { // إذا أرسل المستخدم طلب حذف كل الصور
             foreach ($afterImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $afterImagePaths = [];
        }

        // معالجة الموارد المستخدمة
        $resourcesUsed = $request->input('resources_used') ?: [];

        $sanitationFacilityTask->update(array_merge($validatedData, [
            // updated_by يتم تعيينه في booted method في الموديل
            'before_images' => $beforeImagePaths,
            'after_images' => $afterImagePaths,
            'resources_used' => $resourcesUsed,
        ]));

        // تحديث الموظفين والتقييمات: حذف الكل وإعادة الإضافة لضمان التوافق مع Repeater
        $sanitationFacilityTask->employeeTasks()->delete();
        if ($request->has('employeeTasks')) {
            foreach ($request->input('employeeTasks') as $employeeTaskData) {
                $sanitationFacilityTask->employeeTasks()->create([
                    'employee_id' => $employeeTaskData['employee_id'],
                    'employee_rating' => $employeeTaskData['employee_rating'],
                ]);
            }
        }

        return redirect()->route('sanitation-facility-tasks.index')->with('success', 'تم تحديث مهمة المنشآت الصحية بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SanitationFacilityTask $sanitationFacilityTask)
    {
        // حذف الصور المرتبطة بالمهمة قبل حذف المهمة نفسها
        foreach ($sanitationFacilityTask->before_images ?: [] as $path) {
            Storage::disk('public')->delete($path);
        }
        foreach ($sanitationFacilityTask->after_images ?: [] as $path) {
            Storage::disk('public')->delete($path);
        }

        $sanitationFacilityTask->delete();

        return redirect()->route('sanitation-facility-tasks.index')->with('success', 'تم حذف مهمة المنشآت الصحية بنجاح!');
    }

    /**
     * Define validation rules.
     */
    private function rules()
    {
        return [
            'date' => 'required|date',
            'shift' => 'required|in:صباحي,مسائي,ليلي',
            'task_type' => 'required|in:إدامة,صيانة',
            'facility_name' => 'required|string|max:255',
            'details' => 'required|string|max:1000',
            'status' => 'required|in:مكتمل,قيد التنفيذ,ملغى',
            'notes' => 'nullable|string|max:1000',
            'related_goal_id' => 'required|exists:unit_goals,id',
            'progress' => 'nullable|numeric|min:0|max:100',
            'result_value' => 'nullable|numeric',
            'resources_used' => 'nullable|array',
            'resources_used.*.name' => 'required_with:resources_used|string|max:255',
            'resources_used.*.quantity' => 'required_with:resources_used|numeric|min:0',
            'resources_used.*.unit' => 'required_with:resources_used|string|max:255',
            'before_images' => 'nullable|array',
            'before_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB
            'after_images' => 'nullable|array',
            'after_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB
            'seats_count' => 'nullable|integer|min:0',
            'sinks_count' => 'nullable|integer|min:0',
            'mixers_count' => 'nullable|integer|min:0',
            'mirrors_count' => 'nullable|integer|min:0',
            'doors_count' => 'nullable|integer|min:0',
            'toilets_count' => 'nullable|integer|min:0',
            'working_hours' => 'required|numeric|min:0|max:24',
            'employeeTasks' => 'nullable|array',
            'employeeTasks.*.employee_id' => 'required_with:employeeTasks|exists:employees,id',
            'employeeTasks.*.employee_rating' => 'required_with:employeeTasks|integer|min:1|max:5',
        ];
    }
}
