#!/bin/bash
set -e

# Fix permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Conditional migration logic
su -s /bin/bash www-data -c "
if ! php artisan tinker --execute='DB::select(\"select 1 from migrations limit 1\")' >/dev/null 2>&1; then
  echo 'No migrations table found — running full migration and seed';
  php artisan migrate --force;
  php artisan db:seed --force;
else
  echo 'Migrations table exists — running migrate only';
  php artisan migrate --force;
fi
"

# Cache configurations
su -s /bin/bash www-data -c "php artisan config:clear"
su -s /bin/bash www-data -c "php artisan config:cache"
su -s /bin/bash www-data -c "php artisan route:cache"
su -s /bin/bash www-data -c "php artisan view:cache"

# Start Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
