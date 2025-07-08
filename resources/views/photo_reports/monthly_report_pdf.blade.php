{{-- resources/views/photo_reports/monthly_report_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير الصور الشهري - {{ \Carbon\Carbon::create(null, $month, 1)->monthName }} {{ $year }}</title>
    <style>
        /* الخطوط */
        @font-face {
            font-family: 'Amiri';
            src: url('{{ public_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Amiri';
            src: url('{{ public_path('fonts/Amiri-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Amiri';
            src: url('{{ public_path('fonts/Amiri-Italic.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: italic;
        }
        @font-face {
            font-family: 'Amiri';
            src: url('{{ public_path('fonts/Amiri-BoldItalic.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: italic;
        }

        body {
            font-family: 'Amiri', serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-size: 10px; /* حجم خط مناسب للـ PDF */
            direction: rtl;
            text-align: right;
            color: #333;
        }
        .container {
            width: 100%;
            padding: 10mm;
            box-sizing: border-box;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Amiri', serif;
            color: #0056b3; /* لون أزرق داكن للعناوين */
            text-align: center;
            margin-bottom: 10px;
        }
        h1 { font-size: 20px; }
        h2 { font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 20px;}
        h3 { font-size: 14px; color: #007bff; margin-top: 15px;}
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #555;
        }
        .report-section {
            margin-bottom: 20px;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .report-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .report-details p {
            margin: 0;
            padding: 2px 0;
            font-size: 11px;
        }
        .images-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* لتوسيط الصور */
            margin-top: 10px;
        }
        .image-item {
            width: 48%; /* حوالي نصف عرض الصفحة لكل صورة */
            margin: 1%; /* مسافة صغيرة بين الصور */
            box-sizing: border-box;
            text-align: center;
            page-break-inside: avoid; /* لتجنب كسر الصور عبر الصفحات */
        }
        .image-item img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            padding: 3px;
            background-color: #fff;
            display: block;
            margin: 0 auto;
        }
        .image-item span {
            display: block;
            font-size: 9px;
            margin-top: 5px;
            color: #777;
        }
        .no-images-text {
            text-align: center;
            color: #999;
            font-style: italic;
            margin-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>التقرير الشهري المصور</h1>
        <div class="header-info">
            <p><strong>الشهر:</strong> {{ \Carbon\Carbon::create(null, $month, 1)->monthName }}</p>
            <p><strong>السنة:</strong> {{ $year }}</p>
            <p><strong>نوع الوحدة:</strong> {{ $unit_type_display }}</p>
            <p><strong>تاريخ التقرير:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
        </div>

        @forelse ($reports as $report)
            @if ($loop->index > 0) {{-- إضافة فاصل صفحة بعد التقرير الأول --}}
                <div class="page-break"></div>
            @endif
            <div class="report-section">
                <h2>{{ $report->report_title }} ({{ $report->date->format('Y-m-d') }})</h2>
                <div class="report-details">
                    <p><strong>نوع الوحدة:</strong> {{ $report->unit_type == 'cleaning' ? 'نظافة عامة' : 'منشآت صحية' }}</p>
                    <p><strong>الموقع:</strong> {{ $report->location }}</p>
                    <p><strong>نوع المهمة:</strong> {{ $report->task_type ?? 'غير محدد' }}</p>
                    <p><strong>معرف المهمة:</strong> {{ $report->task_id ?? 'غير محدد' }}</p>
                    <p><strong>الحالة:</strong>
                        @if($report->status == 'completed') مكتمل
                        @elseif($report->status == 'pending') معلق
                        @else ملغى
                        @endif
                    </p>
                    <p><strong>ملاحظات:</strong> {{ $report->notes ?? 'لا يوجد' }}</p>
                </div>

                <h3>صور قبل التنفيذ ({{ $report->before_images_count }})</h3>
                @if($report->before_images_urls && count($report->before_images_urls) > 0)
                    <div class="images-container">
                        @foreach($report->before_images_urls as $image)
                            @if($image['exists'] && $image['absolute_path_for_pdf'])
                                <div class="image-item">
                                    <img src="{{ $image['absolute_path_for_pdf'] }}" alt="صورة قبل التنفيذ">
                                    <span>المسار: {{ $image['path'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="no-images-text">لا توجد صور قبل التنفيذ لهذا التقرير.</p>
                @endif

                <h3>صور بعد التنفيذ ({{ $report->after_images_count }})</h3>
                @if($report->after_images_urls && count($report->after_images_urls) > 0)
                    <div class="images-container">
                        @foreach($report->after_images_urls as $image)
                            @if($image['exists'] && $image['absolute_path_for_pdf'])
                                <div class="image-item">
                                    <img src="{{ $image['absolute_path_for_pdf'] }}" alt="صورة بعد التنفيذ">
                                    <span>المسار: {{ $image['path'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="no-images-text">لا توجد صور بعد التنفيذ لهذا التقرير.</p>
                @endif
            </div>
        @empty
            <p style="text-align: center; margin-top: 50px; font-size: 14px; color: #888;">
                لا توجد تقارير مصورة متوفرة للشهر والسنة ونوع الوحدة المحدد.
            </p>
        @endforelse
    </div>
</body>
</html>