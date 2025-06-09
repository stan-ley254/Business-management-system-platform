# --- Base PHP image with system tools ---
FROM php:8.2-fpm-bullseye AS base

WORKDIR /var/www/html

# Install system packages
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libwebp-dev libxpm-dev libonig-dev libxml2-dev sqlite3 \
    libsqlite3-dev nodejs npm cron nginx gnupg2

# GD extension and others
RUN docker-php-ext-configure gd \
    --with-freetype --with-jpeg --with-webp --with-xpm \
 && docker-php-ext-install gd pdo pdo_sqlite mbstring bcmath exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel project
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 storage bootstrap/cache

# Validate GD is loaded (debug)
RUN php -m | grep -i gd

# Composer install AFTER GD is loaded
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Build front-end assets
RUN npm install && npm run build

# Laravel scheduler setup
COPY ./docker/laravel_scheduler /etc/cron.d/laravel_scheduler
RUN chmod 0644 /etc/cron.d/laravel_scheduler && crontab /etc/cron.d/laravel_scheduler

# Nginx config
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Create SQLite file
RUN touch database/database.sqlite

EXPOSE 80

CMD cron && php artisan migrate:fresh --seed && php artisan config:cache && php artisan route:cache && php artisan view:cache && nginx -g 'daemon off;'
