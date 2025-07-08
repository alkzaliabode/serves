{{-- resources/views/daily_statuses/partials/temporary_leave_item.blade.php --}}
{{-- يستخدم هذا الملف الجزئي للإجازات الزمنية --}}

<div class="row mb-2 align-items-center border p-2 rounded bg-light repeater-item">
    <div class="col-md-3">
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
    <div class="col-md-2">
        <div class="form-group mb-0">
            <label for="{{ $type }}_{{ $index }}_from_time">من الساعة:</label>
            <input type="time"
                   name="{{ $type }}[{{ $index }}][from_time]"
                   id="{{ $type }}_{{ $index }}_from_time"
                   class="form-control form-control-sm"
                   value="{{ old($type . '.' . $index . '.from_time', $leave['from_time'] ?? '') }}"
                   required>
            @error($type . '.' . $index . '.from_time')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group mb-0">
            <label for="{{ $type }}_{{ $index }}_to_time">إلى الساعة:</label>
            <input type="time"
                   name="{{ $type }}[{{ $index }}][to_time]"
                   id="{{ $type }}_{{ $index }}_to_time"
                   class="form-control form-control-sm"
                   value="{{ old($type . '.' . $index . '.to_time', $leave['to_time'] ?? '') }}"
                   required>
            @error($type . '.' . $index . '.to_time')
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
