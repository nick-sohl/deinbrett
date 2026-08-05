<?php /** @var \DeinBrett\Domain\Entity\Order $order */ ?>
<h2 style="margin:0 0 12px; font-size:18px;">Bestellung storniert</h2>
<p>Hallo <?= htmlspecialchars($order->fullName()) ?>,</p>
<p>deine Bestellung <strong><?= htmlspecialchars($order->reference) ?></strong> wurde storniert. Falls du bereits bezahlt hast, erhältst du den Betrag zurück.</p>
<p>Bei Fragen antworte einfach auf diese E-Mail.<br>Dein DeinBrett-Team</p>
