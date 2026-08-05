<?php
/** @var ?array $item */
/** @var array $old */
/** @var array $errors */
$isEdit = $item !== null;
$item   = $item ?? [];
$val    = fn(string $k, $d = '') => htmlspecialchars((string) ($old[$k] ?? ($item[$k] ?? $d)));
$action = $isEdit ? '/admin/options/woods/' . (int)$item['id'] . '/update' : '/admin/options/woods';
?>
<div class="max-w-2xl">
  <?php if (!empty($errors['general'])): ?>
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"><?= htmlspecialchars($errors['general']) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= $action ?>" class="space-y-4 bg-white rounded-lg border border-neutral-200 p-6">
    <?= csrf_field() ?>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Key (URL-safe) *</label>
        <input required name="key" value="<?= $val('key') ?>" class="w-full font-mono rounded border border-neutral-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Name *</label>
        <input required name="name" value="<?= $val('name') ?>" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
      </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div><label class="block text-sm font-medium mb-1">Farbe</label><input name="color" value="<?= $val('color') ?>" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm"></div>
      <div><label class="block text-sm font-medium mb-1">Maserung</label><input name="grain" value="<?= $val('grain') ?>" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm"></div>
      <div><label class="block text-sm font-medium mb-1">Härte</label><input name="hardness" value="<?= $val('hardness') ?>" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm"></div>
      <div><label class="block text-sm font-medium mb-1">Besonderheiten</label><input name="features" value="<?= $val('features') ?>" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm"></div>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Beschreibung</label>
      <textarea name="description" rows="3" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm"><?= $val('description') ?></textarea>
    </div>
    <div class="grid grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Aufpreis (CHF)</label>
        <input name="price_add" type="number" step="0.01" value="<?= $val('price_add', 0) ?>" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Sortierung</label>
        <input name="sort_order" type="number" value="<?= $val('sort_order', 0) ?>" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
      </div>
      <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm">
          <?php $active = $old['active'] ?? ($item['active'] ?? 1); ?>
          <input type="checkbox" name="active" value="1" <?= $active ? 'checked' : '' ?>> Aktiv
        </label>
      </div>
    </div>
    <div class="flex gap-3 pt-3 border-t border-neutral-100">
      <button type="submit" class="rounded bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800"><?= $isEdit ? 'Speichern' : 'Anlegen' ?></button>
      <a href="/admin/options/woods" class="text-sm text-neutral-500 hover:text-neutral-900 self-center">Abbrechen</a>
    </div>
  </form>
</div>
