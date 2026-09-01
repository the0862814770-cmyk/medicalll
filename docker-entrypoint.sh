#!/bin/bash

echo "=========================================="
echo " Starting Medical Supplies System..."
echo "=========================================="

# 1. Source Apache Environment Variables
if [ -f /etc/apache2/envvars ]; then
    . /etc/apache2/envvars
fi

# 2. Configure Dynamic Port for Railway
PORT="${PORT:-80}"
echo "==> Configuring Apache for port: $PORT"
sed -i "s/Listen [0-9]*/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/000-default.conf

# 3. Check APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "==> APP_KEY not detected, setting fallback key..."
    export APP_KEY="base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A="
fi

# 4. Clear old caches
echo "==> Clearing application caches..."
php artisan optimize:clear || true

# 5. Create storage symlink
echo "==> Linking storage..."
php artisan storage:link || true

# 6. Run Database Migrations
if [ -n "$DB_HOST" ]; then
    echo "==> Running migrations for host: $DB_HOST"
    php artisan migrate --force || echo "==> Migration warning: database not reachable yet, skipping..."
fi

# 7. Start Apache Web Server using official apache2-foreground
echo "==> Launching Apache Web Server..."
exec apache2-foreground
