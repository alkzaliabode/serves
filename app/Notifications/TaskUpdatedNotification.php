<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceTask;

class TaskUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $action; // 'created', 'updated', 'assigned', 'status_changed'
    public $customMessage; // لإضافة رسالة مخصصة عند الحاجة

    /**
     * إنشاء مثيل إشعار جديد.
     *
     * @param  \App\Models\ServiceTask  $task
     * @param  string  $action
     * @param  string|null  $customMessage
     * @return void
     */
    public function __construct(ServiceTask $task, string $action, string $customMessage = null)
    {
        $this->task = $task;
        $this->action = $action;
        $this->customMessage = $customMessage;
    }

    /**
     * الحصول على قنوات تسليم الإشعار.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * الحصول على تمثيل الإشعار لقاعدة البيانات.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        // بناء رسالة افتراضية أكثر تفصيلاً باستخدام Accessors من ServiceTask
        $defaultMessage = '';
        switch ($this->action) {
            case 'created':
                $defaultMessage = 'تم إنشاء مهمة جديدة: ' . $this->task->title . ' (الوحدة: ' . $this->task->unit_label . '، الأولوية: ' . $this->task->priority_label . '، الموعد النهائي: ' . $this->task->formatted_due_date . ')';
                break;
            case 'updated':
                $defaultMessage = 'تم تحديث المهمة: ' . $this->task->title . ' (الحالة: ' . $this->task->status_label . '، الأولوية: ' . $this->task->priority_label . ')';
                break;
            case 'assigned':
                $defaultMessage = 'تم تعيين مهمة جديدة لك: ' . $this->task->title . ' (الوحدة: ' . $this->task->unit_label . '، الأولوية: ' . $this->task->priority_label . '، الموعد النهائي: ' . $this->task->formatted_due_date . ')';
                break;
            case 'status_changed':
                $defaultMessage = 'تم تحديث حالة مهمتك: ' . $this->task->title . ' إلى "' . $this->task->status_label . '".';
                break;
            default:
                $defaultMessage = 'تحديث مهمة: ' . $this->task->title;
                break;
        }

        $message = $this->customMessage ?: $defaultMessage;

        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'action' => $this->action,
            'message' => $message,
            'link' => route('service-tasks.board.index', ['task' => $this->task->id]),
        ];
    }

    /**
     * الحصول على تمثيل الإشعار للبث.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBroadcast($notifiable)
    {
        // بناء رسالة افتراضية أكثر تفصيلاً باستخدام Accessors من ServiceTask
        $defaultMessage = '';
        switch ($this->action) {
            case 'created':
                $defaultMessage = 'مهمة جديدة: ' . $this->task->title . ' (الوحدة: ' . $this->task->unit_label . '، الأولوية: ' . $this->task->priority_label . '، الموعد النهائي: ' . $this->task->formatted_due_date . ')';
                break;
            case 'updated':
                $defaultMessage = 'تحديث مهمة: ' . $this->task->title . ' (الحالة: ' . $this->task->status_label . '، الأولوية: ' . $this->task->priority_label . ')';
                break;
            case 'assigned':
                $defaultMessage = 'تم تعيين مهمة جديدة لك: ' . $this->task->title . ' (الوحدة: ' . $this->task->unit_label . '، الأولوية: ' . $this->task->priority_label . '، الموعد النهائي: ' . $this->task->formatted_due_date . ')';
                break;
            case 'status_changed':
                $defaultMessage = 'تم تحديث حالة مهمتك: ' . $this->task->title . ' إلى "' . $this->task->status_label . '".';
                break;
            default:
                $defaultMessage = 'تحديث مهمة: ' . $this->task->title;
                break;
        }
        
        $message = $this->customMessage ?: $defaultMessage;

        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'action' => $this->action,
            'message' => $message,
            'link' => route('service-tasks.board.index', ['task' => $this->task->id]),
        ];
    }

    /**
     * الحصول على اسم حدث البث.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'task.updated';
    }
}
