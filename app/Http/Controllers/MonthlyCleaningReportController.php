<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralCleaningTask; // تم تغيير النموذج المستهدف إلى GeneralCleaningTask
use App\Models\Unit; // تم استيراد نموذج الوحدة لاستخدامه في الفلاتر والعرض
use Carbon\Carbon;

class MonthlyCleaningReportController extends Controller
{
    /**
     * Display the detailed general cleaning report (main view with filters and pagination).
     * يعرض تقرير النظافة العامة التفصيلي (المهام الفردية) مع الفلاتر والترقيم.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = GeneralCleaningTask::query(); // الاستعلام من جدول المهام التفصيلية

        // جلب معلمات البحث والتصفية
        $selectedDate = $request->input('date'); // فلتر جديد: التاريخ
        $selectedMonth = $request->input('month');
        $selectedShift = $request->input('shift'); // فلتر جديد: الشفت
        $selectedLocation = $request->input('location');
        $selectedTaskType = $request->input('task_type');
        $selectedUnitId = $request->input('unit_id'); // فلتر الوحدة
        $searchQuery = $request->input('search');

        // تطبيق الفلاتر
        if ($request->filled('date')) {
            $query->whereDate('date', $selectedDate);
        }
        if ($request->filled('month')) {
            $query->whereYear('date', Carbon::parse($selectedMonth)->year)
                  ->whereMonth('date', Carbon::parse($selectedMonth)->month);
        }
        if ($request->filled('shift')) {
            $query->where('shift', $selectedShift);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $selectedLocation . '%');
        }
        if ($request->filled('task_type')) {
            $query->where('task_type', $selectedTaskType);
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $selectedUnitId);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('location', 'like', '%' . $searchQuery . '%')
                  ->orWhere('task_type', 'like', '%' . $searchQuery . '%')
                  ->orWhere('notes', 'like', '%' . $searchQuery . '%')
                  ->orWhere('shift', 'like', '%' . $searchQuery . '%'); // أضف الشفت للبحث العام
                // أضف أي حقول نصية أخرى من GeneralCleaningTask للبحث
            });
        }

        // تطبيق الفرز
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSortColumns = [
            'date', 'shift', 'task_type', 'location', 'unit_id',
            'mats_count', 'pillows_count', 'fans_count', 'windows_count',
            'carpets_count', 'blankets_count', 'beds_count', 'beneficiaries_count',
            'filled_trams_count', 'carpets_laid_count', 'large_containers_count',
            'small_containers_count', 'working_hours', 'external_partitions_count' // ✅ إضافة external_partitions_count
        ];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'date';
        }
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        $query->orderBy($sortBy, $sortOrder);

        // جلب النتائج مع الترقيم
        $tasks = $query->with('unit')->paginate(10); // استخدام paginate() و eager load 'unit'

        // جلب خيارات الفلاتر المتاحة من GeneralCleaningTask
        $availableMonths = GeneralCleaningTask::selectRaw("DATE_FORMAT(date, '%Y-%m') as month_year")
                                             ->distinct()
                                             ->orderBy('month_year', 'desc')
                                             ->pluck('month_year')
                                             ->mapWithKeys(function ($item) {
                                                 return [$item => Carbon::parse($item)->translatedFormat('F Y')];
                                             })->toArray();

        $availableShifts = GeneralCleaningTask::distinct()->pluck('shift')->filter()->toArray();
        $availableLocations = GeneralCleaningTask::distinct()->pluck('location')->toArray();
        $availableTaskTypes = GeneralCleaningTask::distinct()->pluck('task_type')->toArray();
        $units = Unit::all(); // جلب الوحدات لفلتر الوحدة

        return view('monthly-cleaning-report.index', compact(
            'tasks', // تغيير اسم المتغير إلى tasks
            'availableMonths',
            'availableShifts',
            'availableLocations',
            'availableTaskTypes',
            'units',
            'selectedDate',
            'selectedMonth',
            'selectedShift',
            'selectedLocation',
            'selectedTaskType',
            'selectedUnitId',
            'searchQuery',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Show the form for creating a new general cleaning task.
     * يعرض نموذج إضافة مهمة نظافة عامة جديدة.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $units = Unit::all();
        $availableShifts = ['صباحي', 'مسائي', 'ليلي']; // أو جلبها ديناميكياً إذا كانت مخزنة في قاعدة البيانات
        $availableTaskTypes = ['إدامة', 'صيانة']; // أو جلبها ديناميكياً
        // جلب أي بيانات أخرى ضرورية للنموذج (مثل الأهداف ذات الصلة)
        return view('monthly-cleaning-report.create', compact('units', 'availableShifts', 'availableTaskTypes'));
    }

    /**
     * Store a newly created general cleaning task in storage.
     * يخزن مهمة نظافة عامة جديدة في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'shift' => 'nullable|string|max:255',
            'task_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'quantity' => 'nullable|integer|min:0', // إذا كان لديك كمية عامة
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'unit_id' => 'required|exists:units,id', // يجب أن تكون وحدة موجودة
            'working_hours' => 'nullable|numeric|min:0',
            'mats_count' => 'nullable|integer|min:0',
            'pillows_count' => 'nullable|integer|min:0',
            'fans_count' => 'nullable|integer|min:0',
            'windows_count' => 'nullable|integer|min:0',
            'carpets_count' => 'nullable|integer|min:0',
            'blankets_count' => 'nullable|integer|min:0',
            'beds_count' => 'nullable|integer|min:0',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'filled_trams_count' => 'nullable|integer|min:0',
            'carpets_laid_count' => 'nullable|integer|min:0',
            'large_containers_count' => 'nullable|integer|min:0',
            'small_containers_count' => 'nullable|integer|min:0',
            'maintenance_details' => 'nullable|string',
            'external_partitions_count' => 'nullable|integer|min:0', // ✅ إضافة هذا الحقل لقواعد التحقق
            'before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // لتحميل الصور
            'after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // التعامل مع تحميل الصور
        $beforeImagePaths = [];
        if ($request->hasFile('before_images')) {
            foreach ($request->file('before_images') as $image) {
                $beforeImagePaths[] = $image->store('public/general_cleaning/before');
            }
        }
        $afterImagePaths = [];
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $image) {
                $afterImagePaths[] = $image->store('public/general_cleaning/after');
            }
        }

        $validatedData['before_images'] = $beforeImagePaths;
        $validatedData['after_images'] = $afterImagePaths;

        GeneralCleaningTask::create($validatedData);

        return redirect()->route('monthly-cleaning-report.index')->with('success', 'تم إضافة مهمة النظافة بنجاح!');
    }

    /**
     * Show the form for editing the specified general cleaning task.
     * يعرض نموذج تعديل مهمة النظافة العامة المحددة.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        $task = GeneralCleaningTask::findOrFail($id); // البحث في GeneralCleaningTask
        $units = Unit::all();
        $availableShifts = ['صباحي', 'مسائي', 'ليلي'];
        $availableTaskTypes = ['إدامة', 'صيانة'];
        return view('monthly-cleaning-report.edit', compact('task', 'units', 'availableShifts', 'availableTaskTypes'));
    }

    /**
     * Update the specified general cleaning task in storage.
     * يحدث مهمة النظافة العامة المحددة في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $task = GeneralCleaningTask::findOrFail($id); // البحث في GeneralCleaningTask

        $validatedData = $request->validate([
            'date' => 'required|date',
            'shift' => 'nullable|string|max:255',
            'task_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'quantity' => 'nullable|integer|min:0',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'working_hours' => 'nullable|numeric|min:0',
            'mats_count' => 'nullable|integer|min:0',
            'pillows_count' => 'nullable|integer|min:0',
            'fans_count' => 'nullable|integer|min:0',
            'windows_count' => 'nullable|integer|min:0',
            'carpets_count' => 'nullable|integer|min:0',
            'blankets_count' => 'nullable|integer|min:0',
            'beds_count' => 'nullable|integer|min:0',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'filled_trams_count' => 'nullable|integer|min:0',
            'carpets_laid_count' => 'nullable|integer|min:0',
            'large_containers_count' => 'nullable|integer|min:0',
            'small_containers_count' => 'nullable|integer|min:0',
            'maintenance_details' => 'nullable|string',
            'external_partitions_count' => 'nullable|integer|min:0', // ✅ إضافة هذا الحقل لقواعد التحقق
            'before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_before_images' => 'nullable|array', // للتعامل مع حذف الصور الموجودة
            'existing_after_images' => 'nullable|array',
        ]);

        // التعامل مع تحديث الصور (حذف الصور المحذوفة، إضافة صور جديدة)
        $currentBeforeImages = $task->before_images ?? [];
        $currentAfterImages = $task->after_images ?? [];

        // تحديد الصور المراد الاحتفاظ بها
        $keepBeforeImages = $request->input('existing_before_images', []);
        $keepAfterImages = $request->input('existing_after_images', []);

        // حذف الصور التي لم يعد يتم الاحتفاظ بها
        foreach ($currentBeforeImages as $path) {
            if (!in_array($path, $keepBeforeImages)) {
                \Illuminate\Support\Facades\Storage::delete($path);
            }
        }
        foreach ($currentAfterImages as $path) {
            if (!in_array($path, $keepAfterImages)) {
                \Illuminate\Support\Facades\Storage::delete($path);
            }
        }

        // إضافة الصور الجديدة
        $newBeforeImagePaths = $keepBeforeImages;
        if ($request->hasFile('before_images')) {
            foreach ($request->file('before_images') as $image) {
                $newBeforeImagePaths[] = $image->store('public/general_cleaning/before');
            }
        }
        $newAfterImagePaths = $keepAfterImages;
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $image) {
                $newAfterImagePaths[] = $image->store('public/general_cleaning/after');
            }
        }

        $validatedData['before_images'] = $newBeforeImagePaths;
        $validatedData['after_images'] = $newAfterImagePaths;

        $task->update($validatedData);

        return redirect()->route('monthly-cleaning-report.index')->with('success', 'تم تحديث مهمة النظافة بنجاح!');
    }

    /**
     * Remove the specified general cleaning task from storage.
     * يحذف مهمة النظافة العامة المحددة من قاعدة البيانات.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $task = GeneralCleaningTask::findOrFail($id); // البحث في GeneralCleaningTask
        // دالة cleanupTaskImages في حدث 'deleted' بالنموذج ستتكفل بحذف الصور المرتبطة.
        $task->delete();

        return redirect()->route('monthly-cleaning-report.index')->with('success', 'تم حذف مهمة النظافة بنجاح!');
    }

    /**
     * Function to export the detailed report to CSV format.
     * دالة لتصدير التقرير التفصيلي إلى صيغة CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $query = GeneralCleaningTask::query(); // الاستعلام من GeneralCleaningTask

        // تطبيق نفس الفلاتر المستخدمة في دالة index
        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }
        if ($request->filled('month')) {
            $query->whereYear('date', Carbon::parse($request->input('month'))->year)
                  ->whereMonth('date', Carbon::parse($request->input('month'))->month);
        }
        if ($request->filled('shift')) {
            $query->where('shift', $request->input('shift'));
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }
        if ($request->filled('task_type')) {
            $query->where('task_type', $request->input('task_type'));
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }
        if ($request->filled('search')) {
            $searchQuery = $request->input('search');
            $query->where(function ($q) use ($searchQuery) {
                $q->where('location', 'like', '%' . $searchQuery . '%')
                  ->orWhere('task_type', 'like', '%' . $searchQuery . '%')
                  ->orWhere('notes', 'like', '%' . $searchQuery . '%')
                  ->orWhere('shift', 'like', '%' . $searchQuery . '%');
            });
        }

        $dataToExport = $query->with('unit')->orderBy('date', 'desc')->orderBy('shift', 'asc')->get();

        $fileName = 'تقرير_النظافة_التفصيلي_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Encoding' => 'UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($dataToExport) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM for UTF-8

            // رؤوس الأعمدة باللغة العربية
            fputcsv($file, [
                'التاريخ', 'الشفت', 'الوحدة', 'الموقع', 'نوع المهمة',
                'المنادر', 'الوسائد', 'المراوح', 'النوافذ', 'السجاد',
                'البطانيات', 'الأسرة', 'المستفيدون', 'الترامز', 'السجاد المفروش',
                'حاويات كبيرة', 'حاويات صغيرة', 'ساعات العمل', 'الملاحظات', 'تفاصيل الصيانة',
                'حالة التحقق', 'الموارد المستخدمة', 'التقدم', 'قيمة النتيجة', 'القواطع الخارجية المدامة' // ✅ إضافة رأس العمود هنا
            ]);

            foreach ($dataToExport as $row) {
                $unitName = $row->unit->name ?? 'N/A';
                fputcsv($file, [
                    Carbon::parse($row->date)->format('Y-m-d'),
                    $row->shift,
                    $unitName,
                    $row->location,
                    $row->task_type,
                    $row->mats_count,
                    $row->pillows_count,
                    $row->fans_count,
                    $row->windows_count,
                    $row->carpets_count,
                    $row->blankets_count,
                    $row->beds_count,
                    $row->beneficiaries_count,
                    $row->filled_trams_count,
                    $row->carpets_laid_count,
                    $row->large_containers_count,
                    $row->small_containers_count,
                    $row->working_hours,
                    $row->notes,
                    $row->maintenance_details,
                    $row->verification_status,
                    is_array($row->resources_used) ? implode(', ', $row->resources_used) : $row->resources_used,
                    $row->progress,
                    $row->result_value,
                    $row->external_partitions_count, // ✅ إضافة قيمة الحقل هنا
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display the detailed report in a printable format.
     * يعرض التقرير التفصيلي بتنسيق مناسب للطباعة.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function print(Request $request)
    {
        $query = GeneralCleaningTask::query(); // الاستعلام من GeneralCleaningTask

        // تطبيق نفس الفلاتر المستخدمة في دالة index
        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }
        if ($request->filled('month')) {
            $query->whereYear('date', Carbon::parse($request->input('month'))->year)
                  ->whereMonth('date', Carbon::parse($request->input('month'))->month);
        }
        if ($request->filled('shift')) {
            $query->where('shift', $request->input('shift'));
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }
        if ($request->filled('task_type')) {
            $query->where('task_type', $request->input('task_type'));
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }
        if ($request->filled('search')) {
            $searchQuery = $request->input('search');
            $query->where(function ($q) use ($searchQuery) {
                $q->where('location', 'like', '%' . $searchQuery . '%')
                  ->orWhere('task_type', 'like', '%' . $searchQuery . '%')
                  ->orWhere('notes', 'like', '%' . $searchQuery . '%')
                  ->orWhere('shift', 'like', '%' . $searchQuery . '%');
            });
        }

        $tasks = $query->with('unit')->orderBy('date', 'desc')->orderBy('shift', 'asc')->get();

        // 💡 حساب مجاميع الحقول الكمية وساعات العمل
        $totalMats = $tasks->sum('mats_count');
        $totalPillows = $tasks->sum('pillows_count');
        $totalFans = $tasks->sum('fans_count');
        $totalWindows = $tasks->sum('windows_count');
        $totalCarpets = $tasks->sum('carpets_count');
        $totalBlankets = $tasks->sum('blankets_count');
        $totalBeds = $tasks->sum('beds_count');
        $totalBeneficiaries = $tasks->sum('beneficiaries_count');
        $totalTrams = $tasks->sum('filled_trams_count');
        $totalCarpetsLaid = $tasks->sum('carpets_laid_count');
        $totalLargeContainers = $tasks->sum('large_containers_count');
        $totalSmallContainers = $tasks->sum('small_containers_count');
        $totalWorkingHours = $tasks->sum('working_hours');
        // ✅ التصحيح: يجب أن يكون external_partitions_count وليس total_external_partitions
        $totalExternalPartitions = $tasks->sum('external_partitions_count');


        // جلب خيارات الفلاتر المتاحة (نفسها المطلوبة في العرض)
        $availableMonths = GeneralCleaningTask::selectRaw("DATE_FORMAT(date, '%Y-%m') as month_year")
                                             ->distinct()
                                             ->orderBy('month_year', 'desc')
                                             ->get()
                                             ->mapWithKeys(function ($item) {
                                                 $monthValue = Carbon::parse($item->month_year)->format('Y-m');
                                                 $monthLabel = Carbon::parse($item->month_year)->translatedFormat('F Y');
                                                 return [$monthValue => $monthLabel];
                                             })
                                             ->toArray();
        $availableLocations = GeneralCleaningTask::select('location')->distinct()->pluck('location')->toArray();
        $availableTaskTypes = GeneralCleaningTask::select('task_type')->distinct()->pluck('task_type')->toArray();
        $availableShifts = GeneralCleaningTask::distinct()->pluck('shift')->filter()->toArray(); // جلب الشفتات المتاحة

        // جلب قيم الفلاتر لعرضها في رأس التقرير المطبوع
        $filters = $request->only(['date', 'month', 'shift', 'location', 'task_type', 'unit_id']);

        if ($request->filled('month')) {
            $filters['month_display'] = Carbon::parse($request->month)->translatedFormat('F Y');
        } else {
            $filters['month_display'] = null;
        }

        if ($request->filled('unit_id')) {
            $unit = Unit::find($request->unit_id);
            $filters['unit_name'] = $unit->name ?? 'N/A';
        }

        return view('monthly-cleaning-report.report', compact(
            'tasks',
            'filters',
            'availableMonths',
            'availableLocations',
            'availableTaskTypes',
            'availableShifts',
            'totalMats', // 💡 تمرير المجاميع الجديدة
            'totalPillows',
            'totalFans',
            'totalWindows',
            'totalCarpets',
            'totalBlankets',
            'totalBeds',
            'totalBeneficiaries',
            'totalTrams',
            'totalCarpetsLaid',
            'totalLargeContainers',
            'totalSmallContainers',
            'totalWorkingHours',
            'totalExternalPartitions' // تأكد من وجود هذا الحقل في النموذج
        ));
    }
}
