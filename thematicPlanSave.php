<?php
session_start();
require_once "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
$user_id = $_SESSION['user_id'];

// Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    if (!$json) {
        http_response_code(400);
        echo json_encode(['error' => 'No data']);
        exit;
    }
    $stmt = $pdo->prepare("SELECT id FROM UMK_userPlans WHERE user_id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("UPDATE UMK_userPlans SET plan_data = ? WHERE user_id = ?");
        $stmt->execute([$json, $user_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO UMK_userPlans (user_id, plan_data) VALUES (?, ?)");
        $stmt->execute([$user_id, $json]);
    }
    echo json_encode(['status' => 'ok']);
    exit;
}

// Load
$stmt = $pdo->prepare("SELECT plan_data FROM UMK_userPlans WHERE user_id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetchColumn();
echo $data ?: '{}';