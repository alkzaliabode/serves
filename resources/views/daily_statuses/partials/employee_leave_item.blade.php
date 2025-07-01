<div class="repeater-item grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
    <div>
        <label class="label">اسم الموظف</label>
        <select name="{{ $name_prefix }}[{{ $index }}][employee_id]" class="input-field employee-select"
                onchange="updateEmployeeDetails(this, '{{ $name_prefix }}', {{ $index }})" required>
            <option value="">اختر موظفاً</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ (isset($leave['employee_id']) && $leave['employee_id'] == $employee->id) ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">الرقم الوظيفي</label>
        <input type="text" name="{{ $name_prefix }}[{{ $index }}][employee_number]" class="input-field employee-number-input bg-gray-200" readonly value="{{ $leave['employee_number'] ?? '' }}" required>
    </div>
    <input type="hidden" name="{{ $name_prefix }}[{{ $index }}][employee_name]" class="employee-name-input" value="{{ $leave['employee_name'] ?? '' }}">
    <div class="flex justify-end">
        <button type="button" onclick="removeRepeaterItem(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            إزالة
        </button>
    </div>
</div>
