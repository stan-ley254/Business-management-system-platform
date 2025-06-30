#!/bin/bash

# Start PHP-FPM in background
php-fpm &

# Run Laravel setup
CMD php artisan serve --host=0.0.0.0 --port=80


# Start cron
cron

# Start Nginx in foreground
nginx -g "daemon off;"
