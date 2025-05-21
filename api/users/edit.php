<?php
require_once __DIR__ . '/../../config/db.php';
require_once '../middleware.php';
header('Content-Type: application/json');

authenticate();

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$role = trim($data['role'] ?? '');

if (!$id || !$name || !$email || !$role) {
    echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
    exit;
}

$query = "UPDATE users SET name = ?, email = ?, role = ?";
$params = [$name, $email, $role];

if ($password) {
    $query .= ", password = ?";
    $params[] = password_hash($password, PASSWORD_BCRYPT);
}
$query .= " WHERE id = ?";
$params[] = $id;

$stmt = $pdo->prepare($query);
$success = $stmt->execute($params);

echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'User updated' : 'Update failed']);
