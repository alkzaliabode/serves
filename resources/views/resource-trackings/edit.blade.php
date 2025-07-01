@extends('layouts.adminlte')

@section('title', 'تعديل تتبع موارد')

@section('page_title', 'تعديل تتبع موارد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('resource-trackings.index') }}">تتبع الموارد</a></li>
    <li class="breadcrumb-item active">تعديل</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-edit mr-2"></i> تعديل سجل تتبع الموارد
                </h3>
            </div>
            <form action="{{ route('resource-trackings.update', $resourceTracking->id) }}" method="POST">
                {{-- استخدم الـ partial الذي يحتوي على الفورم --}}
                @include('resource-trackings._form', ['resourceTracking' => $resourceTracking])
            </form>
        </div>
    </div>
@endsection
