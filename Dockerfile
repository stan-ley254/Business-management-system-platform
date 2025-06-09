# Use official PHP image with required extensions
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    cron \
    nginx \
    nodejs \
    npm

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Install npm dependencies and build assets
RUN npm install && npm run build

# Copy Nginx configuration
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy SQLite DB if needed
RUN touch /var/www/html/database/database.sqlite

# Add cron job for Laravel scheduler
COPY ./docker/laravel_scheduler /etc/cron.d/laravel_scheduler
RUN chmod 0644 /etc/cron.d/laravel_scheduler
RUN crontab /etc/cron.d/laravel_scheduler

# Expose port 80
EXPOSE 80

# Start cron and php-fpm using supervisord or shell
CMD cron && php artisan migrate:fresh --seed && php artisan config:cache && php artisan route:cache && php artisan view:cache && nginx -g 'daemon off;'