{{-- resources/views/photo_reports/index.blade.php --}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

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
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }

        /* أنماط للعناوين والأزرار والنصوص داخل البطاقات */
        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important; /* خط فاصل أبيض خفيف */
            color: var(--accent-color); /* لون مميز للعناوين */
            background-color: transparent !important; /* خلفية شفافة لرأس البطاقة */
            padding: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            color: #ffffff; /* أبيض ناصع لعنوان البطاقة */
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-body {
            color: #e0e0e0; /* لون فاتح للنصوص */
            padding: 1.5rem;
        }

        .table {
            color: #e0e0e0; /* لون فاتح للنصوص داخل الجدول */
            margin-bottom: 0; /* إزالة الهامش السفلي الافتراضي */
        }

        .table thead th {
            border-bottom: 2px solid var(--accent-color); /* خط سفلي مميز لرؤوس الأعمدة */
            color: var(--accent-color); /* لون مميز لرؤوس الأعمدة */
            font-weight: bold;
            text-align: right; /* محاذاة لليمين لرؤوس الأعمدة */
            padding: 0.75rem;
            background-color: rgba(255, 255, 255, 0.05); /* خلفية خفيفة لرؤوس الأعمدة */
        }

        .table tbody td {
            border-top: 1px solid rgba(255, 255, 255, 0.1); /* خط فاصل خفيف بين الصفوف */
            padding: 0.75rem;
            vertical-align: middle;
            text-align: right; /* محاذاة لليمين لخلايا الجدول */
        }

        .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.03); /* تأثير تمرير خفيف */
        }

        .btn {
            border-radius: 0.5rem; /* حواف مستديرة للأزرار */
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            font-size: 0.9rem;
        }

        .btn-primary { background-color: #007bff; border-color: #007bff; }
        .btn-primary:hover { background-color: #0056b3; border-color: #004d9b; }
        .btn-info { background-color: #17a2b8; border-color: #17a2b8; }
        .btn-info:hover { background-color: #138496; border-color: #117a8b; }
        .btn-danger { background-color: #dc3545; border-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; border-color: #bd2130; }
        .btn-success { background-color: #28a745; border-color: #28a745; }
        .btn-success:hover { background-color: #218838; border-color: #1e7e34; }
        .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; border-color: #545b62; }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #ffffff !important;
        }

        .form-control::placeholder {
            color: #b0b0b0 !important;
        }

        /* DataTables enhancements */
        #photo_reports_table_wrapper .dataTables_paginate .paginate_button {
            background-color: transparent !important;
            border: 1px solid var(--accent-color) !important;
            color: var(--accent-color) !important;
            margin: 0 5px;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        #photo_reports_table_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--accent-color) !important;
            color: #333 !important;
        }
        #photo_reports_table_wrapper .dataTables_paginate .paginate_button.current {
            background-color: var(--accent-color) !important;
            color: #333 !important;
        }
        #photo_reports_table_filter label,
        #photo_reports_table_length label {
            color: #e0e0e0;
        }
        #photo_reports_table_info {
            color: #b0b0b0;
        }

        /* 💡 جديد: أنماط للصور المصغرة في الجدول */
        .thumbnail-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px; /* مسافة بين الصور المصغرة */
            justify-content: flex-end; /* محاذاة لليمين */
        }
        .thumbnail-container img {
            width: 40px; /* حجم الصورة المصغرة */
            height: 40px; /* حجم الصورة المصغرة */
            object-fit: cover; /* للحفاظ على نسبة الأبعاد وتغطية المساحة */
            border-radius: 5px; /* حواف مستديرة */
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .thumbnail-container img:hover {
            transform: scale(1.1); /* تكبير بسيط عند التمرير */
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-camera mr-2"></i> التقارير المصورة
                </h3>
                <div class="card-tools">
                    {{-- زر إضافة تقرير مصور جديد --}}
                    <a href="{{ route('photo_reports.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle mr-1"></i> إضافة تقرير مصور جديد
                    </a>
                    {{-- زر إنشاء تقرير شهري عالمي --}}
                    <a href="{{ route('global_reports.monthly_form') }}" class="btn btn-primary btn-sm ml-2">
                        <i class="fas fa-file-alt mr-1"></i> إنشاء تقرير شهري عالمي
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
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <table id="photo_reports_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الموقع</th>
                            <th>تاريخ التقرير</th>
                            <th>الشهر</th>
                            <th>السنة</th>
                            <th>الوحدة</th>
                            <th>نوع المهمة</th>
                            <th><i class="fas fa-image mr-1"></i> صور قبل</th>
                            <th><i class="fas fa-image mr-1"></i> صور بعد</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($photo_reports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('photo_reports.show', $report->id) }}" class="text-info">{{ $report->report_title }}</a></td>
                                <td>{{ $report->location }}</td>
                                <td>{{ $report->date->format('Y-m-d') }}</td>
                                <td>{{ $report->date->locale('ar')->monthName }}</td>
                                <td>{{ $report->date->year }}</td>
                                <td>
                                    @if($report->unit_type == 'cleaning')
                                        النظافة العامة
                                    @elseif($report->unit_type == 'sanitation')
                                        المنشآت الصحية
                                    @else
                                        {{ $report->unit_type }}
                                    @endif
                                </td>
                                <td>{{ $report->task_type }}</td>
                                {{-- 💡 عرض الصور المصغرة قبل التنفيذ --}}
                                <td>
                                    <div class="thumbnail-container">
                                        @forelse($report->before_images_display_urls as $imageUrl)
                                            <img src="{{ $imageUrl }}" alt="صورة قبل" onerror="this.onerror=null;this.src='https://placehold.co/40x40/cccccc/333333?text=N/A';">
                                        @empty
                                            لا توجد
                                        @endforelse
                                    </div>
                                </td>
                                {{-- 💡 عرض الصور المصغرة بعد التنفيذ --}}
                                <td>
                                    <div class="thumbnail-container">
                                        @forelse($report->after_images_display_urls as $imageUrl)
                                            <img src="{{ $imageUrl }}" alt="صورة بعد" onerror="this.onerror=null;this.src='https://placehold.co/40x40/cccccc/333333?text=N/A';">
                                        @empty
                                            لا توجد
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @if($report->status == 'completed')
                                        <span class="badge badge-success">مكتمل</span>
                                    @else
                                        <span class="badge badge-warning">قيد التنفيذ</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('photo_reports.show', $report->id) }}" class="btn btn-sm btn-info" title="عرض">
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
                                    <a href="{{ route('photo_reports.print_standalone', $report->id) }}?print=true" target="_blank" class="btn btn-sm btn-secondary" title="طباعة">
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
