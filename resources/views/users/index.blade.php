@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'إدارة المستخدمين') {{-- تعريف عنوان الصفحة --}}

@section('page_title', 'إدارة المستخدمين') {{-- تعريف عنوان الصفحة في AdminLTE Header --}}

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item active">إدارة المستخدمين</li>
@endsection

@section('content') {{-- بداية قسم المحتوى حيث سيتم حقن كود الصفحة --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">قائمة المستخدمين</h3>
                    <div class="card-tools">
                        {{-- زر إضافة مستخدم جديد - يظهر فقط إذا كان لدى المستخدم صلاحية 'create users' --}}
                       {{-- @can('create users') --}}
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> إضافة مستخدم جديد
    </a>
{{-- @endcan --}}
                    </div>
                </div>
                <div class="card-body p-0"> {{-- p-0 لإزالة الحشوة الزائدة من البطاقة حول الجدول --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive"> {{-- لجعل الجدول متجاوبًا --}}
                        <table class="table table-striped table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>{{ __('الاسم') }}</th>
                                    <th>{{ __('البريد الإلكتروني') }}</th>
                                    <th>{{ __('رقم الموظف') }}</th>
                                    <th>{{ __('المسمى الوظيفي') }}</th>
                                    <th>{{ __('القسم/الوحدة') }}</th>
                                    <th>{{ __('نشط') }}</th>
                                    <th>{{ __('الأدوار') }}</th>
                                    <th style="width: 150px;">{{ __('الإجراءات') }}</th> {{-- تحديد عرض ثابت للعمود --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->employee_id ?? '-' }}</td>
                                        <td>{{ $user->job_title ?? '-' }}</td>
                                        <td>{{ $user->unit ?? '-' }}</td>
                                        <td>
                                            @if ($user->is_active)
                                                <span class="badge badge-success">{{ __('نعم') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('لا') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @forelse ($user->getRoleNames() as $role)
                                                <span class="badge badge-info mr-1">{{ $role }}</span>
                                            @empty
                                                <span class="badge badge-secondary">{{ __('لا يوجد دور') }}</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            {{-- زر تعديل - يظهر فقط إذا كان لدى المستخدم صلاحية 'edit users' --}}
                                            @can('edit users')
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i> تعديل
                                                </a>
                                            @endcan

                                            {{-- زر حذف - يظهر فقط إذا كان لدى المستخدم صلاحية 'delete users' --}}
                                            @can('delete users')
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> حذف
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد بيانات مستخدمين لعرضها.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $users->links('vendor.pagination.bootstrap-4') }} {{-- استخدام تصميم الترقيم الخاص بـ Bootstrap 4 --}}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
