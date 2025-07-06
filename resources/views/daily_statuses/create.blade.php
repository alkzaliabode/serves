@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'إنشاء موقف يومي')
@section('page_title', 'إنشاء موقف يومي جديد')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('daily-statuses.index') }}">الموقف اليومي</a></li>
    <li class="breadcrumb-item active">إنشاء</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        {{-- هنا يتم تضمين محتوى النموذج --}}
        @include('daily_statuses.form')
    </div>
@endsection