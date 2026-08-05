<?php

namespace DeinBrett\Domain\Data;

use DeinBrett\Application\Service\ConfiguratorService;

/**
 * Backwards-compatible static facade over {@see ConfiguratorService}.
 * The CMS manages configurator options in the DB; this facade preserves the
 * static call-sites in views and controllers without a bigger refactor.
 */
class BoardData
{
    private static ?ConfiguratorService $service = null;

    public static function bind(ConfiguratorService $service): void
    {
        self::$service = $service;
    }

    private static function svc(): ConfiguratorService
    {
        if (self::$service === null) {
            self::$service = new ConfiguratorService(new \DeinBrett\Infrastructure\Adapter\SqliteRepository());
        }
        return self::$service;
    }

    public static function woodTypes(): array      { return self::svc()->woodTypes(); }
    public static function sizes(): array          { return self::svc()->sizes(); }
    public static function constructions(): array  { return self::svc()->constructions(); }
    public static function extras(): array         { return self::svc()->extras(); }
    public static function extrasGrouped(): array  { return self::svc()->extrasGrouped(); }

    public static function calculatePrice(string $woodId, string $sizeId, string $constructionId, array $extraIds): array
    {
        return self::svc()->calculatePrice($woodId, $sizeId, $constructionId, $extraIds);
    }
}
