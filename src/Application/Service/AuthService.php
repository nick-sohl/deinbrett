<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Domain\Entity\User;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class AuthService
{
    private const SESSION_KEY = 'admin_user_id';

    public function __construct(private SqliteRepository $repo) {}

    public function login(string $email, string $password): bool
    {
        $email = trim(strtolower($email));
        if ($email === '' || $password === '') return false;

        $rows = $this->repo->query(
            "SELECT * FROM users WHERE lower(email) = :email LIMIT 1",
            [':email' => $email]
        );
        if (empty($rows)) return false;

        $row = $rows[0];
        if (!password_verify($password, $row['password'])) return false;

        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = (int) $row['id'];
        return true;
    }

    public function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    public function check(): bool
    {
        return !empty($_SESSION[self::SESSION_KEY]);
    }

    public function currentUser(): ?User
    {
        $id = $_SESSION[self::SESSION_KEY] ?? null;
        if (!$id) return null;
        return $this->repo->findById(User::class, (int) $id);
    }
}
