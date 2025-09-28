#!/bin/bash
set -e

# Ensure storage permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Ensure database directory and file exist
DB_DIR="/var/www/html/storage/database"
DB_FILE="$DB_DIR/database.sqlite"

if [ ! -d "$DB_DIR" ]; then
    mkdir -p "$DB_DIR"
    chown -R www-data:www-data "$DB_DIR"
    chmod -R 775 "$DB_DIR"
fi

if [ ! -f "$DB_FILE" ]; then
    touch "$DB_FILE"
    chown www-data:www-data "$DB_FILE"
    chmod 664 "$DB_FILE"   # <- allow web server writes
    echo "Created SQLite database file at $DB_FILE"
else
    # Always re-apply correct ownership and perms (important on redeploys)
    chown www-data:www-data "$DB_FILE"
    chmod 664 "$DB_FILE"
fi

# Run artisan commands as www-data
su -s /bin/bash www-data -c "php artisan migrate --force"
su -s /bin/bash www-data -c "php artisan db:seed --force"
su -s /bin/bash www-data -c "php artisan config:clear"
su -s /bin/bash www-data -c "php artisan config:cache"
su -s /bin/bash www-data -c "php artisan route:cache"
su -s /bin/bash www-data -c "php artisan view:cache"

# Start Supervisor (manages php-fpm, nginx, scheduler)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
