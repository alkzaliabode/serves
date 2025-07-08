


<div class="row mb-2 align-items-center border p-2 rounded bg-light">
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
            <label for="<?php echo e($type); ?>_<?php echo e($index); ?>_start_date">تاريخ البداية:</label>
            <input type="date"
                   name="<?php echo e($type); ?>[<?php echo e($index); ?>][start_date]"
                   id="<?php echo e($type); ?>_<?php echo e($index); ?>_start_date"
                   class="form-control form-control-sm"
                   value="<?php echo e(old($type . '.' . $index . '.start_date', $leave['start_date'] ?? '')); ?>"
                   required>
            <?php $__errorArgs = [$type . '.' . $index . '.start_date'];
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
            <label for="<?php echo e($type); ?>_<?php echo e($index); ?>_total_days">عدد الأيام الكلي:</label>
            <input type="number"
                   name="<?php echo e($type); ?>[<?php echo e($index); ?>][total_days]"
                   id="<?php echo e($type); ?>_<?php echo e($index); ?>_total_days"
                   class="form-control form-control-sm"
                   value="<?php echo e(old($type . '.' . $index . '.total_days', $leave['total_days'] ?? '')); ?>"
                   min="1"
                   required>
            <?php $__errorArgs = [$type . '.' . $index . '.total_days'];
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
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-sm remove-item w-100">
            <i class="fas fa-trash"></i> إزالة
        </button>
    </div>
</div>

<script>
    // عند تحميل الصفحة أو إضافة عنصر جديد، قم بتهيئة Select2 وتحديث اسم ورقم الموظف
    $(document).ready(function() {
        var selectElement = $('#<?php echo e($type); ?>_<?php echo e($index); ?>_employee_id');
        selectElement.select2({
            placeholder: "اختر موظفاً",
            allowClear: true,
            dir: "rtl"
        });

        // تحديث اسم ورقم الموظف المخفي عند اختيار موظف
        selectElement.on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var employeeName = selectedOption.data('name');
            var employeeNumber = selectedOption.data('employee_number'); // تأكد من وجود هذا الـ data attribute في الـ option
            $(this).closest('.row').find('.employee-name-input').val(employeeName);
            $(this).closest('.row').find('.employee-number-input').val(employeeNumber);
        });

        // تشغيل حدث التغيير لملء الحقول المخفية بالقيم الأولية إذا كانت موجودة
        if (selectElement.val()) {
            selectElement.trigger('change');
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/daily_statuses/partials/dated_leave_item.blade.php ENDPATH**/ ?>