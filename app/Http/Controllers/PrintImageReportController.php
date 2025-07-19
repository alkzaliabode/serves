<?php

namespace App\Http\Controllers;

use App\Models\TaskImageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use Barryvdh\DomPDF\Facade\Pdf; // استيراد واجهة PDF

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

        // معالجة مسارات الصور للحصول على URLs والتحقق من وجودها
        $processedBeforeImages = collect($beforeImages)->map(function ($path) {
            $cleanPath = str_replace('public/', '', $path); // Clean the path
            $exists = Storage::disk('public')->exists($cleanPath);
            return [
                'path' => $cleanPath,
                'url' => $exists ? Storage::url($cleanPath) : asset('images/placeholder-image.png'),
                'exists' => $exists,
                'caption' => '', // Add caption if needed from model
            ];
        })->values()->all();

        $processedAfterImages = collect($afterImages)->map(function ($path) {
            $cleanPath = str_replace('public/', '', $path); // Clean the path
            $exists = Storage::disk('public')->exists($cleanPath);
            return [
                'path' => $cleanPath,
                'url' => $exists ? Storage::url($cleanPath) : asset('images/placeholder-image.png'),
                'exists' => $exists,
                'caption' => '', // Add caption if needed from model
            ];
        })->values()->all();

        // دمج الصور "قبل" و "بعد" في أزواج
        $pairedImages = [];
        $maxImages = max(count($processedBeforeImages), count($processedAfterImages));

        for ($i = 0; $i < $maxImages; $i++) {
            $pairedImages[] = [
                'before' => $processedBeforeImages[$i] ?? ['url' => asset('images/placeholder-image.png'), 'exists' => false, 'caption' => ''],
                'after' => $processedAfterImages[$i] ?? ['url' => asset('images/placeholder-image.png'), 'exists' => false, 'caption' => ''],
            ];
        }

        // اختيار القالب المناسب بناءً على معلمة الطلب
        $template = request()->has('print') ? 'photo_reports.print_only' : 'photo_reports.print';
        
        // إذا كان هناك طلب لإنشاء ملف PDF
        if (request()->has('pdf')) {
            // توليد PDF باستخدام dompdf
            $pdf = Pdf::loadView($template, compact('record', 'unitName', 'pairedImages'));
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);
            $pdf->setPaper('A4', 'portrait');
            
            // تنزيل الملف أو عرضه مباشرة
            return request()->has('download')
                ? $pdf->download('تقرير_صور_' . $record->id . '.pdf')
                : $pdf->stream('تقرير_صور_' . $record->id . '.pdf');
        }
        
        // عرض الصفحة العادية بدون PDF
        return view($template, compact('record', 'unitName', 'pairedImages'));
    }
}