<?php

namespace App\Http\Controllers;

use App\Models\SanitationFacilityTask; // استخدام النموذج الصحيح للمهام الفردية
use App\Models\Unit; // تأكد من استيراد نموذج الوحدة
use Illuminate\Http\Request;
use Illuminate\Support\Carbon; // استخدام Carbon للتعامل مع التواريخ
use Illuminate\Validation\ValidationException; // لاستخدام ValidationException في دوال التعديل والتخزين

class MonthlySanitationReportController extends Controller
{
    /**
     * Display the detailed sanitation tasks report (main view with filters).
     * عرض تقرير المهام الصحية التفصيلي (العرض الرئيسي مع الفلاتر).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = SanitationFacilityTask::query(); // الاستعلام من جدول المهام الفردية الصحيح

        // جلب معلمات البحث والتصفية
        $selectedDate = $request->input('date');
        $selectedStartDate = $request->input('start_date');
        $selectedEndDate = $request->input('end_date');
        $selectedFacilityName = $request->input('facility_name');
        $selectedTaskType = $request->input('task_type');
        $selectedUnitId = $request->input('unit_id');
        $searchQuery = $request->input('search'); // فلتر البحث العام

        // تطبيق فلاتر التاريخ
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$selectedStartDate, $selectedEndDate]);
        } elseif ($request->filled('date')) {
            $query->whereDate('date', $selectedDate);
        }

        // Filter by facility name
        if ($request->filled('facility_name')) {
            $query->where('facility_name', 'like', '%' . $selectedFacilityName . '%');
        }

        // Filter by task type
        if ($request->filled('task_type')) {
            $query->where('task_type', $selectedTaskType);
        }

        // Filter by unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $selectedUnitId);
        }

        // Filter by general search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('facility_name', 'like', '%' . $search . '%')
                  ->orWhere('task_type', 'like', '%' . $search . '%')
                  ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        // تطبيق الفرز
        $sortBy = $request->get('sort_by', 'date'); // الافتراضي هو الفرز حسب التاريخ
        $sortOrder = $request->get('sort_order', 'desc'); // الافتراضي هو تنازلي

        // تأكد من أن الأعمدة موجودة في الجدول لتجنب الأخطاء
        $allowedSorts = ['date', 'facility_name', 'task_type', 'unit_id', 'seats_count', 'mirrors_count', 'mixers_count', 'doors_count', 'sinks_count', 'toilets_count', 'notes', 'shift', 'status', 'working_hours']; // أضف الأعمدة الجديدة من SanitationFacilityTask
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('date', 'desc'); // fallback
        }

        // جلب المهام مع الترحيل (pagination)
        $tasks = $query->with('unit')->paginate(10); // تحميل علاقة الوحدة

        // جلب الوحدات وأسماء المنشآت وأنواع المهام المتاحة للفلاتر من جدول المهام الفردية
        $units = Unit::all();
        $availableFacilityNames = SanitationFacilityTask::select('facility_name')->distinct()->pluck('facility_name');
        $availableTaskTypes = SanitationFacilityTask::select('task_type')->distinct()->pluck('task_type');

        return view('monthly_sanitation_report.index', compact(
            'tasks', // تمرير المهام الفردية
            'units',
            'availableFacilityNames',
            'availableTaskTypes',
            'selectedDate',
            'selectedStartDate',
            'selectedEndDate',
            'selectedFacilityName',
            'selectedTaskType',
            'selectedUnitId',
            'searchQuery',
            'sortBy',
            'sortOrder'
        ));
    }

    // تم إزالة دوال create و store من هنا، حيث يتم التعامل معها في SanitationFacilityTaskController
    // يجب أن يوجه زر "إضافة مهمة" في الواجهة الأمامية إلى sanitation-facility-tasks.create

    /**
     * Show the form for editing the specified sanitation task.
     * عرض نموذج تعديل المهمة الصحية المحددة.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        $task = SanitationFacilityTask::findOrFail($id); // استخدام النموذج الصحيح
        $units = Unit::all();
        // بما أن هذا الكنترولر مخصص للتقرير، فمن الأفضل أن يتم التعديل الفعلي للمهمة عبر SanitationFacilityTaskController.
        // يمكن هنا إعادة التوجيه إلى صفحة التعديل في الكنترولر الآخر.
        // return redirect()->route('sanitation-facility-tasks.edit', $id);
        // أو إذا كنت تريد عرض نموذج التعديل هنا مباشرة، تأكد من تمرير البيانات اللازمة
        // مثل الأهداف والموظفين إذا كانت مطلوبة في نموذج التعديل الخاص بك.
        // $goals = UnitGoal::all(); // إذا كان نموذج SanitationFacilityTask مرتبطاً بالأهداف
        // $employees = Employee::orderBy('name')->get(); // إذا كان نموذج SanitationFacilityTask مرتبطاً بالموظفين
        return view('monthly_sanitation_report.edit', compact('task', 'units')); // , 'goals', 'employees'
    }

    /**
     * Update the specified sanitation task in storage.
     * تحديث المهمة الصحية المحددة في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'date'          => 'required|date',
                'shift'         => 'required|in:صباحي,مسائي,ليلي', // أضفنا هذا من SanitationFacilityTaskController
                'task_type'     => 'required|string|in:إدامة,صيانة',
                'facility_name' => 'required|string|max:255',
                'details'       => 'required|string|max:1000', // أضفنا هذا من SanitationFacilityTaskController
                'status'        => 'required|in:مكتمل,قيد التنفيذ,ملغى', // أضفنا هذا من SanitationFacilityTaskController
                'notes'         => 'nullable|string|max:1000',
                'related_goal_id' => 'required|exists:unit_goals,id', // أضفنا هذا من SanitationFacilityTaskController
                'progress'      => 'nullable|numeric|min:0|max:100', // أضفنا هذا من SanitationFacilityTaskController
                'result_value'  => 'nullable|numeric', // أضفنا هذا من SanitationFacilityTaskController
                'seats_count'   => 'nullable|integer|min:0',
                'sinks_count'   => 'nullable|integer|min:0',
                'mixers_count'  => 'nullable|integer|min:0',
                'mirrors_count' => 'nullable|integer|min:0',
                'doors_count'   => 'nullable|integer|min:0',
                'toilets_count' => 'nullable|integer|min:0',
                'working_hours' => 'required|numeric|min:0|max:24', // أضفنا هذا من SanitationFacilityTaskController
                // لا نقوم بمعالجة الصور أو employeeTasks هنا، حيث أن SanitationFacilityTaskController هو المسؤول عن ذلك
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $task = SanitationFacilityTask::findOrFail($id); // استخدام النموذج الصحيح
        $task->update($validatedData);

        return redirect()->route('monthly-sanitation-report.index')->with('success', 'تم تحديث المهمة بنجاح!');
    }

    /**
     * Remove the specified sanitation task from storage.
     * حذف المهمة الصحية المحددة من قاعدة البيانات.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $task = SanitationFacilityTask::findOrFail($id); // استخدام النموذج الصحيح
        $task->delete();

        return redirect()->route('monthly-sanitation-report.index')->with('success', 'تم حذف المهمة بنجاح!');
    }

    /**
     * Function to export the report (can be extended for CSV/Excel export).
     * وظيفة لتصدير التقرير (يمكن توسيعها لتصدير CSV/Excel).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function export(Request $request)
    {
        $query = SanitationFacilityTask::query(); // الاستعلام من جدول المهام الفردية الصحيح

        // تطبيق نفس الفلاتر المستخدمة في دالة index
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->input('start_date'), $request->input('end_date')]);
        } elseif ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        if ($request->filled('facility_name')) {
            $query->where('facility_name', 'like', '%' . $request->input('facility_name') . '%');
        }
        if ($request->filled('task_type')) {
            $query->where('task_type', $request->input('task_type'));
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('facility_name', 'like', '%' . $search . '%')
                  ->orWhere('task_type', 'like', '%' . $search . '%')
                  ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        $dataToExport = $query->get();

        $fileName = 'تقرير_المنشآت_الصحية_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Encoding' => 'UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($dataToExport) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8 compatibility with Excel
            fwrite($file, "\xEF\xBB\xBF");

            // Column headers for individual tasks
            fputcsv($file, [
                'التاريخ', 'اسم المنشأة', 'نوع المهمة', 'الوحدة', 'المقاعد', 'المرايا',
                'الخلاطات', 'الأبواب', 'الأحواض', 'المراحيض', 'ملاحظات'
            ]);

            // Row data
            foreach ($dataToExport as $row) {
                $unitName = $row->unit->name ?? 'N/A';
                fputcsv($file, [
                    Carbon::parse($row->date)->format('Y / m / d'),
                    $row->facility_name,
                    $row->task_type,
                    $unitName,
                    $row->seats_count,
                    $row->mirrors_count,
                    $row->mixers_count,
                    $row->doors_count,
                    $row->sinks_count,
                    $row->toilets_count,
                    $row->notes,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display the report in a printable format.
     * عرض التقرير بتنسيق مناسب للطباعة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function print(Request $request)
    {
        $query = SanitationFacilityTask::query(); // الاستعلام من جدول المهام الفردية الصحيح

        // Apply the same filters used in the index function
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->input('start_date'), $request->input('end_date')]);
        } elseif ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('facility_name')) {
            $query->where('facility_name', 'like', '%' . $request->facility_name . '%');
        }
        if ($request->filled('task_type')) {
            $query->where('task_type', $request->task_type);
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('facility_name', 'like', '%' . $search . '%')
                  ->orWhere('task_type', 'like', '%' . $search . '%')
                  ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        // Fetch all filtered data for printing (no pagination here)
        $tasks = $query->with('unit')->orderBy('date', 'desc')->orderBy('facility_name', 'asc')->get();

        // حساب مجاميع الحقول الكمية من المهام الفردية المجلوبة
        $totalSeats = $tasks->sum('seats_count');
        $totalMirrors = $tasks->sum('mirrors_count');
        $totalMixers = $tasks->sum('mixers_count');
        $totalDoors = $tasks->sum('doors_count');
        $totalSinks = $tasks->sum('sinks_count');
        $totalToilets = $tasks->sum('toilets_count');
        // لا يوجد "total_tasks" كعمود مباشر في المهام الفردية، يمكن أن يكون عدد المهام المجلوبة
        $totalTasks = $tasks->count();


        // Fetch filter values to display in the printed report header
        $filters = $request->only(['date', 'facility_name', 'task_type', 'unit_id', 'start_date', 'end_date', 'search']);
        if ($request->filled('date')) {
            $filters['date_display'] = Carbon::parse($request->date)->translatedFormat('d F Y');
        } else {
            $filters['date_display'] = null;
        }

        if ($request->filled('start_date')) {
            $filters['start_date_display'] = Carbon::parse($request->start_date)->translatedFormat('d F Y');
        } else {
            $filters['start_date_display'] = null;
        }

        if ($request->filled('end_date')) {
            $filters['end_date_display'] = Carbon::parse($request->end_date)->translatedFormat('d F Y');
        } else {
            $filters['end_date_display'] = null;
        }

        if ($request->filled('unit_id')) {
            $unit = Unit::find($request->unit_id);
            $filters['unit_name'] = $unit->name ?? 'غير معروف';
        }

        return view('monthly_sanitation_report.print', compact(
            'tasks', // تمرير المهام الفردية
            'filters',
            'totalSeats',
            'totalMirrors',
            'totalMixers',
            'totalDoors',
            'totalSinks',
            'totalToilets',
            'totalTasks'
        ));
    }
}
