#!/bin/bash
set -x

echo "==> Preparing Laravel..."

# Export default PORT if not set
export PORT="${PORT:-80}"

# Set default APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A="
fi

# Ensure storage directories exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Run storage symlink
php /var/www/html/artisan storage:link --force || true

# Run migrations if DB is configured
if [ -n "$DB_HOST" ]; then
    php /var/www/html/artisan migrate --force || true
fi

# Clear old cache
php /var/www/html/artisan optimize:clear || true

echo "==> Starting Apache on port $PORT..."
exec /usr/local/bin/apache2-foreground
