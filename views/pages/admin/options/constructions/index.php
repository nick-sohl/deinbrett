<?php /** @var array $items */ ?>
<div class="flex justify-between items-center mb-4">
  <p class="text-sm text-neutral-600"><?= count($items) ?> Bauweise(n)</p>
  <a href="/admin/options/constructions/new" class="rounded bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">+ Neue Bauweise</a>
</div>
<div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-neutral-50 text-xs uppercase text-neutral-500 text-left">
      <tr><th class="px-4 py-3">Key</th><th class="px-4 py-3">Name</th><th class="px-4 py-3 text-right">Aufpreis</th><th class="px-4 py-3">Aktiv</th><th class="px-4 py-3"></th></tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
        <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-500">Noch keine Einträge.</td></tr>
      <?php else: foreach ($items as $it): ?>
        <tr class="border-t border-neutral-100">
          <td class="px-4 py-3 font-mono text-xs text-neutral-500"><?= htmlspecialchars($it['key']) ?></td>
          <td class="px-4 py-3"><a class="hover:underline" href="/admin/options/constructions/<?= (int)$it['id'] ?>/edit"><?= htmlspecialchars($it['name']) ?></a></td>
          <td class="px-4 py-3 text-right">+ CHF <?= number_format((float)$it['price_add'], 0, '.', "'") ?></td>
          <td class="px-4 py-3"><?= $it['active'] ? '✓' : '—' ?></td>
          <td class="px-4 py-3 text-right">
            <a href="/admin/options/constructions/<?= (int)$it['id'] ?>/edit" class="text-neutral-700 hover:text-neutral-900 mr-3">Bearbeiten</a>
            <form method="post" action="/admin/options/constructions/<?= (int)$it['id'] ?>/delete" class="inline" onsubmit="return confirm('Bauweise löschen?')">
              <?= csrf_field() ?>
              <button type="submit" class="text-red-600 hover:text-red-800">Löschen</button>
            </form>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
