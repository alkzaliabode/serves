<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitGoal;
use App\Models\DepartmentGoal;
use App\Models\Unit;
use Illuminate\Support\Carbon; // إضافة Carbon لاستخدام now()

class UnitGoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // البحث عن أول هدف قسم موجود. يمكنك تعديل هذا إذا كان لديك أهداف أقسام محددة.
        $departmentGoal = DepartmentGoal::first();

        if (!$departmentGoal) {
            $this->command->info('No DepartmentGoal found. Skipping UnitGoal seeding.');
            return;
        }

        $departmentGoalId = $departmentGoal->id;

        // تعريف أهداف الوحدات مع قيم target_tasks بناءً على وصفك
        $unitGoalsData = [
            'النظافة العامة' => [
                [
                    'goal_text' => 'ضمان تنظيف قاعة واحدة يوميًا (ما يعادل 7 قاعات أسبوعيًا) بنسبة إنجاز 100%، مع تطبيق الإدامة العميقة للطابقين العلوي والسفلي وفقًا للخطة الأسبوعية المعتمدة بنسبة التزام 95%.',
                    'target_tasks' => 1, // مهمة إدامة عميقة لقاعة واحدة يومياً
                ],
                [
                    'goal_text' => 'إدامة وتعقيم جميع قاعات المبيت بنسبة 100% خلال ساعة واحدة من مغادرة الزوار، وتنفيذ التعقيم الكامل بـ 100% من القاعات المتبقية مرتين أسبوعيًا باستخدام المعقمات المعتمدة.',
                    'target_tasks' => 1, // مهمة تنظيف سريع لجميع قاعات المبيت يومياً
                ],
                [
                    'goal_text' => 'كنس وغسل جميع الساحات العامة 3 مرات يوميًا ضمن وجبات العمل (صباحية من 7 ص إلى 2 م، مسائية من 2 م إلى 9 م، ليلية من 9 ص إلى 7 ص) بنسبة التزام لا تقل عن 95%، وإزالة الأوساخ والمخلفات من جميع النقاط المحددة كل ساعتين على مدار 24 ساعة بنسبة لا تقل عن 98%.',
                    'target_tasks' => 3, // 3 دورات كنس وغسل يومياً
                ],
                [
                    'goal_text' => 'رفع 100% من الحاويات من جميع النقاط المحددة كل 6 ساعات على مدار 24 ساعة يوميًا، وغسل وتعقيم 100% منها يوميًا بعد عملية التفريغ الأخيرة.',
                    'target_tasks' => 4, // 4 دورات رفع حاويات يومياً (24 ساعة / 6 ساعات)
                ],
                [
                    'goal_text' => 'فرش 100% من السجاد في القاعات والساحات المخصصة قبل 30 دقيقة من الفعاليات المجدولة أو عند الحاجة، والتأكد من نظافة وتعقيم 100% من السجاد قبل وبعد كل استخدام أو فعالية.',
                    'target_tasks' => 1, // عملية فرش السجاد اليومية
                ],
                [
                    'goal_text' => 'تعبئة 100% من الترامز بالماء الصالح للشرب كل 4 ساعات أو فورًا عند انخفاض المستوى إلى أقل من 20%، وإجراء فحص يومي لـ 100% من الترامز للتأكد من نظافتها وصلاحية المياه داخلها.',
                    'target_tasks' => 6, // 6 دورات تعبئة ترامز يومياً (24 ساعة / 4 ساعات)
                ],
            ],
            'المنشآت الصحية' => [
                [
                    'goal_text' => 'تنظيف وتعقيم 100% من الحمامات كل ساعتين خلال ساعات العمل الرسمية (7 صباحًا - 10 مساءً)، وإعادة تعبئة جميع المواد الصحية (مثل الصابون والزاهي  ) عند وصولها إلى 25% من سعتها القصوى مع توفر مخزون بنسبة 99%.',
                    'target_tasks' => 60, // 8 مباني * (15 ساعة عمل / 2 ساعة لكل دورة) = 60 دورة تنظيف يومياً
                ],
                [
                    'goal_text' => 'إجراء صيانة وقائية دورية لـ 100% من السيفونات والمغاسل والمرايا في جميع الحمامات مرة واحدة شهريًا، وإصلاح 95% من الأعطال المبلغ عنها خلال 4 ساعات كحد أقصى من وقت الإبلاغ.',
                    'target_tasks' => 5, // متوسط يومي لمهام الإصلاح (يمكن تعديله حسب التوقعات)
                ],
            ],
        ];

        foreach ($unitGoalsData as $unitName => $goals) {
            $unit = Unit::where('name', $unitName)->first();

            if (!$unit) {
                $this->command->warn("Unit with name '{$unitName}' not found. Skipping goals for this unit.");
                continue;
            }

            foreach ($goals as $goal) {
                UnitGoal::create([
                    'department_goal_id' => $departmentGoalId,
                    'unit_id' => $unit->id,
                    'unit_name' => $unitName,
                    'goal_text' => $goal['goal_text'],
                    'target_tasks' => $goal['target_tasks'],
                    'date' => Carbon::now()->toDateString(), // استخدام Carbon::now() لضمان التاريخ الحالي
                ]);
            }
        }
    }
}
