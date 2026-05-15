<?php
/** @var array $size     current size
 *  @var array $allSizes all sizes for re-rendering pills
 */

// Proportional board comparison — scale so XL width = 120px
$scale = 120 / 520;
$gap   = 14;
$padX  = 12;
$maxH  = 0;
$boards = [];
foreach ($allSizes as $id => $s) {
    $w = round($s['length'] * $scale);
    $h = round($s['width']  * $scale);
    $boards[$id] = [
        'w'      => $w,
        'h'      => $h,
        'label'  => $s['label'],
        'active' => ($id === $size['label']),
    ];
    if ($h > $maxH) $maxH = $h;
}
$totalW = $padX * 2 + array_sum(array_column($boards, 'w')) + $gap * (count($boards) - 1);
$svgH   = $maxH + 22;

$x = $padX;
$positions = [];
foreach ($boards as $id => $b) {
    $positions[$id] = ['x' => $x, 'y' => $maxH - $b['h']];
    $x += $b['w'] + $gap;
}
?>
<div class="flex flex-col gap-5">

  <!-- Size pill selector -->
  <div class="bg-accent flex flex-wrap gap-2 items-center p-3.5 rounded-2xl">
    <?php foreach ($allSizes as $id => $s): ?>
      <?php $active = ($id === $size['label']); ?>
      <button
        class="size-pill <?= $active ? 'size-pill--active' : '' ?>"
        hx-get="/api/size?size=<?= htmlspecialchars($id) ?>"
        hx-target="#size-selector"
        hx-swap="innerHTML"
        aria-pressed="<?= $active ? 'true' : 'false' ?>"
      ><?= htmlspecialchars($s['label']) ?></button>
    <?php endforeach; ?>
  </div>

  <!-- Description -->
  <p class="text-base text-muted leading-relaxed min-h-[3rem]"><?= htmlspecialchars($size['description']) ?></p>

  <!-- Dimension stat cards -->
  <dl class="grid grid-cols-3 gap-3">
    <div class="bg-accent rounded-xl p-4 text-center">
      <dt class="text-xs font-semibold uppercase tracking-widest text-muted">Länge</dt>
      <dd class="text-xl font-bold text-black mt-1.5"><?= $size['length'] ?><span class="text-sm font-normal ml-0.5">mm</span></dd>
    </div>
    <div class="bg-accent rounded-xl p-4 text-center">
      <dt class="text-xs font-semibold uppercase tracking-widest text-muted">Breite</dt>
      <dd class="text-xl font-bold text-black mt-1.5"><?= $size['width'] ?><span class="text-sm font-normal ml-0.5">mm</span></dd>
    </div>
    <div class="bg-accent rounded-xl p-4 text-center">
      <dt class="text-xs font-semibold uppercase tracking-widest text-muted">Dicke</dt>
      <dd class="text-xl font-bold text-black mt-1.5"><?= $size['height'] ?><span class="text-sm font-normal ml-0.5">mm</span></dd>
    </div>
  </dl>

  <!-- Price -->
  <p class="text-sm font-semibold text-orange">Ab <?= $size['base_price'] ?> CHF</p>

  <!-- Proportional board comparison -->
  <div class="pt-2 border-t border-border">
    <p class="text-xs font-medium text-muted uppercase tracking-widest mb-3">Grössenvergleich</p>
    <svg viewBox="0 0 <?= $totalW ?> <?= $svgH ?>" xmlns="http://www.w3.org/2000/svg"
         class="w-full" aria-label="Proportionaler Grössenvergleich S bis XL" role="img">
      <?php foreach ($boards as $id => $b): ?>
        <?php $pos = $positions[$id]; $isActive = $b['active']; ?>
        <rect
          x="<?= $pos['x'] ?>" y="<?= $pos['y'] ?>"
          width="<?= $b['w'] ?>" height="<?= $b['h'] ?>"
          rx="3"
          fill="<?= $isActive ? '#0a0a0a' : '#e8e3d8' ?>"
          stroke="<?= $isActive ? '#0a0a0a' : '#c4b89a' ?>"
          stroke-width="1.5"
        />
        <text
          x="<?= $pos['x'] + $b['w'] / 2 ?>"
          y="<?= $maxH + 17 ?>"
          text-anchor="middle"
          font-size="10"
          font-family="Inter,-apple-system,sans-serif"
          font-weight="<?= $isActive ? '700' : '500' ?>"
          fill="<?= $isActive ? '#0a0a0a' : '#9ca3af' ?>"
        ><?= $b['label'] ?></text>
      <?php endforeach; ?>
    </svg>
  </div>

</div>
