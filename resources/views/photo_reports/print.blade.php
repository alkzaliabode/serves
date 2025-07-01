{{-- resources/views/photo_reports/print.blade.php --}}
{{--
    هذا الملف مخصص لعرض تفاصيل تقرير مصور واحد ضمن واجهة AdminLTE.
    يحتوي على الأنماط والمحتوى الخاص بالعرض داخل النظام فقط.
    زر "طباعة" في هذا الملف سيقوم بتوجيه المستخدم إلى صفحة الطباعة المنفصلة.
--}}

@extends('layouts.adminlte')

@section('title', 'تفاصيل تقرير المهام المصور')
@section('page_title', '🖼️ تفاصيل تقرير المهام المصور')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('photo_reports.index') }}">التقارير المصورة</a></li>
    <li class="breadcrumb-item active">تفاصيل التقرير</li>
@endsection

@section('styles')
    {{-- أنماط CSS لوضع العرض العادي --}}
    <style>
        /* Define an accent color variable for distinctiveness */
        :root { --accent-color: #00eaff; }

        /* أنماط البطاقات لتكون شفافة بالكامل مع تأثير زجاجي وخطوط بارزة (تأثير الزجاج المتجمد) */
        .card {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(8px) !important;
            border-radius: 1rem !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        
        /* General text size increase and distinctive color for main texts */
        body, .card-body {
            font-size: 1.1rem !important;
            line-height: 1.7 !important;
            color: white !important;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6) !important;
        }

        /* Titles and Headers - make them more prominent and interactive */
        .card-title, .card-header h3.card-title, .card-header h2.card-title, .card-header .btn {
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            color: var(--accent-color) !important;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9) !important;
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }
        .card-title:hover, .card-header h3.card-title:hover, .card-header h2.card-title:hover {
            color: #ffffff !important;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 1.0) !important;
        }

        /* أنماط حقول الإدخال والاختيار والتكست اريا */
        .form-control, .form-select, input[type="text"], input[type="email"], input[type="password"],
        input[type="number"], input[type="date"], textarea, select {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6) !important;
            font-size: 1.2rem !important;
            padding: 0.8rem 1.2rem !important;
            border-radius: 0.5rem !important;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.7) !important; }
        .form-control:focus, .form-select:focus, input:focus, textarea:focus, select:focus {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 0.3rem rgba(0, 234, 255, 0.4) !important;
        }
        .form-select option { background-color: #2c3e50 !important; color: white !important; }

        /* أنماط تسميات الحقول */
        .form-label, label, .report-details-card .card-body strong {
            font-size: 1.2rem !important;
            font-weight: 600 !important;
            color: var(--accent-color) !important;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8) !important;
            margin-bottom: 0.5rem;
            display: inline-block;
            width: 150px;
        }

        /* أنماط الأزرار */
        .btn {
            font-weight: 600;
            padding: 0.7rem 1.4rem;
            border-radius: 0.75rem;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
        }
        .btn-primary { background-color: #007bff !important; border-color: #007bff !important; color: white !important; box-shadow: 0 4px 10px rgba(0, 123, 255, 0.5) !important; }
        .btn-primary:hover { background-color: #0056b3 !important; border-color: #0056b3 !important; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0, 123, 255, 0.7) !important; filter: brightness(1.15); }
        .btn-secondary { background-color: #6c757d !important; border-color: #6c757d !important; color: white !important; box-shadow: 0 4px 10px rgba(108, 117, 125, 0.5) !important; }
        .btn-secondary:hover { background-color: #5a6268 !important; border-color: #545b62 !important; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(108, 117, 125, 0.7) !important; filter: brightness(1.15); }
        .btn-danger { background-color: #dc3545 !important; border-color: #dc3545 !important; color: white !important; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.5) !important; }
        .btn-danger:hover { background-color: #c82333 !important; border-color: #bd2130 !important; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(220, 53, 69, 0.7) !important; filter: brightness(1.15); }
        .btn-info { background-color: #17a2b8 !important; border-color: #17a2b8 !important; color: white !important; box-shadow: 0 4px 10px rgba(23, 162, 184, 0.5) !important; }
        .btn-info:hover { background-color: #138496 !important; border-color: #138496 !important; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(23, 162, 184, 0.7) !important; filter: brightness(1.15); }
        .btn-success { background-color: #28a745 !important; border-color: #28a745 !important; color: white !important; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.5) !important; }
        .btn-success:hover { background-color: #218838 !important; border-color: #218838 !important; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(40, 167, 69, 0.7) !important; filter: brightness(1.15); }

        /* أنماط الأيقونات في الأزرار */
        .btn .fas { margin-right: 8px; font-size: 1.1rem; }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important;
            color: #333 !important;
            border-color: rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.75rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
            font-size: 1.1rem !important;
            padding: 1.25rem 1.5rem !important;
        }
        .alert-success { background-color: rgba(40, 167, 69, 0.95) !important; color: white !important; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); }
        .alert-danger { background-color: rgba(220, 53, 69, 0.95) !important; color: white !important; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); }
        .alert-info { background-color: rgba(23, 162, 184, 0.95) !important; color: white !important; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); }

        /* أنماط النص المساعد (form-text) */
        .form-text { font-size: 1rem !important; color: rgba(255, 255, 255, 0.8) !important; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6) !important; margin-top: 0.4rem; }

        /* Badge styling */
        .badge {
            font-size: 0.9rem !important;
            padding: 0.5em 0.7em !important;
            border-radius: 0.5rem !important;
            font-weight: 600 !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .badge.bg-success { background-color: rgba(40, 167, 69, 0.9) !important; color: white !important; }
        .badge.bg-info { background-color: rgba(23, 162, 184, 0.9) !important; color: white !important; }
        .badge.bg-warning { background-color: rgba(255, 193, 7, 0.9) !important; color: #333 !important; text-shadow: none !important; }
        .badge.bg-secondary { background-color: rgba(108, 117, 125, 0.9) !important; color: white !important; }

        /* Specific styles for the report details card */
        .report-details-card .card-body p { margin-bottom: 8px; font-size: 1.1rem; color: white; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6); }
        
        /* Gallery Section Styles */
        .gallery-section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 10px;
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            color: var(--accent-color) !important;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9) !important;
        }
        .gallery-container { display: flex; flex-wrap: wrap; gap: 15px; justify-content: start; margin-top: 20px; }
        .gallery-item {
            flex: 0 0 calc(25% - 15px);
            max-width: calc(25% - 15px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .gallery-item:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); }
        .gallery-item img { width: 100%; height: 150px; object-fit: cover; display: block; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .gallery-item .caption { padding: 10px; font-size: 0.9em; color: white; text-align: center; line-height: 1.4; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6); }

        /* استجابة الشاشات الصغيرة */
        @media (max-width: 1200px) { .gallery-item { flex: 0 0 calc(33.333% - 15px); max-width: calc(33.333% - 15px); } }
        @media (max-width: 768px) { .gallery-item { flex: 0 0 calc(50% - 15px); max-width: calc(50% - 15px); } }
        @media (max-width: 576px) { .gallery-item { flex: 0 0 calc(100% - 15px); max-width: calc(100% - 15px); } }
    </style>
@endsection

@section('content')
    {{-- محتوى التقرير لوضع العرض العادي --}}
    <div class="card report-details-card">
        <div class="card-header">
            <h3 class="card-title">تفاصيل التقرير: {{ $photo_report->report_title }}</h3>
            <div class="card-tools"> {{-- الأزرار هنا مرئية دائمًا في وضع العرض --}}
                <a href="{{ route('photo_reports.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> العودة إلى القائمة
                </a>
                <a href="{{ route('photo_reports.edit', $photo_report->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                {{-- هذا الزر يوجه إلى صفحة الطباعة المنفصلة --}}
                <a href="{{ route('photo_reports.print', $photo_report->id) }}?print=1" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> طباعة
                </a>
                <form action="{{ route('photo_reports.destroy', $photo_report->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد أنك تريد حذف هذا التقرير؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>عنوان التقرير:</strong> {{ $photo_report->report_title }}</p>
                    <p><strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($photo_report->date)->format('Y-m-d') }}</p>
                    <p><strong>الموقع:</strong> {{ $photo_report->location }}</p>
                    <p><strong>نوع الوحدة:</strong> {{ $photo_report->unit_type }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>نوع المهمة:</strong> {{ $photo_report->task_type ?? 'غير محدد' }}</p>
                    <p><strong>معرف المهمة:</strong> {{ $photo_report->task_id ?? 'غير محدد' }}</p>
                    <p><strong>الحالة:</strong>
                        <span class="badge {{ $photo_report->status === 'مكتملة' ? 'bg-success' : ($photo_report->status === 'قيد التنفيذ' ? 'bg-info' : 'bg-warning') }}">
                            {{ $photo_report->status }}
                        </span>
                    </p>
                    <p><strong>آخر تحديث:</strong> {{ \Carbon\Carbon::parse($photo_report->updated_at)->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <hr style="border-top: 1px solid rgba(255, 255, 255, 0.2);">

            <h4>الملاحظات:</h4>
            @if($photo_report->notes)
                <p>{{ $photo_report->notes }}</p>
            @else
                <p>لا توجد ملاحظات لهذا التقرير.</p>
            @endif

            <hr style="border-top: 1px solid rgba(255, 255, 255, 0.2);">

            <h4 class="gallery-section-title">صور قبل التنفيذ:</h4>
            @if($photo_report->before_images_urls && count($photo_report->before_images_urls) > 0)
                <div class="gallery-container">
                    @foreach($photo_report->before_images_urls as $image)
                        <div class="gallery-item">
                            <img src="{{ $image['url'] }}" alt="صورة قبل التنفيذ" onerror="this.onerror=null;this.src='https://placehold.co/80x80/cccccc/333333?text=Image+Not+Found';">
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center">لا توجد صور قبل التنفيذ مرفقة بهذا التقرير.</p>
            @endif

            <h4 class="gallery-section-title">صور بعد التنفيذ:</h4>
            @if($photo_report->after_images_urls && count($photo_report->after_images_urls) > 0)
                <div class="gallery-container">
                    @foreach($photo_report->after_images_urls as $image)
                        <div class="gallery-item">
                            <img src="{{ $image['url'] }}" alt="صورة بعد التنفيذ" onerror="this.onerror=null;this.src='https://placehold.co/80x80/cccccc/333333?text=Image+Not+Found';">
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center">لا توجد صور بعد التنفيذ مرفقة بهذا التقرير.</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    {{-- سكربتات لوضع العرض العادي --}}
    <script>
        // يمكن إضافة أي سكريبتات خاصة بالعرض العادي هنا
    </script>
@endsection
