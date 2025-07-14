@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'أهداف الوحدات')

@section('page_title', 'أهداف الوحدات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">أهداف الوحدات</li>
@endsection

@section('content')
    <div class="container-fluid py-4">

        {{-- قسم الهدف الاستراتيجي العام (الهدف الرئيسي للمدينة) --}}
        @isset($mainGoal)
            <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="col-12">
                    <div class="card main-goal-banner text-white shadow-lg border-0 rounded-xl overflow-hidden animate__animated animate__fadeInDown"
                        style="background: linear-gradient(135deg, #0A1931 0%, #1A4D6F 100%);">
                        <div
                            class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-center justify-content-center text-center text-md-center main-goal-content">
                            <div class="icon-wrapper main-goal-icon-left mr-md-4 mb-3 mb-md-0" id="mainGoalIcon">
                                <i class="fas fa-bullseye display-1 text-white-75 animate__animated animate__heartBeat animate__infinite" style="--animate-duration: 3s;"></i>
                            </div>
                            <div class="text-content">
                                <h2 class="display-4 font-weight-bold text-white-glow mb-2 animate__animated animate__fadeInUp">
                                    هدف مدينة الامام الحسين عليه السلام
                                </h2>
                                <p class="lead text-white-75 mb-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                                    {{ $mainGoal->goal_text ?? 'تقديم افضل الخدمات كما ونوعا' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endisset

        {{-- مؤشرات الأداء الرئيسية (KPIs) --}}
        <div class="row mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info hover-lift-effect">
                    <div class="inner">
                        <h3>{{ $totalGoals ?? 0 }}</h3>
                        <p>إجمالي الأهداف</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-flag"></i>
                    </div>
                    <a href="{{ route('unit-goals.index') }}" class="small-box-footer">
                        عرض الكل <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success hover-lift-effect">
                    <div class="inner">
                        <h3>{{ $completedGoalsCount ?? 0 }}</h3>
                        <p>أهداف مكتملة</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-check-circle"></i>
                    </div>
                    <a href="{{ route('unit-goals.index', ['progress_status' => 'completed']) }}" class="small-box-footer">
                        عرض المكتملة <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning hover-lift-effect">
                    <div class="inner">
                        <h3>{{ $inProgressGoalsCount ?? 0 }}</h3>
                        <p>أهداف قيد التقدم</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-loop"></i>
                    </div>
                    <a href="{{ route('unit-goals.index', ['progress_status' => 'in_progress']) }}" class="small-box-footer">
                        عرض قيد التقدم <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger hover-lift-effect">
                    <div class="inner">
                        <h3>{{ $overdueGoalsCount ?? 0 }}</h3>
                        <p>أهداف متأخرة</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-clock"></i>
                    </div>
                    <a href="{{ route('unit-goals.index', ['progress_status' => 'overdue']) }}" class="small-box-footer">
                        عرض المتأخرة <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- الرسوم البيانية --}}
        <div class="row mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="col-md-6">
                <div class="card card-primary card-outline shadow-lg animated-card">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold text-primary">
                            <i class="fas fa-chart-bar mr-2 card-icon"></i> نسبة إنجاز الأهداف الإجمالية
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="overallProgressChart" style="height:250px; min-height:250px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-info card-outline shadow-lg animated-card">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold text-info">
                            <i class="fas fa-chart-pie mr-2 card-icon"></i> الأهداف حسب الحالة
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="goalStatusChart" style="height:250px; min-height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- قسم أهداف الأقسام (الشعب) --}}
        @isset($departmentGoals)
            <div class="row mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="col-12">
                    <div class="card card-outline card-secondary shadow-lg rounded-xl">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bold text-secondary">
                                <i class="fas fa-sitemap mr-2"></i> أهداف الأقسام (الشعب)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                @forelse($departmentGoals as $deptGoal)
                                    <div class="col mb-4">
                                        <div class="card h-100 shadow-md border-0 animated-card hover-lift-effect"
                                             style="background: linear-gradient(45deg, #eceff1 0%, #cfd8dc 100%);">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title font-weight-bold mb-3 text-dark">
                                                    <i class="fas fa-folder-open mr-2 card-icon"></i>
                                                    {{ $deptGoal->goal_text }}
                                                </h5>
                                                <p class="card-text text-muted small">
                                                    الهدف الرئيسي: {{ $deptGoal->mainGoal->goal_text ?? 'N/A' }}
                                                </p>
                                                {{-- يمكن إضافة شريط التقدم هنا إذا كان لديك حقل 'overall_progress_percentage' في DepartmentGoal --}}
                                                @if (isset($deptGoal->overall_progress_percentage))
                                                <div class="mt-auto pt-3">
                                                    <div class="progress progress-sm mb-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                             style="width: {{ $deptGoal->overall_progress_percentage }}%"
                                                             aria-valuenow="{{ $deptGoal->overall_progress_percentage }}"
                                                             aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p class="text-sm text-dark font-weight-bold mb-0">
                                                        التقدم الإجمالي: {{ $deptGoal->overall_progress_percentage }}%
                                                    </p>
                                                </div>
                                                @endif
                                                <a href="{{ route('unit-goals.index', ['department_goal_id' => $deptGoal->id]) }}"
                                                   class="btn btn-sm btn-outline-secondary mt-3">
                                                    عرض أهداف الوحدة <i class="fas fa-arrow-circle-right ml-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p class="text-center text-muted">لا توجد أهداف أقسام لعرضها.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endisset

        {{-- قسم سجلات أهداف الوحدات --}}
        <div class="card card-outline card-info shadow-lg rounded-xl" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-flag mr-2"></i> سجلات أهداف الوحدات
                </h3>
                <div class="card-tools">
                    <a href="{{ route('unit-goals.create') }}" class="btn btn-success btn-sm animated-card hover-lift-effect">
                        <i class="fas fa-plus mr-1"></i> إضافة هدف وحدة جديد
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                {{-- رسائل التنبيه --}}
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

                {{-- فلاتر البحث --}}
                <form method="GET" action="{{ route('unit-goals.index') }}" class="mb-4 p-3 border rounded shadow-sm bg-light">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="department_goal_id"><i class="fas fa-sitemap mr-1"></i> هدف القسم:</label>
                                <select name="department_goal_id" id="department_goal_id" class="form-control form-control-sm">
                                    <option value="">جميع أهداف الأقسام</option>
                                    @foreach($departmentGoalsForFilter as $id => $text)
                                        <option value="{{ $id }}" {{ request('department_goal_id') == $id ? 'selected' : '' }}>
                                            {{ $text }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_id"><i class="fas fa-building mr-1"></i> الوحدة:</label>
                                <select name="unit_id" id="unit_id" class="form-control form-control-sm">
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
                                <label for="search"><i class="fas fa-search mr-1"></i> بحث:</label>
                                <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="ابحث باسم الوحدة أو نص الهدف" value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter mr-1"></i> تطبيق الفلاتر</button>
                    <a href="{{ route('unit-goals.index') }}" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-undo mr-1"></i> إعادة تعيين الفلاتر</a>
                </form>

                {{-- جدول الأهداف --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center animated-table">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>الوحدة</th>
                                <th>اسم الوحدة (المدخل)</th>
                                <th>هدف الوحدة</th>
                                <th>التاريخ</th>
                                <th>المستهدف</th>
                                <th>نسبة تحقق الهدف</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $currentDepartmentGoalId = null; @endphp
                            @forelse($unitGoals as $goal)
                                @if ($goal->department_goal_id !== $currentDepartmentGoalId)
                                    @php $currentDepartmentGoalId = $goal->department_goal_id; @endphp
                                    <tr class="table-active animated-row">
                                        <td colspan="7" class="text-left font-weight-bold" style="background-color: #e9ecef; border-bottom: 2px solid #adb5bd;">
                                            <i class="fas fa-sitemap mr-2 text-primary"></i>
                                            هدف القسم: {{ $goal->departmentGoal->goal_text ?? 'N/A' }}
                                            @if ($goal->departmentGoal && $goal->departmentGoal->mainGoal)
                                                <span class="text-muted ml-3">- (الهدف الرئيسي: {{ $goal->departmentGoal->mainGoal->goal_text }})</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr class="animated-row">
                                    <td>{{ $goal->unit->name ?? 'N/A' }}</td>
                                    <td>{{ $goal->unit_name }}</td>
                                    <td title="{{ $goal->goal_text }}">{{ Str::limit($goal->goal_text, 50, '...') }}</td>
                                    <td>{{ $goal->date ? $goal->date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $goal->target_tasks }}</td>
                                    <td class="font-weight-bold">
                                        <div class="progress progress-xs active">
                                            <div class="progress-bar
                                                @if ($goal->progress_percentage >= 100) bg-success
                                                @elseif ($goal->progress_percentage >= 75) bg-warning
                                                @else bg-danger @endif"
                                                role="progressbar"
                                                style="width: {{ $goal->progress_percentage }}%"
                                                aria-valuenow="{{ $goal->progress_percentage }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="ml-2">{{ $goal->progress_percentage }}%</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('unit-goals.edit', $goal->id) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('unit-goals.destroy', $goal->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الهدف؟')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ml-1" title="حذف"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد أهداف وحدات لعرضها.</td>
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

@push('scripts')
{{-- كود إخفاء رسائل التنبيه تلقائيًا --}}
<script>
    $(document).ready(function(){
        setTimeout(function() {
            $(".alert").alert('close');
        }, 5000);

        // Animations for cards
        $('[data-aos]').each(function() {
            $(this).addClass('aos-init aos-animate'); // Simulate AOS initialization if AOS.js is not loaded
        });

        $('.hover-lift-effect').hover(
            function() {
                $(this).css('transform', 'translateY(-5px)');
                $(this).css('transition', 'transform 0.3s ease');
            },
            function() {
                $(this).css('transform', 'translateY(0)');
            }
        );

        $('.animated-card').hover(
            function() {
                $(this).find('.card-title').addClass('animate__animated animate__swing');
                $(this).find('.card-icon').addClass('animate__animated animate__bounce');
            },
            function() {
                $(this).find('.card-title').removeClass('animate__animated animate__swing');
                $(this).find('.card-icon').removeClass('animate__animated animate__bounce');
            }
        );

        // Animate progress bars on load
        $('.progress-bar').each(function() {
            var progress = $(this).attr('aria-valuenow');
            $(this).css('width', 0).animate({
                width: progress + '%'
            }, 1500, 'easeOutQuint'); // Make sure you have jQuery UI for easeOutQuint or use 'swing'
        });
    });
</script>

{{-- تضمين Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const overallProgressData = {
            labels: ['إجمالي التقدم'],
            datasets: [{
                label: 'نسبة الإنجاز',
                data: [{{ $overallProgressPercentage ?? 0 }}],
                backgroundColor: ['#007bff'],
                borderColor: ['#007bff'],
                borderWidth: 1
            }]
        };

        const goalStatusData = {
            labels: ['مكتمل', 'قيد التقدم', 'متأخر', 'لم يبدأ'],
            datasets: [{
                data: [
                    {{ $completedGoalsCount ?? 0 }},
                    {{ $inProgressGoalsCount ?? 0 }},
                    {{ $overdueGoalsCount ?? 0 }},
                    {{ $notStartedGoalsCount ?? 0 }}
                ],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d'],
                hoverOffset: 4
            }]
        };

        const overallProgressCtx = document.getElementById('overallProgressChart').getContext('2d');
        new Chart(overallProgressCtx, {
            type: 'bar',
            data: overallProgressData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const goalStatusCtx = document.getElementById('goalStatusChart').getContext('2d');
        new Chart(goalStatusCtx, {
            type: 'doughnut',
            data: goalStatusData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endpush
