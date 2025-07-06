 

<?php $__env->startSection('title', 'الرئيسية - لوحة التحكم'); ?>

<?php $__env->startSection('page_title', 'الرئيسية'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">الرئيسية</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">

        
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

        
        <div class="row mb-4" data-animate-section="welcome-card"> 
            <div class="col-12">
                <div class="card bg-gradient-dark text-white shadow-lg border-0 rounded-xl" style="background: linear-gradient(135deg, rgba(30, 39, 46, 0.9) 0%, rgba(60, 70, 80, 0.9) 100%);">
                    <div class="card-body p-4 p-md-5 d-flex align-items-center justify-content-between">
                        <div class="welcome-text-content"> 
                            <h2 class="display-4 font-weight-bold mb-2">
                                <span id="greeting-text">مرحباً</span>، <?php echo e(Auth::user()->name ?? 'الزائر'); ?>!
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

        
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card bg-gradient-info text-white shadow-lg border-0 rounded-xl hover-lift-effect performance-card" style="background: linear-gradient(45deg, #17a2b8, #20c997) !important;" data-animate-card-id="1"> 
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 text-white-75 performance-title">الأداء العام لليوم</h4>
                                <h1 class="display-4 font-weight-bold mb-0 text-white performance-value"><?php echo e(round($todayOverallPerformance, 1)); ?>%</h1>
                                <p class="mb-0 text-white-50 performance-comparison">
                                    <?php if($todayOverallPerformance >= $yesterdayOverallPerformance): ?>
                                        <i class="fas fa-arrow-up text-success-light"></i> أفضل من أمس
                                    <?php else: ?>
                                        <i class="fas fa-arrow-down text-danger-light"></i> أقل من أمس
                                    <?php endif; ?>
                                    (<?php echo e(round($yesterdayOverallPerformance, 1)); ?>% أمس)
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
                                <h1 class="display-4 font-weight-bold mb-0 text-white performance-value"><?php echo e(round($todayQualityRating, 1)); ?> <small class="text-white-50">/ 5</small></h1>
                                <p class="mb-0 text-white-50 performance-comparison">
                                    <?php if($todayQualityRating >= $yesterdayQualityRating): ?>
                                        <i class="fas fa-arrow-up text-success-light"></i> أفضل من أمس
                                    <?php else: ?>
                                        <i class="fas fa-arrow-down text-danger-light"></i> أقل من أمس
                                    <?php endif; ?>
                                    (<?php echo e(round($yesterdayQualityRating, 1)); ?> / 5 أمس)
                                </p>
                            </div>
                            <i class="fas fa-star fa-4x text-white-50 opacity-25 performance-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card card-outline card-primary shadow-lg rounded-xl mt-4" data-animate-section="department-goals"> 
            <div class="card-header border-0 pb-0">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-sitemap mr-2"></i> أهداف الشعبة
                </h3>
            </div>
            <div class="card-body p-4">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php
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
                    ?>
                    <?php $__currentLoopData = $departmentGoalsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col mb-4">
                            <div class="card h-100 text-white shadow-md border rounded-lg animated-card <?php echo e($goal['color_class']); ?>" data-aos="fade-up" data-aos-delay="<?php echo e($index * 100); ?>"> 
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2 font-weight-bold text-white-glow text-xxl">
                                        <i class="<?php echo e($goal['icon']); ?> mr-2 card-icon"></i> <?php echo e($goal['text']); ?>

                                    </h5>
                                    <p class="card-text text-white-75 text-md flex-grow-1">
                                        <small class="text-white-75">الهدف الرئيسي: <?php echo e($goal['main_goal']); ?></small><br>
                                        <small class="text-white-75">تاريخ الإنشاء: <?php echo e($goal['date']); ?></small>
                                    </p>
                                    <div class="text-right mt-auto">
                                        <i class="<?php echo e($goal['icon']); ?> fa-3x text-white-50 opacity-25 card-bg-icon"></i> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="card card-outline card-info shadow-lg rounded-xl mt-4" data-animate-section="unit-goals"> 
            <div class="card-header border-0 pb-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-tasks mr-2"></i> أحدث أهداف الوحدات
                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('unit-goals.index')); ?>" class="btn btn-sm btn-outline-info text-white-75 hover-scale-btn">
                        عرض كل الأهداف <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php
                        $unitGoalsList = [
                            [
                                'text' => 'ضمان تنظيف قاعة واحدة يوميًا (ما يعادل 7 قاعات أسبوعيًا) بنسبة إنجاز 100%، مع تطبيق الإدامة العميقة للطابقين العلوي والسفلي وفقًا للخطة الأسبوعية المعتمدة بنسبة التزام 95%.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-28',
                                'target_tasks' => 7, // 7 قاعات أسبوعياً
                                'progress_percentage' => 90,
                                'icon' => 'fas fa-chalkboard-teacher',
                                'color_class' => 'bg-gradient-dark-blue'
                            ],
                            [
                                'text' => 'إدامة وتعقيم جميع قاعات المبيت بنسبة 100% خلال ساعة واحدة من مغادرة الزوار، وتنفيذ التعقيم الكامل بـ 100% من القاعات المتبقية مرتين أسبوعيًا باستخدام المعقمات المعتمدة.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-29',
                                'target_tasks' => 100, // نسبة مئوية
                                'progress_percentage' => 95,
                                'icon' => 'fas fa-bed',
                                'color_class' => 'bg-gradient-dark-green'
                            ],
                            [
                                'text' => 'كنس وغسل جميع الساحات العامة 3 مرات يوميًا ضمن وجبات العمل (صباحية من 7 ص إلى 2 م، مسائية من 2 م إلى 9 م، ليلية من 9 م إلى 7 ص) بنسبة التزام لا تقل عن 95%، وإزالة الأوساخ والمخلفات من جميع النقاط المحددة كل ساعتين على مدار 24 ساعة بنسبة لا تقل عن 98%.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-30',
                                'target_tasks' => 3, // عدد مرات الكنس والغسل
                                'progress_percentage' => 80,
                                'icon' => 'fas fa-dumpster',
                                'color_class' => 'bg-gradient-dark-red'
                            ],
                            [
                                'text' => 'رفع 100% من الحاويات من جميع النقاط المحددة كل 6 ساعات على مدار 24 ساعة يوميًا، وغسل وتعقيم 100% منها يوميًا بعد عملية التفريغ الأخيرة.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-27',
                                'target_tasks' => 4, // عدد دورات رفع الحاويات
                                'progress_percentage' => 100,
                                'icon' => 'fas fa-trash-alt',
                                'color_class' => 'bg-gradient-dark-purple'
                            ],
                            [
                                'text' => 'فرش 100% من السجاد في القاعات والساحات المخصصة قبل 30 دقيقة من الفعاليات المجدولة أو عند الحاجة، والتأكد من نظافة وتعقيم 100% من السجاد قبل وبعد كل استخدام أو فعالية.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-28',
                                'target_tasks' => 100, // نسبة السجاد
                                'progress_percentage' => 98,
                                'icon' => 'fas fa-rug',
                                'color_class' => 'bg-gradient-dark-blue' // إعادة استخدام لون
                            ],
                            [
                                'text' => 'تعبئة 100% من الترامز بالماء الصالح للشرب كل 4 ساعات أو فورًا عند انخفاض المستوى إلى أقل من 20%، وإجراء فحص يومي لـ 100% من الترامز للتأكد من نظافتها وصلاحية المياه داخلها.',
                                'unit' => 'وحدة النظافة العامة',
                                'date' => '2024-06-29',
                                'target_tasks' => 100, // نسبة الترامز
                                'progress_percentage' => 100,
                                'icon' => 'fas fa-water',
                                'color_class' => 'bg-gradient-dark-green' // إعادة استخدام لون
                            ],
                            [
                                'text' => 'تنظيف وتعقيم 100% من الحمامات كل ساعتين خلال ساعات العمل الرسمية (7 صباحًا - 10 مساءً)، وإعادة تعبئة جميع المواد الصحية (مثل الصابون والزاهي وورق التواليت) عند وصولها إلى 25% من سعتها القصوى مع توفر مخزون بنسبة 99%.',
                                'unit' => 'وحدة المنشآت الصحية',
                                'date' => '2024-06-30',
                                'target_tasks' => 100, // نسبة الحمامات
                                'progress_percentage' => 92,
                                'icon' => 'fas fa-toilet',
                                'color_class' => 'bg-gradient-dark-red' // إعادة استخدام لون
                            ],
                            [
                                'text' => 'إجراء صيانة وقائية دورية لـ 100% من السيفونات والمغاسل والمرايا في جميع الحمامات مرة واحدة شهريًا، وإصلاح 95% من الأعطال المبلغ عنها خلال 4 ساعات كحد أقصى من وقت الإبلاغ.',
                                'unit' => 'وحدة المنشآت الصحية',
                                'date' => '2024-06-27',
                                'target_tasks' => 100, // نسبة الصيانة
                                'progress_percentage' => 88,
                                'icon' => 'fas fa-tools',
                                'color_class' => 'bg-gradient-dark-purple' // إعادة استخدام لون
                            ],
                        ];
                    ?>
                    <?php $__currentLoopData = $unitGoalsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col mb-4">
                            <div class="card h-100 text-white shadow-md border rounded-lg animated-card <?php echo e($goal['color_class']); ?>" data-aos="fade-up" data-aos-delay="<?php echo e($index * 100); ?>"> 
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold mb-3 text-white-glow text-xl">
                                        <i class="<?php echo e($goal['icon']); ?> mr-2 card-icon"></i> هدف الوحدة: <?php echo e(Str::limit($goal['text'], 200)); ?>

                                    </h5>
                                    <p class="card-text text-white-75 text-md mb-2">
                                        <i class="fas fa-building mr-1"></i> الوحدة: <?php echo e($goal['unit']); ?> <br>
                                        <i class="fas fa-calendar-alt mr-1"></i> التاريخ: <?php echo e($goal['date']); ?> <br>
                                        <i class="fas fa-clipboard-check mr-1"></i> المهام المستهدفة: <?php echo e($goal['target_tasks']); ?>

                                    </p>
                                    <div class="progress progress-sm mt-3" style="height: 12px; border-radius: 6px; background-color: rgba(255,255,255,0.2);">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated
                                            <?php if($goal['progress_percentage'] >= 100): ?> bg-success
                                            <?php elseif($goal['progress_percentage'] >= 75): ?> bg-warning
                                            <?php else: ?> bg-danger <?php endif; ?>"
                                            role="progressbar"
                                            style="width: <?php echo e($goal['progress_percentage']); ?>%;"
                                            aria-valuenow="<?php echo e($goal['progress_percentage']); ?>"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <p class="text-right text-muted mt-2 mb-0">
                                        <span class="font-weight-bold text-xl <?php if($goal['progress_percentage'] >= 100): ?> text-success <?php elseif($goal['progress_percentage'] >= 75): ?> text-warning <?php else: ?> text-danger <?php endif; ?>"><?php echo e($goal['progress_percentage']); ?>%</span>
                                        تحقق
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        /* Define an accent color variable for distinctiveness */
        :root {
            --accent-color: #00eaff; /* Light blue/cyan for interactive elements and emphasis */
            --glass-background: rgba(255, 255, 255, 0.08); /* Consistent transparent background for glass effect */
            --glass-border: 1px solid rgba(255, 255, 255, 0.2); /* Consistent transparent border */
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Consistent shadow */
            --text-primary-color: white;
            --text-shadow-strong: 2px 2px 5px rgba(0, 0, 0, 0.9);
            --text-shadow-medium: 1px 1px 3px rgba(0, 0, 0, 0.7);
            --text-shadow-light: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* Hero Section Styling */
        .hero-background-image {
            background-image: url('<?php echo e(asset('images/image_ed91ba.jpg-390afef3-9676-4272-bf03-b50bb447ea67')); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
            color: var(--text-primary-color);
            padding: 8rem 1rem; /* More vertical padding */
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
            background-color: rgba(0, 0, 0, 0.6); /* Darker transparent overlay */
            z-index: 1;
        }

        /* Animated background element */
        .header-bg-move {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1); /* Lighter transparent element */
            border-radius: 50%;
            animation: moveAround 15s ease-in-out infinite alternate; /* Slower animation */
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
            font-size: 3.5rem; /* Even larger for impact */
            font-weight: 900 !important; /* Extra bold */
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-strong);
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 3;
            animation: fadeInDown 1s ease-out forwards;
        }
        .hero-background-image p {
            font-size: 1.5rem; /* Larger paragraph */
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
            background: linear-gradient(45deg, #4F46E5, #6366F1); /* Gradient background */
            color: white;
            transition: all 0.4s ease-in-out; /* Slower transition */
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4); /* Stronger shadow */
            border-radius: 9999px; /* rounded-full */
            font-weight: 700; /* font-bold */
            padding: 0.8rem 2.5rem; /* More padding for larger buttons */
            font-size: 1.25rem; /* Larger font size for buttons */
            display: inline-block;
            text-decoration: none;
            border: none;
            position: relative;
            z-index: 3;
            margin: 0.5rem; /* Spacing between buttons */
        }
        .btn-custom-primary:hover {
            background: linear-gradient(45deg, #6366F1, #4F46E5); /* Reverse gradient on hover */
            transform: translateY(-5px) scale(1.05); /* More pronounced lift */
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.7); /* Even stronger shadow */
        }

        /* Features Section Cards - Glassmorphism effect */
        .card-custom-shadow {
            background: var(--glass-background) !important;
            backdrop-filter: blur(10px) !important; /* Stronger blur for glass effect */
            border-radius: 1rem !important;
            box-shadow: var(--glass-shadow) !important;
            border: var(--glass-border) !important;
            transition: transform 0.4s ease, box-shadow 0.4s ease; /* Smooth transition */
            color: var(--text-primary-color); /* Default text color for cards */
            text-shadow: var(--text-shadow-light); /* Light text shadow */
        }
        .card-custom-shadow:hover {
            transform: translateY(-12px) scale(1.03); /* More pronounced lift on hover */
            box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important; /* Stronger shadow on hover */
        }

        /* Card body content styling */
        .card-custom-shadow .card-body {
            padding: 2rem;
        }
        .card-custom-shadow h3 {
            font-size: 1.75rem !important; /* Larger headings in feature cards */
            font-weight: 700 !important;
            color: var(--accent-color) !important; /* Distinctive color for feature titles */
            text-shadow: var(--text-shadow-medium);
            margin-bottom: 1rem;
        }
        .card-custom-shadow p {
            font-size: 1.1rem !important; /* Larger paragraphs in feature cards */
            color: rgba(255, 255, 255, 0.9) !important; /* Slightly more opaque text */
            text-shadow: var(--text-shadow-light);
        }

        /* Feature Icons - larger and more distinct */
        .feature-icon-indigo { color: #4F46E5; }
        .feature-icon-purple { color: #8B5CF6; }
        .feature-icon-blue { color: #2563EB; }
        .feature-icon {
            font-size: 4.5rem !important; /* Much larger icons */
            margin-bottom: 1rem;
            filter: drop-shadow(2px 2px 5px rgba(0,0,0,0.6)); /* Shadow for icons */
        }

        /* Call to Action Section styling */
        .bg-gradient-dark {
            background: linear-gradient(to bottom right, rgba(29, 36, 48, 0.8), rgba(41, 51, 67, 0.8)) !important; /* Darker transparent gradient */
            padding: 5rem 1rem;
            text-align: center;
            color: var(--text-primary-color);
        }
        .bg-gradient-dark h2 {
            font-size: 2.5rem; /* Large heading */
            font-weight: 800 !important;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-strong);
            margin-bottom: 1.5rem;
        }
        .bg-gradient-dark p {
            font-size: 1.2rem; /* Larger paragraph */
            opacity: 0.9;
            color: var(--text-primary-color);
            text-shadow: var(--text-shadow-medium);
            margin-bottom: 2.5rem;
        }

        /* Ensure .container-fluid background is transparent where needed */
        .container-fluid {
            background-color: transparent !important;
        }

        /* AdminLTE card specific styles (for the welcome message card within content) */
        .card-general-cleaning, .card-sanitation-facility, .card-daily-status,
        .card-resource-report, .card-monthly-cleaning-report, .card-monthly-sanitation-report,
        .card-employees, .card-photo-reports {
            border-radius: 1rem; /* Rounded corners for consistency */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
            transition: all 0.3s ease-in-out;
            border: none; /* Remove default borders */
        }
        .card-general-cleaning:hover, .card-sanitation-facility:hover, .card-daily-status:hover,
        .card-resource-report:hover, .card-monthly-cleaning-report:hover, .card-monthly-sanitation-report:hover,
        .card-employees:hover, .card-photo-reports:hover {
            transform: translateY(-7px) scale(1.03); /* More lift on hover */
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3); /* Stronger shadow on hover */
        }

        /* Specific card colors (can keep as is, or adjust transparency) */
        .card-general-cleaning { background: linear-gradient(to bottom right, rgba(52, 152, 219, 0.8), rgba(142, 68, 173, 0.8)); }
        .card-sanitation-facility { background: linear-gradient(to bottom right, rgba(155, 89, 182, 0.8), rgba(231, 76, 60, 0.8)); }
        .card-daily-status { background: linear-gradient(to bottom right, rgba(46, 204, 113, 0.8), rgba(241, 196, 15, 0.8)); }
        .card-resource-report { background: linear-gradient(to bottom right, rgba(52, 73, 94, 0.8), rgba(127, 140, 141, 0.8)); }
        .card-monthly-cleaning-report { background: linear-gradient(to bottom right, rgba(230, 126, 34, 0.8), rgba(211, 84, 0, 0.8)); }
        .card-monthly-sanitation-report { background: linear-gradient(to bottom right, rgba(26, 188, 156, 0.8), rgba(39, 174, 96, 0.8)); }
        .card-employees { background: linear-gradient(to bottom right, rgba(109, 40, 217, 0.8), rgba(75, 0, 130, 0.8)); }
        .card-photo-reports { background: linear-gradient(to bottom right, rgba(255, 99, 71, 0.8), rgba(205, 92, 92, 0.8)); }

        /* General text for these cards */
        .card-general-cleaning, .card-sanitation-facility, .card-daily-status,
        .card-resource-report, .card-monthly-cleaning-report, .card-monthly-sanitation-report,
        .card-employees, .card-photo-reports {
            color: white;
        }
        .card-general-cleaning .card-body h3, .card-sanitation-facility .card-body h3, .card-daily-status .card-body h3,
        .card-resource-report .card-body h3, .card-monthly-cleaning-report .card-body h3, .card-monthly-sanitation-report .card-body h3,
        .card-employees .card-body h3, .card-photo-reports .card-body h3 {
            color: white !important; /* Ensure titles are white */
            text-shadow: var(--text-shadow-medium);
        }
        .card-general-cleaning .card-body p, .card-sanitation-facility .card-body p, .card-daily-status .card-body p,
        .card-resource-report .card-body p, .card-monthly-cleaning-report .card-body p, .card-monthly-sanitation-report .card-body p,
        .card-employees .card-body p, .card-photo-reports .card-body p {
            color: rgba(255, 255, 255, 0.9) !important; /* Ensure paragraphs are white and readable */
            text-shadow: var(--text-shadow-light);
        }

        /* Card action buttons within the specific cards */
        .btn-card-action {
            background-color: rgba(255, 255, 255, 0.95); /* Slightly more opaque for better visibility */
            color: #34495e;
            border-radius: 50px;
            font-weight: bold;
            padding: 0.6rem 1.8rem; /* Slightly larger padding */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Stronger shadow */
            transition: all 0.3s ease;
            font-size: 1.05rem; /* Consistent font size */
        }
        .btn-card-action:hover {
            background-color: rgba(255, 255, 255, 1);
            color: #1a1a1a;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }

        /* Animations for sections */
        /* هذه سيتم استبدالها بـ GSAP أو AOS */
        .animate-fade-in-down { animation: fadeInDown 1s ease-out forwards; opacity: 0; position: relative; z-index: 2; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }

        .animate-fade-in-up { animation: fadeInUp 1s ease-out 0.3s forwards; opacity: 0; position: relative; z-index: 2; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .animate-scale-in { animation: scaleIn 1s ease-out 0.6s forwards; opacity: 0; transform: scale(0.8); position: relative; z-index: 2; }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }

        /* New Animated Card Styles */
        .animated-card {
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94); /* Smooth cubic-bezier transition */
            cursor: pointer;
            border-radius: 1rem !important; /* Rounded corners */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Initial shadow */
        }

        .animated-card:hover {
            transform: translateY(-10px) scale(1.03); /* Lift and slight scale on hover */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4); /* Deeper shadow on hover */
            border-color: rgba(255, 255, 255, 0.5) !important; /* Lighter border on hover */
        }

        /* Shine effect on hover */
        .animated-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .animated-card:hover::before {
            left: 100%;
        }

        /* Icon styling within cards */
        .card-icon {
            font-size: 2.5rem; /* Larger icon size within the card body */
            margin-bottom: 0.5rem;
            filter: drop-shadow(1px 1px 3px rgba(0,0,0,0.5));
            transition: transform 0.3s ease-in-out;
        }
        .animated-card:hover .card-icon {
            transform: scale(1.1); /* Slightly enlarge icon on hover */
        }

        /* Background icon in cards */
        .card-bg-icon {
            position: absolute;
            bottom: 15px;
            left: 15px;
            font-size: 4rem !important; /* حجم أكبر لأيقونة الخلفية */
            color: rgba(255, 255, 255, 0.1); /* لون خافت جداً */
            pointer-events: none; /* لضمان عدم تداخلها مع عناصر النقر */
            transition: transform 0.4s ease-out;
        }

        .animated-card:hover .card-bg-icon {
            transform: scale(1.2) rotate(10deg); /* تكبير ودوران بسيط عند التحويم */
        }

        /* Font sizes for titles and text within animated cards */
        .text-xxl { font-size: 1.6rem !important; } /* Custom larger font size */
        .text-xl { font-size: 1.3rem !important; }   /* Custom font size for percentages */
        .text-md { font-size: 1rem !important; }     /* Default paragraph size */

        /* Border radius for AdminLTE cards and progress bars */
        .rounded-xl {
            border-radius: 1.25rem !important; /* حواف مستديرة أكبر */
        }
        .progress {
            background-color: rgba(255, 255, 255, 0.2); /* خلفية شريط التقدم */
            border-radius: 8px; /* حواف مستديرة */
            overflow: hidden; /* لضمان عدم خروج الشريط من الحدود */
        }
        .progress-bar {
            border-radius: 8px; /* حواف مستديرة للشريط الداخلي */
        }

        /* Gradients for Department Goals */
        .bg-gradient-blue { background: linear-gradient(135deg, #007bff, #00a0f0) !important; }
        .bg-gradient-green { background: linear-gradient(135deg, #28a745, #2ed184) !important; }
        .bg-gradient-purple { background: linear-gradient(135deg, #6f42c1, #8a5ee0) !important; }
        .bg-gradient-orange { background: linear-gradient(135deg, #fd7e14, #ff9b4f) !important; }
        .bg-gradient-teal { background: linear-gradient(135deg, #20c997, #2bd6a3) !important; }
        .bg-gradient-pink { background: linear-gradient(135deg, #e83e8c, #f06292) !important; }
        .bg-gradient-indigo { background: linear-gradient(135deg, #6610f2, #8540f8) !important; }
        .bg-gradient-yellow { background: linear-gradient(135deg, #ffc107, #ffd700) !important; }
        .bg-gradient-red { background: linear-gradient(135deg, #dc3545, #e65565) !important; }
        .bg-gradient-cyan { background: linear-gradient(135deg, #17a2b8, #26c6da) !important; }

        /* Darker gradients for Unit Goals to differentiate */
        .bg-gradient-dark-blue { background: linear-gradient(135deg, #0d47a1, #1976d2) !important; }
        .bg-gradient-dark-green { background: linear-gradient(135deg, #1b5e20, #43a047) !important; }
        .bg-gradient-dark-red { background: linear-gradient(135deg, #b71c1c, #d32f2f) !important; }
        .bg-gradient-dark-purple { background: linear-gradient(135deg, #4a148c, #7b1fa2) !important; }

        /* Text glow effect for titles */
        .text-white-glow {
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.6), 0 0 15px rgba(255, 255, 255, 0.4);
            color: white; /* Ensure text remains white */
        }

        /* Main Goal Banner Specific Styles */
        .main-goal-banner {
            margin-bottom: 3.5rem !important; /* مسافة أكبر أسفل البانر */
        }
        .main-goal-banner .main-goal-content {
            display: flex;
            align-items: center;
            justify-content: space-around; /* توزيع متساوٍ للمسافة بين العناصر */
            flex-wrap: wrap; /* للسماح بالعناصر بالانتقال إلى سطر جديد في الشاشات الصغيرة */
            gap: 20px; /* مسافة بين العناصر */
        }

        .main-goal-banner .main-goal-title {
            font-size: 3.5rem; /* حجم خط كبير جداً للعنوان */
            font-weight: 900 !important; /* سمك خط فائق */
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.9); /* ظل نص قوي */
            text-align: center; /* التأكد من توسيط العنوان */
            flex-grow: 1; /* للسماح للعنوان بأخذ المساحة المتاحة */
            min-width: 300px; /* ضمان ألا يتقلص العنوان كثيرًا */
        }
        .main-goal-banner .main-goal-text {
            font-size: 1.8rem; /* حجم خط كبير للنص */
            line-height: 1.5;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8); /* ظل نص أقل قوة */
            flex-basis: 100%; /* النص يأخذ سطرًا كاملاً تحت العنوان والأيقونات */
            margin-top: 15px; /* مسافة أعلى النص */
        }

        .main-goal-banner .icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 120px; /* تحديد عرض لـ wrapper */
            height: 120px; /* تحديد ارتفاع لـ wrapper */
            /* خلفية بسيطة لتبرز الأيقونات بشكل أفضل */
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            overflow: hidden; /* لمنع أي تجاوز */
        }

        .main-goal-banner .fas {
            filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.5)); /* توهج للأيقونة */
            z-index: 2; /* تأكد أن الأيقونة فوق أي عناصر وهمية */
        }

        /* تحديد حركة أيقونة الهدف (اليسرى) بشكل أكثر جاذبية - سيتم التحكم بها بـ GSAP */
        .main-goal-icon-left svg {
            width: 120px;
            height: 120px;
            transform-origin: center center;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }
        .main-goal-icon-left svg path {
            stroke: #00eaff;
            stroke-width: 2;
            fill: transparent;
        }
        .main-goal-icon-left svg path:last-child {
            fill: #00eaff;
        }


        /* حركة أيقونة المسجد (اليمنى) - سيتم التحكم بها بـ GSAP */
        .main-goal-icon-right i {
            color: rgba(255, 255, 255, 0.15); /* لون خافت جداً للمسجد ليكون كخلفية */
            filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.5));
        }

        /* تأثير سهم وهمي لأيقونة الهدف (اختياري) - أزلتها لأن GSAP سيكون أفضل للتحكم بـ SVG */
        /*
        .main-goal-icon-left::before, .main-goal-icon-left::after {
            display: none;
        }
        @keyframes arrowFly {
            0% { left: -50px; opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { left: 100%; opacity: 0; }
        }
        */

        @media (max-width: 768px) {
            .main-goal-banner .main-goal-content {
                flex-direction: column; /* ترتيب العناصر عموديًا على الشاشات الصغيرة */
                gap: 15px; /* تقليل المسافة بين العناصر على الجوال */
            }
            .main-goal-banner .main-goal-title {
                font-size: 2.5rem; /* تصغير العنوان على الشاشات الصغيرة */
                margin: 0; /* إزالة الهوامش الإضافية */
                min-width: unset; /* إزالة الحد الأدنى للعرض */
            }
            .main-goal-banner .main-goal-text {
                font-size: 1.3rem; /* تصغير النص على الشاشات الصغيرة */
                margin-top: 10px; /* تعديل المسافة */
            }
            .main-goal-banner .icon-wrapper {
                width: 90px; /* تصغير حجم الـ wrapper على الشاشات الصغيرة */
                height: 90px;
                margin-bottom: 15px; /* مسافة سفلية لكل أيقونة */
            }
            .main-goal-banner .fas {
                font-size: 4rem !important; /* تصغير الأيقونات على الشاشات الصغيرة */
            }
            /* ترتيب الأيقونات والنص عمودياً على الجوال */
            .main-goal-banner .main-goal-icon-right { order: 1; }
            .main-goal-banner .flex-grow-1 { order: 2; width: 100%; } /* العنوان والنص */
            .main-goal-banner .main-goal-icon-left { order: 3; }

            /* تعطيل تأثير السهم على الجوال إذا كان يسبب مشاكل في الأداء أو العرض */
            /* هذه لم تعد ذات صلة بعد إزالة الـ pseudo-elements */
            /*
            .main-goal-icon-left::before, .main-goal-icon-left::after {
                display: none;
            }
            */
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث نص الترحيب
            function updateGreeting() {
                const hour = new Date().getHours();
                let greeting;
                if (hour < 12) {
                    greeting = 'صباح الخير';
                } else if (hour < 18) {
                    greeting = 'مساء الخير';
                } else {
                    greeting = 'مساء الخير';
                }
                document.getElementById('greeting-text').innerText = greeting;
            }
            updateGreeting();

            // تهيئة AOS (سنستخدمها لبطاقات أهداف الشعبة والوحدات)
            AOS.init({
                once: true,
                duration: 800, // مدة أطول للانيميشن
                easing: 'ease-out-back', // تأثير سلس للدخول
                delay: 100,
            });

            // تسجيل ScrollTrigger plugin
            gsap.registerPlugin(ScrollTrigger);

            // -----------------------------------------------------------
            // تحريكات قسم الهدف الرئيسي للمدينة (باستخدام GSAP)
            // -----------------------------------------------------------
            const mainGoalSection = document.querySelector('[data-animate-section="main-goal"]');
            const mainGoalIcon = document.getElementById('mainGoalIcon');
            const mainGoalTitle = mainGoalSection.querySelector('.main-goal-title');
            const mainGoalText = mainGoalSection.querySelector('.main-goal-text');
            const mosqueIcon = document.getElementById('mosqueIcon');

            // إعداد الحالة الأولية (مخفية وجاهزة للظهور)
            gsap.set([mainGoalIcon, mainGoalTitle, mainGoalText, mosqueIcon], { autoAlpha: 0, y: 50 });

            const mainGoalTimeline = gsap.timeline({
                scrollTrigger: {
                    trigger: mainGoalSection,
                    start: "top center+=100", // يبدأ التحريك عندما يكون الجزء العلوي للقسم 100px فوق مركز الشاشة
                    toggleActions: "play reverse play reverse", // تشغيل عند الدخول والخروج، وعكس عند الرجوع
                    // markers: true, // لتصحيح الأخطاء
                }
            });

            mainGoalTimeline
                .to(mainGoalIcon, {
                    autoAlpha: 1, y: 0, duration: 1, ease: "power3.out",
                    filter: 'drop-shadow(0 0 15px rgba(0, 255, 255, 0.7))',
                    scale: 1.1,
                    rotation: 360,
                    repeat: -1, yoyo: true, repeatDelay: 5 // حركة متكررة
                }, 0) // يبدأ الأنميشن في نفس الوقت (الموقع 0 في الجدول الزمني)
                .to(mainGoalIcon.querySelector('svg path:last-child'), { // تحريك النقطة المركزية لـ SVG
                    fill: '#90CAF9', duration: 1, ease: "power3.out",
                    repeat: -1, yoyo: true, repeatDelay: 5
                }, 0)
                .to(mainGoalIcon.querySelectorAll('svg path:not(:last-child)'), { // تحريك الدوائر الخارجية لـ SVG
                    stroke: '#90CAF9', duration: 1, ease: "power3.out",
                    repeat: -1, yoyo: true, repeatDelay: 5
                }, 0)
                .to([mainGoalTitle, mainGoalText], {
                    autoAlpha: 1, y: 0, duration: 1.2, ease: "power3.out", stagger: 0.2,
                    textShadow: '0 0 15px rgba(0, 255, 255, 0.6), 0 0 25px rgba(0, 255, 255, 0.4)', // تأثير توهج للنص
                    color: '#ffffff', // التأكد من لون النص الأصلي
                }, 0.3) // يبدأ النص بعد الأيقونة بـ 0.3 ثانية
                .to(mosqueIcon, {
                    autoAlpha: 0.25, y: 0, duration: 1, ease: "power3.out",
                    rotation: 15,
                    repeat: -1, yoyo: true, repeatDelay: 6 // حركة خفيفة للمسجد
                }, 0.5); // يبدأ المسجد بعد 0.5 ثانية

            // -----------------------------------------------------------
            // تحريكات قسم الترحيب (باستخدام GSAP)
            // -----------------------------------------------------------
            const welcomeCard = document.querySelector('[data-animate-section="welcome-card"] .card');
            const welcomeTextContent = welcomeCard.querySelector('.welcome-text-content');
            const welcomeChartIcon = document.getElementById('welcomeChartIcon');

            gsap.set(welcomeCard, { autoAlpha: 0, y: 50 });
            gsap.set(welcomeChartIcon, { autoAlpha: 0.25, scale: 0.8 }); // أيقونة تبدأ أصغر وأكثر شفافية

            gsap.timeline({
                scrollTrigger: {
                    trigger: welcomeCard,
                    start: "top center+=100",
                    toggleActions: "play reverse play reverse",
                    // markers: true,
                }
            })
            .to(welcomeCard, { autoAlpha: 1, y: 0, duration: 1, ease: "power3.out" })
            .from(welcomeTextContent.children, {
                autoAlpha: 0, y: 20, duration: 0.8, stagger: 0.15, ease: "power2.out"
            }, "<0.2") // تبدأ بعد قليل من ظهور البطاقة
            .to(welcomeChartIcon, {
                autoAlpha: 0.5, scale: 1, rotation: 360, duration: 1.5, ease: "elastic.out(1, 0.5)",
                repeat: -1, yoyo: true, repeatDelay: 4 // حركة نبضية ودوران
            }, "<0.4"); // تبدأ بعد قليل من ظهور النص

            // -----------------------------------------------------------
            // تحريكات بطاقات موجز الأداء (باستخدام GSAP)
            // -----------------------------------------------------------
            document.querySelectorAll('.performance-card').forEach((card, index) => {
                gsap.set(card, { autoAlpha: 0, y: 50, scale: 0.9 });

                gsap.timeline({
                    scrollTrigger: {
                        trigger: card,
                        start: "top center+=100",
                        toggleActions: "play none none reverse", // تشغيل مرة واحدة عند الدخول
                        // markers: true,
                    }
                })
                .to(card, { autoAlpha: 1, y: 0, scale: 1, duration: 0.8, ease: "back.out(1.7)", delay: index * 0.1 }) // تأخير بين البطاقات
                .from(card.querySelectorAll('.performance-title, .performance-value, .performance-comparison, .performance-icon'), {
                    autoAlpha: 0, y: 20, duration: 0.6, stagger: 0.05, ease: "power2.out"
                }, "<0.3"); // تبدأ العناصر الداخلية بالتحرك بعد قليل من ظهور البطاقة
            });

            // -----------------------------------------------------------
            // تحريكات أشرطة التقدم داخل بطاقات الوحدات (GSAP)
            // -----------------------------------------------------------
            document.querySelectorAll('.unit-goals-list .progress-bar').forEach(progressBar => {
                const percentage = parseInt(progressBar.getAttribute('aria-valuenow'));
                gsap.fromTo(progressBar,
                    { width: 0 },
                    {
                        width: `${percentage}%`,
                        duration: 1.5,
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: progressBar,
                            start: "top bottom", // عندما يكون الشريط في الأسفل من منطقة العرض
                            toggleActions: "play none none reverse",
                            // markers: true,
                        }
                    }
                );
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/home.blade.php ENDPATH**/ ?>