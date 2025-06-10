#!/bin/bash

echo "Starting PHP-FPM..."
php-fpm &

echo "Running Laravel setup..."
php artisan migrate:fresh --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting cron..."
cron

echo "Starting Nginx..."
nginx -g "daemon off;"
