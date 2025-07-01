<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActualResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Unit; // Added for potential unit filtering

class GilbertTriangleController extends Controller
{
    /**
     * يعرض صفحة مخطط مثلث جلبرت.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('charts.gilbert-triangle'); // لا حاجة لتمرير التواريخ هنا، سيتم جلبها عبر AJAX
    }

    /**
     * يسترجع بيانات مخطط مثلث جلبرت كـ JSON من ActualResult.
     * يمكن استدعاؤها عبر AJAX من الواجهة الأمامية.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request)
    {
        // يمكنك هنا إضافة منطق لتحديد الوحدة (unit_id) بناءً على مدخلات المستخدم (مثلاً، الوحدة التي ينتمي إليها المستخدم)
        // أو يمكنك تمريرها كمعامل في الطلب إذا كنت تدعم وحدات متعددة
        $unitId = 1; // افتراضيًا الوحدة رقم 1. قم بتغيير هذا لجعله ديناميكيًا.
                     // مثال: $unitId = auth()->user()->unit_id;

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        // جلب النتائج الفعلية لليوم
        $todayResult = ActualResult::where('date', $today)
            ->where('unit_id', $unitId)
            ->first();

        // جلب النتائج الفعلية للأمس
        $yesterdayResult = ActualResult::where('date', $yesterday)
            ->where('unit_id', $unitId)
            ->first();

        // القيم الافتراضية إذا لم تكن البيانات موجودة
        $todayCompletedTasks = $todayResult->completed_tasks ?? 0;
        $todayWorkingHours = $todayResult->working_hours ?? 0;
        $todayTargetTasks = $todayResult->unitGoal->target_tasks ?? 0;

        $yesterdayCompletedTasks = $yesterdayResult->completed_tasks ?? 0;
        $yesterdayWorkingHours = $yesterdayResult->working_hours ?? 0;
        $yesterdayTargetTasks = $yesterdayResult->unitGoal->target_tasks ?? 0;
        
        // حساب النسب المئوية للأركان الرئيسية (لتناسب مقياس 0-100 في المخطط)
        // هذه النسب افتراضية ويجب تعديلها لتناسب منطق عملك ونطاق القيم الفعلية
        // مثال: إذا كان 100 مهمة مكتملة = 100%، 8 ساعات عمل = 100% موارد، 50 مهمة مستهدفة = 100% أهداف
        $maxCompletedTasks = 100; // مثال: أقصى عدد مهام مكتملة متوقعة
        $maxWorkingHours = 8;    // مثال: أقصى ساعات عمل (يوم عمل كامل)
        $maxTargetTasks = 50;    // مثال: أقصى عدد مهام مستهدفة واقعي

        $todayResultPercentage = $maxCompletedTasks > 0 ? min(100, round(($todayCompletedTasks / $maxCompletedTasks) * 100, 2)) : 0;
        $todayGoalPercentage = $maxTargetTasks > 0 ? min(100, round(($todayTargetTasks / $maxTargetTasks) * 100, 2)) : 0;
        $todayResourcePercentage = $maxWorkingHours > 0 ? min(100, round(($todayWorkingHours / $maxWorkingHours) * 100, 2)) : 0;

        $yesterdayResultPercentage = $maxCompletedTasks > 0 ? min(100, round(($yesterdayCompletedTasks / $maxCompletedTasks) * 100, 2)) : 0;
        $yesterdayGoalPercentage = $maxTargetTasks > 0 ? min(100, round(($yesterdayTargetTasks / $maxTargetTasks) * 100, 2)) : 0;
        $yesterdayResourcePercentage = $maxWorkingHours > 0 ? min(100, round(($yesterdayWorkingHours / $maxWorkingHours) * 100, 2)) : 0;

        // حسابات الفعالية والكفاءة والملاءمة بناءً على القيم الفعلية (كما في نموذج ActualResult)
        // الفعالية (Effectiveness): (النتائج الفعلية / الأهداف المستهدفة) * 100
        $todayEffectiveness = ($todayTargetTasks > 0) ? min(100, round(($todayCompletedTasks / $todayTargetTasks) * 100, 2)) : 0;
        $yesterdayEffectiveness = ($yesterdayTargetTasks > 0) ? min(100, round(($yesterdayCompletedTasks / $yesterdayTargetTasks) * 100, 2)) : 0;

        // الكفاءة (Efficiency): (النتائج الفعلية / الموارد المستهلكة) * 100
        $efficiencyConversionFactor = 50; // نفس عامل التحويل المستخدم في ActualResult Model
        $todayEfficiency = ($todayWorkingHours > 0) ? min(100, round(($todayCompletedTasks / $todayWorkingHours) * $efficiencyConversionFactor, 2)) : 0;
        $yesterdayEfficiency = ($yesterdayWorkingHours > 0) ? min(100, round(($yesterdayCompletedTasks / $yesterdayWorkingHours) * $efficiencyConversionFactor, 2)) : 0;

        // الملاءمة (Relevance): (الموارد / الأهداف) * 100 (مع الأخذ في الاعتبار النسبة المثالية)
        $optimal_hours_per_targeted_task = 0.5; // نفس القيمة من الموديل
        $todayRelevance = 0;
        if ($todayTargetTasks > 0 && $todayWorkingHours > 0) {
            $actual_hours_per_targeted_task_for_relevance = $todayWorkingHours / $todayTargetTasks;
            $deviation = abs($actual_hours_per_targeted_task_for_relevance - $optimal_hours_per_targeted_task);
            $max_acceptable_deviation = $optimal_hours_per_targeted_task * 2; 
            if ($max_acceptable_deviation > 0) {
                $todayRelevance = 100 - min(100, ($deviation / $max_acceptable_deviation) * 100);
            }
            $todayRelevance = max(0, round($todayRelevance, 2));
        } elseif ($todayTargetTasks == 0 && $todayWorkingHours == 0) {
            $todayRelevance = 100;
        }

        $yesterdayRelevance = 0;
        if ($yesterdayTargetTasks > 0 && $yesterdayWorkingHours > 0) {
            $actual_hours_per_targeted_task_for_relevance = $yesterdayWorkingHours / $yesterdayTargetTasks;
            $deviation = abs($actual_hours_per_targeted_task_for_relevance - $optimal_hours_per_targeted_task);
            $max_acceptable_deviation = $optimal_hours_per_targeted_task * 2; 
            if ($max_acceptable_deviation > 0) {
                $yesterdayRelevance = 100 - min(100, ($deviation / $max_acceptable_deviation) * 100);
            }
            $yesterdayRelevance = max(0, round($yesterdayRelevance, 2));
        } elseif ($yesterdayTargetTasks == 0 && $yesterdayWorkingHours == 0) {
            $yesterdayRelevance = 100;
        }

        // ---DEBUGGING---
        Log::info("Gilbert Controller Calculated Values for Today ({$today}) for Unit {$unitId}: Effectiveness={$todayEffectiveness}%, Efficiency={$todayEfficiency}%, Relevance={$todayRelevance}%");
        // ---END DEBUGGING---

        // إنشاء datasets كما في المثال الذي قدمته (مع Results, Goals, Resources أولاً ثم Efficiency, Effectiveness)
        $datasets = [
            // dataset لليوم
            [
                'label' => 'اليوم',
                'data' => [
                    $todayResultPercentage, // النتائج
                    $todayGoalPercentage,   // الأهداف
                    $todayResourcePercentage, // الموارد
                    $todayEfficiency, // الكفاءة المحسوبة
                    $todayEffectiveness, // الفعالية المحسوبة
                    $todayRelevance // الملاءمة المحسوبة
                ],
                'backgroundColor' => $this->hexToRgba('#3B82F6', 0.18), // أزرق
                'borderColor' => '#3B82F6',
                'pointBackgroundColor' => '#3B82F6',
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => '#3B82F6',
                'borderWidth' => 2,
                'pointRadius' => 5,
                'pointHoverRadius' => 8,
            ],
            // dataset للأمس
            [
                'label' => 'الأمس',
                'data' => [
                    $yesterdayResultPercentage, // النتائج
                    $yesterdayGoalPercentage,   // الأهداف
                    $yesterdayResourcePercentage, // الموارد
                    $yesterdayEfficiency, // الكفاءة المحسوبة
                    $yesterdayEffectiveness, // الفعالية المحسوبة
                    $yesterdayRelevance // الملاءمة المحسوبة
                ],
                'backgroundColor' => $this->hexToRgba('#EF4444', 0.14), // أحمر
                'borderColor' => '#EF4444',
                'pointBackgroundColor' => '#EF4444',
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => '#EF4444',
                'borderWidth' => 2,
                'pointRadius' => 5,
                'pointHoverRadius' => 8,
            ],
        ];

        // يمكنك إضافة متوسط الأسبوع هنا إذا أردت، بنفس المنطق
        // $weekData = $this->getPeriodData('week', true); // تحتاج لتطبيق منطق getPeriodData

        return response()->json([
            'datasets' => $datasets,
            // تسميات المحاور الجديدة كما طلبت
            'labels' => ['النتائج', 'الفعالية', 'الاهداف', 'الملاءمة', 'الموارد', 'الكفاءة'],
            'options' => $this->getChartOptions(),
            // تمرير بيانات إضافية للعرض خارج المخطط
            'todayOverallPerformance' => $todayResult->overall_performance_score ?? 0,
            'yesterdayOverallPerformance' => $yesterdayResult->overall_performance_score ?? 0,
            'todayQualityRating' => $todayResult->quality_rating ?? 0,
            'yesterdayQualityRating' => $yesterdayResult->quality_rating ?? 0,
        ]);
    }

    /**
     * يعيد خيارات Chart.js لإنشاء مخطط الرادار.
     *
     * @return array
     */
    private function getChartOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => true,
            'layout' => [
                'padding' => 40,
            ],
            'scales' => [
                'r' => [
                    'angleLines' => [
                        'display' => true,
                        'color' => 'rgba(200, 200, 200, 0.3)' // لون أفتح
                    ],
                    'grid' => [
                        'circular' => true,
                        'color' => 'rgba(200, 200, 200, 0.2)' // لون أفتح
                    ],
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                    'ticks' => [
                        'stepSize' => 20,
                        'backdropColor' => 'transparent',
                        'showLabelBackdrop' => false,
                        'font' => [ // تم نقل Font هنا
                            'family' => 'Tajawal, sans-serif',
                            'size' => 10
                        ],
                        'callback' => 'function(value) { return value + "%"; }' 
                    ],
                    'pointLabels' => [
                        'font' => [
                            'family' => 'Tajawal, sans-serif',
                            'size' => 14, // أصغر قليلاً
                            'weight' => 'bold'
                        ],
                        'color' => '#E0E0E0' // لون فاتح لتسميات النقاط
                    ]
                ]
            ],
            'elements' => [
                'line' => [
                    'tension' => 0.1,
                    'borderWidth' => 3 // أعرض قليلاً
                ],
                'point' => [
                    'radius' => 5, // أصغر قليلاً
                    'hoverRadius' => 8, // أصغر قليلاً
                    'borderWidth' => 2 // أرق قليلاً
                ]
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'rtl' => true,
                    'labels' => [
                        'font' => [
                            'family' => 'Tajawal, sans-serif',
                            'size' => 13, // أصغر قليلاً
                            'weight' => '500'
                        ],
                        'usePointStyle' => true,
                        'padding' => 20, // مسافة أكبر
                        'boxWidth' => 12 // حجم مربع اللون
                    ]
                ],
                'tooltip' => [
                    'rtl' => true,
                    'enabled' => true, // التأكد من تفعيل الـ tooltip
                    'backgroundColor' => 'rgba(31, 41, 55, 0.9)', // لون خلفية داكن مع شفافية
                    'titleFont' => [
                        'family' => 'Tajawal, sans-serif',
                        'size' => 14, // أصغر قليلاً
                        'weight' => 'bold'
                    ],
                    'bodyFont' => [
                        'family' => 'Tajawal, sans-serif',
                        'size' => 13
                    ],
                    'footerFont' => [ // إضافة خط للـ footer
                        'family' => 'Tajawal, sans-serif',
                        'size' => 12
                    ],
                    'callbacks' => [
                        'label' => 'function(context) { 
                            const label = context.dataset.label || "";
                            const value = context.raw;
                            const icon = "⬤"; // رمز نقطة جميل
                            return `${icon} ${label}: ${value}%`;
                        }',
                        'footer' => 'function(tooltipItems) {
                            // حساب الأداء العام فقط للمجموعات الرئيسية (اليوم، الأمس، متوسط الأسبوع)
                            // وليس لمجموعات الكفاءة والفعالية والملاءمة المنفصلة
                            if (tooltipItems[0].dataset.label.includes("كفاءة") || 
                                tooltipItems[0].dataset.label.includes("فعالية") ||
                                tooltipItems[0].dataset.label.includes("ملاءمة")) {
                                return ""; // لا تعرض footer لهذه المجموعات
                            }
                            
                            const data = tooltipItems[0].dataset.data;
                            // تأكد أن القيم موجودة قبل حساب المتوسط
                            const performance = ((data[0] + data[1] + data[2] + data[3] + data[4] + data[5]) / 6).toFixed(1); // متوسط الـ 6 محاور
                            return `الأداء العام: ${performance}%`;
                        }'
                    ]
                ],
                'datalabels' => [ // تعطيل datalabels إذا كنت لا تستخدمها
                    'display' => false
                ]
            ]
        ];
    }

    /**
     * Helper to convert Hex to RGBA.
     *
     * @param string $hex
     * @param float $alpha
     * @return string
     */
    private function hexToRgba(string $hex, float $alpha): string
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "rgba($r, $g, $b, $alpha)";
    }
}
