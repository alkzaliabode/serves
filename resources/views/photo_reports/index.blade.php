{{-- resources/views/photo_reports/index.blade.php --}}
{{--
    هذا الملف هو صفحة عرض قائمة التقارير المصورة.
    يستخدم الـ Accessors الجديدة في نموذج TaskImageReport لعرض الصور المصغرة.
--}}

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
            border: 1px solid rgba(255, 255, 255, 0.2) !important; /* حدود شفافة ولكن واضحة */
            transition: all 0.3s ease; /* انتقال سلس عند التفاعل */
        }

        .card-header {
            background: rgba(255, 255, 255, 0.05) !important; /* خلفية شفافة لرأس البطاقة */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important; /* خط فاصل شفاف */
            color: var(--accent-color); /* لون مميز للعناوين */
            font-weight: bold;
        }

        .card-title {
            color: #fff; /* لون أبيض لعناوين البطاقات */
        }

        .table {
            color: #e0e0e0; /* لون فاتح للنص في الجدول */
        }

        .table thead th {
            color: var(--accent-color); /* لون مميز لرؤوس الأعمدة */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }

        .table tbody tr {
            background: rgba(255, 255, 255, 0.03); /* خلفية خفيفة لصفوف الجدول */
        }

        .table tbody tr:nth-of-type(odd) {
            background: rgba(255, 255, 255, 0.05); /* تظليل الصفوف الفردية بشكل مختلف قليلاً */
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1); /* تأثير عند مرور الماوس */
        }

        .table td, .table th {
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important; /* حدود خلايا شفافة */
        }

        /* أزرار الإجراءات */
        .btn-primary {
            background-color: #007bff; /* أزرق قياسي */
            border-color: #007bff;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #138496;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
        }

        /* أنماط الصور المصغرة في الجدول */
        .img-thumbnail {
            max-width: 50px;
            max-height: 50px;
            width: auto;
            height: auto;
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            object-fit: cover; /* لضمان تغطية الصورة للمساحة المخصصة */
        }

        /* تحسين مظهر روابط الإجراءات */
        .action-buttons a.btn, .action-buttons button.btn {
            margin-right: 5px; /* مسافة بين الأزرار */
            margin-bottom: 5px; /* للمساحات الصغيرة */
        }

        /* لوضع الأزرار جنبًا إلى جنب مع زر جديد لإنشاء التقرير الشهري */
        .card-header .d-flex {
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* للسماح بالالتفاف على الشاشات الصغيرة */
        }

        .card-header .btn-group-custom {
            display: flex;
            gap: 10px; /* مسافة بين الأزرار */
            flex-wrap: wrap;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title text-white">التقارير المصورة</h3>
                <div class="btn-group-custom">
                    <a href="{{ route('photo_reports.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> إضافة تقرير مصور جديد
                    </a>
                    {{-- NEW: زر لإنشاء تقرير شهري --}}
                    <a href="{{ route('photo_reports.monthly_report_form') }}" class="btn btn-info">
                        <i class="fas fa-file-pdf"></i> إنشاء تقرير شهري
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>عنوان التقرير</th>
                            <th>تاريخ</th>
                            <th>نوع الوحدة</th>
                            <th>الموقع</th>
                            <th>نوع المهمة</th>
                            <th>معرف المهمة</th>
                            <th>صور قبل التنفيذ (3 كحد أقصى)</th>
                            <th>صور بعد التنفيذ (3 كحد أقصى)</th>
                            <th>الحالة</th>
                            <th>ملاحظات</th>
                            <th style="width: 200px">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ✅ تم التعديل: تغيير اسم المتغير من $taskImageReports إلى $photo_reports --}}
                        @forelse ($photo_reports as $report)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $report->report_title }}</td>
                                <td>{{ $report->date->format('Y-m-d') }}</td>
                                <td>{{ $report->unit_type == 'cleaning' ? 'نظافة عامة' : 'منشآت صحية' }}</td>
                                <td>{{ $report->location }}</td>
                                <td>{{ $report->task_type ?? 'N/A' }}</td>
                                <td>{{ $report->task_id ?? 'N/A' }}</td>
                                <td>
                                    @if(!empty($report->before_images_for_table))
                                        <div class="d-flex flex-wrap align-items-center">
                                            @foreach($report->before_images_for_table as $image_url)
                                                {{-- تأكد من تشغيل الأمر 'php artisan storage:link' في سطر الأوامر بمشروعك --}}
                                                <img src="{{ $image_url }}" class="img-thumbnail mr-1" alt="قبل" onerror="this.onerror=null;this.src='https://placehold.co/50x50/cccccc/333333?text=N/A';">
                                            @endforeach
                                            @if($report->before_images_count > 3)
                                                <span class="badge badge-secondary ml-1">+{{ $report->before_images_count - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        لا يوجد
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($report->after_images_for_table))
                                        <div class="d-flex flex-wrap align-items-center">
                                            @foreach($report->after_images_for_table as $image_url)
                                                {{-- تأكد من تشغيل الأمر 'php artisan storage:link' في سطر الأوامر بمشروعك --}}
                                                <img src="{{ $image_url }}" class="img-thumbnail mr-1" alt="بعد" onerror="this.onerror=null;this.src='https://placehold.co/50x50/cccccc/333333?text=N/A';">
                                            @endforeach
                                            @if($report->after_images_count > 3)
                                                <span class="badge badge-secondary ml-1">+{{ $report->after_images_count - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        لا يوجد
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $report->status == 'مكتمل' ? 'bg-success' : ($report->status == 'قيد التنفيذ' ? 'bg-info' : ($report->status == 'ملغى' ? 'bg-danger' : 'bg-warning')) }}">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td>{{ $report->notes ?? 'لا يوجد' }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('photo_reports.show', $report->id) }}" class="btn btn-sm btn-primary" title="عرض">
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