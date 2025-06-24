<?php

if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    $stmt = $conn->prepare("
        SELECT userId FROM users 
        WHERE remember_token = ? 
        AND remember_token_expires_at > NOW()
    ");
    $stmt->execute([$token]);
    
    if ($stmt->fetch()) {
        // Token is valid - log user in
        echo json_encode(['success' => true]);
    } else {
        // Invalid/expired token - clear cookie
        setcookie('remember_token', '', time() - 3600, '/');
        echo json_encode(['success' => false]);
    }
}

?>