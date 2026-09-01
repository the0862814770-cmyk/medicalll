#!/bin/bash
set -e

echo "=========================================="
echo " Starting Medical Supplies System..."
echo "=========================================="

# 1. Ensure exactly ONE Apache MPM is loaded
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# 2. Determine Port & Export to Apache
PORT="${PORT:-80}"
echo "==> Using Port: $PORT"
echo "export PORT=$PORT" >> /etc/apache2/envvars

# 3. Setup Embedded Database (SQLite)
echo "==> Initializing embedded database..."
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chmod 777 /var/www/html/database /var/www/html/database/database.sqlite

# 4. Create / Update .env file using Embedded Database
echo "==> Generating production .env file (Embedded SQLite)..."
cat <<EOF > /var/www/html/.env
APP_NAME="Medical Supplies System"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A=}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=https://medicalll-production.up.railway.app

LOG_CHANNEL=stderr
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_COOKIE=medical_supplies_session
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
EOF

# 5. Storage permissions and symlink
mkdir -p /var/www/html/storage/framework/{sessions,views,cache,data} /var/www/html/storage/logs /var/www/html/public/images/supplies
touch /var/www/html/storage/logs/laravel.log
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public /var/www/html/database
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public /var/www/html/database /var/www/html/.env

php artisan storage:link --force || true

# 6. Execute Database Migrations and Seed Sample Users directly into Embedded DB
echo "==> Migrating and seeding embedded database..."
php artisan migrate --seed --force || php artisan migrate --force || echo "==> Migrations ready."

# 7. Clear old cache and rebuild config cache
php artisan optimize:clear || true
php artisan config:cache || true
php artisan view:cache || true

# Ensure permissions before launch
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public /var/www/html/database
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env

# 8. Start Apache in foreground
echo "==> Launching Apache Web Server with Embedded Database..."
exec /usr/local/bin/apache2-foreground
