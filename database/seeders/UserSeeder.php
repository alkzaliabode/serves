<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * تشغيل Seeders المستخدمين.
     *
     * @return void
     */
    public function run()
    {
        // التحقق مما إذا كان الدور 'super_admin' موجودًا
        $superAdminRole = Role::where('name', 'super_admin')->first();

        if (!$superAdminRole) {
            Log::error('الدور "super_admin" غير موجود. يرجى تشغيل RolesSeeder أولاً.');
            return;
        }

        // إنشاء مستخدم Super Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'], // للتحقق من وجوده بناءً على البريد الإلكتروني
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // كلمة مرور افتراضية قوية في الإنتاج!
                'employee_id' => '001',
                'job_title' => 'System Administrator',
                'unit' => 'IT Department',
                'is_active' => true,
            ]
        );

        // تعيين دور 'super_admin' للمستخدم
        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
            Log::info('تم إنشاء المستخدم admin@example.com وتم تعيين دور super_admin له.');
        } else {
            Log::info('المستخدم admin@example.com موجود بالفعل ويمتلك دور super_admin.');
        }

        Log::info('تم الانتهاء من seeding المستخدمين.');
    }
}