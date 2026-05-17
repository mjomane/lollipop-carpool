FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zlib1g-dev libonig-dev libpq-dev curl \
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
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permissions
RUN mkdir -p bootstrap/cache storage \
    && chmod -R 775 bootstrap/cache storage

# Generate app key
RUN php artisan key:generate || true

# Create necessary directories
RUN mkdir -p bootstrap/cache && chmod -R 775 bootstrap/cache

# Install nginx
RUN apt-get update && apt-get install -y nginx && rm -rf /var/lib/apt/lists/*

# Copy nginx config
RUN echo 'server {\n    listen 8000;\n    server_name _;\n    root /var/www/html/public;\n    index index.php;\n    location / {\n        try_files $uri $uri/ /index.php?$query_string;\n    }\n    location ~ \\.php$ {\n        fastcgi_pass 127.0.0.1:9000;\n        fastcgi_index index.php;\n        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n        include fastcgi_params;\n    }\n}' > /etc/nginx/sites-available/default

# Expose port
EXPOSE 8000

# Start PHP-FPM and nginx
CMD php-fpm -D && nginx -g "daemon off;"