<?php /** @var \DeinBrett\Domain\Entity\Order $order */ ?>
<h2 style="margin:0 0 12px; font-size:18px;">Deine Bestellung ist unterwegs</h2>
<p>Hallo <?= htmlspecialchars($order->fullName()) ?>,</p>
<p>gute Nachricht: Deine Bestellung <strong><?= htmlspecialchars($order->reference) ?></strong> ist unterwegs zu dir.</p>
<p>Wir freuen uns, dass du bald mit deinem Brett kochen wirst. Solltest du Fragen zur Pflege haben, antworte einfach auf diese E-Mail.</p>
<p style="margin-top:20px;">Dein DeinBrett-Team</p>
