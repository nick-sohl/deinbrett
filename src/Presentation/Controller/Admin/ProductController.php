<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Application\Service\ConfiguratorService;
use DeinBrett\Application\Service\ProductService;
use DeinBrett\Presentation\Helper\Csrf;

class ProductController extends AdminController
{
    public function __construct(
        AuthService $auth,
        private ProductService $products,
        private ConfiguratorService $configurator,
    ) {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $this->render('products/index', [
            'pageTitle' => 'Produkte',
            'activeNav' => 'products',
            'adminView' => 'products/index',
            'products'  => $this->products->list(),
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
            $this->products->create($data, $_FILES['image'] ?? []);
            $this->flash('success', 'Produkt angelegt.');
            $this->redirect('/admin/products');
        } catch (\Throwable $e) {
            $this->renderForm(null, $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function edit(array $params): void
    {
        $product = $this->products->find((int) $params['id']);
        if (!$product) {
            http_response_code(404);
            echo 'Produkt nicht gefunden';
            return;
        }
        $this->renderForm($product, [], []);
    }

    public function update(array $params): void
    {
        Csrf::verify();
        $id = (int) $params['id'];
        try {
            $data = $this->extractPost();
            $this->products->update($id, $data, $_FILES['image'] ?? []);
            $this->flash('success', 'Produkt aktualisiert.');
            $this->redirect('/admin/products');
        } catch (\Throwable $e) {
            $product = $this->products->find($id);
            $this->renderForm($product, $_POST, ['general' => $e->getMessage()]);
        }
    }

    public function delete(array $params): void
    {
        Csrf::verify();
        $this->products->delete((int) $params['id']);
        $this->flash('success', 'Produkt gelöscht.');
        $this->redirect('/admin/products');
    }

    private function extractPost(): array
    {
        return [
            'name'         => trim($_POST['name'] ?? ''),
            'tagline'      => trim($_POST['tagline'] ?? ''),
            'description'  => trim($_POST['description'] ?? ''),
            'wood_type'    => $_POST['wood_type'] ?? 'eiche',
            'construction' => $_POST['construction'] ?? 'stirnholz',
            'size'         => $_POST['size'] ?? 'L',
            'extras'       => (array) ($_POST['extras'] ?? []),
            'price'        => (float) ($_POST['price'] ?? 0),
            'stock'        => (int) ($_POST['stock'] ?? 1),
            'featured'     => !empty($_POST['featured']),
        ];
    }

    private function renderForm($product, array $old, array $errors): void
    {
        $this->render('products/form', [
            'pageTitle'    => $product ? 'Produkt bearbeiten' : 'Neues Produkt',
            'activeNav'    => 'products',
            'adminView'    => 'products/form',
            'product'      => $product,
            'old'          => $old,
            'errors'       => $errors,
            'woodTypes'    => $this->configurator->woodTypes(),
            'sizes'        => $this->configurator->sizes(),
            'constructions'=> $this->configurator->constructions(),
            'extras'       => $this->configurator->extras(),
        ]);
    }
}
