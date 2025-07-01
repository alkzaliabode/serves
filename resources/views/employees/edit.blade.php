@extends('layouts.adminlte') {{-- تأكد من أن هذا يشير إلى ملف الـ layout الصحيح لديك --}}

@section('title', 'تعديل بيانات موظف') {{-- عنوان الصفحة في المتصفح --}}

@section('page_title', 'تعديل بيانات موظف') {{-- عنوان الصفحة داخل AdminLTE Header --}}

@section('breadcrumb') {{-- Breadcrumb لـ AdminLTE --}}
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">الموظفين</a></li>
    <li class="breadcrumb-item active">تعديل بيانات موظف</li>
@endsection

@section('content')
    {{--
        نموذج تعديل بيانات الموظف.
        يستخدم طريقة PUT لتحديث البيانات ويعيد توجيه الطلب إلى مسار التحديث.
        يتضمن (include) النموذج المشترك 'employees.form' الذي يحتوي على حقول الإدخال والتنسيقات.
    --}}
    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf {{-- توكن حماية CSRF لمنع الهجمات --}}
        @method('PUT') {{-- تحديد طريقة الطلب كـ PUT لتحديث المورد --}}

        {{-- تضمين النموذج المشترك لمدخلات الموظف --}}
        @include('employees.form', ['employee' => $employee, 'units' => $units, 'roles' => $roles])
    </form>
@endsection

{{-- لا حاجة لأقسام CSS/JS هنا لأنها يتم تضمينها من خلال employees.form وملف الـ layout الرئيسي --}}
