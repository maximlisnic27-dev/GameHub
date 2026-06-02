<?php
session_start();

define('USERS_FILE', __DIR__ . '/../data/users.json');
define('ITEMS_FILE', __DIR__ . '/../data/items.json');

function readJson(string $file): array {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?? [];
}
function writeJson(string $file, array $data): bool {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

function getUsers(): array { return readJson(USERS_FILE); }

function findUserByEmail(string $email): ?array {
    foreach (getUsers() as $u) if (strtolower($u['email']) === strtolower($email)) return $u;
    return null;
}

function registerUser(string $name, string $email, string $password): array {
    if (strlen(trim($name)) < 2)              return ['ok'=>false,'msg'=>'Numele trebuie să aibă minim 2 caractere.'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['ok'=>false,'msg'=>'Email invalid.'];
    if (strlen($password) < 6)                return ['ok'=>false,'msg'=>'Parola trebuie să aibă minim 6 caractere.'];
    if (findUserByEmail($email))              return ['ok'=>false,'msg'=>'Există deja un cont cu acest email.'];

    $users = getUsers();
    $users[] = [
        'id'         => uniqid('u_', true),
        'name'       => htmlspecialchars(trim($name)),
        'email'      => strtolower(trim($email)),
        'password'   => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
    ];
    writeJson(USERS_FILE, $users);
    return ['ok'=>true,'msg'=>'Cont creat! Autentifică-te.'];
}

function loginUser(string $email, string $password): array {
    if (!$email || !$password) return ['ok'=>false,'msg'=>'Completează toate câmpurile.'];
    $user = findUserByEmail($email);
    if (!$user || !password_verify($password, $user['password'])) return ['ok'=>false,'msg'=>'Credențiale incorecte.'];
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    return ['ok'=>true];
}

function logoutUser(): void { session_destroy(); }
function isLoggedIn(): bool { return isset($_SESSION['user_id']); }
    if (!isLoggedIn()) return null;
    return ['id'=>$_SESSION['user_id'],'name'=>$_SESSION['user_name'],'email'=>$_SESSION['user_email']];

function requireLogin(string $redirect='login.php'): void {
    if (!isLoggedIn()) { header("Location: $redirect"); exit; }
}
