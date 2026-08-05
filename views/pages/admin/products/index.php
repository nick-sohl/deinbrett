<?php /** @var array $products */ ?>
<div class="flex justify-between items-center mb-4">
  <p class="text-sm text-neutral-600"><?= count($products) ?> Produkt(e)</p>
  <a href="/admin/products/new"
     class="inline-flex items-center rounded bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">
    + Neues Produkt
  </a>
</div>

<div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-neutral-50 text-xs uppercase text-neutral-500 text-left">
      <tr>
        <th class="px-4 py-3">Bild</th>
        <th class="px-4 py-3">Name</th>
        <th class="px-4 py-3">Holz / Grösse</th>
        <th class="px-4 py-3 text-right">Preis</th>
        <th class="px-4 py-3 text-right">Bestand</th>
        <th class="px-4 py-3">Featured</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($products)): ?>
        <tr><td colspan="7" class="px-4 py-8 text-center text-neutral-500">Noch keine Produkte.</td></tr>
      <?php else: ?>
        <?php foreach ($products as $p): ?>
          <tr class="border-t border-neutral-100">
            <td class="px-4 py-3">
              <?php if (!empty($p['image_path'])): ?>
                <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="" class="h-10 w-10 object-cover rounded">
              <?php else: ?>
                <div class="h-10 w-10 bg-neutral-100 rounded flex items-center justify-center text-neutral-400 text-xs">—</div>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3">
              <a href="/admin/products/<?= (int)$p['id'] ?>/edit" class="font-medium hover:underline"><?= htmlspecialchars($p['name']) ?></a>
              <div class="text-xs text-neutral-500"><?= htmlspecialchars($p['slug']) ?></div>
            </td>
            <td class="px-4 py-3 text-neutral-600"><?= htmlspecialchars($p['wood_type']) ?> · <?= htmlspecialchars($p['size']) ?></td>
            <td class="px-4 py-3 text-right">CHF <?= number_format((float)$p['price'], 0, '.', "'") ?></td>
            <td class="px-4 py-3 text-right <?= $p['stock'] < 1 ? 'text-red-600' : '' ?>"><?= (int)$p['stock'] ?></td>
            <td class="px-4 py-3"><?= $p['featured'] ? '★' : '' ?></td>
            <td class="px-4 py-3 text-right">
              <a href="/admin/products/<?= (int)$p['id'] ?>/edit" class="text-neutral-700 hover:text-neutral-900 mr-3">Bearbeiten</a>
              <form method="post" action="/admin/products/<?= (int)$p['id'] ?>/delete" class="inline"
                    onsubmit="return confirm('Produkt „<?= htmlspecialchars(addslashes($p['name'])) ?>“ wirklich löschen?')">
                <?= csrf_field() ?>
                <button type="submit" class="text-red-600 hover:text-red-800">Löschen</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
