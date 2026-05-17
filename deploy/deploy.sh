#!/bin/bash
# Deployment script — called by GitHub Actions on every push to main.
set -e

APP_DIR="/sites/deinbrett.ch"

echo "==> Pulling latest code"
cd "$APP_DIR"
git fetch origin main
git reset --hard origin/main

echo "==> Installing dependencies"
composer install --no-dev --optimize-autoloader --quiet

echo "Deploy complete: $(date)"
