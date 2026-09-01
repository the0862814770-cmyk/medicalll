#!/bin/bash
set -e

echo "==> Starting Medical Supplies System..."

# Configure dynamic PORT for Railway/Render
if [ -n "$PORT" ]; then
    echo "==> Configuring Apache to listen on port $PORT"
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/" /etc/apache2/sites-available/000-default.conf
fi

# Ensure storage link
echo "==> Creating storage link..."
php artisan storage:link || true

# Run database migrations
if [ -n "$DB_HOST" ]; then
    echo "==> Database host found ($DB_HOST). Running migrations..."
    php artisan migrate --force || echo "Migration warning: could not run migrations immediately. Will continue..."
fi

# Cache routes and config for high performance
echo "==> Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Starting Apache web server..."
exec apache2-foreground
