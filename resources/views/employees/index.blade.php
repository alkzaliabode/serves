@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'إدارة الموظفين') {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', 'قائمة الموظفين') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">الموظفين</li>
@endsection

@section('styles')
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
        .card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5); /* ظل أكبر عند التحويم */
            transform: translateY(-5px); /* رفع البطاقة قليلاً */
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
        .card-header .btn {
            font-size: 0.95rem; /* حجم خط الأزرار في الهيدر */
            padding: 0.6rem 1.2rem; /* حشوة أكبر للأزرار */
            border-radius: 0.75rem; /* حواف مستديرة للأزرار */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* ظل للأزرار */
            transition: all 0.3s ease;
        }
        .card-header .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        .card-body {
            color: #f8f9fa; /* لون نص أبيض فاتح لجسم البطاقة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6); /* ظل خفيف للنص */
            padding: 1.5rem; /* حشوة أكبر لجسم البطاقة */
        }

        /* أنماط الجدول داخل البطاقة */
        .table {
            color: #f8f9fa; /* لون نص أبيض فاتح للجدول بالكامل */
            margin-bottom: 0; /* إزالة المسافة السفلية الافتراضية */
        }
        .table thead th {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.4) 0%, rgba(0, 190, 255, 0.4) 100%) !important; /* تدرج لوني أزرق شفاف لرؤوس الجدول */
            color: white !important; /* لون نص أبيض لرؤوس الجدول */
            border-color: rgba(255, 255, 255, 0.4) !important; /* حدود بيضاء شفافة */
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
            font-weight: 600; /* خط سميك لرؤوس الجدول */
            padding: 1rem 0.75rem; /* حشوة أكبر لرؤوس الجدول */
        }
        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1) !important; /* حدود بيضاء شفافة للصفوف */
            padding: 0.85rem 0.75rem; /* حشوة مناسبة للخلايا */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.03) !important; /* تظليل خفيف جداً للصفوف الفردية */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1) !important; /* تأثير تحويم أكثر وضوحاً */
            transition: background-color 0.2s ease;
        }

        /* أنماط أزرار وعناصر التحكم في الفلاتر */
        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.8) !important; /* خلفية شفافة للحقول */
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: #212529 !important; /* لون نص داكن للحقول */
            border-radius: 0.75rem; /* حواف مستديرة */
            padding: 0.75rem 1rem; /* حشوة محسنة */
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

        /* أزرار العمليات - تعزيز الألوان والظلال */
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #00bfff) !important;
            border-color: #007bff !important;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #009be6) !important;
            border-color: #0056b3 !important;
        }
        .btn-success {
            background: linear-gradient(45deg, #28a745, #5cb85c) !important;
            border-color: #28a745 !important;
        }
        .btn-success:hover {
            background: linear-gradient(45deg, #218838, #4cae4c) !important;
            border-color: #218838 !important;
        }
        .btn-info {
            background: linear-gradient(45deg, #17a2b8, #20c997) !important;
            border-color: #17a2b8 !important;
        }
        .btn-info:hover {
            background: linear-gradient(45deg, #138496, #1aa079) !important;
            border-color: #138496 !important;
        }
        .btn-danger {
            background: linear-gradient(45deg, #dc3545, #ff6347) !important;
            border-color: #dc3545 !important;
        }
        .btn-danger:hover {
            background: linear-gradient(45deg, #c82333, #e5533d) !important;
            border-color: #bd2130 !important;
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
        .btn .fas, .btn .bi {
            margin-right: 8px; /* مسافة أكبر بين الأيقونة والنص */
            margin-left: -2px; /* تعديل للمحاذاة البصرية لليمين */
            font-size: 1.1em; /* حجم أكبر قليلا للأيقونات داخل الأزرار */
        }
        .btn.btn-sm .fas, .btn.btn-sm .bi {
            font-size: 0.9em;
        }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.95) !important; /* خلفية شفافة جداً للرسائل */
            color: #333 !important; /* لون نص داكن */
            border-radius: 0.75rem; /* حواف مستديرة */
            padding: 1rem 1.5rem; /* حشوة أكبر */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* ظل خفيف */
            backdrop-filter: blur(5px); /* تأثير زجاجي خفيف */
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.9) !important; /* خلفية خضراء شفافة للنجاح */
            color: white !important;
            border-color: rgba(40, 167, 69, 0.6) !important;
        }
        .alert-info {
            background-color: rgba(23, 162, 184, 0.9) !important; /* خلفية زرقاء شفافة للمعلومات */
            color: white !important;
            border-color: rgba(23, 162, 184, 0.6) !important;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.9) !important; /* خلفية حمراء شفافة للخطأ */
            color: white !important;
            border-color: rgba(220, 53, 69, 0.6) !important;
        }
        .alert .close { /* تحسين زر الإغلاق */
            font-size: 1.5rem;
            color: inherit;
            opacity: 0.7;
        }
        .alert .close:hover {
            opacity: 1;
            color: inherit;
        }

        /* Badge styling */
        .badge.bg-success {
            background-color: rgba(40, 167, 69, 0.85) !important;
            color: white !important;
            padding: 0.5em 0.7em;
            border-radius: 0.5em;
            font-size: 0.8em;
        }
        .badge.bg-danger {
            background-color: rgba(220, 53, 69, 0.85) !important;
            color: white !important;
            padding: 0.5em 0.7em;
            border-radius: 0.5em;
            font-size: 0.8em;
        }

        /* Style for table sorting links */
        th a {
            color: white !important;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        th a:hover {
            color: #72efdd !important; /* لون تظليل نيون جذاب عند التحويم */
            text-decoration: underline;
        }
        th a .fas { /* أيقونات الترتيب */
            margin-left: 5px;
            font-size: 0.8em;
        }

        /* تحسينات Pagination */
        .pagination .page-item .page-link {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            color: #e0f2f7 !important;
            margin: 0 5px;
            border-radius: 8px; /* حواف مستديرة */
            transition: all 0.2s ease;
        }
        .pagination .page-item .page-link:hover {
            background-color: rgba(255, 255, 255, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.35) !important;
            color: #72efdd !important;
            transform: translateY(-2px);
        }
        .pagination .page-item.active .page-link {
            background-color: rgba(0, 123, 255, 0.7) !important; /* لون أزرق نشط */
            border-color: rgba(0, 123, 255, 0.8) !important;
            color: white !important;
            font-weight: bold;
        }
        .pagination .page-item.disabled .page-link {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.4) !important;
        }

        /* تنسيق الأيقونات (fas) لتكون متناسقة في الحجم واللون */
        .fas {
            color: inherit; /* لضمان أن الأيقونات تأخذ لون النص الأصلي */
        }
    </style>
@endsection

@section('content')
    <div class="card"> {{-- تم إزالة card-primary card-outline --}}
        <div class="card-header">
            <h3 class="card-title">قائمة الموظفين</h3>
            <div class="card-tools">
                <a href="{{ route('employees.create') }}" class="btn btn-primary"> {{-- تم إزالة btn-sm --}}
                    <i class="fas fa-plus"></i> إضافة موظف جديد
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"> {{-- تم تغيير data-dismiss إلى data-bs-dismiss --}}
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"> {{-- تم تغيير data-dismiss إلى data-bs-dismiss --}}
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('employees.index') }}" method="GET" class="mb-4">
                <div class="row g-3 align-items-end"> {{-- Added align-items-end for better alignment --}}
                    <div class="col-md-4">
                        <label for="search" class="form-label visually-hidden">بحث</label> {{-- Added label for accessibility --}}
                        <input type="text" name="search" id="search" class="form-control" placeholder="بحث بالاسم، الرقم الوظيفي، البريد، المسمى..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="unit_id" class="form-label visually-hidden">الوحدة</label>
                        <select name="unit_id" id="unit_id" class="form-select">
                            <option value="">جميع الوحدات</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="role" class="form-label visually-hidden">الدور</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">جميع الأدوار</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="is_active" class="form-label visually-hidden">حالة النشاط</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="">حالة النشاط</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex"> {{-- Use d-flex for button alignment --}}
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> تصفية
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('employees.export', request()->query()) }}" class="btn btn-success me-2">
                        <i class="fas fa-file-excel"></i> تصدير (CSV)
                    </a>
                    <a href="{{ route('employees.print', request()->query()) }}" target="_blank" class="btn btn-info">
                        <i class="fas fa-print"></i> طباعة
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center table-hover"> {{-- Added table-hover for consistency --}}
                    <thead>
                        <tr>
                            <th>الاسم
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_by' => 'name', 'sort_order' => (request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                    @if(request('sort_by') == 'name' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'name' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                </a>
                            </th>
                            <th>الرقم الوظيفي
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_by' => 'employee_number', 'sort_order' => (request('sort_by') == 'employee_number' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                    @if(request('sort_by') == 'employee_number' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'employee_number' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                </a>
                            </th>
                            <th>الوحدة
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_by' => 'unit_id', 'sort_order' => (request('sort_by') == 'unit_id' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                    @if(request('sort_by') == 'unit_id' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'unit_id' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                </a>
                            </th>
                            <th>الدور
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_by' => 'role', 'sort_order' => (request('sort_by') == 'role' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                    @if(request('sort_by') == 'role' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'role' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                </a>
                            </th>
                            <th>نشط</th>
                            <th>متوسط التقييم
                                <a href="{{ route('employees.index', array_merge(request()->query(), ['sort_by' => 'average_rating', 'sort_order' => (request('sort_by') == 'average_rating' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                    @if(request('sort_by') == 'average_rating' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'average_rating' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                </a>
                            </th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->employee_number }}</td>
                                <td>{{ $employee->unit->name ?? 'N/A' }}</td>
                                <td>{{ $employee->role }}</td>
                                <td>
                                    @if($employee->is_active)
                                        <span class="badge bg-success">نعم</span>
                                    @else
                                        <span class="badge bg-danger">لا</span>
                                    @endif
                                </td>
                                <td>{{ number_format($employee->average_rating, 2) }}</td>
                                <td class="actions">
                                    <div class="btn-group" role="group" aria-label="Employee Actions"> {{-- Added role and aria-label --}}
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-info" title="تعديل"> {{-- تم إزالة btn-sm --}}
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" onclick="showCustomConfirm('هل أنت متأكد من رغبتك في حذف هذا الموظف؟', '{{ route('employees.destroy', $employee->id) }}', 'DELETE')" title="حذف"> {{-- تم تغيير onclick لاستخدام الدالة المخصصة --}}
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد سجلات موظفين مطابقة للبحث أو الفلاتر.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Custom Confirmation Modal --}}
    <div class="modal fade" id="customConfirmModal" tabindex="-1" aria-labelledby="customConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card" style="background-color: rgba(44, 62, 80, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.3);">
                <div class="modal-header card-header" style="background-color: rgba(44, 62, 80, 0.9) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.2);">
                    <h5 class="modal-title" id="customConfirmModalLabel" style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body card-body" style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                    <p id="confirmMessage"></p>
                </div>
                <div class="modal-footer card-footer" style="background-color: rgba(44, 62, 80, 0.9) !important; border-top: 1px solid rgba(255, 255, 255, 0.2);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // دالة لعرض نافذة التأكيد المخصصة
        function showCustomConfirm(message, actionUrl, method) {
            document.getElementById('confirmMessage').innerText = message;
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            // إزالة أي معالجات أحداث سابقة
            confirmDeleteBtn.onclick = null;

            confirmDeleteBtn.onclick = function() {
                // إنشاء form ديناميكياً لإرسال طلب DELETE
                const form = document.createElement('form');
                form.setAttribute('method', 'POST');
                form.setAttribute('action', actionUrl);

                const csrfInput = document.createElement('input');
                csrfInput.setAttribute('type', 'hidden');
                csrfInput.setAttribute('name', '_token');
                csrfInput.setAttribute('value', '{{ csrf_token() }}');
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.setAttribute('type', 'hidden');
                methodInput.setAttribute('name', '_method');
                methodInput.setAttribute('value', method);
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            };

            const confirmModal = new bootstrap.Modal(document.getElementById('customConfirmModal'));
            confirmModal.show();
        }
    </script>
@endsection
