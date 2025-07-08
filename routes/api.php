<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Alkoumi\LaravelHijriDate\Hijri; // تأكد من أن هذه المكتبة مثبتة: composer require alkoumi/laravel-hijri-date

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// مسار API لتحويل التاريخ الميلادي إلى هجري
Route::get('/convert-to-hijri', function (Request $request) {
    // استلام التاريخ الميلادي من معلمة الاستعلام 'date'
    $date = $request->query('date');

    // التحقق مما إذا كان التاريخ قد تم توفيره
    if ($date) {
        try {
            // استخدام مكتبة Alkoumi\LaravelHijriDate لتحويل التاريخ
            // 'j F Y' هو تنسيق لعرض اليوم والشهر بالاسم والسنة الهجرية (مثال: 10 محرم 1445)
            $hijriDate = Hijri::Date('j F Y', $date);
            // إرجاع التاريخ الهجري كاستجابة JSON
            return response()->json(['hijri_date' => $hijriDate]);
        } catch (\Exception $e) {
            // التعامل مع الأخطاء إذا كان التاريخ غير صالح
            // يمكن تسجيل الخطأ في سجلات Laravel لأغراض التصحيح
            \Log::error("Error converting date to Hijri: " . $e->getMessage(), ['date' => $date]);
            // إرجاع رسالة خطأ مناسبة للعميل
            return response()->json(['hijri_date' => 'تاريخ غير صالح', 'error' => $e->getMessage()], 500);
        }
    }
    // إرجاع استجابة خطأ إذا لم يتم توفير التاريخ
    return response()->json(['hijri_date' => 'لم يتم توفير التاريخ', 'error' => 'No date provided'], 400);
});

// أمثلة لمسارات API أخرى (يمكنك إزالتها أو تعديلها حسب الحاجة)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
