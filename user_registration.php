
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | User Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900">Join Us</h2>
            <p class="text-slate-500 mt-2 text-sm">Create an account to browse and order products.</p>
        </div>

        
        <form action="Authentication/user_registration_process.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="full_name" required placeholder="John Doe" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition duration-200 shadow-md shadow-emerald-500/30">
                Create Account
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Already have an account? <a href="users_login.php" class="text-emerald-600 font-semibold hover:underline">Log in here</a>
        </p>
    </div>

</body>
</html>