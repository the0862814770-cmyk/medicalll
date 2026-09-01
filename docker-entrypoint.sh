#!/bin/bash

echo "=========================================="
echo " Starting Medical Supplies System..."
echo "=========================================="

# 1. Source Apache Environment Variables
if [ -f /etc/apache2/envvars ]; then
    . /etc/apache2/envvars
fi

# 2. Configure Dynamic Port for Railway (Target ONLY port 80, do NOT touch 443)
PORT="${PORT:-80}"
echo "==> Configuring Apache to listen on port: $PORT"
sed -i "s/^Listen 80\$/Listen $PORT/" /etc/apache2/ports.conf || true
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/000-default.conf || true

# 3. Suppress Apache ServerName warning
echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf
a2enconf servername > /dev/null 2>&1 || true

# 4. Check APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "==> APP_KEY not detected, setting fallback key..."
    export APP_KEY="base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A="
fi

# 5. Clear application caches
echo "==> Clearing application caches..."
php artisan optimize:clear || true

# 6. Create storage symlink
echo "==> Linking storage..."
php artisan storage:link || true

# 7. Run Database Migrations (non-blocking)
if [ -n "$DB_HOST" ]; then
    echo "==> Database host found ($DB_HOST). Running migrations..."
    php artisan migrate --force || echo "==> Migration warning: database not ready yet."
fi

# 8. Start Apache in foreground
echo "==> Launching Apache Web Server on port $PORT..."
exec apache2-foreground
