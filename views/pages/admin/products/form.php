<?php
/**
 * @var ?\DeinBrett\Domain\Entity\Board $product
 * @var array $old
 * @var array $errors
 * @var array $woodTypes
 * @var array $sizes
 * @var array $constructions
 * @var array $extras
 */
$isEdit = $product !== null;

function val(array $old, ?object $product, string $field, mixed $default = '')
{
    if (array_key_exists($field, $old)) return $old[$field];
    if ($product) return $product->$field ?? $default;
    return $default;
}

$currentExtras = [];
if (!empty($old['extras'])) $currentExtras = (array) $old['extras'];
elseif ($product) $currentExtras = $product->extrasArray();

$action = $isEdit ? '/admin/products/' . $product->id . '/update' : '/admin/products';
?>
<div class="max-w-3xl">
  <?php if (!empty($errors['general'])): ?>
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
      <?= htmlspecialchars($errors['general']) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= $action ?>" enctype="multipart/form-data" class="space-y-5 bg-white rounded-lg border border-neutral-200 p-6">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Name *</label>
        <input required name="name" type="text" value="<?= htmlspecialchars(val($old, $product, 'name')) ?>"
               class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Preis (CHF) *</label>
        <input required name="price" type="number" step="0.01" min="0" value="<?= htmlspecialchars((string) val($old, $product, 'price', 0)) ?>"
               class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Tagline</label>
      <input name="tagline" type="text" value="<?= htmlspecialchars(val($old, $product, 'tagline')) ?>"
             class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Beschreibung</label>
      <textarea name="description" rows="4"
                class="w-full rounded border border-neutral-300 px-3 py-2 text-sm"><?= htmlspecialchars(val($old, $product, 'description')) ?></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Holzart</label>
        <select name="wood_type" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
          <?php $cur = val($old, $product, 'wood_type', 'eiche'); ?>
          <?php foreach ($woodTypes as $w): ?>
            <option value="<?= htmlspecialchars($w['id']) ?>" <?= $cur === $w['id'] ? 'selected' : '' ?>><?= htmlspecialchars($w['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Bauweise</label>
        <select name="construction" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
          <?php $cur = val($old, $product, 'construction', 'stirnholz'); ?>
          <?php foreach ($constructions as $c): ?>
            <option value="<?= htmlspecialchars($c['id']) ?>" <?= $cur === $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Grösse</label>
        <select name="size" class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
          <?php $cur = val($old, $product, 'size', 'L'); ?>
          <?php foreach ($sizes as $s): ?>
            <option value="<?= htmlspecialchars($s['label']) ?>" <?= $cur === $s['label'] ? 'selected' : '' ?>><?= htmlspecialchars($s['label']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <fieldset>
      <legend class="text-sm font-medium mb-2">Extras</legend>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        <?php foreach ($extras as $e):
          $checked = in_array($e['id'], $currentExtras, true);
        ?>
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="extras[]" value="<?= htmlspecialchars($e['id']) ?>" <?= $checked ? 'checked' : '' ?>>
            <?= htmlspecialchars($e['name']) ?>
          </label>
        <?php endforeach; ?>
      </div>
    </fieldset>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Bestand</label>
        <input name="stock" type="number" min="0" value="<?= htmlspecialchars((string) val($old, $product, 'stock', 1)) ?>"
               class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
      </div>
      <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm">
          <?php $featured = $old['featured'] ?? ($product ? $product->featured : 0); ?>
          <input type="checkbox" name="featured" value="1" <?= !empty($featured) ? 'checked' : '' ?>>
          Auf Startseite/Shop hervorheben
        </label>
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Produktbild</label>
      <?php if ($product && $product->image_path): ?>
        <div class="mb-2"><img src="<?= htmlspecialchars($product->image_path) ?>" alt="" class="h-32 rounded border border-neutral-200"></div>
      <?php endif; ?>
      <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="text-sm">
      <p class="mt-1 text-xs text-neutral-500">jpg, png oder webp · max. 5 MB</p>
    </div>

    <div class="flex items-center gap-3 pt-3 border-t border-neutral-100">
      <button type="submit" class="rounded bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">
        <?= $isEdit ? 'Änderungen speichern' : 'Produkt anlegen' ?>
      </button>
      <a href="/admin/products" class="text-sm text-neutral-500 hover:text-neutral-900">Abbrechen</a>
    </div>
  </form>
</div>
