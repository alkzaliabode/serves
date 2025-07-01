{{-- resources/views/dashboard.blade.php --}}
{{--
    هذا الملف هو قالب صفحة لوحة التحكم الرئيسية.
    تم تحديثه لجعل الصورة الخلفية واضحة وجعل البطاقات شفافة بشكل أنيق، مع تأثيرات ثلاثية الأبعاد خفيفة.
--}}

@extends('layouts.adminlte') {{-- تعديل ليرث تخطيط AdminLTE الجديد --}}

@section('title', 'لوحة التحكم') {{-- تحديد عنوان الصفحة --}}

@section('page_title', 'لوحة التحكم') {{-- عنوان الصفحة داخل AdminLTE --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection

@section('styles')
    <style>
        /* أنماط خلفية الصفحة بالكامل */
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
            background-color: rgba(0, 0, 0, 0.4); /* طبقة سوداء شفافة بنسبة 40% لتوضيح النص */
            z-index: -1; /* وضعها خلف المحتوى الرئيسي */
        }

        /* تحسين البطاقة الرئيسية لجعلها شفافة بالكامل */
        .card.card-outline.card-info.shadow-lg {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important; /* إزالة الظل أيضاً ليتناسب مع الشفافية */
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
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    <div class="container-fluid py-4"> {{-- استخدام container-fluid وهامش علوي/سفلي --}}
        <div class="card card-outline card-info shadow-lg"> {{-- استخدام بطاقة AdminLTE رئيسية مع ظل --}}
            <div class="card-body p-4" style="position: relative; z-index: 1;"> {{-- تم إزالة background-color بالكامل --}}
                <div class="mb-5 text-center"> {{-- هامش سفلي أكبر للقسم --}}
                    <h1 class="h2 font-weight-bold text-white mb-3 welcome-title"> {{-- Bootstrap heading, text color --}}
                        أهلاً بك، {{ Auth::user()->name }}!
                    </h1>
                    <p x-data="{
                                message: '',
                                fullMessage: 'لقد قمت بتسجيل الدخول بنجاح! استكشف مهامك الآن.',
                                typingSpeed: 50,
                                showText: false,
                                intervalId: null,
                                currentIndex: 0
                            }"
                        x-init="
                                $watch('showText', value => {
                                    if (value) {
                                        if (intervalId) clearInterval(intervalId);
                                        message = '';
                                        currentIndex = 0;
                                        intervalId = setInterval(() => {
                                            if (currentIndex < fullMessage.length) {
                                                message += fullMessage.charAt(currentIndex);
                                                currentIndex++;
                                            } else {
                                                clearInterval(intervalId);
                                            }
                                        }, typingSpeed);
                                    } else {
                                        if (intervalId) clearInterval(intervalId);
                                    }
                                });
                            "
                        x-intersect:enter="showText = true"
                        x-show="showText"
                        x-transition:opacity
                        class="typing-message"
                    >
                        <span x-text="message"></span><span class="d-inline-block animate-blink">_</span>
                    </p>
                </div>

                <div class="row g-4 justify-content-center"> {{-- استخدام نظام شبكة Bootstrap مع تباعد --}}
                    {{-- Existing Cards --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-general-cleaning h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        مهام النظافة العامة
                                    </h3>
                                    <i class="fas fa-broom text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    استعرض، أنشئ، وادير جميع مهام النظافة العامة الخاصة بفريقك بكفاءة.
                                </p>
                                <a href="{{ route('general-cleaning-tasks.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    إدارة المهام
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-sanitation-facility h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        مهام المنشآت الصحية
                                    </h3>
                                    <i class="fas fa-hospital-alt text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    تتبع وادير مهام الصيانة والإدامة للمرافق والمنشآت الصحية بفاعلية.
                                </p>
                                <a href="{{ route('sanitation-facility-tasks.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    إدارة المهام
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-daily-status h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        الموقف اليومي
                                    </h3>
                                    <i class="fas fa-clipboard-list text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    استعرض، أنشئ، وادير سجلات الموقف اليومي لفريق العمل.
                                </p>
                                <a href="{{ route('daily-statuses.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    إدارة المواقف
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-resource-report h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        تقرير الموارد
                                    </h3>
                                    <i class="fas fa-chart-pie text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    استعرض تقارير شاملة حول استخدام الموارد والأداء.
                                </p>
                                <a href="{{ route('resource-report.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض التقرير
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-monthly-cleaning-report h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        تقرير النظافة العامة الشهري
                                    </h3>
                                    <i class="fas fa-chart-bar text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    اطلع على ملخصات وإحصائيات شهرية مفصلة لمهام النظافة العامة.
                                </p>
                                <a href="{{ route('monthly-cleaning-report.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض التقرير
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-monthly-sanitation-report h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        تقرير المنشآت الصحية الشهرية
                                    </h3>
                                    <i class="fas fa-file-medical text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    استعرض ملخصات شهرية وإحصائيات مفصلة لمهام المنشآت الصحية.
                                </p>
                                <a href="{{ route('monthly-sanitation-report.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض التقرير
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-employees h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        إدارة الموظفين
                                    </h3>
                                    <i class="fas fa-users text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    إدارة بيانات الموظفين، الأدوار، والصلاحيات بسهولة وكفاءة.
                                </p>
                                <a href="{{ route('employees.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    إدارة الموظفين
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-visitor-survey h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        استبيان الزائرين
                                    </h3>
                                    <i class="fas fa-clipboard-question text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    إدارة واستعراض استبيانات الزائرين لجمع آرائهم وتحسين تجربتهم.
                                </p>
                                <a href="{{ route('surveys.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض الاستبيانات
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- NEW CARDS --}}
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-photo-reports h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        التقارير المصورة
                                    </h3>
                                    <i class="fas fa-images text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    تصفح وأدر التقارير المصورة للمهام المنجزة.
                                </p>
                                <a href="{{ route('photo_reports.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض التقارير
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-background-settings h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        إعدادات الخلفية
                                    </h3>
                                    <i class="fas fa-image text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    تخصيص صورة الخلفية للوحة التحكم.
                                </p>
                                <a href="{{ route('background-settings.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    تعديل الإعدادات
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-service-tasks-board h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        لوحة مهام الشُعبة الخدمية
                                    </h3>
                                    <i class="fas fa-columns text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    إدارة وتتبع مهام الشُعبة الخدمية باستخدام لوحة كانبان.
                                </p>
                                <a href="{{ route('service-tasks.board.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض اللوحة
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-actual-results h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        النتائج الفعلية
                                    </h3>
                                    <i class="fas fa-chart-line text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    استعرض وأدر سجلات النتائج الفعلية ومقاييس الأداء.
                                </p>
                                <a href="{{ route('actual-results.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض النتائج
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-resource-tracking h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        تتبع الموارد
                                    </h3>
                                    <i class="fas fa-boxes text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    تتبع استخدام الموارد وإدارتها بكفاءة.
                                </p>
                                <a href="{{ route('resource-trackings.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    تتبع الموارد
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-unit-goals h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        أهداف الوحدات
                                    </h3>
                                    <i class="fas fa-flag text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    إدارة وتتبع الأهداف المحددة لكل وحدة.
                                </p>
                                <a href="{{ route('unit-goals.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    إدارة الأهداف
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-gilbert-triangle h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        مخطط جلبرت
                                    </h3>
                                    <i class="fas fa-project-diagram text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    تحليل الأداء باستخدام مخطط مثلث جلبرت للفاعلية والكفاءة والملاءمة.
                                </p>
                                <a href="{{ route('charts.gilbert-triangle.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض المخطط
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-users h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        إدارة المستخدمين
                                    </h3>
                                    <i class="fas fa-user-shield text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    إدارة حسابات المستخدمين وصلاحياتهم.
                                </p>
                                <a href="{{ route('users.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    إدارة المستخدمين
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-roles h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        إدارة الأدوار
                                    </h3>
                                    <i class="fas fa-user-tag text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    تحديد وإدارة الأدوار والصلاحيات المختلفة للمستخدمين.
                                </p>
                                <a href="{{ route('roles.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    إدارة الأدوار
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card card-dashboard card-survey-charts h-100 cursor-pointer">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="h3 font-weight-bold text-white">
                                        مخططات الاستبيانات
                                    </h3>
                                    <i class="fas fa-chart-pie text-white fs-1 opacity-75"></i>
                                </div>
                                <p class="text-white opacity-90 mb-4">
                                    استعراض الرسوم البيانية والتحليلات لبيانات استبيان الزائرين.
                                </p>
                                <a href="{{ route('charts.surveys.index') }}"
                                   class="btn btn-card-action text-decoration-none">
                                    عرض المخططات
                                    <i class="fas fa-arrow-right me-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts') {{-- لربط سكربتات خاصة باللوحة التحكم إذا لزم الأمر --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // أي سكربتات إضافية يمكن وضعها هنا
        });
    </script>
@endsection
