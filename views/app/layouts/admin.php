<?php
/**
 * Variables available:
 *   $activeNav string
 *   $currentUser DeinBrett\Domain\Entity\User|null
 *   $pageTitle string|null
 *   $adminView string  — path (relative to views/) of the inner template
 *   $data      array   — variables extracted for the inner template
 */

$flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);
$pageTitle = $pageTitle ?? 'Admin';

$nav = [
    'dashboard'    => ['label' => 'Dashboard',    'url' => '/admin'],
    'products'     => ['label' => 'Produkte',     'url' => '/admin/products'],
    'orders'       => ['label' => 'Bestellungen', 'url' => '/admin/orders'],
    'woods'        => ['label' => 'Holzarten',    'url' => '/admin/options/woods'],
    'sizes'        => ['label' => 'Grössen',      'url' => '/admin/options/sizes'],
    'constructions'=> ['label' => 'Bauweisen',    'url' => '/admin/options/constructions'],
    'extras'       => ['label' => 'Extras',       'url' => '/admin/options/extras'],
    'settings'     => ['label' => 'Einstellungen','url' => '/admin/settings'],
];
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> – DeinBrett CMS</title>
  <link rel="stylesheet" href="/css/app.css">
  <script src="/scripts/htmx.min.js"></script>
</head>
<body class="min-h-screen bg-neutral-100 text-neutral-900">
<div class="flex min-h-screen">
  <aside class="w-56 bg-neutral-900 text-neutral-100 flex flex-col">
    <div class="px-5 py-6 border-b border-neutral-800">
      <a href="/admin" class="block text-lg font-semibold tracking-tight">DeinBrett CMS</a>
    </div>
    <nav class="flex-1 py-4">
      <?php foreach ($nav as $key => $item):
        $isActive = ($activeNav === $key);
      ?>
        <a href="<?= $item['url'] ?>"
           class="block px-5 py-2 text-sm hover:bg-neutral-800 <?= $isActive ? 'bg-neutral-800 text-white' : 'text-neutral-300' ?>">
          <?= htmlspecialchars($item['label']) ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="p-4 border-t border-neutral-800 text-xs text-neutral-400">
      <?php if ($currentUser): ?>
        <div class="mb-2 text-neutral-200">
          <?= htmlspecialchars(trim($currentUser->first_name . ' ' . $currentUser->last_name)) ?>
        </div>
        <div class="mb-3 truncate"><?= htmlspecialchars($currentUser->email) ?></div>
      <?php endif; ?>
      <form method="post" action="/admin/logout">
        <?= csrf_field() ?>
        <button type="submit" class="text-neutral-300 hover:text-white underline">Logout</button>
      </form>
      <div class="mt-3">
        <a href="/" class="text-neutral-500 hover:text-neutral-200">← Zur Website</a>
      </div>
    </div>
  </aside>

  <main class="flex-1 flex flex-col">
    <header class="bg-white border-b border-neutral-200 px-8 py-4">
      <h1 class="text-lg font-semibold"><?= htmlspecialchars($pageTitle) ?></h1>
    </header>
    <div class="flex-1 p-8">
      <?php if ($flash): ?>
        <div class="mb-4 rounded border px-4 py-2 text-sm
          <?= $flash['type'] === 'error' ? 'border-red-200 bg-red-50 text-red-700' : 'border-green-200 bg-green-50 text-green-700' ?>">
          <?= htmlspecialchars($flash['message']) ?>
        </div>
      <?php endif; ?>

      <?php include __DIR__ . '/../../pages/admin/' . $adminView . '.php'; ?>
    </div>
  </main>
</div>
</body>
</html>
