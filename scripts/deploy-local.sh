#!/usr/bin/env bash
# ==============================================================================
# Script: deploy-local.sh
# Purpose: Deploy Laravel application to local PHP web server environment (Task 1)
# ==============================================================================

set -euo pipefail

TARGET_DIR="${1:-/var/www/html/laravel}"

echo ">>> Deploying Laravel application to local target: ${TARGET_DIR}"

# Create target directory if it does not exist
mkdir -p "${TARGET_DIR}"

# Sync code excluding development files
rsync -av --delete \
    --exclude='.git' \
    --exclude='.github' \
    --exclude='node_modules' \
    --exclude='tests' \
    ./ "${TARGET_DIR}/"

cd "${TARGET_DIR}"

# Prepare environment file
if [ ! -f .env ]; then
    echo ">>> Creating .env file from .env.example..."
    cp .env.example .env
fi

# Ensure composer dependencies
echo ">>> Installing Composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Ensure APP_KEY
if ! grep -q "APP_KEY=base64:" .env; then
    echo ">>> Generating application key..."
    php artisan key:generate --force
fi

# Ensure SQLite DB exists if configured
if grep -q "DB_CONNECTION=sqlite" .env; then
    touch database/database.sqlite
fi

# Set directory permissions for web server
echo ">>> Setting file permissions for web server..."
if id "www-data" &>/dev/null; then
    chown -R www-data:www-data "${TARGET_DIR}"
fi
chmod -R 775 storage bootstrap/cache

# Cache configuration, routes, and views
echo ">>> Optimizing Laravel caches..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations
echo ">>> Running database migrations..."
php artisan migrate --force || true

echo ">>> Local deployment completed successfully!"
