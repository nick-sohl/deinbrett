<?php
use DeinBrett\Domain\Data\BoardData;
$woodTypes     = BoardData::woodTypes();
$sizes         = BoardData::sizes();
$constructions = BoardData::constructions();
$extrasGrouped = BoardData::extrasGrouped();
$initialResult = BoardData::calculatePrice('eiche', 'L', 'stirnholz', []);
$initialTotal  = $initialResult['total'];
?>
<section id="kalkulator" class="bg-primary section-pad">
  <div class="max-w-[1280px] mx-auto container-pad flex flex-col gap-12 md:gap-16">

    <!-- Header -->
    <div class="flex flex-wrap gap-4 md:gap-6 items-start" data-anim>
      <p class="section-label w-full sm:w-[180px] shrink-0 sm:pt-1 text-white/40">Konfigurator</p>
      <div class="flex flex-col gap-3 flex-1 min-w-0">
        <h2 class="text-display-sm font-bold text-white">Die beste Zeit für dein Brett ist jetzt.</h2>
        <p class="text-display-sm font-light text-gradient"
           style="background-image: linear-gradient(135deg, #fde68a 0%, #f59e0b 40%, #d97706 100%)">
          Ab <?= $sizes['S']['base_price'] ?> Franken
        </p>
      </div>
    </div>

    <!-- Configurator card -->
    <div id="kalkulator-card" class="card" data-anim>

      <!-- Mobile step progress bar (hidden on desktop) -->
      <div id="step-progress" class="lg:hidden border-b border-border px-6 py-4 flex items-center">
        <?php for ($i = 1; $i <= 4; $i++): ?>
          <div class="step-dot" data-step="<?= $i ?>"><?= $i ?></div>
          <?php if ($i < 4): ?><div class="step-line" data-after="<?= $i ?>"></div><?php endif; ?>
        <?php endfor; ?>
      </div>

      <!-- lg:h-[720px] gives the grid a definite height so overflow-y-auto on the form works -->
      <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] lg:h-[720px]">

        <!-- Left: options — scrollable on desktop, wizard on mobile -->
        <form
          id="kalkulator-form"
          hx-post="/api/kalkulator/calculate"
          hx-trigger="change"
          hx-target="#kalkulator-summary"
          hx-swap="innerHTML"
          hx-indicator="#kalkulator-spinner"
          class="overflow-y-auto border-b lg:border-b-0 lg:border-r border-border divide-y divide-border"
        >
          <?= csrf_field() ?>

          <!-- Step 1: Holzart -->
          <fieldset class="kalkulator-step flex flex-col gap-4 p-8 md:p-10" data-step="1">
            <legend class="sr-only">Holzart</legend>
            <div class="lg:hidden">
              <p class="text-xs text-muted uppercase tracking-widest font-medium mb-1">Schritt 1 von 4</p>
              <h3 class="text-heading-lg font-semibold text-black">Welches Holz?</h3>
            </div>
            <p class="hidden lg:block text-heading-lg font-semibold text-black">Holzart</p>
            <div class="flex flex-wrap gap-2.5">
              <?php foreach ($woodTypes as $id => $w): ?>
                <label class="option-pill">
                  <input type="radio" name="wood" value="<?= htmlspecialchars($id) ?>"
                    <?= ($id === 'eiche') ? 'checked' : '' ?>>
                  <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <?= htmlspecialchars($w['name']) ?>
                  <?php if ($w['price_add'] > 0): ?>
                    <span class="text-muted text-xs font-normal">+<?= $w['price_add'] ?></span>
                  <?php endif; ?>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <!-- Step 2: Grösse -->
          <fieldset class="kalkulator-step flex flex-col gap-4 p-8 md:p-10" data-step="2">
            <legend class="sr-only">Grösse</legend>
            <div class="lg:hidden">
              <p class="text-xs text-muted uppercase tracking-widest font-medium mb-1">Schritt 2 von 4</p>
              <h3 class="text-heading-lg font-semibold text-black">Wie gross soll es werden?</h3>
            </div>
            <p class="hidden lg:block text-heading-lg font-semibold text-black">Grösse</p>
            <div class="flex flex-wrap gap-2.5">
              <?php foreach ($sizes as $id => $s): ?>
                <label class="option-pill">
                  <input type="radio" name="size" value="<?= htmlspecialchars($id) ?>"
                    <?= ($id === 'L') ? 'checked' : '' ?>>
                  <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span class="font-semibold"><?= htmlspecialchars($s['label']) ?></span>
                  <span class="text-muted text-xs font-normal"><?= $s['length'] ?>×<?= $s['width'] ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <!-- Step 3: Bauweise -->
          <fieldset class="kalkulator-step flex flex-col gap-4 p-8 md:p-10" data-step="3">
            <legend class="sr-only">Bauweise</legend>
            <div class="lg:hidden">
              <p class="text-xs text-muted uppercase tracking-widest font-medium mb-1">Schritt 3 von 4</p>
              <h3 class="text-heading-lg font-semibold text-black">Welche Bauweise?</h3>
            </div>
            <p class="hidden lg:block text-heading-lg font-semibold text-black">Bauweise</p>
            <div class="flex flex-wrap gap-2.5">
              <?php foreach ($constructions as $id => $c): ?>
                <label class="option-pill">
                  <input type="radio" name="construction" value="<?= htmlspecialchars($id) ?>"
                    <?= ($id === 'stirnholz') ? 'checked' : '' ?>>
                  <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <?= htmlspecialchars($c['name']) ?>
                  <?php if ($c['price_add'] > 0): ?>
                    <span class="text-muted text-xs font-normal">+<?= $c['price_add'] ?></span>
                  <?php endif; ?>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <!-- Step 4: Extras -->
          <fieldset class="kalkulator-step flex flex-col gap-4 p-8 md:p-10" data-step="4">
            <legend class="sr-only">Extras</legend>
            <div class="lg:hidden">
              <p class="text-xs text-muted uppercase tracking-widest font-medium mb-1">Schritt 4 von 4</p>
              <h3 class="text-heading-lg font-semibold text-black">Extras dazunehmen?</h3>
            </div>
            <p class="hidden lg:block text-heading-lg font-semibold text-black">Extras</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <?php foreach ($extrasGrouped as $groupKey => $group): ?>
                <?php if (empty($group['items'])) continue; ?>
                <div class="bg-accent rounded-2xl p-4 flex flex-col gap-3">
                  <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-black uppercase tracking-wider"><?= htmlspecialchars($group['label']) ?></p>
                    <?php if ($group['exclusive']): ?>
                      <span class="text-[10px] text-muted font-medium">max. 1</span>
                    <?php endif; ?>
                  </div>
                  <div class="flex flex-wrap gap-1.5">
                    <?php foreach ($group['items'] as $id => $e): ?>
                      <label class="option-pill text-sm">
                        <input type="checkbox" name="extras[]" value="<?= htmlspecialchars($id) ?>"
                               <?= $group['exclusive'] ? 'data-exclusive-group="' . htmlspecialchars($groupKey) . '"' : '' ?>>
                        <svg class="check-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <?= htmlspecialchars($e['name']) ?>
                        <span class="text-muted text-xs font-normal">+<?= $e['price'] ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </fieldset>

        </form>

        <!-- Right: summary + CTA pinned at bottom (desktop only) -->
        <div class="hidden lg:flex flex-col h-full border-t lg:border-t-0">

          <div class="flex-1 overflow-y-auto p-8">
            <div class="flex items-center justify-between mb-5">
              <h3 class="text-heading-lg font-semibold text-black">Dein Brett</h3>
              <span id="kalkulator-spinner" class="htmx-indicator text-xs text-muted">Berechne…</span>
            </div>
            <div id="kalkulator-summary" class="flex flex-col gap-5">
              <?php
              $result = $initialResult;
              include __DIR__ . '/../../../partials/kalkulator-summary.php';
              ?>
            </div>
          </div>

          <div class="shrink-0 p-8 border-t border-border flex flex-col gap-3">
            <div id="product-cta-custom">
              <button type="button"
                      hx-post="/cart/add-custom"
                      hx-include="#kalkulator-form"
                      hx-target="#product-cta-custom"
                      hx-swap="outerHTML"
                      class="btn-primary w-full justify-center">
                In den Warenkorb
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
              </button>
            </div>
            <p class="text-xs text-muted text-center leading-relaxed">
              Lieferzeit 4–6 Wochen · Handgefertigt in der Schweiz
            </p>
          </div>

        </div>

      </div>
    </div>

    <!-- Spacer so mobile bottom bar doesn't overlap last step -->
    <div class="lg:hidden h-20 -mt-8"></div>

  </div>
</section>

<!-- Mobile: step nav + price bar -->
<div id="mobile-kalkulator-bar" class="lg:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-sm border-t border-border z-50" style="display:none">
  <div class="flex items-center gap-3 px-4 py-3">

    <button id="step-prev-btn" type="button" onclick="kalkulatorPrev()"
            class="flex-shrink-0 w-11 h-11 flex items-center justify-center rounded-xl border border-border text-muted transition-colors hover:border-black hover:text-black invisible">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
    </button>

    <div class="flex-1 min-w-0">
      <p class="text-xs text-muted leading-none mb-0.5">Dein Brett</p>
      <p id="mobile-price-display" class="text-xl font-bold leading-none"><?= $initialTotal ?> CHF</p>
    </div>

    <button id="step-next-btn" type="button" onclick="kalkulatorNext()"
            class="btn-primary flex-shrink-0 text-sm px-4 py-2.5">
      Weiter
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </button>

  </div>
</div>

<script>
(function () {
  var step  = 1;
  var total = 4;

  // Show mobile bar while configurator is in view
  var bar     = document.getElementById('mobile-kalkulator-bar');
  var section = document.getElementById('kalkulator');
  if (bar && section && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      if (window.innerWidth >= 1024) return;
      bar.style.display = entries[0].isIntersecting ? 'block' : 'none';
    }, { threshold: 0.05 }).observe(section);
  }

  function go(n) {
    step = Math.max(1, Math.min(total, n));

    document.querySelectorAll('.kalkulator-step').forEach(function (el) {
      el.classList.toggle('is-active', +el.dataset.step === step);
    });
    document.querySelectorAll('.step-dot').forEach(function (el) {
      var s = +el.dataset.step;
      el.classList.toggle('is-active', s === step);
      el.classList.toggle('is-done',   s < step);
    });
    document.querySelectorAll('.step-line').forEach(function (el) {
      el.classList.toggle('is-done', +el.dataset.after < step);
    });

    var prev = document.getElementById('step-prev-btn');
    if (prev) prev.style.visibility = step > 1 ? 'visible' : 'hidden';

    var next = document.getElementById('step-next-btn');
    if (next) {
      if (step === total) {
        next.textContent = 'In den Warenkorb';
      } else {
        next.innerHTML = 'Weiter <svg style="width:.9rem;height:.9rem;display:inline-block;vertical-align:middle;margin-left:.25rem" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>';
      }
    }
  }

  window.kalkulatorNext = function () {
    if (step === total) {
      var form = document.getElementById('kalkulator-form');
      if (form && typeof htmx !== 'undefined') {
        htmx.ajax('POST', '/cart/add-custom', {
          source: form,
          swap: 'none',
          handler: function () { window.location.href = '/checkout'; }
        });
      } else {
        window.location.href = '/checkout';
      }
    } else {
      go(step + 1);
      document.getElementById('kalkulator-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  window.kalkulatorPrev = function () {
    go(step - 1);
    document.getElementById('kalkulator-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  // Sync mobile price after HTMX recalculation
  document.addEventListener('htmx:afterSwap', function (e) {
    if (!e.target || e.target.id !== 'kalkulator-summary') return;
    var src = document.getElementById('kalkulator-total-price');
    var mob = document.getElementById('mobile-price-display');
    if (src && mob) {
      mob.textContent = src.value + ' CHF';
      mob.classList.remove('price-updated'); void mob.offsetWidth; mob.classList.add('price-updated');
    }
    // Flash total row in desktop summary
    var totalRow = e.target.firstElementChild;
    if (totalRow) {
      totalRow.classList.remove('price-updated'); void totalRow.offsetWidth; totalRow.classList.add('price-updated');
    }
  });

  // Exclusive extras — uncheck siblings in same group when one is selected
  document.querySelectorAll('input[data-exclusive-group]').forEach(function (cb) {
    cb.addEventListener('change', function () {
      if (!this.checked) return;
      var grp = this.dataset.exclusiveGroup;
      document.querySelectorAll('input[data-exclusive-group="' + grp + '"]').forEach(function (o) {
        if (o !== cb) o.checked = false;
      });
    });
  });

  var card = document.getElementById('kalkulator-card');
  if (card) card.dataset.wizard = '1';
  go(1);
})();
</script>
