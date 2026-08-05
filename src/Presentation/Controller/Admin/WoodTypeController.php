<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Helper\Csrf;

class WoodTypeController extends AdminController
{
    public function __construct(AuthService $auth, private SqliteRepository $repo)
    {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $items = $this->repo->query("SELECT * FROM wood_types ORDER BY sort_order, name");
        $this->render('options/woods/index', [
            'pageTitle' => 'Holzarten',
            'activeNav' => 'woods',
            'adminView' => 'options/woods/index',
            'items'     => $items,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(null, [], []);
    }

    public function store(): void
    {
        Csrf::verify();
        try {
            $data = $this->extractPost();
            $this->repo->execute(
                "INSERT INTO wood_types (key, name, color, grain, hardness, features, description, price_add, sort_order, active)
                 VALUES (:key, :name, :color, :grain, :hardness, :features, :description, :price_add, :sort_order, :active)",
                $data
            );
            $this->flash('success', 'Holzart angelegt.');
            $this->redirect('/admin/options/woods');
        } catch (\Throwable $e) {
            $this->renderForm(null, $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function edit(array $params): void
    {
        $item = $this->findOr404((int) $params['id']);
        $this->renderForm($item, [], []);
    }

    public function update(array $params): void
    {
        Csrf::verify();
        $id = (int) $params['id'];
        try {
            $data = $this->extractPost();
            $data[':id'] = $id;
            $this->repo->execute(
                "UPDATE wood_types SET key = :key, name = :name, color = :color, grain = :grain, hardness = :hardness,
                     features = :features, description = :description, price_add = :price_add,
                     sort_order = :sort_order, active = :active
                 WHERE id = :id",
                $data
            );
            $this->flash('success', 'Holzart aktualisiert.');
            $this->redirect('/admin/options/woods');
        } catch (\Throwable $e) {
            $this->renderForm($this->findOr404($id), $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function delete(array $params): void
    {
        Csrf::verify();
        $this->repo->execute("DELETE FROM wood_types WHERE id = :id", [':id' => (int) $params['id']]);
        $this->flash('success', 'Holzart gelöscht.');
        $this->redirect('/admin/options/woods');
    }

    private function findOr404(int $id): array
    {
        $rows = $this->repo->query("SELECT * FROM wood_types WHERE id = :id", [':id' => $id]);
        if (empty($rows)) {
            http_response_code(404);
            echo 'Nicht gefunden';
            exit;
        }
        return $rows[0];
    }

    private function extractPost(): array
    {
        return [
            ':key'         => trim($_POST['key'] ?? ''),
            ':name'        => trim($_POST['name'] ?? ''),
            ':color'       => trim($_POST['color'] ?? ''),
            ':grain'       => trim($_POST['grain'] ?? ''),
            ':hardness'    => trim($_POST['hardness'] ?? ''),
            ':features'    => trim($_POST['features'] ?? ''),
            ':description' => trim($_POST['description'] ?? ''),
            ':price_add'   => (float) ($_POST['price_add'] ?? 0),
            ':sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            ':active'      => !empty($_POST['active']) ? 1 : 0,
        ];
    }

    private function renderForm(?array $item, array $old, array $errors): void
    {
        $this->render('options/woods/form', [
            'pageTitle' => $item ? 'Holzart bearbeiten' : 'Neue Holzart',
            'activeNav' => 'woods',
            'adminView' => 'options/woods/form',
            'item'      => $item,
            'old'       => $old,
            'errors'    => $errors,
        ]);
    }
}
