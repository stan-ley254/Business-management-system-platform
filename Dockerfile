FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
    cron \
    nginx \
    nodejs \
    npm

# GD setup + Laravel required extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-install gd pdo pdo_sqlite mbstring bcmath exif pcntl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel app
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Composer install
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# npm install & vite build
RUN npm install && npm run build

# Laravel scheduler cron
COPY docker/laravel_scheduler /etc/cron.d/laravel_scheduler
RUN chmod 0644 /etc/cron.d/laravel_scheduler && crontab /etc/cron.d/laravel_scheduler

RUN echo 'server {
    listen 80;
    index index.php index.html;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_index index.php;
    }

    location ~ /\.ht {
        deny all;
    }
}' > /etc/nginx/conf.d/default.conf

# Nginx config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Create database if not exists
RUN touch database/database.sqlite



EXPOSE 80

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]

