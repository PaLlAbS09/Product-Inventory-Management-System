<?php
session_start();
include 'config/user_auth.php'; 

// Verify user authentication
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: users_login.php");
    exit;
}

include 'config/dbcon.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop & Place Orders | Customer Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10 shadow-sm">
        <div class="text-xl font-extrabold tracking-tight text-slate-900">
            Store<span class="text-emerald-600">Front</span>
        </div>
        <div class="flex items-center gap-6 text-sm font-medium">
            <a href="dashboard/user_dashboard.php" class="text-slate-600 hover:text-slate-900 transition">Dashboard</a>
            <a href="user_shop.php" class="text-emerald-600 font-semibold">Shop & Order</a>
            <a href="users_payments.php" class="text-slate-600 hover:text-slate-900 transition">My Orders</a>
            <a href="user_support.php" class="text-slate-600 hover:text-slate-900 transition">Support</a>
            
            <!-- User Menu -->
            <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                <span class="text-slate-700">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="Authentication/user_logout.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg transition">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto p-8 space-y-8">
        
        <!-- Header Section -->
        <header class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Product Catalog & Ordering</h1>
                <p class="text-sm text-slate-500">Browse available items, choose your quantity, and place orders instantly.</p>
            </div>
        </header>

        <!-- Product Grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6" id="productGrid">
            <!-- Dynamic Data loaded via AJAX -->
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetchProducts();
        });

        function fetchProducts() {
            fetch('ajax/product_ajax.php?action=search')
            .then(res => res.json())
            .then(data => {
                const grid = document.getElementById('productGrid');
                grid.innerHTML = '';

                if (data.length === 0) {
                    grid.innerHTML = `<p class="col-span-3 text-center text-slate-400 py-12">No products available at the moment.</p>`;
                    return;
                }

                data.forEach(item => {
                    const isOutOfStock = item.available_stock <= 0;
                    
                    grid.innerHTML += `
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-bold text-slate-800">${item.product_name}</h3>
                                    <span class="bg-emerald-50 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-medium">${item.category}</span>
                                </div>
                                <p class="text-2xl font-extrabold text-slate-900 mb-2">$${parseFloat(item.price).toFixed(2)}</p>
                                <p class="text-xs">
                                    ${isOutOfStock 
                                        ? '<span class="text-red-500 font-semibold">Out of Stock</span>' 
                                        : `<span class="text-emerald-600 font-semibold">${item.available_stock} available in stock</span>`}
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                <input type="number" id="qty_${item.id}" value="1" min="1" max="${item.available_stock}" 
                                    class="w-20 border border-slate-200 p-2.5 rounded-xl text-center text-sm outline-none focus:ring-2 focus:ring-emerald-500" 
                                    ${isOutOfStock ? 'disabled' : ''}>
                                <button onclick="placeOrder(${item.id})" 
                                    class="flex-1 ${isOutOfStock ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm'} py-2.5 rounded-xl font-medium text-sm transition"
                                    ${isOutOfStock ? 'disabled' : ''}>
                                    ${isOutOfStock ? 'Unavailable' : 'Place Order'}
                                </button>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(err => console.error('Error loading products:', err));
        }

        function placeOrder(productId) {
            const qtyInput = document.getElementById(`qty_${productId}`);
            const quantity = parseInt(qtyInput.value) || 1;

            const formData = new FormData();
            formData.append('action', 'place_order');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            fetch('ajax/product_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    fetchProducts(); // Refresh catalog to reflect updated stock levels
                }
            })
            .catch(err => console.error('Error placing order:', err));
        }
    </script>
</body>
</html>