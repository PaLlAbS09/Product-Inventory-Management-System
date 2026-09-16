<?php
session_start();
include 'config/auth_check.php'; 
include 'config/dbcon.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $orderStmt = $db->query("SELECT COUNT(*) FROM orders");
    $totalOrders = $orderStmt->fetchColumn();

  
    $revenueStmt = $db->query("SELECT SUM(o.quantity * p.price) as total_revenue FROM orders o JOIN product_inventory p ON o.product_id = p.id");
    $revenueData = $revenueStmt->fetch(PDO::FETCH_ASSOC);
    $totalRevenue = $revenueData['total_revenue'] ?? 0;

    $valuationStmt = $db->query("SELECT SUM(price * available_stock) as inventory_valuation FROM product_inventory");
    $valuationData = $valuationStmt->fetch(PDO::FETCH_ASSOC);
    $inventoryValuation = $valuationData['inventory_valuation'] ?? 0;

} catch (PDOException $e) {
  
    $totalOrders = 0;
    $totalRevenue = 0;
    $inventoryValuation = 0;
}

include 'includes/header.php';
include 'includes/nav.php';
?>

<!-- Main Content Area -->
<main class="flex-1 p-8 overflow-y-auto md">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Analytics & Reports</h1>
                <p class="text-sm text-slate-500">Overview of financial data, product sales, and inventory status.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Print Report</button>
                <a href="admin_dashboard.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Dashboard</a>
            </div>
        </header>

        <!-- Metrics Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Revenue Generated</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-2">$<?= number_format($totalRevenue, 2) ?></h3>
                <span class="text-xs text-emerald-600 font-semibold mt-1 inline-block">Real-time calculated revenue</span>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders Processed</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-2"><?= number_format($totalOrders) ?></h3>
                <span class="text-xs text-emerald-600 font-semibold mt-1 inline-block">Stock updates executed securely</span>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Inventory Valuation</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-2">$<?= number_format($inventoryValuation, 2) ?></h3>
                <span class="text-xs text-blue-600 font-semibold mt-1 inline-block">Based on active available stock</span>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Monthly Revenue Trends</h3>
                <div class="relative w-full h-72">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Top Categories by Sales</h3>
                <div class="relative w-full h-72 flex justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    
<?php 
include 'includes/footer.php'; 
?>
</main>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue ($)',
                data: [3000, 4500, 3200, 5400, 6100, 7500],
                borderColor: '#4f46e5',
                tension: 0.3,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: ['Electronics', 'Clothing', 'Accessories'],
            datasets: [{
                data: [55, 30, 15],
                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
