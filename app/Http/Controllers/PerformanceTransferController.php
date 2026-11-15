<?php

namespace App\Http\Controllers;

use App\Support\PerformanceTransfer;
use App\Support\SurveyWorkbook;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PerformanceTransferController extends Controller
{
    public function export(Request $request, PerformanceTransfer $transfer, SurveyWorkbook $workbook, string $kind)
    {
        $request->validate(['format' => 'nullable|in:excel,pdf']);
        $headers = array_values($transfer::columns($kind));
        $rows = $transfer->exportRows($kind, $request);
        $title = $transfer::title($kind);
        if ($request->input('format') === 'pdf') {
            $chart = $kind === 'gilbert' ? app(GilbertTriangleController::class)->getChartData($request)->getData(true) : null;

            return view('transfers.pdf', compact('headers', 'rows', 'title', 'chart'));
        }

        return response()->download($workbook->write([$headers, ...$rows], $title), $kind.'_'.now()->format('Ymd_His').'.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function template(SurveyWorkbook $workbook, string $kind)
    {
        return response()->download($workbook->write([array_values(PerformanceTransfer::columns($kind))], PerformanceTransfer::title($kind)), $kind.'_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function import(Request $request, PerformanceTransfer $transfer, SurveyWorkbook $workbook, string $kind)
    {
        $request->validate(['file' => 'required|file|extensions:xlsx|max:10240', 'update_existing' => 'nullable|boolean']);
        $path = $request->file('file')->storeAs('performance-imports', Str::uuid().'.xlsx', 'local');
        try {
            $rows = $workbook->read(Storage::disk('local')->path($path), count($transfer::columns($kind)));
        } catch (\InvalidArgumentException $exception) {
            throw ValidationException::withMessages(['file' => $exception->getMessage()]);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['file' => 'تعذر قراءة ملف Excel. استخدم نموذج هذه الصفحة وملفاً بصيغة xlsx.']);
        } finally {
            Storage::disk('local')->delete($path);
        }
        $update = $request->boolean('update_existing');
        $records = $transfer->validateRows($kind, $rows, $update);
        try {
            $transfer->persist($kind, $records, $update);
        } catch (QueryException $exception) {
            throw ValidationException::withMessages(['file' => 'تعذر حفظ الملف بسبب تعارض في البيانات. لم يتم حفظ أي صف. راجع السجلات الموجودة والأهداف المرتبطة.']);
        }
        $route = match ($kind) {
            'resources' => 'resource-trackings.index', 'results' => 'actual-results.index', 'gilbert' => 'charts.gilbert-triangle.index',
        };

        return redirect()->route($route)->with('success', 'تم استيراد '.count($records).' سجل بنجاح.');
    }
}
