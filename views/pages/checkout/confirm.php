<!-- Order confirmation -->
<section class="bg-primary pt-24 pb-10">
  <div class="max-w-[1280px] mx-auto container-pad">
    <p class="section-label text-white/40 mb-3">Bestätigung</p>
    <h1 class="text-display-sm font-bold text-white">Danke für deine Bestellung.</h1>
    <p class="text-display-sm font-light text-gradient mt-1"
       style="background-image: linear-gradient(135deg, #fde68a 0%, #f59e0b 40%, #d97706 100%)">
      Dein Brett ist in guten Händen.
    </p>
  </div>
</section>

<section class="bg-accent section-pad">
  <div class="max-w-[860px] mx-auto container-pad flex flex-col gap-8">

    <!-- Success card -->
    <div class="card p-8 md:p-10 flex flex-col items-center gap-6 text-center" data-anim>
      <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center">
        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <div>
        <p class="text-xs text-muted uppercase tracking-widest font-semibold mb-1">Bestellreferenz</p>
        <p class="text-2xl font-bold text-black font-mono"><?= htmlspecialchars($order->reference) ?></p>
      </div>
      <p class="text-muted max-w-md">
        Wir haben deine Bestellung erhalten und eine Bestätigung an
        <strong><?= htmlspecialchars($order->email) ?></strong> gesendet.
        Wir melden uns innerhalb von 24 Stunden.
      </p>
    </div>

    <!-- Order details -->
    <div class="card p-8 md:p-10" data-anim data-anim-delay="100">
      <h2 class="text-heading-lg font-semibold text-black mb-6">Bestelldetails</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
        <div>
          <p class="text-xs font-semibold text-muted uppercase tracking-widest mb-3">Bestellte Artikel</p>
          <div class="flex flex-col gap-3">
            <?php foreach ($items as $item): ?>
            <div class="flex justify-between gap-3">
              <p class="text-sm text-black font-medium"><?= htmlspecialchars($item['product_name']) ?></p>
              <p class="text-sm font-semibold text-black whitespace-nowrap"><?= format_price($item['unit_price']) ?></p>
            </div>
            <?php endforeach; ?>
            <div class="flex justify-between gap-3 pt-3 border-t border-border font-bold text-black">
              <p>Total</p>
              <p><?= format_price($order->total) ?></p>
            </div>
          </div>
        </div>

        <div>
          <p class="text-xs font-semibold text-muted uppercase tracking-widest mb-3">Lieferadresse</p>
          <address class="text-sm text-muted not-italic leading-relaxed">
            <?= htmlspecialchars($order->fullName()) ?><br>
            <?= htmlspecialchars($order->address) ?><br>
            <?= htmlspecialchars($order->zip) ?> <?= htmlspecialchars($order->city) ?><br>
            <?= htmlspecialchars($order->country) ?>
          </address>

          <p class="text-xs font-semibold text-muted uppercase tracking-widest mb-3 mt-6">Zahlung</p>
          <p class="text-sm text-muted">TWINT · Bezahlt ✓</p>
        </div>
      </div>
    </div>

    <!-- What's next -->
    <div class="card p-8 md:p-10 bg-primary text-white" data-anim data-anim-delay="200">
      <h2 class="text-heading-lg font-semibold mb-4">Wie geht es weiter?</h2>
      <ol class="flex flex-col gap-4">
        <?php foreach ([
          ['Auftragsbestätigung', 'Du erhältst eine E-Mail-Bestätigung mit allen Details deiner Bestellung.'],
          ['Handarbeit beginnt', 'Wir starten die Fertigung deines Brettes. Handarbeit braucht Zeit – und das ist gut so.'],
          ['Versand & Tracking', 'Nach 4–6 Wochen wird dein Brett sorgfältig verpackt und versandt. Du erhältst eine Versandbestätigung.'],
        ] as $i => [$title, $text]): ?>
        <li class="flex gap-4">
          <span class="flex-none w-7 h-7 rounded-full border border-white/25 flex items-center justify-center text-xs font-bold text-white/60"><?= $i + 1 ?></span>
          <div>
            <p class="font-semibold text-white text-sm"><?= $title ?></p>
            <p class="text-sm text-white/60 mt-0.5"><?= $text ?></p>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center" data-anim data-anim-delay="300">
      <a href="/shop" class="btn-primary">Zurück zum Shop</a>
      <a href="/" class="btn-ghost bg-black/5 text-black border-black/10 hover:bg-black/10">Zur Startseite</a>
    </div>

  </div>
</section>
