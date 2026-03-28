# Docker Setup

## Services

| Service | Image | Purpose |
|---------|-------|---------|
| php-fpm | Custom (PHP 8.5-FPM) | Runs PHP application code |
| nginx | nginx:alpine | Serves static files, proxies PHP to php-fpm |

SQLite is file-based and does not need its own container. The database file lives in `db/` and is persisted via bind mount.

## What's Installed in the PHP Container

- PHP 8.5-FPM with `pdo_sqlite` extension
- Composer (for PHP dependencies)
- Node.js 22 (for frontend asset building)

## Entrypoint

On container start (`docker/entrypoint.sh`):

1. `composer install` — installs PHP dependencies
2. `npm install` — installs Node dependencies (if package.json exists)
3. Ensures `db/` is writable by the web server

## Nginx Configuration

- Document root: `public/`
- All requests that don't match a file route to `public/index.php` (front controller)
- Access to `.env`, `.ht`, and `.git` files is blocked

## Usage

```sh
docker compose up --build
```

Application is available at `http://localhost:8080`.
