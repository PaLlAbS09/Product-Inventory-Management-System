<?php
session_start();

if ((!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) && isset($_COOKIE['user_remember'])) {
    require_once 'config/dbcon.php';
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $stmt = $db->prepare("SELECT id, full_name FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $_COOKIE['user_remember']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
        }
    } catch (Exception $e) {
        
    }
}


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: users_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | StoreFront</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- E-commerce Style Top Navbar -->
    <header class="bg-white sticky top-0 z-50 shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4 md:gap-8">
            
            <!-- Brand Logo -->
            <a href="user_dashboard.php" class="flex-shrink-0 text-2xl font-extrabold tracking-tight text-slate-900">
                Store<span class="text-emerald-600">Front</span>
            </a>

            <!-- Functional Search Bar -->
            <div class="hidden md:flex flex-1 max-w-2xl relative group">
                <input type="text" id="dashboardSearchInput" placeholder="Search for products, brands and more..." 
                    class="w-full bg-slate-100 border-2 border-transparent text-sm rounded-l-xl px-4 py-2.5 focus:outline-none focus:bg-white focus:border-emerald-500 transition-all duration-300">
                <button id="dashboardSearchBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 rounded-r-xl transition font-medium">
                    Search
                </button>
            </div>

            <!-- User Menu & Cart -->
            <div class="flex items-center gap-4 md:gap-6 text-sm font-medium">
                <div class="flex flex-col items-end">
                    <span class="text-xs text-slate-500">Hello,</span>
                    <span class="text-slate-800 font-bold truncate max-w-[120px]"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                </div>
                
                <a href="user_shop.php" class="relative p-2 text-slate-600 hover:text-emerald-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </a>

               <a href="user_logout.php" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg transition font-semibold border border-red-100">
    Logout
</a>
            </div>
        </div>
    </header>

    <!-- Main Dashboard Content -->
    <main class="max-w-7xl mx-auto px-4 py-8 space-y-10 flex-1 w-full">

        
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Your Account</h1>
            <p class="text-slate-500 mt-1">Manage your orders, track shipments, and update your profile.</p>
        </div>

        <!-- Action Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <a href="users_payments.php" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-emerald-300 transition-all duration-300 flex items-start gap-4">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
                    📦
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-emerald-600 transition">Your Orders</h3>
                    <p class="text-sm text-slate-500 mt-1">Track, return, or buy things again. View your complete purchase history.</p>
                </div>
            </a>

            <a href="user_shop.php" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-blue-300 transition-all duration-300 flex items-start gap-4">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
                    🛍️
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-blue-600 transition">Browse Store</h3>
                    <p class="text-sm text-slate-500 mt-1">Discover new products, explore the live catalog, and place new orders.</p>
                </div>
            </a>

            <a href="users_profile.php" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-purple-300 transition-all duration-300 flex items-start gap-4">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
                    🔐
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-purple-600 transition">Login & Security</h3>
                    <p class="text-sm text-slate-500 mt-1">Edit your personal profile, update your email, and manage security settings.</p>
                </div>
            </a>

            <a href="user_support.php" class="group bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:border-amber-300 transition-all duration-300 flex items-start gap-4">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
                    🎧
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-amber-600 transition">Customer Service</h3>
                    <p class="text-sm text-slate-500 mt-1">Submit support tickets, view system notices, and get help with your account.</p>
                </div>
            </a>

        </div>

        <!-- Keep Shopping Section -->
        <section class="mt-12 pt-8 border-t border-slate-200">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Keep Shopping</h2>
                    <p class="text-sm text-slate-500 mt-1">Recommendations based on recent activity</p>
                </div>
                <a href="user_shop.php" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline">Explore Catalog &rarr;</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <a href="user_shop.php" class="block bg-white border border-slate-200 rounded-2xl p-3 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 group">
                    <div class="aspect-square bg-slate-100 rounded-xl mb-4 overflow-hidden relative">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md z-10">-20%</span>
                        <img src="./assets/image/headphone1.jpg" alt="Headphones" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h4 class="font-bold text-slate-800 truncate">Wireless Headphones</h4>
                    <p class="text-emerald-600 font-extrabold mt-1">Shop Now</p>
                </a>
                
                <a href="user_shop.php" class="block bg-white border border-slate-200 rounded-2xl p-3 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 group">
                    <div class="aspect-square bg-slate-100 rounded-xl mb-4 overflow-hidden relative">
                        <img src="./assets/image/laptop3.png" alt="Laptop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h4 class="font-bold text-slate-800 truncate">Gaming Laptop</h4>
                    <p class="text-emerald-600 font-extrabold mt-1">Shop Now</p>
                </a>

                <a href="user_shop.php" class="block bg-white border border-slate-200 rounded-2xl p-3 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 group">
                    <div class="aspect-square bg-slate-100 rounded-xl mb-4 overflow-hidden relative">
                        <span class="absolute top-2 left-2 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-md z-10">Bestseller</span>
                        <img src="./assets/image/watch1.jpeg" alt="Watch" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h4 class="font-bold text-slate-800 truncate">Smart Watch</h4>
                    <p class="text-emerald-600 font-extrabold mt-1">Shop Now</p>
                </a>

                <a href="user_shop.php" class="block bg-white border border-slate-200 rounded-2xl p-3 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 group">
                    <div class="aspect-square bg-slate-100 rounded-xl mb-4 overflow-hidden relative">
                        <img src="./assets/image/phone2.png" alt="smartphones" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h4 class="font-bold text-slate-800 truncate">Smartphone</h4>
                    <p class="text-emerald-600 font-extrabold mt-1">Shop Now</p>
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-sm text-slate-500 mt-auto">
        <p>&copy; <?php echo date('Y'); ?> StoreFront Inventory Management System. All rights reserved.</p>
    </footer>

  
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('dashboardSearchInput');
            const searchBtn = document.getElementById('dashboardSearchBtn');

            function performSearch() {
                const query = searchInput.value.trim();
                if (query) {
                    window.location.href = `user_shop.php?keyword=${encodeURIComponent(query)}`;
                } else {
                    window.location.href = `user_shop.php`;
                }
            }

            searchBtn.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') performSearch();
            });
        });
    </script>
</body>
</html>