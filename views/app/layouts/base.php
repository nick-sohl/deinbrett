
<?php ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dein Brett</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="/scripts/htmx.min.js"></script>
  </head>
  <body>
      <?php include __DIR__ . "/../../widgets/hero/hero.php"; ?>
      <main id="main">
        <?php
          extract($data);
          include __DIR__ . "/../../pages/{$page}/{$view}.php";
        ?>
      </main>
      <?php include __DIR__ . "/../../widgets/footer/footer.php"; ?>
  </body>
</html>
