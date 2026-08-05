<?php

return function (PDO $pdo): void {
    $pdo->exec(<<<SQL
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
    SQL);
};
