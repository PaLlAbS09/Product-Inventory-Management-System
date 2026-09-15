<?php 
session_start(); 
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {    
    header('Location: login.php');    
    exit; 
} 

// Include database connection
require_once 'config/dbcon.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    // 1. Fetch Total Products Count (Fixed table name to product_inventory)
    $prodStmt = $db->query("SELECT COUNT(*) FROM product_inventory");
    $totalProducts = $prodStmt->fetchColumn();

    // 2. Fetch Total Orders Count
    $orderStmt = $db->query("SELECT COUNT(*) FROM orders");
    $totalOrders = $orderStmt->fetchColumn();

    // 3. Fetch Active Users Count
    $userStmt = $db->query("SELECT COUNT(*) FROM users");
    $totalUsers = $userStmt->fetchColumn();

    // 4. Fetch Pending Support Tickets Count
    $ticketStmt = $db->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'Pending'");
    $pendingTickets = $ticketStmt->fetchColumn();

} catch (PDOException $e) {
    // Fallback values if a query fails
    $totalProducts = 0;
    $totalOrders = 0;
    $totalUsers = 0;
    $pendingTickets = 0;
}

// Include Header & Navigation Sidebar
include 'includes/header.php';
include 'includes/nav.php';
?>

<!-- Main Content Area with proper left margin to clear the fixed sidebar -->
<main class="flex-1 p-8 overflow-y-auto md:ml-[280px]">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator') ?>!</h1>
        <p class="text-slate-500 mt-1">Here is the overview of your system today.</p>
    </header>

    <!-- Key Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Products Card -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Products</p>
                <p class="text-3xl font-bold text-slate-800 mt-1"><?= number_format($totalProducts) ?></p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-2xl">📦</div>
        </div>
        
        <!-- Total Orders Card -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Orders</p>
                <p class="text-3xl font-bold text-slate-800 mt-1"><?= number_format($totalOrders) ?></p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-2xl">🛒</div>
        </div>

        <!-- Active Users Card -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 font-medium">Active Users</p>
                <p class="text-3xl font-bold text-slate-800 mt-1"><?= number_format($totalUsers) ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl">👥</div>
        </div>

        <!-- Pending Tickets Card -->
        <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 font-medium">Pending Tickets</p>
                <p class="text-3xl font-bold text-slate-800 mt-1"><?= number_format($pendingTickets) ?></p>
            </div>
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-2xl">🎟️</div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <section class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800 mb-4">System Shortcuts</h2>
        <div class="flex gap-4">
            <a href="products.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Add New Product</a>
            <a href="manage_notices.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Publish Notice</a>
            <a href="reports.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Generate Report</a>
        </div>
    </section>
    <?php 

include 'includes/footer.php'; 
?>
</main>


