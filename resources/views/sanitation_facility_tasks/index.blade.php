@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'قائمة مهام المنشآت الصحية')
@section('page_title', 'مهام المنشآت الصحية')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">مهام المنشآت الصحية</li>
@endsection

@section('styles')
    {{-- CSS مخصص إضافي إذا لزم الأمر، ولكن معظم التنسيقات سيتكفل بها AdminLTE و Bootstrap --}}
    <style>
        /* أنماط البطاقات لتكون شفافة مع تباين جيد للنص */
        .card {
            background-color: rgba(255, 255, 255, 0.4) !important; /* خلفية شفافة للبطاقات */
            border-radius: 0.75rem; /* حواف مستديرة */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* ظل أوضح */
            border: none !important; /* إزالة أي حدود */
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.6) !important; /* خلفية رأس البطاقة أكثر شفافية */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important; /* حدود شفافة */
        }
        .card-title,
        .card-header .btn {
            color: white !important; /* لون نص أبيض لعناوين البطاقات والأزرار */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7); /* ظل للنص لتحسين القراءة */
        }

        .card-body {
            color: white; /* لون نص أبيض لجسم البطاقة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* ظل خفيف للنص */
        }

        /* أنماط الجدول داخل البطاقة */
        .table {
            color: white; /* لون نص أبيض للجدول بالكامل */
        }
        .table thead th {
            background-color: rgba(0, 123, 255, 0.5) !important; /* خلفية زرقاء شفافة لرؤوس الجدول */
            color: white !important; /* لون نص أبيض لرؤوس الجدول */
            border-color: rgba(255, 255, 255, 0.3) !important; /* حدود بيضاء شفافة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }
        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1) !important; /* حدود بيضاء شفافة للصفوف */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.2) !important; /* تظليل خفيف للصفوف الفردية */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.3) !important; /* تأثير تحويم أكثر وضوحاً */
        }

        /* أنماط أزرار وعناصر التحكم في الفلاتر */
        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.7) !important; /* خلفية شفافة للحقول */
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: #333 !important; /* لون نص داكن للحقول */
        }
        .form-control::placeholder {
            color: #666 !important;
        }
        .form-control:focus,
        .form-select:focus {
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25) !important;
        }
        /* لتلوين نص الـ <option> داخل الـ <select> عندما يكون الخلفية داكنة */
        .form-select option {
            background-color: #2c3e50; /* خلفية داكنة لخيار القائمة */
            color: white; /* نص أبيض لخيار القائمة */
        }

        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            transition: background-color 0.3s ease;
            color: white !important; /* ضمان لون النص أبيض */
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            transition: background-color 0.3s ease;
            color: white !important; /* ضمان لون النص أبيض */
        }
        .btn-success:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
        }

        .btn-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: white !important;
        }
        .btn-info:hover {
            background-color: #138496 !important;
            border-color: #138496 !important;
        }

        .btn-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #212529 !important; /* لون نص داكن لزر التحذير */
        }
        .btn-warning:hover {
            background-color: #e0a800 !important;
            border-color: #e0a800 !important;
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
        }
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
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
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.9) !important; /* خلفية خضراء شفافة للنجاح */
            color: white !important;
        }
        .alert-info {
            background-color: rgba(23, 162, 184, 0.9) !important; /* خلفية زرقاء شفافة للمعلومات */
            color: white !important;
        }

        /* Badge styling */
        .badge.bg-success {
            background-color: rgba(40, 167, 69, 0.8) !important;
            color: white !important;
        }
        .badge.bg-danger {
            background-color: rgba(220, 53, 69, 0.8) !important;
            color: white !important;
        }
        .badge.bg-warning {
            background-color: rgba(255, 193, 7, 0.8) !important;
            color: black !important;
        }
        .badge.bg-info {
            background-color: rgba(23, 162, 184, 0.8) !important;
            color: white !important;
        }
        .badge.bg-secondary {
            background-color: rgba(108, 117, 125, 0.8) !important;
            color: white !important;
        }


        .table-responsive { overflow-x: auto; }
        th, td { white-space: nowrap; } /* لمنع التفاف النصوص في الجدول */
        .actions button { margin-right: 5px; }
        .filter-section {
            background-color: #f8f9fa; /* هذا قد لا يطبق بسبب تجاوز .card */
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .filter-section .row > div {
            margin-bottom: 1rem;
        }
        .filter-section .row > div:last-child {
            margin-bottom: 0;
        }
        /* لتنسيق أيقونات الفرز */
        th a {
            color: inherit; /* لضمان أن لون الرابط يتطابق مع لون العنوان */
            text-decoration: none; /* لإزالة التسطير */
        }
        th a:hover {
            text-decoration: none; /* إزالة التسطير عند التحويم */
        }
    </style>
    {{-- إضافة أيقونات Bootstrap إذا لم تكن موجودة بالفعل في الـ layout --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-gray-800"></h1> {{-- العنوان تمت إضافته بالفعل في page_title --}}
            <a href="{{ route('sanitation-facility-tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إنشاء مهمة جديدة
            </a>
        </div>

        <div class="card card-primary card-outline collapsed-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter me-1"></i>
                    الفلاتر
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i data-lte-icon="plus" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="minus" class="bi bi-dash-lg" style="display: none;"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('sanitation-facility-tasks.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="search" class="form-label">بحث عام</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="بحث بالمرفق، الحالة، الهدف، المنشئ أو المنفذ..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="task_type" class="form-label">نوع المهمة</label>
                            <select name="task_type" id="task_type" class="form-select">
                                <option value="">الكل</option>
                                <option value="إدامة" {{ request('task_type') == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                <option value="صيانة" {{ request('task_type') == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label">حالة المهمة</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">الكل</option>
                                <option value="مكتمل" {{ request('status') == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                <option value="قيد التنفيذ" {{ request('status') == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="ملغى" {{ request('status') == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="shift" class="form-label">الوجبة</label>
                            <select name="shift" id="shift" class="form-select">
                                <option value="">الكل</option>
                                <option value="صباحي" {{ request('shift') == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                <option value="مسائي" {{ request('shift') == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                <option value="ليلي" {{ request('shift') == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="facility_name" class="form-label">المرفق الصحي</label>
                            <select name="facility_name" id="facility_name" class="form-select">
                                <option value="">الكل</option>
                                <option value="صحية الجامع رجال" {{ request('facility_name') == 'صحية الجامع رجال' ? 'selected' : '' }}>صحية الجامع رجال</option>
                                <option value="صحية الجامع نساء" {{ request('facility_name') == 'صحية الجامع نساء' ? 'selected' : '' }}>صحية الجامع نساء</option>
                                <option value="صحية 1 رجال" {{ request('facility_name') == 'صحية 1 رجال' ? 'selected' : '' }}>صحية 1 رجال</option>
                                <option value="صحية 2 رجال" {{ request('facility_name') == 'صحية 2 رجال' ? 'selected' : '' }}>صحية 2 رجال</option>
                                <option value="صحية 3 رجال" {{ request('facility_name') == 'صحية 3 رجال' ? 'selected' : '' }}>صحية 3 رجال</option>
                                <option value="صحية 4 رجال" {{ request('facility_name') == 'صحية 4 رجال' ? 'selected' : '' }}>صحية 4 رجال</option>
                                <option value="صحية 1 نساء" {{ request('facility_name') == 'صحية 1 نساء' ? 'selected' : '' }}>صحية 1 نساء</option>
                                <option value="صحية 2 نساء" {{ request('facility_name') == 'صحية 2 نساء' ? 'selected' : '' }}>صحية 2 نساء</option>
                                <option value="صحية 3 نساء" {{ request('facility_name') == 'صحية 3 نساء' ? 'selected' : '' }}>صحية 3 نساء</option>
                                <option value="صحية 4 نساء" {{ request('facility_name') == 'صحية 4 نساء' ? 'selected' : '' }}>صحية 4 نساء</option>
                                <option value="المجاميع الكبيرة رجال" {{ request('facility_name') == 'المجاميع الكبيرة رجال' ? 'selected' : '' }}>المجاميع الكبيرة رجال</option>
                                <option value="المجاميع الكبيرة نساء" {{ request('facility_name') == 'المجاميع الكبيرة نساء' ? 'selected' : '' }}>المجاميع الكبيرة نساء</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="employee_id" class="form-label">الموظف المنفذ</label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">الكل</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="from_date" class="form-label">من تاريخ</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="to_date" class="form-label">إلى تاريخ</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> تطبيق الفلاتر
                            </button>
                            <a href="{{ route('sanitation-facility-tasks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i> إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list me-1"></i>
                    قائمة المهام
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>التاريخ
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->query(), ['sort_by' => 'date', 'sort_order' => (request('sort_by') == 'date' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'date' && request('sort_order') == 'asc') <i class="bi bi-sort-up"></i> @elseif(request('sort_by') == 'date' && request('sort_order') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>المرفق الصحي
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->query(), ['sort_by' => 'facility_name', 'sort_order' => (request('sort_by') == 'facility_name' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'facility_name' && request('sort_order') == 'asc') <i class="bi bi-sort-up"></i> @elseif(request('sort_by') == 'facility_name' && request('sort_order') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>نوع المهمة
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->query(), ['sort_by' => 'task_type', 'sort_order' => (request('sort_by') == 'task_type' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'task_type' && request('sort_order') == 'asc') <i class="bi bi-sort-up"></i> @elseif(request('sort_by') == 'task_type' && request('sort_order') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>الوجبة
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->query(), ['sort_by' => 'shift', 'sort_order' => (request('sort_by') == 'shift' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'shift' && request('sort_order') == 'asc') <i class="bi bi-sort-up"></i> @elseif(request('sort_by') == 'shift' && request('sort_order') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>الحالة
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->query(), ['sort_by' => 'status', 'sort_order' => (request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'status' && request('sort_order') == 'asc') <i class="bi bi-sort-up"></i> @elseif(request('sort_by') == 'status' && request('sort_order') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>ساعات العمل
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->query(), ['sort_by' => 'working_hours', 'sort_order' => (request('sort_by') == 'working_hours' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'working_hours' && request('sort_order') == 'asc') <i class="bi bi-sort-up"></i> @elseif(request('sort_by') == 'working_hours' && request('sort_order') == 'desc') <i class="bi bi-sort-down"></i> @else <i class="bi bi-arrow-down-up"></i> @endif
                                    </a>
                                </th>
                                <th>أنشأها المشرف</th>
                                <th>عدّلها المشرف</th>
                                <th>المنفذون والتقييم</th>
                                <th>الهدف المرتبط</th>
                                <th>الوحدة</th>
                                <th>صور قبل</th>
                                <th>صور بعد</th>
                                <th>المقاعد</th>
                                <th>المرايا</th>
                                <th>الخلاطات</th>
                                <th>الأبواب</th>
                                <th>المغاسل</th>
                                <th>الحمامات</th>
                                <th>الموارد المستخدمة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>تاريخ آخر تحديث</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                                <tr>
                                    <td>{{ $task->date->format('Y-m-d') }}</td>
                                    <td>{{ $task->facility_name }}</td>
                                    <td>
                                        @if($task->task_type == 'إدامة') <span class="badge bg-info">إدامة</span>
                                        @elseif($task->task_type == 'صيانة') <span class="badge bg-warning">صيانة</span>
                                        @else <span class="badge bg-secondary">{{ $task->task_type }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->shift }}</td>
                                    <td>
                                        @if($task->status == 'مكتمل') <span class="badge bg-success">مكتمل</span>
                                        @elseif($task->status == 'قيد التنفيذ') <span class="badge bg-warning">قيد التنفيذ</span>
                                        @elseif($task->status == 'ملغى') <span class="badge bg-danger">ملغى</span>
                                        @else <span class="badge bg-secondary">{{ $task->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->working_hours }}</td>
                                    <td>{{ $task->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $task->editor->name ?? 'N/A' }}</td>
                                    <td>
                                        @forelse ($task->employeeTasks as $employeeTask)
                                            @php
                                                $employeeName = $employeeTask->employee->name ?? 'غير معروف';
                                                $rating = $employeeTask->employee_rating;
                                                $ratingText = match ((int)$rating) {
                                                    1 => 'ضعيف ★',
                                                    2 => '★★',
                                                    3 => 'متوسط ★★★',
                                                    4 => '★★★★',
                                                    5 => 'ممتاز ★★★★★',
                                                    default => 'غير مقيم',
                                                };
                                            @endphp
                                            <div>{{ $employeeName }} (<span class="fw-bold">{{ $ratingText }}</span>)</div>
                                        @empty
                                            لا يوجد
                                        @endforelse
                                    </td>
                                    <td>{{ $task->relatedGoal->goal_text ?? 'N/A' }}</td>
                                    <td>{{ $task->unit->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($task->before_images)
                                            @foreach ($task->before_images as $imagePath)
                                                <a href="{{ Storage::url($imagePath) }}" target="_blank">
                                                    <img src="{{ Storage::url($imagePath) }}" alt="قبل" style="width:50px; height:50px; border-radius: 50%; object-fit: cover; margin:2px;">
                                                </a>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if ($task->after_images)
                                            @foreach ($task->after_images as $imagePath)
                                                <a href="{{ Storage::url($imagePath) }}" target="_blank">
                                                    <img src="{{ Storage::url($imagePath) }}" alt="بعد" style="width:50px; height:50px; border-radius: 50%; object-fit: cover; margin:2px;">
                                                </a>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $task->seats_count ?? 0 }}</td>
                                    <td>{{ $task->mirrors_count ?? 0 }}</td>
                                    <td>{{ $task->mixers_count ?? 0 }}</td>
                                    <td>{{ $task->doors_count ?? 0 }}</td>
                                    <td>{{ $task->sinks_count ?? 0 }}</td>
                                    <td>{{ $task->toilets_count ?? 0 }}</td>
                                    <td>
                                        @if (!empty($task->resources_used))
                                            @foreach ($task->resources_used as $resource)
                                                <div>{{ $resource['name'] ?? 'N/A' }} ({{ $resource['quantity'] ?? 'N/A' }} {{ $resource['unit'] ?? '' }})</div>
                                            @endforeach
                                        @else
                                            لا توجد موارد
                                        @endif
                                    </td>
                                    <td>{{ $task->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $task->updated_at->format('Y-m-d H:i') }}</td>
                                    <td class="actions">
                                        <a href="{{ route('sanitation-facility-tasks.edit', $task) }}" class="btn btn-sm btn-info" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sanitation-facility-tasks.destroy', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه المهمة؟')" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="22" class="text-center">لا توجد مهام منشآت صحية حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <div class="d-flex justify-content-center">
                    {{ $tasks->links('pagination::bootstrap-5') }} </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- لا توجد سكربتات JS مخصصة لهذا الاندكس بخلاف ما يوفره AdminLTE و Bootstrap بشكل افتراضي --}}
@endsection
