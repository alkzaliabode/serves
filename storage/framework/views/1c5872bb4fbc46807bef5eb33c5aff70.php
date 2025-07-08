 

<?php $__env->startSection('title', 'لوحة التحكم'); ?> 

<?php $__env->startSection('page_title', 'لوحة التحكم'); ?> 

<?php $__env->startSection('breadcrumb'); ?> 
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item active">لوحة التحكم</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?> 
    <div class="container-fluid py-4"> 
        <div class="card card-outline card-info shadow-lg border-0 dashboard-main-card"> 
            <div class="card-body p-4 dashboard-main-card-body">
                <h4 class="text-white mb-4 text-center section-title-animated">نظرة عامة سريعة على النظام</h4>
                <div class="row dashboard-cards-container">

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view general cleaning tasks', 'manage general cleaning tasks'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-tasks-general h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="100">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">مهام النظافة العامة</h3>
                                        <i class="fas fa-broom text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        إدارة وتتبع مهام النظافة اليومية في الأقسام المختلفة بكفاءة.
                                    </p>
                                    <a href="<?php echo e(route('general-cleaning-tasks.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto"> 
                                        عرض التفاصيل
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view sanitation facility tasks', 'manage sanitation facility tasks'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-tasks-sanitation h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="200">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">مهام المنشآت الصحية</h3>
                                        <i class="fas fa-hospital text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        متابعة المهام الخاصة بالنظافة والتعقيم في المنشآت الصحية بدقة.
                                    </p>
                                    <a href="<?php echo e(route('sanitation-facility-tasks.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض التفاصيل
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view service tasks board')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-service-board h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="300">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">لوحة مهام الشُعبة الخدمية</h3>
                                        <i class="fas fa-columns text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        عرض شامل لجميع المهام والمشاريع الخدمية الجارية وتتبعها.
                                    </p>
                                    <a href="<?php echo e(route('service-tasks.board.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض اللوحة
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view daily statuses', 'manage daily statuses'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-daily-status h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="400">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">الموقف اليومي</h3>
                                        <i class="fas fa-clipboard-list text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تسجيل ومراجعة الحالات اليومية للموارد والعمليات بانتظام.
                                    </p>
                                    <a href="<?php echo e(route('daily-statuses.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض الموقف
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view resource report')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-resource-report h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="500">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">تقرير الموارد</h3>
                                        <i class="fas fa-chart-pie text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تقارير مفصلة عن استخدام وتوفر الموارد المختلفة لاتخاذ القرارات.
                                    </p>
                                    <a href="<?php echo e(route('resource-report.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض التقرير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view monthly cleaning report')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-monthly-cleaning h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="600">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">تقرير النظافة العامة الشهري</h3>
                                        <i class="fas fa-chart-bar text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تقارير شهرية لأداء مهام النظافة العامة وتقييمها الدوري.
                                    </p>
                                    <a href="<?php echo e(route('monthly-cleaning-report.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض التقرير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view monthly sanitation report')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-monthly-sanitation h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="700">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">تقرير المنشآت الصحية الشهري</h3>
                                        <i class="fas fa-file-medical text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تقارير شهرية خاصة بأداء النظافة والتعقيم في المنشآت الصحية.
                                    </p>
                                    <a href="<?php echo e(route('monthly-sanitation-report.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض التقرير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view monthly summary', 'manage monthly summary'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-monthly-summary h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="750">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">الملخص الشهري</h3>
                                        <i class="fas fa-calendar-alt text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        عرض ملخص شامل للحضور والانصراف والإجازات والغيابات للموظفين شهرياً.
                                    </p>
                                    <a href="<?php echo e(route('monthly-summary.show')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض الملخص
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view employees', 'manage employees'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-employees h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="800">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">الموظفين</h3>
                                        <i class="fas fa-users text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        إدارة بيانات الموظفين، تتبع الحضور، وتقييم الأداء.
                                    </p>
                                    <a href="<?php echo e(route('employees.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض القائمة
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view photo reports', 'manage photo reports'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-photo-reports h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="900">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">التقارير المصورة</h3>
                                        <i class="fas fa-images text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        مكتبة شاملة للتقارير الموثقة بالصور والفيديوهات للمشاريع.
                                    </p>
                                    <a href="<?php echo e(route('photo_reports.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض التقارير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage background settings')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-background-settings h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1000">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">إعدادات الخلفية</h3>
                                        <i class="fas fa-image text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تخصيص خلفيات لوحة التحكم والصفحات المختلفة لتعزيز الهوية.
                                    </p>
                                    <a href="<?php echo e(route('background-settings.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        ضبط الإعدادات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view actual results', 'manage actual results'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-actual-results h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1100">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">النتائج الفعلية</h3>
                                        <i class="fas fa-chart-line text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        مقارنة الأداء الفعلي بالأهداف المحددة وتحليل الانحرافات.
                                    </p>
                                    <a href="<?php echo e(route('actual-results.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض النتائج
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view resource trackings', 'manage resource trackings'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-resource-trackings h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1200">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">تتبع الموارد</h3>
                                        <i class="fas fa-boxes text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تتبع دقيق لحركة ومخزون الموارد المختلفة لضمان التوفر.
                                    </p>
                                    <a href="<?php echo e(route('resource-trackings.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        تتبع الموارد
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view unit goals', 'manage unit goals'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-unit-goals h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1300">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">أهداف الوحدات</h3>
                                        <i class="fas fa-bullseye text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تحديد وتتبع الأهداف الاستراتيجية لكل وحدة عمل بفعالية.
                                    </p>
                                    <a href="<?php echo e(route('unit-goals.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        إدارة الأهداف
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view gilbert triangle chart')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-gilbert-charts h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1400">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">مخطط جلبرت</h3>
                                        <i class="fas fa-project-diagram text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        عرض المهام والعلاقات بينها باستخدام مخطط جلبرت التفاعلي.
                                    </p>
                                    <a href="<?php echo e(route('charts.gilbert-triangle.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض المخطط
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view surveys', 'manage surveys'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-surveys h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1500">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">استبيانات رضا الزائرين</h3>
                                        <i class="fas fa-poll text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        إنشاء وإدارة وتحليل استبيانات لجمع آراء الزائرين وتحسين الخدمات.
                                    </p>
                                    <a href="<?php echo e(route('surveys.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        إدارة الاستبيانات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view survey statistics')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-survey-charts h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1600">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">إحصائيات الاستبيانات</h3>
                                        <i class="fas fa-chart-line text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        عرض وتحليل نتائج استبيانات رضا الزائرين عبر الرسوم البيانية المتطورة.
                                    </p>
                                    <a href="<?php echo e(route('charts.surveys.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        عرض الإحصائيات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view notifications', 'manage notifications'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-notifications h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1700">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">الإشعارات</h3>
                                        <i class="far fa-bell text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        إدارة وتكوين إشعارات النظام للمستخدمين لضمان التواصل الفعال.
                                    </p>
                                    <a href="<?php echo e(route('notifications.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        إدارة الإشعارات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage users')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-users h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1800">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">إدارة المستخدمين</h3>
                                        <i class="fas fa-user-shield text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        إنشاء، تعديل، وحذف حسابات المستخدمين وصلاحياتهم بدقة.
                                    </p>
                                    <a href="<?php echo e(route('users.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        إدارة المستخدمين
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage roles')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-roles h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="1900">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">إدارة الأدوار</h3>
                                        <i class="fas fa-user-tag text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4 card-description">
                                        تحديد الأدوار والصلاحيات المختلفة للمستخدمين لضبط الوصول.
                                    </p>
                                    <a href="<?php echo e(route('roles.index')); ?>"
                                       class="btn btn-card-action text-decoration-none mt-auto">
                                        إدارة الأدوار
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card card-dashboard card-profile h-100 animate__animated animate__fadeInUp animate__faster" data-aos="fade-up" data-aos-delay="2000">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white-shadow card-title-animated">الملف الشخصي</h3>
                                    <i class="fas fa-user-circle text-white fs-1 opacity-75 card-icon-animated"></i>
                                </div>
                                <p class="text-white-light mb-4 card-description">
                                    تحديث معلوماتك الشخصية وإعدادات الحساب.
                                </p>
                                <a href="<?php echo e(route('profile.edit')); ?>"
                                   class="btn btn-card-action text-decoration-none mt-auto">
                                    عرض الملف الشخصي
                                    <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div> 
    </div> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- AOS (Animate On Scroll) Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800, // duration of animation
            once: true,    // whether animation should happen only once
        });
    </script>
    <style>
        /* Custom styles for dashboard cards */
        .dashboard-main-card {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); /* Darker gradient */
            border-radius: 1rem;
            overflow: hidden; /* Ensures rounded corners apply to children */
        }

        .dashboard-main-card-body {
            padding: 2.5rem !important;
        }

        .section-title-animated {
            font-size: 2.2rem;
            color: #ecf0f1; /* Light grey */
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease-out;
        }

        /* Card base styling */
        .card-dashboard {
            background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.8rem;
            backdrop-filter: blur(5px); /* Frosted glass effect */
            transition: all 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .card-dashboard:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Card specific gradients */
        .card-tasks-general { background: linear-gradient(45deg, #1abc9c, #16a085); } /* Turquoise */
        .card-tasks-sanitation { background: linear-gradient(45deg, #3498db, #2980b9); } /* Peter River */
        .card-service-board { background: linear-gradient(45deg, #9b59b6, #8e44ad); } /* Amethyst */
        .card-daily-status { background: linear-gradient(45deg, #f1c40f, #f39c12); } /* Sunflower */
        .card-resource-report { background: linear-gradient(45deg, #e67e22, #d35400); } /* Carrot */
        .card-monthly-cleaning { background: linear-gradient(45deg, #e74c3c, #c0392b); } /* Alizarin */
        .card-monthly-sanitation { background: linear-gradient(45deg, #2ecc71, #27ae60); } /* Emerald */
        .card-monthly-summary { background: linear-gradient(45deg, #6c757d, #495057); } /* Gray */
        .card-employees { background: linear-gradient(45deg, #34495e, #2c3e50); } /* Wet Asphalt */
        .card-photo-reports { background: linear-gradient(45deg, #f39c12, #e67e22); } /* Orange */
        .card-background-settings { background: linear-gradient(45deg, #95a5a6, #7f8c8d); } /* Concrete */
        .card-actual-results { background: linear-gradient(45deg, #1abc9c, #16a085); } /* Turquoise */
        .card-resource-trackings { background: linear-gradient(45deg, #3498db, #2980b9); } /* Peter River */
        .card-unit-goals { background: linear-gradient(45deg, #9b59b6, #8e44ad); } /* Amethyst */
        .card-gilbert-charts { background: linear-gradient(45deg, #f1c40f, #f39c12); } /* Sunflower */
        .card-surveys { background: linear-gradient(45deg, #e67e22, #d35400); } /* Carrot */
        .card-survey-charts { background: linear-gradient(45deg, #e74c3c, #c0392b); } /* Alizarin */
        .card-notifications { background: linear-gradient(45deg, #2ecc71, #27ae60); } /* Emerald */
        .card-users { background: linear-gradient(45deg, #34495e, #2c3e50); } /* Wet Asphalt */
        .card-roles { background: linear-gradient(45deg, #7f8c8d, #95a5a6); } /* Asbestos */
        .card-profile { background: linear-gradient(45deg, #c0392b, #e74c3c); } /* Pomegranate */


        /* Card content styling */
        .card-dashboard .h3 {
            color: white;
            font-size: 1.6rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        }

        .card-dashboard .card-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .card-dashboard .card-icon-animated {
            font-size: 3.5rem;
            color: rgba(255, 255, 255, 0.7);
            transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        .card-dashboard:hover .card-icon-animated {
            transform: scale(1.1) rotate(5deg);
            color: white;
        }

        .btn-card-action {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-card-action:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-card-action .icon-arrow-animated {
            margin-right: 8px; /* Space between text and icon */
            transition: transform 0.3s ease-in-out;
        }

        .btn-card-action:hover .icon-arrow-animated {
            transform: translateX(5px);
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .dashboard-main-card-body {
                padding: 1.5rem !important;
            }
            .section-title-animated {
                font-size: 1.8rem;
            }
            .card-dashboard .h3 {
                font-size: 1.4rem;
            }
            .card-dashboard .card-icon-animated {
                font-size: 3rem;
            }
            .btn-card-action {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/dashboard.blade.php ENDPATH**/ ?>