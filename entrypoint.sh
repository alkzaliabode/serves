#!/bin/bash

echo "Starting entrypoint.sh script..."
echo "Current APP_ENV: $APP_ENV"
echo "Attempting to connect to MySQL at $MYSQL_HOST:$MYSQL_PORT..."

until php artisan migrate:status > /dev/null 2>&1
do
  echo "Database is not yet ready or connection failed. Retrying in 3 seconds..."
  # يمكن إضافة طباعة الخطأ هنا إذا أردت رؤية سبب فشل migrate:status
  # php artisan migrate:status 2>&1 # لإظهار الخطأ
  sleep 3
done
echo "Database is ready! Running migrations and seeding..."

# تشغيل الهجرات وملء البيانات
php artisan config:clear # تأكد من مسح الكاش هنا أيضاً
php artisan migrate --force --database=mysql
php artisan db:seed --force --database=mysql

# تشغيل خادم Laravel
echo "Starting Laravel server..."
php artisan serve --host 0.0.0.0 --port $PORT