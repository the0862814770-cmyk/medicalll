FROM php:8.2-apache

# Set default port
ENV PORT=80

# Install system dependencies & SQLite library
RUN apt-get update && apt-get install -y \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    dos2unix \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure & Install PHP Extensions (including pdo_sqlite & pdo_mysql)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo_mysql pdo_sqlite mbstring exif pcntl bcmath zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ensure only mpm_prefork is enabled and enable mod_rewrite
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy custom Apache configurations
COPY docker/ports.conf /etc/apache2/ports.conf
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Fix line endings & permissions for entrypoint
RUN dos2unix docker-entrypoint.sh \
    && chmod +x docker-entrypoint.sh \
    && cp docker-entrypoint.sh /docker-entrypoint.sh \
    && chmod +x /docker-entrypoint.sh

# Set storage, database and cache permissions
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    public/uploads \
    database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public /var/www/html/database \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public /var/www/html/database

EXPOSE 80

CMD ["/docker-entrypoint.sh"]
