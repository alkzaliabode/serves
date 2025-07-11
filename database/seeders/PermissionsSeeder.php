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
     * Run the permissions seeder.
     *
     * @return void
     */
    public function run()
    {
        // إعادة تعيين ذاكرة التخزين المؤقت للأدوار والصلاحيات
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. تحديد الصلاحيات:
        // Define Permissions:
        // كل ميزة أو وظيفة تتطلب تحكمًا بالوصول يجب أن تكون لها صلاحية.
        // Every feature or function requiring access control should have a permission.
        $permissions = [
            // صلاحيات إدارة المستخدمين
            // User Management Permissions
<<<<<<< HEAD
            'manage users',          // صلاحية شاملة لإدارة المستخدمين (CRUD) - Comprehensive permission for user management (CRUD)
=======
            'manage users',        // صلاحية شاملة لإدارة المستخدمين (CRUD) - Comprehensive permission for user management (CRUD)
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
            'create users',
            'edit users',
            'delete users',
            'view users',

            // صلاحيات إدارة الأدوار
            // Role Management Permissions
<<<<<<< HEAD
            'manage roles',          // صلاحية شاملة لإدارة الأدوار (CRUD) - Comprehensive permission for role management (CRUD)
=======
            'manage roles',        // صلاحية شاملة لإدارة الأدوار (CRUD) - Comprehensive permission for role management (CRUD)
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
            'create roles',
            'edit roles',
            'delete roles',
            'view roles',

            // صلاحيات إدارة الصلاحيات (نادرًا ما تُعطى إلا لـ super_admin)
            // Permission Management Permissions (rarely given except to super_admin)
<<<<<<< HEAD
            'manage permissions',    // صلاحية شاملة لإدارة الصلاحيات (CRUD) - Comprehensive permission for permission management (CRUD)
=======
            'manage permissions', // صلاحية شاملة لإدارة الصلاحيات (CRUD) - Comprehensive permission for permission management (CRUD)
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
            'create permissions',
            'edit permissions',
            'delete permissions',
            'view permissions',

            // صلاحيات لوحة التحكم
            // Dashboard Permissions
<<<<<<< HEAD
            'access dashboard',      // الوصول إلى لوحة التحكم الرئيسية - Access to the main dashboard
=======
            'access dashboard',    // الوصول إلى لوحة التحكم الرئيسية - Access to the main dashboard
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28

            // صلاحيات مهام النظافة العامة
            // General Cleaning Tasks Permissions
            'manage general cleaning tasks',
            'view general cleaning tasks',
            'create general cleaning tasks',
            'edit general cleaning tasks',
            'delete general cleaning tasks',
            'approve general cleaning tasks', // إذا كان هناك عملية موافقة - If there is an approval process

            // صلاحيات مهام المنشآت الصحية
            // Sanitation Facility Tasks Permissions
            'manage sanitation facility tasks',
            'view sanitation facility tasks',
            'create sanitation facility tasks',
            'edit sanitation facility tasks',
            'delete sanitation facility tasks',
            'approve sanitation facility tasks', // إذا كان هناك عملية موافقة - If there is an approval process

            // صلاحيات لوحة مهام الشُعبة الخدمية
            // Service Section Tasks Board Permissions
            'view service tasks board',
            'update service tasks status',

            // صلاحيات الموقف اليومي
            // Daily Status Permissions
            'view daily statuses',
            'manage daily statuses',

            // صلاحيات التقارير
            // Reports Permissions
            'view resource report',
            'view monthly cleaning report',
            'view monthly sanitation report',
            'view monthly summary', // صلاحية جديدة: عرض ملخص الحضور الشهري - New permission: view monthly attendance summary

            // صلاحيات التقارير المصورة
            // Photo Reports Permissions
            'manage photo reports',
            'view photo reports',

            // صلاحيات إعدادات الخلفية
            // Background Settings Permissions
            'manage background settings',

            // صلاحيات إدارة الأداء والتحليلات
            // Performance Management and Analytics Permissions
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
            // Notification Permissions
            'manage notifications',
            'view notifications',
        ];

        // 2. إنشاء الصلاحيات في قاعدة البيانات
        // Create Permissions in the Database
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            if ($permission->wasRecentlyCreated) {
                Log::info("الصلاحية '{$permissionName}' تم إنشاؤها بنجاح.");
            } else {
                Log::info("الصلاحية '{$permissionName}' موجودة بالفعل.");
            }
        }

        // 3. ربط الصلاحيات بالأدوار
        // Assign Permissions to Roles
        // البحث عن الأدوار التي تم إنشاؤها مسبقًا في RolesSeeder
        // Find roles previously created in RolesSeeder
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole      = Role::where('name', 'admin')->first();
        $editorRole     = Role::where('name', 'editor')->first();
        $viewerRole     = Role::where('name', 'viewer')->first();
        $employeeRole   = Role::where('name', 'employee')->first();

        // **دور Super Admin**: يمتلك جميع الصلاحيات
        // Super Admin Role: Possesses all permissions
        if ($superAdminRole) {
<<<<<<< HEAD
            $superAdminRole->syncPermissions(Permission::all()); // يمتلك جميع الصلاحيات - Possesses all permissions
            Log::info('تم منح جميع الصلاحيات لدور super_admin.');
        } else {
            Log::warning('دور super_admin غير موجود. يرجى التأكد من تشغيل RolesSeeder أولاً.');
=======
            $superAdminRole->givePermissionTo(Permission::all()); // يمتلك جميع الصلاحيات - Possesses all permissions
            Log::info('تم منح جميع الصلاحيات لدور super_admin.');
        } else {
            Log::warning('دور super_admin غير موجود.');
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
        }

        // **دور Admin**: صلاحيات واسعة ولكن ليست كل الصلاحيات (مثال: لا يدير الصلاحيات نفسها)
        // Admin Role: Broad permissions but not all (e.g., does not manage permissions themselves)
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
                'view monthly summary', // صلاحية جديدة لدور المدير - New permission for Admin role
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
<<<<<<< HEAD
            $adminRole->syncPermissions($adminPermissions); // استخدام syncPermissions
            Log::info('تم منح صلاحيات المدير (admin) لدور admin.');
        } else {
            Log::warning('دور admin غير موجود. يرجى التأكد من تشغيل RolesSeeder أولاً.');
=======
            $adminRole->givePermissionTo($adminPermissions);
            Log::info('تم منح صلاحيات المدير (admin) لدور admin.');
        } else {
            Log::warning('دور admin غير موجود.');
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
        }

        // **دور Editor**: يمكنه إنشاء وتعديل بعض المحتوى، ولكن لا يمكنه حذف أو إدارة المستخدمين/الأدوار.
        // Editor Role: Can create and edit some content, but cannot delete or manage users/roles.
        if ($editorRole) {
            $editorPermissions = [
                'access dashboard',
<<<<<<< HEAD
                'view users', // تم إرجاعها الآن بناءً على طلبك
=======
                'view users',
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
                'view general cleaning tasks', 'create general cleaning tasks', 'edit general cleaning tasks',
                'view sanitation facility tasks', 'create sanitation facility tasks', 'edit sanitation facility tasks',
                'view service tasks board', 'update service tasks status',
                'view daily statuses', 'manage daily statuses',
                'view resource report',
                'view monthly cleaning report',
                'view monthly sanitation report',
                'view photo reports',
                'view monthly summary', // صلاحية جديدة لدور المحرر - New permission for Editor role
                'view actual results',
                'view resource trackings',
                'view unit goals',
                'view gilbert triangle chart',
                'view surveys',
                'view survey statistics',
                'view notifications',
            ];
<<<<<<< HEAD
            $editorRole->syncPermissions($editorPermissions); // استخدام syncPermissions
            Log::info('تم منح صلاحيات المحرر (editor) لدور editor.');
        } else {
            Log::warning('دور editor غير موجود. يرجى التأكد من تشغيل RolesSeeder أولاً.');
=======
            $editorRole->givePermissionTo($editorPermissions);
            Log::info('تم منح صلاحيات المحرر (editor) لدور editor.');
        } else {
            Log::warning('دور editor غير موجود.');
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
        }

        // **دور Viewer**: يمكنه عرض معظم الأشياء فقط.
        // Viewer Role: Can only view most things.
        if ($viewerRole) {
            $viewerPermissions = [
                'access dashboard',
<<<<<<< HEAD
                'view users', // تم إرجاعها الآن بناءً على طلبك
=======
                'view users',
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
                'view general cleaning tasks',
                'view sanitation facility tasks',
                'view service tasks board',
                'view daily statuses',
                'view resource report',
                'view monthly cleaning report',
                'view monthly sanitation report',
                'view photo reports',
                'view monthly summary', // صلاحية جديدة لدور العارض - New permission for Viewer role
                'view actual results',
                'view resource trackings',
                'view unit goals',
                'view gilbert triangle chart',
                'view surveys',
                'view survey statistics',
                'view notifications',
            ];
<<<<<<< HEAD
            $viewerRole->syncPermissions($viewerPermissions); // استخدام syncPermissions
            Log::info('تم منح صلاحيات العرض (viewer) لدور viewer.');
        } else {
            Log::warning('دور viewer غير موجود. يرجى التأكد من تشغيل RolesSeeder أولاً.');
=======
            $viewerRole->givePermissionTo($viewerPermissions);
            Log::info('تم منح صلاحيات العرض (viewer) لدور viewer.');
        } else {
            Log::warning('دور viewer غير موجود.');
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
        }

        // **دور Employee**: صلاحيات محددة جداً للموظفين (مثال: فقط عرض مهامهم أو تسجيل الدخول)
        // Employee Role: Very specific permissions for employees (e.g., only viewing their tasks or logging in)
        if ($employeeRole) {
            $employeePermissions = [
                'access dashboard',
<<<<<<< HEAD
                'view daily statuses',       // قد يحتاج الموظف لعرض الموقف اليومي - Employee might need to view daily status
                'view service tasks board',  // قد يحتاج الموظف لعرض لوحة المهام الخاصة به - Employee might need to view their task board
                'view notifications',        // الموظف قد يحتاج لعرض الإشعارات - Employee might need to view notifications
                // أضف أي صلاحيات أخرى يحتاجها الموظف بشكل يومي
                // Add any other permissions needed by the employee on a daily basis
            ];
            $employeeRole->syncPermissions($employeePermissions); // استخدام syncPermissions
            Log::info('تم منح صلاحيات الموظف (employee) لدور employee.');
        } else {
            Log::warning('دور employee غير موجود. يرجى التأكد من تشغيل RolesSeeder أولاً.');
=======
                'view daily statuses', // قد يحتاج الموظف لعرض الموقف اليومي - Employee might need to view daily status
                'view service tasks board', // قد يحتاج الموظف لعرض لوحة المهام الخاصة به - Employee might need to view their task board
                // أضف أي صلاحيات أخرى يحتاجها الموظف بشكل يومي
                // Add any other permissions needed by the employee on a daily basis
            ];
            $employeeRole->givePermissionTo($employeePermissions);
            Log::info('تم منح صلاحيات الموظف (employee) لدور employee.');
        } else {
            Log::warning('دور employee غير موجود.');
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
        }

        Log::info('تم الانتهاء من seeding الصلاحيات وربطها بالأدوار.');
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 803da7cf45068dbc65c8c30f9c7a8aaea3f14e28
