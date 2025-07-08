<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use App\Models\DailyStatus; // استيراد نموذج DailyStatus

class UniqueEmployeeInLeaves implements ValidationRule
{
    protected $allRequestData;
    protected $currentFieldName;
    protected $exceptField;
    protected $ignoreDailyStatusId; // جديد: لتجاهل السجل الحالي عند التحديث

    public function __construct(array $allRequestData, string $currentFieldName, string $exceptField = '', $ignoreDailyStatusId = null)
    {
        $this->allRequestData = $allRequestData;
        $this->currentFieldName = $currentFieldName;
        $this->exceptField = $exceptField;
        $this->ignoreDailyStatusId = $ignoreDailyStatusId; // تعيين القيمة
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentEmployeeId = $value;

        // Define all leave types that should have unique employees
        $leaveTypesToCheck = [
            'periodic_leaves',
            'annual_leaves',
            'eid_leaves',
            'guard_rest',
            'unpaid_leaves',
            'absences',
            'long_leaves',
            'sick_leaves',
            'bereavement_leaves',
            'custom_usages',
        ];

        // Filter out the current field and the exception field
        $fieldsToCompare = array_diff($leaveTypesToCheck, [$this->exceptField]); // لا تستثنِ الحقل الحالي هنا، بل في المنطق أدناه

        // التحقق من البيانات المرسلة في الطلب
        foreach ($fieldsToCompare as $fieldName) {
            $otherLeaves = Arr::get($this->allRequestData, $fieldName, []);

            foreach ($otherLeaves as $otherIndex => $otherLeave) {
                // إذا كان نفس نوع الإجازة ونفس العنصر، تجاهل التحقق
                if ($fieldName === $this->currentFieldName && $otherLeave['employee_id'] == $currentEmployeeId && (string)$otherIndex === explode('.', $attribute)[1]) {
                    continue;
                }

                if (isset($otherLeave['employee_id']) && $otherLeave['employee_id'] == $currentEmployeeId) {
                    $fail("الموظف المحدد موجود بالفعل في نوع إجازة أو استخدام آخر ضمن هذا الموقف. يرجى اختيار موظف فريد.");
                    return;
                }
            }
        }

        // التحقق من السجلات الموجودة في قاعدة البيانات (للتأكد من عدم التكرار عبر المواقف اليومية الأخرى)
        // هذا الجزء يعتمد على كيفية تخزين بيانات الإجازات. إذا كانت كمصفوفة JSON، فستكون معقدة للتحقق مباشرة من DB.
        // إذا كنت تريد التحقق من التفرد عبر *جميع* المواقف اليومية (وليس فقط ضمن الموقف الحالي)، فسيتطلب ذلك استعلامات DB معقدة
        // للبحث داخل حقول JSON. هذا يتجاوز نطاق التحقق البسيط.
        // إذا كنت تريد التفرد فقط *داخل* الموقف اليومي الواحد، فالمنطق أعلاه كافٍ.
        // إذا كان قصدك هو: "الموظف لا يمكن أن يكون في إجازة دورية ومريضة في نفس اليوم في أي موقف يومي"، فهذا يتطلب منطق DB إضافي.

        // لنفترض أننا نريد التحقق من التفرد فقط ضمن هذا الموقف اليومي الواحد (كما هو الحال في الطلب الحالي).
        // المنطق أعلاه يغطي هذا.
        // إذا كان الهدف هو التفرد عبر الأيام المختلفة (أي موظف لا يمكن أن يكون في نوعين من الإجازات في نفس اليوم، حتى لو كانا في سجلين مختلفين لـ DailyStatus)،
        // فسيتطلب ذلك استعلامات على قاعدة البيانات.
        // على سبيل المثال (مثال معقد وقد يحتاج إلى تحسين الأداء):
        /*
        if ($this->ignoreDailyStatusId) {
            $query = DailyStatus::where('id', '!=', $this->ignoreDailyStatusId)
                                ->where('date', Arr::get($this->allRequestData, 'date')); // تحقق من نفس التاريخ
        } else {
            $query = DailyStatus::where('date', Arr::get($this->allRequestData, 'date'));
        }

        foreach ($leaveTypesToCheck as $fieldName) {
            if ($fieldName === $this->exceptField) { // لا تشمل الإجازات الزمنية في هذا التحقق
                continue;
            }
            // هذا الجزء معقد جداً للتحقق داخل JSONB/TEXT fields بدون استخدام دوال DB مخصصة أو تحويلها إلى جداول منفصلة
            // مثال افتراضي (قد لا يعمل مباشرة حسب إعدادات DB ونوع الحقل):
            // $query->orWhereJsonContains($fieldName, ['employee_id' => $currentEmployeeId]);
        }

        if ($query->exists()) {
            // $fail("الموظف المحدد موجود بالفعل في نوع إجازة آخر في موقف يومي آخر لنفس التاريخ.");
            // return;
        }
        */
    }
}