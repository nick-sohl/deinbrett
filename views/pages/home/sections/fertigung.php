<?php ?>
<section id="fertigung" class="bg-accent section-pad">
  <div class="max-w-[1280px] mx-auto container-pad flex flex-col gap-12 md:gap-16">

    <!-- Header -->
    <div class="flex flex-wrap gap-4 md:gap-6 items-start" data-anim>
      <p class="section-label w-full sm:w-[180px] shrink-0 sm:pt-1">Fertigung</p>
      <div class="flex flex-col gap-3 flex-1 min-w-0">
        <h2 class="text-display-sm font-bold text-black">Brettzision.</h2>
        <p class="text-display-sm font-light text-gradient"
           style="background-image: linear-gradient(135deg, #c8963e 0%, #a07032 50%, #7a5025 100%)">
          Dein Brett gemacht für immer.
        </p>
      </div>
    </div>

    <!-- Intro -->
    <div class="flex justify-center">
      <p class="text-body-lg text-muted text-center max-w-2xl">
        Jedes DeinBrett entsteht in Handarbeit – von der Holzauswahl bis zum
        letzten Öldurchgang. Kein Automat. Kein Fliessband. Nur Handwerk mit Anspruch.
      </p>
    </div>

    <!-- Process steps -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-10 border-t border-border pt-10">

      <div class="flex flex-col gap-3" data-anim data-anim-delay="0">
        <span class="text-5xl font-bold leading-none select-none" style="color: rgba(0,0,0,0.07)">01</span>
        <p class="font-semibold text-black text-sm mt-1">Holzauswahl</p>
        <p class="text-sm text-muted leading-relaxed">
          Ausschliesslich Holz aus nachhaltigen, europäischen Quellen.
          Jedes Brett beginnt mit der bewussten Wahl des richtigen Materials.
        </p>
      </div>

      <div class="flex flex-col gap-3" data-anim data-anim-delay="80">
        <span class="text-5xl font-bold leading-none select-none" style="color: rgba(0,0,0,0.07)">02</span>
        <p class="font-semibold text-black text-sm mt-1">Zuschnitt & Verleimung</p>
        <p class="text-sm text-muted leading-relaxed">
          Millimetergenaue Zuschnitte, verleimt mit lebensmittelechtem Leim
          unter kontrolliertem Druck. Der Grundstein für ein Brett, das hält.
        </p>
      </div>

      <div class="flex flex-col gap-3" data-anim data-anim-delay="160">
        <span class="text-5xl font-bold leading-none select-none" style="color: rgba(0,0,0,0.07)">03</span>
        <p class="font-semibold text-black text-sm mt-1">Handschliff</p>
        <p class="text-sm text-muted leading-relaxed">
          Stundenlanger Schliff von grob bis fein. Bis die Oberfläche sich
          anfühlt wie sie aussieht – gleichmässig, glatt, makellos.
        </p>
      </div>

      <div class="flex flex-col gap-3" data-anim data-anim-delay="240">
        <span class="text-5xl font-bold leading-none select-none" style="color: rgba(0,0,0,0.07)">04</span>
        <p class="font-semibold text-black text-sm mt-1">Ölung & Veredelung</p>
        <p class="text-sm text-muted leading-relaxed">
          Naturöle schützen das Holz, versiegeln die Fasern und betonen
          die natürliche Schönheit. Jedes Brett wird von Hand geölt.
        </p>
      </div>

    </div>

    <!-- Image grid – no horizontal scroll, real layout -->
    <div class="grid grid-cols-1 md:grid-cols-[2fr_1fr] gap-4 md:gap-5">

      <!-- Primary card: tall, text overlay -->
      <div class="relative rounded-2xl overflow-hidden h-[300px] md:h-[460px] flex items-end bg-[#2a2218]" data-anim data-anim-delay="0">
        <div class="absolute inset-0 flex items-center justify-center">
          <svg class="w-16 h-16 text-white/15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="6"/>
            <circle cx="12" cy="12" r="2"/>
          </svg>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/15 to-transparent"></div>
        <p class="relative font-bold text-white leading-snug p-6 md:p-10"
           style="font-size: clamp(1.5rem, 3vw, 2.5rem)">
          Präzision statt<br>Geschwindigkeit.
        </p>
      </div>

      <!-- Right column: two stacked cards -->
      <div class="flex flex-col gap-4 md:gap-5 md:h-[460px]">

        <div class="relative rounded-2xl overflow-hidden h-[220px] md:flex-1 flex items-end bg-[#1e1a14]">
          <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-12 h-12 text-white/15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent"></div>
          <p class="relative font-bold text-white text-lg md:text-2xl leading-snug p-5 md:p-6">
            Handwerk statt<br>Industrie.
          </p>
        </div>

        <div class="relative rounded-2xl overflow-hidden h-[180px] md:flex-1 bg-[#e8e3d8] flex items-center justify-center">
          <svg class="w-10 h-10 text-[#b5a88e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
          </svg>
        </div>

      </div>
    </div>

  </div>
</section>
