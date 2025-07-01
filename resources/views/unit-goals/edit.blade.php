@extends('layouts.adminlte')

@section('title', 'تعديل هدف وحدة')

@section('page_title', 'تعديل هدف وحدة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('unit-goals.index') }}">أهداف الوحدات</a></li>
    <li class="breadcrumb-item active">تعديل</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-edit mr-2"></i> تعديل هدف وحدة
                </h3>
            </div>
            <form action="{{ route('unit-goals.update', $unitGoal->id) }}" method="POST">
                {{-- استخدم الـ partial الذي يحتوي على الفورم --}}
                @include('unit-goals._form', ['unitGoal' => $unitGoal])
            </form>
        </div>
    </div>
@endsection
