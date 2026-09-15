<?php
// ajax/admin_support_ajax.php
session_start();
include '../config/dbcon.php';

header('Content-Type: application/json');

include '../config/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$ticket_id || !$status) {
        echo json_encode(['status' => 'error', 'message' => 'Missing ticket data.']);
        exit;
    }

    // Validate allowed statuses
    if (!in_array($status, ['Approved', 'Rejected'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid status update.']);
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

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

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
    }
}
?>