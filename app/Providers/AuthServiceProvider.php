<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // أضف هذا الاستيراد إذا كنت تستخدم Gates أو ستضيف Policies

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // هنا يمكنك تعريف أي Gates (بوابات صلاحيات) أو تسجيل Policies.
        // مثال: Gate::define('edit-settings', function (User $user) { ... });
    }
}
