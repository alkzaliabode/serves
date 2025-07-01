{{-- resources/views/monthly-cleaning-report/report.blade.php --}}
{{--
    هذا الملف هو قالب صفحة تقرير النظافة العامة الشهري المخصص للطباعة.
    تم تحديث تصميمه ليتوافق مع تصميم صفحة تقرير المنشآت الصحية الشهرية المخصصة للطباعة.
    يحتوي فقط على رأس الطباعة والجدول وتذييل الطباعة.
--}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير النظافة العامة الشهري - طباعة</title>
    {{-- لا نستخدم Bootstrap هنا لأننا نريد التحكم الكامل في أنماط الطباعة --}}
    <style>
        @page { size: A4 landscape; margin: 10mm; } /* تنسيق أفقي A4 مع هوامش */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            font-size: 11px; /* حجم خط مناسب للعرض والطباعة */
            -webkit-print-color-adjust: exact; /* لطباعة الألوان والخلفيات */
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
            justify-content: center; /* توسيط المحتوى الأفقي */
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .header .logo {
            max-width: 60px; /* حجم الشعار */
            height: auto;
            margin-inline-end: 20px; /* مسافة بين الشعار والنص */
        }
        .header .text-content {
            flex-grow: 1;
            text-align: center; /* توسيط النص */
        }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 14px; margin: 2px 0; color: #555; }
        .filters-display { font-size: 12px; margin-top: 10px; text-align: center; color: #666; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px; /* تصغير حجم الخط في الجدول */
            page-break-inside: auto; /* للسماح بكسر الجدول عبر الصفحات */
        }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; } /* لتكرار رأس الجدول في كل صفحة */
        tfoot { display: table-footer-group; } /* لتكرار تذييل الجدول في كل صفحة */
        th, td {
            border: 1px solid #000 !important; /* حدود سوداء قوية للطباعة */
            padding: 5px; /* تصغير الحشوة */
            text-align: center;
            vertical-align: middle;
            white-space: nowrap; /* لمنع التفاف النص */
        }
        th {
            background-color: #e6e6e6 !important; /* خلفية لرؤوس الأعمدة للطباعة */
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee; /* خط فاصل فوق التذييل */
            padding-top: 10px;
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

        /* أنماط الطباعة النهائية */
        @media print {
            .no-print { display: none; } /* تأكيد إخفاء الأزرار */
            body { font-size: 10px; } /* حجم خط أصغر للطباعة النهائية */
            table { font-size: 9px; } /* حجم خط أصغر للجدول في الطباعة */
            th, td { padding: 3px; } /* حشوة أقل للطباعة */
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
            {{-- شعار المدينة - حافظت عليه من الكود السابق للنظافة العامة، يمكنك إزالته إذا لم يكن مطلوباً --}}
            <img src="{{ asset('images/logo.png') }}"
                 alt="شعار المدينة"
                 class="logo"
                 onerror="this.onerror=null; this.src='https://placehold.co/60x60/FF0000/FFFFFF?text=خطأ+شعار';"
                 title="إذا لم يظهر الشعار، تأكد من مسار الصورة في مجلد public/images">
            <div class="text-content">
                <div class="title">تقرير النظافة العامة الشهري</div>
                <div class="subtitle">قسم مدينة الإمام الحسين (ع) للزائرين</div> {{-- يمكن تعديل هذا ليعكس القسم الفعلي --}}
                <div class="subtitle">البيانات بتاريخ: {{ now()->translatedFormat('d F Y') }}</div>
                <div class="filters-display">
                    @if ($selectedMonth)
                        <span>الشهر: {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}</span>
                    @endif
                    @if ($selectedLocation)
                        <span> | الموقع: {{ $selectedLocation }}</span>
                    @endif
                    @if ($selectedTaskType)
                        <span> | نوع المهمة: {{ $selectedTaskType }}</span>
                    @endif
                    @if ($searchQuery)
                        <span> | بحث: "{{ $searchQuery }}"</span>
                    @endif
                    @if(empty($selectedMonth) && empty($selectedLocation) && empty($selectedTaskType) && empty($searchQuery))
                        <span>(جميع الفلاتر غير مطبقة)</span>
                    @endif
                </div>
            </div>
        </div>

        @if($reports->isEmpty())
            <div style="text-align: center; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9;">
                لا توجد بيانات لتقرير النظافة العامة لعرضها بهذه المعايير.
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>الشهر</th>
                        <th>الموقع</th>
                        <th>نوع المهمة</th>
                        <th>المنادر</th>
                        <th>الوسائد</th>
                        <th>المراوح</th>
                        <th>النوافذ</th>
                        <th>السجاد</th>
                        <th>البطانيات</th>
                        <th>الأسرة</th>
                        <th>المستفيدون</th>
                        <th>الترامز</th>
                        <th>السجاد المفروش</th>
                        <th>حاويات كبيرة</th>
                        <th>حاويات صغيرة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($report->month)->translatedFormat('F Y') }}</td>
                            <td>{{ $report->location }}</td>
                            <td>{{ $report->task_type }}</td>
                            <td>{{ $report->total_mats }}</td>
                            <td>{{ $report->total_pillows }}</td>
                            <td>{{ $report->total_fans }}</td>
                            <td>{{ $report->total_windows }}</td>
                            <td>{{ $report->total_carpets }}</td>
                            <td>{{ $report->total_blankets }}</td>
                            <td>{{ $report->total_beds }}</td>
                            <td>{{ $report->total_beneficiaries }}</td>
                            <td>{{ $report->total_trams }}</td>
                            <td>{{ $report->total_laid_carpets }}</td>
                            <td>{{ $report->total_large_containers }}</td>
                            <td>{{ $report->total_small_containers }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer">
            &copy; {{ date('Y') }} نظام إدارة المهام. جميع الحقوق محفوظة.
            <br>
            الصفحة 1 من 1 {{-- يمكنك جعل هذا ديناميكيًا إذا كان التقرير يمتد لعدة صفحات --}}
        </div>
    </div>

    {{-- أزرار الطباعة والإغلاق تظهر فقط عندما لا تكون الصفحة في وضع الطباعة --}}
    <div class="no-print">
        <button onclick="window.print()" class="print-button">طباعة التقرير</button>
        <button onclick="window.close()" class="close-button">إغلاق النافذة</button>
    </div>

    <script>
        // الطباعة التلقائية عند تحميل الصفحة
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // تأخير بسيط لضمان تحميل كل العناصر
        };
    </script>
</body>
</html>
