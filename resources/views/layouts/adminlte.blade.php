<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title', 'لوحة التحكم')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
    {{-- تم استبدال Inter بخطوط أكثر جمالية وتنوعاً لتناسب التصميم الاحترافي --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css">

    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.rtl.min.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">

    @yield('styles') {{-- لربط ملفات CSS إضافية خاصة بالصفحة --}}
    <style>
        /* الخطوط */
        body {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif;
        }

        /* Global body background */
        body {
            /* جلب مسار الصورة من إعدادات التطبيق (المعبأة بواسطة AppServiceProvider) */
            background-image: url('{{ config('app.background_image_url') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed; /* Keep background fixed when scrolling */
            position: relative; /* Needed for pseudo-elements and z-index */
            background-color: #2c3e50; /* Fallback color for areas not covered or if image fails */
        }

        /* Overlay for body background image to improve text readability */
        body::before {
            content: '';
            position: fixed; /* Fixed to cover the whole viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* طبقة تعتيم أغمق لتحسين وضوح النص */
            z-index: -1; /* Ensure it's behind content */
        }

        /* Ensure the main content wrapper of AdminLTE is transparent */
        /* This allows the body's background image to show through */
        .content-wrapper,
        .app-main,
        .app-content {
            background-color: transparent !important;
            box-shadow: none !important; /* Remove any default shadows */
            border: none !important; /* Remove any default borders */
        }

        /* أنماط الشريط الجانبي مع الصورة الخلفية للجسم - الآن يعرض خلفية الجسم */
        .app-sidebar {
            background-color: transparent !important; /* إزالة لون الخلفية الافتراضي */
            box-shadow: none !important; /* إزالة الظل أيضاً */
            position: relative; /* لتمكين استخدام العناصر الوهمية */
            z-index: 1038; /* لضمان ظهوره فوق المحتوى */
        }

        /* طبقة تراكب زجاجية للشريط الجانبي */
        .app-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3); /* طبقة شفافة لتعتيم الصورة قليلاً */
            backdrop-filter: blur(8px); /* تأثير الزجاج المتجمد أكثر قوة */
            z-index: -1; /* تأكد من أنها خلف محتوى الشريط الجانبي */
            border-right: 1px solid rgba(255, 255, 255, 0.1); /* حد جانبي خفيف */
        }

        /* إزالة خلفية القائمة والمستويات الداخلية */
        .sidebar-wrapper,
        .sidebar-wrapper .nav-sidebar,
        .sidebar-wrapper .nav-treeview {
            background-color: transparent !important;
        }

        .sidebar-brand {
            position: relative;
            overflow: hidden; /* لإخفاء أي جزء زائد من الصورة */
            height: 65px; /* ارتفاع ثابت للبراند */
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* حد سفلي أنيق */
            margin-bottom: 15px; /* مسافة أسفل البراند */
        }

        .sidebar-brand .brand-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 0; /* إزالة الحشوة لملء الصورة */
            text-decoration: none;
            position: relative;
            color: white; /* لون النص ليكون مرئياً فوق الصورة */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.9); /* ظل للنص لتحسين القراءة */
            font-size: 1.6rem; /* حجم أكبر للبراند */
            font-weight: 700; /* خط سميك */
            letter-spacing: 1px; /* تباعد بين الحروف */
        }

        .sidebar-brand .brand-image {
            display: none !important; /* إخفاء الصورة الصغيرة لتظهر صورة الخلفية */
        }

        .sidebar-brand .brand-text {
            font-weight: bold;
            font-size: 1.5rem; /* حجم خط أكبر قليلاً */
            color: white; /* التأكد من لون النص */
            z-index: 1; /* التأكد من أن النص فوق الصورة */
        }

        /* تنسيق الخط وحجمه وروابط القائمة */
        .nav-sidebar > .nav-item > .nav-link {
            font-size: 1.35rem; /* حجم أكبر بشكل ملحوظ */
            font-weight: 600; /* سمك خط متوسط */
            color: #ecf0f1 !important; /* لون أبيض مائل للرمادي فاتح */
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* انتقال أكثر سلاسة */
            position: relative;
            margin-bottom: 8px; /* مسافة بين العناصر */
            padding: 12px 20px; /* حشوة أكبر للروابط */
            border-radius: 10px; /* حواف مستديرة */
            overflow: hidden; /* لإخفاء الفائض من الخلفية */
            display: flex;
            align-items: center;
        }

        .nav-sidebar > .nav-item > .nav-link .nav-icon {
            font-size: 1.5rem; /* حجم أكبر للأيقونات */
            margin-left: 15px; /* مسافة بين الأيقونة والنص */
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* عند تحويم الماوس: تكبير وتحريك ولون */
        .nav-sidebar > .nav-item > .nav-link:hover {
            color: #72efdd !important; /* لون جذاب عند التحويم (أزرق مخضر نيون) */
            transform: translateX(10px) scale(1.02); /* حركة وتكبير أكثر وضوحاً */
            background-color: rgba(255, 255, 255, 0.08) !important; /* خلفية شفافة عند التحويم */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* ظل خفيف عند التحويم */
        }

        .nav-sidebar > .nav-item > .nav-link:hover .nav-icon {
            transform: scale(1.1); /* تكبير الأيقونة عند التحويم */
            color: #72efdd !important; /* لون الأيقونة عند التحويم */
        }

        /* عند التفعيل */
        .nav-sidebar > .nav-item > .nav-link.active {
            background-color: rgba(114, 239, 221, 0.2) !important; /* خلفية نشطة بلون أزرق مخضر شفاف */
            color: #72efdd !important; /* لون النص النشط */
            border-left: 5px solid #72efdd; /* خط جانبي بارز */
            box-shadow: 0 5px 20px rgba(0, 255, 255, 0.3); /* ظل لامع للتفعيل */
            transform: translateX(5px); /* حركة خفيفة للداخل */
        }
        .nav-sidebar > .nav-item > .nav-link.active .nav-icon {
            color: #72efdd !important; /* لون الأيقونة النشط */
        }

        /* إزالة تأثير After السابق (إذا كان موجوداً) واستبداله بخط جانبي */
        .nav-sidebar > .nav-item > .nav-link::after {
            display: none;
        }

        /* جعل شريط التنقل العلوي شفافًا وأكثر أناقة */
        .app-header.navbar {
            background-color: rgba(255, 255, 255, 0.05) !important; /* خلفية شفافة جداً */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15) !important; /* ظل خفيف */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* حد سفلي خفيف */
        }
        .app-header .nav-link {
            color: #ecf0f1 !important; /* لون نص علوي فاتح */
        }
        .app-header .nav-link:hover {
            color: #72efdd !important; /* لون التحويم العلوي */
        }

        /* جعل قائمة البروفايل المنسدلة شفافة */
        .user-menu .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.9) !important; /* خلفية شفافة للقائمة المنسدلة */
            border: none !important; /* إزالة الحدود */
            border-radius: 10px; /* حواف مستديرة */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important; /* ظل أفضل */
            color: #333 !important; /* لون نص داكن للقائمة */
            backdrop-filter: blur(5px); /* تأثير زجاجي خفيف */
        }

        .user-menu .dropdown-menu .user-header {
            background-color: rgba(0, 123, 255, 0.9) !important; /* خلفية رأس البروفايل شفافة وملونة */
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .user-menu .dropdown-menu .user-footer {
            background-color: rgba(255, 255, 255, 0.95) !important; /* خلفية شفافة للفوتر الداخلي */
            border-top: none !important;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* جعل التذييل شفافًا بالكامل */
        .app-footer {
            background-color: transparent !important;
            border-top: none !important; /* إزالة الخط العلوي إن وجد */
            color: rgba(255, 255, 255, 0.7) !important; /* لون نص أفتح للفوتر */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }
        .app-footer strong {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .app-footer a {
            color: #72efdd !important; /* لون مميز للروابط في الفوتر */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .app-footer a:hover {
            text-decoration: underline;
            color: #61dcb7 !important;
        }

        /* أنماط البطاقات لتكون شفافة بالكامل مع تأثير زجاجي وخطوط بارزة - شفافية أعلى */
        .card {
            background-color: rgba(255, 255, 255, 0.05) !important; /* خلفية شبه شفافة جداً */
            backdrop-filter: blur(15px) !important; /* تأثير الزجاج المتجمد أكثر قوة */
            border-radius: 1.25rem !important; /* حواف مستديرة أكثر */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3) !important; /* ظل أنعم وأكثر انتشاراً */
            border: 1px solid rgba(255, 255, 255, 0.15) !important; /* حدود بارزة وواضحة جداً */
            transition: all 0.3s ease-in-out; /* انتقال للتحويم */
        }
        .card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4); /* ظل أكبر عند التحويم */
            transform: translateY(-3px); /* رفع البطاقة قليلاً */
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.08) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important; /* حدود سفلية شفافة وواضحة */
            color: white !important; /* لون نص أبيض */
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8) !important; /* ظل للنص أكثر وضوحاً */
            border-top-left-radius: 1.25rem; /* حواف مستديرة لرأس البطاقة */
            border-top-right-radius: 1.25rem;
        }
        .card-title,
        .card-header .btn,
        .card-body,
        .card-footer {
            color: white !important; /* لون نص أبيض */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7) !important; /* ظل للنص لتحسين القراءة */
        }
        .card-footer {
            background-color: rgba(255, 255, 255, 0.03) !important; /* خلفية شفافة للفوتر */
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important; /* حد علوي خفيف */
            border-bottom-left-radius: 1.25rem;
            border-bottom-right-radius: 1.25rem;
        }

        /* Tailwind classes compatibility (for space-y-6) */
        .space-y-6 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(1.5rem * calc(1 - var(--tw-space-y-reverse))) !important;
            margin-bottom: calc(1.5rem * var(--tw-space-y-reverse)) !important;
        }

        /* تحسينات إضافية على الخطوط لجميع النصوص */
        h1, h2, h3, h4, h5, h6, .lead, p, .text-muted, .form-label {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
            color: #ecf0f1 !important; /* لون نص فاتح الافتراضي */
        }
        /* الأزرار و الروابط */
        .btn, a {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
        }
        /* أيقونات Bootstrap */
        .bi {
            vertical-align: -0.125em; /* محاذاة أفضل للأيقونات */
        }
    </style>
</head>
{{-- تم تعديل classes هنا لضمان تثبيت الشريط الجانبي وحل مشكلة الارتفاع --}}
<body class="hold-transition sidebar-mini layout-fixed">
<div class="app-wrapper">

    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
                </li>
                <li class="nav-item d-none d-md-block"> <a href="{{ route('home') }}" class="nav-link">الرئيسية</a> </li>
                {{-- يمكنك إضافة روابط علوية أخرى هنا --}}
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/160x160/cccccc/ffffff?text=U' }}" class="user-image rounded-circle shadow" alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'الضيف' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                            <img src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/160x160/cccccc/ffffff?text=U' }}" class="rounded-circle shadow" alt="User Image">
                            <p>
                                {{ Auth::user()->name ?? 'الضيف' }} - {{ Auth::user()->email ?? '' }}
                                <small>عضو منذ {{ (Auth::user()->created_at ?? now())->format('M. Y') }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">الملف الشخصي</a>
                            <form method="POST" action="{{ route('logout') }}" class="float-end">
                                @csrf
                                <button type="submit" class="btn btn-default btn-flat">تسجيل الخروج</button>
                            </form>
                        </li>
                    </ul>
                </li>
                <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> </a> </li>
            </ul>
        </div>
    </nav>
    <aside class="app-sidebar shadow" data-bs-theme="dark"> {{-- ✅ تم إزالة bg-body-secondary --}}

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>الرئيسية</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>لوحة التحكم</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('general-cleaning-tasks.index') }}" class="nav-link {{ request()->routeIs('general-cleaning-tasks.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-broom"></i>
                            <p>مهام النظافة العامة</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sanitation-facility-tasks.index') }}" class="nav-link {{ request()->routeIs('sanitation-facility-tasks.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hospital"></i>
                            <p>مهام المنشآت الصحية</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('service-tasks.board.index') }}" class="nav-link {{ request()->routeIs('service-tasks.board.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-columns"></i> {{-- أيقونة Kanban --}}
                            <p>لوحة مهام الشُعبة الخدمية</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('daily-statuses.index') }}" class="nav-link {{ request()->routeIs('daily-statuses.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>الموقف اليومي</p>
                        </a>
                    </li>
                    {{-- إضافة رابط لتقرير الموارد --}}
                    <li class="nav-item">
                        <a href="{{ route('resource-report.index') }}" class="nav-link {{ request()->routeIs('resource-report.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i> {{-- أيقونة مناسبة للتقارير --}}
                            <p>تقرير الموارد</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('monthly-cleaning-report.index') }}" class="nav-link {{ request()->routeIs('monthly-cleaning-report.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i> {{-- أيقونة مناسبة --}}
                            <p>تقرير النظافة العامة الشهري</p>
                        </a>
                    </li>
                    {{-- NEW: إضافة رابط لتقرير المنشآت الصحية الشهري --}}
                    <li class="nav-item">
                        <a href="{{ route('monthly-sanitation-report.index') }}" class="nav-link {{ request()->routeIs('monthly-sanitation-report.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-medical"></i> {{-- أيقونة مناسبة للتقارير الطبية/الصحية --}}
                            <p>تقرير المنشآت الصحية الشهري</p>
                        </a>
                    </li>
                    {{-- NEW: إضافة رابط لصفحة إدارة الموظفين --}}
                    <li class="nav-item">
                        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i> {{-- أيقونة مناسبة للموظفين --}}
                            <p>الموظفين</p>
                        </a>
                    </li>
                    {{-- NEW: إضافة رابط لصفحة التقارير المصورة --}}
                    <li class="nav-item">
                        <a href="{{ route('photo_reports.index') }}" class="nav-link {{ request()->routeIs('photo_reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-images"></i> {{-- أيقونة مناسبة للصور والتقارير المرئية --}}
                            <p>التقارير المصورة</p>
                        </a>
                    </li>
                    {{-- NEW: إضافة رابط لصفحة إعدادات الخلفية --}}
                    <li class="nav-item">
                        <a href="{{ route('background-settings.index') }}" class="nav-link {{ request()->routeIs('background-settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-image"></i> {{-- أيقونة مناسبة لإعدادات الصور --}}
                            <p>إعدادات الخلفية</p>
                        </a>
                    </li>

                    {{-- NEW: روابط صفحات النتائج وتتبع الموارد ومخطط جلبرت --}}
                    <li class="nav-header">إدارة الأداء والتحليلات</li>
                    <li class="nav-item">
                        <a href="{{ route('actual-results.index') }}" class="nav-link {{ request()->routeIs('actual-results.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i> {{-- أيقونة مناسبة للنتائج --}}
                            <p>النتائج الفعلية</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('resource-trackings.index') }}" class="nav-link {{ request()->routeIs('resource-trackings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i> {{-- أيقونة مناسبة للموارد --}}
                            <p>تتبع الموارد</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('unit-goals.index') }}" class="nav-link {{ request()->routeIs('unit-goals.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bullseye"></i> {{-- أيقونة مناسبة للأهداف --}}
                            <p>أهداف الوحدات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('charts.gilbert-triangle.index') }}" class="nav-link {{ request()->routeIs('charts.gilbert-triangle.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-project-diagram"></i> {{-- أيقونة مناسبة للمخططات/النماذج --}}
                            <p>مخطط جلبرت</p>
                        </a>
                    </li>
                    {{-- NEW: رابط الاستبيانات --}}
                    <li class="nav-item">
                        <a href="{{ route('surveys.index') }}" class="nav-link {{ request()->routeIs('surveys.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-poll"></i> {{-- أيقونة مناسبة للاستبيانات --}}
                            <p>استبيانات رضا الزائرين</p>
                        </a>
                    </li>
                    {{-- NEW: رابط إحصائيات الاستبيانات (المخططات) --}}
                    <li class="nav-item">
                        <a href="{{ route('charts.surveys.index') }}" class="nav-link {{ request()->routeIs('charts.surveys.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i> {{-- أيقونة مناسبة للرسوم البيانية --}}
                            <p>إحصائيات الاستبيانات</p>
                        </a>
                    </li>
                    {{-- نهاية روابط صفحات النتائج وتتبع الموارد ومخطط جلبرت --}}

                    {{-- NEW: إضافة رابط لصفحة المستخدمين (Users) --}}
                    {{-- يمكن وضعها ضمن قسم الإدارة إذا كان هناك قسم كهذا، أو كعنصر مستقل --}}
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i> {{-- أيقونة تشير إلى إدارة المستخدمين والصلاحيات --}}
                            <p>إدارة المستخدمين</p>
                        </a>
                    </li>
                    {{-- NEW: إضافة رابط لصفحة الأدوار (Roles) --}}
                    <li class="nav-item">
                        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tag"></i> {{-- أيقونة مناسبة للأدوار --}}
                            <p>إدارة الأدوار</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>الملف الشخصي</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">@yield('page_title', 'الصفحة')</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                @yield('content') {{-- هذا هو المكان الذي سيتم فيه حقن محتوى صفحاتك --}}
            </div>
        </div>
        </main>
    <aside class="control-sidebar control-sidebar-dark">
    
    </aside>
    <footer class="app-footer">
    </footer>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>

<script>
    // OverlayScrollbars Configure
    const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
    const Default = {
        scrollbarTheme: "os-theme-light",
        scrollbarAutoHide: "leave",
        scrollbarClickScroll: true,
    };
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (
            sidebarWrapper &&
            typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
        ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });

    // SortableJS (for demo cards)
    const connectedSortables = document.querySelectorAll(".connectedSortable");
    connectedSortables.forEach((connectedSortable) => {
        let sortable = new Sortable(connectedSortable, {
            group: "shared",
            handle: ".card-header",
        });
    });
    const cardHeaders = document.querySelectorAll(".connectedSortables .card-header");
    cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = "move";
    });
</script>

@yield('scripts') {{-- لربط ملفات JavaScript إضافية خاصة بالصفحة --}}
</body>
</html>
