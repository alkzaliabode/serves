 

<?php $__env->startSection('title', 'لوحة التحكم'); ?> 

<?php $__env->startSection('page_title', 'لوحة التحكم'); ?> 

<?php $__env->startSection('breadcrumb'); ?> 
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item active">لوحة التحكم</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?> 
    <div class="container-fluid py-4"> 
        <div class="card card-outline card-info shadow-lg"> 
            <div class="card-body p-4" style="background: linear-gradient(135deg, rgba(10, 25, 49, 0.9), rgba(26, 77, 111, 0.9)); border-radius: 10px;">
                <h4 class="text-white mb-4 text-center section-title-animated">نظرة عامة سريعة</h4>
                <div class="row dashboard-cards-container">

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view general cleaning tasks', 'manage general cleaning tasks'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-tasks-general h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">مهام النظافة العامة</h3>
                                        <i class="fas fa-broom text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        إدارة وتتبع مهام النظافة اليومية في الأقسام المختلفة.
                                    </p>
                                    <a href="<?php echo e(route('general-cleaning-tasks.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض التفاصيل
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view sanitation facility tasks', 'manage sanitation facility tasks'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-tasks-sanitation h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">مهام المنشآت الصحية</h3>
                                        <i class="fas fa-hospital text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        متابعة المهام الخاصة بالنظافة والتعقيم في المنشآت الصحية.
                                    </p>
                                    <a href="<?php echo e(route('sanitation-facility-tasks.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض التفاصيل
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view service tasks board')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-service-board h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="300">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">لوحة مهام الشُعبة الخدمية</h3>
                                        <i class="fas fa-columns text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        عرض شامل لجميع المهام والمشاريع الخدمية الجارية.
                                    </p>
                                    <a href="<?php echo e(route('service-tasks.board.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض اللوحة
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view daily statuses', 'manage daily statuses'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-daily-status h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="400">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">الموقف اليومي</h3>
                                        <i class="fas fa-clipboard-list text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تسجيل ومراجعة الحالات اليومية للموارد والعمليات.
                                    </p>
                                    <a href="<?php echo e(route('daily-statuses.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض الموقف
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view resource report')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-resource-report h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="500">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">تقرير الموارد</h3>
                                        <i class="fas fa-chart-pie text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تقارير مفصلة عن استخدام وتوفر الموارد المختلفة.
                                    </p>
                                    <a href="<?php echo e(route('resource-report.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض التقرير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view monthly cleaning report')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-monthly-cleaning h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="600">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">تقرير النظافة العامة الشهري</h3>
                                        <i class="fas fa-chart-bar text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تقارير شهرية لأداء مهام النظافة العامة وتقييمها.
                                    </p>
                                    <a href="<?php echo e(route('monthly-cleaning-report.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض التقرير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view monthly sanitation report')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-monthly-sanitation h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="700">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">تقرير المنشآت الصحية الشهري</h3>
                                        <i class="fas fa-file-medical text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تقارير شهرية خاصة بأداء النظافة والتعقيم في المنشآت الصحية.
                                    </p>
                                    <a href="<?php echo e(route('monthly-sanitation-report.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض التقرير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view employees', 'manage employees'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-employees h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="800">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">الموظفين</h3>
                                        <i class="fas fa-users text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        إدارة بيانات الموظفين، الحضور، والأداء.
                                    </p>
                                    <a href="<?php echo e(route('employees.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض القائمة
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view photo reports', 'manage photo reports'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-photo-reports h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="900">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">التقارير المصورة</h3>
                                        <i class="fas fa-images text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        مكتبة للتقارير الموثقة بالصور والفيديوهات.
                                    </p>
                                    <a href="<?php echo e(route('photo_reports.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض التقارير
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage background settings')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-background-settings h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1000">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">إعدادات الخلفية</h3>
                                        <i class="fas fa-image text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تخصيص خلفيات لوحة التحكم والصفحات المختلفة.
                                    </p>
                                    <a href="<?php echo e(route('background-settings.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        ضبط الإعدادات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view actual results', 'manage actual results'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-actual-results h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1100">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">النتائج الفعلية</h3>
                                        <i class="fas fa-chart-line text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        مقارنة الأداء الفعلي بالأهداف المحددة.
                                    </p>
                                    <a href="<?php echo e(route('actual-results.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض النتائج
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view resource trackings', 'manage resource trackings'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-resource-trackings h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1200">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">تتبع الموارد</h3>
                                        <i class="fas fa-boxes text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تتبع دقيق لحركة ومخزون الموارد المختلفة.
                                    </p>
                                    <a href="<?php echo e(route('resource-trackings.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        تتبع الموارد
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view unit goals', 'manage unit goals'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-unit-goals h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1300">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">أهداف الوحدات</h3>
                                        <i class="fas fa-bullseye text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تحديد وتتبع الأهداف الاستراتيجية لكل وحدة عمل.
                                    </p>
                                    <a href="<?php echo e(route('unit-goals.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        إدارة الأهداف
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view gilbert triangle chart')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-gilbert-charts h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1400">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">مخطط جلبرت</h3>
                                        <i class="fas fa-project-diagram text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        عرض المهام والعلاقات بينها باستخدام مخطط جلبرت.
                                    </p>
                                    <a href="<?php echo e(route('charts.gilbert-triangle.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض المخطط
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view surveys', 'manage surveys'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-surveys h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1500">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">استبيانات رضا الزائرين</h3>
                                        <i class="fas fa-poll text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        إنشاء وإدارة وتحليل استبيانات لجمع آراء الزائرين.
                                    </p>
                                    <a href="<?php echo e(route('surveys.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        إدارة الاستبيانات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view survey statistics')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-survey-charts h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1600">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">إحصائيات الاستبيانات</h3>
                                        <i class="fas fa-chart-line text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        عرض وتحليل نتائج استبيانات رضا الزائرين عبر الرسوم البيانية.
                                    </p>
                                    <a href="<?php echo e(route('charts.surveys.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        عرض الإحصائيات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view notifications', 'manage notifications'])): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-notifications h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1700">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">الإشعارات</h3>
                                        <i class="far fa-bell text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        إدارة وتكوين إشعارات النظام للمستخدمين.
                                    </p>
                                    <a href="<?php echo e(route('notifications.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        إدارة الإشعارات
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage users')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-users h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1800">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">إدارة المستخدمين</h3>
                                        <i class="fas fa-user-shield text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        إنشاء، تعديل، وحذف حسابات المستخدمين وصلاحياتهم.
                                    </p>
                                    <a href="<?php echo e(route('users.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        إدارة المستخدمين
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage roles')): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-dashboard card-roles h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="1900">
                                <div class="card-body d-flex flex-column justify-content-between p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h3 class="h3 font-weight-bold text-white-shadow">إدارة الأدوار</h3>
                                        <i class="fas fa-user-tag text-white fs-1 opacity-75 card-icon-animated"></i>
                                    </div>
                                    <p class="text-white-light mb-4">
                                        تحديد الأدوار والصلاحيات المختلفة للمستخدمين.
                                    </p>
                                    <a href="<?php echo e(route('roles.index')); ?>"
                                       class="btn btn-card-action text-decoration-none">
                                        إدارة الأدوار
                                        <i class="fas fa-arrow-right me-1 icon-arrow-animated"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card card-dashboard card-profile h-100 cursor-pointer" data-aos="fade-up" data-aos-delay="2000">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white-shadow">الملف الشخصي</h3>
                                    <i class="fas fa-user-circle text-white fs-1 opacity-75 card-icon-animated"></i>
                                </div>
                                <p class="text-white-light mb-4">
                                    تعديل المعلومات الشخصية وإعدادات الحساب.
                                </p>
                                <a href="<?php echo e(route('profile.edit')); ?>"
                                   class="btn btn-card-action text-decoration-none">
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
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <style>
        /* Global variables for consistent styling */
        :root {
            --card-bg-opacity-start: 0.005; /* بداية الشفافية (قريبة جداً من 0) */
            --card-bg-opacity-end: 0.01;   /* نهاية الشفافية (شفافية خفيفة جداً) */
            --card-border-opacity: 0.05;   /* حدود شفافة جداً */
            --card-shadow-opacity: 0.1;    /* ظل خفيف جداً في البداية */
            --card-text-color: #ffffff;
            --card-text-light: rgba(255, 255, 255, 0.85); /* نص أوضح قليلاً */
            --card-icon-opacity: 0.6;      /* أيقونات شفافة قليلاً */
            --card-action-btn-bg: rgba(255, 255, 255, 0.1); /* خلفية زر الإجراء شفافة */
            --card-action-btn-hover-bg: rgba(255, 255, 255, 0.2); /* خلفية زر الإجراء عند التحويم */
            --card-action-btn-text: #fff;
            --card-action-btn-shadow: 0 0 20px rgba(0, 234, 255, 0.6); /* توهج قوي للزر عند التحويم */
            --card-hover-shadow: 0 15px 40px rgba(0, 0, 0, 0.5); /* ظل أقوى عند التحويم */
            --glow-color-strong: rgba(0, 234, 255, 0.9); /* توهج أيقوني/نصي أقوى */
            --transition-speed: 0.4s; /* سرعة انتقال أبطأ قليلاً لظهور التأثير */
        }

        /* General Card Styling */
        .card-dashboard {
            background: linear-gradient(135deg,
                rgba(255, 255, 255, var(--card-bg-opacity-start)),
                rgba(255, 255, 255, var(--card-bg-opacity-end))); /* خلفية متدرجة شفافة جداً */
            backdrop-filter: blur(12px); /* تأثير ضبابي أقوى */
            -webkit-backdrop-filter: blur(12px); /* For Safari */
            border: 1px solid rgba(255, 255, 255, var(--card-border-opacity)); /* حدود شفافة جداً */
            border-radius: 20px; /* حواف أكثر استدارة */
            box-shadow: 0 5px 25px rgba(0, 0, 0, var(--card-shadow-opacity)); /* ظل ابتدائي خفيف */
            transition: all var(--transition-speed) ease-in-out;
            overflow: hidden;
            position: relative;
            transform: translateZ(0); /* لتحسين الأداء مع blur */
        }

        .card-dashboard:hover {
            transform: translateY(-12px) scale(1.02); /* رفع وتكبير خفيف عند التحويم */
            box-shadow: var(--card-hover-shadow); /* ظل أقوى عند التحويم */
            z-index: 2; /* إبراز البطاقة المحومة */
            background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.05), /* تصبح الخلفية مرئية أكثر قليلاً عند التحويم */
                rgba(255, 255, 255, 0.1));
        }

        .card-dashboard .h3 {
            color: var(--card-text-color);
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.6); /* ظل نص أقوى قليلاً */
            transition: all var(--transition-speed) ease-in-out;
        }

        .card-dashboard .text-white-shadow {
            color: var(--card-text-color);
            text-shadow: 0 0 8px rgba(0, 0, 0, 0.7);
        }

        .card-dashboard .text-white-light {
            color: var(--card-text-light);
            font-size: 1rem; /* حجم خط أوضح */
            line-height: 1.7;
        }

        .card-dashboard .card-icon-animated {
            color: rgba(255, 255, 255, var(--card-icon-opacity));
            transition: all var(--transition-speed) ease-in-out;
            font-size: 3.5rem !important; /* أيقونة أكبر قليلاً */
        }

        .card-dashboard:hover .card-icon-animated {
            color: var(--glow-color-strong); /* لون توهج قوي للأيقونة */
            filter: drop-shadow(0 0 15px var(--glow-color-strong)); /* توهج حقيقي للأيقونة */
            transform: scale(1.2) rotate(8deg); /* تكبير ودوران أقوى */
        }

        .card-dashboard .btn-card-action {
            background-color: var(--card-action-btn-bg);
            color: var(--card-action-btn-text) !important;
            border: 1px solid rgba(255, 255, 255, 0.2); /* حدود خفيفة للزر */
            padding: 10px 20px; /* Padding for the button */
            border-radius: 10px; /* Rounded corners for the button */
            transition: all var(--transition-speed) ease-in-out;
            display: inline-flex; /* To align icon and text */
            align-items: center;
            justify-content: center;
        }

        .card-dashboard .btn-card-action:hover {
            background-color: var(--card-action-btn-hover-bg);
            box-shadow: var(--card-action-btn-shadow); /* Glow on hover */
            transform: translateY(-3px); /* Slight lift on hover */
        }

        .card-dashboard .icon-arrow-animated {
            transition: transform var(--transition-speed) ease-in-out;
        }

        .card-dashboard .btn-card-action:hover .icon-arrow-animated {
            transform: translateX(5px); /* Move arrow on hover */
        }

        /* Section Title Animation */
        .section-title-animated {
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInSlideDown 1s ease-out forwards;
        }

        @keyframes fadeInSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-dashboard .h3 {
                font-size: 1.5rem;
            }
            .card-dashboard .text-white-light {
                font-size: 0.9rem;
            }
            .card-dashboard .card-icon-animated {
                font-size: 2.5rem !important;
            }
            .card-dashboard .btn-card-action {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000, // duration of animation
                once: true, // whether animation should happen only once - while scrolling down
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/dashboard.blade.php ENDPATH**/ ?>