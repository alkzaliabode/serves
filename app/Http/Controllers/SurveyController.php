<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Support\SurveyFields;
use App\Support\SurveyWorkbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SurveyController extends Controller
{
    /**
     * عرض قائمة بسجلات الاستبيانات مع الفلاتر والبحث.
     */
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

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

        return view('Surveys.index', compact(
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
        return view('Surveys.create', ['survey' => new Survey]);
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
        return view('Surveys.show', compact('survey'));
    }

    /**
     * عرض نموذج تعديل استبيان موجود.
     */
    public function edit(Survey $survey)
    {
        return view('Surveys.edit', compact('survey'));
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

    private function filteredQuery(Request $request)
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
            $query->where(function ($query) use ($search) {
                $query->where('survey_number', 'like', '%'.$search.'%')
                    ->orWhere('problems_faced', 'like', '%'.$search.'%')
                    ->orWhere('suggestions', 'like', '%'.$search.'%');
            });
        }

        return $query;
    }

    public function export(Request $request, SurveyWorkbook $workbook)
    {
        $request->validate(['format' => 'nullable|in:excel,pdf']);
        $fields = SurveyFields::all();
        $surveys = $this->filteredQuery($request)->orderByDesc('created_at')->get();
        $rows = $surveys->map(function ($survey) use ($fields) {
            $row = [];
            foreach ($fields as $key => $field) {
                $value = $survey->{$key};
                $row[] = $field['options'][$value] ?? $value ?? '';
            }

            return $row;
        })->all();
        $headers = array_column($fields, 'label');
        if ($request->input('format') === 'pdf') {
            return view('Surveys.pdf', compact('headers', 'rows'));
        }

        return response()->download($workbook->write([$headers, ...$rows]), 'surveys_'.now()->format('Ymd_His').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function template(SurveyWorkbook $workbook)
    {
        return response()->download($workbook->write([array_column(SurveyFields::all(), 'label')]), 'surveys_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function import(Request $request, SurveyWorkbook $workbook)
    {
        $request->validate(['file' => 'required|file|extensions:xlsx|max:10240']);
        // Give the ZIP reader a known extension, independent of the upload temp name.
        $path = $request->file('file')->storeAs('survey-imports', (string) Str::uuid().'.xlsx', 'local');
        try {
            $rows = $workbook->read(\Illuminate\Support\Facades\Storage::disk('local')->path($path));
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages(['file' => $exception->getMessage()]);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['file' => 'تعذر قراءة ملف Excel. استخدم النموذج المرفق وملفاً بصيغة xlsx.']);
        } finally {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
        }
        $records = $this->validateImportRows($rows);
        DB::transaction(function () use ($records) {
            foreach ($records as $record) {
                Survey::create($record);
            }
        });

        return redirect()->route('surveys.index')->with('success', 'تم استيراد '.count($records).' استبيان بنجاح.');
    }

    protected function validateImportRows(array $rows): array
    {
        $fields = SurveyFields::all();
        if (($rows[1] ?? []) !== array_column($fields, 'label')) {
            throw ValidationException::withMessages(['file' => 'رؤوس الأعمدة وترتيبها يجب أن تطابق نموذج Excel وصفحة الإضافة تماماً.']);
        }
        unset($rows[1]);
        $records = [];
        $errors = [];
        foreach ($rows as $number => $row) {
            if (! array_filter($row, fn ($value) => $value !== '')) {
                continue;
            }
            $record = [];
            foreach (array_keys($fields) as $i => $key) {
                $value = $row[$i];
                $options = $fields[$key]['options'];
                $translated = array_search($value, $options, true);
                $record[$key] = $value === '' ? null : ($translated !== false ? $translated : $value);
            }
            $validator = Validator::make($record, $this->getValidationRules(), [], array_map(fn ($field) => $field['label'], $fields));
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $key => $messages) {
                    $errors[] = 'الصف '.$number.' — '.$fields[$key]['label'].': قيمة مفقودة أو غير صالحة. الخيارات: '.implode('، ', $fields[$key]['options']);
                    if (count($errors) >= 50) {
                        break 2;
                    }
                }
            } else {
                $records[] = $record;
            }
        }
        if ($errors) {
            throw ValidationException::withMessages(['file' => $errors]);
        }
        if (! $records) {
            throw ValidationException::withMessages(['file' => 'الملف لا يحتوي على بيانات للاستيراد.']);
        }

        return $records;
    }

    protected function getValidationRules(): array
    {
        $rules = [];
        foreach (SurveyFields::all() as $key => $field) {
            $rules[$key] = [$field['required'] ? 'required' : 'nullable', 'string'];
            $rules[$key][] = $field['options'] ? \Illuminate\Validation\Rule::in(array_keys($field['options'])) : 'max:65535';
        }
        $rules['survey_number'] = ['nullable', 'string', \Illuminate\Validation\Rule::unique('surveys', 'survey_number')->ignore(request()->route('survey')?->id)];

        return $rules;
    }
}
