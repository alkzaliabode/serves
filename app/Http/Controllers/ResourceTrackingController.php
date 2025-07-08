<?php

namespace App\Http\Controllers;

use App\Models\ResourceTracking;
use App\Models\Unit; // لجلب الوحدات
use App\Models\GeneralCleaningTask; // لحساب الكفاءة في الجدول
use App\Models\SanitationFacilityTask; // لحساب الكفاءة في الجدول
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session; // للرسائل الومضية
use Illuminate\Support\Facades\Log; // للسجلات

class ResourceTrackingController extends Controller
{
    /**
     * عرض قائمة بسجلات تتبع الموارد.
     */
    public function index(Request $request)
    {
        $query = ResourceTracking::query()->with('unit');

        // تطبيق الفلاتر
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->input('to_date'));
        }

        // البحث (يمكن إضافة منطق بحث أكثر تعقيدًا هنا إذا لزم الأمر)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('unit', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('notes', 'like', '%' . $search . '%');
        }

        $resourceTrackings = $query->orderBy('date', 'desc')->paginate(10);
        $units = Unit::all(); // لجلب الوحدات لفلتر الاختيار

        return view('resource-trackings.index', compact('resourceTrackings', 'units'));
    }

    /**
     * عرض نموذج إنشاء سجل تتبع موارد جديد.
     */
    public function create()
    {
        $units = Unit::all();
        return view('resource-trackings.create', compact('units'));
    }

    /**
     * تخزين سجل تتبع موارد جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'unit_id' => 'required|exists:units,id',
            'working_hours' => 'required|numeric|min:0|max:24',
            'total_supplies_and_tools_score' => 'required|numeric|min:0', // العمود الجديد المدمج
            'notes' => 'nullable|string|max:1000',
        ]);

        ResourceTracking::create($validatedData);

        // بعد إنشاء سجل تتبع الموارد، يجب إعادة حساب ActualResult لهذا اليوم والوحدة
        \App\Models\ActualResult::recalculateForUnitAndDate($validatedData['unit_id'], $validatedData['date']);

        Session::flash('success', 'تم إضافة سجل تتبع الموارد بنجاح.');
        return redirect()->route('resource-trackings.index');
    }

    /**
     * عرض نموذج تعديل سجل تتبع موارد موجود.
     */
    public function edit(ResourceTracking $resourceTracking)
    {
        $units = Unit::all();
        return view('resource-trackings.edit', compact('resourceTracking', 'units'));
    }

    /**
     * تحديث سجل تتبع موارد موجود في قاعدة البيانات.
     */
    public function update(Request $request, ResourceTracking $resourceTracking)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'unit_id' => 'required|exists:units,id',
            'working_hours' => 'required|numeric|min:0|max:24',
            'total_supplies_and_tools_score' => 'required|numeric|min:0', // العمود الجديد المدمج
            'notes' => 'nullable|string|max:1000',
        ]);

        $resourceTracking->update($validatedData);

        // بعد تحديث سجل تتبع الموارد، يجب إعادة حساب ActualResult لهذا اليوم والوحدة
        \App\Models\ActualResult::recalculateForUnitAndDate($validatedData['unit_id'], $validatedData['date']);

        Session::flash('success', 'تم تحديث سجل تتبع الموارد بنجاح.');
        return redirect()->route('resource-trackings.index');
    }

    /**
     * حذف سجل تتبع موارد من قاعدة البيانات.
     */
    public function destroy(ResourceTracking $resourceTracking)
    {
        $resourceTracking->delete();
        Session::flash('success', 'تم حذف سجل تتبع الموارد بنجاح.');
        return redirect()->route('resource-trackings.index');
    }

    /**
     * دالة لتوليد/تحديث بيانات الموارد اليومية لجميع الوحدات.
     * يمكن استدعاؤها من خلال إجراء يدوي أو جدولة (Cron Job).
     */
    public function generateDailyResourceData()
    {
        $units = Unit::all();
        $today = now()->format('Y-m-d');

        // تعريف قيم افتراضية لـ "نقاط" المستلزمات والمعدات لكل مهمة مكتملة
        // ✅ هذه القيم يجب ضبطها بدقة بناءً على متوسط استهلاكك الفعلي وأهمية كل بند
        $supplies_and_tools_score_per_sanitation_task = 1.0; // نقطة لكل مهمة تنظيف مرفق صحي
        $supplies_and_tools_score_per_general_task = 2.0;    // نقطة لكل مهمة نظافة عامة

        // نقاط إضافية لكل عنصر مورد يتم تحديده يدوياً في 'resources_used'
        // هذه تضاف إلى النقاط الافتراضية لكل مهمة
        $resource_item_score = 0.1; // نقطة لكل وحدة من عنصر مورد (مثلاً: 0.1 نقطة لكل لتر منظف، أو لكل مكنسة)


        foreach ($units as $unit) {
            Log::debug("Generating resource data for Unit: {$unit->name} on Date: {$today}");

            $existingRecord = ResourceTracking::where('unit_id', $unit->id)
                ->where('date', $today)
                ->first();

            $totalSuppliesAndToolsScore = 0; // المتغير الجديد المدمج
            $totalWorkingHours = 0;

            // جمع الموارد وساعات العمل من GeneralCleaningTask
            $generalCleaningTasks = GeneralCleaningTask::where('unit_id', $unit->id)
                ->whereDate('date', $today)
                ->where('status', 'مكتمل')
                ->get();

            Log::debug("General Cleaning Tasks for {$unit->name}: " . $generalCleaningTasks->count() . " tasks found.");

            // إضافة نقاط افتراضية لكل مهمة نظافة عامة مكتملة
            $totalSuppliesAndToolsScore += ($generalCleaningTasks->count() * $supplies_and_tools_score_per_general_task);

            foreach ($generalCleaningTasks as $task) {
                $totalWorkingHours += $task->working_hours ?? 0;
                Log::debug("Processing General Task ID: {$task->id}, Working Hours: {$task->working_hours}");

                foreach ($task->resources_used ?? [] as $resource) {
                    $itemName = $resource['name'] ?? '';
                    $quantity = (float)($resource['quantity'] ?? 0);
                    Log::debug("General Task Resource: Item='{$itemName}', Quantity={$quantity}");

                    // كلمات مفتاحية لمواد التنظيف والمعدات (الآن تساهم كلها في نفس المتغير المدمج)
                    if (
                        stripos($itemName, 'منظف') !== false || stripos($itemName, 'صابون') !== false ||
                        stripos($itemName, 'معقم') !== false || stripos($itemName, 'كفوف') !== false ||
                        stripos($itemName, 'كمامات') !== false || stripos($itemName, 'كاسات') !== false ||
                        stripos($itemName, 'نفايات') !== false || stripos($itemName, 'أكياس نفايات') !== false ||
                        stripos($itemName, 'زاهي') !== false || stripos($itemName, 'مطهر') !== false || 
                        stripos($itemName, 'معطر') !== false || stripos($itemName, 'تيزاب') !== false ||
                        stripos($itemName, 'فلاش') !== false || stripos($itemName, 'ملمع') !== false ||
                        stripos($itemName, 'سيم') !== false || stripos($itemName, 'كلور') !== false || 
                        stripos($itemName, 'ديتول') !== false || stripos($itemName, 'مبيض') !== false ||
                        stripos($itemName, 'مزيل بقع') !== false || stripos($itemName, 'فوطة') !== false ||
                        stripos($itemName, 'قماش') !== false ||
                        // كلمات مفتاحية للمعدات
                        stripos($itemName, 'مكنسة') !== false || stripos($itemName, 'ممسحة') !== false ||
                        stripos($itemName, 'جهاز') !== false || stripos($itemName, 'فرشاة') !== false ||
                        stripos($itemName, 'مضخة') !== false || stripos($itemName, 'مكرفة') !== false ||
                        stripos($itemName, 'عربة') !== false || stripos($itemName, 'دلو') !== false ||
                        stripos($itemName, 'خرطوم') !== false || stripos($itemName, 'سلم') !== false ||
                        stripos($itemName, 'منفضة') !== false || stripos($itemName, 'مجرفة') !== false ||
                        stripos($itemName, 'آلة') !== false || stripos($itemName, 'معدات') !== false
                    ) {
                        $totalSuppliesAndToolsScore += ($quantity * $resource_item_score); // إضافة نقاط بناءً على الكمية
                    }
                    // ملاحظة: استهلاك الماء لم يعد يتم تتبعه هنا
                }
            }

            // جمع الموارد وساعات العمل من SanitationFacilityTask
            $sanitationTasks = SanitationFacilityTask::where('unit_id', $unit->id)
                ->whereDate('date', $today)
                ->where('status', 'مكتمل')
                ->get();

            Log::debug("Sanitation Facility Tasks for {$unit->name}: " . $sanitationTasks->count() . " tasks found.");

            // إضافة نقاط افتراضية لكل مهمة مرافق صحية مكتملة
            $totalSuppliesAndToolsScore += ($sanitationTasks->count() * $supplies_and_tools_score_per_sanitation_task);

            foreach ($sanitationTasks as $task) {
                $totalWorkingHours += $task->working_hours ?? 0;
                Log::debug("Processing Sanitation Task ID: {$task->id}, Working Hours: {$task->working_hours}");

                foreach ($task->resources_used ?? [] as $resource) {
                    $itemName = $resource['name'] ?? '';
                    $quantity = (float)($resource['quantity'] ?? 0);
                    Log::debug("Sanitation Task Resource: Item='{$itemName}', Quantity={$quantity}");

                    // كلمات مفتاحية لمواد التنظيف والمعدات (الآن تساهم كلها في نفس المتغير المدمج)
                    if (
                        stripos($itemName, 'منظف') !== false || stripos($itemName, 'مطهر') !== false ||
                        stripos($itemName, 'معطر') !== false || stripos($itemName, 'تيزاب') !== false ||
                        stripos($itemName, 'فلاش') !== false || stripos($itemName, 'زاهي') !== false ||
                        stripos($itemName, 'صابون') !== false || stripos($itemName, 'صابون سائل') !== false ||
                        stripos($itemName, 'ملمع') !== false || stripos($itemName, 'سيم') !== false ||
                        stripos($itemName, 'جلافة') !== false || stripos($itemName, 'بطش') !== false ||
                        stripos($itemName, 'سفنجة') !== false || stripos($itemName, 'كفوف') !== false ||
                        stripos($itemName, 'مكرافة') !== false || stripos($itemName, 'مقشة') !== false ||
                        stripos($itemName, 'شفرة') !== false || stripos($itemName, 'كمامات') !== false ||
                        stripos($itemName, 'كاسات') !== false || stripos($itemName, 'نفايات') !== false ||
                        stripos($itemName, 'أكياس نفايات') !== false || stripos($itemName, 'كلور') !== false ||
                        stripos($itemName, 'ديتول') !== false || stripos($itemName, 'مبيض') !== false ||
                        stripos($itemName, 'مزيل بقع') !== false || stripos($itemName, 'فوطة') !== false ||
                        stripos($itemName, 'قماش') !== false ||
                        // كلمات مفتاحية للمعدات
                        stripos($itemName, 'فرشاة') !== false || stripos($itemName, 'مضخة') !== false ||
                        stripos($itemName, 'مكنسة') !== false || stripos($itemName, 'ممسحة') !== false ||
                        stripos($itemName, 'جهاز') !== false || stripos($itemName, 'مكرفة') !== false ||
                        stripos($itemName, 'عربة') !== false || stripos($itemName, 'دلو') !== false ||
                        stripos($itemName, 'خرطوم') !== false || stripos($itemName, 'سلم') !== false ||
                        stripos($itemName, 'منفضة') !== false || stripos($itemName, 'مجرفة') !== false ||
                        stripos($itemName, 'آلة') !== false || stripos($itemName, 'معدات') !== false
                    ) {
                        $totalSuppliesAndToolsScore += ($quantity * $resource_item_score); // إضافة نقاط بناءً على الكمية
                    }
                    // ملاحظة: استهلاك الماء لم يعد يتم تتبعه هنا
                }
            }

            $totalWorkingHours = $totalWorkingHours > 0 ? $totalWorkingHours : 8;

            Log::debug("Final totals for Unit {$unit->name}: Working Hours={$totalWorkingHours}, Total Supplies & Tools Score={$totalSuppliesAndToolsScore}");

            if (!$existingRecord) {
                ResourceTracking::create([
                    'date' => $today,
                    'unit_id' => $unit->id,
                    'working_hours' => $totalWorkingHours,
                    'total_supplies_and_tools_score' => $totalSuppliesAndToolsScore, // استخدام العمود الجديد
                    'notes' => 'بيانات تم توليدها تلقائياً لـ ' . $unit->name . ' بتاريخ ' . $today . ' بناءً على المهام المكتملة.',
                ]);
                Log::debug("Created new ResourceTracking record for Unit: {$unit->name}");
            } else {
                $existingRecord->update([
                    'working_hours' => $totalWorkingHours,
                    'total_supplies_and_tools_score' => $totalSuppliesAndToolsScore, // استخدام العمود الجديد
                    'notes' => 'بيانات موارد تم تحديثها تلقائياً لـ ' . $unit->name . ' بتاريخ ' . $today . ' بناءً على المهام المكتملة.',
                ]);
                Log::debug("Updated existing ResourceTracking record for Unit: {$unit->name}");
            }

            \App\Models\ActualResult::recalculateForUnitAndDate($unit->id, $today);
            Log::debug("ActualResult recalculated for Unit: {$unit->name} after ResourceTracking update.");
        }

        Session::flash('success', 'تم توليد / تحديث بيانات الموارد لليوم بنجاح.');
        return redirect()->back();
    }
}
