<?php

namespace App\Http\Controllers;

use App\Models\SanitationFacilityTask;
use App\Models\Unit;
use App\Models\UnitGoal;
use App\Models\Employee;
use App\Models\EmployeeTask;
use App\Models\User;
use App\Notifications\TaskUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SanitationFacilityTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ✅ حمل علاقة imageReport لجلب الصور المرتبطة
        $query = SanitationFacilityTask::with(['creator', 'editor', 'relatedGoal', 'unit', 'employeeTasks.employee', 'imageReport']);

        // فلاتر البحث العام
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

        // الفرز
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');
        if (in_array($sortBy, ['date', 'facility_name', 'task_type', 'shift', 'status', 'working_hours'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('date', 'desc');
        }

        $tasks = $query->paginate(10);

        $employees = Employee::orderBy('name')->get();
        $goals = UnitGoal::all();

        return view('sanitation_facility_tasks.index', compact('tasks', 'employees', 'goals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::all();
        $goals = UnitGoal::all();
        $employees = Employee::orderBy('name')->get();

        return view('sanitation_facility_tasks.create', compact('units', 'goals', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('SanitationFacilityTaskController@store - Request Data:', $request->all());

        try {
            $validatedData = $request->validate($this->rules());
        } catch (ValidationException $e) {
            Log::error('SanitationFacilityTaskController@store - Validation Error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // ✅ قم بتحميل الصور أولاً للحصول على المسارات
        $beforeImagePaths = [];
        if ($request->hasFile('before_images')) {
            foreach ($request->file('before_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/before', 'public');
                $beforeImagePaths[] = $path;
            }
        }

        $afterImagePaths = [];
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/after', 'public');
                $afterImagePaths[] = $path;
            }
        }

        $resourcesUsed = $request->input('resources_used') ?: [];

        // ✅ لا تقم بتمرير 'before_images' و 'after_images' مباشرة إلى create
        // قم بإنشاء المهمة أولاً
        $task = SanitationFacilityTask::create(array_merge($validatedData, [
            'resources_used' => $resourcesUsed,
        ]));

        // ✅ الآن، قم بتعيين مسارات الصور للنموذج لتتم معالجتها بواسطة booted()
        // هذا هو الجزء الذي يربط Request -> Model -> TaskImageReport
        $task->before_images = $beforeImagePaths;
        $task->after_images = $afterImagePaths;
        // قم بحفظ النموذج مرة أخرى لتشغيل هوك `updated`، أو يمكنك استدعاء handleTaskImageReport مباشرة هنا
        // لكن الأفضل هو الاعتماد على هوكات النموذج لتجنب تكرار الكود
        $task->save(); // تشغيل هوك `updated` بعد حفظ العلاقات


        if ($request->has('employeeTasks')) {
            foreach ($request->input('employeeTasks') as $employeeTaskData) {
                if (isset($employeeTaskData['employee_id']) && isset($employeeTaskData['employee_rating'])) {
                    Log::info('SanitationFacilityTaskController@store - Employee Task Data:', $employeeTaskData);
                    $task->employeeTasks()->create([
                        'employee_id' => $employeeTaskData['employee_id'],
                        'employee_rating' => $employeeTaskData['employee_rating'],
                    ]);

                    $assignedUser = User::where('employee_id', $employeeTaskData['employee_id'])->first();
                    if ($assignedUser) {
                        $assignedUser->notify(new TaskUpdatedNotification($task, 'assigned', 'تم تعيين مهمة جديدة لك: ' . $task->facility_name . ' - ' . $task->task_type));
                    }
                }
            }
        }

        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })->get();

        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new TaskUpdatedNotification($task, 'created'));
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

        // ✅ تأكد من تحميل علاقة imageReport هنا أيضًا
        $sanitationFacilityTask->load(['employeeTasks', 'imageReport']);

        return view('sanitation_facility_tasks.edit', compact('sanitationFacilityTask', 'units', 'goals', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SanitationFacilityTask $sanitationFacilityTask)
    {
        Log::info('SanitationFacilityTaskController@update - Request Data:', $request->all());

        try {
            $validatedData = $request->validate($this->rules());
        } catch (ValidationException $e) {
            Log::error('SanitationFacilityTaskController@update - Validation Error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $oldBeforeImagePaths = optional($sanitationFacilityTask->imageReport)->before_images ?? [];
        $oldAfterImagePaths = optional($sanitationFacilityTask->imageReport)->after_images ?? [];

        $newBeforeImagePaths = $oldBeforeImagePaths;
        $newAfterImagePaths = $oldAfterImagePaths;

        // معالجة صور ما قبل التنفيذ
        if ($request->hasFile('before_images')) {
            // حذف الصور القديمة المرتبطة بالتقرير
            foreach ($oldBeforeImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $newBeforeImagePaths = []; // إعادة تعيين المسارات للجديدة
            foreach ($request->file('before_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/before', 'public');
                $newBeforeImagePaths[] = $path;
            }
        } else if ($request->boolean('remove_before_images')) {
            foreach ($oldBeforeImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $newBeforeImagePaths = [];
        }

        // معالجة صور ما بعد التنفيذ
        if ($request->hasFile('after_images')) {
            // حذف الصور القديمة المرتبطة بالتقرير
            foreach ($oldAfterImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $newAfterImagePaths = []; // إعادة تعيين المسارات للجديدة
            foreach ($request->file('after_images') as $image) {
                $path = $image->store('sanitation_facility_tasks/after', 'public');
                $newAfterImagePaths[] = $path;
            }
        } else if ($request->boolean('remove_after_images')) {
            foreach ($oldAfterImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            $newAfterImagePaths = [];
        }

        $resourcesUsed = $request->input('resources_used') ?: [];

        // ✅ تحديث المهمة مع تعيين مسارات الصور الجديدة (للتمرير إلى النموذج فقط)
        $sanitationFacilityTask->update(array_merge($validatedData, [
            'resources_used' => $resourcesUsed,
            'before_images' => $newBeforeImagePaths, // ✅ ستُستخدم هذه الحقول بواسطة booted()
            'after_images' => $newAfterImagePaths,   // ✅ ستُستخدم هذه الحقول بواسطة booted()
        ]));

        // تحديث الموظفين والتقييمات: حذف الكل وإعادة الإضافة
        $oldAssignedEmployeeIds = $sanitationFacilityTask->employeeTasks->pluck('employee_id')->toArray();
        $sanitationFacilityTask->employeeTasks()->delete(); // حذف العلاقات القديمة
        $newAssignedEmployeeIds = [];

        if ($request->has('employeeTasks')) {
            foreach ($request->input('employeeTasks') as $employeeTaskData) {
                if (isset($employeeTaskData['employee_id']) && isset($employeeTaskData['employee_rating'])) {
                    Log::info('SanitationFacilityTaskController@update - Employee Task Data:', $employeeTaskData);
                    $sanitationFacilityTask->employeeTasks()->create([
                        'employee_id' => $employeeTaskData['employee_id'],
                        'employee_rating' => $employeeTaskData['employee_rating'],
                    ]);
                    $newAssignedEmployeeIds[] = $employeeTaskData['employee_id'];

                    // إرسال إشعار للموظف المعين
                    if (!in_array($employeeTaskData['employee_id'], $oldAssignedEmployeeIds)) {
                        $assignedUser = User::where('employee_id', $employeeTaskData['employee_id'])->first();
                        if ($assignedUser) {
                            $assignedUser->notify(new TaskUpdatedNotification($sanitationFacilityTask, 'assigned', 'تم تعيين مهمة جديدة لك أو تحديثها: ' . $sanitationFacilityTask->facility_name . ' - ' . $sanitationFacilityTask->task_type));
                        }
                    }
                }
            }
        }

        // إرسال إشعار للمشرفين بعد تحديث المهمة
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })->get();

        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new TaskUpdatedNotification($sanitationFacilityTask, 'updated'));
        }

        return redirect()->route('sanitation-facility-tasks.index')->with('success', 'تم تحديث مهمة المنشآت الصحية بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SanitationFacilityTask $sanitationFacilityTask)
    {
        // ✅ دالة cleanupTaskImages في النموذج ستهتم بحذف الصور
        // لذا لا داعي لحذفها يدويًا هنا.
        // فقط تأكد أن علاقة imageReport محملة إذا كنت بحاجة لـ $sanitationFacilityTask->imageReport
        // لكن النموذج سيتولى ذلك من خلال hook::deleted
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
            'before_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB لكل صورة
            'after_images' => 'nullable|array',
            'after_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB لكل صورة
            'remove_before_images' => 'nullable|boolean',
            'remove_after_images' => 'nullable|boolean',
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