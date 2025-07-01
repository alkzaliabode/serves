@extends('layouts.adminlte')

@section('title', 'إحصائيات استبيان رضا الزائرين')

@section('page_title', 'إحصائيات استبيان رضا الزائرين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">إحصائيات الاستبيانات</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-chart-bar mr-2"></i> إحصائيات استبيان رضا الزائرين
                </h3>
                <div class="card-tools">
                    {{-- يمكن إضافة أزرار إضافية هنا إذا لزم الأمر --}}
                </div>
            </div>
            <div class="card-body p-4">
                {{-- رسائل الفلاش --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- فلاتر التاريخ --}}
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-gradient-dark shadow-sm">
                            <div class="card-header border-0">
                                <h5 class="card-title text-white"><i class="fas fa-filter mr-2"></i> تصفية البيانات حسب التاريخ</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 mb-3">
                                        <label for="from_date" class="text-white">من تاريخ:</label>
                                        <input type="date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="to_date" class="text-white">إلى تاريخ:</label>
                                        <input type="date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end mb-3">
                                        <button id="applyFilters" class="btn btn-primary btn-block"><i class="fas fa-check-circle mr-1"></i> تطبيق</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- المخطط 1: توزيع الرضا العام (دائري) --}}
                    <div class="col-md-6">
                        <div class="card card-outline card-primary shadow-lg">
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bold text-primary">
                                    <i class="fas fa-chart-pie mr-2"></i> توزيع الرضا العام
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:350px; width:100%">
                                    <canvas id="satisfactionPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- المخطط 2: نظافة القاعات (حلقي - Doughnut Chart) --}}
                    <div class="col-md-6">
                        <div class="card card-outline card-success shadow-lg">
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bold text-success">
                                    <i class="fas fa-house-user mr-2"></i> نظافة القاعات
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:350px; width:100%">
                                    <canvas id="hallCleanlinessChart"></canvas>
                                    <div id="hallCleanlinessCenterText" class="chart-center-text"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    {{-- المخطط 3: نظافة ترامز الماء (شريطي - Bar Chart) --}}
                    <div class="col-md-6">
                        <div class="card card-outline card-warning shadow-lg">
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bold text-warning">
                                    <i class="fas fa-water mr-2"></i> نظافة ترامز الماء
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:350px; width:100%">
                                    <canvas id="waterTramsCleanlinessChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- المخطط 4: نظافة المرافق (دورات المياه والساحات - شريطي) --}}
                    <div class="col-md-6">
                        <div class="card card-outline card-danger shadow-lg">
                            <div class="card-header border-0">
                                <h3 class="card-title font-weight-bold text-danger">
                                    <i class="fas fa-toilet mr-2"></i> نظافة المرافق العامة
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="position: relative; height:350px; width:100%">
                                    <canvas id="facilitiesCleanlinessChart"></canvas>
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
    <style>
        .chart-container {
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* مهم للنص المركزي في الدونات */
        }
        canvas {
            background-color: rgba(255, 255, 255, 0.05); /* خلفية خفيفة جداً للمخطط */
            border-radius: 15px; /* حواف مستديرة للمخطط */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* ظل خفيف */
            padding: 20px; /* مسافة داخلية */
            width: 100% !important; /* تأكد من عرض كامل ضمن الحاوية */
            height: 100% !important; /* تأكد من ارتفاع كامل ضمن الحاوية */
        }
        .card-body .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ecf0f1;
        }
        .card-body .form-control::placeholder {
            color: #bdc3c7;
        }
        .card-body .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: #72efdd;
            box-shadow: 0 0 0 0.25rem rgba(114, 239, 221, 0.25);
        }
        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.8rem;
            font-weight: bold;
            color: #ecf0f1;
            text-align: center;
            pointer-events: none; /* لا تتفاعل مع أحداث الماوس */
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
        }
    </style>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Chart.js Datalabels Plugin (for labels on bars/doughnut) -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        // Register the datalabels plugin globally
        Chart.register(ChartDataLabels);

        let satisfactionPieChart = null;
        let hallCleanlinessChart = null;
        let waterTramsCleanlinessChart = null;
        let facilitiesCleanlinessChart = null;

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

            // Function to generate distinct HSL colors
            const generateDynamicColors = (numColors) => {
                const colors = [];
                for (let i = 0; i < numColors; i++) {
                    const hue = (i * 137.508) % 360; // Golden angle approximation for distinct colors
                    colors.push(`hsl(${hue}, 70%, 60%)`);
                }
                return colors;
            };

            // Function to generate gradient colors for bars
            const getGradient = (ctx, chartArea, colors) => {
                if (!chartArea) {
                    // This can happen if the chart is not yet rendered
                    return colors[0];
                }
                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                gradient.addColorStop(0, colors[0]);
                gradient.addColorStop(1, colors[1]);
                return gradient;
            };

            // Function to fetch and render charts
            function fetchAndRenderCharts() {
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;
                const queryParams = `from_date=${fromDate}&to_date=${toDate}`;

                // --- Chart 1: Satisfaction Pie Chart ---
                fetch(`{{ route('charts.surveys.pie-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('satisfactionPieChart').getContext('2d');
                        if (satisfactionPieChart) {
                            satisfactionPieChart.destroy();
                        }
                        satisfactionPieChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    data: data.datasets[0].data,
                                    backgroundColor: generateDynamicColors(data.labels.length),
                                    hoverOffset: 10,
                                    borderColor: 'rgba(255, 255, 255, 0.2)',
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { animateScale: true, animateRotate: true },
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: { color: '#ecf0f1', font: { size: 12, family: 'Cairo, Noto Sans Arabic, sans-serif' } }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                        titleFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 14 },
                                        bodyFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 12 },
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
                                    datalabels: { // Data labels for pie chart
                                        color: '#fff',
                                        font: {
                                            size: 14,
                                            weight: 'bold',
                                            family: 'Cairo, Noto Sans Arabic, sans-serif'
                                        },
                                        formatter: (value, context) => {
                                            const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                            const percentage = (value / total * 100).toFixed(1) + '%';
                                            return percentage;
                                        },
                                        textShadowBlur: 5,
                                        textShadowColor: 'rgba(0,0,0,0.8)'
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching satisfaction pie chart data:', error));

                // --- Chart 2: Hall Cleanliness Doughnut Chart ---
                fetch(`{{ route('charts.surveys.hall-cleanliness-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('hallCleanlinessChart').getContext('2d');
                        const centerTextElement = document.getElementById('hallCleanlinessCenterText');

                        if (hallCleanlinessChart) {
                            hallCleanlinessChart.destroy();
                        }

                        const totalCount = data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                        centerTextElement.innerHTML = `إجمالي: <br>${totalCount}`;

                        hallCleanlinessChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    data: data.datasets[0].data,
                                    backgroundColor: generateDynamicColors(data.labels.length),
                                    hoverOffset: 15, // Increased hover offset
                                    borderColor: 'rgba(255, 255, 255, 0.3)',
                                    borderWidth: 3
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%', // Adjust cutout for doughnut
                                animation: { animateScale: true, animateRotate: true },
                                plugins: {
                                    legend: {
                                        position: 'bottom', // Position legend at the bottom
                                        labels: { color: '#ecf0f1', font: { size: 12, family: 'Cairo, Noto Sans Arabic, sans-serif' } }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                        titleFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 14 },
                                        bodyFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 12 },
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
                                    datalabels: { // Data labels for doughnut chart
                                        color: '#fff',
                                        font: {
                                            size: 14,
                                            weight: 'bold',
                                            family: 'Cairo, Noto Sans Arabic, sans-serif'
                                        },
                                        formatter: (value, context) => {
                                            const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                            const percentage = (value / total * 100).toFixed(1) + '%';
                                            return percentage;
                                        },
                                        textShadowBlur: 5,
                                        textShadowColor: 'rgba(0,0,0,0.8)'
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching hall cleanliness chart data:', error));

                // --- Chart 3: Water Trams Cleanliness Bar Chart ---
                fetch(`{{ route('charts.surveys.water-trams-cleanliness-data') }}?${queryParams}`)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('waterTramsCleanlinessChart').getContext('2d');
                        if (waterTramsCleanlinessChart) {
                            waterTramsCleanlinessChart.destroy();
                        }

                        // Define gradient colors for water trams
                        const gradientColors = [
                            ['#4CAF50', '#8BC34A'], // Excellent to Very Good (Greenish)
                            ['#2196F3', '#03A9F4'], // Good (Blueish)
                            ['#FFC107', '#FF9800'], // Acceptable (Orangeish)
                            ['#F44336', '#E53935']  // Poor (Reddish)
                        ];

                        waterTramsCleanlinessChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: data.datasets.map((dataset, index) => ({
                                    ...dataset,
                                    backgroundColor: (context) => {
                                        const chart = context.chart;
                                        const { ctx, chartArea } = chart;
                                        if (!chartArea) return gradientColors[index % gradientColors.length][0];
                                        return getGradient(ctx, chartArea, gradientColors[index % gradientColors.length]);
                                    },
                                    borderColor: 'rgba(255, 255, 255, 0.2)',
                                    borderWidth: 1,
                                    borderRadius: 8, // More rounded bars
                                    hoverBackgroundColor: (context) => {
                                        const chart = context.chart;
                                        const { ctx, chartArea } = chart;
                                        if (!chartArea) return gradientColors[index % gradientColors.length][1];
                                        return getGradient(ctx, chartArea, [gradientColors[index % gradientColors.length][1], gradientColors[index % gradientColors.length][0]]);
                                    },
                                }))
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { duration: 1200, easing: 'easeOutExpo' }, // Slower, smoother animation
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        titleFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 14, weight: 'bold' },
                                        bodyFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 12 },
                                        borderColor: 'rgba(255, 255, 255, 0.3)',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                        displayColors: false, // Hide color box in tooltip
                                    },
                                    datalabels: { // Data labels on top of bars
                                        color: '#fff',
                                        anchor: 'end',
                                        align: 'top',
                                        offset: 5,
                                        font: {
                                            size: 12,
                                            weight: 'bold',
                                            family: 'Cairo, Noto Sans Arabic, sans-serif'
                                        },
                                        formatter: (value) => value,
                                        textShadowBlur: 3,
                                        textShadowColor: 'rgba(0,0,0,0.6)'
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: { color: '#ecf0f1', font: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 10 } },
                                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: { color: '#ecf0f1', font: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 10 } },
                                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching water trams cleanliness chart data:', error));

                // --- Chart 4: Facilities Cleanliness Bar Chart (Toilet & Yard) ---
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
                                datasets: data.datasets.map(dataset => ({
                                    ...dataset,
                                    borderColor: 'rgba(255, 255, 255, 0.2)',
                                    borderWidth: 1,
                                    borderRadius: 8,
                                    hoverBackgroundColor: dataset.backgroundColor.replace('0.7', '0.9') // Darken on hover
                                }))
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { duration: 1200, easing: 'easeOutExpo' },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: { color: '#ecf0f1', font: { size: 12, family: 'Cairo, Noto Sans Arabic, sans-serif' } }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        titleFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 14, weight: 'bold' },
                                        bodyFont: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 12 },
                                        borderColor: 'rgba(255, 255, 255, 0.3)',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                    },
                                    datalabels: { // Data labels on top of bars
                                        color: '#fff',
                                        anchor: 'end',
                                        align: 'top',
                                        offset: 5,
                                        font: {
                                            size: 12,
                                            weight: 'bold',
                                            family: 'Cairo, Noto Sans Arabic, sans-serif'
                                        },
                                        formatter: (value) => value,
                                        textShadowBlur: 3,
                                        textShadowColor: 'rgba(0,0,0,0.6)'
                                    }
                                }
                                ,
                                scales: {
                                    x: { ticks: { color: '#ecf0f1', font: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 10 } }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                                    y: { beginAtZero: true, ticks: { color: '#ecf0f1', font: { family: 'Cairo, Noto Sans Arabic, sans-serif', size: 10 } }, grid: { color: 'rgba(255, 255, 255, 0.1)' } }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching facilities cleanliness chart data:', error));
            }

            // Initial render on page load
            fetchAndRenderCharts();

            // Event listener for apply filters button
            applyFiltersButton.addEventListener('click', fetchAndRenderCharts);
        });
    </script>
@endsection
