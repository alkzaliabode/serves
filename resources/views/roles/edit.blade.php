@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل الدور')

@section('page_title', 'تعديل الدور: ' . $role->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">إدارة الأدوار</a></li>
    <li class="breadcrumb-item active">تعديل الدور</li>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">
    <style>
        /* تحسين وضوح الكتابة في حقول الإدخال */
        .form-control {
            color: #333; /* لون نص أغمق */
            background-color: #f8f9fa; /* خلفية بيضاء مائلة للرمادي الفاتح لزيادة التباين */
            border-color: #ced4da; /* لون حدود أوضح */
        }
        .form-control::placeholder {
            color: #6c757d; /* لون placeholder أغمق */
        }

        /* تحسين Select2 ليتناسب مع AdminLTE ووضوح الكتابة */
        .select2-container--bootstrap4 .select2-selection--multiple,
        .select2-container--bootstrap4 .select2-selection--single {
            background-color: #f8f9fa !important; /* خلفية بيضاء مائلة للرمادي الفاتح */
            border-color: #ced4da !important; /* حدود أوضح */
            color: #333 !important; /* لون نص أغمق */
        }
        .select2-container--bootstrap4 .select2-selection__choice {
            background-color: #007bff !important; /* خلفية زرقاء للخيار المحدد */
            color: white !important; /* نص أبيض على الخلفية الزرقاء */
            border-color: #006fe6 !important;
        }
        .select2-container--bootstrap4 .select2-selection__choice__remove {
            color: rgba(255, 255, 255, 0.7) !important; /* لون X أبيض فاتح */
        }
        .select2-container--bootstrap4 .select2-selection__choice__remove:hover {
            color: white !important; /* لون X أبيض عند التحويم */
        }
        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #333 !important; /* لون النص المعروض في Select2 */
        }
        .select2-container--bootstrap4 .select2-search__field {
            color: #333 !important; /* لون النص في حقل البحث */
        }
        .select2-container--bootstrap4 .select2-results__option {
            color: #333; /* لون النص في خيارات القائمة المنسدلة */
        }
        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #007bff !important; /* خلفية زرقاء للخيار المميز */
            color: white !important; /* نص أبيض للخيار المميز */
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">تعديل معلومات الدور: {{ $role->name }}</h3>
                </div>
                <form method="POST" action="{{ route('roles.update', $role) }}">
                    @csrf
                    @method('PUT')

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
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required autofocus>
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
                                    <option value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'selected' : '' }}>
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
                        <button type="submit" class="btn btn-primary">{{ __('تحديث الدور') }}</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary float-right">{{ __('إلغاء') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#permissions').select2({
                placeholder: "{{ __('اختر صلاحية أو أكثر') }}",
                allowClear: true,
                theme: 'bootstrap4'
            });

            // استخدام SweetAlert2 بدلاً من alert/confirm المتصفح
            $('form').on('submit', function(e) {
                // إذا كان هناك أخطاء تحقق من صحة النموذج، لا تعرض SweetAlert
                if ($(this).find('.is-invalid').length > 0) {
                    return; // دع التحقق الافتراضي لـ Laravel/Bootstrap يتعامل مع الأخطاء
                }

                // يمكنك إضافة تأكيد مخصص هنا إذا أردت، ولكن عادةً لا يكون مطلوبًا لتحديث الأدوار
                // e.preventDefault(); // لمنع الإرسال الفوري إذا كنت ستضيف تأكيد
                // Swal.fire({
                //     title: 'هل أنت متأكد؟',
                //     text: "سيتم تحديث الدور بالصلاحيات الجديدة!",
                //     icon: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'نعم، قم بالتحديث!',
                //     cancelButtonText: 'إلغاء'
                // }).then((result) => {
                //     if (result.isConfirmed) {
                //         e.currentTarget.submit(); // إرسال النموذج يدوياً بعد التأكيد
                //     }
                // });
            });

            $('.alert .close').on('click', function() {
                $(this).closest('.alert').alert('close');
            });
        });
    </script>
@endsection
