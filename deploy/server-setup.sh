#!/bin/bash
# First-time setup on Infomaniak shared hosting.
# Run once via SSH: ssh <user>@<host> then paste this script.
# The document root must be set to /sites/deinbrett.ch/public in the
# Infomaniak control panel before running this.

set -e

APP_DIR="/sites/deinbrett.ch"
REPO="git@github.com:nick-sohl/deinbrett.git"

echo "==> Cloning repository"
# If the directory already has files from Infomaniak, initialise git inside it
cd "$APP_DIR"
if [ ! -d ".git" ]; then
    git init
    git remote add origin "$REPO"
    git fetch origin main
    git checkout -b main --track origin/main
else
    git pull origin main
fi

echo "==> Installing Composer dependencies"
composer install --no-dev --optimize-autoloader

echo "==> Creating .env"
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo ""
    echo "  .env created. Edit it now with your production values:"
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
echo "  1. Edit .env:               nano $APP_DIR/.env"
echo "  2. Generate deploy SSH key: ssh-keygen -t ed25519 -f ~/.ssh/deploy_key -N ''"
echo "  3. Add deploy public key to GitHub repo → Settings → Deploy keys"
echo "  4. Add GitHub Actions secrets (see README)"
echo "  5. Visit https://deinbrett.ch to verify"
