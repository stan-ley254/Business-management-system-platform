# Use PHP 8.2 with FPM for production
FROM php:8.2-fpm

# Install system dependencies, Node.js, SQLite, and Caddy
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip unzip git curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_sqlite pdo_mysql mbstring exif pcntl bcmath gd zip xml \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && curl -sL https://caddyserver.com/api/download?os=linux | install -m 755 /dev/stdin /usr/bin/caddy

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application code
COPY . /var/www

# Install PHP dependencies
RUN php -d memory_limit=-1 /usr/bin/composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --verbose

# Install frontend dependencies and build assets (for Livewire)


# Create SQLite database file
RUN touch /var/www/database/database.sqlite \
    && chown www-data:www-data /var/www/database/database.sqlite \
    && chmod 664 /var/www/database/database.sqlite

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Copy Caddy configuration
COPY ./Caddyfile /etc/caddy/Caddyfile

# Configure environment
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env \
    && sed -i 's/DB_DATABASE=.*/DB_DATABASE=\/var\/www\/database\/database.sqlite/' .env \
    && php artisan key:generate --ansi \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force --no-interaction

# Expose port (Render uses PORT env variable, default 10000)
EXPOSE $PORT

# Start PHP-FPM and Caddy
CMD ["/bin/sh", "-c", "php-fpm -D && caddy run --config /etc/caddy/Caddyfile"]

CMD ["apache2-foreground"]
