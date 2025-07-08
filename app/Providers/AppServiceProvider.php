<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // تجاوز MySQL وقت البناء باستخدام SQLite داخل الذاكرة
        if (App::environment('production') && App::runningInConsole()) {
            Config::set('database.default', 'sqlite');
            Config::set('database.connections.sqlite.database', ':memory:');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
