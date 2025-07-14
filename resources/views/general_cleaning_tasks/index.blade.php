@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'مهام النظافة العامة')

@section('page_title', 'قائمة مهام النظافة العامة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">مهام النظافة العامة</li>
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
            font-size: 1.05rem; /* Slightly larger body text */
            line-height: 1.6; /* Improved line spacing for readability */
            color: white !important; /* لون نص أبيض لجسم البطاقة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5) !important; /* ظل خفيف للنص */
        }

        /* Titles and Headers - make them more prominent and interactive */
        .card-title,
        .card-header h3.card-title { /* Target the h3 specifically */
            font-size: 1.5rem !important; /* Larger titles */
            font-weight: 700 !important; /* Bolder */
            color: var(--accent-color) !important; /* Distinctive color for titles */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.9) !important; /* Stronger shadow */
            transition: color 0.3s ease, text-shadow 0.3s ease; /* Smooth transition */
        }
        .card-title:hover,
        .card-header h3.card-title:hover {
            color: #ffffff !important; /* Change color on hover for interactivity */
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 1.0) !important;
        }

        /* أنماط الجدول داخل البطاقة */
        .table {
            color: white !important; /* لون نص أبيض للجدول بالكامل */
        }
        .table thead th {
            background-color: rgba(0, 123, 255, 0.3) !important; /* خلفية زرقاء شفافة لرؤوس الجدول */
            color: white !important; /* لون نص أبيض لرؤوس الجدول */
            border-color: rgba(255, 255, 255, 0.3) !important; /* حدود بيضاء شفافة */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            font-size: 1.1rem !important; /* Larger text for table headers */
        }
        .table tbody td {
            border-color: rgba(255, 255, 255, 0.1) !important; /* حدود بيضاء شفافة للصفوف */
            font-size: 1.0rem; /* Slightly larger text for table cells */
            text-shadow: 1px 1px 1px rgba(0,0,0,0.5); /* Shadow for cell text */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05) !important; /* تظليل خفيف للصفوف الفردية */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.15) !important; /* تأثير تحويم أكثر وضوحاً */
        }

        /* أنماط أزرار وعناصر التحكم في الفلاتر */
        .form-control,
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
        input:focus,
        textarea:focus,
        select:focus {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.5) !important;
        }
        .form-select option {
            background-color: #2c3e50 !important;
            color: white !important;
        }

        /* أنماط الأزرار - with hover effects */
        .btn {
            font-weight: 600; /* Make button text bolder */
            padding: 0.6rem 1.2rem; /* Adjust padding for larger text */
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease; /* Add transform and box-shadow to transition */
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: white !important; /* ضمان لون النص أبيض */
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4) !important; /* ظل للأزرار */
        }
        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-2px); /* Slight lift on hover */
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.6) !important;
            filter: brightness(1.2); /* Slightly brighter on hover */
        }

        .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important; /* ضمان لون النص أبيض */
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4) !important;
        }
        .btn-success:hover {
            background-color: #218838 !important;
            border-color: #218838 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(23, 162, 184, 0.4) !important;
        }
        .btn-info:hover {
            background-color: #138496 !important;
            border-color: #138496 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #212529 !important; /* لون نص داكن لزر التحذير */
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.4) !important;
        }
        .btn-warning:hover {
            background-color: #e0a800 !important;
            border-color: #e0a800 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4) !important;
        }
        .btn-danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6) !important;
            filter: brightness(1.2);
        }

        .btn-secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.4) !important;
        }
        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.6) !important;
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
        .alert-info {
            background-color: rgba(23, 162, 184, 0.9) !important; /* خلفية زرقاء شفافة للمعلومات */
            color: white !important;
        }

        /* تحسين مظهر روابط الفرز في رؤوس الجدول - more interactive */
        th a {
            color: var(--accent-color) !important; /* لون مميز للروابط */
            text-decoration: none; /* إزالة التسطير */
            margin-left: 5px; /* مسافة بين النص والأيقونة */
            transition: color 0.3s ease, transform 0.3s ease; /* Smooth transition */
            display: inline-flex; /* Align icon and text properly */
            align-items: center;
        }
        th a:hover {
            color: #ffffff !important; /* لون أبيض عند التحويم */
            transform: translateY(-1px); /* Slight lift on hover */
        }

        /* أنماط إضافية لإزالة البياض من الطبقات الداخلية */
        .input-group,
        .input-group-append,
        .input-group-append button {
            background-color: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
        }

        /* Paginator styling - matching the theme */
        .pagination .page-item .page-link {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .pagination .page-item .page-link:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: var(--accent-color) !important;
        }
        .pagination .page-item.active .page-link {
            background-color: rgba(0, 123, 255, 0.5) !important;
            border-color: rgba(0, 123, 255, 0.7) !important;
            color: white !important;
        }
        .pagination .page-item.disabled .page-link {
            color: rgba(255, 255, 255, 0.5) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Ensure .container-fluid and .table-responsive are transparent */
        .container-fluid,
        .table-responsive {
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* Ensure any auto-generated div by Livewire/Jetstream etc. are transparent */
        .bg-white,
        .shadow.sm\:rounded-lg,
        .px-4.py-5.sm\:p-6,
        .sm\:px-6.lg\:px-8,
        .max-w-7xl.mx-auto.py-10.sm\:px-6.lg\:px-8,
        .w-full.bg-white.shadow.overflow-hidden.sm\:rounded-lg,
        .w-full.bg-gray-800.sm\:rounded-lg.shadow,
        .border-gray-200.dark\:border-gray-700,
        div[x-data] {
            background-color: transparent !important;
            box-shadow: none !important;
            border-color: transparent !important;
        }

        /* Added styles for the filter row to ensure proper spacing and alignment */
        .filter-row {
            display: flex;
            flex-wrap: wrap; /* Allows items to wrap to the next line on smaller screens */
            gap: 15px; /* Spacing between filter items */
            margin-bottom: 15px;
            align-items: flex-end; /* Aligns items to the bottom if they have different heights */
        }

        .filter-item {
            flex: 1; /* Allows items to grow and shrink */
            min-width: 180px; /* Minimum width for each filter item before wrapping */
        }

        /* Ensure form-group padding/margin works well within the filter-row */
        .form-group {
            margin-bottom: 0; /* Remove default margin-bottom if it causes issues with gap */
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">قائمة مهام النظافة العامة</h3>
                <div class="card-tools">
                    <a href="{{ route('general-cleaning-tasks.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> إنشاء مهمة جديدة
                    </a>
                </div>
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

                <form action="{{ route('general-cleaning-tasks.index') }}" method="GET" class="mb-4">
                    <div class="filter-row">
                        {{-- Global Search Input --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="search">البحث العام</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="بحث عام..." value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Date Filter --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="start_date">تاريخ البدء</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="end_date">تاريخ الانتهاء</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                        </div>

                        {{-- Shift Filter (الوجبة) --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="shift">الوجبة</label>
                                <select name="shift" id="shift" class="form-control">
                                    <option value="">كل الوجبات</option>
                                    {{-- تأكد من أن هذه القيم تتطابق تمامًا مع القيم المخزنة في قاعدة البيانات --}}
                                    <option value="صباحي" {{ request('shift') == 'صباحي' ? 'selected' : '' }}>صباحي</option>
                                    <option value="مسائي" {{ request('shift') == 'مسائي' ? 'selected' : '' }}>مسائي</option>
                                    <option value="ليلي" {{ request('shift') == 'ليلي' ? 'selected' : '' }}>ليلي</option>
                                    {{-- الأفضل أن تمرر الوجبات من المتحكم: @foreach($shifts as $s) <option value="{{ $s }}" {{ request('shift') == $s ? 'selected' : '' }}>{{ $s }}</option> @endforeach --}}
                                </select>
                            </div>
                        </div>

                        {{-- Location Filter (الموقع) --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="location">الموقع</label>
                                <select name="location" id="location" class="form-control">
                                    <option value="">كل المواقع</option>
                                    {{-- هذا الجزء يعتمد على تمرير $uniqueLocations من المتحكم، تأكد من صحة القيم --}}
                                    @foreach($uniqueLocations as $loc)
                                        <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Task Type Filter (نوع المهمة) --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="task_type">نوع المهمة</label>
                                <select name="task_type" id="task_type" class="form-control">
                                    <option value="">كل الأنواع</option>
                                    {{-- هنا يجب التأكد من القيم بشكل خاص --}}
                                    <option value="إدامة" {{ request('task_type') == 'إدامة' ? 'selected' : '' }}>إدامة</option>
                                    {{-- هذا هو السطر الذي قد يكون فيه الخطأ "ادامة وصيانة" --}}
                                    {{-- تأكد من أن القيمة في قاعدة البيانات هي "صيانة نظافة" إذا كان هذا هو ما تريد عرضه --}}
                                    <option value="صيانة " {{ request('task_type') == 'صيانة ' ? 'selected' : '' }}>صيانة </option>
                                    {{-- أضف أي أنواع أخرى تتوقعها هنا --}}
                                    {{-- الأفضل أن تمرر أنواع المهام من المتحكم: @foreach($taskTypes as $type) <option value="{{ $type }}" {{ request('task_type') == $type ? 'selected' : '' }}>{{ $type }}</option> @endforeach --}}
                                </select>
                            </div>
                        </div>

                        {{-- Status Filter (الحالة) --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="status">الحالة</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">كل الحالات</option>
                                    {{-- هنا هو مكان تصحيح "مكتملة" / "مكتمل" --}}
                                    {{-- إذا كانت القيمة المخزنة في DB هي "مكتمل" فالخيار يجب أن يكون value="مكتمل" والنص المعروض "مكتمل" --}}
                                    {{-- أما إذا كانت القيمة المخزنة "مكتملة" فالخيار يجب أن يكون value="مكتملة" والنص المعروض "مكتملة" --}}
                                    {{-- سأفترض أنك تريد "مكتملة" كقيمة ونص، ولكن تحقق من قاعدة البيانات لديك --}}
                                    <option value="قيد الانتظار" {{ request('status') == 'قيد الانتظار' ? 'selected' : '' }}>قيد الانتظار</option>
                                    <option value="قيد التنفيذ" {{ request('status') == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                                    <option value="مكتمل" {{ request('status') == 'مكتملة' ? 'selected' : '' }}>مكتملة</option> {{-- تم التأكد من الاتساق هنا --}}
                                    <option value="ملغاة" {{ request('status') == 'ملغاة' ? 'selected' : '' }}>ملغاة</option>
                                    {{-- الأفضل أن تمرر الحالات من المتحكم: @foreach($statuses as $s) <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option> @endforeach --}}
                                </select>
                            </div>
                        </div>

                        {{-- Related Goal Filter (الهدف المرتبط) --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="related_goal_id">الهدف المرتبط</label>
                                <select name="related_goal_id" id="related_goal_id" class="form-control">
                                    <option value="">كل الأهداف</option>
                                    {{-- هذا الجزء يعتمد على تمرير $goals من المتحكم، تأكد من صحة القيم --}}
                                    @foreach($goals as $goal)
                                        <option value="{{ $goal->id }}" {{ request('related_goal_id') == $goal->id ? 'selected' : '' }}>{{ $goal->goal_text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Executing Employees Filter (الموظفون المنفذون) --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="employee_id">الموظف المنفذ</label>
                                <select name="employee_id" id="employee_id" class="form-control">
                                    <option value="">كل الموظفين</option>
                                    {{-- هذا الجزء يعتمد على تمرير $employees من المتحكم، تأكد من صحة القيم --}}
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Creator Filter (المنشئ) --}}
                        <div class="filter-item">
                            <div class="form-group">
                                <label for="creator_id">المنشئ</label>
                                <select name="creator_id" id="creator_id" class="form-control">
                                    <option value="">كل المنشئين</option>
                                    {{-- هذا الجزء يعتمد على تمرير $creators من المتحكم، تأكد من صحة القيم --}}
                                    @foreach($creators as $creator)
                                        <option value="{{ $creator->id }}" {{ request('creator_id') == $creator->id ? 'selected' : '' }}>{{ $creator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Filter and Reset Buttons --}}
                        <div class="filter-item" style="align-self: flex-end;">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> تطبيق الفلاتر
                                </button>
                                <a href="{{ route('general-cleaning-tasks.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> إعادة تعيين
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>التاريخ
                                    <a href="{{ route('general-cleaning-tasks.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'date', 'sort_order' => (request('sort_by') == 'date' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'date' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'date' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                    </a>
                                </th>
                                <th>الوجبة</th>
                                <th>الموقع
                                    <a href="{{ route('general-cleaning-tasks.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'location', 'sort_order' => (request('sort_by') == 'location' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'location' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'location' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                    </a>
                                </th>
                                <th>نوع المهمة</th>
                                <th>الحالة
                                    <a href="{{ route('general-cleaning-tasks.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'status', 'sort_order' => (request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'desc' : 'asc')])) }}">
                                        @if(request('sort_by') == 'status' && request('sort_order') == 'asc') &uarr; @elseif(request('sort_by') == 'status' && request('sort_order') == 'desc') &darr; @else &harr; @endif
                                    </a>
                                </th>
                                <th>الهدف المرتبط</th>
                                <th>الموظفون المنفذون</th>
                                <th>المنشئ</th>
                                <th>المعدل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                                <tr>
                                    <td>{{ $task->date }}</td>
                                    <td>{{ $task->shift }}</td>
                                    <td>{{ $task->location }}</td>
                                    <td>{{ $task->task_type }}</td>
                                    <td>{{ $task->status }}</td>
                                    <td>{{ $task->relatedGoal->goal_text ?? 'N/A' }}</td>
                                    <td>
                                        @forelse ($task->employeeTasks as $employeeTask)
                                            {{ $employeeTask->employee->name ?? 'N/A' }} (تقييم: {{ $employeeTask->employee_rating }})<br>
                                        @empty
                                            لا يوجد موظفون
                                        @endforelse
                                    </td>
                                    <td>{{ $task->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $task->editor->name ?? 'N/A' }}</td>
                                    <td class="actions">
                                        <div class="btn-group">
                                            <a href="{{ route('general-cleaning-tasks.edit', $task) }}" class="btn btn-info btn-sm" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('general-cleaning-tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">لا توجد مهام نظافة عامة حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Optional: Include any specific JS for datepickers if not in admin_layout --}}
    {{-- <script>
        $(function () {
            // Initialize datepickers if you're using a specific library like Bootstrap Datepicker or jQuery UI Datepicker
            // $('#start_date, #end_date').datepicker({
            //     format: 'yyyy-mm-dd',
            //     autoclose: true,
            //     todayHighlight: true
            // });
        });
    </script> --}}