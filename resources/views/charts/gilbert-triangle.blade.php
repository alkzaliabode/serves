@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'مخطط الأداء (مثلث جلبرت)')

@section('page_title', 'مخطط الأداء (مثلث جلبرت)')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('charts.gilbert-triangle.index') }}">التحليلات</a></li>
    <li class="breadcrumb-item active">مخطط جلبرت</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-project-diagram mr-2"></i> تحليل الأداء باستخدام مثلث جلبرت
                </h3>
                <div class="card-tools">
                    {{-- يمكنك إضافة أزرار هنا مثل تحديث البيانات يدوياً أو اختيار نطاق زمني --}}
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

                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="text-white mb-3 animated-heading">شرح "مثلث الأداء" (نموذج جون غيلبرت 1980)</h4>
                        <p class="text-light animated-text">
                            يُعرف هذا النموذج كإطار لتقييم الأداء عبر ثلاث محاور أساسية: الأهداف، الموارد، والنتائج.
                            يهدف إلى توفير رؤية شاملة لمدى كفاءة وفاعلية المؤسسة في تحقيق أهدافها.
                        </p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box bg-gradient-primary animated-card">
                                    <span class="info-box-icon"><i class="fas fa-bullseye"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الأهداف (Objectifs)</span>
                                        <span class="info-box-number">تمثل ما تريد المؤسسة تحقيقه من نتائج، وفق استراتيجيتها أو خطة المشروع.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-gradient-success animated-card">
                                    <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">الموارد (Moyens/Ressources)</span>
                                        <span class="info-box-number">تشمل كل ما يُستخدم لتحقيق الأهداف: العمالة، المال، المواد، الوقت…</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-gradient-info animated-card">
                                    <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">النتائج (Résultats)</span>
                                        <span class="info-box-number">تمثل ما تحقق فعليًا، سواء كان منتجًا أو خدمة أو إنجازًا محددًا.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-white mt-4 mb-3 animated-heading">العلاقات الثلاث الأساسية</h4>
                        <p class="text-light animated-text">
                            يقوم نموذج جلبرت بتحليل ثلاث علاقات رئيسية بين هذه المحاور لتقييم الأداء بشكل متكامل:
                        </p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="small-box bg-primary animated-card">
                                    <div class="inner">
                                        <h3>الفعالية <sup style="font-size: 20px">%</sup></h3>
                                        <p>علاقة بين النتائج والأهداف: تقيس مدى تحقيق الأهداف.</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-success animated-card">
                                    <div class="inner">
                                        <h3>الكفاءة <sup style="font-size: 20px">%</sup></h3>
                                        <p>علاقة بين النتائج والموارد: تقيس استخدام الموارد بأقل تكلفة.</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-info animated-card">
                                    <div class="inner">
                                        <h3>الملاءمة <sup style="font-size: 20px">%</sup></h3>
                                        <p>علاقة بين الموارد والأهداف: تقيس مدى ملاءمة الموارد للوصول للأهداف.</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-person-add"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-white-50">

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-7">
                        <h4 class="text-white text-center mb-3 animated-heading">مخطط الأداء اليومي لمثلث جلبرت</h4>
                        <div class="chart-container" style="position: relative; height:450px; width:100%">
                            <canvas id="gilbertTriangleChart"></canvas>
                            <div id="chart-loading-spinner" class="loading-spinner" style="display: none;">
                                <div class="spinner-border text-info" role="status">
                                    <span class="sr-only">جاري التحميل...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 justify-content-center" x-data="{
                    todayOverallPerformance: 0,
                    yesterdayOverallPerformance: 0,
                    todayQualityRating: 0,
                    yesterdayQualityRating: 0,
                    loadingSummary: true,
                }" x-init="
                    fetch('/charts/gilbert-triangle-data')
                        .then(response => response.json())
                        .then(data => {
                            todayOverallPerformance = data.todayOverallPerformance;
                            yesterdayOverallPerformance = data.yesterdayOverallPerformance;
                            todayQualityRating = data.todayQualityRating;
                            yesterdayQualityRating = data.yesterdayQualityRating;
                            loadingSummary = false;
                        })
                        .catch(error => {
                            console.error('Error fetching summary data:', error);
                            loadingSummary = false;
                        });
                ">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-dark animated-card">
                            <div class="card-header border-0">
                                <h5 class="card-title text-white">الأداء الإجمالي اليومي</h5>
                            </div>
                            <div class="card-body">
                                <template x-if="loadingSummary">
                                    <div class="text-center">
                                        <div class="spinner-border text-info" role="status">
                                            <span class="sr-only">جاري التحميل...</span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!loadingSummary">
                                    <h1 class="text-white text-center">
                                        <span x-text="todayOverallPerformance"></span><small class="text-muted">%</small>
                                    </h1>
                                    <p class="text-white text-center">
                                        أمس: <span x-text="yesterdayOverallPerformance"></span>%
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-dark animated-card">
                            <div class="card-header border-0">
                                <h5 class="card-title text-white">متوسط تقييم الجودة اليومي</h5>
                            </div>
                            <div class="card-body">
                                <template x-if="loadingSummary">
                                    <div class="text-center">
                                        <div class="spinner-border text-info" role="status">
                                            <span class="sr-only">جاري التحميل...</span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!loadingSummary">
                                    <h1 class="text-white text-center">
                                        <span x-text="todayQualityRating"></span><small class="text-muted"> / 5</small>
                                    </h1>
                                    <p class="text-white text-center">
                                        أمس: <span x-text="yesterdayQualityRating"></span> / 5
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('styles')
    {{-- أنماط CSS إضافية للمخطط --}}
    <style>
        /* Accent Color for consistency */
        :root {
            --accent-color: #00eaff; /* Light blue/cyan for interactive elements and emphasis */
            --primary-gradient-start: rgba(52, 152, 219, 0.75);
            --primary-gradient-end: rgba(142, 68, 173, 0.75);
            --success-gradient-start: rgba(46, 204, 113, 0.75);
            --success-gradient-end: rgba(241, 196, 15, 0.75);
            --info-gradient-start: rgba(10, 207, 131, 0.75);
            --info-gradient-end: rgba(0, 172, 193, 0.75);
        }

        /* General Body and Card Styling for Glassmorphism */
        body {
            background-image: url('{{ config('app.background_image_url') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            background-color: #2c3e50;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Stronger overlay for better text contrast */
            z-index: -1;
        }

        .card {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(12px) !important; /* Stronger blur for glass effect */
            border-radius: 1.5rem !important; /* More rounded corners */
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4) !important; /* More prominent shadow */
            border: 1px solid rgba(255, 255, 255, 0.25) !important; /* Clearer border */
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Smooth transition */
        }

        .card:hover {
            transform: translateY(-5px) scale(1.01); /* Slight lift and scale on hover */
            box-shadow: 0 12px 50px rgba(0, 0, 0, 0.6) !important; /* Enhanced shadow on hover */
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: white !important;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8) !important;
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
        }

        .card-title,
        .card-body,
        .card-footer {
            color: white !important;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7) !important;
        }

        /* Chart Container Styling */
        .chart-container {
            margin: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* For spinner positioning */
            min-height: 400px; /* Ensure minimum height */
        }
        canvas {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 20px; /* More rounded */
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3); /* Stronger shadow */
            padding: 25px; /* More padding */
            width: 100% !important;
            height: 100% !important;
            opacity: 1; /* Default opacity */
            transition: opacity 0.5s ease-in-out;
        }
        canvas.loading {
            opacity: 0.5; /* Reduce opacity when loading */
        }

        /* Loading Spinner */
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: var(--accent-color) !important;
        }

        /* Info Boxes and Small Boxes Enhancements */
        .info-box, .small-box {
            background-color: rgba(255, 255, 255, 0.15) !important; /* More transparent */
            backdrop-filter: blur(10px); /* Stronger glass effect */
            border-radius: 15px; /* More rounded */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            color: white !important;
            margin-bottom: 25px; /* More space */
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            border: 1px solid rgba(255, 255, 255, 0.2); /* Subtle border */
        }
        .info-box:hover, .small-box:hover {
            transform: translateY(-8px) scale(1.03); /* Lift and scale on hover */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5); /* Enhanced shadow */
            border-color: var(--accent-color); /* Highlight border */
        }
        .info-box .info-box-icon, .small-box .icon {
            opacity: 0.4; /* Slightly more visible icons */
            color: white !important;
            font-size: 3rem; /* Larger icons */
            transition: transform 0.3s ease;
        }
        .info-box:hover .info-box-icon, .small-box:hover .icon {
            transform: rotate(5deg) scale(1.1); /* Rotate and scale icon on hover */
        }
        .info-box .info-box-text, .info-box .info-box-number,
        .small-box h3, .small-box p {
            color: white !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
        }

        /* Specific gradients for info/small boxes */
        .info-box.bg-gradient-primary, .small-box.bg-primary {
            background: linear-gradient(135deg, var(--primary-gradient-start), var(--primary-gradient-end)) !important;
        }
        .info-box.bg-gradient-success, .small-box.bg-success {
            background: linear-gradient(135deg, var(--success-gradient-start), var(--success-gradient-end)) !important;
        }
        .info-box.bg-gradient-info, .small-box.bg-info {
            background: linear-gradient(135deg, var(--info-gradient-start), var(--info-gradient-end)) !important;
        }
        .card.bg-dark { /* For overall performance and quality rating cards */
            background: rgba(0, 0, 0, 0.3) !important; /* Darker, more opaque */
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(8px) !important;
        }

        /* Typography Enhancements */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
            font-weight: 700;
            color: var(--accent-color) !important; /* Use accent color for headings */
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9);
        }
        p, span, div {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
            color: #ecf0f1 !important; /* Light off-white for body text */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .text-light {
            color: #ecf0f1 !important;
        }

        /* Animations */
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .animated-card {
            animation: fadeInScale 0.8s ease-out forwards;
            opacity: 0; /* Start hidden */
        }
        .animated-card:nth-child(1) { animation-delay: 0.1s; }
        .animated-card:nth-child(2) { animation-delay: 0.2s; }
        .animated-card:nth-child(3) { animation-delay: 0.3s; }
        .animated-card:nth-child(4) { animation-delay: 0.4s; }
        .animated-card:nth-child(5) { animation-delay: 0.5s; }
        .animated-card:nth-child(6) { animation-delay: 0.6s; }

        @keyframes slideInFromLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animated-heading {
            animation: slideInFromLeft 0.7s ease-out forwards;
            opacity: 0;
        }
        .animated-text {
            animation: slideInFromLeft 0.7s ease-out forwards;
            animation-delay: 0.2s; /* Delay for text after heading */
            opacity: 0;
        }
    </style>
@endsection

@section('scripts')
    {{-- تضمين Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- تضمين كود JavaScript للمخطط --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('gilbertTriangleChart');
            const loadingSpinner = document.getElementById('chart-loading-spinner');

            if (ctx) {
                let gilbertChart = null; // للاحتفاظ بمرجع الرسم البياني

                // دالة لجلب البيانات وتحديث الرسم البياني
                async function fetchAndRenderChart() {
                    loadingSpinner.style.display = 'block'; // إظهار مؤشر التحميل
                    ctx.classList.add('loading'); // إضافة فئة لتحسين مظهر التحميل

                    try {
                        const response = await fetch('/charts/gilbert-triangle-data', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const chartData = await response.json();
                        console.log('Fetched Chart Data:', chartData);

                        let chartOptions = chartData.options;

                        // تحديث callback للـ ticks
                        if (chartOptions.scales && chartOptions.scales.r && chartOptions.scales.r.ticks && typeof chartOptions.scales.r.ticks.callback === 'string') {
                            const callbackBody = chartOptions.scales.r.ticks.callback.match(/function\((.*?)\)\s*\{\s*return\s*(.*?);\s*\}/);
                            if (callbackBody && callbackBody[1] && callbackBody[2]) {
                                chartOptions.scales.r.ticks.callback = new Function(callbackBody[1], 'return ' + callbackBody[2]);
                            } else {
                                chartOptions.scales.r.ticks.callback = function(value) { return value + "%"; };
                            }
                        }

                        // تحديث callback للـ tooltip label
                        if (chartOptions.plugins && chartOptions.plugins.tooltip && chartOptions.plugins.tooltip.callbacks && typeof chartOptions.plugins.tooltip.callbacks.label === 'string') {
                            const labelBody = chartOptions.plugins.tooltip.callbacks.label.match(/function\((.*?)\)\s*\{\s*return\s*(.*?);\s*\}/);
                            if (labelBody && labelBody[1] && labelBody[2]) {
                                chartOptions.plugins.tooltip.callbacks.label = new Function(labelBody[1], 'return ' + labelBody[2]);
                            } else {
                                chartOptions.plugins.tooltip.callbacks.label = function(context) { return " " + context.dataset.label + ": " + context.raw + "%"; };
                            }
                        }

                        // Ensure labels are always strings for Chart.js
                        chartData.labels = chartData.labels.map(label => String(label));

                        if (gilbertChart) {
                            gilbertChart.destroy(); // تدمير الرسم البياني القديم قبل إنشاء الجديد
                        }

                        gilbertChart = new Chart(ctx, {
                            type: 'radar',
                            data: {
                                labels: chartData.labels,
                                datasets: chartData.datasets
                            },
                            options: chartOptions
                        });

                    } catch (error) {
                        console.error('Error fetching or rendering chart:', error);
                        if (ctx.parentNode) {
                            ctx.parentNode.innerHTML = '<p class="text-danger text-center mt-5">تعذر تحميل بيانات المخطط. يرجى المحاولة مرة أخرى لاحقًا.</p>';
                        }
                    } finally {
                        loadingSpinner.style.display = 'none'; // إخفاء مؤشر التحميل
                        ctx.classList.remove('loading'); // إزالة فئة التحميل
                    }
                }

                // جلب البيانات وعرض الرسم البياني عند تحميل الصفحة
                fetchAndRenderChart();

                // عند تغيير حجم النافذة، تأكد من أن الرسم البياني يستجيب
                window.addEventListener('resize', () => {
                    if (gilbertChart) {
                        gilbertChart.resize();
                    }
                });
            }
        });
    </script>
@endsection
