@extends('layouts.admin_layout')

@section('title', 'إحصائيات استبيان رضا الزائرين المتقدمة')

@section('page_title', 'لوحة معلومات إحصائيات رضا الزائرين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">إحصائيات الاستبيانات المتقدمة</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg border-primary rounded-lg">
            <div class="card-header border-0 bg-gradient-dark">
                <h3 class="card-title font-weight-bold text-white">
                    <i class="fas fa-chart-line mr-2"></i> لوحة معلومات إحصائيات رضا الزائرين
                </h3>
                <div class="card-tools">
                    {{-- تم إضافة زر الطباعة هنا --}}
                    <button id="printReportBtn" class="btn btn-success btn-sm shadow-sm">
                        <i class="fas fa-file-pdf mr-2"></i> طباعة التقرير / PDF
                    </button>
                </div>
            </div>
            <div class="card-body p-4 bg-dark-custom">
                {{-- رسائل الفلاش --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show animated--grow-in" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show animated--grow-in" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- فلاتر التاريخ --}}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-gradient-dark shadow-sm border-secondary rounded">
                            <div class="card-header border-0">
                                <h5 class="card-title text-white"><i class="fas fa-filter mr-2"></i> تصفية البيانات حسب التاريخ</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-5 mb-3 mb-md-0">
                                        <label for="from_date" class="text-white-50">من تاريخ:</label>
                                        <input type="date" id="from_date" class="form-control form-control-dark" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-5 mb-3 mb-md-0">
                                        <label for="to_date" class="text-white-50">إلى تاريخ:</label>
                                        <input type="date" id="to_date" class="form-control form-control-dark" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button id="applyFilters" class="btn btn-primary btn-block btn-flat custom-btn-gradient"><i class="fas fa-check-circle mr-1"></i> تطبيق</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- المخطط 1: توزيع الرضا العام (دائري) --}}
                    <div class="col-md-6 mb-4">
                        <div class="card card-outline card-primary shadow-lg border-primary rounded-lg chart-card">
                            <div class="card-header border-0 bg-gradient-primary-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-chart-pie mr-2"></i> توزيع الرضا العام
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="satisfactionPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- المخطط 2: نظافة القاعات (حلقي - Doughnut Chart) --}}
                    <div class="col-md-6 mb-4">
                        <div class="card card-outline card-success shadow-lg border-success rounded-lg chart-card">
                            <div class="card-header border-0 bg-gradient-success-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-house-user mr-2"></i> نظافة القاعات
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="hallCleanlinessChart"></canvas>
                                    <div id="hallCleanlinessCenterText" class="chart-center-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    {{-- المخطط 3: نظافة ترامز الماء (شريطي أفقي - Horizontal Bar Chart) --}}
                    <div class="col-md-6 mb-4">
                        <div class="card card-outline card-warning shadow-lg border-warning rounded-lg chart-card">
                            <div class="card-header border-0 bg-gradient-warning-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-faucet mr-2"></i> نظافة ترامز الماء
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="waterTramsCleanlinessChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- المخطط 4: نظافة المرافق العامة (عمودي - Vertical Bar Chart) --}}
                    <div class="col-md-6 mb-4">
                        <div class="card card-outline card-danger shadow-lg border-danger rounded-lg chart-card">
                            <div class="card-header border-0 bg-gradient-danger-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-restroom mr-2"></i> نظافة المرافق العامة
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="facilitiesCleanlinessChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- تم إزالة قسم مخطط السرعة والدقة بالكامل هنا بناءً على طلبك --}}

                {{-- جدول الإحصائيات التفصيلية --}}
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card card-outline card-secondary shadow-lg border-secondary rounded-lg">
                            <div class="card-header border-0 bg-gradient-secondary-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-table mr-2"></i> الإحصائيات التفصيلية
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="statisticsTable" class="table table-dark table-striped table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>المؤشر</th>
                                                <th>ممتاز</th>
                                                <th>جيد جداً</th>
                                                <th>جيد</th>
                                                <th>مقبول</th>
                                                <th>ضعيف</th>
                                                <th>المجموع</th>
                                                <th>متوسط الرضا</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- سيتم ملء البيانات بواسطة JavaScript --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    {{-- نموذج الطباعة المخفي --}}
    <div id="printableReport" style="display: none; direction: rtl;">
        <div class="print-container" lang="ar" dir="rtl">
            <div class="print-header">
                <div class="header-content">
                    {{-- الشعار الأول على اليمين --}}
                    <div class="logo-section">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="شعار المؤسسة 1"
                             class="logo"
                             onerror="this.onerror=null; this.src='https://placehold.co/80x80/CCCCCC/666666?text=شعار1';"
                             title="إذا لم يظهر الشعار الأول، تأكد من مساره في مجلد public/images">
                    </div>

                    {{-- محتوى النص في المنتصف --}}
                    <div class="title-section">
                        <h1>تقرير إحصائيات رضا الزائرين</h1>
                        <h2>لوحة معلومات شاملة</h2>
                        <div class="date-range">
                            <span>الفترة من: <span id="printFromDate"></span> إلى: <span id="printToDate"></span></span>
                        </div>
                    </div>

                    {{-- الشعار الثاني على اليسار --}}
                    <div class="logo-section">
                        <img src="{{ asset('images/another_logo.png') }}"
                             alt="شعار المؤسسة 2"
                             class="logo"
                             onerror="this.onerror=null; this.src='https://placehold.co/80x80/CCCCCC/666666?text=شعار2';"
                             title="إذا لم يظهر الشعار الثاني، تأكد من مساره في مجلد public/images">
                    </div>
                </div>
            </div>


            <div class="print-body">
                <div class="charts-section">
                    <h3>المخططات البيانية</h3>
                    <div class="charts-grid">
                        <div class="chart-item">
                            <h4>توزيع الرضا العام</h4>
                            <canvas id="printSatisfactionChart"></canvas>
                        </div>
                        <div class="chart-item">
                            <h4>نظافة القاعات</h4>
                            <canvas id="printHallChart"></canvas>
                        </div>
                        <div class="chart-item">
                            <h4>نظافة ترامز الماء</h4>
                            <canvas id="printWaterChart"></canvas>
                        </div>
                        <div class="chart-item">
                            <h4>نظافة المرافق العامة</h4>
                            <canvas id="printFacilitiesChart"></canvas>
                        </div>
                    </div>
                    {{-- تم إزالة قسم مخطط السرعة والدقة من الطباعة هنا بناءً على طلبك --}}
                </div>

                <div class="statistics-section">
                    <h3>الإحصائيات التفصيلية</h3>
                    <table id="printStatisticsTable" class="statistics-table">
                        <thead>
                            <tr>
                                <th>المؤشر</th>
                                <th>ممتاز</th>
                                <th>جيد جداً</th>
                                <th>جيد</th>
                                <th>مقبول</th>
                                <th>ضعيف</th>
                                <th>المجموع</th>
                                <th>متوسط الرضا</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- سيتم ملء البيانات بواسطة JavaScript للطباعة --}}
                        </tbody>
                    </table>
                </div>

                <div class="summary-section">
                    <h3>ملخص التقرير</h3>
                    <div class="summary-content">
                        <div class="summary-item">
                            <strong>إجمالي الاستبيانات:</strong> <span id="totalSurveysPrint">-</span>
                        </div>
                        <div class="summary-item">
                            <strong>معدل الرضا العام:</strong> <span id="overallSatisfactionPrint">-</span>%
                        </div>
                        <div class="summary-item">
                            <strong>أعلى مؤشر رضا:</strong> <span id="highestIndicatorPrint" class="highest-indicator-color">-</span>
                        </div>
                        <div class="summary-item">
                            <strong>أقل مؤشر رضا:</strong> <span id="lowestIndicatorPrint" class="lowest-indicator-color">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="print-footer">
                <div class="footer-content">
                    <div class="generation-info">
                        <span>تم إنشاء التقرير في: <span id="generationDate"></span></span>
                    </div>
                    <div class="page-number">
                        <span>صفحة 1 من 1</span> {{-- يمكن جعلها ديناميكية لاحقًا لتقارير متعددة الصفحات --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
            background-color: #2c3e50;
            color: #ecf0f1;
        }
        .bg-dark-custom {
            background-color: #34495e;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
        }
        .card-header {
            border-bottom: none !important;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            font-size: 1.35rem;
            margin-bottom: 0;
            color: #ecf0f1;
        }
        .card-tools .btn {
            font-size: 0.85rem; /* حجم خط أصغر لزر الطباعة */
            padding: 0.45rem 0.9rem;
        }
        .card-outline.card-info { border-top: 3px solid #17a2b8; }
        .card-outline.card-primary { border-top: 3px solid #007bff; }
        .card-outline.card-success { border-top: 3px solid #28a745; }
        .card-outline.card-warning { border-top: 3px solid #ffc107; }
        .card-outline.card-danger { border-top: 3px solid #dc3545; }
        .card-outline.card-secondary { border-top: 3px solid #6c757d; } /* إضافة لون للجدول */

        .bg-gradient-primary-dark { background: linear-gradient(to right, #007bff, #0056b3); }
        .bg-gradient-success-dark { background: linear-gradient(to right, #28a745, #1e7e34); }
        .bg-gradient-warning-dark { background: linear-gradient(to right, #ffc107, #d39e00); }
        .bg-gradient-danger-dark { background: linear-gradient(to right, #dc3545, #b21f2d); }
        .bg-gradient-info-dark { background: linear-gradient(to right, #17a2b8, #117a8b); }
        .bg-gradient-secondary-dark { background: linear-gradient(to right, #6c757d, #545b62); } /* إضافة لون للجدول */
        .bg-gradient-dark { background: linear-gradient(to right, #343a40, #23272b); }

        .chart-container {
            position: relative;
            height: 380px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        canvas {
            background-color: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            padding: 15px;
            width: 100% !important;
            height: 100% !important;
        }
        .card-body .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ecf0f1;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }
        .card-body .form-control::placeholder {
            color: #bdc3c7;
        }
        .card-body .form-control:focus {
            background-color: rgba(255, 255, 255, 0.18);
            border-color: #6edff6;
            box-shadow: 0 0 0 0.25rem rgba(110, 223, 246, 0.35);
        }
        .form-control-dark {
            background-color: #2c3e50;
            border-color: #5d6d7e;
            color: #ecf0f1;
        }
        .form-control-dark:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
        }

        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            font-weight: bold;
            color: #e0e0e0;
            text-align: center;
            pointer-events: none;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.9);
            line-height: 1.2;
        }
        .custom-btn-gradient {
            background: linear-gradient(to right, #007bff, #0056b3);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .custom-btn-gradient:hover {
            background: linear-gradient(to right, #0056b3, #007bff);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 10px;
            font-weight: 500;
        }
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }

        .animated--grow-in {
            animation: growIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        @keyframes growIn {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .table-dark {
            background-color: #2c3e50;
            color: #ecf0f1;
        }
        .table-dark th {
            background-color: #34495e;
            border-color: #5d6d7e;
        }
        .table-dark td {
            border-color: #5d6d7e;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Adjust chart legend and tooltip for dark theme */
        .chartjs-tooltip {
            background-color: rgba(0, 0, 0, 0.9) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 8px !important;
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
        }
        .chartjs-tooltip-header-title {
            color: #f1c40f !important;
        }
        .chartjs-tooltip-body {
            color: #ecf0f1 !important;
        }
        .chartjs-tooltip-item-label {
            color: #ecf0f1 !important;
        }

        /* Print Styles */
        #printableReport {
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: white;
        }
        .print-container {
            width: 100%;
            max-width: 1200px; /* يمكن تعديل هذا حسب عرض PDF */
            margin: 0 auto;
            background: white;
            color: #333;
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif;
            padding: 20px; /* لتقليل الهوامش الزائدة */
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center; /* توسيط المحتوى */
            gap: 20px;
        }
        .logo-section .logo {
            height: 80px;
            width: auto;
            max-width: 150px;
        }
        .title-section {
            text-align: center;
        }
        .title-section h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #007bff;
            margin: 0 0 5px 0;
        }
        .title-section h2 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #6c757d;
            margin: 0 0 10px 0;
        }
        .date-range {
            background: #007bff;
            color: white;
            padding: 8px 18px;
            border-radius: 18px;
            font-weight: 600;
            display: inline-block;
            font-size: 0.95rem;
        }

        .print-body {
            padding: 20px 0;
        }

        .charts-section h3,
        .statistics-section h3,
        .summary-section h3 {
            color: #007bff;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #007bff;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        .chart-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .chart-item h4 {
            color: #495057;
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 12px;
            text-align: center;
        }
        .chart-item canvas {
            width: 100% !important;
            height: 280px !important; /* ارتفاع ثابت للمخططات في الطباعة */
            background-color: transparent !important; /* إزالة الخلفية الشفافة */
            box-shadow: none !important; /* إزالة الظل */
            border: none !important; /* إزالة الحدود */
            padding: 0 !important; /* إزالة المسافة الداخلية */
        }

        .full-width-chart {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }
        .full-width-chart h4 {
            color: #495057;
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 12px;
            text-align: center;
        }
        .full-width-chart canvas {
            width: 100% !important;
            height: 350px !important; /* ارتفاع ثابت للمخططات في الطباعة */
            background-color: transparent !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }

        .statistics-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .statistics-table th,
        .statistics-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
        }
        .statistics-table th {
            background: #007bff;
            color: white;
            font-weight: 600;
        }
        .statistics-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .statistics-table tr:hover {
            background-color: #e9ecef;
        }

        .summary-section {
            margin-top: 25px;
        }
        .summary-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        .summary-item {
            background: #f8f9fa;
            padding: 18px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .summary-item strong {
            color: #007bff;
            font-size: 1rem;
            display: block;
            margin-bottom: 5px;
        }
        .summary-item span {
            color: #495057;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* New styles for highest/lowest indicator colors */
        .highest-indicator-color {
            color: #808000; /* Olive */
            font-weight: bold;
        }
        .lowest-indicator-color {
            color: #dc3545; /* Red */
            font-weight: bold;
        }

        .print-footer {
            margin-top: 30px;
            padding: 15px 20px;
            border-top: 2px solid #dee2e6;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .generation-info,
        .page-number {
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Hide elements when printing the entire page (if not using specific PDF generation) */
        @media print {
            body > *:not(#printableReport) {
                display: none;
            }
            #printableReport {
                display: block !important; /* عرض قسم الطباعة فقط */
                position: relative; /* لضمان عدم حدوث مشاكل في التخطيط */
                width: auto; /* السماح بتوسيع العرض ليلائم حجم الورقة */
                margin: 0;
                padding: 0;
            }
            .print-container {
                max-width: none;
                width: 100%;
                padding: 0;
            }
            /* إعادة تعيين ارتفاع الكانفاس للطباعة */
            .chart-item canvas, .full-width-chart canvas {
                height: 280px !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .charts-grid {
                page-break-inside: avoid; /* منع تقسيم الشبكة بين الصفحات */
            }
            .statistics-section {
                page-break-before: always; /* بدء قسم الإحصائيات في صفحة جديدة إذا كان المحتوى طويلاً */
            }
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        // Register the datalabels plugin globally
        Chart.register(ChartDataLabels);

        // Define chart instances globally for access and destruction
        let satisfactionPieChart = null;
        let hallCleanlinessChart = null;
        let waterTramsCleanlinessChart = null;
        let facilitiesCleanlinessChart = null;
        // speedAccuracyChart تم إزالته

        // Store fetched data globally for printing
        let currentChartData = {};
        let currentTableData = [];
        let currentSummaryData = {};

        document.addEventListener('DOMContentLoaded', function () {
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            const applyFiltersButton = document.getElementById('applyFilters');
            const printReportBtn = document.getElementById('printReportBtn');

            // Set default dates if not already set (e.g., from previous request)
            if (!fromDateInput.value) {
                const today = new Date();
                const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                fromDateInput.value = firstDayOfMonth.toISOString().split('T')[0];
            }
            if (!toDateInput.value) {
                toDateInput.value = new Date().toISOString().split('T')[0];
            }

            // Function to generate distinct HSL colors for charts
            const generateDynamicColors = (numColors, saturationOffset = 0, lightnessOffset = 0) => {
                const colors = [];
                const baseHues = [0, 60, 120, 180, 240, 300, 30, 90, 150, 210, 270, 330, 10, 70, 130, 190, 250, 310];
                for (let i = 0; i < numColors; i++) {
                    const hue = baseHues[i % baseHues.length];
                    const saturation = Math.min(100, 70 + saturationOffset + (i * 2 % 10));
                    const lightness = Math.min(100, 60 + lightnessOffset + (i * 3 % 10));
                    colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
                }
                return colors;
            };

            // Common chart options for Arabic fonts and dark theme
            const commonChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#ecf0f1',
                            font: { size: 13, family: 'Cairo, Noto Sans Arabic, sans-serif', weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        titleFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 15, weight: 'bold' },
                        bodyFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 13 },
                        borderColor: 'rgba(255, 255, 255, 0.4)',
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12,
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            size: 13,
                            weight: 'bold',
                            family: 'Cairo, Noto Sans Arabic, sans-serif'
                        },
                        textShadowBlur: 6,
                        textShadowColor: 'rgba(0,0,0,0.9)'
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#ecf0f1', font: { family: 'Cairo, Noto Sans Arabic', size: 11, weight: '500' } },
                        grid: { color: 'rgba(255, 255, 255, 0.08)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#ecf0f1', font: { family: 'Cairo, Noto Sans Arabic', size: 11, weight: '500' } },
                        grid: { color: 'rgba(255, 255, 255, 0.08)' }
                    }
                }
            };

            // Options for charts when rendered for printing (lighter theme)
            const printChartOptions = (originalOptions) => {
                const options = JSON.parse(JSON.stringify(originalOptions)); // Deep copy
                options.plugins.legend.labels.color = '#495057'; // Darker text for print
                options.plugins.datalabels.color = '#333'; // Darker text for print
                options.plugins.datalabels.textShadowColor = 'rgba(255,255,255,0.7)'; // Lighter shadow for print
                if (options.scales) {
                    if (options.scales.x) {
                        options.scales.x.ticks.color = '#495057';
                        options.scales.x.grid.color = 'rgba(0, 0, 0, 0.08)';
                        options.scales.x.title = {
                            display: true,
                            text: options.scales.x.title ? options.scales.x.title.text : '',
                            color: '#495057',
                            font: { family: 'Cairo', size: 12, weight: 'bold' }
                        };
                    }
                    if (options.scales.y) {
                        options.scales.y.ticks.color = '#495057';
                        options.scales.y.grid.color = 'rgba(0, 0, 0, 0.08)';
                        options.scales.y.title = {
                            display: true,
                            text: options.scales.y.title ? options.scales.y.title.text : '',
                            color: '#495057',
                            font: { family: 'Cairo', size: 12, weight: 'bold' }
                        };
                    }
                }
                return options;
            };

            // Helper function to render or update a chart
            const renderChart = (ctx, type, data, options, chartInstance) => {
                if (chartInstance) {
                    chartInstance.destroy(); // Destroy existing chart instance
                }
                return new Chart(ctx, { type, data, options }); // Create new chart instance
            };

            // Function to populate the detailed statistics table
            function populateStatisticsTable(data) {
                const tableBody = document.querySelector('#statisticsTable tbody');
                tableBody.innerHTML = ''; // Clear existing rows

                if (!data || data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="8" class="text-center">لا توجد بيانات لعرضها.</td></tr>`;
                    return;
                }

                data.forEach(item => {
                    const row = tableBody.insertRow();
                    row.innerHTML = `
                        <td>${item.indicator}</td>
                        <td>${item.excellent}</td>
                        <td>${item.very_good}</td>
                        <td>${item.good}</td>
                        <td>${item.acceptable}</td>
                        <td>${item.poor}</td>
                        <td>${item.total}</td>
                        <td>${item.avg_satisfaction.toFixed(2)}%</td>
                    `;
                });
            }

            // Function to populate the summary section
            // Modified to accept a target container ID for flexibility (main dashboard vs. print)
            function populateSummarySection(data, containerId = '') {
                let targetContainer = document;
                if (containerId) {
                    targetContainer = document.getElementById(containerId);
                    if (!targetContainer) {
                        console.error(`Target container with ID "${containerId}" not found for summary population.`);
                        return;
                    }
                }

                // Use querySelector within the targetContainer to find the spans
                const totalSurveysSpan = targetContainer.querySelector('#totalSurveys' + (containerId ? 'Print' : ''));
                const overallSatisfactionSpan = targetContainer.querySelector('#overallSatisfaction' + (containerId ? 'Print' : ''));
                const highestIndicatorSpan = targetContainer.querySelector('#highestIndicator' + (containerId ? 'Print' : ''));
                const lowestIndicatorSpan = targetContainer.querySelector('#lowestIndicator' + (containerId ? 'Print' : ''));

                if (totalSurveysSpan) totalSurveysSpan.textContent = data.total_surveys || '-';
                if (overallSatisfactionSpan) overallSatisfactionSpan.textContent = (data.overall_satisfaction || 0).toFixed(2);

                // Apply colors to highest and lowest indicators for print version
                if (highestIndicatorSpan) {
                    highestIndicatorSpan.textContent = data.highest_indicator || '-';
                    if (containerId) { // Only apply color for the print version
                        highestIndicatorSpan.classList.add('highest-indicator-color');
                    } else {
                        highestIndicatorSpan.classList.remove('highest-indicator-color');
                    }
                }
                if (lowestIndicatorSpan) {
                    lowestIndicatorSpan.textContent = data.lowest_indicator || '-';
                    if (containerId) { // Only apply color for the print version
                        lowestIndicatorSpan.classList.add('lowest-indicator-color');
                    } else {
                        lowestIndicatorSpan.classList.remove('lowest-indicator-color');
                    }
                }
            }


            // Main function to fetch and render all dashboard data
            async function fetchAndRenderAllData() {
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;
                const queryParams = `from_date=${fromDate}&to_date=${toDate}`;

                try {
                    // Fetch all data concurrently using Promise.all
                    const [
                        pieDataResponse,
                        hallDataResponse,
                        waterDataResponse,
                        facilitiesDataResponse,
                        tableSummaryDataResponse
                    ] = await Promise.all([
                        fetch(`{{ route('charts.surveys.pie-data') }}?${queryParams}`),
                        fetch(`{{ route('charts.surveys.hall-cleanliness-data') }}?${queryParams}`),
                        fetch(`{{ route('charts.surveys.water-trams-cleanliness-data') }}?${queryParams}`),
                        fetch(`{{ route('charts.surveys.facilities-cleanliness-data') }}?${queryParams}`),
                        fetch(`{{ route('charts.surveys.table-summary-data') }}?${queryParams}`)
                    ]);

                    // Parse JSON responses
                    const pieData = await pieDataResponse.json();
                    const hallData = await hallDataResponse.json();
                    const waterData = await waterDataResponse.json();
                    const facilitiesData = await facilitiesDataResponse.json();
                    const tableSummaryData = await tableSummaryDataResponse.json();

                    // Store fetched data globally for later use (e.g., printing)
                    currentChartData.satisfaction = pieData;
                    currentChartData.hallCleanliness = hallData;
                    currentChartData.waterTrams = waterData;
                    currentChartData.facilities = facilitiesData;
                    currentTableData = tableSummaryData.detailed_statistics || [];
                    currentSummaryData = tableSummaryData.summary || {};


                    // Render Satisfaction Pie Chart
                    satisfactionPieChart = renderChart(
                        document.getElementById('satisfactionPieChart').getContext('2d'),
                        'doughnut',
                        {
                            labels: pieData.labels,
                            datasets: [{
                                data: pieData.datasets[0].data,
                                backgroundColor: generateDynamicColors(pieData.labels.length, 5),
                                hoverOffset: 15,
                                borderColor: 'rgba(255, 255, 255, 0.3)',
                                borderWidth: 2
                            }]
                        },
                        {
                            ...commonChartOptions,
                            cutout: '70%',
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { position: 'right', labels: commonChartOptions.plugins.legend.labels },
                                tooltip: {
                                    ...commonChartOptions.plugins.tooltip,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed !== null) {
                                                label += context.parsed;
                                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                                const percentage = (context.parsed / total * 100).toFixed(1) + '%';
                                                label += ' (' + percentage + ')';
                                            }
                                            return label;
                                        }
                                    }
                                },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    formatter: (value, context) => {
                                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                        const percentage = (value / total * 100).toFixed(1) + '%';
                                        return percentage;
                                    },
                                    align: 'center',
                                    anchor: 'center'
                                }
                            }
                        },
                        satisfactionPieChart
                    );

                    // Render Hall Cleanliness Doughnut Chart
                    const hallCenterTextElement = document.getElementById('hallCleanlinessCenterText');
                    const hallTotalCount = hallData.datasets[0].data.reduce((sum, val) => sum + val, 0);
                    hallCenterTextElement.innerHTML = `<span style="font-size:1.5rem; display:block; margin-bottom: 5px;">إجمالي التقييمات</span>${hallTotalCount}`;

                    hallCleanlinessChart = renderChart(
                        document.getElementById('hallCleanlinessChart').getContext('2d'),
                        'doughnut',
                        {
                            labels: hallData.labels,
                            datasets: [{
                                data: hallData.datasets[0].data,
                                backgroundColor: generateDynamicColors(hallData.labels.length, -5, 5),
                                hoverOffset: 18,
                                borderColor: 'rgba(255, 255, 255, 0.4)',
                                borderWidth: 3
                            }]
                        },
                        {
                            ...commonChartOptions,
                            cutout: '65%',
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { position: 'bottom', labels: commonChartOptions.plugins.legend.labels },
                                tooltip: {
                                    ...commonChartOptions.plugins.tooltip,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed !== null) {
                                                label += context.parsed;
                                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                                const percentage = (context.parsed / total * 100).toFixed(1) + '%';
                                                label += ' (' + percentage + ')';
                                            }
                                            return label;
                                        }
                                    }
                                },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    formatter: (value, context) => {
                                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                        const percentage = (value / total * 100).toFixed(1) + '%';
                                        return percentage;
                                    }
                                }
                            }
                        },
                        hallCleanlinessChart
                    );

                    // Render Water Trams Cleanliness Bar Chart
                    waterTramsCleanlinessChart = renderChart(
                        document.getElementById('waterTramsCleanlinessChart').getContext('2d'),
                        'bar',
                        {
                            labels: waterData.labels,
                            datasets: waterData.datasets.map((dataset, index) => ({
                                ...dataset,
                                backgroundColor: generateDynamicColors(waterData.labels.length, 0, -5),
                                borderColor: 'rgba(255, 255, 255, 0.07)',
                                borderWidth: 1,
                                borderRadius: 8,
                                hoverBackgroundColor: (context) => {
                                    const colors = generateDynamicColors(waterData.labels.length, 0, -10);
                                    return colors[context.dataIndex % colors.length];
                                },
                                barThickness: 20,
                            }))
                        },
                        {
                            ...commonChartOptions,
                            indexAxis: 'y', // Horizontal bar chart
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { display: false }, // No legend for single dataset bars
                                tooltip: { ...commonChartOptions.plugins.tooltip, displayColors: false },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    anchor: 'end', align: 'end', offset: 4, formatter: (value) => value + '%',
                                    color: '#ecf0f1', textShadowColor: 'rgba(0,0,0,0.7)', textShadowBlur: 4
                                }
                            },
                            scales: {
                                x: {
                                    ...commonChartOptions.scales.x,
                                    title: { display: true, text: 'مستوى الرضا (%)', color: '#bdc3c7', font: { family: 'Cairo', size: 12, weight: 'bold' } },
                                    max: 100, min: 0, ticks: { callback: function(value) { return value + '%'; } }
                                },
                                y: { ...commonChartOptions.scales.y, grid: { display: false } } // Hide Y-axis grid lines
                            }
                        },
                        waterTramsCleanlinessChart
                    );

                    // Render Facilities Cleanliness Bar Chart
                    facilitiesCleanlinessChart = renderChart(
                        document.getElementById('facilitiesCleanlinessChart').getContext('2d'),
                        'bar',
                        {
                            labels: facilitiesData.labels,
                            datasets: facilitiesData.datasets.map((dataset, index) => ({
                                ...dataset,
                                backgroundColor: generateDynamicColors(facilitiesData.labels.length, 10, -5),
                                borderColor: 'rgba(255, 255, 255, 0.52)',
                                borderWidth: 1,
                                borderRadius: 8,
                                hoverBackgroundColor: (context) => {
                                    const colors = generateDynamicColors(facilitiesData.labels.length, 10, -10);
                                    return colors[context.dataIndex % colors.length];
                                },
                                barThickness: 20
                            }))
                        },
                        {
                            ...commonChartOptions,
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { display: false }, // No legend for single dataset bars
                                tooltip: { ...commonChartOptions.plugins.tooltip, displayColors: false },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    anchor: 'end', align: 'top', offset: 4, formatter: (value) => value + '%',
                                    color: '#ecf0f1', textShadowColor: 'rgba(0,0,0,0.7)', textShadowBlur: 4
                                }
                            },
                            scales: {
                                x: {
                                    ...commonChartOptions.scales.x,
                                    title: { display: true, text: 'مستوى الرضا (%)', color: '#bdc3c7', font: { family: 'Cairo', size: 12, weight: 'bold' } }
                                },
                                y: {
                                    ...commonChartOptions.scales.y,
                                    max: 100, min: 0, ticks: { callback: function(value) { return value + '%'; } },
                                    grid: { display: true }
                                }
                            }
                        },
                        facilitiesCleanlinessChart
                    );

                    // Populate the statistics table and summary section
                    populateStatisticsTable(currentTableData);
                    populateSummarySection(currentSummaryData); // For the main dashboard

                } catch (error) {
                    console.error('Error fetching dashboard data:', error);
                    // Display an error message to the user
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show animated--grow-in';
                    alertDiv.setAttribute('role', 'alert');
                    alertDiv.innerHTML = `
                        حدث خطأ أثناء جلب البيانات. يرجى المحاولة مرة أخرى.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    document.querySelector('.card-body.p-4').prepend(alertDiv);
                }
            }

            // Initial data fetch and render on page load
            fetchAndRenderAllData();

            // Event listeners for date filters
            applyFiltersButton.addEventListener('click', fetchAndRenderAllData);
            fromDateInput.addEventListener('change', fetchAndRenderAllData);
            toDateInput.addEventListener('change', fetchAndRenderAllData);


            // Event listener for the Print Report / PDF button
            printReportBtn.addEventListener('click', async function () {
                const printableReportDiv = document.getElementById('printableReport');
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;
                const generationDate = new Date().toLocaleString('ar-EG', {
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit',
                    hour12: true
                });

                // Update date range and generation date in the printable report
                document.getElementById('printFromDate').textContent = fromDate;
                document.getElementById('printToDate').textContent = toDate;
                document.getElementById('generationDate').textContent = generationDate;

                // Make the printable report visible BEFORE rendering charts for printing
                printableReportDiv.style.display = 'block';

                // Populate print table (using the print-specific table body)
                const printTableBody = printableReportDiv.querySelector('#printStatisticsTable tbody');
                printTableBody.innerHTML = '';
                currentTableData.forEach(item => {
                    const row = printTableBody.insertRow();
                    row.innerHTML = `
                        <td>${item.indicator}</td>
                        <td>${item.excellent}</td>
                        <td>${item.very_good}</td>
                        <td>${item.good}</td>
                        <td>${item.acceptable}</td>
                        <td>${item.poor}</td>
                        <td>${item.total}</td>
                        <td>${item.avg_satisfaction.toFixed(2)}%</td>
                    `;
                });

                // Populate print summary (targeting the printableReportDiv specifically)
                populateSummarySection(currentSummaryData, 'printableReport');


                // Destroy existing print chart instances before re-rendering
                const printCharts = ['printSatisfactionChart', 'printHallChart', 'printWaterChart', 'printFacilitiesChart']; // Removed 'printSpeedChart'
                printCharts.forEach(id => {
                    const canvas = document.getElementById(id);
                    if (canvas && Chart.getChart(canvas)) {
                        Chart.getChart(canvas).destroy();
                    }
                });

                // Render Satisfaction Pie Chart for printing
                const printSatisfactionChartCtx = document.getElementById('printSatisfactionChart').getContext('2d');
                new Chart(printSatisfactionChartCtx, {
                    type: 'doughnut',
                    data: {
                        labels: currentChartData.satisfaction.labels,
                        datasets: [{
                            data: currentChartData.satisfaction.datasets[0].data,
                            backgroundColor: generateDynamicColors(currentChartData.satisfaction.labels.length, 5),
                            hoverOffset: 15,
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        cutout: '70%',
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { position: 'right', labels: { color: '#495057' } },
                            datalabels: { ...printChartOptions(commonChartOptions).plugins.datalabels, color: '#333' }
                        }
                    }
                });

                // Render Hall Cleanliness Doughnut Chart for printing
                const printHallCleanlinessChartCtx = document.getElementById('printHallChart').getContext('2d');
                new Chart(printHallCleanlinessChartCtx, {
                    type: 'doughnut',
                    data: {
                        labels: currentChartData.hallCleanliness.labels,
                        datasets: [{
                            data: currentChartData.hallCleanliness.datasets[0].data,
                            backgroundColor: generateDynamicColors(currentChartData.hallCleanliness.labels.length, -5, 5),
                            hoverOffset: 18,
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 3
                        }]
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        cutout: '65%',
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { position: 'bottom', labels: { color: '#495057' } },
                            datalabels: { ...printChartOptions(commonChartOptions).plugins.datalabels, color: '#333' }
                        }
                    }
                });

                // Render Water Trams Cleanliness Bar Chart for printing
                const printWaterTramsCleanlinessChartCtx = document.getElementById('printWaterChart').getContext('2d');
                new Chart(printWaterTramsCleanlinessChartCtx, {
                    type: 'bar',
                    data: {
                        labels: currentChartData.waterTrams.labels,
                        datasets: currentChartData.waterTrams.datasets.map((dataset, index) => ({
                            ...dataset,
                            backgroundColor: generateDynamicColors(currentChartData.waterTrams.labels.length, 0, -5),
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                            borderRadius: 8,
                        }))
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        indexAxis: 'y',
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { display: false },
                            datalabels: { ...printChartOptions(commonChartOptions).plugins.datalabels, color: '#333' }
                        },
                        scales: {
                            x: {
                                ...printChartOptions(commonChartOptions).scales.x,
                                title: { display: true, text: 'مستوى الرضا (%)', color: '#495057' },
                                max: 100, min: 0, ticks: { callback: function(value) { return value + '%'; } }
                            },
                            y: { ...printChartOptions(commonChartOptions).scales.y, grid: { display: false } }
                        }
                    }
                });

                // Render Facilities Cleanliness Bar Chart for printing
                const printFacilitiesCleanlinessChartCtx = document.getElementById('printFacilitiesChart').getContext('2d');
                new Chart(printFacilitiesCleanlinessChartCtx, {
                    type: 'bar',
                    data: {
                        labels: currentChartData.facilities.labels,
                        datasets: currentChartData.facilities.datasets.map((dataset, index) => ({
                            ...dataset,
                            backgroundColor: generateDynamicColors(currentChartData.facilities.labels.length, 10, -5),
                            borderColor: 'rgba(0, 0, 0, 0.2)',
                            borderWidth: 1,
                            borderRadius: 8,
                        }))
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { display: false },
                            datalabels: { ...printChartOptions(commonChartOptions).plugins.datalabels, color: '#333' }
                        },
                        scales: {
                            x: {
                                ...printChartOptions(commonChartOptions).scales.x,
                                title: { display: true, text: 'مستوى الرضا (%)', color: '#495057' }
                            },
                            y: {
                                ...printChartOptions(commonChartOptions).scales.y,
                                max: 100, min: 0, ticks: { callback: function(value) { return value + '%'; } },
                                grid: { display: true }
                            }
                        }
                    }
                });

                // Give charts a moment to render before capturing
                setTimeout(() => {
                    // Initialize jsPDF
                    const pdf = new window.jspdf.jsPDF('p', 'pt', 'a4');
                    const docWidth = pdf.internal.pageSize.getWidth();
                    const docHeight = pdf.internal.pageSize.getHeight();
                    const margin = 20;

                    // Use html2canvas to capture the printable report div
                    html2canvas(printableReportDiv, {
                        scale: 2, // Higher scale for better resolution in PDF
                        logging: true,
                        useCORS: true,
                        allowTaint: true,
                        scrollY: -window.scrollY, // Correct scrolling for hidden elements
                        windowWidth: document.documentElement.offsetWidth,
                        windowHeight: document.documentElement.offsetHeight
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const imgWidth = docWidth - (2 * margin);
                        const imgHeight = (canvas.height * imgWidth) / canvas.width;
                        let heightLeft = imgHeight;
                        let position = margin; // Starting position for the image on the PDF page

                        pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                        heightLeft -= docHeight - position;

                        // Handle multi-page PDF if content is too long
                        while (heightLeft >= 0) {
                            position = heightLeft - imgHeight + margin;
                            pdf.addPage();
                            pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                            heightLeft -= docHeight;
                        }

                        // Save the PDF
                        pdf.save(`تقرير-رضا-الزائرين-${fromDate}-إلى-${toDate}.pdf`);

                        // Hide the printable report div after PDF generation
                        printableReportDiv.style.display = 'none';
                    }).catch(error => {
                        console.error('Error generating PDF:', error);
                        // Use a custom message box instead of alert()
                        const errorMessage = 'حدث خطأ أثناء إنشاء ملف PDF. يرجى التأكد من تحميل جميع الموارد وإعادة المحاولة.';
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show animated--grow-in';
                        alertDiv.setAttribute('role', 'alert');
                        alertDiv.innerHTML = `
                            ${errorMessage}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        `;
                        document.querySelector('.card-body.p-4').prepend(alertDiv);
                        printableReportDiv.style.display = 'none'; // Ensure it's hidden on error
                    });
                }, 100); // Small delay to allow charts to fully render
            });
        });
    </script>
@endsection
