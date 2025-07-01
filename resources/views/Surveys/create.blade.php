@extends('layouts.adminlte')

@section('title', 'إضافة استبيان جديد')

@section('page_title', 'إضافة استبيان جديد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surveys.index') }}">استبيانات رضا الزائرين</a></li>
    <li class="breadcrumb-item active">إضافة</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-success shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-success">
                    <i class="fas fa-plus-circle mr-2"></i> إضافة استبيان رضا زائرين جديد
                </h3>
            </div>
            <form action="{{ route('surveys.store') }}" method="POST">
                {{-- استخدام الـ partial الذي يحتوي على الفورم --}}
                @include('surveys._form', ['survey' => new \App\Models\Survey()])
            </form>
        </div>
    </div>
@endsection
