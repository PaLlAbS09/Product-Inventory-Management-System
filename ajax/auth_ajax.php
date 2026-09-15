<?php
// ajax/auth_ajax.php

// Suppress direct text output so it doesn't break JSON parsing
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

try {
    // Include database connection configuration
    require_once '../config/dbcon.php';

    // Verify request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        exit;
    }

    // Capture and trim input data
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate inputs
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Both email and password are required.']);
        exit;
    }

    // Initialize Database connection via PDO
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit;
    }

    // Query the admins table using prepared statements
    $stmt = $db->prepare("SELECT id, name, password_hash FROM admins WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password hash
    if ($admin && password_verify($password, $admin['password_hash'])) {
        // Set secure session variables
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        
        echo json_encode(['status' => 'success', 'message' => 'Authentication successful.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }

} catch (Exception $e) {

    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
}
?>