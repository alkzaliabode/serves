<?php

namespace App\Http\Controllers;

use App\Models\TaskImageReport;
use Illuminate\Http\Request;

class PrintImageReportController extends Controller
{
    /**
     * طباعة تقرير مصور واحد.
     *
     * @param  \App\Models\TaskImageReport  $record
     * @return \Illuminate\View\View
     */
    public function printSingleReport(TaskImageReport $record)
    {
        // تهيئة نوع الوحدة للعرض
        $unitName = $record->unit_type === 'cleaning' ? 'النظافة العامة' : 'المنشآت الصحية';

        // التأكد من أن before_images و after_images هي مصفوفات
        $beforeImages = is_array($record->before_images) ? $record->before_images : json_decode($record->before_images, true) ?? [];
        $afterImages = is_array($record->after_images) ? $record->after_images : json_decode($record->after_images, true) ?? [];

        // ✅ تم التعديل هنا: استخدام 'photo_reports.print' بدلاً من 'image-reports.print'
        return view('photo_reports.print', compact('record', 'unitName', 'beforeImages', 'afterImages'));
    }
}