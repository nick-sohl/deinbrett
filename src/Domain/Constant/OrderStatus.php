<?php

namespace DeinBrett\Domain\Constant;

class OrderStatus
{
    public const PENDING   = 'pending';
    public const PAID      = 'paid';
    public const SHIPPED   = 'shipped';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';
    public const CONFIRMED = 'confirmed';

    public const ALL = [
        self::PENDING,
        self::PAID,
        self::SHIPPED,
        self::COMPLETED,
        self::CANCELLED,
        self::CONFIRMED,
    ];

    public const LABELS = [
        self::PENDING   => 'Ausstehend',
        self::PAID      => 'Bezahlt',
        self::SHIPPED   => 'Versendet',
        self::COMPLETED => 'Abgeschlossen',
        self::CANCELLED => 'Storniert',
        self::CONFIRMED => 'Bestätigt',
    ];

    public static function label(string $status): string
    {
        return self::LABELS[$status] ?? $status;
    }
}
