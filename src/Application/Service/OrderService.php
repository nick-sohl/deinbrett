<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Domain\Entity\Order;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class OrderService
{
    public function __construct(private SqliteRepository $repo) {}

    public function create(array $delivery, array $cartItems): Order
    {
        $subtotal  = array_sum(array_column($cartItems, 'price'));
        $shipping  = 0.0;
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
            $this->repo->execute(
                "INSERT INTO order_items (order_id, board_id, product_name, product_snapshot, quantity, unit_price, total)
                 VALUES (:oid, :bid, :name, :snapshot, 1, :price, :price)",
                [
                    ':oid'      => $orderId,
                    ':bid'      => $item['board_id'],
                    ':name'     => $item['name'],
                    ':snapshot' => json_encode($item),
                    ':price'    => $item['price'],
                ]
            );

            if ($item['board_id'] !== null) {
                $this->repo->execute(
                    "UPDATE boards SET stock = 0 WHERE id = :id",
                    [':id' => $item['board_id']]
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
            "UPDATE orders SET payment_status = 'paid', status = 'confirmed' WHERE reference = :ref",
            [':ref' => $reference]
        );

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

    public function itemsForOrder(int $orderId): array
    {
        return $this->repo->query(
            "SELECT * FROM order_items WHERE order_id = :id",
            [':id' => $orderId]
        );
    }

    private function generateReference(): string
    {
        return 'DB-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
    }
}
