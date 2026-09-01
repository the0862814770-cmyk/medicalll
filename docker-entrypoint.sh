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

# 3. Detect Database (MySQL vs SQLite)
if [ -n "$MYSQLHOST" ] || [ -n "$MYSQL_HOST" ] || [ -n "$DB_HOST" ]; then
    DB_CONN="mysql"
    DB_HOST_VAL="${DB_HOST:-${MYSQLHOST:-${MYSQL_HOST:-127.0.0.1}}}"
    DB_PORT_VAL="${DB_PORT:-${MYSQLPORT:-${MYSQL_PORT:-3306}}}"
    DB_NAME_VAL="${DB_DATABASE:-${MYSQLDATABASE:-${MYSQL_DATABASE:-railway}}}"
    DB_USER_VAL="${DB_USERNAME:-${MYSQLUSER:-${MYSQL_USER:-root}}}"
    DB_PASS_VAL="${DB_PASSWORD:-${MYSQLPASSWORD:-${MYSQL_PASSWORD:-}}}"
    echo "==> Using MySQL Database on $DB_HOST_VAL:$DB_PORT_VAL ($DB_NAME_VAL)"
else
    DB_CONN="sqlite"
    DB_HOST_VAL="127.0.0.1"
    DB_PORT_VAL="3306"
    DB_NAME_VAL="/var/www/html/database/database.sqlite"
    DB_USER_VAL="root"
    DB_PASS_VAL=""
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database /var/www/html/database/database.sqlite
    echo "==> Using Embedded SQLite Database"
fi

# 4. Create / Update .env file
echo "==> Generating production .env file..."
cat <<EOF > /var/www/html/.env
APP_NAME="Medical Supplies System"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-base64:rRXZZCi7D+kBcVU2IKlXwXlYpXqTmHxeE/Edw02eK4A=}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=https://medicalll-production.up.railway.app

LOG_CHANNEL=stderr
LOG_LEVEL=debug

DB_CONNECTION=${DB_CONN}
DB_HOST=${DB_HOST_VAL}
DB_PORT=${DB_PORT_VAL}
DB_DATABASE=${DB_NAME_VAL}
DB_USERNAME=${DB_USER_VAL}
DB_PASSWORD=${DB_PASS_VAL}

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

# 6. Execute Database Migrations and Seed Sample Users
echo "==> Migrating and seeding database..."
php artisan migrate --seed --force || php artisan migrate --force || echo "==> Migrations ready."

# 7. Clear old cache and rebuild config cache
php artisan optimize:clear || true
php artisan config:cache || true

# Ensure permissions before launch
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public /var/www/html/database
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env

# 8. Start Apache in foreground
echo "==> Launching Apache Web Server..."
exec /usr/local/bin/apache2-foreground
