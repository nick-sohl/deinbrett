<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;

abstract class AdminController
{
    public function __construct(protected AuthService $auth)
    {
        if (!$this->auth->check()) {
            header('Location: /admin/login');
            exit;
        }
    }

    protected function render(string $view, array $data = []): void
    {
        $data['currentUser'] = $this->auth->currentUser();
        $data['activeNav']   = $data['activeNav'] ?? '';
        extract($data);
        include __DIR__ . '/../../../../views/app/layouts/admin.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['admin_flash'] = ['type' => $type, 'message' => $message];
    }

    protected function consumeFlash(): ?array
    {
        $f = $_SESSION['admin_flash'] ?? null;
        unset($_SESSION['admin_flash']);
        return $f;
    }
}
