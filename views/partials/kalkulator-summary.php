<?php
/** @var array $result  (wood, size, construction, base_price, extras, extras_total, total) */
$woodNames = array_map(fn($w) => $w['name'], [$result['wood']]);
$sizeLabel = $result['size']['label'];
$constructionName = $result['construction']['name'];
$basePrice = $result['base_price'];
$extrasTotal = $result['extras_total'];
$total = $result['total'];
$selectedExtras = $result['extras'];
?>
<div class="flex font-bold items-center justify-between pb-4 border-b border-black text-2xl">
  <span>Total</span>
  <span><?= $total ?>.-</span>
</div>

<div class="flex items-start justify-between text-base gap-4">
  <div class="flex flex-col gap-1">
    <p class="font-semibold">Basis</p>
    <p class="text-muted"><?= htmlspecialchars($result['wood']['name']) ?></p>
    <p class="text-muted">Grösse <?= htmlspecialchars($sizeLabel) ?> (<?= $result['size']['length'] ?> × <?= $result['size']['width'] ?> × <?= $result['size']['height'] ?> mm)</p>
    <p class="text-muted"><?= htmlspecialchars($constructionName) ?></p>
  </div>
  <p class="font-semibold whitespace-nowrap"><?= $basePrice ?>.-</p>
</div>

<data id="kalkulator-total-price" value="<?= $total ?>"></data>
<?php if (!empty($selectedExtras)): ?>
<div class="flex items-start justify-between text-base gap-4">
  <div class="flex flex-col gap-1">
    <p class="font-semibold">Extras</p>
    <?php foreach ($selectedExtras as $extra): ?>
      <p class="text-muted"><?= htmlspecialchars($extra['name']) ?></p>
    <?php endforeach; ?>
  </div>
  <div class="text-right whitespace-nowrap flex flex-col gap-1">
    <p class="font-semibold"><?= $extrasTotal ?>.-</p>
    <?php foreach ($selectedExtras as $extra): ?>
      <p class="text-muted"><?= $extra['price'] ?>.-</p>
    <?php endforeach; ?>
  </div>
</div>
<?php else: ?>
<p class="text-muted text-sm">Keine Extras gewählt</p>
<?php endif; ?>
