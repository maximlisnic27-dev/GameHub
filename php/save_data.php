<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn()) { echo json_encode(['ok'=>false,'msg'=>'Neautentificat.']); exit; }

$action = $_POST['action'] ?? '';
$userId = currentUser()['id'];

$result = match($action) {
    'add'    => addGame($userId, $_POST),
    'update' => updateGame($_POST['game_id'] ?? '', $userId, $_POST),
    'delete' => deleteGame($_POST['game_id'] ?? '', $userId),
    default  => ['ok'=>false,'msg'=>'Acțiune necunoscută.'],
};

echo json_encode($result);
