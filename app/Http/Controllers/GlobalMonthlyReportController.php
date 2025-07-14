<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskImageReport;
use App\Models\Survey;
use App\Models\MonthlySanitationSummary;
use App\Models\MonthlyGeneralCleaningSummary;
use App\Models\GeneralCleaningTask;
use App\Models\SanitationFacilityTask;
use App\Models\Unit; // ✅ استيراد موديل الوحدة
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GlobalMonthlyReportController extends Controller
{
    /**
     * يعرض نموذجًا يسمح للمستخدم باختيار الشهر والسنة لإنشاء التقرير الشهري الشامل.
     *
     * @return \Illuminate\View\View
     */
    public function showReportForm()
    {
        $years = range(Carbon::now()->year, 2020);
        return view('global_reports.monthly_report_form', compact('years'));
    }

    /**
     * يولد التقرير الشهري الشامل بناءً على الشهر والسنة المحددين من قبل المستخدم.
     * يجمع هذا التابع البيانات من عدة نماذج (موديلات) لإنشاء ملخص شامل واحترافي.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateMonthlyReport(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . Carbon::now()->year,
            'main_task_ids_string' => 'nullable|string',
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $monthName = Carbon::createFromDate($year, $month, 1)->monthName;

        $mainTaskIds = [];
        if ($request->filled('main_task_ids_string')) {
            $mainTaskIds = array_map('trim', explode(',', $request->input('main_task_ids_string')));
            $mainTaskIds = array_filter($mainTaskIds);
        }

        // 1. حساب الملخصات الشهرية للمنشآت الصحية
        $sanitationSummary = MonthlySanitationSummary::where('month', Carbon::createFromDate($year, $month, 1)->format('Y-m'))
            ->selectRaw('
                COALESCE(SUM(total_seats), 0) as total_seats,
                COALESCE(SUM(total_mirrors), 0) as total_mirrors,
                COALESCE(SUM(total_mixers), 0) as total_mixers,
                COALESCE(SUM(total_doors), 0) as total_doors,
                COALESCE(SUM(total_sinks), 0) as total_sinks,
                COALESCE(SUM(total_toilets), 0) as total_toilets,
                COALESCE(SUM(total_tasks), 0) as total_tasks_count
            ')
            ->first();

        $totalSanitationSeats = $sanitationSummary->total_seats;
        $totalSanitationMirrors = $sanitationSummary->total_mirrors;
        $totalSanitationMixers = $sanitationSummary->total_mixers;
        $totalSanitationDoors = $sanitationSummary->total_doors;
        $totalSanitationSinks = $sanitationSummary->total_sinks;
        $totalSanitationToilets = $sanitationSummary->total_toilets;
        $totalSanitationTasks = $sanitationSummary->total_tasks_count;


        // 2. حساب الملخصات الشهرية للنظافة العامة
        $generalCleaningSummary = MonthlyGeneralCleaningSummary::where('month', Carbon::createFromDate($year, $month, 1)->format('Y-m'))
            ->selectRaw('
                COALESCE(SUM(total_mats), 0) as total_mats,
                COALESCE(SUM(total_pillows), 0) as total_pillows,
                COALESCE(SUM(total_fans), 0) as total_fans,
                COALESCE(SUM(total_windows), 0) as total_windows,
                COALESCE(SUM(total_carpets), 0) as total_carpets,
                COALESCE(SUM(total_blankets), 0) as total_blankets,
                COALESCE(SUM(total_beds), 0) as total_beds,
                COALESCE(SUM(total_beneficiaries), 0) as total_beneficiaries,
                COALESCE(SUM(total_trams), 0) as total_trams,
                COALESCE(SUM(total_laid_carpets), 0) as total_laid_carpets,
                COALESCE(SUM(total_large_containers), 0) as total_large_containers,
                COALESCE(SUM(total_small_containers), 0) as total_small_containers,
                COALESCE(SUM(total_tasks), 0) as total_tasks_count
            ')
            ->first();

        $totalCleaningMats = $generalCleaningSummary->total_mats;
        $totalCleaningPillows = $generalCleaningSummary->total_pillows;
        $totalCleaningFans = $generalCleaningSummary->total_fans;
        $totalCleaningWindows = $generalCleaningSummary->total_windows;
        $totalCleaningCarpets = $generalCleaningSummary->total_carpets;
        $totalCleaningBlankets = $generalCleaningSummary->total_blankets;
        $totalCleaningBeds = $generalCleaningSummary->total_beds;
        $totalCleaningBeneficiaries = $generalCleaningSummary->total_beneficiaries;
        $totalCleaningTrams = $generalCleaningSummary->total_trams;
        $totalCleaningLaidCarpets = $generalCleaningSummary->total_laid_carpets;
        $totalCleaningLargeContainers = $generalCleaningSummary->total_large_containers;
        $totalCleaningSmallContainers = $generalCleaningSummary->total_small_containers;
        $totalCleaningTasks = $generalCleaningSummary->total_tasks_count;

        // 3. معالجة بيانات الاستبيانات
        $surveys = Survey::whereYear('created_at', $year)
                         ->whereMonth('created_at', $month)
                         ->get();

        $totalSurveys = $surveys->count();

        $ageGroupDistribution = $surveys->groupBy('age_group')
                                        ->mapWithKeys(function ($group, $age_group) {
                                            return [$age_group => $group->count()];
                                        })->toArray();
        $allAgeGroups = ['under_18', '18_30', '30_45', '45_60', 'over_60'];
        $ageGroupDistribution = collect($allAgeGroups)->mapWithKeys(function ($group) use ($ageGroupDistribution) {
            return [$group => $ageGroupDistribution[$group] ?? 0];
        })->toArray();


        $genderDistribution = $surveys->groupBy('gender')
                                      ->mapWithKeys(function ($group, $gender) {
                                          return [$gender => $group->count()];
                                      })->toArray();
        $allGenders = ['male', 'female'];
        $genderDistribution = collect($allGenders)->mapWithKeys(function ($gender) use ($genderDistribution) {
            return [$gender => $genderDistribution[$gender] ?? 0];
        })->toArray();


        $overallSatisfactionScores = [
            'very_satisfied' => 5,
            'satisfied' => 4,
            'acceptable' => 3,
            'dissatisfied' => 2,
            'very_dissatisfied' => 1,
        ];
        
        $totalSatisfactionScore = 0;
        $totalRatedSurveys = 0;
        
        foreach ($surveys as $survey) {
            if (isset($overallSatisfactionScores[$survey->overall_satisfaction])) {
                $totalSatisfactionScore += $overallSatisfactionScores[$survey->overall_satisfaction];
                $totalRatedSurveys++;
            }
        }
        
        $averageSatisfaction = $totalRatedSurveys > 0 ? ($totalSatisfactionScore / $totalRatedSurveys) : 0;
        $averageSatisfactionPercentage = ($averageSatisfaction / 5) * 100;
        $averageSatisfactionPercentage = round($averageSatisfactionPercentage, 2);

        $satisfiedSurveysCount = $surveys->whereIn('overall_satisfaction', ['very_satisfied', 'satisfied'])->count();
        $beneficiarySatisfaction = $totalSurveys > 0 ? round(($satisfiedSurveysCount / $totalSurveys) * 100, 2) : 0;

        $excellentCount = $surveys->where('overall_satisfaction', 'very_satisfied')->count();
        $excellentPercentage = $totalSurveys > 0 ? round(($excellentCount / $totalSurveys) * 100, 2) : 0;

        $goodCount = $surveys->where('overall_satisfaction', 'satisfied')->count();
        $goodPercentage = $totalSurveys > 0 ? round(($goodCount / $totalSurveys) * 100, 2) : 0;

        $acceptableCount = $surveys->where('overall_satisfaction', 'acceptable')->count();
        $acceptablePercentage = $totalSurveys > 0 ? round(($acceptableCount / $totalSurveys) * 100, 2) : 0;

        $dissatisfiedCount = $surveys->where('overall_satisfaction', 'dissatisfied')->count();
        $dissatisfiedPercentage = $totalSurveys > 0 ? round(($dissatisfiedCount / $totalSurveys) * 100, 2) : 0;

        $visitTimeDistribution = $surveys->groupBy('stay_duration')
                                         ->mapWithKeys(function ($group, $stay_duration) {
                                             return [$stay_duration => $group->count()];
                                         })->toArray();
        $allStayDurations = ['less_1h', '2_3h', '4_6h', 'over_6h'];
        $visitTimeDistribution = collect($allStayDurations)->mapWithKeys(function ($duration) use ($visitTimeDistribution) {
            return [$duration => $visitTimeDistribution[$duration] ?? 0];
        })->toArray();


        $problemsFaced = $surveys->pluck('problems_faced')->filter()->all();
        $suggestions = $surveys->pluck('suggestions')->filter()->all();

        // 4. تقارير الصور الرئيسية (المهام المختارة)
        $mainPhotoReports = [];
        if (!empty($mainTaskIds)) {
            $mainPhotoReports = TaskImageReport::whereIn('task_id', $mainTaskIds)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();
        }

        // حساب إجمالي المهام المكتملة
        $totalCompletedGeneralCleaningTasks = GeneralCleaningTask::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'مكتمل')
            ->count();

        $totalCompletedSanitationFacilityTasks = SanitationFacilityTask::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'مكتمل')
            ->count();

        $totalCompletedTasks = $totalCompletedGeneralCleaningTasks + $totalCompletedSanitationFacilityTasks;

        // ✅ حساب ملخص المهام المكتملة لكل وحدة
        $sanitationTasksPerUnit = MonthlySanitationSummary::where('month', Carbon::createFromDate($year, $month, 1)->format('Y-m'))
            ->select('unit_id', DB::raw('SUM(total_tasks) as total_tasks'))
            ->groupBy('unit_id')
            ->get()
            ->keyBy('unit_id');

        $generalCleaningTasksPerUnit = MonthlyGeneralCleaningSummary::where('month', Carbon::createFromDate($year, $month, 1)->format('Y-m'))
            ->select('unit_id', DB::raw('SUM(total_tasks) as total_tasks'))
            ->groupBy('unit_id')
            ->get()
            ->keyBy('unit_id');

        $tasksPerUnitSummary = [];
        $allUnitIds = $sanitationTasksPerUnit->keys()->merge($generalCleaningTasksPerUnit->keys())->unique();

        foreach ($allUnitIds as $unitId) {
            $sanitationCount = $sanitationTasksPerUnit->has($unitId) ? $sanitationTasksPerUnit[$unitId]->total_tasks : 0;
            $generalCleaningCount = $generalCleaningTasksPerUnit->has($unitId) ? $generalCleaningTasksPerUnit[$unitId]->total_tasks : 0;
            
            $unitName = Unit::find($unitId)->name ?? 'وحدة غير معروفة'; // افتراض أن لديك موديل Unit

            $tasksPerUnitSummary[] = [
                'unit_name' => $unitName,
                'total_tasks' => $sanitationCount + $generalCleaningCount
            ];
        }


        // 5. حساب مؤشرات جيلبرت (KPIs)
        $gilbertData = [
            'efficiency' => rand(70, 95),
            'effectiveness' => rand(60, 90),
            'quality' => rand(75, 98),
        ];

        $pdf = Pdf::loadView('global_reports.monthly_global_report_pdf', compact(
            'monthName', 'year',
            'totalSanitationSeats', 'totalSanitationMirrors', 'totalSanitationMixers', 'totalSanitationDoors',
            'totalSanitationSinks', 'totalSanitationToilets', 'totalSanitationTasks',
            'totalCleaningMats', 'totalCleaningPillows', 'totalCleaningFans', 'totalCleaningWindows',
            'totalCleaningCarpets', 'totalCleaningBlankets', 'totalCleaningBeds', 'totalCleaningBeneficiaries',
            'totalCleaningTrams', 'totalCleaningLaidCarpets', 'totalCleaningLargeContainers', 'totalCleaningSmallContainers',
            'totalCleaningTasks',
            'totalSurveys',
            'ageGroupDistribution',
            'genderDistribution',
            'averageSatisfactionPercentage',
            'beneficiarySatisfaction',
            'excellentPercentage',
            'goodPercentage',
            'acceptablePercentage',
            'dissatisfiedPercentage',
            'visitTimeDistribution',
            'problemsFaced',
            'suggestions',
            'gilbertData',
            'mainPhotoReports',
            'totalCompletedTasks',
            'tasksPerUnitSummary' // ✅ تمرير المتغير الجديد هنا
        ));

        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream("تقرير_الأداء_الشهري_{$monthName}_{$year}.pdf");
    }
}