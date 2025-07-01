<div class="repeater-item grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
    <div>
        <label class="label">اسم الموظف</label>
        <select name="{{ $name_prefix }}[{{ $index }}][employee_id]" class="input-field employee-select"
                onchange="updateEmployeeDetails(this, '{{ $name_prefix }}', {{ $index }})" required>
            <option value="">اختر موظفاً</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ (isset($usage['employee_id']) && $usage['employee_id'] == $employee->id) ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">الرقم الوظيفي</label>
        <input type="text" name="{{ $name_prefix }}[{{ $index }}][employee_number]" class="input-field employee-number-input bg-gray-200" readonly value="{{ $usage['employee_number'] ?? '' }}" required>
    </div>
    <input type="hidden" name="{{ $name_prefix }}[{{ $index }}][employee_name]" class="employee-name-input" value="{{ $usage['employee_name'] ?? '' }}">
    <div class="col-span-full">
        <label class="label">تفاصيل الاستخدام</label>
        <input type="text" name="{{ $name_prefix }}[{{ $index }}][usage_details]" class="input-field" placeholder="مثال: اجتماع، مهمة خارجية، دورة تدريبية..." value="{{ $usage['usage_details'] ?? '' }}" required>
    </div>
    <div class="flex justify-end col-span-full">
        <button type="button" onclick="removeRepeaterItem(this)" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            إزالة
        </button>
    </div>
</div>
