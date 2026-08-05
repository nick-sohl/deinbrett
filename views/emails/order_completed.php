<?php /** @var \DeinBrett\Domain\Entity\Order $order */ ?>
<h2 style="margin:0 0 12px; font-size:18px;">Bestellung abgeschlossen</h2>
<p>Hallo <?= htmlspecialchars($order->fullName()) ?>,</p>
<p>deine Bestellung <strong><?= htmlspecialchars($order->reference) ?></strong> ist nun abgeschlossen. Wir hoffen, du hast Freude an deinem Brett.</p>
<p>Falls du Feedback teilen oder ein weiteres Brett bestellen möchtest — wir freuen uns von dir zu hören.</p>
<p style="margin-top:20px;">Dein DeinBrett-Team</p>
