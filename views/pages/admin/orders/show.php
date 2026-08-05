<?php
/** @var \DeinBrett\Domain\Entity\Order $order */
/** @var array $items */
/** @var array $statuses */
?>
<div class="mb-4">
  <a href="/admin/orders" class="text-sm text-neutral-500 hover:text-neutral-900">← Zurück zur Übersicht</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 space-y-6">
    <section class="bg-white rounded-lg border border-neutral-200 p-6">
      <h2 class="text-sm font-semibold mb-3">Positionen</h2>
      <table class="w-full text-sm">
        <thead class="text-xs uppercase text-neutral-500 text-left">
          <tr><th class="pb-2">Produkt</th><th class="pb-2 text-center">Menge</th><th class="pb-2 text-right">Einzelpreis</th><th class="pb-2 text-right">Total</th></tr>
        </thead>
        <tbody>
        <?php foreach ($items as $it): ?>
          <tr class="border-t border-neutral-100">
            <td class="py-2"><?= htmlspecialchars($it['product_name']) ?></td>
            <td class="py-2 text-center"><?= (int)$it['quantity'] ?></td>
            <td class="py-2 text-right">CHF <?= number_format((float)$it['unit_price'], 2, '.', "'") ?></td>
            <td class="py-2 text-right">CHF <?= number_format((float)$it['total'], 2, '.', "'") ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="text-sm">
          <tr class="border-t border-neutral-200">
            <td colspan="3" class="pt-3 text-right text-neutral-600">Zwischensumme</td>
            <td class="pt-3 text-right">CHF <?= number_format($order->subtotal, 2, '.', "'") ?></td>
          </tr>
          <tr>
            <td colspan="3" class="pt-1 text-right text-neutral-600">Versand</td>
            <td class="pt-1 text-right">CHF <?= number_format($order->shipping, 2, '.', "'") ?></td>
          </tr>
          <tr class="font-semibold">
            <td colspan="3" class="pt-1 text-right">Total</td>
            <td class="pt-1 text-right">CHF <?= number_format($order->total, 2, '.', "'") ?></td>
          </tr>
        </tfoot>
      </table>
      <?php if ($order->notes): ?>
        <div class="mt-4 pt-4 border-t border-neutral-100">
          <div class="text-xs uppercase text-neutral-500 mb-1">Notizen</div>
          <p class="text-sm text-neutral-700"><?= nl2br(htmlspecialchars($order->notes)) ?></p>
        </div>
      <?php endif; ?>
    </section>
  </div>

  <div class="space-y-6">
    <section class="bg-white rounded-lg border border-neutral-200 p-6">
      <h2 class="text-sm font-semibold mb-3">Status</h2>
      <p class="text-sm text-neutral-600 mb-2">Aktuell: <strong><?= htmlspecialchars($statuses[$order->status] ?? $order->status) ?></strong></p>
      <p class="text-xs text-neutral-500 mb-4">Zahlung: <?= htmlspecialchars($order->payment_status) ?></p>
      <form method="post" action="/admin/orders/<?= $order->id ?>/status" class="space-y-2">
        <?= csrf_field() ?>
        <select name="status" class="w-full rounded border border-neutral-300 px-2 py-1.5 text-sm">
          <?php foreach ($statuses as $s => $label): ?>
            <option value="<?= htmlspecialchars($s) ?>" <?= $order->status === $s ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="w-full rounded bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Status ändern</button>
        <p class="text-xs text-neutral-500">Kunde wird per E-Mail benachrichtigt.</p>
      </form>
    </section>

    <section class="bg-white rounded-lg border border-neutral-200 p-6 text-sm">
      <h2 class="text-sm font-semibold mb-3">Kunde</h2>
      <div class="text-neutral-700">
        <div><?= htmlspecialchars($order->fullName()) ?></div>
        <div><a href="mailto:<?= htmlspecialchars($order->email) ?>" class="text-neutral-500 hover:underline"><?= htmlspecialchars($order->email) ?></a></div>
        <?php if ($order->phone): ?><div class="text-neutral-500"><?= htmlspecialchars($order->phone) ?></div><?php endif; ?>
      </div>
      <div class="mt-3 pt-3 border-t border-neutral-100 text-neutral-600">
        <div><?= htmlspecialchars($order->address) ?></div>
        <div><?= htmlspecialchars($order->zip . ' ' . $order->city) ?></div>
        <div><?= htmlspecialchars($order->country) ?></div>
      </div>
    </section>

    <section class="bg-white rounded-lg border border-neutral-200 p-6 text-sm text-neutral-600">
      <h2 class="text-sm font-semibold text-neutral-900 mb-3">Meta</h2>
      <div>Referenz: <span class="font-mono"><?= htmlspecialchars($order->reference) ?></span></div>
      <div>Erstellt: <?= htmlspecialchars($order->created_at) ?></div>
      <div>Zahlungsmethode: <?= htmlspecialchars($order->payment_method) ?></div>
    </section>
  </div>
</div>
