<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TaskImageReport extends Model
{
    /**
     * Mass assignable attributes.
     */
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

    /**
     * Attribute casting.
     */
    protected $casts = [
        'date' => 'date',
        'before_images' => 'array',
        'after_images' => 'array',
    ];

    /**
     * Accessors to append to JSON output.
     */
    protected $appends = [
        'before_images_urls',
        'after_images_urls',
        'before_images_for_table',
        'after_images_for_table',
    ];

    /**
     * Accessor: Full URLs for before images.
     */
    public function getBeforeImagesUrlsAttribute(): array
    {
        return $this->processImages($this->before_images);
    }

    /**
     * Accessor: Full URLs for after images.
     */
    public function getAfterImagesUrlsAttribute(): array
    {
        return $this->processImages($this->after_images);
    }

    /**
     * Get only first 3 before image URLs for use in tables or previews.
     */
    public function getBeforeImagesForTableAttribute(): array
    {
        return collect($this->before_images_urls)
            ->take(3)
            ->pluck('url')
            ->filter()
            ->toArray();
    }

    /**
     * Get only first 3 after image URLs for use in tables or previews.
     */
    public function getAfterImagesForTableAttribute(): array
    {
        return collect($this->after_images_urls)
            ->take(3)
            ->pluck('url')
            ->filter()
            ->toArray();
    }

    /**
     * Processes image paths to return URLs, paths, and existence status.
     */
    private function processImages(?array $images): array
    {
        if (empty($images)) {
            return [];
        }

        return collect($images)->filter()->map(function ($path) {
            // تحقق إذا كان المسار بدأ بـ http/https (يشير إلى URL خارجي)
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return [
                    'url' => $path,
                    'path' => $path,
                    'exists' => true,
                    'absolute_path_for_pdf' => $path,
                    'is_external' => true
                ];
            }

            // تنظيف المسار وإزالة 'public/' و 'storage/' إذا كانت موجودة
            $cleanPath = str_replace(['public/', 'storage/'], '', $path);
            
            // التحقق من وجود الصورة في التخزين العام
            $exists = Storage::disk('public')->exists($cleanPath);

            // إذا لم تكن الصورة موجودة، تحقق من وجودها في المجلدات العامة
            if (!$exists && file_exists(public_path($path))) {
                $exists = true;
                $cleanPath = $path;
                
                Log::debug("Image found in public directory", [
                    'original' => $path,
                    'cleaned' => $cleanPath,
                    'exists' => $exists
                ]);
                
                return [
                    'url' => asset($cleanPath),
                    'path' => $cleanPath,
                    'exists' => $exists,
                    'absolute_path_for_pdf' => public_path($cleanPath),
                    'is_public' => true
                ];
            }

            // محاولة ثانية: البحث في المجلد العام بدون تنظيف
            if (!$exists && file_exists(public_path($cleanPath))) {
                $exists = true;
                
                Log::debug("Image found in public directory with clean path", [
                    'original' => $path,
                    'cleaned' => $cleanPath,
                    'exists' => $exists
                ]);
                
                return [
                    'url' => asset($cleanPath),
                    'path' => $cleanPath,
                    'exists' => $exists,
                    'absolute_path_for_pdf' => public_path($cleanPath),
                    'is_public' => true
                ];
            }
            
            // أخيراً، إذا كنا غير قادرين على تحديد موقع الصورة، حاول إنشاء URL لها على أي حال
            $url = $exists ? asset('storage/' . $cleanPath) : asset('storage/' . $path);
            
            Log::debug("Processing image", [
                'original' => $path,
                'cleaned' => $cleanPath,
                'exists' => $exists,
                'url' => $url
            ]);

            return [
                'url' => $url,
                'path' => $cleanPath,
                'exists' => $exists,
                'absolute_path_for_pdf' => $exists ? public_path('storage/' . $cleanPath) : null,
                'is_storage' => true
            ];
        })->values()->toArray(); // إزالة الفلتر الذي يزيل الصور غير الموجودة
    }

    /**
     * Delete stored before/after images from disk.
     */
    public function deleteRelatedImages(): void
    {
        foreach (['before_images', 'after_images'] as $type) {
            foreach ($this->$type ?? [] as $path) {
                // تخطي URLs الخارجية
                if (filter_var($path, FILTER_VALIDATE_URL)) {
                    Log::info("Skipping deletion of external {$type} image", ['url' => $path, 'report_id' => $this->id]);
                    continue;
                }
                
                // تنظيف المسار وإزالة 'public/' و 'storage/' إذا كانت موجودة
                $cleanPath = str_replace(['public/', 'storage/'], '', $path);
                
                try {
                    if (Storage::disk('public')->exists($cleanPath)) {
                        Storage::disk('public')->delete($cleanPath);
                        Log::info("Deleted {$type} image from storage", ['path' => $cleanPath, 'report_id' => $this->id]);
                    } else if (file_exists(public_path($path))) {
                        // التحقق من وجود الملف في المجلد العام
                        unlink(public_path($path));
                        Log::info("Deleted {$type} image from public directory", ['path' => $path, 'report_id' => $this->id]);
                    } else {
                        Log::warning("{$type} image not found for deletion", ['path' => $path, 'report_id' => $this->id]);
                    }
                } catch (\Exception $e) {
                    Log::error("Error deleting {$type} image", [
                        'path' => $path, 
                        'report_id' => $this->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Model boot method to hook into lifecycle events.
     */
    protected static function booted(): void
    {
        static::saving(function (self $report) {
            $report->before_images_count = count($report->before_images ?? []);
            $report->after_images_count = count($report->after_images ?? []);
        });

        static::deleted(function (self $report) {
            $report->deleteRelatedImages();
        });
    }
}
