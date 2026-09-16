<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center h-screen font-sans">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Reset Password</h2>
            <p class="text-slate-500 mt-2">Enter your admin email to set a new password.</p>
        </div>

        <form id="forgotPasswordForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Registered Email</label>
                <input type="email" name="email" required class="w-full border border-slate-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
                <input type="password" name="new_password" required class="w-full border border-slate-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password</label>
                <input type="password" name="confirm_password" required class="w-full border border-slate-300 p-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
            </div>

            <div id="resetMessage" class="hidden text-sm p-3 rounded-lg"></div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 rounded-lg transition duration-200">
                Reset Password
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Remembered your password? <a href="login.php" class="text-indigo-600 font-semibold hover:underline">Back to Login</a>
        </p>
    </div>

    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageBox = document.getElementById('resetMessage');
            messageBox.className = 'hidden text-sm p-3 rounded-lg';

            fetch('ajax/forgot_password_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                messageBox.classList.remove('hidden');
                messageBox.textContent = data.message;
                
                if (data.status === 'success') {
                    messageBox.classList.add('bg-emerald-50', 'text-emerald-700');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 2000); 
                } else {
                    messageBox.classList.add('bg-red-50', 'text-red-600');
                }
            })
            .catch(error => {
                messageBox.classList.remove('hidden');
                messageBox.classList.add('bg-red-50', 'text-red-600');
                messageBox.textContent = "A system error occurred.";
            });
        });
    </script>
</body>
</html>