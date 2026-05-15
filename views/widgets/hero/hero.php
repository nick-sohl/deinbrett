<?php ?>
<header id="site-hero"
        class="relative bg-primary min-h-screen flex flex-col items-center justify-center overflow-hidden">

  <?php include __DIR__ . "/navbar.php"; ?>

  <!-- Content -->
  <div class="relative z-10 flex flex-col gap-16 md:gap-24 items-center justify-center
              max-w-[1280px] w-full container-pad pt-24 pb-16 text-center">

    <!-- Headline -->
    <div class="flex flex-col gap-4 md:gap-6 w-full hero-anim hero-anim-1">
      <h1 class="text-display-xl font-bold text-gradient w-full"
          style="background-image: linear-gradient(135deg, #fde68a 0%, #f59e0b 40%, #d97706 100%)">
        DeinBrett.
      </h1>
      <p class="text-display-md font-light text-gradient w-full"
         style="background-image: linear-gradient(135deg, #fde68a 0%, #f59e0b 40%, #d97706 100%)">
        Wo Handwerk zu Luxus wird
      </p>
    </div>

    <!-- Trust signals -->
    <div class="flex items-center justify-center gap-2 sm:gap-4 flex-wrap hero-anim hero-anim-2">
      <span class="text-white/35 text-xs font-medium tracking-widest uppercase">Handgefertigt</span>
      <span class="text-white/20 text-xs">·</span>
      <span class="text-white/35 text-xs font-medium tracking-widest uppercase">Schweizer Holz</span>
      <span class="text-white/20 text-xs">·</span>
      <span class="text-white/35 text-xs font-medium tracking-widest uppercase">Seit 2025</span>
    </div>

    <!-- Product image -->
    <div class="relative w-full max-w-4xl hero-anim hero-anim-3" style="aspect-ratio: 16/7">
      <div class="absolute inset-0 rounded-2xl border border-white/10 bg-white/5 flex flex-col items-center justify-center gap-4">
        <svg class="w-16 h-16 text-white/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <rect width="22" height="14" x="1" y="5" rx="2"/>
          <path d="M5 5v14"/>
          <path d="M19 5v14"/>
          <path d="M1 12h22"/>
        </svg>
        <p class="text-white/20 text-xs font-medium tracking-widest uppercase">Produktbild folgt</p>
      </div>
    </div>

    <!-- CTA -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-center hero-anim hero-anim-4">
      <a href="#kalkulator" class="btn-ghost text-base">
        Brett konfigurieren
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      <a href="/shop"
         class="inline-flex items-center gap-1.5 text-white/60 hover:text-white/90 transition-colors text-sm font-medium border border-white/15 hover:border-white/30 px-4 py-2.5 rounded-xl">
        Bretter kaufen
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>

  <!-- Subtle bottom gradient fade -->
  <div class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-primary to-transparent pointer-events-none"></div>
</header>
