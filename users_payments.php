<?php
session_start();
include 'config/user_auth.php';
include 'config/dbcon.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: users_login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$database = new Database();
$db = $database->getConnection();

// Fetch orders placed by this user (assuming orders table relates to users or user email)
// If your orders table tracks user_id, adjust query accordingly:
try {
    $stmt = $db->prepare("SELECT o.id, o.quantity, o.created_at, p.product_name, p.price FROM orders o JOIN products p ON o.product_id = p.id WHERE o.product_id IS NOT NULL ORDER BY o.id DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $orders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | User Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans p-8">
    <div class="max-w-4xl mx-auto space-y-6">
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">My Orders & Purchase History</h1>
                <p class="text-sm text-slate-500">Track your past purchases and shipments.</p>
            </div>
            <a href="dashboard/user_dashboard.php" class="text-emerald-600 font-medium hover:underline text-sm">&larr; Back to Dashboard</a>
        </header>

        <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Order ID</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Product Name</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Quantity</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="4" class="p-8 text-center text-slate-400">No orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 text-sm text-slate-500">#<?= $order['id'] ?></td>
                                <td class="p-4 text-sm font-bold text-slate-800"><?= htmlspecialchars($order['product_name']) ?></td>
                                <td class="p-4 text-sm text-slate-600"><?= $order['quantity'] ?></td>
                                <td class="p-4 text-sm text-slate-600">$<?= number_format($order['price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>