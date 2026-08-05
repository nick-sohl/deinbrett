<?php /** @var \DeinBrett\Domain\Entity\Order $order */ ?>
<h2 style="margin:0 0 12px; font-size:18px;">Zahlung erhalten – vielen Dank!</h2>
<p>Hallo <?= htmlspecialchars($order->fullName()) ?>,</p>
<p>wir haben deine Zahlung für die Bestellung <strong><?= htmlspecialchars($order->reference) ?></strong> erhalten.</p>
<p>Deine Bretter werden nun handgefertigt. Die Lieferzeit beträgt 4–6 Wochen. Sobald wir versenden, meldest du dich per E-Mail.</p>
<p style="margin-top:20px;">Bei Fragen antworte einfach auf diese E-Mail.<br>Dein DeinBrett-Team</p>
