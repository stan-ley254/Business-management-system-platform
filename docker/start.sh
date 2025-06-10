#!/bin/bash

# Start PHP-FPM in background
php-fpm &

# Run Laravel setup
php artisan migrate:fresh --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start cron
cron

# Start Nginx in foreground
nginx -g "daemon off;"
