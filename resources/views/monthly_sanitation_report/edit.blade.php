{{-- resources/views/monthly-sanitation-report/edit.blade.php --}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل تقرير المنشآت الصحية الشهري')

@section('page_title', '✏️ تعديل تقرير المنشآت الصحية الشهري')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('monthly-sanitation-report.index') }}">تقارير المنشآت الصحية الشهرية</a></li>
    <li class="breadcrumb-item active">تعديل التقرير</li>
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

        .btn-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(23, 162, 184, 0.5) !important;
        }
        .btn-info:hover {
            background-color: #138496 !important;
            border-color: #138496 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(23, 162, 184, 0.7) !important;
            filter: brightness(1.15);
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.5) !important;
        }
        .btn-success:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.7) !important;
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
        .alert-info {
            background-color: rgba(23, 162, 184, 0.95) !important; /* خلفية زرقاء شفافة للمعلومات */
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
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">تعديل تقرير المنشآت الصحية الشهري</h3>
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

                <form action="{{ route('monthly-sanitation-report.update', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- مهم جداً لتحديد طريقة الطلب كـ PUT --}}

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">معلومات التقرير</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="month" class="form-label">الشهر</label>
                                    <input type="month" class="form-control" id="month" name="month" value="{{ old('month', $report->month) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="facility_name" class="form-label">اسم المنشأة</label>
                                    <select class="form-select" id="facility_name" name="facility_name" required>
                                        <option value="">اختر المنشأة</option>
                                        <option value="صحية الجامع رجال" {{ old('facility_name', $report->facility_name) == 'صحية الجامع رجال' ? 'selected' : '' }}>صحية الجامع رجال</option>
                                        <option value="صحية الجامع نساء" {{ old('facility_name', $report->facility_name) == 'صحية الجامع نساء' ? 'selected' : '' }}>صحية الجامع نساء</option>
                                        <option value="صحية 1 رجال" {{ old('facility_name', $report->facility_name) == 'صحية 1 رجال' ? 'selected' : '' }}>صحية 1 رجال</option>
                                        <option value="صحية 2 رجال" {{ old('facility_name', $report->facility_name) == 'صحية 2 رجال' ? 'selected' : '' }}>صحية 2 رجال</option>
                                        <option value="صحية 3 رجال" {{ old('facility_name', $report->facility_name) == 'صحية 3 رجال' ? 'selected' : '' }}>صحية 3 رجال</option>
                                        <option value="صحية 4 رجال" {{ old('facility_name', $report->facility_name) == 'صحية 4 رجال' ? 'selected' : '' }}>صحية 4 رجال</option>
                                        <option value="صحية 1 نساء" {{ old('facility_name', $report->facility_name) == 'صحية 1 نساء' ? 'selected' : '' }}>صحية 1 نساء</option>
                                        <option value="صحية 2 نساء" {{ old('facility_name', $report->facility_name) == 'صحية 2 نساء' ? 'selected' : '' }}>صحية 2 نساء</option>
                                        <option value="صحية 3 نساء" {{ old('facility_name', $report->facility_name) == 'صحية 3 نساء' ? 'selected' : '' }}>صحية 3 نساء</option>
                                        <option value="صحية 4 نساء" {{ old('facility_name', $report->facility_name) == 'صحية 4 نساء' ? 'selected' : '' }}>صحية 4 نساء</option>
                                        <option value="المجاميع الكبيرة رجال" {{ old('facility_name', $report->facility_name) == 'المجاميع الكبيرة رجال' ? 'selected' : '' }}>المجاميع الكبيرة رجال</option>
                                        <option value="المجاميع الكبيرة نساء" {{ old('facility_name', $report->facility_name) == 'المجاميع الكبيرة نساء' ? 'selected' : '' }}>المجاميع الكبيرة نساء</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="task_type" class="form-label">نوع المهمة</label>
                                    <select class="form-select" id="task_type" name="task_type" required>
                                        <option value="إدامة" {{ old('task_type', $report->task_type) == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                        <option value="صيانة" {{ old('task_type', $report->task_type) == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="unit_id" class="form-label">الوحدة</label>
                                    <select class="form-select" id="unit_id" name="unit_id">
                                        <option value="">اختر الوحدة</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $report->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">تفاصيل الأداء (الكميات المجمعة)</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3"><label for="total_seats" class="form-label">إجمالي المقاعد</label><input type="number" class="form-control" id="total_seats" name="total_seats" min="0" value="{{ old('total_seats', $report->total_seats) }}"></div>
                                <div class="col-md-4 mb-3"><label for="total_mirrors" class="form-label">إجمالي المرايا</label><input type="number" class="form-control" id="total_mirrors" name="total_mirrors" min="0" value="{{ old('total_mirrors', $report->total_mirrors) }}"></div>
                                <div class="col-md-4 mb-3"><label for="total_mixers" class="form-label">إجمالي الخلاطات</label><input type="number" class="form-control" id="total_mixers" name="total_mixers" min="0" value="{{ old('total_mixers', $report->total_mixers) }}"></div>
                                <div class="col-md-4 mb-3"><label for="total_doors" class="form-label">إجمالي الأبواب</label><input type="number" class="form-control" id="total_doors" name="total_doors" min="0" value="{{ old('total_doors', $report->total_doors) }}"></div>
                                <div class="col-md-4 mb-3"><label for="total_sinks" class="form-label">إجمالي الأحواض</label><input type="number" class="form-control" id="total_sinks" name="total_sinks" min="0" value="{{ old('total_sinks', $report->total_sinks) }}"></div>
                                <div class="col-md-4 mb-3"><label for="total_toilets" class="form-label">إجمالي المراحيض</label><input type="number" class="form-control" id="total_toilets" name="total_toilets" min="0" value="{{ old('total_toilets', $report->total_toilets) }}"></div>
                                <div class="col-md-4 mb-3"><label for="total_tasks" class="form-label">إجمالي المهام</label><input type="number" class="form-control" id="total_tasks" name="total_tasks" min="0" value="{{ old('total_tasks', $report->total_tasks) }}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg me-2">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                        <a href="{{ route('monthly-sanitation-report.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // لا توجد حاجة لـ JavaScript معقد هنا لأن هذا النموذج يعرض بيانات مجمعة
        // ولا يحتوي على حقول ديناميكية مثل نماذج إنشاء المهام الأصلية.
        // الحقول كلها مرئية وقابلة للتعديل مباشرة.
    </script>
@endsection
