@extends('layouts.adminlte')

@section('title', 'استبيانات رضا الزائرين')

@section('page_title', 'استبيانات رضا الزائرين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">استبيانات رضا الزائرين</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-clipboard-list mr-2"></i> سجلات استبيانات رضا الزائرين
                </h3>
                <div class="card-tools">
                    <a href="{{ route('surveys.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> إضافة استبيان جديد
                    </a>
                    <a href="{{ route('surveys.export', request()->query()) }}" class="btn btn-primary btn-sm ml-2">
                        <i class="fas fa-file-export mr-1"></i> تصدير البيانات
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

                <form method="GET" action="{{ route('surveys.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="overall_satisfaction">الرضا العام:</label>
                                <select name="overall_satisfaction" id="overall_satisfaction" class="form-control">
                                    <option value="">جميع المستويات</option>
                                    @foreach($satisfactionOptions as $key => $value)
                                        <option value="{{ $key }}" {{ request('overall_satisfaction') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="visit_count">عدد الزيارات:</label>
                                <select name="visit_count" id="visit_count" class="form-control">
                                    <option value="">جميع الأعداد</option>
                                    @foreach($visitCountOptions as $key => $value)
                                        <option value="{{ $key }}" {{ request('visit_count') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">الجنس:</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">كلا الجنسين</option>
                                    @foreach($genderOptions as $key => $value)
                                        <option value="{{ $key }}" {{ request('gender') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="age_group">الفئة العمرية:</label>
                                <select name="age_group" id="age_group" class="form-control">
                                    <option value="">جميع الفئات</option>
                                    @foreach($ageGroupOptions as $key => $value)
                                        <option value="{{ $key }}" {{ request('age_group') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="from_date">من تاريخ:</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="to_date">إلى تاريخ:</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="search">بحث:</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="ابحث برقم الاستبيان أو الملاحظات" value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter mr-1"></i> تطبيق الفلاتر</button>
                    <a href="{{ route('surveys.index') }}" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-undo mr-1"></i> إعادة تعيين</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>تاريخ الإدخال</th>
                                <th>رقم الاستبيان</th>
                                <th>الرضا العام</th>
                                <th>عدد الزيارات</th>
                                <th>مدة الإقامة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surveys as $survey)
                                <tr>
                                    <td>{{ $survey->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>📄 {{ $survey->survey_number }}</td>
                                    <td>
                                        @php
                                            $satisfactionText = match($survey->overall_satisfaction) {
                                                'very_satisfied' => 'راض جدًا',
                                                'satisfied' => 'راض',
                                                'acceptable' => 'مقبول',
                                                'dissatisfied' => 'غير راض',
                                                default => 'غير محدد',
                                            };
                                            $satisfactionColor = match($survey->overall_satisfaction) {
                                                'very_satisfied' => 'badge-success',
                                                'satisfied' => 'badge-primary',
                                                'acceptable' => 'badge-warning',
                                                'dissatisfied' => 'badge-danger',
                                                default => 'badge-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $satisfactionColor }}">{{ $satisfactionText }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $visitCountText = match($survey->visit_count) {
                                                'first_time' => 'أول مرة',
                                                '2_5_times' => '2-5 مرات',
                                                'over_5_times' => 'أكثر من 5',
                                                default => 'غير محدد',
                                            };
                                        @endphp
                                        {{ $visitCountText }}
                                    </td>
                                    <td>
                                        @php
                                            $stayDurationText = match($survey->stay_duration) {
                                                'less_1h' => '< ساعة',
                                                '2_3h' => '2-3 ساعات',
                                                '4_6h' => '4-6 ساعات',
                                                'over_6h' => '> 6 ساعات',
                                                default => 'غير محدد',
                                            };
                                        @endphp
                                        {{ $stayDurationText }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('surveys.show', $survey->id) }}" class="btn btn-sm btn-info" title="عرض"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('surveys.edit', $survey->id) }}" class="btn btn-sm btn-primary ml-1" title="تعديل"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('surveys.destroy', $survey->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاستبيان؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ml-1" title="حذف"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا توجد استبيانات لعرضها.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $surveys->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
