#!/bin/bash

# Start PHP-FPM in background
php-fpm &

# Laravel setup
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start cron
cron

# Confirm Nginx config exists (for debugging)
echo "==== NGINX CONFIG ===="
cat /etc/nginx/conf.d/default.conf

# Start Nginx
nginx -g "daemon off;"
