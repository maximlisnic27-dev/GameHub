<?php

require_once __DIR__ . '/php/auth.php';
require_once __DIR__ . '/php/functions.php';
requireLogin();

$user  = currentUser();
$games = getUserGames($user['id']);
$total = count($games);

$completed = count(array_filter($games, fn($g) => $g['status']==='completed'));
$playing   = count(array_filter($games, fn($g) => $g['status']==='playing'));
$backlog   = count(array_filter($games, fn($g) => $g['status']==='backlog'));
$totalHrs  = array_sum(array_column($games, 'hours'));
$avgRating = $total > 0 ? round(array_sum(array_column($games, 'rating')) / $total, 1) : 0;

$alertHtml = '';
if (isset($_GET['welcome'])) $alertHtml = alert('GAME LOADED. Bine ai venit, ' . $user['name'] . '! 🎮', 'success');

// filter by status
$filterStatus = $_GET['status'] ?? 'all';
$displayed = $filterStatus === 'all' ? $games : array_values(array_filter($games, fn($g) => $g['status']===$filterStatus));
?>
<!DOCTYPE html>
<html lang="ro" data-theme="dark">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vault — GameVault</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?= navbarHtml('dashboard.php') ?>

<div class="container" style="padding-top:2rem;padding-bottom:4rem">
  <?= $alertHtml ?>
  <div id="alertBox"></div>

  <!-- Header -->
  <div class="flex items-center justify-between" style="margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
    <div>
      <span class="section-label">MY VAULT</span>
      <h2><span class="text-neon"><?= htmlspecialchars($user['name']) ?></span> — Game Library</h2>
    </div>
    <button class="btn btn-primary" onclick="openModal('addGameModal')">+ ADD GAME</button>
  </div>

  <!-- Stats -->
  <div class="stats-bar">
    <div class="stat"><span class="stat-num"><?= $total ?></span><span class="stat-label">Total Games</span></div>
    <div class="stat"><span class="stat-num" style="color:var(--success)"><?= $completed ?></span><span class="stat-label">Completed</span></div>
    <div class="stat"><span class="stat-num" style="color:var(--neon)"><?= $playing ?></span><span class="stat-label">Playing</span></div>
    <div class="stat"><span class="stat-num" style="color:var(--neon2)"><?= $backlog ?></span><span class="stat-label">Backlog</span></div>
    <div class="stat"><span class="stat-num"><?= $totalHrs ?>h</span><span class="stat-label">Hours Played</span></div>
    <div class="stat"><span class="stat-num"><?= $avgRating ?>/5</span><span class="stat-label">Avg Rating</span></div>
  </div>

  <!-- Filters -->
  <div class="filter-tabs">
    <?php foreach ([
      'all'=>'All Games','playing'=>'▶ Playing','completed'=>'✓ Completed',
      'backlog'=>'◷ Backlog','dropped'=>'✕ Dropped'
    ] as $val=>$label): ?>
    <a href="?status=<?= $val ?>" class="filter-tab <?= $filterStatus===$val?'active':'' ?>"><?= $label ?></a>
    <?php endforeach; ?>
  </div>

  <!-- Games Grid -->
  <?php if (empty($displayed)): ?>
  <div class="empty-state">
    <div class="icon">🎮</div>
    <h3>Vault-ul e gol<?= $filterStatus!=='all' ? ' în această categorie' : '' ?></h3>
    <p>Adaugă primul joc și începe să-ți construiești colecția.</p>
    <?php if ($filterStatus==='all'): ?>
    <button class="btn btn-primary" style="margin-top:1.5rem" onclick="openModal('addGameModal')">+ ADD FIRST GAME</button>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <div class="cards-grid" id="gamesGrid">
    <?php foreach ($displayed as $i => $game): ?>
    <div class="game-card" id="card-<?= $game['id'] ?>" style="animation-delay:<?= $i*60 ?>ms">
      <div class="game-card-cover">
        <?= $game['emoji'] ?>
        <span class="game-card-platform"><?= htmlspecialchars($game['platform']) ?></span>
      </div>
      <div class="game-card-body">
        <div class="game-card-title"><?= htmlspecialchars($game['title']) ?></div>
        <div class="game-card-genre"><?= htmlspecialchars($game['genre']) ?> <?= $game['hours'] > 0 ? '· '.$game['hours'].'h' : '' ?></div>
        <span class="game-card-status status-<?= $game['status'] ?>"><?= statusLabel($game['status']) ?></span>
        <?php if ($game['rating'] > 0): ?>
        <div class="game-card-rating"><?= starsHtml($game['rating']) ?></div>
        <?php endif; ?>
        <?php if ($game['note']): ?>
        <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:.7rem;font-style:italic">"<?= htmlspecialchars($game['note']) ?>"</p>
        <?php endif; ?>
        <div class="game-card-actions">
          <button class="btn btn-ghost btn-sm" onclick='editGame(<?= json_encode($game) ?>)'>EDIT</button>
          <button class="btn btn-danger btn-sm" onclick="deleteGame('<?= $game['id'] ?>',this)">DEL</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<!-- MODAL: Add Game -->
<div class="modal-overlay" id="addGameModal">
  <div class="modal">
    <div class="modal-header">
      <h3>🎮 ADD GAME</h3>
      <button class="modal-close" onclick="closeModal('addGameModal')">✕</button>
    </div>
    <div id="addAlertBox"></div>
    <form id="addGameForm">
      <div class="form-row">
        <div class="form-group"><label>Titlu *</label>
          <input type="text" name="title" placeholder="Numele jocului" required>
        </div>
        <div class="form-group"><label>Platformă</label>
          <select name="platform">
            <option value="">— selectează —</option>
            <option>PC</option><option>PlayStation 5</option><option>PlayStation 4</option>
            <option>Xbox Series X</option><option>Xbox One</option>
            <option>Nintendo Switch</option><option>Mobile</option><option>Altă</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Gen</label>
          <select name="genre">
            <option value="">— gen —</option>
            <option value="action">Action</option><option value="rpg">RPG</option>
            <option value="fps">FPS</option><option value="racing">Racing</option>
            <option value="sport">Sport</option><option value="strategy">Strategy</option>
            <option value="horror">Horror</option><option value="adventure">Adventure</option>
            <option value="puzzle">Puzzle</option><option value="simulation">Simulation</option>
            <option value="fighting">Fighting</option><option value="mmo">MMO</option>
            <option value="indie">Indie</option><option value="platformer">Platformer</option>
          </select>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status">
            <option value="playing">▶ Playing</option>
            <option value="completed">✓ Completed</option>
            <option value="backlog">◷ Backlog</option>
            <option value="dropped">✕ Dropped</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Rating (0–5)</label>
          <select name="rating">
            <option value="0">— fără rating —</option>
            <option value="5">★★★★★ Masterpiece</option>
            <option value="4">★★★★☆ Great</option>
            <option value="3">★★★☆☆ Good</option>
            <option value="2">★★☆☆☆ Meh</option>
            <option value="1">★☆☆☆☆ Bad</option>
          </select>
        </div>
        <div class="form-group"><label>Ore jucate</label>
          <input type="number" name="hours" placeholder="0" min="0" max="9999" value="0">
        </div>
      </div>
      <div class="form-group"><label>Recenzie scurtă</label>
        <textarea name="note" placeholder="Ce crezi despre joc?"></textarea>
      </div>
      <div class="flex gap-1" style="margin-top:.5rem">
        <button type="button" class="btn btn-ghost" onclick="closeModal('addGameModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">SAVE →</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Edit Game -->
<div class="modal-overlay" id="editGameModal">
  <div class="modal">
    <div class="modal-header">
      <h3>✏️ EDIT GAME</h3>
      <button class="modal-close" onclick="closeModal('editGameModal')">✕</button>
    </div>
    <div id="editAlertBox"></div>
    <form id="editGameForm">
      <input type="hidden" name="game_id" id="editId">
      <div class="form-row">
        <div class="form-group"><label>Titlu</label><input type="text" name="title" id="eTitle" required></div>
        <div class="form-group"><label>Platformă</label>
          <select name="platform" id="ePlatform">
            <option value="">—</option>
            <option>PC</option><option>PlayStation 5</option><option>PlayStation 4</option>
            <option>Xbox Series X</option><option>Xbox One</option>
            <option>Nintendo Switch</option><option>Mobile</option><option>Altă</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Gen</label>
          <select name="genre" id="eGenre">
            <option value="">—</option>
            <option value="action">Action</option><option value="rpg">RPG</option>
            <option value="fps">FPS</option><option value="racing">Racing</option>
            <option value="sport">Sport</option><option value="strategy">Strategy</option>
            <option value="horror">Horror</option><option value="adventure">Adventure</option>
            <option value="puzzle">Puzzle</option><option value="simulation">Simulation</option>
            <option value="fighting">Fighting</option><option value="mmo">MMO</option>
            <option value="indie">Indie</option><option value="platformer">Platformer</option>
          </select>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status" id="eStatus">
            <option value="playing">▶ Playing</option>
            <option value="completed">✓ Completed</option>
            <option value="backlog">◷ Backlog</option>
            <option value="dropped">✕ Dropped</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label>Rating</label>
          <select name="rating" id="eRating">
            <option value="0">— fără —</option>
            <option value="5">★★★★★</option><option value="4">★★★★☆</option>
            <option value="3">★★★☆☆</option><option value="2">★★☆☆☆</option><option value="1">★☆☆☆☆</option>
          </select>
        </div>
        <div class="form-group"><label>Ore jucate</label>
          <input type="number" name="hours" id="eHours" min="0" max="9999">
        </div>
      </div>
      <div class="form-group"><label>Recenzie</label>
        <textarea name="note" id="eNote"></textarea>
      </div>
      <div class="flex gap-1" style="margin-top:.5rem">
        <button type="button" class="btn btn-ghost" onclick="closeModal('editGameModal')">Cancel</button>
        <button type="submit" class="btn btn-primary">UPDATE →</button>
      </div>
    </form>
  </div>
</div>

<script src="js/script.js"></script>
<script>
// ADD
document.getElementById('addGameForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const fd = new FormData(this); fd.append('action','add');
  const r = await (await fetch('php/save_data.php',{method:'POST',body:fd})).json();
  if (r.ok) { showAlert('alertBox',r.msg,'success'); closeModal('addGameModal'); this.reset(); setTimeout(()=>location.reload(),700); }
  else showAlert('addAlertBox',r.msg,'error');
});

// EDIT POPULATE
function editGame(g) {
  document.getElementById('editId').value       = g.id;
  document.getElementById('eTitle').value       = g.title;
  document.getElementById('ePlatform').value    = g.platform;
  document.getElementById('eGenre').value       = g.genre;
  document.getElementById('eStatus').value      = g.status;
  document.getElementById('eRating').value      = g.rating;
  document.getElementById('eHours').value       = g.hours;
  document.getElementById('eNote').value        = g.note;
  openModal('editGameModal');
}

// UPDATE
document.getElementById('editGameForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const fd = new FormData(this); fd.append('action','update');
  const r = await (await fetch('php/save_data.php',{method:'POST',body:fd})).json();
  if (r.ok) { showAlert('alertBox',r.msg,'success'); closeModal('editGameModal'); setTimeout(()=>location.reload(),700); }
  else showAlert('editAlertBox',r.msg,'error');
});

// DELETE
async function deleteGame(id, btn) {
  if (!confirm('Ești sigur că vrei să ștergi acest joc?')) return;
  btn.disabled = true;
  const fd = new FormData(); fd.append('action','delete'); fd.append('game_id',id);
  const r = await (await fetch('php/save_data.php',{method:'POST',body:fd})).json();
  if (r.ok) {
    const card = document.getElementById('card-'+id);
    if (card) { card.style.cssText='opacity:0;transform:scale(.95);transition:.25s'; setTimeout(()=>card.remove(),250); }
    showAlert('alertBox',r.msg,'success');
  } else { showAlert('alertBox',r.msg,'error'); btn.disabled=false; }
}
</script>
</body>
</html>
