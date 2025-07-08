<?php

namespace App\Http\Controllers;

use App\Models\DailyStatus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MonthlySummaryController extends Controller
{
    /**
     * Display a monthly summary of daily statuses.
     * يعرض ملخصًا شهريًا للمواقف اليومية.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\View\View
     */
    public function showMonthlySummary(Request $request, $year = null, $month = null)
    {
        // If year or month are not provided, default to current month/year
        // إذا لم يتم توفير السنة أو الشهر، يتم تعيينهما إلى الشهر/السنة الحاليين افتراضياً
        if (is_null($year) || is_null($month)) {
            $date = Carbon::now();
            $year = $date->year;
            $month = $date->month;
        } else {
            // Validate year and month
            // التحقق من صحة السنة والشهر
            if (!is_numeric($year) || !is_numeric($month) || $month < 1 || $month > 12) {
                // Redirect to current month or show error
                // إعادة التوجيه إلى الشهر الحالي أو عرض خطأ
                return redirect()->route('monthly-summary.show', [
                    'year' => Carbon::now()->year,
                    'month' => Carbon::now()->month
                ])->with('error', 'تاريخ غير صالح. تم عرض الملخص للشهر الحالي.');
            }
            $date = Carbon::create($year, $month, 1);
        }

        // Get the number of days in the selected month
        // الحصول على عدد الأيام في الشهر المحدد
        $daysInMonth = $date->daysInMonth;

        // Fetch all DailyStatus records for the specified month
        // جلب جميع سجلات الموقف اليومي للشهر المحدد
        $dailyStatuses = DailyStatus::whereYear('date', $year)
                                    ->whereMonth('date', $month)
                                    ->orderBy('date', 'asc')
                                    ->get();

        // Aggregate data for each employee
        // تجميع البيانات لكل موظف
        $monthlySummary = $this->aggregateMonthlyData($dailyStatuses, $daysInMonth);

        // Get all employees to ensure we have names for all, even if they have no records
        // This is important if you want to display all employees in the summary,
        // even those with zero leaves/absences.
        // جلب جميع الموظفين لضمان وجود أسماء للجميع، حتى لو لم يكن لديهم سجلات
        // هذا مهم إذا كنت ترغب في عرض جميع الموظفين في الملخص،
        // حتى أولئك الذين لديهم صفر إجازات/غيابات.
        $allEmployees = Employee::all()->keyBy('id');

        // Merge aggregated data with all employees, initializing missing ones
        // دمج البيانات المجمعة مع جميع الموظفين، وتهيئة البيانات المفقودة
        $finalSummary = [];
        foreach ($allEmployees as $employeeId => $employee) {
            $finalSummary[$employeeId] = $monthlySummary[$employeeId] ?? $this->initializeEmployeeSummary($employee->name, $employee->employee_number);
        }

        // Sort the final summary by employee name
        // فرز الملخص النهائي حسب اسم الموظف
        usort($finalSummary, function($a, $b) {
            return strcmp($a['employee_name'], $b['employee_name']);
        });

        return view('monthly_summary', compact('finalSummary', 'year', 'month', 'daysInMonth'));
    }

    /**
     * Aggregates daily status data into a monthly summary per employee.
     * يجمع بيانات الموقف اليومي في ملخص شهري لكل موظف.
     *
     * @param  \Illuminate\Support\Collection $dailyStatuses
     * @param  int $daysInMonth
     * @return array
     */
    protected function aggregateMonthlyData($dailyStatuses, $daysInMonth): array
    {
        $monthlySummary = []; // Keyed by employee_id

        foreach ($dailyStatuses as $status) {
            // Define all leave types that store employee details and potentially days/hours
            // تعريف جميع أنواع الإجازات التي تخزن تفاصيل الموظفين وربما الأيام/الساعات
            $leaveTypes = [
                'periodic_leaves', 'annual_leaves', 'unpaid_leaves', 'bereavement_leaves',
                'eid_leaves', 'guard_rest', 'wedding_leaves', 'other_leaves',
                'absences', 'long_leaves', 'sick_leaves',
                'temporary_leaves',
                'custom_usages', // Map custom_usages to 'other_leaves_count'
                'monthly_hours' // New: Monthly hours
            ];

            foreach ($leaveTypes as $type) {
                // Access the attribute directly as it's already cast to an array by the model
                // الوصول إلى الخاصية مباشرة حيث تم تحويلها بالفعل إلى مصفوفة بواسطة النموذج
                $items = $status->$type ?: [];

                foreach ($items as $item) {
                    $employeeId = $item['employee_id'] ?? null;
                    $employeeName = $item['employee_name'] ?? 'غير معروف';
                    $employeeNumber = $item['employee_number'] ?? '';

                    if (!$employeeId) continue; // Skip if no employee ID

                    // Initialize employee's summary if not already present
                    // تهيئة ملخص الموظف إذا لم يكن موجوداً
                    if (!isset($monthlySummary[$employeeId])) {
                        $monthlySummary[$employeeId] = $this->initializeEmployeeSummary($employeeName, $employeeNumber);
                    }

                    // Aggregate based on type
                    // التجميع بناءً على النوع
                    switch ($type) {
                        case 'absences':
                        case 'long_leaves':
                        case 'sick_leaves':
                            // For dated leaves, sum total_days
                            // Ensure 'total_days' is an integer, default to 0
                            // للإجازات المؤرخة، يتم جمع إجمالي الأيام
                            // التأكد من أن 'total_days' هو عدد صحيح، الافتراضي هو 0
                            $monthlySummary[$employeeId]["{$type}_days"] += (int)($item['total_days'] ?? 0);
                            break;
                        case 'temporary_leaves':
                            // Calculate hours for temporary leaves
                            // حساب الساعات للإجازات الزمنية
                            if (isset($item['from_time']) && isset($item['to_time'])) {
                                try {
                                    $fromTime = Carbon::parse($item['from_time']);
                                    $toTime = Carbon::parse($item['to_time']);
                                    // Calculate duration in minutes, then convert to hours
                                    // حساب المدة بالدقائق، ثم تحويلها إلى ساعات
                                    $durationMinutes = $toTime->diffInMinutes($fromTime);
                                    $monthlySummary[$employeeId]['temporary_leaves_hours'] += ($durationMinutes / 60);
                                } catch (\Exception $e) {
                                    // Log error or handle invalid time format
                                    // تسجيل الخطأ أو التعامل مع تنسيق الوقت غير الصالح
                                    error_log("Error parsing temporary leave times: " . $e->getMessage());
                                }
                            }
                            break;
                        case 'monthly_hours':
                            // Sum monthly hours for the employee
                            // جمع الساعات الشهرية للموظف
                            $monthlySummary[$employeeId]['monthly_hours_total'] += (float)($item['hours'] ?? 0);
                            break;
                        case 'custom_usages':
                            // Map custom_usages to 'other_leaves_count'
                            // ربط الاستخدامات المخصصة بـ 'other_leaves_count'
                            $monthlySummary[$employeeId]['other_leaves_count'] += 1; // Count each custom usage as one entry
                            break;
                        default:
                            // For other simple leaves, just count occurrences
                            // لأنواع الإجازات البسيطة الأخرى، يتم فقط عد التكرارات
                            $monthlySummary[$employeeId]["{$type}_count"] += 1;
                            break;
                    }
                }
            }
        }

        // Calculate total_leave_days, total_absence_days, and total_working_days for each employee
        // حساب إجمالي أيام الإجازات، إجمالي أيام الغياب، وإجمالي أيام العمل لكل موظف
        foreach ($monthlySummary as $employeeId => &$data) {
            // Sum all counted leaves (each occurrence is 1 day)
            // جمع جميع الإجازات المحتسبة (كل تكرار هو يوم واحد)
            $data['total_leave_days'] = $data['periodic_leaves_count'] + $data['annual_leaves_count'] +
                                        $data['unpaid_leaves_count'] + $data['bereavement_leaves_count'] +
                                        $data['eid_leaves_count'] + $data['guard_rest_count'] +
                                        $data['wedding_leaves_count'] + $data['other_leaves_count'];

            // Sum all absence/long/sick leave days (from total_days field)
            // جمع جميع أيام الغياب/الإجازات الطويلة/المرضية (من حقل total_days)
            $data['total_absence_days'] = $data['absences_days'] + $data['long_leaves_days'] + $data['sick_leaves_days'];

            // Total non-working days for the employee = sum of all counted leave days + sum of all absence days
            // إجمالي الأيام غير العاملة للموظف = مجموع جميع أيام الإجازات المحتسبة + مجموع جميع أيام الغياب
            $totalNonWorkingDays = $data['total_leave_days'] + $data['total_absence_days'];

            // Calculate total working days: days in month - total non-working days
            // حساب إجمالي أيام العمل: أيام في الشهر - إجمالي الأيام غير العاملة
            $data['total_working_days'] = $daysInMonth - $totalNonWorkingDays;
            if ($data['total_working_days'] < 0) {
                $data['total_working_days'] = 0; // Cannot have negative working days
            }

            // Ensure temporary_leaves_hours and monthly_hours_total are formatted to two decimal places
            // التأكد من تنسيق temporary_leaves_hours و monthly_hours_total إلى منزلتين عشريتين
            $data['temporary_leaves_hours'] = round($data['temporary_leaves_hours'], 2);
            $data['monthly_hours_total'] = round($data['monthly_hours_total'], 2);
        }

        return $monthlySummary;
    }

    /**
     * Initializes an employee's summary array with default values.
     * تهيئة مصفوفة ملخص الموظف بالقيم الافتراضية.
     *
     * @param  string $employeeName
     * @param  string $employeeNumber
     * @return array
     */
    protected function initializeEmployeeSummary(string $employeeName = '', string $employeeNumber = ''): array
    {
        return [
            'employee_name' => $employeeName,
            'employee_number' => $employeeNumber,
            'periodic_leaves_count' => 0,
            'annual_leaves_count' => 0,
            'temporary_leaves_hours' => 0.0, // Sum of hours
            'unpaid_leaves_count' => 0,
            'absences_days' => 0, // Sum of total_days
            'long_leaves_days' => 0, // Sum of total_days
            'sick_leaves_days' => 0, // Sum of total_days
            'bereavement_leaves_count' => 0,
            'eid_leaves_count' => 0,
            'guard_rest_count' => 0,
            'wedding_leaves_count' => 0,
            'other_leaves_count' => 0,
            'monthly_hours_total' => 0.0, // New: Total monthly hours
            'total_leave_days' => 0,
            'total_absence_days' => 0,
            'total_working_days' => 0,
        ];
    }
}
