<?php
// ajax/product_ajax.php
header('Content-Type: application/json');
require_once '../config/dbcon.php';

$database = new Database();
$db = $database->getConnection();

// Handle GET requests for Advanced Search & Filter Engine
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'search') {
    
    $query = "SELECT * FROM product_inventory WHERE 1=1";
    $params = [];

    // 1. Keyword Search (LIKE query with prepared statements)
    if (!empty($_GET['keyword'])) {
        $query .= " AND (product_name LIKE :keyword OR description LIKE :keyword OR sku LIKE :keyword)";
        $params[':keyword'] = '%' . $_GET['keyword'] . '%';
    }

    // 2. Filter by Date Range (BETWEEN)
    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $query .= " AND created_date BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $_GET['start_date'] . ' 00:00:00';
        $params[':end_date'] = $_GET['end_date'] . ' 23:59:59';
    }

    // 3. Filter by Category
    if (!empty($_GET['category'])) {
        $query .= " AND category = :category";
        $params[':category'] = $_GET['category'];
    }

    // 4. Dynamic Sorting
    $allowed_sorts = ['created_date ASC', 'created_date DESC', 'price ASC', 'price DESC', 'product_name ASC'];
    if (!empty($_GET['sort_by']) && in_array($_GET['sort_by'], $allowed_sorts)) {
        $query .= " ORDER BY " . $_GET['sort_by'];
    } else {
        $query .= " ORDER BY created_date DESC";
    }

    try {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode([]);
    }
    exit;
}

// Handle POST requests for Adding Products and Placing Orders
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Action: Add Product Stock
    if (isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $name = $_POST['product_name'] ?? '';
        $category = $_POST['category'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock = $_POST['available_stock'] ?? 0;

        // Validate positive integers for stock
        if (!filter_var($stock, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
            echo json_encode(['status' => 'error', 'message' => 'Stock must be a positive integer.']);
            exit;
        }

        try {
            $query = "INSERT INTO product_inventory (product_name, category, price, available_stock) VALUES (:name, :category, :price, :stock)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':name' => $name, 
                ':category' => $category, 
                ':price' => $price, 
                ':stock' => $stock
            ]);
            
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Action: Reduce Stock on Order
    if (isset($_POST['action']) && $_POST['action'] === 'place_order') {
        $product_id = $_POST['product_id'] ?? null;
        $order_qty = $_POST['quantity'] ?? 1;

        try {
            // Begin PDO transaction
            $db->beginTransaction();

            // Fetch current stock and lock row for update
            $stmt = $db->prepare("SELECT available_stock FROM product_inventory WHERE id = :id FOR UPDATE");
            $stmt->execute([':id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            // Prevent Negative Stock: Validate sufficient stock
            if (!$product || $product['available_stock'] < $order_qty) {
                throw new Exception("Insufficient stock. Order cannot be placed.");
            }

            // Reduce stock based on ordered quantity
            $new_stock = $product['available_stock'] - $order_qty;
            
            if ($new_stock < 0) {
                 throw new Exception("System error: Stock cannot fall below zero.");
            }

            $updateStmt = $db->prepare("UPDATE product_inventory SET available_stock = :stock WHERE id = :id");
            $updateStmt->execute([':stock' => $new_stock, ':id' => $product_id]);

            // Save order details in an orders table
            $orderStmt = $db->prepare("INSERT INTO orders (product_id, quantity) VALUES (:product_id, :qty)");
            $orderStmt->execute([':product_id' => $product_id, ':qty' => $order_qty]);

            // Commit transaction if successful
            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Order placed successfully!']);

        } catch (Exception $e) {
            // If any query fails, rollback the transaction
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
}
?>