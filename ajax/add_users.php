<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $names = $_POST['name'] ?? [];
    $emails = $_POST['email'] ?? [];
    $passwords = $_POST['password'] ?? [];
    $roles = $_POST['role'] ?? [];

    $count = count($names);
    $errors = [];
    $successCount = 0;

    for ($i = 0; $i < $count; $i++) {
        $name = trim($names[$i]);
        $email = trim($emails[$i]);
        $password = trim($passwords[$i]);
        $role = trim($roles[$i]);

        if (!$name || !$email || !$password || !$role) {
            $errors[] = "All fields are required for user " . ($i+1);
            continue;
        }

        // Check if email exists (not deleted)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND deleted_at IS NULL");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email already exists: $email";
            continue;
        }

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, md5($password), $role]);
        $successCount++;
    }

    if ($successCount > 0) {
        echo json_encode(['status' => 'success', 'message' => "$successCount users added.", 'errors' => $errors]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "No users added.", 'errors' => $errors]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
