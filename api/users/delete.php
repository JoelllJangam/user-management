<?php
require_once __DIR__ . '/../../config/db.php';
require_once '../middleware.php';
header('Content-Type: application/json');

authenticate();

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

$stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = ?");
$success = $stmt->execute([$id]);

echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'User deleted' : 'Delete failed']);
