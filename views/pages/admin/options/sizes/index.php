<?php /** @var array $items */ ?>
<div class="flex justify-between items-center mb-4">
  <p class="text-sm text-neutral-600"><?= count($items) ?> Grösse(n)</p>
  <a href="/admin/options/sizes/new" class="rounded bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">+ Neue Grösse</a>
</div>
<div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-neutral-50 text-xs uppercase text-neutral-500 text-left">
      <tr>
        <th class="px-4 py-3">Key</th>
        <th class="px-4 py-3">Label</th>
        <th class="px-4 py-3">Dimensionen (mm)</th>
        <th class="px-4 py-3 text-right">Basispreis</th>
        <th class="px-4 py-3">Aktiv</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
        <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">Noch keine Einträge.</td></tr>
      <?php else: foreach ($items as $it): ?>
        <tr class="border-t border-neutral-100">
          <td class="px-4 py-3 font-mono text-xs text-neutral-500"><?= htmlspecialchars($it['key']) ?></td>
          <td class="px-4 py-3"><a class="hover:underline" href="/admin/options/sizes/<?= (int)$it['id'] ?>/edit"><?= htmlspecialchars($it['label']) ?></a></td>
          <td class="px-4 py-3 text-neutral-600"><?= (int)$it['length_mm'] ?> × <?= (int)$it['width_mm'] ?> × <?= (int)$it['height_mm'] ?></td>
          <td class="px-4 py-3 text-right">CHF <?= number_format((float)$it['base_price'], 0, '.', "'") ?></td>
          <td class="px-4 py-3"><?= $it['active'] ? '✓' : '—' ?></td>
          <td class="px-4 py-3 text-right">
            <a href="/admin/options/sizes/<?= (int)$it['id'] ?>/edit" class="text-neutral-700 hover:text-neutral-900 mr-3">Bearbeiten</a>
            <form method="post" action="/admin/options/sizes/<?= (int)$it['id'] ?>/delete" class="inline" onsubmit="return confirm('Grösse löschen?')">
              <?= csrf_field() ?>
              <button type="submit" class="text-red-600 hover:text-red-800">Löschen</button>
            </form>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
