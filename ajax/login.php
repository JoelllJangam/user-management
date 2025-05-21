<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Using MD5 as per your current setup (for testing only)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND deleted_at IS NULL");
        $stmt->execute([$email, md5($password)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all fields']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
