<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\ActualResult;
use App\Models\UnitGoal;
use App\Models\Unit;
use App\Models\DepartmentGoal; // للتأكد من وجوده إذا كان يستخدم

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // جلب الأداء العام لليوم
        $todayOverallPerformance = ActualResult::whereDate('date', $today)->avg('overall_performance_score') ?? 0;
        $todayQualityRating = ActualResult::whereDate('date', $today)->avg('quality_rating') ?? 0;

        // جلب الأداء العام لأمس
        $yesterdayOverallPerformance = ActualResult::whereDate('date', $yesterday)->avg('overall_performance_score') ?? 0;
        $yesterdayQualityRating = ActualResult::whereDate('date', $yesterday)->avg('quality_rating') ?? 0;

        // --- جلب أهداف الوحدات مع نسبة التحقق الفعلية ---
        $dynamicUnitGoalsList = [];
        $units = Unit::all(); // جلب جميع الوحدات

        foreach ($units as $unit) {
            // جلب أحدث هدف للوحدة (أو الهدف الخاص باليوم إذا كان موجوداً)
            $unitGoals = UnitGoal::where('unit_id', $unit->id)
                                 ->orderBy('date', 'desc') // جلب الأحدث أولاً
                                 ->get(); // جلب كل الأهداف للوحدة لليوم أو الأيام السابقة

            foreach ($unitGoals as $unitGoal) {
                // جلب النتائج الفعلية المرتبطة بهذا الهدف في نفس التاريخ
                $actualResult = ActualResult::where('unit_id', $unit->id)
                                            ->where('unit_goal_id', $unitGoal->id)
                                            ->whereDate('date', $unitGoal->date) // يجب أن يكون التاريخ متطابقاً
                                            ->first();

                $progressPercentage = 0;
                if ($actualResult) {
                    $progressPercentage = $actualResult->effectiveness; // الفعالية هي نسبة التحقق
                }

                // إضافة الهدف إلى القائمة الديناميكية
                $dynamicUnitGoalsList[] = [
                    'text' => $unitGoal->goal_text,
                    'unit' => $unit->name,
                    'date' => $unitGoal->date->format('Y-m-d'),
                    'target_tasks' => $unitGoal->target_tasks,
                    'progress_percentage' => round($progressPercentage, 0), // تقريب النسبة المئوية
                    'icon' => 'fas fa-clipboard-check', // أيقونة افتراضية
                    'color_class' => 'bg-gradient-dark-blue' // لون افتراضي (يمكن تخصيصه بناءً على الوحدة أو الأداء)
                ];
            }
        }

        // يمكنك هنا تخصيص الأيقونات والألوان بناءً على نوع الهدف أو الوحدة
        // مثال:
        foreach ($dynamicUnitGoalsList as &$goal) {
            if (stripos($goal['text'], 'قاعة') !== false || stripos($goal['text'], 'مبيت') !== false) {
                $goal['icon'] = 'fas fa-chalkboard-teacher';
                $goal['color_class'] = 'bg-gradient-dark-blue';
            } elseif (stripos($goal['text'], 'ساحات') !== false || stripos($goal['text'], 'حاويات') !== false) {
                $goal['icon'] = 'fas fa-dumpster';
                $goal['color_class'] = 'bg-gradient-dark-green';
            } elseif (stripos($goal['text'], 'سجاد') !== false) {
                $goal['icon'] = 'fas fa-rug';
                $goal['color_class'] = 'bg-gradient-dark-purple';
            } elseif (stripos($goal['text'], 'ترامز') !== false) {
                $goal['icon'] = 'fas fa-water';
                $goal['color_class'] = 'bg-gradient-dark-red';
            } elseif (stripos($goal['text'], 'حمامات') !== false || stripos($goal['text'], 'صحية') !== false) {
                $goal['icon'] = 'fas fa-toilet';
                $goal['color_class'] = 'bg-gradient-dark-info'; // لون جديد
            } elseif (stripos($goal['text'], 'صيانة') !== false || stripos($goal['text'], 'أعطال') !== false) {
                $goal['icon'] = 'fas fa-tools';
                $goal['color_class'] = 'bg-gradient-dark-warning'; // لون جديد
            }

            // تعديل لون شريط التقدم بناءً على النسبة
            if ($goal['progress_percentage'] >= 100) {
                $goal['color_class'] = 'bg-gradient-success'; // لون أخضر للنجاح
            } elseif ($goal['progress_percentage'] >= 75) {
                $goal['color_class'] = 'bg-gradient-warning'; // لون أصفر للتحذير
            } else {
                $goal['color_class'] = 'bg-gradient-danger'; // لون أحمر للخطر
            }
        }


        // جلب أهداف الشعبة (لا تزال ثابتة هنا، يمكن جعلها ديناميكية لاحقاً)
        $departmentGoalsList = [
            [
                'text' => 'الحفاظ على نظافة جميع مرافق المدينة ومحيطها الخارجي.',
                'main_goal' => 'ضمان بيئة نظيفة وآمنة للجميع.',
                'icon' => 'fas fa-broom',
                'color_class' => 'bg-gradient-blue',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'ضمان جاهزية وصيانة جميع المرافق الصحية بشكل مستمر.',
                'main_goal' => 'توفير مرافق صحية عالية الجودة.',
                'icon' => 'fas fa-clinic-medical',
                'color_class' => 'bg-gradient-green',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'توفير بيئة نظيفة وصحية وآمنة للزائرين على مدار الساعة.',
                'main_goal' => 'راحة وسلامة الزوار.',
                'icon' => 'fas fa-shield-alt',
                'color_class' => 'bg-gradient-purple',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'الاستجابة السريعة للأعطال الطارئة في وحدات الخدمة.',
                'main_goal' => 'كفاءة الاستجابة للطوارئ.',
                'icon' => 'fas fa-bolt',
                'color_class' => 'bg-gradient-orange',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'جدولة وتنفيذ خطط التنظيف والصيانة اليومية والأسبوعية.',
                'main_goal' => 'إدارة عمليات فعالة.',
                'icon' => 'fas fa-calendar-check',
                'color_class' => 'bg-gradient-teal',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'دعم المناسبات والزيارات المليونية بخطط تنظيف استثنائية.',
                'main_goal' => 'دعم الفعاليات الكبرى.',
                'icon' => 'fas fa-users-crown',
                'color_class' => 'bg-gradient-pink',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'إدارة ومتابعة أداء فرق العمل وتوزيع المهام بكفاءة.',
                'main_goal' => 'تعزيز كفاءة فريق العمل.',
                'icon' => 'fas fa-user-tie',
                'color_class' => 'bg-gradient-indigo',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'تحسين الموارد المستخدمة وتقليل الهدر في الاستهلاك.',
                'main_goal' => 'إدارة موارد مستدامة.',
                'icon' => 'fas fa-recycle',
                'color_class' => 'bg-gradient-yellow',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'تعزيز ثقافة النظافة العامة لدى العاملين والزائرين.',
                'main_goal' => 'نشر الوعي البيئي.',
                'icon' => 'fas fa-hands-helping',
                'color_class' => 'bg-gradient-red',
                'date' => '2024-01-01'
            ],
            [
                'text' => 'إعداد تقارير مهنية يومية وشهرية لرفعها لإدارة المدينة.',
                'main_goal' => 'شفافية الأداء.',
                'icon' => 'fas fa-file-alt',
                'color_class' => 'bg-gradient-cyan',
                'date' => '2024-01-01'
            ],
        ];

        return view('home', compact(
            'todayOverallPerformance',
            'todayQualityRating',
            'yesterdayOverallPerformance',
            'yesterdayQualityRating',
            'departmentGoalsList',
            'dynamicUnitGoalsList' // تمرير قائمة أهداف الوحدات الديناميكية
        ));
    }
}
