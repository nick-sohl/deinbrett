<?php
$v = function(string $field) use ($old): string {
    return htmlspecialchars($old[$field] ?? '');
};
$e = function(string $field) use ($errors): string {
    return $errors[$field] ?? '';
};
?>

<!-- Checkout header -->
<section class="bg-primary pt-24 pb-10">
  <div class="max-w-[1280px] mx-auto container-pad">
    <p class="section-label text-white/40 mb-3">Kasse</p>
    <h1 class="text-display-sm font-bold text-white">Fast geschafft.</h1>
  </div>
</section>

<section class="bg-accent section-pad">
  <div class="max-w-[1280px] mx-auto container-pad">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 lg:gap-12 items-start">

      <!-- Delivery form -->
      <div class="card p-8 md:p-10">
        <h2 class="text-heading-lg font-semibold text-black mb-6">Lieferadresse</h2>

        <form method="POST" action="/checkout" class="flex flex-col gap-5" novalidate>
          <?= csrf_field() ?>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="form-group <?= $e('first_name') ? 'has-error' : '' ?>">
              <label class="form-label" for="first_name">Vorname *</label>
              <input class="form-input" type="text" id="first_name" name="first_name"
                     value="<?= $v('first_name') ?>" autocomplete="given-name" required>
              <?php if ($e('first_name')): ?><p class="form-error"><?= $e('first_name') ?></p><?php endif; ?>
            </div>
            <div class="form-group <?= $e('last_name') ? 'has-error' : '' ?>">
              <label class="form-label" for="last_name">Nachname *</label>
              <input class="form-input" type="text" id="last_name" name="last_name"
                     value="<?= $v('last_name') ?>" autocomplete="family-name" required>
              <?php if ($e('last_name')): ?><p class="form-error"><?= $e('last_name') ?></p><?php endif; ?>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="form-group <?= $e('email') ? 'has-error' : '' ?>">
              <label class="form-label" for="email">E-Mail *</label>
              <input class="form-input" type="email" id="email" name="email"
                     value="<?= $v('email') ?>" autocomplete="email" required>
              <?php if ($e('email')): ?><p class="form-error"><?= $e('email') ?></p><?php endif; ?>
            </div>
            <div class="form-group">
              <label class="form-label" for="phone">Telefon</label>
              <input class="form-input" type="tel" id="phone" name="phone"
                     value="<?= $v('phone') ?>" autocomplete="tel">
            </div>
          </div>

          <div class="form-group <?= $e('address') ? 'has-error' : '' ?>">
            <label class="form-label" for="address">Strasse & Hausnummer *</label>
            <input class="form-input" type="text" id="address" name="address"
                   value="<?= $v('address') ?>" autocomplete="street-address" required>
            <?php if ($e('address')): ?><p class="form-error"><?= $e('address') ?></p><?php endif; ?>
          </div>

          <div class="grid grid-cols-[120px_1fr] gap-5">
            <div class="form-group <?= $e('zip') ? 'has-error' : '' ?>">
              <label class="form-label" for="zip">PLZ *</label>
              <input class="form-input" type="text" id="zip" name="zip"
                     value="<?= $v('zip') ?>" autocomplete="postal-code" required>
              <?php if ($e('zip')): ?><p class="form-error"><?= $e('zip') ?></p><?php endif; ?>
            </div>
            <div class="form-group <?= $e('city') ? 'has-error' : '' ?>">
              <label class="form-label" for="city">Ort *</label>
              <input class="form-input" type="text" id="city" name="city"
                     value="<?= $v('city') ?>" autocomplete="address-level2" required>
              <?php if ($e('city')): ?><p class="form-error"><?= $e('city') ?></p><?php endif; ?>
            </div>
          </div>

          <div class="form-group <?= $e('notes') ? 'has-error' : '' ?>">
            <label class="form-label" for="notes">Anmerkungen zur Bestellung</label>
            <textarea class="form-input" id="notes" name="notes" rows="3" maxlength="500"
                      placeholder="z.B. Lieferfenster, besondere Wünsche…"><?= $v('notes') ?></textarea>
            <?php if ($e('notes')): ?><p class="form-error"><?= $e('notes') ?></p><?php endif; ?>
          </div>

          <div class="pt-4 border-t border-border">
            <p class="text-xs text-muted mb-5">Mit deiner Bestellung akzeptierst du unsere AGB. Zahlung via TWINT.</p>
            <button type="submit" class="btn-primary w-full justify-center text-base">
              Weiter zur Zahlung
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>

        </form>
      </div>

      <!-- Order summary -->
      <div class="flex flex-col gap-4 lg:sticky lg:top-24">
        <div class="card p-6">
          <h2 class="text-heading-lg font-semibold text-black mb-5">Deine Bestellung</h2>
          <div class="flex flex-col divide-y divide-border/60">
            <?php foreach ($cartItems as $item): ?>
            <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
              <div class="flex-1 min-w-0">
                <p class="font-medium text-black text-sm leading-snug"><?= htmlspecialchars($item['name']) ?></p>
                <p class="text-xs text-muted mt-0.5"><?= ($item['type'] ?? '') === 'custom' ? 'Individuell konfiguriert' : 'Aus dem Shop' ?></p>
              </div>
              <p class="font-semibold text-black text-sm whitespace-nowrap"><?= format_price($item['price']) ?></p>
              <div class="cart-item-actions flex-none">
                <div class="cart-action-default">
                  <button type="button" onclick="cartItemShowConfirm(this)" class="cart-remove-btn" aria-label="<?= htmlspecialchars($item['name']) ?> entfernen">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
                    </svg>
                  </button>
                </div>
                <div class="cart-action-confirm hidden items-center gap-1.5">
                  <span class="text-xs font-medium text-black whitespace-nowrap">Entfernen?</span>
                  <form method="POST" action="/cart/remove" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="board_id" value="<?= htmlspecialchars((string)($item['cart_key'] ?? $item['board_id'])) ?>">
                    <button type="submit" class="cart-confirm-yes">Ja</button>
                  </form>
                  <button type="button" onclick="cartItemCancelConfirm(this)" class="cart-confirm-no">Nein</button>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="border-t border-border mt-5 pt-4 flex flex-col gap-2">
            <div class="flex justify-between text-sm text-muted">
              <span>Zwischensumme</span>
              <span><?= format_price($cartTotal) ?></span>
            </div>
            <div class="flex justify-between text-sm text-muted">
              <span>Versand</span>
              <span class="text-orange font-medium">Kostenlos</span>
            </div>
            <div class="flex justify-between font-bold text-black mt-1 pt-2 border-t border-border">
              <span>Total</span>
              <span><?= format_price($cartTotal) ?></span>
            </div>
          </div>
        </div>

        <p class="text-xs text-muted text-center">Lieferzeit 4–6 Wochen · Handgefertigt in der Schweiz</p>
      </div>

    </div>
  </div>
</section>

<?php if (!empty($errors)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var first = document.querySelector('.has-error');
  if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
});
</script>
<?php endif; ?>
