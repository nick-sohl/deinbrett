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

    public function findById(string $entity, int $id): ?object
    {
        $tableName = strtolower((new ReflectionClass($entity))->getShortName()) . 's';
        $stmt = $this->pdo->prepare("SELECT * FROM {$tableName} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $entity);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
}
