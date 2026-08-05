<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class SettingsService
{
    public function __construct(private SqliteRepository $repo) {}

    public function all(): array
    {
        $rows = $this->repo->query("SELECT key, value FROM settings");
        $out  = [];
        foreach ($rows as $r) $out[$r['key']] = $r['value'];
        return $out;
    }

    public function get(string $key, string $default = ''): string
    {
        $rows = $this->repo->query("SELECT value FROM settings WHERE key = :k LIMIT 1", [':k' => $key]);
        return $rows[0]['value'] ?? $default;
    }

    public function set(string $key, string $value): void
    {
        $existing = $this->repo->query("SELECT 1 FROM settings WHERE key = :k LIMIT 1", [':k' => $key]);
        if (!empty($existing)) {
            $this->repo->execute("UPDATE settings SET value = :v WHERE key = :k", [':k' => $key, ':v' => $value]);
        } else {
            $this->repo->execute("INSERT INTO settings (key, value) VALUES (:k, :v)", [':k' => $key, ':v' => $value]);
        }
    }
}
