<?php
session_start();
include '../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        die("All fields are required. <a href='../user_registration.php'>Go back</a>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format. <a href='../user_registration.php'>Go back</a>");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match. <a href='../user_registration.php'>Go back</a>");
    }

    try {
        $database = new Database();
        $db = $database->getConnection();
        $checkStmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->rowCount() > 0) {
            die("Email is already registered. <a href='../users_login.php'>Please log in</a>");
        }

    
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (full_name, email, password_hash) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':name' => $full_name,
            ':email' => $email,
            ':password' => $hashed_password
        ]);

      
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['user_name'] = $full_name;

        header("Location: ../user_dashboard.php");
        exit;

    } catch (PDOException $e) {
        die("A system error occurred. Please try again later.");
    }
}
?>