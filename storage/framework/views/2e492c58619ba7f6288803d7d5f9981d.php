


<div class="kanban-card"
     data-task-id="<?php echo e($task->id); ?>"
     data-title="<?php echo e($task->title); ?>"
     data-description="<?php echo e($task->description ?? ''); ?>" 
     data-status="<?php echo e($task->status); ?>"
     data-unit="<?php echo e($task->unit); ?>"
     data-priority="<?php echo e($task->priority); ?>"
     data-due-date="<?php echo e($task->due_date ? $task->due_date->format('Y-m-d') : ''); ?>" 
     data-assigned-to="<?php echo e($task->assigned_to ?? ''); ?>" 
     data-assigned-to-name="<?php echo e($task->assigned_to_name ?? 'غير معين'); ?>" 
     data-order-column="<?php echo e($task->order_column ?? 0); ?>"
>
    <div class="card-title-text"><?php echo e($task->title); ?></div>
    <?php if($task->description): ?>
        <div class="card-description-text"><?php echo e(Str::limit($task->description, 100)); ?></div>
    <?php endif; ?>

    <div class="card-attributes-wrapper mt-auto">
        
        <div class="card-attribute attribute-purple">
            <i class="<?php echo e($task->unit_icon); ?> unit-icon"></i>
            <span>الوحدة: <span class="unit-text"><?php echo e($task->unit_label); ?></span></span>
        </div>
        
        
        <div class="card-attribute attribute-sky">
            <i class="fas fa-calendar-alt"></i> 
            <span>تاريخ الاستحقاق: <span class="due-date"><?php echo e($task->formatted_due_date); ?></span></span>
        </div>
        
        
        <div class="card-attribute attribute-indigo">
            <i class="fas fa-user-tie"></i> 
            <span>المسؤول: <span class="assigned-to-name"><?php echo e($task->assigned_to_name); ?></span></span>
        </div>
        
        
        <div class="card-attribute attribute-<?php echo e($task->priority_color); ?>">
            <i class="<?php echo e($task->priority_icon); ?> priority-icon"></i>
            <span>الأولوية: <span class="priority-text"><?php echo e($task->priority_label); ?></span></span>
        </div>
        
        
        <div class="card-attribute">
            <span class="badge status-badge 
                <?php if($task->status == 'pending'): ?> bg-warning
                <?php elseif($task->status == 'in_progress'): ?> bg-info
                <?php elseif($task->status == 'completed'): ?> bg-success
                <?php elseif($task->status == 'rejected'): ?> bg-danger
                <?php endif; ?>
            "><?php echo e($task->status_label); ?></span>
        </div>
    </div>

    <div class="card-actions">
        <button type="button" class="btn btn-edit btn-sm" data-bs-toggle="modal" data-bs-target="#editTaskModal">
            <i class="fas fa-edit"></i> تعديل
        </button>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/kanban/partials/task-card.blade.php ENDPATH**/ ?>