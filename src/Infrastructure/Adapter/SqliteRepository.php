<?php

namespace DeinBrett\Infrastructure\Adapter;

use PDO;
use PDOStatement;
use DeinBrett\Application\Port\Repository;
use DeinBrett\Infrastructure\Database\Database;
use ReflectionClass;

class SqliteRepository implements Repository
{
    private Database $db;
    private PDO $pdo;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->pdo = $this->db->getPdo();
    }

    public function findAll(string $entity): array
    {
        $tableName = strtolower((new ReflectionClass($entity))->getShortName()) . 's';
        $sql = "SELECT * FROM {$tableName}";
        /** @var PDOStatement $result */
        $result = $this->pdo->query($sql);

        return $result->fetchAll(PDO::FETCH_CLASS, $entity);
    }
}
