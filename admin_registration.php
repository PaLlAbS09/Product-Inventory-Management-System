<!-- admin_registration.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🛡️</div>
            <h2 class="text-3xl font-extrabold text-slate-900">Admin Registration</h2>
            <p class="text-slate-500 mt-2 text-sm">Create a new secure administrator account.</p>
        </div>

        <form action="Authentication/admin_registration_process.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="full_name" required placeholder="Admin Name" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="admin@example.com" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl transition duration-200 shadow-md">
                Register Admin Account
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Already have an admin account? <a href="login.php" class="text-indigo-600 font-semibold hover:underline">Log in here</a>
        </p>
    </div>

</body>

</html>