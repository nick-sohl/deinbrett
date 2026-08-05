<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Helper\Csrf;

class SizeController extends AdminController
{
    public function __construct(AuthService $auth, private SqliteRepository $repo)
    {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $items = $this->repo->query("SELECT * FROM sizes ORDER BY sort_order, base_price");
        $this->render('options/sizes/index', [
            'pageTitle' => 'Grössen',
            'activeNav' => 'sizes',
            'adminView' => 'options/sizes/index',
            'items'     => $items,
        ]);
    }

    public function create(): void { $this->renderForm(null, [], []); }

    public function store(): void
    {
        Csrf::verify();
        try {
            $data = $this->extractPost();
            $this->repo->execute(
                "INSERT INTO sizes (key, label, length_mm, width_mm, height_mm, description, base_price, sort_order, active)
                 VALUES (:key, :label, :length_mm, :width_mm, :height_mm, :description, :base_price, :sort_order, :active)",
                $data
            );
            $this->flash('success', 'Grösse angelegt.');
            $this->redirect('/admin/options/sizes');
        } catch (\Throwable $e) {
            $this->renderForm(null, $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function edit(array $params): void
    {
        $this->renderForm($this->findOr404((int) $params['id']), [], []);
    }

    public function update(array $params): void
    {
        Csrf::verify();
        $id = (int) $params['id'];
        try {
            $data = $this->extractPost();
            $data[':id'] = $id;
            $this->repo->execute(
                "UPDATE sizes SET key = :key, label = :label, length_mm = :length_mm, width_mm = :width_mm,
                     height_mm = :height_mm, description = :description, base_price = :base_price,
                     sort_order = :sort_order, active = :active
                 WHERE id = :id",
                $data
            );
            $this->flash('success', 'Grösse aktualisiert.');
            $this->redirect('/admin/options/sizes');
        } catch (\Throwable $e) {
            $this->renderForm($this->findOr404($id), $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function delete(array $params): void
    {
        Csrf::verify();
        $this->repo->execute("DELETE FROM sizes WHERE id = :id", [':id' => (int) $params['id']]);
        $this->flash('success', 'Grösse gelöscht.');
        $this->redirect('/admin/options/sizes');
    }

    private function findOr404(int $id): array
    {
        $rows = $this->repo->query("SELECT * FROM sizes WHERE id = :id", [':id' => $id]);
        if (empty($rows)) { http_response_code(404); echo 'Nicht gefunden'; exit; }
        return $rows[0];
    }

    private function extractPost(): array
    {
        return [
            ':key'         => trim($_POST['key'] ?? ''),
            ':label'       => trim($_POST['label'] ?? ''),
            ':length_mm'   => (int) ($_POST['length_mm'] ?? 0),
            ':width_mm'    => (int) ($_POST['width_mm'] ?? 0),
            ':height_mm'   => (int) ($_POST['height_mm'] ?? 0),
            ':description' => trim($_POST['description'] ?? ''),
            ':base_price'  => (float) ($_POST['base_price'] ?? 0),
            ':sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            ':active'      => !empty($_POST['active']) ? 1 : 0,
        ];
    }

    private function renderForm(?array $item, array $old, array $errors): void
    {
        $this->render('options/sizes/form', [
            'pageTitle' => $item ? 'Grösse bearbeiten' : 'Neue Grösse',
            'activeNav' => 'sizes',
            'adminView' => 'options/sizes/form',
            'item'      => $item,
            'old'       => $old,
            'errors'    => $errors,
        ]);
    }
}
