@extends('layouts.adminlte')

@section('title', 'إضافة نتيجة فعلية')

@section('page_title', 'إضافة نتيجة فعلية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('actual-results.index') }}">النتائج الفعلية</a></li>
    <li class="breadcrumb-item active">إضافة</li>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card card-outline card-success shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-success">
                    <i class="fas fa-plus-circle mr-2"></i> إضافة سجل نتائج فعلية جديد
                </h3>
            </div>
            <form action="{{ route('actual-results.store') }}" method="POST">
                {{-- استخدم الـ partial الذي يحتوي على الفورم --}}
                @include('actual-results._form', ['actualResult' => new \App\Models\ActualResult()])
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Custom styles for enhanced look - consistent with index page */
        body, html {
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.08) !important; /* Slightly more transparent */
            backdrop-filter: blur(10px); /* Stronger blur for glass effect */
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ecf0f1;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .card-title {
            color: #ecf0f1 !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
            font-size: 1.6rem !important; /* Larger title font */
        }
        .card-body {
            color: #ecf0f1;
            font-size: 1.1rem; /* Base font size for card body */
        }

        /* Form Group Labels */
        .form-group label {
            font-size: 1.15rem !important; /* Labels font size */
            font-weight: 600;
            color: #ecf0f1 !important; /* White color for labels */
            text-shadow: 0.5px 0.5px 1px rgba(0,0,0,0.5);
        }

        /* Form Controls (Inputs, Selects, Textareas) */
        .form-control {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #ecf0f1 !important;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
            font-size: 1.05rem !important; /* Input/select font size */
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
        }
        .form-control::placeholder {
            color: #bdc3c7;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(114, 239, 221, 0.4) !important;
            border-color: #72efdd !important;
            background-color: rgba(255, 255, 255, 0.2) !important;
        }
        .form-control option {
            background-color: #343a40; /* Dark background for options */
            color: #ecf0f1;
        }
        .form-text {
            color: rgba(255, 255, 255, 0.7) !important; /* Muted text for helper text */
            font-size: 0.95rem;
        }

        /* Buttons with glow effect */
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            font-size: 1.05rem !important; /* Button font size */
            padding: 0.7rem 1.2rem;
            font-family: 'Cairo', 'Noto Sans Arabic', sans-serif !important;
            font-weight: bold;
            text-shadow: 0.5px 0.5px 1px rgba(0,0,0,0.5);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #545b62;
            border-color: #545b62;
        }

        /* Alert messages */
        .alert {
            border-radius: 8px;
            font-size: 1.05rem;
            font-weight: bold;
            text-shadow: 0.5px 0.5px 1px rgba(0,0,0,0.5);
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2) !important;
            border-color: rgba(40, 167, 69, 0.4) !important;
            color: #28a745 !important;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2) !important;
            border-color: rgba(220, 53, 69, 0.4) !important;
            color: #dc3545 !important;
        }

        /* Gilbert Metrics Display */
        .text-primary {
            color: #007bff !important;
            text-shadow: none; /* Remove shadow for better contrast on light background */
        }
        .text-green-600 { color: #28a745 !important; }
        .text-blue-600 { color: #17a2b8 !important; }
        .text-purple-600 { color: #6f42c1 !important; }
        .text-red-600 { color: #dc3545 !important; }

        /* Ensure input text for metrics is clear */
        .form-control[disabled] {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ecf0f1 !important;
            opacity: 0.9;
            text-shadow: 0.5px 0.5px 1px rgba(0,0,0,0.5);
        }
    </style>
@endsection

@section('scripts')
    {{-- Alpine.js is included in layouts/adminlte, so no need to include here --}}
@endsection
