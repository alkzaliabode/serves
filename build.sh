#!/bin/bash

echo "Starting custom build script..."

# تثبيت اعتمادات Composer مع تخطي السكربتات لمنع مشاكل DB
APP_ENV=testing composer install --no-dev --prefer-dist --ignore-platform-reqs --no-scripts

# جعل ملف artisan قابلاً للتنفيذ
chmod +x artisan

# تشغيل package:discover بشكل منفصل بعد تثبيت الاعتمادات
php artisan package:discover --ansi --no-interaction

echo "Custom build script finished."