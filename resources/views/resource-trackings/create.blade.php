@extends('layouts.adminlte')

@section('title', 'إضافة تتبع موارد')

@section('page_title', 'إضافة تتبع موارد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('resource-trackings.index') }}">تتبع الموارد</a></li>
    <li class="breadcrumb-item active">إضافة</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-success shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-success">
                    <i class="fas fa-plus-circle mr-2"></i> إضافة سجل تتبع موارد جديد
                </h3>
            </div>
            <form action="{{ route('resource-trackings.store') }}" method="POST">
                {{-- استخدم الـ partial الذي يحتوي على الفورم --}}
                @include('resource-trackings._form', ['resourceTracking' => new \App\Models\ResourceTracking()])
            </form>
        </div>
    </div>
@endsection
