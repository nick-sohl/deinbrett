# Dein Brett

Handgefertigte Küchenbretter · PHP-Shop mit SQLite und einem simplen CMS.

## Setup

```bash
composer install
npm install
```

Erforderliche Umgebungsvariablen in `.env` (siehe `.env.example` als Ausgangspunkt):

```
APP_ENV=production           # 'local' schreibt E-Mails ins Log statt zu senden
ADMIN_EMAIL=nick@example.ch  # Empfänger der Bestell-Benachrichtigungen
MAIL_FROM=info@deinbrett.ch
MAIL_FROM_NAME="DeinBrett"
```

## Datenbank & Admin-User

```bash
php bin/migrate.php        # führt pending migrations aus (idempotent)
php bin/create-admin.php   # legt Admin-Konto an (interaktiv)
```

## Dev-Server

```bash
npm run css:dev            # Tailwind Watch
php -S 127.0.0.1:8000 -t public
```

Aufrufe:
- Shop: <http://127.0.0.1:8000/>
- CMS:  <http://127.0.0.1:8000/admin/login>

## Deploy (Infomaniak)

Skripte unter `deploy/`. Nach Deploy einmalig `php bin/migrate.php` auf dem Server ausführen.

## CMS

Unter `/admin` erreichbar. Verwaltet Produkte, Bilder, Bestellungen und die Konfigurator-Optionen
(Holzarten, Grössen, Bauweisen, Extras). Kunden-E-Mails werden bei Statuswechseln automatisch
verschickt (in `APP_ENV=local` nur ins Log `storage/logs/mail.log` geschrieben).
