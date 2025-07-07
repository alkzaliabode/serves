#!/bin/bash

echo "🔧 Starting entrypoint.sh script..."
echo "🌍 Current APP_ENV: $APP_ENV"

# تحميل المتغيرات من .env
if [ -f .env ]; then
    echo "📦 Loading environment variables from .env"
    export $(cat .env | grep -v '^#' | xargs)
fi

# عرض معلومات الاتصال بقاعدة البيانات
echo "📡 Checking DB connection..."
echo "DB_HOST: '$DB_HOST'"
echo "DB_PORT: '$DB_PORT'"
echo "DB_DATABASE: '$DB_DATABASE'"
echo "DB_USERNAME: '$DB_USERNAME'"
# لا تطبع كلمة المرور

# الانتظار حتى تصبح قاعدة البيانات جاهزة
echo "⌛ Waiting for MySQL to be ready..."
until php artisan migrate:status > /dev/null 2>&1
do
  echo "❌ Database not ready. Retrying in 3 seconds..."
  sleep 3
done

echo "✅ Database is ready! Running migrations and seeders..."

# تجهيز Laravel
php artisan config:clear
php artisan config:cache
php artisan migrate --force
php artisan db:seed --force

# بدء Laravel server
echo "🚀 Starting Laravel server on port ${PORT:-8000}..."
php artisan serve --host 0.0.0.0 --port ${PORT:-8000}
