<?php
header('Content-Type: application/json');
include '../config/dbcon.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] == 'search') {
    
    $query = "SELECT * FROM product_inventory WHERE 1=1";
    $params = [];

    if (!empty($_GET['keyword'])) {
        $query .= " AND (product_name LIKE :keyword OR description LIKE :keyword OR sku LIKE :keyword)";
        $params[':keyword'] = '%' . $_GET['keyword'] . '%';
    }

    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $query .= " AND created_date BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $_GET['start_date'] . ' 00:00:00';
        $params[':end_date'] = $_GET['end_date'] . ' 23:59:59';
    }

    if (!empty($_GET['category'])) {
        $query .= " AND category = :category";
        $params[':category'] = $_GET['category'];
    }

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $name = $_POST['product_name'] ?? '';
        $category = $_POST['category'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock = $_POST['available_stock'] ?? 0;

        $image_path = null;
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/product_images/'; 
            
            $fileExtension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('prod_') . '.' . $fileExtension;
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
                $image_path = 'assets/product_images/' . $fileName;
            }
        }
        
        if (!filter_var($stock, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
            echo json_encode(['status' => 'error', 'message' => 'Stock must be a positive integer.']);
            exit;
        }

        try {
            $query = "INSERT INTO product_inventory (product_name, category, image_path, price, available_stock) 
                      VALUES (:name, :category, :image_path, :price, :stock)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':name' => $name, 
                ':category' => $category, 
                ':image_path' => $image_path, 
                ':price' => $price, 
                ':stock' => $stock
            ]);
            
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
        }
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete_product') {
        $product_id = $_POST['product_id'] ?? null;

        if (!$product_id) {
            echo json_encode(['status' => 'error', 'message' => 'Product ID is missing.']);
            exit;
        }

        try {
            
            $imgStmt = $db->prepare("SELECT image_path FROM product_inventory WHERE id = :id");
            $imgStmt->execute([':id' => $product_id]);
            $product = $imgStmt->fetch(PDO::FETCH_ASSOC);

            if ($product && !empty($product['image_path'])) {
                $fullPath = '../' . $product['image_path'];
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }

            // Delete record from database
            $stmt = $db->prepare("DELETE FROM product_inventory WHERE id = :id");
            $stmt->execute([':id' => $product_id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot delete product due to existing relational order records.']);
        }
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'place_order') {
        $product_id = $_POST['product_id'] ?? null;
        $order_qty = $_POST['quantity'] ?? 1;

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT available_stock FROM product_inventory WHERE id = :id FOR UPDATE");
            $stmt->execute([':id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product || $product['available_stock'] < $order_qty) {
                throw new Exception("Insufficient stock. Order cannot be placed.");
            }
            
            $new_stock = $product['available_stock'] - $order_qty;
            
            if ($new_stock < 0) {
                 throw new Exception("System error: Stock cannot fall below zero.");
            }

            $updateStmt = $db->prepare("UPDATE product_inventory SET available_stock = :stock WHERE id = :id");
            $updateStmt->execute([':stock' => $new_stock, ':id' => $product_id]);

            $orderStmt = $db->prepare("INSERT INTO orders (product_id, quantity) VALUES (:product_id, :qty)");
            $orderStmt->execute([':product_id' => $product_id, ':qty' => $order_qty]);

            $db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Order placed successfully!']);

        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
}
?>