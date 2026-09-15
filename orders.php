<?php
session_start();
include 'config/auth_check.php';
include 'config/dbcon.php';

$database = new Database();
$db = $database->getConnection();

// Fetch all orders joined with the product_inventory table (fixed column name: order_date)
try {
    $query = "SELECT o.id AS order_id, o.quantity, o.order_date, p.product_name, p.price 
              FROM orders o 
              JOIN product_inventory p ON o.product_id = p.id 
              ORDER BY o.id DESC";
    $stmt = $db->query($query);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $orders = [];
}

// Include Header and Navigation Sidebar
include 'includes/header.php';
include 'includes/nav.php';
?>

<!-- Main Content Area -->
<main class="flex-1 p-8 overflow-y-auto md:ml-[280px]">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header Section -->
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Order Management</h1>
                <p class="text-sm text-slate-500">Review all customer orders and stock reductions.</p>
            </div>
            <a href="admin_dashboard.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Dashboard</a>
        </header>

        <!-- Orders Data Grid -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Order ID</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Product Name</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Quantity</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Unit Price</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Total Price</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Order Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">No orders have been placed yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <?php $totalPrice = $order['quantity'] * $order['price']; ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 text-sm text-slate-500">#<?= htmlspecialchars($order['order_id']) ?></td>
                                <td class="p-4 text-sm font-bold text-slate-800"><?= htmlspecialchars($order['product_name']) ?></td>
                                <td class="p-4 text-sm text-slate-600">
                                    <span class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full text-xs font-medium">
                                        <?= htmlspecialchars($order['quantity']) ?> item(s)
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-slate-600">$<?= number_format($order['price'], 2) ?></td>
                                <td class="p-4 text-sm font-bold text-slate-700">$<?= number_format($totalPrice, 2) ?></td>
                                <td class="p-4 text-sm text-slate-500"><?= htmlspecialchars($order['order_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
    <?php 

include 'includes/footer.php'; 
?>

</main>


