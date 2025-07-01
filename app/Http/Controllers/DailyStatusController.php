<?php

namespace App\Http\Controllers;

use App\Models\DailyStatus;
use App\Models\Employee; // تأكد من استيراد نموذج الموظف
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Alkoumi\LaravelHijriDate\Hijri; // تأكد من تثبيت هذه المكتبة: composer require alkoumi/laravel-hijri-date

class DailyStatusController extends Controller
{
    /**
     * عرض قائمة بالمواقف اليومية.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع المواقف اليومية مرتبة حسب التاريخ تنازلياً
        $dailyStatuses = DailyStatus::orderBy('date', 'desc')->paginate(10);
        return view('daily_statuses.index', compact('dailyStatuses'));
    }

    /**
     * عرض نموذج إنشاء موقف يومي جديد.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // جلب جميع الموظفين للاستخدام في حقول الاختيار
        $employees = Employee::all();
        // إعداد بيانات الموقف الافتراضية
        $defaultDate = now()->toDateString();
        $hijriDate = self::convertToHijri($defaultDate);
        $dayName = self::getDayName($defaultDate);
        $totalEmployees = Employee::where('is_active', 1)->count();
        $totalRequired = 86; // القيمة الافتراضية للملاك

        return view('daily_statuses.create', compact('employees', 'defaultDate', 'hijriDate', 'dayName', 'totalEmployees', 'totalRequired'));
    }

    /**
     * تخزين موقف يومي جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // قواعد التحقق من صحة البيانات
        $validatedData = $request->validate([
            'date' => 'required|date',
            'periodic_leaves' => 'nullable|array',
            'periodic_leaves.*.employee_id' => 'required|exists:employees,id',
            'periodic_leaves.*.employee_number' => 'required|numeric',
            'periodic_leaves.*.employee_name' => 'required|string',

            'annual_leaves' => 'nullable|array',
            'annual_leaves.*.employee_id' => 'required|exists:employees,id',
            'annual_leaves.*.employee_number' => 'required|numeric',
            'annual_leaves.*.employee_name' => 'required|string',

            'temporary_leaves' => 'nullable|array',
            'temporary_leaves.*.employee_id' => 'required|exists:employees,id',
            'temporary_leaves.*.employee_number' => 'required|numeric',
            'temporary_leaves.*.employee_name' => 'required|string',
            'temporary_leaves.*.from_time' => 'required|date_format:H:i',
            'temporary_leaves.*.to_time' => 'required|date_format:H:i',

            'eid_leaves' => 'nullable|array',
            'eid_leaves.*.eid_type' => 'required|string',
            'eid_leaves.*.employee_id' => 'required|exists:employees,id',
            'eid_leaves.*.employee_number' => 'required|numeric',
            'eid_leaves.*.employee_name' => 'required|string',

            'guard_rest' => 'nullable|array',
            'guard_rest.*.employee_id' => 'required|exists:employees,id',
            'guard_rest.*.employee_number' => 'required|numeric',
            'guard_rest.*.employee_name' => 'required|string',

            'unpaid_leaves' => 'nullable|array',
            'unpaid_leaves.*.employee_id' => 'required|exists:employees,id',
            'unpaid_leaves.*.employee_number' => 'required|numeric',
            'unpaid_leaves.*.employee_name' => 'required|string',

            'absences' => 'nullable|array',
            'absences.*.employee_id' => 'required|exists:employees,id',
            'absences.*.employee_number' => 'required|numeric',
            'absences.*.employee_name' => 'required|string',
            'absences.*.from_date' => 'required|date',
            'absences.*.to_date' => 'required|date',

            'long_leaves' => 'nullable|array',
            'long_leaves.*.employee_id' => 'required|exists:employees,id',
            'long_leaves.*.employee_number' => 'required|numeric',
            'long_leaves.*.employee_name' => 'required|string',
            'long_leaves.*.from_date' => 'required|date',
            'long_leaves.*.to_date' => 'required|date',

            'sick_leaves' => 'nullable|array',
            'sick_leaves.*.employee_id' => 'required|exists:employees,id',
            'sick_leaves.*.employee_number' => 'required|numeric',
            'sick_leaves.*.employee_name' => 'required|string',
            'sick_leaves.*.from_date' => 'required|date',
            'sick_leaves.*.to_date' => 'required|date',

            'bereavement_leaves' => 'nullable|array',
            'bereavement_leaves.*.employee_id' => 'required|exists:employees,id',
            'bereavement_leaves.*.employee_number' => 'required|numeric',
            'bereavement_leaves.*.employee_name' => 'required|string',

            'custom_usages' => 'nullable|array',
            'custom_usages.*.employee_id' => 'required|exists:employees,id',
            'custom_usages.*.employee_number' => 'required|numeric',
            'custom_usages.*.employee_name' => 'required|string',
            'custom_usages.*.usage_details' => 'required|string',

            'total_required' => 'required|numeric',
            'organizer_employee_id' => 'required|exists:employees,id',
            'organizer_employee_name' => 'required|string',
        ]);

        // حساب الحقول المحسوبة
        $totalEmployees = Employee::where('is_active', 1)->count();
        $shortage = $validatedData['total_required'] - $totalEmployees;

        $paidLeavesCount = count($validatedData['annual_leaves'] ?? [])
                    + count($validatedData['periodic_leaves'] ?? [])
                    + count($validatedData['sick_leaves'] ?? [])
                    + count($validatedData['bereavement_leaves'] ?? []);

        // حساب إجازات الأعياد بشكل منفصل للتأكد من وجود employee_id
        $eidLeavesCount = 0;
        foreach ($validatedData['eid_leaves'] ?? [] as $eidLeave) {
            if (isset($eidLeave['employee_id'])) {
                $eidLeavesCount++;
            }
        }
        $paidLeavesCount += $eidLeavesCount;

        $unpaidLeavesCount = count($validatedData['unpaid_leaves'] ?? []);
        $absencesCount = count($validatedData['absences'] ?? []);
        $temporaryLeavesCount = count($validatedData['temporary_leaves'] ?? []);
        $guardRestCount = count($validatedData['guard_rest'] ?? []);
        $customUsagesCount = count($validatedData['custom_usages'] ?? []);

        $actualAttendance = $totalEmployees - ($paidLeavesCount + $unpaidLeavesCount + $absencesCount + $temporaryLeavesCount + $guardRestCount + $customUsagesCount);

        // إنشاء الموقف اليومي
        $dailyStatus = DailyStatus::create([
            'date' => $validatedData['date'],
            'hijri_date' => self::convertToHijri($validatedData['date']),
            'day_name' => self::getDayName($validatedData['date']),
            'periodic_leaves' => $validatedData['periodic_leaves'] ?? [],
            'annual_leaves' => $validatedData['annual_leaves'] ?? [],
            'temporary_leaves' => $validatedData['temporary_leaves'] ?? [],
            'eid_leaves' => $validatedData['eid_leaves'] ?? [],
            'guard_rest' => $validatedData['guard_rest'] ?? [],
            'unpaid_leaves' => $validatedData['unpaid_leaves'] ?? [],
            'absences' => $validatedData['absences'] ?? [],
            'long_leaves' => $validatedData['long_leaves'] ?? [],
            'sick_leaves' => $validatedData['sick_leaves'] ?? [],
            'bereavement_leaves' => $validatedData['bereavement_leaves'] ?? [],
            'custom_usages' => $validatedData['custom_usages'] ?? [], // حفظ الاستخدامات المخصصة
            'total_required' => $validatedData['total_required'],
            'total_employees' => $totalEmployees,
            'shortage' => $shortage,
            'actual_attendance' => $actualAttendance,
            'paid_leaves_count' => $paidLeavesCount, // حفظ العدد المحسوب
            'unpaid_leaves_count' => $unpaidLeavesCount, // حفظ العدد المحسوب
            'absences_count' => $absencesCount, // حفظ العدد المحسوب
            'organizer_employee_id' => $validatedData['organizer_employee_id'],
            'organizer_employee_name' => $validatedData['organizer_employee_name'],
        ]);

        return redirect()->route('daily-statuses.index')->with('success', 'تم إنشاء الموقف اليومي بنجاح.');
    }

    /**
     * عرض تفاصيل موقف يومي محدد.
     *
     * @param  \App\Models\DailyStatus  $dailyStatus
     * @return \Illuminate\View\View
     */
    public function show(DailyStatus $dailyStatus)
    {
        // جلب جميع الموظفين للاستخدام في عرض التفاصيل إذا لزم الأمر
        $employees = Employee::all();
        return view('daily_statuses.show', compact('dailyStatus', 'employees'));
    }

    /**
     * عرض نموذج تعديل موقف يومي محدد.
     *
     * @param  \App\Models\DailyStatus  $dailyStatus
     * @return \Illuminate\View\View
     */
    public function edit(DailyStatus $dailyStatus)
    {
        // جلب جميع الموظفين للاستخدام في حقول الاختيار
        $employees = Employee::all();
        // القيم المحسوبة التي قد تحتاج لإعادة حسابها ديناميكياً في الواجهة أو عند الحفظ
        $totalEmployees = Employee::where('is_active', 1)->count();
        $hijriDate = self::convertToHijri($dailyStatus->date);
        $dayName = self::getDayName($dailyStatus->date);

        return view('daily_statuses.edit', compact('dailyStatus', 'employees', 'totalEmployees', 'hijriDate', 'dayName'));
    }

    /**
     * تحديث موقف يومي محدد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyStatus  $dailyStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, DailyStatus $dailyStatus)
    {
        // قواعد التحقق من صحة البيانات (نفس قواعد التخزين)
        $validatedData = $request->validate([
            'date' => 'required|date',
            'periodic_leaves' => 'nullable|array',
            'periodic_leaves.*.employee_id' => 'required|exists:employees,id',
            'periodic_leaves.*.employee_number' => 'required|numeric',
            'periodic_leaves.*.employee_name' => 'required|string',

            'annual_leaves' => 'nullable|array',
            'annual_leaves.*.employee_id' => 'required|exists:employees,id',
            'annual_leaves.*.employee_number' => 'required|numeric',
            'annual_leaves.*.employee_name' => 'required|string',

            'temporary_leaves' => 'nullable|array',
            'temporary_leaves.*.employee_id' => 'required|exists:employees,id',
            'temporary_leaves.*.employee_number' => 'required|numeric',
            'temporary_leaves.*.employee_name' => 'required|string',
            'temporary_leaves.*.from_time' => 'required|date_format:H:i',
            'temporary_leaves.*.to_time' => 'required|date_format:H:i',

            'eid_leaves' => 'nullable|array',
            'eid_leaves.*.eid_type' => 'required|string',
            'eid_leaves.*.employee_id' => 'required|exists:employees,id',
            'eid_leaves.*.employee_number' => 'required|numeric',
            'eid_leaves.*.employee_name' => 'required|string',

            'guard_rest' => 'nullable|array',
            'guard_rest.*.employee_id' => 'required|exists:employees,id',
            'guard_rest.*.employee_number' => 'required|numeric',
            'guard_rest.*.employee_name' => 'required|string',

            'unpaid_leaves' => 'nullable|array',
            'unpaid_leaves.*.employee_id' => 'required|exists:employees,id',
            'unpaid_leaves.*.employee_number' => 'required|numeric',
            'unpaid_leaves.*.employee_name' => 'required|string',

            'absences' => 'nullable|array',
            'absences.*.employee_id' => 'required|exists:employees,id',
            'absences.*.employee_number' => 'required|numeric',
            'absences.*.employee_name' => 'required|string',
            'absences.*.from_date' => 'required|date',
            'absences.*.to_date' => 'required|date',

            'long_leaves' => 'nullable|array',
            'long_leaves.*.employee_id' => 'required|exists:employees,id',
            'long_leaves.*.employee_number' => 'required|numeric',
            'long_leaves.*.employee_name' => 'required|string',
            'long_leaves.*.from_date' => 'required|date',
            'long_leaves.*.to_date' => 'required|date',

            'sick_leaves' => 'nullable|array',
            'sick_leaves.*.employee_id' => 'required|exists:employees,id',
            'sick_leaves.*.employee_number' => 'required|numeric',
            'sick_leaves.*.employee_name' => 'required|string',
            'sick_leaves.*.from_date' => 'required|date',
            'sick_leaves.*.to_date' => 'required|date',

            'bereavement_leaves' => 'nullable|array',
            'bereavement_leaves.*.employee_id' => 'required|exists:employees,id',
            'bereavement_leaves.*.employee_number' => 'required|numeric',
            'bereavement_leaves.*.employee_name' => 'required|string',

            'custom_usages' => 'nullable|array',
            'custom_usages.*.employee_id' => 'required|exists:employees,id',
            'custom_usages.*.employee_number' => 'required|numeric',
            'custom_usages.*.employee_name' => 'required|string',
            'custom_usages.*.usage_details' => 'required|string',

            'total_required' => 'required|numeric',
            'organizer_employee_id' => 'required|exists:employees,id',
            'organizer_employee_name' => 'required|string',
        ]);

        // حساب الحقول المحسوبة
        $totalEmployees = Employee::where('is_active', 1)->count();
        $shortage = $validatedData['total_required'] - $totalEmployees;

        $paidLeavesCount = count($validatedData['annual_leaves'] ?? [])
                    + count($validatedData['periodic_leaves'] ?? [])
                    + count($validatedData['sick_leaves'] ?? [])
                    + count($validatedData['bereavement_leaves'] ?? []);

        $eidLeavesCount = 0;
        foreach ($validatedData['eid_leaves'] ?? [] as $eidLeave) {
            if (isset($eidLeave['employee_id'])) {
                $eidLeavesCount++;
            }
        }
        $paidLeavesCount += $eidLeavesCount;

        $unpaidLeavesCount = count($validatedData['unpaid_leaves'] ?? []);
        $absencesCount = count($validatedData['absences'] ?? []);
        $temporaryLeavesCount = count($validatedData['temporary_leaves'] ?? []);
        $guardRestCount = count($validatedData['guard_rest'] ?? []);
        $customUsagesCount = count($validatedData['custom_usages'] ?? []);

        $actualAttendance = $totalEmployees - ($paidLeavesCount + $unpaidLeavesCount + $absencesCount + $temporaryLeavesCount + $guardRestCount + $customUsagesCount);

        // تحديث الموقف اليومي
        $dailyStatus->update([
            'date' => $validatedData['date'],
            'hijri_date' => self::convertToHijri($validatedData['date']),
            'day_name' => self::getDayName($validatedData['date']),
            'periodic_leaves' => $validatedData['periodic_leaves'] ?? [],
            'annual_leaves' => $validatedData['annual_leaves'] ?? [],
            'temporary_leaves' => $validatedData['temporary_leaves'] ?? [],
            'eid_leaves' => $validatedData['eid_leaves'] ?? [],
            'guard_rest' => $validatedData['guard_rest'] ?? [],
            'unpaid_leaves' => $validatedData['unpaid_leaves'] ?? [],
            'absences' => $validatedData['absences'] ?? [],
            'long_leaves' => $validatedData['long_leaves'] ?? [],
            'sick_leaves' => $validatedData['sick_leaves'] ?? [],
            'bereavement_leaves' => $validatedData['bereavement_leaves'] ?? [],
            'custom_usages' => $validatedData['custom_usages'] ?? [], // تحديث الاستخدامات المخصصة
            'total_required' => $validatedData['total_required'],
            'total_employees' => $totalEmployees,
            'shortage' => $shortage,
            'actual_attendance' => $actualAttendance,
            'paid_leaves_count' => $paidLeavesCount, // حفظ العدد المحسوب
            'unpaid_leaves_count' => $unpaidLeavesCount, // حفظ العدد المحسوب
            'absences_count' => $absencesCount, // حفظ العدد المحسوب
            'organizer_employee_id' => $validatedData['organizer_employee_id'],
            'organizer_employee_name' => $validatedData['organizer_employee_name'],
        ]);

        return redirect()->route('daily-statuses.index')->with('success', 'تم تحديث الموقف اليومي بنجاح.');
    }

    /**
     * حذف موقف يومي محدد.
     *
     * @param  \App\Models\DailyStatus  $dailyStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DailyStatus $dailyStatus)
    {
        $dailyStatus->delete();
        return redirect()->route('daily-statuses.index')->with('success', 'تم حذف الموقف اليومي بنجاح.');
    }

    /**
     * عرض صفحة الطباعة لموقف يومي محدد.
     *
     * @param  \App\Models\DailyStatus  $dailyStatus
     * @return \Illuminate\View\View
     */
    public function print(DailyStatus $dailyStatus)
    {
        // يمكنك هنا جلب أي بيانات إضافية تحتاجها للطباعة
        // وتمريرها إلى العرض الخاص بالطباعة
        return view('daily_statuses.print', compact('dailyStatus'));
    }

    /**
     * تحويل التاريخ الميلادي إلى هجري.
     *
     * @param string $gregorianDate التاريخ الميلادي
     * @return string التاريخ الهجري
     */
    protected static function convertToHijri(string $gregorianDate): string
    {
        try {
            // تأكد من أن التاريخ المدخل بصيغة صالحة
            return Hijri::Date('j F Y', $gregorianDate);
        } catch (\Exception $e) {
            // التعامل مع الأخطاء إذا كان التاريخ غير صالح
            return 'تاريخ غير صالح';
        }
    }

    /**
     * الحصول على اسم اليوم بالعربية.
     *
     * @param string $date التاريخ الميلادي
     * @return string اسم اليوم
     */
    protected static function getDayName(string $date): string
    {
        $days = [
            'Sunday' => 'الأحد',
            'Monday' => 'الإثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت',
        ];

        try {
            $day = Carbon::parse($date)->format('l');
            return $days[$day] ?? $day;
        } catch (\Exception $e) {
            // التعامل مع الأخطاء إذا كان التاريخ غير صالح
            return 'يوم غير معروف';
        }
    }
}
