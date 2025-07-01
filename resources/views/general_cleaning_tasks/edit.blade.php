@extends('layouts.adminlte') {{-- تعديل ليرث تخطيط AdminLTE الجديد --}}

@section('title', 'تعديل مهمة نظافة عامة') {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', 'تعديل مهمة نظافة عامة') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('general-cleaning-tasks.index') }}">مهام النظافة العامة</a></li>
    <li class="breadcrumb-item active">تعديل مهمة</li>
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    <div class="card card-primary card-outline"> {{-- استخدام بطاقة AdminLTE --}}
        <div class="card-header">
            <h3 class="card-title">تعديل مهمة نظافة عامة</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('general-cleaning-tasks.update', $generalCleaningTask) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50"> {{-- يمكن استخدام card-body أو card-outline لفصل الأقسام --}}
                    <h2 class="text-xl font-semibold mb-3">المعلومات الأساسية</h2>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label">التاريخ</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $generalCleaningTask->date) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="shift" class="form-label">الوجبة</label>
                            <select class="form-select" id="shift" name="shift" required>
                                <option value="صباحي" {{ old('shift', $generalCleaningTask->shift) == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                <option value="مسائي" {{ old('shift', $generalCleaningTask->shift) == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                <option value="ليلي" {{ old('shift', $generalCleaningTask->shift) == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="مكتمل" {{ old('status', $generalCleaningTask->status) == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                <option value="قيد التنفيذ" {{ old('status', $generalCleaningTask->status) == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="ملغى" {{ old('status', $generalCleaningTask->status) == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="related_goal_id" class="form-label">الهدف المرتبط</label>
                        <select class="form-select" id="related_goal_id" name="related_goal_id" required>
                            <option value="">اختر الهدف المرتبط</option>
                            @foreach($goals as $goal)
                                <option value="{{ $goal->id }}" {{ old('related_goal_id', $generalCleaningTask->related_goal_id) == $goal->id ? 'selected' : '' }}>{{ $goal->goal_text }}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted">اختر الهدف الاستراتيجي أو التشغيلي الذي تساهم فيه هذه المهمة.</div>
                    </div>
                </div>

                <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="text-xl font-semibold mb-3">تفاصيل المهمة</h2>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="task_type" class="form-label">نوع المهمة</label>
                            <select class="form-select" id="task_type" name="task_type" required>
                                <option value="إدامة" {{ old('task_type', $generalCleaningTask->task_type) == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                <option value="صيانة" {{ old('task_type', $generalCleaningTask->task_type) == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="location" class="form-label">الموقع</label>
                            <select class="form-select" id="location" name="location" required>
                                <option value="">اختر الموقع</option>
                                <option value="قاعة 1 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 1 الأسفل' ? 'selected' : '' }}>قاعة 1 الأسفل</option>
                                <option value="قاعة 1 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 1 الأعلى' ? 'selected' : '' }}>قاعة 1 الأعلى</option>
                                <option value="قاعة 2 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 2 الأسفل' ? 'selected' : '' }}>قاعة 2 الأسفل</option>
                                <option value="قاعة 2 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 2 الأعلى' ? 'selected' : '' }}>قاعة 2 الأعلى</option>
                                <option value="قاعة 3 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 3 الأسفل' ? 'selected' : '' }}>قاعة 3 الأسفل</option>
                                <option value="قاعة 3 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 3 الأعلى' ? 'selected' : '' }}>قاعة 3 الأعلى</option>
                                <option value="قاعة 4 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 4 الأسفل' ? 'selected' : '' }}>قاعة 4 الأسفل</option>
                                <option value="قاعة 4 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 4 الأعلى' ? 'selected' : '' }}>قاعة 4 الأعلى</option>
                                <option value="المطبخ الشمالي" {{ old('location', $generalCleaningTask->location) == 'المطبخ الشمالي' ? 'selected' : '' }}>المطبخ الشمالي</option>
                                <option value="المطبخ الجنوبي" {{ old('location', $generalCleaningTask->location) == 'المطبخ الجنوبي' ? 'selected' : '' }}>المطبخ الجنوبي</option>
                                <option value="الترامز" {{ old('location', $generalCleaningTask->location) == 'الترامز' ? 'selected' : '' }}>الترامز</option>
                                <option value="السجاد" {{ old('location', $generalCleaningTask->location) == 'السجاد' ? 'selected' : '' }}>السجاد</option>
                                <option value="الحاويات" {{ old('location', $generalCleaningTask->location) == 'الحاويات' ? 'selected' : '' }}>الحاويات</option>
                                <option value="الجامع" {{ old('location', $generalCleaningTask->location) == 'الجامع' ? 'selected' : '' }}>الجامع</option>
                                <option value="المركز الصحي" {{ old('location', $generalCleaningTask->location) == 'المركز الصحي' ? 'selected' : '' }}>الالمركز الصحي</option>
                                <option value="جميع القواطع الخارجية" {{ old('location', $generalCleaningTask->location) == 'جميع القواطع الخارجية' ? 'selected' : '' }}>جميع القواطع الخارجية</option>
                                <option value="المرافق الصحية" {{ old('location', $generalCleaningTask->location) == 'المرافق الصحية' ? 'selected' : '' }}>المرافق الصحية</option>
                            </select>
                        </div>
                    </div>

                    <fieldset id="task-details-fieldset" class="mb-3 p-3 border border-dashed border-secondary rounded" style="display: {{ old('location', $generalCleaningTask->location) ? 'block' : 'none' }};">
                        <legend class="text-lg font-semibold mb-3">تفاصيل التنفيذ</legend>
                        <div id="room-fields" class="row mb-3" style="display: none;">
                            <div class="col-md-3 mb-3"><label for="mats_count" class="form-label">عدد المنادر المدامة</label><input type="number" class="form-control" id="mats_count" name="mats_count" min="0" value="{{ old('mats_count', $generalCleaningTask->mats_count ?? 0) }}"></div>
                            <div class="col-md-3 mb-3"><label for="pillows_count" class="form-label">عدد الوسادات المدامة</label><input type="number" class="form-control" id="pillows_count" name="pillows_count" min="0" value="{{ old('pillows_count', $generalCleaningTask->pillows_count ?? 0) }}"></div>
                            <div class="col-md-3 mb-3"><label for="fans_count" class="form-label">عدد المراوح المدامة</label><input type="number" class="form-control" id="fans_count" name="fans_count" min="0" value="{{ old('fans_count', $generalCleaningTask->fans_count ?? 0) }}"></div>
                            <div class="col-md-3 mb-3"><label for="windows_count" class="form-label">عدد النوافذ المدامة</label><input type="number" class="form-control" id="windows_count" name="windows_count" min="0" value="{{ old('windows_count', $generalCleaningTask->windows_count ?? 0) }}"></div>
                            <div class="col-md-3 mb-3"><label for="carpets_count" class="form-label">عدد السجاد المدام</label><input type="number" class="form-control" id="carpets_count" name="carpets_count" min="0" value="{{ old('carpets_count', $generalCleaningTask->carpets_count ?? 0) }}"></div>
                            <div class="col-md-3 mb-3"><label for="blankets_count" class="form-label">عدد البطانيات المدامة</label><input type="number" class="form-control" id="blankets_count" name="blankets_count" min="0" value="{{ old('blankets_count', $generalCleaningTask->blankets_count ?? 0) }}"></div>
                            <div class="col-md-3 mb-3"><label for="beds_count" class="form-label">عدد الأسرة</label><input type="number" class="form-control" id="beds_count" name="beds_count" min="0" value="{{ old('beds_count', $generalCleaningTask->beds_count ?? 0) }}"></div>
                            <div class="col-md-3 mb-3"><label for="beneficiaries_count" class="form-label">عدد المستفيدين من القاعة</label><input type="number" class="form-control" id="beneficiaries_count" name="beneficiaries_count" min="0" value="{{ old('beneficiaries_count', $generalCleaningTask->beneficiaries_count ?? 0) }}"></div>
                        </div>
                        <div id="trams-fields" class="row mb-3" style="display: none;">
                            <div class="col-md-6 mb-3"><label for="filled_trams_count" class="form-label">عدد الترامز المملوئة والمدامة</label><input type="number" class="form-control" id="filled_trams_count" name="filled_trams_count" min="0" value="{{ old('filled_trams_count', $generalCleaningTask->filled_trams_count ?? 0) }}"></div>
                        </div>
                        <div id="carpets-laid-fields" class="row mb-3" style="display: none;">
                            <div class="col-md-6 mb-3"><label for="carpets_laid_count" class="form-label">عدد السجاد المفروش في الساحات</label><input type="number" class="form-control" id="carpets_laid_count" name="carpets_laid_count" min="0" value="{{ old('carpets_laid_count', $generalCleaningTask->carpets_laid_count ?? 0) }}"></div>
                        </div>
                        <div id="containers-fields" class="row mb-3" style="display: none;">
                            <div class="col-md-6 mb-3"><label for="large_containers_count" class="form-label">عدد الحاويات الكبيرة المفرغة والمدامة</label><input type="number" class="form-control" id="large_containers_count" name="large_containers_count" min="0" value="{{ old('large_containers_count', $generalCleaningTask->large_containers_count ?? 0) }}"></div>
                            <div class="col-md-6 mb-3"><label for="small_containers_count" class="form-label">عدد الحاويات الصغيرة المفرغة والمدامة</label><input type="number" class="form-control" id="small_containers_count" name="small_containers_count" min="0" value="{{ old('small_containers_count', $generalCleaningTask->small_containers_count ?? 0) }}"></div>
                        </div>
                        <div id="maintenance-details-fields" class="row mb-3" style="display: none;">
                            <div class="col-md-12 mb-3"><label for="maintenance_details" class="form-label">تفاصيل الإدامة اليومية</label><textarea class="form-control" id="maintenance_details" name="maintenance_details" rows="3">{{ old('maintenance_details', $generalCleaningTask->maintenance_details) }}</textarea></div>
                        </div>
                    </fieldset>

                    <fieldset id="external-partitions-fieldset" class="mb-3 p-3 border border-dashed border-secondary rounded" style="display: {{ old('location', $generalCleaningTask->location) == 'جميع القواطع الخارجية' ? 'block' : 'none' }};">
                        <legend class="text-lg font-semibold mb-3">تفاصيل القواطع الخارجية</legend>
                        <div class="mb-3">
                            <label for="external_partitions_count" class="form-label">عدد القواطع الخارجية المدامة</label>
                            <input type="number" class="form-control" id="external_partitions_count" name="external_partitions_count" min="0" value="{{ old('external_partitions_count', $generalCleaningTask->external_partitions_count ?? 0) }}">
                        </div>
                    </fieldset>
                </div>

                <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="text-xl font-semibold mb-3">الموارد المستخدمة وساعات العمل</h2>
                    <div class="mb-3">
                        <label for="working_hours" class="form-label">إجمالي ساعات العمل للمهمة</label>
                        <input type="number" step="0.5" class="form-control" id="working_hours" name="working_hours" min="0" max="24" value="{{ old('working_hours', $generalCleaningTask->working_hours) }}" required>
                        <div class="form-text text-muted">إجمالي ساعات العمل التي استغرقتها هذه المهمة.</div>
                    </div>

                    <h3 class="text-lg font-semibold mb-3">الموارد الأخرى المستخدمة</h3>
                    <div id="resources-repeater">
                        @php $oldResources = old('resources_used', $generalCleaningTask->resources_used ?? []); @endphp
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
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-resource-button">
                        <i class="fas fa-plus"></i> إضافة مورد جديد
                    </button>
                </div>

                <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="text-xl font-semibold mb-3">المنفذون والتقييم</h2>
                    <div id="employees-repeater">
                        @php $oldEmployeeTasks = old('employeeTasks', $generalCleaningTask->employeeTasks->toArray() ?? []); @endphp
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
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-employee-task-button">
                        <i class="fas fa-plus"></i> إضافة منفذ جديد
                    </button>
                </div>

                <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="text-xl font-semibold mb-3">المرفقات</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="before_images" class="form-label">صور قبل التنفيذ</label>
                            <input type="file" class="form-control" id="before_images" name="before_images[]" multiple accept="image/*">
                            <div class="form-text text-muted">يمكنك رفع عدة صور توضح حالة الموقع قبل بدء المهمة.</div>
                            @if ($generalCleaningTask->before_images)
                                <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                    @foreach($generalCleaningTask->before_images as $imagePath)
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
                            @if ($generalCleaningTask->after_images)
                                <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                    @foreach($generalCleaningTask->after_images as $imagePath)
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

                <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h2 class="text-xl font-semibold mb-3">ملاحظات إضافية</h2>
                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $generalCleaningTask->notes) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">
                    <i class="fas fa-save"></i> تحديث مهمة النظافة
                </button>
                <a href="{{ route('general-cleaning-tasks.index') }}" class="btn btn-secondary mt-4">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </form>
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts') {{-- لربط السكربتات الخاصة بهذه الصفحة --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationSelect = document.getElementById('location');
            const taskDetailsFieldset = document.getElementById('task-details-fieldset');
            const externalPartitionsFieldset = document.getElementById('external-partitions-fieldset');

            const roomFields = document.getElementById('room-fields');
            const tramsFields = document.getElementById('trams-fields');
            const carpetsLaidFields = document.getElementById('carpets-laid-fields');
            const containersFields = document.getElementById('containers-fields');
            const maintenanceDetailsFields = document.getElementById('maintenance-details-fields');

            function toggleLocationFields() {
                const location = locationSelect.value;

                // Hide all location-specific fields first
                roomFields.style.display = 'none';
                tramsFields.style.display = 'none';
                carpetsLaidFields.style.display = 'none';
                containersFields.style.display = 'none';
                maintenanceDetailsFields.style.display = 'none';
                externalPartitionsFieldset.style.display = 'none';
                taskDetailsFieldset.style.display = 'none'; // Hide main fieldset by default

                if (location) {
                    taskDetailsFieldset.style.display = 'block'; // Show main fieldset if location is selected
                    if (location.includes('قاعة') || location.includes('المطبخ')) {
                        roomFields.style.display = 'flex'; // Use flex for grid layout
                        maintenanceDetailsFields.style.display = 'block'; // Show always with rooms/kitchens
                    } else if (location === 'الترامز') {
                        tramsFields.style.display = 'flex';
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'السجاد') {
                        carpetsLaidFields.style.display = 'flex';
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'الحاويات') {
                        containersFields.style.display = 'flex';
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'الجامع' || location === 'المركز الصحي' || location === 'المرافق الصحية') {
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'جميع القواطع الخارجية') {
                        maintenanceDetailsFields.style.display = 'block';
                        externalPartitionsFieldset.style.display = 'block';
                    }
                }
            }

            locationSelect.addEventListener('change', toggleLocationFields);
            toggleLocationFields(); // Initial call to set correct visibility based on old input or model data

            // Repeater for Resources
            let resourceIndex = Math.max(0, {{ $generalCleaningTask->resources_used ? count($generalCleaningTask->resources_used) : 0 }}, {{ old('resources_used') ? count(old('resources_used')) : 0 }});
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
            let employeeIndex = Math.max(0, {{ $generalCleaningTask->employeeTasks->count() }}, {{ old('employeeTasks') ? count(old('employeeTasks')) : 0 }});
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
