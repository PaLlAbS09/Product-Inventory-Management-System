<?php  
session_start();  

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");      
    exit(); 
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/user_dashboard.php");      
    exit();  
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoreFront | Advanced Inventory & Shopping</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
      .promo-banner {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
        }
        .promo-banner {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">

    
    <header class="bg-white sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4 md:gap-8">
       
            <a href="index.php" class="flex-shrink-0 text-2xl font-extrabold tracking-tight text-slate-900">
                Store<span class="text-emerald-600">Front</span>
            </a>

        
            <div class="hidden md:flex flex-1 max-w-2xl relative">
                <input type="text" placeholder="Search for products, brands and more..." 
                    class="w-full bg-slate-50 border border-slate-200 text-sm rounded-l-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 rounded-r-xl transition font-medium">
                    Search
                </button>
            </div>

            
            <div class="flex items-center gap-2 md:gap-6 text-sm font-medium">
                <a href="users_login.php" class="flex items-center gap-1.5 text-slate-700 hover:text-emerald-600 transition">
                    <span class="hidden md:inline">Login / Sign Up</span>
                </a>
                <a href="login.php" class="flex items-center gap-1.5 text-slate-700 hover:text-indigo-600 transition border-l border-slate-200 pl-4 md:pl-6">
                    <span class="hidden md:inline">Seller / Admin</span>
                </a>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b border-slate-200 hidden md:block">
        <div class="max-w-7xl mx-auto px-4">
            <ul class="flex items-center justify-center gap-8 py-3 text-sm font-semibold text-slate-600">
                <li class="hover:text-emerald-600 cursor-pointer transition">Top Offers</li>
                <li class="hover:text-emerald-600 cursor-pointer transition">Electronics</li>
                <li class="hover:text-emerald-600 cursor-pointer transition">Mobiles & Tablets</li>
                <li class="hover:text-emerald-600 cursor-pointer transition">Fashion</li>
                <li class="hover:text-emerald-600 cursor-pointer transition">Home & Kitchen</li>
                <li class="hover:text-emerald-600 cursor-pointer transition">Beauty</li>
            </ul>
        </div>
    </nav>

    
    <main class="max-w-7xl mx-auto px-4 py-6 space-y-8">
        
        <section class="promo-banner rounded-2xl p-8 md:p-12 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row items-center justify-between">
            <div class="relative z-10 max-w-lg space-y-4 text-center md:text-left">
                <span class="bg-white/20 text-emerald-50 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider backdrop-blur-sm">Grand Opening</span>
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">
                    The Ultimate <br> Shopping Experience.
                </h1>
                <p class="text-emerald-100 text-lg">
                    Discover millions of products at unbeatable prices. Fast delivery, secure payments, and top-tier inventory management.
                </p>
            </div>
            
            <div class="absolute -right-10 -top-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute right-20 -bottom-20 w-56 h-56 bg-emerald-900/20 rounded-full blur-2xl"></div>
        </section>

      
        <section>
            <h2 class="text-xl font-bold text-slate-800 mb-6 px-2">Access Your Dashboard</h2>
            <div class="grid md:grid-cols-2 gap-6">
                
                
                <a href="users_login.php" class="group bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-2xl mb-6 shadow-sm">
                        
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition">Customer Portal</h3>
                    <p class="text-slate-500 mb-6">Browse our massive catalog, add items to your cart, and track your orders in real-time.</p>
                    <span class="inline-block bg-slate-900 text-white font-medium px-5 py-2.5 rounded-lg group-hover:bg-emerald-600 transition shadow-md">
                        Start Shopping &rarr;
                    </span>
                </a>

               
                <a href="login.php" class="group bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-2xl mb-6 shadow-sm">
                        
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-2 group-hover:text-indigo-600 transition">Admin & Seller Portal</h3>
                    <p class="text-slate-500 mb-6">Manage inventory, process customer orders, view revenue analytics, and handle support tickets.</p>
                    <span class="inline-block bg-slate-900 text-white font-medium px-5 py-2.5 rounded-lg group-hover:bg-indigo-600 transition shadow-md">
                        Manage Store &rarr;
                    </span>
                </a>

            </div>
        </section>

   
       <section class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Trending Right Now</h2>
        <a href="users_login.php" class="text-sm font-semibold text-emerald-600 hover:underline">View All</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    
        <!-- Product 1: Smartphone -->
        <div class="border border-slate-100 rounded-xl p-4 hover:border-emerald-200 transition cursor-pointer group">
            <div class="aspect-square bg-slate-50 rounded-lg mb-3 overflow-hidden">
                <img src="./assets/image/phone1.jpg" alt="Premium Smartphone" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <h4 class="font-semibold text-slate-700 text-sm truncate">Premium Smartphone</h4>
            <p class="text-emerald-600 font-bold mt-1">Log in to view</p>
        </div>
      
        <!-- Product 2: Laptop -->
        <div class="border border-slate-100 rounded-xl p-4 hover:border-emerald-200 transition cursor-pointer group">
            <div class="aspect-square bg-slate-50 rounded-lg mb-3 overflow-hidden">
                <img src="./assets/image/laptop1.jpg" alt="Pro Laptop M2" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <h4 class="font-semibold text-slate-700 text-sm truncate">Pro Laptop M2</h4>
            <p class="text-emerald-600 font-bold mt-1">Log in to view</p>
        </div>
        
        <!-- Product 3: Headphones -->
        <div class="border border-slate-100 rounded-xl p-4 hover:border-emerald-200 transition cursor-pointer group">
            <div class="aspect-square bg-slate-50 rounded-lg mb-3 overflow-hidden">
                <img src="./assets/image/headphone1.jpg" alt="Wireless Noise Cancelling" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <h4 class="font-semibold text-slate-700 text-sm truncate">Wireless Noise Cancelling</h4>
            <p class="text-emerald-600 font-bold mt-1">Log in to view</p>
        </div>
        
        <!-- Product 4: Smart Watch -->
        <div class="border border-slate-100 rounded-xl p-4 hover:border-emerald-200 transition cursor-pointer group">
            <div class="aspect-square bg-slate-50 rounded-lg mb-3 overflow-hidden">
                <img src="./assets/image/watch2.jpg" alt="Smart Watch Series 8" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <h4 class="font-semibold text-slate-700 text-sm truncate">Smart Watch Series 8</h4>
            <p class="text-emerald-600 font-bold mt-1">Log in to view</p>
        </div>
        
    </div>
</section>

    </main>

  
    <footer class="bg-white border-t border-slate-200 mt-12 py-8 text-center text-sm text-slate-500">
        <p>&copy; <?php echo date('Y'); ?> StoreFront Inventory Management System. All rights reserved.</p>
    </footer>

</body>
</html>