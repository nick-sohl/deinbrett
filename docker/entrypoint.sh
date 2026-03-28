#!/bin/sh
set -e

composer install --no-interaction --optimize-autoloader

if [ -f package.json ]; then
    npm install
fi

mkdir -p /var/www/html/db
chown -R www-data:www-data /var/www/html/db

exec "$@"
