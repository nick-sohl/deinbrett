<?php
use DeinBrett\Domain\Data\BoardData;

$woodMeta = [
    'eiche'        => ['label' => 'Europäische Eiche',  'bg' => '#e8e3d8', 'text' => '#8a7660'],
    'raeuchereiche'=> ['label' => 'Räuchereiche',       'bg' => '#3a2e28', 'text' => '#9a8070'],
    'schwarznuss'  => ['label' => 'Schwarznuss',        'bg' => '#2a2218', 'text' => '#7a6858'],
    'ahorn'        => ['label' => 'Ahorn',              'bg' => '#f0ebe0', 'text' => '#9a9080'],
    'esche'        => ['label' => 'Esche',              'bg' => '#e8e8e0', 'text' => '#8a8a80'],
    'birke'        => ['label' => 'Birke',              'bg' => '#f5f0e0', 'text' => '#9a9070'],
    'robinie'      => ['label' => 'Robinie',            'bg' => '#d4c080', 'text' => '#7a6030'],
    'birnbaum'     => ['label' => 'Birnbaum',           'bg' => '#c8a060', 'text' => '#6a4820'],
];

$constructionLabel = ['stirnholz' => 'Stirnholz', 'laengsholz' => 'Längsholz'];
$sizeData = BoardData::sizes();
$extrasData = BoardData::extras();
?>

<!-- Shop header -->
<section class="bg-primary pt-24 pb-12 md:pb-16">
  <div class="max-w-[1280px] mx-auto container-pad">
    <p class="section-label text-white/40 mb-3">Shop</p>
    <h1 class="text-display-sm font-bold text-white mb-2">Handgefertigte Bretter.</h1>
    <p class="text-display-sm font-light text-gradient"
       style="background-image: linear-gradient(135deg, #fde68a 0%, #f59e0b 40%, #d97706 100%)">
      Direkt verfügbar.
    </p>
    <p class="text-body-lg text-white/50 mt-6 max-w-xl">
      Jedes Brett ein Einzelstück — handgefertigt, sofort lieferbar, ohne Wartezeit.
    </p>
  </div>
</section>

<!-- Product grid -->
<section class="bg-accent section-pad">
  <div class="max-w-[1280px] mx-auto container-pad">

    <?php if (empty($boards)): ?>
      <p class="text-muted text-center py-20">Derzeit keine Bretter verfügbar.</p>
    <?php else: ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <?php foreach ($boards as $board):
        $wood   = $woodMeta[$board->wood_type] ?? $woodMeta['eiche'];
        $inCart = in_array($board->id, $cartIds, true);
        $size   = $sizeData[$board->size] ?? null;
        $extras = $board->extrasArray();
      ?>
      <article class="card flex flex-col group" data-anim>

        <!-- Image placeholder -->
        <div class="relative overflow-hidden flex-none" style="aspect-ratio: 4/3; background: <?= $wood['bg'] ?>">
          <div class="absolute inset-0 flex items-center justify-center transition-transform duration-500 ease-out group-hover:scale-[1.04]">
            <svg class="w-16 h-16" style="color: <?= $wood['text'] ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect width="20" height="14" x="2" y="5" rx="2"/>
              <path d="M6 5v14"/><path d="M18 5v14"/><path d="M2 12h20"/>
            </svg>
          </div>
          <!-- Badges -->
          <div class="absolute top-3 left-3 flex gap-2 flex-wrap">
            <?php if ($board->featured): ?>
              <span class="bg-orange text-white text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide uppercase">Empfohlen</span>
            <?php endif; ?>
            <?php if (!$board->isAvailable()): ?>
              <span class="bg-black/60 text-white text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide uppercase">Verkauft</span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-4 p-6 flex-1">

          <div>
            <h2 class="text-heading-xl font-bold text-black leading-tight"><?= htmlspecialchars($board->name) ?></h2>
            <p class="text-sm text-muted mt-1"><?= htmlspecialchars($board->tagline) ?></p>
          </div>

          <!-- Specs chips -->
          <div class="flex flex-wrap gap-1.5">
            <span class="text-[11px] font-medium bg-accent text-muted px-2.5 py-1 rounded-full"><?= htmlspecialchars($wood['label']) ?></span>
            <span class="text-[11px] font-medium bg-accent text-muted px-2.5 py-1 rounded-full"><?= htmlspecialchars($constructionLabel[$board->construction] ?? $board->construction) ?></span>
            <?php if ($size): ?>
              <span class="text-[11px] font-medium bg-accent text-muted px-2.5 py-1 rounded-full"><?= $size['length'] ?>×<?= $size['width'] ?> mm</span>
            <?php endif; ?>
            <?php foreach ($extras as $eid):
              $extra = $extrasData[$eid] ?? null;
              if ($extra): ?>
                <span class="text-[11px] font-medium bg-accent text-muted px-2.5 py-1 rounded-full"><?= htmlspecialchars($extra['name']) ?></span>
              <?php endif;
            endforeach; ?>
          </div>

          <p class="text-sm text-muted leading-relaxed flex-1"><?= htmlspecialchars($board->description) ?></p>

          <!-- Price + CTA -->
          <div class="pt-4 border-t border-border flex items-center justify-between gap-4">
            <p class="text-xl font-bold text-black"><?= format_price($board->price) ?></p>

            <div id="product-cta-<?= $board->id ?>">
            <?php if ($inCart): ?>
              <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-orange">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Im Warenkorb
              </span>
            <?php elseif ($board->isAvailable()): ?>
              <form hx-post="/cart/add"
                    hx-target="#product-cta-<?= $board->id ?>"
                    hx-swap="outerHTML">
                <?= csrf_field() ?>
                <input type="hidden" name="board_id" value="<?= $board->id ?>">
                <button type="submit" class="btn-primary text-sm px-5 py-2.5">
                  In den Warenkorb
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
              </form>
            <?php else: ?>
              <span class="text-sm text-muted">Nicht verfügbar</span>
            <?php endif; ?>
            </div>
          </div>

        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <!-- Link back to configurator -->
    <div class="mt-16 pt-12 border-t border-border text-center">
      <p class="text-muted mb-4">Nichts Passendes dabei? Konfiguriere dein eigenes Brett.</p>
      <a href="/#kalkulator" class="btn-primary">
        Brett konfigurieren
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

  </div>
</section>
