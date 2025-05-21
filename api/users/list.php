<?php
require_once __DIR__ . '/../../config/db.php';
require_once '../middleware.php';
header('Content-Type: application/json');

authenticate();

$stmt = $pdo->query("SELECT id, name, email, role FROM users WHERE deleted_at IS NULL");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['status' => 'success', 'data' => $users]);
