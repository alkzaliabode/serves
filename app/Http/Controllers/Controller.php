<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // ✅ تسمية الكلاس الأساسي بـ BaseController لتجنب التعارض

abstract class Controller extends BaseController // ✅ الوراثة من BaseController
{
    use AuthorizesRequests, ValidatesRequests; // ✅ إضافة خصائص التحقق والتفويض
    // يمكنك إضافة DispatchesJobs إذا كنت تستخدم الـ Jobs بشكل متكرر
    // use Illuminate\Foundation\Bus\DispatchesJobs; // إذا أردت استخدام هذا
}
