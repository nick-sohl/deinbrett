<?php ?>
<footer class="bg-primary text-white">
  <div class="max-w-[1280px] mx-auto container-pad py-16 md:py-20">

    <!-- Top: brand + nav -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10 border-b border-white/10">

      <!-- Brand -->
      <div class="flex flex-col gap-4">
        <p class="text-2xl font-bold tracking-tight">DeinBrett.</p>
        <p class="text-white/50 text-sm leading-relaxed max-w-xs">
          Handgefertigte Schneidebretter aus massivem Schweizer Holz.
          Gefertigt für Menschen, die Küche als Erlebnis verstehen.
        </p>
      </div>

      <!-- Links -->
      <div class="flex flex-col gap-3">
        <p class="text-xs font-semibold text-white/30 uppercase tracking-widest mb-1">Produkt</p>
        <a href="/#fertigung"  class="text-white/60 hover:text-white text-sm transition-colors">Fertigung</a>
        <a href="/#bauweise"   class="text-white/60 hover:text-white text-sm transition-colors">Bauweise</a>
        <a href="/#holzarten"  class="text-white/60 hover:text-white text-sm transition-colors">Holzarten</a>
        <a href="/#groessen"   class="text-white/60 hover:text-white text-sm transition-colors">Grössen</a>
        <a href="/#kalkulator" class="text-white/60 hover:text-white text-sm transition-colors">Konfigurator</a>
        <a href="/shop"        class="text-white/60 hover:text-white text-sm transition-colors">Shop</a>
      </div>

      <!-- CTAs -->
      <div class="flex flex-col gap-4 items-start">
        <p class="text-white/50 text-sm">Bereit für dein Brett?</p>
        <a href="/#kalkulator" class="btn-ghost text-sm">
          Jetzt konfigurieren →
        </a>
        <a href="/shop" class="inline-flex items-center gap-1.5 text-white/60 hover:text-white text-sm transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 10a4 4 0 01-8 0"/></svg>
          Fertige Bretter kaufen →
        </a>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="pt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
      <p class="text-white/30 text-xs">© <?= date('Y') ?> DeinBrett. Alle Rechte vorbehalten.</p>
      <div class="flex gap-5">
        <a href="#" class="text-white/30 hover:text-white/60 text-xs transition-colors">Datenschutz</a>
        <a href="#" class="text-white/30 hover:text-white/60 text-xs transition-colors">Impressum</a>
        <a href="#" class="text-white/30 hover:text-white/60 text-xs transition-colors">AGB</a>
      </div>
    </div>
  </div>
</footer>
