<?php /** @var ?string $error */ ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin – DeinBrett</title>
  <link rel="stylesheet" href="/css/app.css">
</head>
<body class="min-h-screen bg-neutral-100 flex items-center justify-center p-4">
  <div class="w-full max-w-sm bg-white shadow-md rounded-lg p-8">
    <div class="mb-6 text-center">
      <h1 class="text-xl font-semibold text-neutral-900">DeinBrett CMS</h1>
      <p class="text-sm text-neutral-500 mt-1">Admin-Login</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="mb-4 rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="/admin/login" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1" for="email">E-Mail</label>
        <input id="email" name="email" type="email" required autocomplete="username"
               class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-neutral-900 focus:outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1" for="password">Passwort</label>
        <input id="password" name="password" type="password" required autocomplete="current-password"
               class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:border-neutral-900 focus:outline-none">
      </div>
      <button type="submit"
              class="w-full rounded bg-neutral-900 py-2 text-sm font-medium text-white hover:bg-neutral-800">
        Einloggen
      </button>
    </form>
  </div>
</body>
</html>
