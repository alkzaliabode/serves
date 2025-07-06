<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. الأدوار يجب أن تأتي أولاً لأن الصلاحيات والمستخدمين يعتمدون عليها.
            RolesSeeder::class,

            // 2. الصلاحيات تأتي بعد الأدوار لأنها تربط الصلاحيات بالأدوار.
            PermissionsSeeder::class,

            // 3. المستخدمين (خاصة الـ Super Admin) يأتي بعد الأدوار لأننا نُعيّن له الدور.
            UserSeeder::class,

            // 4. Seeders الأخرى التي لا تعتمد على الأدوار والصلاحيات (أو تعتمد على المستخدمين بعد إنشائهم)
            // قم بترتيب هذه الـ Seeders حسب أي تبعيات محددة بينها.
            // على سبيل المثال، إذا كانت GoalSeeder تحتاج إلى بيانات من DepartmentSeeder، ضع DepartmentSeeder أولاً.
            MainGoalSeeder::class,
            DepartmentGoalSeeder::class,
            UnitSeeder::class,
            UnitGoalSeeder::class,
            MonthlyGeneralCleaningSummarySeeder::class,
            MonthlySanitationSummarySeeder::class,
            EmployeeSeeder::class, // إذا كان الموظفين سيتم ربطهم بالمستخدمين أو الأدوار، ففكر في ترتيبهم.
            DailyStatusSeeder::class,
            TaskSeeder::class,
            ServiceTaskSeeder::class,
        ]);
    }
}