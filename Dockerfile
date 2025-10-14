# Gunakan image PHP resmi dengan GD extension
FROM php:8.2-fpm

# Install dependencies untuk Laravel & GD
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

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
