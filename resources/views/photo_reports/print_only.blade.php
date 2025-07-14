{{-- resources/views/photo_reports/print_only.blade.php --}}
{{--
    هذا الملف مخصص حصريًا لطباعة تفاصيل تقرير مصور واحد.
    يحتوي على هيكل HTML وأنماط CSS مُحسّنة خصيصًا للطباعة،
    مع التركيز الشديد على احتواء المحتوى في صفحة A4 أفقية واحدة،
    وتكبير الصور قدر الإمكان ضمن هذا القيد، وتنظيم الخطوط بفعالية.
    تم تصحيح خطأ TypeError: count() في قسم صور بعد التنفيذ.
    💡 تم إضافة شعارين في رأس التقرير وتنسيق المحتوى بينهما.
    ✅ تم التحديث: عرض صور "بعد التنفيذ" على اليسار وصور "قبل التنفيذ" على اليمين، كبيرة وكاملة.
--}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة تقرير المهام المصور - {{ $photo_report->report_title ?? 'تقرير غير معنون' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base Print Styles */
        @page {
            size: A4 landscape; /* تنسيق أفقي لزيادة المساحة العرضية */
            margin: 5mm 8mm; /* تقليل الهوامش لزيادة مساحة المحتوى */
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4; /* تباعد أسطر ضيق لتقليل المساحة العمودية */
            color: #222;
            margin: 0;
            padding: 0;
            font-size: 10.5px; /* حجم خط أساسي أصغر قليلاً لاحتواء المحتوى */
            background: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Layout & Container */
        .container-print {
            width: 100%;
            max-width: 290mm; /* عرض قصوى مناسب لـ A4 landscape مع هوامش 8mm */
            margin: 0 auto;
            padding: 5mm; /* هامش داخلي صغير للتقرير */
            box-sizing: border-box;
            border: 1px solid #777; /* إطار رفيع حول التقرير */
        }

        /* Header Section */
        .header-print {
            text-align: center;
            margin-bottom: 12px; /* تقليل المسافة بعد العنوان */
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
            display: flex; /* 💡 استخدام فليكس بوكس لتنظيم الشعار والعناوين */
            align-items: center; /* 💡 توسيط العناصر عمودياً */
            justify-content: space-between; /* 💡 دفع العناصر إلى الأطراف مع توسيط النص */
        }
        .header-print .logo {
            width: 60px; /* 💡 حجم الشعار */
            height: 60px; /* 💡 حجم الشعار */
            object-fit: contain; /* 💡 لضمان احتواء الصورة داخل أبعادها */
        }
        .header-print .text-content {
            flex-grow: 1; /* 💡 للسماح للنص بأخذ المساحة المتاحة والتوسط */
            text-align: center; /* 💡 توسيط النص */
        }
        .title-print { font-size: 18px; font-weight: bold; margin: 0; color: #333; }
        .subtitle-print { font-size: 13px; margin: 2px 0; color: #555; }
        .print-date { font-size: 10px; color: #777; margin-top: 5px; }

        /* Information Section */
        .info-section p {
            margin-bottom: 4px; /* مسافة أقل بين سطور المعلومات */
            font-size: 10.5px;
            color: #333;
            display: flex;
            align-items: baseline;
            flex-wrap: wrap; /* السماح للعناصر بالالتفاف إذا طالت */
        }
        .info-section strong {
            display: inline-block;
            width: 90px; /* تقليل عرض المفاتيح لتوفير مساحة */
            color: #000;
            flex-shrink: 0;
        }
        .info-section span {
            flex-grow: 1;
        }

        /* Badge Styling (for Status) */
        .badge-print {
            background-color: #f0f0f0 !important;
            color: #555 !important;
            border: 1px solid #eee !important;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: normal;
            display: inline-block;
        }

        /* Headings & Paragraphs */
        h4 {
            font-size: 13px; /* عناوين فرعية أصغر قليلاً */
            font-weight: bold;
            margin-top: 15px; /* تقليل المسافة قبل العناوين */
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            color: #333;
        }
        p {
            font-size: 10.5px; /* حجم خط الفقرات العامة */
            color: #333;
            margin-bottom: 6px;
        }

        /* Image Pairing Section - New Styles */
        .image-pair-container {
            display: flex; /* استخدام Flexbox لترتيب العناصر جنباً إلى جنب */
            justify-content: space-around; /* توزيع المساحة بالتساوي بين الصور */
            align-items: center; /* محاذاة الصور عمودياً في المنتصف */
            margin-bottom: 15mm; /* مسافة بين كل زوج من الصور والزوج التالي */
            page-break-inside: avoid; /* منع تقسيم زوج الصور عبر صفحتين */
            flex-wrap: wrap; /* السماح للصور بالانتقال إلى سطر جديد إذا كانت الشاشة صغيرة */
        }

        .image-pair-item {
            width: 48%; /* تحديد عرض كل صورة لتكون تقريباً نصف الصفحة مع بعض الهوامش */
            margin: 1%; /* هامش بسيط بين الصورتين */
            box-sizing: border-box; /* للتأكد من أن العرض يشمل الهوامش والحدود */
            text-align: center; /* لمحاذاة التسميات التوضيحية */
        }

        .image-pair-item img {
            max-width: 100%; /* التأكد من أن الصورة لا تتجاوز عرض حاويتها */
            height: auto; /* الحفاظ على نسبة العرض إلى الارتفاع */
            object-fit: contain; /* التأكد من ظهور الصورة كاملة داخل الإطار دون قص */
            border: 1px solid #eee; /* إضافة حدود خفيفة للصور */
            padding: 2mm; /* مساحة داخل الحدود */
            background-color: #f9f9f9; /* خلفية خفيفة */
        }

        .image-label {
            font-weight: bold;
            margin-top: 5mm; /* مسافة أعلى تسمية الصورة */
            font-size: 11pt;
            color: #555;
        }

        .text-center-print {
            text-align: center;
            font-size: 10.5px;
            color: #888;
            padding: 10px 0;
        }
        hr {
            border: none;
            border-top: 1px dashed #e9e9e9;
            margin: 15px 0; /* تقليل المسافة للخطوط الفاصلة */
        }

        /* Hide UI elements for print */
        .no-print { display: none !important; }

        /* Final Print Adjustments (Media Query) */
        /* هذه الأنماط مهمة جداً لضمان الاحتواء في الطباعة الفعلية */
        @media print {
            html, body {
                width: 297mm;
                height: 210mm;
                margin: 0;
                padding: 0;
                overflow: hidden; /* منع أي أشرطة تمرير */
            }
            @page {
                size: A4 landscape;
                margin: 5mm 8mm; /* تأكيد الهوامش المقللة لـ A4 landscape */
            }
            .container-print {
                width: 281mm; /* محاذاة أكثر دقة مع الهوامش */
                min-height: 195mm; /* ارتفاع أدنى لضمان ملء الصفحة بدون تجاوز */
                max-height: 198mm; /* تحديد أقصى ارتفاع لمنع تجاوز الصفحة */
                border: 1px solid #555;
                padding: 6mm; /* تعديل الهوامش الداخلية في الطباعة */
                box-shadow: none;
            }
            body { font-size: 10px; line-height: 1.3; } /* تصغير الخط أكثر للطباعة النهائية */
            .title-print { font-size: 17px; }
            .subtitle-print { font-size: 12px; }
            .info-section p, p { font-size: 10px; margin-bottom: 3px; }
            .info-section strong { width: 85px; }
            h4 { font-size: 12px; margin-top: 12px; margin-bottom: 6px; padding-bottom: 4px;}
            /* New print adjustments for image pairing */
            .image-pair-container {
                margin-bottom: 10mm; /* تقليل المسافة بين أزواج الصور في الطباعة */
            }
            .image-pair-item {
                width: 49%; /* زيادة عرض الصورة قليلاً في الطباعة */
                margin: 0.5%; /* تقليل الهامش بين الصورتين */
            }
            .image-pair-item img {
                padding: 1.5mm; /* تقليل البادينغ حول الصورة في الطباعة */
            }
            .image-label {
                font-size: 10pt; /* تصغير حجم تسمية الصورة في الطباعة */
                margin-top: 3mm;
            }
            .badge-print { font-size: 7.5px; }
            .text-center-print { font-size: 10px; padding: 8px 0;}
            hr { margin: 12px 0; }
        }
    </style>
</head>
<body>
    <div class="container-print" lang="ar" dir="rtl">
        <div class="header-print">
            {{-- الشعار الأول على اليمين --}}
            <img src="{{ asset('images/logo.png') }}"
                 alt="شعار المؤسسة 1"
                 class="logo"
                 onerror="this.onerror=null; this.src='https://placehold.co/60x60/CCCCCC/666666?text=شعار1';"
                 title="إذا لم يظهر الشعار الأول، تأكد من مساره في مجلد public/images">

            {{-- محتوى النص في المنتصف --}}
            <div class="text-content">
                <div class="title-print">تقرير المهام المصور</div>
                <div class="subtitle-print">تفاصيل التقرير والصور المرفقة</div>
                <div class="print-date">
                    <span>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            {{-- الشعار الثاني على اليسار --}}
            <img src="{{ asset('images/another_logo.png') }}" {{-- افترض أن لديك ملف صورة آخر هنا --}}
                 alt="شعار المؤسسة 2"
                 class="logo"
                 onerror="this.onerror=null; this.src='https://placehold.co/60x60/CCCCCC/666666?text=شعار2';"
                 title="إذا لم يظهر الشعار الثاني، تأكد من مساره في مجلد public/images">
        </div>

        <div class="info-section">
            <p><strong>عنوان التقرير:</strong> <span>{{ $photo_report->report_title ?? 'غير متوفر' }}</span></p>
            <p><strong>التاريخ:</strong> <span>{{ \Carbon\Carbon::parse($photo_report->date)->format('Y-m-d') }}</span></p>
            <p><strong>الموقع:</strong> <span>{{ $photo_report->location ?? 'غير متوفر' }}</span></p>
            <p><strong>نوع الوحدة:</strong> <span>{{ $photo_report->unit_type ?? 'غير متوفر' }}</span></p>
            <p><strong>نوع المهمة:</strong> <span>{{ $photo_report->task_type ?? 'غير محدد' }}</span></p>
            <p><strong>معرف المهمة:</strong> <span>{{ $photo_report->task_id ?? 'غير محدد' }}</span></p>
            <p><strong>الحالة:</strong>
                <span class="badge-print">
                    {{ $photo_report->status ?? 'غير متوفر' }}
                </span>
            </p>
            <p><strong>آخر تحديث:</strong> <span>{{ \Carbon\Carbon::parse($photo_report->updated_at)->format('Y-m-d H:i') }}</span></p>
        </div>

        <hr>

        <h4>الملاحظات:</h4>
        @if(!empty($photo_report->notes))
            <p>{{ $photo_report->notes }}</p>
        @else
            <p class="text-center-print">لا توجد ملاحظات لهذا التقرير.</p>
        @endif

        <hr>

        {{-- قسم عرض الصور قبل وبعد بتخطيط جديد --}}
        {{--
            ملاحظة هامة: هذا الجزء يفترض أن الكنترولر يمرر مصفوفة $pairedImages
            التي تحتوي على أزواج من الصور (قبل وبعد) لتسهيل عرضها جنباً إلى جنب.
            إذا لم يتم ذلك في الكنترولر، فستحتاج إلى معالجة قبل تمرير البيانات هنا
            أو تعديل هذا الجزء للتعامل مع مصفوفتي before_images_urls و after_images_urls بشكل منفصل
            ثم دمجها منطقياً هنا إذا كانت الفهارس متطابقة.
        --}}
        @php
            $maxImages = max(
                count($photo_report->before_images_urls ?? []),
                count($photo_report->after_images_urls ?? [])
            );
            $pairedImages = [];
            for ($i = 0; $i < $maxImages; $i++) {
                $pairedImages[] = [
                    'before' => ($photo_report->before_images_urls[$i] ?? null),
                    'after' => ($photo_report->after_images_urls[$i] ?? null),
                ];
            }
        @endphp

        @if(count($pairedImages) > 0)
            @foreach($pairedImages as $pair)
                <div class="image-pair-container">
                    {{-- الصورة بعد العمل (على اليسار) --}}
                    @if($pair['after'] && $pair['after']['exists'])
                        <div class="image-pair-item" dir="ltr">
                            <p class="image-label">صورة بعد التنفيذ:</p>
                            <img src="{{ e($pair['after']['url']) }}" alt="صورة بعد التنفيذ" onerror="this.onerror=null;this.src='{{ asset('images/placeholder-image.png') }}';">
                            @if(isset($pair['after']['caption']) && !empty($pair['after']['caption']))
                                <div class="caption-print">{{ e($pair['after']['caption']) }}</div>
                            @endif
                        </div>
                    @endif

                    {{-- الصورة قبل العمل (على اليمين) --}}
                    @if($pair['before'] && $pair['before']['exists'])
                        <div class="image-pair-item" dir="ltr">
                            <p class="image-label">صورة قبل التنفيذ:</p>
                            <img src="{{ e($pair['before']['url']) }}" alt="صورة قبل التنفيذ" onerror="this.onerror=null;this.src='{{ asset('images/placeholder-image.png') }}';">
                            @if(isset($pair['before']['caption']) && !empty($pair['before']['caption']))
                                <div class="caption-print">{{ e($pair['before']['caption']) }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p class="text-center-print">لا توجد صور قبل أو بعد التنفيذ مرفقة بهذا التقرير.</p>
        @endif

    </div>

    {{-- الأزرار الخاصة بالطباعة والإغلاق (تظهر فقط في المتصفح، تختفي عند الطباعة الفعلية) --}}
    <div class="no-print" style="text-align: center; margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
        <button onclick="window.print()" style="padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; background: #28a745; color: white; transition: background-color 0.3s ease;">طباعة التقرير</button>
        <button onclick="window.close()" style="padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; background: #dc3545; color: white; transition: background-color 0.3s ease;">إغلاق النافذة</button>
    </div>

    <script>
        // طباعة الصفحة تلقائيًا بعد تحميلها
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1500); // زيادة التأخير لضمان أقصى قدر من التحميل والتخطيط
        };
    </script>
</body>
</html>