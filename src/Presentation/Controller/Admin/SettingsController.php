<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Application\Service\SettingsService;
use DeinBrett\Presentation\Helper\Csrf;

class SettingsController extends AdminController
{
    public function __construct(AuthService $auth, private SettingsService $settings)
    {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $this->render('settings/index', [
            'pageTitle' => 'Einstellungen',
            'activeNav' => 'settings',
            'adminView' => 'settings/index',
            'settings'  => $this->settings->all(),
        ]);
    }

    public function update(): void
    {
        Csrf::verify();
        $shipping = (float) ($_POST['shipping_cost'] ?? 0);
        $admin    = trim($_POST['admin_email'] ?? '');
        $this->settings->set('shipping_cost', (string) $shipping);
        if ($admin !== '') $this->settings->set('admin_email', $admin);
        $this->flash('success', 'Einstellungen gespeichert.');
        $this->redirect('/admin/settings');
    }
}
