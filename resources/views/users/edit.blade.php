@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل المستخدم') {{-- تعريف عنوان الصفحة --}}

@section('page_title', 'تعديل المستخدم: ' . $user->name) {{-- تعريف عنوان الصفحة في AdminLTE Header --}}

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">إدارة المستخدمين</a></li>
    <li class="breadcrumb-item active">تعديل المستخدم</li>
@endsection

@section('content') {{-- بداية قسم المحتوى حيث سيتم حقن كود الصفحة --}}
    <div class="row">
        <div class="col-md-8 offset-md-2"> {{-- لتوسيط البطاقة على الشاشة --}}
            <div class="card card-primary"> {{-- استخدام كارد AdminLTE --}}
                <div class="card-header">
                    <h3 class="card-title">تعديل معلومات المستخدم: {{ $user->name }}</h3>
                </div>
                <!-- /.card-header -->
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT') {{-- استخدام PUT method للتحديث --}}

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

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
                            <label for="name">{{ __('الاسم') }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('البريد الإلكتروني') }}</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="employee_id">{{ __('رقم الموظف') }}</label>
                            <input type="text" name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $user->employee_id) }}">
                            @error('employee_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="job_title">{{ __('المسمى الوظيفي') }}</label>
                            <input type="text" name="job_title" id="job_title" class="form-control @error('job_title') is-invalid @enderror" value="{{ old('job_title', $user->job_title) }}">
                            @error('job_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="unit">{{ __('القسم/الوحدة') }}</label>
                            <input type="text" name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $user->unit) }}">
                            @error('unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">{{ __('حساب نشط') }}</label>
                            </div>
                            <small class="form-text text-muted">{{ __('حدد ما إذا كان هذا الحساب نشطاً ومسموحاً له بالوصول.') }}</small>
                            @error('is_active')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('كلمة المرور (اتركها فارغة لعدم التغيير)') }}</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('تأكيد كلمة المرور') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <label for="roles">{{ __('الأدوار') }}</label>
                            <select id="roles" name="roles[]" multiple class="form-control select2 @error('roles') is-invalid @enderror" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ in_array($role->name, old('roles', $userRoles)) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @error('roles.*')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('تحديث المستخدم') }}</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary float-right">{{ __('إلغاء') }}</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 JS (لتحسين عرض قائمة الأدوار المتعددة) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5.0.15/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2 لحقل الأدوار
            $('#roles').select2({
                placeholder: "{{ __('اختر دوراً أو أكثر') }}",
                allowClear: true, // السماح بمسح التحديد
                theme: 'bootstrap4' // إذا كنت تستخدم Bootstrap 4
            });

            // Alerts dismissal for AdminLTE
            $('.alert .close').on('click', function() {
                $(this).closest('.alert').alert('close');
            });
        });
    </script>
@endsection
