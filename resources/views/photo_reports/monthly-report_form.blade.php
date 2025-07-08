{{-- resources/views/photo_reports/monthly_report_form.blade.php --}}

@extends('layouts.admin_layout') {{-- يرث تخطيط AdminLTE الخاص بك --}}

@section('title', 'إنشاء تقرير شهري مصور')

@section('page_title', '📊 إنشاء تقرير شهري مصور')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">تقرير شهري</li>
@endsection

@section('styles')
    <style>
        /* إضافة أنماطك لتتناسب مع تصميم AdminLTE Dark */
        .card {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(8px) !important;
            border-radius: 1rem !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #fff !important;
        }
        .form-control::placeholder {
            color: #ccc !important;
        }
        .form-select option {
            background-color: #343a40; /* خلفية داكنة للخيارات في الوضع الداكن */
            color: #fff;
        }
        label {
            color: #fff;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">تحديد معايير التقرير الشهري</h3>
                    </div>
                    <form action="{{ route('photo_reports.generate_monthly_report') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="month">الشهر:</label>
                                <select name="month" id="month" class="form-control" required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ (int)date('m') == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->locale('ar')->monthName }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">السنة:</label>
                                <select name="year" id="year" class="form-control" required>
                                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                        <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="unit_type">نوع الوحدة:</label>
                                <select name="unit_type" id="unit_type" class="form-control">
                                    <option value="all">جميع الوحدات</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->unit_type }}">{{ $unit->unit_type === 'cleaning' ? 'النظافة العامة' : 'المنشآت الصحية' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="task_type">نوع المهمة:</label>
                                <select name="task_type" id="task_type" class="form-control">
                                    <option value="all">جميع المهام</option>
                                    @foreach($taskTypes as $taskType)
                                        <option value="{{ $taskType }}">{{ $taskType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">توليد التقرير</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection