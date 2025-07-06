{{-- resources/views/layouts/admin_layout.blade.php --}}
{{-- هذا هو ملف التخطيط الرئيسي (Master Layout) الذي يجمع جميع الأجزاء. --}}
{{-- يجب على جميع صفحات المحتوى أن "تمدد" هذا التخطيط باستخدام @extends('layouts.admin_layout') --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- عنوان الصفحة: يتم تحديده بواسطة @section('title', 'عنوانك') في الصفحات الفرعية --}}
    <title>{{ config('app.name', 'Laravel') }} | @yield('title', 'لوحة التحكم')</title>

    {{-- خطوط Google Fonts (Cairo, Noto Sans Arabic) و Source Sans 3 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet" />

    {{-- أيقونات Font Awesome و Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">

    {{-- OverlayScrollbars (لأشرطة التمرير المخصصة) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css">

    {{-- ملفات AdminLTE CSS الأساسية --}}
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.rtl.min.css') }}">

    {{-- مكتبات الرسوم البيانية والخرائط (ApexCharts, jsVectorMap) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">

    {{-- مكان لحقن أنماط CSS إضافية خاصة بالصفحة. استخدم @section('styles') في الصفحات الفرعية --}}
    @yield('styles')

    {{-- الأنماط المخصصة (يمكن نقلها إلى ملف app.css أو ملف CSS مخصص آخر للحفاظ على التنظيم) --}}
    <style>
        /* الخطوط */
        body {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif;
        }

        /* Global body background */
        body {
            /* جلب مسار الصورة من إعدادات التطبيق (المعبأة بواسطة AppServiceProvider) */
            background-image: url('{{ config('app.background_image_url') }}');
            background-size: cover; /* تغطية كاملة للمساحة */
            background-position: center; /* توسيط الصورة */
            background-repeat: no-repeat; /* عدم تكرار الصورة */
            background-attachment: fixed; /* لتثبيت الصورة أثناء التمرير (تأثير البارالاكس) */
            position: relative; /* لجعل الطبقة الشفافة تعمل بشكل صحيح */
            background-color: #2c3e50; /* Fallback color for areas not covered or if image fails */
        }

        /* طبقة تراكب لجعل النص أكثر وضوحاً على الصورة الخلفية مع شفافية أقل */
        body::before {
            content: '';
            position: fixed; /* Fixed to cover the whole viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* طبقة تعتيم أغمق لتحسين وضوح النص */
            z-index: -1; /* وضعها خلف المحتوى الرئيسي */
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
            font-size: 1.6rem; /* حجم أكبر بشكل ملحوظ لعناوين الصفحات */
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
            font-size: 2rem; /* حجم أكبر للأيقونات */
            margin-left: 15px; /* مسافة بين الأيقونة والنص */
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* انتقال أكثر سلاسة */
        }

        /* عند تحويم الماوس: تكبير وتحريك ولون */
        .nav-sidebar > .nav-item > .nav-link:hover {
            color: #72efdd !important; /* لون جذاب عند التحويم (أزرق مخضر نيون) */
            transform: translateX(10px) scale(1.02); /* حركة وتكبير أكثر وضوحاً */
            background-color: rgba(255, 255, 255, 0.08) !important; /* خلفية شفافة عند التحويم */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* ظل خفيف عند التحويم */
        }

        .nav-sidebar > .nav-item > .nav-link:hover .nav-icon {
            transform: scale(1.2) rotate(10deg); /* تكبير ودوران الأيقونة عند التحويم */
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

        /* أنماط الإشعارات */
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            padding: 3px 7px;
            border-radius: 50%;
            background-color: #dc3545; /* أحمر */
            color: white;
            font-size: 0.75em;
            font-weight: bold;
            display: none; /* مخفي افتراضياً */
        }

        .notifications-dropdown-menu {
            width: 300px; /* عرض ثابت للقائمة المنسدلة */
            max-height: 400px; /* أقصى ارتفاع */
            overflow-y: auto; /* شريط تمرير إذا تجاوز الارتفاع */
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            padding: 10px;
        }

        .notifications-dropdown-menu .dropdown-header,
        .notifications-dropdown-menu .dropdown-item {
            color: #333;
            padding: 8px 15px;
            white-space: normal; /* السماح بتعدد الأسطر */
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .notifications-dropdown-menu .dropdown-item:last-child {
            border-bottom: none;
        }

        .notifications-dropdown-menu .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .notification-item.unread {
            background-color: rgba(255, 255, 0, 0.1); /* خلفية خفيفة للإشعارات غير المقروءة */
            font-weight: bold;
        }

        .notification-item .notification-time {
            font-size: 0.8em;
            color: #666;
            display: block;
            margin-top: 5px;
        }

        /* أنماط مخصصة للحفاظ على تدرجات البطاقات وتأثيرات التحويم مع شفافية محسنة وتأثير 3D خفيف */
        .card-dashboard {
            border-radius: 20px; /* حواف مستديرة أكثر */
            color: white;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* انتقال أكثر سلاسة */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3); /* ظل أوضح وأكثر انتشاراً */
            border: 1px solid rgba(255, 255, 255, 0.2); /* حدود خفيفة */
            overflow: hidden; /* لضمان عدم خروج الظل عند التحويم */
            perspective: 1000px; /* لتمكين تأثير 3D على العناصر الأبناء */
            backdrop-filter: blur(10px); /* تأثير الزجاج المتجمد أقوى للبطاقات الفردية */
        }

        .card-dashboard:hover {
            transform: translateY(-10px) scale(1.05) rotateX(5deg); /* حركة أكبر وتكبير ودوران 3D خفيف */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5); /* ظل أقوى عند التحويم */
            border-color: rgba(114, 239, 221, 0.5); /* تغيير لون الحدود عند التحويم */
        }

        /* ألوان البطاقات - تم تقليل الشفافية إلى 0.75 */
        .card-general-cleaning {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.75), rgba(142, 68, 173, 0.75));
        }

        .card-sanitation-facility {
            background: linear-gradient(135deg, rgba(155, 89, 182, 0.75), rgba(231, 76, 60, 0.75));
        }

        .card-daily-status {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.75), rgba(241, 196, 15, 0.75));
        }

        .card-resource-report {
            background: linear-gradient(135deg, rgba(52, 73, 94, 0.75), rgba(127, 140, 141, 0.75));
        }

        .card-monthly-cleaning-report {
            background: linear-gradient(135deg, rgba(230, 126, 34, 0.75), rgba(211, 84, 0, 0.75));
        }

        .card-monthly-sanitation-report {
            background: linear-gradient(135deg, rgba(26, 188, 156, 0.75), rgba(39, 174, 96, 0.75));
        }

        .card-employees {
            background: linear-gradient(135deg, rgba(109, 40, 217, 0.75), rgba(75, 0, 130, 0.75));
        }

        .card-visitor-survey {
            background: linear-gradient(135deg, rgba(10, 207, 131, 0.75), rgba(0, 172, 193, 0.75)); /* Light blue/green gradient */
        }

        /* NEW CARDS GRADIENTS - Added specific gradients for new cards for variety */
        .card-photo-reports {
            background: linear-gradient(135deg, rgba(255, 165, 0, 0.75), rgba(255, 99, 71, 0.75)); /* Orange to Tomato */
        }
        .card-background-settings {
            background: linear-gradient(135deg, rgba(128, 0, 128, 0.75), rgba(75, 0, 130, 0.75)); /* Purple to Indigo */
        }
        .card-service-tasks-board {
            background: linear-gradient(135deg, rgba(0, 128, 128, 0.75), rgba(0, 139, 139, 0.75)); /* Teal to DarkCyan */
        }
        .card-actual-results {
            background: linear-gradient(135deg, rgba(255, 0, 0, 0.75), rgba(178, 34, 34, 0.75)); /* Red to FireBrick */
        }
        .card-resource-tracking {
            background: linear-gradient(135deg, rgba(0, 128, 0, 0.75), rgba(34, 139, 34, 0.75)); /* Green to ForestGreen */
        }
        .card-unit-goals {
            background: linear-gradient(135deg, rgba(70, 130, 180, 0.75), rgba(100, 149, 237, 0.75)); /* SteelBlue to CornflowerBlue */
        }
        .card-gilbert-triangle {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.75), rgba(218, 165, 32, 0.75)); /* Gold to Goldenrod */
        }
        .card-users {
            background: linear-gradient(135deg, rgba(65, 105, 225, 0.75), rgba(0, 0, 205, 0.75)); /* RoyalBlue to MediumBlue */
        }
        .card-roles {
            background: linear-gradient(135deg, rgba(138, 43, 226, 0.75), rgba(75, 0, 130, 0.75)); /* BlueViolet to Indigo */
        }
        .card-survey-charts {
            background: linear-gradient(135deg, rgba(255, 105, 180, 0.75), rgba(255, 20, 147, 0.75)); /* HotPink to DeepPink */
        }

        /* أنماط لأزرار الإدارة داخل البطاقات */
        .btn-card-action {
            background-color: rgba(255, 255, 255, 0.98); /* شفافية أعلى قليلاً للأزرار */
            color: #34495e; /* لون نص داكن مناسب للخلفية الفاتحة */
            border-radius: 50px; /* rounded-full */
            font-weight: bold;
            padding: 0.6rem 1.8rem; /* حشوة أكبر */
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2); /* ظل أوضح للأزرار */
            transition: all 0.3s ease;
            font-size: 1.05rem; /* حجم خط أكبر قليلاً */
        }
        .btn-card-action:hover {
            background-color: rgba(255, 255, 255, 1);
            color: #1a1a1a;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4); /* ظل أقوى عند التحويم */
            transform: translateY(-2px); /* رفع الزر قليلاً */
        }

        /* أنماط لرسالة الترحيب */
        .welcome-title {
            animation: fadeIn 1.5s ease-out forwards; /* مدة أطول للظهور */
            color: #ffffff; /* لون أبيض لعنوان الترحيب */
            text-shadow: 2px 2px 5px rgba(0,0,0,0.8); /* ظل أقوى لعنوان الترحيب */
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .typing-message {
            color: #ffffff; /* لون أبيض لرسالة الترحيب لتكون واضحة على الخلفية الداكنة */
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6); /* إضافة ظل خفيف للنص */
            font-size: 1.2rem; /* حجم أكبر لرسالة الكتابة */
            font-weight: 500; /* سمك خط متوسط */
        }

        @keyframes blink {
            50% { opacity: 0; }
        }
        .animate-blink {
            animation: blink 1s step-end infinite;
        }

        /* تحسينات عامة على الخطوط والعناصر */
        .h3 {
            font-size: 1.8rem; /* حجم أكبر لعناوين البطاقات */
            text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
        }
        .text-white.fs-1.opacity-75 {
            font-size: 3.5rem !important; /* أيقونات أكبر */
            opacity: 0.85 !important;
            transition: transform 0.3s ease;
        }
        .card-dashboard:hover .text-white.fs-1.opacity-75 {
            transform: rotate(10deg) scale(1.1); /* دوران وتكبير للأيقونات عند التحويم */
        }
        .text-white.opacity-90 {
            font-size: 1.05rem; /* حجم أكبر لوصف البطاقات */
            line-height: 1.6; /* مسافة أسطر أفضل */
        }
    </style>
</head>
{{-- تم تعديل classes هنا لضمان تثبيت الشريط الجانبي وحل مشكلة الارتفاع --}}
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse"> {{-- تم إضافة sidebar-collapse هنا لجعله يبدأ مغلقاً --}}
<div class="app-wrapper">

    {{-- تضمين شريط التنقل العلوي. يوجد الكود في resources/views/layouts/partials/_navbar.blade.php --}}
    @include('layouts.partials._navbar')

    {{-- تضمين الشريط الجانبي. يوجد الكود في resources/views/layouts/partials/_sidebar.blade.php --}}
    @include('layouts.partials._sidebar')

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
        @include('layouts.partials._footer')
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
            // تهيئة OverlayScrollbars هنا
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, Default); // تم تصحيح هذا السطر
            console.log('OverlayScrollbars جاهز للتهيئة.');
        } else {
            console.warn('OverlayScrollbars غير متاح أو عنصر الشريط الجانبي غير موجود.');
        }
    });
</script>

{{-- مكان لحقن سكريبتات JavaScript إضافية خاصة بالصفحة. استخدم @section('scripts') في الصفحات الفرعية. --}}
@yield('scripts')

</body>
</html>
