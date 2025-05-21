<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['ids'] ?? [];

    if (empty($ids) || !is_array($ids)) {
        echo json_encode(['status' => 'error', 'message' => 'No users selected']);
        exit;
    }

    // Soft delete all selected users
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id IN ($placeholders)");
    $stmt->execute($ids);

    echo json_encode(['status' => 'success', 'message' => count($ids) . " users deleted."]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
