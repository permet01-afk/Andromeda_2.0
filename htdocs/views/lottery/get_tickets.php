<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: text/plain; charset=utf-8');

if (empty($_SESSION['player_id'])) { echo 0; exit; }

require_once __DIR__ . '/../../libs/database.php';
require_once __DIR__ . '/../../config/database.php';

$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
$sth = $db->prepare('SELECT tickets FROM users_infos WHERE id = :id LIMIT 1');
$sth->execute([':id' => $_SESSION['player_id']]);
$row = $sth->fetch(PDO::FETCH_ASSOC);

echo $row ? (int)$row['tickets'] : 0;
