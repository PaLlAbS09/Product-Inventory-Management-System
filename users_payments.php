<?php
session_start();
require_once 'config/dbcon.php'; 

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: users_login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Fetch orders joined with the correct 'product_inventory' table
try {
    $stmt = $db->prepare("SELECT o.id AS order_id, o.quantity, o.order_date, p.product_name, p.price 
                          FROM orders o 
                          JOIN product_inventory p ON o.product_id = p.id 
                          ORDER BY o.id DESC");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | StoreFront</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar matching your Shop page -->
    <header class="bg-white sticky top-0 z-50 shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4 md:gap-8">
            <a href="dashboard/user_dashboard.php" class="flex-shrink-0 text-2xl font-extrabold tracking-tight text-slate-900">
                Store<span class="text-emerald-600">Front</span>
            </a>
            <div class="flex items-center gap-4 md:gap-6 text-sm font-medium">
                <a href="user_shop.php" class="text-slate-600 hover:text-emerald-600 transition">Shop</a>
                <a href="users_payments.php" class="text-emerald-600 font-semibold">My Orders</a>
                <span class="text-slate-700 border-l border-slate-200 pl-4">Hi, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                <a href="Authentication/user_logout.php" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg transition font-semibold border border-red-100">Logout</a>
            </div>
        </div>
    </header>

    <div class="max-w-5xl mx-auto p-8 space-y-6 flex-1 w-5/6">
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">My Orders & Purchase History</h1>
                <p class="text-sm text-slate-500">Track your past purchases and shipments.</p>
            </div>
            <a href="user_dashboard.php" class="text-emerald-600 font-semibold hover:underline text-sm">&larr; Back to Dashboard</a>
        </header>

        <section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Order ID</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Product Name</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Quantity</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Total Price</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="5" class="p-12 text-center text-slate-400">No orders found.</td></tr>
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
                                <td class="p-4 text-sm font-bold text-slate-700">$<?= number_format($totalPrice, 2) ?></td>
                                <td class="p-4 text-sm text-slate-500"><?= htmlspecialchars($order['order_date'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-sm text-slate-500 mt-auto">
        <p>&copy; <?php echo date('Y'); ?> StoreFront Inventory Management System. All rights reserved.</p>
    </footer>
</body>
</html>