<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log; // لإضافة رسائل في السجل

class RolesSeeder extends Seeder
{
    /**
     * تشغيل Seeders الأدوار.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'super_admin', // لديه جميع الصلاحيات
            'admin',       // مدير عام بصلاحيات واسعة ولكن ليست كلها
            'editor',      // يمكنه إنشاء وتعديل المحتوى
            'viewer',      // يمكنه عرض المحتوى فقط
            'employee',    // للموظفين العاديين
        ];

        foreach ($roles as $roleName) {
            // التحقق مما إذا كان الدور موجودًا بالفعل قبل إنشائه لتجنب التكرار
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if ($role->wasRecentlyCreated) {
                Log::info("الدور '{$roleName}' تم إنشاؤه بنجاح.");
            } else {
                Log::info("الدور '{$roleName}' موجود بالفعل.");
            }
        }

        Log::info('تم الانتهاء من seeding الأدوار.');
    }
}