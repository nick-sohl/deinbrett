<?php /** @var \DeinBrett\Domain\Entity\Order $order */ /** @var array $items */ ?>
<h2 style="margin:0 0 12px; font-size:18px;">Neue Bestellung eingegangen</h2>
<p><strong>Referenz:</strong> <?= htmlspecialchars($order->reference) ?><br>
<strong>Kunde:</strong> <?= htmlspecialchars($order->fullName()) ?> (<?= htmlspecialchars($order->email) ?>)<br>
<strong>Adresse:</strong> <?= htmlspecialchars($order->address . ', ' . $order->zip . ' ' . $order->city . ', ' . $order->country) ?><br>
<?php if ($order->phone): ?><strong>Telefon:</strong> <?= htmlspecialchars($order->phone) ?><br><?php endif; ?>
<strong>Total:</strong> CHF <?= number_format($order->total, 2, '.', "'") ?></p>

<h3 style="margin:20px 0 8px; font-size:15px;">Positionen</h3>
<ul style="padding-left:18px; margin:0;">
  <?php foreach ($items as $it): ?>
    <li><?= (int)$it['quantity'] ?>× <?= htmlspecialchars($it['product_name']) ?> — CHF <?= number_format((float)$it['total'], 2, '.', "'") ?></li>
  <?php endforeach; ?>
</ul>

<?php if ($order->notes): ?>
  <h3 style="margin:20px 0 8px; font-size:15px;">Notizen des Kunden</h3>
  <p style="white-space:pre-wrap;"><?= htmlspecialchars($order->notes) ?></p>
<?php endif; ?>

<p style="margin-top:20px;"><a href="https://deinbrett.ch/admin/orders/<?= $order->id ?>" style="color:#111;">→ Bestellung im CMS öffnen</a></p>
