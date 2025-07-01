<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting; // استيراد نموذج Setting
use Illuminate\Support\Facades\Schema; // استيراد الواجهة Schema للتحقق من وجود الجدول

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // تحميل إعدادات الخلفية من قاعدة البيانات إلى إعدادات التطبيق (config)
        // يتم التحقق من وجود جدول 'settings' لتجنب الأخطاء أثناء تشغيل 'php artisan migrate' لأول مرة.
        if (Schema::hasTable('settings')) {
            $backgroundImageUrl = Setting::get('background_image_url'); // استخدام الدالة المساعدة get من نموذج Setting

            // إذا كان هناك مسار مخزن للخلفية، قم بتعيينه في إعدادات التطبيق
            if ($backgroundImageUrl) {
                config(['app.background_image_url' => $backgroundImageUrl]);
            } else {
                // إذا لم يتم العثور على مسار في قاعدة البيانات، استخدم المسار الافتراضي من asset
                config(['app.background_image_url' => asset('images/dashboard-background.jpg')]);
            }
        } else {
            // إذا كان جدول الإعدادات غير موجود بعد (مثلاً أثناء عملية الترحيل)، استخدم المسار الافتراضي مباشرة.
            config(['app.background_image_url' => asset('images/dashboard-background.jpg')]);
        }
    }
}
