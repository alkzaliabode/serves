<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralCleaningTaskController;
use App\Http\Controllers\SanitationFacilityTaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DailyStatusController;
use App\Http\Controllers\ResourceReportController;
use App\Http\Controllers\MonthlyCleaningReportController;
use App\Http\Controllers\MonthlySanitationReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ImageReportController;
use App\Http\Controllers\BackgroundSettingController;
use App\Http\Controllers\ServiceTasksBoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActualResultController;
use App\Http\Controllers\ResourceTrackingController;
use App\Http\Controllers\GilbertTriangleController;
use App\Models\UnitGoal; // ✅ تم تصحيح هذا الاستيراد لموديل UnitGoal
use App\Http\Controllers\UnitGoalController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyChartController;
use App\Http\Controllers\UserProfilePhotoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MonthlySummaryController;
use App\Http\Controllers\GlobalMonthlyReportController;
use App\Http\Controllers\PrintImageReportController; // تأكد من استيراد هذا الكونترولر

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| هنا يمكنك تسجيل مسارات الويب لتطبيقك. يتم تحميل هذه المسارات بواسطة
| RouteServiceProvider وسيتم تعيينها جميعًا إلى مجموعة برمجيات "web" الوسيطة.
| اجعلها رائعة!
|
*/

// المسار الرئيسي لتطبيقك
Route::get('/', [HomeController::class, 'index'])->name('home');

// مجموعة المسارات المحمية بالمصادقة
// جميع المسارات داخل هذه المجموعة تتطلب من المستخدم أن يكون مسجلاً للدخول.
Route::middleware(['auth'])->group(function () {
    // مسارات لوحة التحكم
    Route::view('dashboard', 'dashboard')
        ->middleware('verified')
        ->name('dashboard');

    // مسارات الملف الشخصي (Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // مسارات الصورة الشخصية
    Route::put('/user/profile-photo', [UserProfilePhotoController::class, 'update'])->name('user-profile-photo.update');
    Route::delete('/user/profile-photo', [UserProfilePhotoController::class, 'destroy'])->name('user-profile-photo.destroy');

    // مسارات الموقف اليومي
    Route::resource('daily-statuses', DailyStatusController::class);
    Route::get('daily-statuses/{daily_status}/print', [DailyStatusController::class, 'print'])->name('daily-statuses.print');
    Route::get('/daily-statuses/{dailyStatus}/print-standalone', [DailyStatusController::class, 'printStandalone'])->name('daily-statuses.print.standalone');

    // مسارات AJAX لجلب عناصر الإجازات/الغيابات والتاريخ
    Route::get('/daily-statuses/get-employee-leave-item', [DailyStatusController::class, 'getEmployeeLeaveItem'])->name('daily-statuses.get-employee-leave-item');
    Route::get('/daily-statuses/get-eid-leave-item', [DailyStatusController::class, 'getEidLeaveItem'])->name('daily-statuses.get-eid-leave-item');
    Route::get('/daily-statuses/get-temporary-leave-item', [DailyStatusController::class, 'getTemporaryLeaveItem'])->name('daily-statuses.get-temporary-leave-item');
    Route::get('/daily-statuses/get-dated-leave-item', [DailyStatusController::class, 'getDatedLeaveItem'])->name('daily-statuses.get-dated-leave-item');
    Route::get('/daily-statuses/get-custom-usage-item', [DailyStatusController::class, 'getCustomUsageItem'])->name('daily-statuses.get-custom-usage-item');
    Route::get('/daily-statuses/get-hijri-date-and-day', [DailyStatusController::class, 'getHijriDateAndDay'])->name('daily-statuses.get-hijri-date-and-day');

    // مسار الملخص الشهري الجديد
    Route::get('/monthly-summary/{year?}/{month?}', [MonthlySummaryController::class, 'showMonthlySummary'])->name('monthly-summary.show');

    // مسارات تقرير الموارد
    Route::get('/resource-report', [ResourceReportController::class, 'index'])->name('resource-report.index');
    Route::get('/resource-report/print', [ResourceReportController::class, 'print'])->name('resource-report.print');

    // مسارات تقرير النظافة العامة التفصيلي (الآن يتولاه MonthlyCleaningReportController)
    // يتضمن الآن مسارات الإنشاء (create) والتخزين (store) للمهام الفردية
    Route::prefix('monthly-cleaning-report')->name('monthly-cleaning-report.')->group(function () {
        Route::get('/', [MonthlyCleaningReportController::class, 'index'])->name('index');
        Route::get('/create', [MonthlyCleaningReportController::class, 'create'])->name('create');
        Route::post('/', [MonthlyCleaningReportController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MonthlyCleaningReportController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MonthlyCleaningReportController::class, 'update'])->name('update');
        Route::delete('/{id}', [MonthlyCleaningReportController::class, 'destroy'])->name('destroy');
        Route::get('/print', [MonthlyCleaningReportController::class, 'print'])->name('print');
        Route::get('/export', [MonthlyCleaningReportController::class, 'export'])->name('export');
    });

    // مسارات تقرير المنشآت الصحية الشهري (تبقى كما هي)
    Route::get('/monthly-sanitation-report', [MonthlySanitationReportController::class, 'index'])->name('monthly-sanitation-report.index');
    Route::get('/monthly-sanitation-report/export', [MonthlySanitationReportController::class, 'export'])->name('monthly-sanitation-report.export');
    Route::get('/monthly-sanitation-report/print', [MonthlySanitationReportController::class, 'print'])->name('monthly-sanitation-report.print');
    Route::get('/monthly-sanitation-report/{id}/edit', [MonthlySanitationReportController::class, 'edit'])->name('monthly-sanitation-report.edit');
    Route::put('/monthly-sanitation-report/{id}', [MonthlySanitationReportController::class, 'update'])->name('monthly-sanitation-report.update');
    Route::delete('/monthly-sanitation-report/{id}', [MonthlySanitationReportController::class, 'destroy'])->name('monthly-sanitation-report.destroy');

    // مسارات إدارة الموظفين
    Route::middleware(['permission:view users'])->group(function () {
        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('employees/print', [EmployeeController::class, 'print'])->name('employees.print');
        Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    });

    Route::middleware(['permission:create users'])->group(function () {
        Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    });

    Route::middleware(['permission:edit users'])->group(function () {
        Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    });

    Route::middleware(['permission:delete users'])->group(function () {
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // مسارات التقارير المصورة (Image Reports)
    // 💡 ملاحظة: Route::resource ينشئ مسارات index, create, store, show, edit, update, destroy
    // لذلك، لا تكرر تعريفات هذه المسارات يدوياً.
    Route::resource('photo_reports', ImageReportController::class);

    // المسار الخاص بعرض نموذج التقرير الشهري (GET)
    Route::get('/photo_reports/monthly-report', [ImageReportController::class, 'showMonthlyReportForm'])->name('photo_reports.monthly_report_form');

    // المسار الخاص بإنشاء التقرير الشهري (POST)
    Route::post('/photo_reports/generate-monthly-report', [ImageReportController::class, 'generateMonthlyReport'])->name('photo_reports.generate_monthly_report');

    // مسار لصفحة الطباعة المستقلة (print_only.blade.php)
    // نستخدم 'photo_report' كاسم للمتغير في المسار ليتوافق مع ربط النموذج التلقائي (Route Model Binding)
    Route::get('photo_reports/{photo_report}/print-standalone', [ImageReportController::class, 'printSingleReport'])->name('photo_reports.print_standalone');

    // مسار لصفحة الطباعة داخل لوحة التحكم (print.blade.php)
    // يستخدم 'record' كاسم للمتغير، لذا يجب أن يكون PrintImageReportController قادرًا على التعامل معه
    Route::get('photo_reports/{record}/print-internal', [PrintImageReportController::class, 'printSingleReport'])->name('photo_reports.print_internal');

    // ✅ تم إضافة هذا المسار لحل مشكلة 'Route [photo_reports.print] not defined.'
    // تأكد أن PrintImageReportController::printSingleReport يمكنه التعامل مع 'record'
    Route::get('photo_reports/{record}/print', [PrintImageReportController::class, 'printSingleReport'])->name('photo_reports.print');


    // مسارات إدارة إعدادات الخلفية
    Route::get('/admin/background-settings', [BackgroundSettingController::class, 'index'])->name('background-settings.index');
    Route::post('/admin/background-settings', [BackgroundSettingController::class, 'update'])->name('background-settings.update');

    // مسارات لوحة مهام الشُعبة الخدمية (Kanban Board)
    Route::prefix('service-tasks')->name('service-tasks.')->group(function () {
        Route::get('/board', [ServiceTasksBoardController::class, 'index'])->name('board.index');
        Route::post('/', [ServiceTasksBoardController::class, 'store'])->name('store');
        Route::put('/{task}', [ServiceTasksBoardController::class, 'update'])->name('update');
        Route::delete('/{task}', [ServiceTasksBoardController::class, 'destroy'])->name('destroy');
        Route::put('/{task}/update-status-and-order', [ServiceTasksBoardController::class, 'updateStatusAndOrder'])->name('update-status-and-and-order');
    });

    // مسارات مهام النظافة العامة (CRUD للمهام الفردية)
    Route::get('/general-cleaning-tasks', [GeneralCleaningTaskController::class, 'index'])->name('general-cleaning-tasks.index');
    Route::get('/general-cleaning-tasks/create', [GeneralCleaningTaskController::class, 'create'])->name('general-cleaning-tasks.create');
    Route::post('/general-cleaning-tasks', [GeneralCleaningTaskController::class, 'store'])->name('general-cleaning-tasks.store');
    Route::get('/general-cleaning-tasks/{generalCleaningTask}/edit', [GeneralCleaningTaskController::class, 'edit'])->name('general-cleaning-tasks.edit');
    Route::put('/general-cleaning-tasks/{generalCleaningTask}', [GeneralCleaningTaskController::class, 'update'])->name('general-cleaning-tasks.update');
    Route::delete('/general-cleaning-tasks/{generalCleaningTask}', [GeneralCleaningTaskController::class, 'destroy'])->name('general-cleaning-tasks.destroy');

    // مسارات مهام المنشآت الصحية (CRUD للمهام الفردية)
    Route::get('/sanitation-facility-tasks', [SanitationFacilityTaskController::class, 'index'])->name('sanitation-facility-tasks.index');
    Route::get('/sanitation-facility-tasks/create', [SanitationFacilityTaskController::class, 'create'])->name('sanitation-facility-tasks.create');
    Route::post('/sanitation-facility-tasks', [SanitationFacilityTaskController::class, 'store'])->name('sanitation-facility-tasks.store');
    Route::get('/sanitation-facility-tasks/{sanitationFacilityTask}/edit', [SanitationFacilityTaskController::class, 'edit'])->name('sanitation-facility-tasks.edit');
    Route::put('/sanitation-facility-tasks/{sanitationFacilityTask}', [SanitationFacilityTaskController::class, 'update'])->name('sanitation-facility-tasks.update');
    Route::delete('/sanitation-facility-tasks/{sanitationFacilityTask}', [SanitationFacilityTaskController::class, 'destroy'])->name('sanitation-facility-tasks.destroy');

    // مسارات النتائج الفعلية (Actual Results)
    Route::prefix('actual-results')->name('actual-results.')->group(function () {
        Route::get('/', [ActualResultController::class, 'index'])->name('index');
        Route::get('/create', [ActualResultController::class, 'create'])->name('create');
        Route::post('/', [ActualResultController::class, 'store'])->name('store');
        Route::get('/{actualResult}/edit', [ActualResultController::class, 'edit'])->name('edit');
        Route::put('/{actualResult}', [ActualResultController::class, 'update'])->name('update');
        Route::delete('/{actualResult}', [ActualResultController::class, 'destroy'])->name('destroy');
        Route::get('/generate-daily', [ActualResultController::class, 'generateDailyResults'])->name('generate-daily');
        Route::get('/get-form-metrics', [ActualResultController::class, 'getFormMetrics'])->name('get-form-metrics');
    });

    // مسارات تتبع الموارد (Resource Tracking)
    Route::prefix('resource-trackings')->name('resource-trackings.')->group(function () {
        Route::get('/', [ResourceTrackingController::class, 'index'])->name('index');
        Route::get('/create', [ResourceTrackingController::class, 'create'])->name('create');
        Route::post('/', [ResourceTrackingController::class, 'store'])->name('store');
        Route::get('/{resourceTracking}/edit', [ResourceTrackingController::class, 'edit'])->name('edit');
        Route::put('/{resourceTracking}', [ResourceTrackingController::class, 'update'])->name('update');
        Route::delete('/{resourceTracking}', [ResourceTrackingController::class, 'destroy'])->name('destroy');
        Route::get('/generate-daily', [ResourceTrackingController::class, 'generateDailyResourceData'])->name('generate-daily');
    });

    // مسارات أهداف الوحدات (Unit Goals)
    Route::prefix('unit-goals')->name('unit-goals.')->group(function () {
        Route::get('/', [UnitGoalController::class, 'index'])->name('index');
        Route::get('/create', [UnitGoalController::class, 'create'])->name('create');
        Route::post('/', [UnitGoalController::class, 'store'])->name('store');
        Route::get('/{unitGoal}/edit', [UnitGoalController::class, 'edit'])->name('edit');
        Route::put('/{unitGoal}', [UnitGoalController::class, 'update'])->name('update');
        Route::delete('/{unitGoal}', [UnitGoalController::class, 'destroy'])->name('destroy'); // ✅ تم تصحيح استدعاء destroy هنا
    });

    // مسارات استبيانات رضا الزائرين (Surveys)
    Route::prefix('surveys')->name('surveys.')->group(function () {
        Route::get('/', [SurveyController::class, 'index'])->name('index');
        Route::get('/create', [SurveyController::class, 'create'])->name('create');
        Route::post('/', [SurveyController::class, 'store'])->name('store');
        Route::get('/{survey}', [SurveyController::class, 'show'])->name('show');
        Route::get('/{survey}/edit', [SurveyController::class, 'edit'])->name('edit');
        Route::put('/{survey}', [SurveyController::class, 'update'])->name('update');
        Route::delete('/{survey}', [SurveyController::class, 'destroy'])->name('destroy');
        Route::get('/export', [SurveyController::class, 'export'])->name('export');
    });

    // مسارات مخططات الاستبيانات (Survey Charts)
    Route::prefix('charts/surveys')->name('charts.surveys.')->group(function () {
        Route::get('/', [SurveyChartController::class, 'index'])->name('index');
        Route::get('/pie-data', [SurveyChartController::class, 'getSatisfactionPieChartData'])->name('pie-data');
        Route::get('/hall-cleanliness-data', [SurveyChartController::class, 'getHallCleanlinessChartData'])->name('hall-cleanliness-data');
        Route::get('/water-trams-cleanliness-data', [SurveyChartController::class, 'getWaterTramsCleanlinessChartData'])->name('water-trams-cleanliness-data');
        Route::get('/facilities-cleanliness-data', [SurveyChartController::class, 'getFacilitiesCleanlinessChartData'])->name('facilities-cleanliness-data');
        Route::get('/speed-accuracy-data', [SurveyChartController::class, 'getSpeedAccuracyData'])->name('speed-accuracy-data');
    });

    // مسارات مخطط جلبرت (Gilbert Triangle Chart)
    Route::prefix('charts')->name('charts.')->group(function () {
        Route::get('/gilbert-triangle', [GilbertTriangleController::class, 'index'])->name('gilbert-triangle.index');
        Route::get('/gilbert-triangle-data', [GilbertTriangleController::class, 'getChartData'])->name('gilbert-triangle.data');
    });

    // مسارات التقارير الشهرية العالمية
    Route::prefix('global-reports')->name('global_reports.')->group(function () {
        Route::get('/monthly-form', [GlobalMonthlyReportController::class, 'showReportForm'])->name('monthly_form');
        // ✅ تم تصحيح اسم الدالة هنا إلى 'generateMonthlyReport'
        Route::post('/generate', [GlobalMonthlyReportController::class, 'generateMonthlyReport'])->name('generate');
    });

    // مسارات إدارة المستخدمين
    Route::middleware(['permission:manage users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // مسارات إدارة الأدوار
    Route::middleware(['permission:manage roles'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // مسارات الإشعارات
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/count', [NotificationController::class, 'unreadCount'])->name('count');
        Route::post('/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
    });
});

// هذا السطر ضروري جداً لتحميل جميع مسارات المصادقة (login, register, logout, etc.)
require __DIR__.'/auth.php';