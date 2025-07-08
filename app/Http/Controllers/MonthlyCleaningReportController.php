<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyGeneralCleaningSummary; // تأكد من وجود هذا النموذج
use Carbon\Carbon; // تأكد من استيراد Carbon إذا كنت تستخدمه لمعالجة التواريخ

class MonthlyCleaningReportController extends Controller
{
    /**
     * Display the monthly general cleaning report (main view with filters).
     * عرض تقرير النظافة العامة الشهري (العرض الرئيسي مع الفلاتر).
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // تهيئة المتغيرات للاستعلام
        $query = MonthlyGeneralCleaningSummary::query();

        // جلب معلمات البحث والتصفية من الطلب
        $selectedMonth = $request->input('month', '');
        $selectedLocation = $request->input('location', '');
        $selectedTaskType = $request->input('task_type', '');
        $searchQuery = $request->input('search', ''); // للبحث العام

        // تطبيق فلتر الشهر
        if (!empty($selectedMonth)) {
            $query->where('month', $selectedMonth);
        }

        // تطبيق فلتر الموقع
        if (!empty($selectedLocation)) {
            $query->where('location', $selectedLocation);
        }

        // تطبيق فلتر نوع المهمة
        if (!empty($selectedTaskType)) {
            $query->where('task_type', $selectedTaskType);
        }

        // تطبيق البحث العام على الأعمدة النصية
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('month', 'like', '%' . $searchQuery . '%')
                  ->orWhere('location', 'like', '%' . $searchQuery . '%')
                  ->orWhere('task_type', 'like', '%' . $searchQuery . '%');
                // يمكنك إضافة المزيد من الأعمدة هنا للبحث حسب الحاجة (مثلاً 'notes', 'description')
            });
        }

        // تطبيق الفرز بناءً على طلب المستخدم
        // الافتراضي هو الفرز حسب الشهر تنازليًا
        $sortBy = $request->input('sort_by', 'month');
        $sortOrder = $request->input('sort_order', 'desc');

        // قائمة الأعمدة المسموح بها للفرز (لمنع حقن SQL)
        $allowedSortColumns = [
            'month', 'location', 'task_type',
            'total_mats', 'total_pillows', 'total_fans',
            'total_windows', 'total_carpets', 'total_blankets',
            'total_beds', 'total_beneficiaries', 'total_trams',
            'total_laid_carpets', 'total_large_containers', 'total_small_containers'
        ];

        // التحقق من صحة عمود الفرز
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'month'; // الرجوع إلى القيمة الافتراضية إذا كان العمود غير مسموح به
        }
        // التحقق من صحة ترتيب الفرز
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc'; // الرجوع إلى القيمة الافتراضية إذا كان الترتيب غير صحيح
        }

        $query->orderBy($sortBy, $sortOrder);

        // جلب النتائج
        $reports = $query->get(); // أو paginate(15) إذا كنت تستخدم تقسيم الصفحات

        // جلب خيارات الفلاتر المتاحة من قاعدة البيانات
        // استخدام pluck للحصول على مصفوفة مفتاح-قيمة لتناسب الـ <select>
        $availableMonths = MonthlyGeneralCleaningSummary::select('month')->distinct()->orderBy('month', 'desc')->pluck('month', 'month')->toArray();
        $availableLocations = MonthlyGeneralCleaningSummary::select('location')->distinct()->pluck('location', 'location')->toArray();
        
        // أنواع المهام المحددة (يمكن جلبها من قاعدة البيانات أو تعريفها يدويًا)
        $availableTaskTypes = [
            'إدامة' => 'إدامة',
            'صيانة' => 'صيانة',
            // أضف أي أنواع مهام أخرى هنا حسب البيانات الموجودة في قاعدة البيانات
        ];

        // تمرير البيانات إلى واجهة العرض الرئيسية (index.blade.php)
        return view('monthly-cleaning-report.index', compact(
            'reports',
            'availableMonths',
            'availableLocations',
            'availableTaskTypes',
            'selectedMonth',
            'selectedLocation',
            'selectedTaskType',
            'searchQuery',
            'sortBy', // تمرير متغيرات الفرز لاستخدامها في روابط أيقونات الفرز
            'sortOrder'
        ));
    }

    /**
     * Display the specified monthly general cleaning summary for editing.
     * عرض ملخص النظافة العامة الشهري المحدد للتعديل.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        $report = MonthlyGeneralCleaningSummary::findOrFail($id);
        // يمكنك هنا جلب أي بيانات إضافية تحتاجها لنموذج التعديل، مثل قائمة الأهداف أو الموظفين
        // على سبيل المثال: $goals = Goal::all(); $employees = Employee::all();
        return view('monthly-cleaning-report.edit', compact('report'));
    }

    /**
     * Update the specified monthly general cleaning summary in storage.
     * تحديث ملخص النظافة العامة الشهري المحدد في قاعدة البيانات.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        // قواعد التحقق من صحة البيانات
        $validatedData = $request->validate([
            'month'                     => 'required|string|max:7', // مثال: YYYY-MM
            'location'                  => 'required|string|max:255',
            'task_type'                 => 'required|string|in:إدامة,صيانة',
            'total_mats'                => 'nullable|integer|min:0',
            'total_pillows'             => 'nullable|integer|min:0',
            'total_fans'                => 'nullable|integer|min:0',
            'total_windows'             => 'nullable|integer|min:0',
            'total_carpets'             => 'nullable|integer|min:0',
            'total_blankets'            => 'nullable|integer|min:0',
            'total_beds'                => 'nullable|integer|min:0',
            'total_beneficiaries'       => 'nullable|integer|min:0',
            'total_trams'               => 'nullable|integer|min:0',
            'total_laid_carpets'        => 'nullable|integer|min:0',
            'total_large_containers'    => 'nullable|integer|min:0',
            'total_small_containers'    => 'nullable|integer|min:0',
            'total_external_partitions' => 'nullable|integer|min:0',
            'total_working_hours'       => 'nullable|numeric|min:0',
            'notes'                     => 'nullable|string',
            // أضف هنا أي حقول أخرى تحتاج إلى تحديثها
        ]);

        $report = MonthlyGeneralCleaningSummary::findOrFail($id);
        $report->update($validatedData);

        return redirect()->route('monthly-cleaning-report.index')->with('success', 'تم تحديث التقرير بنجاح!');
    }

    /**
     * Remove the specified monthly general cleaning summary from storage.
     * حذف ملخص النظافة العامة الشهري المحدد من قاعدة البيانات.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $report = MonthlyGeneralCleaningSummary::findOrFail($id);
        $report->delete();

        return redirect()->route('monthly-cleaning-report.index')->with('success', 'تم حذف التقرير بنجاح!');
    }

    /**
     * Display the monthly general cleaning report specifically for printing.
     * عرض تقرير النظافة العامة الشهري خصيصًا للطباعة.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function print(Request $request)
    {
        // تهيئة المتغيرات للاستعلام (نفس منطق جلب البيانات في دالة index)
        $query = MonthlyGeneralCleaningSummary::query();

        // جلب معلمات البحث والتصفية من الطلب
        $selectedMonth = $request->input('month', '');
        $selectedLocation = $request->input('location', '');
        $selectedTaskType = $request->input('task_type', '');
        $searchQuery = $request->input('search', ''); // للبحث العام

        // تطبيق فلتر الشهر
        if (!empty($selectedMonth)) {
            $query->where('month', $selectedMonth);
        }

        // تطبيق فلتر الموقع
        if (!empty($selectedLocation)) {
            $query->where('location', $selectedLocation);
        }

        // تطبيق فلتر نوع المهمة
        if (!empty($selectedTaskType)) {
            $query->where('task_type', $selectedTaskType);
        }

        // تطبيق البحث العام على الأعمدة النصية
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('month', 'like', '%' . $searchQuery . '%')
                  ->orWhere('location', 'like', '%' . $searchQuery . '%')
                  ->orWhere('task_type', 'like', '%' . $searchQuery . '%');
            });
        }

        // الترتيب للطباعة (يمكن أن يكون نفس ترتيب العرض أو ترتيب ثابت للطباعة)
        $sortBy = $request->input('sort_by', 'month');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSortColumns = [
            'month', 'location', 'task_type',
            'total_mats', 'total_pillows', 'total_fans',
            'total_windows', 'total_carpets', 'total_blankets',
            'total_beds', 'total_beneficiaries', 'total_trams',
            'total_laid_carpets', 'total_large_containers', 'total_small_containers'
        ];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'month';
        }
        if (!in_array(strtolower($sortOrder), ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);

        // جلب النتائج
        $reports = $query->get();

        // جلب خيارات الفلاتر المتاحة (نفسها المطلوبة في العرض)
        $availableMonths = MonthlyGeneralCleaningSummary::select('month')->distinct()->orderBy('month', 'desc')->pluck('month', 'month')->toArray();
        $availableLocations = MonthlyGeneralCleaningSummary::select('location')->distinct()->pluck('location', 'location')->toArray();
        $availableTaskTypes = [
            'إدامة' => 'إدامة',
            'صيانة' => 'صيانة',
        ];

        // تمرير البيانات إلى واجهة عرض الطباعة (report.blade.php)
        return view('monthly-cleaning-report.report', compact(
            'reports',
            'availableMonths',
            'availableLocations',
            'availableTaskTypes',
            'selectedMonth',
            'selectedLocation',
            'selectedTaskType',
            'searchQuery'
        ));
    }
}
