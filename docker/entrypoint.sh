#!/bin/sh
set -e

composer install --no-interaction --optimize-autoloader

if [ -f package.json ]; then
    npm install
fi

mkdir -p /var/www/html/db
chown -R www-data:www-data /var/www/html/db

# Start Tailwind watcher only in local development
if [ "$APP_ENV" = "local" ] && [ -f package.json ]; then
    echo "Starting Tailwind watcher..."
    nohup npm run css:dev > /tmp/tailwind.log 2>&1 &
fi

exec "$@"
