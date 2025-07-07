@extends('layouts.admin_layout')

@section('title', 'الرئيسية - لوحة التحكم')

@section('page_title', 'الرئيسية')

@section('breadcrumb')
    <li class="breadcrumb-item active">الرئيسية</li>
@endsection

@section('content')
    <div class="container-fluid py-4">

        {{-- قسم الهدف الرئيسي للمدينة --}}
        <div class="row mb-5" data-animate-section="main-goal">
            <div class="col-12">
                <div class="card main-goal-banner text-white shadow-lg border-0 rounded-xl overflow-hidden" style="background: linear-gradient(135deg, #0A1931 0%, #1A4D6F 100%);">
                    <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-center justify-content-center text-center text-md-center main-goal-content">
                        <div class="icon-wrapper main-goal-icon-left" id="mainGoalIcon">
                            <svg width="100px" height="100px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0.25"/>
                                <path d="M12 17C14.7614 17 17 14.7614 17 12C17 9.23858 14.7614 7 12 7C9.23858 7 7 9.23858 7 12C7 14.7614 9.23858 17 12 17Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" opacity="0.25"/>
                                <path d="M12 12H12.01" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div class="flex-grow-1 text-content-wrapper">
                            <h1 class="display-4 font-weight-bold mb-2 text-white-glow main-goal-title">
                                الهدف الرئيسي للمدينة
                            </h1>
                            <p class="lead mb-0 text-white-75 main-goal-text">
                                تقديم أفضل الخدمات كماً ونوعاً للزائرين الكرام خلال السنة.
                            </p>
                        </div>

                        <div class="icon-wrapper main-goal-icon-right d-none d-md-flex">
                            <i class="fas fa-mosque fa-6x text-white-75 opacity-25" id="mosqueIcon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- قسم الترحيب المتحرك --}}
        <div class="row mb-4" data-animate-section="welcome-card">
            <div class="col-12">
                <div class="card bg-gradient-dark text-white shadow-lg border-0 rounded-xl" style="background: linear-gradient(135deg, rgba(30, 39, 46, 0.9) 0%, rgba(60, 70, 80, 0.9) 100%);">
                    <div class="card-body p-4 p-md-5 d-flex align-items-center justify-content-between">
                        <div class="welcome-text-content">
                            <h2 class="display-4 font-weight-bold mb-2">
                                <span id="greeting-text">مرحباً</span>، {{ Auth::user()->name ?? 'الزائر' }}!
                            </h2>
                            <p class="lead mb-0 text-white-50">
                                نظرة سريعة على أداء اليوم وأهداف الشُعب والوحدات.
                            </p>
                        </div>
                        <i class="fas fa-chart-area fa-5x text-white-50 opacity-25 welcome-icon" id="welcomeChartIcon"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- قسم موجز الأداء العام والمكتمل --}}
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card bg-gradient-info text-white shadow-lg border-0 rounded-xl hover-lift-effect performance-card" style="background: linear-gradient(45deg, #17a2b8, #20c997) !important;" data-animate-card-id="1">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-white-75 performance-title">الأداء العام لليوم</h4>
                                <h1 class="display-4 font-weight-bold mb-0 text-white performance-value">{{ round($todayOverallPerformance, 1) }}%</h1>
                                <p class="mb-0 text-white-50 performance-comparison">
                                    @if ($todayOverallPerformance >= $yesterdayOverallPerformance)
                                        <i class="fas fa-arrow-up text-success-light"></i> أفضل من أمس
                                    @else
                                        <i class="fas fa-arrow-down text-danger-light"></i> أقل من أمس
                                    @endif
                                    ({{ round($yesterdayOverallPerformance, 1) }}% أمس)
                                </p>
                            </div>
                            <i class="fas fa-chart-line fa-4x text-white-50 opacity-25 performance-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card bg-gradient-success text-white shadow-lg border-0 rounded-xl hover-lift-effect performance-card" style="background: linear-gradient(45deg, #28a745, #20c997) !important;" data-animate-card-id="2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-white-75 performance-title">متوسط تقييم الجودة اليومي</h4>
                                <h1 class="display-4 font-weight-bold mb-0 text-white performance-value">{{ round($todayQualityRating, 1) }} <small class="text-white-50">/ 5</small></h1>
                                <p class="mb-0 text-white-50 performance-comparison">
                                    @if ($todayQualityRating >= $yesterdayQualityRating)
                                        <i class="fas fa-arrow-up text-success-light"></i> أفضل من أمس
                                    @else
                                        <i class="fas fa-arrow-down text-danger-light"></i> أقل من أمس
                                    @endif
                                    ({{ round($yesterdayQualityRating, 1) }} / 5 أمس)
                                </p>
                            </div>
                            <i class="fas fa-star fa-4x text-white-50 opacity-25 performance-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- قسم أهداف الشعبة --}}
        <div class="card card-outline card-primary shadow-lg rounded-xl mt-4" data-animate-section="department-goals">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-sitemap mr-2"></i> أهداف الشعبة
                </h3>
            </div>
            <div class="card-body p-4">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @php
                        $departmentGoalsList = [
                            [
                                'text' => 'الحفاظ على نظافة جميع مرافق المدينة ومحيطها الخارجي.',
                                'main_goal' => 'ضمان بيئة نظيفة وآمنة للجميع.',
                                'icon' => 'fas fa-broom',
                                'color_class' => 'bg-gradient-blue',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'ضمان جاهزية وصيانة جميع المرافق الصحية بشكل مستمر.',
                                'main_goal' => 'توفير مرافق صحية عالية الجودة.',
                                'icon' => 'fas fa-clinic-medical',
                                'color_class' => 'bg-gradient-green',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'توفير بيئة نظيفة وصحية وآمنة للزائرين على مدار الساعة.',
                                'main_goal' => 'راحة وسلامة الزوار.',
                                'icon' => 'fas fa-shield-alt',
                                'color_class' => 'bg-gradient-purple',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'الاستجابة السريعة للأعطال الطارئة في وحدات الخدمة.',
                                'main_goal' => 'كفاءة الاستجابة للطوارئ.',
                                'icon' => 'fas fa-bolt',
                                'color_class' => 'bg-gradient-orange',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'جدولة وتنفيذ خطط التنظيف والصيانة اليومية والأسبوعية.',
                                'main_goal' => 'إدارة عمليات فعالة.',
                                'icon' => 'fas fa-calendar-check',
                                'color_class' => 'bg-gradient-teal',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'دعم المناسبات والزيارات المليونية بخطط تنظيف استثنائية.',
                                'main_goal' => 'دعم الفعاليات الكبرى.',
                                'icon' => 'fas fa-users-crown',
                                'color_class' => 'bg-gradient-pink',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'إدارة ومتابعة أداء فرق العمل وتوزيع المهام بكفاءة.',
                                'main_goal' => 'تعزيز كفاءة فريق العمل.',
                                'icon' => 'fas fa-user-tie',
                                'color_class' => 'bg-gradient-indigo',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'تحسين الموارد المستخدمة وتقليل الهدر في الاستهلاك.',
                                'main_goal' => 'إدارة موارد مستدامة.',
                                'icon' => 'fas fa-recycle',
                                'color_class' => 'bg-gradient-yellow',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'تعزيز ثقافة النظافة العامة لدى العاملين والزائرين.',
                                'main_goal' => 'نشر الوعي البيئي.',
                                'icon' => 'fas fa-hands-helping',
                                'color_class' => 'bg-gradient-red',
                                'date' => '2024-01-01'
                            ],
                            [
                                'text' => 'إعداد تقارير مهنية يومية وشهرية لرفعها لإدارة المدينة.',
                                'main_goal' => 'شفافية الأداء.',
                                'icon' => 'fas fa-file-alt',
                                'color_class' => 'bg-gradient-cyan',
                                'date' => '2024-01-01'
                            ],
                        ];
                    @endphp
                    @foreach ($departmentGoalsList as $index => $goal)
                        <div class="col mb-4">
                            <div class="card h-100 text-white shadow-md border rounded-lg animated-card {{ $goal['color_class'] }}" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2 font-weight-bold text-white-glow text-xxl">
                                        <i class="{{ $goal['icon'] }} mr-2 card-icon"></i> {{ $goal['text'] }}
                                    </h5>
                                    <p class="card-text text-white-75 text-md flex-grow-1">
                                        <small class="text-white-75">الهدف الرئيسي: {{ $goal['main_goal'] }}</small><br>
                                        <small class="text-white-75">تاريخ الإنشاء: {{ $goal['date'] }}</small>
                                    </p>
                                    <div class="text-right mt-auto">
                                        <i class="{{ $goal['icon'] }} fa-3x text-white-50 opacity-25 card-bg-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- قسم أحدث أهداف الوحدات --}}
        <div class="card card-outline card-info shadow-lg rounded-xl mt-4" data-animate-section="unit-goals">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-tasks mr-2"></i> أحدث أهداف الوحدات
                </h3>
                <div class="card-tools">
                    <a href="{{ route('unit-goals.index') }}" class="btn btn-sm btn-outline-info text-white-75 hover-scale-btn">
                        عرض كل الأهداف <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @php
                        $unitGoalsList = [
                            [
                                'text' => 'ضمان تنظيف قاعة واحدة يوميًا (ما يعادل 7 قاعات أسبوعيًا) بنسبة إنجاز 100%، مع تطبيق الإدامة العميقة للطابقين العلوي والسفلي وفقًا للخطة الأسبوعية المعتمدة بنسبة التزام 95%.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-28',
                                'target_tasks' => 7,
                                'progress_percentage' => 90,
                                'icon' => 'fas fa-chalkboard-teacher',
                                'color_class' => 'bg-gradient-dark-blue'
                            ],
                            [
                                'text' => 'إدامة وتعقيم جميع قاعات المبيت بنسبة 100% خلال ساعة واحدة من مغادرة الزوار، وتنفيذ التعقيم الكامل بـ 100% من القاعات المتبقية مرتين أسبوعيًا باستخدام المعقمات المعتمدة.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-29',
                                'target_tasks' => 100,
                                'progress_percentage' => 95,
                                'icon' => 'fas fa-bed',
                                'color_class' => 'bg-gradient-dark-green'
                            ],
                            [
                                'text' => 'كنس وغسل جميع الساحات العامة 3 مرات يوميًا ضمن وجبات العمل (صباحية من 7 ص إلى 2 م، مسائية من 2 م إلى 9 م، ليلية من 9 م إلى 7 ص) بنسبة التزام لا تقل عن 95%، وإزالة الأوساخ والمخلفات من جميع النقاط المحددة كل ساعتين على مدار 24 ساعة بنسبة لا تقل عن 98%.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-30',
                                'target_tasks' => 3,
                                'progress_percentage' => 80,
                                'icon' => 'fas fa-dumpster',
                                'color_class' => 'bg-gradient-dark-red'
                            ],
                            [
                                'text' => 'رفع 100% من الحاويات من جميع النقاط المحددة كل 6 ساعات على مدار 24 ساعة يوميًا، وغسل وتعقيم 100% منها يوميًا بعد عملية التفريغ الأخيرة.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-27',
                                'target_tasks' => 4,
                                'progress_percentage' => 100,
                                'icon' => 'fas fa-trash-alt',
                                'color_class' => 'bg-gradient-dark-purple'
                            ],
                            [
                                'text' => 'فرش 100% من السجاد في القاعات والساحات المخصصة قبل 30 دقيقة من الفعاليات المجدولة أو عند الحاجة، والتأكد من نظافة وتعقيم 100% من السجاد قبل وبعد كل استخدام أو فعالية.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-28',
                                'target_tasks' => 100,
                                'progress_percentage' => 98,
                                'icon' => 'fas fa-rug',
                                'color_class' => 'bg-gradient-dark-blue'
                            ],
                            [
                                'text' => 'تعبئة 100% من الترامز بالماء الصالح للشرب كل 4 ساعات أو فورًا عند انخفاض المستوى إلى أقل من 20%، وإجراء فحص يومي لـ 100% من الترامز للتأكد من نظافتها وصلاحية المياه داخلها.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-29',
                                'target_tasks' => 100,
                                'progress_percentage' => 100,
                                'icon' => 'fas fa-water',
                                'color_class' => 'bg-gradient-dark-green'
                            ],
                            [
                                'text' => 'تنظيف وتعقيم 100% من الحمامات كل ساعتين خلال ساعات العمل الرسمية (7 صباحًا - 10 مساءً)، وإعادة تعبئة جميع المواد الصحية (مثل الصابون والزاهي وورق التواليت) عند وصولها إلى 25% من سعتها القصوى مع توفر مخزون بنسبة 99%.',
                                'unit' => 'وحدة المنشآت الصحية',
                                'date' => '2024-06-30',
                                'target_tasks' => 100,
                                'progress_percentage' => 92,
                                'icon' => 'fas fa-toilet',
                                'color_class' => 'bg-gradient-dark-red'
                            ],
                            [
                                'text' => 'إجراء صيانة وقائية دورية لـ 100% من السيفونات والمغاسل والمرايا في جميع الحمامات مرة واحدة شهريًا، وإصلاح 95% من الأعطال المبلغ عنها خلال 4 ساعات كحد أقصى من وقت الإبلاغ.',
                                'unit' => 'وحدة المنشآت الصحية',
                                'date' => '2024-06-27',
                                'target_tasks' => 100,
                                'progress_percentage' => 88,
                                'icon' => 'fas fa-tools',
                                'color_class' => 'bg-gradient-dark-purple'
                            ],
                        ];
                    @endphp
                    @foreach ($unitGoalsList as $index => $goal)
                        <div class="col mb-4">
                            <div class="card h-100 text-white shadow-md border rounded-lg animated-card {{ $goal['color_class'] }}" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold mb-3 text-white-glow text-xl">
                                        <i class="{{ $goal['icon'] }} mr-2 card-icon"></i> هدف الوحدة: {{ Str::limit($goal['text'], 200) }}
                                    </h5>
                                    <p class="card-text text-white-75 text-md mb-2">
                                        <i class="fas fa-building mr-1"></i> الوحدة: {{ $goal['unit'] }} <br>
                                        <i class="fas fa-calendar-alt mr-1"></i> التاريخ: {{ $goal['date'] }} <br>
                                        <i class="fas fa-clipboard-check mr-1"></i> المهام المستهدفة: {{ $goal['target_tasks'] }}
                                    </p>
                                    <div class="progress progress-sm mt-3" style="height: 12px; border-radius: 6px; background-color: rgba(255,255,255,0.2);">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated
                                            @if ($goal['progress_percentage'] >= 100) bg-success
                                            @elseif ($goal['progress_percentage'] >= 75) bg-warning
                                            @else bg-danger @endif"
                                            role="progressbar"
                                            style="width: {{ $goal['progress_percentage'] }}%;"
                                            aria-valuenow="{{ $goal['progress_percentage'] }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <p class="text-right text-muted mt-2 mb-0">
                                        <span class="font-weight-bold text-xl @if ($goal['progress_percentage'] >= 100) text-success @elseif ($goal['progress_percentage'] >= 75) text-warning @else text-danger @endif">{{ $goal['progress_percentage'] }}%</span>
                                        تحقق
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- قسم التقارير السريعة --}}
        <div class="row mt-4" data-animate-section="quick-reports">
            <div class="col-12">
                <div class="card card-outline card-secondary shadow-lg rounded-xl">
                    <div class="card-header border-0 pb-0">
                        <h3 class="card-title font-weight-bold text-secondary">
                            <i class="fas fa-file-invoice mr-2"></i> التقارير السريعة
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-general-cleaning text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="0">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-file-csv fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقرير النظافة العامة</h3>
                                            <p class="card-text flex-grow-1">عرض تقارير يومية وشهرية عن أعمال النظافة.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرير</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-sanitation-facility text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="100">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-clipboard-list fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقرير المنشآت الصحية</h3>
                                            <p class="card-text flex-grow-1">متابعة حالة وصيانة المرافق الصحية.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرير</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-daily-status text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="200">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-chart-pie fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقرير الحالة اليومية</h3>
                                            <p class="card-text flex-grow-1">نظرة عامة على الأداء والمهام اليومية.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرير</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-resource-report text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="300">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-boxes fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقرير الموارد والمخزون</h3>
                                            <p class="card-text flex-grow-1">تتبع استهلاك الموارد المتاحة.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرير</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-monthly-cleaning-report text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="400">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-calendar-alt fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقرير النظافة الشهري</h3>
                                            <p class="card-text flex-grow-1">تحليل الأداء الشهري لأعمال النظافة.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرير</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-monthly-sanitation-report text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="500">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-chart-bar fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقرير المنشآت الشهري</h3>
                                            <p class="card-text flex-grow-1">ملخص شامل لصيانة وتقييم المرافق الصحية.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرier</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-employees text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="600">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-users fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقرير الموظفين</h3>
                                            <p class="card-text flex-grow-1">متابعة أداء وحضور وانصراف الموظفين.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرير</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col mb-4">
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-photo-reports text-white h-100 hover-zoom-effect" data-aos="fade-up" data-aos-delay="700">
                                        <div class="card-body d-flex flex-column justify-content-between align-items-center text-center">
                                            <i class="fas fa-images fa-4x mb-3"></i>
                                            <h3 class="card-title text-white font-weight-bold mb-2">تقارير الصور</h3>
                                            <p class="card-text flex-grow-1">مكتبة للتقارير المرئية المصورة.</p>
                                            <button class="btn btn-card-action btn-block"><i class="fas fa-eye mr-2"></i>عرض التقرير</button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <style>
        :root {
            --accent-color: #00eaff;
            --glass-background: rgba(255, 255, 255, 0.08);
            --glass-border: 1px solid rgba(255, 255, 255, 0.2);
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            --text-primary-color: white;
            --text-shadow-strong: 2px 2px 5px rgba(0, 0, 0, 0.9);
            --text-shadow-medium: 1px 1px 3px rgba(0, 0, 0, 0.7);
            --text-shadow-light: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* General Body and Main Content Styling */
        body {
            background-color: #f4f6f9; /* Default AdminLTE background */
        }
        .content-wrapper {
            background: url('{{ asset('images/admin-bg-pattern.png') }}') repeat; /* Subtle pattern background */
            background-size: 200px;
        }

        /* Hero Section Styling (Existing, but adding context) */
        .hero-background-image {
            background-image: url('{{ asset('images/image_ed91ba.jpg-390afef3-9676-4272-bf03-b50bb447ea67') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
            color: var(--text-primary-color);
            padding: 8rem 1rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        /* Overlay for better text readability */
        .hero-background-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        /* Animated background element */
        .header-bg-move {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: moveAround 15s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 2;
        }

        @keyframes moveAround {
            0% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(50px, -20px) scale(1.05); }
            50% { transform: translate(-30px, 40px) scale(1.1); }
            75% { transform: translate(20px, -50px) scale(0.95); }
            100% { transform: translate(0, 0) scale(1); }
        }

        /* Hero text styling */
        .hero-background-image h1 {
            font-size: 3.5rem;
            font-weight: 900 !important;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-strong);
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 3;
            animation: fadeInDown 1s ease-out forwards;
        }
        .hero-background-image p {
            font-size: 1.5rem;
            opacity: 0.95;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-medium);
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 3;
            animation: fadeInUp 1s ease-out 0.3s forwards;
        }

        /* Custom buttons for hero and call to action */
        .btn-custom-primary {
            background: linear-gradient(45deg, #4F46E5, #6366F1);
            color: white;
            transition: all 0.4s ease-in-out;
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4);
            border-radius: 9999px;
            font-weight: 700;
            padding: 0.8rem 2.5rem;
            font-size: 1.25rem;
            display: inline-block;
            text-decoration: none;
            border: none;
            position: relative;
            z-index: 3;
            margin: 0.5rem;
        }
        .btn-custom-primary:hover {
            background: linear-gradient(45deg, #6366F1, #4F46E5);
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.7);
        }

        /* Features Section Cards - Glassmorphism effect */
        .card-custom-shadow {
            background: var(--glass-background) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 1rem !important;
            box-shadow: var(--glass-shadow) !important;
            border: var(--glass-border) !important;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-light);
        }
        .card-custom-shadow:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
        }

        /* Card body content styling */
        .card-custom-shadow .card-body {
            padding: 2rem;
        }
        .card-custom-shadow h3 {
            font-size: 1.75rem !important;
            font-weight: 700 !important;
            color: var(--accent-color) !important;
            text-shadow: var(--text-shadow-medium);
            margin-bottom: 1rem;
        }
        .card-custom-shadow p {
            font-size: 1.1rem !important;
            color: rgba(255, 255, 255, 0.9) !important;
            text-shadow: var(--text-shadow-light);
        }

        /* Feature Icons - larger and more distinct */
        .feature-icon-indigo { color: #4F46E5; }
        .feature-icon-purple { color: #8B5CF6; }
        .feature-icon-blue { color: #2563EB; }
        .feature-icon {
            font-size: 4.5rem !important;
            margin-bottom: 1rem;
            filter: drop-shadow(2px 2px 5px rgba(0,0,0,0.6));
        }

        /* Call to Action Section styling */
        .bg-gradient-dark {
            background: linear-gradient(to bottom right, rgba(29, 36, 48, 0.8), rgba(41, 51, 67, 0.8)) !important;
            padding: 5rem 1rem;
            text-align: center;
            color: var(--text-primary-color);
        }
        .bg-gradient-dark h2 {
            font-size: 2.5rem;
            font-weight: 800 !important;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-strong);
            margin-bottom: 1.5rem;
        }
        .bg-gradient-dark p {
            font-size: 1.2rem;
            opacity: 0.9;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-medium);
            margin-bottom: 2.5rem;
        }

        /* Ensure .container-fluid background is transparent where needed */
        .container-fluid {
            background-color: transparent !important;
        }

        /* AdminLTE card specific styles */
        .card-general-cleaning, .card-sanitation-facility, .card-daily-status,
        .card-resource-report, .card-monthly-cleaning-report, .card-monthly-sanitation-report,
        .card-employees, .card-photo-reports {
            border-radius: 1rem;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            border: none;
            overflow: hidden; /* Ensure gradient corners are rounded */
        }
        .card-general-cleaning:hover, .card-sanitation-facility:hover, .card-daily-status:hover,
        .card-resource-report:hover, .card-monthly-cleaning-report:hover, .card-monthly-sanitation-report:hover,
        .card-employees:hover, .card-photo-reports:hover {
            transform: translateY(-7px) scale(1.03);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
        }

        /* Specific card colors */
        .card-general-cleaning { background: linear-gradient(to bottom right, #3498db, #8e44ad); } /* Blue to Purple */
        .card-sanitation-facility { background: linear-gradient(to bottom right, #9b59b6, #e74c3c); } /* Purple to Red */
        .card-daily-status { background: linear-gradient(to bottom right, #2ecc71, #f1c40f); } /* Green to Yellow */
        .card-resource-report { background: linear-gradient(to bottom right, #34495e, #7f8c8d); } /* Dark Blue to Gray */
        .card-monthly-cleaning-report { background: linear-gradient(to bottom right, #e67e22, #d35400); } /* Orange to Dark Orange */
        .card-monthly-sanitation-report { background: linear-gradient(to bottom right, #1abc9c, #27ae60); } /* Turquoise to Emerald Green */
        .card-employees { background: linear-gradient(to bottom right, #6d28d9, #4b0082); } /* Indigo to Dark Violet */
        .card-photo-reports { background: linear-gradient(to bottom right, #ff6347, #cd5c5c); } /* Tomato to Indian Red */


        /* General text for these cards */
        .card-general-cleaning, .card-sanitation-facility, .card-daily-status,
        .card-resource-report, .card-monthly-cleaning-report, .card-monthly-sanitation-report,
        .card-employees, .card-photo-reports {
            color: white;
        }
        .card-general-cleaning .card-body h3, .card-sanitation-facility .card-body h3, .card-daily-status .card-body h3,
        .card-resource-report .card-body h3, .card-monthly-cleaning-report .card-body h3, .card-monthly-sanitation-report .card-body h3,
        .card-employees .card-body h3, .card-photo-reports .card-body h3 {
            color: white !important;
            text-shadow: var(--text-shadow-medium);
        }
        .card-general-cleaning .card-body p, .card-sanitation-facility .card-body p, .card-daily-status .card-body p,
        .card-resource-report .card-body p, .card-monthly-cleaning-report .card-body p, .card-monthly-sanitation-report .card-body p,
        .card-employees .card-body p, .card-photo-reports .card-body p {
            color: rgba(255, 255, 255, 0.9) !important;
            text-shadow: var(--text-shadow-light);
        }

        /* Card action buttons within the specific cards */
        .btn-card-action {
            background-color: rgba(255, 255, 255, 0.95);
            color: #34495e;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-card-action:hover {
            background-color: white;
            color: #1a4d6f; /* A darker tone from the main goal banner */
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
        }

        /* Specific gradient classes for department goals */
        .bg-gradient-blue { background: linear-gradient(45deg, #007bff, #0056b3); }
        .bg-gradient-green { background: linear-gradient(45deg, #28a745, #1e7e34); }
        .bg-gradient-purple { background: linear-gradient(45deg, #6f42c1, #563d7c); }
        .bg-gradient-orange { background: linear-gradient(45deg, #fd7e14, #cb6b11); }
        .bg-gradient-teal { background: linear-gradient(45deg, #20c997, #179e7a); }
        .bg-gradient-pink { background: linear-gradient(45deg, #e83e8c, #c5347b); }
        .bg-gradient-indigo { background: linear-gradient(45deg, #6610f2, #520dc2); }
        .bg-gradient-yellow { background: linear-gradient(45deg, #ffc107, #d39e00); }
        .bg-gradient-red { background: linear-gradient(45deg, #dc3545, #b02a37); }
        .bg-gradient-cyan { background: linear-gradient(45deg, #17a2b8, #138496); }

        /* Specific gradient classes for unit goals (darker variations) */
        .bg-gradient-dark-blue { background: linear-gradient(45deg, #004085, #002752); }
        .bg-gradient-dark-green { background: linear-gradient(45deg, #19692c, #0d3817); }
        .bg-gradient-dark-red { background: linear-gradient(45deg, #8b0000, #5a0000); }
        .bg-gradient-dark-purple { background: linear-gradient(45deg, #4b0082, #2e0050); }

        /* Text glow effect */
        .text-white-glow {
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.7), 0 0 15px rgba(255, 255, 255, 0.4);
        }

        /* Hover lift effect for general cards */
        .hover-lift-effect {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift-effect:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
        }

        /* Hover scale effect for buttons */
        .hover-scale-btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-scale-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 10px rgba(0, 123, 255, 0.3);
        }

        /* Hover zoom effect for report cards */
        .hover-zoom-effect {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-zoom-effect:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Card icon and background icon styling for goals */
        .card-icon {
            font-size: 1.8rem;
            vertical-align: middle;
        }
        .card-bg-icon {
            position: absolute;
            bottom: 15px;
            right: 15px;
            font-size: 4em;
            opacity: 0.15;
            z-index: 0;
            filter: blur(1px); /* Subtle blur for background icons */
        }
        .animated-card {
            position: relative;
            overflow: hidden;
        }
        .animated-card .card-body {
            position: relative;
            z-index: 1; /* Ensure content is above background icon */
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .main-goal-content {
                flex-direction: column !important;
                padding: 3rem 1rem !important;
            }
            .main-goal-icon-left, .main-goal-icon-right {
                margin-bottom: 1.5rem !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .main-goal-icon-right {
                display: none !important; /* Hide right icon on small screens */
            }
            .main-goal-title {
                font-size: 2.5rem !important;
            }
            .main-goal-text {
                font-size: 1.1rem !important;
            }
            .welcome-text-content h2 {
                font-size: 2.2rem !important;
            }
            .welcome-text-content p {
                font-size: 1rem !important;
            }
            .welcome-icon {
                font-size: 3.5rem !important;
            }
            .performance-value {
                font-size: 3rem !important;
            }
            .performance-icon {
                font-size: 3rem !important;
            }
        }
        @media (max-width: 575.98px) {
            .hero-background-image h1 {
                font-size: 2.5rem;
            }
            .hero-background-image p {
                font-size: 1.2rem;
            }
            .btn-custom-primary {
                padding: 0.6rem 2rem;
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize AOS (Animate On Scroll)
            AOS.init({
                duration: 1000,
                once: true, // Whether animation should happen only once - while scrolling down
                mirror: false, // Whether elements should animate out while scrolling past them
            });

            // Dynamic greeting based on time of day
            function setGreeting() {
                const now = new Date();
                const hour = now.getHours();
                let greeting;

                if (hour >= 5 && hour < 12) {
                    greeting = 'صباح الخير';
                } else if (hour >= 12 && hour < 18) {
                    greeting = 'مساء الخير';
                } else {
                    greeting = 'تصبح على خير';
                }
                $('#greeting-text').text(greeting);
            }
            setGreeting(); // Call it once on load

            // Subtle animation for main goal icon on hover
            $('#mainGoalIcon').hover(
                function() {
                    $(this).find('svg').css('transform', 'scale(1.1) rotate(5deg)');
                    $(this).find('svg path').css('stroke-width', '3');
                },
                function() {
                    $(this).find('svg').css('transform', 'scale(1) rotate(0deg)');
                    $(this).find('svg path').css('stroke-width', '2');
                }
            );

            // Subtle animation for welcome chart icon on hover
            $('#welcomeChartIcon').hover(
                function() {
                    $(this).css('transform', 'scale(1.1) rotate(5deg)');
                    $(this).addClass('animate__animated animate__pulse');
                },
                function() {
                    $(this).css('transform', 'scale(1) rotate(0deg)');
                    $(this).removeClass('animate__animated animate__pulse');
                }
            );

            // Add animation classes for performance cards on initial load
            $('.performance-card').each(function(index) {
                $(this).addClass('animate__animated animate__fadeInUp');
                $(this).css('animation-delay', (index * 0.2) + 's');
            });

            // Add animation to the card titles and icons on hover for Department and Unit Goals
            $('.animated-card').hover(
                function() {
                    $(this).find('.card-title').addClass('animate__animated animate__swing');
                    $(this).find('.card-icon').addClass('animate__animated animate__bounce');
                },
                function() {
                    $(this).find('.card-title').removeClass('animate__animated animate__swing');
                    $(this).find('.card-icon').removeClass('animate__animated animate__bounce');
                }
            );

            // Make the progress bars animate on load
            $('.progress-bar').each(function() {
                var progress = $(this).attr('aria-valuenow');
                $(this).css('width', 0).animate({
                    width: progress + '%'
                }, 1500, 'easeOutQuint'); // Using easeOutQuint for a smoother animation
            });
        });
    </script>
@endsection