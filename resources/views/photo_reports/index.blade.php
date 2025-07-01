{{-- resources/views/photo_reports/index.blade.php --}}

@extends('layouts.adminlte')

@section('title', 'قائمة التقارير المصورة')

@section('page_title', '🖼️ قائمة التقارير المصورة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">التقارير المصورة</li>
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
        .badge {
            font-size: 0.9rem !important;
            padding: 0.5em 0.7em !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .badge.bg-success {
            background-color: rgba(40, 167, 69, 0.8) !important;
            color: white !important;
        }
        .badge.bg-danger {
            background-color: rgba(220, 53, 69, 0.8) !important;
            color: white !important;
        }
        .badge.bg-info {
            background-color: rgba(23, 162, 184, 0.9) !important;
            color: white !important;
        }
        .badge.bg-warning {
            background-color: rgba(255, 193, 7, 0.9) !important;
            color: #333 !important; /* Darker text for warning badge */
            text-shadow: none !important;
        }
        .badge.bg-secondary {
            background-color: rgba(108, 117, 125, 0.9) !important;
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">قائمة التقارير المصورة</h3>
            <div class="card-tools">
                <a href="{{ route('photo_reports.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> إضافة تقرير جديد
                </a>
            </div>
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

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان التقرير</th>
                            <th>التاريخ</th>
                            <th>نوع الوحدة</th>
                            <th>الموقع</th>
                            <th>نوع المهمة</th>
                            <th>معرف المهمة</th>
                            <th>الحالة</th>
                            <th>صور قبل</th>
                            <th>صور بعد</th>
                            <th>الملاحظات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taskImageReports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $report->report_title }}</td>
                                <td>{{ $report->date->format('Y-m-d') }}</td>
                                <td>{{ $report->unit_type }}</td>
                                <td>{{ $report->location }}</td>
                                <td>{{ $report->task_type }}</td>
                                <td>{{ $report->task_id }}</td>
                                <td>
                                    @if($report->status == 'مكتملة')
                                        <span class="badge bg-success">{{ $report->status }}</span>
                                    @elseif($report->status == 'قيد التنفيذ')
                                        <span class="badge bg-info">{{ $report->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $report->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->before_images_count > 0)
                                        <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 5px;">
                                            @foreach($report->before_images_for_table as $imageUrl)
                                                <img src="{{ $imageUrl }}" alt="قبل" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" onerror="this.onerror=null;this.src='https://placehold.co/50x50/cccccc/333333?text=N/A';">
                                            @endforeach
                                            @if($report->before_images_count > 3)
                                                <span class="badge bg-secondary">+{{ $report->before_images_count - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        لا توجد صور
                                    @endif
                                </td>
                                <td>
                                    @if($report->after_images_count > 0)
                                        <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 5px;">
                                            @foreach($report->after_images_for_table as $imageUrl)
                                                <img src="{{ $imageUrl }}" alt="بعد" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" onerror="this.onerror=null;this.src='https://placehold.co/50x50/cccccc/333333?text=N/A';">
                                            @endforeach
                                            @if($report->after_images_count > 3)
                                                <span class="badge bg-secondary">+{{ $report->after_images_count - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        لا توجد صور
                                    @endif
                                </td>
                                <td>{{ Str::limit($report->notes, 50) }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('photo_reports.show', $report->id) }}" class="btn btn-sm btn-primary" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i> عرض
                                    </a>
                                    <a href="{{ route('photo_reports.edit', $report->id) }}" class="btn btn-sm btn-info" title="تعديل">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('photo_reports.destroy', $report->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا التقرير؟')" title="حذف">
                                            <i class="fas fa-trash"></i> حذف
                                        </button>
                                    </form>
                                    <a href="{{ route('photo_reports.print', $report->id) }}?print=1" target="_blank" class="btn btn-sm btn-secondary" title="طباعة">
                                        <i class="fas fa-print"></i> طباعة
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12">لا توجد تقارير مصورة لعرضها.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- لا توجد حاجة لسكريبتات مخصصة هنا حالياً --}}
@endsection
