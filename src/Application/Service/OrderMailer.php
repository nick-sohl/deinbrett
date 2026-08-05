<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Domain\Constant\OrderStatus;
use DeinBrett\Domain\Entity\Order;
use DeinBrett\Infrastructure\Adapter\MailAdapter;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class OrderMailer
{
    public function __construct(
        private MailAdapter $mail,
        private SqliteRepository $repo,
        private string $adminEmail,
    ) {}

    public function sendNewOrderNotification(Order $order): void
    {
        $items = $this->repo->query(
            "SELECT * FROM order_items WHERE order_id = :id",
            [':id' => $order->id]
        );
        $body = $this->renderTemplate('new_order_admin', [
            'order' => $order,
            'items' => $items,
        ]);
        $this->mail->send($this->adminEmail, "Neue Bestellung {$order->reference} – {$order->fullName()}", $body);
    }

    public function sendStatusUpdate(Order $order, string $previousStatus): void
    {
        $template = match ($order->status) {
            OrderStatus::PAID      => 'order_paid',
            OrderStatus::SHIPPED   => 'order_shipped',
            OrderStatus::CANCELLED => 'order_cancelled',
            OrderStatus::COMPLETED => 'order_completed',
            default                => null,
        };
        if ($template === null) return;

        $items = $this->repo->query(
            "SELECT * FROM order_items WHERE order_id = :id",
            [':id' => $order->id]
        );

        $subjects = [
            'order_paid'      => "Zahlung erhalten – Bestellung {$order->reference}",
            'order_shipped'   => "Deine Bestellung {$order->reference} ist unterwegs",
            'order_cancelled' => "Bestellung {$order->reference} storniert",
            'order_completed' => "Bestellung {$order->reference} abgeschlossen",
        ];

        $body = $this->renderTemplate($template, [
            'order'          => $order,
            'items'          => $items,
            'previousStatus' => $previousStatus,
        ]);
        $this->mail->send($order->email, $subjects[$template], $body, $this->adminEmail);
    }

    private function renderTemplate(string $name, array $data): string
    {
        $file = __DIR__ . '/../../../views/emails/' . $name . '.php';
        if (!is_file($file)) {
            return '<p>Template fehlt: ' . htmlspecialchars($name) . '</p>';
        }
        extract($data);
        ob_start();
        include __DIR__ . '/../../../views/emails/layout.php';
        return (string) ob_get_clean();
    }
}
