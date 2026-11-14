<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Use the configured database connection for console commands as well.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        // تحميل إعدادات الخلفية فقط إذا كان جدول settings موجودًا
        if (Schema::hasTable('settings')) {
            $backgroundImageUrl = Setting::get('background_image_url');

            if ($backgroundImageUrl) {
                config(['app.background_image_url' => $backgroundImageUrl]);
            } else {
                config(['app.background_image_url' => asset('images/dashboard-background.jpg')]);
            }
        } else {
            config(['app.background_image_url' => asset('images/dashboard-background.jpg')]);
        }
    }
}
