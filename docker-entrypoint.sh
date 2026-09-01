#!/bin/bash
set -e

# Configure Apache port if PORT env variable is provided (Railway uses dynamic $PORT)
if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/" /etc/apache2/sites-available/000-default.conf
fi

# Run storage link if needed
php artisan storage:link || true

# Run database migrations
php artisan migrate --force || true

# Cache configurations
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start Apache in foreground
exec apache2-foreground
