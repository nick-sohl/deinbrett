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
