<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController; // ✅ استيراد AuthenticatedSessionController
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    // ✅ تم استبدال مسار تسجيل الدخول الخاص بـ Livewire بمسار Laravel قياسي
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']); // ✅ إضافة مسار POST لتقديم النموذج

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    // ✅ إضافة مسار تسجيل الخروج - تم تصحيح الخطأ هنا
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']) // ✅ تم إضافة القوس المربع ] بعد 'destroy'
        ->name('logout');
});
