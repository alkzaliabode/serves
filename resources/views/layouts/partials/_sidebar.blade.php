{{-- resources/views/layouts/partials/_sidebar.blade.php --}}
{{-- هذا الملف يحتوي على الشريط الجانبي (Sidebar) للتطبيق. --}}

<aside class="app-sidebar shadow" data-bs-theme="dark">
    <div class="sidebar-wrapper">
        {{-- قائمة التنقل الرئيسية في الشريط الجانبي --}}
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                {{-- روابط التنقل الأساسية --}}
                <li class="nav-item animated-nav-item" data-animation-delay="0.1">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active-link' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>الرئيسية</p>
                    </a>
                </li>
                {{-- لوحة التحكم: يمكن لأي مستخدم مسجل الدخول الوصول إليها --}}
                <li class="nav-item animated-nav-item" data-animation-delay="0.2">
                    @can('access dashboard') {{-- افتراضياً، صلاحية للوصول إلى لوحة التحكم --}}
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>لوحة التحكم</p>
                        </a>
                    @endcan
                </li>
                {{-- مهام النظافة العامة --}}
                <li class="nav-item animated-nav-item" data-animation-delay="0.3">
                    @canany(['view general cleaning tasks', 'manage general cleaning tasks'])
                        <a href="{{ route('general-cleaning-tasks.index') }}" class="nav-link {{ request()->routeIs('general-cleaning-tasks.*') ? 'active-link' : '' }}">
                            <i class="nav-icon fas fa-broom"></i>
                            <p>مهام النظافة العامة</p>
                        </a>
                    @endcanany
                </li>
                {{-- مهام المنشآت الصحية --}}
                <li class="nav-item animated-nav-item" data-animation-delay="0.4">
                    @canany(['view sanitation facility tasks', 'manage sanitation facility tasks'])
                        <a href="{{ route('sanitation-facility-tasks.index') }}" class="nav-link {{ request()->routeIs('sanitation-facility-tasks.*') ? 'active-link' : '' }}">
                            <i class="nav-icon fas fa-hospital"></i>
                            <p>مهام المنشآت الصحية</p>
                        </a>
                    @endcanany
                </li>
                {{-- لوحة مهام الشُعبة الخدمية --}}
                <li class="nav-item animated-nav-item" data-animation-delay="0.5">
                    @can('view service tasks board')
                        <a href="{{ route('service-tasks.board.index') }}" class="nav-link {{ request()->routeIs('service-tasks.board.*') ? 'active-link' : '' }}">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>لوحة مهام الشُعبة الخدمية</p>
                        </a>
                    @endcan
                </li>
                {{-- الموقف اليومي --}}
                <li class="nav-item animated-nav-item" data-animation-delay="0.6">
                    @canany(['view daily statuses', 'manage daily statuses'])
                        <a href="{{ route('daily-statuses.index') }}" class="nav-link {{ request()->routeIs('daily-statuses.*') ? 'active-link' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>الموقف اليومي</p>
                        </a>
                    @endcanany
                </li>

                {{-- روابط التقارير --}}
                {{-- هذا الـ nav-header سيظهر إذا كان لدى المستخدم أي صلاحية لعرض أي من التقارير الفرعية --}}
                @canany(['view resource report', 'view monthly cleaning report', 'view monthly sanitation report', 'view photo reports', 'view monthly summary'])
                    <li class="nav-header animated-nav-item" data-animation-delay="0.7">التقارير</li>
                    <li class="nav-item animated-nav-item" data-animation-delay="0.8">
                        @can('view resource report')
                            <a href="{{ route('resource-report.index') }}" class="nav-link {{ request()->routeIs('resource-report.index') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>تقرير الموارد</p>
                            </a>
                        @endcan
                    </li>
                    <li class="nav-item animated-nav-item" data-animation-delay="0.9">
                        @can('view monthly cleaning report')
                            <a href="{{ route('monthly-cleaning-report.index') }}" class="nav-link {{ request()->routeIs('monthly-cleaning-report.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>تقرير النظافة العامة الشهري</p>
                            </a>
                        @endcan
                    </li>
                    <li class="nav-item animated-nav-item" data-animation-delay="1.0">
                        @can('view monthly sanitation report')
                            <a href="{{ route('monthly-sanitation-report.index') }}" class="nav-link {{ request()->routeIs('monthly-sanitation-report.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-file-medical"></i>
                                <p>تقرير المنشآت الصحية الشهري</p>
                            </a>
                        @endcan
                    </li>
                    {{-- التقارير المصورة (تم نقلها من قسم الإدارة لتبقى مع التقارير) --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.3">
                        @canany(['view photo reports', 'manage photo reports'])
                            <a href="{{ route('photo_reports.index') }}" class="nav-link {{ request()->routeIs('photo_reports.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-images"></i>
                                <p>التقارير المصورة</p>
                            </a>
                        @endcanany
                    </li>
                    {{-- رابط الملخص الشهري الجديد --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.05"> {{-- Adjusted delay --}}
                        @can('view monthly summary') {{-- New permission for monthly summary --}}
                            <a href="{{ route('monthly-summary.show') }}" class="nav-link {{ request()->routeIs('monthly-summary.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i> {{-- Icon for monthly summary --}}
                                <p>ملخص الحضور الشهري</p>
                            </a>
                        @endcan
                    </li>
                @endcanany


                {{-- روابط الإدارة --}}
                {{-- هذا الـ nav-header سيظهر إذا كان لدى المستخدم أي صلاحية لإدارة الموظفين أو إعدادات الخلفية --}}
                @canany(['view users', 'manage users', 'manage background settings']) {{-- تم تعديل الصلاحيات هنا --}}
                    <li class="nav-header animated-nav-item" data-animation-delay="1.1">الإدارة</li>
                    {{-- الموظفين --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.2">
                        @canany(['view users', 'manage users']) {{-- تم تعديل الصلاحيات هنا --}}
                            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>الموظفين</p>
                            </a>
                        @endcanany
                    </li>
                    {{-- إعدادات الخلفية --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.4">
                        @can('manage background settings')
                            <a href="{{ route('background-settings.index') }}" class="nav-link {{ request()->routeIs('background-settings.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-image"></i>
                                <p>إعدادات الخلفية</p>
                            </a>
                        @endcan
                    </li>
                @endcanany

                {{-- قسم إدارة الأداء والتحليلات --}}
                @canany([
                    'view actual results', 'manage actual results',
                    'view resource trackings', 'manage resource trackings',
                    'view unit goals', 'manage unit goals',
                    'view gilbert triangle chart',
                    'view surveys', 'manage surveys',
                    'view survey statistics'
                ])
                    <li class="nav-header animated-nav-item" data-animation-delay="1.5">إدارة الأداء والتحليلات</li>
                    {{-- النتائج الفعلية --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.6">
                        @canany(['view actual results', 'manage actual results'])
                            <a href="{{ route('actual-results.index') }}" class="nav-link {{ request()->routeIs('actual-results.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>النتائج الفعلية</p>
                            </a>
                        @endcanany
                    </li>
                    {{-- تتبع الموارد --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.7">
                        @canany(['view resource trackings', 'manage resource trackings'])
                            <a href="{{ route('resource-trackings.index') }}" class="nav-link {{ request()->routeIs('resource-trackings.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>تتبع الموارد</p>
                            </a>
                        @endcanany
                    </li>
                    {{-- أهداف الوحدات --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.8">
                        @canany(['view unit goals', 'manage unit goals'])
                            <a href="{{ route('unit-goals.index') }}" class="nav-link {{ request()->routeIs('unit-goals.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-bullseye"></i>
                                <p>أهداف الوحدات</p>
                            </a>
                        @endcanany
                    </li>
                    {{-- مخطط جلبرت --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="1.9">
                        @can('view gilbert triangle chart')
                            <a href="{{ route('charts.gilbert-triangle.index') }}" class="nav-link {{ request()->routeIs('charts.gilbert-triangle.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-project-diagram"></i>
                                <p>مخطط جلبرت</p>
                            </a>
                        @endcan
                    </li>
                    {{-- استبيانات رضا الزائرين --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="2.0">
                        @canany(['view surveys', 'manage surveys'])
                            <a href="{{ route('surveys.index') }}" class="nav-link {{ request()->routeIs('surveys.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-poll"></i>
                                <p>استبيانات رضا الزائرين</p>
                            </a>
                        @endcanany
                    </li>
                    {{-- إحصائيات الاستبيانات --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="2.1">
                        @can('view survey statistics')
                            <a href="{{ route('charts.surveys.index') }}" class="nav-link {{ request()->routeIs('charts.surveys.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>إحصائيات الاستبيانات</p>
                            </a>
                        @endcan
                    </li>
                @endcanany

                {{-- روابط إعدادات النظام --}}
                @canany(['view notifications', 'manage notifications', 'manage users', 'manage roles'])
                    <li class="nav-header animated-nav-item" data-animation-delay="2.2">إعدادات النظام</li>
                    {{-- الإشعارات --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="2.3">
                        @canany(['view notifications', 'manage notifications'])
                            <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active-link' : '' }}">
                                <i class="nav-icon far fa-bell"></i>
                                <p>الإشعارات</p>
                            </a>
                        @endcanany
                    </li>
                    {{-- إدارة المستخدمين --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="2.4">
                        @can('manage users')
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>إدارة المستخدمين</p>
                            </a>
                        @endcan
                    </li>
                    {{-- إدارة الأدوار --}}
                    <li class="nav-item animated-nav-item" data-animation-delay="2.5">
                        @can('manage roles')
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active-link' : '' }}">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>إدارة الأدوار</p>
                            </a>
                        @endcan
                    </li>
                @endcanany
                {{-- الملف الشخصي (يعرض لجميع المستخدمين المصادق عليهم) --}}
                <li class="nav-item animated-nav-item" data-animation-delay="2.6">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active-link' : '' }}">
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
