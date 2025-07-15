<?php

namespace App\Http\Controllers;

use App\Models\GeneralCleaningTask;
use App\Models\Unit;        // افتراض أن لديك نموذج Unit
use App\Models\UnitGoal;    // افتراض أن لديك نموذج UnitGoal
use App\Models\Employee;    // افتراض أن لديك نموذج Employee
use App\Models\User;        // استيراد نموذج المستخدم لإرسال الإشعارات
use App\Models\EmployeeTask; // افتراض أن لديك نموذج EmployeeTask للجدول المحوري
use App\Notifications\TaskUpdatedNotification; // استيراد الإشعار المخصص
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // لاستخدام تخزين الملفات
use Illuminate\Support\Facades\Log;     // لاستخدام Log للتحقق
use Illuminate\Validation\Rule; // لاستخدام قواعد تحقق متقدمة

class GeneralCleaningTaskController extends Controller
{
    /**
     * قم بتهيئة المتحكم بسياسات الأذونات.
     */
    // public function __construct()
    // {
    //     $this->authorizeResource(GeneralCleaningTask::class, 'general_cleaning_task');
    // }

    /**
     * عرض قائمة بالموارد.
     */
    public function index(Request $request)
    {
        $query = GeneralCleaningTask::with(['creator', 'editor', 'relatedGoal', 'unit', 'employeeTasks.employee']);

        // 1. البحث العام
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('location', 'like', '%' . $searchTerm . '%')
                  ->orWhere('task_type', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%')
                  ->orWhere('notes', 'like', '%' . $searchTerm . '%')
                  ->orWhere('maintenance_details', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('relatedGoal', function ($sq) use ($searchTerm) {
                      $sq->where('goal_text', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('creator', function ($sq) use ($searchTerm) {
                      $sq->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('employeeTasks.employee', function ($sq) use ($searchTerm) {
                      $sq->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 2. فلاتر التاريخ
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->input('end_date'));
        }

        // 3. فلاتر محددة (للقوائم المنسدلة)
        if ($request->filled('shift')) {
            $query->where('shift', $request->input('shift'));
        }
        if ($request->filled('location')) {
            $query->where('location', $request->input('location'));
        }
        if ($request->filled('task_type')) {
            $query->where('task_type', $request->input('task_type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('related_goal_id')) {
            $query->where('goal_id', $request->input('related_goal_id'));
        }
        if ($request->filled('employee_id')) {
            $employeeId = $request->input('employee_id');
            $query->whereHas('employeeTasks', function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            });
        }
        if ($request->filled('creator_id')) {
            $query->where('created_by', $request->input('creator_id'));
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }

        // 4. الفرز
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');

        // قائمة بيضاء لأعمدة الفرز الآمنة
        $allowedSortColumns = ['date', 'status', 'location', 'task_type', 'working_hours', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('date', 'desc'); // الترتيب الافتراضي إذا كان الفرز غير صالح
        }

        $tasks = $query->paginate(10); // 10 عناصر في كل صفحة

        // جلب البيانات للقوائم المنسدلة (Dropdowns) لنموذج الفلترة
        $uniqueLocations = GeneralCleaningTask::select('location')->distinct()->pluck('location');
        $goals = UnitGoal::all(); // افتراض أن UnitGoal يمثل الأهداف
        $employees = Employee::orderBy('name')->get();
        // جلب المستخدمين الذين أنشأوا مهام نظافة
        $creators = User::whereHas('createdGeneralCleaningTasks')->distinct()->get();
        $units = Unit::all(); // جلب الوحدات إذا كانت تستخدم للفلترة

        return view('general_cleaning_tasks.index', compact('tasks', 'uniqueLocations', 'goals', 'employees', 'creators', 'units'));
    }

    /**
     * عرض النموذج لإنشاء مورد جديد.
     */
    public function create()
    {
        // $this->authorize('create', GeneralCleaningTask::class); // يتم تغطيتها بـ authorizeResource

        $units = Unit::all();
        $goals = UnitGoal::all();
        $employees = Employee::orderBy('name')->get();

        // ✅ جلب ID لوحدة "النظافة العامة"
        $generalCleaningUnit = Unit::where('name', 'النظافة العامة')->first();
        $generalCleaningUnitId = $generalCleaningUnit ? $generalCleaningUnit->id : null;

        return view('general_cleaning_tasks.create', compact('units', 'goals', 'employees', 'generalCleaningUnitId'));
    }

    /**
     * تخزين مورد تم إنشاؤه حديثًا في التخزين.
     */
    public function store(Request $request)
    {
        // $this->authorize('create', GeneralCleaningTask::class); // يتم تغطيتها بـ authorizeResource

        $validatedData = $request->validate($this->rules());

        try {
            // ✅ جلب ID لوحدة "النظافة العامة" تلقائيًا
            $generalCleaningUnit = Unit::where('name', 'النظافة العامة')->first();
            $unitId = $generalCleaningUnit ? $generalCleaningUnit->id : null;

            if (is_null($unitId)) {
                // يمكنك التعامل مع هذه الحالة إذا لم يتم العثور على وحدة "النظافة العامة"
                // مثلاً، رمي استثناء أو إعادة توجيه مع رسالة خطأ
                Log::error('Unit "النظافة العامة" not found for General Cleaning Task.');
                return redirect()->back()->with('error', 'حدث خطأ: لم يتم العثور على وحدة النظافة العامة.')->withInput();
            }

            // معالجة صور ما قبل التنفيذ
            $beforeImagePaths = $this->handleImageUpload($request, 'before_images', 'general_cleaning_tasks/before');

            // معالجة صور ما بعد التنفيذ
            $afterImagePaths = $this->handleImageUpload($request, 'after_images', 'general_cleaning_tasks/after');

            // معالجة الموارد المستخدمة
            $resourcesUsed = $request->input('resources_used') ?: [];

            $task = GeneralCleaningTask::create(array_merge($validatedData, [
                'created_by' => Auth::id(),
                'unit_id' => $unitId, // ✅ استخدام unit_id الذي تم جلبه تلقائيًا
                'before_images' => $beforeImagePaths,
                'after_images' => $afterImagePaths,
                'resources_used' => $resourcesUsed,
            ]));

            // حفظ الموظفين والتقييمات
            if ($request->has('employeeTasks') && is_array($request->input('employeeTasks'))) {
                foreach ($request->input('employeeTasks') as $employeeTaskData) {
                    if (isset($employeeTaskData['employee_id']) && isset($employeeTaskData['employee_rating'])) {
                        $task->employeeTasks()->create([
                            'employee_id' => $employeeTaskData['employee_id'],
                            'employee_rating' => $employeeTaskData['employee_rating'],
                        ]);

                        // إرسال إشعار للموظف المعين
                        $this->notifyAssignedEmployee($task, $employeeTaskData['employee_id'], 'assigned', 'تم تعيين مهمة جديدة لك: ' . $task->location . ' - ' . $task->task_type);
                    }
                }
            }

            // إرسال إشعار للمشرفين بعد إنشاء المهمة
            $this->notifySupervisors($task, 'created');

            return redirect()->route('general-cleaning-tasks.index')->with('success', 'تم إنشاء مهمة النظافة بنجاح!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error creating General Cleaning Task: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating General Cleaning Task: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المهمة. يرجى المحاولة مرة أخرى.')->withInput();
        }
    }

    /**
     * عرض النموذج لتعديل المورد المحدد.
     */
    public function edit(GeneralCleaningTask $generalCleaningTask)
    {
        // $this->authorize('update', $generalCleaningTask); // يتم تغطيتها بـ authorizeResource

        $units = Unit::all();
        $goals = UnitGoal::all();
        $employees = Employee::orderBy('name')->get();

        // تحميل علاقة employeeTasks مسبقاً إذا لم تكن موجودة
        $generalCleaningTask->load('employeeTasks');

        return view('general_cleaning_tasks.edit', compact('generalCleaningTask', 'units', 'goals', 'employees'));
    }

    /**
     * تحديث المورد المحدد في التخزين.
     */
    public function update(Request $request, GeneralCleaningTask $generalCleaningTask)
    {
        // $this->authorize('update', $generalCleaningTask); // يتم تغطيتها بـ authorizeResource

        // ✅ لا نتحقق من unit_id هنا لأنه لا يتغير في التحديثات
        $validatedData = $request->validate($this->rules($generalCleaningTask)); // تمرير المهمة للتحقق من القواعد

        try {
            // معالجة صور ما قبل التنفيذ
            $beforeImagePaths = $this->handleImageUpdate($request, $generalCleaningTask, 'before_images', 'general_cleaning_tasks/before');

            // معالجة صور ما بعد التنفيذ
            $afterImagePaths = $this->handleImageUpdate($request, $generalCleaningTask, 'after_images', 'general_cleaning_tasks/after');

            // معالجة الموارد المستخدمة
            $resourcesUsed = $request->input('resources_used') ?: [];

            $generalCleaningTask->update(array_merge($validatedData, [
                'updated_by' => Auth::id(), // تسجيل المستخدم الذي قام بالتعديل
                'before_images' => $beforeImagePaths,
                'after_images' => $afterImagePaths,
                'resources_used' => $resourcesUsed,
            ]));

            // تحديث الموظفين والتقييمات
            $this->syncEmployeeTasks($request, $generalCleaningTask);

            // إرسال إشعار للمشرفين بعد تحديث المهمة
            $this->notifySupervisors($generalCleaningTask, 'updated');

            return redirect()->route('general-cleaning-tasks.index')->with('success', 'تم تحديث مهمة النظافة بنجاح!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error updating General Cleaning Task: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating General Cleaning Task: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المهمة. يرجى المحاولة مرة أخرى.')->withInput();
        }
    }

    /**
     * إزالة المورد المحدد من التخزين.
     */
    public function destroy(GeneralCleaningTask $generalCleaningTask)
    {
        // $this->authorize('delete', $generalCleaningTask); // يتم تغطيتها بـ authorizeResource

        try {
            // حذف الصور المرتبطة بالمهمة قبل حذف المهمة نفسها
            $this->deleteTaskImages($generalCleaningTask);

            $generalCleaningTask->delete();

            return redirect()->route('general-cleaning-tasks.index')->with('success', 'تم حذف مهمة النظافة بنجاح!');
        } catch (\Exception $e) {
            Log::error('Error deleting General Cleaning Task: ' . $e->getMessage(), ['task_id' => $generalCleaningTask->id, 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المهمة. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * تعريف قواعد التحقق.
     */
    private function rules(?GeneralCleaningTask $task = null)
    {
        return [
            'date' => 'required|date',
            'shift' => ['required', Rule::in(['صباحي', 'مسائي', 'ليلي'])],
            'status' => ['required', Rule::in(['مكتمل', 'قيد التنفيذ', 'ملغى'])],
            // 'unit_id' => 'required|exists:units,id', // ✅ تم إزالة هذه القاعدة لأن unit_id يتم تعيينه تلقائيًا
            'related_goal_id' => 'required|exists:unit_goals,id',
            'task_type' => ['required', Rule::in(['إدامة', 'صيانة'])],
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
            'remove_before_images' => 'nullable|boolean', // حقل جديد للإشارة إلى حذف جميع الصور
            'remove_after_images' => 'nullable|boolean',   // حقل جديد للإشارة إلى حذف جميع الصور
        ];
    }

    /**
     * دالة مساعدة لمعالجة رفع الصور.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $fieldName
     * @param string $storagePath
     * @return array
     */
    private function handleImageUpload(Request $request, string $fieldName, string $storagePath): array
    {
        $imagePaths = [];
        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $image) {
                try {
                    $path = $image->store($storagePath, 'public');
                    $imagePaths[] = $path;
                } catch (\Exception $e) {
                    Log::error("Failed to upload image for field {$fieldName}: " . $e->getMessage());
                    // يمكنك هنا رمي استثناء أو تخطي هذه الصورة
                }
            }
        }
        return $imagePaths;
    }

    /**
     * دالة مساعدة لمعالجة تحديث الصور (حذف القديمة ورفع الجديدة).
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\GeneralCleaningTask $task
     * @param string $fieldName
     * @param string $storagePath
     * @return array
     */
    private function handleImageUpdate(Request $request, GeneralCleaningTask $task, string $fieldName, string $storagePath): array
    {
        $currentImagePaths = $task->{$fieldName} ?: [];
        $newImagePaths = [];

        // التحقق مما إذا كان المستخدم يريد إزالة جميع الصور
        if ($request->input('remove_' . $fieldName) == '1') {
            foreach ($currentImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            return []; // لا توجد صور بعد الآن
        }

        // إذا تم رفع ملفات جديدة، احذف القديمة وقم بتحميل الجديدة
        if ($request->hasFile($fieldName)) {
            foreach ($currentImagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            return $this->handleImageUpload($request, $fieldName, $storagePath);
        }

        // إذا لم يتم رفع ملفات جديدة ولم يطلب المستخدم الإزالة، احتفظ بالصور الحالية
        return $currentImagePaths;
    }

    /**
     * دالة مساعدة لمزامنة الموظفين والتقييمات.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\GeneralCleaningTask $task
     * @return void
     */
    private function syncEmployeeTasks(Request $request, GeneralCleaningTask $task): void
    {
        $oldAssignedEmployeeIds = $task->employeeTasks->pluck('employee_id')->toArray();
        $newAssignedEmployeeIds = [];

        // حذف الموظفين الذين لم يعودوا موجودين في الطلب
        $task->employeeTasks()->whereNotIn('employee_id', array_column($request->input('employeeTasks', []), 'employee_id'))
                             ->delete();

        if ($request->has('employeeTasks') && is_array($request->input('employeeTasks'))) {
            foreach ($request->input('employeeTasks') as $employeeTaskData) {
                if (isset($employeeTaskData['employee_id']) && isset($employeeTaskData['employee_rating'])) {
                    $newAssignedEmployeeIds[] = $employeeTaskData['employee_id'];

                    $task->employeeTasks()->updateOrCreate(
                        ['employee_id' => $employeeTaskData['employee_id']],
                        ['employee_rating' => $employeeTaskData['employee_rating']]
                    );

                    // إرسال إشعار للموظف المعين فقط إذا كان جديدًا على المهمة
                    if (!in_array($employeeTaskData['employee_id'], $oldAssignedEmployeeIds)) {
                        $this->notifyAssignedEmployee($task, $employeeTaskData['employee_id'], 'assigned', 'تم تعيين مهمة جديدة لك أو تحديثها: ' . $task->location . ' - ' . $task->task_type);
                    }
                }
            }
        }

        // إرسال إشعار للموظفين الذين تم إلغاء تعيينهم
        $unassignedEmployeeIds = array_diff($oldAssignedEmployeeIds, $newAssignedEmployeeIds);
        foreach ($unassignedEmployeeIds as $employeeId) {
            $this->notifyAssignedEmployee($task, $employeeId, 'unassigned', 'تم إلغاء تعيينك من المهمة: ' . $task->location . ' - ' . $task->task_type);
        }
    }

    /**
     * دالة مساعدة لإرسال إشعار للموظف المعين.
     *
     * @param \App\Models\GeneralCleaningTask $task
     * @param int $employeeId
     * @param string $type
     * @param string $message
     * @return void
     */
    private function notifyAssignedEmployee(GeneralCleaningTask $task, int $employeeId, string $type, string $message): void
    {
        $assignedUser = User::where('employee_id', $employeeId)->first();
        if ($assignedUser) {
            $assignedUser->notify(new TaskUpdatedNotification($task, $type, $message));
        } else {
            Log::warning("No user found for employee ID: {$employeeId} to send notification for task: {$task->id}");
        }
    }

    /**
     * دالة مساعدة لإرسال إشعارات للمشرفين.
     *
     * @param \App\Models\GeneralCleaningTask $task
     * @param string $type
     * @return void
     */
    private function notifySupervisors(GeneralCleaningTask $task, string $type): void
    {
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor'); // افترض أن لديك دور 'supervisor'
        })->get();

        if ($supervisors->isEmpty()) {
            Log::warning("No supervisors found to send notification for task: {$task->id}, type: {$type}");
        }

        foreach ($supervisors as $supervisor) {
            $supervisor->notify(new TaskUpdatedNotification($task, $type));
        }
    }

    /**
     * دالة مساعدة لحذف صور المهمة من التخزين.
     *
     * @param \App\Models\GeneralCleaningTask $task
     * @return void
     */
    private function deleteTaskImages(GeneralCleaningTask $task): void
    {
        foreach ($task->before_images ?: [] as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        foreach ($task->after_images ?: [] as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
