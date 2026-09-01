#!/bin/bash
set -e

echo "==> Starting Medical Supplies Laravel Server..."

# 1. Ensure exactly ONE Apache MPM is loaded (clean all MPM symlinks and force prefork)
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# 2. Determine Port
PORT="${PORT:-80}"
echo "==> Using Port: $PORT"

# 3. Pass PORT to Apache's environment
echo "export PORT=$PORT" >> /etc/apache2/envvars

# 4. Ensure APP_KEY
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A="
fi

# 5. Storage permissions and symlink
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

php artisan storage:link --force || true

# 6. Non-blocking Database Migrations
if [ -n "$DB_HOST" ]; then
    echo "==> Running migrations for database: $DB_HOST"
    php artisan migrate --force || echo "==> Migrations will run on demand"
fi

# 7. Clear caches
php artisan optimize:clear || true

# 8. Start Apache in foreground
echo "==> Launching Apache on port $PORT..."
exec /usr/local/bin/apache2-foreground
