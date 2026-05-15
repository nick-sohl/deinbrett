<?php
/** @var array $wood      current wood
 *  @var array $allWoods  all wood types for re-rendering pills with active state
 */
?>
<div class="flex flex-col gap-6">

  <!-- Pill selector (re-rendered with active state) -->
  <div class="bg-accent rounded-2xl p-3.5">
    <div class="flex flex-wrap gap-2">
      <?php foreach ($allWoods as $id => $w): ?>
        <?php $active = ($id === $wood['id']); ?>
        <button
          class="pill-btn <?= $active ? 'pill-btn--active' : '' ?>"
          hx-get="/api/wood?wood=<?= htmlspecialchars($id) ?>"
          hx-target="#wood-selector"
          hx-swap="innerHTML"
          aria-pressed="<?= $active ? 'true' : 'false' ?>"
        ><?= htmlspecialchars($w['name']) ?></button>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Description – stable height via min-h -->
  <div class="min-h-[4.5rem]">
    <p class="text-base leading-relaxed text-muted"><?= htmlspecialchars($wood['description']) ?></p>
  </div>

  <!-- Properties grid -->
  <dl class="grid grid-cols-2 gap-x-8 gap-y-5">
    <div>
      <dt class="text-xs font-semibold text-black uppercase tracking-widest mb-1">Farbton</dt>
      <dd class="text-sm text-muted"><?= htmlspecialchars($wood['color']) ?></dd>
    </div>
    <div>
      <dt class="text-xs font-semibold text-black uppercase tracking-widest mb-1">Maserung</dt>
      <dd class="text-sm text-muted"><?= htmlspecialchars($wood['grain']) ?></dd>
    </div>
    <div>
      <dt class="text-xs font-semibold text-black uppercase tracking-widest mb-1">Härte</dt>
      <dd class="text-sm text-muted"><?= htmlspecialchars($wood['hardness']) ?></dd>
    </div>
    <div>
      <dt class="text-xs font-semibold text-black uppercase tracking-widest mb-1">Besonderheiten</dt>
      <dd class="text-sm text-muted"><?= htmlspecialchars($wood['features']) ?></dd>
    </div>
  </dl>

  <!-- Price indicator – always visible, consistent height -->
  <div class="pt-1">
    <?php if ($wood['price_add'] > 0): ?>
      <p class="text-sm text-muted">+ <?= $wood['price_add'] ?> CHF gegenüber Basisholz</p>
    <?php else: ?>
      <p class="text-sm font-semibold text-orange">Basisholz – im Startpreis enthalten</p>
    <?php endif; ?>
  </div>

</div>
