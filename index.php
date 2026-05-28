<?php

session_start();

define('USERS_FILE', __DIR__ . '/data/users.json');
define('ITEMS_FILE', __DIR__ . '/data/items.json');

function readJson(string $file): array {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?? [];
}

function currentUser(): ?array {
    if (!isset($_SESSION['user_id'])) return null;
    $users = readJson(USERS_FILE);
    foreach ($users as $u) {
        if ($u['id'] === $_SESSION['user_id']) return $u;
    }
    return null;
}

$user = currentUser();
?>
<!DOCTYPE html>
<html lang="ro" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GameVault — Colecția ta de jocuri</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ═══════════════════════════════════════
     NAVBAR
═══════════════════════════════════════ -->
<nav class="navbar">
  <a href="index.php" class="navbar-brand">
    <span class="brand-icon">🎮</span>
    <span class="brand-vault">GameVault</span>
  </a>

  <div class="navbar-links">
    <a href="index.php"           class="active" data-i18n="nav_home">Acasă</a>
    <a href="index.php#about"     data-i18n="nav_about">Despre</a>
    <a href="index.php#features"  data-i18n="nav_features">Funcționalități</a>
    <a href="contact.php"         data-i18n="nav_contact">Contact</a>
    <?php if ($user): ?>
    <a href="dashboard.php"       data-i18n="nav_dashboard">Colecție</a>
    <?php endif; ?>
  </div>

  <div class="navbar-actions">
    <select id="langSelect" class="lang-select">
      <option value="ro">RO</option>
      <option value="en">EN</option>
      <option value="ru">RU</option>
    </select>
    <button id="themeToggle" class="icon-btn" title="Schimbă tema">🌙</button>

    <?php if ($user): ?>
      <span class="navbar-user">▸ <?= htmlspecialchars($user['name']) ?></span>
      <a href="logout.php" class="btn btn-ghost btn-sm" data-i18n="nav_logout">Ieșire</a>
    <?php else: ?>
      <a href="login.php"    class="btn btn-ghost btn-sm"   data-i18n="nav_login">Autentificare</a>
      <a href="register.php" class="btn btn-primary btn-sm" data-i18n="nav_register">Înregistrare</a>
    <?php endif; ?>

    <button class="hamburger" id="hamburgerBtn" aria-label="Meniu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Meniu mobil -->
<div class="mobile-menu" id="mobileMenu">
  <a href="index.php"          data-i18n="nav_home">Acasă</a>
  <a href="index.php#about"    data-i18n="nav_about">Despre</a>
  <a href="index.php#features" data-i18n="nav_features">Funcționalități</a>
  <a href="contact.php"        data-i18n="nav_contact">Contact</a>
  <?php if ($user): ?>
    <a href="dashboard.php" data-i18n="nav_dashboard">Colecție</a>
    <a href="logout.php"    data-i18n="nav_logout">Ieșire</a>
  <?php else: ?>
    <a href="login.php"    data-i18n="nav_login">Autentificare</a>
    <a href="register.php" data-i18n="nav_register">Înregistrare</a>
  <?php endif; ?>
</div>


<!-- ═══════════════════════════════════════
     HERO
═══════════════════════════════════════ -->
<section class="hero">
  <div class="hero-bg"></div>

  <div class="container">
    <p class="hero-eyebrow" data-i18n="hero_eyebrow">Colecția ta de jocuri</p>

    <h1 data-i18n="hero_title">
      Gestionează-ți
      <span class="accent">biblioteca</span>
      <span class="accent2">gaming</span>
    </h1>

    <p class="hero-sub" data-i18n="hero_sub">
      Adaugă jocuri, urmărește progresul, evaluează experiența.<br>
      Totul într-un singur loc.
    </p>

    <div class="hero-cta">
      <?php if ($user): ?>
        <a href="dashboard.php" class="btn btn-primary">Colecția mea →</a>
      <?php else: ?>
        <a href="register.php" class="btn btn-primary" data-i18n="hero_start">Start Game</a>
        <a href="login.php"    class="btn btn-ghost"   data-i18n="hero_demo">Explorează</a>
      <?php endif; ?>
    </div>
  </div>
</section>



<section class="section section-about" id="about">
  <div class="container">
    <span class="section-label" data-i18n="nav_about">// about</span>
    <div class="divider"></div>
    <h2>What is GameVault?</h2>
    <p class="about-text">
      GameVault is your personal game tracking companion. Organize your collection,
      log hours played, track status per title, and leave personal reviews —
      all stored locally in JSON files. No cloud, no database, just yours.
    </p>
  </div>
</section>



<section class="section section-features" id="features">
  <div class="container">
    <span class="section-label">// features</span>
    <h2>Everything you need</h2>

    <div class="features-grid">

      <?php
      $features = [
        ['🎮', 'Personal Collection',
         'Add any game with title, platform, genre, and your own rating.'],
        ['📊', 'Status Tracking',
         'Mark games as Playing, Completed, Backlog, or Dropped.'],
        ['⏱️', 'Playtime Logging',
         'Keep track of how many hours you invest in your gaming stats.'],
        ['⭐', 'Rating System',
         'Rate each game 1–5 stars and write a short personal review.'],
        ['🌙', 'Dark / Light Mode',
         'UI optimized for both late-night sessions and daylight.'],
        ['📱', 'Fully Responsive',
         'Works seamlessly on PC, tablet and mobile.'],
      ];
      foreach ($features as $i => [$icon, $title, $desc]):
      ?>
      <div class="feature-card" style="animation-delay:<?= $i * 80 ?>ms">
        <span class="feature-icon"><?= $icon ?></span>
        <h3 class="feature-title"><?= $title ?></h3>
        <p class="feature-desc"><?= $desc ?></p>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>



<section class="section section-cta">
  <div class="container cta-inner">
    <span class="section-label">// ready?</span>
    <h2>Ready <span class="text-gradient">Player</span> One?</h2>
    <p class="cta-sub">
      Create your account and add your first game in under 60 seconds.
    </p>
    <?php if ($user): ?>
      <a href="dashboard.php" class="btn btn-primary btn-lg">OPEN VAULT →</a>
    <?php else: ?>
      <a href="register.php"  class="btn btn-primary btn-lg" data-i18n="hero_start">CREATE ACCOUNT →</a>
    <?php endif; ?>
  </div>
</section>



<footer class="footer">
  <span class="footer-brand">GameVault</span>
  <span class="footer-copy" data-i18n="footer_copy">© 2025 GameVault. Toate drepturile rezervate.</span>
</footer>


<script src="js/script.js"></script>
</body>
</html>
