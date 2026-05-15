#!/bin/bash
# Deployment script — run on the server by GitHub Actions on every push to main.
set -e

APP_DIR="/var/www/deinbrett"
PHP_BIN="php8.5"

echo "==> Pulling latest code"
cd "$APP_DIR"
git fetch origin main
git reset --hard origin/main

echo "==> Installing dependencies"
composer install --no-dev --optimize-autoloader --quiet

echo "==> Clearing PHP opcode cache"
${PHP_BIN} -r "if (function_exists('opcache_reset')) opcache_reset();" 2>/dev/null || true

echo "==> Fixing permissions on db/"
chown -R www-data:www-data "$APP_DIR/db"
chmod -R 770 "$APP_DIR/db"

echo "==> Reloading PHP-FPM"
systemctl reload php8.5-fpm

echo "Deploy complete: $(date)"
