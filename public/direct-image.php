<?php
// يتم تنفيذ هذا الملف عند الوصول إلى صورة من خلال /direct-image.php?path=...
$imagePath = $_GET['path'] ?? null;

if (!$imagePath) {
    header('HTTP/1.1 404 Not Found');
    echo 'الملف غير موجود';
    exit;
}

// تنظيف المسار من أي محاولات للوصول غير المصرح به
$imagePath = str_replace(['../', '..\\'], '', $imagePath);

// مسارات البحث المحتملة
$possiblePaths = [
    __DIR__ . '/storage/' . $imagePath,
    __DIR__ . '/' . $imagePath,
    __DIR__ . '/storage/' . str_replace(['public/', 'storage/'], '', $imagePath),
];

$foundImage = false;
foreach ($possiblePaths as $path) {
    if (file_exists($path) && is_file($path)) {
        $foundImage = true;
        
        // تحديد نوع الصورة
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $contentType = 'image/jpeg'; // افتراضي
        
        switch (strtolower($extension)) {
            case 'png':
                $contentType = 'image/png';
                break;
            case 'gif':
                $contentType = 'image/gif';
                break;
            case 'jpg':
            case 'jpeg':
                $contentType = 'image/jpeg';
                break;
        }
        
        // تعيين نوع المحتوى وإرسال الصورة
        header('Content-Type: ' . $contentType);
        readfile($path);
        exit;
    }
}

if (!$foundImage) {
    header('HTTP/1.1 404 Not Found');
    echo 'الصورة غير موجودة';
    exit;
}
