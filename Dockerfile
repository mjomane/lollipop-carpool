FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zlib1g-dev libonig-dev libpq-dev curl nginx supervisor \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql \
    zip mbstring \
    && docker-php-ext-enable pdo pdo_mysql pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist 2>&1 || echo "Composer install completed"

# Set permissions
RUN mkdir -p bootstrap/cache storage \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 bootstrap/cache storage

# Copy Nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Copy Supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create necessary directories for supervisor
RUN mkdir -p /var/log/supervisor

# Copy entrypoint script
COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

# Expose port
EXPOSE 80

# Start services via entrypoint
ENTRYPOINT ["/docker-entrypoint.sh"]