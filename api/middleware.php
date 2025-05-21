<?php
function jwt_encode($payload, $secret = 'your_secret_key') {
    $header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
    $payload = base64_encode(json_encode($payload));
    $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
    return "$header.$payload." . base64_encode($signature);
}

function jwt_decode($jwt, $secret = 'your_secret_key') {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    list($header, $payload, $signature) = $parts;
    $expectedSig = base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true));
    if ($signature !== $expectedSig) return false;

    $payload = json_decode(base64_decode($payload), true);
    if ($payload['exp'] < time()) return false;

    return $payload;
}

function authenticate() {
    $headers = getallheaders();
    $auth = $headers['Authorization'] ?? '';
    if (!preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $token = $matches[1];
    $user = jwt_decode($token);
    if (!$user) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token']);
        exit;
    }

    return $user;
}
