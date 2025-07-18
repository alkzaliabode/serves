@extends('layouts.admin_layout')

@section('title', 'إحصائيات استبيان رضا الزائرين المتقدمة')

@section('page_title', 'لوحة معلومات إحصائيات رضا الزائرين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">إحصائيات الاستبيانات المتقدمة</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg border-primary rounded-lg animate__animated animate__fadeInUp">
            <div class="card-header border-0 bg-gradient-dark">
                <h3 class="card-title font-weight-bold text-white">
                    <i class="fas fa-chart-line mr-2"></i> لوحة معلومات إحصائيات رضا الزائرين
                </h3>
                <div class="card-tools">
                    <button id="printReportBtn" class="btn btn-success btn-sm shadow-sm animate__animated animate__pulse animate__infinite">
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

                {{-- رسالة خطأ مخصصة لجلب البيانات --}}
                <div id="chartErrorAlert" class="alert alert-danger alert-dismissible fade show animate__animated animate__growIn" role="alert" style="display: none;">
                    حدث خطأ أثناء تحميل البيانات. يرجى التحقق من اتصال الشبكة وإعادة المحاولة.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- فلاتر التاريخ --}}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-gradient-dark shadow-sm border-secondary rounded animate__animated animate__fadeIn">
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
                    {{-- المخطط 1: توزيع الرضا العام (Doughnut Chart) --}}
                    <div class="col-md-6 mb-4 animate__animated animate__fadeInLeft">
                        <div class="card card-outline card-primary shadow-lg border-primary rounded-lg chart-card h-100">
                            <div class="card-header border-0 bg-gradient-primary-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-chart-pie mr-2"></i> توزيع الرضا العام
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="satisfactionDoughnutChart"></canvas>
                                    <div id="satisfactionCenterText" class="chart-center-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- المخطط 2: نظافة القاعات (Bar Chart - Vertical) --}}
                    <div class="col-md-6 mb-4 animate__animated animate__fadeInRight">
                        <div class="card card-outline card-success shadow-lg border-success rounded-lg chart-card h-100">
                            <div class="card-header border-0 bg-gradient-success-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-house-user mr-2"></i> نظافة القاعات
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="hallCleanlinessBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    {{-- المخطط 3: نظافة ترامز الماء (Horizontal Bar Chart) --}}
                    <div class="col-md-6 mb-4 animate__animated animate__fadeInLeft">
                        <div class="card card-outline card-warning shadow-lg border-warning rounded-lg chart-card h-100">
                            <div class="card-header border-0 bg-gradient-warning-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-faucet mr-2"></i> نظافة ترامز الماء
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="waterTramsCleanlinessHorizontalBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- المخطط 4: نظافة دورات المياه (Doughnut Chart) --}}
                    <div class="col-md-6 mb-4 animate__animated animate__fadeInRight">
                        <div class="card card-outline card-danger shadow-lg border-danger rounded-lg chart-card h-100">
                            <div class="card-header border-0 bg-gradient-danger-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-toilet mr-2"></i> نظافة دورات المياه
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="restroomCleanlinessDoughnutChart"></canvas>
                                    <div id="restroomCenterText" class="chart-center-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    {{-- المخطط 5: نظافة الساحات والممرات (Polar Area Chart) --}}
                    <div class="col-md-12 mb-4 animate__animated animate__fadeInUp">
                        <div class="card card-outline card-info shadow-lg border-info rounded-lg chart-card">
                            <div class="card-header border-0 bg-gradient-info-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-walking mr-2"></i> نظافة الساحات والممرات
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="courtyardsCleanlinessPolarAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- جدول الإحصائيات التفصيلية --}}
                <div class="row mt-4 animate__animated animate__fadeInUp">
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
                            <h4>نظافة دورات المياه</h4>
                            <canvas id="printRestroomChart"></canvas>
                        </div>
                        <div class="chart-item">
                            <h4>نظافة الساحات والممرات</h4>
                            <canvas id="printCourtyardsChart"></canvas>
                        </div>
                    </div>
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
                        <span>صفحة 1 من 1</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root {
            --primary-color: #007bff;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --secondary-color: #6c757d;
            --dark-bg-primary: #34495e;
            --dark-bg-secondary: #2c3e50;
            --text-color: #ecf0f1;
            --light-text-color: #bdc3c7;
            --border-color-dark: #5d6d7e;
            --chart-bg-dark: rgba(255, 255, 255, 0.03);
            --chart-border-dark: rgba(255, 255, 255, 0.3);
            
            /* نظام الألوان الجديد للمؤشرات */
            --excellent-color: #28a745; /* أخضر - ممتاز */
            --very-good-color: #8bc34a; /* أخضر فاتح - جيد جداً */
            --good-color: #ffc107; /* أصفر - جيد */
            --acceptable-color: #fd7e14; /* برتقالي - مقبول */
            --poor-color: #dc3545; /* أحمر - ضعيف */
            
            /* تدرجات الألوان */
            --gradient-primary: linear-gradient(to right, var(--primary-color), #0056b3);
            --gradient-success: linear-gradient(to right, var(--excellent-color), #1e7e34);
            --gradient-warning: linear-gradient(to right, var(--good-color), #d39e00);
            --gradient-danger: linear-gradient(to right, var(--poor-color), #b21f2d);
            --gradient-info: linear-gradient(to right, var(--info-color), #117a8b);
            --gradient-secondary: linear-gradient(to right, var(--secondary-color), #545b62);
            --gradient-dark: linear-gradient(to right, #343a40, #23272b);
        }

        body {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
            background-color: var(--dark-bg-secondary);
            color: var(--text-color);
            direction: rtl;
            text-align: right;
            overflow-x: hidden;
        }
        
        .bg-dark-custom { background-color: var(--dark-bg-primary); }

        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: none;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }
        
        .card-header {
            border-bottom: none !important;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        
        .card-title {
            font-size: 1.45rem;
            margin-bottom: 0;
            color: var(--text-color);
            display: flex;
            align-items: center;
        }
        
        .card-title i { margin-left: 10px; margin-right: 0; }

        .card-tools .btn {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Card outline colors */
        .card-outline.card-info { border-top: 4px solid var(--info-color); }
        .card-outline.card-primary { border-top: 4px solid var(--primary-color); }
        .card-outline.card-success { border-top: 4px solid var(--excellent-color); }
        .card-outline.card-warning { border-top: 4px solid var(--good-color); }
        .card-outline.card-danger { border-top: 4px solid var(--poor-color); }
        .card-outline.card-secondary { border-top: 4px solid var(--secondary-color); }

        /* Gradient backgrounds for headers */
        .bg-gradient-primary-dark { background: var(--gradient-primary); }
        .bg-gradient-success-dark { background: var(--gradient-success); }
        .bg-gradient-warning-dark { background: var(--gradient-warning); }
        .bg-gradient-danger-dark { background: var(--gradient-danger); }
        .bg-gradient-info-dark { background: var(--gradient-info); }
        .bg-gradient-secondary-dark { background: var(--gradient-secondary); }
        .bg-gradient-dark { background: var(--gradient-dark); }

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
            background-color: var(--chart-bg-dark);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Added for subtle 3D effect */
            padding: 15px;
            width: 100% !important;
            height: 100% !important;
            transition: all 0.5s ease-in-out;
        }
        
        canvas:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
        }

        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2.2rem;
            font-weight: bold;
            color: #e0e0e0;
            text-align: center;
            pointer-events: none;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.9);
            line-height: 1.2;
        }
        
        .custom-btn-gradient {
            background: var(--gradient-primary);
            border: none;
            border-radius: 10px;
            padding: 0.8rem 1.5rem;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }
        
        .custom-btn-gradient:hover {
            background: linear-gradient(to right, #0056b3, #007bff);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.5);
            transform: translateY(-3px);
        }
        
        .alert {
            border-radius: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }

        /* Table styles for dark theme */
        .table-dark {
            background-color: var(--dark-bg-secondary);
            color: var(--text-color);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table-dark thead th {
            background-color: var(--dark-bg-primary);
            border-color: var(--border-color-dark);
            color: var(--text-color);
            font-weight: 600;
            padding: 12px 15px;
        }
        
        .table-dark tbody td {
            border-color: var(--border-color-dark);
            padding: 10px 15px;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.08);
            transform: scale(1.005);
            transition: all 0.2s ease-in-out;
        }

        /* Chart.js Tooltip and Legend adjustments for dark theme */
        .chartjs-tooltip {
            background-color: rgba(0, 0, 0, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            border-radius: 12px !important;
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        
        .chartjs-tooltip-header-title { color: var(--warning-color) !important; }
        .chartjs-tooltip-body, .chartjs-tooltip-item-label { color: var(--text-color) !important; }

        /* Print Styles */
        #printableReport {
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: white;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999;
        }
        
        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            color: #333;
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif;
            padding: 20mm;
            box-sizing: border-box;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 10px;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        
        .logo-section { flex: 0 0 auto; }
        
        .logo-section .logo {
            height: 70px;
            width: auto;
            max-width: 120px;
        }
        
        .title-section {
            text-align: center;
            flex-grow: 1;
        }
        
        .title-section h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 5px 0;
        }
        
        .title-section h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0 0 10px 0;
        }
        
        .date-range {
            background: var(--primary-color);
            color: white;
            padding: 8px 18px;
            border-radius: 18px;
            font-weight: 600;
            display: inline-block;
            font-size: 0.95rem;
        }

        .print-body { padding: 20px 0; }

        .charts-section h3,
        .statistics-section h3,
        .summary-section h3 {
            color: var(--primary-color);
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-color);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
            page-break-inside: avoid;
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
            page-break-inside: avoid;
        }
        
        .chart-item h4 {
            color: #495057;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 12px;
            text-align: center;
        }
        
        .chart-item canvas {
            width: 100% !important;
            height: 250px !important;
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
            page-break-inside: avoid;
        }
        
        .statistics-table th,
        .statistics-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
            font-size: 1rem;
        }
        
        .statistics-table th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }
        
  .statistics-table tr:nth-child(even) { background-color: #f8f9fa; }
        .statistics-table tr:hover { background-color: #e9ecef; }

        .summary-section { margin-top: 25px; }
        
        .summary-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        
        .summary-item {
            background: #f8f9fa;
            padding: 18px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .summary-item strong {
            color: var(--primary-color);
            font-size: 1.05rem;
            display: block;
            margin-bottom: 5px;
        }
        
        .summary-item span {
            color: #495057;
            font-size: 1.15rem;
            font-weight: 600;
        }

        .highest-indicator-color { color: var(--excellent-color); font-weight: bold; }
        .lowest-indicator-color { color: var(--poor-color); font-weight: bold; }

        .print-footer {
            margin-top: 30px;
            padding: 15px 20px;
            border-top: 2px solid #dee2e6;
            background: #f8f9fa;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .generation-info, .page-number {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-header { flex-direction: column; align-items: flex-start; }
            .card-title { margin-bottom: 15px; }
            .card-tools { width: 100%; text-align: center; }
            .card-tools .btn { width: 100%; }
            .charts-grid { grid-template-columns: 1fr; }
            .summary-content { grid-template-columns: 1fr; }
            .header-content { flex-direction: column; }
            .logo-section { margin-bottom: 10px; }
            .title-section { margin-bottom: 10px; }
            .print-container { padding: 10mm; }
        }

        /* Print-specific media queries */
        @media print {
            body > *:not(#printableReport) { display: none; }
            #printableReport {
                display: block !important;
                position: relative;
                width: auto;
                margin: 0;
                padding: 0;
            }
            
            .print-container {
                max-width: none;
                width: 100%;
                padding: 0;
                box-shadow: none;
            }
            
            .chart-item canvas {
                height: 300px !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .charts-grid, .statistics-table, .summary-content {
                page-break-inside: avoid;
            }
            
            .statistics-section { page-break-before: auto; }
            
            .statistics-table th,
            .statistics-table td {
                padding: 8px 5px;
                font-size: 12px;
            }
            
            .print-footer { 
                position: fixed; 
                bottom: 0; 
                left: 0; 
                right: 0; 
                width: 100%; 
                border-top: 1px solid #eee; 
                background: white; 
                padding: 10px 20px; 
            }
            
            @page {
                size: A4;
                margin: 15mm;
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
        let satisfactionDoughnutChart = null;
        let hallCleanlinessBarChart = null;
        let waterTramsCleanlinessHorizontalBarChart = null;
        let restroomCleanlinessDoughnutChart = null; // Changed from Radar to Doughnut
        let courtyardsCleanlinessPolarAreaChart = null;

        // Store fetched data globally for printing
        let currentChartData = {};
        let currentTableData = [];
        let currentSummaryData = {};

        document.addEventListener('DOMContentLoaded', function () {
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            const applyFiltersButton = document.getElementById('applyFilters');
            const printReportBtn = document.getElementById('printReportBtn');
            const chartErrorAlert = document.getElementById('chartErrorAlert');

            // Set default dates if not already set
            if (!fromDateInput.value) {
                const today = new Date();
                const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                fromDateInput.value = firstDayOfMonth.toISOString().split('T')[0];
            }
            if (!toDateInput.value) {
                toDateInput.value = new Date().toISOString().split('T')[0];
            }

            // Define a consistent color palette with green for highest rating
            const chartColors = {
                excellent: 'var(--excellent-color)', // #28a745 (Green)
                veryGood: 'var(--very-good-color)',  // #8bc34a (Light Green)
                good: 'var(--good-color)',           // #ffc107 (Yellow)
                acceptable: 'var(--acceptable-color)', // #fd7e14 (Orange)
                poor: 'var(--poor-color)',           // #dc3545 (Red)
                backgrounds: [
                    'rgba(40, 167, 69, 0.7)',  // Excellent (Green)
                    'rgba(139, 195, 74, 0.7)', // Very Good
                    'rgba(255, 193, 7, 0.7)',  // Good (Yellow)
                    'rgba(253, 126, 20, 0.7)', // Acceptable (Orange)
                    'rgba(220, 53, 69, 0.7)'   // Poor (Red)
                ],
                borders: [
                    'var(--excellent-color)',
                    'var(--very-good-color)',
                    'var(--good-color)',
                    'var(--acceptable-color)',
                    'var(--poor-color)'
                ],
                fillBackgrounds: [
                    'rgba(40, 167, 69, 0.4)',
                    'rgba(139, 195, 74, 0.4)',
                    'rgba(255, 193, 7, 0.4)',
                    'rgba(253, 126, 20, 0.4)',
                    'rgba(220, 53, 69, 0.4)'
                ]
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
                        position: 'bottom',
                        labels: {
                            color: '#ecf0f1',
                            font: { 
                                size: 14, 
                                family: 'Cairo, Noto Sans Arabic, sans-serif', 
                                weight: '600' 
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.9)',
                        titleFont: { 
                            family: 'Cairo, Noto Sans Arabic, sans-serif', 
                            size: 14, 
                            weight: 'bold' 
                        },
                        bodyFont: { 
                            family: 'Cairo, Noto Sans Arabic, sans-serif', 
                            size: 13 
                        },
                        borderColor: 'rgba(255, 255, 255, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed !== null) {
                                    label += context.parsed;
                                    if (context.chart.data.labels[context.dataIndex]) {
                                        label += ' (' + context.chart.data.labels[context.dataIndex] + ')';
                                    }
                                }
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            size: 14,
                            weight: 'bold',
                            family: 'Cairo, Noto Sans Arabic, sans-serif'
                        },
                        textShadowBlur: 8,
                        textShadowColor: 'rgba(0,0,0,0.8)',
                        formatter: function(value, context) {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return percentage > 5 ? percentage + '%' : '';
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { 
                            color: '#ecf0f1', 
                            font: { 
                                family: 'Cairo, Noto Sans Arabic', 
                                size: 11, 
                                weight: '500' 
                            } 
                        },
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.08)' 
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: '#ecf0f1', 
                            font: { 
                                family: 'Cairo, Noto Sans Arabic', 
                                size: 11, 
                                weight: '500' 
                            } 
                        },
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.08)' 
                        }
                    }
                }
            };

            // Options for charts when rendered for printing (lighter theme)
            const printChartOptions = (originalOptions) => {
                const options = JSON.parse(JSON.stringify(originalOptions));
                options.plugins.legend.labels.color = '#000';
                options.plugins.datalabels.color = '#000';
                options.plugins.datalabels.font = { weight: 'bold', size: 10 }; // Reduced font size for print
                options.plugins.datalabels.offset = 2; // Adjusted offset for print
                options.plugins.datalabels.clip = false; // Add this to prevent clipping
                options.plugins.datalabels.textShadowBlur = 0; // Remove text shadow for print
                options.plugins.datalabels.textShadowColor = 'transparent'; // Remove text shadow for print
                
                if (options.scales) {
                    if (options.scales.x) {
                        options.scales.x.ticks.font = { size: 10, weight: 'bold' }; // Reduced font size for print
                    }
                    if (options.scales.y) {
                        options.scales.y.ticks.font = { size: 10, weight: 'bold' }; // Reduced font size for print
                    }
                }
                
                if (options.elements) {
                    options.elements.point = {
                        radius: 6,
                        hoverRadius: 8
                    };
                }
                
                return options;
            };

            // Helper function to render or update a chart
            const renderChart = (ctx, type, data, options, chartInstance) => {
                if (chartInstance) {
                    chartInstance.destroy();
                }
                return new Chart(ctx, { type, data, options });
            };

            // Function to populate the detailed statistics table
            function populateStatisticsTable(data) {
                const tableBody = document.querySelector('#statisticsTable tbody');
                tableBody.innerHTML = '';

                if (!data || data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="8" class="text-center">لا توجد بيانات لعرضها.</td></tr>`;
                    return;
                }

                // Mapping indicator keys to Arabic names
                const indicatorNames = {
                    overall_satisfaction: 'الرضا العام',
                    toilet_cleanliness: 'نظافة دورات المياه',
                    hygiene_supplies: 'توفر مستلزمات النظافة',
                    yard_cleanliness: 'نظافة الساحات والممرات',
                    cleaning_teams: 'ملاحظة فرق التنظيف',
                    hall_cleanliness: 'نظافة القاعات',
                    bedding_condition: 'حالة الفرش والبطانيات',
                    ventilation: 'التهوية',
                    lighting: 'الإضاءة',
                    water_trams_distribution: 'توزيع ترامز الماء',
                    water_trams_cleanliness: 'نظافة ترامز الماء',
                    water_availability: 'توفر الماء'
                };

                // Define the order of indicators for the table (matching the order in PHP controller)
                const orderedIndicators = [
                    'overall_satisfaction',
                    'toilet_cleanliness',
                    'hygiene_supplies',
                    'yard_cleanliness',
                    'cleaning_teams',
                    'hall_cleanliness',
                    'bedding_condition',
                    'ventilation',
                    'lighting',
                    'water_trams_distribution',
                    'water_trams_cleanliness',
                    'water_availability'
                ];

                orderedIndicators.forEach(key => {
                    const item = data.find(item => item.indicator === indicatorNames[key]); // Find by translated name
                    if (item) {
                        const row = tableBody.insertRow();
                        row.innerHTML = `
                            <td>${item.indicator}</td>
                            <td>${item.excellent}</td>
                            <td>${item.very_good}</td>
                            <td>${item.good}</td>
                            <td>${item.acceptable}</td>
                            <td>${item.poor}</td>
                            <td>${item.total}</td>
                            <td>${item.avg_satisfaction}%</td>
                        `;
                    }
                });
            }

            // Function to populate the summary section
            function populateSummarySection(data, containerId = '') {
                let targetContainer = document;
                if (containerId) {
                    targetContainer = document.getElementById(containerId);
                    if (!targetContainer) {
                        console.error(`Target container with ID "${containerId}" not found for summary population.`);
                        return;
                    }
                }

                const totalSurveysSpan = targetContainer.querySelector('#totalSurveys' + (containerId ? 'Print' : ''));
                const overallSatisfactionSpan = targetContainer.querySelector('#overallSatisfaction' + (containerId ? 'Print' : ''));
                const highestIndicatorSpan = targetContainer.querySelector('#highestIndicator' + (containerId ? 'Print' : ''));
                const lowestIndicatorSpan = targetContainer.querySelector('#lowestIndicator' + (containerId ? 'Print' : ''));

                if (totalSurveysSpan) totalSurveysSpan.textContent = data.total_surveys || '-';
                if (overallSatisfactionSpan) overallSatisfactionSpan.textContent = (data.overall_satisfaction || 0).toFixed(2);

                if (highestIndicatorSpan) {
                    highestIndicatorSpan.textContent = data.highest_indicator || '-';
                    if (containerId) {
                        highestIndicatorSpan.classList.add('highest-indicator-color');
                    } else {
                        highestIndicatorSpan.classList.remove('highest-indicator-color');
                    }
                }
                if (lowestIndicatorSpan) {
                    lowestIndicatorSpan.textContent = data.lowest_indicator || '-';
                    if (containerId) {
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
                const queryParams = new URLSearchParams({
                    from_date: fromDate,
                    to_date: toDate
                }).toString();

                try {
                    const response = await fetch(`/api/survey-data?${queryParams}`);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const allData = await response.json();
                    console.log("Fetched all data:", allData);

                    // Store fetched data globally
                    currentChartData = allData.chartData;
                    currentTableData = allData.tableData;
                    currentSummaryData = allData.summaryData;

                    // Chart 1: General Satisfaction (Doughnut Chart)
                    const satisfactionCtx = document.getElementById('satisfactionDoughnutChart').getContext('2d');
                    satisfactionDoughnutChart = renderChart(
                        satisfactionCtx,
                        'doughnut',
                        {
                            labels: currentChartData.satisfaction.labels,
                            datasets: [{
                                data: currentChartData.satisfaction.datasets[0].data,
                                backgroundColor: currentChartData.satisfaction.datasets[0].backgroundColor, // استخدم الألوان من المتحكم
                                borderColor: '#11db2cff', // يمكن تعديل هذا اللون إذا لزم الأمر
                                borderWidth: 2
                            }]
                        },
                        {
                            ...commonChartOptions,
                            cutout: '70%',
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { position: 'bottom', labels: commonChartOptions.plugins.legend.labels },
                                tooltip: {
                                    ...commonChartOptions.plugins.tooltip,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed !== null) {
                                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                                const percentage = (context.parsed / total * 100).toFixed(1) + '%';
                                                label += context.parsed + ' (' + percentage + ')';
                                            }
                                            return label;
                                        }
                                    }
                                },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    formatter: (value, context) => {
                                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                        return (value / total * 100).toFixed(1) + '%';
                                    },
                                    align: 'center',
                                    anchor: 'center'
                                }
                            }
                        },
                        satisfactionDoughnutChart
                    );

                    // Update center text for Doughnut Chart
                    const satisfactionCenterTextElement = document.getElementById('satisfactionCenterText');
                    if (satisfactionCenterTextElement) {
                        const totalSurveys = currentChartData.satisfaction.datasets[0].data.reduce((sum, val) => sum + val, 0);
                        const verySatisfiedIndex = currentChartData.satisfaction.labels.indexOf('راض جدًا');
                        const verySatisfiedCount = (verySatisfiedIndex !== -1) ? currentChartData.satisfaction.datasets[0].data[verySatisfiedIndex] : 0;
                        let percentage = 0;
                        if (totalSurveys > 0) {
                            percentage = (verySatisfiedCount / totalSurveys * 100).toFixed(1);
                        }
                        satisfactionCenterTextElement.innerHTML = `
                            <div style="font-size: 1.5rem; font-weight: bold; color: ${chartColors.excellent};">
                                ${percentage}%
                            </div>
                            <div style="font-size: 1rem; color: #14ee14ff; margin-top: 5px;">
                                رضا ممتاز
                            </div>
                        `;
                    }

                    // Chart 2: Hall Cleanliness (Bar Chart - Vertical)
                    const hallCleanlinessCtx = document.getElementById('hallCleanlinessBarChart').getContext('2d');
                    hallCleanlinessBarChart = renderChart(
                        hallCleanlinessCtx,
                        'bar',
                        {
                            labels: currentChartData.hallCleanliness.labels, // استخدم التسميات من المتحكم
                            datasets: [{
                                label: 'عدد الاستجابات',
                                data: currentChartData.hallCleanliness.datasets[0].data,
                                backgroundColor: currentChartData.hallCleanliness.datasets[0].backgroundColor, // استخدم الألوان من المتحكم
                                borderColor: currentChartData.hallCleanliness.datasets[0].borderColor,
                                borderWidth: 1,
                                borderRadius: 8,
                            }]
                        },
                        {
                            ...commonChartOptions,
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { display: false },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    anchor: 'end', 
                                    align: 'top', 
                                    formatter: (value) => value,
                                    color: '#ee0808ff', 
                                    textShadowColor: 'rgba(0,0,0,0.7)', 
                                    textShadowBlur: 4
                                }
                            },
                            scales: {
                                x: { ...commonChartOptions.scales.x },
                                y: { ...commonChartOptions.scales.y }
                            }
                        },
                        hallCleanlinessBarChart
                    );

                    // Chart 3: Water Trams Cleanliness (Horizontal Bar Chart)
                    const waterTramsCleanlinessCtx = document.getElementById('waterTramsCleanlinessHorizontalBarChart').getContext('2d');
                    waterTramsCleanlinessHorizontalBarChart = renderChart(
                        waterTramsCleanlinessCtx,
                        'bar',
                        {
                            labels: currentChartData.waterTramsCleanliness.labels, // استخدم التسميات من المتحكم
                            datasets: [{
                                label: 'عدد الاستجابات',
                                data: currentChartData.waterTramsCleanliness.datasets[0].data,
                                backgroundColor: currentChartData.waterTramsCleanliness.datasets[0].backgroundColor, // استخدم الألوان من المتحكم
                                borderColor: currentChartData.waterTramsCleanliness.datasets[0].borderColor,
                                borderWidth: 1,
                                borderRadius: 8,
                            }]
                        },
                        {
                            ...commonChartOptions,
                            indexAxis: 'y',
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { display: false },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    anchor: 'end', 
                                    align: 'end', 
                                    formatter: (value) => value,
                                    color: '#ecf0f1', 
                                    textShadowColor: 'rgba(0,0,0,0.7)', 
                                    textShadowBlur: 4
                                }
                            },
                            scales: {
                                x: { ...commonChartOptions.scales.x },
                                y: { ...commonChartOptions.scales.y }
                            }
                        },
                        waterTramsCleanlinessHorizontalBarChart
                    );

                    // Chart 4: Restroom Cleanliness (Doughnut Chart)
                    const restroomCleanlinessCtx = document.getElementById('restroomCleanlinessDoughnutChart').getContext('2d');

                    // Create a mutable copy of the colors array for this chart
                    let restroomDisplayColors = [...currentChartData.restroomCleanlinessColors];

                    // Find the index of 'جيدة جداً' and apply yellow color
                    const veryGoodRestroomIndex = currentChartData.restroomCleanlinessLabels.indexOf('جيدة جداً');
                    if (veryGoodRestroomIndex !== -1) {
                        restroomDisplayColors[veryGoodRestroomIndex] = chartColors.good; // Make 'جيدة جداً' yellow
                    }

                    // Find the index of 'جيد' and apply a distinct color (e.g., acceptable color)
                    const goodRestroomIndex = currentChartData.restroomCleanlinessLabels.indexOf('جيد');
                    if (goodRestroomIndex !== -1) {
                        restroomDisplayColors[goodRestroomIndex] = chartColors.acceptable; // Make 'جيد' orange
                    }

                    restroomCleanlinessDoughnutChart = renderChart(
                        restroomCleanlinessCtx,
                        'doughnut', // Changed to doughnut type
                        {
                            labels: currentChartData.restroomCleanlinessLabels, // استخدم التسميات من المتحكم
                            datasets: [{
                                label: 'مستوى النظافة',
                                data: currentChartData.restroomCleanliness,
                                backgroundColor: restroomDisplayColors, // استخدم الألوان المعدلة الآن
                                borderColor: '#fff', // Border for doughnut segments
                                borderWidth: 2
                            }]
                        },
                        {
                            ...commonChartOptions,
                            cutout: '0%', // Changed to 0% for full pie chart (no inner hole)
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
                                                const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                                const percentage = (context.parsed / total * 100).toFixed(1) + '%';
                                                label += context.parsed + ' (' + percentage + ')';
                                            }
                                            return label;
                                        }
                                    }
                                },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    formatter: (value, context) => {
                                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                        return (value / total * 100).toFixed(1) + '%';
                                    },
                                    align: 'center',
                                    anchor: 'center'
                                }
                            },
                            scales: {} // Removed radar-specific scales
                        },
                        restroomCleanlinessDoughnutChart
                    );

                    // Update center text for Restroom Cleanliness Doughnut Chart
                    const restroomCenterTextElement = document.getElementById('restroomCenterText');
                    if (restroomCenterTextElement) {
                        const restroomStats = currentTableData.find(item => item.indicator === 'نظافة دورات المياه');
                        const restroomPercentage = restroomStats ? restroomStats.avg_satisfaction.toFixed(1) : '0.0';
                        restroomCenterTextElement.innerHTML = `
                            <div style="font-size: 1.5rem; font-weight: bold; color: ${chartColors.excellent};">
                                ${restroomPercentage}%
                            </div>
                            <div style="font-size: 1rem; color: #14ee14ff; margin-top: 5px;">
                                متوسط الرضا
                            </div>
                        `;
                    }

                    // Chart 5: Courtyards and Corridors Cleanliness (Polar Area Chart)
                    const courtyardsCleanlinessCtx = document.getElementById('courtyardsCleanlinessPolarAreaChart').getContext('2d');
                    courtyardsCleanlinessPolarAreaChart = renderChart(
                        courtyardsCleanlinessCtx,
                        'polarArea',
                        {
                            labels: currentChartData.courtyardsCleanlinessLabels, // استخدم التسميات من المتحكم
                            datasets: [{
                                label: 'عدد الاستجابات',
                                data: currentChartData.courtyardsCleanliness,
                                backgroundColor: currentChartData.courtyardsCleanlinessColors.map(color => color.replace('0.7', '0.5')), // استخدم الألوان من المتحكم
                                borderColor: currentChartData.courtyardsCleanlinessColors, // استخدم الألوان من المتحكم
                                borderWidth: 1
                            }]
                        },
                        {
                            ...commonChartOptions,
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: { 
                                    position: 'bottom', 
                                    labels: commonChartOptions.plugins.legend.labels 
                                },
                                datalabels: {
                                    ...commonChartOptions.plugins.datalabels,
                                    formatter: (value, context) => {
                                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                        return (value / total * 100).toFixed(1) + '%';
                                    }
                                }
                            },
                            scales: {
                                r: {
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                                    ticks: { 
                                        backdropColor: 'transparent', 
                                        color: '#ecf0f1' 
                                    }
                                }
                            }
                        },
                        courtyardsCleanlinessPolarAreaChart
                    );

                    // Populate the statistics table and summary section
                    populateStatisticsTable(currentTableData);
                    populateSummarySection(currentSummaryData);

                    // Hide error alert if data fetched successfully
                    chartErrorAlert.style.display = 'none';

                } catch (error) {
                    console.error('Error fetching dashboard data:', error);
                    chartErrorAlert.textContent = 'حدث خطأ أثناء جلب البيانات. يرجى التأكد من تعريف المسار /api/survey-data في Laravel وإعادة المحاولة.';
                    chartErrorAlert.style.display = 'block';
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

                // Populate print table
                const printTableBody = printableReportDiv.querySelector('#printStatisticsTable tbody');
                printTableBody.innerHTML = '';
                // Since currentTableData is an array of objects, iterate directly
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
                        <td>${item.avg_satisfaction}%</td>
                    `;
                });

                // Populate print summary
                populateSummarySection(currentSummaryData, 'printableReport');

                // Destroy existing print chart instances before re-rendering
                const printCharts = [
                    'printSatisfactionChart', 'printHallChart', 'printWaterChart',
                    'printRestroomChart', 'printCourtyardsChart'
                ];
                printCharts.forEach(id => {
                    const canvas = document.getElementById(id);
                    if (canvas && Chart.getChart(canvas)) {
                        Chart.getChart(canvas).destroy();
                    }
                });

                // Render Satisfaction Doughnut Chart for printing
                const printSatisfactionChartCtx = document.getElementById('printSatisfactionChart').getContext('2d');
                new Chart(printSatisfactionChartCtx, {
                    type: 'doughnut',
                    data: {
                        labels: currentChartData.satisfaction.labels,
                        datasets: [{
                            data: currentChartData.satisfaction.datasets[0].data,
                            backgroundColor: currentChartData.satisfaction.datasets[0].backgroundColor, // استخدم الألوان من المتحكم
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
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    color: '#495057',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                } 
                            },
                            datalabels: { 
                                ...printChartOptions(commonChartOptions).plugins.datalabels, 
                                color: '#333',
                                formatter: (value, context) => {
                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    return (value / total * 100).toFixed(1) + '%';
                                },
                                align: 'center',
                                anchor: 'center',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                });

                // Render Hall Cleanliness Bar Chart for printing
                const printHallCleanlinessChartCtx = document.getElementById('printHallChart').getContext('2d');
                new Chart(printHallCleanlinessChartCtx, {
                    type: 'bar',
                    data: {
                        labels: currentChartData.hallCleanliness.labels, // استخدم التسميات من المتحكم
                        datasets: [{
                            data: currentChartData.hallCleanliness.datasets[0].data,
                            backgroundColor: currentChartData.hallCleanliness.datasets[0].backgroundColor, // استخدم الألوان من المتحكم
                            borderColor: currentChartData.hallCleanliness.datasets[0].borderColor,
                            borderWidth: 1,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { display: false },
                            datalabels: { 
                                ...printChartOptions(commonChartOptions).plugins.datalabels, 
                                color: '#333',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        scales: {
                            x: { 
                                ...printChartOptions(commonChartOptions).scales.x,
                                ticks: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            },
                            y: { 
                                ...printChartOptions(commonChartOptions).scales.y,
                                ticks: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            }
                        }
                    }
                });

                // Render Water Trams Cleanliness Horizontal Bar Chart for printing
                const printWaterTramsCleanlinessChartCtx = document.getElementById('printWaterChart').getContext('2d');
                new Chart(printWaterTramsCleanlinessChartCtx, {
                    type: 'bar',
                    data: {
                        labels: currentChartData.waterTramsCleanliness.labels, // استخدم التسميات من المتحكم
                        datasets: [{
                            data: currentChartData.waterTramsCleanliness.datasets[0].data,
                            backgroundColor: currentChartData.waterTramsCleanliness.datasets[0].backgroundColor, // استخدم الألوان من المتحكم
                            borderColor: currentChartData.waterTramsCleanliness.datasets[0].borderColor,
                            borderWidth: 1,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        indexAxis: 'y',
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { display: false },
                            datalabels: { 
                                ...printChartOptions(commonChartOptions).plugins.datalabels, 
                                color: '#333',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        scales: {
                            x: { 
                                ...printChartOptions(commonChartOptions).scales.x,
                                ticks: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            },
                            y: { 
                                ...printChartOptions(commonChartOptions).scales.y,
                                ticks: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            }
                        }
                    }
                });

                // Render Restroom Cleanliness Doughnut Chart for printing
                const printRestroomCleanlinessChartCtx = document.getElementById('printRestroomChart').getContext('2d');

                // Create a mutable copy of the colors array for this chart
                let printRestroomColors = [...currentChartData.restroomCleanlinessColors];

                // Apply the same color override for the print chart
                const printVeryGoodRestroomIndex = currentChartData.restroomCleanlinessLabels.indexOf('جيدة جداً');
                if (printVeryGoodRestroomIndex !== -1) {
                    printRestroomColors[printVeryGoodRestroomIndex] = chartColors.good; // Make 'جيدة جداً' yellow
                }

                const printGoodRestroomIndex = currentChartData.restroomCleanlinessLabels.indexOf('جيد');
                if (printGoodRestroomIndex !== -1) {
                    printRestroomColors[printGoodRestroomIndex] = chartColors.acceptable; // Make 'جيد' orange
                }

                new Chart(printRestroomCleanlinessChartCtx, {
                    type: 'doughnut', // Changed to doughnut type
                    data: {
                        labels: currentChartData.restroomCleanlinessLabels, // استخدم التسميات من المتحكم
                        datasets: [{
                            label: 'مستوى النظافة',
                            data: currentChartData.restroomCleanliness,
                            backgroundColor: printRestroomColors, // استخدم الألوان المعدلة للطباعة
                            borderColor: '#fff', // Border for doughnut segments
                            borderWidth: 2
                        }]
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        cutout: '0%', // Changed to 0% for full pie chart
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    color: '#495057',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                } 
                            },
                            datalabels: { 
                                ...printChartOptions(commonChartOptions).plugins.datalabels, 
                                color: '#333',
                                formatter: (value, context) => {
                                    const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    return (value / total * 100).toFixed(1) + '%';
                                },
                                align: 'center',
                                anchor: 'center',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        scales: {} // Removed radar-specific scales
                    }
                });

                // Render Courtyards and Corridors Cleanliness Polar Area Chart for printing
                const printCourtyardsCleanlinessChartCtx = document.getElementById('printCourtyardsChart').getContext('2d');
                new Chart(printCourtyardsCleanlinessChartCtx, {
                    type: 'polarArea',
                    data: {
                        labels: currentChartData.courtyardsCleanlinessLabels, // استخدم التسميات من المتحكم
                        datasets: [{
                            data: currentChartData.courtyardsCleanliness,
                            backgroundColor: currentChartData.courtyardsCleanlinessColors.map(color => color.replace('0.7', '0.5')), // استخدم الألوان من المتحكم
                            borderColor: currentChartData.courtyardsCleanlinessColors, // استخدم الألوان من المتحكم
                            borderWidth: 1
                        }]
                    },
                    options: {
                        ...printChartOptions(commonChartOptions),
                        plugins: {
                            ...printChartOptions(commonChartOptions).plugins,
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    color: '#495057',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                } 
                            },
                            datalabels: { 
                                ...printChartOptions(commonChartOptions).plugins.datalabels, 
                                color: '#333',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        scales: {
                            r: {
                                grid: { color: 'rgba(0,0,0,0.1)' },
                                ticks: { 
                                    backdropColor: 'transparent', 
                                    color: '#333',
                                    font: {
                                        size: 10,
                                        weight: 'bold'
                                    }
                                }
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
                        scale: 3, // Increased scale for better quality
                        logging: true,
                        useCORS: true,
                        allowTaint: true,
                        scrollY: -window.scrollY,
                        windowWidth: document.documentElement.offsetWidth,
                        windowHeight: document.documentElement.offsetHeight
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const imgWidth = docWidth - (2 * margin);
                        const imgHeight = (canvas.height * imgWidth) / canvas.width;
                        let heightLeft = imgHeight;
                        let position = margin;

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
                        printableReportDiv.style.display = 'none';
                    });
                }, 2000); // Increased delay to ensure charts are fully rendered
            });
        });
    </script>
@endsection
