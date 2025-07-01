@extends('layouts.adminlte')

@section('title', 'النتائج الفعلية')

@section('page_title', 'النتائج الفعلية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">النتائج الفعلية</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-primary shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-clipboard-list mr-2"></i> سجلات النتائج الفعلية
                </h3>
                <div class="card-tools">
                    <a href="{{ route('actual-results.create') }}" class="btn btn-success btn-sm custom-btn-glow">
                        <i class="fas fa-plus mr-1"></i> إضافة سجل جديد
                    </a>
                    <a href="{{ route('actual-results.generate-daily') }}" class="btn btn-info btn-sm ml-2 custom-btn-glow">
                        <i class="fas fa-sync-alt mr-1"></i> توليد النتائج لليوم
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Filter and Search Section --}}
                <div class="card bg-gradient-dark shadow-sm mb-4">
                    <div class="card-header border-0">
                        <h5 class="card-title text-white"><i class="fas fa-filter mr-2"></i> تصفية وبحث النتائج</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('actual-results.index') }}">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="unit_id" class="text-white">الوحدة:</label>
                                        <select name="unit_id" id="unit_id" class="form-control custom-select">
                                            <option value="">جميع الوحدات</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="performance_level" class="text-white">مستوى الأداء:</label>
                                        <select name="performance_level" id="performance_level" class="form-control custom-select">
                                            <option value="">كل المستويات</option>
                                            @foreach($performanceLevels as $key => $value)
                                                <option value="{{ $key }}" {{ request('performance_level') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="search" class="text-white">بحث:</label>
                                        <input type="text" name="search" id="search" class="form-control custom-input" placeholder="ابحث بالوحدة أو الملاحظات" value="{{ request('search') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-sm custom-btn-glow"><i class="fas fa-filter mr-1"></i> تطبيق الفلاتر</button>
                                    <a href="{{ route('actual-results.index') }}" class="btn btn-secondary btn-sm ml-2 custom-btn-glow"><i class="fas fa-undo mr-1"></i> إعادة تعيين الفلاتر</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center custom-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>التاريخ</th>
                                <th>الوحدة</th>
                                <th>المستهدف</th>
                                <th>المكتمل</th>
                                <th>الفعالية (%)</th>
                                <th>الكفاءة (%)</th>
                                <th>الملاءمة (%)</th>
                                <th>الأداء الإجمالي (%)</th>
                                <th>الجودة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($actualResults as $result)
                                <tr class="table-row-animated">
                                    <td>{{ $result->date->format('Y-m-d') }}</td>
                                    <td>{{ $result->unit->name ?? 'N/A' }}</td>
                                    <td><span class="badge badge-info p-2 badge-text-shadow">{{ $result->unitGoal->target_tasks ?? 0 }}</span></td>
                                    <td><span class="badge badge-success p-2 badge-text-shadow">{{ $result->completed_tasks }}</span></td>
                                    <td class="font-weight-bold">
                                        <div class="progress-container">
                                            <div class="progress-bar-custom {{ \App\Http\Controllers\ActualResultController::getPerformanceColor($result->effectiveness) }}" style="width: {{ $result->effectiveness }}%;">
                                                {{ $result->effectiveness }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold">
                                        <div class="progress-container">
                                            <div class="progress-bar-custom {{ \App\Http\Controllers\ActualResultController::getPerformanceColor($result->efficiency) }}" style="width: {{ $result->efficiency }}%;">
                                                {{ $result->efficiency }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold">
                                        <div class="progress-container">
                                            <div class="progress-bar-custom {{ \App\Http\Controllers\ActualResultController::getPerformanceColor($result->relevance) }}" style="width: {{ $result->relevance }}%;">
                                                {{ $result->relevance }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold text-lg">
                                        <div class="progress-container">
                                            <div class="progress-bar-custom {{ \App\Http\Controllers\ActualResultController::getOverallPerformanceColor($result->overall_performance_score) }}" style="width: {{ $result->overall_performance_score }}%;">
                                                {{ $result->overall_performance_score }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @for ($i = 0; $i < $result->quality_rating; $i++)
                                            <i class="fas fa-star text-warning star-animation"></i>
                                        @endfor
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('actual-results.edit', $result->id) }}" class="btn btn-sm btn-primary action-btn" title="تعديل"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('actual-results.destroy', $result->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ml-1 action-btn" title="حذف"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="alert alert-warning d-inline-flex align-items-center" role="alert">
                                            <i class="fas fa-exclamation-triangle mr-2"></i> لا توجد نتائج فعلية لعرضها.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $actualResults->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Custom styles for enhanced look */
        body, html {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.08) !important; /* Slightly more transparent */
            backdrop-filter: blur(10px); /* Stronger blur for glass effect */
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ecf0f1;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .card-title {
            color: #ecf0f1 !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
            font-size: 1.6rem !important; /* Larger title font */
        }
        .card-body {
            color: #ecf0f1;
            font-size: 1.1rem; /* Base font size for card body */
        }

        /* Filter Section Styling */
        .card.bg-gradient-dark {
            background: linear-gradient(135deg, #343a40, #495057) !important; /* Darker gradient */
            border: none;
        }
        .form-group label {
            font-size: 1.15rem !important; /* Labels font size */
            font-weight: 600;
        }
        .custom-select, .custom-input {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #ecf0f1 !important;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
            font-size: 1.05rem !important; /* Input/select font size */
        }
        .custom-select option {
            background-color: #343a40; /* Dark background for options */
            color: #ecf0f1;
        }
        .custom-select:focus, .custom-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(114, 239, 221, 0.4) !important;
            border-color: #72efdd !important;
        }
        .custom-input::placeholder {
            color: #bdc3c7;
        }

        /* Buttons with glow effect */
        .custom-btn-glow {
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            font-size: 1.05rem !important; /* Button font size */
            padding: 0.7rem 1.2rem;
        }
        .custom-btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
        }
        .btn-primary.custom-btn-glow {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary.custom-btn-glow:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-info.custom-btn-glow {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info.custom-btn-glow:hover {
            background-color: #117a8b;
            border-color: #117a8b;
        }
        .btn-success.custom-btn-glow {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success.custom-btn-glow:hover {
            background-color: #1e7e34;
            border-color: #1e7e34;
        }
        .btn-secondary.custom-btn-glow {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary.custom-btn-glow:hover {
            background-color: #545b62;
            border-color: #545b62;
        }

        /* Table Styling */
        .custom-table {
            border-collapse: separate;
            border-spacing: 0 10px; /* Space between rows */
        }
        .custom-table thead th {
            background-color: #007bff; /* Primary color for header */
            border: none;
            padding: 18px 12px; /* Increased padding */
            font-size: 1.1rem !important; /* Larger header font */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 8px 8px 0 0; /* Rounded top corners */
        }
        .custom-table tbody tr {
            background-color: rgba(255, 255, 255, 0.05); /* Lighter transparent for rows */
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .custom-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Darker on hover */
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .custom-table tbody td {
            padding: 15px 12px; /* Increased padding */
            vertical-align: middle;
            border-top: none;
            border-bottom: none;
            color: #1A237E !important; /* Dark Indigo for all table cell text */
            font-size: 1.05rem !important; /* Larger cell font */
            font-weight: bold; /* Make text bold for better visibility */
            text-shadow: 0.5px 0.5px 1px rgba(255,255,255,0.5); /* Lighter shadow for dark text on light background */
        }
        .custom-table tbody tr:first-child td {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .custom-table tbody tr:last-child td {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .custom-table tbody tr:not(:last-child) {
            margin-bottom: 10px; /* Adjust margin for spacing */
        }

        /* Performance Progress Bars */
        .progress-container {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            height: 30px; /* Taller progress bar */
            overflow: hidden;
            margin: 5px auto;
            width: 95%; /* Adjust width for better look */
        }
        .progress-bar-custom {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1A237E !important; /* Dark Indigo for numbers in progress bars */
            font-weight: bold;
            font-size: 0.95rem !important; /* Larger text in progress bar */
            transition: width 0.8s ease-out; /* Smooth animation for width change */
            border-radius: 5px;
            /* Adjusted text shadow for better visibility with dark text */
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8) !important; /* Lighter shadow for dark text */
        }
        /* Colors for performance levels */
        .progress-bar-custom.text-success { background-color: #28a745; }
        .progress-bar-custom.text-warning { background-color: #ffc107; }
        .progress-bar-custom.text-info { background-color: #17a2b8; }
        .progress-bar-custom.text-danger { background-color: #dc3545; }

        /* Star Animation for Quality Rating */
        .star-animation {
            animation: pulse-star 1.5s infinite alternate;
            display: inline-block; /* Ensure animation applies correctly */
            margin: 0 2px; /* Slightly more space between stars */
            font-size: 1.2rem; /* Larger stars */
        }
        @keyframes pulse-star {
            0% { transform: scale(0.9); opacity: 0.7; }
            100% { transform: scale(1.2); opacity: 1; }
        }

        /* Empty table message */
        .alert-warning {
            background-color: rgba(255, 193, 7, 0.2) !important;
            border-color: rgba(255, 193, 7, 0.4) !important;
            color: #ffc107 !important;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1rem !important; /* Larger alert text */
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        /* Pagination Styling */
        .pagination .page-item .page-link {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #ecf0f1 !important;
            border-radius: 5px;
            margin: 0 3px;
            transition: all 0.3s ease;
            font-size: 1.05rem !important; /* Pagination font size */
        }
        .pagination .page-item .page-link:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: #72efdd !important;
            color: #72efdd !important;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff !important;
            border-color: #007bff !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.4);
        }
        .pagination .page-item.disabled .page-link {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Badge text shadow for numbers in "المستهدف" and "المكتمل" */
        .badge-text-shadow {
            color: #1A237E !important; /* Dark Indigo for badge numbers */
            text-shadow: 1px 1px 2px rgba(255,255,255,0.8) !important; /* Lighter shadow for dark text */
        }
    </style>
@endsection

@section('scripts')
    {{-- No additional scripts needed here, as the styling is purely CSS. --}}
@endsection
