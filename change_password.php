<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | User Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900">Reset Password</h2>
            <p class="text-slate-500 mt-2 text-sm">Enter your registered email to set a new password.</p>
        </div>

        <form id="userForgotPasswordForm" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Registered Email</label>
                <input type="email" name="email" required placeholder="john@example.com" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">New Password</label>
                <input type="password" name="new_password" required placeholder=" " class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Confirm New Password</label>
                <input type="password" name="confirm_password" required placeholder=" " class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <div id="resetMessage" class="hidden text-sm p-3 rounded-lg border"></div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition duration-200 shadow-md shadow-emerald-500/30">
                Reset Password
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Remembered your password? <a href="users_login.php" class="text-emerald-600 font-semibold hover:underline">Back to Login</a>
        </p>
    </div>

    <script>
        document.getElementById('userForgotPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageBox = document.getElementById('resetMessage');
            
            messageBox.className = 'hidden text-sm p-3 rounded-lg border';

            fetch('ajax/user_forgot_password_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                messageBox.classList.remove('hidden');
                messageBox.textContent = data.message;
                
                if (data.status === 'success') {
                    messageBox.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                    setTimeout(() => {
                        window.location.href = 'users_login.php';
                    }, 2000); 
                } else {
                    messageBox.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
                }
            })
            .catch(error => {
                messageBox.classList.remove('hidden');
                messageBox.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
                messageBox.textContent = "A system error occurred.";
            });
        });
    </script>
</body>
</html>