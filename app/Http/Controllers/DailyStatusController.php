<?php

namespace App\Http\Controllers;

use App\Models\DailyStatus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Alkoumi\LaravelHijriDate\Hijri;
use App\Rules\UniqueEmployeeInLeaves;

class DailyStatusController extends Controller
{
    /**
     * عرض قائمة بالمواقف اليومية.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
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
        $employees = Employee::all();
        $defaultDate = now()->toDateString();
        $hijriDate = self::convertToHijri($defaultDate);
        $dayName = self::getDayName($defaultDate);
        $totalEmployees = Employee::where('is_active', 1)->count();
        $totalRequired = 86;

        // عند إنشاء موقف جديد، dailyStatus غير موجود، لذا نمرر null أو بيانات فارغة
        $dailyStatus = null; 

        return view('daily_statuses.create', compact('employees', 'defaultDate', 'hijriDate', 'dayName', 'totalEmployees', 'totalRequired', 'dailyStatus'));
    }

    /**
     * تخزين موقف يومي جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'date' => 'required|date',
            'total_required' => 'required|numeric',
            'organizer_employee_id' => 'required|exists:employees,id',
            'organizer_employee_name' => 'required|string',
        ];

        $uniqueLeaveTypes = [
            'periodic_leaves', 'annual_leaves', 'eid_leaves', 'guard_rest',
            'unpaid_leaves', 'bereavement_leaves', 'custom_usages',
        ];

        foreach ($uniqueLeaveTypes as $type) {
            $rules["{$type}"] = 'nullable|array';
            $rules["{$type}.*.employee_id"] = [
                'required',
                'exists:employees,id',
                new UniqueEmployeeInLeaves($request->all(), $type, 'temporary_leaves'),
            ];
            $rules["{$type}.*.employee_number"] = 'required|numeric';
            $rules["{$type}.*.employee_name"] = 'required|string';
        }

        $datedLeaveTypes = ['absences', 'long_leaves', 'sick_leaves'];
        foreach ($datedLeaveTypes as $type) {
            $rules["{$type}"] = 'nullable|array';
            $rules["{$type}.*.employee_id"] = [
                'required',
                'exists:employees,id',
                new UniqueEmployeeInLeaves($request->all(), $type, 'temporary_leaves'),
            ];
            $rules["{$type}.*.employee_number"] = 'required|numeric';
            $rules["{$type}.*.employee_name"] = 'required|string';
            $rules["{$type}.*.start_date"] = 'required|date';
            $rules["{$type}.*.total_days"] = 'required|integer|min:1';
        }

        $rules['temporary_leaves'] = 'nullable|array';
        $rules['temporary_leaves.*.employee_id'] = 'required|exists:employees,id';
        $rules['temporary_leaves.*.employee_number'] = 'required|numeric';
        $rules['temporary_leaves.*.employee_name'] = 'required|string';
        $rules['temporary_leaves.*.from_time'] = 'required|date_format:H:i';
        $rules['temporary_leaves.*.to_time'] = 'required|date_format:H:i';

        $rules['eid_leaves.*.eid_type'] = 'required|string';
        $rules['custom_usages.*.usage_details'] = 'required|string';

        $validatedData = $request->validate($rules);

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

        $actualAttendance = $totalEmployees - (
            $paidLeavesCount +
            $unpaidLeavesCount +
            $absencesCount +
            $temporaryLeavesCount +
            $guardRestCount +
            $customUsagesCount
        );

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
            'custom_usages' => $validatedData['custom_usages'] ?? [],
            'total_required' => $validatedData['total_required'],
            'total_employees' => $totalEmployees,
            'shortage' => $shortage,
            'actual_attendance' => $actualAttendance,
            'paid_leaves_count' => $paidLeavesCount,
            'unpaid_leaves_count' => $unpaidLeavesCount,
            'absences_count' => $absencesCount,
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
        $employees = Employee::all();
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
        $rules = [
            'date' => 'required|date',
            'total_required' => 'required|numeric',
            'organizer_employee_id' => 'required|exists:employees,id',
            'organizer_employee_name' => 'required|string',
        ];

        $uniqueLeaveTypes = [
            'periodic_leaves', 'annual_leaves', 'eid_leaves', 'guard_rest',
            'unpaid_leaves', 'bereavement_leaves', 'custom_usages',
        ];

        foreach ($uniqueLeaveTypes as $type) {
            $rules["{$type}"] = 'nullable|array';
            $rules["{$type}.*.employee_id"] = [
                'required',
                'exists:employees,id',
                new UniqueEmployeeInLeaves($request->all(), $type, 'temporary_leaves', $dailyStatus->id),
            ];
            $rules["{$type}.*.employee_number"] = 'required|numeric';
            $rules["{$type}.*.employee_name"] = 'required|string';
        }

        $datedLeaveTypes = ['absences', 'long_leaves', 'sick_leaves'];
        foreach ($datedLeaveTypes as $type) {
            $rules["{$type}"] = 'nullable|array';
            $rules["{$type}.*.employee_id"] = [
                'required',
                'exists:employees,id',
                new UniqueEmployeeInLeaves($request->all(), $type, 'temporary_leaves', $dailyStatus->id),
            ];
            $rules["{$type}.*.employee_number"] = 'required|numeric';
            $rules["{$type}.*.employee_name"] = 'required|string';
            $rules["{$type}.*.start_date"] = 'required|date';
            $rules["{$type}.*.total_days"] = 'required|integer|min:1';
        }

        $rules['temporary_leaves'] = 'nullable|array';
        $rules['temporary_leaves.*.employee_id'] = 'required|exists:employees,id';
        $rules['temporary_leaves.*.employee_number'] = 'required|numeric';
        $rules['temporary_leaves.*.employee_name'] = 'required|string';
        $rules['temporary_leaves.*.from_time'] = 'required|date_format:H:i';
        $rules['temporary_leaves.*.to_time'] = 'required|date_format:H:i';

        $rules['eid_leaves.*.eid_type'] = 'required|string';
        $rules['custom_usages.*.usage_details'] = 'required|string';

        $validatedData = $request->validate($rules);

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

        $actualAttendance = $totalEmployees - (
            $paidLeavesCount +
            $unpaidLeavesCount +
            $absencesCount +
            $temporaryLeavesCount +
            $guardRestCount +
            $customUsagesCount
        );

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
            'custom_usages' => $validatedData['custom_usages'] ?? [],
            'total_required' => $validatedData['total_required'],
            'total_employees' => $totalEmployees,
            'shortage' => $shortage,
            'actual_attendance' => $actualAttendance,
            'paid_leaves_count' => $paidLeavesCount,
            'unpaid_leaves_count' => $unpaidLeavesCount,
            'absences_count' => $absencesCount,
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
        return view('daily_statuses.print', compact('dailyStatus'));
    }

    /**
     * عرض صفحة الطباعة المستقلة لموقف يومي محدد.
     *
     * @param  \App\Models\DailyStatus  $dailyStatus
     * @return \Illuminate\View\View
     */
    public function printStandalone(DailyStatus $dailyStatus)
    {
        return view('daily_statuses.print_standalone', compact('dailyStatus'));
    }

    /**
     * جلب التاريخ الهجري واسم اليوم لتاريخ ميلادي محدد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHijriDateAndDay(Request $request)
    {
        $gregorianDate = $request->input('date');

        if (!$gregorianDate) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        try {
            $carbonDate = Carbon::parse($gregorianDate);
            $dayName = $carbonDate->isoFormat('dddd');
            
            // استخدام مكتبة Alkoumi\LaravelHijriDate
            $hijriDate = Hijri::Date('j F Y', $gregorianDate);

            return response()->json([
                'hijri_date' => $hijriDate,
                'day_name' => $dayName
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format or processing error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * جلب جزء HTML لعنصر إجازة موظف جديد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEmployeeLeaveItem(Request $request)
    {
        $type = $request->input('type');
        $index = $request->input('index');
        $employees = json_decode($request->input('employees'), true); // فك تشفير JSON

        // يجب تهيئة $leave كـ array فارغ أو بقيم افتراضية
        $leave = []; 

        return view('daily_statuses.partials.employee_leave_item', compact('type', 'index', 'employees', 'leave'));
    }

    /**
     * جلب جزء HTML لعنصر إجازة عيد جديد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEidLeaveItem(Request $request)
    {
        $type = $request->input('type');
        $index = $request->input('index');
        $employees = json_decode($request->input('employees'), true);

        $leave = []; 

        return view('daily_statuses.partials.eid_leave_item', compact('type', 'index', 'employees', 'leave'));
    }

    /**
     * جلب جزء HTML لعنصر إجازة زمنية جديد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTemporaryLeaveItem(Request $request)
    {
        $type = $request->input('type');
        $index = $request->input('index');
        $employees = json_decode($request->input('employees'), true);

        $leave = []; 

        return view('daily_statuses.partials.temporary_leave_item', compact('type', 'index', 'employees', 'leave'));
    }

    /**
     * جلب جزء HTML لعنصر إجازة ذات تاريخ (غياب، طويلة، مرضية) جديد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDatedLeaveItem(Request $request)
    {
        $type = $request->input('type');
        $index = $request->input('index');
        $employees = json_decode($request->input('employees'), true);

        $leave = []; 

        return view('daily_statuses.partials.dated_leave_item', compact('type', 'index', 'employees', 'leave'));
    }

    /**
     * جلب جزء HTML لعنصر استخدام مخصص جديد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCustomUsageItem(Request $request)
    {
        $type = $request->input('type');
        $index = $request->input('index');
        $employees = json_decode($request->input('employees'), true);

        $usage = []; // للمخاطبة بـ $usage بدلاً من $leave

        return view('daily_statuses.partials.custom_usage_item', compact('type', 'index', 'employees', 'usage'));
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
            return Hijri::Date('j F Y', $gregorianDate);
        } catch (\Exception $e) {
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
            return 'يوم غير معروف';
        }
    }
}
