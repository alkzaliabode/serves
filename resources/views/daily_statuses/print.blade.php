@extends('layouts.adminlte') {{-- تعديل ليرث تخطيط AdminLTE الجديد --}}

@section('title', 'الموقف اليومي - ' . \Carbon\Carbon::parse($dailyStatus->date)->format('Y-m-d')) {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', 'الموقف اليومي') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('daily-statuses.index') }}">الموقف اليومي</a></li>
    <li class="breadcrumb-item active">عرض الموقف</li>
@endsection

@section('styles')
    <style>
        /* أنماط الطباعة */
        @page { size: A4; margin: 10mm; }
        body { font-family: 'Arial', sans-serif; line-height: 1.4; color: #000; margin: 0; padding: 0; font-size: 13px; } /* تقليل ارتفاع السطر وحجم الخط الأساسي */
        .container-print { /* تم تغيير اسم الكلاس لتجنب التعارض مع Bootstrap container */
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 5mm;
            border: 1px solid #ccc; /* <--- إضافة إطار كامل للصفحة هنا */
            box-sizing: border-box; /* لضمان عدم زيادة الحجم الكلي مع الحدود */
        }
        .header-print { /* تم تغيير اسم الكلاس */
            text-align: center;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .header-print .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-left: 10px; /* مسافة بين الشعار والنص */
        }
        .header-print .text-content {
            flex-grow: 1;
            text-align: center;
        }
        .title-print { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle-print { font-size: 14px; margin: 2px 0; color: #555; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #e6e6e6;
            font-weight: bold;
        }
        .table-title {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin-top: 12px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            color: #333;
        }
        .two-column-tables {
            display: flex;
            justify-content: space-between;
            flex-wrap: nowrap; /* Prevents wrapping on screen too if not enough width, crucial for equal columns */
            margin-bottom: 10px;
        }
        .two-column-tables > div {
            width: 49%; /* تقريبا نصف العرض لكل جدول */
            box-sizing: border-box; /* لضمان عدم تجاوز العرض المحدد */
        }
        .two-column-tables table {
            margin: 0;
            width: 100%; /* Ensure tables within columns take full width of their parent div */
        }

        /* ** تنسيقات التوقيعات الجديدة (مسؤول يمين، منظم يسار) ** */
        .signatures-container {
            margin-top: 25px;
            overflow: hidden; /* Clearfix for floats */
            width: 100%; /* تأكد أنها تأخذ العرض الكامل */
            display: flex; /* استخدام Flexbox لتوزيع العناصر */
            justify-content: space-between; /* توزيع العناصر على الأطراف */
            align-items: flex-end; /* محاذاة العناصر إلى الأسفل */
        }
        .signature-block {
            width: 48%; /* ضبط العرض لكل كتلة توقيع */
            margin-top: 10px;
            font-size: 12px;
            padding: 5px; /* إضافة حشوة حول الكتلة */
            box-sizing: border-box;
            /* Flexbox items don't need float, justify-content handles positioning */
        }
        .responsible-signature {
            text-align: right; /* مسؤول شعبة الخدمية في اليمين */
        }
        .organizer-signature {
            text-align: left; /* منظم الموقف في اليسار */
        }
        .signature-line {
            margin-top: 10px; /* مسافة بين النص وسطر التوقيع */
        }

        .department { text-align: center; margin-top: 10px; font-weight: bold; }

        /* طباعة CSS */
        @media print {
            /* إخفاء عناصر AdminLTE عند الطباعة */
            .app-header,
            .app-sidebar,
            .app-footer,
            .app-content-header,
            .no-print-adminlte,
            .main-header, /* AdminLTE 3 header */
            .main-sidebar, /* AdminLTE 3 sidebar */
            .main-footer, /* AdminLTE 3 footer */
            .content-header, /* AdminLTE 3 page title area */
            .control-sidebar, /* AdminLTE 3 control sidebar */
            .preloader, /* AdminLTE 3 preloader */
            .wrapper > .content-wrapper, /* Target the main content wrapper */
            body > .wrapper > .content-wrapper .card-tools, /* Hide card tools within content wrapper */
            .btn, /* Hide all buttons for a cleaner print */
            .card-tools, /* Hide card collapse/remove buttons */
            .card-header .card-tools { /* Specific for card header tools */
                display: none !important;
            }

            /* إظهار محتوى الطباعة فقط وتنسيقه */
            body {
                font-size: 12px;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                margin: 0; /* Remove body margin */
                padding: 0; /* Remove body padding */
                width: 100%; /* Ensure body takes full width */
                min-width: 0 !important; /* Override any min-width */
            }

            .wrapper { /* Ensure the main wrapper doesn't interfere */
                width: 100% !important;
                margin-left: 0 !important;
                padding-left: 0 !important;
            }

            .content-wrapper, .main-panel, .content-wrapper .container-fluid {
                padding: 0 !important;
                margin-left: 0 !important;
                min-height: 0 !important; /* Important for preventing extra space */
                width: 100% !important;
                float: none !important; /* Clear any floats */
            }

            .container-fluid {
                padding: 0 !important;
            }

            .card {
                border: none !important; /* Remove card borders */
                box-shadow: none !important; /* Remove card shadows */
                margin-bottom: 0 !important; /* Remove margin between cards */
            }

            .card-header, .card-body {
                padding: 0 !important; /* Remove card padding */
            }

            .container-print {
                border: 1px solid #000 !important; /* Ensure the frame is always visible in print */
                width: 100% !important;
                max-width: 100% !important; /* Use 100% of available print width */
                padding: 5mm !important; /* Keep original padding */
                margin: 0 auto !important; /* Center the content */
                box-sizing: border-box !important;
            }
            table { font-size: 11px; page-break-inside: avoid; } /* منع تقسيم الجداول عبر الصفحات */
            th, td { padding: 3px; }
            .header-print { margin-bottom: 10px; }
            .title-print { font-size: 16px; }
            .subtitle-print { font-size: 12px; }
            .table-title { font-size: 13px; margin-top: 8px; }
            .two-column-tables {
                flex-wrap: nowrap; /* Ensure they stay in one line even if content is long */
                align-items: flex-start; /* Align tables at the top */
            }
            .two-column-tables > div {
                width: 49.5%; /* Slightly increased to account for small gaps, adjust if needed */
            }
            .signature-block { width: 48%; }
        }
    </style>
@endsection

@section('content')
    <div class="card card-primary card-outline"> {{-- استخدام بطاقة AdminLTE --}}
        <div class="card-header">
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                    <i data-lte-icon="plus" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="minus" class="bi bi-dash-lg" style="display: none;"></i>
                </button>
                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="container-print" lang="ar" dir="rtl">

                <div class="header-print">
                    <img src="{{ asset('images/logo.png') }}"
                            alt="شعار المدينة"
                            class="logo"
                            onerror="this.onerror=null; this.src='https://placehold.co/60x60/FF0000/FFFFFF?text=خطأ+شعار';"
                            title="إذا لم يظهر الشعار، تأكد من مسار الصورة في مجلد public/images">
                    <div class="text-content">
                        <div class="title-print">الموقف اليومي للموظفين</div>
                        <div class="subtitle-print">قسم مدينة الإمام الحسين (ع) للزائرين</div>
                        <div class="subtitle-print">الموقف الخاص بالشعبة الخدمية</div>
                    </div>
                </div>

                <table>
                    <tr>
                        <td colspan="2">اليوم: {{ $dailyStatus->day_name }}</td>
                        <td colspan="2">التاريخ: {{ $dailyStatus->hijri_date }} ({{ \Carbon\Carbon::parse($dailyStatus->date)->format('d/m/Y') }})</td>
                    </tr>
                </table>

                {{-- حاوية للجداول ذات العمودين: الإجازات الدورية والسنوية --}}
                <div class="two-column-tables">
                    @if (!empty($dailyStatus->periodic_leaves))
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

                    @if (!empty($dailyStatus->annual_leaves))
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
                    @if (!empty($dailyStatus->eid_leaves))
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

                    {{-- استراحة خفر (الآن بدون حقل الاستخدام المدمج) --}}
                    @if (!empty($dailyStatus->guard_rest))
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
                {{-- نهاية حاوية الجداول ذات العمودين الجديدة --}}

                {{-- جدول جديد للاستخدامات المخصصة (جدول واحد، لا يوجد ما يقاسمه) --}}
                @if (!empty($dailyStatus->custom_usages))
                <div class="table-title">الاستخدام </div>
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

                @if (!empty($dailyStatus->temporary_leaves))
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

                @if (!empty($dailyStatus->bereavement_leaves))
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
                @endif

                @if (!empty($dailyStatus->unpaid_leaves))
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
                @endif

                @if (!empty($dailyStatus->absences))
                <div class="table-title">الغياب</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>من تاريخ</th>
                            <th>إلى تاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyStatus->absences as $index => $absence)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $absence['employee_name'] ?? '' }}</td>
                            <td>{{ $absence['employee_number'] ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($absence['from_date'])->format('Y-m-d') ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($absence['to_date'])->format('Y-m-d') ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if (!empty($dailyStatus->long_leaves))
                <div class="table-title">الإجازات الطويلة</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>من تاريخ</th>
                            <th>إلى تاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyStatus->long_leaves as $index => $leave)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $leave['employee_name'] ?? '' }}</td>
                            <td>{{ $leave['employee_number'] ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d') ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                @if (!empty($dailyStatus->sick_leaves))
                <div class="table-title">الإجازات المرضية</div>
                <table>
                    <thead>
                        <tr>
                            <th>م</th>
                            <th>الاسم</th>
                            <th>الرقم الوظيفي</th>
                            <th>من تاريخ</th>
                            <th>إلى تاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyStatus->sick_leaves as $index => $leave)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $leave['employee_name'] ?? '' }}</td>
                            <td>{{ $leave['employee_number'] ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d') ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <table>
                    @php
                        // استرجاع total_required من السجل إذا كان موجودًا، وإلا فاستخدم القيمة الافتراضية 86
                        $totalRequired = $dailyStatus->total_required ?? 86;
                        $totalEmployees = \App\Models\Employee::where('is_active', 1)->count();
                        $shortage = $totalRequired - $totalEmployees;

                        $paidLeavesCount = count($dailyStatus->annual_leaves ?? [])
                                                + count($dailyStatus->periodic_leaves ?? [])
                                                + count($dailyStatus->sick_leaves ?? [])
                                                + count($dailyStatus->bereavement_leaves ?? []);

                        // حساب إجازات الأعياد بشكل منفصل للتأكد من وجود employee_id
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
                        $customUsagesCount = count($dailyStatus->custom_usages ?? []); // إضافة عدد الاستخدامات المخصصة

                        // تم تعديل حساب الحضور الفعلي ليشمل guard_rest و custom_usages
                        $actualAttendance = $totalEmployees - ($paidLeavesCount + $unpaidLeavesCount + $absencesCount + $temporaryLeavesCount + $guardRestCount + $customUsagesCount);

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
                        <th>إجازة وفاة</th> {{-- إضافة عمود جديد --}}
                        <th>استخدامات مخصصة</th> {{-- إضافة عمود جديد --}}
                        <th>تعيين</th>
                        <th>نقل</th>
                        <th>فصل</th>
                    </tr>
                    <tr>
                        <td>{{ $totalRequired }}</td>
                        <td>{{ $totalEmployees }}</td>
                        <td>{{ $shortage }}</td>
                        <td>{{ $actualAttendance }}</td>
                        <td>{{ $paidLeavesCount }}</td>
                        <td>{{ $unpaidLeavesCount }}</td>
                        <td>{{ $absencesCount }}</td>
                        <td>{{ $guardRestCount }}</td>
                        <td>{{ $temporaryLeavesCount }}</td>
                        <td>{{ count($dailyStatus->bereavement_leaves ?? []) }}</td> {{-- عرض عدد إجازات الوفاة --}}
                        <td>{{ $customUsagesCount }}</td> {{-- عرض عدد الاستخدامات المخصصة --}}
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                </table>

                <div class="signatures-container">
                    {{-- مسؤول شعبة الخدمية في اليمين --}}
                    <div class="signature-block responsible-signature">
                        <div>مسؤول شعبة الخدمية</div>
                        <div class="signature-line">
                            <div>التوقيع: ........................</div>
                            {{-- تعديل تاريخ التوقيع ليكون بعد يوم واحد من تاريخ التقرير --}}
                            <div>التاريخ: {{ \Carbon\Carbon::parse($dailyStatus->date)->addDay()->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    {{-- منظم الموقف في اليسار --}}
                    @if (!empty($dailyStatus->organizer_employee_name))
                    <div class="signature-block organizer-signature">
                        <div>منظم الموقف: {{ $dailyStatus->organizer_employee_name }}</div>
                        <div class="signature-line">
                            <div>التوقيع: ........................</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="text-center mt-4">
                    <button onclick="window.print()" class="btn btn-success me-2">
                        <i class="fas fa-print"></i> طباعة التقرير
                    </button>
                    <button onclick="window.history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> العودة
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts')
    {{-- لا توجد سكربتات JS مخصصة إضافية لهذه الصفحة بخلاف ما يوفره AdminLTE و Bootstrap بشكل افتراضي --}}
@endsection