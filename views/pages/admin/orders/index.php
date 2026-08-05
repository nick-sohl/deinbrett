<?php
/** @var array $orders */
/** @var array $filters */
/** @var array $statuses */
$qs = http_build_query(array_filter($filters));
?>
<form method="get" action="/admin/orders" class="mb-4 flex flex-wrap items-end gap-3 bg-white rounded-lg border border-neutral-200 p-4">
  <div>
    <label class="block text-xs uppercase text-neutral-500 mb-1">Status</label>
    <select name="status" class="rounded border border-neutral-300 px-2 py-1 text-sm">
      <option value="">Alle</option>
      <?php foreach ($statuses as $s => $label): ?>
        <option value="<?= htmlspecialchars($s) ?>" <?= $filters['status'] === $s ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label class="block text-xs uppercase text-neutral-500 mb-1">Von</label>
    <input type="date" name="from" value="<?= htmlspecialchars($filters['from']) ?>" class="rounded border border-neutral-300 px-2 py-1 text-sm">
  </div>
  <div>
    <label class="block text-xs uppercase text-neutral-500 mb-1">Bis</label>
    <input type="date" name="to" value="<?= htmlspecialchars($filters['to']) ?>" class="rounded border border-neutral-300 px-2 py-1 text-sm">
  </div>
  <div class="flex-1 min-w-[180px]">
    <label class="block text-xs uppercase text-neutral-500 mb-1">Suche</label>
    <input type="text" name="q" value="<?= htmlspecialchars($filters['q']) ?>" placeholder="Ref, Email, Name" class="w-full rounded border border-neutral-300 px-2 py-1 text-sm">
  </div>
  <button type="submit" class="rounded bg-neutral-900 px-3 py-1.5 text-sm text-white hover:bg-neutral-800">Filtern</button>
  <a href="/admin/orders/export.csv?<?= htmlspecialchars($qs) ?>" class="rounded border border-neutral-300 px-3 py-1.5 text-sm hover:bg-neutral-100">CSV Export</a>
</form>

<div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-neutral-50 text-xs uppercase text-neutral-500 text-left">
      <tr>
        <th class="px-4 py-3">Referenz</th>
        <th class="px-4 py-3">Datum</th>
        <th class="px-4 py-3">Kunde</th>
        <th class="px-4 py-3">Status</th>
        <th class="px-4 py-3">Zahlung</th>
        <th class="px-4 py-3 text-right">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($orders)): ?>
        <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Keine Bestellungen gefunden.</td></tr>
      <?php else: foreach ($orders as $o): ?>
        <tr class="border-t border-neutral-100 hover:bg-neutral-50">
          <td class="px-4 py-3"><a class="font-medium hover:underline" href="/admin/orders/<?= (int)$o['id'] ?>"><?= htmlspecialchars($o['reference']) ?></a></td>
          <td class="px-4 py-3 text-neutral-600"><?= htmlspecialchars($o['created_at']) ?></td>
          <td class="px-4 py-3">
            <?= htmlspecialchars(trim($o['first_name'] . ' ' . $o['last_name'])) ?>
            <div class="text-xs text-neutral-500"><?= htmlspecialchars($o['email']) ?></div>
          </td>
          <td class="px-4 py-3"><?= htmlspecialchars($statuses[$o['status']] ?? $o['status']) ?></td>
          <td class="px-4 py-3 text-neutral-600"><?= htmlspecialchars($o['payment_status']) ?></td>
          <td class="px-4 py-3 text-right">CHF <?= number_format((float)$o['total'], 0, '.', "'") ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
