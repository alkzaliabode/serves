{{-- resources/views/monthly-sanitation-report/index.blade.php --}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تقرير المنشآت الصحية الشهري') {{-- تحديد عنوان الصفحة --}}

@section('page_title', '📊 تقرير المنشآت الصحية الشهري') {{-- عنوان الصفحة داخل AdminLTE --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">تقرير المنشآت الصحية الشهري</li>
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
    <div class="card"> {{-- تم إزالة card-primary card-outline --}}
        <div class="card-header">
            <h3 class="card-title">ملخصات المنشآت الصحية الشهرية</h3>
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

            <form action="{{ route('monthly-sanitation-report.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="month" class="form-label">الشهر</label>
                        <input type="month" class="form-control" id="month" name="month" value="{{ request('month') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="facility_name" class="form-label">اسم المنشأة</label>
                        <select class="form-select" id="facility_name" name="facility_name">
                            <option value="">كل المنشآت</option>
                            @foreach($facilityNames as $name)
                                <option value="{{ $name }}" {{ request('facility_name') == $name ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="task_type" class="form-label">نوع المهمة</label>
                        <select class="form-select" id="task_type" name="task_type">
                            <option value="">كل الأنواع</option>
                            @foreach($taskTypes as $type)
                                <option value="{{ $type }}" {{ request('task_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> تصفية
                        </button>
                        <a href="{{ route('monthly-sanitation-report.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-sync-alt"></i> إعادة تعيين
                        </a>
                        {{-- زر التصدير (يبقى كما هو، يرتبط بـ Controller Export) --}}
                        <a href="{{ route('monthly-sanitation-report.export', request()->query()) }}" class="btn btn-success me-2">
                            <i class="fas fa-file-excel"></i> تصدير (CSV)
                        </a>
                        {{-- زر الطباعة (الآن يرتبط بمسار طباعة مخصص) --}}
                        <a href="{{ route('monthly-sanitation-report.print', request()->query()) }}" target="_blank" class="btn btn-info">
                            <i class="fas fa-print"></i> طباعة
                        </a>
                    </div>
                </div>
            </form>

            @if($monthlySummaries->isEmpty())
                <div class="alert alert-info" role="alert">
                    لا توجد بيانات لتقرير المنشآت الصحية الشهرية بهذه المعايير.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center table-hover"> {{-- إضافة table-hover --}}
                        <thead>
                            <tr>
                                <th>الشهر</th>
                                <th>اسم المنشأة</th>
                                <th>نوع المهمة</th>
                                <th>الوحدة</th>
                                <th>إجمالي المقاعد</th>
                                <th>إجمالي المرايا</th>
                                <th>إجمالي الخلاطات</th>
                                <th>إجمالي الأبواب</th>
                                <th>إجمالي الأحواض</th>
                                <th>إجمالي المراحيض</th>
                                <th>إجمالي المهام</th>
                                <th>الإجراءات</th> {{-- عمود جديد للإجراءات --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySummaries as $summary)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($summary->month)->format('Y / m') }}</td>
                                    <td>{{ $summary->facility_name }}</td>
                                    <td>{{ $summary->task_type }}</td>
                                    <td>{{ $summary->unit->name ?? 'N/A' }}</td>
                                    <td>{{ $summary->total_seats }}</td>
                                    <td>{{ $summary->total_mirrors }}</td>
                                    <td>{{ $summary->total_mixers }}</td>
                                    <td>{{ $summary->total_doors }}</td>
                                    <td>{{ $summary->total_sinks }}</td>
                                    <td>{{ $summary->total_toilets }}</td>
                                    <td>{{ $summary->total_tasks }}</td>
                                    <td class="text-nowrap"> {{-- خلية الإجراءات --}}
                                        <a href="{{ route('monthly-sanitation-report.edit', $summary->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <form action="{{ route('monthly-sanitation-report.destroy', $summary->id) }}" method="POST" style="display:inline-block;">
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

                <div class="d-flex justify-content-center mt-4">
                    {{ $monthlySummaries->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

