#!/bin/bash

echo "=========================================="
echo " Starting Medical Supplies System..."
echo "=========================================="

# 1. Configure Port for Railway
PORT="${PORT:-80}"
echo "==> Configuring Apache for port: $PORT"
sed -i "s/Listen [0-9]*/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf

# 2. Check APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "==> APP_KEY not detected, generating default key..."
    export APP_KEY="base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A="
fi

# 3. Clear old caches to prevent stale config issues
echo "==> Clearing application caches..."
php artisan optimize:clear || true

# 4. Create storage symlink
echo "==> Linking storage..."
php artisan storage:link || true

# 5. Run Database Migrations (non-blocking)
echo "==> Checking database migration..."
php artisan migrate --force || echo "==> Migration skipped or waiting for DB connection."

# 6. Start Apache Web Server in Foreground
echo "==> Launching Apache Web Server..."
exec apache2 -D FOREGROUND
