<?php
session_start();
include 'config/user_auth.php'; 
include 'config/dbcon.php';
include 'includes/user_nav.php';
// Verify user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: users_login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$database = new Database();
$db = $database->getConnection();

// Fetch user profile details
$stmt = $db->prepare("SELECT full_name, email, created_at FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings | User Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans p-8">
    <div class="max-w-2xl mx-auto space-y-6">
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Account Settings</h1>
                <p class="text-sm text-slate-500">Manage your personal information.</p>
            </div>
            <a href="dashboard/user_dashboard.php" class="text-emerald-600 font-medium hover:underline text-sm">&larr; Back to Dashboard</a>
        </header>

        <section class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Full Name</label>
                <p class="text-lg font-bold text-slate-800"><?= htmlspecialchars($user['full_name'] ?? '') ?></p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Email Address</label>
                <p class="text-lg text-slate-800"><?= htmlspecialchars($user['email'] ?? '') ?></p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Account Created</label>
                <p class="text-sm text-slate-600"><?= htmlspecialchars($user['created_at'] ?? '') ?></p>
            </div>
        </section>
    </div>
</body>
</html>