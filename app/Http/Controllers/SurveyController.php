<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str; // لاستخدام Str::limit

class SurveyController extends Controller
{
    /**
     * عرض قائمة بسجلات الاستبيانات مع الفلاتر والبحث.
     */
    public function index(Request $request)
    {
        $query = Survey::query();

        // تطبيق الفلاتر
        if ($request->filled('overall_satisfaction')) {
            $query->where('overall_satisfaction', $request->input('overall_satisfaction'));
        }
        if ($request->filled('visit_count')) {
            $query->where('visit_count', $request->input('visit_count'));
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }
        if ($request->filled('age_group')) {
            $query->where('age_group', $request->input('age_group'));
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        // البحث برقم الاستبيان أو الملاحظات
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('survey_number', 'like', '%' . $search . '%')
                  ->orWhere('problems_faced', 'like', '%' . $search . '%')
                  ->orWhere('suggestions', 'like', '%' . $search . '%');
        }

        $surveys = $query->orderBy('created_at', 'desc')->paginate(10);

        // خيارات الفلاتر للعرض في الواجهة
        $satisfactionOptions = [
            'very_satisfied' => 'راض جدًا',
            'satisfied' => 'راض',
            'acceptable' => 'مقبول',
            'dissatisfied' => 'غير راض',
        ];

        $visitCountOptions = [
            'first_time' => 'أول مرة',
            '2_5_times' => '2-5 مرات',
            'over_5_times' => 'أكثر من 5',
        ];

        $genderOptions = [
            'male' => 'ذكر',
            'female' => 'أنثى',
        ];

        $ageGroupOptions = [
            'under_18' => 'أقل من 18',
            '18_30' => '18-30',
            '30_45' => '30-45',
            '45_60' => '45-60',
            'over_60' => 'أكثر من 60',
        ];

        return view('surveys.index', compact(
            'surveys',
            'satisfactionOptions',
            'visitCountOptions',
            'genderOptions',
            'ageGroupOptions'
        ));
    }

    /**
     * عرض نموذج إنشاء استبيان جديد.
     */
    public function create()
    {
        // تمرير كائن Survey فارغ لنموذج الإنشاء
        return view('surveys.create', ['survey' => new Survey()]);
    }

    /**
     * تخزين سجل استبيان جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->getValidationRules());

        Survey::create($validatedData);

        Session::flash('success', 'تم إضافة الاستبيان بنجاح.');
        return redirect()->route('surveys.index');
    }

    /**
     * عرض تفاصيل استبيان معين.
     */
    public function show(Survey $survey)
    {
        return view('surveys.show', compact('survey'));
    }

    /**
     * عرض نموذج تعديل استبيان موجود.
     */
    public function edit(Survey $survey)
    {
        return view('surveys.edit', compact('survey'));
    }

    /**
     * تحديث سجل استبيان موجود في قاعدة البيانات.
     */
    public function update(Request $request, Survey $survey)
    {
        $validatedData = $request->validate($this->getValidationRules());

        $survey->update($validatedData);

        Session::flash('success', 'تم تحديث الاستبيان بنجاح.');
        return redirect()->route('surveys.index');
    }

    /**
     * حذف سجل استبيان من قاعدة البيانات.
     */
    public function destroy(Survey $survey)
    {
        $survey->delete();
        Session::flash('success', 'تم حذف الاستبيان بنجاح.');
        return redirect()->route('surveys.index');
    }

    /**
     * تصدير بيانات الاستبيانات إلى ملف CSV.
     */
    public function export(Request $request)
    {
        $surveys = Survey::query();

        // تطبيق نفس الفلاتر المستخدمة في صفحة الفهرس
        if ($request->filled('overall_satisfaction')) {
            $surveys->where('overall_satisfaction', $request->input('overall_satisfaction'));
        }
        if ($request->filled('visit_count')) {
            $surveys->where('visit_count', $request->input('visit_count'));
        }
        if ($request->filled('gender')) {
            $surveys->where('gender', $request->input('gender'));
        }
        if ($request->filled('age_group')) {
            $surveys->where('age_group', $request->input('age_group'));
        }
        if ($request->filled('from_date')) {
            $surveys->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $surveys->whereDate('created_at', '<=', $request->input('to_date'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $surveys->where('survey_number', 'like', '%' . $search . '%')
                    ->orWhere('problems_faced', 'like', '%' . $search . '%')
                    ->orWhere('suggestions', 'like', '%' . $search . '%');
        }

        $surveys = $surveys->get();

        $fileName = 'surveys_' . Carbon::now()->format('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8", // تحديد UTF-8
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // تحويل القيم الإنجليزية إلى العربية للعرض في التصدير
        $columnHeadings = [
            'survey_number' => 'رقم الاستبيان',
            'created_at' => 'تاريخ الإدخال',
            'gender' => 'الجنس',
            'age_group' => 'الفئة العمرية',
            'visit_count' => 'عدد الزيارات',
            'stay_duration' => 'مدة الإقامة',
            'toilet_cleanliness' => 'نظافة دورات المياه',
            'hygiene_supplies' => 'توفر مستلزمات النظافة',
            'yard_cleanliness' => 'نظافة الساحات والممرات',
            'cleaning_teams' => 'فرق التنظيف',
            'hall_cleanliness' => 'نظافة القاعات',
            'bedding_condition' => 'حالة البطائن والفرش',
            'ventilation' => 'التهوية',
            'lighting' => 'الإضاءة',
            'water_trams_distribution' => 'توزيع ترامز الماء',
            'water_trams_cleanliness' => 'نظافة ترامز الماء',
            'water_availability' => 'توفر مياه الشرب',
            'overall_satisfaction' => 'مستوى الرضا العام',
            'problems_faced' => 'المشاكل التي واجهتها',
            'suggestions' => 'اقتراحات للتحسين',
        ];

        $callback = function() use ($surveys, $columnHeadings) {
            $file = fopen('php://output', 'w');
            // إضافة BOM لضمان عرض UTF-8 في Excel
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); 
            
            fputcsv($file, array_values($columnHeadings)); // رؤوس الأعمدة

            foreach ($surveys as $survey) {
                $row = [];
                foreach ($columnHeadings as $key => $value) {
                    $val = $survey->{$key};
                    // تحويل القيم الإنجليزية إلى العربية
                    $row[$key] = $this->translateSurveyValue($key, $val);
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * دالة مساعدة لقواعد التحقق.
     */
    protected function getValidationRules(): array
    {
        return [
            'gender' => 'nullable|in:male,female',
            'age_group' => 'nullable|in:under_18,18_30,30_45,45_60,over_60',
            'visit_count' => 'nullable|in:first_time,2_5_times,over_5_times',
            'stay_duration' => 'nullable|in:less_1h,2_3h,4_6h,over_6h',
            'toilet_cleanliness' => 'required|in:excellent,very_good,good,acceptable,poor',
            'hygiene_supplies' => 'required|in:always,often,rarely,never',
            'yard_cleanliness' => 'required|in:clean,needs_improvement,dirty',
            'cleaning_teams' => 'required|in:clearly,sometimes,rarely,not_noticed',
            'hall_cleanliness' => 'required|in:very_clean,clean,needs_improvement,dirty',
            'bedding_condition' => 'required|in:excellent,needs_care,not_clean,not_available',
            'ventilation' => 'required|in:excellent,needs_improvement,poor',
            'lighting' => 'required|in:excellent,good,needs_improvement',
            'water_trams_distribution' => 'required|in:everywhere,needs_more,not_enough',
            'water_trams_cleanliness' => 'required|in:very_clean,clean,needs_improvement,dirty',
            'water_availability' => 'required|in:always,often,rarely,not_enough',
            'overall_satisfaction' => 'required|in:very_satisfied,satisfied,acceptable,dissatisfied',
            'problems_faced' => 'nullable|string|max:65535',
            'suggestions' => 'nullable|string|max:65535',
            'survey_number' => 'nullable|string|unique:surveys,survey_number,' . (request()->route('survey')->id ?? 'NULL'),
        ];
    }

    /**
     * دالة مساعدة لترجمة قيم الاستبيان الإنجليزية إلى العربية.
     *
     * @param string $key اسم الحقل
     * @param string|null $value القيمة الإنجليزية
     * @return string القيمة المترجمة أو القيمة الأصلية إذا لم يتم العرف عليها
     */
    private function translateSurveyValue(string $key, ?string $value): string
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
        ];

        // ترجمة تاريخ الإنشاء
        if ($key === 'created_at' && $value) {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        }

        return $translations[$key][$value] ?? $value;
    }
}
