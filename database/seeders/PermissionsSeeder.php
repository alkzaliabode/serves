<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionsSeeder extends Seeder
{
    /**
     * تشغيل Seeders الصلاحيات.
     *
     * @return void
     */
    public function run()
    {
        // إعادة تعيين ذاكرة التخزين المؤقت للأدوار والصلاحيات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. تحديد الصلاحيات:
        // كل ميزة أو وظيفة تتطلب تحكمًا بالوصول يجب أن تكون لها صلاحية.
        $permissions = [
            // صلاحيات إدارة المستخدمين
            'manage users',      // صلاحية شاملة لإدارة المستخدمين (CRUD)
            'create users',
            'edit users',
            'delete users',
            'view users',

            // صلاحيات إدارة الأدوار
            'manage roles',      // صلاحية شاملة لإدارة الأدوار (CRUD)
            'create roles',
            'edit roles',
            'delete roles',
            'view roles',

            // صلاحيات إدارة الصلاحيات (نادرًا ما تُعطى إلا لـ super_admin)
            'manage permissions', // صلاحية شاملة لإدارة الصلاحيات (CRUD)
            'create permissions',
            'edit permissions',
            'delete permissions',
            'view permissions',

            // صلاحيات لوحة التحكم
            'access dashboard',  // الوصول إلى لوحة التحكم الرئيسية

            // صلاحيات مهام النظافة العامة
            'manage general cleaning tasks',
            'view general cleaning tasks',
            'create general cleaning tasks',
            'edit general cleaning tasks',
            'delete general cleaning tasks',
            'approve general cleaning tasks', // إذا كان هناك عملية موافقة

            // صلاحيات مهام المنشآت الصحية
            'manage sanitation facility tasks',
            'view sanitation facility tasks',
            'create sanitation facility tasks',
            'edit sanitation facility tasks',
            'delete sanitation facility tasks',
            'approve sanitation facility tasks', // إذا كان هناك عملية موافقة

            // صلاحيات لوحة مهام الشُعبة الخدمية
            'view service tasks board',
            'update service tasks status',

            // صلاحيات الموقف اليومي
            'view daily statuses',
            'manage daily statuses',

            // صلاحيات التقارير
            'view resource report',
            'view monthly cleaning report',
            'view monthly sanitation report',

            // صلاحيات التقارير المصورة
            'manage photo reports',
            'view photo reports',

            // صلاحيات إعدادات الخلفية
            'manage background settings',

            // صلاحيات إدارة الأداء والتحليلات
            'view actual results',
            'manage actual results',
            'view resource trackings',
            'manage resource trackings',
            'view unit goals',
            'manage unit goals',
            'view gilbert triangle chart',
            'view surveys',
            'manage surveys',
            'view survey statistics',

            // صلاحيات الإشعارات
            'manage notifications',
            'view notifications',
        ];

        // 2. إنشاء الصلاحيات في قاعدة البيانات
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            if ($permission->wasRecentlyCreated) {
                Log::info("الصلاحية '{$permissionName}' تم إنشاؤها بنجاح.");
            } else {
                Log::info("الصلاحية '{$permissionName}' موجودة بالفعل.");
            }
        }

        // 3. ربط الصلاحيات بالأدوار
        // البحث عن الأدوار التي تم إنشاؤها مسبقًا في RolesSeeder
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole      = Role::where('name', 'admin')->first();
        $editorRole     = Role::where('name', 'editor')->first();
        $viewerRole     = Role::where('name', 'viewer')->first();
        $employeeRole   = Role::where('name', 'employee')->first();

        // **دور Super Admin**: يمتلك جميع الصلاحيات
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo(Permission::all()); // يمتلك جميع الصلاحيات
            Log::info('تم منح جميع الصلاحيات لدور super_admin.');
        } else {
            Log::warning('دور super_admin غير موجود.');
        }

        // **دور Admin**: صلاحيات واسعة ولكن ليست كل الصلاحيات (مثال: لا يدير الصلاحيات نفسها)
        if ($adminRole) {
            $adminPermissions = [
                'access dashboard',
                'manage users', 'view users', 'create users', 'edit users', 'delete users',
                'manage roles', 'view roles', 'create roles', 'edit roles', 'delete roles',
                'manage general cleaning tasks', 'view general cleaning tasks', 'create general cleaning tasks', 'edit general cleaning tasks', 'delete general cleaning tasks', 'approve general cleaning tasks',
                'manage sanitation facility tasks', 'view sanitation facility tasks', 'create sanitation facility tasks', 'edit sanitation facility tasks', 'delete sanitation facility tasks', 'approve sanitation facility tasks',
                'view service tasks board', 'update service tasks status',
                'view daily statuses', 'manage daily statuses',
                'view resource report',
                'view monthly cleaning report',
                'view monthly sanitation report',
                'manage photo reports', 'view photo reports',
                'manage background settings',
                'view actual results', 'manage actual results',
                'view resource trackings', 'manage resource trackings',
                'view unit goals', 'manage unit goals',
                'view gilbert triangle chart',
                'view surveys', 'manage surveys',
                'view survey statistics',
                'manage notifications', 'view notifications',
            ];
            $adminRole->givePermissionTo($adminPermissions);
            Log::info('تم منح صلاحيات المدير (admin) لدور admin.');
        } else {
            Log::warning('دور admin غير موجود.');
        }

        // **دور Editor**: يمكنه إنشاء وتعديل بعض المحتوى، ولكن لا يمكنه حذف أو إدارة المستخدمين/الأدوار.
        if ($editorRole) {
            $editorPermissions = [
                'access dashboard',
                'view users',
                'view general cleaning tasks', 'create general cleaning tasks', 'edit general cleaning tasks',
                'view sanitation facility tasks', 'create sanitation facility tasks', 'edit sanitation facility tasks',
                'view service tasks board', 'update service tasks status',
                'view daily statuses', 'manage daily statuses',
                'view resource report',
                'view monthly cleaning report',
                'view monthly sanitation report',
                'view photo reports',
                'view actual results',
                'view resource trackings',
                'view unit goals',
                'view gilbert triangle chart',
                'view surveys',
                'view survey statistics',
                'view notifications',
            ];
            $editorRole->givePermissionTo($editorPermissions);
            Log::info('تم منح صلاحيات المحرر (editor) لدور editor.');
        } else {
            Log::warning('دور editor غير موجود.');
        }

        // **دور Viewer**: يمكنه عرض معظم الأشياء فقط.
        if ($viewerRole) {
            $viewerPermissions = [
                'access dashboard',
                'view users',
                'view general cleaning tasks',
                'view sanitation facility tasks',
                'view service tasks board',
                'view daily statuses',
                'view resource report',
                'view monthly cleaning report',
                'view monthly sanitation report',
                'view photo reports',
                'view actual results',
                'view resource trackings',
                'view unit goals',
                'view gilbert triangle chart',
                'view surveys',
                'view survey statistics',
                'view notifications',
            ];
            $viewerRole->givePermissionTo($viewerPermissions);
            Log::info('تم منح صلاحيات العرض (viewer) لدور viewer.');
        } else {
            Log::warning('دور viewer غير موجود.');
        }

        // **دور Employee**: صلاحيات محددة جداً للموظفين (مثال: فقط عرض مهامهم أو تسجيل الدخول)
        if ($employeeRole) {
            $employeePermissions = [
                'access dashboard',
                'view daily statuses', // قد يحتاج الموظف لعرض الموقف اليومي
                'view service tasks board', // قد يحتاج الموظف لعرض لوحة المهام الخاصة به
                // أضف أي صلاحيات أخرى يحتاجها الموظف بشكل يومي
            ];
            $employeeRole->givePermissionTo($employeePermissions);
            Log::info('تم منح صلاحيات الموظف (employee) لدور employee.');
        } else {
            Log::warning('دور employee غير موجود.');
        }

        Log::info('تم الانتهاء من seeding الصلاحيات وربطها بالأدوار.');
    }
}