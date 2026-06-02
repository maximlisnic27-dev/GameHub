<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/php/functions.php';

$alertHtml = '';
$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $errors = [];
    if (strlen($name) < 2)                          $errors[] = 'Numele prea scurt.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalid.';
    if (strlen($subject) < 3)                       $errors[] = 'Subiectul e obligatoriu.';
    if (strlen($message) < 10)                      $errors[] = 'Mesajul e prea scurt.';
    if ($errors) $alertHtml = alert(implode(' | ', $errors), 'error');
    else { $alertHtml = alert('Mesaj transmis. Te contactăm în curând!', 'success'); $sent = true; }
}
?>



<!DOCTYPE html>
<html lang="ro" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact — GameVault</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?= navbarHtml('contact.php') ?>

<div class="container" style="padding-top:3rem;padding-bottom:4rem">
  <div class="divider"></div>
  <span class="section-label">Contact</span>
  <h2 style="margin-bottom:2.5rem">SUPPORT CENTER</h2>

  <div class="contact-grid">

    <!-- Coloana stânga -->
    <div class="contact-info anim-fade">
      <p>Bug report, sugestie de funcționalitate sau altă întrebare? Scrie-ne și răspundem rapid.</p>
      <br>
      <div class="contact-detail">📧 <span>contact@gamevault.ro</span></div>
      <div class="contact-detail">💬 <span>Discord: gamevault.gg</span></div>
      <div class="contact-detail">⏱️ <span>Răspuns în 24–48h</span></div>
      <br>
      <div style="padding:1.5rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius)">
        <h3 style="margin-bottom:1rem;font-size:.9rem">FAQ</h3>
        <details style="margin-bottom:.6rem">
          <summary style="cursor:pointer;color:var(--text-muted);font-size:.85rem">Datele mele sunt în siguranță?</summary>
          <p style="font-size:.82rem;color:var(--text-muted);margin-top:.5rem;padding-left:1rem">Parolele sunt stocate cu bcrypt. Datele rămân private pe serverul tău.</p>
        </details>
        <details style="margin-bottom:.6rem">
          <summary style="cursor:pointer;color:var(--text-muted);font-size:.85rem">Pot importa din Steam / PlayStation?</summary>
          <p style="font-size:.82rem;color:var(--text-muted);margin-top:.5rem;padding-left:1rem">Funcție planificată pentru versiunile viitoare.</p>
        </details>
        <details>
          <summary style="cursor:pointer;color:var(--text-muted);font-size:.85rem">GameVault este gratuit?</summary>
          <p style="font-size:.82rem;color:var(--text-muted);margin-top:.5rem;padding-left:1rem">Da, 100% gratuit și open-source.</p>
        </details>
      </div>
    </div>

    <!-- Coloana dreapta -->
    <div class="form-card">
      <?= $alertHtml ?>
      <?php if (!$sent): ?>
      <form method="POST" novalidate>
        <div class="form-row">
          <div class="form-group">
            <label>Nume *</label>
            <input type="text" name="name" placeholder="Numele tău" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="email@exemplu.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label>Subiect *</label>
          <input type="text" name="subject" placeholder="ex: Bug report, Sugestie..." value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Mesaj *</label>
          <textarea name="message" rows="5" placeholder="Descrie problema sau sugestia ta..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-full">SEND MESSAGE →</button>
      </form>
      <?php else: ?>
      <div class="empty-state">
        <div class="icon">✅</div>
        <h3>Message Sent!</h3>
        <p>Îți mulțumim. Te contactăm în curând.</p>
        <a href="index.php" class="btn btn-ghost" style="margin-top:1rem">← HOME</a>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<?= footerHtml() ?>
<script src="js/script.js"></script>
</body>
</html>