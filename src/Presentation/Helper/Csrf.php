<?php

namespace DeinBrett\Presentation\Helper;

class Csrf
{
    private const SESSION_KEY = 'csrf_token';

    public static function generate(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::SESSION_KEY];
    }

    public static function verify(): void
    {
        $token    = $_POST['csrf_token'] ?? '';
        $expected = $_SESSION[self::SESSION_KEY] ?? '';

        if (!$expected || !hash_equals($expected, $token)) {
            http_response_code(403);
            exit('Forbidden');
        }
    }

    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
