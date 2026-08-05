<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class DashboardController extends AdminController
{
    public function __construct(AuthService $auth, private SqliteRepository $repo)
    {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $openOrders = (int) ($this->repo->query(
            "SELECT COUNT(*) AS c FROM orders WHERE status IN ('pending', 'paid')"
        )[0]['c'] ?? 0);

        $monthStart = date('Y-m-01 00:00:00');
        $monthRevenue = (float) ($this->repo->query(
            "SELECT COALESCE(SUM(total), 0) AS s FROM orders
             WHERE payment_status = 'paid' AND created_at >= :start",
            [':start' => $monthStart]
        )[0]['s'] ?? 0);

        $activeProducts = (int) ($this->repo->query(
            "SELECT COUNT(*) AS c FROM boards WHERE stock > 0"
        )[0]['c'] ?? 0);

        $lowStock = $this->repo->query(
            "SELECT id, name, stock FROM boards WHERE stock < 1 ORDER BY name"
        );

        $recentOrders = $this->repo->query(
            "SELECT id, reference, status, payment_status, first_name, last_name, total, created_at
             FROM orders ORDER BY created_at DESC LIMIT 8"
        );

        $this->render('dashboard', [
            'pageTitle'      => 'Dashboard',
            'activeNav'      => 'dashboard',
            'adminView'      => 'dashboard',
            'openOrders'     => $openOrders,
            'monthRevenue'   => $monthRevenue,
            'activeProducts' => $activeProducts,
            'lowStock'       => $lowStock,
            'recentOrders'   => $recentOrders,
        ]);
    }
}
