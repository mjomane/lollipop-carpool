FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zlib1g-dev libonig-dev libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql \
    zip mbstring \
    && docker-php-ext-enable pdo pdo_mysql pdo_pgsql

# Enable Apache modules
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permissions
RUN mkdir -p bootstrap/cache storage \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 bootstrap/cache storage

# Generate app key
RUN php artisan key:generate || true

# Create necessary directories
RUN mkdir -p bootstrap/cache && chmod -R 775 bootstrap/cache

# Configure Apache to serve from public directory
RUN rm /etc/apache2/sites-enabled/000-default.conf && \
    echo '<VirtualHost *:80>\n    ServerName _\n    DocumentRoot /var/www/html/public\n    <Directory /var/www/html/public>\n        AllowOverride All\n        Require all granted\n    </Directory>\n</VirtualHost>' > /etc/apache2/sites-available/000-default.conf && \
    a2ensite 000-default

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]