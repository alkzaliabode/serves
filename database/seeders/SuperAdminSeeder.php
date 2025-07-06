<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إفراغ الكاش الخاص بالأذونات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. إعادة ضبط كل المستخدمين (ما عدا super_admin) بإزالة صلاحياتهم المباشرة وأدوارهم
        User::where('email', '!=', 'roan1@admin.com')->each(function ($user) {
            $user->syncPermissions([]); // إزالة الصلاحيات المباشرة
            $user->syncRoles([]);       // إزالة جميع الأدوار
        });

        // 3. إنشاء جميع الصلاحيات
        $permissions = [
            'view dashboard',
            'view reports',
            'manage background settings',
            'view photo reports',
            'manage photo reports',
            'view surveys',
            'manage surveys',
            'view survey statistics',
            'view gilbert chart',
            'view resource report',
            'view resource tracking',
            'manage resource tracking',
            'view unit goals',
            'manage unit goals',
            'view monthly general cleaning report',
            'view monthly sanitation report',
            'write articles',
            'edit own articles',
            'delete own articles',
            'view general cleaning tasks',
            'create general cleaning tasks',
            'edit general cleaning tasks',
            'delete general cleaning tasks',
            'view sanitation facility tasks',
            'create sanitation facility tasks',
            'edit sanitation facility tasks',
            'delete sanitation facility tasks',
            'view daily statuses',
            'create daily statuses',
            'edit daily statuses',
            'delete daily statuses',
            'view employees',
            'manage employees',
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage roles',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 4. إنشاء الأدوار وتحديد صلاحياتها

        // Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // Writer
        $writerRole = Role::firstOrCreate(['name' => 'writer', 'guard_name' => 'web']);
        $writerRole->syncPermissions([
            'view dashboard',
            'write articles',
            'edit own articles',
            'delete own articles',
            'view photo reports',
        ]);

        // General Cleaning Supervisor
        $generalCleaningRole = Role::firstOrCreate(['name' => 'general_cleaning_supervisor', 'guard_name' => 'web']);
        $generalCleaningRole->syncPermissions([
            'view dashboard',
            'view general cleaning tasks',
            'create general cleaning tasks',
            'edit general cleaning tasks',
            'view daily statuses',
            'create daily statuses',
            'edit daily statuses',
            'view employees',
            'view photo reports',
            'view monthly general cleaning report',
            'view resource report',
        ]);

        // Sanitation Facility Supervisor
        $sanitationFacilityRole = Role::firstOrCreate(['name' => 'sanitation_facility_supervisor', 'guard_name' => 'web']);
        $sanitationFacilityRole->syncPermissions([
            'view dashboard',
            'view sanitation facility tasks',
            'create sanitation facility tasks',
            'edit sanitation facility tasks',
            'view daily statuses',
            'create daily statuses',
            'edit daily statuses',
            'view employees',
            'view photo reports',
            'view monthly sanitation report',
            'view resource report',
        ]);

        // Admin Role 2
        $adminRole2 = Role::firstOrCreate(['name' => 'admin_role_2', 'guard_name' => 'web']);
        $adminRole2->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view general cleaning tasks',
            'view daily statuses',
        ]);

        // 5. إنشاء المستخدم super_admin
        $rawanUser = User::firstOrCreate(
            ['email' => 'roan1@admin.com'],
            [
                'name' => 'Rawan',
                'password' => Hash::make('1234'),
                'employee_id' => '12345',
                'job_title' => 'مدير النظام',
                'unit' => 'الإدارة العامة',
                'is_active' => true,
            ]
        );
        $rawanUser->syncRoles([$superAdminRole]);
        $rawanUser->syncPermissions(Permission::all()); // فقط هذا المستخدم لديه كل الصلاحيات مباشرة

        // 6. إنشاء المستخدم الآخر وربطه بالدور فقط
        $forawildghaniUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'forawildghani',
                'password' => Hash::make('password'),
                'employee_id' => '123332',
                'job_title' => 'عامل',
                'unit' => 'التنظيف',
                'is_active' => true,
            ]
        );
        $forawildghaniUser->syncRoles([$adminRole2]); // لا تعطه صلاحيات مباشرة
        $forawildghaniUser->syncPermissions([]); // تأكد من عدم وجود صلاحيات مباشرة له
    }
}
