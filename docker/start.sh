#!/bin/bash

# Fix permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Start PHP-FPM in background
php-fpm &

# Run artisan commands as www-data
su -s /bin/bash www-data -c "php artisan migrate:fresh --seed"
su -s /bin/bash www-data -c "php artisan config:clear"
su -s /bin/bash www-data -c "php artisan config:cache"
su -s /bin/bash www-data -c "php artisan route:cache"
su -s /bin/bash www-data -c "php artisan view:cache"

# Start cron
cron

# Confirm Nginx config exists (for debugging)
echo "==== NGINX CONFIG ===="
cat /etc/nginx/conf.d/default.conf

# Start Nginx
nginx -g "daemon off;"