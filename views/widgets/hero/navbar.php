<?php
$_navPrefix  = (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/') ? '' : '/';
$_cartCount  = $cartCount ?? 0;
?>
<nav id="site-nav"
     class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
     aria-label="Hauptnavigation">
  <div class="max-w-[1280px] mx-auto container-pad flex items-center justify-between h-16">

    <!-- Logo -->
    <a href="/" class="text-white font-bold text-lg tracking-tight hover:opacity-80 transition-opacity flex-shrink-0">
      DeinBrett.
    </a>

    <!-- Desktop links -->
    <div class="hidden md:flex items-center gap-6">
      <a href="<?= $_navPrefix ?>#fertigung"  class="nav-link">Fertigung</a>
      <a href="<?= $_navPrefix ?>#bauweise"   class="nav-link">Bauweise</a>
      <a href="<?= $_navPrefix ?>#holzarten"  class="nav-link">Holzarten</a>
      <a href="<?= $_navPrefix ?>#groessen"   class="nav-link">Grössen</a>
      <a href="<?= $_navPrefix ?>#features"   class="nav-link">Details</a>
    </div>

    <!-- CTAs + mobile toggle -->
    <div class="flex items-center gap-2">
      <!-- Cart icon -->
      <a href="/checkout" aria-label="Warenkorb"
         class="relative flex items-center justify-center w-9 h-9 rounded-xl text-white/70 hover:text-white hover:bg-white/10 transition-colors <?= $_cartCount > 0 ? 'text-white' : '' ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span id="navbar-cart-badge">
        <?php if ($_cartCount > 0): ?>
          <span class="absolute -top-0.5 -right-0.5 bg-orange text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none"><?= $_cartCount ?></span>
        <?php endif; ?>
        </span>
      </a>
      <a href="/shop"
         class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-white/75 hover:text-white border border-white/20 hover:border-white/40 px-3.5 py-2 rounded-xl transition-colors">
        Shop
      </a>
      <a href="<?= $_navPrefix ?>#kalkulator"
         class="hidden sm:inline-flex items-center gap-1.5 bg-white text-black font-semibold text-sm px-4 py-2 rounded-xl hover:bg-accent transition-colors">
        Konfigurieren
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </a>
      <button id="mobile-menu-btn"
              class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition-colors ml-1"
              aria-expanded="false" aria-controls="mobile-menu" aria-label="Menü öffnen">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile dropdown -->
  <div id="mobile-menu"
       class="md:hidden hidden border-t border-white/10 bg-primary/98 backdrop-blur-sm"
       role="menu">
    <div class="container-pad py-4 flex flex-col gap-1">
      <a href="<?= $_navPrefix ?>#fertigung"  class="text-white/80 hover:text-white py-3 text-sm font-medium border-b border-white/10 block">Fertigung</a>
      <a href="<?= $_navPrefix ?>#bauweise"   class="text-white/80 hover:text-white py-3 text-sm font-medium border-b border-white/10 block">Bauweise</a>
      <a href="<?= $_navPrefix ?>#holzarten"  class="text-white/80 hover:text-white py-3 text-sm font-medium border-b border-white/10 block">Holzarten</a>
      <a href="<?= $_navPrefix ?>#groessen"   class="text-white/80 hover:text-white py-3 text-sm font-medium border-b border-white/10 block">Grössen</a>
      <a href="<?= $_navPrefix ?>#features"   class="text-white/80 hover:text-white py-3 text-sm font-medium border-b border-white/10 block">Details</a>
      <a href="/shop"       class="text-white/80 hover:text-white py-3 text-sm font-medium border-b border-white/10 block">Shop → Fertige Bretter</a>
      <a href="<?= $_navPrefix ?>#kalkulator" class="btn-ghost mt-4 text-center text-sm justify-center">Brett konfigurieren</a>
    </div>
  </div>
</nav>

<style>
  #site-nav              { background: transparent; }
  #site-nav.nav--scrolled {
    background: rgba(10,10,10,0.95);
    backdrop-filter: blur(12px);
    box-shadow: 0 1px 0 rgba(255,255,255,0.06);
  }
</style>
