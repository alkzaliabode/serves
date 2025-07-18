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
     * دالة مساعدة لتعيين الألوان بناءً على مستوى الرضا/النظافة.
     * تستخدم نفس لوحة الألوان الموحدة في الواجهة الأمامية.
     *
     * @param string $translatedLabel التسمية المترجمة (مثلاً 'ممتازة', 'جيدة جدًا', 'سيئة')
     * @return string اللون السداسي العشري المقابل
     */
    protected function getColorForLabel(string $translatedLabel): string
    {
        switch ($translatedLabel) {
            case 'راض جدًا':
            case 'ممتازة':
            case 'نظيفة جدًا':
            case 'نعم، بحالة ممتازة':
            case 'نعم، التهوية ممتازة':
                return '#28a745'; // excellent-color (أخضر)
            case 'راض':
            case 'نظيفة':
            case 'نعم، ولكن تحتاج إلى مزيد من العناية': // لحالة الفراش
                return '#8bc34a'; // very-good-color (أخضر فاتح)
            case 'جيدة جدًا': // تم تعيين اللون الأصفر هنا
                return '#ffc107'; // good-color (أصفر)
            case 'جيدة': // تم تعيين اللون البرتقالي هنا لتمييزها عن "جيدة جداً"
            case 'مقبول':
            case 'مقبولة':
            case 'تحتاج إلى تحسين':
            case 'متوفرة ولكن تحتاج إلى تحسين': // للتهوية
            case 'ضعيفة وتحتاج إلى تحسين': // للضوء
                return '#fd7e14'; // acceptable-color (برتقالي)
            case 'غير راض':
            case 'سيئة':
            case 'غير نظيفة':
            case 'ليست نظيفة بما يكفي': // لحالة الفراش
            case 'التهوية ضعيفة وغير كافية': // للتهوية
                return '#dc3545'; // poor-color (أحمر)
            default:
                return '#6c757d'; // لون رمادي افتراضي
        }
    }

    /**
     * جلب بيانات مخطط توزيع الرضا العام (دائري).
     */
    public function getSatisfactionPieChartData(Request $request)
    {
        // Gate::authorize('view_survey_stats');
        $query = Survey::query();
        $query = $this->applyDateFilters($request, $query);

        // جلب البيانات الخام وتجميعها
        $data = $query->selectRaw('overall_satisfaction, COUNT(*) as count')
            ->groupBy('overall_satisfaction')
            ->get();

        // تهيئة مصفوفة لترتيب البيانات والألوان بشكل صحيح
        $orderedLabels = ['very_satisfied', 'satisfied', 'acceptable', 'dissatisfied'];
        $translatedLabels = [];
        $counts = [];
        $backgroundColors = [];

        foreach ($orderedLabels as $label) {
            $translated = $this->translateSurveyValue('overall_satisfaction', $label);
            $count = $data->firstWhere('overall_satisfaction', $label)['count'] ?? 0;

            $translatedLabels[] = $translated;
            $counts[] = $count;
            $backgroundColors[] = $this->getColorForLabel($translated);
        }

        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $translatedLabels,
        ];
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
            });

        // ترتيب مخصص للقيم لضمان ترتيب منطقي
        $order = ['نظيفة جدًا', 'نظيفة', 'تحتاج إلى تحسين', 'غير نظيفة'];
        $orderedLabels = [];
        $orderedData = [];
        $orderedColors = [];

        foreach ($order as $label) {
            if (in_array($label, $data->keys()->toArray())) {
                $orderedLabels[] = $label;
                $orderedData[] = $data->get($label, 0);
                $orderedColors[] = $this->getColorForLabel($label);
            }
        }
        // إضافة أي تسميات غير موجودة في الترتيب المخصص في النهاية (مع لون افتراضي)
        foreach ($data->keys() as $label) {
            if (!in_array($label, $orderedLabels)) {
                $orderedLabels[] = $label;
                $orderedData[] = $data->get($label, 0);
                $orderedColors[] = $this->getColorForLabel($label); // أو لون افتراضي آخر
            }
        }

        return [
            'labels' => $orderedLabels,
            'datasets' => [
                [
                    'label' => 'نظافة القاعات',
                    'data' => $orderedData,
                    'backgroundColor' => $orderedColors, // ألوان متنوعة لكل شريط
                    'borderColor' => $orderedColors,
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ],
            ],
        ];
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
            });

        $order = ['نظيفة جدًا', 'نظيفة', 'تحتاج إلى تحسين', 'غير نظيفة'];
        $orderedLabels = [];
        $orderedData = [];
        $orderedColors = [];

        foreach ($order as $label) {
            if (in_array($label, $data->keys()->toArray())) {
                $orderedLabels[] = $label;
                $orderedData[] = $data->get($label, 0);
                $orderedColors[] = $this->getColorForLabel($label);
            }
        }
        foreach ($data->keys() as $label) {
            if (!in_array($label, $orderedLabels)) {
                $orderedLabels[] = $label;
                $orderedData[] = $data->get($label, 0);
                $orderedColors[] = $this->getColorForLabel($label);
            }
        }

        return [
            'labels' => $orderedLabels,
            'datasets' => [
                [
                    'label' => 'نظافة ترامز الماء',
                    'data' => $orderedData,
                    'backgroundColor' => $orderedColors,
                    'borderColor' => $orderedColors,
                    'borderWidth' => 1,
                    'borderRadius' => 8,
                ],
            ],
        ];
    }

    /**
     * جلب بيانات مخطط نظافة المرافق (دورات المياه والساحات - شريطي).
     */
    public function getFacilitiesCleanlinessChartData(Request $request)
    {
        // Gate::authorize('view_survey_stats');
        $query = Survey::query();
        $query = $this->applyDateFilters($request, $query);

        // جلب بيانات نظافة دورات المياه
        $toiletDataRaw = $query->clone()->selectRaw('toilet_cleanliness, COUNT(*) as count')
            ->groupBy('toilet_cleanliness')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$this->translateSurveyValue('toilet_cleanliness', $item->toilet_cleanliness) => $item->count];
            });

        // جلب بيانات نظافة الساحات والممرات
        $yardDataRaw = $query->clone()->selectRaw('yard_cleanliness, COUNT(*) as count')
            ->groupBy('yard_cleanliness')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$this->translateSurveyValue('yard_cleanliness', $item->yard_cleanliness) => $item->count];
            });

        // دمج جميع التصنيفات الفريدة من كلا المجموعتين
        $allLabels = array_unique(array_merge($toiletDataRaw->keys()->toArray(), $yardDataRaw->keys()->toArray()));

        // ترتيب التصنيفات لضمان الاتساق
        $order = ['ممتازة', 'جيدة جدًا', 'جيدة', 'مقبولة', 'سيئة', 'نظيفة', 'تحتاج إلى تحسين', 'غير نظيفة'];
        usort($allLabels, function ($a, $b) use ($order) {
            $posA = array_search($a, $order);
            $posB = array_search($b, $order);
            return ($posA === false ? count($order) : $posA) <=> ($posB === false ? count($order) : $posB);
        });

        // التأكد من أن جميع البيانات تحتوي على جميع الـ labels، مع قيم صفرية للمفقود
        $toiletCounts = [];
        $toiletColors = [];
        foreach ($allLabels as $label) {
            $toiletCounts[] = $toiletDataRaw->get($label, 0);
            $toiletColors[] = $this->getColorForLabel($label); // لون لكل شريط
        }

        $yardCounts = [];
        $yardColors = [];
        foreach ($allLabels as $label) {
            $yardCounts[] = $yardDataRaw->get($label, 0);
            $yardColors[] = $this->getColorForLabel($label); // لون لكل شريط
        }

        return [
            'labels' => $allLabels,
            'datasets' => [
                [
                    'label' => 'نظافة دورات المياه',
                    'data' => $toiletCounts,
                    'backgroundColor' => $toiletColors, // مصفوفة ألوان لكل شريط
                    'borderColor' => $toiletColors,
                    'borderWidth' => 1,
                    'borderRadius' => 5,
                ],
                [
                    'label' => 'نظافة الساحات والممرات',
                    'data' => $yardCounts,
                    'backgroundColor' => $yardColors, // مصفوفة ألوان لكل شريط
                    'borderColor' => $yardColors,
                    'borderWidth' => 1,
                    'borderRadius' => 5,
                ],
            ],
        ];
    }

    /**
     * جلب بيانات الجدول التفصيلية والملخص لتقرير الاستبيانات.
     * يعتمد على قيم الاستبيان المخزنة في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
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
            'good' => ['category' => 'good', 'score' => 3], // هذه الفئة مستخدمة في بعض الحقول مثل toilet_cleanliness
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
            // قيم أخرى قد تحتاج إلى تعيين نقاط لتضمينها في المتوسط العام
            'always' => ['category' => 'excellent', 'score' => 5], // hygiene_supplies, water_availability
            'often' => ['category' => 'very_good', 'score' => 4],
            'rarely' => ['category' => 'poor', 'score' => 1],
            'never' => ['category' => 'poor', 'score' => 1],
            'everywhere' => ['category' => 'excellent', 'score' => 5], // water_trams_distribution
            'needs_more' => ['category' => 'acceptable', 'score' => 2],
            'not_enough' => ['category' => 'poor', 'score' => 1],
            'clearly' => ['category' => 'excellent', 'score' => 5], // cleaning_teams
            'sometimes' => ['category' => 'good', 'score' => 3],
            'not_noticed' => ['category' => 'poor', 'score' => 1],
            'not_available' => ['category' => 'poor', 'score' => 1], // bedding_condition
            'needs_care' => ['category' => 'acceptable', 'score' => 2],
            'excellent' => ['category' => 'excellent', 'score' => 5], // ventilation, lighting (إذا كانت القيم هي نفسها)
            'poor' => ['category' => 'poor', 'score' => 1], // ventilation
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
            // 5 نقاط = 100%، 1 نقطة = 0% (أو 20% إذا كانت 1 هي الأقل)
            // (متوسط_النقاط - 1) / (5 - 1) * 100
            $avgSatisfaction = ($responseCount > 0) ? (($totalScore / $responseCount - 1) / 4) * 100 : 0;

            $detailedStatistics[] = [
                'indicator' => $indicatorLabel,
                'excellent' => $counts['excellent'],
                'very_good' => $counts['very_good'],
                'good' => $counts['good'], // هذه الفئة قد تكون غير مستخدمة لبعض المؤشرات
                'acceptable' => $counts['acceptable'],
                'poor' => $counts['poor'],
                'total' => $responseCount,
                'avg_satisfaction' => round($avgSatisfaction, 2),
            ];
        };

        // 2. معالجة كل مؤشر من مؤشرات الاستبيان
        $processIndicator($surveys, 'overall_satisfaction', 'الرضا العام');
        $processIndicator($surveys, 'toilet_cleanliness', 'نظافة دورات المياه');
        $processIndicator($surveys, 'hygiene_supplies', 'توفر مستلزمات النظافة');
        $processIndicator($surveys, 'yard_cleanliness', 'نظافة الساحات والممرات');
        $processIndicator($surveys, 'cleaning_teams', 'ملاحظة فرق التنظيف');
        $processIndicator($surveys, 'hall_cleanliness', 'نظافة القاعات');
        $processIndicator($surveys, 'bedding_condition', 'حالة الفرش والبطانيات');
        $processIndicator($surveys, 'ventilation', 'التهوية');
        $processIndicator($surveys, 'lighting', 'الإضاءة');
        $processIndicator($surveys, 'water_trams_distribution', 'توزيع ترامز الماء');
        $processIndicator($surveys, 'water_trams_cleanliness', 'نظافة ترامز الماء');
        $processIndicator($surveys, 'water_availability', 'توفر الماء');


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
                // نستبعد المؤشرات التي ليس لها معنى في متوسط الرضا العام مثل 'ملاحظة فرق التنظيف'
                // أو نعتبر فقط مؤشرات الرضا المباشرة
                if (in_array($stat['indicator'], ['الرضا العام', 'نظافة دورات المياه', 'نظافة القاعات', 'نظافة ترامز الماء', 'نظافة الساحات والممرات', 'حالة الفرش والبطانيات', 'التهوية', 'الإضاءة', 'توفر مستلزمات النظافة', 'توفر الماء'])) {
                    $overallSatisfactionSum += ($stat['avg_satisfaction'] * $stat['total']);
                    $totalResponsesAcrossIndicators += $stat['total'];
                }


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
        return [
            'detailed_statistics' => $detailedStatistics,
            'summary' => $summary
        ];
    }

    /**
     * جلب جميع بيانات المخططات والجدول والملخص في طلب API واحد.
     * هذه هي الدالة التي سيتم استدعاؤها من الواجهة الأمامية.
     * الرابط: /api/survey-data
     */
    public function getAllSurveyData(Request $request)
    {
        // Gate::authorize('view_survey_stats'); // قم بإلغاء التعليق إذا كنت تستخدم Gates

        // جلب بيانات كل مخطط
        $satisfactionData = $this->getSatisfactionPieChartData($request);
        $hallCleanlinessData = $this->getHallCleanlinessChartData($request);
        $waterTramsCleanlinessData = $this->getWaterTramsCleanlinessChartData($request);
        $facilitiesCleanlinessData = $this->getFacilitiesCleanlinessChartData($request); // يحتوي على دورات المياه والساحات

        // تجميع بيانات المخططات تحت مفتاح chartData
        $chartData = [
            'satisfaction' => $satisfactionData,
            'hallCleanliness' => $hallCleanlinessData,
            'waterTramsCleanliness' => $waterTramsCleanlinessData,
            'restroomCleanliness' => $facilitiesCleanlinessData['datasets'][0]['data'] ?? [], // بيانات دورات المياه
            'courtyardsCleanliness' => $facilitiesCleanlinessData['datasets'][1]['data'] ?? [], // بيانات الساحات والممرات
            // نحتاج أيضًا إلى إرسال labels منفصلة لدورات المياه والساحات إذا كانت الـ frontend تتوقعها
            'restroomCleanlinessLabels' => $facilitiesCleanlinessData['labels'] ?? [],
            'courtyardsCleanlinessLabels' => $facilitiesCleanlinessData['labels'] ?? [],
            // إرسال الألوان لكل dataset لتطبيقها في الواجهة الأمامية
            'restroomCleanlinessColors' => $facilitiesCleanlinessData['datasets'][0]['backgroundColor'] ?? [],
            'courtyardsCleanlinessColors' => $facilitiesCleanlinessData['datasets'][1]['backgroundColor'] ?? [],
        ];

        // جلب بيانات الجدول والملخص
        $tableAndSummary = $this->getTableSummaryData($request);

        return response()->json([
            'chartData' => $chartData,
            'tableData' => $tableAndSummary['detailed_statistics'],
            'summaryData' => $tableAndSummary['summary'],
        ]);
    }
}
