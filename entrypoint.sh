#!/bin/bash

echo "Waiting for database connection using Laravel migration status..."
until php artisan migrate:status > /dev/null 2>&1
do
  echo "Database is not yet ready. Waiting 3 seconds..."
  sleep 3
done
echo "Database is ready! Running migrations and seeding..."

# تشغيل الهجرات وملء البيانات
php artisan migrate --force --database=mysql
php artisan db:seed --force --database=mysql

# تشغيل خادم Laravel
php artisan serve --host 0.0.0.0 --port $PORT