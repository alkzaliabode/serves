<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskImageReport;
use App\Models\Unit; // تأكد من استيراد نموذج الوحدة
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // استيراد Carbon للتعامل مع التواريخ
use Barryvdh\DomPDF\Facade\Pdf; // استيراد الواجهة لـ DomPDF

class ImageReportController extends Controller
{
    /**
     * عرض قائمة بجميع التقارير المصورة.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $taskImageReports = TaskImageReport::latest()->get();
        return view('photo_reports.index', compact('taskImageReports'));
    }

    /**
     * عرض نموذج إنشاء تقرير مصور جديد.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $units = Unit::all(); // جلب جميع الوحدات
        return view('photo_reports.create', compact('units')); // تمرير الوحدات إلى الـ View
    }

    /**
     * تخزين تقرير مصور جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_title' => 'required|string|max:255',
            'date' => 'required|date',
            'unit_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'task_type' => 'nullable|string|max:255',
            'task_id' => 'nullable', // تم إزالة قاعدة integer للسماح بإدخال نصي
            'before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string|in:قيد التنفيذ,مكتملة,معلقة', // تم تغييرها لتقبل القيم العربية
            'notes' => 'nullable|string',
        ]);

        $beforeImagePaths = [];
        if ($request->hasFile('before_images')) {
            foreach ($request->file('before_images') as $image) {
                $path = $image->store('public/images/before', 'public');
                $beforeImagePaths[] = str_replace('public/', '', $path);
            }
        }

        $afterImagePaths = [];
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $image) {
                $path = $image->store('public/images/after', 'public');
                $afterImagePaths[] = str_replace('public/', '', $path);
            }
        }

        TaskImageReport::create([
            'report_title' => $request->report_title,
            'date' => $request->date,
            'unit_type' => $request->unit_type,
            'location' => $request->location,
            'task_type' => $request->task_type,
            'task_id' => $request->task_id,
            'before_images' => $beforeImagePaths,
            'after_images' => $afterImagePaths,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('photo_reports.index')
                         ->with('success', 'تم إنشاء تقرير المهام المصور بنجاح.');
    }

    /**
     * عرض تفاصيل تقرير مصور محدد.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\View\View
     */
    public function show(TaskImageReport $photo_report)
    {
        return view('photo_reports.show', compact('photo_report'));
    }

    /**
     * عرض نموذج تعديل تقرير مصور محدد.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\View\View
     */
    public function edit(TaskImageReport $photo_report)
    {
        $units = Unit::all(); // جلب جميع الوحدات
        return view('photo_reports.edit', compact('photo_report', 'units')); // تمرير الوحدات
    }

    /**
     * تحديث تقرير مصور محدد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TaskImageReport $photo_report)
    {
        $request->validate([
            'report_title' => 'required|string|max:255',
            'date' => 'required|date',
            'unit_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'task_type' => 'nullable|string|max:255',
            'task_id' => 'nullable', // تم إزالة قاعدة integer للسماح بإدخال نصي
            'new_before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'new_after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string|in:قيد التنفيذ,مكتملة,معلقة', // تم تغييرها لتقبل القيم العربية
            'notes' => 'nullable|string',
            'deleted_before_images' => 'nullable|json',
            'deleted_after_images' => 'nullable|json',
        ]);

        // معالجة الصور المحذوفة
        $currentBeforeImages = $photo_report->before_images;
        $currentAfterImages = $photo_report->after_images;

        $deletedBeforeImages = json_decode($request->input('deleted_before_images', '[]'), true);
        $deletedAfterImages = json_decode($request->input('deleted_after_images', '[]'), true);

        // حذف الصور من التخزين وإزالتها من المصفوفة
        foreach ($deletedBeforeImages as $path) {
            Storage::disk('public')->delete($path);
            $currentBeforeImages = array_values(array_diff($currentBeforeImages, [$path]));
        }
        foreach ($deletedAfterImages as $path) {
            Storage::disk('public')->delete($path);
            $currentAfterImages = array_values(array_diff($currentAfterImages, [$path]));
        }

        // إضافة الصور الجديدة
        if ($request->hasFile('new_before_images')) {
            foreach ($request->file('new_before_images') as $image) {
                $path = $image->store('public/images/before', 'public');
                $currentBeforeImages[] = str_replace('public/', '', $path);
            }
        }

        if ($request->hasFile('new_after_images')) {
            foreach ($request->file('new_after_images') as $image) {
                $path = $image->store('public/images/after', 'public');
                $currentAfterImages[] = str_replace('public/', '', $path);
            }
        }

        $photo_report->update([
            'report_title' => $request->report_title,
            'date' => $request->date,
            'unit_type' => $request->unit_type,
            'location' => $request->location,
            'task_type' => $request->task_type,
            'task_id' => $request->task_id,
            'before_images' => $currentBeforeImages,
            'after_images' => $currentAfterImages,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('photo_reports.index')
                         ->with('success', 'تم تحديث تقرير المهام المصور بنجاح.');
    }

    /**
     * حذف تقرير مصور من قاعدة البيانات.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TaskImageReport $photo_report)
    {
        // حذف جميع الصور المرتبطة بالتقرير من التخزين (قبل وبعد)
        foreach ($photo_report->before_images as $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
        foreach ($photo_report->after_images as $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        $photo_report->delete();

        return redirect()->route('photo_reports.index')
                         ->with('success', 'تم حذف تقرير المهام المصور بنجاح.');
    }

    /**
     * طباعة تقرير مصور واحد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\View\View
     */
    public function print(Request $request, TaskImageReport $photo_report)
    {
        // إذا كان هناك بارامتر 'print' في الرابط، اعرض صفحة الطباعة المنفصلة
        if ($request->has('print')) {
            return view('photo_reports.print_only', compact('photo_report'));
        }

        // وإلا، اعرض صفحة التفاصيل العادية ضمن AdminLTE
        return view('photo_reports.show', compact('photo_report'));
    }

    /**
     * عرض نموذج اختيار الشهر والسنة ونوع الوحدة ونوع المهمة لتوليد التقرير الشهري.
     *
     * @return \Illuminate\View\View
     */
    public function showMonthlyReportForm()
    {
        // جلب جميع الوحدات
        $units = Unit::all();

        // جلب أنواع المهام الفريدة من قاعدة البيانات
        // هذا يفترض أن task_type موجود في جدول TaskImageReport
        $taskTypes = TaskImageReport::distinct()->pluck('task_type')->filter()->sort()->values();

        return view('photo_reports.monthly_report_form', compact('units', 'taskTypes'));
    }

    /**
     * توليد تقرير PDF الشهري بناءً على الشهر والسنة ونوع الوحدة ونوع المهمة المحددين.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateMonthlyReport(Request $request)
    {
        $currentYear = date('Y');
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:' . ($currentYear - 5) . '|max:' . $currentYear, // نطاق السنوات المناسب
            'unit_type' => 'nullable|string', // يمكن أن يكون 'all' أو 'cleaning' أو 'sanitation'
            'task_type' => 'nullable|string', // يمكن أن يكون 'all' أو اسم نوع مهمة
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $unitTypeFilter = $request->input('unit_type');
        $taskTypeFilter = $request->input('task_type');

        // الحصول على التقارير للفترة والفلاتر المحددة
        $reportsQuery = TaskImageReport::whereYear('date', $year)
                                     ->whereMonth('date', $month);

        if ($unitTypeFilter && $unitTypeFilter !== 'all') {
            $reportsQuery->where('unit_type', $unitTypeFilter);
        }

        if ($taskTypeFilter && $taskTypeFilter !== 'all') {
            $reportsQuery->where('task_type', $taskTypeFilter);
        }

        $reports = $reportsQuery->orderBy('date')->get();

        // اسم الشهر للعرض في التقرير
        $monthName = Carbon::createFromDate($year, $month, 1)->locale('ar')->monthName;

        // تهيئة نص نوع الوحدة للعرض
        $unit_type_display = 'جميع الوحدات';
        if ($unitTypeFilter === 'cleaning') {
            $unit_type_display = 'النظافة العامة';
        } elseif ($unitTypeFilter === 'sanitation') {
            $unit_type_display = 'المنشآت الصحية';
        }

        // تهيئة نص نوع المهمة للعرض
        $task_type_display = 'جميع المهام';
        if ($taskTypeFilter && $taskTypeFilter !== 'all') {
            $task_type_display = $taskTypeFilter;
        }

        // توليد PDF باستخدام dompdf
        $pdf = Pdf::loadView('photo_reports.monthly_report_pdf', compact('reports', 'monthName', 'year', 'unit_type_display', 'task_type_display'));

        // تأكد من أن الصور في monthly_report_pdf تستخدم المسار المطلق
        // مثال في الـ View: `<img src="{{ $image['absolute_path_for_pdf'] }}">`
        // هذا يتطلب وجود 'absolute_path_for_pdf' accessor في TaskImageReport Model

        return $pdf->download('التقرير_الشهري_المصور_' . $monthName . '_' . $year . '.pdf');
    }
}