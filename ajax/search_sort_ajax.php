<?php
// ajax/search_sort_ajax.php
include '../config/dbcon.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Base Query
    $query = "SELECT id, full_name, email, created_at FROM users WHERE 1=1";
    $params = [];

    // 1. Keyword Search via LIKE and Prepared Statements[cite: 1]
    if (!empty($_GET['keyword'])) {
        $query .= " AND (full_name LIKE :keyword OR email LIKE :keyword)";
        $params[':keyword'] = '%' . $_GET['keyword'] . '%';
    }

    // 2. Filter by Date Range using BETWEEN[cite: 1]
    // Handles missing inputs gracefully by checking both fields[cite: 1]
    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $query .= " AND created_at BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $_GET['start_date'] . ' 00:00:00';
        $params[':end_date'] = $_GET['end_date'] . ' 23:59:59';
    } elseif (!empty($_GET['start_date'])) {
        // Graceful fallback for single date bounds
        $query .= " AND created_at >= :start_date";
        $params[':start_date'] = $_GET['start_date'] . ' 00:00:00';
    }

    // 3. Dynamic Sorting using predefined safe options[cite: 1]
    $allowed_sorts = ['created_at ASC', 'created_at DESC', 'full_name ASC', 'full_name DESC'];
    if (!empty($_GET['sort_by']) && in_array($_GET['sort_by'], $allowed_sorts)) {
        $query .= " ORDER BY " . $_GET['sort_by'];
    } else {
        // Default sort configuration
        $query .= " ORDER BY created_at DESC";
    }

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        // Graceful error handling format
        echo json_encode(['error' => 'An error occurred processing the search engine query.']);
    }
}
?>