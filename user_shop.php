<?php
session_start();
require_once 'config/dbcon.php'; 
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
    <title>Shop & Place Orders | StoreFront</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
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
                <input type="text" id="searchInput" placeholder="Search for products, brands and more..." 
                    class="w-full bg-slate-100 border-2 border-transparent text-sm rounded-l-xl px-4 py-2.5 focus:outline-none focus:bg-white focus:border-emerald-500 transition-all duration-300">
                <button id="searchBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 rounded-r-xl transition font-medium">
                    Search
                </button>
            </div>

            <!-- User Menu & Navigation -->
            <div class="flex items-center gap-4 md:gap-6 text-sm font-medium">
                <div class="hidden lg:flex items-center gap-4 border-r border-slate-200 pr-6 mr-2">
                    <a href="user_dashboard.php" class="text-slate-600 hover:text-emerald-600 transition">Dashboard</a>
                    <a href="users_payments.php" class="text-slate-600 hover:text-emerald-600 transition">Orders</a>
                </div>

                <div class="flex flex-col items-end">
                    <span class="text-xs text-slate-500">Hello,</span>
                    <span class="text-slate-800 font-bold truncate max-w-[120px]"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                </div>
                
                <a href="Authentication/user_logout.php" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg transition font-semibold border border-red-100 hidden md:block">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Functional Category Nav -->
    <nav class="bg-white border-b border-slate-200 hidden md:block">
        <div class="max-w-7xl mx-auto px-4">
            <ul id="categoryNav" class="flex items-center gap-8 py-3 text-sm font-semibold text-slate-600 overflow-x-auto">
                <li data-category="" class="cat-link text-emerald-600 border-b-2 border-emerald-600 pb-2 -mb-3 cursor-pointer">All Products</li>
                <li data-category="Electronics" class="cat-link hover:text-emerald-600 cursor-pointer transition">Electronics</li>
                <li data-category="Clothing" class="cat-link hover:text-emerald-600 cursor-pointer transition">Clothing</li>
                <li data-category="Accessories" class="cat-link hover:text-emerald-600 cursor-pointer transition">Accessories</li>
                <li data-category="Laptops" class="cat-link hover:text-emerald-600 cursor-pointer transition">Laptops</li>
                <li data-category="Watches" class="cat-link hover:text-emerald-600 cursor-pointer transition">Watches</li>
                <li data-category="Smartphones" class="cat-link hover:text-emerald-600 cursor-pointer transition">Smartphones</li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 py-8 space-y-8 flex-1 w-full">
        
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 bg-white p-8 rounded-2xl shadow-sm border border-slate-200 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Live Catalog</h1>
                <p class="text-slate-500 mt-2">Browse items, select quantities, and checkout instantly.</p>
            </div>
            
            <!-- Functional Filter Dropdown -->
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-slate-500">Sort by:</span>
                <select id="sortDropdown" class="border border-slate-200 bg-slate-50 rounded-lg px-3 py-2 text-sm font-medium outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                    <option value="created_date DESC">Newest Arrivals</option>
                    <option value="price ASC">Price: Low to High</option>
                    <option value="price DESC">Price: High to Low</option>
                    <option value="product_name ASC">Name: A to Z</option>
                </select>
            </div>
        </header>

        <!-- Product Grid -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="productGrid">
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-sm text-slate-500 mt-auto">
        <p>&copy; <?php echo date('Y'); ?> StoreFront Inventory Management System. All rights reserved.</p>
    </footer>

   <script>
  
        let currentKeyword = '';
        let currentCategory = '';
        let currentSort = 'created_date DESC';

        document.addEventListener('DOMContentLoaded', () => {
            
            applyFilters();

          
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            function triggerSearch() {
                currentKeyword = searchInput.value.trim();
                applyFilters();
            }

            searchBtn.addEventListener('click', triggerSearch);
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') triggerSearch();
            });

           
            const sortDropdown = document.getElementById('sortDropdown');
            sortDropdown.addEventListener('change', (e) => {
                currentSort = e.target.value;
                applyFilters();
            });

          
            const categoryLinks = document.querySelectorAll('.cat-link');
            categoryLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    
                    categoryLinks.forEach(l => {
                        l.classList.remove('text-emerald-600', 'border-b-2', 'border-emerald-600', 'pb-2', '-mb-3');
                        l.classList.add('hover:text-emerald-600');
                    });
                    e.target.classList.add('text-emerald-600', 'border-b-2', 'border-emerald-600', 'pb-2', '-mb-3');
                    e.target.classList.remove('hover:text-emerald-600');

                 
                    currentCategory = e.target.getAttribute('data-category');
                    applyFilters();
                });
            });
        });

       
        function applyFilters() {
            const params = new URLSearchParams();
            if (currentKeyword) params.append('keyword', currentKeyword);
            if (currentCategory) params.append('category', currentCategory);
            if (currentSort) params.append('sort_by', currentSort);

            fetchProducts(params.toString());
        }

        
        function fetchProducts(queryString = '') {
            fetch(`ajax/product_ajax.php?action=search&${queryString}`)
            .then(res => res.json())
            .then(data => {
                const grid = document.getElementById('productGrid');
                grid.innerHTML = '';

                if (data.length === 0) {
                    grid.innerHTML = `
                        <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm">
                            <span class="text-4xl">🔍</span>
                            <h3 class="text-lg font-bold text-slate-800 mt-4">No products found</h3>
                            <p class="text-slate-500 mt-1">Try adjusting your search or filters.</p>
                            <button onclick="resetFilters()" class="mt-4 text-emerald-600 font-semibold hover:underline">Clear all filters</button>
                        </div>`;
                    return;
                }

                data.forEach(item => {
                    const isOutOfStock = item.available_stock <= 0;
                    
                   
                    let imgUrl = item.image_path; 
                    
                    if (!imgUrl || imgUrl === '') {
                        if (item.category === 'Electronics') {
                            imgUrl = 'assets/image/headphone2.jpg';
                        } else if (item.category === 'Laptops') {
                            imgUrl = 'assets/image/laptop1.jpg';
                        } else if (item.category === 'Smartphones') {
                            imgUrl = 'assets/image/phone2.png';
                        } else if (item.category === 'Watches') {
                            imgUrl = 'assets/image/watch3.jpg';
                        } else {
                            imgUrl = 'https://via.placeholder.com/500?text=No+Image';
                        }
                    }
                    
                    grid.innerHTML += `
                        <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-emerald-300 transition-all duration-300 overflow-hidden flex flex-col">
                            <div class="aspect-square bg-slate-100 overflow-hidden relative">
                                ${isOutOfStock 
                                    ? '<span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-md z-10 shadow-sm">Out of Stock</span>' 
                                    : ''}
                                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-slate-700 text-xs font-bold px-2.5 py-1 rounded-md z-10 shadow-sm border border-slate-200/50">
                                    ${item.category}
                                </span>
                                <img src="${imgUrl}" alt="${item.product_name}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ${isOutOfStock ? 'grayscale opacity-60' : ''}">
                            </div>
                            
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-lg font-bold text-slate-800 line-clamp-1 mb-1" title="${item.product_name}">${item.product_name}</h3>
                                <p class="text-2xl font-extrabold text-slate-900 mb-2">$${parseFloat(item.price).toFixed(2)}</p>
                                
                                <!-- Stock numbers removed here -->
                                <p class="text-xs text-slate-500 mb-5 flex-1">
                                    ${isOutOfStock 
                                        ? '<span class="text-red-500 font-semibold">Currently unavailable</span>' 
                                        : '<span class="text-emerald-600 font-semibold">In stock</span> - Ready to ship'}
                                </p>
                                
                                <div class="flex items-center gap-3 pt-4 border-t border-slate-100 mt-auto">
                                    <input type="number" id="qty_${item.id}" value="1" min="1" max="${item.available_stock}" 
                                        class="w-16 border border-slate-200 p-2.5 rounded-xl text-center text-sm outline-none focus:ring-2 focus:ring-emerald-500 font-medium" 
                                        ${isOutOfStock ? 'disabled' : ''}>
                                    <button onclick="placeOrder(${item.id})" 
                                        class="flex-1 ${isOutOfStock ? 'bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-500/20'} py-2.5 rounded-xl font-bold text-sm transition-all"
                                        ${isOutOfStock ? 'disabled' : ''}>
                                        ${isOutOfStock ? 'Sold Out' : 'Buy Now'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(err => console.error('Error loading products:', err));
        }

        
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('sortDropdown').value = 'created_date DESC';
            currentKeyword = '';
            currentCategory = '';
            currentSort = 'created_date DESC';
            
          
            const categoryLinks = document.querySelectorAll('.cat-link');
            categoryLinks.forEach(l => {
                l.classList.remove('text-emerald-600', 'border-b-2', 'border-emerald-600', 'pb-2', '-mb-3');
                l.classList.add('hover:text-emerald-600');
            });
            categoryLinks[0].classList.add('text-emerald-600', 'border-b-2', 'border-emerald-600', 'pb-2', '-mb-3');
            categoryLinks[0].classList.remove('hover:text-emerald-600');

            applyFilters();
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
                    applyFilters(); 
                }
            })
            .catch(err => console.error('Error placing order:', err));
        }
    </script>
</body>
</html>