{{-- resources/views/monthly-cleaning-report/edit.blade.php --}}

@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل مهمة النظافة العامة') {{-- 💡 تغيير العنوان --}}

@section('page_title', '✏️ تعديل مهمة النظافة العامة') {{-- 💡 تغيير العنوان --}}

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('monthly-cleaning-report.index') }}">تقرير النظافة العامة التفصيلي</a></li> {{-- 💡 تغيير اسم المسار --}}
    <li class="breadcrumb-item active">تعديل المهمة</li>
@endsection

@section('styles')
    <style>
        /* Define an accent color variable for distinctiveness */
        :root {
            --accent-color: #00eaff; /* Light blue/cyan for interactive elements and emphasis */
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
            font-size: 1.1rem !important; /* Slightly larger body text */
            line-height: 1.7 !important; /* Improved line spacing for readability */
            color: white !important; /* لون نص أبيض لجسم البطاقة */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6) !important; /* ظل خفيف للنص */
        }

        /* Titles and Headers - make them more prominent and interactive */
        .card-title,
        .card-header h3.card-title, /* Target the h3 specifically */
        .card-header h2.card-title, /* Target the h2 specifically */
        .card-header .btn {
            font-size: 1.8rem !important; /* Larger titles */
            font-weight: 700 !important; /* Bolder */
            color: var(--accent-color) !important; /* Distinctive color for titles */
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9) !important; /* Stronger shadow */
            transition: color 0.3s ease, text-shadow 0.3s ease; /* Smooth transition */
        }
        .card-title:hover,
        .card-header h3.card-title:hover,
        .card-header h2.card-title:hover {
            color: #ffffff !important; /* Change color on hover for interactivity */
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 1.0) !important;
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
            font-size: 1.2rem !important; /* Larger text inside inputs */
            padding: 0.8rem 1.2rem !important; /* More padding for better feel */
            border-radius: 0.5rem !important; /* Rounded corners for inputs */
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7) !important; /* لون أفتح لـ placeholder */
        }
        .form-control:focus,
        .form-select:focus,
        input:focus,
        textarea:focus,
        select:focus {
            background-color: rgba(255, 255, 255, 0.2) !important; /* يصبح أكثر شفافية عند التركيز */
            border-color: var(--accent-color) !important; /* Highlight with accent color on focus */
            box-shadow: 0 0 0 0.3rem rgba(0, 234, 255, 0.4) !important; /* Glow effect on focus */
        }
        .form-select option {
            background-color: #2c3e50 !important; /* خلفية داكنة لخيار القائمة */
            color: white !important; /* نص أبيض لخيار القائمة */
        }

        /* أنماط تسميات الحقول - bigger and more distinct */
        .form-label,
        label {
            font-size: 1.2rem !important; /* Larger labels */
            font-weight: 600 !important; /* Bolder */
            color: var(--accent-color) !important; /* Distinctive color for labels */
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8) !important;
            margin-bottom: 0.5rem; /* Space below labels */
            display: block; /* Ensure labels take full width */
        }

        /* أنماط الأزرار */
        .btn {
            font-weight: 600; /* Make button text bolder */
            padding: 0.7rem 1.4rem; /* Adjust padding for larger text */
            border-radius: 0.75rem; /* More rounded buttons */
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease; /* Add transform and box-shadow to transition */
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.5) !important; /* ظل للأزرار */
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-3px); /* Slight lift on hover */
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.7) !important;
            filter: brightness(1.15); /* Slightly brighter on hover */
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.5) !important;
        }
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(108, 117, 125, 0.7) !important;
            filter: brightness(1.15);
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.5) !important;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.7) !important;
            filter: brightness(1.15);
        }

        .btn-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(23, 162, 184, 0.5) !important;
        }
        .btn-info:hover {
            background-color: #138496 !important;
            border-color: #138496 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(23, 162, 184, 0.7) !important;
            filter: brightness(1.15);
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.5) !important;
        }
        .btn-success:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.7) !important;
            filter: brightness(1.15);
        }

        /* أنماط الأيقونات في الأزرار */
        .btn .fas {
            margin-right: 8px; /* مسافة بين الأيقونة والنص */
            font-size: 1.1rem; /* Larger icon */
        }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important; /* خلفية شفافة للرسائل */
            color: #333 !important; /* لون نص داكن */
            border-color: rgba(0, 0, 0, 0.2) !important;
            border-radius: 0.75rem; /* More rounded alerts */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25); /* Stronger shadow */
            font-size: 1.1rem !important; /* Larger alert text */
            padding: 1.25rem 1.5rem !important; /* More padding */
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.95) !important; /* خلفية خضراء شفافة للنجاح */
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.95) !important; /* خلفية حمراء شفافة للخطأ */
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .alert-info {
            background-color: rgba(23, 162, 184, 0.95) !important; /* خلفية زرقاء شفافة للمعلومات */
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* أنماط النص المساعد (form-text) - Slightly larger helper text */
        .form-text {
            font-size: 1rem !important; /* Larger helper text */
            color: rgba(255, 255, 255, 0.8) !important; /* لون أبيض شفاف للنص المساعد */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6) !important;
            margin-top: 0.4rem; /* Space above helper text */
        }

        /* أنماط Fieldset وتفاصيلها - more prominent */
        fieldset {
            border: 1px solid rgba(255, 255, 255, 0.3) !important; /* حدود شفافة */
            padding: 2rem !important; /* More padding */
            border-radius: 1rem !important; /* More rounded */
            margin-bottom: 2rem !important; /* More space below */
            background-color: rgba(255, 255, 255, 0.05); /* خلفية خفيفة جداً للـ fieldset */
            box-shadow: inset 0 0 10px rgba(0, 234, 255, 0.1); /* Subtle inner glow */
        }
        fieldset legend {
            font-size: 1.8rem !important; /* Even larger for legends */
            font-weight: 700 !important;
            color: var(--accent-color) !important; /* Distinctive color for legends */
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9) !important;
            padding: 0 0.8rem; /* More padding for legend text */
            border-bottom: none; /* إزالة الخط الافتراضي */
            width: auto; /* يجعل الـ legend يأخذ عرض محتواه فقط */
            background-color: rgba(44, 62, 80, 0.7); /* Darker background for legend */
            border-radius: 0.5rem; /* Rounded corners for legend background */
            margin-bottom: 1rem; /* Space below legend */
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
            font-size: 1.15rem !important; /* Slightly smaller than main labels but still prominent */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7) !important;
        }

        /* Specific styling for repeater items to make them visually distinct */
        .resource-item,
        .employee-task-item {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">تعديل مهمة النظافة العامة</h3> {{-- 💡 تغيير العنوان --}}
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

                <form action="{{ route('monthly-cleaning-report.update', $task->id) }}" method="POST" enctype="multipart/form-data"> {{-- 💡 تغيير $report->id إلى $task->id وإضافة enctype للصور --}}
                    @csrf
                    @method('PUT') {{-- مهم جداً لتحديد طريقة الطلب كـ PUT --}}

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">معلومات المهمة</h2> {{-- 💡 تغيير العنوان --}}
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">التاريخ</label> {{-- 💡 حقل التاريخ --}}
                                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $task->date ? $task->date->format('Y-m-d') : '') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shift" class="form-label">الشفت</label> {{-- 💡 حقل الشفت --}}
                                    <select class="form-select" id="shift" name="shift" required>
                                        <option value="">اختر الشفت</option>
                                        @foreach($availableShifts as $shiftOption)
                                            <option value="{{ $shiftOption }}" {{ old('shift', $task->shift) == $shiftOption ? 'selected' : '' }}>{{ $shiftOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="unit_id" class="form-label">الوحدة</label> {{-- 💡 حقل الوحدة --}}
                                    <select class="form-select" id="unit_id" name="unit_id" required>
                                        <option value="">اختر الوحدة</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id', $task->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">الموقع</label>
                                    <select class="form-select" id="location" name="location" required>
                                        <option value="">اختر الموقع</option>
                                        {{-- القاعات --}}
                                        <option value="قاعة 1 الأسفل" {{ old('location', $task->location) == 'قاعة 1 الأسفل' ? 'selected' : '' }}>قاعة 1 الأسفل</option>
                                        <option value="قاعة 1 الأعلى" {{ old('location', $task->location) == 'قاعة 1 الأعلى' ? 'selected' : '' }}>قاعة 1 الأعلى</option>
                                        <option value="قاعة 2 الأسفل" {{ old('location', $task->location) == 'قاعة 2 الأسفل' ? 'selected' : '' }}>قاعة 2 الأسفل</option>
                                        <option value="قاعة 2 الأعلى" {{ old('location', $task->location) == 'قاعة 2 الأعلى' ? 'selected' : '' }}>قاعة 2 الأعلى</option>
                                        <option value="قاعة 3 الأسفل" {{ old('location', $task->location) == 'قاعة 3 الأسفل' ? 'selected' : '' }}>قاعة 3 الأسفل</option>
                                        <option value="قاعة 3 الأعلى" {{ old('location', $task->location) == 'قاعة 3 الأعلى' ? 'selected' : '' }}>قاعة 3 الأعلى</option>
                                        <option value="قاعة 4 الأسفل" {{ old('location', $task->location) == 'قاعة 4 الأسفل' ? 'selected' : '' }}>قاعة 4 الأسفل</option>
                                        <option value="قاعة 4 الأعلى" {{ old('location', $task->location) == 'قاعة 4 الأعلى' ? 'selected' : '' }}>قاعة 4 الأعلى</option>
                                        <option value="قاعة 5 الأسفل" {{ old('location', $task->location) == 'قاعة 5 الأسفل' ? 'selected' : '' }}>قاعة 5 الأسفل</option>
                                        <option value="قاعة 5 الأعلى" {{ old('location', $task->location) == 'قاعة 5 الأعلى' ? 'selected' : '' }}>قاعة 5 الأعلى</option>
                                        <option value="قاعة 6 الأسفل" {{ old('location', $task->location) == 'قاعة 6 الأسفل' ? 'selected' : '' }}>قاعة 6 الأسفل</option>
                                        <option value="قاعة 6 الأعلى" {{ old('location', $task->location) == 'قاعة 6 الأعلى' ? 'selected' : '' }}>قاعة 6 الأعلى</option>
                                        <option value="قاعة 7 الأسفل" {{ old('location', $task->location) == 'قاعة 7 الأسفل' ? 'selected' : '' }}>قاعة 7 الأسفل</option>
                                        <option value="قاعة 7 الأعلى" {{ old('location', $task->location) == 'قاعة 7 الأعلى' ? 'selected' : '' }}>قاعة 7 الأعلى</option>
                                        <option value="قاعة 8 الأسفل" {{ old('location', $task->location) == 'قاعة 8 الأسفل' ? 'selected' : '' }}>قاعة 8 الأسفل</option>
                                        <option value="قاعة 8 الأعلى" {{ old('location', $task->location) == 'قاعة 8 الأعلى' ? 'selected' : '' }}>قاعة 8 الأعلى</option>
                                        <option value="قاعة 9 الأسفل" {{ old('location', $task->location) == 'قاعة 9 الأسفل' ? 'selected' : '' }}>قاعة 9 الأسفل</option>
                                        <option value="قاعة 9 الأعلى" {{ old('location', $task->location) == 'قاعة 9 الأعلى' ? 'selected' : '' }}>قاعة 9 الأعلى</option>
                                        <option value="قاعة 10 الأسفل" {{ old('location', $task->location) == 'قاعة 10 الأسفل' ? 'selected' : '' }}>قاعة 10 الأسفل</option>
                                        <option value="قاعة 10 الأعلى" {{ old('location', $task->location) == 'قاعة 10 الأعلى' ? 'selected' : '' }}>قاعة 10 الأعلى</option>
                                        <option value="قاعة 11 الأسفل" {{ old('location', $task->location) == 'قاعة 11 الأسفل' ? 'selected' : '' }}>قاعة 11 الأسفل</option>
                                        <option value="قاعة 11 الأعلى" {{ old('location', $task->location) == 'قاعة 11 الأعلى' ? 'selected' : '' }}>قاعة 11 الأعلى</option>
                                        <option value="قاعة 12 الأسفل" {{ old('location', $task->location) == 'قاعة 12 الأسفل' ? 'selected' : '' }}>قاعة 12 الأسفل</option>
                                        <option value="قاعة 12 الأعلى" {{ old('location', $task->location) == 'قاعة 12 الأعلى' ? 'selected' : '' }}>قاعة 12 الأعلى</option>
                                        <option value="قاعة 13 الأسفل" {{ old('location', $task->location) == 'قاعة 13 الأسفل' ? 'selected' : '' }}>قاعة 13 الأسفل</option>
                                        <option value="قاعة 13 الأعلى" {{ old('location', $task->location) == 'قاعة 13 الأعلى' ? 'selected' : '' }}>قاعة 13 الأعلى</option>
                                        {{-- المناطق الخارجية --}}
                                        <option value="جميع القواطع الخارجية" {{ old('location', $task->location) == 'جميع القواطع الخارجية' ? 'selected' : '' }}>جميع القواطع الخارجية</option>
                                        <option value="الترامز" {{ old('location', $task->location) == 'الترامز' ? 'selected' : '' }}>الترامز</option>
                                        <option value="السجاد" {{ old('location', $task->location) == 'السجاد' ? 'selected' : '' }}>السجاد</option>
                                        <option value="الحاويات" {{ old('location', $task->location) == 'الحاويات' ? 'selected' : '' }}>الحاويات</option>
                                        <option value="الجامع" {{ old('location', $task->location) == 'الجامع' ? 'selected' : '' }}>الجامع</option>
                                        <option value="المركز الصحي" {{ old('location', $task->location) == 'المركز الصحي' ? 'selected' : '' }}>المركز الصحي</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="task_type" class="form-label">نوع المهمة</label>
                                <select class="form-select" id="task_type" name="task_type" required>
                                    <option value="">اختر نوع المهمة</option>
                                    @foreach($availableTaskTypes as $taskTypeOption)
                                        <option value="{{ $taskTypeOption }}" {{ old('task_type', $task->task_type) == $taskTypeOption ? 'selected' : '' }}>{{ $taskTypeOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="status" class="form-label">الحالة</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="قيد التنفيذ" {{ old('status', $task->status) == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                    <option value="مكتمل" {{ old('status', $task->status) == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="معلق" {{ old('status', $task->status) == 'معلق' ? 'selected' : '' }}>معلق</option>
                                    <option value="ملغاة" {{ old('status', $task->status) == 'ملغاة' ? 'selected' : '' }}>ملغاة</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="working_hours" class="form-label">ساعات العمل لهذه المهمة</label> {{-- 💡 تغيير الاسم --}}
                                <input type="number" step="0.5" class="form-control" id="working_hours" name="working_hours" min="0" max="24" value="{{ old('working_hours', $task->working_hours) }}" required>
                                <div class="form-text">ساعات العمل التي استغرقتها هذه المهمة.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h2 class="card-title">تفاصيل الأداء (الكميات)</h2> {{-- 💡 تغيير العنوان --}}
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3"><label for="mats_count" class="form-label">المنادر المدامة</label><input type="number" class="form-control" id="mats_count" name="mats_count" min="0" value="{{ old('mats_count', $task->mats_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="pillows_count" class="form-label">الوسادات المدامة</label><input type="number" class="form-control" id="pillows_count" name="pillows_count" min="0" value="{{ old('pillows_count', $task->pillows_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="fans_count" class="form-label">المراوح المدامة</label><input type="number" class="form-control" id="fans_count" name="fans_count" min="0" value="{{ old('fans_count', $task->fans_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="windows_count" class="form-label">النوافذ المدامة</label><input type="number" class="form-control" id="windows_count" name="windows_count" min="0" value="{{ old('windows_count', $task->windows_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="carpets_count" class="form-label">السجاد المدام</label><input type="number" class="form-control" id="carpets_count" name="carpets_count" min="0" value="{{ old('carpets_count', $task->carpets_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="blankets_count" class="form-label">البطانيات المدامة</label><input type="number" class="form-control" id="blankets_count" name="blankets_count" min="0" value="{{ old('blankets_count', $task->blankets_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="beds_count" class="form-label">الأسرة</label><input type="number" class="form-control" id="beds_count" name="beds_count" min="0" value="{{ old('beds_count', $task->beds_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="beneficiaries_count" class="form-label">المستفيدون من القاعة</label><input type="number" class="form-control" id="beneficiaries_count" name="beneficiaries_count" min="0" value="{{ old('beneficiaries_count', $task->beneficiaries_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="filled_trams_count" class="form-label">الترامز المملوئة والمدامة</label><input type="number" class="form-control" id="filled_trams_count" name="filled_trams_count" min="0" value="{{ old('filled_trams_count', $task->filled_trams_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="carpets_laid_count" class="form-label">السجاد المفروش في الساحات</label><input type="number" class="form-control" id="carpets_laid_count" name="carpets_laid_count" min="0" value="{{ old('carpets_laid_count', $task->carpets_laid_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="large_containers_count" class="form-label">الحاويات الكبيرة المفرغة والمدامة</label><input type="number" class="form-control" id="large_containers_count" name="large_containers_count" min="0" value="{{ old('large_containers_count', $task->large_containers_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="small_containers_count" class="form-label">الحاويات الصغيرة المفرغة والمدامة</label><input type="number" class="form-control" id="small_containers_count" name="small_containers_count" min="0" value="{{ old('small_containers_count', $task->small_containers_count) }}"></div> {{-- 💡 تغيير الاسم --}}
                                <div class="col-md-4 mb-3"><label for="total_external_partitions" class="form-label">القواطع الخارجية المدامة</label><input type="number" class="form-control" id="total_external_partitions" name="total_external_partitions" min="0" value="{{ old('total_external_partitions', $task->total_external_partitions) }}"></div> {{-- 💡 تغيير الاسم --}}
                            </div>
                            <div class="mb-4">
                                <label for="notes" class="form-label">ملاحظات إضافية</label>
                                <textarea class="form-control" id="notes" name="notes" rows="5" placeholder="أضف أي ملاحظات إضافية هنا...">{{ old('notes', $task->notes) }}</textarea> {{-- 💡 تغيير $report->notes إلى $task->notes --}}
                                <div class="form-text">أي تفاصيل أو ملاحظات أخرى تتعلق بالمهمة.</div> {{-- 💡 تغيير النص --}}
                            </div>
                            {{-- 💡 حقول الصور --}}
                            <div class="mb-4">
                                <label for="before_images" class="form-label">صور قبل المهمة</label>
                                <input type="file" class="form-control" id="before_images" name="before_images[]" multiple accept="image/*">
                                <div class="form-text">يمكنك رفع صور متعددة قبل بدء المهمة.</div>
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    @if ($task->before_images)
                                        @foreach ($task->before_images as $imagePath)
                                            <div class="position-relative d-inline-block me-2 mb-2">
                                                <img src="{{ Storage::url($imagePath) }}" alt="صورة قبل" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image" data-path="{{ $imagePath }}" style="border-radius: 50%; width: 25px; height: 25px; padding: 0; font-size: 0.7rem; line-height: 1; text-align: center;">&times;</button>
                                                <input type="hidden" name="existing_before_images[]" value="{{ $imagePath }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="after_images" class="form-label">صور بعد المهمة</label>
                                <input type="file" class="form-control" id="after_images" name="after_images[]" multiple accept="image/*">
                                <div class="form-text">يمكنك رفع صور متعددة بعد انتهاء المهمة.</div>
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    @if ($task->after_images)
                                        @foreach ($task->after_images as $imagePath)
                                            <div class="position-relative d-inline-block me-2 mb-2">
                                                <img src="{{ Storage::url($imagePath) }}" alt="صورة بعد" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image" data-path="{{ $imagePath }}" style="border-radius: 50%; width: 25px; height: 25px; padding: 0; font-size: 0.7rem; line-height: 1; text-align: center;">&times;</button>
                                                <input type="hidden" name="existing_after_images[]" value="{{ $imagePath }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg me-2">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                        <a href="{{ route('monthly-cleaning-report.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.remove-image').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                        this.closest('.position-relative').remove();
                        // يمكنك إضافة منطق AJAX هنا لحذف الصورة من الخادم فوراً إذا أردت
                        // أو الاعتماد على إرسال 'existing_images' المتبقية عند حفظ النموذج
                    }
                });
            });
        });
    </script>
@endsection
