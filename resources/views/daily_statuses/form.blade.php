{{-- resources/views/daily-statuses/form.blade.php --}}

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* أنماط مخصصة إذا لزم الأمر، لكن الأفضل الاعتماد على Bootstrap/AdminLTE */
        .section-heading {
            font-size: 1.25rem; /* text-xl */
            font-weight: 600; /* font-semibold */
            color: #343a40; /* text-gray-800 */
            margin-bottom: 1rem; /* mb-4 */
        }
        .input-group-text-custom { /* لنمط حقول العرض فقط مثل التاريخ الهجري واليوم */
            background-color: #e9ecef; /* bg-gray-100 */
            color: #495057; /* text-gray-700 */
            padding: .5rem .75rem; /* py-2.5 */
            border: 1px solid #ced4da; /* border-gray-300 */
            border-radius: .25rem; /* rounded-md */
            display: flex;
            align-items: center;
            min-height: calc(1.5em + .75rem + 2px); /* لتنسيق أفضل مع حقول الإدخال */
        }
        /* تحسين Select2 ليتناسب مع AdminLTE */
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            border-radius: .25rem;
            min-height: calc(1.5em + .75rem + 2px); /* نفس ارتفاع حقول الإدخال */
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + .75rem + 2px);
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + .75rem + 2px);
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-right: .75rem; /* مسافة من اليمين للنص */
            padding-left: 2.5rem; /* مسافة من اليسار للسهم */
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-width: 5px 4px 0 4px;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #007bff !important; /* لون التحديد في القائمة */
            color: white !important;
        }
        .select2-container--default .select2-results__option--selected {
            background-color: #e9ecef !important; /* لون العنصر المختار */
            color: #495057 !important;
        }
        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }
        .select2-search input {
            border: 1px solid #ced4da !important;
        }

        /* أنماط جديدة لتكبير حجم الخط في الجداول الديناميكية */
        .repeater-item .form-label {
            font-size: 0.9rem; /* حجم خط أكبر للتسميات */
        }
        .repeater-item .form-control,
        .repeater-item .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 0.95rem; /* حجم خط أكبر لحقول الإدخال والاختيار */
            min-height: calc(1.8em + .75rem + 2px); /* تعديل الارتفاع ليتناسب مع حجم الخط الجديد */
            line-height: calc(1.8em + .75rem + 2px); /* تعديل ارتفاع السطر */
        }
        .repeater-item .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.8em + .75rem + 2px); /* تعديل ارتفاع السهم */
        }
    </style>
@endsection

<div class="card card-primary card-outline"> {{-- استخدام بطاقة AdminLTE لتوحيد المظهر --}}
    <div class="card-header">
        <h3 class="card-title">
            {{ isset($dailyStatus) ? 'تعديل الموقف اليومي' : 'إنشاء موقف يومي جديد' }}
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ isset($dailyStatus) ? route('daily-statuses.update', $dailyStatus->id) : route('daily-statuses.store') }}" method="POST">
            @csrf
            @if(isset($dailyStatus))
                @method('PUT')
            @endif

            <!-- معلومات الموقف -->
            <div class="card card-info card-outline mb-4">
                <div class="card-header">
                    <h2 class="card-title section-heading">معلومات الموقف</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="date" class="form-label">التاريخ</label>
                            <input type="date" id="date" name="date" value="{{ old('date', $dailyStatus->date ?? now()->toDateString()) }}"
                                   class="form-control" required onchange="updateDateInfo()">
                            @error('date')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="hijri_date" class="form-label">التاريخ الهجري</label>
                            <p id="hijri_date_display" class="input-group-text-custom">
                                {{ $hijriDate ?? 'سيتم التحديد تلقائياً' }}
                            </p>
                            <input type="hidden" id="hijri_date_input" name="hijri_date" value="{{ $hijriDate ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="day_name" class="form-label">اليوم</label>
                            <p id="day_name_display" class="input-group-text-custom">
                                {{ $dayName ?? 'سيتم التحديد تلقائياً' }}
                            </p>
                            <input type="hidden" id="day_name_input" name="day_name" value="{{ $dayName ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- الإجازات -->
            <div class="card card-info card-outline mb-4">
                <div class="card-header">
                    <h2 class="card-title section-heading">الإجازات</h2>
                </div>
                <div class="card-body">
                    <div id="periodic_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">الإجازات الدورية</h3>
                        <div id="periodic_leaves_container">
                            @php $periodic_leaves = old('periodic_leaves', $dailyStatus->periodic_leaves ?? []); @endphp
                            @foreach($periodic_leaves as $index => $leave)
                                @include('daily_statuses.partials.employee_leave_item', ['type' => 'periodic_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addEmployeeLeaveItem('periodic_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة دورية
                        </button>
                    </div>

                    <div id="annual_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">الإجازات السنوية</h3>
                        <div id="annual_leaves_container">
                            @php $annual_leaves = old('annual_leaves', $dailyStatus->annual_leaves ?? []); @endphp
                            @foreach($annual_leaves as $index => $leave)
                                @include('daily_statuses.partials.employee_leave_item', ['type' => 'annual_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addEmployeeLeaveItem('annual_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة سنوية
                        </button>
                    </div>

                    <div id="temporary_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">الإجازات الزمنية</h3>
                        <div id="temporary_leaves_container">
                            @php $temporary_leaves = old('temporary_leaves', $dailyStatus->temporary_leaves ?? []); @endphp
                            @foreach($temporary_leaves as $index => $leave)
                                @include('daily_statuses.partials.temporary_leave_item', ['type' => 'temporary_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addTemporaryLeaveItem('temporary_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة زمنية
                        </button>
                    </div>

                    <div id="eid_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">إجازات الأعياد</h3>
                        <div id="eid_leaves_container">
                            @php $eid_leaves = old('eid_leaves', $dailyStatus->eid_leaves ?? []); @endphp
                            @foreach($eid_leaves as $index => $leave)
                                @include('daily_statuses.partials.eid_leave_item', ['type' => 'eid_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addEidLeaveItem('eid_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة عيد
                        </button>
                    </div>

                    <div id="guard_rest_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">استراحة خفر</h3>
                        <div id="guard_rest_container">
                            @php $guard_rest = old('guard_rest', $dailyStatus->guard_rest ?? []); @endphp
                            @foreach($guard_rest as $index => $rest)
                                @include('daily_statuses.partials.employee_leave_item', ['type' => 'guard_rest', 'index' => $index, 'leave' => $rest, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addEmployeeLeaveItem('guard_rest')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة استراحة خفر
                        </button>
                    </div>
                </div>
            </div>

            <!-- الغياب والإجازات الأخرى -->
            <div class="card card-info card-outline mb-4">
                <div class="card-header">
                    <h2 class="card-title section-heading">الغياب والإجازات الأخرى</h2>
                </div>
                <div class="card-body">
                    <div id="unpaid_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">إجازة بدون راتب</h3>
                        <div id="unpaid_leaves_container">
                            @php $unpaid_leaves = old('unpaid_leaves', $dailyStatus->unpaid_leaves ?? []); @endphp
                            @foreach($unpaid_leaves as $index => $leave)
                                @include('daily_statuses.partials.employee_leave_item', ['type' => 'unpaid_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addEmployeeLeaveItem('unpaid_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة بدون راتب
                        </button>
                    </div>

                    <div id="absences_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">الغياب</h3>
                        <div id="absences_container">
                            @php $absences = old('absences', $dailyStatus->absences ?? []); @endphp
                            @foreach($absences as $index => $absence)
                                @include('daily_statuses.partials.dated_leave_item', ['type' => 'absences', 'index' => $index, 'leave' => $absence, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addDatedLeaveItem('absences')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة غياب
                        </button>
                    </div>

                    <div id="long_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">الإجازات الطويلة</h3>
                        <div id="long_leaves_container">
                            @php $long_leaves = old('long_leaves', $dailyStatus->long_leaves ?? []); @endphp
                            @foreach($long_leaves as $index => $leave)
                                @include('daily_statuses.partials.dated_leave_item', ['type' => 'long_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addDatedLeaveItem('long_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة طويلة
                        </button>
                    </div>

                    <div id="sick_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">الإجازات المرضية</h3>
                        <div id="sick_leaves_container">
                            @php $sick_leaves = old('sick_leaves', $dailyStatus->sick_leaves ?? []); @endphp
                            @foreach($sick_leaves as $index => $leave)
                                @include('daily_statuses.partials.dated_leave_item', ['type' => 'sick_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addDatedLeaveItem('sick_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة مرضية
                        </button>
                    </div>

                    <div id="bereavement_leaves_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">إجازة وفاة</h3>
                        <div id="bereavement_leaves_container">
                            @php $bereavement_leaves = old('bereavement_leaves', $dailyStatus->bereavement_leaves ?? []); @endphp
                            @foreach($bereavement_leaves as $index => $leave)
                                @include('daily_statuses.partials.employee_leave_item', ['type' => 'bereavement_leaves', 'index' => $index, 'leave' => $leave, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addEmployeeLeaveItem('bereavement_leaves')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة إجازة وفاة
                        </button>
                    </div>

                    <div id="custom_usages_repeater" class="mb-4">
                        <h3 class="font-weight-bold text-gray-700 mb-3">الاستخدامات المخصصة</h3>
                        <div id="custom_usages_container">
                            @php $custom_usages = old('custom_usages', $dailyStatus->custom_usages ?? []); @endphp
                            @foreach($custom_usages as $index => $usage)
                                @include('daily_statuses.partials.custom_usage_item', ['type' => 'custom_usages', 'index' => $index, 'usage' => $usage, 'employees' => $employees])
                            @endforeach
                        </div>
                        <button type="button" onclick="addCustomUsageItem('custom_usages')" class="btn btn-info btn-sm mt-2">
                            <i class="fas fa-plus"></i> إضافة استخدام مخصص
                        </button>
                    </div>
                </div>
            </div>

            <!-- الإحصائيات -->
            <div class="card card-info card-outline mb-4">
                <div class="card-header">
                    <h2 class="card-title section-heading">الإحصائيات</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total_required" class="form-label">الملاك</label>
                            <input type="number" id="total_required" name="total_required" value="{{ old('total_required', $dailyStatus->total_required ?? 86) }}"
                                   class="form-control" required oninput="updateStats()">
                            @error('total_required')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="total_employees" class="form-label">الموجود الحالي</label>
                            <p id="total_employees_display" class="input-group-text-custom">
                                {{ $totalEmployees ?? \App\Models\Employee::where('is_active', 1)->count() }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="shortage" class="form-label">النقص</label>
                            <p id="shortage_display" class="input-group-text-custom">0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="actual_attendance" class="form-label">الحضور الفعلي</label>
                            <p id="actual_attendance_display" class="input-group-text-custom">0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="paid_leaves_count" class="form-label">إجازات براتب</label>
                            <p id="paid_leaves_count_display" class="input-group-text-custom">0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="unpaid_leaves_count" class="form-label">إجازات بدون راتب</label>
                            <p id="unpaid_leaves_count_display" class="input-group-text-custom">0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="absences_count" class="form-label">الغياب</label>
                            <p id="absences_count_display" class="input-group-text-custom">0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="guard_rest_count" class="form-label">استراحة خفر</label>
                            <p id="guard_rest_count_display" class="input-group-text-custom">0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="temporary_leaves_count" class="form-label">إجازات زمنية</label>
                            <p id="temporary_leaves_count_display" class="input-group-text-custom">0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="custom_usages_count" class="form-label">الاستخدامات المخصصة</label>
                            <p id="custom_usages_count_display" class="input-group-text-custom">0</p>
                        </div>
                        {{-- يمكنك إضافة حقول 'تعيين', 'نقل', 'فصل' هنا إذا كنت تخزنها في DailyStatus --}}
                        {{-- <div class="col-md-6 mb-3"><label class="form-label">تعيين</label><p class="input-group-text-custom">0</p></div> --}}
                        {{-- <div class="col-md-6 mb-3"><label class="form-label">نقل</label><p class="input-group-text-custom">0</p></div> --}}
                        {{-- <div class="col-md-6 mb-3"><label class="form-label">فصل</label><p class="input-group-text-custom">0</p></div> --}}
                    </div>
                </div>
            </div>

            <!-- منظم الموقف -->
            <div class="card card-info card-outline mb-4">
                <div class="card-header">
                    <h2 class="card-title section-heading">منظم الموقف</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="organizer_employee_id" class="form-label">اسم الموظف المنظم</label>
                        <select id="organizer_employee_id" name="organizer_employee_id" class="form-control select2-init" required onchange="updateOrganizerName()">
                            <option value="">اختر موظفاً</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('organizer_employee_id', $dailyStatus->organizer_employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('organizer_employee_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        <input type="hidden" id="organizer_employee_name_input" name="organizer_employee_name" value="{{ old('organizer_employee_name', $dailyStatus->organizer_employee_name ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> حفظ الموقف اليومي
                </button>
                <a href="{{ route('daily-statuses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
    {{-- تحميل jQuery قبل Select2 --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // استخدام jQuery.noConflict() لتجنب التعارضات مع مكتبات أخرى تستخدم علامة $
        var $j = jQuery.noConflict();

        // Array of employee data passed from Laravel (Blade to JS)
        const employeesData = @json($employees->keyBy('id'));
        const daysOfWeek = {
            'Sunday': 'الأحد',
            'Monday': 'الإثنين',
            'Tuesday': 'الثلاثاء',
            'Wednesday': 'الأربعاء',
            'Thursday': 'الخميس',
            'Friday': 'الجمعة',
            'Saturday': 'السبت',
        };

        // Function to convert Gregorian to Hijri (requires an AJAX call or pre-calculated list)
        async function convertToHijri(gregorianDate) {
            try {
                const response = await fetch('{{ route("daily-statuses.get-hijri-date-and-day") }}?date=' + gregorianDate);
                const data = await response.json();
                return data.hijri_date;
            } catch (error) {
                console.error('Error converting date:', error);
                return 'خطأ في التحويل';
            }
        }

        function getDayName(dateString) {
            try {
                const date = new Date(dateString);
                const dayNameEn = date.toLocaleDateString('en-US', { weekday: 'long' });
                return daysOfWeek[dayNameEn] || dayNameEn;
            } catch (error) {
                return 'يوم غير معروف';
            }
        }

        async function updateDateInfo() {
            const dateInput = document.getElementById('date');
            const gregorianDate = dateInput.value;

            if (gregorianDate) {
                // Update Hijri Date
                const hijriDate = await convertToHijri(gregorianDate);
                document.getElementById('hijri_date_display').innerText = hijriDate;
                document.getElementById('hijri_date_input').value = hijriDate;

                // Update Day Name
                const dayName = getDayName(gregorianDate);
                document.getElementById('day_name_display').innerText = dayName;
                document.getElementById('day_name_input').value = dayName;
            } else {
                document.getElementById('hijri_date_display').innerText = 'سيتم التحديد تلقائياً';
                document.getElementById('hijri_date_input').value = '';
                document.getElementById('day_name_display').innerText = 'سيتم التحديد تلقائياً';
                document.getElementById('day_name_input').value = '';
            }
        }

        function updateOrganizerName() {
            const selectElement = document.getElementById('organizer_employee_id');
            const selectedEmployeeId = selectElement.value;
            let organizerName = '';
            if (selectedEmployeeId && employeesData[selectedEmployeeId]) {
                organizerName = employeesData[selectedEmployeeId].name;
            }
            document.getElementById('organizer_employee_name_input').value = organizerName;
        }

        function getRepeaterCurrentIndex(name_prefix) {
            const container = document.getElementById(`${name_prefix}_container`);
            return container.children.length;
        }

        function removeRepeaterItem(button) {
            button.closest('.repeater-item').remove();
            updateStats(); // Recalculate stats after removing an item
        }

        function createEmployeeSelect(type_prefix, index, selectedId = '') { // Changed name_prefix to type_prefix
            let optionsHtml = '<option value="">اختر موظفاً</option>';
            for (const id in employeesData) {
                const employee = employeesData[id];
                const selected = selectedId == id ? 'selected' : '';
                optionsHtml += `<option value="${employee.id}" ${selected} data-name="${employee.name}" data-employee_number="${employee.employee_number}">${employee.name}</option>`;
            }
            return `<select name="${type_prefix}[${index}][employee_id]" class="form-control select2-dynamic"
                            onchange="updateEmployeeDetails(this, '${type_prefix}', ${index})" required>
                        ${optionsHtml}
                    </select>`;
        }

        function updateEmployeeDetails(selectElement, type_prefix, index) { // Changed name_prefix to type_prefix
            const selectedEmployeeId = selectElement.value;
            const parentDiv = selectElement.closest('.repeater-item');

            if (selectedEmployeeId && employeesData[selectedEmployeeId]) {
                const employee = employeesData[selectedEmployeeId];
                parentDiv.querySelector(`input[name="${type_prefix}[${index}][employee_number]"]`).value = employee.employee_number || '';
                parentDiv.querySelector(`input[name="${type_prefix}[${index}][employee_name]"]`).value = employee.name || '';
            } else {
                parentDiv.querySelector(`input[name="${type_prefix}[${index}][employee_number]"]`).value = '';
                parentDiv.querySelector(`input[name="${type_prefix}[${index}][employee_name]"]`).value = '';
            }
        }

        function addEmployeeLeaveItem(type_prefix, leave = {}) { // Changed name_prefix to type_prefix
            const index = getRepeaterCurrentIndex(type_prefix);
            const container = document.getElementById(`${type_prefix}_container`);
            const div = document.createElement('div');
            div.className = 'repeater-item card card-outline card-secondary mb-3'; // Use card for repeater items
            div.innerHTML = `
                <div class="card-body row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">اسم الموظف</label>
                        ${createEmployeeSelect(type_prefix, index, leave.employee_id || '')}
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">الرقم الوظيفي</label>
                        <input type="text" name="${type_prefix}[${index}][employee_number]" class="form-control employee-number-input" readonly value="${leave.employee_number || ''}" required>
                    </div>
                    <input type="hidden" name="${type_prefix}[${index}][employee_name]" class="employee-name-input" value="${leave.employee_name || ''}">
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button type="button" onclick="removeRepeaterItem(this)" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> إزالة
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(div);
            initializeSelect2ForNewElements(div); // Initialize Select2 for new elements
            updateStats(); // Recalculate stats
        }

        function addTemporaryLeaveItem(type_prefix, leave = {}) { // Changed name_prefix to type_prefix
            const index = getRepeaterCurrentIndex(type_prefix);
            const container = document.getElementById(`${type_prefix}_container`);
            const div = document.createElement('div');
            div.className = 'repeater-item card card-outline card-secondary mb-3';
            div.innerHTML = `
                <div class="card-body row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">اسم الموظف</label>
                        ${createEmployeeSelect(type_prefix, index, leave.employee_id || '')}
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">الرقم الوظيفي</label>
                        <input type="text" name="${type_prefix}[${index}][employee_number]" class="form-control employee-number-input" readonly value="${leave.employee_number || ''}" required>
                    </div>
                    <input type="hidden" name="${type_prefix}[${index}][employee_name]" class="employee-name-input" value="${leave.employee_name || ''}">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">من الساعة</label>
                        <input type="time" name="${type_prefix}[${index}][from_time]" class="form-control" value="${leave.from_time || ''}" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">إلى الساعة</label>
                        <input type="time" name="${type_prefix}[${index}][to_time]" class="form-control" value="${leave.to_time || ''}" required>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" onclick="removeRepeaterItem(this)" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> إزالة
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(div);
            initializeSelect2ForNewElements(div); // Initialize Select2 for new elements
            updateStats();
        }

        function addEidLeaveItem(type_prefix, leave = {}) { // Changed name_prefix to type_prefix
            const index = getRepeaterCurrentIndex(type_prefix);
            const container = document.getElementById(`${type_prefix}_container`);
            const div = document.createElement('div');
            div.className = 'repeater-item card card-outline card-secondary mb-3';
            div.innerHTML = `
                <div class="card-body row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">نوع العيد</label>
                        <select name="${type_prefix}[${index}][eid_type]" class="form-control" required>
                            <option value="">اختر نوع العيد</option>
                            <option value="eid_alfitr" ${leave.eid_type == 'eid_alfitr' ? 'selected' : ''}>عيد الفطر</option>
                            <option value="eid_aladha" ${leave.eid_type == 'eid_aladha' ? 'selected' : ''}>عيد الأضحى</option>
                            <option value="eid_algahdir" ${leave.eid_type == 'eid_algahdir' ? 'selected' : ''}>عيد الغدير</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">اسم الموظف</label>
                        ${createEmployeeSelect(type_prefix, index, leave.employee_id || '')}
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">الرقم الوظيفي</label>
                        <input type="text" name="${type_prefix}[${index}][employee_number]" class="form-control employee-number-input" readonly value="${leave.employee_number || ''}" required>
                    </div>
                    <input type="hidden" name="${type_prefix}[${index}][employee_name]" class="employee-name-input" value="${leave.employee_name || ''}">
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="button" onclick="removeRepeaterItem(this)" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> إزالة
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(div);
            initializeSelect2ForNewElements(div); // Initialize Select2 for new elements
            updateStats();
        }

        function addDatedLeaveItem(type_prefix, leave = {}) { // Changed name_prefix to type_prefix
            const index = getRepeaterCurrentIndex(type_prefix);
            const container = document.getElementById(`${type_prefix}_container`);
            const div = document.createElement('div');
            div.className = 'repeater-item card card-outline card-secondary mb-3';
            div.innerHTML = `
                <div class="card-body row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">اسم الموظف</label>
                        ${createEmployeeSelect(type_prefix, index, leave.employee_id || '')}
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">الرقم الوظيفي</label>
                        <input type="text" name="${type_prefix}[${index}][employee_number]" class="form-control employee-number-input" readonly value="${leave.employee_number || ''}" required>
                    </div>
                    <input type="hidden" name="${type_prefix}[${index}][employee_name]" class="employee-name-input" value="${leave.employee_name || ''}">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="${type_prefix}[${index}][from_date]" class="form-control" value="${leave.from_date || ''}" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="${type_prefix}[${index}][to_date]" class="form-control" value="${leave.to_date || ''}" required>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" onclick="removeRepeaterItem(this)" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> إزالة
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(div);
            initializeSelect2ForNewElements(div); // Initialize Select2 for new elements
            updateStats();
        }

        function addCustomUsageItem(type_prefix, usage = {}) { // Changed name_prefix to type_prefix
            const index = getRepeaterCurrentIndex(type_prefix);
            const container = document.getElementById(`${type_prefix}_container`);
            const div = document.createElement('div');
            div.className = 'repeater-item card card-outline card-secondary mb-3';
            div.innerHTML = `
                <div class="card-body row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">اسم الموظف</label>
                        ${createEmployeeSelect(type_prefix, index, usage.employee_id || '')}
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">الرقم الوظيفي</label>
                        <input type="text" name="${type_prefix}[${index}][employee_number]" class="form-control employee-number-input" readonly value="${usage.employee_number || ''}" required>
                    </div>
                    <input type="hidden" name="${type_prefix}[${index}][employee_name]" class="employee-name-input" value="${usage.employee_name || ''}">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">تفاصيل الاستخدام</label>
                        <input type="text" name="${type_prefix}[${index}][usage_details]" class="form-control" value="${usage.usage_details || ''}" required>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" onclick="removeRepeaterItem(this)" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> إزالة
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(div);
            initializeSelect2ForNewElements(div); // Initialize Select2 for new elements
            updateStats();
        }

        function updateStats() {
            const totalRequired = parseInt(document.getElementById('total_required').value) || 0;
            // Assuming totalEmployees is fetched from backend or static
            const totalEmployees = parseInt(document.getElementById('total_employees_display').innerText) || 0;

            // Count current leaves/absences from the form
            const periodicLeavesCount = document.getElementById('periodic_leaves_container').children.length;
            const annualLeavesCount = document.getElementById('annual_leaves_container').children.length;
            const eidLeavesCount = document.getElementById('eid_leaves_container').children.length;
            const guardRestCount = document.getElementById('guard_rest_container').children.length;
            const unpaidLeavesCount = document.getElementById('unpaid_leaves_container').children.length;
            const bereavementLeavesCount = document.getElementById('bereavement_leaves_container').children.length;
            const temporaryLeavesCount = document.getElementById('temporary_leaves_container').children.length;
            const sickLeavesCount = document.getElementById('sick_leaves_container').children.length;
            const longLeavesCount = document.getElementById('long_leaves_container').children.length;
            const absencesCount = document.getElementById('absences_container').children.length;
            const customUsagesCount = document.getElementById('custom_usages_container').children.length;

            const paidLeaves = annualLeavesCount + periodicLeavesCount + eidLeavesCount + sickLeavesCount + bereavementLeavesCount;
            const actualAttendance = totalEmployees - (paidLeaves + unpaidLeavesCount + absencesCount + temporaryLeavesCount + guardRestCount + customUsagesCount);
            const shortage = totalRequired - totalEmployees;

            document.getElementById('shortage_display').innerText = shortage;
            document.getElementById('actual_attendance_display').innerText = actualAttendance;
            document.getElementById('paid_leaves_count_display').innerText = paidLeaves;
            document.getElementById('unpaid_leaves_count_display').innerText = unpaidLeavesCount;
            document.getElementById('absences_count_display').innerText = absencesCount;
            document.getElementById('guard_rest_count_display').innerText = guardRestCount;
            document.getElementById('temporary_leaves_count_display').innerText = temporaryLeavesCount;
            document.getElementById('custom_usages_count_display').innerText = customUsagesCount;
        }

        // Function to initialize Select2 for newly added elements
        function initializeSelect2ForNewElements(element) {
            $j(element).find('.select2-dynamic').select2({
                placeholder: "اختر موظفاً",
                allowClear: true,
                dir: "rtl"
            });
            // Trigger change to populate hidden fields if an option is pre-selected
            $j(element).find('.select2-dynamic').each(function() {
                updateEmployeeDetails(this, $j(this).attr('name').split('[')[0], $j(this).attr('name').split('[')[1].replace(']', ''));
            });
        }

        // Initial setup on document ready
        $j(document).ready(function() {
            // Initialize Select2 for the organizer select
            $j('#organizer_employee_id').select2({
                placeholder: "اختر موظفاً",
                allowClear: true,
                dir: "rtl"
            });
            updateOrganizerName(); // Set initial organizer name

            // Initialize Select2 for any existing repeater items on page load
            $j('.repeater-item').each(function() {
                initializeSelect2ForNewElements(this);
            });

            // Trigger initial date info update
            updateDateInfo();
            // Trigger initial stats update
            updateStats();
        });
    </script>
@endsection
