{{-- resources/views/photo_reports/print_only.blade.php --}}
{{--
    هذا الملف مخصص حصريًا لطباعة تفاصيل تقرير مصور واحد.
    يحتوي على هيكل HTML وأنماط CSS مُحسّنة خصيصًا للطباعة،
    مع التركيز الشديد على احتواء المحتوى في صفحة A4 أفقية واحدة،
    وتكبير الصور قدر الإمكان ضمن هذا القيد، وتنظيم الخطوط بفعالية.
    🚀 تم حل مشكلة الصور الكبيرة بنظام تحكم احترافي متقدم
    💡 نظام تحكم ذكي في أحجام الصور يتكيف مع المحتوى
    ✅ تحسين الاستفادة من المساحة المتاحة بأقصى درجة
--}}

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة تقرير المهام المصور - {{ $record->report_title ?? 'تقرير غير معنون' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base Print Styles */
        @page {
            size: A4 landscape;
            margin: 8mm 10mm;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.3;
            color: #222;
            margin: 0;
            padding: 0;
            font-size: 10px;
            background: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Layout & Container */
        .container-print {
            width: 100%;
            max-width: 277mm; /* محسوب بدقة لـ A4 landscape مع الهوامش */
            margin: 0 auto;
            padding: 4mm;
            border: 1px solid #777;
            min-height: 194mm; /* ارتفاع مضبوط للصفحة */
            max-height: 194mm;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Header Section - مضغوط أكثر */
        .header-print {
            text-align: center;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0; /* منع تقلص الهيدر */
        }
        
        .header-print .logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }
        
        .header-print .text-content {
            flex-grow: 1;
            text-align: center;
        }
        
        .title-print { 
            font-size: 16px; 
            font-weight: bold; 
            margin: 0; 
            color: #333; 
        }
        
        .subtitle-print { 
            font-size: 11px; 
            margin: 2px 0; 
            color: #555; 
        }
        
        .print-date { 
            font-size: 8px; 
            color: #777; 
            margin-top: 2px; 
        }

        /* Information Section - مضغوط جداً */
        .info-section {
            flex-shrink: 0;
            margin-bottom: 6px;
        }
        
        .info-section p {
            margin-bottom: 2mm;
            font-size: 9px;
            color: #333;
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
        }
        
        .info-section strong {
            display: inline-block;
            width: 70px;
            color: #000;
            flex-shrink: 0;
        }
        
        .info-section span {
            flex-grow: 1;
        }

        /* Badge Styling */
        .badge-print {
            background-color: #f0f0f0 !important;
            color: #555 !important;
            border: 1px solid #eee !important;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: normal;
            display: inline-block;
        }

        /* Notes Section - مضغوط */
        .notes-section {
            flex-shrink: 0;
            margin-bottom: 6px;
        }
        
        .notes-section h4 {
            font-size: 10px;
            font-weight: bold;
            margin: 4px 0 2px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 2px;
            color: #333;
        }
        
        .notes-section p {
            font-size: 8px;
            color: #333;
            margin-bottom: 2px;
        }

        /* 🚀 نظام التحكم الاحترافي في الصور */
        .images-container {
            flex-grow: 1; /* يأخذ المساحة المتبقية */
            display: flex;
            flex-direction: column;
            min-height: 0; /* مهم للفليكس */
        }

        .image-pair-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4mm;
            flex-grow: 1;
            min-height: 0;
        }

        .image-pair-item {
            width: 48.5%;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .image-label {
            font-weight: bold;
            font-size: 8px;
            color: #555;
            margin-bottom: 2px;
            text-align: center;
            flex-shrink: 0;
        }

        /* 🎯 التحكم الذكي في حجم الصور */
        .image-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #eee;
            background-color: #f9f9f9;
            padding: 2px;
            overflow: hidden;
        }

        .image-pair-item img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }

        .caption-print {
            font-size: 7px;
            color: #666;
            text-align: center;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .placeholder-image-print {
            opacity: 0.5;
        }

        .text-center-print {
            text-align: center;
            font-size: 9px;
            color: #888;
            padding: 6px 0;
        }

        hr {
            border: none;
            border-top: 1px dashed #e9e9e9;
            margin: 4px 0;
            flex-shrink: 0;
        }

        /* Hide UI elements for print */
        .no-print { display: none !important; }

        /* 🎨 تحسينات الطباعة المتقدمة */
        @media print {
            html, body {
                width: 297mm;
                height: 210mm;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            
            @page {
                size: A4 landscape;
                margin: 8mm 10mm;
            }
            
            body {
                font-size: 9px;
                line-height: 1.2;
            }
            
            .container-print {
                width: 277mm;
                height: 194mm;
                border: 1px solid #555;
                padding: 3mm;
                box-shadow: none;
                display: flex;
                flex-direction: column;
            }
            
            .header-print .logo {
                width: 40px;
                height: 40px;
            }
            
            .title-print { font-size: 14px; }
            .subtitle-print { font-size: 10px; }
            .print-date { font-size: 7px; }
            
            .info-section p {
                font-size: 8px;
                margin-bottom: 1.5mm;
            }
            
            .info-section strong {
                width: 60px;
            }
            
            .notes-section h4 {
                font-size: 9px;
                margin: 3px 0 1px 0;
            }
            
            .notes-section p {
                font-size: 7px;
            }
            
            .badge-print {
                font-size: 6px;
                padding: 1px 3px;
            }
            
            /* 🎯 تحسينات الصور للطباعة */
            .images-container {
                flex-grow: 1;
                display: flex;
                flex-direction: column;
            }
            
            .image-pair-container {
                margin-bottom: 2mm;
                flex-grow: 1;
                display: flex;
                align-items: stretch;
            }
            
            .image-pair-item {
                width: 48.5%;
                display: flex;
                flex-direction: column;
            }
            
            .image-label {
                font-size: 7px;
                margin-bottom: 1px;
            }
            
            .image-wrapper {
                flex-grow: 1;
                min-height: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .caption-print {
                font-size: 6px;
                margin-top: 1px;
            }
            
            .text-center-print {
                font-size: 8px;
                padding: 4px 0;
            }
            
            hr {
                margin: 2px 0;
            }
        }

        /* 🚀 حساب ديناميكي لحجم الصور حسب عددها */
        @media print {
            /* لزوج واحد من الصور */
            .images-container[data-pairs="1"] .image-wrapper {
                height: 120mm;
            }
            
            /* لزوجين من الصور */
            .images-container[data-pairs="2"] .image-wrapper {
                height: 58mm;
            }
            
            /* لثلاثة أزواج أو أكثر */
            .images-container[data-pairs="3"] .image-wrapper,
            .images-container[data-pairs="4"] .image-wrapper,
            .images-container[data-pairs="5"] .image-wrapper {
                height: 38mm;
            }
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
                 onerror="this.onerror=null; this.src='https://placehold.co/45x45/CCCCCC/666666?text=شعار1';"
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
            <img src="{{ asset('images/another_logo.png') }}"
                 alt="شعار المؤسسة 2"
                 class="logo"
                 onerror="this.onerror=null; this.src='https://placehold.co/45x45/CCCCCC/666666?text=شعار2';"
                 title="إذا لم يظهر الشعار الثاني، تأكد من مساره في مجلد public/images">
        </div>

        <div class="info-section">
            <p><strong>عنوان التقرير:</strong> <span>{{ $record->report_title ?? 'غير متوفر' }}</span></p>
            <p><strong>التاريخ:</strong> <span>{{ \Carbon\Carbon::parse($record->date)->format('Y-m-d') }}</span></p>
            <p><strong>الموقع:</strong> <span>{{ $record->location ?? 'غير متوفر' }}</span></p>
            <p><strong>نوع الوحدة:</strong> <span>{{ $unitName ?? 'غير متوفر' }}</span></p>
            <p><strong>نوع المهمة:</strong> <span>{{ $record->task_type ?? 'غير محدد' }}</span></p>
            <p><strong>معرف المهمة:</strong> <span>{{ $record->task_id ?? 'غير محدد' }}</span></p>
            <p><strong>الحالة:</strong>
                <span class="badge-print">
                    {{ $record->status ?? 'غير متوفر' }}
                </span>
            </p>
            <p><strong>آخر تحديث:</strong> <span>{{ \Carbon\Carbon::parse($record->updated_at)->format('Y-m-d H:i') }}</span></p>
        </div>

        <hr>

        <div class="notes-section">
            <h4>الملاحظات:</h4>
            @if(!empty($record->notes))
                <p>{{ $record->notes }}</p>
            @else
                <p class="text-center-print">لا توجد ملاحظات لهذا التقرير.</p>
            @endif
        </div>

        <hr>

        {{-- 🚀 قسم عرض الصور المحسن --}}
        @if(isset($pairedImages) && count($pairedImages) > 0)
            <div class="images-container" data-pairs="{{ count($pairedImages) }}">
                @foreach($pairedImages as $pair)
                    <div class="image-pair-container">
                        {{-- الصورة بعد العمل (على اليسار) --}}
                        <div class="image-pair-item" dir="ltr">
                            <div class="image-label">صورة بعد التنفيذ:</div>
                            <div class="image-wrapper">
                                @if(isset($pair['after']['url']) && $pair['after']['exists'])
                                    <img src="{{ e($pair['after']['url']) }}" alt="صورة بعد التنفيذ" onerror="this.onerror=null;this.src='{{ asset('images/placeholder-image.png') }}';">
                                @else
                                    <img src="{{ asset('images/placeholder-image.png') }}" alt="لا توجد صورة بعد" class="placeholder-image-print">
                                @endif
                            </div>
                            @if(isset($pair['after']['caption']) && !empty($pair['after']['caption']))
                                <div class="caption-print">{{ e($pair['after']['caption']) }}</div>
                            @elseif(!isset($pair['after']['url']) || !$pair['after']['exists'])
                                <div class="caption-print">لا توجد صورة بعد</div>
                            @endif
                        </div>

                        {{-- الصورة قبل العمل (على اليمين) --}}
                        <div class="image-pair-item" dir="ltr">
                            <div class="image-label">صورة قبل التنفيذ:</div>
                            <div class="image-wrapper">
                                @if(isset($pair['before']['url']) && $pair['before']['exists'])
                                    <img src="{{ e($pair['before']['url']) }}" alt="صورة قبل التنفيذ" onerror="this.onerror=null;this.src='{{ asset('images/placeholder-image.png') }}';">
                                @else
                                    <img src="{{ asset('images/placeholder-image.png') }}" alt="لا توجد صورة قبل" class="placeholder-image-print">
                                @endif
                            </div>
                            @if(isset($pair['before']['caption']) && !empty($pair['before']['caption']))
                                <div class="caption-print">{{ e($pair['before']['caption']) }}</div>
                            @elseif(!isset($pair['before']['url']) || !$pair['before']['exists'])
                                <div class="caption-print">لا توجد صورة قبل</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="images-container">
                <p class="text-center-print">لا توجد صور قبل أو بعد التنفيذ مرفقة بهذا التقرير لعرضها.</p>
            </div>
        @endif

    </div>

    {{-- أزرار التحكم --}}
    <div class="no-print" style="text-align: center; margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
        <button onclick="window.print()" style="padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; background: #28a745; color: white; transition: background-color 0.3s ease;">طباعة التقرير</button>
        <button onclick="window.close()" style="padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; background: #dc3545; color: white; transition: background-color 0.3s ease;">إغلاق النافذة</button>
    </div>

    <script>
        // طباعة تلقائية مع تحسينات
        window.onload = function() {
            // إضافة تحسينات للصور قبل الطباعة
            const images = document.querySelectorAll('.image-pair-item img');
            let loadedImages = 0;
            
            function checkAllImagesLoaded() {
                loadedImages++;
                if (loadedImages >= images.length) {
                    setTimeout(function() {
                        window.print();
                    }, 800);
                }
            }
            
            images.forEach(function(img) {
                if (img.complete) {
                    checkAllImagesLoaded();
                } else {
                    img.onload = checkAllImagesLoaded;
                    img.onerror = checkAllImagesLoaded;
                }
            });
            
            // احتياطي للطباعة حتى لو لم تُحمل الصور
            setTimeout(function() {
                window.print();
            }, 3000);
        };
    </script>
</body>
</html>