<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// لا حاجة لاستيراد Filament أو Str إذا لم تكن تستخدمهما هنا
// use Filament\Facades\Filament;
// use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إعادة تعيين ذاكرة التخزين المؤقت للأذونات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. إنشاء/جلب دور Super Admin
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );

        // 3. تعريف جميع الصلاحيات التي يحتاجها تطبيقك (الكونترولر والويب)
        // هذه هي الصلاحيات التي ستقوم بالتحقق منها باستخدام $user->can() أو @can
        $appPermissions = [
            // صلاحيات لوحة التحكم والتقارير العامة
            'view dashboard',
            'view reports', // صلاحية عامة لعرض التقارير
            'manage background settings',
            'view photo reports',
            'manage photo reports', // إذا كان مسموحًا بإدارة التقارير المصورة
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

            // صلاحيات الكاتب الذاتية
            'write articles',
            'edit own articles',
            'delete own articles',

            // صلاحيات مهام النظافة العامة
            'view general cleaning tasks',
            'create general cleaning tasks',
            'edit general cleaning tasks',
            'delete general cleaning tasks',

            // صلاحيات مهام المنشآت الصحية
            'view sanitation facility tasks',
            'create sanitation facility tasks',
            'edit sanitation facility tasks',
            'delete sanitation facility tasks',

            // صلاحيات الموقف اليومي
            'view daily statuses',
            'create daily statuses',
            'edit daily statuses',
            'delete daily statuses',

            // صلاحيات الموظفين
            'view employees',
            'manage employees', // تشمل (إنشاء، تعديل، حذف)

            // صلاحيات إدارة المستخدمين والأدوار (صلاحيات حساسة جداً)
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage users', // صلاحية شاملة لإدارة المستخدمين
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage roles', // صلاحية شاملة لإدارة الأدوار
        ];

        // 4. إنشاء جميع الصلاحيات في قاعدة البيانات
        foreach ($appPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 5. ربط جميع الصلاحيات المولدة بالدور Super Admin
        $superAdminRole->syncPermissions(Permission::all());
        
        // 6. إنشاء أو جلب المستخدم Rawan وتعيين دور Super Admin له
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

        if (!$rawanUser->hasRole('super_admin')) {
            $rawanUser->assignRole($superAdminRole);
        }
        $rawanUser->syncPermissions(Permission::all()); // يضمن حصوله على كل الصلاحيات

        // 7. إنشاء الأدوار الأخرى (كاتب ذاتية، مشرف نظافة عامة، مشرف منشآت صحية)
        // ودور admin_role_2 الذي كان موجودًا في كودك السابق.

        // 7.1. دور الكاتب الذاتية (Writer)
        $writerRole = Role::firstOrCreate(['name' => 'writer', 'guard_name' => 'web']);
        $writerRole->givePermissionTo([
            'view dashboard',
            'write articles',
            'edit own articles',
            'delete own articles',
            'view photo reports',
        ]);

        // 7.2. دور مشرف النظافة العامة (General Cleaning Supervisor)
        $generalCleaningSupervisorRole = Role::firstOrCreate(['name' => 'general_cleaning_supervisor', 'guard_name' => 'web']);
        $generalCleaningSupervisorRole->givePermissionTo([
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
        // ملاحظة: أذونات الحذف 'delete general cleaning tasks' يجب إضافتها فقط إذا كان هذا مسموحًا لهم.

        // 7.3. دور مشرف المنشآت الصحية (Sanitation Facility Supervisor)
        $sanitationFacilitySupervisorRole = Role::firstOrCreate(['name' => 'sanitation_facility_supervisor', 'guard_name' => 'web']);
        $sanitationFacilitySupervisorRole->givePermissionTo([
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
        // ملاحظة: أذونات الحذف 'delete sanitation facility tasks' يجب إضافتها فقط إذا كان هذا مسموحًا لهم.

        // 7.4. دور Admin Role 2 (استناداً إلى كودك السابق)
        $adminRole2 = Role::firstOrCreate(['name' => 'admin_role_2', 'guard_name' => 'web']);
        // تأكد من تحديد الصلاحيات التي يجب أن يمتلكها admin_role_2
        $adminRole2->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view general cleaning tasks', // مثال لصلاحية أخرى
            'view daily statuses', // مثال لصلاحية أخرى
            // أضف هنا فقط الصلاحيات الأخرى التي تريد أن يمتلكها admin_role_2
            // لا تضع Permission::all() هنا!
        ]);

        // 8. إنشاء أو جلب المستخدم forawildghani وتعيين الدور 'admin_role_2' له
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

        $forawildghaniUser->syncRoles([]); // إزالة جميع الأدوار السابقة
        $forawildghaniUser->syncPermissions([]); // إزالة أي صلاحيات مباشرة سابقة
        $forawildghaniUser->assignRole($adminRole2); // تعيين الدور الجديد
    }
}