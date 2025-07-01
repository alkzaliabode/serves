<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon; // لإدارة التواريخ

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
     */
    protected function applyDateFilters(Request $request, $query)
    {
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }
        return $query;
    }

    /**
     * دالة مساعدة لترجمة قيم الاستبيان.
     */
    protected function translateSurveyValue(string $key, string $value): string
    {
        $translations = [
            'overall_satisfaction' => [
                'very_satisfied' => 'راض جدًا',
                'satisfied' => 'راض',
                'acceptable' => 'مقبول',
                'dissatisfied' => 'غير راض',
            ],
            'toilet_cleanliness' => [
                'excellent' => 'ممتازة',
                'very_good' => 'جيدة جدًا',
                'good' => 'جيدة',
                'acceptable' => 'مقبولة',
                'poor' => 'سيئة',
            ],
            'yard_cleanliness' => [
                'clean' => 'نظيفة',
                'needs_improvement' => 'تحتاج إلى تحسين',
                'dirty' => 'غير نظيفة',
            ],
            'hall_cleanliness' => [
                'very_clean' => 'نظيفة جدًا',
                'clean' => 'نظيفة',
                'needs_improvement' => 'تحتاج إلى تحسين',
                'dirty' => 'غير نظيفة',
            ],
            'water_trams_cleanliness' => [
                'very_clean' => 'نظيفة جدًا',
                'clean' => 'نظيفة',
                'needs_improvement' => 'تحتاج إلى تحسين',
                'dirty' => 'غير نظيفة',
            ],
        ];
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
                    'backgroundColor' => ['#4CAF50', '#2196F3', '#FFC107', '#F44336'], // ألوان افتراضية، سيتم تجاوزها بـ JS
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
                // ترتيب مخصص للقيم لضمان ترتيب منطقي (ممتازة، جيدة جدا، ..., سيئة)
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
}
