<?php

return function (PDO $pdo): void {
    $cols = $pdo->query("PRAGMA table_info(boards)")->fetchAll(PDO::FETCH_ASSOC);
    $hasImagePath = false;
    foreach ($cols as $c) {
        if ($c['name'] === 'image_path') {
            $hasImagePath = true;
            break;
        }
    }
    if (!$hasImagePath) {
        $pdo->exec("ALTER TABLE boards ADD COLUMN image_path TEXT NOT NULL DEFAULT ''");
    }
};
