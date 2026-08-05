<?php
/**
 * @var int   $openOrders
 * @var float $monthRevenue
 * @var int   $activeProducts
 * @var array $lowStock
 * @var array $recentOrders
 */
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
  <div class="bg-white rounded-lg border border-neutral-200 p-5">
    <div class="text-xs uppercase tracking-wide text-neutral-500 mb-1">Offene Bestellungen</div>
    <div class="text-3xl font-semibold"><?= $openOrders ?></div>
  </div>
  <div class="bg-white rounded-lg border border-neutral-200 p-5">
    <div class="text-xs uppercase tracking-wide text-neutral-500 mb-1">Umsatz (Monat)</div>
    <div class="text-3xl font-semibold">CHF <?= number_format($monthRevenue, 0, '.', "'") ?></div>
  </div>
  <div class="bg-white rounded-lg border border-neutral-200 p-5">
    <div class="text-xs uppercase tracking-wide text-neutral-500 mb-1">Verfügbare Produkte</div>
    <div class="text-3xl font-semibold"><?= $activeProducts ?></div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <section class="bg-white rounded-lg border border-neutral-200">
    <header class="px-5 py-3 border-b border-neutral-200 flex items-center justify-between">
      <h2 class="text-sm font-semibold">Letzte Bestellungen</h2>
      <a href="/admin/orders" class="text-xs text-neutral-500 hover:text-neutral-900">Alle →</a>
    </header>
    <div class="p-4">
      <?php if (empty($recentOrders)): ?>
        <p class="text-sm text-neutral-500">Noch keine Bestellungen.</p>
      <?php else: ?>
        <table class="w-full text-sm">
          <thead class="text-left text-xs text-neutral-500 uppercase">
            <tr>
              <th class="pb-2">Ref</th>
              <th class="pb-2">Kunde</th>
              <th class="pb-2">Status</th>
              <th class="pb-2 text-right">Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentOrders as $o): ?>
              <tr class="border-t border-neutral-100">
                <td class="py-2"><a href="/admin/orders/<?= (int)$o['id'] ?>" class="text-neutral-900 hover:underline"><?= htmlspecialchars($o['reference']) ?></a></td>
                <td class="py-2 text-neutral-600"><?= htmlspecialchars(trim($o['first_name'] . ' ' . $o['last_name'])) ?></td>
                <td class="py-2 text-neutral-600"><?= htmlspecialchars($o['status']) ?></td>
                <td class="py-2 text-right">CHF <?= number_format((float)$o['total'], 0, '.', "'") ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </section>

  <section class="bg-white rounded-lg border border-neutral-200">
    <header class="px-5 py-3 border-b border-neutral-200 flex items-center justify-between">
      <h2 class="text-sm font-semibold">Nicht verfügbar (Bestand)</h2>
      <a href="/admin/products" class="text-xs text-neutral-500 hover:text-neutral-900">Alle Produkte →</a>
    </header>
    <div class="p-4">
      <?php if (empty($lowStock)): ?>
        <p class="text-sm text-neutral-500">Alle Produkte auf Lager.</p>
      <?php else: ?>
        <ul class="text-sm divide-y divide-neutral-100">
          <?php foreach ($lowStock as $p): ?>
            <li class="py-2 flex justify-between">
              <a href="/admin/products/<?= (int)$p['id'] ?>/edit" class="hover:underline"><?= htmlspecialchars($p['name']) ?></a>
              <span class="text-red-600">Bestand: <?= (int)$p['stock'] ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </section>
</div>
