{{-- resources/views/monthly-sanitation-report/index.blade.php --}}
{{--
    هذا الملف هو قالب صفحة تقرير المنشآت الصحية التفصيلي للعرض على الشاشة.
    تم تحديث تصميمه ليعرض المهام الفردية (حسب التاريخ ونوع المهمة والمنشأة) بدلاً من الملخصات الشهرية.
    يشمل ذلك:
    - بطاقة فلاتر محسنة مع فلاتر جديدة للتاريخ ونوع المهمة والوحدة واسم المنشأة.
    - أيقونات فرز في رؤوس الأعمدة.
    - تنسيقات محسنة للجدول لجعلها أكثر تناسقاً ونظافة.
    - دعم لرسائل الجلسة (Session messages).
    - تم تفعيل Pagination للجدول.
    - تمت إضافة أزرار التعديل والحذف لكل سجل مهمة.
    - تمت إضافة زر "إضافة مهمة" جديدة.
    - تمت إضافة فلتر "بحث عام".
--}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تقرير المنشآت الصحية التفصيلي') {{-- تحديد عنوان الصفحة --}}

@section('page_title', '📊 تقرير المنشآت الصحية التفصيلي') {{-- عنوان الصفحة داخل AdminLTE --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">تقرير المنشآت الصحية التفصيلي</li>
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

@section('content')
    {{-- رسائل الجلسة (مثلاً للنجاح) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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
            <form id="filter-form" action="{{ route('monthly-sanitation-report.index') }}" method="GET" class="form-filters-print">
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-3">
                        <label for="date" class="form-label">التاريخ</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $selectedDate ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">تاريخ البداية</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $selectedStartDate ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">تاريخ النهاية</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $selectedEndDate ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="facility_name" class="form-label">اسم المنشأة</label>
                        <select class="form-select" id="facility_name" name="facility_name" aria-label="اسم المنشأة">
                            <option value="">كل المنشآت</option>
                            @foreach($availableFacilityNames as $name)
                                <option value="{{ $name }}" {{ ($selectedFacilityName ?? '') == $name ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="task_type" class="form-label">نوع المهمة</label>
                        <select class="form-select" id="task_type" name="task_type" aria-label="نوع المهمة">
                            <option value="">كل الأنواع</option>
                            @foreach($availableTaskTypes as $type)
                                <option value="{{ $type }}" {{ ($selectedTaskType ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="unit_id" class="form-label">الوحدة</label>
                        <select class="form-select" id="unit_id" name="unit_id" aria-label="الوحدة">
                            <option value="">كل الوحدات</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ ($selectedUnitId ?? '') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">بحث عام</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="بحث بالكلمات المفتاحية..." value="{{ $searchQuery ?? '' }}">
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> تطبيق الفلاتر
                        </button>
                        <a href="{{ route('monthly-sanitation-report.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-sync-alt"></i> إعادة تعيين
                        </a>
                        {{-- تم تحديث هذا الزر ليوجه إلى SanitationFacilityTaskController --}}
                        <a href="{{ route('sanitation-facility-tasks.create') }}" class="btn btn-success me-2">
                            <i class="fas fa-plus"></i> إضافة مهمة
                        </a>
                        <button type="button" onclick="printReport()" class="btn btn-success me-2">
                            <i class="fas fa-print"></i> طباعة التقرير
                        </button>
                        <button type="button" onclick="ReportPrintExcel.download(this, 'تقرير_المنشآت_الصحية', '{{ route('monthly-sanitation-report.print') }}')" class="btn btn-info btn-export-print">
                            <i class="fas fa-file-excel"></i> تصدير Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-info card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list me-1"></i>
                نتائج التقرير
            </h3>
        </div>
        <div class="card-body p-0">
            <h4 class="text-md font-weight-bold mb-3 mt-3 text-secondary d-print-none text-center">
                بيانات تقرير المنشآت الصحية التفصيلي
                <span class="text-primary">
                    @if (!empty($selectedDate))
                        (تاريخ: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }})
                    @endif
                    @if (!empty($selectedStartDate) && !empty($selectedEndDate))
                        (الفترة: من {{ \Carbon\Carbon::parse($selectedStartDate)->translatedFormat('d F Y') }} إلى {{ \Carbon\Carbon::parse($selectedEndDate)->translatedFormat('d F Y') }})
                    @elseif (!empty($selectedStartDate))
                        (من تاريخ: {{ \Carbon\Carbon::parse($selectedStartDate)->translatedFormat('d F Y') }})
                    @elseif (!empty($selectedEndDate))
                        (إلى تاريخ: {{ \Carbon\Carbon::parse($selectedEndDate)->translatedFormat('d F Y') }})
                    @endif
                    @if (!empty($selectedFacilityName))
                        (اسم المنشأة: {{ $selectedFacilityName }})
                    @endif
                    @if (!empty($selectedTaskType))
                        (نوع المهمة: {{ $selectedTaskType }})
                    @endif
                    @if (!empty($selectedUnitId))
                        (الوحدة: {{ $units->find($selectedUnitId)->name ?? 'غير معروف' }})
                    @endif
                    @if (!empty($searchQuery))
                        (بحث: "{{ $searchQuery }}")
                    @endif
                </span>
            </h4>

            @if($tasks->isEmpty()) {{-- تم التعديل هنا --}}
                <div class="alert alert-info" role="alert">
                    لا توجد مهام للمنشآت الصحية لعرضها بهذه المعايير.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-sm">
                        <thead>
                            <tr class="bg-light">
                                <th>التاريخ
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'date', 'sort_order' => (($sortBy ?? '') == 'date' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'date' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'date' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>اسم المنشأة
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'facility_name', 'sort_order' => (($sortBy ?? '') == 'facility_name' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'facility_name' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'facility_name' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>نوع المهمة
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'task_type', 'sort_order' => (($sortBy ?? '') == 'task_type' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'task_type' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'task_type' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>الوحدة
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'unit_id', 'sort_order' => (($sortBy ?? '') == 'unit_id' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'unit_id' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'unit_id' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المقاعد
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'seats_count', 'sort_order' => (($sortBy ?? '') == 'seats_count' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'seats_count' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'seats_count' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المرايا
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'mirrors_count', 'sort_order' => (($sortBy ?? '') == 'mirrors_count' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'mirrors_count' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'mirrors_count' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الخلاطات
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'mixers_count', 'sort_order' => (($sortBy ?? '') == 'mixers_count' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'mixers_count' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'mixers_count' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الأبواب
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'doors_count', 'sort_order' => (($sortBy ?? '') == 'doors_count' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'doors_count' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'doors_count' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المغاسل {{-- تم التعديل من الأحواض --}}
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'sinks_count', 'sort_order' => (($sortBy ?? '') == 'sinks_count' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'sinks_count' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'sinks_count' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الحمامات {{-- تم التعديل من المراحيض --}}
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'toilets_count', 'sort_order' => (($sortBy ?? '') == 'toilets_count' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'toilets_count' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'toilets_count' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">ملاحظات
                                    <a href="{{ route('monthly-sanitation-report.index', array_merge(request()->query(), ['sort_by' => 'notes', 'sort_order' => (($sortBy ?? '') == 'notes' && ($sortOrder ?? '') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(($sortBy ?? '') == 'notes' && ($sortOrder ?? '') == 'asc') <i class="bi bi-sort-up"></i> @elseif(($sortBy ?? '') == 'notes' && ($sortOrder ?? '') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($task->date)->translatedFormat('d F Y') }}</td>
                                    <td>{{ $task->facility_name }}</td>
                                    <td>{{ $task->task_type }}</td>
                                    <td>{{ $task->unit->name ?? 'N/A' }}</td>
                                    <td>{{ $task->seats_count }}</td>
                                    <td>{{ $task->mirrors_count }}</td>
                                    <td>{{ $task->mixers_count }}</td>
                                    <td>{{ $task->doors_count }}</td>
                                    <td>{{ $task->sinks_count }}</td> {{-- المغاسل --}}
                                    <td>{{ $task->toilets_count }}</td> {{-- الحمامات --}}
                                    <td>{{ $task->notes }}</td>
                                    <td class="text-nowrap">
                                        {{-- تم تحديث الروابط هنا لتشير إلى SanitationFacilityTaskController --}}
                                        <a href="{{ route('sanitation-facility-tasks.edit', $task->id) }}" class="btn btn-sm btn-info" aria-label="تعديل المهمة رقم {{ $task->id }}">
                                            <i class="fas fa-edit" aria-hidden="true"></i> تعديل
                                        </a>
                                        <form action="{{ route('sanitation-facility-tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المهمة؟')" aria-label="حذف المهمة رقم {{ $task->id }}">
                                                <i class="fas fa-trash" aria-hidden="true"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/report-excel.js') }}"></script>
    <script src="{{ asset('js/report-print-excel.js') }}"></script>
<script>
    function printReport() {
        // Get current filter parameters from the form
        const form = document.getElementById('filter-form');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value) { // Only add non-empty values
                params.append(key, value);
            }
        }
        // Add sorting parameters if present
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('sort_by')) {
            params.append('sort_by', urlParams.get('sort_by'));
        }
        if (urlParams.has('sort_order')) {
            params.append('sort_order', urlParams.get('sort_order'));
        }

        const printUrl = "{{ route('monthly-sanitation-report.print') }}?" + params.toString();
        window.open(printUrl, '_blank');
    }

</script>
@endsection
