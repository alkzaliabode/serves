@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تفاصيل الموقف اليومي - ' . \Carbon\Carbon::parse($dailyStatus->date)->format('Y-m-d')) {{-- تحديد عنوان الصفحة في المتصفح --}}

@section('page_title', 'تفاصيل الموقف اليومي') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('daily-statuses.index') }}">الموقف اليومي</a></li>
    <li class="breadcrumb-item active">عرض التفاصيل</li>
@endsection

@section('content')
    <div class="card card-primary card-outline"> {{-- استخدام بطاقة AdminLTE --}}
        <div class="card-header">
            <h3 class="card-title">تفاصيل الموقف اليومي لـ {{ \Carbon\Carbon::parse($dailyStatus->date)->format('Y-m-d') }}</h3>
            <div class="card-tools">
                <a href="{{ route('daily-statuses.print', $dailyStatus->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-print"></i> طباعة التقرير
                </a>
                <a href="{{ route('daily-statuses.edit', $dailyStatus->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                <form action="{{ route('daily-statuses.destroy', $dailyStatus->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الموقف اليومي؟')">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($dailyStatus->date)->format('Y-m-d') }}</p>
                    <p><strong>التاريخ الهجري:</strong> {{ $dailyStatus->hijri_date }}</p>
                    <p><strong>اليوم:</strong> {{ $dailyStatus->day_name }}</p>
                    <p><strong>منظم الموقف:</strong> {{ $dailyStatus->organizer_employee_name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>الملاك:</strong> {{ $dailyStatus->total_required }}</p>
                    <p><strong>الموجود الحالي:</strong> {{ $dailyStatus->total_employees }}</p>
                    <p><strong>النقص:</strong> {{ $dailyStatus->shortage }}</p>
                    <p><strong>الحضور الفعلي:</strong> {{ $dailyStatus->actual_attendance }}</p>
                </div>
            </div>

            <hr>

            <h4>إحصائيات الإجازات والغياب</h4>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>إجازات براتب:</strong> {{ $dailyStatus->paid_leaves_count }}</p>
                    <p><strong>إجازات بدون راتب:</strong> {{ $dailyStatus->unpaid_leaves_count }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>الغياب:</strong> {{ $dailyStatus->absences_count }}</p>
                    <p><strong>استراحة خفر:</strong> {{ count($dailyStatus->guard_rest ?? []) }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>إجازات زمنية:</strong> {{ count($dailyStatus->temporary_leaves ?? []) }}</p>
                    <p><strong>إجازة وفاة:</strong> {{ count($dailyStatus->bereavement_leaves ?? []) }}</p>
                    <p><strong>إجازات طويلة:</strong> {{ count($dailyStatus->long_leaves ?? []) }}</p>
                    <p><strong>إجازات مرضية:</strong> {{ count($dailyStatus->sick_leaves ?? []) }}</p>
                    <p><strong>استخدامات مخصصة:</strong> {{ count($dailyStatus->custom_usages ?? []) }}</p>
                </div>
            </div>

            <hr>

            @if (!empty($dailyStatus->periodic_leaves))
                <h4>الإجازات الدورية</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->periodic_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->annual_leaves))
                <h4>الإجازات السنوية</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->annual_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->temporary_leaves))
                <h4>الإجازات الزمنية</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                                <th>الوقت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->temporary_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($leave['from_time'])->format('H:i') ?? '' }} -
                                    {{ \Carbon\Carbon::parse($leave['to_time'])->format('H:i') ?? '' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->eid_leaves))
                <h4>إجازات الأعياد</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>نوع العيد</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->eid_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $eidType = $leave['eid_type'] ?? '';
                                        echo match ($eidType) {
                                            'eid_alfitr' => 'عيد الفطر',
                                            'eid_aladha' => 'عيد الأضحى',
                                            'eid_algahdir' => 'عيد الغدير',
                                            default => $eidType
                                        };
                                    @endphp
                                </td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->guard_rest))
                <h4>استراحة خفر</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->guard_rest as $index => $rest)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rest['employee_name'] ?? '' }}</td>
                                <td>{{ $rest['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->unpaid_leaves))
                <h4>إجازة بدون راتب</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->unpaid_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->absences))
                <h4>الغياب</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                                <th>من تاريخ</th>
                                <th>إلى تاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->absences as $index => $absence)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $absence['employee_name'] ?? '' }}</td>
                                <td>{{ $absence['employee_number'] ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($absence['from_date'])->format('Y-m-d') ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($absence['to_date'])->format('Y-m-d') ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->long_leaves))
                <h4>الإجازات الطويلة</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                                <th>من تاريخ</th>
                                <th>إلى تاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->long_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d') ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->sick_leaves))
                <h4>الإجازات المرضية</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                                <th>من تاريخ</th>
                                <th>إلى تاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->sick_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave['from_date'])->format('Y-m-d') ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave['to_date'])->format('Y-m-d') ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->bereavement_leaves))
                <h4>إجازة الوفاة</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->bereavement_leaves as $index => $leave)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $leave['employee_name'] ?? '' }}</td>
                                <td>{{ $leave['employee_number'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if (!empty($dailyStatus->custom_usages))
                <h4>الاستخدامات المخصصة</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>الرقم الوظيفي</th>
                                <th>تفاصيل الاستخدام</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStatus->custom_usages as $index => $usage)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $usage['employee_name'] ?? '' }}</td>
                                <td>{{ $usage['employee_number'] ?? '' }}</td>
                                <td>{{ $usage['usage_details'] ?? '&mdash;' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-4 text-center">
                <a href="{{ route('daily-statuses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> العودة إلى القائمة
                </a>
            </div>
        </div>
    </div>
@endsection
