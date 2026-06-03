<?php


function currentUser(): ?array {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email']
    ];
}
function alert(string $msg, string $type = 'info'): string {
    $icons = ['success' => '✓', 'error' => '✕', 'info' => '>'];
    $icon  = $icons[$type] ?? '>';
    return "<div class='alert alert-{$type}'><span>{$icon}</span>" . htmlspecialchars($msg) . "</div>";
}

function navbarHtml(string $active=''): string {
    $user   = currentUser();
    $logged = $user !== null;
    $uname  = $logged ? htmlspecialchars($user['name']) : '';

    $links = [
        ['index.php',          'Acasă'],
        ['index.php#about',    'Despre'],
        ['index.php#features', 'Funcționalități'],
        ['contact.php',        'Contact'],
    ];
    if ($logged) $links[] = ['dashboard.php','nav_dashboard'];

    $linksHtml = '';
    foreach ($links as [$href,$key]) {
        $cls = $href === $active ? ' class="active"' : '';
        $linksHtml .= "<a href=\"$href\"$cls data-i18n=\"$key\">$key</a>";
    }

    $authHtml = $logged
        ? "<span style='font-size:.75rem;color:var(--text-muted);font-family:var(--font-mono)'>▸ {$uname}</span>
           <a href='logout.php' class='btn btn-ghost btn-sm' data-i18n='nav_logout'>Ieșire</a>"
        : "<a href='login.php'    class='btn btn-ghost btn-sm'   data-i18n='nav_login'>Login</a>
           <a href='register.php' class='btn btn-primary btn-sm' data-i18n='nav_register'>Register</a>";

    $mobileLinks = '';
    foreach ($links as [$href,$key]) $mobileLinks .= "<a href=\"$href\" data-i18n=\"$key\">$key</a>";
    if ($logged) $mobileLinks .= "<a href='logout.php' data-i18n='nav_logout'>Ieșire</a>";
    else { $mobileLinks .= "<a href='login.php' data-i18n='nav_login'>Login</a><a href='register.php' data-i18n='nav_register'>Register</a>"; }

    return "
    <nav class='navbar'>
      <a href='index.php' class='navbar-brand'><span class='brand-icon'>🎮</span><span class='brand-vault'>GameVault</span></a>
      <div class='navbar-links'>$linksHtml</div>
      <div class='navbar-actions'>
        <select id='langSelect' class='lang-select'><option value='ro'>RO</option><option value='en'>EN</option><option value='ru'>RU</option></select>
        <button id='themeToggle' class='icon-btn'>🌙</button>
        $authHtml
        <button class='hamburger' id='hamburgerBtn'><span></span><span></span><span></span></button>
      </div>
    </nav>
    <div class='mobile-menu' id='mobileMenu'>$mobileLinks</div>";
}

function getGames(): array { return readJson(ITEMS_FILE); }

function getUserGames(string $userId): array {
    return array_values(array_filter(getGames(), fn($g) => $g['user_id'] === $userId));
}

function addGame(string $userId, array $d): array {
    $title    = trim($d['title']    ?? '');
    $platform = trim($d['platform'] ?? '');
    $genre    = trim($d['genre']    ?? '');
    $status   = trim($d['status']   ?? 'backlog');
    $rating   = (int)($d['rating']  ?? 0);
    $note     = trim($d['note']     ?? '');
    $hours    = (int)($d['hours']   ?? 0);

    if (strlen($title) < 1)  return ['ok'=>false,'msg'=>'Titlul este obligatoriu.'];
    if ($rating < 0 || $rating > 5) $rating = 0;

    $games = getGames();
    $games[] = [
        'id'         => uniqid('g_', true),
        'user_id'    => $userId,
        'title'      => htmlspecialchars($title),
        'platform'   => htmlspecialchars($platform),
        'genre'      => htmlspecialchars($genre),
        'status'     => $status,
        'rating'     => $rating,
        'hours'      => $hours,
        'note'       => htmlspecialchars($note),
        'emoji'      => genreEmoji($genre),
        'created_at' => date('Y-m-d H:i:s'),
    ];
    writeJson(ITEMS_FILE, $games);
    return ['ok'=>true,'msg'=>'Jocul a fost adăugat în colecție!'];
}

function updateGame(string $id, string $userId, array $d): array {
    $games = getGames(); $found = false;
    foreach ($games as &$g) {
        if ($g['id'] === $id && $g['user_id'] === $userId) {
            $g['title']    = htmlspecialchars(trim($d['title']    ?? $g['title']));
            $g['platform'] = htmlspecialchars(trim($d['platform'] ?? $g['platform']));
            $g['genre']    = htmlspecialchars(trim($d['genre']    ?? $g['genre']));
            $g['status']   = $d['status']  ?? $g['status'];
            $g['rating']   = (int)($d['rating'] ?? $g['rating']);
            $g['hours']    = (int)($d['hours']  ?? $g['hours']);
            $g['note']     = htmlspecialchars(trim($d['note'] ?? $g['note']));
            $g['emoji']    = genreEmoji($g['genre']);
            $found = true; break;
        }
    }
    if (!$found) return ['ok'=>false,'msg'=>'Jocul nu a fost găsit.'];
    writeJson(ITEMS_FILE, $games);
    return ['ok'=>true,'msg'=>'Jocul a fost actualizat!'];
}

function deleteGame(string $id, string $userId): array {
    $games = getGames();
    $new   = array_values(array_filter($games, fn($g) => !($g['id']===$id && $g['user_id']===$userId)));
    if (count($new) === count($games)) return ['ok'=>false,'msg'=>'Jocul nu a fost găsit.'];
    writeJson(ITEMS_FILE, $new);
    return ['ok'=>true,'msg'=>'Jocul a fost șters.'];
}

function genreEmoji(string $genre): string {
    $map = [
        'action'=>'⚔️','rpg'=>'🧙','fps'=>'🔫','racing'=>'🏎️','sport'=>'⚽',
        'strategy'=>'♟️','horror'=>'👻','adventure'=>'🗺️','puzzle'=>'🧩',
        'simulation'=>'🏙️','fighting'=>'🥊','mmo'=>'🌐','indie'=>'🎨','platformer'=>'🦘',
    ];
    return $map[strtolower($genre)] ?? '🎮';
}

function statusLabel(string $status): string {
    return match($status) {
        'completed' => 'Finalizat',
        'playing'   => 'În joc',
        'backlog'   => 'Backlog',
        'dropped'   => 'Abandonat',
        default     => $status,
    };
}

function starsHtml(int $r): string {
    return str_repeat('★', $r) . str_repeat('☆', 5 - $r);
}

function footerHtml(): string {
    return "<footer>
      <span class='brand'>GameVault</span>
      <span data-i18n='footer_copy'>© 2025 GameVault. Toate drepturile rezervate.</span>
    </footer>";
}

