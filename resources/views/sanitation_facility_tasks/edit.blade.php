@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل مهمة منشأة صحية') {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', 'تعديل مهمة منشأة صحية') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sanitation-facility-tasks.index') }}">مهام المنشآت الصحية</a></li>
    <li class="breadcrumb-item active">تعديل مهمة</li>
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    <div class="card card-primary card-outline"> {{-- استخدام بطاقة AdminLTE --}}
        <div class="card-header">
            <h3 class="card-title">تعديل مهمة منشأة صحية</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('sanitation-facility-tasks.update', $sanitationFacilityTask) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card card-info card-outline"> {{-- استخدام بطاقة AdminLTE كقسم للنموذج --}}
                    <div class="card-header">
                        <h2 class="card-title">المعلومات الأساسية</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">التاريخ</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $sanitationFacilityTask->date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="shift" class="form-label">الوجبة</label>
                                <select class="form-select" id="shift" name="shift" required>
                                    <option value="صباحي" {{ old('shift', $sanitationFacilityTask->shift) == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                    <option value="مسائي" {{ old('shift', $sanitationFacilityTask->shift) == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                    <option value="ليلي" {{ old('shift', $sanitationFacilityTask->shift) == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="مكتمل" {{ old('status', $sanitationFacilityTask->status) == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="قيد التنفيذ" {{ old('status', $sanitationFacilityTask->status) == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                    <option value="ملغى" {{ old('status', $sanitationFacilityTask->status) == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="related_goal_id" class="form-label">الهدف المرتبط</label>
                            <select class="form-select" id="related_goal_id" name="related_goal_id" required>
                                <option value="">اختر الهدف المرتبط</option>
                                @foreach($goals as $goal)
                                    <option value="{{ $goal->id }}" {{ old('related_goal_id', $sanitationFacilityTask->related_goal_id) == $goal->id ? 'selected' : '' }}>{{ $goal->goal_text }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">اختر الهدف الاستراتيجي أو التشغيلي الذي تساهم فيه هذه المهمة.</div>
                        </div>
                        <div class="mb-3">
                            <label for="working_hours" class="form-label">إجمالي ساعات العمل للمهمة</label>
                            <input type="number" step="0.5" class="form-control" id="working_hours" name="working_hours" min="0" max="24" value="{{ old('working_hours', $sanitationFacilityTask->working_hours) }}" required>
                            <div class="form-text text-muted">إجمالي ساعات العمل التي استغرقتها هذه المهمة.</div>
                        </div>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">تفاصيل المهمة</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="task_type" class="form-label">نوع المهمة</label>
                                <select class="form-select" id="task_type" name="task_type" required>
                                    <option value="">اختر نوع المهمة</option>
                                    <option value="إدامة" {{ old('task_type', $sanitationFacilityTask->task_type) == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                    <option value="صيانة" {{ old('task_type', $sanitationFacilityTask->task_type) == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="facility_name" class="form-label">اسم المرفق الصحي</label>
                                <select class="form-select" id="facility_name" name="facility_name" required>
                                    <option value="">اختر المرفق الصحي</option>
                                    <option value="صحية الجامع رجال" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية الجامع رجال' ? 'selected' : '' }}>صحية الجامع رجال</option>
                                    <option value="صحية الجامع نساء" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية الجامع نساء' ? 'selected' : '' }}>صحية الجامع نساء</option>
                                    <option value="صحية 1 رجال" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 1 رجال' ? 'selected' : '' }}>صحية 1 رجال</option>
                                    <option value="صحية 2 رجال" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 2 رجال' ? 'selected' : '' }}>صحية 2 رجال</option>
                                    <option value="صحية 3 رجال" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 3 رجال' ? 'selected' : '' }}>صحية 3 رجال</option>
                                    <option value="صحية 4 رجال" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 4 رجال' ? 'selected' : '' }}>صحية 4 رجال</option>
                                    <option value="صحية 1 نساء" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 1 نساء' ? 'selected' : '' }}>صحية 1 نساء</option>
                                    <option value="صحية 2 نساء" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 2 نساء' ? 'selected' : '' }}>صحية 2 نساء</option>
                                    <option value="صحية 3 نساء" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 3 نساء' ? 'selected' : '' }}>صحية 3 نساء</option>
                                    <option value="صحية 4 نساء" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'صحية 4 نساء' ? 'selected' : '' }}>صحية 4 نساء</option>
                                    <option value="المجاميع الكبيرة رجال" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'المجاميع الكبيرة رجال' ? 'selected' : '' }}>المجاميع الكبيرة رجال</option>
                                    <option value="المجاميع الكبيرة نساء" {{ old('facility_name', $sanitationFacilityTask->facility_name) == 'المجاميع الكبيرة نساء' ? 'selected' : '' }}>المجاميع الكبيرة نساء</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="details" class="form-label">تفاصيل إضافية</label>
                            <textarea class="form-control" id="details" name="details" rows="3" required>{{ old('details', $sanitationFacilityTask->details) }}</textarea>
                        </div>

                        <fieldset id="equipment-details-fieldset" class="mb-3 p-3 border border-dashed border-secondary rounded" style="display: {{ old('task_type', $sanitationFacilityTask->task_type) ? 'block' : 'none' }};">
                            <legend class="text-lg font-semibold mb-3">تفاصيل المعدات</legend>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3"><label for="seats_count" class="form-label"><span class="prefix">عدد</span> المقاعد <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="seats_count" name="seats_count" min="0" value="{{ old('seats_count', $sanitationFacilityTask->seats_count ?? 0) }}"></div>
                                <div class="col-md-4 mb-3"><label for="mirrors_count" class="form-label"><span class="prefix">عدد</span> المرايا <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="mirrors_count" name="mirrors_count" min="0" value="{{ old('mirrors_count', $sanitationFacilityTask->mirrors_count ?? 0) }}"></div>
                                <div class="col-md-4 mb-3"><label for="mixers_count" class="form-label"><span class="prefix">عدد</span> الخلاطات <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="mixers_count" name="mixers_count" min="0" value="{{ old('mixers_count', $sanitationFacilityTask->mixers_count ?? 0) }}"></div>
                                <div class="col-md-4 mb-3"><label for="doors_count" class="form-label"><span class="prefix">عدد</span> الأبواب <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="doors_count" name="doors_count" min="0" value="{{ old('doors_count', $sanitationFacilityTask->doors_count ?? 0) }}"></div>
                                <div class="col-md-4 mb-3"><label for="sinks_count" class="form-label"><span class="prefix">عدد</span> المغاسل <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="sinks_count" name="sinks_count" min="0" value="{{ old('sinks_count', $sanitationFacilityTask->sinks_count ?? 0) }}"></div>
                                <div class="col-md-4 mb-3"><label for="toilets_count" class="form-label"><span class="prefix">عدد</span> الحمامات <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="toilets_count" name="toilets_count" min="0" value="{{ old('toilets_count', $sanitationFacilityTask->toilets_count ?? 0) }}"></div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">الموارد المستخدمة</h2>
                    </div>
                    <div class="card-body">
                        <div id="resources-repeater">
                            @php $oldResources = old('resources_used', $sanitationFacilityTask->resources_used ?? []); @endphp
                            @forelse ($oldResources as $index => $resource)
                                <div class="row mb-3 resource-item">
                                    <div class="col-md-5 mb-3 mb-md-0"><label class="form-label">اسم المورد</label><input type="text" class="form-control" name="resources_used[{{ $index }}][name]" value="{{ $resource['name'] ?? '' }}" required></div>
                                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">الكمية</label><input type="number" class="form-control" name="resources_used[{{ $index }}][quantity]" min="0" value="{{ $resource['quantity'] ?? '' }}" required></div>
                                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">وحدة القياس</label>
                                        <select class="form-select" name="resources_used[{{ $index }}][unit]" required>
                                            <option value="قطعة" {{ ($resource['unit'] ?? '') == 'قطعة' ? 'selected' : '' }}>قطعة</option>
                                            <option value="كرتون" {{ ($resource['unit'] ?? '') == 'كرتون' ? 'selected' : '' }}>كرتون</option>
                                            <option value="رول" {{ ($resource['unit'] ?? '') == 'رول' ? 'selected' : '' }}>رول</option>
                                            <option value="لتر" {{ ($resource['unit'] ?? '') == 'لتر' ? 'selected' : '' }}>لتر</option>
                                            <option value="عبوة" {{ ($resource['unit'] ?? '') == 'عبوة' ? 'selected' : '' }}>عبوة</option>
                                            <option value="أخرى" {{ ($resource['unit'] ?? '') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-resource"><i class="fas fa-trash"></i></button></div>
                                </div>
                            @empty
                                {{-- لا حاجة لإضافة مورد افتراضي هنا، سيتم التعامل معها من خلال JavaScript إذا كان هناك `old()` --}}
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-resource-button">
                            <i class="fas fa-plus"></i> إضافة مورد جديد
                        </button>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">المنفذون والتقييم</h2>
                    </div>
                    <div class="card-body">
                        <div id="employees-repeater">
                            @php $oldEmployeeTasks = old('employeeTasks', $sanitationFacilityTask->employeeTasks->toArray() ?? []); @endphp
                            @forelse ($oldEmployeeTasks as $index => $employeeTask)
                                <div class="row mb-3 employee-task-item">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label">الموظف</label>
                                        <select class="form-select" name="employeeTasks[{{ $index }}][employee_id]" required>
                                            <option value="">اختر الموظف</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ ($employeeTask['employee_id'] ?? '') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label">تقييم الأداء</label>
                                        <select class="form-select" name="employeeTasks[{{ $index }}][employee_rating]" required>
                                            <option value="">اختر التقييم</option>
                                            <option value="1" {{ ($employeeTask['employee_rating'] ?? '') == 1 ? 'selected' : '' }}>★ (ضعيف)</option>
                                            <option value="2" {{ ($employeeTask['employee_rating'] ?? '') == 2 ? 'selected' : '' }}>★★</option>
                                            <option value="3" {{ ($employeeTask['employee_rating'] ?? '') == 3 ? 'selected' : '' }}>★★★ (متوسط)</option>
                                            <option value="4" {{ ($employeeTask['employee_rating'] ?? '') == 4 ? 'selected' : '' }}>★★★★</option>
                                            <option value="5" {{ ($employeeTask['employee_rating'] ?? '') == 5 ? 'selected' : '' }}>★★★★★ (ممتاز)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button></div>
                                </div>
                            @empty
                                {{-- لا حاجة لإضافة موظف افتراضي هنا، سيتم التعامل معها من خلال JavaScript إذا كان هناك `old()` --}}
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-employee-task-button">
                            <i class="fas fa-plus"></i> إضافة منفذ جديد
                        </button>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">المرفقات</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="before_images" class="form-label">صور قبل التنفيذ</label>
                                <input type="file" class="form-control" id="before_images" name="before_images[]" multiple accept="image/*">
                                <div class="form-text text-muted">يمكنك رفع عدة صور توضح حالة الموقع قبل بدء المهمة.</div>
                                @if ($sanitationFacilityTask->before_images)
                                    <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                        @foreach($sanitationFacilityTask->before_images as $imagePath)
                                            <img src="{{ Storage::url($imagePath) }}" alt="صورة قبل" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="remove_before_images" name="remove_before_images">
                                        <label class="form-check-label" for="remove_before_images">
                                            حذف جميع الصور الحالية (قبل التنفيذ)
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="after_images" class="form-label">صور بعد التنفيذ</label>
                                <input type="file" class="form-control" id="after_images" name="after_images[]" multiple accept="image/*">
                                <div class="form-text text-muted">يمكنك رفع عدة صور توضح حالة الموقع بعد انتهاء المهمة.</div>
                                @if ($sanitationFacilityTask->after_images)
                                    <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                        @foreach($sanitationFacilityTask->after_images as $imagePath)
                                            <img src="{{ Storage::url($imagePath) }}" alt="صورة بعد" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" id="remove_after_images" name="remove_after_images">
                                        <label class="form-check-label" for="remove_after_images">
                                            حذف جميع الصور الحالية (بعد التنفيذ)
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">ملاحظات إضافية</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $sanitationFacilityTask->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">
                    <i class="fas fa-save"></i> تحديث مهمة المنشآت الصحية
                </button>
                <a href="{{ route('sanitation-facility-tasks.index') }}" class="btn btn-secondary mt-4">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </form>
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts') {{-- لربط السكربتات الخاصة بهذه الصفحة --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const taskTypeSelect = document.getElementById('task_type');
            const equipmentDetailsFieldset = document.getElementById('equipment-details-fieldset');
            const prefixes = document.querySelectorAll('.prefix');
            const suffixes = document.querySelectorAll('.suffix');

            function toggleEquipmentFields() {
                const taskType = taskTypeSelect.value;
                if (taskType) {
                    equipmentDetailsFieldset.style.display = 'block';
                    const prefixText = 'عدد'; // Prefix is always 'عدد' as per Filament
                    const suffixText = taskType === 'إدامة' ? 'المدامة' : 'المصانة';

                    prefixes.forEach(el => el.textContent = prefixText);
                    suffixes.forEach(el => el.textContent = suffixText);
                } else {
                    equipmentDetailsFieldset.style.display = 'none';
                }
            }

            taskTypeSelect.addEventListener('change', toggleEquipmentFields);
            toggleEquipmentFields(); // Initial call to set correct visibility based on old input or model data

            // Repeater for Resources
            let resourceIndex = Math.max(0, {{ $sanitationFacilityTask->resources_used ? count($sanitationFacilityTask->resources_used) : 0 }}, {{ old('resources_used') ? count(old('resources_used')) : 0 }});
            document.getElementById('add-resource-button').addEventListener('click', function() {
                const repeaterDiv = document.getElementById('resources-repeater');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'mb-3', 'resource-item');
                newItem.innerHTML = `
                    <div class="col-md-5 mb-3 mb-md-0"><label class="form-label">اسم المورد</label><input type="text" class="form-control" name="resources_used[${resourceIndex}][name]" required></div>
                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">الكمية</label><input type="number" class="form-control" name="resources_used[${resourceIndex}][quantity]" min="0" required></div>
                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">وحدة القياس</label>
                        <select class="form-select" name="resources_used[${resourceIndex}][unit]" required>
                            <option value="قطعة">قطعة</option>
                            <option value="كرتون">كرتون</option>
                            <option value="رول">رول</option>
                            <option value="لتر">لتر</option>
                            <option value="عبوة">عبوة</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-resource"><i class="fas fa-trash"></i></button></div>
                `;
                repeaterDiv.appendChild(newItem);
                resourceIndex++;
            });

            document.getElementById('resources-repeater').addEventListener('click', function(event) {
                if (event.target.closest('.remove-resource')) { // استخدم closest للتأكد من النقر على الزر نفسه
                    event.target.closest('.resource-item').remove();
                }
            });

            // Repeater for Employees
            let employeeIndex = Math.max(0, {{ $sanitationFacilityTask->employeeTasks->count() }}, {{ old('employeeTasks') ? count(old('employeeTasks')) : 0 }});
            document.getElementById('add-employee-task-button').addEventListener('click', function() {
                const repeaterDiv = document.getElementById('employees-repeater');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'mb-3', 'employee-task-item');
                newItem.innerHTML = `
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">الموظف</label>
                        <select class="form-select" name="employeeTasks[${employeeIndex}][employee_id]" required>
                            <option value="">اختر الموظف</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label">تقييم الأداء</label>
                        <select class="form-select" name="employeeTasks[${employeeIndex}][employee_rating]" required>
                            <option value="">اختر التقييم</option>
                            <option value="1">★ (ضعيف)</option>
                            <option value="2">★★</option>
                            <option value="3">★★★ (متوسط)</option>
                            <option value="4">★★★★</option>
                            <option value="5">★★★★★ (ممتاز)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button></div>
                `;
                repeaterDiv.appendChild(newItem);
                employeeIndex++;
            });

            document.getElementById('employees-repeater').addEventListener('click', function(event) {
                if (event.target.closest('.remove-employee-task')) { // استخدم closest للتأكد من النقر على الزر نفسه
                    event.target.closest('.employee-task-item').remove();
                }
            });
        });
    </script>
@endsection
