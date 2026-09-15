<?php
// ajax/submit_ticket.php
session_start();
require_once '../config/dbcon.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['user_email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic Validation
    if (empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        $query = "INSERT INTO support_tickets (user_email, subject, message, status) VALUES (:email, :subject, :message, 'Pending')";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Your ticket has been submitted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit ticket. Please try again later.']);
    }
}
?>