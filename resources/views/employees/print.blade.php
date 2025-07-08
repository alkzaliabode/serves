<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الموظفين - طباعة</title>
    {{--
        أنماط CSS مخصصة لتقرير الطباعة.
        تم تصميم هذه الأنماط لضمان مظهر احترافي وواضح عند طباعة التقرير،
        مع التركيز على تحسين قراءة الجداول والعناوين على ورق A4 أفقي.
    --}}
    <style>
        /* إعدادات الصفحة للطباعة: حجم A4 أفقي مع هوامش معقولة */
        @page {
            size: A4 landscape;
            margin: 10mm; /* هوامش متساوية من جميع الجوانب */
        }

        /* أنماط الجسم الأساسية: الخطوط، الألوان، أحجام الخطوط الافتراضية */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* خط حديث وواضح */
            line-height: 1.6; /* تباعد أسطر أفضل للقراءة */
            color: #333; /* لون نص داكن لتباين جيد */
            margin: 0;
            padding: 0;
            font-size: 11px; /* حجم خط أساسي صغير بما يكفي للتقرير */
            direction: rtl; /* اتجاه النص من اليمين لليسار */
            text-align: right; /* محاذاة النص لليمين افتراضياً */
        }

        /* حاوية المحتوى: لتحديد عرض المحتوى المركزي وهوامشه */
        .container {
            width: 100%;
            max-width: 285mm; /* عرض قصوى مناسبة لـ A4 landscape */
            margin: 0 auto; /* توسيط الحاوية */
            padding: 5mm; /* حشوة داخلية */
            box-sizing: border-box; /* تضمين الحشوة والحدود في العرض الكلي */
        }

        /* رأس التقرير: لعنوان التقرير والتفاصيل */
        .header {
            text-align: center; /* توسيط محتوى الرأس */
            margin-bottom: 20px; /* مسافة أسفل الرأس */
            padding-bottom: 10px; /* حشوة سفلية */
            border-bottom: 1px solid #ccc; /* خط فاصل رفيع */
        }

        /* عنوان التقرير الرئيسي */
        .title {
            font-size: 22px; /* حجم خط كبير */
            font-weight: bold; /* خط سميك */
            margin: 0 0 5px 0; /* مسافة سفلية */
            color: #2c3e50; /* لون داكن وأنيق */
        }

        /* عنوان فرعي للتقرير */
        .subtitle {
            font-size: 15px; /* حجم خط متوسط */
            margin: 5px 0;
            color: #555; /* لون رمادي أغمق */
        }

        /* عرض الفلاتر المطبقة في التقرير */
        .filters-display {
            font-size: 12px; /* حجم خط أصغر */
            margin-top: 15px; /* مسافة علوية */
            text-align: center;
            color: #666; /* لون رمادي متوسط */
            line-height: 1.8; /* تباعد أسطر جيد */
        }
        .filters-display span {
            display: inline-block; /* لجعل العناصر بجانب بعضها مع التحكم في المسافات */
            margin: 0 8px; /* مسافة بين الفلاتر */
            background-color: #f0f0f0; /* خلفية خفيفة لكل فلتر */
            padding: 3px 8px;
            border-radius: 4px; /* حواف مستديرة */
            border: 1px solid #ddd;
        }

        /* أنماط الجدول */
        table {
            width: 100%;
            border-collapse: collapse; /* دمج حدود الخلايا */
            margin: 20px 0; /* مسافة علوية وسفلية للجدول */
            font-size: 10px; /* حجم خط صغير لخلايا الجدول */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); /* ظل خفيف للجدول */
        }

        /* أنماط رؤوس الجدول */
        th {
            border: 1px solid #a0a0a0; /* حدود أغمق لرؤوس الجدول */
            padding: 8px 6px; /* حشوة محسنة */
            text-align: center; /* توسيط النص */
            vertical-align: middle; /* محاذاة رأسية للنص */
            background-color: #e9ecef; /* خلفية رمادية فاتحة */
            font-weight: bold;
            color: #495057; /* لون نص داكن لرؤوس الجدول */
            white-space: nowrap; /* منع التفاف النص في الرؤوس */
        }

        /* أنماط خلايا البيانات في الجدول */
        td {
            border: 1px solid #d0d0d0; /* حدود أفتح لخلايا البيانات */
            padding: 6px; /* حشوة أقل لخلايا البيانات */
            text-align: center; /* توسيط النص */
            vertical-align: middle;
            color: #343a40; /* لون نص داكن قليلاً */
        }

        /* تأثير صفوف متناوبة لزيادة القراءة */
        tbody tr:nth-child(even) {
            background-color: #f8f9fa; /* خلفية فاتحة للصفوف الزوجية */
        }
        tbody tr:hover {
            background-color: #e2e6ea; /* خلفية عند التحويم (لن يظهر في الطباعة ولكن مفيد للمعاينة) */
        }

        /* تذييل التقرير */
        .footer {
            text-align: center;
            margin-top: 30px; /* مسافة علوية كبيرة */
            font-size: 10px;
            color: #777; /* لون رمادي فاتح */
            border-top: 1px solid #eee; /* خط فاصل علوي */
            padding-top: 10px;
        }

        /* قسم أزرار التحكم (إخفائها عند الطباعة) */
        .no-print {
            text-align: center;
            margin-top: 25px;
            padding: 15px;
            background-color: #f2f2f2;
            border-top: 1px solid #e0e0e0;
            display: flex; /* لعرض الأزرار بجانب بعضها */
            justify-content: center; /* توسيط الأزرار */
            gap: 15px; /* مسافة بين الأزرار */
        }

        /* أنماط الأزرار */
        .print-button, .close-button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            transition: all 0.3s ease; /* انتقال سلس عند التحويم */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* ظل خفيف */
        }
        .print-button {
            background: linear-gradient(45deg, #28a745, #218838); /* تدرج لوني أخضر */
            color: white;
        }
        .print-button:hover {
            background: linear-gradient(45deg, #218838, #1e7e34);
            transform: translateY(-1px);
        }
        .close-button {
            background: linear-gradient(45deg, #dc3545, #c82333); /* تدرج لوني أحمر */
            color: white;
        }
        .close-button:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
            transform: translateY(-1px);
        }

        /* أنماط الطباعة: تطبيق تعديلات خاصة فقط عند الطباعة */
        @media print {
            .no-print {
                display: none; /* إخفاء أزرار التحكم عند الطباعة */
            }
            body {
                font-size: 9.5px; /* حجم خط أصغر قليلاً للطباعة النهائية */
            }
            table {
                font-size: 8.5px; /* حجم خط أصغر لبيانات الجدول */
            }
            th, td {
                padding: 4px; /* حشوة أصغر للخلايا */
            }
            .header {
                margin-bottom: 15px;
            }
            .title {
                font-size: 20px;
            }
            .subtitle {
                font-size: 13px;
            }
            .filters-display {
                font-size: 11px;
                margin-top: 10px;
            }
            .filters-display span {
                padding: 2px 6px; /* حشوة أصغر للفلاتر */
                border: 0.5px solid #ddd; /* حدود أرق */
            }
            .footer {
                font-size: 9px;
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">تقرير الموظفين</div>
            <div class="subtitle">قائمة مفصلة ببيانات الموظفين</div>
            <div class="filters-display">
                {{-- عرض الفلاتر المطبقة بشكل واضح --}}
                @if(!empty($filters['search']))
                    <span>البحث: "{{ $filters['search'] }}"</span>
                @endif
                @if(!empty($filters['unit_name']))
                    <span>الوحدة: {{ $filters['unit_name'] }}</span>
                @endif
                @if(!empty($filters['role']))
                    <span>الدور: {{ $filters['role'] }}</span>
                @endif
                @if(!empty($filters['is_active_display']))
                    <span>الحالة: {{ $filters['is_active_display'] }}</span>
                @endif
                @if(empty($filters['search']) && empty($filters['unit_name']) && empty($filters['role']) && empty($filters['is_active_display']))
                    <span>(جميع الفلاتر غير مطبقة)</span>
                @endif
                <span>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        {{-- عرض رسالة في حال عدم وجود بيانات --}}
        @if($employees->isEmpty())
            <div style="text-align: center; padding: 25px; border: 1px dashed #ccc; background-color: #fefefe; color: #777; border-radius: 8px;">
                لا توجد بيانات موظفين مطابقة للمعايير المحددة للطباعة.
            </div>
        @else
            {{-- جدول عرض بيانات الموظفين --}}
            <table>
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الرقم الوظيفي</th>
                        <th>البريد الإلكتروني</th>
                        <th>المسمى الوظيفي</th>
                        <th>الوحدة</th>
                        <th>الدور</th>
                        <th>نشط</th>
                        <th>متوسط التقييم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->employee_number }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->job_title ?? 'غير محدد' }}</td>
                            <td>{{ $employee->unit->name ?? 'غير محدد' }}</td>
                            <td>{{ $employee->role ?? 'غير محدد' }}</td>
                            <td>{{ $employee->is_active ? 'نعم' : 'لا' }}</td>
                            <td>{{ number_format($employee->average_rating ?? 0, 2) }}</td> {{-- عرض متوسط التقييم بكسرين عشريين --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- تذييل التقرير مع معلومات حقوق النشر --}}
        <div class="footer">
            &copy; {{ date('Y') }} نظام إدارة المهام. جميع الحقوق محفوظة.
        </div>
    </div>

    {{-- قسم الأزرار الذي يظهر فقط في معاينة المتصفح وليس عند الطباعة --}}
    <div class="no-print">
        <button onclick="window.print()" class="print-button">
            <i class="fas fa-print"></i> طباعة التقرير
        </button>
        <button onclick="window.close()" class="close-button">
            <i class="fas fa-times-circle"></i> إغلاق النافذة
        </button>
    </div>

    {{-- سكربت JavaScript لطباعة الصفحة تلقائيًا --}}
    <script>
        // طباعة الصفحة تلقائيًا بعد تحميلها
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 700); // تأخير بسيط لضمان تحميل وتطبيق كافة الأنماط
        };
    </script>
</body>
</html>
