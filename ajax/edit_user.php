<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$id || !$name || !$email || !$role) {
        echo json_encode(['status' => 'error', 'message' => 'All fields except password are required']);
        exit;
    }

    // Check email uniqueness excluding current user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ? AND deleted_at IS NULL");
    $stmt->execute([$email, $id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        exit;
    }

    if ($password) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ?, password = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, md5($password), $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);
    }

    echo json_encode(['status' => 'success', 'message' => 'User updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
