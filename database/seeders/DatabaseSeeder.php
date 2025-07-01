<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// لا حاجة لاستيراد User, Hash, Role, Permission هنا، لأنها تُستخدم داخل Seeders المُستدعاة

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class, // *** هذا هو الـ Seeder الرئيسي لإنشاء المستخدم المسؤول والأدوار والصلاحيات ***

            // ... Seeders الأخرى التي لا تعتمد على الصلاحيات أو الأدوار
            MainGoalSeeder::class,
            DepartmentGoalSeeder::class,
            UnitSeeder::class,
            UnitGoalSeeder::class,
            MonthlyGeneralCleaningSummarySeeder::class,
            MonthlySanitationSummarySeeder::class,
            EmployeeSeeder::class,
            DailyStatusSeeder::class,
            TaskSeeder::class,
            ServiceTaskSeeder::class,
            // لا تقم باستدعاء DatabaseSeeder::class هنا لتجنب الحلقة اللانهائية
            // لا تقم باستدعاء PermissionSeeder::class هنا إذا كان SuperAdminSeeder ينشئ كل الصلاحيات
        ]);

        // التعليقات الموجودة أدناه في كودك الأصلي صحيحة،
        // يجب إزالة أي كود لإنشاء مستخدم Super Admin هنا
        // إذا كان SuperAdminSeeder يقوم بذلك بالفعل.
        // $user = User::factory()->create([...]);
        // $user->assignRole('Super Admin');
    }
}
