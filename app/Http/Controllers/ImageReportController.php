<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskImageReport;
use App\Models\Unit; // استيراد نموذج الوحدة
use Illuminate\Support\Facades\Storage;

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
            'task_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $beforeImagePaths = [];
        if ($request->hasFile('before_images')) {
            foreach ($request->file('before_images') as $imageFile) {
                $path = $imageFile->store('task_reports_before_images', 'public');
                $beforeImagePaths[] = $path;
            }
        }

        $afterImagePaths = [];
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $imageFile) {
                $path = $imageFile->store('task_reports_after_images', 'public');
                $afterImagePaths[] = $path;
            }
        }

        TaskImageReport::create([
            'report_title' => $request->report_title,
            'date' => $request->date,
            'unit_type' => $request->unit_type,
            'location' => $request->location,
            'task_type' => $request->task_type,
            'task_id' => $request->task_id,
            'notes' => $request->notes,
            'before_images' => $beforeImagePaths,
            'after_images' => $afterImagePaths,
            'status' => $request->status,
        ]);

        return redirect()->route('photo_reports.index')
                         ->with('success', 'تم إضافة تقرير المهام المصور بنجاح.');
    }

    /**
     * عرض تفاصيل تقرير مصور واحد.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\View\View
     */
    public function show(TaskImageReport $photo_report)
    {
        return view('photo_reports.show', compact('photo_report'));
    }

    /**
     * عرض نموذج تعديل تقرير مصور موجود.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\View\View
     */
    public function edit(TaskImageReport $photo_report)
    {
        $units = Unit::all(); // جلب جميع الوحدات
        return view('photo_reports.edit', compact('photo_report', 'units'));
    }

    /**
     * تحديث تقرير مصور في قاعدة البيانات.
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
            'task_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'new_before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'new_after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deleted_before_images' => 'nullable|array',
            'deleted_after_images' => 'nullable|array',
        ]);

        // معالجة الصور قبل التنفيذ (before_images)
        $currentBeforeImages = $photo_report->before_images;
        if ($request->has('deleted_before_images')) {
            foreach ($request->deleted_before_images as $pathToDelete) {
                Storage::disk('public')->delete($pathToDelete);
                $currentBeforeImages = array_values(array_filter($currentBeforeImages, function ($path) use ($pathToDelete) {
                    return $path !== $pathToDelete;
                }));
            }
        }
        if ($request->hasFile('new_before_images')) {
            foreach ($request->file('new_before_images') as $imageFile) {
                $path = $imageFile->store('task_reports_before_images', 'public');
                $currentBeforeImages[] = $path;
            }
        }

        // معالجة الصور بعد التنفيذ (after_images)
        $currentAfterImages = $photo_report->after_images;
        if ($request->has('deleted_after_images')) {
            foreach ($request->deleted_after_images as $pathToDelete) {
                Storage::disk('public')->delete($pathToDelete);
                $currentAfterImages = array_values(array_filter($currentAfterImages, function ($path) use ($pathToDelete) {
                    return $path !== $pathToDelete;
                }));
            }
        }
        if ($request->hasFile('new_after_images')) {
            foreach ($request->file('new_after_images') as $imageFile) {
                $path = $imageFile->store('task_reports_after_images', 'public');
                $currentAfterImages[] = $path;
            }
        }

        $photo_report->update([
            'report_title' => $request->report_title,
            'date' => $request->date,
            'unit_type' => $request->unit_type,
            'location' => $request->location,
            'task_type' => $request->task_type,
            'task_id' => $request->task_id,
            'notes' => $request->notes,
            'status' => $request->status,
            'before_images' => $currentBeforeImages,
            'after_images' => $currentAfterImages,
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
        // (هذا هو الملف الذي تم تعديله في ردي السابق ليصبح مخصصًا للعرض فقط)
        return view('photo_reports.show', compact('photo_report')); // تم التغيير من photo_reports.print إلى photo_reports.show
    }
}
