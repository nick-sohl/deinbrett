<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Presentation\Helper\Csrf;

class AuthController
{
    public function __construct(private AuthService $auth) {}

    public function showLogin(): void
    {
        if ($this->auth->check()) {
            header('Location: /admin');
            exit;
        }
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        include __DIR__ . '/../../../../views/pages/admin/login.php';
    }

    public function login(): void
    {
        Csrf::verify();
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        if (!$this->auth->login($email, $password)) {
            $_SESSION['login_error'] = 'Ungültige Zugangsdaten.';
            header('Location: /admin/login');
            exit;
        }

        header('Location: /admin');
        exit;
    }

    public function logout(): void
    {
        Csrf::verify();
        $this->auth->logout();
        header('Location: /admin/login');
        exit;
    }
}
