 

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

                    
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view users', 'manage users'])): ?>
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
                                        إنشاء، تعديل، وحذف الأدوار وتعيين الصلاحيات للمستخدمين.
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
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?> 
    <script>
        // AOS (Animate On Scroll) initialization
        AOS.init({
            duration: 800, // Duration of animation
            once: true,    // Whether animation should happen only once - while scrolling down
            mirror: false, // Whether elements should animate out while scrolling past them
        });

        // Add custom styling for dashboard cards
        document.addEventListener('DOMContentLoaded', function() {
            const dashboardCards = document.querySelectorAll('.card-dashboard');
            dashboardCards.forEach(card => {
                // Add a subtle glow on hover
                card.addEventListener('mouseenter', () => {
                    card.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.6), 0 0 20px rgba(0, 123, 255, 0.4)';
                    card.style.transform = 'translateY(-5px) scale(1.01)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.4)';
                    card.style.transform = 'translateY(0) scale(1)';
                });

                // Add a subtle animation to the icon and title on hover
                const icon = card.querySelector('.card-icon-animated');
                const title = card.querySelector('.card-title-animated');
                const arrowIcon = card.querySelector('.icon-arrow-animated');

                if (icon) {
                    card.addEventListener('mouseenter', () => {
                        icon.style.transform = 'scale(1.1) rotate(5deg)';
                        icon.style.color = '#00a0f0'; // Brighter color on hover
                    });
                    card.addEventListener('mouseleave', () => {
                        icon.style.transform = 'scale(1) rotate(0deg)';
                        icon.style.color = 'rgba(255, 255, 255, 0.75)'; // Original color
                    });
                }
                if (title) {
                    card.addEventListener('mouseenter', () => {
                        title.style.transform = 'translateX(5px)';
                        title.style.color = 'white'; // Ensure title is bright
                    });
                    card.addEventListener('mouseleave', () => {
                        title.style.transform = 'translateX(0)';
                        title.style.color = 'rgba(255, 255, 255, 0.9)'; // Original color
                    });
                }
                if (arrowIcon) {
                    card.addEventListener('mouseenter', () => {
                        arrowIcon.style.transform = 'translateX(5px)';
                    });
                    card.addEventListener('mouseleave', () => {
                        arrowIcon.style.transform = 'translateX(0)';
                    });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('styles'); ?> 
    <style>
        /* General Dashboard Card Styling */
        .dashboard-main-card {
            background: linear-gradient(135deg, rgba(42, 56, 75, 0.9), rgba(31, 39, 50, 0.9)); /* Darker, more prominent gradient */
            border-radius: 1.5rem; /* More rounded corners for main card */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5); /* Stronger shadow for main card */
            border: 1px solid rgba(255, 255, 255, 0.15); /* Subtle border for main card */
            backdrop-filter: blur(10px); /* Apply blur to main card */
        }

        .dashboard-main-card-body {
            padding: 2.5rem !important; /* More padding inside the main card */
        }

        .section-title-animated {
            font-size: 2.2rem;
            font-weight: 700;
            color: #e0e0e0;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            animation: fadeInDown 1s ease-out forwards;
        }

        /* Individual Dashboard Cards */
        .card-dashboard {
            background-color: rgba(255, 255, 255, 0.08) !important; /* Slightly transparent white for cards */
            border-radius: 1.25rem !important; /* Rounded corners */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4) !important; /* Soft shadow */
            border: 1px solid rgba(255, 255, 255, 0.2) !important; /* Prominent border */
            backdrop-filter: blur(15px) !important; /* Stronger glassmorphism effect */
            transition: all 0.3s ease-in-out; /* Smooth transitions for hover */
            overflow: hidden; /* Ensure content stays within rounded corners */
            position: relative;
            z-index: 1; /* Ensure cards are above background elements if any */
        }

        .card-dashboard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.01));
            z-index: -1;
            border-radius: 1.25rem;
        }

        .card-dashboard:hover {
            transform: translateY(-5px) scale(1.01); /* Lift and slightly enlarge on hover */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6), 0 0 20px rgba(0, 123, 255, 0.4); /* Enhanced shadow and glow */
        }

        .card-dashboard .card-body {
            color: rgba(255, 255, 255, 0.9); /* Lighter text for better contrast */
        }

        .card-title-animated {
            font-size: 1.6rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
        }

        .card-icon-animated {
            transition: all 0.3s ease-in-out;
        }

        .card-description {
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-card-action {
            background: linear-gradient(90deg, #007bff, #00a0f0); /* Blue gradient button */
            color: white;
            border: none;
            border-radius: 0.75rem; /* Rounded button */
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-card-action:hover {
            background: linear-gradient(90deg, #0056b3, #007bff); /* Darker blue on hover */
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.6);
            transform: translateY(-2px);
        }

        .icon-arrow-animated {
            margin-right: 8px; /* Space between text and arrow */
            transition: transform 0.3s ease-in-out;
        }

        /* Specific card color themes (optional, can be expanded) */
        .card-tasks-general {
            background: linear-gradient(135deg, rgba(30, 144, 255, 0.1), rgba(0, 123, 255, 0.1)) !important;
        }
        .card-tasks-sanitation {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(0, 128, 0, 0.1)) !important;
        }
        .card-service-board {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 165, 0, 0.1)) !important;
        }
        .card-daily-status {
            background: linear-gradient(135deg, rgba(108, 117, 125, 0.1), rgba(80, 80, 80, 0.1)) !important;
        }
        .card-resource-report {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(0, 139, 139, 0.1)) !important;
        }
        .card-monthly-cleaning {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(178, 34, 34, 0.1)) !important;
        }
        .card-monthly-sanitation {
            background: linear-gradient(135deg, rgba(111, 66, 193, 0.1), rgba(75, 0, 130, 0.1)) !important;
        }
        .card-monthly-summary {
            background: linear-gradient(135deg, rgba(255, 99, 71, 0.1), rgba(255, 69, 0, 0.1)) !important;
        }
        .card-employees {
            background: linear-gradient(135deg, rgba(255, 159, 64, 0.1), rgba(255, 140, 0, 0.1)) !important;
        }
        .card-photo-reports {
            background: linear-gradient(135deg, rgba(128, 0, 128, 0.1), rgba(75, 0, 130, 0.1)) !important;
        }
        .card-background-settings {
            background: linear-gradient(135deg, rgba(0, 128, 128, 0.1), rgba(0, 100, 0, 0.1)) !important;
        }
        .card-actual-results {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1), rgba(218, 165, 32, 0.1)) !important;
        }
        .card-resource-trackings {
            background: linear-gradient(135deg, rgba(128, 128, 0, 0.1), rgba(85, 107, 47, 0.1)) !important;
        }
        .card-unit-goals {
            background: linear-gradient(135deg, rgba(255, 0, 255, 0.1), rgba(199, 21, 133, 0.1)) !important;
        }
        .card-gilbert-charts {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(0, 206, 209, 0.1)) !important;
        }
        .card-surveys {
            background: linear-gradient(135deg, rgba(138, 43, 226, 0.1), rgba(75, 0, 130, 0.1)) !important;
        }
        .card-survey-charts {
            background: linear-gradient(135deg, rgba(255, 105, 180, 0.1), rgba(255, 20, 147, 0.1)) !important;
        }
        .card-notifications {
            background: linear-gradient(135deg, rgba(255, 99, 71, 0.1), rgba(255, 69, 0, 0.1)) !important;
        }
        .card-users {
            background: linear-gradient(135deg, rgba(70, 130, 180, 0.1), rgba(65, 105, 225, 0.1)) !important;
        }
        .card-roles {
            background: linear-gradient(135deg, rgba(255, 20, 147, 0.1), rgba(199, 21, 133, 0.1)) !important;
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 767.98px) {
            .dashboard-main-card-body {
                padding: 1.5rem !important;
            }
            .section-title-animated {
                font-size: 1.8rem;
            }
            .card-title-animated {
                font-size: 1.3rem;
            }
            .card-description {
                font-size: 0.85rem;
            }
            .btn-card-action {
                font-size: 0.9rem;
                padding: 8px 15px;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/dashboard.blade.php ENDPATH**/ ?>