{{-- resources/views/daily_statuses/partials/dated_leave_item.blade.php --}}
{{-- يستخدم هذا الملف الجزئي للإجازات التي تعتمد على تاريخ بداية وعدد أيام (مثل المرضية، الطويلة، الغياب) --}}

<div class="row mb-2 align-items-center border p-2 rounded bg-light">
    <div class="col-md-4">
        <div class="form-group mb-0">
            <label for="{{ $type }}_{{ $index }}_employee_id">الموظف:</label>
            <select name="{{ $type }}[{{ $index }}][employee_id]"
                    id="{{ $type }}_{{ $index }}_employee_id"
                    class="form-control form-control-sm employee-select select2"
                    required>
                <option value="">اختر موظفاً</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}"
                            data-name="{{ $employee->name }}"
                            {{ old($type . '.' . $index . '.employee_id', $leave['employee_id'] ?? '') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->employee_number }})
                    </option>
                @endforeach
            </select>
            {{-- حقل مخفي لحفظ اسم الموظف --}}
            <input type="hidden"
                   name="{{ $type }}[{{ $index }}][employee_name]"
                   class="employee-name-input"
                   value="{{ old($type . '.' . $index . '.employee_name', $leave['employee_name'] ?? '') }}">
            {{-- حقل مخفي لحفظ الرقم الوظيفي --}}
            <input type="hidden"
                   name="{{ $type }}[{{ $index }}][employee_number]"
                   class="employee-number-input"
                   value="{{ old($type . '.' . $index . '.employee_number', $leave['employee_number'] ?? '') }}">
            @error($type . '.' . $index . '.employee_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-0">
            <label for="{{ $type }}_{{ $index }}_start_date">تاريخ البداية:</label>
            <input type="date"
                   name="{{ $type }}[{{ $index }}][start_date]"
                   id="{{ $type }}_{{ $index }}_start_date"
                   class="form-control form-control-sm"
                   value="{{ old($type . '.' . $index . '.start_date', $leave['start_date'] ?? '') }}"
                   required>
            @error($type . '.' . $index . '.start_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-0">
            <label for="{{ $type }}_{{ $index }}_total_days">عدد الأيام الكلي:</label>
            <input type="number"
                   name="{{ $type }}[{{ $index }}][total_days]"
                   id="{{ $type }}_{{ $index }}_total_days"
                   class="form-control form-control-sm"
                   value="{{ old($type . '.' . $index . '.total_days', $leave['total_days'] ?? '') }}"
                   min="1"
                   required>
            @error($type . '.' . $index . '.total_days')
                <span class="text-danger">{{ $message }}</span>
            @enderror
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
        var selectElement = $('#{{ $type }}_{{ $index }}_employee_id');
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
