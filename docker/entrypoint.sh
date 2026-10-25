#!/bin/bash
set -e

# Copy .env.example to .env if .env doesn't exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate APP_KEY if empty or default placeholder
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# Ensure SQLite database file exists if sqlite is used
if grep -q "DB_CONNECTION=sqlite" /var/www/html/.env; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chown -R www-data:www-data /var/www/html/database
fi

# Fix storage and cache permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cache configuration, routes, and views for production performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations
php artisan migrate --force || true

echo "Laravel application is ready. Starting web server..."
exec "$@"
