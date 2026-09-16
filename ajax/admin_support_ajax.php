<?php
// ajax/admin_support_ajax.php
session_start();
header('Content-Type: application/json');
require_once '../config/dbcon.php';

// JSON-safe auth check to prevent HTML redirects from breaking fetch requests
if (!isset($_SESSION['admin_id']) && (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true)) {
    echo json_encode([]);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // 1. Handle GET request to fetch all support tickets for the admin table
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetch_all') {
        $stmt = $db->query("SELECT id, user_email, subject, message, status FROM support_tickets ORDER BY id DESC");
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tickets);
        exit;
    }

    // 2. Handle POST request to update ticket status (Approve / Reject)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ticket_id = $_POST['ticket_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$ticket_id || !$status) {
            echo json_encode(['status' => 'error', 'message' => 'Missing ticket data.']);
            exit;
        }

        if (!in_array($status, ['Approved', 'Rejected'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid status update.']);
            exit;
        }

        $query = "UPDATE support_tickets SET status = :status WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':status' => $status,
            ':id' => $ticket_id
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => "Ticket #{$ticket_id} has been {$status}."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Ticket not found or status is already set.']);
        }
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>