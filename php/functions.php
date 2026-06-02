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

function footerHtml(): string {
    return "<footer>
      <span class='brand'>GameVault</span>
      <span data-i18n='footer_copy'>© 2025 GameVault. Toate drepturile rezervate.</span>
    </footer>";
}
