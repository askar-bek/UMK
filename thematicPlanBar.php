<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once "db.php";
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$username = $stmt->fetchColumn();
?>
<div class="user-bar" style="float:right; margin:8px 0 0 0; font-size:11pt;">
  User: <b><?=htmlspecialchars($username)?></b>
    <a href="thematicPlanPrint.php" target="_blank" style="color:#175a0b; text-decoration:none; margin-left:10px;">Print</a>
  <a href="logout.php" style="color:#175a0b; text-decoration:none; margin-left:10px;">Logout</a>
</div>