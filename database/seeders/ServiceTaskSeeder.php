<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceTask; // تأكد من استيراد الموديل
use App\Models\Employee; // تأكد من استيراد موديل الموظف إذا كان غير موجود

class ServiceTaskSeeder extends Seeder
{
    /**
     * تشغيل الـ Seeder لإنشاء مهام خدمية.
     */
    public function run(): void
    {
        // تأكد من وجود موظف بالـ ID = 1، أو قم بإنشاء واحد إذا لم يكن موجودًا.
        // مثال: إذا لم يكن هناك موظفون بعد
        if (!Employee::count()) {
            Employee::factory()->create([
                'id' => 1, // يمكنك تحديد ID يدويًا للاختبار أو تركه ليتولد تلقائيًا
                'name' => 'الموظف التجريبي',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        
        ServiceTask::create([
            'title' => 'تنظيف شامل لساحة الألعاب',
            'description' => 'تنظيف الحشائش والقمامة وتنظيم المكان.',
            // **التعديل هنا:** استخدم المفتاح (Key) بدلاً من القيمة المعروضة (Label)
            'unit' => 'GeneralCleaning', 
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => now()->addDays(3),
            'assigned_to' => 1, // تأكد أن لديك موظف بـ id = 1
        ]);

        // يمكنك إضافة المزيد من المهام هنا
        ServiceTask::create([
            'title' => 'صيانة مرافق الصرف الصحي',
            'description' => 'فحص وإصلاح أنابيب الصرف الصحي في المبنى.',
            'unit' => 'SanitationFacility', // استخدام المفتاح الصحيح
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(1),
            'assigned_to' => 1, 
        ]);
    }
}
