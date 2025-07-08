


<div class="row mb-2 align-items-center border p-2 rounded bg-light repeater-item">
    <div class="col-md-3">
        <div class="form-group mb-0">
            <label for="<?php echo e($type); ?>_<?php echo e($index); ?>_eid_type">نوع العيد:</label>
            <select name="<?php echo e($type); ?>[<?php echo e($index); ?>][eid_type]"
                    id="<?php echo e($type); ?>_<?php echo e($index); ?>_eid_type"
                    class="form-control form-control-sm"
                    required>
                <option value="">اختر نوع العيد</option>
                <option value="eid_alfitr" <?php echo e(old($type . '.' . $index . '.eid_type', $leave['eid_type'] ?? '') == 'eid_alfitr' ? 'selected' : ''); ?>>عيد الفطر</option>
                <option value="eid_aladha" <?php echo e(old($type . '.' . $index . '.eid_type', $leave['eid_type'] ?? '') == 'eid_aladha' ? 'selected' : ''); ?>>عيد الأضحى</option>
                <option value="eid_algahdir" <?php echo e(old($type . '.' . $index . '.eid_type', $leave['eid_type'] ?? '') == 'eid_algahdir' ? 'selected' : ''); ?>>عيد الغدير</option>
            </select>
            <?php $__errorArgs = [$type . '.' . $index . '.eid_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-0">
            <label for="<?php echo e($type); ?>_<?php echo e($index); ?>_employee_id">الموظف:</label>
            <select name="<?php echo e($type); ?>[<?php echo e($index); ?>][employee_id]"
                    id="<?php echo e($type); ?>_<?php echo e($index); ?>_employee_id"
                    class="form-control form-control-sm employee-select select2"
                    required>
                <option value="">اختر موظفاً</option>
                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($employee->id); ?>"
                            data-name="<?php echo e($employee->name); ?>"
                            data-employee_number="<?php echo e($employee->employee_number); ?>"
                            <?php echo e(old($type . '.' . $index . '.employee_id', $leave['employee_id'] ?? '') == $employee->id ? 'selected' : ''); ?>>
                        <?php echo e($employee->name); ?> (<?php echo e($employee->employee_number); ?>)
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            
            <input type="hidden"
                   name="<?php echo e($type); ?>[<?php echo e($index); ?>][employee_name]"
                   class="employee-name-input"
                   value="<?php echo e(old($type . '.' . $index . '.employee_name', $leave['employee_name'] ?? '')); ?>">
            
            <input type="hidden"
                   name="<?php echo e($type); ?>[<?php echo e($index); ?>][employee_number]"
                   class="employee-number-input"
                   value="<?php echo e(old($type . '.' . $index . '.employee_number', $leave['employee_number'] ?? '')); ?>">
            <?php $__errorArgs = [$type . '.' . $index . '.employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-0">
            <label>الرقم الوظيفي:</label>
            <input type="text"
                   class="form-control form-control-sm employee-number-input"
                   value="<?php echo e(old($type . '.' . $index . '.employee_number', $leave['employee_number'] ?? '')); ?>"
                   readonly>
        </div>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-sm remove-item w-100">
            <i class="fas fa-trash"></i> إزالة
        </button>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/daily_statuses/partials/eid_leave_item.blade.php ENDPATH**/ ?>