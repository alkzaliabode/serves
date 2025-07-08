#!/bin/bash
set -e

echo "🔧 Starting Laravel application on Railway..."
echo "🌍 Environment: $APP_ENV"

# دالة للتحقق من قاعدة البيانات
check_database() {
    php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD'),
            [
                PDO::ATTR_TIMEOUT => 30,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
            ]
        );
        echo 'Database connection successful';
        exit(0);
    } catch (Exception \$e) {
        echo 'Database connection failed: ' . \$e->getMessage();
        exit(1);
    }
    "
}

# عرض معلومات الاتصال
echo "📡 Database connection details:"
echo "   Host: $DB_HOST"
echo "   Port: $DB_PORT"
echo "   Database: $DB_DATABASE"
echo "   Username: $DB_USERNAME"

# انتظار قاعدة البيانات
echo "⌛ Waiting for database to be ready..."
max_attempts=60
attempt=1

while [ $attempt -le $max_attempts ]; do
    if check_database; then
        echo "✅ Database connection established!"
        break
    else
        echo "❌ Attempt $attempt/$max_attempts failed. Retrying in 5 seconds..."
        sleep 5
        ((attempt++))
    fi
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ Failed to connect to database after $max_attempts attempts"
    exit 1
fi

# تشغيل الترحيلات
echo "🗃️ Running database migrations..."
php artisan migrate --force

# مسح الكاش
echo "🧹 Clearing application cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# تحسين للإنتاج
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🚀 Starting web server..."
exec "$@"