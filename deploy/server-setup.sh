#!/bin/bash
# First-time setup on Infomaniak shared hosting.
# Run once via SSH after uploading this script or downloading it.

set -e

APP_DIR="/home/clients/5587997f74a0eeff8f0c921f1e7a7b40/sites/deinbrett.ch"
REPO="https://github.com/nick-sohl/deinbrett.git"

echo "==> Cloning repository"
cd "$APP_DIR"
if [ ! -d ".git" ]; then
    git init
    git config pull.ff only
    git remote add origin "$REPO"
    git fetch origin main
    git checkout -b main --track origin/main
else
    git config pull.ff only
    git pull origin main
fi

echo "==> Installing Composer dependencies"
composer install --no-dev --optimize-autoloader

echo "==> Creating .env"
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo ""
    echo "  .env created. Edit it now:"
    echo "  nano $APP_DIR/.env"
fi

echo "==> Setting db/ directory permissions"
chmod 770 "$APP_DIR/db"
touch "$APP_DIR/db/deinbrett.sqlite" 2>/dev/null || true
chmod 660 "$APP_DIR/db/deinbrett.sqlite"

echo ""
echo "Setup complete."
echo ""
echo "Next steps:"
echo "  1. Edit .env:  nano $APP_DIR/.env"
echo "  2. Visit the preview URL to verify the site loads"
echo "  3. Set up the deploy pipeline (see deploy/deploy.sh)"
