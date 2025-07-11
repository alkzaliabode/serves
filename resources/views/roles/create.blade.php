@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'إضافة دور جديد')

@section('page_title', 'إضافة دور جديد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">إدارة الأدوار</a></li>
    <li class="breadcrumb-item active">إضافة دور جديد</li>
@endsection

<<<<<<< HEAD
@section('styles')
    @parent {{-- للحفاظ على أي أنماط أخرى قد تكون موجودة في layout الأب --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <style>
        /* أنماط البطاقات لتكون شفافة مع تباين جيد للنص وتأثير زجاجي معزز */
        .card {
            background-color: rgba(255, 255, 255, 0.08) !important; /* خلفية شفافة أكثر */
            border-radius: 1.25rem !important; /* حواف مستديرة أكثر */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4) !important; /* ظل أنعم وأكثر انتشاراً */
            border: 1px solid rgba(255, 255, 255, 0.2) !important; /* حدود بارزة وواضحة جداً */
            backdrop-filter: blur(15px) !important; /* تأثير الزجاج المتجمد أكثر قوة */
            transition: all 0.3s ease-in-out; /* انتقال للتحويم */
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.15) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom: 1px solid rgba(255, 255, 255, 0.25) !important; /* حدود شفافة وواضحة */
            border-top-left-radius: 1.25rem; /* حواف مستديرة لرأس البطاقة */
            border-top-right-radius: 1.25rem;
            padding: 1.25rem 1.5rem; /* حشوة أكبر */
        }
        .card-title {
            font-size: 2rem; /* حجم خط أكبر للعنوان */
            font-weight: 700; /* خط سميك */
            color: #e0f2f7 !important; /* لون نص أبيض مزرق */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.9); /* ظل نص قوي */
        }

        .card-body {
            color: #f8f9fa; /* لون نص أبيض فاتح لجسم البطاقة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6); /* ظل خفيف للنص */
            padding: 1.8rem; /* حشوة أكبر لجسم البطاقة */
            font-size: 1.1rem; /* زيادة حجم الخط الأساسي للجسم */
        }

        /* أنماط حقول الإدخال والقوائم المنسدلة */
        .form-label {
            color: #e0f2f7 !important; /* لون أبيض فاتح للتسميات */
            font-weight: 600; /* خط سميك للتسميات */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7); /* ظل نص للتسميات */
            margin-bottom: 0.6rem; /* مسافة أكبر أسفل التسمية */
            font-size: 1.15rem; /* زيادة حجم خط التسميات */
        }

        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.8) !important; /* خلفية شفافة للحقول */
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: #212529 !important; /* لون نص داكن للحقول */
            border-radius: 0.75rem; /* حواف مستديرة */
            padding: 0.85rem 1.2rem; /* حشوة محسنة */
            font-size: 1.05rem; /* زيادة حجم خط حقول الإدخال */
            transition: all 0.2s ease-in-out;
        }
        .form-control::placeholder {
            color: #495057 !important; /* لون placeholder داكن */
            opacity: 0.7;
        }
        .form-control:focus,
        .form-select:focus {
            background-color: rgba(255, 255, 255, 0.95) !important; /* خلفية أفتح عند التركيز */
            border-color: #007bff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.4) !important; /* ظل أزرق عند التركيز */
        }
        /* لتلوين نص الـ <option> داخل الـ <select> عندما يكون الخلفية داكنة */
        .form-select option {
            background-color: #2c3e50; /* خلفية داكنة لخيار القائمة */
            color: white; /* نص أبيض لخيار القائمة */
        }

        /* تحسين Select2 ليتناسب مع AdminLTE ووضوح الكتابة */
        .select2-container--bootstrap4 .select2-selection--multiple,
        .select2-container--bootstrap4 .select2-selection--single {
            background-color: rgba(255, 255, 255, 0.8) !important; /* خلفية شفافة أكثر */
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: #212529 !important; /* لون نص داكن */
            border-radius: 0.75rem !important;
            padding: 0.85rem 1.2rem !important; /* حشوة محسنة */
            font-size: 1.05rem !important; /* زيادة حجم الخط */
        }
        .select2-container--bootstrap4 .select2-selection__choice {
            background-color: #007bff !important; /* خلفية زرقاء للخيار المحدد */
            color: white !important; /* نص أبيض على الخلفية الزرقاء */
            border-color: #006fe6 !important;
            font-size: 0.95rem; /* حجم خط الخيارات المحددة */
            padding: 0.4rem 0.6rem; /* حشوة الخيارات المحددة */
            border-radius: 0.5rem;
        }
        .select2-container--bootstrap4 .select2-selection__choice__remove {
            color: rgba(255, 255, 255, 0.7) !important; /* لون X أبيض فاتح */
        }
        .select2-container--bootstrap4 .select2-selection__choice__remove:hover {
            color: white !important; /* لون X أبيض عند التحويم */
        }
        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #212529 !important; /* لون النص المعروض في Select2 */
            font-size: 1.05rem !important; /* زيادة حجم الخط */
        }
        .select2-container--bootstrap4 .select2-search__field {
            color: #212529 !important; /* لون النص في حقل البحث */
            font-size: 1.05rem !important; /* زيادة حجم الخط */
        }
        .select2-container--bootstrap4 .select2-results__option {
            color: #333; /* لون النص في خيارات القائمة المنسدلة */
            font-size: 1.05rem; /* زيادة حجم الخط */
            padding: 0.75rem 1rem; /* حشوة محسنة */
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #007bff !important; /* خلفية زرقاء للخيار المميز */
            color: white !important; /* نص أبيض للخيار المميز */
        }

        /* أنماط رسائل الأخطاء (Alerts) */
        .alert {
            background-color: rgba(220, 53, 69, 0.9) !important; /* خلفية حمراء شفافة للخطأ */
            color: white !important;
            border-color: rgba(220, 53, 69, 0.6) !important;
            border-radius: 0.75rem; /* حواف مستديرة */
            padding: 1.2rem 1.8rem; /* حشوة أكبر */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* ظل خفيف */
            backdrop-filter: blur(5px); /* تأثير زجاجي خفيف */
            font-size: 1rem; /* حجم خط التنبيهات */
        }
        .alert ul {
            padding-left: 1.5rem; /* حشوة للقائمة */
            margin-bottom: 0;
        }
        .alert li {
            margin-bottom: 0.3rem;
        }
        .alert .close { /* تحسين زر الإغلاق */
            font-size: 1.8rem; /* حجم أكبر لزر الإغلاق */
            color: inherit; /* يرث لون النص */
            opacity: 0.7;
            filter: invert(1); /* لعكس اللون ليكون مرئياً على الخلفية الداكنة */
        }
        .alert .close:hover {
            opacity: 1;
            filter: invert(1);
        }

        /* أنماط مربعات الاختيار */
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            box-shadow: none !important;
            width: 1.2em; /* حجم أكبر لمربع الاختيار */
            height: 1.2em; /* حجم أكبر لمربع الاختيار */
            margin-top: 0.25em;
        }
        .form-check-input:checked {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }
        .form-check-label {
            color: #e0f2f7 !important; /* لون أبيض فاتح للتسمية */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            font-size: 1.1rem; /* زيادة حجم خط التسمية */
        }
        .form-text {
            color: #cce5ff !important; /* لون فاتح لنص المساعدة */
            font-size: 0.95em; /* زيادة حجم خط نص المساعدة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* أنماط أزرار الـ footer */
        .card-footer {
            background-color: rgba(255, 255, 255, 0.15) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom-left-radius: 1.25rem; /* حواف مستديرة لذيل البطاقة */
            border-bottom-right-radius: 1.25rem;
            padding: 1.5rem 1.8rem; /* حشوة أكبر */
            display: flex;
            justify-content: flex-end; /* محاذاة الأزرار لليمين */
            gap: 15px; /* مسافة بين الأزرار */
        }
        .card-footer .btn {
            font-size: 1.1rem; /* حجم خط الأزرار في الهيدر */
            padding: 0.8rem 1.6rem; /* حشوة أكبر للأزرار */
            border-radius: 0.75rem; /* حواف مستديرة للأزرار */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* ظل للأزرار */
            transition: all 0.3s ease;
            color: white !important; /* ضمان لون نص أبيض */
        }
        .card-footer .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        /* أزرار العمليات - تعزيز الألوان والتدرجات */
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #00bfff) !important;
            border-color: #007bff !important;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #009be6) !important;
            border-color: #0056b3 !important;
        }
        .btn-secondary {
            background: linear-gradient(45deg, #6c757d, #868e96) !important;
            border-color: #6c757d !important;
        }
        .btn-secondary:hover {
            background: linear-gradient(45deg, #5a6268, #717a82) !important;
            border-color: #545b62 !important;
        }

        /* أنماط الأيقونات في الأزرار */
        .btn .fas {
            margin-right: 10px; /* مسافة أكبر بين الأيقونة والنص */
            margin-left: -5px; /* تعديل للمحاذاة البصرية لليمين */
            font-size: 1.2em; /* حجم أكبر قليلا للأيقونات داخل الأزرار */
        }
    </style>
@endsection

=======
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">إنشاء دور جديد</h3>
                </div>
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <h5><i class="icon fas fa-ban"></i> خطأ في الإدخال!</h5>
                                يرجى مراجعة الأخطاء التالية:
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">{{ __('اسم الدور') }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="permissions">{{ __('الصلاحيات') }}</label>
                            <select id="permissions" name="permissions[]" multiple class="form-control select2 @error('permissions') is-invalid @enderror">
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', [])) ? 'selected' : '' }}>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('permissions')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @error('permissions.*')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('إنشاء الدور') }}</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary float-right">{{ __('إلغاء') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<<<<<<< HEAD
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
=======
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
    <script>
        $(document).ready(function() {
            $('#permissions').select2({
                placeholder: "{{ __('اختر صلاحية أو أكثر') }}",
                allowClear: true,
                theme: 'bootstrap4'
            });

            $('.alert .close').on('click', function() {
                $(this).closest('.alert').alert('close');
            });
        });
    </script>
@endsection
