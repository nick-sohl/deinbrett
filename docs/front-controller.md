# Front Controller Pattern

## Concept

Every HTTP request enters the application through a single file: `public/index.php`. This file bootstraps the application and delegates to the appropriate handler.

## How It Works

1. Nginx's `try_files` directive sends all requests that don't match a real file to `/index.php`
2. `index.php` bootstraps the app — autoloading, environment loading, routing, response
3. Only the `public/` directory is exposed to the web, keeping files like `.env`, `composer.json`, and `src/` inaccessible

## Bootstrap Flow

```
Request → Nginx → public/index.php
                     ├── Autoloader (vendor/autoload.php)
                     ├── Environment (.env via phpdotenv)
                     ├── Repository
                     ├── Service
                     ├── Controller
                     └── Router → Response
```

## Why

- Single entry point makes it easy to apply global concerns (auth, logging, error handling)
- Source code stays outside the web root — no accidental exposure
- Standard approach used by Laravel, Symfony, Slim, and virtually every modern PHP framework
