FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system packages + Supervisor
RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libxpm-dev \
    libzip-dev zip unzip git curl libonig-dev libxml2-dev \
    libpq-dev cron nginx nodejs npm postgresql-client supervisor

# Configure GD and install PHP extensions including PostgreSQL
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-install gd pdo pdo_pgsql mbstring bcmath exif pcntl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel app
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install JS dependencies & build assets
RUN npm install && npm run build

# Laravel Scheduler cron setup
COPY docker/laravel_scheduler /etc/cron.d/laravel_scheduler
RUN chmod 0644 /etc/cron.d/laravel_scheduler && crontab /etc/cron.d/laravel_scheduler

# Copy Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy start script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Expose port
EXPOSE 80

# Start everything with Supervisor
CMD ["/start.sh"]
