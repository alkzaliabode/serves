{{--
    هذا الملف هو الجزء المشترك لنموذج إضافة وتعديل الموظفين.
    يتم تضمينه في create.blade.php و edit.blade.php.
    يتوقع أن يتم تمرير متغير $employee إذا كان النموذج لتعديل موظف موجود.
    ويتوقع تمرير متغير $units و $roles كقوائم للعناصر المنسدلة.
--}}

{{--
    بداية تنسيقات CSS المخصصة لهذا النموذج
    تم تصميم الأنماط لتكون متناسقة مع تصميم Glassmorphism الشفاف
--}}
@section('styles')
    @parent {{-- للحفاظ على أي أنماط أخرى قد تكون موجودة في layout الأب --}}
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
            font-size: 1.8rem; /* حجم خط أكبر للعنوان */
            font-weight: 700; /* خط سميك */
            color: #e0f2f7 !important; /* لون نص أبيض مزرق */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.9); /* ظل نص قوي */
        }

        .card-body {
            color: #f8f9fa; /* لون نص أبيض فاتح لجسم البطاقة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6); /* ظل خفيف للنص */
            padding: 1.5rem; /* حشوة أكبر لجسم البطاقة */
        }

        /* أنماط حقول الإدخال والقوائم المنسدلة */
        .form-label {
            color: #e0f2f7 !important; /* لون أبيض فاتح للتسميات */
            font-weight: 600; /* خط سميك للتسميات */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7); /* ظل نص للتسميات */
            margin-bottom: 0.5rem; /* مسافة أسفل التسمية */
        }

        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.8) !important; /* خلفية شفافة للحقول */
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: #212529 !important; /* لون نص داكن للحقول */
            border-radius: 0.75rem; /* حواف مستديرة */
            padding: 0.75rem 1rem; /* حشوة محسنة */
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

        /* أنماط رسائل الأخطاء (Alerts) */
        .alert {
            background-color: rgba(220, 53, 69, 0.9) !important; /* خلفية حمراء شفافة للخطأ */
            color: white !important;
            border-color: rgba(220, 53, 69, 0.6) !important;
            border-radius: 0.75rem; /* حواف مستديرة */
            padding: 1rem 1.5rem; /* حشوة أكبر */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* ظل خفيف */
            backdrop-filter: blur(5px); /* تأثير زجاجي خفيف */
        }
        .alert ul {
            padding-left: 1.25rem; /* حشوة للقائمة */
        }
        .alert .btn-close { /* تحسين زر الإغلاق */
            font-size: 1.5rem;
            color: inherit; /* يرث لون النص */
            opacity: 0.7;
            filter: invert(1); /* لعكس اللون ليكون مرئياً على الخلفية الداكنة */
        }
        .alert .btn-close:hover {
            opacity: 1;
            filter: invert(1);
        }

        /* أنماط مربعات الاختيار */
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            box-shadow: none !important;
        }
        .form-check-input:checked {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }
        .form-check-label {
            color: #e0f2f7 !important; /* لون أبيض فاتح للتسمية */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .form-text {
            color: #cce5ff !important; /* لون فاتح لنص المساعدة */
            font-size: 0.875em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* أنماط أزرار الـ footer */
        .card-footer {
            background-color: rgba(255, 255, 255, 0.15) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom-left-radius: 1.25rem; /* حواف مستديرة لذيل البطاقة */
            border-bottom-right-radius: 1.25rem;
            padding: 1.25rem 1.5rem; /* حشوة أكبر */
            display: flex;
            justify-content: flex-end; /* محاذاة الأزرار لليمين */
            gap: 15px; /* مسافة بين الأزرار */
        }
        .card-footer .btn {
            font-size: 1rem; /* حجم خط الأزرار في الهيدر */
            padding: 0.7rem 1.4rem; /* حشوة أكبر للأزرار */
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
            margin-right: 8px; /* مسافة أكبر بين الأيقونة والنص */
            margin-left: -2px; /* تعديل للمحاذاة البصرية لليمين */
            font-size: 1.1em; /* حجم أكبر قليلا للأيقونات داخل الأزرار */
        }
    </style>
@endsection

<div class="card"> {{-- تم إزالة card-primary card-outline لكي نطبق أنماطنا المخصصة --}}
    <div class="card-header">
        <h3 class="card-title">{{ isset($employee) ? 'تعديل بيانات الموظف' : 'إضافة موظف جديد' }}</h3>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">الاسم</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $employee->name ?? '') }}" required placeholder="أدخل اسم الموظف">
        </div>
        <div class="mb-3">
            <label for="employee_number" class="form-label">الرقم الوظيفي</label>
            <input type="text" class="form-control" id="employee_number" name="employee_number" value="{{ old('employee_number', $employee->employee_number ?? '') }}" required placeholder="أدخل الرقم الوظيفي">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email ?? '') }}" required placeholder="أدخل البريد الإلكتروني">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" {{ isset($employee) ? '' : 'required' }} placeholder="{{ isset($employee) ? 'اترك فارغاً لعدم التغيير' : 'أدخل كلمة المرور' }}">
            @if(isset($employee))
                <div class="form-text">اترك هذا الحقل فارغاً إذا كنت لا ترغب في تغيير كلمة المرور.</div>
            @endif
        </div>
        <div class="mb-3">
            <label for="job_title" class="form-label">المسمى الوظيفي</label>
            <input type="text" class="form-control" id="job_title" name="job_title" value="{{ old('job_title', $employee->job_title ?? '') }}" placeholder="أدخل المسمى الوظيفي">
        </div>
        <div class="mb-3">
            <label for="unit_id" class="form-label">الوحدة</label>
            <select class="form-select" id="unit_id" name="unit_id" required>
                <option value="">اختر الوحدة</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id', $employee->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">الدور</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">اختر الدور</option>
                @foreach($roles as $roleOption)
                    <option value="{{ $roleOption }}" {{ old('role', $employee->role ?? '') == $roleOption ? 'selected' : '' }}>
                        {{ $roleOption }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $employee->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                نشط
            </label>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ isset($employee) ? 'تحديث الموظف' : 'حفظ الموظف' }}
        </button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> إلغاء
        </a>
    </div>
</div>
