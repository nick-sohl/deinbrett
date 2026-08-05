CREATE TABLE IF NOT EXISTS users (
    id          INTEGER     PRIMARY KEY,
    first_name  TEXT        NOT NULL,
    last_name   TEXT        NOT NULL,
    email       TEXT        NOT NULL,
    password    TEXT        NOT NULL,
    created_at  DATETIME    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS boards (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    name         TEXT    NOT NULL,
    slug         TEXT    NOT NULL UNIQUE,
    tagline      TEXT    NOT NULL DEFAULT '',
    description  TEXT    NOT NULL DEFAULT '',
    wood_type    TEXT    NOT NULL DEFAULT 'eiche',
    construction TEXT    NOT NULL DEFAULT 'stirnholz',
    size         TEXT    NOT NULL DEFAULT 'L',
    extras       TEXT    NOT NULL DEFAULT '[]',
    price        REAL    NOT NULL,
    stock        INTEGER NOT NULL DEFAULT 1,
    featured     INTEGER NOT NULL DEFAULT 0,
    image_path   TEXT    NOT NULL DEFAULT '',
    created_at   TEXT    NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS orders (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    reference      TEXT    NOT NULL UNIQUE,
    status         TEXT    NOT NULL DEFAULT 'pending',
    first_name     TEXT    NOT NULL,
    last_name      TEXT    NOT NULL,
    email          TEXT    NOT NULL,
    phone          TEXT    NOT NULL DEFAULT '',
    address        TEXT    NOT NULL,
    city           TEXT    NOT NULL,
    zip            TEXT    NOT NULL,
    country        TEXT    NOT NULL DEFAULT 'CH',
    notes          TEXT    NOT NULL DEFAULT '',
    subtotal       REAL    NOT NULL,
    shipping       REAL    NOT NULL DEFAULT 0,
    total          REAL    NOT NULL,
    payment_method TEXT    NOT NULL DEFAULT 'twint',
    payment_status TEXT    NOT NULL DEFAULT 'pending',
    created_at     TEXT    NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS order_items (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id         INTEGER NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    board_id         INTEGER,
    product_name     TEXT    NOT NULL,
    product_snapshot TEXT    NOT NULL DEFAULT '{}',
    quantity         INTEGER NOT NULL DEFAULT 1,
    unit_price       REAL    NOT NULL,
    total            REAL    NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_order_items_order_id ON order_items(order_id);

-- Configurator options (CMS-managed)
CREATE TABLE IF NOT EXISTS wood_types (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    key          TEXT    NOT NULL UNIQUE,
    name         TEXT    NOT NULL,
    color        TEXT    NOT NULL DEFAULT '',
    grain        TEXT    NOT NULL DEFAULT '',
    hardness     TEXT    NOT NULL DEFAULT '',
    features     TEXT    NOT NULL DEFAULT '',
    description  TEXT    NOT NULL DEFAULT '',
    price_add    REAL    NOT NULL DEFAULT 0,
    image_path   TEXT    NOT NULL DEFAULT '',
    sort_order   INTEGER NOT NULL DEFAULT 0,
    active       INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS sizes (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    key          TEXT    NOT NULL UNIQUE,
    label        TEXT    NOT NULL,
    length_mm    INTEGER NOT NULL DEFAULT 0,
    width_mm     INTEGER NOT NULL DEFAULT 0,
    height_mm    INTEGER NOT NULL DEFAULT 0,
    description  TEXT    NOT NULL DEFAULT '',
    base_price   REAL    NOT NULL DEFAULT 0,
    sort_order   INTEGER NOT NULL DEFAULT 0,
    active       INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS constructions (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    key          TEXT    NOT NULL UNIQUE,
    name         TEXT    NOT NULL,
    description  TEXT    NOT NULL DEFAULT '',
    price_add    REAL    NOT NULL DEFAULT 0,
    sort_order   INTEGER NOT NULL DEFAULT 0,
    active       INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS extras (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    key           TEXT    NOT NULL UNIQUE,
    name          TEXT    NOT NULL,
    description   TEXT    NOT NULL DEFAULT '',
    category      TEXT    NOT NULL DEFAULT '',
    category_label TEXT   NOT NULL DEFAULT '',
    exclusive     INTEGER NOT NULL DEFAULT 0,
    price         REAL    NOT NULL DEFAULT 0,
    sort_order    INTEGER NOT NULL DEFAULT 0,
    active        INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE IF NOT EXISTS settings (
    key   TEXT PRIMARY KEY,
    value TEXT NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS schema_migrations (
    filename    TEXT PRIMARY KEY,
    applied_at  TEXT NOT NULL DEFAULT (datetime('now'))
);
