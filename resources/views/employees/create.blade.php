@extends('layouts.admin_layout') {{-- تم التعديل ليرث تخطيط admin_layout الجديد --}}

@section('title', 'إضافة موظف جديد') {{-- عنوان الصفحة في المتصفح --}}

@section('page_title', 'إضافة موظف جديد') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">الموظفين</a></li>
    <li class="breadcrumb-item active">إضافة موظف جديد</li>
@endsection

@section('content')
    {{--
        نموذج إضافة موظف جديد.
        يستخدم طريقة POST لتخزين البيانات ويعيد توجيه الطلب إلى مسار التخزين.
        يتضمن (include) النموذج المشترك 'employees.form' الذي يحتوي على حقول الإدخال والتنسيقات.
    --}}
    <form action="{{ route('employees.store') }}" method="POST">
        @csrf {{-- توكن حماية CSRF لمنع الهجمات --}}

        {{-- تضمين النموذج المشترك لمدخلات الموظف --}}
        @include('employees.form', ['units' => $units, 'roles' => $roles])
    </form>
@endsection

{{-- لا حاجة لأقسام CSS/JS هنا لأنها يتم تضمينها من خلال employees.form وملف الـ layout الرئيسي --}}
