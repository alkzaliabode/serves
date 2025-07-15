<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskImageReport;
use App\Models\Unit; // تأكد من استيراد نموذج الوحدة
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // استيراد Carbon للتعامل مع التواريخ
use Barryvdh\DomPDF\Facade\Pdf; // استيراد الواجهة لـ DomPDF
use Illuminate\Validation\Rule; // ✅ استيراد Rule لاستخدامه في قواعد التحقق

class ImageReportController extends Controller
{
    /**
     * عرض قائمة بجميع التقارير المصورة.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // تم تحديث اسم المتغير الذي يتم تمريره ليتوافق مع $photo_reports في View
        $photo_reports = TaskImageReport::latest()->get();

        // 💡 جديد: معالجة مسارات الصور لعرضها كصور مصغرة في جدول القائمة
        foreach ($photo_reports as $report) {
            // التأكد من أن before_images و after_images هي مصفوفات
            // إذا كان النموذج يستخدم $casts لـ 'array'، فلن تحتاج إلى json_decode هنا
            // ولكن لضمان التوافقية القصوى، سنقوم بإجراء فحص دفاعي
            $beforeImages = $report->before_images;
            if (!is_array($beforeImages)) {
                $beforeImages = json_decode($beforeImages, true) ?? [];
            }

            $afterImages = $report->after_images;
            if (!is_array($afterImages)) {
                $afterImages = json_decode($afterImages, true) ?? [];
            }

            // تحويل مسارات الصور إلى URLs قابلة للاستخدام في الواجهة
            $report->before_images_display_urls = collect($beforeImages)->map(function ($path) {
                return Storage::url($path);
            })->all();

            $report->after_images_display_urls = collect($afterImages)->map(function ($path) {
                return Storage::url($path);
            })->all();
        }

        return view('photo_reports.index', compact('photo_reports'));
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
        $validatedData = $request->validate([ // ✅ استخدام متغير لتخزين البيانات المتحقق منها
            'report_title' => 'required|string|max:255',
            'date' => 'required|date',
            'unit_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'task_id' => 'nullable|string|max:255',
            'task_type' => 'nullable|string|max:255',
            'status' => ['required', 'string', Rule::in(['مكتمل', 'قيد التنفيذ', 'ملغى'])],
            'notes' => 'nullable|string',
            'before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $beforeImagePaths = [];
        if ($request->hasFile('before_images')) {
            foreach ($request->file('before_images') as $file) {
                $path = $file->store('uploads/before_images', 'public');
                $beforeImagePaths[] = $path;
            }
        }

        $afterImagePaths = [];
        if ($request->hasFile('after_images')) {
            foreach ($request->file('after_images') as $file) {
                $path = $file->store('uploads/after_images', 'public');
                $afterImagePaths[] = $path;
            }
        }

        // ✅ إنشاء مصفوفة البيانات المراد حفظها، مع استبعاد الحقول غير الموجودة في قاعدة البيانات
        TaskImageReport::create([
            'report_title' => $validatedData['report_title'],
            'date' => $validatedData['date'],
            'unit_type' => $validatedData['unit_type'],
            'location' => $validatedData['location'],
            'task_id' => $validatedData['task_id'],
            'task_type' => $validatedData['task_type'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'],
            'before_images' => $beforeImagePaths,
            'after_images' => $afterImagePaths,
            // 'before_images_count' و 'after_images_count' ليسا أعمدة في قاعدة البيانات
            // لذلك لا يجب تمريرهما هنا.
        ]);

        return redirect()->route('photo_reports.index')->with('success', 'تم إنشاء التقرير المصور بنجاح.');
    }

    /**
     * عرض تفاصيل تقرير مصور محدد.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\View\View
     */
    public function show(TaskImageReport $photo_report)
    {
        // تهيئة نوع الوحدة للعرض
        $unitName = $photo_report->unit_type === 'cleaning' ? 'النظافة العامة' : 'المنشآت الصحية';

        // 💡 تم التعديل: التأكد من أن before_images و after_images هي مصفوفات بشكل صريح
        $beforeImages = $photo_report->before_images;
        if (!is_array($beforeImages)) {
            $beforeImages = json_decode($beforeImages, true) ?? [];
        }

        $afterImages = $photo_report->after_images;
        if (!is_array($afterImages)) {
            $afterImages = json_decode($afterImages, true) ?? [];
        }

        // تحويل مسارات الصور إلى URLs قابلة للاستخدام في الواجهة
        $beforeImagesUrls = collect($beforeImages)->map(function ($path) {
            return ['path' => $path, 'url' => Storage::url($path)];
        })->all();

        $afterImagesUrls = collect($afterImages)->map(function ($path) {
            return ['path' => $path, 'url' => Storage::url($path)];
        })->all();


        return view('photo_reports.show', compact('photo_report', 'unitName', 'beforeImagesUrls', 'afterImagesUrls'));
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
        return view('photo_reports.edit', compact('photo_report', 'units'));
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
        $validatedData = $request->validate([ // ✅ استخدام متغير لتخزين البيانات المتحقق منها
            'report_title' => 'required|string|max:255',
            'date' => 'required|date',
            'unit_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'task_id' => 'nullable|string|max:255',
            'task_type' => 'nullable|string|max:255',
            'status' => ['required', 'string', Rule::in(['مكتمل', 'قيد التنفيذ', 'ملغى'])],
            'notes' => 'nullable|string',
            'new_before_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // للصور الجديدة
            'new_after_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',   // للصور الجديدة
            'deleted_before_images' => 'nullable|json', // الصور التي تم حذفها
            'deleted_after_images' => 'nullable|json',   // الصور التي تم حذفها
        ]);

        // التعامل مع الصور الموجودة وحذف الصور المحددة للحذف
        // 💡 تم التعديل: التأكد من أن currentBeforeImages و currentAfterImages هي مصفوفات بشكل صريح
        $currentBeforeImages = $photo_report->before_images;
        if (!is_array($currentBeforeImages)) {
            $currentBeforeImages = json_decode($currentBeforeImages, true) ?? [];
        }

        $currentAfterImages = $photo_report->after_images;
        if (!is_array($currentAfterImages)) {
            $currentAfterImages = json_decode($currentAfterImages, true) ?? [];
        }

        $deletedBeforeImages = json_decode($request->input('deleted_before_images', '[]'), true) ?? [];
        $deletedAfterImages = json_decode($request->input('deleted_after_images', '[]'), true) ?? [];
        // حذف الصور من التخزين
        foreach ($deletedBeforeImages as $path) {
            Storage::disk('public')->delete($path);
        }
        foreach ($deletedAfterImages as $path) {
            Storage::disk('public')->delete($path);
        }

        // تصفية الصور المتبقية
        $updatedBeforeImages = array_values(array_diff($currentBeforeImages, $deletedBeforeImages));
        $updatedAfterImages = array_values(array_diff($currentAfterImages, $deletedAfterImages));

        // إضافة الصور الجديدة
        if ($request->hasFile('new_before_images')) {
            foreach ($request->file('new_before_images') as $file) {
                $path = $file->store('uploads/before_images', 'public');
                $updatedBeforeImages[] = $path;
            }
        }
        if ($request->hasFile('new_after_images')) {
            foreach ($request->file('new_after_images') as $file) {
                $path = $file->store('uploads/after_images', 'public');
                $updatedAfterImages[] = $path;
            }
        }

        // ✅ إنشاء مصفوفة البيانات المراد حفظها، مع استبعاد الحقول غير الموجودة في قاعدة البيانات
        $photo_report->update([
            'report_title' => $validatedData['report_title'],
            'date' => $validatedData['date'],
            'unit_type' => $validatedData['unit_type'],
            'location' => $validatedData['location'],
            'task_id' => $validatedData['task_id'],
            'task_type' => $validatedData['task_type'],
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'],
            'before_images' => $updatedBeforeImages,
            'after_images' => $updatedAfterImages,
            // 'before_images_count' و 'after_images_count' ليسا أعمدة في قاعدة البيانات
            // لذلك لا يجب تمريرهما هنا.
        ]);

        return redirect()->route('photo_reports.index')->with('success', 'تم تحديث التقرير المصور بنجاح.');
    }

    /**
     * حذف تقرير مصور محدد.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TaskImageReport $photo_report)
    {
        // حذف الصور المرتبطة بالتقرير من التخزين قبل حذف التقرير نفسه
        // 💡 تم التعديل: التأكد من أن beforeImages و afterImages هي مصفوفات بشكل صريح
        $beforeImages = $photo_report->before_images;
        if (!is_array($beforeImages)) {
            $beforeImages = json_decode($beforeImages, true) ?? [];
        }

        $afterImages = $photo_report->after_images;
        if (!is_array($afterImages)) {
            $afterImages = json_decode($afterImages, true) ?? [];
        }

        foreach ($beforeImages as $path) {
            Storage::disk('public')->delete($path);
        }
        foreach ($afterImages as $path) {
            Storage::disk('public')->delete($path);
        }

        $photo_report->delete();
        return redirect()->route('photo_reports.index')->with('success', 'تم حذف التقرير المصور بنجاح.');
    }

    /**
     * يولد تقرير PDF شهري للتقارير المصورة بناءً على الشهر والسنة ونوع الوحدة ونوع المهمة المحددين.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateMonthlyReport(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000|max:' . (Carbon::now()->year + 5),
            'unit_type' => 'nullable|string',
            'task_type' => 'nullable|string',
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $unitTypeFilter = $request->input('unit_type');
        $taskTypeFilter = $request->input('task_type');

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

        // إعداد الصور للملف الشخصي لـ PDF
        foreach ($reports as $report) {
            // 💡 تم التعديل: التأكد من أن beforeImages و afterImages هي مصفوفات بشكل صريح
            $beforeImages = $report->before_images;
            if (!is_array($beforeImages)) {
                $beforeImages = json_decode($beforeImages, true) ?? [];
            }

            $afterImages = $report->after_images;
            if (!is_array($afterImages)) {
                $afterImages = json_decode($afterImages, true) ?? [];
            }

            $report->before_images_urls = collect($beforeImages)->map(function ($path) {
                $absolutePath = public_path('storage/' . $path);
                return [
                    'path' => $path,
                    'url' => Storage::url($path), // للاستخدام في الويب
                    'absolute_path_for_pdf' => file_exists($absolutePath) ? $absolutePath : null, // للملف الشخصي PDF
                    'exists' => file_exists($absolutePath)
                ];
            })->filter(function($item) { return $item['exists']; })->all(); // تصفية الصور غير الموجودة

            $report->after_images_urls = collect($afterImages)->map(function ($path) {
                $absolutePath = public_path('storage/' . $path);
                return [
                    'path' => $path,
                    'url' => Storage::url($path),
                    'absolute_path_for_pdf' => file_exists($absolutePath) ? $absolutePath : null,
                    'caption' => '',
                    'exists' => file_exists($absolutePath)
                ];
            })->filter(function($item) { return $item['exists']; })->all();

            $report->before_images_count = count($report->before_images_urls);
            $report->after_images_count = count($report->after_images_urls);
        }

        // توليد PDF باستخدام dompdf
        $pdf = Pdf::loadView('photo_reports.monthly_report_pdf', compact('reports', 'monthName', 'year', 'unit_type_display', 'task_type_display'));
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->setPaper('A4', 'portrait'); // يمكن أن يكون portrait أو landscape

        return $pdf->stream('Monthly_Image_Report_' . $monthName . '_' . $year . '.pdf');
    }

    /**
     * 💡 دالة جديدة: طباعة تقرير مصور واحد في صفحة واحدة.
     *
     * @param  \App\Models\TaskImageReport  $photo_report
     * @return \Illuminate\View\View
     */
    public function printSingleReport(TaskImageReport $photo_report)
    {
        // تهيئة نوع الوحدة للعرض
        $unitName = $photo_report->unit_type === 'cleaning' ? 'النظافة العامة' : 'المنشآت الصحية';

        // 💡 تم التعديل: التأكد من أن beforeImages و afterImages هي مصفوفات بشكل صريح
        $beforeImages = $photo_report->before_images;
        if (!is_array($beforeImages)) {
            $beforeImages = json_decode($beforeImages, true) ?? [];
        }

        $afterImages = $photo_report->after_images;
        if (!is_array($afterImages)) {
            $afterImages = json_decode($afterImages, true) ?? [];
        }

        // معالجة مسارات الصور لتكون جاهزة للاستخدام في src بالـ PDF
        $processedBeforeImages = [];
        foreach ($beforeImages as $imagePath) {
            $absolutePath = public_path('storage/' . $imagePath);
            $processedBeforeImages[] = [
                'path' => $imagePath,
                'url' => Storage::url($imagePath), // للاستخدام في الويب
                'absolute_path_for_pdf' => file_exists($absolutePath) ? $absolutePath : null, // للملف الشخصي PDF
                'caption' => '', // يمكنك إضافة تسمية توضيحية هنا إذا كانت موجودة في بياناتك
            ];
        }

        $processedAfterImages = [];
        foreach ($afterImages as $imagePath) {
            $absolutePath = public_path('storage/' . $imagePath);
            $processedAfterImages[] = [
                'path' => $imagePath,
                'url' => Storage::url($imagePath),
                'absolute_path_for_pdf' => file_exists($absolutePath) ? $absolutePath : null,
                'caption' => '',
            ];
        }

        // تمرير البيانات إلى الواجهة print_only
        return view('photo_reports.print_only', compact('photo_report', 'unitName', 'processedBeforeImages', 'processedAfterImages'));
    }
}
