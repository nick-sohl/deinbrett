<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$db = DeinBrett\Infrastructure\Database\Database::getInstance();
$pdo = $db->getPdo();

$pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (
    filename    TEXT PRIMARY KEY,
    applied_at  TEXT NOT NULL DEFAULT (datetime('now'))
)");

$dir = __DIR__ . '/../db/migrations';
$files = glob($dir . '/*.php');
sort($files);

$applied = [];
foreach ($pdo->query("SELECT filename FROM schema_migrations")->fetchAll(PDO::FETCH_COLUMN) as $f) {
    $applied[$f] = true;
}

$ran = 0;
foreach ($files as $path) {
    $name = basename($path);
    if (isset($applied[$name])) continue;

    echo "→ Running {$name} ... ";
    try {
        $pdo->beginTransaction();
        $migration = require $path;
        if (!is_callable($migration)) {
            throw new RuntimeException("Migration {$name} did not return a callable");
        }
        $migration($pdo);

        $stmt = $pdo->prepare("INSERT INTO schema_migrations (filename) VALUES (?)");
        $stmt->execute([$name]);
        $pdo->commit();

        echo "OK\n";
        $ran++;
    } catch (Throwable $e) {
        $pdo->rollBack();
        echo "FAIL\n";
        fwrite(STDERR, "Migration {$name} failed: " . $e->getMessage() . "\n");
        exit(1);
    }
}

if ($ran === 0) {
    echo "No pending migrations.\n";
} else {
    echo "Applied {$ran} migration(s).\n";
}
