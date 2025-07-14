<?php

namespace App\Http\Controllers;

use App\Models\MonthlySanitationSummary;
use App\Models\Unit; // تأكد من استيراد نموذج الوحدة
use Illuminate\Http\Request;
use Illuminate\Support\Carbon; // استخدام Carbon للتعامل مع التواريخ

class MonthlySanitationReportController extends Controller
{
    /**
     * Display the monthly sanitation summaries report (main view with filters).
     * عرض تقرير الملخصات الشهرية للمنشآت الصحية (العرض الرئيسي مع الفلاتر).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = MonthlySanitationSummary::query();

        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Filter by facility name
        if ($request->filled('facility_name')) {
            $query->where('facility_name', 'like', '%' . $request->facility_name . '%');
        }

        // Filter by task type
        if ($request->filled('task_type')) {
            $query->where('task_type', $request->task_type);
        }

        // Filter by unit (if multiple units exist)
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Fetch summaries sorted by month descending
        $monthlySummaries = $query->orderBy('month', 'desc')
                               ->orderBy('facility_name', 'asc')
                               ->paginate(10); // Set number of items per page

        // Fetch list of units for the filter (if you have a units table)
        $units = Unit::all();

        // Fetch unique facility names and task types from summaries for filters
        $facilityNames = MonthlySanitationSummary::select('facility_name')->distinct()->pluck('facility_name');
        $taskTypes = MonthlySanitationSummary::select('task_type')->distinct()->pluck('task_type');

        return view('monthly_sanitation_report.index', compact('monthlySummaries', 'units', 'facilityNames', 'taskTypes'));
    }

    /**
     * Show the form for editing the specified monthly sanitation summary.
     * عرض نموذج تعديل ملخص المنشآت الصحية الشهري المحدد.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        $report = MonthlySanitationSummary::findOrFail($id);
        // You might need to pass other data like units if your edit form uses them
        $units = Unit::all();
        return view('monthly_sanitation_report.edit', compact('report', 'units'));
    }

    /**
     * Update the specified monthly sanitation summary in storage.
     * تحديث ملخص المنشآت الصحية الشهري المحدد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'month'             => 'required|string|max:7', // YYYY-MM format
            'facility_name'     => 'required|string|max:255',
            'task_type'         => 'required|string|in:إدامة,صيانة',
            'unit_id'           => 'nullable|exists:units,id', // Validate if unit_id exists in units table
            'total_seats'       => 'nullable|integer|min:0',
            'total_mirrors'     => 'nullable|integer|min:0',
            'total_mixers'      => 'nullable|integer|min:0',
            'total_doors'       => 'nullable|integer|min:0',
            'total_sinks'       => 'nullable|integer|min:0',
            'total_toilets'     => 'nullable|integer|min:0',
            'total_tasks'       => 'nullable|integer|min:0',
            // Add other fields from your MonthlySanitationSummary model here
        ]);

        $report = MonthlySanitationSummary::findOrFail($id);
        $report->update($validatedData);

        return redirect()->route('monthly-sanitation-report.index')->with('success', 'تم تحديث التقرير بنجاح!');
    }

    /**
     * Remove the specified monthly sanitation summary from storage.
     * حذف ملخص المنشآت الصحية الشهري المحدد من قاعدة البيانات.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $report = MonthlySanitationSummary::findOrFail($id);
        $report->delete();

        return redirect()->route('monthly-sanitation-report.index')->with('success', 'تم حذف التقرير بنجاح!');
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
        $query = MonthlySanitationSummary::query();

        // Apply filters before export
        if ($request->filled('month')) {
            $query->where('month', $request->input('month'));
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

        // Fetch data to export
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

            // Column headers
            fputcsv($file, [
                'الشهر', 'اسم المنشأة', 'نوع المهمة', 'الوحدة', 'إجمالي المقاعد', 'إجمالي المرايا',
                'إجمالي الخلاطات', 'إجمالي الأبواب', 'إجمالي الأحواض', 'إجمالي المراحيض', 'إجمالي المهام'
            ]);

            // Row data
            foreach ($dataToExport as $row) {
                // Get unit name if the relationship exists
                $unitName = $row->unit->name ?? 'N/A';
                fputcsv($file, [
                    Carbon::parse($row->month)->format('Y / m'),
                    $row->facility_name,
                    $row->task_type,
                    $unitName,
                    $row->total_seats,
                    $row->total_mirrors,
                    $row->total_mixers,
                    $row->total_doors,
                    $row->total_sinks,
                    $row->total_toilets,
                    $row->total_tasks,
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
        $query = MonthlySanitationSummary::query();

        // Apply the same filters used in the index function
        if ($request->filled('month')) {
            $query->where('month', $request->month);
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

        // Fetch all filtered data for printing (no pagination here)
        $monthlySummaries = $query->orderBy('month', 'desc')
                               ->orderBy('facility_name', 'asc')
                               ->get();

        // 💡 حساب مجاميع الحقول الكمية لتقرير المنشآت الصحية
        $totalSeats = $monthlySummaries->sum('total_seats');
        $totalMirrors = $monthlySummaries->sum('total_mirrors');
        $totalMixers = $monthlySummaries->sum('total_mixers');
        $totalDoors = $monthlySummaries->sum('total_doors');
        $totalSinks = $monthlySummaries->sum('total_sinks');
        $totalToilets = $monthlySummaries->sum('total_toilets');
        $totalTasks = $monthlySummaries->sum('total_tasks');


        // Fetch filter values to display in the printed report header
        $filters = $request->only(['month', 'facility_name', 'task_type']);
        if ($request->filled('month')) {
            // Convert month to a suitable format for display in the printed report
            $filters['month_display'] = Carbon::parse($request->month)->format('F Y');
        } else {
            $filters['month_display'] = null; // Ensure no empty value if month is not selected
        }

        // Add unit name to displayed filters if selected
        if ($request->filled('unit_id')) {
            $unit = Unit::find($request->unit_id);
            $filters['unit_name'] = $unit->name ?? 'N/A';
        }

        return view('monthly_sanitation_report.print', compact(
            'monthlySummaries',
            'filters',
            'totalSeats', // 💡 تمرير المجاميع الجديدة
            'totalMirrors',
            'totalMixers',
            'totalDoors',
            'totalSinks',
            'totalToilets',
            'totalTasks'
        ));
    }
}
