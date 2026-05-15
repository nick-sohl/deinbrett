#!/bin/bash
# One-time server setup: switches from Apache to nginx, installs dependencies,
# clones the repo and wires everything up for deinbrett.ch
# Run as root on a fresh Infomaniak VPS.

set -e

APP_DIR="/var/www/deinbrett"
APP_USER="www-data"
REPO="https://github.com/nick-sohl/deinbrett.git"
DOMAIN="deinbrett.ch"
PHP="php8.5"

echo "==> Updating packages"
apt-get update -q

echo "==> Stopping and disabling Apache"
systemctl stop apache2   2>/dev/null || true
systemctl disable apache2 2>/dev/null || true

echo "==> Installing nginx, PHP-FPM, Composer deps"
apt-get install -y -q \
    nginx \
    ${PHP}-fpm \
    ${PHP}-sqlite3 \
    ${PHP}-mbstring \
    ${PHP}-xml \
    ${PHP}-curl \
    ${PHP}-zip \
    composer \
    certbot \
    python3-certbot-nginx \
    git \
    unzip

echo "==> Cloning repository"
mkdir -p "$APP_DIR"
git clone "$REPO" "$APP_DIR"

echo "==> Installing Composer dependencies"
cd "$APP_DIR"
composer install --no-dev --optimize-autoloader

echo "==> Creating .env"
cp .env.example .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env

echo "==> Setting permissions"
# App files owned by root, readable by www-data
chown -R root:${APP_USER} "$APP_DIR"
chmod -R 750 "$APP_DIR"
# db/ directory must be writable by www-data so SQLite can create/update the file
chown -R ${APP_USER}:${APP_USER} "$APP_DIR/db"
chmod -R 770 "$APP_DIR/db"
# public/ readable
chmod -R 755 "$APP_DIR/public"

echo "==> Configuring nginx"
cp "$APP_DIR/deploy/nginx.conf" /etc/nginx/sites-available/deinbrett
ln -sf /etc/nginx/sites-available/deinbrett /etc/nginx/sites-enabled/deinbrett
rm -f /etc/nginx/sites-enabled/default

echo "==> Testing nginx config"
nginx -t

echo "==> Obtaining SSL certificate"
# Temporarily serve on HTTP so certbot can verify the domain
# Remove the HTTPS block from nginx.conf for initial cert request
certbot --nginx -d "$DOMAIN" -d "www.$DOMAIN" --non-interactive --agree-tos --email "nick.sohl@novu.ch" --redirect

echo "==> Reloading nginx"
systemctl reload nginx
systemctl enable nginx

echo ""
echo "Setup complete. deinbrett.ch is live."
echo "Add this deploy public key to GitHub secrets:"
echo ""
cat /root/.ssh/id_ed25519.pub 2>/dev/null || echo "(no SSH key found at /root/.ssh/id_ed25519.pub)"
