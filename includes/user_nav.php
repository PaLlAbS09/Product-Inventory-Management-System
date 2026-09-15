<?php
$is_subfolder = (basename(dirname($_SERVER['PHP_SELF'])) === 'dashboard');
$base = $is_subfolder ? '../' : '';
?>
<!-- User Sidebar -->
<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col hidden md:flex" style="position: fixed; top: 0; left: 0; height: 100vh; z-index: 40; border-right: 1px solid #1e293b;">
    <!-- Brand Header -->
    <div class="p-6 text-xl font-bold text-white tracking-wide border-b border-slate-700 text-center">
        USER <span class="text-cyan-400">PORTAL</span>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 p-4 space-y-2 flex flex-col">
        <a href="<?= $base ?>dashboard/user_dashboard.php" class="block hover:bg-slate-800 text-slate-300 hover:text-white px-4 py-3 rounded-lg transition font-medium">Dashboard & Search</a>
        <a href="<?= $base ?>users_profile.php" class="block hover:bg-slate-800 text-slate-300 hover:text-white px-4 py-3 rounded-lg transition font-medium">My Profile</a>
        
    </nav>

    <!-- Footer Logout Button -->
    <div class="p-4 border-t border-slate-700">
       <a href="../user_logout.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg transition">Logout</a>
</aside>