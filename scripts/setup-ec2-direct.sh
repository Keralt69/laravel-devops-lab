#!/usr/bin/env bash
# ==============================================================================
# Script: setup-ec2-direct.sh
# Purpose: Provision fresh Ubuntu 22.04 / 24.04 EC2 instance for Direct Laravel
#          Deployment with Nginx, PHP 8.2/8.3, Composer, and Git (Task 3).
# Usage:   sudo bash setup-ec2-direct.sh
# ==============================================================================

set -euo pipefail

echo ">>> [1/7] Updating apt repositories..."
apt-get update -y
apt-get install -y software-properties-common curl git unzip rsync ufw

echo ">>> [2/7] Installing PHP and required Laravel extensions..."
add-apt-repository -y ppa:ondrej/php || true
apt-get update -y

PHP_VER="8.2"
apt-get install -y \
    php${PHP_VER} \
    php${PHP_VER}-fpm \
    php${PHP_VER}-cli \
    php${PHP_VER}-common \
    php${PHP_VER}-mysql \
    php${PHP_VER}-sqlite3 \
    php${PHP_VER}-zip \
    php${PHP_VER}-gd \
    php${PHP_VER}-mbstring \
    php${PHP_VER}-curl \
    php${PHP_VER}-xml \
    php${PHP_VER}-bcmath \
    php${PHP_VER}-intl \
    php${PHP_VER}-opcache

echo ">>> [3/7] Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi
composer --version

echo ">>> [4/7] Installing and configuring Nginx..."
apt-get install -y nginx

# Deploy Nginx Laravel site configuration
cat << 'EOF' > /etc/nginx/sites-available/laravel
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/laravel-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html index.htm;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable site and remove default
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/laravel

echo ">>> [5/7] Creating deployment directory: /var/www/laravel-app..."
mkdir -p /var/www/laravel-app
chown -R ubuntu:www-data /var/www/laravel-app
chmod -R 775 /var/www/laravel-app

# Grant ubuntu user passwordless reload of nginx and php-fpm
echo "ubuntu ALL=(ALL) NOPASSWD: /usr/sbin/service nginx reload, /usr/sbin/service php8.2-fpm reload, /usr/bin/systemctl reload nginx, /usr/bin/systemctl reload php8.2-fpm" > /etc/sudoers.d/laravel-deploy
chmod 0440 /etc/sudoers.d/laravel-deploy

echo ">>> [6/7] Testing and reloading Nginx..."
nginx -t
systemctl restart php${PHP_VER}-fpm
systemctl restart nginx

echo ">>> [7/7] EC2 Direct Deployment Environment is READY!"
echo "Target directory: /var/www/laravel-app"
echo "Web root:         /var/www/laravel-app/public"
