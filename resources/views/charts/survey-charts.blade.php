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
                    {{-- يمكن إضافة أزرار إضافية هنا إذا لزم الأمر --}}
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

                <div class="row mt-2">
                    {{-- المخطط 5: تقييم السرعة والدقة (خطي - Line Chart) --}}
                    <div class="col-md-12 mb-4">
                        <div class="card card-outline card-info shadow-lg border-info rounded-lg chart-card">
                            <div class="card-header border-0 bg-gradient-info-dark">
                                <h3 class="card-title font-weight-bold text-white">
                                    <i class="fas fa-tachometer-alt mr-2"></i> تقييم السرعة والدقة
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="height: 450px;">
                                    <canvas id="speedAccuracyChart"></canvas>
                                </div>
                            </div>
                        </div>
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
            background-color: #2c3e50; /* خلفية داكنة رئيسية */
            color: #ecf0f1;
        }
        .bg-dark-custom {
            background-color: #34495e; /* لون أغمق قليلاً لخلفية الكارد بودي */
        }
        .card {
            border-radius: 15px; /* حواف أكثر استدارة للكروت */
            overflow: hidden; /* لضمان أن الحدود المستديرة تطبق بشكل صحيح */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px); /* تأثير رفع بسيط عند التحويم */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4); /* ظل أكبر عند التحويم */
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
        .card-outline.card-info { border-top: 3px solid #17a2b8; }
        .card-outline.card-primary { border-top: 3px solid #007bff; }
        .card-outline.card-success { border-top: 3px solid #28a745; }
        .card-outline.card-warning { border-top: 3px solid #ffc107; }
        .card-outline.card-danger { border-top: 3px solid #dc3545; }

        /* Custom gradients for card headers */
        .bg-gradient-primary-dark { background: linear-gradient(to right, #007bff, #0056b3); }
        .bg-gradient-success-dark { background: linear-gradient(to right, #28a745, #1e7e34); }
        .bg-gradient-warning-dark { background: linear-gradient(to right, #ffc107, #d39e00); }
        .bg-gradient-danger-dark { background: linear-gradient(to right, #dc3545, #b21f2d); }
        .bg-gradient-info-dark { background: linear-gradient(to right, #17a2b8, #117a8b); }
        .bg-gradient-dark { background: linear-gradient(to right, #343a40, #23272b); }


        .chart-container {
            position: relative;
            height: 380px; /* زيادة الارتفاع لمساحة أفضل */
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px; /* مسافة داخلية للمخطط */
        }
        canvas {
            background-color: rgba(255, 255, 255, 0.03); /* خلفية خفيفة جداً للمخطط */
            border-radius: 12px; /* حواف مستديرة للمخطط */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); /* ظل خفيف وواضح */
            padding: 15px; /* مسافة داخلية */
            width: 100% !important; /* تأكد من عرض كامل ضمن الحاوية */
            height: 100% !important; /* تأكد من ارتفاع كامل ضمن الحاوية */
        }
        .card-body .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ecf0f1;
            border-radius: 8px; /* حواف مستديرة لحقول الإدخال */
            padding: 0.75rem 1rem;
        }
        .card-body .form-control::placeholder {
            color: #bdc3c7;
        }
        .card-body .form-control:focus {
            background-color: rgba(255, 255, 255, 0.18);
            border-color: #6edff6; /* لون تحديد أكثر حيوية */
            box-shadow: 0 0 0 0.25rem rgba(110, 223, 246, 0.35); /* ظل تحديد ناعم */
        }
        .form-control-dark { /* لتمييز حقول التاريخ */
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
            font-size: 2rem; /* حجم أكبر للنص المركزي */
            font-weight: bold;
            color: #e0e0e0; /* لون أفتح قليلاً */
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

        /* Animations for alerts */
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

        /* Adjust chart legend and tooltip for dark theme */
        .chartjs-tooltip {
            background-color: rgba(0, 0, 0, 0.9) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 8px !important;
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
        }
        .chartjs-tooltip-header-title {
            color: #f1c40f !important; /* لون أصفر مميز للعنوان */
        }
        .chartjs-tooltip-body {
            color: #ecf0f1 !important;
        }
        .chartjs-tooltip-item-label {
            color: #ecf0f1 !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        // Register the datalabels plugin globally
        Chart.register(ChartDataLabels);

        let satisfactionPieChart = null;
        let hallCleanlinessChart = null;
        let waterTramsCleanlinessChart = null;
        let facilitiesCleanlinessChart = null;
        let speedAccuracyChart = null; // مخطط جديد

        document.addEventListener('DOMContentLoaded', function () {
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            const applyFiltersButton = document.getElementById('applyFilters');

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
                // Base hues for vibrant and distinct colors. More options for diverse charts.
                const baseHues = [0, 60, 120, 180, 240, 300, 30, 90, 150, 210, 270, 330, 10, 70, 130, 190, 250, 310];
                for (let i = 0; i < numColors; i++) {
                    const hue = baseHues[i % baseHues.length];
                    // Introduce slight variations for more distinct colors if needed
                    const saturation = Math.min(100, 70 + saturationOffset + (i * 2 % 10)); // Vary slightly
                    const lightness = Math.min(100, 60 + lightnessOffset + (i * 3 % 10)); // Vary slightly
                    colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
                }
                return colors;
            };

            // Function to fetch and render charts
            function fetchAndRenderCharts() {
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;
                const queryParams = `from_date=${fromDate}&to_date=${toDate}`;

                // Common chart options for Arabic fonts and dark theme
                const commonChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1500, // Smoother, slightly slower animation
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

                // --- Chart 1: Satisfaction Pie Chart (Doughnut) ---
                fetch(`{{ route('charts.surveys.pie-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('satisfactionPieChart').getContext('2d');
                        if (satisfactionPieChart) {
                            satisfactionPieChart.destroy();
                        }
                        satisfactionPieChart = new Chart(ctx, {
                            type: 'doughnut', // تغيير هنا من pie إلى doughnut لإضافة بعض التنوع
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    data: data.datasets[0].data,
                                    backgroundColor: generateDynamicColors(data.labels.length, 5), // اختلاف بسيط في الألوان
                                    hoverOffset: 15,
                                    borderColor: 'rgba(255, 255, 255, 0.3)',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                ...commonChartOptions,
                                cutout: '70%', // حجم الفتحة المركزية للمخطط الحلقي
                                plugins: {
                                    ...commonChartOptions.plugins,
                                    legend: {
                                        position: 'right',
                                        labels: commonChartOptions.plugins.legend.labels
                                    },
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
                                        // display: 'auto', // عرض التسميات تلقائيًا لتجنب التداخل
                                        align: 'center',
                                        anchor: 'center'
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching satisfaction doughnut chart data:', error));


                // --- Chart 2: Hall Cleanliness Doughnut Chart (with Center Text) ---
                fetch(`{{ route('charts.surveys.hall-cleanliness-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('hallCleanlinessChart').getContext('2d');
                        const centerTextElement = document.getElementById('hallCleanlinessCenterText');

                        if (hallCleanlinessChart) {
                            hallCleanlinessChart.destroy();
                        }

                        const totalCount = data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                        centerTextElement.innerHTML = `<span style="font-size:1.5rem; display:block; margin-bottom: 5px;">إجمالي التقييمات</span>${totalCount}`; // نص أكثر احترافية

                        hallCleanlinessChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    data: data.datasets[0].data,
                                    backgroundColor: generateDynamicColors(data.labels.length, -5, 5), // اختلاف بسيط في الألوان
                                    hoverOffset: 18,
                                    borderColor: 'rgba(255, 255, 255, 0.4)',
                                    borderWidth: 3
                                }]
                            },
                            options: {
                                ...commonChartOptions,
                                cutout: '65%',
                                plugins: {
                                    ...commonChartOptions.plugins,
                                    legend: {
                                        position: 'bottom',
                                        labels: commonChartOptions.plugins.legend.labels
                                    },
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
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching hall cleanliness chart data:', error));

                // --- Chart 3: Water Trams Cleanliness Horizontal Bar Chart ---
                fetch(`{{ route('charts.surveys.water-trams-cleanliness-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('waterTramsCleanlinessChart').getContext('2d');
                        if (waterTramsCleanlinessChart) {
                            waterTramsCleanlinessChart.destroy();
                        }

                        waterTramsCleanlinessChart = new Chart(ctx, {
                            type: 'bar', // النوع الأساسي هو 'bar'
                            data: {
                                labels: data.labels,
                                datasets: data.datasets.map((dataset, index) => ({
                                    ...dataset,
                                    backgroundColor: generateDynamicColors(data.labels.length, 0, -5), // ألوان ديناميكية
                                    borderColor: 'rgba(255, 255, 255, 0.2)',
                                    borderWidth: 1,
                                    borderRadius: 8, // حواف أكثر استدارة
                                    hoverBackgroundColor: (context) => {
                                        const colors = generateDynamicColors(data.labels.length, 0, -10); // لون أغمق عند التحويم
                                        return colors[context.dataIndex % colors.length];
                                    },
                                    barThickness: 20, // سمك الشريط
                                }))
                            },
                            options: {
                                ...commonChartOptions,
                                indexAxis: 'y', // جعل المخطط شريطيًا أفقيًا
                                plugins: {
                                    ...commonChartOptions.plugins,
                                    legend: { display: false },
                                    tooltip: {
                                        ...commonChartOptions.plugins.tooltip,
                                        displayColors: false,
                                    },
                                    datalabels: {
                                        ...commonChartOptions.plugins.datalabels,
                                        anchor: 'end',
                                        align: 'end', // عرض القيمة في نهاية الشريط
                                        offset: 4,
                                        formatter: (value) => value + '%', // إضافة علامة النسبة المئوية
                                        color: '#ecf0f1', // لون أبيض للقيمة
                                        textShadowColor: 'rgba(0,0,0,0.7)',
                                        textShadowBlur: 4
                                    }
                                },
                                scales: {
                                    x: {
                                        ...commonChartOptions.scales.x,
                                        title: {
                                            display: true,
                                            text: 'مستوى الرضا (%)',
                                            color: '#bdc3c7',
                                            font: { family: 'Cairo', size: 12, weight: 'bold' }
                                        },
                                        max: 100, // يمكن تحديد الحد الأقصى للمحور
                                        min: 0,
                                        ticks: {
                                            callback: function(value) { return value + '%'; } // إضافة % للقيم
                                        }
                                    },
                                    y: {
                                        ...commonChartOptions.scales.y,
                                        grid: { display: false } // إخفاء خطوط الشبكة الأفقية
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching water trams cleanliness chart data:', error));

                // --- Chart 4: Facilities Cleanliness Vertical Bar Chart ---
                fetch(`{{ route('charts.surveys.facilities-cleanliness-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('facilitiesCleanlinessChart').getContext('2d');
                        if (facilitiesCleanlinessChart) {
                            facilitiesCleanlinessChart.destroy();
                        }

                        facilitiesCleanlinessChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: data.datasets.map((dataset, index) => ({
                                    ...dataset,
                                    backgroundColor: generateDynamicColors(data.labels.length, 10, -5), // ألوان ديناميكية
                                    borderColor: 'rgba(255, 255, 255, 0.2)',
                                    borderWidth: 1,
                                    borderRadius: 8,
                                    hoverBackgroundColor: (context) => {
                                        const colors = generateDynamicColors(data.labels.length, 10, -10);
                                        return colors[context.dataIndex % colors.length];
                                    },
                                    barThickness: 20
                                }))
                            },
                            options: {
                                ...commonChartOptions,
                                plugins: {
                                    ...commonChartOptions.plugins,
                                    legend: { display: false },
                                    tooltip: {
                                        ...commonChartOptions.plugins.tooltip,
                                        displayColors: false,
                                    },
                                    datalabels: {
                                        ...commonChartOptions.plugins.datalabels,
                                        anchor: 'end',
                                        align: 'top',
                                        offset: 4,
                                        formatter: (value) => value + '%',
                                        color: '#ecf0f1',
                                        textShadowColor: 'rgba(0,0,0,0.7)',
                                        textShadowBlur: 4
                                    }
                                },
                                scales: {
                                    x: {
                                        ...commonChartOptions.scales.x,
                                        title: {
                                            display: true,
                                            text: 'مستوى الرضا (%)',
                                            color: '#bdc3c7',
                                            font: { family: 'Cairo', size: 12, weight: 'bold' }
                                        }
                                    },
                                    y: {
                                        ...commonChartOptions.scales.y,
                                        max: 100,
                                        min: 0,
                                        ticks: {
                                            callback: function(value) { return value + '%'; }
                                        },
                                        grid: { display: true }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching facilities cleanliness chart data:', error));

                // --- Chart 5: Speed and Accuracy Line Chart ---
                fetch(`{{ route('charts.surveys.speed-accuracy-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('speedAccuracyChart').getContext('2d');
                        if (speedAccuracyChart) {
                            speedAccuracyChart.destroy();
                        }
                        speedAccuracyChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: data.datasets.map((dataset, index) => ({
                                    ...dataset,
                                    borderColor: generateDynamicColors(data.datasets.length)[index],
                                    backgroundColor: generateDynamicColors(data.datasets.length, 0, 10)[index].replace(')', ', 0.2)'), // lighter fill
                                    tension: 0.4, // Smooth the line
                                    pointRadius: 6,
                                    pointBackgroundColor: generateDynamicColors(data.datasets.length)[index],
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    fill: true, // Fill area under the line
                                }))
                            },
                            options: {
                                ...commonChartOptions,
                                plugins: {
                                    ...commonChartOptions.plugins,
                                    datalabels: {
                                        ...commonChartOptions.plugins.datalabels,
                                        formatter: (value) => value.toFixed(1), // Show value with one decimal
                                        display: 'auto',
                                        align: 'end',
                                        offset: 8
                                    },
                                    legend: {
                                        position: 'top',
                                        labels: commonChartOptions.plugins.legend.labels
                                    }
                                },
                                scales: {
                                    x: {
                                        ...commonChartOptions.scales.x,
                                        title: {
                                            display: true,
                                            text: 'التاريخ',
                                            color: '#bdc3c7',
                                            font: { family: 'Cairo', size: 12, weight: 'bold' }
                                        }
                                    },
                                    y: {
                                        ...commonChartOptions.scales.y,
                                        title: {
                                            display: true,
                                            text: 'التقييم',
                                            color: '#bdc3c7',
                                            font: { family: 'Cairo', size: 12, weight: 'bold' }
                                        },
                                        min: 0,
                                        max: 100 // يمكن تعديله حسب مقياس التقييم
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching speed and accuracy chart data:', error));

            } // End of fetchAndRenderCharts function

            // Initial load of charts
            fetchAndRenderCharts();

            // Apply filters button click handler
            applyFiltersButton.addEventListener('click', fetchAndRenderCharts);

            // You might want to also trigger on date input change for responsiveness
            fromDateInput.addEventListener('change', fetchAndRenderCharts);
            toDateInput.addEventListener('change', fetchAndRenderCharts);
        });
    </script>
@endsection