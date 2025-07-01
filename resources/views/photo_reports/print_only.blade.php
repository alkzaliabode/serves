{{-- resources/views/photo_reports/print_only.blade.php --}}
{{--
    هذا الملف مخصص حصريًا لطباعة تفاصيل تقرير مصور واحد.
    يحتوي على هيكل HTML وأنماط CSS مُحسّنة خصيصًا للطباعة،
    مع التركيز الشديد على احتواء المحتوى في صفحة A4 أفقية واحدة،
    وتكبير الصور قدر الإمكان ضمن هذا القيد، وتنظيم الخطوط بفعالية.
    تم تصحيح خطأ TypeError: count() في قسم صور بعد التنفيذ.
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

        /* Image Gallery */
        .gallery-container-print {
            display: flex;
            flex-wrap: wrap;
            gap: 7mm; /* مسافة بين الصور (معدلة أكثر) */
            justify-content: start;
            margin-top: 12px; /* تقليل المسافة قبل المعرض */
        }
        .gallery-item-print {
            /* 3 صور في الصف: (100% / 3) - (gap * 2 / 3) */
            flex: 0 0 calc(33.333% - 4.66mm);
            max-width: calc(33.333% - 4.66mm);
            border: 1px solid #ddd;
            padding: 2mm; /* تقليل الهوامش الداخلية للصورة */
            box-sizing: border-box;
            background-color: #fdfdfd;
            page-break-inside: avoid;
            box-shadow: 0 0 2px rgba(0,0,0,0.05); /* ظل أخف */
        }
        .gallery-item-print img {
            width: 100%;
            height: 95px; /* ارتفاع الصور (متوازن لاحتوائها) */
            object-fit: cover; /* لملء المساحة والحفاظ على نسبة العرض إلى الارتفاع */
            display: block;
            margin-bottom: 3mm;
            border-bottom: 1px solid #f5f5f5;
        }
        .gallery-item-print .caption-print {
            font-size: 8px; /* حجم خط تعليق الصورة أصغر */
            text-align: center;
            color: #777;
            line-height: 1.1;
            padding-top: 1mm;
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
            .gallery-container-print {
                gap: 6mm; /* تقليل المسافة بين الصور في الطباعة النهائية */
                margin-top: 10px;
            }
            .gallery-item-print {
                flex: 0 0 calc(33.333% - 4mm);
                max-width: calc(33.333% - 4mm);
                padding: 1.5mm;
            }
            .gallery-item-print img {
                height: 85px; /* ارتفاع الصورة في الطباعة النهائية */
            }
            .gallery-item-print .caption-print { font-size: 7.5px; padding-top: 1mm;}
            .badge-print { font-size: 7.5px; }
            .text-center-print { font-size: 10px; padding: 8px 0;}
            hr { margin: 12px 0; }
        }
    </style>
</head>
<body>
    <div class="container-print" lang="ar" dir="rtl">
        <div class="header-print">
            <div class="title-print">تقرير المهام المصور</div>
            <div class="subtitle-print">تفاصيل التقرير والصور المرفقة</div>
            <div class="print-date">
                <span>تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</span>
            </div>
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

        <h4>صور قبل التنفيذ:</h4>
        @if(!empty($photo_report->before_images_urls) && count($photo_report->before_images_urls) > 0)
            <div class="gallery-container-print">
                @foreach($photo_report->before_images_urls as $image)
                    <div class="gallery-item-print">
                        <img src="{{ e($image['url']) }}" alt="صورة قبل التنفيذ" onerror="this.onerror=null;this.src='{{ asset('images/placeholder-image.png') }}';">
                        @if(isset($image['caption']) && !empty($image['caption']))
                            <div class="caption-print">{{ e($image['caption']) }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center-print">لا توجد صور قبل التنفيذ مرفقة بهذا التقرير.</p>
        @endif

        <h4>صور بعد التنفيذ:</h4>
        {{-- تم تصحيح الخطأ هنا: after_reports_urls إلى after_images_urls --}}
        @if(!empty($photo_report->after_images_urls) && count($photo_report->after_images_urls) > 0)
            <div class="gallery-container-print">
                @foreach($photo_report->after_images_urls as $image)
                    <div class="gallery-item-print">
                        <img src="{{ e($image['url']) }}" alt="صورة بعد التنفيذ" onerror="this.onerror=null;this.src='{{ asset('images/placeholder-image.png') }}';">
                        @if(isset($image['caption']) && !empty($image['caption']))
                            <div class="caption-print">{{ e($image['caption']) }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center-print">لا توجد صور بعد التنفيذ مرفقة بهذا التقرير.</p>
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