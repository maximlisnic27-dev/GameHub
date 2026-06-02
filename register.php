<?php
require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/functions.php';
if (isLoggedIn()) { header('Location: dashboard.php'); exit; }
$alertHtml = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $r = registerUser($_POST['name']??'', $_POST['email']??'', $_POST['password']??'');
    if ($r['ok']) { loginUser($_POST['email'], $_POST['password']); header('Location: dashboard.php?welcome=1'); exit; }
    $alertHtml = alert($r['msg'], 'error');
}
?>
<!DOCTYPE html>
<html lang="ro" data-theme="dark">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — GameVault</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?= navbarHtml('register.php') ?>
<div class="container" style="padding-top:3rem;padding-bottom:4rem">
  <div class="form-card">
    <div class="form-card-header">
      <p style="font-size:2.5rem;margin-bottom:.6rem;filter:drop-shadow(0 0 12px rgba(124,58,255,0.4))">🎮</p>
      <h2>CREATE ACCOUNT</h2>
      <p>Join the GameVault community</p>
    </div>
    <?= $alertHtml ?>
    <div id="alertBox"></div>
    <form method="POST" id="regForm" novalidate>
      <div class="form-group"><label>Username / Nume</label>
        <input type="text" name="name" placeholder="GamerTag sau nume" value="<?= htmlspecialchars($_POST['name']??'') ?>" required minlength="2">
      </div>
      <div class="form-group"><label>Email</label>
        <input type="email" name="email" placeholder="email@exemplu.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
      </div>
      <div class="form-group"><label>Parolă <span class="text-muted">(min 6 caractere)</span></label>
        <input type="password" name="password" placeholder="••••••••" required minlength="6">
      </div>
      <div class="form-group"><label>Confirmă parola</label>
        <input type="password" name="password_confirm" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-primary w-full" style="margin-top:.5rem">CREATE VAULT →</button>
    </form>
    <div class="form-footer">Ai deja cont? <a href="login.php">Autentifică-te</a></div>
  </div>
</div>
<script src="js/script.js"></script>
<script>
document.getElementById('regForm').addEventListener('submit', function(e) {
  const p1 = this.querySelector('[name="password"]').value;
  const p2 = this.querySelector('[name="password_confirm"]').value;
  if (p1 !== p2) { e.preventDefault(); showAlert('alertBox','Parolele nu coincid.','error'); }
});
</script>
</body>
</html>
