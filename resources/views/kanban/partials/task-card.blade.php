{{-- resources/views/kanban/partials/task-card.blade.php --}}
{{--
    هذا الملف هو جزئي (partial) يستخدم لعرض بطاقة مهمة واحدة.
    يتم استخدامه في العرض الرئيسي للوحة كانبان وعند إنشاء/تعديل مهمة عبر AJAX.
    يعتمد بشكل كبير على الـ Accessors المعرفة في موديل App\Models\ServiceTask
    لتوفير البيانات المنسقة مباشرة.
--}}

<div class="kanban-card"
     data-task-id="{{ $task->id }}"
     data-title="{{ $task->title }}"
     data-description="{{ $task->description ?? '' }}" {{-- التأكد من عدم وجود وصف فارغ --}}
     data-status="{{ $task->status }}"
     data-unit="{{ $task->unit }}"
     data-priority="{{ $task->priority }}"
     data-due-date="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" {{-- تنسيق تاريخ الاستحقاق لـ dataset --}}
     data-assigned-to="{{ $task->assigned_to ?? '' }}" {{-- استخدام ID الموظف المعين مباشرة --}}
     data-assigned-to-name="{{ $task->assigned_to_name ?? 'غير معين' }}" {{-- اسم الموظف المعين من accessor --}}
     data-order-column="{{ $task->order_column ?? 0 }}"
>
    <div class="card-title-text">{{ $task->title }}</div>
    @if ($task->description)
        <div class="card-description-text">{{ Str::limit($task->description, 100) }}</div>
    @endif

    <div class="card-attributes-wrapper mt-auto">
        {{-- عرض الوحدة باستخدام الـ accessor unit_icon و unit_label --}}
        <div class="card-attribute attribute-purple">
            <i class="{{ $task->unit_icon }} unit-icon"></i>
            <span>الوحدة: <span class="unit-text">{{ $task->unit_label }}</span></span>
        </div>
        
        {{-- عرض تاريخ الاستحقاق باستخدام الـ accessor formatted_due_date --}}
        <div class="card-attribute attribute-sky">
            <i class="fas fa-calendar-alt"></i> {{-- تم تغيير الأيقونة لتكون أكثر دقة --}}
            <span>تاريخ الاستحقاق: <span class="due-date">{{ $task->formatted_due_date }}</span></span>
        </div>
        
        {{-- عرض المسؤول باستخدام الـ accessor assigned_to_name --}}
        <div class="card-attribute attribute-indigo">
            <i class="fas fa-user-tie"></i> {{-- تم تغيير الأيقونة لتكون أكثر دقة --}}
            <span>المسؤول: <span class="assigned-to-name">{{ $task->assigned_to_name }}</span></span>
        </div>
        
        {{-- عرض الأولوية باستخدام الـ accessors priority_icon و priority_label و priority_color --}}
        <div class="card-attribute attribute-{{ $task->priority_color }}">
            <i class="{{ $task->priority_icon }} priority-icon"></i>
            <span>الأولوية: <span class="priority-text">{{ $task->priority_label }}</span></span>
        </div>
        
        {{-- عرض شارة الحالة باستخدام الـ accessor status_label --}}
        <div class="card-attribute">
            <span class="badge status-badge 
                @if($task->status == 'pending') bg-warning
                @elseif($task->status == 'in_progress') bg-info
                @elseif($task->status == 'completed') bg-success
                @elseif($task->status == 'rejected') bg-danger
                @endif
            ">{{ $task->status_label }}</span>
        </div>
    </div>

    <div class="card-actions">
        <button type="button" class="btn btn-edit btn-sm" data-bs-toggle="modal" data-bs-target="#editTaskModal">
            <i class="fas fa-edit"></i> تعديل
        </button>
    </div>
</div>
