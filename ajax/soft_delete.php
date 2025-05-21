<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'] ?? null;
    if ($userId) {
        $stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User ID missing']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
