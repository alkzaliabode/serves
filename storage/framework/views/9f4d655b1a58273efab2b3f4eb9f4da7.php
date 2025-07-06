 

<?php $__env->startSection('title', 'إنشاء مهمة نظافة عامة'); ?> 

<?php $__env->startSection('page_title', 'إنشاء مهمة نظافة عامة جديدة'); ?> 

<?php $__env->startSection('breadcrumb'); ?> 
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('general-cleaning-tasks.index')); ?>">مهام النظافة العامة</a></li>
    <li class="breadcrumb-item active">إنشاء مهمة</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?> 
    <div class="container-fluid">
        <div class="card"> 
            <div class="card-header">
                <h3 class="card-title">إنشاء مهمة نظافة عامة جديدة</h3>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('general-cleaning-tasks.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="card card-info card-outline"> 
                        <div class="card-header">
                            <h2 class="card-title">المعلومات الأساسية</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date" class="form-label">التاريخ</label>
                                    <input type="date" class="form-control" id="date" name="date" value="<?php echo e(old('date', date('Y-m-d'))); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="shift" class="form-label">الوجبة</label>
                                    <select class="form-select" id="shift" name="shift" required>
                                        <option value="صباحي" <?php echo e(old('shift') == 'صباحي' ? 'selected' : ''); ?>>صباحي</option>
                                        <option value="مسائي" <?php echo e(old('shift') == 'مسائي' ? 'selected' : ''); ?>>مسائي</option>
                                        <option value="ليلي" <?php echo e(old('shift') == 'ليلي' ? 'selected' : ''); ?>>ليلي</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="مكتمل" <?php echo e(old('status') == 'مكتمل' ? 'selected' : ''); ?>>مكتمل</option>
                                        <option value="قيد التنفيذ" <?php echo e(old('status') == 'قيد التنفيذ' ? 'selected' : ''); ?>>قيد التنفيذ</option>
                                        <option value="ملغى" <?php echo e(old('status') == 'ملغى' ? 'selected' : ''); ?>>ملغى</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="related_goal_id" class="form-label">الهدف المرتبط</label>
                                <select class="form-select" id="related_goal_id" name="related_goal_id" required>
                                    <option value="">اختر الهدف المرتبط</option>
                                    <?php $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($goal->id); ?>" <?php echo e(old('related_goal_id') == $goal->id ? 'selected' : ''); ?>><?php echo e($goal->goal_text); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="form-text">اختر الهدف الاستراتيجي أو التشغيلي الذي تساهم فيه هذه المهمة.</div>
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
                                        <option value="إدامة" <?php echo e(old('task_type') == 'إدامة' ? 'selected' : ''); ?>>إدامة</option>
                                        <option value="صيانة" <?php echo e(old('task_type') == 'صيانة' ? 'selected' : ''); ?>>صيانة</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="form-label">الموقع</label>
                                    
                                    <select class="form-select" id="location" name="location" required>
                                        <option value="">اختر الموقع</option>
                                        
                                        <option value="قاعة 1 الأسفل" <?php echo e(old('location') == 'قاعة 1 الأسفل' ? 'selected' : ''); ?>>قاعة 1 الأسفل</option>
                                        <option value="قاعة 1 الأعلى" <?php echo e(old('location') == 'قاعة 1 الأعلى' ? 'selected' : ''); ?>>قاعة 1 الأعلى</option>
                                        <option value="قاعة 2 الأسفل" <?php echo e(old('location') == 'قاعة 2 الأسفل' ? 'selected' : ''); ?>>قاعة 2 الأسفل</option>
                                        <option value="قاعة 2 الأعلى" <?php echo e(old('location') == 'قاعة 2 الأعلى' ? 'selected' : ''); ?>>قاعة 2 الأعلى</option>
                                        <option value="قاعة 3 الأسفل" <?php echo e(old('location') == 'قاعة 3 الأسفل' ? 'selected' : ''); ?>>قاعة 3 الأسفل</option>
                                        <option value="قاعة 3 الأعلى" <?php echo e(old('location') == 'قاعة 3 الأعلى' ? 'selected' : ''); ?>>قاعة 3 الأعلى</option>
                                        <option value="قاعة 4 الأسفل" <?php echo e(old('location') == 'قاعة 4 الأسفل' ? 'selected' : ''); ?>>قاعة 4 الأسفل</option>
                                        <option value="قاعة 4 الأعلى" <?php echo e(old('location') == 'قاعة 4 الأعلى' ? 'selected' : ''); ?>>قاعة 4 الأعلى</option>
                                        <option value="قاعة 5 الأسفل" <?php echo e(old('location') == 'قاعة 5 الأسفل' ? 'selected' : ''); ?>>قاعة 5 الأسفل</option>
                                        <option value="قاعة 5 الأعلى" <?php echo e(old('location') == 'قاعة 5 الأعلى' ? 'selected' : ''); ?>>قاعة 5 الأعلى</option>
                                        <option value="قاعة 6 الأسفل" <?php echo e(old('location') == 'قاعة 6 الأسفل' ? 'selected' : ''); ?>>قاعة 6 الأسفل</option>
                                        <option value="قاعة 6 الأعلى" <?php echo e(old('location') == 'قاعة 6 الأعلى' ? 'selected' : ''); ?>>قاعة 6 الأعلى</option>
                                        <option value="قاعة 7 الأسفل" <?php echo e(old('location') == 'قاعة 7 الأسفل' ? 'selected' : ''); ?>>قاعة 7 الأسفل</option>
                                        <option value="قاعة 7 الأعلى" <?php echo e(old('location') == 'قاعة 7 الأعلى' ? 'selected' : ''); ?>>قاعة 7 الأعلى</option>
                                        <option value="قاعة 8 الأسفل" <?php echo e(old('location') == 'قاعة 8 الأسفل' ? 'selected' : ''); ?>>قاعة 8 الأسفل</option>
                                        <option value="قاعة 8 الأعلى" <?php echo e(old('location') == 'قاعة 8 الأعلى' ? 'selected' : ''); ?>>قاعة 8 الأعلى</option>
                                        <option value="قاعة 9 الأسفل" <?php echo e(old('location') == 'قاعة 9 الأسفل' ? 'selected' : ''); ?>>قاعة 9 الأسفل</option>
                                        <option value="قاعة 9 الأعلى" <?php echo e(old('location') == 'قاعة 9 الأعلى' ? 'selected' : ''); ?>>قاعة 9 الأعلى</option>
                                        <option value="قاعة 10 الأسفل" <?php echo e(old('location') == 'قاعة 10 الأسفل' ? 'selected' : ''); ?>>قاعة 10 الأسفل</option>
                                        <option value="قاعة 10 الأعلى" <?php echo e(old('location') == 'قاعة 10 الأعلى' ? 'selected' : ''); ?>>قاعة 10 الأعلى</option>
                                        <option value="قاعة 11 الأسفل" <?php echo e(old('location') == 'قاعة 11 الأسفل' ? 'selected' : ''); ?>>قاعة 11 الأسفل</option>
                                        <option value="قاعة 11 الأعلى" <?php echo e(old('location') == 'قاعة 11 الأعلى' ? 'selected' : ''); ?>>قاعة 11 الأعلى</option>
                                        <option value="قاعة 12 الأسفل" <?php echo e(old('location') == 'قاعة 12 الأسفل' ? 'selected' : ''); ?>>قاعة 12 الأسفل</option>
                                        <option value="قاعة 12 الأعلى" <?php echo e(old('location') == 'قاعة 12 الأعلى' ? 'selected' : ''); ?>>قاعة 12 الأعلى</option>
                                        <option value="قاعة 13 الأسفل" <?php echo e(old('location') == 'قاعة 13 الأسفل' ? 'selected' : ''); ?>>قاعة 13 الأسفل</option>
                                        <option value="قاعة 13 الأعلى" <?php echo e(old('location') == 'قاعة 13 الأعلى' ? 'selected' : ''); ?>>قاعة 13 الأعلى</option>
                                        
                                        <option value="جميع القواطع الخارجية" <?php echo e(old('location') == 'جميع القواطع الخارجية' ? 'selected' : ''); ?>>جميع القواطع الخارجية</option>
                                        <option value="الترامز" <?php echo e(old('location') == 'الترامز' ? 'selected' : ''); ?>>الترامز</option>
                                        <option value="السجاد" <?php echo e(old('location') == 'السجاد' ? 'selected' : ''); ?>>السجاد</option>
                                        <option value="الحاويات" <?php echo e(old('location') == 'الحاويات' ? 'selected' : ''); ?>>الحاويات</option>
                                        <option value="الجامع" <?php echo e(old('location') == 'الجامع' ? 'selected' : ''); ?>>الجامع</option>
                                        <option value="المركز الصحي" <?php echo e(old('location') == 'المركز الصحي' ? 'selected' : ''); ?>>المركز الصحي</option>
                                    </select>
                                </div>
                            </div>

                            <fieldset id="task-details-fieldset" class="mb-3" style="display: <?php echo e(old('location') ? 'block' : 'none'); ?>;">
                                <legend>تفاصيل التنفيذ</legend>
                                <div id="room-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-3 mb-3"><label for="mats_count" class="form-label">عدد المنادر المدامة</label><input type="number" class="form-control" id="mats_count" name="mats_count" min="0" value="<?php echo e(old('mats_count', 0)); ?>"></div>
                                    <div class="col-md-3 mb-3"><label for="pillows_count" class="form-label">عدد الوسادات المدامة</label><input type="number" class="form-control" id="pillows_count" name="pillows_count" min="0" value="<?php echo e(old('pillows_count', 0)); ?>"></div>
                                    <div class="col-md-3 mb-3"><label for="fans_count" class="form-label">عدد المراوح المدامة</label><input type="number" class="form-control" id="fans_count" name="fans_count" min="0" value="<?php echo e(old('fans_count', 0)); ?>"></div>
                                    <div class="col-md-3 mb-3"><label for="windows_count" class="form-label">عدد النوافذ المدامة</label><input type="number" class="form-control" id="windows_count" name="windows_count" min="0" value="<?php echo e(old('windows_count', 0)); ?>"></div>
                                    <div class="col-md-3 mb-3"><label for="carpets_count" class="form-label">عدد السجاد المدام</label><input type="number" class="form-control" id="carpets_count" name="carpets_count" min="0" value="<?php echo e(old('carpets_count', 0)); ?>"></div>
                                    <div class="col-md-3 mb-3"><label for="blankets_count" class="form-label">عدد البطانيات المدامة</label><input type="number" class="form-control" id="blankets_count" name="blankets_count" min="0" value="<?php echo e(old('blankets_count', 0)); ?>"></div>
                                    <div class="col-md-3 mb-3"><label for="beds_count" class="form-label">عدد الأسرة</label><input type="number" class="form-control" id="beds_count" name="beds_count" min="0" value="<?php echo e(old('beds_count', 0)); ?>"></div>
                                    <div class="col-md-3 mb-3"><label for="beneficiaries_count" class="form-label">عدد المستفيدين من القاعة</label><input type="number" class="form-control" id="beneficiaries_count" name="beneficiaries_count" min="0" value="<?php echo e(old('beneficiaries_count', 0)); ?>"></div>
                                </div>
                                <div id="trams-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-6 mb-3"><label for="filled_trams_count" class="form-label">عدد الترامز المملوئة والمدامة</label><input type="number" class="form-control" id="filled_trams_count" name="filled_trams_count" min="0" value="<?php echo e(old('filled_trams_count', 0)); ?>"></div>
                                </div>
                                <div id="carpets-laid-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-6 mb-3"><label for="carpets_laid_count" class="form-label">عدد السجاد المفروش في الساحات</label><input type="number" class="form-control" id="carpets_laid_count" name="carpets_laid_count" min="0" value="<?php echo e(old('carpets_laid_count', 0)); ?>"></div>
                                </div>
                                <div id="containers-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-6 mb-3"><label for="large_containers_count" class="form-label">عدد الحاويات الكبيرة المفرغة والمدامة</label><input type="number" class="form-control" id="large_containers_count" name="large_containers_count" min="0" value="<?php echo e(old('large_containers_count', 0)); ?>"></div>
                                    <div class="col-md-6 mb-3"><label for="small_containers_count" class="form-label">عدد الحاويات الصغيرة المفرغة والمدامة</label><input type="number" class="form-control" id="small_containers_count" name="small_containers_count" min="0" value="<?php echo e(old('small_containers_count', 0)); ?>"></div>
                                </div>
                                <div id="maintenance-details-fields" class="row mb-3" style="display: none;">
                                    <div class="col-md-12 mb-3"><label for="maintenance_details" class="form-label">تفاصيل الإدامة اليومية</label><textarea class="form-control" id="maintenance_details" name="maintenance_details" rows="3"><?php echo e(old('maintenance_details')); ?></textarea></div>
                                </div>
                            </fieldset>

                            <fieldset id="external-partitions-fieldset" class="mb-3" style="display: <?php echo e(old('location') == 'جميع القواطع الخارجية' ? 'block' : 'none'); ?>;">
                                <legend>تفاصيل القواطع الخارجية</legend>
                                <div class="mb-3">
                                    <label for="external_partitions_count" class="form-label">عدد القواطع الخارجية المدامة</label>
                                    <input type="number" class="form-control" id="external_partitions_count" name="external_partitions_count" min="0" value="<?php echo e(old('external_partitions_count', 0)); ?>">
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">الموارد المستخدمة وساعات العمل</h2>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="working_hours" class="form-label">إجمالي ساعات العمل للمهمة</label>
                                <input type="number" step="0.5" class="form-control" id="working_hours" name="working_hours" min="0" max="24" value="<?php echo e(old('working_hours')); ?>" required>
                                <div class="form-text">إجمالي ساعات العمل التي استغرقتها هذه المهمة.</div>
                            </div>

                            <h3 class="card-title mb-3" style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">الموارد الأخرى المستخدمة</h3>
                            <div id="resources-repeater">
                                <?php if(old('resources_used')): ?>
                                    <?php $__currentLoopData = old('resources_used'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row mb-3 resource-item">
                                            <div class="col-md-5 mb-3 mb-md-0"><label class="form-label">اسم المورد</label><input type="text" class="form-control" name="resources_used[<?php echo e($index); ?>][name]" value="<?php echo e($resource['name'] ?? ''); ?>" required></div>
                                            <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">الكمية</label><input type="number" class="form-control" name="resources_used[<?php echo e($index); ?>][quantity]" min="0" value="<?php echo e($resource['quantity'] ?? ''); ?>" required></div>
                                            <div class="col-md-3 mb-3 mb-md-0"><label class="form-label">وحدة القياس</label>
                                                <select class="form-select" name="resources_used[<?php echo e($index); ?>][unit]" required>
                                                    <option value="قطعة" <?php echo e(($resource['unit'] ?? '') == 'قطعة' ? 'selected' : ''); ?>>قطعة</option>
                                                    <option value="كرتون" <?php echo e(($resource['unit'] ?? '') == 'كرتون' ? 'selected' : ''); ?>>كرتون</option>
                                                    <option value="رول" <?php echo e(($resource['unit'] ?? '') == 'رول' ? 'selected' : ''); ?>>رول</option>
                                                    <option value="لتر" <?php echo e(($resource['unit'] ?? '') == 'لتر' ? 'selected' : ''); ?>>لتر</option>
                                                    <option value="عبوة" <?php echo e(($resource['unit'] ?? '') == 'عبوة' ? 'selected' : ''); ?>>عبوة</option>
                                                    <option value="أخرى" <?php echo e(($resource['unit'] ?? '') == 'أخرى' ? 'selected' : ''); ?>>أخرى</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-resource"><i class="fas fa-trash"></i></button></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-secondary" id="add-resource-button">
                                <i class="fas fa-plus"></i> إضافة مورد جديد
                            </button>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">المنفذون والتقييم</h2>
                        </div>
                        <div class="card-body">
                            <div id="employees-repeater">
                                <?php if(old('employeeTasks')): ?>
                                    <?php $__currentLoopData = old('employeeTasks'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $employeeTask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row mb-3 employee-task-item">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label class="form-label">الموظف</label>
                                                <select class="form-select" name="employeeTasks[<?php echo e($index); ?>][employee_id]" required>
                                                    <option value="">اختر الموظف</option>
                                                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($employee->id); ?>" <?php echo e(($employeeTask['employee_id'] ?? '') == $employee->id ? 'selected' : ''); ?>><?php echo e($employee->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <label class="form-label">تقييم الأداء</label>
                                                <select class="form-select" name="employeeTasks[<?php echo e($index); ?>][employee_rating]" required>
                                                    <option value="">اختر التقييم</option>
                                                    <option value="5" <?php echo e(($employeeTask['employee_rating'] ?? '') == '5' ? 'selected' : ''); ?>>5 - ممتاز</option>
                                                    <option value="4" <?php echo e(($employeeTask['employee_rating'] ?? '') == '4' ? 'selected' : ''); ?>>4 - جيد جداً</option>
                                                    <option value="3" <?php echo e(($employeeTask['employee_rating'] ?? '') == '3' ? 'selected' : ''); ?>>3 - جيد</option>
                                                    <option value="2" <?php echo e(($employeeTask['employee_rating'] ?? '') == '2' ? 'selected' : ''); ?>>2 - مقبول</option>
                                                    <option value="1" <?php echo e(($employeeTask['employee_rating'] ?? '') == '1' ? 'selected' : ''); ?>>1 - ضعيف</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0"><button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-secondary" id="add-employee-task-button">
                                <i class="fas fa-user-plus"></i> إضافة موظف
                            </button>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">الصور والملاحظات</h2>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="notes" class="form-label">الملاحظات</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4"><?php echo e(old('notes')); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="before_images" class="form-label">صور قبل التنفيذ</label>
                                <input type="file" class="form-control" id="before_images" name="before_images[]" multiple accept="image/*">
                                <div class="form-text">يمكنك رفع صور متعددة قبل بدء المهمة.</div>
                            </div>
                            <div class="mb-3">
                                <label for="after_images" class="form-label">صور بعد التنفيذ</label>
                                <input type="file" class="form-control" id="after_images" name="after_images[]" multiple accept="image/*">
                                <div class="form-text">يمكنك رفع صور متعددة بعد الانتهاء من المهمة.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ المهمة
                        </button>
                        <a href="<?php echo e(route('general-cleaning-tasks.index')); ?>" class="btn btn-secondary ml-2">
                            <i class="fas fa-ban"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Function to show/hide task details fieldset based on location selection
            function toggleTaskDetails() {
                var location = $('#location').val();
                var taskDetailsFieldset = $('#task-details-fieldset');
                var roomFields = $('#room-fields');
                var tramsFields = $('#trams-fields');
                var carpetsLaidFields = $('#carpets-laid-fields');
                var containersFields = $('#containers-fields');
                var maintenanceDetailsFields = $('#maintenance-details-fields');
                var externalPartitionsFieldset = $('#external-partitions-fieldset');

                // Hide all specific fields initially
                roomFields.hide().find('input').prop('required', false);
                tramsFields.hide().find('input').prop('required', false);
                carpetsLaidFields.hide().find('input').prop('required', false);
                containersFields.hide().find('input').prop('required', false);
                maintenanceDetailsFields.hide().find('textarea').prop('required', false);
                externalPartitionsFieldset.hide().find('input').prop('required', false);

                // Show/hide main task details fieldset
                if (location) {
                    taskDetailsFieldset.show();
                } else {
                    taskDetailsFieldset.hide();
                }

                // Show specific fields based on location
                if (location.includes('قاعة')) {
                    roomFields.show().find('input').prop('required', true);
                    maintenanceDetailsFields.show().find('textarea').prop('required', true);
                } else if (location === 'الترامز') {
                    tramsFields.show().find('input').prop('required', true);
                    maintenanceDetailsFields.show().find('textarea').prop('required', true);
                } else if (location === 'السجاد') {
                    carpetsLaidFields.show().find('input').prop('required', true);
                    maintenanceDetailsFields.show().find('textarea').prop('required', true);
                } else if (location === 'الحاويات') {
                    containersFields.show().find('input').prop('required', true);
                    maintenanceDetailsFields.show().find('textarea').prop('required', true);
                } else if (location === 'جميع القواطع الخارجية') {
                    externalPartitionsFieldset.show().find('input').prop('required', true);
                    maintenanceDetailsFields.show().find('textarea').prop('required', true);
                } else if (location === 'الجامع' || location === 'المركز الصحي') {
                    maintenanceDetailsFields.show().find('textarea').prop('required', true);
                }
            }

            // Initial call on page load
            toggleTaskDetails();

            // Event listener for location change
            $('#location').change(toggleTaskDetails);

            // Resources Repeater
            var resourceIndex = <?php echo e(old('resources_used') ? count(old('resources_used')) : 0); ?>;
            $('#add-resource-button').click(function() {
                var newResource = `
                    <div class="row mb-3 resource-item">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label class="form-label">اسم المورد</label>
                            <input type="text" class="form-control" name="resources_used[${resourceIndex}][name]" required>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label">الكمية</label>
                            <input type="number" class="form-control" name="resources_used[${resourceIndex}][quantity]" min="0" required>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label">وحدة القياس</label>
                            <select class="form-select" name="resources_used[${resourceIndex}][unit]" required>
                                <option value="قطعة">قطعة</option>
                                <option value="كرتون">كرتون</option>
                                <option value="رول">رول</option>
                                <option value="لتر">لتر</option>
                                <option value="عبوة">عبوة</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0">
                            <button type="button" class="btn btn-danger remove-resource"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `;
                $('#resources-repeater').append(newResource);
                resourceIndex++;
            });

            $(document).on('click', '.remove-resource', function() {
                $(this).closest('.resource-item').remove();
            });

            // Employees Repeater
            var employeeTaskIndex = <?php echo e(old('employeeTasks') ? count(old('employeeTasks')) : 0); ?>;
            $('#add-employee-task-button').click(function() {
                var newEmployeeTask = `
                    <div class="row mb-3 employee-task-item">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label">الموظف</label>
                            <select class="form-select" name="employeeTasks[${employeeTaskIndex}][employee_id]" required>
                                <option value="">اختر الموظف</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">تقييم الأداء</label>
                            <select class="form-select" name="employeeTasks[${employeeTaskIndex}][employee_rating]" required>
                                <option value="">اختر التقييم</option>
                                <option value="5">5 - ممتاز</option>
                                <option value="4">4 - جيد جداً</option>
                                <option value="3">3 - جيد</option>
                                <option value="2">2 - مقبول</option>
                                <option value="1">1 - ضعيف</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end mb-3 mb-md-0">
                            <button type="button" class="btn btn-danger remove-employee-task"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `;
                $('#employees-repeater').append(newEmployeeTask);
                employeeTaskIndex++;
            });

            $(document).on('click', '.remove-employee-task', function() {
                $(this).closest('.employee-task-item').remove();
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin_layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\kadm-drgham\resources\views/general_cleaning_tasks/create.blade.php ENDPATH**/ ?>