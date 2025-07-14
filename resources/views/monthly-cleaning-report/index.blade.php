{{-- resources/views/monthly-cleaning-report/index.blade.php --}}
{{--
    هذا الملف هو قالب صفحة تقرير النظافة العامة التفصيلي للعرض على الشاشة.
    تم تحديث تصميمه ليعرض المهام الفردية (حسب اليوم والشفت) بدلاً من الملخصات الشهرية.
    يشمل ذلك:
    - بطاقة فلاتر قابلة للطي بتصميم محسن، مع فلاتر جديدة للتاريخ والشفت والوحدة.
    - أيقونات فرز في رؤوس الأعمدة.
    - تنسيقات محسنة للجدول لجعلها أكثر تناسقاً ونظافة.
    - دعم لرسائل الجلسة (Session messages).
    - تم تفعيل Pagination للجدول.
    - تمت إضافة أزرار التعديل والحذف لكل سجل مهمة.
--}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تقرير النظافة العامة التفصيلي') {{-- تحديد عنوان الصفحة --}}

@section('page_title', '📊 تقرير النظافة العامة التفصيلي') {{-- عنوان الصفحة داخل AdminLTE --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li> {{-- إضافة لوحة التحكم --}}
    <li class="breadcrumb-item active">تقرير النظافة العامة التفصيلي</li>
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
                    {{-- 💡 فلتر التاريخ الجديد --}}
                    <div class="col-md-3">
                        <label for="date" class="form-label">التاريخ</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $selectedDate }}">
                    </div>
                    {{-- 💡 فلتر الشفت الجديد --}}
                    <div class="col-md-3">
                        <label for="shift" class="form-label">الشفت</label>
                        <select name="shift" id="shift" class="form-control">
                            <option value="">كل الشفتات</option>
                            @foreach($availableShifts as $shiftOption)
                                <option value="{{ $shiftOption }}" {{ $selectedShift == $shiftOption ? 'selected' : '' }}>
                                    {{ $shiftOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="month" class="form-label">الشهر</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">كل الشهور</option>
                            @foreach($availableMonths as $monthValue => $monthLabel)
                                <option value="{{ $monthValue }}" {{ $selectedMonth == $monthValue ? 'selected' : '' }}>
                                    {{ $monthLabel }}
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
                            @foreach($availableTaskTypes as $value) {{-- 💡 تم تعديل هنا: لم تعد هناك حاجة لـ $label --}}
                                <option value="{{ $value }}" {{ $selectedTaskType == $value ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- 💡 فلتر الوحدة الجديد --}}
                    <div class="col-md-3">
                        <label for="unit_id" class="form-label">الوحدة</label>
                        <select name="unit_id" id="unit_id" class="form-control">
                            <option value="">كل الوحدات</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
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
                        <a href="{{ route('monthly-cleaning-report.create') }}" class="btn btn-success me-2"> {{-- 💡 زر إضافة مهمة جديدة --}}
                            <i class="fas fa-plus"></i> إضافة مهمة
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
                بيانات تقرير النظافة العامة التفصيلي
                <span class="text-primary">
                    @if ($selectedDate)
                        (تاريخ: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }})
                    @endif
                    @if ($selectedMonth)
                        (شهر: {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }})
                    @endif
                    @if ($selectedShift)
                        (شفت: {{ $selectedShift }})
                    @endif
                    @if ($selectedLocation)
                        (موقع: {{ $selectedLocation }})
                    @endif
                    @if ($selectedTaskType)
                        (نوع المهمة: {{ $selectedTaskType }})
                    @endif
                    @if ($selectedUnitId)
                        (وحدة: {{ $units->find($selectedUnitId)->name ?? 'غير معروف' }})
                    @endif
                    @if ($searchQuery)
                        (بحث: "{{ $searchQuery }}")
                    @endif
                </span>
            </h4>

            @if($tasks->isEmpty()) {{-- 💡 تغيير المتغير إلى tasks --}}
                <div class="alert alert-info" role="alert">
                    لا توجد بيانات لتقرير النظافة العامة لعرضها بهذه المعايير.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-sm"> {{-- تم تعديل classes --}}
                        <thead>
                            <tr class="bg-light">
                                <th>التاريخ
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'date', 'sort_order' => ($sortBy == 'date' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'date' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'date' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>الشفت
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'shift', 'sort_order' => ($sortBy == 'shift' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'shift' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'shift' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>الوحدة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'unit_id', 'sort_order' => ($sortBy == 'unit_id' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'unit_id' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'unit_id' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
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
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'mats_count', 'sort_order' => ($sortBy == 'mats_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'mats_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'mats_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الوسائد
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'pillows_count', 'sort_order' => ($sortBy == 'pillows_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'pillows_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'pillows_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المراوح
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'fans_count', 'sort_order' => ($sortBy == 'fans_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'fans_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'fans_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">النوافذ
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'windows_count', 'sort_order' => ($sortBy == 'windows_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'windows_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'windows_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">السجاد
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'carpets_count', 'sort_order' => ($sortBy == 'carpets_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'carpets_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'carpets_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">البطانيات
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'blankets_count', 'sort_order' => ($sortBy == 'blankets_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'blankets_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'blankets_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الأسرة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'beds_count', 'sort_order' => ($sortBy == 'beds_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'beds_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'beds_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">المستفيدون
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'beneficiaries_count', 'sort_order' => ($sortBy == 'beneficiaries_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'beneficiaries_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'beneficiaries_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الترامز
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'filled_trams_count', 'sort_order' => ($sortBy == 'filled_trams_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'filled_trams_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'filled_trams_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">السجاد المفروش
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'carpets_laid_count', 'sort_order' => ($sortBy == 'carpets_laid_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'carpets_laid_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'carpets_laid_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">حاويات كبيرة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'large_containers_count', 'sort_order' => ($sortBy == 'large_containers_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'large_containers_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'large_containers_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">حاويات صغيرة
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'small_containers_count', 'sort_order' => ($sortBy == 'small_containers_count' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'small_containers_count' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'small_containers_count' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">ساعات العمل
                                    <a href="{{ route('monthly-cleaning-report.index', array_merge(request()->query(), ['sort_by' => 'working_hours', 'sort_order' => ($sortBy == 'working_hours' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if($sortBy == 'working_hours' && $sortOrder == 'asc') <i class="bi bi-sort-up"></i> @elseif($sortBy == 'working_hours' && $sortOrder == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th class="text-nowrap">الملاحظات</th>
                                <th class="text-nowrap">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task) {{-- 💡 تغيير المتغير إلى tasks --}}
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($task->date)->translatedFormat('d F Y') }}</td> {{-- 💡 عرض التاريخ --}}
                                    <td>{{ $task->shift }}</td> {{-- 💡 عرض الشفت --}}
                                    <td>{{ $task->unit->name ?? 'N/A' }}</td> {{-- 💡 عرض اسم الوحدة --}}
                                    <td>{{ $task->location }}</td>
                                    <td>{{ $task->task_type }}</td>
                                    <td>{{ $task->mats_count }}</td> {{-- 💡 عرض الكميات الفردية --}}
                                    <td>{{ $task->pillows_count }}</td>
                                    <td>{{ $task->fans_count }}</td>
                                    <td>{{ $task->windows_count }}</td>
                                    <td>{{ $task->carpets_count }}</td>
                                    <td>{{ $task->blankets_count }}</td>
                                    <td>{{ $task->beds_count }}</td>
                                    <td>{{ $task->beneficiaries_count }}</td>
                                    <td>{{ $task->filled_trams_count }}</td>
                                    <td>{{ $task->carpets_laid_count }}</td>
                                    <td>{{ $task->large_containers_count }}</td>
                                    <td>{{ $task->small_containers_count }}</td>
                                    <td>{{ $task->working_hours }}</td>
                                    <td>{{ $task->notes }}</td>
                                    <td class="text-nowrap"> {{-- خلية الإجراءات --}}
                                        <a href="{{ route('monthly-cleaning-report.edit', $task->id) }}" class="btn btn-sm btn-info"> {{-- 💡 التعديل ليعمل مع $task->id --}}
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <form action="{{ route('monthly-cleaning-report.destroy', $task->id) }}" method="POST" style="display:inline-block;"> {{-- 💡 التعديل ليعمل مع $task->id --}}
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
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
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tasks->links('pagination::bootstrap-5') }} {{-- 💡 تغيير المتغير إلى tasks --}}
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
                console.error('Table not found for CSV export.');
                return;
            }

            let csv = [];
            // Get headers from <thead>
            let headers = [];
            actualTable.querySelectorAll('thead th').forEach(th => {
                // Exclude the "الإجراءات" column
                if (th.innerText.trim() !== 'الإجراءات') {
                    headers.push('"' + th.innerText.trim().replace(/"/g, '""') + '"');
                }
            });
            csv.push(headers.join(','));

            // Get data from <tbody>
            actualTable.querySelectorAll('tbody tr').forEach(row => {
                let rowData = [];
                row.querySelectorAll('td').forEach((td, index) => {
                    // Exclude the "الإجراءات" column (last column)
                    if (index < row.querySelectorAll('td').length - 1) {
                        let data = td.innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                        data = data.replace(/"/g, '""');
                        rowData.push('"' + data + '"');
                    }
                });
                csv.push(rowData.join(','));
            });

            const csvString = csv.join('\n');
            const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            const filename = 'تقرير_النظافة_التفصيلي_' + new Date().toISOString().slice(0,10) + '.csv'; // 💡 تغيير اسم الملف

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
                    console.error('المتصفح لا يدعم تصدير CSV بهذه الطريقة. يرجى استخدام متصفح أحدث.');
                }
            }
        }
    </script>
@endsection {{-- نهاية قسم السكربتات --}}
