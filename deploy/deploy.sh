#!/bin/bash
# Deployment script — called by GitHub Actions on every push to main.
set -e

APP_DIR="/home/clients/5587997f74a0eeff8f0c921f1e7a7b40/sites/deinbrett.ch"

echo "==> Pulling latest code"
cd "$APP_DIR"
git fetch origin main
git reset --hard origin/main

echo "==> Installing dependencies"
composer install --no-dev --optimize-autoloader --quiet

echo "Deploy complete: $(date)"
