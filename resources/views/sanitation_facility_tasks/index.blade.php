@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'قائمة مهام المنشآت الصحية')
@section('page_title', 'مهام المنشآت الصحية')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">مهام المنشآت الصحية</li>
@endsection

@section('styles')
    <style>
        /* أنماط البطاقات لتكون شفافة مع تباين جيد للنص */
        .card {
            background-color: rgba(255, 255, 255, 0.4) !important;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: none !important;
            backdrop-filter: blur(5px); /* تأثير ضبابي لتحسين الشفافية */
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.6) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .card-title,
        .card-header .btn {
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }

        .card-body {
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* أنماط الجدول داخل البطاقة */
        .table {
            color: white;
        }

        .table thead th {
            background-color: rgba(0, 123, 255, 0.5) !important;
            color: white !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
        }

        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1) !important;
            vertical-align: middle; /* لمحاذاة المحتوى في المنتصف */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.3) !important;
        }

        /* أنماط أزرار وعناصر التحكم في الفلاتر */
        .form-control,
        .form-select {
            background-color: rgba(255, 255, 255, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: #333 !important;
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
            background-color: #2c3e50;
            color: white;
        }

        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            transition: background-color 0.3s ease;
            color: white !important;
        }

        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            transition: background-color 0.3s ease;
            color: white !important;
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
            color: #212529 !important;
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
            margin-right: 5px;
        }

        /* أنماط رسائل التنبيه (Alerts) */
        .alert {
            background-color: rgba(255, 255, 255, 0.9) !important;
            color: #333 !important;
            border-color: rgba(0, 0, 0, 0.2) !important;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.9) !important;
            color: white !important;
        }

        .alert-info {
            background-color: rgba(23, 162, 184, 0.9) !important;
            color: white !important;
        }

        /* Badge styling */
        .badge {
            padding: 0.4em 0.8em;
            border-radius: 0.75rem;
            font-weight: bold;
        }
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

        .table-responsive {
            overflow-x: auto;
        }

        th,
        td {
            white-space: nowrap; /* يمنع التفاف النصوص في الجدول بشكل افتراضي */
        }

        /* لمنع التفاف النصوص في الجدول */
        .actions button, .actions a {
            margin-right: 5px;
            margin-bottom: 5px; /* لتحسين التباعد في الشاشات الصغيرة */
        }

        .filter-section {
            background-color: rgba(248, 249, 250, 0.6); /* شفافية أخف للفلاتر */
            border: 1px solid rgba(226, 232, 240, 0.3);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* ظل أخف */
        }

        .filter-section .row>div {
            margin-bottom: 1rem;
        }

        .filter-section .row>div:last-child {
            margin-bottom: 0;
        }

        /* لتنسيق أيقونات الفرز */
        th a {
            color: inherit;
            text-decoration: none;
            display: inline-flex; /* لجعل الأيقونة والنص متجاورين */
            align-items: center;
        }

        th a:hover {
            text-decoration: none;
        }
        th a i {
            margin-left: 5px; /* مسافة بين النص والأيقونة */
        }


        /* Styles for thumbnail images in table */
        .img-thumbnail-container {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            justify-content: center; /* لمحاذاة الصور في المنتصف */
            align-items: center;
        }

        .img-thumbnail {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s ease-in-out; /* إضافة تأثير عند التكبير */
        }
        .img-thumbnail:hover {
            transform: scale(1.1); /* تكبير الصورة عند التمرير */
        }

        /* Rating Stars Styling */
        .rating-stars {
            color: #ffc107; /* لون النجوم الذهبي */
        }
        .rating-text {
            font-weight: bold;
            display: inline-block;
            margin-right: 5px;
        }
        .employee-rating-item {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dotted rgba(255, 255, 255, 0.2);
        }
        .employee-rating-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-gray-800"></h1> {{-- العنوان تمت إضافته بالفعل في page_title --}}
            <a href="{{ route('sanitation-facility-tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إنشاء مهمة جديدة
            </a>
        </div>

        {{-- قسم البحث والفلاتر الاحترافي --}}
        <div class="card card-primary card-outline {{ request()->anyFilled(['search', 'task_type', 'status', 'shift', 'facility_name', 'employee_id', 'from_date', 'to_date']) ? '' : 'collapsed-card' }}">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter me-1"></i>
                    **الفلاتر وخيارات البحث المتقدمة** 🔍
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i data-lte-icon="plus" class="bi bi-plus-lg" {{ request()->anyFilled(['search', 'task_type', 'status', 'shift', 'facility_name', 'employee_id', 'from_date', 'to_date']) ? 'style="display: none;"' : '' }}></i>
                        <i data-lte-icon="minus" class="bi bi-dash-lg" {{ request()->anyFilled(['search', 'task_type', 'status', 'shift', 'facility_name', 'employee_id', 'from_date', 'to_date']) ? '' : 'style="display: none;"' }}></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('sanitation-facility-tasks.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="search" class="form-label">**بحث عام**</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="بحث بالمرفق، الحالة، الهدف، المنشئ أو المنفذ..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="task_type" class="form-label">**نوع المهمة**</label>
                            <select name="task_type" id="task_type" class="form-select">
                                <option value="">الكل</option>
                                <option value="إدامة" {{ request('task_type') == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                <option value="صيانة" {{ request('task_type') == 'صيانة' ? 'selected' : '' }}>صيانة</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">**حالة المهمة**</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">الكل</option>
                                <option value="مكتمل" {{ request('status') == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                                <option value="قيد التنفيذ" {{ request('status') == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                <option value="ملغى" {{ request('status') == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                                <option value="معلق" {{ request('status') == 'معلق' ? 'selected' : '' }}>معلق</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="shift" class="form-label">**الوجبة**</label>
                            <select name="shift" id="shift" class="form-select">
                                <option value="">الكل</option>
                                <option value="صباحي" {{ request('shift') == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                <option value="مسائي" {{ request('shift') == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                <option value="ليلي" {{ request('shift') == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="facility_name" class="form-label">**المرفق الصحي**</label>
                            <select name="facility_name" id="facility_name" class="form-select">
                                <option value="">الكل</option>
                                @php
                                    $facilities = [
                                        'صحية الجامع رجال', 'صحية الجامع نساء',
                                        'صحية 1 رجال', 'صحية 2 رجال', 'صحية 3 رجال', 'صحية 4 رجال',
                                        'صحية 1 نساء', 'صحية 2 نساء', 'صحية 3 نساء', 'صحية 4 نساء',
                                        'المجاميع الكبيرة رجال', 'المجاميع الكبيرة نساء'
                                    ];
                                @endphp
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility }}" {{ request('facility_name') == $facility ? 'selected' : '' }}>{{ $facility }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="employee_id" class="form-label">**الموظف المنفذ**</label>
                            <select name="employee_id" id="employee_id" class="form-select">
                                <option value="">الكل</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="from_date" class="form-label">**من تاريخ**</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="to_date" class="form-label">**إلى تاريخ**</label>
                            <input type="date" name="to_date" id="to_date" class="form-control"
                                value="{{ request('to_date') }}">
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
        <hr>

        {{-- قسم قائمة المهام --}}
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list me-1"></i>
                    **قائمة المهام** 📋
                </h3>
                <div class="card-tools">
                    {{-- عرض عدد المهام الإجمالي --}}
                    <span class="badge bg-primary">عدد المهام: {{ $tasks->total() }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>التاريخ
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'date', 'sort_order' => (request('sort_by') == 'date' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if (request('sort_by') == 'date' && request('sort_order') == 'asc')
                                            <i class="bi bi-sort-up"></i>
                                        @elseif(request('sort_by') == 'date' && request('sort_order') == 'desc')
                                            <i class="bi bi-sort-down"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>المرفق الصحي
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'facility_name', 'sort_order' => (request('sort_by') == 'facility_name' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if (request('sort_by') == 'facility_name' && request('sort_order') == 'asc')
                                            <i class="bi bi-sort-up"></i>
                                        @elseif(request('sort_by') == 'facility_name' && request('sort_order') == 'desc')
                                            <i class="bi bi-sort-down"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>نوع المهمة
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'task_type', 'sort_order' => (request('sort_by') == 'task_type' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if (request('sort_by') == 'task_type' && request('sort_order') == 'asc')
                                            <i class="bi bi-sort-up"></i>
                                        @elseif(request('sort_by') == 'task_type' && request('sort_order') == 'desc')
                                            <i class="bi bi-sort-down"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>الوجبة
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'shift', 'sort_order' => (request('sort_by') == 'shift' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if (request('sort_by') == 'shift' && request('sort_order') == 'asc')
                                            <i class="bi bi-sort-up"></i>
                                        @elseif(request('sort_by') == 'shift' && request('sort_order') == 'desc')
                                            <i class="bi bi-sort-down"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>الحالة
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'status', 'sort_order' => (request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if (request('sort_by') == 'status' && request('sort_order') == 'asc')
                                            <i class="bi bi-sort-up"></i>
                                        @elseif(request('sort_by') == 'status' && request('sort_order') == 'desc')
                                            <i class="bi bi-sort-down"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>ساعات العمل
                                    <a href="{{ route('sanitation-facility-tasks.index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'working_hours', 'sort_order' => (request('sort_by') == 'working_hours' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if (request('sort_by') == 'working_hours' && request('sort_order') == 'asc')
                                            <i class="bi bi-sort-up"></i>
                                        @elseif(request('sort_by') == 'working_hours' && request('sort_order') == 'desc')
                                            <i class="bi bi-sort-down"></i>
                                        @else
                                            <i class="bi bi-arrow-down-up"></i>
                                        @endif
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
                                        @switch($task->task_type)
                                            @case('إدامة')
                                                <span class="badge bg-info">إدامة</span>
                                                @break
                                            @case('صيانة')
                                                <span class="badge bg-warning">صيانة</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $task->task_type }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $task->shift }}</td>
                                    <td>
                                        @switch($task->status)
                                            @case('مكتمل')
                                                <span class="badge bg-success">مكتمل</span>
                                                @break
                                            @case('قيد التنفيذ')
                                                <span class="badge bg-warning">قيد التنفيذ</span>
                                                @break
                                            @case('ملغى')
                                                <span class="badge bg-danger">ملغى</span>
                                                @break
                                            @case('معلق')
                                                <span class="badge bg-secondary">معلق</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $task->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $task->working_hours }}</td>
                                    <td>{{ $task->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $task->editor->name ?? 'N/A' }}</td>
                                    <td>
                                        @forelse ($task->employeeTasks as $employeeTask)
                                            <div class="employee-rating-item">
                                                @php
                                                    $employeeName = $employeeTask->employee->name ?? 'غير معروف';
                                                    $rating = (int) $employeeTask->employee_rating;
                                                    $ratingText = match ($rating) {
                                                        1 => 'ضعيف',
                                                        2 => 'مقبول',
                                                        3 => 'متوسط',
                                                        4 => 'جيد',
                                                        5 => 'ممتاز',
                                                        default => 'غير مقيم',
                                                    };
                                                @endphp
                                                <div>{{ $employeeName }} (<span class="rating-text">{{ $ratingText }}</span> <span class="rating-stars">
                                                    @for ($i = 0; $i < $rating; $i++)
                                                        ★
                                                    @endfor
                                                    @for ($i = $rating; $i < 5; $i++)
                                                        ☆
                                                    @endfor
                                                </span>)</div>
                                            </div>
                                        @empty
                                            لا يوجد
                                        @endforelse
                                    </td>
                                    <td>{{ $task->relatedGoal->goal_text ?? 'N/A' }}</td>
                                    <td>{{ $task->unit->name ?? 'N/A' }}</td>
                                    <td>
                                        @include('partials.task-image-links', ['images' => $task->before_images])
                                    </td>
                                    <td>
                                        @include('partials.task-image-links', ['images' => $task->after_images])
                                    </td>
                                    <td>{{ $task->seats_count ?? 0 }}</td>
                                    <td>{{ $task->mirrors_count ?? 0 }}</td>
                                    <td>{{ $task->mixers_count ?? 0 }}</td>
                                    <td>{{ $task->doors_count ?? 0 }}</td>
                                    <td>{{ $task->sinks_count ?? 0 }}</td>
                                    <td>{{ $task->bathrooms_count ?? 0 }}</td> {{-- تم تصحيح هذا السطر بناءً على السياق --}}
                                    <td>
                                        @if($task->used_resources && is_array($task->used_resources))
                                            @forelse($task->used_resources as $resource)
                                                <span class="badge bg-secondary">{{ $resource['name'] }} ({{ $resource['quantity'] }})</span><br>
                                            @empty
                                                لا توجد موارد
                                            @endforelse
                                        @else
                                            لا توجد موارد
                                        @endif
                                    </td>
                                    <td>{{ $task->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $task->updated_at->format('Y-m-d H:i') }}</td>
                                    <td class="actions">
                                        {{-- تم حذف زر Show (عرض التفاصيل) لعدم الحاجة إليه بناءً على طلبك --}}
                                        <a href="{{ route('sanitation-facility-tasks.edit', $task->id) }}" class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('sanitation-facility-tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذه المهمة؟')" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="21" class="text-center">لا توجد مهام منشآت صحية لعرضها.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $tasks->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to handle collapsing/expanding the filter card
            const filterCard = document.querySelector('.card-primary.card-outline');
            const collapseButton = filterCard.querySelector('[data-lte-toggle="card-collapse"]');
            const plusIcon = collapseButton.querySelector('[data-lte-icon="plus"]');
            const minusIcon = collapseButton.querySelector('[data-lte-icon="minus"]');

            if (filterCard.classList.contains('collapsed-card')) {
                plusIcon.style.display = 'inline-block';
                minusIcon.style.display = 'none';
            } else {
                plusIcon.style.display = 'none';
                minusIcon.style.display = 'inline-block';
            }

            collapseButton.addEventListener('click', function() {
                if (filterCard.classList.contains('collapsed-card')) {
                    filterCard.classList.remove('collapsed-card');
                    plusIcon.style.display = 'none';
                    minusIcon.style.display = 'inline-block';
                } else {
                    filterCard.classList.add('collapsed-card');
                    plusIcon.style.display = 'inline-block';
                    minusIcon.style.display = 'none';
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    bootstrap.Alert.getInstance(alert)?.close();
                }, 5000);
            });
        });
    </script>
@endsection