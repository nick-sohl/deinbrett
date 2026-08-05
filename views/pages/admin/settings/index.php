<?php /** @var array $settings */ ?>
<div class="max-w-xl">
  <form method="post" action="/admin/settings" class="space-y-4 bg-white rounded-lg border border-neutral-200 p-6">
    <?= csrf_field() ?>
    <div>
      <label class="block text-sm font-medium mb-1">Versandkosten (CHF)</label>
      <input name="shipping_cost" type="number" step="0.01" min="0"
             value="<?= htmlspecialchars($settings['shipping_cost'] ?? '0') ?>"
             class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
      <p class="mt-1 text-xs text-neutral-500">Wird zum Zwischensumme addiert. 0 = kostenloser Versand.</p>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Admin-E-Mail (für neue Bestellungen)</label>
      <input name="admin_email" type="email"
             value="<?= htmlspecialchars($settings['admin_email'] ?? '') ?>"
             class="w-full rounded border border-neutral-300 px-3 py-2 text-sm">
    </div>
    <div class="pt-3 border-t border-neutral-100">
      <button type="submit" class="rounded bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">Speichern</button>
    </div>
  </form>
</div>
