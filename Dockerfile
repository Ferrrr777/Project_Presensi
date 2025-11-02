# Gunakan image PHP 8.3 agar kompatibel dengan zipstream-php
FROM php:8.3-fpm

# Install dependencies untuk Laravel, GD, dan ZIP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

# Copy semua file project
COPY . /var/www/html
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Jalankan Composer install
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Set permission storage & bootstrap
RUN chmod -R 775 storage bootstrap/cache

# Jalankan Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8000

EXPOSE 8000
