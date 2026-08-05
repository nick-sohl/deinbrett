<?php

namespace DeinBrett\Presentation\Controller\Admin;

use DeinBrett\Application\Service\AuthService;
use DeinBrett\Application\Service\OrderMailer;
use DeinBrett\Application\Service\OrderService;
use DeinBrett\Domain\Constant\OrderStatus;
use DeinBrett\Presentation\Helper\Csrf;

class OrderController extends AdminController
{
    public function __construct(
        AuthService $auth,
        private OrderService $orders,
        private OrderMailer $mailer,
    ) {
        parent::__construct($auth);
    }

    public function index(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'from'   => $_GET['from']   ?? '',
            'to'     => $_GET['to']     ?? '',
            'q'      => trim($_GET['q'] ?? ''),
        ];

        $this->render('orders/index', [
            'pageTitle' => 'Bestellungen',
            'activeNav' => 'orders',
            'adminView' => 'orders/index',
            'orders'    => $this->orders->list($filters),
            'filters'   => $filters,
            'statuses'  => OrderStatus::LABELS,
        ]);
    }

    public function show(array $params): void
    {
        $order = $this->orders->find((int) $params['id']);
        if (!$order) {
            http_response_code(404);
            echo 'Bestellung nicht gefunden';
            return;
        }
        $items = $this->orders->itemsForOrder($order->id);

        $this->render('orders/show', [
            'pageTitle' => 'Bestellung ' . $order->reference,
            'activeNav' => 'orders',
            'adminView' => 'orders/show',
            'order'     => $order,
            'items'     => $items,
            'statuses'  => OrderStatus::LABELS,
        ]);
    }

    public function updateStatus(array $params): void
    {
        Csrf::verify();
        $newStatus = $_POST['status'] ?? '';
        try {
            $result = $this->orders->updateStatus((int) $params['id'], $newStatus);
            if ($result['changed']) {
                $this->mailer->sendStatusUpdate($result['order'], $result['previousStatus']);
                $this->flash('success', 'Status aktualisiert und Kunde benachrichtigt.');
            } else {
                $this->flash('success', 'Status unverändert.');
            }
        } catch (\Throwable $e) {
            $this->flash('error', $e->getMessage());
        }
        $this->redirect('/admin/orders/' . (int) $params['id']);
    }

    public function exportCsv(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'from'   => $_GET['from']   ?? '',
            'to'     => $_GET['to']     ?? '',
            'q'      => trim($_GET['q'] ?? ''),
        ];
        $csv = $this->orders->exportCsv($filters);
        $filename = 'bestellungen-' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        echo $csv;
        exit;
    }
}
