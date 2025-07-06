@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'أهداف الوحدات')

@section('page_title', 'أهداف الوحدات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">أهداف الوحدات</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-flag mr-2"></i> سجلات أهداف الوحدات
                </h3>
                <div class="card-tools">
                    <a href="{{ route('unit-goals.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> إضافة هدف وحدة جديد
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

                <form method="GET" action="{{ route('unit-goals.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="department_goal_id">هدف القسم:</label>
                                <select name="department_goal_id" id="department_goal_id" class="form-control">
                                    <option value="">جميع أهداف الأقسام</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_goal_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->mainGoal ? $dept->mainGoal->goal_text . ' - ' : '' }} {{ $dept->goal_text }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_id">الوحدة:</label>
                                <select name="unit_id" id="unit_id" class="form-control">
                                    <option value="">جميع الوحدات</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">بحث:</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="ابحث باسم الوحدة أو نص الهدف" value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter mr-1"></i> تطبيق الفلاتر</button>
                    <a href="{{ route('unit-goals.index') }}" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-undo mr-1"></i> إعادة تعيين الفلاتر</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>الوحدة</th>
                                <th>اسم الوحدة (المدخل)</th>
                                <th>هدف الوحدة</th>
                                <th>هدف الشعبة</th>
                                <th>الهدف الرئيسي</th>
                                <th>التاريخ</th>
                                <th>المستهدف</th>
                                <th>نسبة تحقق الهدف</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unitGoals as $goal)
                                <tr>
                                    <td>{{ $goal->unit->name ?? 'N/A' }}</td>
                                    <td>{{ $goal->unit_name }}</td>
                                    <td>{{ Str::limit($goal->goal_text, 50, '...') }}</td>
                                    <td>{{ Str::limit($goal->departmentGoal->goal_text ?? 'N/A', 40, '...') }}</td>
                                    <td>{{ Str::limit($goal->departmentGoal->mainGoal->goal_text ?? 'N/A', 40, '...') }}</td>
                                    <td>{{ $goal->date ? $goal->date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $goal->target_tasks }}</td>
                                    <td class="font-weight-bold
                                        @if ($goal->progress_percentage >= 100) text-success
                                        @elseif ($goal->progress_percentage >= 75) text-warning
                                        @else text-danger @endif">
                                        {{ $goal->progress_percentage }}%
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('unit-goals.edit', $goal->id) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('unit-goals.destroy', $goal->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الهدف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ml-1" title="حذف"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">لا توجد أهداف وحدات لعرضها.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $unitGoals->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
