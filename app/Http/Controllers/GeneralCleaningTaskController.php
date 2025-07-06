<?php

namespace App\Http\Controllers;

use App\Models\GeneralCleaningTask;
use App\Models\Unit; // افتراض أن لديك نموذج Unit
use App\Models\UnitGoal; // افتراض أن لديك نموذج UnitGoal
use App\Models\Employee; // افتراض أن لديك نموذج Employee
use App\Models\User; // استيراد نموذج المستخدم لإرسال الإشعارات
use App\Notifications\TaskUpdatedNotification; // استيراد الإشعار المخصص
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // لاستخدام تخزين الملفات
use Illuminate\Support\Facades\Log; // لاستخدام Log للتحقق

class GeneralCleaningTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = GeneralCleaningTask::with(['creator', 'editor', 'relatedGoal', 'unit', 'employeeTasks.employee']);

        // مثال بسيط للبحث:
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('location', 'like', '%' . $search . '%')
                    ->orWhere('task_type', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('relatedGoal', function ($sq) use ($search) {
                        $sq->where('goal_text', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('creator', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // مثال بسيط للفرز (يمكنك توسيع هذا ليشمل أعمدة مختلفة)
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order');
            if (in_array($sortBy, ['date', 'status', 'location'])) { // أمثلة على أعمدة يمكن الفرز بها
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('date', 'desc'); // الترتيب الافتراضي
        }

        $tasks = $query->paginate(10); // 10 عناصر في كل صفحة

        return view('general_cleaning_tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::all();
        $goals = UnitGoal::all();
        $employees = Employee::orderBy('name')->get(); // لجلب الموظفين لنموذج المنفذين

        return view('general_cleaning_tasks.create', compact('units', 'goals', 'employees'));
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
                $path = $image->store('general_cleaning_tasks/before', 'public');
                $beforeImagePaths[] = $path;
            }
        }

        // معالجة صور ما بعد التنفيذ
        $afterImagePaths = [];
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $image) {
                $path = $image->store('general_cleaning_tasks/after', 'public');
                $afterImagePaths[] = $path;
            }
        }

        // معالجة الموارد المستخدمة
        $resourcesUsed = $request->input('resources_used') ?: [];

        $task = GeneralCleaningTask::create(array_merge($validatedData, [
            'created_by' => Auth::id(),
            'unit_id' => Unit::where('name', 'وحدة النظافة العامة')->first()?->id, // قد تحتاج لتعديل هذا بناءً على منطق عملك
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

                // إرسال إشعار للموظف المعين (إذا كان لديه حساب مستخدم)
                $assignedUser = User::where('employee_id', $employeeTaskData['employee_id'])->first();
                if ($assignedUser) {
                    $assignedUser->notify(new TaskUpdatedNotification($task, 'assigned', 'تم تعيين مهمة جديدة لك: ' . $task->location . ' - ' . $task->task_type));
                }
            }
        }

        // إرسال إشعار للمشرفين بعد إنشاء المهمة
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor'); // افترض أن لديك دور 'supervisor'
        })->get();

        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new TaskUpdatedNotification($task, 'created'));
        }

        return redirect()->route('general-cleaning-tasks.index')->with('success', 'تم إنشاء مهمة النظافة بنجاح!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneralCleaningTask $generalCleaningTask)
    {
        $units = Unit::all();
        $goals = UnitGoal::all();
        $employees = Employee::orderBy('name')->get();

        // تحميل علاقة employeeTasks مسبقاً إذا لم تكن موجودة
        $generalCleaningTask->load('employeeTasks');

        return view('general_cleaning_tasks.edit', compact('generalCleaningTask', 'units', 'goals', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneralCleaningTask $generalCleaningTask)
    {
        $validatedData = $request->validate($this->rules());

        // معالجة صور ما قبل التنفيذ
        $beforeImagePaths = $generalCleaningTask->before_images ?: [];
        if ($request->hasFile('before_images')) {
            // حذف الصور القديمة إذا كنت لا تريد الاحتفاظ بها
            foreach ($beforeImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $beforeImagePaths = []; // إعادة تعيين المسارات
            foreach ($request->file('before_images') as $image) {
                $path = $image->store('general_cleaning_tasks/before', 'public');
                $beforeImagePaths[] = $path;
            }
        } else if ($request->input('remove_before_images') == '1') { // إذا أرسل المستخدم طلب حذف كل الصور
            foreach ($beforeImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $beforeImagePaths = [];
        }


        // معالجة صور ما بعد التنفيذ
        $afterImagePaths = $generalCleaningTask->after_images ?: [];
        if ($request->hasFile('after_images')) {
            // حذف الصور القديمة
            foreach ($afterImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $afterImagePaths = [];
            foreach ($request->file('after_images') as $image) {
                $path = $image->store('general_cleaning_tasks/after', 'public');
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

        $generalCleaningTask->update(array_merge($validatedData, [
            'edited_by' => Auth::id(), // تسجيل المستخدم الذي قام بالتعديل
            'before_images' => $beforeImagePaths,
            'after_images' => $afterImagePaths,
            'resources_used' => $resourcesUsed,
        ]));

        // تحديث الموظفين والتقييمات
        // الحصول على قائمة معرفات الموظفين المعينين حاليًا قبل الحذف
        $oldAssignedEmployeeIds = $generalCleaningTask->employeeTasks->pluck('employee_id')->toArray();
        $generalCleaningTask->employeeTasks()->delete(); // حذف الكل وإعادة الإضافة
        $newAssignedEmployeeIds = [];

        if ($request->has('employeeTasks')) {
            foreach ($request->input('employeeTasks') as $employeeTaskData) {
                $generalCleaningTask->employeeTasks()->create([
                    'employee_id' => $employeeTaskData['employee_id'],
                    'employee_rating' => $employeeTaskData['employee_rating'],
                ]);
                $newAssignedEmployeeIds[] = $employeeTaskData['employee_id'];

                // إرسال إشعار للموظف المعين (إذا كان لديه حساب مستخدم)
                // إذا كان الموظف جديدًا على المهمة أو تم تحديث المهمة
                if (!in_array($employeeTaskData['employee_id'], $oldAssignedEmployeeIds)) {
                    $assignedUser = User::where('employee_id', $employeeTaskData['employee_id'])->first();
                    if ($assignedUser) {
                        $assignedUser->notify(new TaskUpdatedNotification($generalCleaningTask, 'assigned', 'تم تعيين مهمة جديدة لك أو تحديثها: ' . $generalCleaningTask->location . ' - ' . $generalCleaningTask->task_type));
                    }
                }
            }
        }
        
        // إرسال إشعار للمشرفين بعد تحديث المهمة
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })->get();

        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new TaskUpdatedNotification($generalCleaningTask, 'updated'));
        }

        return redirect()->route('general-cleaning-tasks.index')->with('success', 'تم تحديث مهمة النظافة بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralCleaningTask $generalCleaningTask)
    {
        // حذف الصور المرتبطة بالمهمة قبل حذف المهمة نفسها
        foreach ($generalCleaningTask->before_images ?: [] as $path) {
            Storage::disk('public')->delete($path);
        }
        foreach ($generalCleaningTask->after_images ?: [] as $path) {
            Storage::disk('public')->delete($path);
        }

        $generalCleaningTask->delete();

        return redirect()->route('general-cleaning-tasks.index')->with('success', 'تم حذف مهمة النظافة بنجاح!');
    }

    /**
     * Define validation rules.
     */
    private function rules()
    {
        return [
            'date' => 'required|date',
            'shift' => 'required|in:صباحي,مسائي,ليلي',
            'status' => 'required|in:مكتمل,قيد التنفيذ,ملغى',
            'related_goal_id' => 'required|exists:unit_goals,id',
            'task_type' => 'required|in:إدامة,صيانة',
            'location' => 'required|string|max:255',
            'mats_count' => 'nullable|integer|min:0',
            'pillows_count' => 'nullable|integer|min:0',
            'fans_count' => 'nullable|integer|min:0',
            'windows_count' => 'nullable|integer|min:0',
            'carpets_count' => 'nullable|integer|min:0',
            'blankets_count' => 'nullable|integer|min:0',
            'beds_count' => 'nullable|integer|min:0',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'filled_trams_count' => 'nullable|integer|min:0',
            'carpets_laid_count' => 'nullable|integer|min:0',
            'large_containers_count' => 'nullable|integer|min:0',
            'small_containers_count' => 'nullable|integer|min:0',
            'external_partitions_count' => 'nullable|integer|min:0',
            'maintenance_details' => 'nullable|string|max:1000',
            'working_hours' => 'required|numeric|min:0|max:24',
            'notes' => 'nullable|string|max:1000',
            'resources_used' => 'nullable|array',
            'resources_used.*.name' => 'required_with:resources_used|string|max:255',
            'resources_used.*.quantity' => 'required_with:resources_used|numeric|min:0',
            'resources_used.*.unit' => 'required_with:resources_used|string|max:255',
            'employeeTasks' => 'nullable|array',
            'employeeTasks.*.employee_id' => 'required_with:employeeTasks|exists:employees,id',
            'employeeTasks.*.employee_rating' => 'required_with:employeeTasks|integer|min:1|max:5',
            'before_images' => 'nullable|array',
            'before_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB
            'after_images' => 'nullable|array',
            'after_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB
        ];
    }
}
