<?php
session_start();
include '../config/user_auth.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../users_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10 shadow-sm">
        <div class="text-xl font-extrabold tracking-tight text-slate-900">
            Store<span class="text-emerald-600">Front</span>
        </div>
        <div class="flex items-center gap-6 text-sm font-medium">
            <a href="user_dashboard.php" class="text-emerald-600 font-semibold">Dashboard</a>
            <a href="../products.php" class="text-slate-600 hover:text-slate-900 transition">Browse & Order Products</a>
            <a href="../user_support.php" class="text-slate-600 hover:text-slate-900 transition">Support</a>
            <a href="../user_shop.php" class="bg-white text-emerald-700 px-6 py-3 rounded-xl font-bold hover:bg-emerald-50 transition shadow-md flex items-center gap-2">
                Shop & Place Orders
            </a>

            <!-- User Menu -->
            <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                <span class="text-slate-700">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
             <a href="../user_logout.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg transition">Logout</a>
    </nav>

    <!-- Main Content Area -->
    <main class="max-w-6xl mx-auto p-8 space-y-8">

        <!-- Welcome Hero Banner with Shop Now / Place Orders CTA -->
        <header class="bg-gradient-to-r from-emerald-600 to-teal-700 p-10 rounded-3xl text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Welcome to your Dashboard!</h1>
                <p class="text-emerald-100 max-w-xl">Browse our live inventory catalog, place new item orders instantly, or track your shipment history below.</p>
            </div>
            <!-- Decorative background element -->
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </header>

        <!-- Quick Action Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Order Status Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl mb-4">📦</div>
                <h3 class="text-lg font-bold text-slate-800">My Orders</h3>
                <p class="text-sm text-slate-500 mt-1 mb-4">View your purchase history and track current shipments.</p>
                <a href="../users_payments.php" class="text-blue-600 text-sm font-semibold hover:underline">View History &rarr;</a>
            </div>

            <!-- Profile Settings Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl mb-4">⚙️</div>
                <h3 class="text-lg font-bold text-slate-800">Account Settings</h3>
                <p class="text-sm text-slate-500 mt-1 mb-4">Update your personal information and security settings.</p>
                <a href="../users_profile.php" class="text-purple-600 text-sm font-semibold hover:underline">Edit Profile &rarr;</a>
            </div>

            <!-- Help & Support Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl mb-4">💬</div>
                <h3 class="text-lg font-bold text-slate-800">Need Help?</h3>
                <p class="text-sm text-slate-500 mt-1 mb-4">Check system notices or submit a support ticket to admins.</p>
                <a href="../user_support.php" class="text-amber-600 text-sm font-semibold hover:underline">Contact Support &rarr;</a>
            </div>
        </div>
    </main>

</body>

</html>