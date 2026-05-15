<?php
use DeinBrett\Domain\Data\BoardData;
$sizes       = BoardData::sizes();
$defaultSize = $sizes['L'];
?>
<section id="groessen" class="bg-accent section-pad">
  <div class="max-w-[1280px] mx-auto container-pad flex flex-col gap-12 md:gap-16">

    <!-- Header -->
    <div class="flex flex-wrap gap-4 md:gap-6 items-start" data-anim>
      <p class="section-label w-full sm:w-[180px] shrink-0 sm:pt-1">Grössen</p>
      <div class="flex flex-col gap-3 flex-1 min-w-0">
        <h2 class="text-display-sm font-bold text-black">Für jede Küche die richtige Grösse.</h2>
        <p class="text-display-sm font-light text-gradient"
           style="background-image: linear-gradient(135deg, #c8963e 0%, #a07032 50%, #7a5025 100%)">
          Damit du immer die Wahl hast.
        </p>
      </div>
    </div>

    <!-- Intro -->
    <div class="flex justify-center">
      <p class="text-body-lg text-muted text-center max-w-2xl">
        Von kompakt bis imposant – unsere vier Grössen sind so gewählt, dass sie
        Funktionalität, Handhabung und Design optimal verbinden.
      </p>
    </div>

    <!-- Content card -->
    <div class="card" data-anim>
      <div class="p-8 md:p-12 flex flex-col gap-6">
        <h3 class="text-heading-lg font-semibold text-black">Grösse wählen</h3>
        <div id="size-selector" class="min-h-[420px]">
          <?php
          $size     = $defaultSize;
          $allSizes = $sizes;
          include __DIR__ . '/../../../partials/size-properties.php';
          ?>
        </div>
        <p class="text-xs text-muted">
          Alle Masse sind Richtwerte – echtes Handwerk hat einen eigenen Charakter.
        </p>
      </div>
    </div>

  </div>
</section>
