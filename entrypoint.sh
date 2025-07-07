#!/bin/bash

echo "Starting entrypoint.sh script..."
echo "Current APP_ENV: $APP_ENV"
echo "Debugging MySQL connection variables:"
echo "MYSQL_HOST: '$MYSQL_HOST'"
echo "MYSQL_PORT: '$MYSQL_PORT'"
echo "MYSQL_DATABASE: '$MYSQL_DATABASE'"
echo "MYSQL_USER: '$MYSQL_USER'"
echo "MYSQL_PASSWORD: '$MYSQL_PASSWORD'" # لا تطبع كلمات المرور الحقيقية في سجلات الإنتاج عادةً

echo "Attempting to connect to MySQL database..."
until php artisan migrate:status > /dev/null 2>&1
do
  echo "Database is not yet ready or connection failed. Retrying in 3 seconds..."
  # يمكنك إلغاء التعليق على السطر التالي لترى سبب فشل migrate:status
  # php artisan migrate:status
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