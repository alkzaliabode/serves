@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'تعديل الموقف اليومي')
@section('page_title', 'تعديل الموقف اليومي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('daily-statuses.index') }}">الموقف اليومي</a></li>
    <li class="breadcrumb-item active">تعديل</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        {{-- هنا يتم تضمين محتوى النموذج، مع تمرير متغير $dailyStatus --}}
        @include('daily_statuses.form', ['dailyStatus' => $dailyStatus])
    </div>
@endsection