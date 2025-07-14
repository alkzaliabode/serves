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
            {{-- عرض رسائل النجاح --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- عرض رسائل الأخطاء --}}
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

            {{-- نموذج تعديل مهمة المنشأة الصحية --}}
            <form action="{{ route('sanitation-facility-tasks.update', $sanitationFacilityTask) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- تحديد طريقة HTTP كـ PUT للتعديل --}}

                {{-- القسم الأول: المعلومات الأساسية للمهمة --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">المعلومات الأساسية</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $sanitationFacilityTask->date->format('Y-m-d')) }}" required>
                                @error('date')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="shift" class="form-label">الوجبة <span class="text-danger">*</span></label>
                                <select class="form-select" id="shift" name="shift" required>
                                    <option value="صباحي" {{ old('shift', $sanitationFacilityTask->shift) == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                    <option value="مسائي" {{ old('shift', $sanitationFacilityTask->shift) == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                    <option value="ليلي" {{ old('shift', $sanitationFacilityTask->shift) == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                                </select>
                                @error('shift')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="مكتمل" {{ old('status', $sanitationFacilityTask->status) == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="قيد التنفيذ" {{ old('status', $sanitationFacilityTask->status) == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                    <option value="ملغى" {{ old('status', $sanitationFacilityTask->status) == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                                </select>
                                @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="related_goal_id" class="form-label">الهدف المرتبط <span class="text-danger">*</span></label>
                            <select class="form-select" id="related_goal_id" name="related_goal_id" required>
                                <option value="">اختر الهدف المرتبط</option>
                                @foreach($goals as $goal)
                                    <option value="{{ $goal->id }}" {{ old('related_goal_id', $sanitationFacilityTask->related_goal_id) == $goal->id ? 'selected' : '' }}>{{ $goal->goal_text }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">اختر الهدف الاستراتيجي أو التشغيلي الذي تساهم فيه هذه المهمة.</div>
                            @error('related_goal_id')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="working_hours" class="form-label">إجمالي ساعات العمل للمهمة <span class="text-danger">*</span></label>
                            <input type="number" step="0.5" class="form-control" id="working_hours" name="working_hours" min="0" max="24" value="{{ old('working_hours', $sanitationFacilityTask->working_hours) }}" required>
                            <div class="form-text text-muted">إجمالي ساعات العمل التي استغرقتها هذه المهمة (مثال: 8 أو 4.5).</div>
                            @error('working_hours')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- القسم الثاني: تفاصيل المهمة والمعدات --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">تفاصيل المهمة</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="task_type" class="form-label">نوع المهمة <span class="text-danger">*</span></label>
                                <select class="form-select" id="task_type" name="task_type" required>
                                    <option value="">اختر نوع المهمة</option>
                                    <option value="إدامة" {{ old('task_type', $sanitationFacilityTask->task_type) == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                    <option value="صيانة" {{ old('task_type', $sanitationFacilityTask->task_type) == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                                </select>
                                @error('task_type')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="facility_name" class="form-label">اسم المرفق الصحي <span class="text-danger">*</span></label>
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
                                @error('facility_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="details" class="form-label">تفاصيل إضافية <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="details" name="details" rows="3" required>{{ old('details', $sanitationFacilityTask->details) }}</textarea>
                            <div class="form-text text-muted">وصف تفصيلي للمهمة التي تم إجراؤها.</div>
                            @error('details')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        {{-- تفاصيل المعدات (تظهر بناءً على نوع المهمة) --}}
                        <fieldset id="equipment-details-fieldset" class="mb-3 p-3 border border-dashed border-secondary rounded" style="display: {{ old('task_type', $sanitationFacilityTask->task_type) ? 'block' : 'none' }};">
                            <legend class="text-lg font-semibold mb-3">تفاصيل المعدات</legend>
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label for="seats_count" class="form-label"><span class="prefix">عدد</span> المقاعد <span class="suffix"></span></label>
                                    <input type="number" class="form-control" id="seats_count" name="seats_count" min="0" value="{{ old('seats_count', $sanitationFacilityTask->seats_count ?? 0) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mirrors_count" class="form-label"><span class="prefix">عدد</span> المرايا <span class="suffix"></span></label>
                                    <input type="number" class="form-control" id="mirrors_count" name="mirrors_count" min="0" value="{{ old('mirrors_count', $sanitationFacilityTask->mirrors_count ?? 0) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mixers_count" class="form-label"><span class="prefix">عدد</span> الخلاطات <span class="suffix"></span></label>
                                    <input type="number" class="form-control" id="mixers_count" name="mixers_count" min="0" value="{{ old('mixers_count', $sanitationFacilityTask->mixers_count ?? 0) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="doors_count" class="form-label"><span class="prefix">عدد</span> الأبواب <span class="suffix"></span></label>
                                    <input type="number" class="form-control" id="doors_count" name="doors_count" min="0" value="{{ old('doors_count', $sanitationFacilityTask->doors_count ?? 0) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="sinks_count" class="form-label"><span class="prefix">عدد</span> المغاسل <span class="suffix"></span></label>
                                    <input type="number" class="form-control" id="sinks_count" name="sinks_count" min="0" value="{{ old('sinks_count', $sanitationFacilityTask->sinks_count ?? 0) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="toilets_count" class="form-label"><span class="prefix">عدد</span> الحمامات <span class="suffix"></span></label>
                                    <input type="number" class="form-control" id="toilets_count" name="toilets_count" min="0" value="{{ old('toilets_count', $sanitationFacilityTask->toilets_count ?? 0) }}">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                {{-- القسم الثالث: الموارد المستخدمة (Repeater) --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">الموارد المستخدمة</h2>
                    </div>
                    <div class="card-body">
                        <div id="resources-repeater">
                            @php
                                // دمج البيانات القديمة مع البيانات المحفوظة، مع إعطاء الأولوية للبيانات القديمة
                                $currentResources = old('resources_used', $sanitationFacilityTask->resources_used ?? []);
                            @endphp
                            @forelse ($currentResources as $index => $resource)
                                <div class="row mb-3 resource-item">
                                    <div class="col-md-5 mb-3 mb-md-0">
                                        <label class="form-label">اسم المورد</label>
                                        <input type="text" class="form-control" name="resources_used[{{ $index }}][name]" value="{{ $resource['name'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label class="form-label">الكمية</label>
                                        <input type="number" class="form-control" name="resources_used[{{ $index }}][quantity]" min="0" value="{{ $resource['quantity'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label class="form-label">وحدة القياس</label>
                                        <select class="form-select" name="resources_used[{{ $index }}][unit]" required>
                                            <option value="قطعة" {{ ($resource['unit'] ?? '') == 'قطعة' ? 'selected' : '' }}>قطعة</option>
                                            <option value="كرتون" {{ ($resource['unit'] ?? '') == 'كرتون' ? 'selected' : '' }}>كرتون</option>
                                            <option value="رول" {{ ($resource['unit'] ?? '') == 'رول' ? 'selected' : '' }}>رول</option>
                                            <option value="لتر" {{ ($resource['unit'] ?? '') == 'لتر' ? 'selected' : '' }}>لتر</option>
                                            <option value="عبوة" {{ ($resource['unit'] ?? '') == 'عبوة' ? 'selected' : '' }}>عبوة</option>
                                            <option value="أخرى" {{ ($resource['unit'] ?? '') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0">
                                        <button type="button" class="btn btn-danger remove-resource" title="إزالة المورد"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            @empty
                                {{-- إذا لم تكن هناك موارد موجودة أو قديمة، لا تعرض شيئًا، سيتم إضافتها عبر JS عند الحاجة --}}
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" id="add-resource-button">
                            <i class="fas fa-plus"></i> إضافة مورد جديد
                        </button>
                    </div>
                </div>

                {{-- القسم الرابع: المنفذون والتقييم (Repeater) --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">المنفذون والتقييم</h2>
                    </div>
                    <div class="card-body">
                        <div id="employees-repeater">
                            @php
                                // دمج البيانات القديمة مع البيانات المحفوظة، مع إعطاء الأولوية للبيانات القديمة
                                $currentEmployeeTasks = old('employeeTasks', $sanitationFacilityTask->employeeTasks->toArray() ?? []);
                            @endphp
                            @forelse ($currentEmployeeTasks as $index => $employeeTask)
                                <div class="row mb-3 employee-task-item">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label">الموظف <span class="text-danger">*</span></label>
                                        <select class="form-select" name="employeeTasks[{{ $index }}][employee_id]" required>
                                            <option value="">اختر الموظف</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ ($employeeTask['employee_id'] ?? '') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label">تقييم الأداء <span class="text-danger">*</span></label>
                                        <select class="form-select" name="employeeTasks[{{ $index }}][employee_rating]" required>
                                            <option value="">اختر التقييم</option>
                                            <option value="1" {{ ($employeeTask['employee_rating'] ?? '') == 1 ? 'selected' : '' }}>★ (ضعيف)</option>
                                            <option value="2" {{ ($employeeTask['employee_rating'] ?? '') == 2 ? 'selected' : '' }}>★★</option>
                                            <option value="3" {{ ($employeeTask['employee_rating'] ?? '') == 3 ? 'selected' : '' }}>★★★ (متوسط)</option>
                                            <option value="4" {{ ($employeeTask['employee_rating'] ?? '') == 4 ? 'selected' : '' }}>★★★★</option>
                                            <option value="5" {{ ($employeeTask['employee_rating'] ?? '') == 5 ? 'selected' : '' }}>★★★★★ (ممتاز)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end mb-3 mb-md-0">
                                        <button type="button" class="btn btn-danger remove-employee-task" title="إزالة المنفذ"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            @empty
                                {{-- إذا لم يكن هناك موظفون موجودون أو قدامى، لا تعرض شيئًا، سيتم إضافتها عبر JS عند الحاجة --}}
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" id="add-employee-task-button">
                            <i class="fas fa-plus"></i> إضافة منفذ جديد
                        </button>
                    </div>
                </div>

                {{-- القسم الخامس: المرفقات (صور قبل وبعد) --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">المرفقات</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="before_images" class="form-label">صور قبل التنفيذ</label>
                                <input type="file" class="form-control" id="before_images" name="before_images[]" multiple accept="image/*">
                                <div class="form-text text-muted">يمكنك رفع عدة صور توضح حالة الموقع قبل بدء المهمة. سيتم إضافة الصور الجديدة إلى الصور الموجودة حاليًا ما لم تقم بحذفها.</div>
                                @error('before_images.*')<div class="text-danger">{{ $message }}</div>@enderror
                                @if ($sanitationFacilityTask->before_images) {{-- Changed to use the casted array --}}
                                    <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                        @foreach($sanitationFacilityTask->before_images as $imagePath)
                                            <div class="position-relative">
                                                <img src="{{ Storage::url($imagePath) }}" alt="صورة قبل" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                {{-- يمكنك إضافة زر حذف فردي هنا إذا كنت ترغب بذلك --}}
                                            </div>
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
                                <div class="form-text text-muted">يمكنك رفع عدة صور توضح حالة الموقع بعد انتهاء المهمة. سيتم إضافة الصور الجديدة إلى الصور الموجودة حاليًا ما لم تقم بحذفها.</div>
                                @error('after_images.*')<div class="text-danger">{{ $message }}</div>@enderror
                                @if ($sanitationFacilityTask->after_images) {{-- Changed to use the casted array --}}
                                    <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                        @foreach($sanitationFacilityTask->after_images as $imagePath)
                                            <div class="position-relative">
                                                <img src="{{ Storage::url($imagePath) }}" alt="صورة بعد" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                {{-- يمكنك إضافة زر حذف فردي هنا إذا كنت ترغب بذلك --}}
                                            </div>
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

                {{-- القسم السادس: ملاحظات إضافية --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h2 class="card-title">ملاحظات إضافية</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $sanitationFacilityTask->notes) }}</textarea>
                            <div class="form-text text-muted">أي ملاحظات إضافية أو تعليقات حول المهمة.</div>
                            @error('notes')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- أزرار الإجراءات --}}
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
            const suffixes = document.querySelectorAll('.suffix'); // يجب أن يكون suffixes فقط

            /**
             * تبديل عرض حقول تفاصيل المعدات وتحديث النص اللاحق (suffix) بناءً على نوع المهمة.
             */
            function toggleEquipmentFields() {
                const taskType = taskTypeSelect.value;
                if (taskType) {
                    equipmentDetailsFieldset.style.display = 'block';
                    const suffixText = taskType === 'إدامة' ? 'المدامة' : 'المصانة';

                    suffixes.forEach(el => el.textContent = suffixText);
                } else {
                    equipmentDetailsFieldset.style.display = 'none';
                }
            }

            // الاستماع لتغييرات نوع المهمة وتحديث الحقول
            taskTypeSelect.addEventListener('change', toggleEquipmentFields);
            // استدعاء أولي لضبط الرؤية والنص بناءً على القيمة الحالية عند تحميل الصفحة
            toggleEquipmentFields();

            // -----------------------------------------------------------
            // منطق إضافة/إزالة الموارد (Repeater for Resources)
            // -----------------------------------------------------------
            // حساب أقصى فهرس موجود حاليًا (من البيانات المحفوظة أو القديمة)
            let resourceIndex = 0;
            document.querySelectorAll('#resources-repeater .resource-item').forEach((item, idx) => {
                const nameInput = item.querySelector('input[name^="resources_used["][name$="][name]"]');
                if (nameInput) {
                    const match = nameInput.name.match(/\[(\d+)\]/);
                    if (match && parseInt(match[1]) >= resourceIndex) {
                        resourceIndex = parseInt(match[1]) + 1;
                    }
                }
            });
            // إذا لم يكن هناك موارد موجودة، ابدأ من الصفر
            if (resourceIndex === 0 && document.querySelectorAll('#resources-repeater .resource-item').length === 0) {
                // Initial check for old data from PHP, otherwise defaults to 0
                const initialOldResourcesCount = {{ old('resources_used') ? count(old('resources_used')) : 0 }};
                const initialTaskResourcesCount = {{ $sanitationFacilityTask->resources_used ? count($sanitationFacilityTask->resources_used) : 0 }};
                resourceIndex = Math.max(initialOldResourcesCount, initialTaskResourcesCount);
            }


            document.getElementById('add-resource-button').addEventListener('click', function() {
                const repeaterDiv = document.getElementById('resources-repeater');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'mb-3', 'resource-item');
                newItem.innerHTML = `
                    <div class="col-md-5 mb-3 mb-md-0">
                        <label class="form-label">اسم المورد</label>
                        <input type="text" class="form-control" name="resources_used[${resourceIndex}][name]" required>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label">الكمية</label>
                        <input type="number" class="form-control" name="resources_used[${resourceIndex}][quantity]" min="0" required>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="form-label">وحدة القياس</label>
                        <select class="form-select" name="resources_used[${resourceIndex}][unit]" required>
                            <option value="قطعة">قطعة</option>
                            <option value="كرتون">كرتون</option>
                            <option value="رول">رول</option>
                            <option value="لتر">لتر</option>
                            <option value="عبوة">عبوة</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0">
                        <button type="button" class="btn btn-danger remove-resource" title="إزالة المورد"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                repeaterDiv.appendChild(newItem);
                resourceIndex++; // زيادة الفهرس للعنصر التالي
            });

            // تفويض حدث النقر لأزرار الإزالة (يعمل على العناصر المضافة ديناميكيًا)
            document.getElementById('resources-repeater').addEventListener('click', function(event) {
                if (event.target.closest('.remove-resource')) {
                    event.target.closest('.resource-item').remove();
                }
            });

            // -----------------------------------------------------------
            // منطق إضافة/إزالة المنفذين (Repeater for Employees)
            // -----------------------------------------------------------
            let employeeIndex = 0;
            document.querySelectorAll('#employees-repeater .employee-task-item').forEach((item, idx) => {
                const employeeSelect = item.querySelector('select[name^="employeeTasks["][name$="][employee_id]"]');
                if (employeeSelect) {
                    const match = employeeSelect.name.match(/\[(\d+)\]/);
                    if (match && parseInt(match[1]) >= employeeIndex) {
                        employeeIndex = parseInt(match[1]) + 1;
                    }
                }
            });

            // If no existing employees or old data, initialize index based on max of old and existing
            if (employeeIndex === 0 && document.querySelectorAll('#employees-repeater .employee-task-item').length === 0) {
                 const initialOldEmployeeTasksCount = {{ old('employeeTasks') ? count(old('employeeTasks')) : 0 }};
                 const initialTaskEmployeeTasksCount = {{ $sanitationFacilityTask->employeeTasks ? count($sanitationFacilityTask->employeeTasks) : 0 }};
                 employeeIndex = Math.max(initialOldEmployeeTasksCount, initialTaskEmployeeTasksCount);
            }

            document.getElementById('add-employee-task-button').addEventListener('click', function() {
                const employeesRepeaterDiv = document.getElementById('employees-repeater');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'mb-3', 'employee-task-item');
                newItem.innerHTML = `
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">الموظف <span class="text-danger">*</span></label>
                        <select class="form-select" name="employeeTasks[${employeeIndex}][employee_id]" required>
                            <option value="">اختر الموظف</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label">تقييم الأداء <span class="text-danger">*</span></label>
                        <select class="form-select" name="employeeTasks[${employeeIndex}][employee_rating]" required>
                            <option value="">اختر التقييم</option>
                            <option value="1">★ (ضعيف)</option>
                            <option value="2">★★</option>
                            <option value="3">★★★ (متوسط)</option>
                            <option value="4">★★★★</option>
                            <option value="5">★★★★★ (ممتاز)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mb-3 mb-md-0">
                        <button type="button" class="btn btn-danger remove-employee-task" title="إزالة المنفذ"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                employeesRepeaterDiv.appendChild(newItem);
                employeeIndex++;
            });

            document.getElementById('employees-repeater').addEventListener('click', function(event) {
                if (event.target.closest('.remove-employee-task')) {
                    event.target.closest('.employee-task-item').remove();
                }
            });
        });
    </script>
@endsection