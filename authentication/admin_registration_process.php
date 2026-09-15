<?php

session_start();
include '../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

   
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required. <a href='../admin_registration.php'>Go back</a>");
    }

  
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format. <a href='../admin_registration.php'>Go back</a>");
    }
    if ($password !== $confirm_password) {
        die("Passwords do not match. <a href='../admin_registration.php'>Go back</a>");
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

       
        $checkStmt = $db->prepare("SELECT id FROM admins WHERE email = :email");
        $checkStmt->execute([':email' => $email]);
        if ($checkStmt->rowCount() > 0) {
            die("An admin account with this email already exists. <a href='../login.php'>Please log in</a>");
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        
        $query = "INSERT INTO admins (name, email, password_hash) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':name' => $full_name,
            ':email' => $email,
            ':password' => $hashed_password
        ]);

       
        header("Location: ../login.php?registration=success");
        exit;

    } catch (PDOException $e) {
        die("A database error occurred. Please try again later.");
    }
} else {
  
    header("Location: ../admin_registration.php");
    exit;
}
?>