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
                <h1 class="text-2xl font-bold text-slate-800">Support Ticket Management</h1>
                <p class="text-sm text-slate-500">Review, approve, and resolve user inquiries.</p>
            </div>
            <a href="admin_dashboard.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">Dashboard</a>
        </header>

        <!-- Ticket Data Grid -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Ticket ID</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">User Email</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Subject & Details</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm">Status</th>
                        <th class="p-4 font-semibold text-slate-600 text-sm text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="ticketTableBody" class="divide-y divide-slate-100">
                    
                </tbody>
            </table>
        </section>
    </div>
    <?php 
// Include Footer
include 'includes/footer.php'; 
?>
</main>

<script>
    document.addEventListener('DOMContentLoaded', fetchTickets);

    function fetchTickets() {
        fetch('ajax/admin_support_ajax.php?action=fetch_all')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('ticketTableBody');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-400">No support tickets found.</td></tr>`;
                return;
            }

            data.forEach(ticket => {
                let statusBadge = ticket.status === 'Pending' 
                    ? `<span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-medium">Pending</span>` 
                    : `<span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-medium">${ticket.status}</span>`;

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 text-sm text-slate-500">#${ticket.id}</td>
                        <td class="p-4 text-sm font-medium text-slate-700">${ticket.user_email}</td>
                        <td class="p-4">
                            <p class="text-sm font-bold text-slate-800">${ticket.subject}</p>
                            <p class="text-xs text-slate-500 truncate max-w-xs mt-1">${ticket.message}</p>
                        </td>
                        <td class="p-4">${statusBadge}</td>
                        <td class="p-4 text-right space-x-2">
                            <button onclick="updateTicketStatus(${ticket.id}, 'Approved')" class="text-xs bg-emerald-600 text-white px-3 py-1.5 rounded-lg hover:bg-emerald-700 transition">Approve</button>
                            <button onclick="updateTicketStatus(${ticket.id}, 'Rejected')" class="text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg hover:bg-red-700 transition">Reject</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error('Error fetching tickets:', err));
    }

    function updateTicketStatus(ticketId, newStatus) {
        if(!confirm(`Are you sure you want to mark this ticket as ${newStatus}?`)) return;

        const formData = new FormData();
        formData.append('ticket_id', ticketId);
        formData.append('status', newStatus);

        fetch('ajax/admin_support_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                fetchTickets(); // Refresh table
            }
        })
        .catch(err => console.error('Error:', err));
    }
</script>

