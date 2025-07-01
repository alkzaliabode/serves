@extends('layouts.adminlte')

@section('title', 'تعديل استبيان')

@section('page_title', 'تعديل استبيان')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('surveys.index') }}">استبيانات رضا الزائرين</a></li>
    <li class="breadcrumb-item active">تعديل</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-edit mr-2"></i> تعديل استبيان رضا زائرين
                </h3>
            </div>
            <form action="{{ route('surveys.update', $survey->id) }}" method="POST">
                {{-- استخدام الـ partial الذي يحتوي على الفورم --}}
                @include('surveys._form', ['survey' => $survey])
            </form>
        </div>
    </div>
@endsection
