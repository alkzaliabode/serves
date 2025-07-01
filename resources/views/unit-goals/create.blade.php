@extends('layouts.adminlte')

@section('title', 'إضافة هدف وحدة')

@section('page_title', 'إضافة هدف وحدة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('unit-goals.index') }}">أهداف الوحدات</a></li>
    <li class="breadcrumb-item active">إضافة</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-success shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-success">
                    <i class="fas fa-plus-circle mr-2"></i> إضافة هدف وحدة جديد
                </h3>
            </div>
            <form action="{{ route('unit-goals.store') }}" method="POST">
                {{-- استخدم الـ partial الذي يحتوي على الفورم --}}
                @include('unit-goals._form', ['unitGoal' => new \App\Models\UnitGoal()])
            </form>
        </div>
    </div>
@endsection
