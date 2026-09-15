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
    <title>Welcome | Product Inventory Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
     
        .cinematic-bg {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #334155, #0f172a);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="cinematic-bg h-screen flex items-center justify-center font-sans text-slate-100">

    <div class="max-w-4xl w-full mx-auto p-8 text-center space-y-12">
        <div class="space-y-4">
            <h1 class="text-5xl font-extrabold tracking-tight text-white drop-shadow-lg">
                Inventory Management System
            </h1>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto">
                Streamline your product catalog, manage real-time stock levels, and process orders seamlessly with our advanced tracking architecture.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-2xl mx-auto mt-12">
            <!-- Admin Portal Card -->
            <a href="login.php" class="group block bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl hover:bg-white/20 transition duration-300 shadow-xl">
                <div class="text-4xl mb-4">🛡️</div>
                <h2 class="text-2xl font-bold text-white mb-2 group-hover:text-indigo-300 transition">Admin Portal</h2>
                <p class="text-sm text-slate-300">Manage products, users, support tickets, and system settings.</p>
            </a>

            <!-- User Portal Card -->
            <a href="users_login.php" class="group block bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-2xl hover:bg-white/20 transition duration-300 shadow-xl">
                <div class="text-4xl mb-4">👤</div>
                <h2 class="text-2xl font-bold text-white mb-2 group-hover:text-emerald-300 transition">User Portal</h2>
                <p class="text-sm text-slate-300">Browse inventory, place orders, and manage your account.</p>
            </a>
        </div>
    </div>

</body>
</html>