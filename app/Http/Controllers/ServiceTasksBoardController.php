<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceTask;
use App\Models\Employee;
use App\Models\User; // استيراد نموذج المستخدم لإرسال الإشعارات
use App\Notifications\TaskUpdatedNotification; // استيراد الإشعار المخصص
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // استيراد Log للتحقق

class ServiceTasksBoardController extends Controller
{
    /**
     * Display the Kanban board for service tasks.
     * عرض لوحة كانبان لمهام الشُعبة الخدمية.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع المهام مع علاقة assignedTo
        $tasks = ServiceTask::with('assignedTo')->orderBy('order_column', 'asc')->get();

        // تجميع المهام حسب الحالة
        $groupedTasks = $tasks->groupBy('status');

        // تعريف الأعمدة كما هو في Filament
        $columns = [
            'pending' => 'معلقة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            'rejected' => 'مرفوضة',
        ];

        // جلب الموظفين لملء قائمة الاختيار في النماذج
        $employees = Employee::orderBy('name')->pluck('name', 'id');

        // تمرير البيانات إلى واجهة العرض
        return view('kanban.service-tasks-board', [
            'columns' => $columns,
            'groupedTasks' => $groupedTasks,
            'statuses' => ServiceTask::STATUSES,
            'units' => ServiceTask::UNITS,
            'priorities' => ServiceTask::PRIORITIES,
            'employees' => $employees,
        ]);
    }

    /**
     * Handle drag-and-drop status update for a task.
     * معالجة تحديث حالة المهمة عبر السحب والإفلات.
     *
     * @param Request $request
     * @param ServiceTask $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusAndOrder(Request $request, ServiceTask $task)
    {
        // Log the raw request body for debugging
        Log::info('Raw Request Body for updateStatusAndOrder:', [$request->getContent()]);
        // Validate the request data from the JSON body
        $validator = Validator::make($request->json()->all(), [
            'newStatus' => ['required', 'string', Rule::in(array_keys(ServiceTask::STATUSES))],
            'newOrder' => ['required', 'array'],
            'newOrder.*' => ['integer'], // Ensure each ID in the array is an integer
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for updateStatusAndOrder:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $newStatus = $request->json('newStatus');
        $newOrder = $request->json('newOrder'); // Array of task IDs in the new order

        try {
            // حفظ الحالة القديمة للمهمة قبل التحديث
            $oldStatus = $task->status;

            // Update task status
            $task->status = $newStatus;
            $task->save();

            // Update order_column for the new order within the column
            foreach ($newOrder as $index => $taskId) {
                // Use update() directly to avoid unnecessary model hydration for each task
                // Also, ensure we only update tasks within the new status column if that's the intent
                ServiceTask::where('id', $taskId)
                           ->where('status', $newStatus) // Crucial: only update tasks in the target column
                           ->update(['order_column' => $index]);
            }
            
            // Reload the task with its assignedTo relation and append accessors
            // This ensures the frontend receives all necessary computed properties
            $task->load('assignedTo');
            // Dynamically append accessors, assuming they are defined in the ServiceTask model
            $task->append([
                'assigned_to_name', 
                'formatted_due_date', 
                'status_label', 
                'priority_label', 
                'priority_color',
                'unit_icon',      // NEW: Append unit icon accessor
                'priority_icon'   // NEW: Append priority icon accessor
            ]);

            // إرسال إشعار للمشرفين بعد تحديث المهمة
            $supervisors = User::whereHas('roles', function ($query) {
                $query->where('name', 'supervisor'); // افترض أن لديك دور 'supervisor'
            })->get();

            foreach ($supervisors as $supervisor) {
                // يمكنك تخصيص رسالة الإشعار هنا بناءً على تغيير الحالة
                $actionMessage = 'تم تحديث حالة المهمة "' . $task->title . '" من "' . ServiceTask::STATUSES[$oldStatus] . '" إلى "' . ServiceTask::STATUSES[$newStatus] . '".';
                $supervisor->notify(new TaskUpdatedNotification($task, 'updated', $actionMessage));
            }

            // إرسال إشعار للموظف المعين إذا تغيرت حالة المهمة
            if ($task->assignedTo) {
                $assignedUser = User::where('employee_id', $task->assignedTo->id)->first();
                if ($assignedUser) {
                    $assignedUser->notify(new TaskUpdatedNotification($task, 'status_changed', 'تم تحديث حالة مهمتك "' . $task->title . '" إلى: ' . ServiceTask::STATUSES[$newStatus]));
                }
            }


            return response()->json(['success' => true, 'message' => 'تم تحديث حالة المهمة والترتيب بنجاح.', 'task' => $task->toArray()]);
        } catch (\Exception $e) {
            Log::error('Failed to update task status and order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'فشل تحديث المهمة: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created task.
     * تخزين مهمة جديدة تم إنشاؤها.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Log the raw request body for debugging
        Log::info('Raw Request Body for store:', [$request->getContent()]);
        $validator = Validator::make($request->json()->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => ['required', 'string', Rule::in(array_keys(ServiceTask::UNITS))],
            'priority' => ['required', 'string', Rule::in(array_keys(ServiceTask::PRIORITIES))],
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for store:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $task = ServiceTask::create($validator->validated());
            $task->load('assignedTo'); // Reload the relation to get assignedTo_name
            // Dynamically append accessors
            $task->append([
                'assigned_to_name', 
                'formatted_due_date', 
                'status_label', 
                'priority_label', 
                'priority_color',
                'unit_icon',      // NEW: Append unit icon accessor
                'priority_icon'   // NEW: Append priority icon accessor
            ]);

            // إرسال إشعار للمشرفين بعد إنشاء المهمة
            $supervisors = User::whereHas('roles', function ($query) {
                $query->where('name', 'supervisor');
            })->get();

            foreach ($supervisors as $supervisor) {
                $supervisor->notify(new TaskUpdatedNotification($task, 'created'));
            }

            // إرسال إشعار للموظف المعين (إذا كان لديه حساب مستخدم)
            if ($task->assignedTo) {
                $assignedUser = User::where('employee_id', $task->assignedTo->id)->first();
                if ($assignedUser) {
                    $assignedUser->notify(new TaskUpdatedNotification($task, 'assigned', 'تم تعيين مهمة جديدة لك: ' . $task->title . ' (الرجاء المراجعة)'));
                }
            }

            // Return the new task data including accessors for frontend rendering
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المهمة بنجاح.',
                'task' => $task->toArray(), 
                // 'taskHtml' is not needed if the frontend creates the element from 'task' object
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create task: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'فشل إنشاء المهمة: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified task.
     * تحديث المهمة المحددة.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ServiceTask $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ServiceTask $task)
    {
        // Log the raw request body for debugging
        Log::info('Raw Request Body for update:', [$request->getContent()]);
        $validator = Validator::make($request->json()->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => ['required', 'string', Rule::in(array_keys(ServiceTask::UNITS))],
            'status' => ['required', 'string', Rule::in(array_keys(ServiceTask::STATUSES))],
            'priority' => ['required', 'string', Rule::in(array_keys(ServiceTask::PRIORITIES))],
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for update:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $oldAssignedTo = $task->assigned_to; // حفظ الموظف المعين القديم

            $task->update($validator->validated());
            $task->load('assignedTo'); // Reload the relation to get assignedTo_name
            // Dynamically append accessors
            $task->append([
                'assigned_to_name', 
                'formatted_due_date', 
                'status_label', 
                'priority_label', 
                'priority_color',
                'unit_icon',      // NEW: Append unit icon accessor
                'priority_icon'   // NEW: Append priority icon accessor
            ]);

            // إرسال إشعار للمشرفين بعد تحديث المهمة
            $supervisors = User::whereHas('roles', function ($query) {
                $query->where('name', 'supervisor');
            })->get();

            foreach ($supervisors as $supervisor) {
                $supervisor->notify(new TaskUpdatedNotification($task, 'updated'));
            }

            // إرسال إشعار للموظف المعين إذا تغير التعيين أو تم تحديث المهمة
            if ($task->assignedTo) {
                $assignedUser = User::where('employee_id', $task->assignedTo->id)->first();
                if ($assignedUser) {
                    if ($task->assigned_to != $oldAssignedTo) {
                        // إذا تغير التعيين
                        $assignedUser->notify(new TaskUpdatedNotification($task, 'assigned', 'تم تعيين مهمة جديدة لك أو تغيير تعيينها: ' . $task->title . ' (الرجاء المراجعة)'));
                    } else {
                        // إذا تم تحديث المهمة ولكن التعيين لم يتغير
                        $assignedUser->notify(new TaskUpdatedNotification($task, 'updated', 'تم تحديث تفاصيل مهمتك: ' . $task->title));
                    }
                }
            }


            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المهمة بنجاح.',
                'task' => $task->toArray(),
                // 'taskHtml' is not needed if the frontend creates the element from 'task' object
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update task: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'فشل تحديث المهمة: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified task from storage.
     * حذف المهمة المحددة من التخزين.
     *
     * @param \App\Models\ServiceTask $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ServiceTask $task)
    {
        try {
            $task->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف المهمة بنجاح.']);
        } catch (\Exception $e) {
            Log::error('Failed to delete task: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'فشل حذف المهمة: ' . $e->getMessage()], 500);
        }
    }
}
