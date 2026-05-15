<?php ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DeinBrett – Wo Handwerk zu Luxus wird</title>
  <meta name="description" content="Handgefertigte Schneidebretter aus massivem Holz. Entworfen für Menschen, die Küche als Erlebnis verstehen.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/app.css">
  <script src="/scripts/htmx.min.js"></script>
</head>
<body>
  <?php
  $showHero  = $data['showHero']  ?? true;
  $cartCount = $data['cartCount'] ?? 0;
  if ($showHero):
    include __DIR__ . "/../../widgets/hero/hero.php";
  else:
    include __DIR__ . "/../../widgets/hero/navbar.php";
  endif;
  ?>

  <main id="main">
    <?php
      extract($data);
      include __DIR__ . "/../../pages/{$page}/{$view}.php";
    ?>
  </main>

  <?php include __DIR__ . "/../../widgets/footer/footer.php"; ?>

  <?php include __DIR__ . "/../../partials/cart-toast.php"; ?>

  <script>
    /* Navbar: scrolled state via hero observer or scroll position fallback */
    const nav  = document.getElementById('site-nav');
    const hero = document.getElementById('site-hero');
    if (nav && hero) {
      new IntersectionObserver(
        ([e]) => nav.classList.toggle('nav--scrolled', !e.isIntersecting),
        { threshold: 0.05 }
      ).observe(hero);
    } else if (nav) {
      function updateNav() { nav.classList.toggle('nav--scrolled', window.scrollY > 10); }
      window.addEventListener('scroll', updateNav, { passive: true });
      updateNav();
    }

    /* Scroll entrance animations */
    document.querySelectorAll('[data-anim]').forEach(function(el) {
      new IntersectionObserver(function(entries, obs) {
        if (!entries[0].isIntersecting) return;
        var delay = el.dataset.animDelay ? +el.dataset.animDelay : 0;
        setTimeout(function() { el.classList.add('anim-visible'); }, delay);
        obs.disconnect();
      }, { threshold: 0.1 }).observe(el);
    });

    /* Active nav link tracking */
    (function () {
      var links = document.querySelectorAll('.nav-link[href^="#"]');
      if (!links.length) return;
      var lastActive = null;
      function update() {
        var threshold = window.scrollY + window.innerHeight * 0.35;
        var active = null;
        links.forEach(function (l) {
          var el = document.getElementById(l.getAttribute('href').slice(1));
          if (el && el.offsetTop <= threshold) active = l;
        });
        if (active === lastActive) return;
        if (lastActive) lastActive.classList.remove('nav-link--active');
        if (active)     active.classList.add('nav-link--active');
        lastActive = active;
      }
      window.addEventListener('scroll', update, { passive: true });
      update();
    })();

    /* Mobile menu toggle */
    const mobileBtn  = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileBtn?.addEventListener('click', () => {
      const isOpen = !mobileMenu.classList.contains('hidden');
      mobileMenu.classList.toggle('hidden', isOpen);
      mobileBtn.setAttribute('aria-expanded', String(!isOpen));
    });
    document.querySelectorAll('#mobile-menu a').forEach(a =>
      a.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        mobileBtn.setAttribute('aria-expanded', 'false');
      })
    );
  </script>
</body>
</html>
