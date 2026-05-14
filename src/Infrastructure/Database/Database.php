<?php

namespace DeinBrett\Infrastructure\Database;

use PDO;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private const string BASE_PATH = __DIR__ . "/../../../db";

    // DSN SQLITE
    private const string SQLITE_DSN_PREFIX = "sqlite:";
    private const string SQLITE_FILE_PATH = self::BASE_PATH . "/deinbrett.sqlite";
    private const string SQLITE_DSN = self::SQLITE_DSN_PREFIX . self::SQLITE_FILE_PATH;

    // SQLITE
    private const string SCHEMA_FILE_PATH = self::BASE_PATH . "/schema.sql";
    private const string SEEDER_FILE_PATH = self::BASE_PATH . "/seed.sql";

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() { throw new \Exception("Cannot unserialize singleton"); }

    protected function __construct()
    {
        // Init PDO object
        $this->pdo = new PDO(self::SQLITE_DSN);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->create();
        $this->seed();
    }

    // create the DB schema
    private function create(): void
    {
        // get SQL from schema.sql file
        if (!file_exists(self::SCHEMA_FILE_PATH)) {
            throw new \RuntimeException('Schema file not found: ' . self::SCHEMA_FILE_PATH);
        }
        $this->pdo->exec(file_get_contents(self::SCHEMA_FILE_PATH));
    }

    // seed database
    private function seed(): void
    {
        if (!file_exists(self::SEEDER_FILE_PATH)) {
            return;
        }

        // check if DB is already seeded -> if yes don't seed
        $count = (int) $this->pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        if ($count > 0) {
            return;
        }

        $this->pdo->beginTransaction();
        // execute SQL from schema.sql
        $this->pdo->exec(file_get_contents(self::SEEDER_FILE_PATH));
        // commit the transaction
        $this->pdo->commit();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
