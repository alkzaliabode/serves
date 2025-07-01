<?php

namespace Database\Seeders;

use App\Models\DailyStatus;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Alkoumi\LaravelHijriDate\Hijri; // تأكد من تثبيت هذه المكتبة: composer require alkoumi/laravel-hijri-date

class DailyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. التأكد من وجود موظفين لربطهم بالموقف اليومي.
        // سنقوم بإنشاء موظفين افتراضيين إذا لم يكونوا موجودين.
        // ملاحظة: تأكد أن جدول 'units' موجود وأن unit_id 1 صالح، أو قم بإنشاء وحدة أولاً.
        $organizerEmployee = Employee::firstOrCreate(
            ['employee_number' => 'EMP001'],
            [
                'name' => 'منظم الموقف التجريبي',
                'email' => 'organizer@example.com',
                'job_title' => 'منظم',
                'unit_id' => 1, // استبدل بـ ID وحدة موجودة لديك
                'role' => 'مدير',
                'is_active' => true,
                'password' => bcrypt('password'),
            ]
        );

        $employee2 = Employee::firstOrCreate(
            ['employee_number' => 'EMP002'],
            [
                'name' => 'موظف الإجازات',
                'email' => 'leave.employee@example.com',
                'job_title' => 'فني',
                'unit_id' => 1, // استبدل بـ ID وحدة موجودة لديك
                'role' => 'موظف',
                'is_active' => true,
                'password' => bcrypt('password'),
            ]
        );

        $employee3 = Employee::firstOrCreate(
            ['employee_number' => 'EMP003'],
            [
                'name' => 'موظف مهام',
                'email' => 'task.employee@example.com',
                'job_title' => 'عامل',
                'unit_id' => 1, // استبدل بـ ID وحدة موجودة لديك
                'role' => 'موظف',
                'is_active' => true,
                'password' => bcrypt('password'),
            ]
        );

        // 2. تحديد بيانات تجريبية للحقول التي تستخدم JSON
        $sampleDate = now()->toDateString(); // تاريخ اليوم للموقف اليومي

        $periodicLeaves = [
            [
                'employee_id' => $employee2->id,
                'employee_number' => $employee2->employee_number,
                'employee_name' => $employee2->name,
            ],
        ];

        $annualLeaves = [
            [
                'employee_id' => $organizerEmployee->id,
                'employee_number' => $organizerEmployee->employee_number,
                'employee_name' => $organizerEmployee->name,
            ],
        ];

        $temporaryLeaves = [
            [
                'employee_id' => $employee3->id,
                'employee_number' => $employee3->employee_number,
                'employee_name' => $employee3->name,
                'from_time' => '09:00',
                'to_time' => '11:00',
            ],
        ];

        $eidLeaves = [
            [
                'eid_type' => 'eid_alfitr',
                'employee_id' => $organizerEmployee->id,
                'employee_number' => $organizerEmployee->employee_number,
                'employee_name' => $organizerEmployee->name,
            ],
        ];

        $guardRest = [
            [
                'employee_id' => $employee2->id,
                'employee_number' => $employee2->employee_number,
                'employee_name' => $employee2->name,
            ],
        ];

        $unpaidLeaves = [
             [
                'employee_id' => $employee3->id,
                'employee_number' => $employee3->employee_number,
                'employee_name' => $employee3->name,
            ],
        ];

        $absences = [
            [
                'employee_id' => $employee2->id,
                'employee_number' => $employee2->employee_number,
                'employee_name' => $employee2->name,
                'from_date' => now()->subDays(2)->toDateString(),
                'to_date' => now()->subDays(1)->toDateString(),
            ],
        ];

        $longLeaves = [
             [
                'employee_id' => $employee3->id,
                'employee_number' => $employee3->employee_number,
                'employee_name' => $employee3->name,
                'from_date' => now()->subDays(10)->toDateString(),
                'to_date' => now()->subDays(5)->toDateString(),
            ],
        ];

        $sickLeaves = [
             [
                'employee_id' => $employee2->id,
                'employee_number' => $employee2->employee_number,
                'employee_name' => $employee2->name,
                'from_date' => now()->subDays(3)->toDateString(),
                'to_date' => now()->subDays(3)->toDateString(),
            ],
        ];

        $bereavementLeaves = [
             [
                'employee_id' => $employee3->id,
                'employee_number' => $employee3->employee_number,
                'employee_name' => $employee3->name,
            ],
        ];

        $customUsages = [
            [
                'employee_id' => $organizerEmployee->id,
                'employee_number' => $organizerEmployee->employee_number,
                'employee_name' => $organizerEmployee->name,
                'usage_details' => 'اجتماع مع الإدارة العليا',
            ],
            [
                'employee_id' => $employee2->id,
                'employee_number' => $employee2->employee_number,
                'employee_name' => $employee2->name,
                'usage_details' => 'مهمة ميدانية - متابعة مشروع',
            ],
        ];

        // 3. حساب الحقول الرقمية المطلوبة
        $totalEmployeesInDB = Employee::where('is_active', 1)->count();
        $totalRequired = 86; // القيمة الافتراضية للملاك

        $shortage = $totalRequired - $totalEmployeesInDB;

        // حساب إجازات براتب (تشمل كل الإجازات المدفوعة)
        $paidLeavesCount = count($annualLeaves)
                            + count($periodicLeaves)
                            + count($sickLeaves)
                            + count($bereavementLeaves)
                            + count($eidLeaves);

        $unpaidLeavesCount = count($unpaidLeaves);
        $absencesCount = count($absences);
        // لا توجد حقول for temporaryLeavesCount, guardRestCount, customUsagesCount مباشرة في النموذج،
        // لكنها تستخدم في حساب actual_attendance.
        $temporaryLeavesCount = count($temporaryLeaves);
        $guardRestCount = count($guardRest);
        $customUsagesCount = count($customUsages);


        // الحضور الفعلي = الموجود الحالي - (جميع أنواع الإجازات والغياب واستراحة الخفر والاستخدامات المخصصة)
        $actualAttendance = $totalEmployeesInDB - ($paidLeavesCount + $unpaidLeavesCount + $absencesCount + $temporaryLeavesCount + $guardRestCount + $customUsagesCount);

        // 4. إنشاء سجل الموقف اليومي
        DailyStatus::create([
            'date' => $sampleDate,
            'hijri_date' => $this->convertToHijri($sampleDate),
            'day_name' => $this->getDayName($sampleDate),
            'periodic_leaves' => $periodicLeaves,
            'annual_leaves' => $annualLeaves,
            'temporary_leaves' => $temporaryLeaves,
            'eid_leaves' => $eidLeaves,
            'guard_rest' => $guardRest,
            'unpaid_leaves' => $unpaidLeaves,
            'absences' => $absences,
            'long_leaves' => $longLeaves,
            'sick_leaves' => $sickLeaves,
            'bereavement_leaves' => $bereavementLeaves,
            'custom_usages' => $customUsages,
            'total_required' => $totalRequired,
            'total_employees' => $totalEmployeesInDB,
            'shortage' => $shortage,
            'actual_attendance' => $actualAttendance,
            'paid_leaves_count' => $paidLeavesCount,
            'unpaid_leaves_count' => $unpaidLeavesCount,
            'absences_count' => $absencesCount,
            'organizer_employee_id' => $organizerEmployee->id,
            'organizer_employee_name' => $organizerEmployee->name,
        ]);

        $this->command->info('تم تعبئة سجل الموقف اليومي بنجاح!');
    }

    /**
     * Converts a Gregorian date to Hijri.
     * تحويل التاريخ الميلادي إلى هجري.
     *
     * @param string $gregorianDate The Gregorian date string.
     * @return string The Hijri date string.
     */
    protected function convertToHijri(string $gregorianDate): string
    {
        try {
            return Hijri::Date('j F Y', $gregorianDate);
        } catch (\Exception $e) {
            return 'تاريخ غير صالح';
        }
    }

    /**
     * Gets the Arabic name of the day for a given date.
     * الحصول على اسم اليوم بالعربية.
     *
     * @param string $date The date string.
     * @return string The Arabic day name.
     */
    protected function getDayName(string $date): string
    {
        $days = [
            'Sunday' => 'الأحد',
            'Monday' => 'الإثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت',
        ];

        try {
            $day = Carbon::parse($date)->format('l');
            return $days[$day] ?? $day;
        } catch (\Exception $e) {
            return 'يوم غير معروف';
        }
    }
}
