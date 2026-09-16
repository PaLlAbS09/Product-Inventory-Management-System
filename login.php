<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login | Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 flex items-center justify-center h-screen font-sans">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Admin Login</h2>
            <p class="text-slate-500 mt-2">Enter your credentials to access the dashboard</p>
        </div>

        <form id="adminLoginForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full border border-slate-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-slate-700">Password</label>
                    <a href="forgetpassword.php" class="text-xs text-indigo-600 hover:underline">Forgot password?</a>
                </div>
                <input type="password" name="password" required class="w-full border border-slate-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 mr-2">
                    Remember me
                </label>
            </div>

            <div id="loginError" class="hidden text-sm text-red-600 bg-red-50 p-3 rounded-lg"></div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 rounded-lg transition duration-200">
                Sign In
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Need a new administrator account? <a href="admin_registration.php" class="text-indigo-600 font-semibold hover:underline">Register here</a>
        </p>
    </div>

    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const errorBox = document.getElementById('loginError');
            errorBox.classList.add('hidden');

            fetch('ajax/auth_ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(async response => {
                    const text = await response.text();
                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        throw new Error("Invalid server response: " + text);
                    }
                })
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = 'admin_dashboard.php';
                    } else {
                        errorBox.textContent = data.message;
                        errorBox.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorBox.textContent = error.message;
                    errorBox.classList.remove('hidden');
                });
        });
    </script>
</body>

</html>