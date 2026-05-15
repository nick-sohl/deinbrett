<?php
$types = [
  [
    'tag'      => 'Klassisch',
    'title'    => 'Längsholz',
    'tagline'  => 'Ruhige Eleganz für jeden Tag.',
    'icon'     => '<path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>',
    'alt'      => 'Längsholzbrett – parallele Schichten',
    'desc'     => 'Parallel verleimte Streifen, deren Fasern in Schnittrichtung verlaufen. Das Ergebnis ist eine ruhige, lineare Maserung – zeitlos, schlicht und formstabil.',
    'benefits' => [
      'Ruhige, lineare Maserung',
      'Leicht und langzeitstabil',
      'Für alle Holzarten verfügbar',
    ],
    'cta_href' => '#kalkulator',
    'cta_text' => 'Im Konfigurator wählen',
  ],
  [
    'tag'      => 'Profi-Wahl',
    'title'    => 'Stirnholz',
    'tagline'  => 'Substanz. Kraft. Beständigkeit.',
    'icon'     => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
    'alt'      => 'Stirnholzbrett – charakteristisches Endholz-Muster',
    'desc'     => 'Quer zur Faser geschnitten: Die Klinge taucht zwischen die Fasern – das Brett schliesst sich von selbst. Messerschonend, selbstheilend, extrem langlebig.',
    'benefits' => [
      'Messerschonend – Klinge gleitet zwischen Fasern',
      'Selbstheilend: Schnittspuren schliessen sich',
      'Extrem robust im täglichen Einsatz',
    ],
    'cta_href' => '#kalkulator',
    'cta_text' => 'Im Konfigurator wählen',
  ],
  [
    'tag'      => 'Unikat',
    'title'    => 'Kunstbrett',
    'tagline'  => 'Handgefertigte Holzkompositionen.',
    'icon'     => '<circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>',
    'alt'      => 'Kunstbrett – Kombination mehrerer Holzarten',
    'desc'     => 'Zwei oder mehr Holzarten – bewusst kombiniert. Farben, Maserungen und Texturen spielen miteinander. Kein Brett ist wie das andere.',
    'benefits' => [
      'Mehrere Holzarten als Komposition',
      'Jedes Brett ein echtes Einzelstück',
      'Maximale gestalterische Freiheit',
    ],
    'cta_href' => '#holzarten',
    'cta_text' => 'Holzarten erkunden',
  ],
];
?>
<section id="bauweise" class="bg-[#fafafa] section-pad">
  <div class="max-w-[1280px] mx-auto container-pad flex flex-col gap-12 md:gap-16">

    <!-- Header -->
    <div class="flex flex-wrap gap-4 md:gap-6 items-start" data-anim>
      <p class="section-label w-full sm:w-[180px] shrink-0 sm:pt-1">Bauweise</p>
      <div class="flex flex-col gap-3 flex-1 min-w-0">
        <h2 class="text-display-sm font-bold text-black">Die Bauweise entscheidest du.</h2>
        <p class="text-display-sm font-light text-gradient"
           style="background-image: linear-gradient(135deg, #c8963e 0%, #a07032 50%, #7a5025 100%)">
          Finde was zu dir passt.
        </p>
      </div>
    </div>

    <!-- Intro -->
    <div class="flex justify-center">
      <p class="text-body-lg text-muted text-center max-w-2xl">
        Die Bauweise bestimmt das Gefühl, die Haltbarkeit und den Charakter deines Brettes.
        Sie ist keine Nebensache – sie ist die Grundlage.
      </p>
    </div>

    <!-- Mobile tab selector -->
    <div class="md:hidden flex bg-accent rounded-2xl p-1 gap-1">
      <?php foreach ($types as $i => $type): ?>
        <button class="bauweise-tab <?= $i === 0 ? 'is-active' : '' ?>" data-tab="<?= $i ?>">
          <?= htmlspecialchars($type['title']) ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Cards -->
    <div id="bauweise-cards" class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6 items-stretch">
      <?php foreach ($types as $i => $type): ?>
      <div class="card card-hover flex flex-col group bauweise-card" data-card="<?= $i ?>" data-anim data-anim-delay="<?= $i * 100 ?>">

        <!-- Image placeholder – fixed aspect ratio, never resizes -->
        <div class="relative overflow-hidden flex-none bg-[#e8e3d8]" style="aspect-ratio: 4/3;">
          <div class="absolute inset-0 flex items-center justify-center transition-transform duration-500 ease-out group-hover:scale-[1.04]">
            <svg class="w-16 h-16 text-[#b5a88e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <?= $type['icon'] ?>
            </svg>
          </div>
          <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-black text-xs font-semibold px-3 py-1.5 rounded-full tracking-widest uppercase">
            <?= htmlspecialchars($type['tag']) ?>
          </span>
        </div>

        <!-- Content body – grows to equal height across cards -->
        <div class="flex flex-col gap-4 p-6 md:p-7 flex-1">
          <div>
            <h3 class="text-heading-xl font-bold text-black leading-tight"><?= htmlspecialchars($type['title']) ?></h3>
            <p class="text-sm text-muted mt-1"><?= htmlspecialchars($type['tagline']) ?></p>
          </div>

          <p class="text-sm text-muted leading-relaxed"><?= htmlspecialchars($type['desc']) ?></p>

          <ul class="flex flex-col gap-2 flex-1">
            <?php foreach ($type['benefits'] as $benefit): ?>
            <li class="flex items-start gap-2.5 text-sm text-muted leading-snug">
              <svg class="w-4 h-4 text-orange mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
              <?= htmlspecialchars($benefit) ?>
            </li>
            <?php endforeach; ?>
          </ul>

          <!-- CTA – always at card bottom -->
          <a href="<?= htmlspecialchars($type['cta_href']) ?>"
             class="mt-2 pt-4 border-t border-border text-sm font-semibold text-black hover:text-orange transition-colors inline-flex items-center gap-1.5">
            <?= htmlspecialchars($type['cta_text']) ?>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
          </a>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<script>
(function () {
  var cards   = document.querySelectorAll('.bauweise-card');
  var tabs    = document.querySelectorAll('.bauweise-tab');
  var grid    = document.getElementById('bauweise-cards');
  if (!grid || !cards.length) return;

  grid.dataset.js = '1';

  function activate(idx) {
    tabs.forEach(function (t, i)  { t.classList.toggle('is-active', i === idx); });
    cards.forEach(function (c, i) { c.classList.toggle('is-active', i === idx); });
  }

  tabs.forEach(function (tab, i) {
    tab.addEventListener('click', function () { activate(i); });
  });

  activate(0);
})();
</script>
