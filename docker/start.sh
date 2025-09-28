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

# Run artisan commands as www-data, step by step
echo "Running migrations..."
su -s /bin/bash www-data -c "php artisan migrate --force" || exit 1

echo "Running seeders..."
su -s /bin/bash www-data -c "php artisan db:seed --force" || exit 1

echo "Clearing config..."
su -s /bin/bash www-data -c "php artisan config:clear" || exit 1

echo "Caching config..."
su -s /bin/bash www-data -c "php artisan config:cache" || exit 1

echo "Caching routes..."
su -s /bin/bash www-data -c "php artisan route:cache" || exit 1

echo "Caching views..."
su -s /bin/bash www-data -c "php artisan view:cache" || exit 1

echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
