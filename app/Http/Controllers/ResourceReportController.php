<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanitationFacilityTask;
use App\Models\GeneralCleaningTask;
use App\Models\Unit; // افتراض وجود نموذج الوحدة
use Carbon\Carbon;

class ResourceReportController extends Controller
{
    /**
     * Display the resource report (main view with filters).
     * عرض تقرير الموارد (العرض الرئيسي مع الفلاتر).
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // تهيئة المتغيرات
        $resources = collect(); // حافظ على أنها كائن Collection
        $totalQuantityForSearchItem = 0;
        $formattedSelectedMonth = '';

        // جلب معلمات البحث والتصفية من الطلب
        $searchItem = $request->input('searchItem', '');
        // الافتراضي هو الشهر الحالي إذا لم يتم تحديده
        $selectedMonth = $request->input('selectedMonth', Carbon::now()->format('Y-m'));

        // تنسيق الشهر المختار للعرض
        if (!empty($selectedMonth)) {
            try {
                $formattedSelectedMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y');
            } catch (\Exception $e) {
                // تسجيل الخطأ أو التعامل معه، وإعادة تعيين المتغيرات
                \Log::error('Invalid month format for resource report: ' . $selectedMonth . ' - ' . $e->getMessage());
                $formattedSelectedMonth = '';
            }
        }

        // بناء استعلامات المهام مع eager loading لنموذج الوحدة
        $sanitationQuery = SanitationFacilityTask::with('unit');
        $generalCleaningQuery = GeneralCleaningTask::with('unit');

        // تطبيق فلتر الشهر على الاستعلامات
        if (!empty($selectedMonth)) {
            try {
                $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
                $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

                $sanitationQuery->whereBetween('date', [$startDate, $endDate]);
                $generalCleaningQuery->whereBetween('date', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // التعامل مع الخطأ إذا كان تنسيق الشهر غير صحيح، أو تركه بدون تصفية الشهر
                \Log::error('Error applying month filter in resource report index: ' . $e->getMessage());
            }
        }

        // جلب البيانات بعد تطبيق فلتر الشهر
        $sanitationTasks = $sanitationQuery->get();
        $generalCleaningTasks = $generalCleaningQuery->get();

        // معالجة مهام مرافق الصرف الصحي
        $sanitationTasks->each(function ($task) use (&$resources, $searchItem, &$totalQuantityForSearchItem) {
            foreach ($task->resources_used ?? [] as $res) {
                $itemName = $res['name'] ?? '-';
                $quantity = (float)($res['quantity'] ?? 0);

                // تطبيق فلتر البحث على اسم المورد
                if (empty($searchItem) || stripos($itemName, $searchItem) !== false) {
                    $resources->push([
                        'date' => Carbon::parse($task->date)->format('Y-m-d'), // تنسيق التاريخ
                        'unit' => $task->unit->name ?? '---', // اسم الوحدة
                        'task_type' => $task->task_type, // نوع المهمة (إدامة/صيانة)
                        'item' => $itemName, // اسم المورد
                        'quantity' => $quantity, // الكمية
                        'resource_unit' => $res['unit'] ?? '-', // وحدة قياس المورد
                        'notes' => $res['notes'] ?? '', // ملاحظات المورد
                    ]);

                    // حساب الإجمالي إذا كان هناك عنصر بحث محدد ومطابق
                    if (!empty($searchItem) && stripos($itemName, $searchItem) !== false) {
                        $totalQuantityForSearchItem += $quantity;
                    }
                }
            }
        });

        // معالجة مهام التنظيف العام
        $generalCleaningTasks->each(function ($task) use (&$resources, $searchItem, &$totalQuantityForSearchItem) {
            foreach ($task->resources_used ?? [] as $res) {
                $itemName = $res['name'] ?? '-';
                $quantity = (float)($res['quantity'] ?? 0);

                // تطبيق فلتر البحث على اسم المورد
                if (empty($searchItem) || stripos($itemName, $searchItem) !== false) {
                    $resources->push([
                        'date' => Carbon::parse($task->date)->format('Y-m-d'), // تنسيق التاريخ
                        'unit' => $task->unit->name ?? '---', // اسم الوحدة
                        'task_type' => $task->task_type, // نوع المهمة (عامة)
                        'item' => $itemName, // اسم المورد
                        'quantity' => $quantity, // الكمية
                        'resource_unit' => $res['unit'] ?? '-', // وحدة قياس المورد
                        'notes' => $res['notes'] ?? '', // ملاحظات المورد
                    ]);

                    // حساب الإجمالي إذا كان هناك عنصر بحث محدد ومطابق
                    if (!empty($searchItem) && stripos($itemName, $searchItem) !== false) {
                        $totalQuantityForSearchItem += $quantity;
                    }
                }
            }
        });

        // فرز الموارد حسب التاريخ (الأحدث أولاً)
        // ✅ تم إزالة ->toArray() هنا
        $resources = $resources->sortByDesc('date')->values();

        // تمرير البيانات إلى واجهة العرض الرئيسية
        return view('reports.resource-report', compact(
            'resources',
            'searchItem',
            'selectedMonth',
            'totalQuantityForSearchItem',
            'formattedSelectedMonth'
        ));
    }

    /**
     * Display the resource report specifically for printing.
     * عرض تقرير الموارد خصيصًا للطباعة.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function print(Request $request)
    {
        // تهيئة المتغيرات
        $resources = collect(); // حافظ على أنها كائن Collection
        $totalQuantityForSearchItem = 0;
        $formattedSelectedMonth = '';

        // جلب معلمات البحث والتصفية من الطلب
        $searchItem = $request->input('searchItem', '');
        $selectedMonth = $request->input('selectedMonth', Carbon::now()->format('Y-m'));

        // تنسيق الشهر المختار للعرض
        if (!empty($selectedMonth)) {
            try {
                $formattedSelectedMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y');
            } catch (\Exception $e) {
                \Log::error('Invalid month format for resource report print: ' . $selectedMonth . ' - ' . $e->getMessage());
                $formattedSelectedMonth = '';
            }
        }

        // بناء استعلامات المهام مع eager loading لنموذج الوحدة
        $sanitationQuery = SanitationFacilityTask::with('unit');
        $generalCleaningQuery = GeneralCleaningTask::with('unit');

        // تطبيق فلتر الشهر على الاستعلامات
        if (!empty($selectedMonth)) {
            try {
                $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
                $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

                $sanitationQuery->whereBetween('date', [$startDate, $endDate]);
                $generalCleaningQuery->whereBetween('date', [$startDate, $endDate]);
            } catch (\Exception $e) {
                \Log::error('Error applying month filter in resource report print: ' . $e->getMessage());
            }
        }

        // جلب البيانات بعد تطبيق فلتر الشهر
        $sanitationTasks = $sanitationQuery->get();
        $generalCleaningTasks = $generalCleaningQuery->get();

        // معالجة مهام مرافق الصرف الصحي
        $sanitationTasks->each(function ($task) use (&$resources, $searchItem, &$totalQuantityForSearchItem) {
            foreach ($task->resources_used ?? [] as $res) {
                $itemName = $res['name'] ?? '-';
                $quantity = (float)($res['quantity'] ?? 0);

                if (empty($searchItem) || stripos($itemName, $searchItem) !== false) {
                    $resources->push([
                        'date' => Carbon::parse($task->date)->format('Y-m-d'),
                        'unit' => $task->unit->name ?? '---',
                        'task_type' => $task->task_type,
                        'item' => $itemName,
                        'quantity' => $quantity,
                        'resource_unit' => $res['unit'] ?? '-',
                        'notes' => $res['notes'] ?? '',
                    ]);

                    if (!empty($searchItem) && stripos($itemName, $searchItem) !== false) {
                        $totalQuantityForSearchItem += $quantity;
                    }
                }
            }
        });

        // معالجة مهام التنظيف العام
        $generalCleaningTasks->each(function ($task) use (&$resources, $searchItem, &$totalQuantityForSearchItem) {
            foreach ($task->resources_used ?? [] as $res) {
                $itemName = $res['name'] ?? '-';
                $quantity = (float)($res['quantity'] ?? 0);

                if (empty($searchItem) || stripos($itemName, $searchItem) !== false) {
                    $resources->push([
                        'date' => Carbon::parse($task->date)->format('Y-m-d'),
                        'unit' => $task->unit->name ?? '---',
                        'task_type' => $task->task_type,
                        'item' => $itemName,
                        'quantity' => $quantity,
                        'resource_unit' => $res['unit'] ?? '-',
                        'notes' => $res['notes'] ?? '',
                    ]);

                    if (!empty($searchItem) && stripos($itemName, $searchItem) !== false) {
                        $totalQuantityForSearchItem += $quantity;
                    }
                }
            }
        });

        // فرز الموارد حسب التاريخ (الأحدث أولاً)
        // ✅ تم إزالة ->toArray() هنا
        $resources = $resources->sortByDesc('date')->values();

        // تمرير البيانات إلى واجهة عرض الطباعة
        return view('reports.resource-report-print', compact(
            'resources',
            'searchItem',
            'selectedMonth',
            'totalQuantityForSearchItem',
            'formattedSelectedMonth'
        ));
    }
}
