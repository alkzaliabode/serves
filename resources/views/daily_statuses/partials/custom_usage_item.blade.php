{{-- resources/views/daily_statuses/partials/custom_usage_item.blade.php --}}
{{-- يستخدم هذا الملف الجزئي للاستخدامات المخصصة --}}

<div class="row mb-2 align-items-center border p-2 rounded bg-light repeater-item">
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
                            data-employee_number="{{ $employee->employee_number }}"
                            {{ old($type . '.' . $index . '.employee_id', $usage['employee_id'] ?? '') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->employee_number }})
                    </option>
                @endforeach
            </select>
            {{-- حقل مخفي لحفظ اسم الموظف --}}
            <input type="hidden"
                   name="{{ $type }}[{{ $index }}][employee_name]"
                   class="employee-name-input"
                   value="{{ old($type . '.' . $index . '.employee_name', $usage['employee_name'] ?? '') }}">
            {{-- حقل مخفي لحفظ الرقم الوظيفي --}}
            <input type="hidden"
                   name="{{ $type }}[{{ $index }}][employee_number]"
                   class="employee-number-input"
                   value="{{ old($type . '.' . $index . '.employee_number', $usage['employee_number'] ?? '') }}">
            @error($type . '.' . $index . '.employee_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-0">
            <label>الرقم الوظيفي:</label>
            <input type="text"
                   class="form-control form-control-sm employee-number-input"
                   value="{{ old($type . '.' . $index . '.employee_number', $usage['employee_number'] ?? '') }}"
                   readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-0">
            <label for="{{ $type }}_{{ $index }}_usage_details">تفاصيل الاستخدام:</label>
            <input type="text"
                   name="{{ $type }}[{{ $index }}][usage_details]"
                   id="{{ $type }}_{{ $index }}_usage_details"
                   class="form-control form-control-sm"
                   value="{{ old($type . '.' . $index . '.usage_details', $usage['usage_details'] ?? '') }}"
                   placeholder="مثال: مهمة خارجية، دورة تدريبية"
                   required>
            @error($type . '.' . $index . '.usage_details')
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
