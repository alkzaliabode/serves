<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // تأكد من استيراد Log

class TaskImageReport extends Model
{
    // الأعمدة القابلة للتعبئة الجماعية (Mass Assignment)
    protected $fillable = [
        'report_title',
        'date',
        'unit_type',
        'location',
        'task_type',
        'task_id',
        'before_images',
        'after_images',
        'status',
        'notes',
        'before_images_count',
        'after_images_count',
    ];

    // تحويل أنواع البيانات
    protected $casts = [
        'date' => 'date',
        'before_images' => 'array',
        'after_images' => 'array',
    ];

    // تحديد الـ Accessors التي ستُضاف تلقائياً إلى مصفوفة الـ JSON
    protected $appends = [
        'before_images_urls',
        'after_images_urls',
        // يمكنك إضافة 'before_images_for_table', 'after_images_for_table' إذا كنت بحاجة إليها في الـ JSON
    ];

    /**
     * Accessor للحصول على عناوين URL لصور "قبل" التنفيذ مع تفاصيل إضافية.
     *
     * @return array
     */
    public function getBeforeImagesUrlsAttribute(): array
    {
        return $this->processImages($this->before_images);
    }

    /**
     * Accessor للحصول على عناوين URL لصور "بعد" التنفيذ مع تفاصيل إضافية.
     *
     * @return array
     */
    public function getAfterImagesUrlsAttribute(): array
    {
        return $this->processImages($this->after_images);
    }

    /**
     * دالة مساعدة لمعالجة مسارات الصور للحصول على URLs والمسارات المطلقة للـ PDF.
     *
     * @param array|null $images
     * @return array
     */
    private function processImages(?array $images): array
    {
        if (empty($images)) {
            return [];
        }

        return collect($images)->filter()->map(function ($imagePath) {
            // قم بتنظيف المسار بحيث يبدأ مباشرة من مجلد القرص 'public'
            // هذا سيتعامل مع المسارات التي قد تبدأ بـ 'public/' أو 'storage/'
            $cleanPath = str_replace('public/', '', $imagePath);
            $cleanPath = str_replace('storage/', '', $cleanPath);

            // سجل المسار النظيف للتحقق منه
            Log::debug("Processing image path: Original='{$imagePath}', Cleaned='{$cleanPath}'");
            
            // التحقق من وجود الصورة في نظام الملفات
            $exists = Storage::disk('public')->exists($cleanPath);
            
            return [
                'url' => $exists ? Storage::disk('public')->url($cleanPath) : null,
                'path' => $cleanPath, // المسار النسبي داخل storage/app/public
                'exists' => $exists,
                // المسار المطلق اللازم لـ Dompdf أو أي معالج PDF آخر
                'absolute_path_for_pdf' => $exists ? public_path('storage/' . $cleanPath) : null
            ];
        })->filter(fn($item) => $item['exists'] ?? false)->toArray(); // فلترة أي عناصر غير موجودة أو غير صالحة
    }

    /**
     * Accessor للحصول على عناوين URL لعدد محدود من صور "قبل" التنفيذ للعرض في الجداول.
     *
     * @return array
     */
    public function getBeforeImagesForTableAttribute(): array
    {
        return collect($this->getBeforeImagesUrlsAttribute())
            ->take(3) // جلب أول 3 صور فقط
            ->pluck('url') // استخراج الـ URL فقط
            ->filter() // إزالة أي قيم null إذا كانت الصورة غير موجودة
            ->toArray();
    }

    /**
     * Accessor للحصول على عناوين URL لعدد محدود من صور "بعد" التنفيذ للعرض في الجداول.
     *
     * @return array
     */
    public function getAfterImagesForTableAttribute(): array
    {
        return collect($this->getAfterImagesUrlsAttribute())
            ->take(3)
            ->pluck('url')
            ->filter()
            ->toArray();
    }

    /**
     * دالة لحذف الصور الفعلية المرتبطة بهذا التقرير من نظام الملفات.
     */
    public function deleteRelatedImages(): void
    {
        // حذف صور "قبل" التنفيذ
        foreach ($this->before_images ?? [] as $path) {
            $cleanPath = str_replace('public/', '', $path);
            $cleanPath = str_replace('storage/', '', $cleanPath);

            if (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
                Log::info("Deleted before image: {$cleanPath} for TaskImageReport ID: {$this->id}");
            } else {
                Log::warning("Before image not found for deletion: {$cleanPath} for TaskImageReport ID: {$this->id}");
            }
        }

        // حذف صور "بعد" التنفيذ
        foreach ($this->after_images ?? [] as $path) {
            $cleanPath = str_replace('public/', '', $path);
            $cleanPath = str_replace('storage/', '', $cleanPath);

            if (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
                Log::info("Deleted after image: {$cleanPath} for TaskImageReport ID: {$this->id}");
            } else {
                Log::warning("After image not found for deletion: {$cleanPath} for TaskImageReport ID: {$this->id}");
            }
        }
    }

    /**
     * الأحداث التي يتم تشغيلها عند بدء تشغيل الموديل.
     */
    protected static function booted()
    {
        static::saving(function ($report) {
            // تحديث count_images بناءً على عدد الصور الفعلية المخزنة
            $report->before_images_count = count($report->before_images ?? []);
            $report->after_images_count = count($report->after_images ?? []);
        });

        static::deleted(function ($report) {
            $report->deleteRelatedImages(); // ضمان حذف الصور عند حذف التقرير
        });
    }
}