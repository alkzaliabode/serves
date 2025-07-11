


<aside class="app-sidebar shadow" data-bs-theme="dark">
    <div class="sidebar-wrapper">
        
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item animated-nav-item" data-animation-delay="0.1">
                    <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e(request()->routeIs('home') ? 'active-link' : ''); ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>الرئيسية</p>
                    </a>
                </li>
                
                <li class="nav-item animated-nav-item" data-animation-delay="0.2">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access dashboard')): ?> 
                        <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active-link' : ''); ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>لوحة التحكم</p>
                        </a>
                    <?php endif; ?>
                </li>
                
                <li class="nav-item animated-nav-item" data-animation-delay="0.3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view general cleaning tasks', 'manage general cleaning tasks'])): ?>
                        <a href="<?php echo e(route('general-cleaning-tasks.index')); ?>" class="nav-link <?php echo e(request()->routeIs('general-cleaning-tasks.*') ? 'active-link' : ''); ?>">
                            <i class="nav-icon fas fa-broom"></i>
                            <p>مهام النظافة العامة</p>
                        </a>
                    <?php endif; ?>
                </li>
                
                <li class="nav-item animated-nav-item" data-animation-delay="0.4">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view sanitation facility tasks', 'manage sanitation facility tasks'])): ?>
                        <a href="<?php echo e(route('sanitation-facility-tasks.index')); ?>" class="nav-link <?php echo e(request()->routeIs('sanitation-facility-tasks.*') ? 'active-link' : ''); ?>">
                            <i class="nav-icon fas fa-hospital"></i>
                            <p>مهام المنشآت الصحية</p>
                        </a>
                    <?php endif; ?>
                </li>
                
                <li class="nav-item animated-nav-item" data-animation-delay="0.5">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view service tasks board')): ?>
                        <a href="<?php echo e(route('service-tasks.board.index')); ?>" class="nav-link <?php echo e(request()->routeIs('service-tasks.board.*') ? 'active-link' : ''); ?>">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>لوحة مهام الشُعبة الخدمية</p>
                        </a>
                    <?php endif; ?>
                </li>
                
                <li class="nav-item animated-nav-item" data-animation-delay="0.6">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view daily statuses', 'manage daily statuses'])): ?>
                        <a href="<?php echo e(route('daily-statuses.index')); ?>" class="nav-link <?php echo e(request()->routeIs('daily-statuses.*') ? 'active-link' : ''); ?>">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>الموقف اليومي</p>
                        </a>
                    <?php endif; ?>
                </li>

                
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view resource report', 'view monthly cleaning report', 'view monthly sanitation report', 'view photo reports', 'view monthly summary'])): ?>
                    <li class="nav-header animated-nav-item" data-animation-delay="0.7">التقارير</li>
                    <li class="nav-item animated-nav-item" data-animation-delay="0.8">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view resource report')): ?>
                            <a href="<?php echo e(route('resource-report.index')); ?>" class="nav-link <?php echo e(request()->routeIs('resource-report.index') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>تقرير الموارد</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item animated-nav-item" data-animation-delay="0.9">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view monthly cleaning report')): ?>
                            <a href="<?php echo e(route('monthly-cleaning-report.index')); ?>" class="nav-link <?php echo e(request()->routeIs('monthly-cleaning-report.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>تقرير النظافة العامة الشهري</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item animated-nav-item" data-animation-delay="1.0">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view monthly sanitation report')): ?>
                            <a href="<?php echo e(route('monthly-sanitation-report.index')); ?>" class="nav-link <?php echo e(request()->routeIs('monthly-sanitation-report.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-file-medical"></i>
                                <p>تقرير المنشآت الصحية الشهري</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.3">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view photo reports', 'manage photo reports'])): ?>
                            <a href="<?php echo e(route('photo_reports.index')); ?>" class="nav-link <?php echo e(request()->routeIs('photo_reports.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-images"></i>
                                <p>التقارير المصورة</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.05"> 
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view monthly summary')): ?> 
                            <a href="<?php echo e(route('monthly-summary.show')); ?>" class="nav-link <?php echo e(request()->routeIs('monthly-summary.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-calendar-alt"></i> 
                                <p>ملخص الحضور الشهري</p>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>


                
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view users', 'manage users', 'manage background settings'])): ?> 
                    <li class="nav-header animated-nav-item" data-animation-delay="1.1">الإدارة</li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view users', 'manage users'])): ?> 
                            <a href="<?php echo e(route('employees.index')); ?>" class="nav-link <?php echo e(request()->routeIs('employees.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>الموظفين</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.4">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage background settings')): ?>
                            <a href="<?php echo e(route('background-settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('background-settings.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-image"></i>
                                <p>إعدادات الخلفية</p>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any([
                    'view actual results', 'manage actual results',
                    'view resource trackings', 'manage resource trackings',
                    'view unit goals', 'manage unit goals',
                    'view gilbert triangle chart',
                    'view surveys', 'manage surveys',
                    'view survey statistics'
                ])): ?>
                    <li class="nav-header animated-nav-item" data-animation-delay="1.5">إدارة الأداء والتحليلات</li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.6">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view actual results', 'manage actual results'])): ?>
                            <a href="<?php echo e(route('actual-results.index')); ?>" class="nav-link <?php echo e(request()->routeIs('actual-results.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>النتائج الفعلية</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.7">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view resource trackings', 'manage resource trackings'])): ?>
                            <a href="<?php echo e(route('resource-trackings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('resource-trackings.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>تتبع الموارد</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.8">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view unit goals', 'manage unit goals'])): ?>
                            <a href="<?php echo e(route('unit-goals.index')); ?>" class="nav-link <?php echo e(request()->routeIs('unit-goals.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-bullseye"></i>
                                <p>أهداف الوحدات</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="1.9">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view gilbert triangle chart')): ?>
                            <a href="<?php echo e(route('charts.gilbert-triangle.index')); ?>" class="nav-link <?php echo e(request()->routeIs('charts.gilbert-triangle.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-project-diagram"></i>
                                <p>مخطط جلبرت</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="2.0">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view surveys', 'manage surveys'])): ?>
                            <a href="<?php echo e(route('surveys.index')); ?>" class="nav-link <?php echo e(request()->routeIs('surveys.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-poll"></i>
                                <p>استبيانات رضا الزائرين</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="2.1">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view survey statistics')): ?>
                            <a href="<?php echo e(route('charts.surveys.index')); ?>" class="nav-link <?php echo e(request()->routeIs('charts.surveys.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>إحصائيات الاستبيانات</p>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view notifications', 'manage notifications', 'manage users', 'manage roles'])): ?>
                    <li class="nav-header animated-nav-item" data-animation-delay="2.2">إعدادات النظام</li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="2.3">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view notifications', 'manage notifications'])): ?>
                            <a href="<?php echo e(route('notifications.index')); ?>" class="nav-link <?php echo e(request()->routeIs('notifications.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon far fa-bell"></i>
                                <p>الإشعارات</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="2.4">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage users')): ?>
                            <a href="<?php echo e(route('users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>إدارة المستخدمين</p>
                            </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item animated-nav-item" data-animation-delay="2.5">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage roles')): ?>
                            <a href="<?php echo e(route('roles.index')); ?>" class="nav-link <?php echo e(request()->routeIs('roles.*') ? 'active-link' : ''); ?>">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>إدارة الأدوار</p>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
                
                <li class="nav-item animated-nav-item" data-animation-delay="2.6">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="nav-link <?php echo e(request()->routeIs('profile.edit') ? 'active-link' : ''); ?>">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>الملف الشخصي</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
    /* Global variables for consistent styling */
    :root {
        --sidebar-bg-start: #2a384b; /* Darker blue-grey */
        --sidebar-bg-end: #1f2732;   /* Even darker for a subtle gradient */
        --nav-link-color: rgba(255, 255, 255, 0.85); /* Slightly transparent white for links */
        --nav-link-hover-bg: rgba(255, 255, 255, 0.1); /* Light transparent background on hover */
        --active-link-bg: #007bff;   /* Primary blue for active link */
        --active-link-glow: 0 0 15px rgba(0, 123, 255, 0.6); /* Glow for active link */
        --icon-color: rgba(255, 255, 255, 0.7); /* Slightly transparent white for icons */
        --shadow-strong: 0 10px 20px rgba(0, 0, 0, 0.4);
        --shadow-medium: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Sidebar general styling */
    .app-sidebar {
        background: linear-gradient(180deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
        box-shadow: var(--shadow-strong);
        border-right: 1px solid rgba(255, 255, 255, 0.05); /* Subtle border */
        transition: width 0.3s ease-in-out; /* Smooth transition for sidebar collapse/expand */
    }

    .sidebar-wrapper {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    /* Nav item and link styling */
    .nav-item {
        margin-bottom: 5px; /* Spacing between nav items */
        opacity: 0; /* Initial state for animation */
        transform: translateX(-20px); /* Initial state for animation */
        transition: opacity 0.5s ease-out, transform 0.5s ease-out; /* Base transition for entry animation */
    }

    /* Animation for nav items */
    .nav-item.animate-in {
        opacity: 1;
        transform: translateX(0);
    }

    .nav-link {
        color: var(--nav-link-color) !important;
        padding: 12px 20px; /* More padding for a larger touch area */
        border-radius: 8px; /* Rounded corners for links */
        transition: all 0.3s ease-in-out; /* Smooth transitions for hover effects */
        display: flex;
        align-items: center;
        position: relative; /* For active link animation */
        overflow: hidden; /* For shine effect */
    }

    .nav-link:hover {
        background-color: var(--nav-link-hover-bg);
        transform: translateX(5px) scale(1.02); /* Slight movement and scale on hover */
        color: white !important; /* Brighter text on hover */
        box-shadow: var(--shadow-medium);
    }

    .nav-link:hover .nav-icon {
        transform: scale(1.1) rotate(5deg); /* Icon animation on hover */
        color: var(--active-link-bg) !important; /* Change icon color on hover */
    }

    /* Active link styling */
    .nav-link.active-link {
        background: linear-gradient(90deg, var(--active-link-bg), #00a0f0) !important;
        color: white !important;
        box-shadow: var(--active-link-glow);
        font-weight: bold;
        position: relative;
        overflow: hidden;
    }

    .nav-link.active-link .nav-icon {
        color: white !important; /* Ensure active icon is white */
        filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.7));
    }

    /* Shine effect for active link */
    .nav-link.active-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transform: skewX(-20deg);
        transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .nav-link.active-link:hover::before {
        left: 120%;
    }

    /* Icons within nav links */
    .nav-icon {
        color: var(--icon-color) !important;
        font-size: 1.2rem; /* Larger icons */
        margin-left: 15px; /* Space between icon and text */
        width: 25px; /* Fixed width for alignment */
        text-align: center;
        transition: all 0.3s ease-in-out;
    }

    /* Nav headers */
    .nav-header {
        color: rgba(255, 255, 255, 0.5); /* Lighter color for headers */
        font-size: 0.9rem;
        padding: 10px 20px 5px 20px;
        margin-top: 15px;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: bold;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05); /* Subtle separator */
        padding-bottom: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) { /* Adjust for smaller screens like tablets */
        .app-sidebar {
            width: 80px; /* Collapsed width */
        }
        .nav-link p {
            display: none; /* Hide text when collapsed */
        }
        .nav-link {
            justify-content: center; /* Center icons */
            padding: 12px 0;
        }
        .nav-icon {
            margin-left: 0;
        }
        .nav-header {
            text-align: center;
            font-size: 0.7rem;
            white-space: nowrap; /* Prevent wrapping */
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 10px 5px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple slide-in animation for sidebar items
        const navItems = document.querySelectorAll('.animated-nav-item');
        navItems.forEach((item, index) => {
            const delay = parseFloat(item.dataset.animationDelay || 0);
            setTimeout(() => {
                item.classList.add('animate-in');
            }, delay * 1000); // Convert seconds to milliseconds
        });
    });
</script>
<?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/layouts/partials/_sidebar.blade.php ENDPATH**/ ?>