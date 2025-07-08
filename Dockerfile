# استخدم صورة PHP-FPM أساسية
FROM php:8.2-fpm-alpine

# تثبيت تبعيات النظام المطلوبة لـ Laravel و Composer
RUN apk add --no-cache \
    nginx \
    mysql-client \
    git \
    curl \
    nodejs \
    npm \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxml2-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    # تثبيت امتدادات PHP
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        zip \
        gd \
        dom \
        intl \
        mbstring \
        pdo_pgsql \
        pdo_sqlite \
        bcmath \
        exif \
    # مسح ذاكرة التخزين المؤقت لتقليل حجم الصورة
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تعيين دليل العمل
WORKDIR /app

# نسخ ملفات التطبيق
COPY . /app

# جعل ملف artisan قابلاً للتنفيذ
RUN chmod +x artisan

# تثبيت تبعيات Composer
# تجاهل السكربتات لمنع مشاكل DB أثناء البناء
RUN composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-scripts

# تشغيل package:discover بشكل منفصل
RUN php artisan package:discover --ansi --no-interaction

# تثبيت تبعيات NPM (إذا كان تطبيقك يستخدمها)
# إذا لم يكن تطبيقك يستخدم npm، يمكنك حذف هذا السطر
RUN npm ci

# مسح كاش Laravel وتحسينه
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan route:clear
RUN php artisan php artisan view:clear
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# إعداد Nginx
# تأكد من وجود مجلد ./.docker/nginx/ وملفات nginx.conf و default.conf
COPY ./.docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./.docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# إعداد PHP-FPM
# تأكد من وجود مجلد ./.docker/php-fpm/ وملف www.conf
COPY ./.docker/php-fpm/www.conf /etc/php82/php-fpm.d/www.conf

# كشف المنفذ الذي سيستمع عليه Nginx
EXPOSE 80

# أمر بدء التشغيل
# تشغيل PHP-FPM و Nginx في الخلفية
CMD php-fpm && nginx -g "daemon off;"
