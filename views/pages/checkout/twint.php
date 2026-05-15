<!-- Twint payment mock -->
<section class="bg-primary pt-24 pb-10">
  <div class="max-w-[1280px] mx-auto container-pad">
    <p class="section-label text-white/40 mb-3">Zahlung</p>
    <h1 class="text-display-sm font-bold text-white">Bezahlen mit TWINT.</h1>
  </div>
</section>

<section class="bg-accent section-pad">
  <div class="max-w-[1280px] mx-auto container-pad">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 lg:gap-12 items-start">

      <!-- Twint payment area -->
      <div class="card p-8 md:p-10 flex flex-col items-center gap-8">

        <!-- TWINT branding -->
        <div class="flex flex-col items-center gap-2">
          <div class="w-16 h-16 rounded-2xl bg-[#eb0021] flex items-center justify-center">
            <span class="text-white font-black text-xl tracking-tight">T</span>
          </div>
          <p class="text-lg font-bold text-black">TWINT</p>
        </div>

        <!-- Amount -->
        <div class="text-center">
          <p class="text-sm text-muted mb-1">Zu bezahlen</p>
          <p class="text-4xl font-bold text-black">CHF <?= number_format($order->total, 2, '.', "'") ?></p>
          <p class="text-xs text-muted mt-1">Ref. <?= htmlspecialchars($order->reference) ?></p>
        </div>

        <!-- QR placeholder -->
        <div class="w-52 h-52 bg-white border-2 border-border rounded-2xl flex flex-col items-center justify-center gap-3 p-4">
          <svg class="w-32 h-32 text-black" viewBox="0 0 100 100" fill="currentColor" aria-hidden="true">
            <!-- Simple QR-like placeholder pattern -->
            <rect x="5"  y="5"  width="30" height="30" rx="2" fill="none" stroke="currentColor" stroke-width="4"/>
            <rect x="12" y="12" width="16" height="16" rx="1"/>
            <rect x="65" y="5"  width="30" height="30" rx="2" fill="none" stroke="currentColor" stroke-width="4"/>
            <rect x="72" y="12" width="16" height="16" rx="1"/>
            <rect x="5"  y="65" width="30" height="30" rx="2" fill="none" stroke="currentColor" stroke-width="4"/>
            <rect x="12" y="72" width="16" height="16" rx="1"/>
            <rect x="45" y="5"  width="8"  height="8"/>
            <rect x="45" y="17" width="8"  height="8"/>
            <rect x="45" y="29" width="8"  height="8"/>
            <rect x="5"  y="45" width="8"  height="8"/>
            <rect x="17" y="45" width="8"  height="8"/>
            <rect x="29" y="45" width="8"  height="8"/>
            <rect x="45" y="45" width="8"  height="8"/>
            <rect x="57" y="45" width="8"  height="8"/>
            <rect x="69" y="45" width="8"  height="8"/>
            <rect x="81" y="45" width="8"  height="8"/>
            <rect x="45" y="57" width="8"  height="8"/>
            <rect x="57" y="57" width="8"  height="8"/>
            <rect x="69" y="69" width="8"  height="8"/>
            <rect x="81" y="57" width="8"  height="8"/>
            <rect x="57" y="69" width="8"  height="8"/>
            <rect x="81" y="69" width="8"  height="8"/>
            <rect x="57" y="81" width="8"  height="8"/>
            <rect x="69" y="81" width="8"  height="8"/>
            <rect x="81" y="81" width="8"  height="8"/>
          </svg>
          <p class="text-[10px] text-muted text-center font-medium">QR-Code mit TWINT scannen</p>
        </div>

        <p class="text-sm text-muted text-center max-w-xs">
          Öffne die TWINT App und scanne den QR-Code, um die Zahlung abzuschliessen.
        </p>

        <!-- Dev: simulate payment -->
        <div class="w-full border-t border-border pt-6 flex flex-col items-center gap-3">
          <p class="text-xs font-semibold text-muted uppercase tracking-widest">Entwicklungsmodus</p>
          <form method="POST" action="/checkout/twint">
            <?= csrf_field() ?>
            <button type="submit" class="btn-primary bg-[#eb0021] hover:bg-[#c8001c]">
              Zahlung simulieren
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </button>
          </form>
          <p class="text-xs text-muted">In der Produktion: echte TWINT-Integration via Worldline/SIX</p>
        </div>

      </div>

      <!-- Order summary -->
      <div class="card p-6 lg:sticky lg:top-24">
        <h2 class="text-heading-lg font-semibold text-black mb-5">Bestellung</h2>

        <div class="flex flex-col gap-3 mb-5">
          <?php foreach ($items as $item): ?>
          <div class="flex items-start justify-between gap-3">
            <p class="font-medium text-black text-sm leading-snug flex-1"><?= htmlspecialchars($item['product_name']) ?></p>
            <p class="font-semibold text-black text-sm whitespace-nowrap"><?= format_price($item['unit_price']) ?></p>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="border-t border-border pt-4 space-y-2">
          <div class="flex justify-between text-sm text-muted">
            <span>Versand</span>
            <span class="text-orange font-medium">Kostenlos</span>
          </div>
          <div class="flex justify-between font-bold text-black mt-2 pt-2 border-t border-border">
            <span>Total</span>
            <span><?= format_price($order->total) ?></span>
          </div>
        </div>

        <div class="mt-5 pt-5 border-t border-border text-sm text-muted space-y-1">
          <p class="font-semibold text-black"><?= htmlspecialchars($order->fullName()) ?></p>
          <p><?= htmlspecialchars($order->address) ?></p>
          <p><?= htmlspecialchars($order->zip) ?> <?= htmlspecialchars($order->city) ?></p>
        </div>
      </div>

    </div>
  </div>
</section>
