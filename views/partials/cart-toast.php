<?php
use DeinBrett\Application\Service\CartService;
$_cs    = new CartService();
$_items = $_cs->items();
$_count = $_cs->count();
$_total = $_cs->total();
$_plural = $_count === 1 ? 'Brett' : 'Bretter';
?>
<div id="cart-toast"
     role="region" aria-label="Warenkorb"
     onmouseenter="cartToastClearTimer()"
     onmouseleave="cartToastOnLeave()"
     class="cart-toast <?= $_count > 0 ? 'cart-toast--visible' : '' ?>">

  <?php if ($_count > 0): ?>

  <!-- Items drawer (animated) -->
  <div id="cart-toast-items" class="cart-toast-panel" aria-hidden="true">
    <div>
      <div class="max-w-[1280px] mx-auto container-pad border-b border-border">
        <?php foreach ($_items as $_item): ?>
        <div class="flex items-center gap-3 sm:gap-4 py-3.5 border-b border-border/60 last:border-0">
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-black leading-snug truncate"><?= htmlspecialchars($_item['name']) ?></p>
            <p class="text-xs text-muted mt-0.5"><?= $_item['type'] === 'custom' ? 'Individuell konfiguriert' : 'Aus dem Shop' ?></p>
          </div>
          <p class="text-sm font-semibold text-black whitespace-nowrap"><?= format_price($_item['price']) ?></p>

          <!-- Action area: toggles between remove button and confirm prompt -->
          <div class="cart-item-actions flex-none">
            <div class="cart-action-default">
              <button type="button" onclick="cartItemShowConfirm(this)" class="cart-remove-btn" aria-label="<?= htmlspecialchars($_item['name']) ?> entfernen">
                <svg class="w-3 h-3 flex-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
                </svg>
                <span class="hidden sm:inline">Entfernen</span>
              </button>
            </div>
            <div class="cart-action-confirm hidden items-center gap-1.5">
              <span class="text-xs font-medium text-black">Entfernen?</span>
              <form method="POST" action="/cart/remove" class="inline">
                <?= csrf_field() ?>
                <input type="hidden" name="board_id" value="<?= htmlspecialchars((string)($_item['cart_key'] ?? $_item['board_id'])) ?>">
                <button type="submit" class="cart-confirm-yes">Ja</button>
              </form>
              <button type="button" onclick="cartItemCancelConfirm(this)" class="cart-confirm-no">Nein</button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Main bar -->
  <div class="max-w-[1280px] mx-auto container-pad py-3 flex items-center gap-3">
    <button type="button" onclick="cartToastToggle()" aria-expanded="false" aria-controls="cart-toast-items"
            class="flex items-center gap-2.5 flex-1 min-w-0 text-left group">
      <div class="relative flex-none">
        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6" stroke-linecap="round"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span class="absolute -top-1.5 -right-1.5 bg-orange text-white text-[9px] font-bold w-3.5 h-3.5 rounded-full flex items-center justify-center leading-none"><?= $_count ?></span>
      </div>
      <span class="font-semibold text-black text-sm"><?= $_count ?> <?= $_plural ?></span>
      <span class="text-muted text-sm">· <?= format_price($_total) ?></span>
      <span id="cart-toast-hint" class="text-xs text-muted group-hover:text-black transition-colors ml-0.5 hidden sm:inline">Anzeigen</span>
      <svg id="cart-toast-chevron" class="w-3.5 h-3.5 text-muted transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
      </svg>
    </button>
    <a href="/checkout" class="btn-primary flex-none text-sm px-5 py-2.5">
      Zur Kasse
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </a>
  </div>

  <?php endif; ?>
</div>

<script>
var _cartCollapseTimer = null;

function cartToastClearTimer() {
  clearTimeout(_cartCollapseTimer);
  _cartCollapseTimer = null;
}

function cartToastAutoCollapse(ms) {
  cartToastClearTimer();
  _cartCollapseTimer = setTimeout(function() {
    var panel = document.getElementById('cart-toast-items');
    if (panel && panel.classList.contains('cart-toast-panel--open')) {
      cartToastToggle();
    }
  }, ms || 5000);
}

function cartToastOnLeave() {
  var panel = document.getElementById('cart-toast-items');
  if (panel && panel.classList.contains('cart-toast-panel--open')) {
    cartToastAutoCollapse(3000);
  }
}

function cartToastToggle() {
  var panel   = document.getElementById('cart-toast-items');
  var chevron = document.getElementById('cart-toast-chevron');
  var hint    = document.getElementById('cart-toast-hint');
  var btn     = document.querySelector('[aria-controls="cart-toast-items"]');
  if (!panel) return;
  cartToastClearTimer();
  var open = panel.classList.toggle('cart-toast-panel--open');
  panel.setAttribute('aria-hidden', String(!open));
  if (chevron) chevron.style.transform = open ? 'rotate(180deg)' : '';
  if (hint)    hint.textContent = open ? 'Ausblenden' : 'Anzeigen';
  if (btn)     btn.setAttribute('aria-expanded', String(open));
}

function cartItemShowConfirm(btn) {
  cartToastClearTimer();
  var wrap = btn.closest('.cart-item-actions');
  wrap.querySelector('.cart-action-default').classList.add('hidden');
  var conf = wrap.querySelector('.cart-action-confirm');
  conf.classList.remove('hidden');
  conf.classList.add('flex');
}

function cartItemCancelConfirm(btn) {
  var wrap = btn.closest('.cart-item-actions');
  wrap.querySelector('.cart-action-default').classList.remove('hidden');
  var conf = wrap.querySelector('.cart-action-confirm');
  conf.classList.add('hidden');
  conf.classList.remove('flex');
}
</script>
