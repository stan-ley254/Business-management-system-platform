#!/bin/bash

# Ensure storage permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Ensure database file exists in persistent storage (/var/www/html/storage/database)
DB_FILE="/var/www/html/storage/database/database.sqlite"
if [ ! -f "$DB_FILE" ]; then
    mkdir -p /var/www/html/storage/database
    touch "$DB_FILE"
    chown www-data:www-data "$DB_FILE"
    chmod 660 "$DB_FILE"
    echo "Created SQLite database file at $DB_FILE"
fi

# Run artisan commands safely as www-data
su -s /bin/bash www-data -c "php artisan migrate:fresh --seed --force"
su -s /bin/bash www-data -c "php artisan config:clear"
su -s /bin/bash www-data -c "php artisan config:cache"
su -s /bin/bash www-data -c "php artisan route:cache"
su -s /bin/bash www-data -c "php artisan view:cache"

# Start Supervisor (manages php-fpm, nginx, scheduler)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
