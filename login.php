<?php

require_once __DIR__ . '/php/functions.php';
require_once __DIR__ . '/php/auth.php';
if (isLoggedIn()) { header('Location: dashboard.php'); exit; }
$alertHtml = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $r = loginUser($_POST['email']??'', $_POST['password']??'');
    if ($r['ok']) { header('Location: dashboard.php'); exit; }
    $alertHtml = alert($r['msg'], 'error');
}
?>
<!DOCTYPE html>
<html lang="ro" data-theme="dark">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — GameVault</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?= navbarHtml('login.php') ?>
<div class="container login-center">
  <div class="form-card">
    <div class="form-card-header">
      <p style="font-size:2.5rem;margin-bottom:.6rem;filter:drop-shadow(0 0 12px rgba(0,229,255,0.4))">🔐</p>
      <h2>LOGIN</h2>
      <p>Welcome back, Player</p>
    </div>
    <?= $alertHtml ?>
    <form method="POST" novalidate>
      <div class="form-group"><label>Email</label>
        <input type="email" name="email" placeholder="email@exemplu.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
      </div>
      <div class="form-group"><label>Parolă</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-primary w-full" style="margin-top:.5rem">ENTER VAULT →</button>
    </form>
    <div class="form-footer">Nu ai cont? <a href="register.php">Creează unul</a></div>
  </div>
</div>
<script src="js/script.js"></script>
</body>
</html>
