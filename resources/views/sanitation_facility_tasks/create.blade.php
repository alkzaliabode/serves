@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'إنشاء مهمة منشأة صحية') {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', 'إنشاء مهمة منشأة صحية جديدة') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sanitation-facility-tasks.index') }}">مهام المنشآت الصحية</a></li>
    <li class="breadcrumb-item active">إنشاء مهمة</li>
@endsection

@section('styles')
    <style>
        /* Define an accent color variable for distinctiveness */
        :root {
            --accent-color: #00eaff; /* Light blue/cyan for interactive elements and emphasis */
        }

        /* أنماط البطاقات لتكون شفافة بالكامل مع تأثير زجاجي وخطوط بارزة (تأثير الزجاج المتجمد) */
        .card {
            background: rgba(255, 255, 255, 0.08) !important; /* شفافية عالية جداً */
            backdrop-filter: blur(8px) !important; /* تأثير الزجاج المتجمد */
            border-radius: 1rem !important; /* حواف مستديرة */
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important; /* ظل أنعم */
            border: 1px solid rgba(255, 255, 255, 0.2) !important; /* حدود بارزة وواضحة */
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.15) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important; /* حدود سفلية شفافة وواضحة */
        }
        
        /* General text size increase and distinctive color for main texts */
        body,
        .card-body {
            font-size: 1.1rem !important; /* Slightly larger body text */
            line-height: 1.7 !important; /* Improved line spacing for readability */
            color: white !important; /* لون نص أبيض لجسم البطاقة */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6) !important; /* ظل خفيف للنص */
        }

        /* Titles and Headers - make them more prominent and interactive */
        .card-title,
        .card-header h3.card-title, /* Target the h3 specifically */
        .card-header h2.card-title, /* Target the h2 specifically */
        .card-header .btn {
            font-size: 1.8rem !important; /* Larger titles */
            font-weight: 700 !important; /* Bolder */
            color: var(--accent-color) !important; /* Distinctive color for titles */
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9) !important; /* Stronger shadow */
            transition: color 0.3s ease, text-shadow 0.3s ease; /* Smooth transition */
        }
        .card-title:hover,
        .card-header h3.card-title:hover,
        .card-header h2.card-title:hover {
            color: #ffffff !important; /* Change color on hover for interactivity */
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 1.0) !important;
        }

        /* أنماط حقول الإدخال والاختيار والتكست اريا */
        .form-control,
        .form-select,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            background-color: rgba(255, 255, 255, 0.1) !important; /* شفافية عالية جدًا لحقول الإدخال */
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important; /* لون نص أبيض للحقول */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6) !important;
            font-size: 1.2rem !important; /* Larger text inside inputs */
            padding: 0.8rem 1.2rem !important; /* More padding for better feel */
            border-radius: 0.5rem !important; /* Rounded corners for inputs */
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7) !important; /* لون أفتح لـ placeholder */
        }
        .form-control:focus,
        .form-select:focus,
        input:focus,
        textarea:focus,
        select:focus {
            background-color: rgba(255, 255, 255, 0.2) !important; /* يصبح أكثر شفافية عند التركيز */
            border-color: var(--accent-color) !important; /* Highlight with accent color on focus */
            box-shadow: 0 0 0 0.3rem rgba(0, 234, 255, 0.4) !important; /* Glow effect on focus */
        }
        .form-select option {
            background-color: #2c3e50 !important; /* خلفية داكنة لخيار القائمة */
            color: white !important; /* نص أبيض لخيار القائمة */
        }

        /* أنماط تسميات الحقول - bigger and more distinct */
        .form-label,
        label {
            font-size: 1.2rem !important; /* Larger labels */
            font-weight: 600 !important; /* Bolder */
            color: var(--accent-color) !important; /* Distinctive color for labels */
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8) !important;
            margin-bottom: 0.5rem; /* Space below labels */
            display: block; /* Ensure labels take full width */
        }

        /* أنماط الأزرار */
        .btn {
            font-weight: 600; /* Make button text bolder */
            padding: 0.7rem 1.4rem; /* Adjust padding for larger text */
            border-radius: 0.75rem; /* More rounded buttons */
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease; /* Add transform and box-shadow to transition */
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.5) !important; /* ظل للأزرار */
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-3px); /* Slight lift on hover */
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.7) !important;
            filter: brightness(1.15); /* Slightly brighter on hover */
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.5) !important;
        }
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 117, 125, 0.7) !important;
            filter: brightness(1.15);
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.5) !important;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.7) !important;
            filter: brightness(1.15);
        }

        /* أنماط الأيقونات في الأزرار */
        .btn .fas {
            margin-right: 8px; /* مسافة بين الأيقونة والنص */
            font-size: 1.1rem; /* Larger icon */
        }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important; /* خلفية شفافة للرسائل */
            color: #333 !important; /* لون نص داكن */
            border-color: rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.75rem; /* More rounded alerts */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25); /* Stronger shadow */
            font-size: 1.1rem !important; /* Larger alert text */
            padding: 1.25rem 1.5rem !important; /* More padding */
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.95) !important; /* خلفية خضراء شفافة للنجاح */
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.95) !important; /* خلفية حمراء شفافة للخطأ */
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* أنماط النص المساعد (form-text) - Slightly larger helper text */
        .form-text {
            font-size: 1rem !important; /* Larger helper text */
            color: rgba(255, 255, 255, 0.8) !important; /* لون أبيض شفاف للنص المساعد */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6) !important;
            margin-top: 0.4rem; /* Space above helper text */
        }

        /* أنماط Fieldset وتفاصيلها - more prominent */
        fieldset {
            border: 1px solid rgba(255, 255, 255, 0.3) !important; /* حدود شفافة */
            padding: 2rem !important; /* More padding */
            border-radius: 1rem !important; /* More rounded */
            margin-bottom: 2rem !important; /* More space below */
            background-color: rgba(255, 255, 255, 0.05); /* خلفية خفيفة جداً للـ fieldset */
            box-shadow: inset 0 0 10px rgba(0, 234, 255, 0.1); /* Subtle inner glow */
        }
        fieldset legend {
            font-size: 1.8rem !important; /* Even larger for legends */
            font-weight: 700 !important;
            color: var(--accent-color) !important; /* Distinctive color for legends */
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9) !important;
            padding: 0 0.8rem; /* More padding for legend text */
            border-bottom: none; /* إزالة الخط الافتراضي */
            width: auto; /* يجعل الـ legend يأخذ عرض محتواه فقط */
            background-color: rgba(44, 62, 80, 0.7); /* Darker background for legend */
            border-radius: 0.5rem; /* Rounded corners for legend background */
            margin-bottom: 1rem; /* Space below legend */
        }

        /* لضمان شفافية العناصر الداخلية لـ Livewire أو Jetstream */
        .bg-white,
        .shadow.sm\:rounded-lg,
        .px-4.py-5.sm\:p-6,
        .sm\:px-6.lg\:px-8,
        .max-w-7xl.mx-auto.py-10.sm\:px-6.lg\:px-8,
        .w-full.bg-white.shadow.overflow-hidden.sm\:rounded-lg,
        .w-full.bg-gray-800.sm\:rounded-lg.shadow,
        .border-gray-200.dark\:border-gray-700,
        div[x-data] { /* استهداف عام لأي divs تنشئها Livewire / Alpine.js */
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }

        .form-group,
        form > div,
        .input-group,
        .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-12,
        div[class*="col-"] {
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }

        /* Adjustments for repeater labels to ensure they are distinctive */
        #resources-repeater .form-label,
        #employees-repeater .form-label {
            color: var(--accent-color) !important;
            font-size: 1.15rem !important; /* Slightly smaller than main labels but still prominent */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7) !important;
        }

        /* Specific styling for repeater items to make them visually distinct */
        .resource-item,
        .employee-task-item {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    <div class="container-fluid">
        <div class="card"> {{-- استخدام بطاقة AdminLTE --}}
            <div class="card-header">
                <h3 class="card-title">إنشاء مهمة منشأة صحية جديدة</h3>
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

                <form action="{{ route('sanitation-facility-tasks.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card card-info card-outline"> {{-- استخدام بطاقة AdminLTE كقسم للنموذج --}}
                        <div class="card-header">
                            <h2 class="card-title">المعلومات الأساسية</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4"> {{-- Increased margin-bottom for better spacing --}}
                                <div class="col-md-4 mb-3"> {{-- Added mb-3 for consistent spacing on small screens --}}
                                    <label for="date" class="form-label">التاريخ</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="shift" class="form-label">الوجبة</label>
                                    <select class="form-select" id="shift" name="shift" required>
                                        <option value="صباحي" {{ old('shift') == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                        <option value="مسائي" {{ old('shift') == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                        <option value="ليلي" {{ old('shift') == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="مكتمل" {{ old('status') == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="قيد التنفيذ" {{ old('status') == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                        <option value="ملغى" {{ old('status') == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="related_goal_id" class="form-label">الهدف المرتبط</label>
                                <select class="form-select" id="related_goal_id" name="related_goal_id" required>
                                    <option value="">اختر الهدف المرتبط</option>
                                    @foreach($goals as $goal)
                                        <option value="{{ $goal->id }}" {{ old('related_goal_id') == $goal->id ? 'selected' : '' }}>{{ $goal->goal_text }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">اختر الهدف الاستراتيجي أو التشغيلي الذي تساهم فيه هذه المهمة.</div>
                            </div>
                            <div class="mb-4">
                                <label for="working_hours" class="form-label">إجمالي ساعات العمل للمهمة</label>
                                <input type="number" step="0.5" class="form-control" id="working_hours" name="working_hours" min="0" max="24" value="{{ old('working_hours') }}" required>
                                <div class="form-text">إجمالي ساعات العمل التي استغرقتها هذه المهمة.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">تفاصيل المهمة</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="task_type" class="form-label">نوع المهمة</label>
                                    <select class="form-select" id="task_type" name="task_type" required>
                                        <option value="">اختر نوع المهمة</option>
                                        <option value="إدامة" {{ old('task_type') == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                        <option value="صيانة" {{ old('task_type') == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="facility_name" class="form-label">اسم المرفق الصحي</label>
                                    <select class="form-select" id="facility_name" name="facility_name" required>
                                        <option value="">اختر المرفق الصحي</option>
                                        <option value="صحية الجامع رجال" {{ old('facility_name') == 'صحية الجامع رجال' ? 'selected' : '' }}>صحية الجامع رجال</option>
                                        <option value="صحية الجامع نساء" {{ old('facility_name') == 'صحية الجامع نساء' ? 'selected' : '' }}>صحية الجامع نساء</option>
                                        <option value="صحية 1 رجال" {{ old('facility_name') == 'صحية 1 رجال' ? 'selected' : '' }}>صحية 1 رجال</option>
                                        <option value="صحية 2 رجال" {{ old('facility_name') == 'صحية 2 رجال' ? 'selected' : '' }}>صحية 2 رجال</option>
                                        <option value="صحية 3 رجال" {{ old('facility_name') == 'صحية 3 رجال' ? 'selected' : '' }}>صحية 3 رجال</option>
                                        <option value="صحية 4 رجال" {{ old('facility_name') == 'صحية 4 رجال' ? 'selected' : '' }}>صحية 4 رجال</option>
                                        <option value="صحية 1 نساء" {{ old('facility_name') == 'صحية 1 نساء' ? 'selected' : '' }}>صحية 1 نساء</option>
                                        <option value="صحية 2 نساء" {{ old('facility_name') == 'صحية 2 نساء' ? 'selected' : '' }}>صحية 2 نساء</option>
                                        <option value="صحية 3 نساء" {{ old('facility_name') == 'صحية 3 نساء' ? 'selected' : '' }}>صحية 3 نساء</option>
                                        <option value="صحية 4 نساء" {{ old('facility_name') == 'صحية 4 نساء' ? 'selected' : '' }}>صحية 4 نساء</option>
                                        <option value="المجاميع الكبيرة رجال" {{ old('facility_name') == 'المجاميع الكبيرة رجال' ? 'selected' : '' }}>المجاميع الكبيرة رجال</option>
                                        <option value="المجاميع الكبيرة نساء" {{ old('facility_name') == 'المجاميع الكبيرة نساء' ? 'selected' : '' }}>المجاميع الكبيرة نساء</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="details" class="form-label">تفاصيل إضافية</label>
                                <textarea class="form-control" id="details" name="details" rows="5" placeholder="أضف تفاصيل إضافية هنا..." required>{{ old('details') }}</textarea>
                            </div>

                            <fieldset id="equipment-details-fieldset" class="mb-4" style="display: {{ old('task_type') ? 'block' : 'none' }};">
                                <legend>تفاصيل المعدات</legend>
                                <div class="row mb-3">
                                    <div class="col-md-4 mb-3"><label for="seats_count" class="form-label"><span class="prefix">عدد</span> المقاعد <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="seats_count" name="seats_count" min="0" value="{{ old('seats_count', 0) }}"></div>
                                    <div class="col-md-4 mb-3"><label for="mirrors_count" class="form-label"><span class="prefix">عدد</span> المرايا <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="mirrors_count" name="mirrors_count" min="0" value="{{ old('mirrors_count', 0) }}"></div>
                                    <div class="col-md-4 mb-3"><label for="mixers_count" class="form-label"><span class="prefix">عدد</span> الخلاطات <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="mixers_count" name="mixers_count" min="0" value="{{ old('mixers_count', 0) }}"></div>
                                    <div class="col-md-4 mb-3"><label for="doors_count" class="form-label"><span class="prefix">عدد</span> الأبواب <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="doors_count" name="doors_count" min="0" value="{{ old('doors_count', 0) }}"></div>
                                    <div class="col-md-4 mb-3"><label for="sinks_count" class="form-label"><span class="prefix">عدد</span> المغاسل <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="sinks_count" name="sinks_count" min="0" value="{{ old('sinks_count', 0) }}"></div>
                                    <div class="col-md-4 mb-3"><label for="toilets_count" class="form-label"><span class="prefix">عدد</span> الحمامات <span class="suffix">المدامة</span></label><input type="number" class="form-control" id="toilets_count" name="toilets_count" min="0" value="{{ old('toilets_count', 0) }}"></div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">الموارد المستخدمة</h2>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title mb-4" style="color: white; text-shadow: 1px 1px 3px rgba(0,0,0,0.8); font-size: 1.5rem !important;">الموارد الأخرى المستخدمة</h3>
                            <div id="resources-repeater">
                                @if (old('resources_used'))
                                    @foreach (old('resources_used') as $index => $resource)
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
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary mt-3" id="add-resource-button">
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
                                @if (old('employeeTasks'))
                                    @foreach (old('employeeTasks') as $index => $employeeTask)
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
                                                    <option value="1" {{ (isset($employeeTask['employee_rating']) && $employeeTask['employee_rating'] == '1') ? 'selected' : '' }}>ضعيف</option>
                                                    <option value="2" {{ (isset($employeeTask['employee_rating']) && $employeeTask['employee_rating'] == '2') ? 'selected' : '' }}>مقبول</option>
                                                    <option value="3" {{ (isset($employeeTask['employee_rating']) && $employeeTask['employee_rating'] == '3') ? 'selected' : '' }}>جيد</option>
                                                    <option value="4" {{ (isset($employeeTask['employee_rating']) && $employeeTask['employee_rating'] == '4') ? 'selected' : '' }}>جيد جدا</option>
                                                    <option value="5" {{ (isset($employeeTask['employee_rating']) && $employeeTask['employee_rating'] == '5') ? 'selected' : '' }}>ممتاز</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button></div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary mt-3" id="add-employee-task-button">
                                <i class="fas fa-user-plus"></i> إضافة منفذ جديد
                            </button>

                            {{-- تم حذف حقل تقييم المشرف العام --}}
                            {{--
                            <div class="mb-4 mt-4">
                                <label for="supervisor_rating" class="form-label">تقييم المشرف العام للمهمة</label>
                                <select class="form-select" id="supervisor_rating" name="supervisor_rating" required>
                                    <option value="">اختر التقييم</option>
                                    <option value="ممتاز" {{ old('supervisor_rating') == 'ممتاز' ? 'selected' : '' }}>ممتاز</option>
                                    <option value="جيد جدا" {{ old('supervisor_rating') == 'جيد جدا' ? 'selected' : '' }}>جيد جدا</option>
                                    <option value="جيد" {{ old('supervisor_rating') == 'جيد' ? 'selected' : '' }}>جيد</option>
                                    <option value="مقبول" {{ old('supervisor_rating') == 'مقبول' ? 'selected' : '' }}>مقبول</option>
                                    <option value="ضعيف" {{ old('supervisor_rating') == 'ضعيف' ? 'selected' : '' }}>ضعيف</option>
                                </select>
                                <div class="form-text">تقييم المشرف لأداء المهمة بشكل عام.</div>
                            </div>
                            --}}

                            <div class="mb-4">
                                <label for="notes" class="form-label">ملاحظات إضافية</label>
                                <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="أضف أي ملاحظات إضافية هنا...">{{ old('notes') }}</textarea>
                                <div class="form-text">أي تفاصيل أو ملاحظات أخرى تتعلق بالمهمة.</div>
                            </div>

                            <div class="mb-4">
                                <label for="before_images" class="form-label">صور قبل التنفيذ</label>
                                <input type="file" class="form-control" id="before_images" name="before_images[]" multiple accept="image/*">
                                <div class="form-text">يمكنك رفع عدة صور توضح حالة الموقع قبل بدء المهمة.</div>
                            </div>
                            <div class="mb-4">
                                <label for="after_images" class="form-label">صور بعد التنفيذ</label>
                                <input type="file" class="form-control" id="after_images" name="after_images[]" multiple accept="image/*">
                                <div class="form-text">يمكنك رفع عدة صور توضح حالة الموقع بعد الانتهاء من المهمة.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ المهمة
                        </button>
                        <a href="{{ route('sanitation-facility-tasks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-ban"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic to show/hide equipment details based on task type
            const taskTypeSelect = document.getElementById('task_type');
            const equipmentDetailsFieldset = document.getElementById('equipment-details-fieldset');

            function toggleEquipmentDetails() {
                if (taskTypeSelect.value === 'صيانة') {
                    equipmentDetailsFieldset.style.display = 'block';
                    // Make fields required for 'صيانة'
                    equipmentDetailsFieldset.querySelectorAll('input[type="number"]').forEach(input => {
                        input.setAttribute('required', 'required');
                    });
                } else {
                    equipmentDetailsFieldset.style.display = 'none';
                    // Remove required attribute if not 'صيانة'
                    equipmentDetailsFieldset.querySelectorAll('input[type="number"]').forEach(input => {
                        input.removeAttribute('required');
                        input.value = 0; // Reset values when hidden
                    });
                }
            }

            taskTypeSelect.addEventListener('change', toggleEquipmentDetails);
            toggleEquipmentDetails(); // Call on load to set initial state

            // Repeater for Resources Used
            const resourcesRepeater = document.getElementById('resources-repeater');
            const addResourceButton = document.getElementById('add-resource-button');
            let resourceIndex = resourcesRepeater.children.length; // Start index from existing items

            function addResourceItem(name = '', quantity = '', unit = '') {
                const newResourceItem = document.createElement('div');
                newResourceItem.classList.add('row', 'mb-3', 'resource-item');
                newResourceItem.innerHTML = `
                    <div class="col-md-5 mb-3 mb-md-0"><label class="form-label">اسم المورد</label><input type="text" class="form-control" name="resources_used[${resourceIndex}][name]" value="${name}" required></div>
                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">الكمية</label><input type="number" class="form-control" name="resources_used[${resourceIndex}][quantity]" min="0" value="${quantity}" required></div>
                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">وحدة القياس</label>
                        <select class="form-select" name="resources_used[${resourceIndex}][unit]" required>
                            <option value="قطعة" ${unit === 'قطعة' ? 'selected' : ''}>قطعة</option>
                            <option value="كرتون" ${unit === 'كرتون' ? 'selected' : ''}>كرتون</option>
                            <option value="رول" ${unit === 'رول' ? 'selected' : ''}>رول</option>
                            <option value="لتر" ${unit === 'لتر' ? 'selected' : ''}>لتر</option>
                            <option value="عبوة" ${unit === 'عبوة' ? 'selected' : ''}>عبوة</option>
                            <option value="أخرى" ${unit === 'أخرى' ? 'selected' : ''}>أخرى</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-resource"><i class="fas fa-trash"></i></button></div>
                `;
                resourcesRepeater.appendChild(newResourceItem);
                resourceIndex++;
            }

            addResourceButton.addEventListener('click', function() {
                addResourceItem();
            });

            resourcesRepeater.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-resource') || e.target.closest('.remove-resource')) {
                    e.target.closest('.resource-item').remove();
                }
            });

            // Repeater for Employees and Ratings
            const employeesRepeater = document.getElementById('employees-repeater');
            const addEmployeeTaskButton = document.getElementById('add-employee-task-button');
            let employeeTaskIndex = employeesRepeater.children.length; // Start index from existing items

            function addEmployeeTaskItem(employeeId = '', rating = '') {
                const newEmployeeTaskItem = document.createElement('div');
                newEmployeeTaskItem.classList.add('row', 'mb-3', 'employee-task-item');
                newEmployeeTaskItem.innerHTML = `
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">الموظف</label>
                        <select class="form-select" name="employeeTasks[${employeeTaskIndex}][employee_id]" required>
                            <option value="">اختر الموظف</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" ${employeeId == {{ $employee->id }} ? 'selected' : ''}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label">تقييم الأداء</label>
                        <select class="form-select" name="employeeTasks[${employeeTaskIndex}][employee_rating]" required>
                            <option value="">اختر التقييم</option>
                            <option value="1" ${rating == '1' ? 'selected' : ''}>ضعيف</option>
                            <option value="2" ${rating == '2' ? 'selected' : ''}>مقبول</option>
                            <option value="3" ${rating == '3' ? 'selected' : ''}>جيد</option>
                            <option value="4" ${rating == '4' ? 'selected' : ''}>جيد جدا</option>
                            <option value="5" ${rating == '5' ? 'selected' : ''}>ممتاز</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button></div>
                `;
                employeesRepeater.appendChild(newEmployeeTaskItem);
                employeeTaskIndex++;
            }

            addEmployeeTaskButton.addEventListener('click', function() {
                addEmployeeTaskItem();
            });

            employeesRepeater.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-employee-task') || e.target.closest('.remove-employee-task')) {
                    e.target.closest('.employee-task-item').remove();
                }
            });

            // Handle old input for repeaters
            @if (old('resources_used'))
                // No need to re-add, they are already rendered by Blade
            @else
                // Add one empty resource item if no old input exists
                // addResourceItem(); // Commented out to avoid adding an empty row by default
            @endif

            @if (old('employeeTasks'))
                // No need to re-add, they are already rendered by Blade
            @else
                // Add one empty employee task item if no old input exists
                addEmployeeTaskItem(); // Keep one empty employee task row by default
            @endif
        });
    </script>
@endsection
