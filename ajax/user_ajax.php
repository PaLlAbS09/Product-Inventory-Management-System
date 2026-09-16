<?php

session_start();
include '../config/dbcon.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'fetch_all') {
    $stmt = $db->query("SELECT id, full_name, email, created_at FROM users ORDER BY id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_user') {
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        try {
            $stmt = $db->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (:name, :email, :pass)");
            $stmt->execute([':name' => $full_name, ':email' => $email, ':pass' => $password]);
            echo json_encode(['status' => 'success', 'message' => 'User successfully registered.']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Email may already exist.']);
        }
        exit;
    }

    if ($action === 'delete_user') {
        $id = $_POST['id'];
        try {
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot delete user due to existing relational records (e.g., orders).']);
        }
        exit;
    }
}
?>