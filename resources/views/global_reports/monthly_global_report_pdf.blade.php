<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>التقرير الشهري العالمي للأداء - {{ $monthName }} {{ $year }}</title>
    <style>
        /* Base Print Styles */
        @page {
            size: A4 landscape; /* تنسيق أفقي لزيادة المساحة العرضية */
            margin: 5mm 8mm; /* تقليل الهوامش لزيادة مساحة المحتوى */
        }
        body {
            /* استخدام خطوط تدعم العربية بشكل أفضل */
            font-family: 'DejaVuSans', 'Arial Unicode MS', 'Tahoma', sans-serif; /* 💡 تم التغيير لاستخدام DejaVuSans كخط أساسي */
            line-height: 1.4; /* تباعد أسطر ضيق لتقليل المساحة العمودية */
            color: #222;
            margin: 0;
            padding: 0;
            font-size: 10.5px; /* حجم خط أساسي أصغر قليلاً لاحتواء المحتوى */
            direction: rtl;
            text-align: right;
            background: white !important; /* لضمان خلفية بيضاء للطباعة */
            -webkit-print-color-adjust: exact !important; /* للحفاظ على الألوان كما هي */
            print-color-adjust: exact !important;
            /* إضافة خصائص تحسين النص */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Layout & Container */
        .container-print {
            width: 100%;
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 10mm;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 2px;
            color: #333;
        }
        .header h2 {
            font-size: 16px;
            margin-top: 0;
            color: #555;
        }
        .header img {
            max-width: 100px;
            height: auto;
            margin-bottom: 5px;
            /* إضافة object-fit للصور لتحسين العرض */
            object-fit: contain; 
        }

        /* Sections */
        .section {
            margin-bottom: 7mm;
            border: 1px solid #eee;
            padding: 5mm;
            border-radius: 4px;
            background-color: #fdfdfd;
        }
        .section h3 {
            font-size: 14px;
            color: #0056b3;
            margin-top: 0;
            margin-bottom: 4mm;
            padding-bottom: 2mm;
            border-bottom: 1px solid #eee;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
            font-size: 9.5px; /* خط أصغر للجداول */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 4px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }
        td {
            color: #444;
        }
        .table-center th, .table-center td {
            text-align: center;
        }

        /* Lists */
        ul {
            margin: 0 0 5mm 0;
            padding: 0;
            list-style: none; /* إزالة التنقيط الافتراضي */
        }
        ul li {
            margin-bottom: 2px;
            padding-right: 15px; /* مسافة بادئة للنص */
            position: relative;
        }
        ul.bullet-list li:before {
            content: '•'; /* استخدام نقطة بدلاً من الرمز الافتراضي */
            position: absolute;
            right: 0;
            color: #0056b3;
            font-weight: bold;
        }

        /* Percentages */
        .percentages-list {
            /* استخدام display: table بدلاً من flexbox للتوافق الأفضل مع DomPDF */
            display: table; 
            width: 100%;
            margin-top: 5px;
            border-spacing: 10px 0; /* لإنشاء فجوة بين الخلايا */
            table-layout: fixed; /* لتوزيع عرض الأعمدة بالتساوي */
        }
        .percentages-list li {
            display: table-cell; /* يجعل العناصر تعمل كخلايا جدول */
            background-color: #e9f7ef; /* لون خلفية فاتح */
            padding: 5px 10px;
            border-radius: 3px;
            border: 1px solid #d4edda;
            font-size: 10px;
            text-align: center;
            vertical-align: top; /* محاذاة العناصر للأعلى */
        }
        .percentages-list li strong {
            color: #28a745;
        }

        /* Gilber KPIs */
        .gilbert-triangle {
            text-align: center;
            margin-top: 10mm;
            margin-bottom: 10mm;
            background-color: #eef7ff;
            border: 1px solid #cce5ff;
            padding: 10mm;
            border-radius: 8px;
        }
        .gilbert-triangle h3 {
            color: #004085;
            font-size: 16px;
            margin-bottom: 8mm;
        }
        .gilbert-triangle p {
            font-size: 9.5px;
            color: #666;
            margin-bottom: 10mm;
        }
        .kpi-items-container { /* حاوية جديدة لمؤشرات الأداء */
            display: table; /* استخدام table بدلاً من inline-block لضمان التوزيع */
            width: 100%;
            table-layout: fixed;
        }
        .kpi-item {
            display: table-cell; /* كل مؤشر يصبح خلية في الجدول */
            vertical-align: top;
            padding: 5px;
            width: 33.33%; /* لتوزيع متساوٍ على ثلاث خلايا */
        }
        .kpi-item h4 {
            font-size: 11px;
            color: #366092;
            margin-bottom: 3px;
        }
        .kpi-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
        }
        .kpi-description {
            font-size: 8px;
            color: #777;
        }

        /* Photo Reports Section */
        .photo-report-section {
            margin-top: 10mm;
            page-break-before: auto; /* حاول عدم كسر الصفحة قبل هذا القسم إلا إذا لزم الأمر */
        }
        .photo-report-item {
            border: 1px solid #ddd;
            padding: 5mm;
            margin-bottom: 5mm;
            border-radius: 5px;
            background-color: #fff;
            page-break-inside: avoid; /* منع كسر العنصر الواحد */
        }
        .photo-report-item h4 {
            font-size: 12px;
            color: #333;
            margin-top: 0;
            margin-bottom: 3mm;
            border-bottom: 1px dashed #eee;
            padding-bottom: 2mm;
        }
        .image-gallery {
            text-align: center;
            margin-top: 3mm;
        }
        .image-gallery img {
            max-width: 48%; /* صورتان في الصف */
            height: auto;
            border: 1px solid #eee;
            margin: 1%; /* مسافة بين الصور */
            border-radius: 3px;
            object-fit: contain; /* التأكد من أن الصور تتناسب مع المساحة دون قص */
        }
        .no-images {
            color: #888;
            font-style: italic;
            text-align: center;
            font-size: 9px;
            margin-top: 5mm;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 15mm;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 5mm;
        }
        .page-break {
            page-break-before: always;
        }

        /* تضمين الخطوط */
        @font-face {
            font-family: 'DejaVuSans';
            src: url('{{ public_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'DejaVuSans';
            src: url('{{ public_path('fonts/DejaVuSans-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
    </style>
</head>
<body>
    <div class="container-print">
        <div class="header">
            @php
                $logoPath = public_path('assets/logo-placeholder.png'); // تأكد من المسار الصحيح
                $logoBase64 = '';
                if (file_exists($logoPath)) {
                    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                }
            @endphp

            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="شعار المؤسسة">
            @else
                <div>[شعار المؤسسة غير متوفر]</div>
            @endif
            <h1>تقرير الأداء الشهري الشامل</h1>
            <h2>شهر {{ $monthName }}، {{ $year }}</h2>
        </div>

        <div class="section">
            <h3>ملخص مهام المنشآت الصحية (الحمامات والمرافق)</h3>
            <table>
                <thead>
                    <tr>
                        <th>إجمالي المقاعد</th>
                        <th>إجمالي المرايا</th>
                        <th>إجمالي الخلاطات</th>
                        <th>إجمالي الأبواب</th>
                        <th>إجمالي الأحواض</th>
                        <th>إجمالي المراحيض</th>
                        <th>إجمالي المهام المكتملة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalSanitationSeats ?? 0 }}</td>
                        <td>{{ $totalSanitationMirrors ?? 0 }}</td>
                        <td>{{ $totalSanitationMixers ?? 0 }}</td>
                        <td>{{ $totalSanitationDoors ?? 0 }}</td>
                        <td>{{ $totalSanitationSinks ?? 0 }}</td>
                        <td>{{ $totalSanitationToilets ?? 0 }}</td>
                        <td>{{ $totalSanitationTasks ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>ملخص مهام النظافة العامة</h3>
            <table>
                <thead>
                    <tr>
                        <th>إجمالي الحصر</th>
                        <th>إجمالي الوسائد</th>
                        <th>إجمالي المراوح</th>
                        <th>إجمالي الشبابيك</th>
                        <th>إجمالي السجاد</th>
                        <th>إجمالي الأغطية</th>
                        <th>إجمالي الأسرة</th>
                        <th>إجمالي المستفيدين</th>
                        <th>إجمالي عربات المياه</th>
                        <th>إجمالي السجاد الممدد</th>
                        <th>إجمالي الحاويات الكبيرة</th>
                        <th>إجمالي الحاويات الصغيرة</th>
                        <th>إجمالي المهام المكتملة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $totalCleaningMats ?? 0 }}</td>
                        <td>{{ $totalCleaningPillows ?? 0 }}</td>
                        <td>{{ $totalCleaningFans ?? 0 }}</td>
                        <td>{{ $totalCleaningWindows ?? 0 }}</td>
                        <td>{{ $totalCleaningCarpets ?? 0 }}</td>
                        <td>{{ $totalCleaningBlankets ?? 0 }}</td>
                        <td>{{ $totalCleaningBeds ?? 0 }}</td>
                        <td>{{ $totalCleaningBeneficiaries ?? 0 }}</td>
                        <td>{{ $totalCleaningTrams ?? 0 }}</td>
                        <td>{{ $totalCleaningLaidCarpets ?? 0 }}</td>
                        <td>{{ $totalCleaningLargeContainers ?? 0 }}</td>
                        <td>{{ $totalCleaningSmallContainers ?? 0 }}</td>
                        <td>{{ $totalCleaningTasks ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3>ملخص الاستبيانات</h3>
            <p>عدد الاستبيانات المكتملة: <strong>{{ $totalSurveys ?? 0 }}</strong></p>

            <h4>توزيع المستفيدين حسب الفئة العمرية</h4>
            <ul class="bullet-list">
                <li><strong>أقل من 18:</strong> {{ $ageGroupDistribution['under_18'] ?? 0 }}</li>
                <li><strong>18-30:</strong> {{ $ageGroupDistribution['18_30'] ?? 0 }}</li>
                <li><strong>30-45:</strong> {{ $ageGroupDistribution['30_45'] ?? 0 }}</li>
                <li><strong>45-60:</strong> {{ $ageGroupDistribution['45_60'] ?? 0 }}</li>
                <li><strong>أكثر من 60:</strong> {{ $ageGroupDistribution['over_60'] ?? 0 }}</li>
            </ul>

            <h4>توزيع المستفيدين حسب الجنس</h4>
            <ul class="bullet-list">
                <li><strong>ذكور:</strong> {{ $genderDistribution['male'] ?? 0 }}</li>
                <li><strong>إناث:</strong> {{ $genderDistribution['female'] ?? 0 }}</li>
            </ul>

            <h4>توزيع مدة الزيارة</h4>
            <ul class="bullet-list">
                <li><strong>أقل من ساعة:</strong> {{ $visitTimeDistribution['less_1h'] ?? 0 }}</li>
                <li><strong>2-3 ساعات:</strong> {{ $visitTimeDistribution['2_3h'] ?? 0 }}</li>
                <li><strong>4-6 ساعات:</strong> {{ $visitTimeDistribution['4_6h'] ?? 0 }}</li>
                <li><strong>أكثر من 6 ساعات:</strong> {{ $visitTimeDistribution['over_6h'] ?? 0 }}</li>
            </ul>

            <h4>رضا المستفيدين العام</h4>
            <p>متوسط درجة الرضا: <strong>{{ $averageSatisfactionPercentage ?? 0 }}%</strong></p>
            <ul class="percentages-list">
                <li><strong>ممتاز:</strong> {{ $excellentPercentage ?? 0 }}%</li>
                <li><strong>جيد:</strong> {{ $goodPercentage ?? 0 }}%</li>
                <li><strong>مقبول:</strong> {{ $acceptablePercentage ?? 0 }}%</li>
                <li><strong>غير راض:</strong> {{ $dissatisfiedPercentage ?? 0 }}%</li>
            </ul>

            <h4>المشاكل التي واجهها المستفيدون</h4>
            @if(isset($problemsFaced) && count($problemsFaced) > 0)
                <ul class="bullet-list">
                    @foreach($problemsFaced as $problem)
                        <li>{{ $problem }}</li>
                    @endforeach
                </ul>
            @else
                <p>لا توجد مشاكل مبلغ عنها لهذا الشهر.</p>
            @endif

            <h4>مقترحات المستفيدين</h4>
            @if(isset($suggestions) && count($suggestions) > 0)
                <ul class="bullet-list">
                    @foreach($suggestions as $suggestion)
                        <li>{{ $suggestion }}</li>
                    @endforeach
                </ul>
            @else
                <p>لا توجد مقترحات لهذا الشهر.</p>
            @endif
        </div>

        <div class="section">
            <h3>ملخص المهام المكتملة لكل وحدة</h3>
            @if(isset($tasksPerUnitSummary) && count($tasksPerUnitSummary) > 0)
            <table>
                <thead>
                    <tr>
                        <th>اسم الوحدة</th>
                        <th>إجمالي المهام المكتملة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasksPerUnitSummary as $item)
                    <tr>
                        <td>{{ $item['unit_name'] ?? 'غير معروف' }}</td>
                        <td>{{ $item['total_tasks'] ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>لا توجد مهام مكتملة لكل وحدة لهذا الشهر.</p>
            @endif
        </div>

        <div class="gilbert-triangle">
            <h3>مؤشرات الأداء الرئيسية (K.P.I's)</h3>
            <p style="font-size: 10px; color: #555;">ملخص لأداء المؤسسة من حيث الكفاءة والفعالية والجودة.</p>
            <div class="kpi-items-container">
                <div class="kpi-item">
                    <h4>الكفاءة</h4>
                    <div class="kpi-value">{{ $gilbertData['efficiency'] ?? 0 }}%</div>
                    <div class="kpi-description">تعبر عن مدى جودة استخدام الموارد لإنجاز المهام.</div>
                </div>
                <div class="kpi-item">
                    <h4>الفعالية</h4>
                    <div class="kpi-value">{{ $gilbertData['effectiveness'] ?? 0 }}%</div>
                    <div class="kpi-description">تعبر عن مدى تحقيق الأهداف المرجوة ورضا المستفيدين.</div>
                </div>
                <div class="kpi-item">
                    <h4>الجودة</h4>
                    <div class="kpi-value">{{ $gilbertData['quality'] ?? 0 }}%</div>
                    <div class="kpi-description">تعبر عن مستوى الإتقان والامتياز في تقديم الخدمات.</div>
                </div>
            </div>
        </div>

        @if(isset($mainPhotoReports) && count($mainPhotoReports) > 0)
        <div class="photo-report-section">
            <div class="page-break"></div> <h3>تقارير المهام المصورة الرئيسية</h3>
            @foreach($mainPhotoReports as $report)
            <div class="photo-report-item">
                <h4>تقرير مهمة: {{ $report->report_title ?? 'لا يوجد عنوان' }} (ID: {{ $report->task_id ?? 'N/A' }})</h4>
                <p><strong>الوحدة:</strong> {{ $report->unit_type ?? 'غير معروف' }} | <strong>الموقع:</strong> {{ $report->location ?? 'غير معروف' }} | <strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($report->date ?? now())->format('Y-m-d') }} | <strong>الحالة:</strong> {{ $report->status ?? 'غير معروف' }}</p>
                
                <h5>الصور قبل التنفيذ ({{ $report->before_images_count ?? 0 }})</h5>
                @if(isset($report->before_images_urls) && count($report->before_images_urls) > 0)
                <div class="image-gallery">
                    @foreach($report->before_images_urls as $image)
                        @php
                            $imagePath = public_path($image['path_relative_to_public'] ?? ''); // تأكد من أن 'path_relative_to_public' هو المفتاح الصحيح
                            $imageBase64 = '';
                            if (file_exists($imagePath)) {
                                $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagePath));
                            }
                        @endphp
                        @if($imageBase64)
                            <img src="{{ $imageBase64 }}" alt="صورة قبل">
                        @else
                            @endif
                    @endforeach
                </div>
                @else
                <p class="no-images">لا توجد صور قبل التنفيذ لهذه المهمة.</p>
                @endif

                <h5>الصور بعد التنفيذ ({{ $report->after_images_count ?? 0 }})</h5>
                @if(isset($report->after_images_urls) && count($report->after_images_urls) > 0)
                <div class="image-gallery">
                    @foreach($report->after_images_urls as $image)
                        @php
                            $imagePath = public_path($image['path_relative_to_public'] ?? ''); // تأكد من أن 'path_relative_to_public' هو المفتاح الصحيح
                            $imageBase64 = '';
                            if (file_exists($imagePath)) {
                                $imageBase64 = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagePath));
                            }
                        @endphp
                        @if($imageBase64)
                            <img src="{{ $imageBase64 }}" alt="صورة بعد">
                        @else
                            @endif
                    @endforeach
                </div>
                @else
                <p class="no-images">لا توجد صور بعد التنفيذ لهذه المهمة.</p>
                @endif

                @if(isset($report->notes) && $report->notes)
                <p><strong>ملاحظات:</strong> {{ $report->notes }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <div class="footer">
            &copy; {{ date('Y') }} نظام إدارة المهام. جميع الحقوق محفوظة.
            <br>
            <span style="font-size: 7px;">تم إنشاء هذا التقرير بتاريخ {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</span>
        </div>
    </div>
</body>
</html>