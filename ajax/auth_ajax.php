<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

try {

   include '../config/dbcon.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        exit;
    }

   
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

  
    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Both email and password are required.']);
        exit;
    }


    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit;
    }


    $stmt = $db->prepare("SELECT id, name, password_hash FROM admins WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        
    
        if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
           
            setcookie('admin_remember', $admin['id'], time() + (86400 * 30), "/", "", false, true);
        }

        echo json_encode(['status' => 'success', 'message' => 'Authentication successful.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
}
?>