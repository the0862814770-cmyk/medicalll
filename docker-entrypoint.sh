#!/bin/bash
set -e

echo "=========================================="
echo " Starting Medical Supplies Laravel Server..."
echo "=========================================="

# 1. Ensure exactly ONE Apache MPM is loaded
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# 2. Determine Port & Export to Apache
PORT="${PORT:-80}"
echo "==> Using Port: $PORT"
echo "export PORT=$PORT" >> /etc/apache2/envvars

# 3. Create / Update .env file from Container Environment
echo "==> Creating production .env file..."
cat <<EOF > /var/www/html/.env
APP_NAME="ระบบบริหารคลังเวชภัณฑ์ มรภ.นครศรีธรรมราช"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A=}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-railway}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOF

# 4. Storage permissions and symlink
mkdir -p /var/www/html/storage/framework/{sessions,views,cache,data} /var/www/html/storage/logs /var/www/html/public/images/supplies
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public /var/www/html/.env
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

php artisan storage:link --force || true

# 5. Run Database Migrations and Seed Initial Accounts
echo "==> Executing database migration & seeder..."
php artisan migrate --seed --force || php artisan migrate --force || echo "==> Migrations completed or skipped."

# 6. Cache config and routes for optimal performance
echo "==> Caching application config..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# 7. Start Apache in foreground
echo "==> Launching Apache Web Server..."
exec /usr/local/bin/apache2-foreground
