#!/bin/bash
set -e

# Generate app key if not set
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file..."
    cp /var/www/html/.env.example /var/www/html/.env || true
fi

# Ensure APP_KEY is set
if ! grep -q "APP_KEY=" /var/www/html/.env || [ -z "$(grep '^APP_KEY=' /var/www/html/.env | cut -d= -f2)" ]; then
    echo "Generating APP_KEY..."
    cd /var/www/html && php artisan key:generate --force
fi

# Set proper permissions
chown -R www-data:www-data /var/www/html
chmod -R 775 bootstrap/cache storage

# Start Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
