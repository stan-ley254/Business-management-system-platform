FROM php:8.2-fpm-bullseye

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev libxpm-dev \
    libonig-dev libxml2-dev sqlite3 libsqlite3-dev \
    cron nginx nodejs npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install gd pdo pdo_sqlite mbstring bcmath exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

# Nginx config
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# SQLite
RUN touch /var/www/html/database/database.sqlite

# Scheduler setup
COPY ./docker/laravel_scheduler /etc/cron.d/laravel_scheduler
RUN chmod 0644 /etc/cron.d/laravel_scheduler && crontab /etc/cron.d/laravel_scheduler

EXPOSE 80

CMD cron && php artisan migrate:fresh --seed && php artisan config:cache && php artisan route:cache && php artisan view:cache && nginx -g 'daemon off;'
