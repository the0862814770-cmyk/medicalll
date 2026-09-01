#!/bin/bash
set -e

echo "==> Starting Medical Supplies Laravel Server..."

# 1. Determine Port
PORT="${PORT:-80}"
echo "==> Using Port: $PORT"

# 2. Pass PORT to Apache's environment
echo "export PORT=$PORT" >> /etc/apache2/envvars

# 3. Ensure APP_KEY
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A="
fi

# 4. Storage permissions and symlink
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

php artisan storage:link --force || true

# 5. Non-blocking Database Migrations
if [ -n "$DB_HOST" ]; then
    echo "==> Running migrations for database: $DB_HOST"
    php artisan migrate --force || echo "==> Migrations will run on demand"
fi

# 6. Clear caches
php artisan optimize:clear || true

# 7. Start Apache in foreground
echo "==> Launching Apache on port $PORT..."
exec /usr/local/bin/apache2-foreground
