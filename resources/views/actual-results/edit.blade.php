@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل نتيجة فعلية')

@section('page_title', 'تعديل نتيجة فعلية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('actual-results.index') }}">النتائج الفعلية</a></li>
    <li class="breadcrumb-item active">تعديل</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-info shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-info">
                    <i class="fas fa-edit mr-2"></i> تعديل سجل النتائج الفعلية
                </h3>
            </div>
            <form action="{{ route('actual-results.update', $actualResult->id) }}" method="POST">
                {{-- استخدم الـ partial الذي يحتوي على الفورم --}}
                @include('actual-results._form', ['actualResult' => $actualResult])
            </form>
        </div>
    </div>
@endsection
