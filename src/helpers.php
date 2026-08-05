<?php

use DeinBrett\Presentation\Helper\Csrf;

function csrf_field(): string
{
    return Csrf::field();
}

function format_price(int|float $amount): string
{
    return 'CHF ' . number_format($amount, 0, '.', "'");
}

function asset(string $path): string
{
    $file = __DIR__ . '/../public' . $path;
    $v = @filemtime($file) ?: time();
    return $path . '?v=' . $v;
}
