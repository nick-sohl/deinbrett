<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Domain\Constant\OrderStatus;
use DeinBrett\Domain\Entity\Order;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class OrderService
{
    public function __construct(private SqliteRepository $repo) {}

    public function create(array $delivery, array $cartItems): Order
    {
        $subtotal  = array_sum(array_column($cartItems, 'price'));
        $shipping  = $this->shippingCost();
        $total     = $subtotal + $shipping;
        $reference = $this->generateReference();

        $this->repo->execute(
            "INSERT INTO orders
                (reference, first_name, last_name, email, phone, address, city, zip, country, notes,
                 subtotal, shipping, total, payment_method, payment_status)
             VALUES
                (:ref, :fn, :ln, :email, :phone, :addr, :city, :zip, :country, :notes,
                 :subtotal, :shipping, :total, 'twint', 'pending')",
            [
                ':ref'      => $reference,
                ':fn'       => $delivery['first_name'],
                ':ln'       => $delivery['last_name'],
                ':email'    => $delivery['email'],
                ':phone'    => $delivery['phone']   ?? '',
                ':addr'     => $delivery['address'],
                ':city'     => $delivery['city'],
                ':zip'      => $delivery['zip'],
                ':country'  => $delivery['country'] ?? 'CH',
                ':notes'    => $delivery['notes']   ?? '',
                ':subtotal' => $subtotal,
                ':shipping' => $shipping,
                ':total'    => $total,
            ]
        );

        $orderId = $this->repo->lastInsertId();

        foreach ($cartItems as $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $this->repo->execute(
                "INSERT INTO order_items (order_id, board_id, product_name, product_snapshot, quantity, unit_price, total)
                 VALUES (:oid, :bid, :name, :snapshot, :qty, :price, :total)",
                [
                    ':oid'      => $orderId,
                    ':bid'      => $item['board_id'],
                    ':name'     => $item['name'],
                    ':snapshot' => json_encode($item),
                    ':qty'      => $qty,
                    ':price'    => $item['price'],
                    ':total'    => $item['price'] * $qty,
                ]
            );

            if ($item['board_id'] !== null) {
                $this->repo->execute(
                    "UPDATE boards SET stock = MAX(0, stock - :qty) WHERE id = :id",
                    [':id' => $item['board_id'], ':qty' => $qty]
                );
            }
        }

        /** @var Order $order */
        $order = $this->repo->findById(Order::class, $orderId);
        return $order;
    }

    public function markPaid(string $reference): ?Order
    {
        $this->repo->execute(
            "UPDATE orders SET payment_status = 'paid', status = 'paid' WHERE reference = :ref",
            [':ref' => $reference]
        );
        return $this->findByReference($reference);
    }

    public function findByReference(string $reference): ?Order
    {
        $rows = $this->repo->query(
            "SELECT * FROM orders WHERE reference = :ref LIMIT 1",
            [':ref' => $reference]
        );
        if (empty($rows)) return null;

        $order = new Order();
        foreach ($rows[0] as $k => $v) {
            if (property_exists($order, $k)) $order->$k = $v;
        }
        return $order;
    }

    public function find(int $id): ?Order
    {
        return $this->repo->findById(Order::class, $id);
    }

    public function itemsForOrder(int $orderId): array
    {
        return $this->repo->query(
            "SELECT * FROM order_items WHERE order_id = :id",
            [':id' => $orderId]
        );
    }

    public function list(array $filters = []): array
    {
        $where  = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'status = :status';
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['from'])) {
            $where[] = 'created_at >= :from';
            $params[':from'] = $filters['from'] . ' 00:00:00';
        }
        if (!empty($filters['to'])) {
            $where[] = 'created_at <= :to';
            $params[':to'] = $filters['to'] . ' 23:59:59';
        }
        if (!empty($filters['q'])) {
            $where[] = '(reference LIKE :q OR email LIKE :q OR last_name LIKE :q OR first_name LIKE :q)';
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        $sql = "SELECT * FROM orders";
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY created_at DESC';

        return $this->repo->query($sql, $params);
    }

    /**
     * @return array{order: Order, previousStatus: string, changed: bool}
     */
    public function updateStatus(int $orderId, string $newStatus): array
    {
        if (!in_array($newStatus, OrderStatus::ALL, true)) {
            throw new \InvalidArgumentException("Unbekannter Status: {$newStatus}");
        }

        $order = $this->find($orderId);
        if (!$order) {
            throw new \RuntimeException("Bestellung {$orderId} nicht gefunden.");
        }
        $previous = $order->status;
        if ($previous === $newStatus) {
            return ['order' => $order, 'previousStatus' => $previous, 'changed' => false];
        }

        if ($newStatus === OrderStatus::CANCELLED && $previous !== OrderStatus::CANCELLED) {
            $this->restoreStock($orderId);
        }

        $paymentStatus = $order->payment_status;
        if ($newStatus === OrderStatus::PAID || $newStatus === OrderStatus::SHIPPED || $newStatus === OrderStatus::COMPLETED) {
            $paymentStatus = 'paid';
        }
        if ($newStatus === OrderStatus::CANCELLED) {
            $paymentStatus = 'cancelled';
        }

        $this->repo->execute(
            "UPDATE orders SET status = :status, payment_status = :payment WHERE id = :id",
            [':status' => $newStatus, ':payment' => $paymentStatus, ':id' => $orderId]
        );

        $fresh = $this->find($orderId);
        return ['order' => $fresh, 'previousStatus' => $previous, 'changed' => true];
    }

    private function restoreStock(int $orderId): void
    {
        $items = $this->repo->query(
            "SELECT board_id, quantity FROM order_items WHERE order_id = :id AND board_id IS NOT NULL",
            [':id' => $orderId]
        );
        foreach ($items as $it) {
            $this->repo->execute(
                "UPDATE boards SET stock = stock + :qty WHERE id = :id",
                [':id' => (int) $it['board_id'], ':qty' => (int) $it['quantity']]
            );
        }
    }

    public function exportCsv(array $filters = []): string
    {
        $orders = $this->list($filters);

        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, [
            'Referenz', 'Status', 'Zahlungsstatus', 'Datum',
            'Vorname', 'Nachname', 'E-Mail', 'Telefon',
            'Adresse', 'PLZ', 'Ort', 'Land',
            'Zwischensumme', 'Versand', 'Total',
            'Zahlung', 'Notizen', 'Positionen',
        ]);

        foreach ($orders as $o) {
            $items    = $this->itemsForOrder((int) $o['id']);
            $itemsStr = implode(' | ', array_map(
                fn($it) => $it['quantity'] . '× ' . $it['product_name'] . ' (CHF ' . number_format((float) $it['total'], 2, '.', '') . ')',
                $items
            ));

            fputcsv($fh, [
                $o['reference'], $o['status'], $o['payment_status'], $o['created_at'],
                $o['first_name'], $o['last_name'], $o['email'], $o['phone'],
                $o['address'], $o['zip'], $o['city'], $o['country'],
                number_format((float) $o['subtotal'], 2, '.', ''),
                number_format((float) $o['shipping'], 2, '.', ''),
                number_format((float) $o['total'],    2, '.', ''),
                $o['payment_method'], $o['notes'],
                $itemsStr,
            ]);
        }

        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);
        return $csv;
    }

    private function shippingCost(): float
    {
        $rows = $this->repo->query("SELECT value FROM settings WHERE key = 'shipping_cost' LIMIT 1");
        if (empty($rows)) return 0.0;
        return (float) $rows[0]['value'];
    }

    private function generateReference(): string
    {
        return 'DB-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
    }
}
