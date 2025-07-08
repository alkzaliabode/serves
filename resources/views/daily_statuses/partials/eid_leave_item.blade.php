{{-- resources/views/daily_statuses/partials/eid_leave_item.blade.php --}}
{{-- يستخدم هذا الملف الجزئي لإجازات الأعياد --}}

<div class="row mb-2 align-items-center border p-2 rounded bg-light repeater-item">
    <div class="col-md-3">
        <div class="form-group mb-0">
            <label for="{{ $type }}_{{ $index }}_eid_type">نوع العيد:</label>
            <select name="{{ $type }}[{{ $index }}][eid_type]"
                    id="{{ $type }}_{{ $index }}_eid_type"
                    class="form-control form-control-sm"
                    required>
                <option value="">اختر نوع العيد</option>
                <option value="eid_alfitr" {{ old($type . '.' . $index . '.eid_type', $leave['eid_type'] ?? '') == 'eid_alfitr' ? 'selected' : '' }}>عيد الفطر</option>
                <option value="eid_aladha" {{ old($type . '.' . $index . '.eid_type', $leave['eid_type'] ?? '') == 'eid_aladha' ? 'selected' : '' }}>عيد الأضحى</option>
                <option value="eid_algahdir" {{ old($type . '.' . $index . '.eid_type', $leave['eid_type'] ?? '') == 'eid_algahdir' ? 'selected' : '' }}>عيد الغدير</option>
            </select>
            @error($type . '.' . $index . '.eid_type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
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
            <label>الرقم الوظيفي:</label>
            <input type="text"
                   class="form-control form-control-sm employee-number-input"
                   value="{{ old($type . '.' . $index . '.employee_number', $leave['employee_number'] ?? '') }}"
                   readonly>
        </div>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-sm remove-item w-100">
            <i class="fas fa-trash"></i> إزالة
        </button>
    </div>
</div>
