<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageHelper
{
    /**
     * معالجة مسار الصورة وتحويله إلى URL قابل للوصول
     * 
     * @param string|null $path مسار الصورة
     * @param string $defaultImage مسار الصورة الافتراضية
     * @return string عنوان URL للصورة
     */
    public static function getImageUrl(?string $path, string $defaultImage = 'images/no-image.png'): string
    {
        // إذا كان المسار فارغاً، إرجاع الصورة الافتراضية
        if (empty($path)) {
            return asset($defaultImage);
        }

        // إذا كان المسار عبارة عن URL، إرجاعه كما هو
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // تنظيف المسار
        $cleanPath = str_replace(['public/', 'storage/'], '', $path);

        // التحقق من وجود الصورة في مجلد التخزين العام
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        // التحقق من وجود الصورة في المجلد العام
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        // التحقق من وجود الصورة في المجلد العام بعد التنظيف
        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        // إذا تعذر العثور على الصورة، استخدام direct-image.php
        return url('direct-image.php?path=' . urlencode($path));
    }

    /**
     * التحقق من وجود الصورة على الخادم
     * 
     * @param string|null $path مسار الصورة
     * @return bool هل الصورة موجودة
     */
    public static function imageExists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // إذا كان URL خارجي، نفترض أنه موجود (يمكن استخدام curl للتأكد)
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return true;
        }

        // تنظيف المسار
        $cleanPath = str_replace(['public/', 'storage/'], '', $path);

        // التحقق من وجود الصورة في مجلد التخزين العام
        if (Storage::disk('public')->exists($cleanPath)) {
            return true;
        }

        // التحقق من وجود الصورة في المجلد العام
        if (file_exists(public_path($path))) {
            return true;
        }

        // التحقق من وجود الصورة في المجلد العام بعد التنظيف
        if (file_exists(public_path($cleanPath))) {
            return true;
        }

        return false;
    }

    /**
     * تحميل صورة وتخزينها في المسار المحدد
     * 
     * @param \Illuminate\Http\UploadedFile $file ملف الصورة المرفوعة
     * @param string $directory مجلد التخزين
     * @param string|null $oldFile مسار الصورة القديمة (للحذف)
     * @return string مسار الصورة المخزنة
     */
    public static function uploadImage($file, string $directory, ?string $oldFile = null): string
    {
        // حذف الصورة القديمة إذا وجدت
        if ($oldFile) {
            static::deleteImage($oldFile);
        }

        // توليد اسم فريد للملف
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // تخزين الصورة في المجلد المحدد
        $path = $file->storeAs($directory, $filename, 'public');
        
        // تسجيل نجاح تحميل الصورة
        Log::info("تم تحميل صورة جديدة", ['path' => $path]);
        
        return $path;
    }

    /**
     * حذف صورة من التخزين
     * 
     * @param string|null $path مسار الصورة
     * @return bool نجاح العملية
     */
    public static function deleteImage(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // إذا كان URL خارجي، لا يمكن حذفه
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return false;
        }

        // تنظيف المسار
        $cleanPath = str_replace(['public/', 'storage/'], '', $path);

        try {
            // محاولة حذف الصورة من مجلد التخزين العام
            if (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
                Log::info("تم حذف الصورة من التخزين العام", ['path' => $cleanPath]);
                return true;
            }

            // محاولة حذف الصورة من المجلد العام
            if (file_exists(public_path($path))) {
                unlink(public_path($path));
                Log::info("تم حذف الصورة من المجلد العام", ['path' => $path]);
                return true;
            }

            // محاولة حذف الصورة من المجلد العام بعد التنظيف
            if (file_exists(public_path($cleanPath))) {
                unlink(public_path($cleanPath));
                Log::info("تم حذف الصورة من المجلد العام بعد التنظيف", ['path' => $cleanPath]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error("فشل في حذف الصورة", ['path' => $path, 'error' => $e->getMessage()]);
            return false;
        }

        Log::warning("لم يتم العثور على الصورة للحذف", ['path' => $path]);
        return false;
    }
}
