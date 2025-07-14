<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المنشآت الصحية الشهري - طباعة</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; } /* تنسيق أفقي للحصول على مساحة أكبر */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            font-size: 11px;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .container {
            width: 100%;
            max-width: 280mm; /* عرض أكبر ليتناسب مع A4 landscape */
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
        }
        .header {
            display: flex; /* استخدام فليكس بوكس لتنظيم الشعار والعناوين */
            align-items: center;
            justify-content: space-between; /* 💡 دفع العناصر إلى الأطراف مع توسيط النص */
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .header .logo {
            width: 60px; /* 💡 حجم الشعار */
            height: 60px; /* 💡 حجم الشعار */
            object-fit: contain; /* 💡 لضمان احتواء الصورة داخل أبعادها */
            /* margin-inline-end: 20px; تم إزالته لأن justify-content: space-between سيتولى المسافات */
        }
        .header .text-content {
            flex-grow: 1;
            text-align: center; /* توسيط النص */
        }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 14px; margin: 2px 0; color: #555; }
        .filters-display { font-size: 12px; margin-top: 10px; text-align: center; color: #666; }
        .filters-display span { margin: 0 5px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
            page-break-inside: auto;
        }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        th, td {
            border: 1px solid #000 !important;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }
        th {
            background-color: #e6e6e6 !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        /* 💡 أنماط جديدة ومحسنة لقسم الملخص الإحصائي */
        .summary-section {
            margin-top: 25px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            page-break-before: auto;
            page-break-inside: avoid;
        }
        .summary-section h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            text-align: center;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* زيادة العرض الأدنى للعناصر */
            gap: 10px 20px; /* مسافة عمودية وأفقية أكبر */
            text-align: right;
        }
        .summary-item {
            padding: 5px 0;
            font-size: 12px;
            color: #444;
            display: flex;
            justify-content: flex-start; /* تغيير من space-between إلى flex-start */
            align-items: baseline;
            gap: 8px; /* إضافة مسافة صغيرة بين النص والرقم */
            border-bottom: 1px dotted #eee;
            direction: rtl; /* التأكد من أن الاتجاه من اليمين لليسار للحاوية المرنة */
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .summary-item span {
            white-space: nowrap; /* منع النص من الالتفاف لضمان بقائه على سطر واحد مع الرقم */
            text-align: right;
        }
        .summary-item strong {
            color: #000;
            font-size: 14px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .no-print {
            display: none;
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

        /* أنماط الطباعة النهائية */
        @media print {
            .no-print { display: none; }
            body { font-size: 10px; }
            table { font-size: 9px; }
            th, td { padding: 3px; }
            .header { margin-bottom: 10px; }
            .title { font-size: 16px; }
            .subtitle { font-size: 12px; }
            .filters-display { font-size: 10px; margin-top: 5px; }
            .summary-section {
                margin-top: 15px;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f0f0f0;
                border-radius: 5px;
            }
            .summary-section h4 {
                font-size: 14px;
                margin-bottom: 10px;
            }
            .summary-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 5px 10px;
            }
            .summary-item {
                font-size: 10px;
                padding: 4px 0;
                justify-content: flex-start;
                gap: 5px;
            }
            .summary-item strong {
                font-size: 11px;
            }
            .footer { font-size: 9px; margin-top: 10px; }
        }
    </style>
</head>
<body>
    <div class="container" lang="ar" dir="rtl">
        <div class="header">
            {{-- الشعار الأول على اليمين --}}
            <img src="{{ asset('images/logo.png') }}"
                 alt="شعار المؤسسة 1"
                 class="logo"
                 onerror="this.onerror=null; this.src='https://placehold.co/60x60/CCCCCC/666666?text=شعار1';"
                 title="إذا لم يظهر الشعار الأول، تأكد من مساره في مجلد public/images">

            {{-- محتوى النص في المنتصف --}}
            <div class="text-content">
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
                    @if(!empty($filters['unit_name']))
                        <span> | الوحدة: {{ $filters['unit_name'] }}</span>
                    @endif
                    @if(empty($filters['month_display']) && empty($filters['facility_name']) && empty($filters['task_type']) && empty($filters['unit_name']))
                        <span>(جميع الفلاتر غير مطبقة)</span>
                    @endif
                    <span> | تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            {{-- الشعار الثاني على اليسار --}}
            <img src="{{ asset('images/another_logo.png') }}" {{-- افترض أن لديك ملف صورة آخر هنا --}}
                 alt="شعار المؤسسة 2"
                 class="logo"
                 onerror="this.onerror=null; this.src='https://placehold.co/60x60/CCCCCC/666666?text=شعار2';"
                 title="إذا لم يظهر الشعار الثاني، تأكد من مساره في مجلد public/images">
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

            <div class="summary-section">
                <h4>ملخص إحصائي للفترة المحددة (المنشآت الصحية)</h4>
                <div class="summary-grid">
                    <div class="summary-item">
                        <span>إجمالي المقاعد:</span> <strong>{{ $totalSeats }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>إجمالي المرايا:</span> <strong>{{ $totalMirrors }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>إجمالي الخلاطات:</span> <strong>{{ $totalMixers }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>إجمالي الأبواب:</span> <strong>{{ $totalDoors }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>إجمالي الأحواض:</span> <strong>{{ $totalSinks }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>إجمالي المراحيض:</span> <strong>{{ $totalToilets }}</strong>
                    </div>
                    <div class="summary-item">
                        <span>إجمالي المهام المنجزة:</span> <strong>{{ $totalTasks }}</strong>
                    </div>
                </div>
            </div>
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
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
