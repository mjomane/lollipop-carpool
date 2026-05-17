FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zlib1g-dev libonig-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql \
    zip mbstring \
    && docker-php-ext-enable pdo pdo_mysql pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permissions
RUN mkdir -p bootstrap/cache storage \
    && chmod -R 775 bootstrap/cache storage

# Generate app key
RUN php artisan key:generate || true

# Expose port
EXPOSE 80

# Start Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
