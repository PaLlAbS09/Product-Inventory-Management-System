
<?php
session_start();
include './config/dbcon.php';
include './config/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xl mx-auto mb-3">🔒</div>
            <h2 class="text-2xl font-bold text-slate-800">Security Settings</h2>
            <p class="text-sm text-slate-500 mt-1">Update your administrator password securely.</p>
        </div>

        <form action="Authentication/change_process.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Current Password</label>
                <input type="password" name="current_password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">New Password</label>
                <input type="password" name="new_password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Confirm New Password</label>
                <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-3 rounded-xl transition mt-2">
                Update Password
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="admin_dashboard.php" class="text-xs text-indigo-600 hover:underline font-medium">&larr; Back to Dashboard</a>
        </div>
    </div>

</body>
</html>