<?php
session_start();
include '../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../users_login.php?error=empty");
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        $stmt = $db->prepare("SELECT id, full_name, password_hash FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Establish session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            
            header("Location: ../dashboard/user_dashboard.php");
            exit;
        } else {
            // Authentication failed
            header("Location: ../users_login.php?error=invalid");
            exit;
        }
    } catch (PDOException $e) {
        die("Database error occurred.");
    }
}
?>