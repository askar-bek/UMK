<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: thematicPlan.php");
        exit;
    } else {
        echo "Invalid login";
    }
}
?>
<form method="POST">
  <input name="username" placeholder="Username" required>
  <input name="password" type="password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>