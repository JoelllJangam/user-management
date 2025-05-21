<?php
require_once __DIR__ . '/../../config/db.php';
require_once '../middleware.php';
header('Content-Type: application/json');

authenticate(); // Protect route

$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$role = trim($data['role'] ?? '');

if (!$name || !$email || !$password || !$role) {
    echo json_encode(['status' => 'error', 'message' => 'All fields required']);
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$success = $stmt->execute([$name, $email, $hash, $role]);

echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'User added' : 'Failed to add user']);
