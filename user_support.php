
<?php session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support & Notices | User Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen p-8">

    <div class="max-w-6xl mx-auto space-y-8">
        
        <header class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Support Center</h1>
                <p class="text-sm text-slate-500">View announcements or submit a help request.</p>
            </div>
            <a href="user_dashboard.php" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition">
                &larr; Back to Dashboard
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Notice Board Panel -->
            <section class="lg:col-span-1 space-y-4">
                <h2 class="text-lg font-bold text-slate-700 flex items-center gap-2">
                    <span>📢</span> System Notices
                </h2>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 space-y-4">
                    <!-- Example Static Notices (Typically fetched via AJAX) -->
                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl">
                        <h3 class="text-sm font-bold text-amber-800">Scheduled Maintenance</h3>
                        <p class="text-xs text-amber-700 mt-1">The inventory system will be down for 30 minutes tonight at 2:00 AM UTC.</p>
                    </div>
                    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
                        <h3 class="text-sm font-bold text-emerald-800">New Products Added</h3>
                        <p class="text-xs text-emerald-700 mt-1">Check out the new electronics category updated today!</p>
                    </div>
                </div>
            </section>

            <!-- Ticket Submission Form -->
            <section class="lg:col-span-2 space-y-4">
                <h2 class="text-lg font-bold text-slate-700 flex items-center gap-2">
                    <span>✉️</span> Submit a Ticket
                </h2>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <form id="submitTicketForm" class="space-y-5">
                        
                        <!-- Assuming user is logged in, their ID would be passed securely in backend, but for email contact: -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Your Email</label>
                            <input type="email" name="user_email" required placeholder="user@example.com" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Issue Subject</label>
                            <input type="text" name="subject" required placeholder="e.g., Missing Order Confirmation" class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Details</label>
                            <textarea name="message" rows="5" required placeholder="Describe your issue in detail..." class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition resize-none"></textarea>
                        </div>

                        <div id="ticketResponse" class="hidden p-3 rounded-xl text-sm font-medium"></div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-medium px-6 py-3 rounded-xl transition duration-200">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.getElementById('submitTicketForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const responseBox = document.getElementById('ticketResponse');

            fetch('ajax/submit_ticket.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                responseBox.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'bg-emerald-50', 'text-emerald-700');
                if (data.status === 'success') {
                    responseBox.classList.add('bg-emerald-50', 'text-emerald-700');
                    responseBox.textContent = data.message;
                    this.reset(); // Clear form
                } else {
                    responseBox.classList.add('bg-red-50', 'text-red-700');
                    responseBox.textContent = data.message;
                }
            })
            .catch(err => console.error('Error:', err));
        });
    </script>
</body>
</html>