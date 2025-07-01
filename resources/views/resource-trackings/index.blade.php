@extends('layouts.adminlte')

@section('title', 'تتبع الموارد')

@section('page_title', 'تتبع الموارد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">تتبع الموارد</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-clipboard-list mr-2"></i> سجلات تتبع الموارد
                </h3>
                <div class="card-tools">
                    <a href="{{ route('resource-trackings.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> إضافة سجل جديد
                    </a>
                    <a href="{{ route('resource-trackings.generate-daily') }}" class="btn btn-info btn-sm ml-2">
                        <i class="fas fa-sync-alt mr-1"></i> توليد بيانات موارد لليوم
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

                <form method="GET" action="{{ route('resource-trackings.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">بحث:</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="ابحث بالوحدة أو الملاحظات" value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter mr-1"></i> تطبيق الفلاتر</button>
                    <a href="{{ route('resource-trackings.index') }}" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-undo mr-1"></i> إعادة تعيين</a>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>التاريخ</th>
                                <th>الوحدة</th>
                                <th>ساعات العمل</th>
                                <th>إجمالي المستلزمات والمعدات (نقاط)</th> {{-- العمود الجديد المدمج --}}
                                <th>الكفاءة (مهمة/ساعة)</th>
                                <th>كفء؟</th>
                                <th>ملاحظات</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resourceTrackings as $tracking)
                                <tr>
                                    <td>{{ $tracking->date->format('Y-m-d') }}</td>
                                    <td>{{ $tracking->unit->name ?? 'N/A' }}</td>
                                    <td>{{ $tracking->working_hours }}</td>
                                    <td>{{ $tracking->total_supplies_and_tools_score }}</td> {{-- عرض العمود الجديد --}}
                                    <td>{{ $tracking->efficiency }}</td>
                                    <td>
                                        @if ($tracking->working_hours > 0 && $tracking->total_supplies_and_tools_score > 0) {{-- تم تعديل الشرط --}}
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($tracking->notes, 50, '...') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('resource-trackings.edit', $tracking->id) }}" class="btn btn-sm btn-primary" title="تعديل"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('resource-trackings.destroy', $tracking->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger ml-1" title="حذف"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد بيانات تتبع موارد لعرضها.</td> {{-- تم تعديل colspan --}}
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $resourceTrackings->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
