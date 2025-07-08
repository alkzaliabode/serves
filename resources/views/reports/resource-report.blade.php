{{-- resources/views/reports/resource-report.blade.php --}}
{{--
    هذا الملف هو قالب صفحة تقرير الموارد المستخدمة للعرض على الشاشة.
    تم تحديث تصميمه ليتوافق مع تصميم صفحات التقارير الأخرى (مثل المنشآت الصحية والنظافة العامة)، بما في ذلك:
    - بطاقة فلاتر قابلة للطي بتصميم محسن.
    - تنسيقات محسنة للجدول لجعلها أكثر تناسقاً ونظافة.
    - تم تطبيق أنماط الشفافية لضمان التناسق البصري مع الأقسام الأخرى.
    ملاحظة: تم نقل محتوى الطباعة إلى ملف 'resources/views/reports/resource-report-print.blade.php'.
--}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تقرير الموارد') {{-- تحديد عنوان الصفحة --}}

@section('page_title', '📊 تقرير الموارد المستخدمة') {{-- عنوان الصفحة داخل AdminLTE --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li> {{-- إضافة لوحة التحكم --}}
    <li class="breadcrumb-item active">تقرير الموارد</li>
@endsection

@section('styles')
    <style>
        /* تعريف متغيرات الألوان لضمان التناسق */
        :root {
            --accent-color: #00eaff; /* Light blue/cyan for interactive elements and emphasis */
            --glass-background: rgba(255, 255, 255, 0.08); /* Consistent transparent background for glass effect */
            --glass-border: 1px solid rgba(255, 255, 255, 0.2); /* Consistent transparent border */
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Consistent shadow */
            --text-primary-color: white;
            --text-shadow-strong: 2px 2px 5px rgba(0, 0, 0, 0.9);
            --text-shadow-medium: 1px 1px 3px rgba(0, 0, 0, 0.7);
            --text-shadow-light: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* أنماط البطاقات لتكون شفافة بالكامل مع تأثير زجاجي وخطوط بارزة */
        .card {
            background: var(--glass-background) !important;
            backdrop-filter: blur(10px) !important; /* تأثير الزجاج المتجمد */
            border-radius: 1rem !important; /* حواف مستديرة */
            box-shadow: var(--glass-shadow) !important;
            border: var(--glass-border) !important;
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        
        /* General text styling inside cards */
        .card-body,
        .card-title,
        .card-header .btn {
            color: var(--text-primary-color) !important;
            text-shadow: var(--text-shadow-light) !important;
        }

        /* Titles and Headers */
        .card-title,
        .card-header h3.card-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-strong) !important;
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }
        .card-title:hover,
        .card-header h3.card-title:hover {
            color: #ffffff !important;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 1.0) !important;
        }

        /* Form Controls (Inputs, Selects, Textareas) */
        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: var(--text-primary-color) !important;
            text-shadow: var(--text-shadow-medium) !important;
            font-size: 1.1rem !important;
            padding: 0.75rem 1rem !important;
            border-radius: 0.5rem; /* Rounded corners for inputs */
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        .form-control:focus,
        .form-select:focus {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.5) !important;
        }
        /* لتلوين نص الـ <option> داخل الـ <select> عندما يكون الخلفية داكنة */
        .form-select option {
            background-color: #2c3e50; /* خلفية داكنة لخيار القائمة */
            color: white; /* نص أبيض لخيار القائمة */
        }

        /* Labels */
        .form-label,
        label {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-medium) !important;
            margin-bottom: 0.5rem; /* Spacing below label */
            display: block; /* Ensure label takes full width */
        }

        /* Buttons */
        .btn {
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 0.5rem; /* Rounded corners for buttons */
            color: white !important; /* Default to white text for all custom styled buttons */
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4) !important;
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4) !important;
        }
        .btn-success:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.6) !important;
            filter: brightness(1.2);
        }
        
        .btn-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            box-shadow: 0 2px 8px rgba(23, 162, 184, 0.4) !important;
        }
        .btn-info:hover {
            background-color: #138496 !important;
            border-color: #138496 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #212529 !important; /* Dark text for warning button */
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.4) !important;
        }
        .btn-warning:hover {
            background-color: #e0a800 !important;
            border-color: #e0a800 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4) !important;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.4) !important;
        }
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.6) !important;
            filter: brightness(1.2);
        }

        /* أنماط الأيقونات في الأزرار */
        .btn .fas, .btn .bi {
            margin-inline-end: 5px; /* مسافة بين الأيقونة والنص */
        }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important; /* خلفية شفافة للرسائل */
            color: #333 !important; /* لون نص داكن */
            border-color: rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.9) !important; /* خلفية خضراء شفافة للنجاح */
            color: white !important;
        }
        .alert-info {
            background-color: rgba(23, 162, 184, 0.9) !important; /* خلفية زرقاء شفافة للمعلومات */
            color: white !important;
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

        /* أنماط عامة للجدول لجعلها متناسقة وشفافة */
        .table {
            background-color: rgba(255, 255, 255, 0.1) !important; /* خلفية شفافة خفيفة للجدول نفسه */
            color: var(--text-primary-color) !important; /* لون نص أبيض للجدول بالكامل */
            border-radius: 0.75rem; /* حواف مستديرة للجدول */
            overflow: hidden; /* لإخفاء الزوايا الحادة للخلايا عند تطبيق border-radius على الجدول */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15); /* ظل خفيف للجدول */
        }
        .table thead th {
            background-color: rgba(0, 123, 255, 0.3) !important; /* خلفية زرقاء شفافة لرؤوس الجدول */
            color: var(--accent-color) !important; /* لون نص أبيض لرؤوس الجدول */
            border-color: rgba(255, 255, 255, 0.3) !important; /* حدود بيضاء شفافة */
            text-shadow: var(--text-shadow-medium);
            font-weight: 700;
            padding: 0.75rem; /* زيادة الحشوة */
        }
        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1) !important; /* حدود بيضاء شفافة للصفوف */
            text-shadow: var(--text-shadow-light);
            padding: 0.6rem; /* حشوة مناسبة */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05) !important; /* تظليل خفيف جداً للصفوف الفردية */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.15) !important; /* تأثير تحويم أكثر وضوحاً */
        }
        /* لضمان أن النص في الـ `h4` أعلى الجدول يظهر بوضوح */
        .text-secondary {
            color: rgba(255, 255, 255, 0.9) !important;
            text-shadow: var(--text-shadow-strong) !important;
        }
        .text-primary {
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-strong) !important;
        }
        .total-summary {
            background-color: rgba(0, 123, 255, 0.5) !important; /* خلفية زرقاء شفافة للتلخيص */
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            text-shadow: var(--text-shadow-medium) !important;
        }
        .total-summary .font-weight-bold, .total-summary .h4 {
            color: var(--accent-color) !important; /* لون مميز للأرقام والمورد */
            text-shadow: var(--text-shadow-strong) !important;
        }
        .total-summary .text-muted {
            color: rgba(255, 255, 255, 0.7) !important; /* نص باهت لوحدة القياس */
            text-shadow: var(--text-shadow-light) !important;
        }


        /* إخفاء عناصر الطباعة في عرض الشاشة */
        @media screen {
            .d-print-flex {
                display: none !important;
            }
            .header-print {
                display: none !important;
            }
            .filters-display {
                display: none !important;
            }
        }
    </style>
    {{-- إضافة أيقونات Bootstrap إذا لم تكن موجودة بالفعل في الـ layout --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    <div class="card card-primary card-outline"> {{-- ✅ تم إزالة 'collapsed-card' لجعلها مرئية دائمًا --}}
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
            <form id="filter-form" action="{{ route('resource-report.index') }}" method="GET" class="form-filters-print">
                <div class="row g-3 align-items-end mb-3"> {{-- استخدام g-3 و align-items-end --}}
                    <div class="col-md-5">
                        <label for="searchItem" class="form-label">البحث باسم المورد</label>
                        <input type="text" id="searchItem" name="searchItem"
                               class="form-control" placeholder="ابحث باسم المورد..."
                               value="{{ $searchItem }}">
                    </div>
                    <div class="col-md-5">
                        <label for="selectedMonth" class="form-label">اختيار الشهر</label>
                        <input type="month" id="selectedMonth" name="selectedMonth"
                               class="form-control" value="{{ $selectedMonth }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2">
                            <i class="fas fa-filter"></i> تطبيق الفلاتر
                        </button>
                        {{-- زر طباعة - سيفتح نافذة جديدة لصفحة الطباعة --}}
                        <button type="button" onclick="printReport()" class="btn btn-success w-100">
                            <i class="fas fa-print"></i> طباعة التقرير
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
                النتائج
            </h3>
        </div>
        <div class="card-body p-0"> {{-- p-0 لإزالة الحشوة الزائدة من حول الجدول --}}
            {{-- رأس التقرير للعرض فقط --}}
            <h4 class="text-md font-weight-bold mb-3 mt-3 text-secondary text-center">
                البيانات بتاريخ: {{ now()->translatedFormat('d F Y') }}
                @if ($formattedSelectedMonth)
                    <span class="text-primary"> (شهر {{ $formattedSelectedMonth }})</span>
                @endif
                @if ($searchItem)
                    <span class="text-primary"> (المورد: {{ $searchItem }})</span>
                @endif
            </h4>

            @if (!empty($searchItem))
                <div class="alert alert-info total-summary mb-4 text-center">
                    <p class="mb-0">
                        إجمالي كمية "<span class="font-weight-bold">{{ $searchItem }}</span>" المصروفة:
                        <span class="h4 ml-2">{{ $totalQuantityForSearchItem }}</span>
                        @if (!empty($resources) && isset($resources[0]['resource_unit']))
                            <span class="text-muted">
                                {{ $resources[0]['resource_unit'] }}
                            </span>
                        @endif
                    </p>
                </div>
            @endif

            <div class="table-responsive"> {{-- Bootstrap responsive table --}}
                @if($resources->isEmpty())
                    <div class="alert alert-info" role="alert">
                        لا توجد موارد مستخدمة لعرضها بهذه المعايير.
                    </div>
                @else
                    <table class="table table-bordered table-striped table-hover text-center table-sm"> {{-- Bootstrap table classes --}}
                        <thead>
                            <tr class="bg-light"> {{-- bg-light for header background --}}
                                <th class="text-nowrap">التاريخ</th>
                                <th class="text-nowrap">الوحدة</th>
                                <th class="text-nowrap">نوع المهمة</th>
                                <th class="text-nowrap">المورد</th>
                                <th class="text-nowrap">الكمية</th>
                                <th class="text-nowrap">وحدة المورد</th>
                                <th class="text-nowrap">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resources as $res)
                                <tr>
                                    <td class="text-nowrap">{{ $res['date'] }}</td>
                                    <td class="text-nowrap">{{ $res['unit'] }}</td>
                                    <td class="text-nowrap">{{ $res['task_type'] }}</td>
                                    <td class="text-nowrap">{{ $res['item'] }}</td>
                                    <td>{{ $res['quantity'] }}</td>
                                    <td class="text-nowrap">{{ $res['resource_unit'] }}</td>
                                    <td>{{ $res['notes'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts') {{-- لربط سكربتات خاصة بهذه الصفحة --}}
    <script>
        function printReport() {
            // توجيه المستخدم إلى صفحة الطباعة مع تمرير نفس الفلاتر
            const urlParams = new URLSearchParams(window.location.search);
            const printUrl = `{{ route('resource-report.print') }}?${urlParams.toString()}`;
            window.open(printUrl, '_blank');
        }
    </script>
@endsection {{-- نهاية قسم السكربتات --}}
