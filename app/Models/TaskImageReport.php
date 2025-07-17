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
            $cleanPath = str_replace(['public/', 'storage/'], '', $path);
            $exists = Storage::disk('public')->exists($cleanPath);

            Log::debug("Processing image", [
                'original' => $path,
                'cleaned' => $cleanPath,
                'exists' => $exists
            ]);

            return [
                'url' => $exists ? Storage::disk('public')->url($cleanPath) : null,
                'path' => $cleanPath,
                'exists' => $exists,
                'absolute_path_for_pdf' => $exists ? public_path('storage/' . $cleanPath) : null,
            ];
        })->filter(fn($item) => $item['exists'])->values()->toArray();
    }

    /**
     * Delete stored before/after images from disk.
     */
    public function deleteRelatedImages(): void
    {
        foreach (['before_images', 'after_images'] as $type) {
            foreach ($this->$type ?? [] as $path) {
                $cleanPath = str_replace(['public/', 'storage/'], '', $path);
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                    Log::info("Deleted {$type} image", ['path' => $cleanPath, 'report_id' => $this->id]);
                } else {
                    Log::warning("{$type} image not found for deletion", ['path' => $cleanPath, 'report_id' => $this->id]);
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