<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['username'=>null]);
    exit;
}
require_once "db.php";
$stmt = $pdo->prepare("SELECT username FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$username = $stmt->fetchColumn();
echo json_encode(['username'=>$username]);
?>