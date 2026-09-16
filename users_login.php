<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login | Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">👤</div>
            <h2 class="text-3xl font-extrabold text-slate-900">Welcome Back</h2>
            <p class="text-slate-500 mt-2 text-sm">Sign in to manage your orders and profile.</p>
        </div>

        <form action="Authentication/user_login_process.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-semibold text-slate-700">Password</label>
                    <a href="change_password.php" class="text-xs text-emerald-600 font-medium hover:underline">Forgot password?</a>
                </div>
                <input type="password" name="password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <!-- Remember Me Checkbox Added Here -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 mr-2">
                    Remember me
                </label>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-50 text-red-600 text-sm p-3 rounded-lg border border-red-100">
                    Invalid email or password.
                </div>
            <?php endif; ?>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl transition duration-200 shadow-md">
                Sign In
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Don't have an account yet? <a href="user_registration.php" class="text-emerald-600 font-semibold hover:underline">Sign up</a>
        </p>
    </div>

</body>

</html>