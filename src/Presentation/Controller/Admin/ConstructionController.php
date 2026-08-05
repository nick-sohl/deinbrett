<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Helper\Csrf;

class ConstructionController extends AdminController
{
    public function __construct(AuthService $auth, private SqliteRepository $repo)
    {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $items = $this->repo->query("SELECT * FROM constructions ORDER BY sort_order, name");
        $this->render('options/constructions/index', [
            'pageTitle' => 'Bauweisen',
            'activeNav' => 'constructions',
            'adminView' => 'options/constructions/index',
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
                "INSERT INTO constructions (key, name, description, price_add, sort_order, active)
                 VALUES (:key, :name, :description, :price_add, :sort_order, :active)",
                $data
            );
            $this->flash('success', 'Bauweise angelegt.');
            $this->redirect('/admin/options/constructions');
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
                "UPDATE constructions SET key = :key, name = :name, description = :description,
                     price_add = :price_add, sort_order = :sort_order, active = :active
                 WHERE id = :id",
                $data
            );
            $this->flash('success', 'Bauweise aktualisiert.');
            $this->redirect('/admin/options/constructions');
        } catch (\Throwable $e) {
            $this->renderForm($this->findOr404($id), $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function delete(array $params): void
    {
        Csrf::verify();
        $this->repo->execute("DELETE FROM constructions WHERE id = :id", [':id' => (int) $params['id']]);
        $this->flash('success', 'Bauweise gelöscht.');
        $this->redirect('/admin/options/constructions');
    }

    private function findOr404(int $id): array
    {
        $rows = $this->repo->query("SELECT * FROM constructions WHERE id = :id", [':id' => $id]);
        if (empty($rows)) { http_response_code(404); echo 'Nicht gefunden'; exit; }
        return $rows[0];
    }

    private function extractPost(): array
    {
        return [
            ':key'         => trim($_POST['key'] ?? ''),
            ':name'        => trim($_POST['name'] ?? ''),
            ':description' => trim($_POST['description'] ?? ''),
            ':price_add'   => (float) ($_POST['price_add'] ?? 0),
            ':sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            ':active'      => !empty($_POST['active']) ? 1 : 0,
        ];
    }

    private function renderForm(?array $item, array $old, array $errors): void
    {
        $this->render('options/constructions/form', [
            'pageTitle' => $item ? 'Bauweise bearbeiten' : 'Neue Bauweise',
            'activeNav' => 'constructions',
            'adminView' => 'options/constructions/form',
            'item'      => $item,
            'old'       => $old,
            'errors'    => $errors,
        ]);
    }
}
