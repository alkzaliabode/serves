<?php

namespace App\Http\Controllers;

use App\Models\Survey; // تأكد من أن هذا يشير إلى موديل الاستبيان الصحيح لديك
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;

class SurveyChartController extends Controller
{
    /**
     * عرض صفحة المخططات الإحصائية للاستبيانات.
     */
    public function index()
    {
        // Gate::authorize('view_survey_stats'); // قم بإلغاء التعليق إذا كنت تستخدم Gates
        return view('charts.survey-charts');
    }

    /**
     * دالة مساعدة لتطبيق فلاتر التاريخ على الاستعلام.
     * تم تحديثها لاستخدام Carbon لضمان شمولية اليوم بالكامل.
     */
    protected function applyDateFilters(Request $request, $query)
    {
        if ($request->filled('from_date')) {
            // تحويل التاريخ المدخل إلى كائن Carbon وتعيين الوقت إلى بداية اليوم (00:00:00)
            $fromDate = Carbon::parse($request->input('from_date'))->startOfDay();
            $query->where('created_at', '>=', $fromDate);
        }
        if ($request->filled('to_date')) {
            // تحويل التاريخ المدخل إلى كائن Carbon وتعيين الوقت إلى نهاية اليوم (23:59:59)
            $toDate = Carbon::parse($request->input('to_date'))->endOfDay();
            $query->where('created_at', '<=', $toDate);
        }
        return $query;
    }

    /**
     * دالة مساعدة لترجمة قيم الاستبيان الإنجليزية إلى العربية.
     * تم تحديثها لتشمل جميع الترجمات من SurveyController
     * وتمت إزالة حقل 'speed_accuracy' بناءً على طلبك.
     *
     * @param string $key اسم الحقل (مثلاً 'overall_satisfaction', 'gender')
     * @param string|null $value القيمة الإنجليزية المخزنة في قاعدة البيانات
     * @return string القيمة المترجمة أو القيمة الأصلية إذا لم يتم العرف عليها
     */
    protected function translateSurveyValue(string $key, ?string $value): string
    {
        if (is_null($value)) {
            return 'غير محدد';
        }

        $translations = [
            'gender' => [
                'male' => 'ذكر',
                'female' => 'أنثى',
            ],
            'age_group' => [
                'under_18' => 'أقل من 18',
                '18_30' => '18-30',
                '30_45' => '30-45',
                '45_60' => '45-60',
                'over_60' => 'أكثر من 60',
            ],
            'visit_count' => [
                'first_time' => 'أول مرة',
                '2_5_times' => '2-5 مرات',
                'over_5_times' => 'أكثر من 5',
            ],
            'stay_duration' => [
                'less_1h' => 'أقل من ساعة',
                '2_3h' => '2-3 ساعات',
                '4_6h' => '4-6 ساعات',
                'over_6h' => 'أكثر من 6 ساعات',
            ],
            'toilet_cleanliness' => [
                'excellent' => 'ممتازة',
                'very_good' => 'جيدة جدًا',
                'good' => 'جيدة',
                'acceptable' => 'مقبولة',
                'poor' => 'سيئة',
            ],
            'hygiene_supplies' => [
                'always' => 'دائمًا متوفرة',
                'often' => 'غالبًا متوفرة',
                'rarely' => 'نادرًا متوفرة',
                'never' => 'غير متوفرة إطلاقًا',
            ],
            'yard_cleanliness' => [
                'clean' => 'نظيفة',
                'needs_improvement' => 'تحتاج إلى تحسين',
                'dirty' => 'غير نظيفة',
            ],
            'cleaning_teams' => [
                'clearly' => 'نعم، بشكل واضح',
                'sometimes' => 'نعم، ولكن ليس دائمًا',
                'rarely' => 'نادرًا ما ألاحظ ذلك',
                'not_noticed' => 'لا، لم ألاحظ',
            ],
            'hall_cleanliness' => [
                'very_clean' => 'نظيفة جدًا',
                'clean' => 'نظيفة',
                'needs_improvement' => 'تحتاج إلى تحسين',
                'dirty' => 'غير نظيفة',
            ],
            'bedding_condition' => [
                'excellent' => 'نعم، بحالة ممتازة',
                'needs_care' => 'نعم، ولكن تحتاج إلى مزيد من العناية',
                'not_clean' => 'ليست نظيفة بما يكفي',
                'not_available' => 'غير متوفرة بشكل كافي',
            ],
            'ventilation' => [
                'excellent' => 'نعم، التهوية ممتازة',
                'needs_improvement' => 'متوفرة ولكن تحتاج إلى تحسين',
                'poor' => 'التهوية ضعيفة وغير كافية',
            ],
            'lighting' => [
                'excellent' => 'ممتازة',
                'good' => 'جيدة',
                'needs_improvement' => 'ضعيفة وتحتاج إلى تحسين',
            ],
            'water_trams_distribution' => [
                'everywhere' => 'نعم، في كل مكان',
                'needs_more' => 'نعم، ولكن تحتاج إلى زيادة',
                'not_enough' => 'غير موزعة بشكل كافي',
            ],
            'water_trams_cleanliness' => [
                'very_clean' => 'نظيفة جدًا',
                'clean' => 'نظيفة',
                'needs_improvement' => 'تحتاج إلى تحسين',
                'dirty' => 'غير نظيفة',
            ],
            'water_availability' => [
                'always' => 'دائمًا متوفرة',
                'often' => 'غالبًا متوفرة',
                'rarely' => 'نادرًا ما تتوفر',
                'not_enough' => 'لا تتوفر بشكل كافي',
            ],
            'overall_satisfaction' => [
                'very_satisfied' => 'راض جدًا',
                'satisfied' => 'راض',
                'acceptable' => 'مقبول',
                'dissatisfied' => 'غير راض',
            ],
            // تم إزالة ترجمة 'speed_accuracy' هنا بناءً على طلبك
        ];

        // ترجمة تاريخ الإنشاء
        if ($key === 'created_at' && $value) {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        }

        return $translations[$key][$value] ?? $value;
    }

    /**
     * جلب بيانات مخطط توزيع الرضا العام (دائري).
     */
    public function getSatisfactionPieChartData(Request $request)
    {
        // Gate::authorize('view_survey_stats');
        $query = Survey::query();
        $query = $this->applyDateFilters($request, $query);

        $data = $query->selectRaw('overall_satisfaction, COUNT(*) as count')
            ->groupBy('overall_satisfaction')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$this->translateSurveyValue('overall_satisfaction', $item->overall_satisfaction) => $item->count];
            });

        return response()->json([
            'datasets' => [
                [
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => ['#4CAF50', '#2196F3', '#FFC107', '#F44336'], // ألوان افتراضية
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ]);
    }

    /**
     * جلب بيانات مخطط نظافة القاعات (شريطي).
     */
    public function getHallCleanlinessChartData(Request $request)
    {
        // Gate::authorize('view_survey_stats');
        $query = Survey::query();
        $query = $this->applyDateFilters($request, $query);

        $data = $query->selectRaw('hall_cleanliness, COUNT(*) as count')
            ->groupBy('hall_cleanliness')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$this->translateSurveyValue('hall_cleanliness', $item->hall_cleanliness) => $item->count];
            })
            ->sortKeysUsing(function ($keyA, $keyB) {
                // ترتيب مخصص للقيم لضمان ترتيب منطقي
                $order = ['نظيفة جدًا', 'نظيفة', 'تحتاج إلى تحسين', 'غير نظيفة'];
                return array_search($keyA, $order) <=> array_search($keyB, $order);
            });

        return response()->json([
            'labels' => $data->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'نظافة القاعات',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => ['#28a745', '#17a2b8', '#ffc107', '#dc3545'], // ألوان متنوعة
                    'borderColor' => ['#28a745', '#17a2b8', '#ffc107', '#dc3545'],
                    'borderWidth' => 1,
                ],
            ],
        ]);
    }

    /**
     * جلب بيانات مخطط نظافة ترامز الماء (شريطي).
     */
    public function getWaterTramsCleanlinessChartData(Request $request)
    {
        // Gate::authorize('view_survey_stats');
        $query = Survey::query();
        $query = $this->applyDateFilters($request, $query);

        $data = $query->selectRaw('water_trams_cleanliness, COUNT(*) as count')
            ->groupBy('water_trams_cleanliness')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$this->translateSurveyValue('water_trams_cleanliness', $item->water_trams_cleanliness) => $item->count];
            })
            ->sortKeysUsing(function ($keyA, $keyB) {
                $order = ['نظيفة جدًا', 'نظيفة', 'تحتاج إلى تحسين', 'غير نظيفة'];
                return array_search($keyA, $order) <=> array_search($keyB, $order);
            });

        return response()->json([
            'labels' => $data->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'نظافة ترامز الماء',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => ['#6f42c1', '#20c997', '#fd7e14', '#e83e8c'],
                    'borderColor' => ['#6f42c1', '#20c997', '#fd7e14', '#e83e8c'],
                    'borderWidth' => 1,
                ],
            ],
        ]);
    }

    /**
     * جلب بيانات مخطط نظافة المرافق (دورات المياه والساحات - شريطي).
     */
    public function getFacilitiesCleanlinessChartData(Request $request)
    {
        // Gate::authorize('view_survey_stats');
        $query = Survey::query();
        $query = $this->applyDateFilters($request, $query);

        // مهم: يجب استخدام clone() عند استخدام نفس كائن الـ query لأكثر من استعلام
        // للحفاظ على الاستعلام الأصلي دون تطبيق التغييرات عليه في الاستعلامات اللاحقة.
        // هنا تم استخدام clone() بشكل صحيح
        $toiletData = $query->clone()->selectRaw('toilet_cleanliness, COUNT(*) as count')
            ->groupBy('toilet_cleanliness')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$this->translateSurveyValue('toilet_cleanliness', $item->toilet_cleanliness) => $item->count];
            });

        $yardData = $query->clone()->selectRaw('yard_cleanliness, COUNT(*) as count')
            ->groupBy('yard_cleanliness')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$this->translateSurveyValue('yard_cleanliness', $item->yard_cleanliness) => $item->count];
            });

        // دمج جميع التصنيفات الفريدة من كلا المجموعتين
        $allLabels = array_unique(array_merge($toiletData->keys()->toArray(), $yardData->keys()->toArray()));

        // ترتيب التصنيفات لضمان الاتساق
        $order = ['ممتازة', 'جيدة جدًا', 'جيدة', 'مقبولة', 'سيئة', 'نظيفة', 'تحتاج إلى تحسين', 'غير نظيفة'];
        usort($allLabels, function ($a, $b) use ($order) {
            return array_search($a, $order) <=> array_search($b, $order);
        });

        // التأكد من أن جميع البيانات تحتوي على جميع الـ labels، مع قيم صفرية للمفقود
        $toiletCounts = array_values(array_map(function($label) use ($toiletData) {
            return $toiletData->get($label, 0);
        }, $allLabels));

        $yardCounts = array_values(array_map(function($label) use ($yardData) {
            return $yardData->get($label, 0);
        }, $allLabels));

        return response()->json([
            'labels' => $allLabels,
            'datasets' => [
                [
                    'label' => 'نظافة دورات المياه',
                    'data' => $toiletCounts,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.7)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'borderRadius' => 5,
                ],
                [
                    'label' => 'نظافة الساحات والممرات',
                    'data' => $yardCounts,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'borderRadius' => 5,
                ],
            ],
        ]);
    }

    // تم إزالة دالة getSpeedAccuracyData بالكامل بناءً على طلبك.


    /**
     * جلب بيانات الجدول التفصيلية والملخص لتقرير الاستبيانات.
     * يعتمد على قيم الاستبيان المخزنة في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTableSummaryData(Request $request)
    {
        // Gate::authorize('view_survey_stats'); // قم بإلغاء التعليق إذا كنت تستخدم Gates

        // 1. جلب الاستبيانات بناءً على فلاتر التاريخ
        $query = Survey::query();
        $surveys = $this->applyDateFilters($request, $query)->get();

        $detailedStatistics = [];

        // خريطة لتعيين القيم الإنجليزية إلى فئات قياسية ونقاط
        $valueToScoreMap = [
            'excellent' => ['category' => 'excellent', 'score' => 5],
            'very_good' => ['category' => 'very_good', 'score' => 4],
            'good' => ['category' => 'good', 'score' => 3],
            'acceptable' => ['category' => 'acceptable', 'score' => 2],
            'poor' => ['category' => 'poor', 'score' => 1],
            // لـ overall_satisfaction
            'very_satisfied' => ['category' => 'excellent', 'score' => 5],
            'satisfied' => ['category' => 'very_good', 'score' => 4],
            'dissatisfied' => ['category' => 'poor', 'score' => 1],
            // لـ hall_cleanliness, water_trams_cleanliness, yard_cleanliness, toilet_cleanliness
            'very_clean' => ['category' => 'excellent', 'score' => 5],
            'clean' => ['category' => 'very_good', 'score' => 4],
            'needs_improvement' => ['category' => 'acceptable', 'score' => 2],
            'dirty' => ['category' => 'poor', 'score' => 1],
        ];

        // دالة مساعدة لمعالجة مؤشر واحد وحساب الإحصائيات الخاصة به
        $processIndicator = function($surveysCollection, $columnName, $indicatorLabel) use (&$detailedStatistics, $valueToScoreMap) {
            $counts = [
                'excellent' => 0, 'very_good' => 0, 'good' => 0, 'acceptable' => 0, 'poor' => 0
            ];
            $totalScore = 0;
            $responseCount = 0;

            foreach ($surveysCollection as $survey) {
                // تحقق مما إذا كان العمود موجودًا وغير فارغ
                if (!isset($survey->$columnName) || is_null($survey->$columnName)) {
                    continue; // تخطى إذا لم يكن العمود موجودًا أو فارغًا
                }

                $value = $survey->$columnName;
                if (isset($valueToScoreMap[$value])) {
                    $mapping = $valueToScoreMap[$value];
                    $counts[$mapping['category']]++;
                    $totalScore += $mapping['score'];
                    $responseCount++;
                }
            }

            // حساب متوسط الرضا وتحويله إلى نسبة مئوية (إذا كان المقياس من 1-5)
            $avgSatisfaction = ($responseCount > 0) ? ($totalScore / $responseCount) * 20 : 0;

            $detailedStatistics[] = [
                'indicator' => $indicatorLabel,
                'excellent' => $counts['excellent'],
                'very_good' => $counts['very_good'],
                'good' => $counts['good'], // قد تكون هذه الفئة غير مستخدمة لبعض المؤشرات
                'acceptable' => $counts['acceptable'],
                'poor' => $counts['poor'],
                'total' => $responseCount,
                'avg_satisfaction' => round($avgSatisfaction, 2),
            ];
        };

        // 2. معالجة كل مؤشر من مؤشرات الاستبيان
        $processIndicator($surveys, 'overall_satisfaction', 'الرضا العام');
        $processIndicator($surveys, 'hall_cleanliness', 'نظافة القاعات');
        $processIndicator($surveys, 'water_trams_cleanliness', 'نظافة ترامز الماء');
        $processIndicator($surveys, 'toilet_cleanliness', 'نظافة دورات المياه');
        $processIndicator($surveys, 'yard_cleanliness', 'نظافة الساحات والممرات');
        // تم إزالة استدعاء $processIndicator لـ 'speed_accuracy' هنا بناءً على طلبك


        // 3. حساب الإحصائيات الموجزة (Summary)
        $totalSurveysInPeriod = $surveys->count(); // العدد الكلي للاستبيانات في الفترة المحددة
        $overallSatisfactionSum = 0;
        $totalResponsesAcrossIndicators = 0; // مجموع الاستجابات لجميع المؤشرات
        $highestIndicator = 'لا يوجد';
        $lowestIndicator = 'لا يوجد';
        $highestAvg = -1;
        $lowestAvg = 101; // قيمة أولية عالية جداً

        if (!empty($detailedStatistics)) {
            foreach ($detailedStatistics as $stat) {
                // لمتوسط الرضا الكلي، نستخدم المتوسط المرجح بناءً على عدد الاستجابات لكل مؤشر
                $overallSatisfactionSum += ($stat['avg_satisfaction'] * $stat['total']);
                $totalResponsesAcrossIndicators += $stat['total'];

                if ($stat['avg_satisfaction'] > $highestAvg) {
                    $highestAvg = $stat['avg_satisfaction'];
                    $highestIndicator = $stat['indicator'];
                }
                if ($stat['avg_satisfaction'] < $lowestAvg) {
                    $lowestAvg = $stat['avg_satisfaction'];
                    $lowestIndicator = $stat['indicator'];
                }
            }
            // حساب متوسط الرضا الكلي
            $overallSatisfaction = ($totalResponsesAcrossIndicators > 0) ? round(($overallSatisfactionSum / $totalResponsesAcrossIndicators), 2) : 0;
        } else {
            $overallSatisfaction = 0;
        }

        $summary = [
            'total_surveys' => $totalSurveysInPeriod,
            'overall_satisfaction' => $overallSatisfaction,
            'highest_indicator' => $highestIndicator,
            'lowest_indicator' => $lowestIndicator,
        ];

        // 4. إرجاع البيانات كـ JSON
        return response()->json([
            'detailed_statistics' => $detailedStatistics,
            'summary' => $summary
        ]);
    }
}
