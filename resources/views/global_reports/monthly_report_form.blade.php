@extends('layouts.admin_layout')

@section('title', 'إنشاء تقرير شهري عالمي')

@section('page_title', '📊 إنشاء تقرير شهري عالمي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">تقرير شهري عالمي</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0 bg-gradient-dark text-white">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-file-alt mr-2"></i> تحديد الشهر والسنة لإنشاء التقرير
                </h3>
            </div>
            <div class="card-body p-4 bg-dark-custom">
                {{-- رسائل الخطأ من الجلسة --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- نموذج تحديد الشهر والسنة --}}
                <form action="{{ route('global_reports.generate') }}" method="POST"> {{-- ✅ تم التحديث هنا --}}
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label text-white">الشهر:</label>
                            <select name="month" id="month" class="form-control bg-dark-custom text-white border-secondary">
                                @php
                                    $currentMonth = date('n');
                                    $months = [
                                        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                                        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                                        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
                                    ];
                                @endphp
                                @foreach ($months as $num => $name)
                                    <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="year" class="form-label text-white">السنة:</label>
                            <select name="year" id="year" class="form-control bg-dark-custom text-white border-secondary">
                                @php $currentYear = date('Y'); @endphp
                                @foreach ($years as $yr)
                                    <option value="{{ $yr }}" {{ $yr == $currentYear ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 💡 حقل إدخال معرفات المهام الرئيسية (اختياري) --}}
                    <div class="mb-3">
                        <label for="main_task_ids_string" class="form-label text-white">معرفات المهام الرئيسية (اختياري):</label>
                        <input type="text"
                               name="main_task_ids_string"
                               id="main_task_ids_string"
                               placeholder="مثال: 101,105,203 (أدخل المعرفات مفصولة بفاصلة)"
                               class="form-control bg-dark-custom text-white border-secondary">
                        <small class="form-text text-muted">
                            يمكنك إدخال معرفات المهام المصورة (Task IDs) التي ترغب في عرض تفاصيلها بشكل منفصل في التقرير. اترك هذا الحقل فارغاً لتضمين جميع المهام في الإحصائيات العامة فقط.
                        </small>
                    </div>

                    {{-- زر توليد التقرير --}}
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-file-pdf mr-2"></i> توليد التقرير الشهري</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection