<?php

namespace App\Http\Controllers;

use App\Models\Employee; // استيراد نموذج الموظف
use App\Models\Unit;     // استيراد نموذج الوحدة
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;    // لاستخدام تجزئة كلمات المرور
use Illuminate\Support\Facades\Storage; // تم تضمينه للرجوع إليه في المستقبل، ولكنه غير مستخدم حالياً
use Illuminate\Support\Carbon;  // لاستخدام وظائف الوقت والتاريخ

/**
 * فئة EmployeeController
 * تدير عمليات CRUD (الإنشاء، القراءة، التحديث، الحذف) للموظفين،
 * بالإضافة إلى وظائف الطباعة والتصدير.
 */
class EmployeeController extends Controller
{
    /**
     * @var array تعاريف الأدوار المتاحة للموظفين.
     * يمكن استخدامها للتحقق من الصحة ولعرض الخيارات.
     */
    private $availableRoles = ['موظف', 'مشرف', 'مدير'];

    /**
     * يطبق الفلاتر ومعايير الفرز على استعلام الموظفين.
     * هذه الدالة الخاصة تُستخدم لتقليل تكرار الكود في طرق "index" و "print" و "export".
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  استعلام الموظفين.
     * @param  \Illuminate\Http\Request  $request  كائن الطلب الذي يحتوي على الفلاتر.
     * @return \Illuminate\Database\Eloquent\Builder  استعلام الموظفين بعد تطبيق الفلاتر.
     */
    private function applyFilters($query, Request $request): \Illuminate\Database\Eloquent\Builder
    {
        // فلتر البحث العام: يبحث في الاسم، البريد الإلكتروني، المسمى الوظيفي، ورقم الموظف.
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('job_title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('employee_number', 'like', '%' . $searchTerm . '%');
            });
        }

        // فلتر حسب الوحدة: يصفّي الموظفين بناءً على unit_id.
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // فلتر حسب الدور: يصفّي الموظفين بناءً على الدور (موظف، مشرف، مدير).
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // فلتر حسب حالة النشاط: يصفّي الموظفين النشطين أو غير النشطين.
        if ($request->filled('is_active')) {
            // التحقق من القيمة المدخلة وتحويلها إلى boolean
            $query->where('is_active', $request->is_active === '1');
        }

        return $query;
    }

    /**
     * عرض قائمة بجميع الموظفين.
     * تدعم هذه الدالة الفرز، البحث، والفلترة بناءً على الوحدة، الدور، وحالة النشاط.
     * يتم تحميل علاقة 'unit' مسبقاً لتجنب مشكلة N+1.
     *
     * @param  \Illuminate\Http\Request  $request كائن الطلب الذي يحتوي على معايير الفرز والفلترة.
     * @return \Illuminate\View\View عرض صفحة قائمة الموظفين مع البيانات المصفاة والمفرزة.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        // بدء الاستعلام مع التحميل المسبق لعلاقة 'unit'
        $query = Employee::with('unit');

        // تطبيق الفلاتر على الاستعلام
        $query = $this->applyFilters($query, $request);

        // تحديد عمود وطريقة الفرز الافتراضية
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        // التحقق من أن عمود الفرز المدخل صالح لتجنب الأخطاء الأمنية أو الأخطاء في قاعدة البيانات.
        $validSortColumns = ['name', 'email', 'job_title', 'employee_number', 'role', 'is_active', 'average_rating', 'created_at'];
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'name'; // العودة إلى الفرز الافتراضي إذا كان العمود غير صالح
        }

        // جلب الموظفين المصفاة والمفرزة مع تقسيم الصفحات (10 موظفين لكل صفحة)
        $employees = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        // جلب جميع الوحدات والأدوار لعرضها في فلاتر واجهة المستخدم
        $units = Unit::all();
        $roles = $this->availableRoles;

        // إرجاع العرض مع البيانات
        return view('employees.index', compact('employees', 'units', 'roles'));
    }

    /**
     * عرض نموذج إنشاء موظف جديد.
     * تجلب الوحدات والأدوار المتاحة لملء حقول الاختيار في النموذج.
     *
     * @return \Illuminate\View\View عرض نموذج إنشاء الموظف.
     */
    public function create(): \Illuminate\View\View
    {
        $units = Unit::all(); // جلب جميع الوحدات
        $roles = $this->availableRoles; // جلب الأدوار المتاحة
        return view('employees.create', compact('units', 'roles'));
    }

    /**
     * تخزين موظف جديد في قاعدة البيانات.
     * تقوم بالتحقق من صحة البيانات المدخلة وإنشاء سجل موظف جديد.
     *
     * @param  \Illuminate\Http\Request  $request بيانات الطلب التي تحتوي على معلومات الموظف.
     * @return \Illuminate\Http\RedirectResponse إعادة توجيه إلى قائمة الموظفين مع رسالة نجاح/خطأ.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // التحقق من صحة البيانات المدخلة مع رسائل خطأ مخصصة
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|max:255',
            'password' => 'required|string|min:8|max:255',
            'job_title' => 'nullable|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'employee_number' => 'required|string|unique:employees,employee_number|max:20',
            'role' => 'required|string|in:' . implode(',', $this->availableRoles), // التحقق من أن الدور ضمن الأدوار المحددة
            'is_active' => 'boolean', // يجب أن تكون القيمة boolean (0 أو 1)
        ], [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نصًا.',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرفًا.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون بتنسيق صالح.',
            'email.unique' => 'البريد الإلكتروني موجود بالفعل لموظف آخر.',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.string' => 'كلمة المرور يجب أن تكون نصًا.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 255 حرفًا.',
            'job_title.string' => 'المسمى الوظيفي يجب أن يكون نصًا.',
            'job_title.max' => 'المسمى الوظيفي يجب ألا يتجاوز 255 حرفًا.',
            'unit_id.required' => 'حقل الوحدة مطلوب.',
            'unit_id.exists' => 'الوحدة المحددة غير صالحة.',
            'employee_number.required' => 'حقل الرقم الوظيفي مطلوب.',
            'employee_number.string' => 'الرقم الوظيفي يجب أن يكون نصًا.',
            'employee_number.unique' => 'الرقم الوظيفي موجود بالفعل لموظف آخر.',
            'employee_number.max' => 'الرقم الوظيفي يجب ألا يتجاوز 20 حرفًا.',
            'role.required' => 'حقل الدور مطلوب.',
            'role.string' => 'الدور يجب أن يكون نصًا.',
            'role.in' => 'الدور المحدد غير صالح.',
            'is_active.boolean' => 'حالة النشاط يجب أن تكون صحيحة أو خاطئة.',
        ]);

        try {
            // إنشاء موظف جديد في قاعدة البيانات
            Employee::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // تجزئة كلمة المرور قبل التخزين
                'job_title' => $validatedData['job_title'],
                'unit_id' => $validatedData['unit_id'],
                'employee_number' => $validatedData['employee_number'],
                'role' => $validatedData['role'],
                'is_active' => $validatedData['is_active'] ?? false, // تعيين القيمة الافتراضية إذا لم يتم تحديدها
            ]);

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('employees.index')->with('success', 'تم إنشاء الموظف بنجاح.');
        } catch (\Exception $e) {
            // تسجيل الخطأ للمراجعة (مثلاً في storage/logs/laravel.log)
            \Log::error('خطأ في إنشاء الموظف: ' . $e->getMessage(), ['exception' => $e]);
            // إعادة التوجيه إلى الصفحة السابقة مع إبقاء المدخلات ورسالة خطأ
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء الموظف. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * عرض نموذج تعديل موظف موجود.
     * تجلب بيانات الموظف المحدد والوحدات والأدوار المتاحة لملء النموذج.
     *
     * @param  \App\Models\Employee  $employee نموذج الموظف المراد تعديله (يتم حقنه تلقائيًا بواسطة Laravel).
     * @return \Illuminate\View\View عرض نموذج تعديل الموظف.
     */
    public function edit(Employee $employee): \Illuminate\View\View
    {
        $units = Unit::all(); // جلب جميع الوحدات
        $roles = $this->availableRoles; // جلب الأدوار المتاحة
        return view('employees.edit', compact('employee', 'units', 'roles'));
    }

    /**
     * تحديث موظف موجود في قاعدة البيانات.
     * تقوم بالتحقق من صحة البيانات المدخلة وتحديث سجل الموظف.
     *
     * @param  \Illuminate\Http\Request  $request بيانات الطلب التي تحتوي على المعلومات المحدثة للموظف.
     * @param  \App\Models\Employee  $employee نموذج الموظف المراد تحديثه.
     * @return \Illuminate\Http\RedirectResponse إعادة توجيه إلى قائمة الموظفين مع رسالة نجاح/خطأ.
     */
    public function update(Request $request, Employee $employee): \Illuminate\Http\RedirectResponse
    {
        // التحقق من صحة البيانات المدخلة مع رسائل خطأ مخصصة.
        // قاعدة unique للبريد الإلكتروني ورقم الموظف تستثني الموظف الحالي.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id . '|max:255',
            'password' => 'nullable|string|min:8|max:255', // يمكن أن تكون كلمة المرور فارغة عند التعديل
            'job_title' => 'nullable|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'employee_number' => 'required|string|unique:employees,employee_number,' . $employee->id . '|max:20',
            'role' => 'required|string|in:' . implode(',', $this->availableRoles),
            'is_active' => 'boolean',
        ], [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نصًا.',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرفًا.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني يجب أن يكون بتنسيق صالح.',
            'email.unique' => 'البريد الإلكتروني موجود بالفعل لموظف آخر.',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
            'password.string' => 'كلمة المرور يجب أن تكون نصًا.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 255 حرفًا.',
            'job_title.string' => 'المسمى الوظيفي يجب أن يكون نصًا.',
            'job_title.max' => 'المسمى الوظيفي يجب ألا يتجاوز 255 حرفًا.',
            'unit_id.required' => 'حقل الوحدة مطلوب.',
            'unit_id.exists' => 'الوحدة المحددة غير صالحة.',
            'employee_number.required' => 'حقل الرقم الوظيفي مطلوب.',
            'employee_number.string' => 'الرقم الوظيفي يجب أن يكون نصًا.',
            'employee_number.unique' => 'الرقم الوظيفي موجود بالفعل لموظف آخر.',
            'employee_number.max' => 'الرقم الوظيفي يجب ألا يتجاوز 20 حرفًا.',
            'role.required' => 'حقل الدور مطلوب.',
            'role.string' => 'الدور يجب أن يكون نصًا.',
            'role.in' => 'الدور المحدد غير صالح.',
            'is_active.boolean' => 'حالة النشاط يجب أن تكون صحيحة أو خاطئة.',
        ]);

        try {
            // تحديث بيانات الموظف باستخدام طريقة fill
            $employee->fill($validatedData);

            // تحديث كلمة المرور فقط إذا تم إدخال قيمة جديدة
            if ($request->filled('password')) {
                $employee->password = Hash::make($validatedData['password']);
            }

            // التعامل مع حقل 'is_active' (checkbox)
            // إذا كان checkbox غير محدد، فلن يكون موجوداً في الطلب، لذا نستخدم has() لتحديد حالته.
            $employee->is_active = $request->has('is_active');

            $employee->save(); // حفظ التغييرات في قاعدة البيانات

            return redirect()->route('employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح.');
        } catch (\Exception $e) {
            \Log::error('خطأ في تحديث الموظف: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث بيانات الموظف. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * حذف موظف من قاعدة البيانات.
     *
     * @param  \App\Models\Employee  $employee نموذج الموظف المراد حذفه.
     * @return \Illuminate\Http\RedirectResponse إعادة توجيه إلى قائمة الموظفين مع رسالة نجاح/خطأ.
     */
    public function destroy(Employee $employee): \Illuminate\Http\RedirectResponse
    {
        try {
            $employee->delete(); // حذف سجل الموظف
            return redirect()->route('employees.index')->with('success', 'تم حذف الموظف بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            // معالجة الأخطاء المتعلقة بقاعدة البيانات، مثل قيود المفتاح الخارجي
            if ($e->getCode() == 23000) { // رمز خطأ SQL لحالة انتهاك قيود التكامل (مثل قيود المفتاح الخارجي)
                \Log::warning('محاولة حذف موظف مرتبط بسجلات أخرى: ' . $employee->id, ['exception' => $e]);
                return redirect()->route('employees.index')->with('error', 'لا يمكن حذف الموظف لوجود سجلات مرتبطة به (مثل تقييمات، مهام، أو تسجيلات دخول). الرجاء حذف السجلات المرتبطة أولاً.');
            }
            // لأي أخطاء أخرى غير معروفة في قاعدة البيانات
            \Log::error('خطأ في حذف الموظف: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('employees.index')->with('error', 'حدث خطأ غير متوقع أثناء حذف الموظف: ' . $e->getMessage());
        } catch (\Exception $e) {
            // معالجة أي أخطاء عامة أخرى
            \Log::error('خطأ عام في حذف الموظف: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('employees.index')->with('error', 'حدث خطأ أثناء حذف الموظف: ' . $e->getMessage());
        }
    }

    /**
     * عرض قائمة الموظفين بتنسيق مناسب للطباعة.
     * تطبق نفس الفلاتر المستخدمة في الدالة 'index' ولكنها تجلب جميع السجلات (بدون تقسيم صفحات).
     *
     * @param  \Illuminate\Http\Request  $request كائن الطلب الذي يحتوي على معايير الفلترة.
     * @return \Illuminate\View\View عرض صفحة الطباعة لتقرير الموظفين.
     */
    public function print(Request $request): \Illuminate\View\View
    {
        // بدء الاستعلام مع التحميل المسبق لعلاقة 'unit'
        $query = Employee::with('unit');

        // تطبيق الفلاتر على الاستعلام
        $query = $this->applyFilters($query, $request);

        // جلب جميع الموظفين (بدون تقسيم صفحات) للطباعة، مرتبة حسب الاسم تصاعدياً
        $employees = $query->orderBy('name', 'asc')->get();

        // جلب أسماء الوحدات لعرضها في قسم الفلاتر بالتقرير المطبوع
        $units = Unit::all()->pluck('name', 'id')->toArray();

        // تحضير الفلاتر التي سيتم عرضها في رأس التقرير المطبوع
        $filters = [
            'search' => $request->search,
            'unit_name' => $request->filled('unit_id') ? ($units[$request->unit_id] ?? 'غير معروف') : null,
            'role' => $request->role,
            'is_active_display' => $request->filled('is_active') ? ($request->is_active === '1' ? 'نشط' : 'غير نشط') : null,
        ];

        return view('employees.print', compact('employees', 'filters'));
    }

    /**
     * تصدير قائمة الموظفين إلى ملف CSV.
     * تطبق نفس الفلاتر المستخدمة في الدالة 'index' وتصدر جميع السجلات المطابقة إلى ملف CSV.
     * يتم إضافة BOM لضمان التوافق مع برامج مثل Microsoft Excel عند فتح الملف.
     *
     * @param  \Illuminate\Http\Request  $request كائن الطلب الذي يحتوي على معايير الفلترة.
     * @return \Symfony\Component\HttpFoundation\StreamedResponse استجابة دفق تحتوي على ملف CSV.
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // بدء الاستعلام مع التحميل المسبق لعلاقة 'unit'
        $query = Employee::with('unit');

        // تطبيق الفلاتر على الاستعلام
        $query = $this->applyFilters($query, $request);

        // جلب جميع الموظفين المطابقين للفلاتر
        $employees = $query->orderBy('name', 'asc')->get();

        // إنشاء اسم ملف CSV ديناميكي بتاريخ ووقت التصدير
        $fileName = 'employees_report_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // تعريف رؤوس الاستجابة لملف CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Encoding' => 'UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // دالة رد الاتصال التي ستولد محتوى ملف CSV
        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            // إضافة BOM (Byte Order Mark) لتوافق UTF-8 مع برامج مثل Microsoft Excel
            fwrite($file, "\xEF\xBB\xBF");

            // رؤوس الأعمدة في ملف CSV باللغة العربية
            fputcsv($file, [
                'الاسم',
                'الرقم الوظيفي',
                'البريد الإلكتروني',
                'المسمى الوظيفي',
                'الوحدة',
                'الدور',
                'نشط',
                'متوسط التقييم'
            ]);

            // إضافة بيانات كل موظف إلى ملف CSV
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->name,
                    $employee->employee_number,
                    $employee->email,
                    $employee->job_title ?? '', // قيمة فارغة إذا لم يكن هناك مسمى وظيفي
                    $employee->unit->name ?? 'N/A', // اسم الوحدة أو 'N/A' إذا لم تكن موجودة
                    $employee->role,
                    $employee->is_active ? 'نعم' : 'لا',
                    number_format($employee->average_rating ?? 0, 2), // تنسيق متوسط التقييم إلى كسرين عشريين
                ]);
            }
            fclose($file); // إغلاق الملف
        };

        // إرجاع استجابة الدفق لملف CSV
        return response()->stream($callback, 200, $headers);
    }
}
