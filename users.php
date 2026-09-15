<?php 
session_start();
include 'config/auth_check.php';
include 'config/dbcon.php';

// Include Header and Navigation Sidebar
include 'includes/header.php';
include 'includes/nav.php';
?>

<!-- Main Content Area -->
<main class="flex-1 p-8 overflow-y-auto">
    <div class="max-w-7xl mx-auto space-y-6">
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">User Management</h1>
                <p class="text-sm text-slate-500">Register new users and manage existing accounts.</p>
            </div>
            <div class="flex gap-3">
                <a href="search.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Advanced Search</a>
                <button onclick="document.getElementById('addUserModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    + Add User
                </button>
            </div>
        </header>

        <!-- User Data Grid -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600 text-sm">ID</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Full Name</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Email</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Joined Date</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-slate-100">
                    <!-- Dynamic Data loaded via AJAX -->
                </tbody>
            </table>
        </section>
    </div>
    <?php

include 'includes/footer.php'; 
?>
</main>


<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed inset-0 bg-black/50 flex justify-center items-center backdrop-blur-sm z-50">
    <div class="bg-white p-8 rounded-2xl w-96 shadow-xl">
        <h2 class="text-xl font-bold mb-4 text-slate-800">Register New User</h2>
        <form id="addUserForm" class="space-y-4">
            <input type="hidden" name="action" value="add_user">
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Full Name</label>
                <input type="text" name="full_name" required class="w-full border border-slate-200 p-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Email Address</label>
                <input type="email" name="email" required class="w-full border border-slate-200 p-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Temporary Password</label>
                <input type="password" name="password" required class="w-full border border-slate-200 p-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">Save User</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', fetchUsers);

    function fetchUsers() {
        fetch('ajax/user_ajax.php?action=fetch_all')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';
            data.forEach(user => {
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 text-sm text-slate-500">#${user.id}</td>
                        <td class="p-4 text-sm font-bold text-slate-800">${user.full_name}</td>
                        <td class="p-4 text-sm text-slate-600">${user.email}</td>
                        <td class="p-4 text-sm text-slate-500">${new Date(user.created_at).toLocaleDateString()}</td>
                        <td class="p-4 text-right space-x-2">
                            <button onclick="deleteUser(${user.id})" class="text-xs bg-red-100 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-200 font-medium transition">Delete</button>
                        </td>
                    </tr>
                `;
            });
        });
    }

    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('ajax/user_ajax.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                this.reset();
                document.getElementById('addUserModal').classList.add('hidden');
                fetchUsers();
            }
        });
    });

    function deleteUser(id) {
        if(confirm('Are you sure you want to permanently delete this user?')) {
            const formData = new FormData();
            formData.append('action', 'delete_user');
            formData.append('id', id);

            fetch('ajax/user_ajax.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                fetchUsers();
            });
        }
    }
</script>

