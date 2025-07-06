{{-- resources/views/monthly-cleaning-report/index.blade.php --}}
{{--
    هذا الملف هو قالب صفحة تقرير النظافة العامة الشهري للعرض على الشاشة.
    تم تحديث تصميمه ليتوافق مع تصميم صفحة تقرير المنشآت الصحية الشهرية ومهام المنشآت الصحية، بما في ذلك:
    - بطاقة فلاتر قابلة للطي بتصميم محسن.
    - أيقونات فرز في رؤوس الأعمدة.
    - تنسيقات محسنة للجدول لجعلها أكثر تناسقاً ونظافة.
    - دعم لرسائل الجلسة (Session messages).
    - تم تفعيل Pagination للجدول.
    - تمت إضافة أزرار التعديل والحذف لكل سجل تقرير.
--}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تقرير النظافة العامة الشهري') {{-- تحديد عنوان الصفحة --}}

@section('page_title', '📊 تقرير النظافة العامة الشهري') {{-- عنوان الصفحة داخل AdminLTE --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li> {{-- إضافة لوحة التحكم --}}
    <li class="breadcrumb-item active">تقرير النظافة العامة الشهري</li>
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

        /* Badge styling */
        .badge.bg-success {
            background-color: rgba(40, 167, 69, 0.8) !important;
            color: white !important;
        }
        .badge.bg-danger {
            background-color: rgba(220, 53, 69, 0.8) !important;
            color: white !important;
        }
        /* Style for table sorting links */
        th a {
            color: white !important;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        th a:hover {
            color: var(--accent-color) !important; /* Highlight on hover */
        }

        /* أنماط الجدول داخل البطاقة */
        .table {
            color: white; /* لون نص أبيض للجدول بالكامل */
            font-size: 1.05rem; /* Slightly larger table text */
        }
        .table thead th {
            background-color: rgba(0, 123, 255, 0.3) !important; /* خلفية زرقاء شفافة لرؤوس الجدول */
            color: white !important; /* لون نص أبيض لرؤوس الجدول */
            border-color: rgba(255, 255, 255, 0.2) !important; /* حدود بيضاء شفافة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            padding: 1rem; /* More padding for headers */
        }
        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1) !important; /* حدود بيضاء شفافة للصفوف */
            padding: 0.8rem; /* More padding for cells */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05) !important; /* تظليل خفيف للصفوف الفردية */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.15) !important; /* تأثير تحويم أكثر وضوحاً */
            cursor: pointer;
        }

        /* Ensure .form-filters-print buttons are consistent */
        .form-filters-print .btn {
            padding: 0.6rem 1.2rem;
            font-size: 1rem;
            border-radius: 0.5rem;
        }

        /* Actions column buttons specific styling */
        .table .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            border-radius: 0.4rem;
            margin: 0 3px; /* Small margin between buttons */
        }
        .table .btn-sm .fas {
            margin-right: 5px;
            font-size: 0.9rem;
        }
    </style>
    {{-- إضافة أيقونات Bootstrap إذا لم تكن موجودة بالفعل في الـ layout --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    {{-- رسائل الجلسة (مثلاً للنجاح) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- ✅ تم إزالة 'collapsed-card' لجعل بطاقة الفلاتر مفتوحة بشكل افتراضي --}}
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter me-1"></i>
                خيارات التقرير
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="plus" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="minus" class="bi bi-dash-lg" style="display: none;"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="filter-form" action="{{ route('monthly-cleaning-report.index') }}" method="GET" class="form-filters-print">
                <div class="row g-3 align-items-end mb-3"> {{-- استخدام g-3 و align-items-end --}}
                    <div class="col-md-3">
                        <label for="month" class="form-label">الشهر</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">كل الشهور</option>
                            @foreach($availableMonths as $monthOption)
                                <option value="{{ $monthOption }}" {{ $selectedMonth == $monthOption ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($monthOption)->translatedFormat('F Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="location" class="form-label">الموقع</label>
                        <select name="location" id="location" class="form-control">
                            <option value="">كل المواقع</option>
                            @foreach($availableLocations as $locationOption)
                                <option value="{{ $locationOption }}" {{ $selectedLocation == $locationOption ? 'selected' : '' }}>
                                    {{ $locationOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="task_type" class="form-label">نوع المهمة</label>
                        <select name="task_type" id="task_type" class="form-control">
                            <option value="">كل الأنواع</option>
                            @foreach($availableTaskTypes as $value => $label)
                                <option value="{{ $value }}" {{ $selectedTaskType == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">بحث عام</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="بحث بالكلمات المفتاحية..." value="{{ $searchQuery }}">
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> تطبيق الفلاتر
                        </button>
                        <a href="{{ route('monthly-cleaning-report.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-sync-alt"></i> إعادة تعيين
                        </a>
                        <button type="button" onclick="printReport()" class="btn btn-success me-2"> {{-- زر طباعة --}}
                            <i class="fas fa-print"></i> طباعة التقرير
                        </button>
                        <button type="button" onclick="exportToCsv()" class="btn btn-info btn-export-print"> {{-- زر تصدير --}}
                            <i class="fas fa-file-excel"></i> تصدير CSV
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-info card-outline"> {{-- بطاقة لنتائج التقرير --}}
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list me-1"></i>
                نتائج التقرير
            </h3>
        </div>
        <div class="card-body p-0">
            {{-- المحتوى المراد عرضه على الشاشة (الجدول) --}}
            <h4 class="text-md font-weight-bold mb-3 mt-3 text-secondary d-print-none text-center"> {{-- d-print-none لإخفاء العنوان عند الطباعة --}}
                بيانات تقرير النظافة العامة الشهري
                <span class="text-primary">
                    @if ($selectedMonth)
                        (شهر {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }})
                    @endif
                    @if ($selectedLocation)
                        (موقع: {{ $selectedLocation }})
                    @endif
                    @if ($selectedTaskType)
                        (نوع المهمة: {{ $selectedTaskType }})
                    @endif
                    @if ($searchQuery)
                        (بحث: "{{ $searchQuery }}")
                    @endif
                </span>
            </h4>

            @if($reports->isEmpty())
                <div class="alert alert-info" role="alert">
                    لا توجد بيانات لتقرير النظافة العامة لعرضها بهذه المعايير.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-sm"> {{-- تم تعديل classes --}}
                        <thead>
                            <tr class="bg-light">
                                <th>الشهر
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'month', 'sort_order' => ($sortBy == 'month' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'month' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'month' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>الموقع
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'location', 'sort_order' => ($sortBy == 'location' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'location' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'location' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>نوع المهمة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'task_type', 'sort_order' => ($sortBy == 'task_type' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'task_type' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'task_type' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المنادر
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_mats', 'sort_order' => ($sortBy == 'total_mats' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_mats' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_mats' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الوسائد
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_pillows', 'sort_order' => ($sortBy == 'total_pillows' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_pillows' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_pillows' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المراوح
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_fans', 'sort_order' => ($sortBy == 'total_fans' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_fans' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_fans' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">النوافذ
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_windows', 'sort_order' => ($sortBy == 'total_windows' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_windows' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_windows' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">السجاد
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_carpets', 'sort_order' => ($sortBy == 'total_carpets' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_carpets' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_carpets' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">البطانيات
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_blankets', 'sort_order' => ($sortBy == 'total_blankets' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_blankets' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_blankets' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الأسرة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_beds', 'sort_order' => ($sortBy == 'total_beds' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_beds' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_beds' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المستفيدون
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_beneficiaries', 'sort_order' => ($sortBy == 'total_beneficiaries' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_beneficiaries' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_beneficiaries' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الترامز
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_trams', 'sort_order' => ($sortBy == 'total_trams' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_trams' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_trams' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">السجاد المفروش
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_laid_carpets', 'sort_order' => ($sortBy == 'total_laid_carpets' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_laid_carpets' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_laid_carpets' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">حاويات كبيرة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_large_containers', 'sort_order' => ($sortBy == 'total_large_containers' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_large_containers' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_large_containers' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">حاويات صغيرة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'total_small_containers', 'sort_order' => ($sortBy == 'total_small_containers' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'total_small_containers' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'total_small_containers' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الإجراءات</th> {{-- عمود جديد للإجراءات --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($report->month)->translatedFormat('F Y') }}</td>
                                    <td>{{ $report->location }}</td>
                                    <td>{{ $report->task_type }}</td>
                                    <td>{{ $report->total_mats }}</td>
                                    <td>{{ $report->total_pillows }}</td>
                                    <td>{{ $report->total_fans }}</td>
                                    <td>{{ $report->total_windows }}</td>
                                    <td>{{ $report->total_carpets }}</td>
                                    <td>{{ $report->total_blankets }}</td>
                                    <td>{{ $report->total_beds }}</td>
                                    <td>{{ $report->total_beneficiaries }}</td>
                                    <td>{{ $report->total_trams }}</td>
                                    <td>{{ $report->total_laid_carpets }}</td>
                                    <td>{{ $report->total_large_containers }}</td>
                                    <td>{{ $report->total_small_containers }}</td>
                                    <td class="text-nowrap"> {{-- خلية الإجراءات --}}
                                        <a href="{{ route('monthly-cleaning-report.edit', $report->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <form action="{{ route('monthly-cleaning-report.destroy', $report->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التقرير؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="d-flex justify-content-center mt-4"> {{-- تم تعديل justify-content --}}
                        {{-- Pagination for monthly summaries, if applicable --}}
                        {{-- افتراضياً، تقارير الملخصات الشهرية قد لا تحتاج تقسيم صفحات إذا كانت البيانات قليلة --}}
                        {{-- إذا كان $reports هو كائن Paginator، فيمكنك تفعيل هذا السطر --}}
                        {{-- {{ $reports->links('pagination::bootstrap-5') }} --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts') {{-- لربط سكربتات خاصة بهذه الصفحة --}}
    <script>
        function printReport() {
            // توجيه المستخدم إلى صفحة الطباعة مع تمرير نفس الفلاتر
            const urlParams = new URLSearchParams(window.location.search);
            const printUrl = `{{ route('monthly-cleaning-report.print') }}?${urlParams.toString()}`;
            window.open(printUrl, '_blank');
        }

        function exportToCsv() {
            const actualTable = document.querySelector('.table-responsive table'); // استهداف الجدول الفعلي في هذا الملف

            if (!actualTable) {
                // بدلاً من alert، يمكن استخدام modal أو رسالة داخل الصفحة
                console.error('Table not found for CSV export.');
                return;
            }

            let csv = [];
            for (let i = 0; i < actualTable.rows.length; i++) {
                let row = [], cols = actualTable.rows[i].querySelectorAll('td, th');
                for (let j = 0; j < cols.length; j++) {
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                    data = data.replace(/"/g, '""'); // Escape double quotes
                    row.push('"' + data + '"');
                }
                csv.push(row.join(','));
            }

            const csvString = csv.join('\n');
            const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            const filename = 'تقرير_النظافة_العامة_' + new Date().toISOString().slice(0,10) + '.csv';

            // Check if navigator.msSaveBlob exists (for IE10+)
            if (navigator.msSaveBlob) {
                navigator.msSaveBlob(blob, filename);
            } else {
                const link = document.createElement('a');
                if (link.download !== undefined) { // Feature detection
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', filename);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    // بدلاً من alert، يمكن استخدام modal أو رسالة داخل الصفحة
                    console.error('المتصفح لا يدعم تصدير CSV بهذه الطريقة. يرجى استخدام متصفح أحدث.');
                }
            }
        }
    </script>
@endsection {{-- نهاية قسم السكربتات --}}
