@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل مهمة نظافة عامة') {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', 'تعديل مهمة نظافة عامة') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('general-cleaning-tasks.index') }}">مهام النظافة العامة</a></li>
    <li class="breadcrumb-item active">تعديل مهمة</li>
@endsection

@section('styles')
    {{-- إضافة animate.css لتأثيرات الدخول --}}
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

        /* أنماط البطاقات لتكون شفافة بالكامل مع تأثير زجاجي وخطوط بارزة (تأثير الزجاج المتجمد) */
        .card {
            background: rgba(255, 255, 255, 0.08) !important; /* شفافية عالية جداً */
            backdrop-filter: blur(8px) !important; /* تأثير الزجاج المتجمد */
            border-radius: 1rem !important; /* حواف مستديرة */
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important; /* ظل أنعم */
            border: 1px solid rgba(255, 255, 255, 0.2) !important; /* حدود بارزة وواضحة */
        }
        .card-header {
            background-color: rgba(255, 255, 255, 0.15) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important; /* حدود سفلية شفافة وواضحة */
        }
        
        /* General text size increase and distinctive color for main texts */
        body,
        .card-body {
            font-size: 1.05rem; /* Slightly larger body text */
            line-height: 1.6; /* Improved line spacing for readability */
            color: white !important; /* لون نص أبيض لجسم البطاقة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5) !important; /* ظل خفيف للنص */
        }

        /* Titles and Headers - make them more prominent and interactive */
        .card-title,
        .card-header h2.card-title, /* Target the h2 specifically */
        .card-header .btn {
            font-size: 1.5rem !important; /* Larger titles */
            font-weight: 700 !important; /* Bolder */
            color: var(--accent-color) !important; /* Distinctive color for titles */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.9) !important; /* Stronger shadow */
            transition: color 0.3s ease, text-shadow 0.3s ease; /* Smooth transition */
        }
        .card-title:hover,
        .card-header h2.card-title:hover {
            color: #ffffff !important; /* Change color on hover for interactivity */
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 1.0) !important;
        }

        /* أنماط حقول الإدخال والاختيار والتكست اريا */
        .form-control,
        .form-select,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            background-color: rgba(255, 255, 255, 0.1) !important; /* شفافية عالية جدًا لحقول الإدخال */
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important; /* لون نص أبيض للحقول */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6) !important;
            font-size: 1.1rem !important; /* Larger text inside inputs */
            padding: 0.75rem 1rem !important; /* More padding for better feel */
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important; /* لون أفتح لـ placeholder */
        }
        .form-control:focus,
        .form-select:focus,
        input:focus,
        textarea:focus,
        select:focus {
            background-color: rgba(255, 255, 255, 0.2) !important; /* يصبح أكثر شفافية عند التركيز */
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.5) !important;
        }
        .form-select option {
            background-color: #2c3e50 !important; /* خلفية داكنة لخيار القائمة */
            color: white !important; /* نص أبيض لخيار القائمة */
        }

        /* أنماط تسميات الحقول - bigger and more distinct */
        .form-label,
        label {
            font-size: 1.1rem !important; /* Larger labels */
            font-weight: 600 !important; /* Bolder */
            color: var(--accent-color) !important; /* Distinctive color for labels */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8) !important;
        }

        /* أنماط الأزرار */
        .btn {
            font-weight: 600; /* Make button text bolder */
            padding: 0.6rem 1.2rem; /* Adjust padding for larger text */
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease; /* Add transform and box-shadow to transition */
            color: white !important;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4) !important; /* ظل للأزرار */
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-2px); /* Slight lift on hover */
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.6) !important;
            filter: brightness(1.2); /* Slightly brighter on hover */
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.4) !important;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4) !important;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6) !important;
            filter: brightness(1.2);
        }

        /* أنماط الأيقونات في الأزرار */
        .btn .fas {
            margin-right: 5px; /* مسافة بين الأيقونة والنص */
        }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important; /* خلفية شفافة للرسائل */
            color: #333 !important; /* لون نص داكن */
            border-color: rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.9) !important; /* خلفية خضراء شفافة للنجاح */
            color: white !important;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.9) !important; /* خلفية حمراء شفافة للخطأ */
            color: white !important;
        }

        /* أنماط النص المساعد (form-text) - Slightly larger helper text */
        .form-text {
            font-size: 0.95rem !important;
            color: rgba(255, 255, 255, 0.7) !important; /* لون أبيض شفاف للنص المساعد */
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5) !important;
        }

        /* أنماط Fieldset وتفاصيلها - more prominent */
        fieldset {
            border: 1px solid rgba(255, 255, 255, 0.3) !important; /* حدود شفافة */
            padding: 1.5rem !important;
            border-radius: 0.75rem !important;
            margin-bottom: 1.5rem !important;
            background-color: rgba(255, 255, 255, 0.05); /* خلفية خفيفة جداً للـ fieldset */
        }
        fieldset legend {
            font-size: 1.5rem !important; /* Even larger for legends */
            font-weight: 700 !important;
            color: var(--accent-color) !important; /* Distinctive color for legends */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.9) !important;
            padding: 0 0.5rem;
            border-bottom: none; /* إزالة الخط الافتراضي */
            width: auto; /* يجعل الـ legend يأخذ عرض محتواه فقط */
        }

        /* لضمان شفافية العناصر الداخلية لـ Livewire أو Jetstream */
        .bg-white,
        .shadow.sm\:rounded-lg,
        .px-4.py-5.sm\:p-6,
        .sm\:px-6.lg\:px-8,
        .max-w-7xl.mx-auto.py-10.sm\:px-6.lg\:px-8,
        .w-full.bg-white.shadow.overflow-hidden.sm\:rounded-lg,
        .w-full.bg-gray-800.sm\:rounded-lg.shadow,
        .border-gray-200.dark\:border-gray-700,
        div[x-data] { /* استهداف عام لأي divs تنشئها Livewire / Alpine.js */
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }

        .form-group,
        form > div,
        .input-group,
        .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-12,
        div[class*="col-"] {
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }

        /* Adjustments for repeater labels to ensure they are distinctive */
        #resources-repeater .form-label,
        #employees-repeater .form-label {
            color: var(--accent-color) !important;
        }

        /* --- New Styles for Unit Goals Text Alignment --- */
        /* Custom text sizes to override AdminLTE defaults if needed */
        .text-xl { font-size: 1.15rem !important; } /* For unit goal titles */
        .text-xxl { font-size: 1.35rem !important; } /* For department goal titles */

        /* Specific styling for the unit goal card titles to ensure proper wrapping and alignment */
        .card.animated-card .card-body h5.card-title {
            /* Ensure text wraps and breaks words if necessary */
            white-space: normal !important; /* السماح للنص بالالتفاف بشكل طبيعي */
            word-break: break-word !important; /* كسر الكلمات الطويلة لمنع التجاوز الأفقي */
            overflow-wrap: break-word !important; /* بديل لـ word-break */

            /* Ensure proper alignment for RTL and prevent pushing off the right edge */
            text-align: right !important; /* محاذاة صريحة لليمين للغات RTL */
            padding-right: 0 !important; /* إزالة أي حشوة يمنى افتراضية أو موروثة */
            margin-right: 0 !important;  /* إزالة أي هامش أيمن افتراضي أو موروث */

            /* Use flexbox for icon-text alignment */
            display: flex; /* استخدام فليكس بوكس للمحاذاة الأفقية */
            align-items: flex-start; /* محاذاة الأيقونة والنص من الأعلى */
            gap: 0.5rem; /* مسافة بين الأيقونة والنص */
        }

        /* Adjust icon within the flex container */
        .card.animated-card .card-body h5.card-title .card-icon {
            flex-shrink: 0; /* منع الأيقونة من الانكماش */
            margin-right: 0 !important; /* إزالة الهامش الأيمن الافتراضي حيث أن 'gap' يتعامل مع المسافة */
            margin-left: 0 !important; /* التأكد من عدم وجود هامش أيسر يدفع الأيقونة */
        }
        /* --- End New Styles --- */
    </style>
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    <div class="container-fluid">
        <div class="card"> {{-- استخدام بطاقة AdminLTE --}}
            <div class="card-header">
                <h3 class="card-title">تعديل مهمة نظافة عامة</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('general-cleaning-tasks.update', $generalCleaningTask) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- **هذا هو الحقل المخفي الجديد لـ unit_id** --}}
                    <input type="hidden" name="unit_id" value="{{ old('unit_id', $generalCleaningTask->unit_id) }}">

                    <div class="card card-info card-outline"> {{-- استخدام بطاقة AdminLTE كقسم للنموذج --}}
                        <div class="card-header">
                            <h2 class="card-title">المعلومات الأساسية</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date" class="form-label">التاريخ</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $generalCleaningTask->date) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="shift" class="form-label">الوجبة</label>
                                    <select class="form-select" id="shift" name="shift" required>
                                        <option value="صباحي" {{ old('shift', $generalCleaningTask->shift) == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                        <option value="مسائي" {{ old('shift', $generalCleaningTask->shift) == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                        <option value="ليلي" {{ old('shift', $generalCleaningTask->shift) == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="مكتمل" {{ old('status', $generalCleaningTask->status) == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="قيد التنفيذ" {{ old('status', $generalCleaningTask->status) == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                        <option value="ملغى" {{ old('status', $generalCleaningTask->status) == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="related_goal_id" class="form-label">الهدف المرتبط</label>
                                <select class="form-select" id="related_goal_id" name="related_goal_id" required>
                                    <option value="">اختر الهدف المرتبط</option>
                                    @foreach($goals as $goal)
                                        <option value="{{ $goal->id }}" {{ old('related_goal_id', $generalCleaningTask->related_goal_id) == $goal->id ? 'selected' : '' }}>{{ $goal->goal_text }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted">اختر الهدف الاستراتيجي أو التشغيلي الذي تساهم فيه هذه المهمة.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">تفاصيل المهمة</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="task_type" class="form-label">نوع المهمة</label>
                                    <select class="form-select" id="task_type" name="task_type" required>
                                        <option value="إدامة" {{ old('task_type', $generalCleaningTask->task_type) == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                        <option value="صيانة" {{ old('task_type', $generalCleaningTask->task_type) == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="form-label">الموقع</label>
                                    <select class="form-select" id="location" name="location" required>
                                        <option value="">اختر الموقع</option>
                                        {{-- القاعات --}}
                                        <option value="قاعة 1 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 1 الأسفل' ? 'selected' : '' }}>قاعة 1 الأسفل</option>
                                        <option value="قاعة 1 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 1 الأعلى' ? 'selected' : '' }}>قاعة 1 الأعلى</option>
                                        <option value="قاعة 2 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 2 الأسفل' ? 'selected' : '' }}>قاعة 2 الأسفل</option>
                                        <option value="قاعة 2 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 2 الأعلى' ? 'selected' : '' }}>قاعة 2 الأعلى</option>
                                        <option value="قاعة 3 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 3 الأسفل' ? 'selected' : '' }}>قاعة 3 الأسفل</option>
                                        <option value="قاعة 3 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 3 الأعلى' ? 'selected' : '' }}>قاعة 3 الأعلى</option>
                                        <option value="قاعة 4 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 4 الأسفل' ? 'selected' : '' }}>قاعة 4 الأسفل</option>
                                        <option value="قاعة 4 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 4 الأعلى' ? 'selected' : '' }}>قاعة 4 الأعلى</option>
                                        <option value="قاعة 5 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 5 الأسفل' ? 'selected' : '' }}>قاعة 5 الأسفل</option>
                                        <option value="قاعة 5 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 5 الأعلى' ? 'selected' : '' }}>قاعة 5 الأعلى</option>
                                        <option value="قاعة 6 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 6 الأسفل' ? 'selected' : '' }}>قاعة 6 الأسفل</option>
                                        <option value="قاعة 6 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 6 الأعلى' ? 'selected' : '' }}>قاعة 6 الأعلى</option>
                                        <option value="قاعة 7 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 7 الأسفل' ? 'selected' : '' }}>قاعة 7 الأسفل</option>
                                        <option value="قاعة 7 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 7 الأعلى' ? 'selected' : '' }}>قاعة 7 الأعلى</option>
                                        <option value="قاعة 8 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 8 الأسفل' ? 'selected' : '' }}>قاعة 8 الأسفل</option>
                                        <option value="قاعة 8 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 8 الأعلى' ? 'selected' : '' }}>قاعة 8 الأعلى</option>
                                        <option value="قاعة 9 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 9 الأسفل' ? 'selected' : '' }}>قاعة 9 الأسفل</option>
                                        <option value="قاعة 9 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 9 الأعلى' ? 'selected' : '' }}>قاعة 9 الأعلى</option>
                                        <option value="قاعة 10 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 10 الأسفل' ? 'selected' : '' }}>قاعة 10 الأسفل</option>
                                        <option value="قاعة 10 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 10 الأعلى' ? 'selected' : '' }}>قاعة 10 الأعلى</option>
                                        <option value="قاعة 11 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 11 الأسفل' ? 'selected' : '' }}>قاعة 11 الأسفل</option>
                                        <option value="قاعة 11 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 11 الأعلى' ? 'selected' : '' }}>قاعة 11 الأعلى</option>
                                        <option value="قاعة 12 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 12 الأسفل' ? 'selected' : '' }}>قاعة 12 الأسفل</option>
                                        <option value="قاعة 12 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 12 الأعلى' ? 'selected' : '' }}>قاعة 12 الأعلى</option>
                                        <option value="قاعة 13 الأسفل" {{ old('location', $generalCleaningTask->location) == 'قاعة 13 الأسفل' ? 'selected' : '' }}>قاعة 13 الأسفل</option>
                                        <option value="قاعة 13 الأعلى" {{ old('location', $generalCleaningTask->location) == 'قاعة 13 الأعلى' ? 'selected' : '' }}>قاعة 13 الأعلى</option>
                                        {{-- المناطق الخارجية --}}
                                        <option value="جميع القواطع الخارجية" {{ old('location', $generalCleaningTask->location) == 'جميع القواطع الخارجية' ? 'selected' : '' }}>جميع القواطع الخارجية</option>
                                        <option value="الترامز" {{ old('location', $generalCleaningTask->location) == 'الترامز' ? 'selected' : '' }}>الترامز</option>
                                        <option value="السجاد" {{ old('location', $generalCleaningTask->location) == 'السجاد' ? 'selected' : '' }}>السجاد</option>
                                        <option value="الحاويات" {{ old('location', $generalCleaningTask->location) == 'الحاويات' ? 'selected' : '' }}>الحاويات</option>
                                        <option value="الجامع" {{ old('location', $generalCleaningTask->location) == 'الجامع' ? 'selected' : '' }}>الجامع</option>
                                        <option value="المركز الصحي" {{ old('location', $generalCleaningTask->location) == 'المركز الصحي' ? 'selected' : '' }}>الالمركز الصحي</option>
                                        <option value="المرافق الصحية" {{ old('location', $generalCleaningTask->location) == 'المرافق الصحية' ? 'selected' : '' }}>المرافق الصحية</option>
                                    </select>
                                </div>
                            </div>

                            <fieldset id="task-details-fieldset" class="mb-3 p-3 border border-dashed border-secondary rounded" style="display: {{ old('location', $generalCleaningTask->location) ? 'block' : 'none' }};">
                                <legend class="text-lg font-semibold mb-3">تفاصيل التنفيذ</legend>
                                <div id="room-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-3 mb-3"><label for="mats_count" class="form-label">عدد المنادر المدامة</label><input type="number" class="form-control" id="mats_count" name="mats_count" min="0" value="{{ old('mats_count', $generalCleaningTask->mats_count ?? 0) }}"></div>
                                    <div class="col-md-3 mb-3"><label for="pillows_count" class="form-label">عدد الوسادات المدامة</label><input type="number" class="form-control" id="pillows_count" name="pillows_count" min="0" value="{{ old('pillows_count', $generalCleaningTask->pillows_count ?? 0) }}"></div>
                                    <div class="col-md-3 mb-3"><label for="fans_count" class="form-label">عدد المراوح المدامة</label><input type="number" class="form-control" id="fans_count" name="fans_count" min="0" value="{{ old('fans_count', $generalCleaningTask->fans_count ?? 0) }}"></div>
                                    <div class="col-md-3 mb-3"><label for="windows_count" class="form-label">عدد النوافذ المدامة</label><input type="number" class="form-control" id="windows_count" name="windows_count" min="0" value="{{ old('windows_count', $generalCleaningTask->windows_count ?? 0) }}"></div>
                                    <div class="col-md-3 mb-3"><label for="carpets_count" class="form-label">عدد السجاد المدام</label><input type="number" class="form-control" id="carpets_count" name="carpets_count" min="0" value="{{ old('carpets_count', $generalCleaningTask->carpets_count ?? 0) }}"></div>
                                    <div class="col-md-3 mb-3"><label for="blankets_count" class="form-label">عدد البطانيات المدامة</label><input type="number" class="form-control" id="blankets_count" name="blankets_count" min="0" value="{{ old('blankets_count', $generalCleaningTask->blankets_count ?? 0) }}"></div>
                                    <div class="col-md-3 mb-3"><label for="beds_count" class="form-label">عدد الأسرة</label><input type="number" class="form-control" id="beds_count" name="beds_count" min="0" value="{{ old('beds_count', $generalCleaningTask->beds_count ?? 0) }}"></div>
                                    <div class="col-md-3 mb-3"><label for="beneficiaries_count" class="form-label">عدد المستفيدين من القاعة</label><input type="number" class="form-control" id="beneficiaries_count" name="beneficiaries_count" min="0" value="{{ old('beneficiaries_count', $generalCleaningTask->beneficiaries_count ?? 0) }}"></div>
                                </div>
                                <div id="trams-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-6 mb-3"><label for="filled_trams_count" class="form-label">عدد الترامز المملوئة والمدامة</label><input type="number" class="form-control" id="filled_trams_count" name="filled_trams_count" min="0" value="{{ old('filled_trams_count', $generalCleaningTask->filled_trams_count ?? 0) }}"></div>
                                </div>
                                <div id="carpets-laid-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-6 mb-3"><label for="carpets_laid_count" class="form-label">عدد السجاد المفروش في الساحات</label><input type="number" class="form-control" id="carpets_laid_count" name="carpets_laid_count" min="0" value="{{ old('carpets_laid_count', $generalCleaningTask->carpets_laid_count ?? 0) }}"></div>
                                </div>
                                <div id="containers-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-6 mb-3"><label for="large_containers_count" class="form-label">عدد الحاويات الكبيرة المفرغة والمدامة</label><input type="number" class="form-control" id="large_containers_count" name="large_containers_count" min="0" value="{{ old('large_containers_count', $generalCleaningTask->large_containers_count ?? 0) }}"></div>
                                    <div class="col-md-6 mb-3"><label for="small_containers_count" class="form-label">عدد الحاويات الصغيرة المفرغة والمدامة</label><input type="number" class="form-control" id="small_containers_count" name="small_containers_count" min="0" value="{{ old('small_containers_count', $generalCleaningTask->small_containers_count ?? 0) }}"></div>
                                </div>
                                <div id="maintenance-details-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-12 mb-3"><label for="maintenance_details" class="form-label">تفاصيل الإدامة اليومية</label><textarea class="form-control" id="maintenance_details" name="maintenance_details" rows="3">{{ old('maintenance_details', $generalCleaningTask->maintenance_details) }}</textarea></div>
                                </div>
                            </fieldset>

                            <fieldset id="external-partitions-fieldset" class="mb-3 p-3 border border-dashed border-secondary rounded" style="display: {{ old('location', $generalCleaningTask->location) == 'جميع القواطع الخارجية' ? 'block' : 'none' }};">
                                <legend class="text-lg font-semibold mb-3">تفاصيل القواطع الخارجية</legend>
                                <div class="mb-3">
                                    <label for="external_partitions_count" class="form-label">عدد القواطع الخارجية المدامة</label>
                                    <input type="number" class="form-control" id="external_partitions_count" name="external_partitions_count" min="0" value="{{ old('external_partitions_count', $generalCleaningTask->external_partitions_count ?? 0) }}">
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h2 class="text-xl font-semibold mb-3">الموارد المستخدمة وساعات العمل</h2>
                        <div class="mb-3">
                            <label for="working_hours" class="form-label">إجمالي ساعات العمل للمهمة</label>
                            <input type="number" step="0.5" class="form-control" id="working_hours" name="working_hours" min="0" max="24" value="{{ old('working_hours', $generalCleaningTask->working_hours) }}" required>
                            <div class="form-text text-muted">إجمالي ساعات العمل التي استغرقتها هذه المهمة.</div>
                        </div>

                        <h3 class="text-lg font-semibold mb-3">الموارد الأخرى المستخدمة</h3>
                        <div id="resources-repeater">
                            @php $oldResources = old('resources_used', $generalCleaningTask->resources_used ?? []); @endphp
                            @forelse ($oldResources as $index => $resource)
                                <div class="row mb-3 resource-item">
                                    <div class="col-md-5 mb-3 mb-md-0"><label class="form-label">اسم المورد</label><input type="text" class="form-control" name="resources_used[{{ $index }}][name]" value="{{ $resource['name'] ?? '' }}" required></div>
                                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">الكمية</label><input type="number" class="form-control" name="resources_used[{{ $index }}][quantity]" min="0" value="{{ $resource['quantity'] ?? '' }}" required></div>
                                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">وحدة القياس</label>
                                        <select class="form-select" name="resources_used[{{ $index }}][unit]" required>
                                            <option value="قطعة" {{ ($resource['unit'] ?? '') == 'قطعة' ? 'selected' : '' }}>قطعة</option>
                                            <option value="كرتون" {{ ($resource['unit'] ?? '') == 'كرتون' ? 'selected' : '' }}>كرتون</option>
                                            <option value="رول" {{ ($resource['unit'] ?? '') == 'رول' ? 'selected' : '' }}>رول</option>
                                            <option value="لتر" {{ ($resource['unit'] ?? '') == 'لتر' ? 'selected' : '' }}>لتر</option>
                                            <option value="عبوة" {{ ($resource['unit'] ?? '') == 'عبوة' ? 'selected' : '' }}>عبوة</option>
                                            <option value="أخرى" {{ ($resource['unit'] ?? '') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-resource"><i class="fas fa-trash"></i></button></div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-resource-button">
                            <i class="fas fa-plus"></i> إضافة مورد جديد
                        </button>
                    </div>

                    <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h2 class="text-xl font-semibold mb-3">المنفذون والتقييم</h2>
                        <div id="employees-repeater">
                            @php $oldEmployeeTasks = old('employeeTasks', $generalCleaningTask->employeeTasks->toArray() ?? []); @endphp
                            @forelse ($oldEmployeeTasks as $index => $employeeTask)
                                <div class="row mb-3 employee-task-item">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label">الموظف</label>
                                        <select class="form-select" name="employeeTasks[{{ $index }}][employee_id]" required>
                                            <option value="">اختر الموظف</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ ($employeeTask['employee_id'] ?? '') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label class="form-label">تقييم الأداء</label>
                                        <select class="form-select" name="employeeTasks[{{ $index }}][employee_rating]" required>
                                            <option value="">اختر التقييم</option>
                                            <option value="1" {{ ($employeeTask['employee_rating'] ?? '') == 1 ? 'selected' : '' }}>★ (ضعيف)</option>
                                            <option value="2" {{ ($employeeTask['employee_rating'] ?? '') == 2 ? 'selected' : '' }}>★★</option>
                                            <option value="3" {{ ($employeeTask['employee_rating'] ?? '') == 3 ? 'selected' : '' }}>★★★ (متوسط)</option>
                                            <option value="4" {{ ($employeeTask['employee_rating'] ?? '') == 4 ? 'selected' : '' }}>★★★★</option>
                                            <option value="5" {{ ($employeeTask['employee_rating'] ?? '') == 5 ? 'selected' : '' }}>★★★★★ (ممتاز)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button></div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-employee-task-button">
                            <i class="fas fa-plus"></i> إضافة منفذ جديد
                        </button>
                    </div>

                    <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h2 class="text-xl font-semibold mb-3">المرفقات</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="before_images" class="form-label">صور قبل التنفيذ</label>
                                <input type="file" class="form-control" id="before_images" name="before_images[]" multiple accept="image/*">
                                <div class="form-text text-muted">يمكنك رفع عدة صور توضح حالة الموقع قبل بدء المهمة.</div>
                                @if ($generalCleaningTask->before_images)
                                    <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                        @foreach($generalCleaningTask->before_images as $imagePath)
                                            <div class="image-item position-relative me-2 mb-2">
                                                <img src="{{ Storage::url($imagePath) }}" alt="صورة قبل" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-existing-image" data-path="{{ $imagePath }}" style="z-index: 1;">&times;</button>
                                                <input type="hidden" name="existing_before_images[]" value="{{ $imagePath }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="after_images" class="form-label">صور بعد التنفيذ</label>
                                <input type="file" class="form-control" id="after_images" name="after_images[]" multiple accept="image/*">
                                <div class="form-text text-muted">يمكنك رفع عدة صور توضح حالة الموقع بعد انتهاء المهمة.</div>
                                @if ($generalCleaningTask->after_images)
                                    <div class="image-preview mt-2 d-flex flex-wrap gap-2">
                                        @foreach($generalCleaningTask->after_images as $imagePath)
                                            <div class="image-item position-relative me-2 mb-2">
                                                <img src="{{ Storage::url($imagePath) }}" alt="صورة بعد" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-existing-image" data-path="{{ $imagePath }}" style="z-index: 1;">&times;</button>
                                                <input type="hidden" name="existing_after_images[]" value="{{ $imagePath }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-section mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h2 class="text-xl font-semibold mb-3">ملاحظات إضافية</h2>
                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $generalCleaningTask->notes) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">
                        <i class="fas fa-save"></i> تحديث مهمة النظافة
                    </button>
                    <a href="{{ route('general-cleaning-tasks.index') }}" class="btn btn-secondary mt-4">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts') {{-- لربط السكربتات الخاصة بهذه الصفحة --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationSelect = document.getElementById('location');
            const taskDetailsFieldset = document.getElementById('task-details-fieldset');
            const externalPartitionsFieldset = document.getElementById('external-partitions-fieldset');

            const roomFields = document.getElementById('room-fields');
            const tramsFields = document.getElementById('trams-fields');
            const carpetsLaidFields = document.getElementById('carpets-laid-fields');
            const containersFields = document.getElementById('containers-fields');
            const maintenanceDetailsFields = document.getElementById('maintenance-details-fields');

            function toggleLocationFields() {
                const location = locationSelect.value;

                // Hide all location-specific fields first
                roomFields.style.display = 'none';
                tramsFields.style.display = 'none';
                carpetsLaidFields.style.display = 'none';
                containersFields.style.display = 'none';
                maintenanceDetailsFields.style.display = 'none';
                externalPartitionsFieldset.style.display = 'none';
                taskDetailsFieldset.style.display = 'none'; // Hide main fieldset by default

                if (location) {
                    taskDetailsFieldset.style.display = 'block'; // Show main fieldset if location is selected
                    if (location.includes('قاعة') || location.includes('المطبخ')) {
                        roomFields.style.display = 'flex'; // Use flex for grid layout
                        maintenanceDetailsFields.style.display = 'block'; // Show always with rooms/kitchens
                    } else if (location === 'الترامز') {
                        tramsFields.style.display = 'flex';
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'السجاد') {
                        carpetsLaidFields.style.display = 'flex';
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'الحاويات') {
                        containersFields.style.display = 'flex';
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'الجامع' || location === 'المركز الصحي' || location === 'المرافق الصحية') {
                        maintenanceDetailsFields.style.display = 'block';
                    } else if (location === 'جميع القواطع الخارجية') {
                        maintenanceDetailsFields.style.display = 'block';
                        externalPartitionsFieldset.style.display = 'block';
                    }
                }
            }

            locationSelect.addEventListener('change', toggleLocationFields);
            toggleLocationFields(); // Initial call to set correct visibility based on old input or model data

            // Repeater for Resources
            let resourceIndex = Math.max(0, {{ $generalCleaningTask->resources_used ? count($generalCleaningTask->resources_used) : 0 }}, {{ old('resources_used') ? count(old('resources_used')) : 0 }});
            document.getElementById('add-resource-button').addEventListener('click', function() {
                const repeaterDiv = document.getElementById('resources-repeater');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'mb-3', 'resource-item');
                newItem.innerHTML = `
                    <div class="col-md-5 mb-3 mb-md-0"><label class="form-label">اسم المورد</label><input type="text" class="form-control" name="resources_used[${resourceIndex}][name]" required></div>
                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">الكمية</label><input type="number" class="form-control" name="resources_used[${resourceIndex}][quantity]" min="0" required></div>
                    <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">وحدة القياس</label>
                        <select class="form-select" name="resources_used[${resourceIndex}][unit]" required>
                            <option value="قطعة">قطعة</option>
                            <option value="كرتون">كرتون</option>
                            <option value="رول">رول</option>
                            <option value="لتر">لتر</option>
                            <option value="عبوة">عبوة</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-resource"><i class="fas fa-trash"></i></button></div>
                `;
                repeaterDiv.appendChild(newItem);
                resourceIndex++;
            });

            document.getElementById('resources-repeater').addEventListener('click', function(event) {
                if (event.target.closest('.remove-resource')) { // استخدم closest للتأكد من النقر على الزر نفسه
                    event.target.closest('.resource-item').remove();
                }
            });

            // Repeater for Employees
            let employeeIndex = Math.max(0, {{ $generalCleaningTask->employeeTasks->count() }}, {{ old('employeeTasks') ? count(old('employeeTasks')) : 0 }});
            document.getElementById('add-employee-task-button').addEventListener('click', function() {
                const repeaterDiv = document.getElementById('employees-repeater');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'mb-3', 'employee-task-item');
                newItem.innerHTML = `
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label">الموظف</label>
                        <select class="form-select" name="employeeTasks[${employeeIndex}][employee_id]" required>
                            <option value="">اختر الموظف</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label">تقييم الأداء</label>
                        <select class="form-select" name="employeeTasks[${employeeIndex}][employee_rating]" required>
                            <option value="">اختر التقييم</option>
                            <option value="1">★ (ضعيف)</option>
                            <option value="2">★★</option>
                            <option value="3">★★★ (متوسط)</option>
                            <option value="4">★★★★</option>
                            <option value="5">★★★★★ (ممتاز)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button></div>
                `;
                repeaterDiv.appendChild(newItem);
                employeeIndex++;
            });

            document.getElementById('employees-repeater').addEventListener('click', function(event) {
                if (event.target.closest('.remove-employee-task')) {
                    event.target.closest('.employee-task-item').remove();
                }
            });

            // Handle image removal for existing images
            document.querySelectorAll('.remove-existing-image').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.image-item').remove();
                });
            });
        });
    </script>
@endsection
