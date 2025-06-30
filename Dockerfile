FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system packages
RUN apt-get update && apt-get install -y \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libxpm-dev \
    libzip-dev zip unzip git curl libonig-dev libxml2-dev \
    sqlite3 libsqlite3-dev cron nginx nodejs npm

# Configure GD and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-install gd pdo pdo_sqlite mbstring bcmath exif pcntl zip

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

# Write custom nginx config directly (overrides default welcome)
RUN rm -f /etc/nginx/sites-enabled/default && \
    printf "%s\n" \
    "server {" \
    "    listen 80;" \
    "    index index.php index.html;" \
    "    root /var/www/html/public;" \
    "" \
    "    location / {" \
    "        try_files \$uri \$uri/ /index.php?\$query_string;" \
    "    }" \
    "" \
    "    location ~ \.php$ {" \
    "        try_files \$uri =404;" \
    "        fastcgi_pass 127.0.0.1:9000;" \
    "        fastcgi_index index.php;" \
    "        include fastcgi_params;" \
    "        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;" \
    "    }" \
    "" \
    "    location ~ /\.ht {" \
    "        deny all;" \
    "    }" \
    "}" > /etc/nginx/conf.d/default.conf

# Create SQLite DB file if not present
RUN touch database/database.sqlite \
    && chown -R www-data:www-data database \
    && chmod -R 775 database


# Expose port
EXPOSE 80

# Add start script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Run everything
CMD ["/start.sh"]
