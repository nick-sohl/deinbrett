<?php
use DeinBrett\Domain\Data\BoardData;
$woodTypes   = BoardData::woodTypes();
$defaultWood = $woodTypes['eiche'];
?>
<section id="holzarten" class="bg-white section-pad">
  <div class="max-w-[1280px] mx-auto container-pad flex flex-col gap-12 md:gap-16">

    <!-- Header -->
    <div class="flex flex-wrap gap-4 md:gap-6 items-start" data-anim>
      <p class="section-label w-full sm:w-[180px] shrink-0 sm:pt-1">Holzarten</p>
      <div class="flex flex-col gap-3 flex-1 min-w-0">
        <h2 class="text-display-sm font-bold text-black">Ein Holz oder viele. Immer bewusst.</h2>
        <p class="text-display-sm font-light text-gradient"
           style="background-image: linear-gradient(135deg, #c8963e 0%, #a07032 50%, #7a5025 100%)">
          Dein Brett ist ganz viel Natur.
        </p>
      </div>
    </div>

    <!-- Intro -->
    <div class="flex justify-center">
      <p class="text-body-lg text-muted text-center max-w-2xl">
        Ob aus einem einzigen Holz oder aus mehreren kunstvoll kombiniert – jedes DeinBrett
        folgt einem Prinzip: Balance zwischen Funktion und Form, Material und Design.
      </p>
    </div>

    <!-- Content card -->
    <div class="card" data-anim>
      <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px]">

        <!-- Wood selector + properties -->
        <div class="flex flex-col gap-6 p-8 md:p-12">
          <h3 class="text-heading-lg font-semibold text-black">Welches Holz spricht dich an?</h3>
          <div id="wood-selector" class="min-h-[440px]">
            <?php
            $wood     = $defaultWood;
            $allWoods = $woodTypes;
            include __DIR__ . '/../../../partials/wood-properties.php';
            ?>
          </div>
        </div>

        <!-- Image panel -->
        <div class="hidden lg:flex relative bg-[#e8e3d8] overflow-hidden min-h-[400px] items-center justify-center">
          <svg class="w-20 h-20 text-[#b5a88e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          <div class="absolute bottom-6 left-6 right-6">
            <p class="text-[#8a7660] font-semibold text-sm">8 Holzarten · aus der Schweiz und Europa</p>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>
