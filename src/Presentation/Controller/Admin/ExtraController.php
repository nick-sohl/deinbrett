<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Helper\Csrf;

class ExtraController extends AdminController
{
    public function __construct(AuthService $auth, private SqliteRepository $repo)
    {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $items = $this->repo->query("SELECT * FROM extras ORDER BY sort_order, name");
        $this->render('options/extras/index', [
            'pageTitle' => 'Extras',
            'activeNav' => 'extras',
            'adminView' => 'options/extras/index',
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
                "INSERT INTO extras (key, name, description, category, category_label, exclusive, price, sort_order, active)
                 VALUES (:key, :name, :description, :category, :category_label, :exclusive, :price, :sort_order, :active)",
                $data
            );
            $this->flash('success', 'Extra angelegt.');
            $this->redirect('/admin/options/extras');
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
                "UPDATE extras SET key = :key, name = :name, description = :description, category = :category,
                     category_label = :category_label, exclusive = :exclusive, price = :price,
                     sort_order = :sort_order, active = :active
                 WHERE id = :id",
                $data
            );
            $this->flash('success', 'Extra aktualisiert.');
            $this->redirect('/admin/options/extras');
        } catch (\Throwable $e) {
            $this->renderForm($this->findOr404($id), $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function delete(array $params): void
    {
        Csrf::verify();
        $this->repo->execute("DELETE FROM extras WHERE id = :id", [':id' => (int) $params['id']]);
        $this->flash('success', 'Extra gelöscht.');
        $this->redirect('/admin/options/extras');
    }

    private function findOr404(int $id): array
    {
        $rows = $this->repo->query("SELECT * FROM extras WHERE id = :id", [':id' => $id]);
        if (empty($rows)) { http_response_code(404); echo 'Nicht gefunden'; exit; }
        return $rows[0];
    }

    private function extractPost(): array
    {
        return [
            ':key'            => trim($_POST['key'] ?? ''),
            ':name'           => trim($_POST['name'] ?? ''),
            ':description'    => trim($_POST['description'] ?? ''),
            ':category'       => trim($_POST['category'] ?? ''),
            ':category_label' => trim($_POST['category_label'] ?? ''),
            ':exclusive'      => !empty($_POST['exclusive']) ? 1 : 0,
            ':price'          => (float) ($_POST['price'] ?? 0),
            ':sort_order'     => (int) ($_POST['sort_order'] ?? 0),
            ':active'         => !empty($_POST['active']) ? 1 : 0,
        ];
    }

    private function renderForm(?array $item, array $old, array $errors): void
    {
        $this->render('options/extras/form', [
            'pageTitle' => $item ? 'Extra bearbeiten' : 'Neues Extra',
            'activeNav' => 'extras',
            'adminView' => 'options/extras/form',
            'item'      => $item,
            'old'       => $old,
            'errors'    => $errors,
        ]);
    }
}
