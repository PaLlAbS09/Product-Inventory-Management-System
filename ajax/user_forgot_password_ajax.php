<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
   include '../config/dbcon.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

  
    $checkStmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $checkStmt->execute([':email' => $email]);
    
    if ($checkStmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No account found with that email address.']);
        exit;
    }

   
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $updateStmt = $db->prepare("UPDATE users SET password_hash = :password WHERE email = :email");
    $updateStmt->execute([
        ':password' => $hashed_password,
        ':email' => $email
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Password reset successfully. Redirecting...']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
}
?>