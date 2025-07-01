<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission; // تأكد من استيراد فئة الصلاحية

class PermissionSeeder extends Seeder // يجب أن يكون اسم الفئة هنا PermissionSeeder
{
    /**
     * تشغيل بذور قاعدة البيانات.
     *
     * @return void
     */
    public function run()
    {
        // إذا كنت ترغب في مسح الصلاحيات الموجودة قبل إضافتها (اختياري، كن حذرًا عند الاستخدام)
        // Permission::query()->delete();

        // إنشاء الصلاحيات المتعلقة بالمستخدمين
        Permission::firstOrCreate(['name' => 'view users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete users', 'guard_name' => 'web']);

        // إنشاء الصلاحيات المتعلقة بالأدوار (إذا لم تكن موجودة بالفعل)
        Permission::firstOrCreate(['name' => 'view roles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create roles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'edit roles', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete roles', 'guard_name' => 'web']);

        // يمكنك إضافة أي صلاحيات أخرى هنا...
        Permission::firstOrCreate(['name' => 'manage roles', 'guard_name' => 'web']); // الصلاحية الشاملة للأدوار
        Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']); // الصلاحية الشاملة للمستخدمين
    }
}
