<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المنشآت الصحية الشهري - طباعة</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; } /* تنسيق أفقي للحصول على مساحة أكبر */
        body { font-family: 'Arial', sans-serif; line-height: 1.4; color: #000; margin: 0; padding: 0; font-size: 11px; }
        .container {
            width: 100%;
            max-width: 280mm; /* عرض أكبر ليتناسب مع A4 landscape */
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 14px; margin: 2px 0; color: #555; }
        .filters-display { font-size: 12px; margin-top: 10px; text-align: center; color: #666; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px; /* تصغير حجم الخط في الجدول */
        }
        th, td {
            border: 1px solid #000;
            padding: 5px; /* تصغير الحشوة */
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #e6e6e6;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #777;
        }
        .no-print {
            display: none; /* إخفاء هذا القسم عند الطباعة */
            text-align: center;
            margin-top: 20px;
        }
        .print-button, .close-button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-button { background: #4CAF50; color: white; }
        .close-button { background: #f44336; color: white; margin-right: 10px; }

        @media print {
            .no-print { display: none; }
            body { font-size: 10px; } /* حجم خط أصغر للطباعة النهائية */
            table { font-size: 9px; }
            th, td { padding: 3px; }
            .header { margin-bottom: 10px; }
            .title { font-size: 16px; }
            .subtitle { font-size: 12px; }
            .filters-display { font-size: 10px; margin-top: 5px; }
            .footer { font-size: 9px; margin-top: 10px; }
        }
    </style>
</head>
<body>
    <div class="container" lang="ar" dir="rtl">
        <div class="header">
            <div class="title">تقرير المنشآت الصحية الشهري</div>
            <div class="subtitle">ملخصات الأداء الشهرية</div>
            <div class="filters-display">
                @if(!empty($filters['month_display']))
                    <span>الشهر: {{ $filters['month_display'] }}</span>
                @endif
                @if(!empty($filters['facility_name']))
                    <span> | اسم المنشأة: {{ $filters['facility_name'] }}</span>
                @endif
                @if(!empty($filters['task_type']))
                    <span> | نوع المهمة: {{ $filters['task_type'] }}</span>
                @endif
                @if(empty($filters['month_display']) && empty($filters['facility_name']) && empty($filters['task_type']))
                    <span>(جميع الفلاتر غير مطبقة)</span>
                @endif
                <span> | تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        @if($monthlySummaries->isEmpty())
            <div style="text-align: center; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9;">
                لا توجد بيانات لتقرير المنشآت الصحية الشهرية بهذه المعايير.
            </div>
        @else
            <table>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer">
            &copy; {{ date('Y') }} نظام إدارة المهام. جميع الحقوق محفوظة.
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="print-button">طباعة التقرير</button>
        <button onclick="window.close()" class="close-button">إغلاق النافذة</button>
    </div>

    <script>
        // طباعة الصفحة تلقائيًا بعد تحميلها
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // تأخير بسيط لضمان تحميل كل العناصر
        };
    </script>
</body>
</html>
