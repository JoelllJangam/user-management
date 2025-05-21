<?php
require_once __DIR__ . '/../config/db.php';
require_once 'middleware.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$email || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password required.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND deleted_at IS NULL");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Generate JWT token
$payload = ['id' => $user['id'], 'email' => $user['email'], 'exp' => time() + 3600];
$jwt = jwt_encode($payload);

echo json_encode(['status' => 'success', 'token' => $jwt]);
