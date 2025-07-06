<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الموقف اليومي - {{ \Carbon\Carbon::parse($dailyStatus->date)->format('Y-m-d') }}</title>
    <link rel="icon" href="{{ asset('path/to/your/favicon.ico') }}" type="image/x-icon">
    <style>
        /* أنماط الطباعة */
        @page {
            size: A4 portrait;
            margin: 0; /* لا هوامش للصفحة نفسها، الهوامش ستكون داخل الـ print-container */
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 11px;
            direction: rtl;
            text-align: right;
            -webkit-print-color-adjust: exact; /* لضمان طباعة الألوان والخلفيات */
            color-adjust: exact;
        }
        .print-container {
            /* أبعاد A4 هي 210mm عرض و 297mm ارتفاع.
               إذا كان الإطار 1.5px (تقريباً 0.4mm)، والهوامش 5mm من كل جانب.
               فالعرض الفعلي للمحتوى سيكون 210mm - (5mm * 2) = 200mm
               والارتفاع الفعلي للمحتوى سيكون 297mm - (5mm * 2) = 287mm
               لذا، الـ width و height هنا تمثل المساحة المتاحة للمحتوى داخل الإطار.
               الإطار نفسه سيتم إضافته حول هذه المساحة.
            */
            width: 200mm;
            height: 287mm;
            margin: 5mm auto; /* هوامش 5 مم من جميع الجوانب في وسط الصفحة المطبوعة */
            padding: 5mm; /* حشوة داخلية للحاوية */
            border: 1.5px solid #333; /* الإطار حول المحتوى القابل للطباعة */
            box-sizing: border-box;
            background-color: #fff;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* تعديل الارتفاع الأدنى ليتناسب مع الهوامش الداخلية والإطار */
            min-height: calc(287mm - (5mm * 2) - (1.5px * 2));
        }
        .content-wrapper {
            flex-grow: 1; /* للسماح للمحتوى بالنمو و دفع التوقيعات للأسفل */
        }
        .header-print {
            text-align: center;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 5px;
            border-bottom: 1px solid #bbb;
        }
        .header-print .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-left: 5px;
        }
        .header-print .text-content {
            flex-grow: 1;
            text-align: center;
        }
        .title-print {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }
        .subtitle-print {
            font-size: 13px;
            margin: 3px 0;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 10px;
            page-break-inside: avoid; /* منع تقسيم الجداول عبر الصفحات */
        }
        th, td {
            border: 1px solid #333;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
            color: #333;
        }
        th {
            background-color: #666;
            font-weight: bold;
            color: #fff;
            font-size: 11px;
        }
        td {
            background-color: #f8f8f8;
            color: #444;
            font-size: 10px;
        }
        .table-title {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-top: 8px;
            margin-bottom: 3px;
            border-bottom: 1px solid #666;
            padding-bottom: 3px;
            color: #000;
        }
        .two-column-tables {
            display: flex;
            justify-content: space-between;
            flex-wrap: nowrap;
            margin-bottom: 5px;
            align-items: flex-start;
        }
        .two-column-tables > div {
            width: 49.5%;
            box-sizing: border-box;
        }
        .two-column-tables table {
            margin: 0;
            width: 100%;
        }

        /* تنسيقات التوقيعات */
        .signatures-container {
            margin-top: 15px;
            overflow: hidden;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 12px;
            color: #333;
        }
        .signature-block {
            width: 48%;
            margin-top: 5px;
            padding: 3px;
            box-sizing: border-box;
            border-top: 1px dashed #666;
            padding-top: 5px;
        }
        .responsible-signature {
            text-align: right;
        }
        .organizer-signature {
            text-align: left;
        }
        .signature-line {
            margin-top: 5px;
            font-weight: bold;
        }
        .signature-line div {
            margin-bottom: 3px;
        }
        .department { text-align: center; margin-top: 8px; font-weight: bold; font-size: 12px; color: #333; }

        /* لضمان عدم ظهور هذه العناصر عند الطباعة، حتى لو تم فتحها بشكل مباشر */
        .no-print-standalone {
            display: none !important;
        }

        /* أنماط الطباعة الفعلية */
        @media print {
            .app-header, .app-sidebar, .app-footer, .app-content-header, .no-print-adminlte,
            .main-header, .main-sidebar, .main-footer, .content-header, .control-sidebar,
            .preloader, .wrapper > .content-wrapper, body > .wrapper > .content-wrapper .card-tools,
            .btn, .card-tools, .card-header .card-tools {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 0; /* لا هوامش للصفحة نفسها */
            }

            body {
                font-size: 10px;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                margin: 0;
                padding: 0;
                width: 100%;
                min-width: 0 !important;
            }

            .wrapper {
                width: 100% !important;
                margin-left: 0 !important;
                padding-left: 0 !important;
            }

            .content-wrapper, .main-panel, .content-wrapper .container-fluid {
                padding: 0 !important;
                margin-left: 0 !important;
                min-height: 0 !important;
                width: 100% !important;
                float: none !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                margin-bottom: 0 !important;
            }

            .card-header, .card-body {
                padding: 0 !important;
            }

            .print-container {
                border: 1.5px solid #333 !important; /* الإطار حول المحتوى */
                width: 200mm !important; /* عرض ثابت للمحتوى */
                height: 287mm !important; /* ارتفاع ثابت للمحتوى */
                margin: 5mm auto !important; /* هوامش حول الحاوية المطبوعة */
                padding: 5mm !important; /* حشوة داخلية للحاوية */
                box-sizing: border-box !important;
                box-shadow: none !important;
            }
            table {
                font-size: 9px;
                page-break-inside: avoid;
            }
            th, td {
                padding: 2px;
                /* فرض طباعة الخلفيات والألوان */
                background-color: #666 !important; /* لون خلفية رأس الجدول */
                color: #fff !important; /* لون نص رأس الجدول */
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            td {
                background-color: #f8f8f8 !important; /* لون خلفية خلايا الجدول */
                color: #444 !important; /* لون نص خلايا الجدول */
            }

            .header-print { margin-bottom: 8px; border-bottom: 1px solid #bbb !important;}
            .title-print { font-size: 16px; text-shadow: none !important; }
            .subtitle-print { font-size: 12px; text-shadow: none !important; }
            .table-title { font-size: 13px; margin-top: 5px; border-bottom: 1px solid #666 !important; text-shadow: none !important; padding-bottom: 2px;}
            .two-column-tables {
                flex-wrap: nowrap;
                align-items: flex-start;
                margin-bottom: 5px;
            }
            .two-column-tables > div {
                width: 49.5%;
            }
            .signatures-container { font-size: 11px; margin-top: 10px; }
            .signature-block { width: 48%; border-top: 1px dashed #666 !important; padding-top: 5px; }
            .signature-line { margin-top: 3px; }
            .signature-line div { margin-bottom: 2px; }
        }
    </style>
</head>
<body>
    <div class="print-container" lang="ar" dir="rtl">
        <div class="content-wrapper">
            <div class="header-print">
                <img src="{{ asset('images/logo.png') }}"
                        alt="شعار المدينة"
                        class="logo"
                        onerror="this.onerror=null; this.src='https://placehold.co/60x60/CCCCCC/666666?text=شعار';"
                        title="إذا لم يظهر الشعار، تأكد من مسار الصورة في مجلد public/images">
                <div class="text-content">
                    <div class="title-print">الموقف اليومي للمنتسبين</div>
                    <div class="subtitle-print">قسم مدينة الإمام الحسين (ع) للزائرين</div>
                    <div class="subtitle-print">الموقف الخاص بالشعبة الخدمية</div>
                </div>
            </div>

            <table>
                <tr>
                    <td colspan="2">اليوم: <strong>{{ $dailyStatus->day_name }}</strong></td>
                    <td colspan="2">التاريخ: <strong>{{ $dailyStatus->hijri_date }} ({{ \Carbon\Carbon::parse($dailyStatus->date)->format('d/m/Y') }})</strong></td>
                </tr>
            </table>

            {{-- حاوية للجداول ذات العمودين: الإجازات الدورية والسنوية --}}
            <div class="two-column-tables">
                @if (!empty($dailyStatus->periodic_leaves) && count($dailyStatus->periodic_leaves) > 0)
                <div>
                    <div class="table-title">الإجازات الدورية</div>
                    <table>
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->periodic_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if (!empty($dailyStatus->annual_leaves) && count($dailyStatus->annual_leaves) > 0)
                <div>
                    <div class="table-title">الإجازات السنوية</div>
                    <table>
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->annual_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div class="two-column-tables"> {{-- حاوية جديدة لإجازات الأعياد واستراحة الخفر --}}
                @if (!empty($dailyStatus->eid_leaves) && count($dailyStatus->eid_leaves) > 0)
                <div>
                    <div class="table-title">إجازات الأعياد</div>
                    <table>
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>نوع العيد</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->eid_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $eidType = $leave['eid_type'] ?? '';
                                        echo match ($eidType) {
                                            'eid_alfitr' => 'عيد الفطر',
                                            'eid_aladha' => 'عيد الأضحى',
                                            'eid_algahdir' => 'عيد الغدير',
                                            default => $eidType
                                        };
                                    @endphp
                                </td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if (!empty($dailyStatus->guard_rest) && count($dailyStatus->guard_rest) > 0)
                <div>
                    <div class="table-title">استراحة خفر</div>
                    <table>
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->guard_rest as $index => $rest)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rest['employee_name'] ?? '' }}</td>
                                <td>{{ $rest['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- *** هنا التعديل: وضع إجازة الوفاة وبدون راتب في حاوية two-column-tables *** --}}
            <div class="two-column-tables">
                @if (!empty($dailyStatus->bereavement_leaves) && count($dailyStatus->bereavement_leaves) > 0)
                <div>
                    <div class="table-title">إجازة الوفاة</div>
                    <table>
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->bereavement_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if (!empty($dailyStatus->unpaid_leaves) && count($dailyStatus->unpaid_leaves) > 0)
                <div>
                    <div class="table-title">إجازة بدون راتب</div>
                    <table>
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->unpaid_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            {{-- نهاية التعديل --}}

            {{-- ** إضافة جدول الإجازات المرضية هنا (يبقى بجدول واحد لأنه قد يكون أطول) ** --}}
            @if (!empty($dailyStatus->sick_leaves) && count($dailyStatus->sick_leaves) > 0)
            <div class="table-title">الإجازات المرضية</div>
            <table>
                <thead>
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>الرقم الوظيفي</th>
                        <th>مدة الإجازة</th> {{-- تم تغيير العنوان --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStatus->sick_leaves as $index => $leave)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $leave['employee_name'] ?? '' }}</td>
                        <td>{{ $leave['employee_number'] ?? '' }}</td>
                        <td>
                            @php
                                $displayDuration = 'بيانات غير متوفرة';
                                if (isset($leave['start_date']) && isset($leave['total_days'])) {
                                    $startDate = \Carbon\Carbon::parse($leave['start_date']);
                                    $currentDate = \Carbon\Carbon::parse($dailyStatus->date);
                                    $totalDays = $leave['total_days'];
                                    $dayOfLeave = $startDate->diffInDays($currentDate) + 1; // +1 لأن اليوم الأول هو 1

                                    if ($dayOfLeave > $totalDays) {
                                        $displayDuration = 'انتهت الإجازة';
                                    } else {
                                        $displayDuration = "اليوم $dayOfLeave من $totalDays يوم";
                                    }
                                } elseif (isset($leave['from_date']) && isset($leave['to_date'])) {
                                    // Fallback for old data if it still exists
                                    $displayDuration = \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') . ' - ' . \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d');
                                }
                                echo $displayDuration;
                            @endphp
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            {{-- نهاية إضافة جدول الإجازات المرضية --}}


            {{-- جدول جديد للاستخدامات المخصصة (جدول واحد، لا يوجد ما يقاسمه) --}}
            @if (!empty($dailyStatus->custom_usages) && count($dailyStatus->custom_usages) > 0)
            <div class="table-title">الاستخدام</div>
            <table>
                <thead>
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>الرقم الوظيفي</th>
                        <th>تفاصيل الاستخدام</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStatus->custom_usages as $index => $usage)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $usage['employee_name'] ?? '' }}</td>
                        <td>{{ $usage['employee_number'] ?? '' }}</td>
                        <td>{{ $usage['usage_details'] ?? '&mdash;' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            {{-- ** الجداول التالية ستبقى منفردة في صفوفها لأنها لا تندرج تحت تصنيف "عمودين متساويين" منطقيًا ** --}}

            @if (!empty($dailyStatus->temporary_leaves) && count($dailyStatus->temporary_leaves) > 0)
            <div class="table-title">الإجازات الزمنية</div>
            <table>
                <thead>
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>الرقم الوظيفي</th>
                        <th>الوقت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStatus->temporary_leaves as $index => $leave)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $leave['employee_name'] ?? '' }}</td>
                        <td>{{ $leave['employee_number'] ?? '' }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($leave['from_time'])->format('H:i') ?? '' }} -
                            {{ \Carbon\Carbon::parse($leave['to_time'])->format('H:i') ?? '' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            @if (!empty($dailyStatus->absences) && count($dailyStatus->absences) > 0)
            <div class="table-title">الغياب</div>
            <table>
                <thead>
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>الرقم الوظيفي</th>
                        <th>مدة الغياب</th> {{-- تم تغيير العنوان --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStatus->absences as $index => $absence)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $absence['employee_name'] ?? '' }}</td>
                        <td>{{ $absence['employee_number'] ?? '' }}</td>
                        <td>
                            @php
                                $displayDuration = 'بيانات غير متوفرة';
                                if (isset($absence['start_date']) && isset($absence['total_days'])) {
                                    $startDate = \Carbon\Carbon::parse($absence['start_date']);
                                    $currentDate = \Carbon\Carbon::parse($dailyStatus->date);
                                    $totalDays = $absence['total_days'];
                                    $dayOfAbsence = $startDate->diffInDays($currentDate) + 1;

                                    if ($dayOfAbsence > $totalDays) {
                                        $displayDuration = 'انتهى الغياب';
                                    } else {
                                        $displayDuration = "اليوم $dayOfAbsence من $totalDays يوم";
                                    }
                                } elseif (isset($absence['from_date']) && isset($absence['to_date'])) {
                                    // Fallback for old data if it still exists
                                    $displayDuration = \Carbon\Carbon::parse($absence['from_date'])->format('Y-m-d') . ' - ' . \Carbon\Carbon::parse($absence['to_date'])->format('Y-m-d');
                                }
                                echo $displayDuration;
                            @endphp
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            @if (!empty($dailyStatus->long_leaves) && count($dailyStatus->long_leaves) > 0)
            <div class="table-title">الإجازات الطويلة</div>
            <table>
                <thead>
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>الرقم الوظيفي</th>
                        <th>مدة الإجازة</th> {{-- تم تغيير العنوان --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStatus->long_leaves as $index => $leave)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $leave['employee_name'] ?? '' }}</td>
                        <td>{{ $leave['employee_number'] ?? '' }}</td>
                        <td>
                            @php
                                $displayDuration = 'بيانات غير متوفرة';
                                if (isset($leave['start_date']) && isset($leave['total_days'])) {
                                    $startDate = \Carbon\Carbon::parse($leave['start_date']);
                                    $currentDate = \Carbon\Carbon::parse($dailyStatus->date);
                                    $totalDays = $leave['total_days'];
                                    $dayOfLeave = $startDate->diffInDays($currentDate) + 1;

                                    if ($dayOfLeave > $totalDays) {
                                        $displayDuration = 'انتهت الإجازة';
                                    } else {
                                        $displayDuration = "اليوم $dayOfLeave من $totalDays يوم";
                                    }
                                } elseif (isset($leave['from_date']) && isset($leave['to_date'])) {
                                    // Fallback for old data if it still exists
                                    $displayDuration = \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') . ' - ' . \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d');
                                }
                                echo $displayDuration;
                            @endphp
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <table>
                @php
                    $totalRequired = $dailyStatus->total_required ?? 86;
                    // Use a try-catch block to safely access Employee model, as it might not be available
                    try {
                        $totalEmployees = \App\Models\Employee::where('is_active', 1)->count();
                    } catch (Throwable $e) {
                        $totalEmployees = 0; // Fallback if model is not accessible
                    }
                    $shortage = $totalRequired - $totalEmployees;

                    $paidLeavesCount = count($dailyStatus->annual_leaves ?? [])
                                        + count($dailyStatus->periodic_leaves ?? [])
                                        + count($dailyStatus->sick_leaves ?? [])
                                        + count($dailyStatus->bereavement_leaves ?? []);

                    $eidLeavesCount = 0;
                    foreach ($dailyStatus->eid_leaves ?? [] as $eidLeave) {
                        if (isset($eidLeave['employee_id'])) {
                            $eidLeavesCount++;
                        }
                    }
                    $paidLeavesCount += $eidLeavesCount;

                    $unpaidLeavesCount = count($dailyStatus->unpaid_leaves ?? []);
                    $absencesCount = count($dailyStatus->absences ?? []);
                    $temporaryLeavesCount = count($dailyStatus->temporary_leaves ?? []);
                    $guardRestCount = count($dailyStatus->guard_rest ?? []);
                    $customUsagesCount = count($dailyStatus->custom_usages ?? []);

                    // حساب الحضور الفعلي
                    // العدد الإجمالي للموظفين ناقص كل من هو في إجازة (بأنواعها) أو غياب أو استراحة خفر أو استخدام مخصص
                    $actualAttendance = $totalEmployees - (
                        count($dailyStatus->periodic_leaves ?? []) +
                        count($dailyStatus->annual_leaves ?? []) +
                        count($dailyStatus->eid_leaves ?? []) +
                        count($dailyStatus->sick_leaves ?? []) +
                        count($dailyStatus->bereavement_leaves ?? []) +
                        count($dailyStatus->unpaid_leaves ?? []) +
                        count($dailyStatus->absences ?? []) +
                        count($dailyStatus->temporary_leaves ?? []) +
                        count($dailyStatus->guard_rest ?? []) +
                        count($dailyStatus->custom_usages ?? [])
                    );
                @endphp
                <tr>
                    <th>الملاك</th>
                    <th>الموجود الحالي</th>
                    <th>النقص</th>
                    <th>الحضور الفعلي</th>
                    <th>إجازات براتب</th>
                    <th>إجازات بدون راتب</th>
                    <th>الغياب</th>
                    <th>استراحة خفر</th>
                    <th>إجازات زمنية</th>
                    <th>إجازة وفاة</th>
                    <th>الاستخدام</th>
                    <th>تعيين</th>
                    <th>نقل</th>
                    <th>فصل</th>
                </tr>
                <tr>
                    <td><strong>{{ $totalRequired }}</strong></td>
                    <td><strong>{{ $totalEmployees }}</strong></td>
                    <td><strong>{{ $shortage }}</strong></td>
                    <td><strong>{{ $actualAttendance }}</strong></td>
                    <td>{{ $paidLeavesCount }}</td>
                    <td>{{ $unpaidLeavesCount }}</td>
                    <td>{{ $absencesCount }}</td>
                    <td>{{ $guardRestCount }}</td>
                    <td>{{ $temporaryLeavesCount }}</td>
                    <td>{{ count($dailyStatus->bereavement_leaves ?? []) }}</td>
                    <td>{{ $customUsagesCount }}</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            </table>
        </div>

        <div class="signatures-container">
            <div class="signature-block responsible-signature">
                <div>مسؤول شعبة الخدمية</div>
                <div class="signature-line">
                    <div>التوقيع: ........................</div>
                    <div>التاريخ: {{ \Carbon\Carbon::parse($dailyStatus->date)->addDay()->format('d/m/Y') }}</div>
                </div>
            </div>

            @if (!empty($dailyStatus->organizer_employee_name))
            <div class="signature-block organizer-signature">
                <div>منظم الموقف: <strong>{{ $dailyStatus->organizer_employee_name }}</strong></div>
                <div class="signature-line">
                    <div>التوقيع: ........................</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
