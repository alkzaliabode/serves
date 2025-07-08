{{-- resources/views/reports/resource-report-print.blade.php --}}
{{--
    هذا الملف هو قالب صفحة تقرير الموارد المستخدمة المخصص للطباعة.
    تم تحديث تصميمه ليتوافق مع تصميم صفحة تقرير المنشآت الصحية الشهرية المخصصة للطباعة.
    يحتوي فقط على رأس الطباعة والجدول وتذييل الطباعة.
--}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الموارد المستخدمة - طباعة</title>
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
        .total-summary {
            font-size: 11px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
            border: 1px solid #ccc; /* الإطار الافتراضي */
            padding: 8px;
            background-color: #f9f9f9;
        }
        .total-summary p { margin-bottom: 0; }

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

            /* ✅ التعديل هنا: جعل إطار ملخص الإجمالي أكثر وضوحاً عند الطباعة */
            .total-summary {
                border: 2px solid #000 !important; /* إطار أسود أكثر سمكاً */
                background-color: #f0f0f0 !important; /* خلفية خفيفة */
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container" lang="ar" dir="rtl">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}"
                 alt="شعار المدينة"
                 class="logo"
                 onerror="this.onerror=null; this.src='https://placehold.co/60x60/FF0000/FFFFFF?text=خطأ+شعار';"
                 title="إذا لم يظهر الشعار، تأكد من مسار الصورة في مجلد public/images">
            <div class="text-content">
                <div class="title">تقرير الموارد المستخدمة في المهام</div>
                <div class="subtitle">قسم مدينة الإمام الحسين (ع) للزائرين</div>
                <div class="subtitle">البيانات بتاريخ: {{ now()->translatedFormat('d F Y') }}</div>
                @if ($formattedSelectedMonth)
                    <div class="subtitle">شهر: {{ $formattedSelectedMonth }}</div>
                @endif
                @if ($searchItem)
                    <div class="subtitle">المورد: {{ $searchItem }}</div>
                @endif
            </div>
        </div>

        @if (!empty($searchItem))
            <div class="total-summary">
                <p class="mb-0">
                    إجمالي كمية "<span style="font-weight: bold;">{{ $searchItem }}</span>" المصروفة:
                    <span style="font-size: 1.2em; margin-left: 5px;">{{ $totalQuantityForSearchItem }}</span>
                    @if (!empty($resources) && isset($resources[0]['resource_unit']))
                        <span style="color: #6c757d;">
                            {{ $resources[0]['resource_unit'] }}
                        </span>
                    @endif
                </p>
            </div>
        @endif

        @if($resources->isEmpty())
            <div style="text-align: center; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9;">
                لا توجد موارد مستخدمة لعرضها بهذه المعايير.
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>الوحدة</th>
                        <th>نوع المهمة</th>
                        <th>المورد</th>
                        <th>الكمية</th>
                        <th>وحدة المورد</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resources as $res)
                        <tr>
                            <td>{{ $res['date'] }}</td>
                            <td>{{ $res['unit'] }}</td>
                            <td>{{ $res['task_type'] }}</td>
                            <td>{{ $res['item'] }}</td>
                            <td>{{ $res['quantity'] }}</td>
                            <td>{{ $res['resource_unit'] }}</td>
                            <td>{{ $res['notes'] ?? '' }}</td>
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
