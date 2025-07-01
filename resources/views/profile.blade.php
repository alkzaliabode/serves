@extends('layouts.adminlte') {{-- تعديل ليرث تخطيط AdminLTE الجديد --}}

@section('title', __('الملف الشخصي')) {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', __('الملف الشخصي')) {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">{{ __('الملف الشخصي') }}</li>
@endsection

@section('styles')
    <style>
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
        .card-title {
            color: #1A237E !important; /* لون نص أزرق داكن */
            text-shadow: 0.5px 0.5px 2px rgba(255, 255, 255, 0.8) !important; /* ظل خفيف لتحسين القراءة */
        }
        .card-header .btn,
        .card-body {
            color: #1A237E !important; /* لون نص أزرق داكن */
            text-shadow: 0.5px 0.5px 2px rgba(255, 255, 255, 0.8) !important; /* ظل خفيف لتحسين القراءة */
        }

        /* أنماط الأزرار وعناصر التحكم (تم الاحتفاظ بها من التحديثات السابقة) */
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            transition: background-color 0.3s ease;
            color: white !important; /* الأزرار تبقى بيضاء لتباين أفضل */
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important; /* الأزرار تبقى بيضاء لتباين أفضل */
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
        }

        /* أنماط حقول الإدخال والاختيار (تم التعديل هنا لتعزيز الشفافية) */
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
            border-color: rgba(255, 255, 255, 0.3) !important; /* حدود أكثر وضوحاً */
            color: #1A237E !important; /* لون نص أزرق داكن للحقول */
            text-shadow: 0.5px 0.5px 1px rgba(255, 255, 255, 0.6) !important;
        }
        .form-control::placeholder {
            color: rgba(26, 35, 126, 0.7) !important; /* لون أزرق داكن أفتح لـ placeholder */
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

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.95) !important;
            color: #333 !important;
            border-color: rgba(0, 0, 0, 0.3) !important;
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.95) !important;
            color: white !important;
        }
        .alert-info {
            background-color: rgba(23, 162, 184, 0.95) !important;
            color: white !important;
        }

        /* Badge styling */
        .badge.bg-success {
            background-color: rgba(40, 167, 69, 0.9) !important;
            color: white !important;
        }
        .badge.bg-danger {
            background-color: rgba(220, 53, 69, 0.9) !important;
            color: white !important;
        }
        .badge.bg-warning {
            background-color: rgba(255, 193, 7, 0.9) !important;
            color: black !important;
        }
        .badge.bg-info {
            background-color: rgba(23, 162, 184, 0.9) !important;
            color: white !important;
        }
        .badge.bg-secondary {
            background-color: rgba(108, 117, 125, 0.9) !important;
            color: white !important;
        }

        /* Tailwind classes compatibility (for space-y-6) */
        .space-y-6 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(1.5rem * calc(1 - var(--tw-space-y-reverse))) !important;
            margin-bottom: calc(1.5rem * var(--tw-space-y-reverse)) !important;
        }

        /* أنماط إضافية لإزالة البياض من الطبقات العليا في AdminLTE ومكونات Livewire */
        /* استهداف عناصر AdminLTE التي قد تكون لها خلفيات بيضاء افتراضية */
        .app-content-header .container-fluid,
        .app-content .container-fluid,
        .app-content .container-fluid .container-fluid, /* استهداف التداخل */
        .content-header,
        .content { /* التأكد من شفافية المحتوى العام */
            background-color: transparent !important;
            box-shadow: none !important; /* إزالة أي ظلال قد تسبب بياضًا */
        }

        /* استهداف عناصر Livewire التي قد يكون لها خلفيات بيضاء أو تتداخل مع الأنماط */
        .bg-white, /* overrides Tailwind's bg-white */
        .shadow.sm\:rounded-lg, /* Common Livewire form wrappers */
        .px-4.py-5.sm\:p-6, /* Livewire section padding */
        .sm\:px-6.lg\:px-8, /* Livewire padding for wide screens */
        .max-w-7xl.mx-auto.py-10.sm\:px-6.lg\:px-8, /* Main content wrapper for Livewire views */
        .w-full.bg-white.shadow.overflow-hidden.sm\:rounded-lg, /* Specific Livewire wrapper */
        .w-full.bg-gray-800.sm\:rounded-lg.shadow, /* Targeting dark mode Livewire components if any */
        .border-gray-200.dark\:border-gray-700, /* Common border for Livewire sections */
        div[x-data]:not(.card) /* استهداف عام لأي divs تنشئها Livewire / Alpine.js */
        {
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important; /* إزالة حدود العناصر الداخلية البيضاء */
        }

        /* Targeting form elements and labels to ensure color/shadows */
        .form-group,
        form > div, /* General divs within forms */
        .input-group, /* قد يكون Bootstrap input-group له خلفية */
        .col-md-5, .col-md-2, .col-md-4, .col-md-12, /* Ensure Bootstrap column backgrounds are transparent */
        div[class*="col-"] /* More general Bootstrap column transparency */
        {
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }
        .form-group label,
        label { /* Ensure all labels are dark blue with lighter shadow */
            color: #1A237E !important;
            text-shadow: 0.5px 0.5px 1px rgba(255, 255, 255, 0.6) !important;
        }
        /* أي عناصر نصية أخرى داخل البطاقات تظهر بيضاء يمكن استهدافها هنا */
        .pb-4.px-6.text-gray-900, /* A common text div in Jetstream/Livewire */
        .text-gray-700.dark\:text-gray-300, /* Common text color in Jetstream/Livewire */
        .text-sm.text-gray-600, /* Smaller descriptive text */
        p.text-sm.text-gray-600.dark\:text-gray-400 /* Specific Jetstream/Livewire paragraph */
        {
            background-color: transparent !important;
            box-shadow: none !important;
            color: #1A237E !important; /* لضمان أن النص الداخلي أزرق داكن */
            text-shadow: 0.5px 0.5px 1px rgba(255, 255, 255, 0.6) !important;
        }

        /* Profile Photo Specific Styles */
        .profile-photo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .profile-photo-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.5); /* Semi-transparent white border */
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5); /* Glowing effect */
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .profile-photo-wrapper:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.8);
        }

        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .profile-photo-wrapper:hover .profile-photo {
            transform: scale(1.1);
        }

        .profile-photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .profile-photo-wrapper:hover .profile-photo-overlay {
            opacity: 1;
        }

        .profile-photo-overlay i {
            color: white;
            font-size: 2rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.8);
        }

        .photo-buttons {
            margin-top: 1rem;
            display: flex;
            gap: 10px;
        }

        .hidden-file-input {
            display: none;
        }
    </style>
@endsection

@section('content') {{-- بداية قسم المحتوى الذي سيتم عرضه داخل AdminLTE layout --}}
    <div class="container-fluid">
        <div class="space-y-6"> {{-- حافظ على space-y-6 لتباعد البطاقات إذا كانت لا تزال مفضلة --}}

            {{-- بطاقة صورة الملف الشخصي --}}
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{ __('صورة الملف الشخصي') }}</h3>
                </div>
                <div class="card-body">
                    {{--
                        ملاحظة: هذا النموذج هو لغرض العرض التوضيحي.
                        في تطبيق Laravel Jetstream، يتم التعامل مع تحميل الصور الشخصية عادةً
                        من خلال مكون Livewire/Inertia المسمى `UpdateProfileInformationForm`.
                        إذا كنت تستخدم Jetstream، فقد تحتاج إلى تعديل هذا المكون مباشرةً
                        أو التأكد من أنه يدعم تحميل الصور تلقائيًا.
                        هنا، قمنا بإنشاء نموذج HTML منفصل لتبسيط المثال.
                    --}}
                    <form id="profile-photo-form" action="{{ route('user-profile-photo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="profile-photo-container">
                            <label for="profile_photo_input" class="profile-photo-wrapper">
                                <img id="profile_photo_preview"
                                     src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/150x150/cccccc/ffffff?text=U' }}"
                                     alt="{{ Auth::user()->name ?? 'User' }}"
                                     class="profile-photo">
                                <div class="profile-photo-overlay">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo_input" class="hidden-file-input" accept="image/*">

                            <div class="photo-buttons">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('profile_photo_input').click()">
                                    <i class="fas fa-upload mr-1"></i> {{ __('تغيير الصورة') }}
                                </button>
                                @if (Auth::user()->profile_photo_path)
                                    <button type="button" class="btn btn-danger" id="remove-photo-button">
                                        <i class="fas fa-trash mr-1"></i> {{ __('إزالة الصورة') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- بطاقة تحديث معلومات الملف الشخصي --}}
            <div class="card card-primary card-outline mt-4">
                <div class="card-header">
                    <h3 class="card-title">{{ __('معلومات الملف الشخصي') }}</h3>
                </div>
                <div class="card-body">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            {{-- بطاقة تحديث كلمة المرور --}}
            <div class="card card-primary card-outline mt-4"> {{-- أضف هامش علوي بين البطاقات --}}
                <div class="card-header">
                    <h3 class="card-title">{{ __('تحديث كلمة المرور') }}</h3>
                </div>
                <div class="card-body">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            {{-- بطاقة حذف الحساب --}}
            <div class="card card-danger card-outline mt-4"> {{-- استخدم card-danger للتحذير من الحذف --}}
                <div class="card-header">
                    <h3 class="card-title">{{ __('حذف الحساب') }}</h3>
                </div>
                <div class="card-body">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
@endsection {{-- نهاية قسم المحتوى --}}

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profilePhotoInput = document.getElementById('profile_photo_input');
            const profilePhotoPreview = document.getElementById('profile_photo_preview');
            const profilePhotoForm = document.getElementById('profile-photo-form');
            const removePhotoButton = document.getElementById('remove-photo-button');

            // Handle image preview
            profilePhotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePhotoPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    // Automatically submit the form when a new file is selected
                    profilePhotoForm.submit();
                }
            });

            // Handle remove photo button click
            if (removePhotoButton) {
                removePhotoButton.addEventListener('click', function() {
                    if (confirm('هل أنت متأكد أنك تريد إزالة صورة ملفك الشخصي؟')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('user-profile-photo.destroy') }}'; // Define this route in web.php
                        form.style.display = 'none';

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        });
    </script>
@endsection
