 

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
                                    تعديل المعلومات الشخصية وإعدادات الحساب الخاص بك بسهولة.
                                </p>
                                <a href="<?php echo e(route('profile.edit')); ?>"
                                   class="btn btn-card-action text-decoration-none mt-auto">
                                    تعديل الملف
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

<?php $__env->startPush('styles'); ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <style>
        /* Global variables for consistent styling */
        :root {
            --primary-blue: #0A1931;
            --secondary-blue: #163152;
            --accent-blue: #2A607F;
            --text-white: #ffffff;
            --text-light-white: rgba(255, 255, 255, 0.85);
            --card-bg-start: rgba(255, 255, 255, 0.05); /* Increased opacity for better visibility against dark background */
            --card-bg-end: rgba(255, 255, 255, 0.1);   /* Slight gradient for depth */
            --card-border: rgba(255, 255, 255, 0.15); /* More visible border */
            --card-shadow: rgba(0, 0, 0, 0.3);        /* Stronger, but still subtle shadow */
            --card-hover-bg-start: rgba(255, 255, 255, 0.1);
            --card-hover-bg-end: rgba(255, 255, 255, 0.15);
            --card-hover-border: rgba(255, 255, 255, 0.3);
            --button-bg: #4A90E2; /* A clear, appealing blue for buttons */
            --button-hover-bg: #357ABD;
            --icon-color: #ffffff;
            --icon-opacity: 0.85; /* Slightly more opaque */
            --transition-speed: 0.3s;
        }

        body {
            background-color: var(--primary-blue); /* Ensure consistent dark background */
        }

        .dashboard-main-card {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 15px; /* Slightly more rounded */
            overflow: hidden; /* Ensures child elements respect border-radius */
            box-shadow: 0 10px 30px var(--card-shadow); /* Enhanced shadow */
            transition: transform var(--transition-speed) ease-in-out, box-shadow var(--transition-speed) ease-in-out;
        }

        .dashboard-main-card:hover {
            transform: translateY(-3px); /* Subtle lift on hover */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        }

        .dashboard-main-card-body {
            padding: 3rem !important; /* More internal padding */
        }

        .section-title-animated {
            font-size: 2.2rem; /* Larger heading */
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4); /* Deeper text shadow */
            color: var(--text-white);
            position: relative;
            animation: fadeInScale 1s ease-out forwards; /* Initial animation for the title */
            margin-bottom: 2.5rem !important;
        }

        /* Keyframes for title animation */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .dashboard-cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center cards if not filling row */
            gap: 20px; /* Space between cards */
        }

        .card-dashboard {
            background: linear-gradient(145deg, var(--card-bg-start), var(--card-bg-end));
            border: 1px solid var(--card-border);
            border-radius: 12px; /* Consistent rounded corners */
            transition: all var(--transition-speed) ease-in-out; /* Smooth transitions for all properties */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Initial subtle shadow */
            position: relative;
            overflow: hidden; /* Hide anything overflowing, especially for animations */
        }

        .card-dashboard:hover {
            transform: translateY(-5px) scale(1.02); /* More pronounced lift and slight scale */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4), 0 0 0 3px var(--accent-blue); /* Stronger shadow and accent border */
            background: linear-gradient(145deg, var(--card-hover-bg-start), var(--card-hover-bg-end));
        }

        .card-dashboard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.05) 0%, transparent 70%); /* Subtle light source effect */
            pointer-events: none;
            z-index: 1;
            opacity: 0;
            transition: opacity var(--transition-speed) ease-in-out;
        }

        .card-dashboard:hover::before {
            opacity: 1;
        }

        .card-body {
            position: relative;
            z-index: 2; /* Ensure content is above the pseudo-element */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%; /* Ensures content stretches vertically */
        }

        .card-title-animated {
            font-size: 1.5rem;
            color: var(--text-white);
            margin-bottom: 0.75rem;
            position: relative;
            padding-bottom: 5px; /* Space for underline */
        }

        .card-title-animated::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0%;
            height: 2px;
            background-color: var(--button-bg);
            transition: width var(--transition-speed) ease-in-out;
        }

        .card-dashboard:hover .card-title-animated::after {
            width: 100%;
        }

        .card-description {
            font-size: 0.95rem;
            color: var(--text-light-white);
            line-height: 1.6;
            flex-grow: 1; /* Allows description to take up available space */
        }

        .card-icon-animated {
            font-size: 3.5rem !important; /* Larger icons */
            color: var(--icon-color);
            opacity: var(--icon-opacity);
            transition: transform var(--transition-speed) ease-in-out, opacity var(--transition-speed) ease-in-out;
        }

        .card-dashboard:hover .card-icon-animated {
            transform: scale(1.1) rotate(5deg); /* Slight scale and rotation on hover */
            opacity: 1; /* Full opacity on hover */
        }

        .btn-card-action {
            background-color: var(--button-bg);
            color: var(--text-white);
            border: none;
            border-radius: 8px; /* Slightly more rounded buttons */
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color var(--transition-speed) ease-in-out, transform var(--transition-speed) ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px; /* Space between text and icon */
        }

        .btn-card-action:hover {
            background-color: var(--button-hover-bg);
            transform: translateY(-2px); /* Subtle lift */
            color: var(--text-white); /* Keep text white on hover */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .icon-arrow-animated {
            transition: transform var(--transition-speed) ease-in-out;
        }

        .btn-card-action:hover .icon-arrow-animated {
            transform: translateX(5px); /* Move arrow to the right on hover */
        }

        /* Specific card color themes (examples) */
        .card-tasks-general {
            background: linear-gradient(145deg, rgba(30, 90, 150, 0.6), rgba(50, 120, 180, 0.7));
            border-color: rgba(60, 130, 190, 0.3);
        }
        .card-tasks-general:hover {
            background: linear-gradient(145deg, rgba(40, 100, 160, 0.7), rgba(60, 130, 190, 0.8));
            border-color: rgba(70, 140, 200, 0.5);
        }

        .card-tasks-sanitation {
            background: linear-gradient(145deg, rgba(120, 50, 180, 0.6), rgba(150, 70, 200, 0.7));
            border-color: rgba(160, 80, 210, 0.3);
        }
        .card-tasks-sanitation:hover {
            background: linear-gradient(145deg, rgba(130, 60, 190, 0.7), rgba(160, 80, 210, 0.8));
            border-color: rgba(170, 90, 220, 0.5);
        }

        .card-service-board {
            background: linear-gradient(145deg, rgba(40, 140, 120, 0.6), rgba(60, 160, 140, 0.7));
            border-color: rgba(70, 170, 150, 0.3);
        }
        .card-service-board:hover {
            background: linear-gradient(145deg, rgba(50, 150, 130, 0.7), rgba(70, 170, 150, 0.8));
            border-color: rgba(80, 180, 160, 0.5);
        }

        .card-daily-status {
            background: linear-gradient(145deg, rgba(180, 100, 40, 0.6), rgba(200, 120, 60, 0.7));
            border-color: rgba(210, 130, 70, 0.3);
        }
        .card-daily-status:hover {
            background: linear-gradient(145deg, rgba(190, 110, 50, 0.7), rgba(210, 130, 70, 0.8));
            border-color: rgba(220, 140, 80, 0.5);
        }

        .card-resource-report {
            background: linear-gradient(145deg, rgba(80, 100, 200, 0.6), rgba(100, 120, 220, 0.7));
            border-color: rgba(110, 130, 230, 0.3);
        }
        .card-resource-report:hover {
            background: linear-gradient(145deg, rgba(90, 110, 210, 0.7), rgba(110, 130, 230, 0.8));
            border-color: rgba(120, 140, 240, 0.5);
        }

        .card-monthly-cleaning {
            background: linear-gradient(145deg, rgba(200, 60, 80, 0.6), rgba(220, 80, 100, 0.7));
            border-color: rgba(230, 90, 110, 0.3);
        }
        .card-monthly-cleaning:hover {
            background: linear-gradient(145deg, rgba(210, 70, 90, 0.7), rgba(230, 90, 110, 0.8));
            border-color: rgba(240, 100, 120, 0.5);
        }

        .card-monthly-sanitation {
            background: linear-gradient(145deg, rgba(100, 180, 50, 0.6), rgba(120, 200, 70, 0.7));
            border-color: rgba(130, 210, 80, 0.3);
        }
        .card-monthly-sanitation:hover {
            background: linear-gradient(145deg, rgba(110, 190, 60, 0.7), rgba(130, 210, 80, 0.8));
            border-color: rgba(140, 220, 90, 0.5);
        }

        .card-employees {
            background: linear-gradient(145deg, rgba(25, 150, 150, 0.6), rgba(45, 170, 170, 0.7));
            border-color: rgba(55, 180, 180, 0.3);
        }
        .card-employees:hover {
            background: linear-gradient(145deg, rgba(35, 160, 160, 0.7), rgba(55, 180, 180, 0.8));
            border-color: rgba(65, 190, 190, 0.5);
        }

        .card-photo-reports {
            background: linear-gradient(145deg, rgba(170, 90, 20, 0.6), rgba(190, 110, 40, 0.7));
            border-color: rgba(200, 120, 50, 0.3);
        }
        .card-photo-reports:hover {
            background: linear-gradient(145deg, rgba(180, 100, 30, 0.7), rgba(200, 120, 50, 0.8));
            border-color: rgba(210, 130, 60, 0.5);
        }

        .card-background-settings {
            background: linear-gradient(145deg, rgba(90, 70, 160, 0.6), rgba(110, 90, 180, 0.7));
            border-color: rgba(120, 100, 190, 0.3);
        }
        .card-background-settings:hover {
            background: linear-gradient(145deg, rgba(100, 80, 170, 0.7), rgba(120, 100, 190, 0.8));
            border-color: rgba(130, 110, 200, 0.5);
        }

        .card-actual-results {
            background: linear-gradient(145deg, rgba(20, 160, 190, 0.6), rgba(40, 180, 210, 0.7));
            border-color: rgba(50, 190, 220, 0.3);
        }
        .card-actual-results:hover {
            background: linear-gradient(145deg, rgba(30, 170, 200, 0.7), rgba(50, 190, 220, 0.8));
            border-color: rgba(60, 200, 230, 0.5);
        }

        .card-resource-trackings {
            background: linear-gradient(145deg, rgba(200, 150, 20, 0.6), rgba(220, 170, 40, 0.7));
            border-color: rgba(230, 180, 50, 0.3);
        }
        .card-resource-trackings:hover {
            background: linear-gradient(145deg, rgba(210, 160, 30, 0.7), rgba(230, 180, 50, 0.8));
            border-color: rgba(240, 190, 60, 0.5);
        }

        .card-unit-goals {
            background: linear-gradient(145deg, rgba(100, 70, 200, 0.6), rgba(120, 90, 220, 0.7));
            border-color: rgba(130, 100, 230, 0.3);
        }
        .card-unit-goals:hover {
            background: linear-gradient(145deg, rgba(110, 80, 210, 0.7), rgba(130, 100, 230, 0.8));
            border-color: rgba(140, 110, 240, 0.5);
        }

        .card-gilbert-charts {
            background: linear-gradient(145deg, rgba(70, 190, 90, 0.6), rgba(90, 210, 110, 0.7));
            border-color: rgba(100, 220, 120, 0.3);
        }
        .card-gilbert-charts:hover {
            background: linear-gradient(145deg, rgba(80, 200, 100, 0.7), rgba(100, 220, 120, 0.8));
            border-color: rgba(110, 230, 130, 0.5);
        }

        .card-surveys {
            background: linear-gradient(145deg, rgba(230, 100, 50, 0.6), rgba(250, 120, 70, 0.7));
            border-color: rgba(260, 130, 80, 0.3);
        }
        .card-surveys:hover {
            background: linear-gradient(145deg, rgba(240, 110, 60, 0.7), rgba(260, 130, 80, 0.8));
            border-color: rgba(270, 140, 90, 0.5);
        }

        .card-survey-charts {
            background: linear-gradient(145deg, rgba(90, 150, 230, 0.6), rgba(110, 170, 250, 0.7));
            border-color: rgba(120, 180, 260, 0.3);
        }
        .card-survey-charts:hover {
            background: linear-gradient(145deg, rgba(100, 160, 240, 0.7), rgba(120, 180, 260, 0.8));
            border-color: rgba(130, 190, 270, 0.5);
        }

        .card-notifications {
            background: linear-gradient(145deg, rgba(220, 70, 150, 0.6), rgba(240, 90, 170, 0.7));
            border-color: rgba(250, 100, 180, 0.3);
        }
        .card-notifications:hover {
            background: linear-gradient(145deg, rgba(230, 80, 160, 0.7), rgba(250, 100, 180, 0.8));
            border-color: rgba(260, 110, 190, 0.5);
        }

        .card-users {
            background: linear-gradient(145deg, rgba(50, 100, 200, 0.6), rgba(70, 120, 220, 0.7));
            border-color: rgba(80, 130, 230, 0.3);
        }
        .card-users:hover {
            background: linear-gradient(145deg, rgba(60, 110, 210, 0.7), rgba(80, 130, 230, 0.8));
            border-color: rgba(90, 140, 240, 0.5);
        }

        .card-roles {
            background: linear-gradient(145deg, rgba(150, 50, 200, 0.6), rgba(170, 70, 220, 0.7));
            border-color: rgba(180, 80, 230, 0.3);
        }
        .card-roles:hover {
            background: linear-gradient(145deg, rgba(160, 60, 210, 0.7), rgba(180, 80, 230, 0.8));
            border-color: rgba(190, 90, 240, 0.5);
        }

        .card-profile {
            background: linear-gradient(145deg, rgba(200, 80, 50, 0.6), rgba(220, 100, 70, 0.7));
            border-color: rgba(230, 110, 80, 0.3);
        }
        .card-profile:hover {
            background: linear-gradient(145deg, rgba(210, 90, 60, 0.7), rgba(230, 110, 80, 0.8));
            border-color: rgba(240, 120, 90, 0.5);
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .col-md-6.col-lg-4 {
                flex: 0 0 50%; /* On medium screens, make them 2 columns */
                max-width: 50%;
            }
        }

        @media (max-width: 767.98px) {
            .col-md-6.col-lg-4 {
                flex: 0 0 100%; /* On small screens, make them single column */
                max-width: 100%;
            }
        }

        /* Animations for AOS */
        [data-aos="fade-up"] {
            transform: translateY(20px);
            opacity: 0;
            transition-property: transform, opacity;
        }
        [data-aos="fade-up"].aos-animate {
            transform: translateY(0);
            opacity: 1;
        }

    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,     // animation duration
                easing: 'ease-out-quad', // easing for animation
                once: true,        // animate only once
                mirror: false      // whether elements should animate out while scrolling past them
            });

            // Optional: Add a subtle parallax effect to the main card background
            const mainCardBody = document.querySelector('.dashboard-main-card-body');
            if (mainCardBody) {
                document.addEventListener('mousemove', (e) => {
                    const rect = mainCardBody.getBoundingClientRect();
                    const x = (e.clientX - rect.left) / rect.width;
                    const y = (e.clientY - rect.top) / rect.height;

                    const xTilt = (x - 0.5) * 10; // -5 to 5 degrees
                    const yTilt = (y - 0.5) * -10; // -5 to 5 degrees

                    mainCardBody.style.setProperty('transform', `perspective(1000px) rotateX(${yTilt}deg) rotateY(${xTilt}deg)`);
                    mainCardBody.style.setProperty('transition', 'none'); // Disable transition during mouse movement
                });

                mainCardBody.addEventListener('mouseleave', () => {
                    mainCardBody.style.setProperty('transition', 'transform 0.5s ease-in-out');
                    mainCardBody.style.setProperty('transform', 'perspective(1000px) rotateX(0deg) rotateY(0deg)');
                });
            }

            // Animate card titles and icons on hover using JS for more control
            document.querySelectorAll('.card-dashboard').forEach(card => {
                const title = card.querySelector('.card-title-animated');
                const icon = card.querySelector('.card-icon-animated');

                card.addEventListener('mouseenter', () => {
                    if (title) title.classList.add('animate__swing'); // Animate.css for title
                    if (icon) icon.classList.add('animate__pulse'); // Animate.css for icon
                });

                card.addEventListener('mouseleave', () => {
                    if (title) title.classList.remove('animate__swing');
                    if (icon) icon.classList.remove('animate__pulse');
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/dashboard.blade.php ENDPATH**/ ?>