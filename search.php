
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Directory Search | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans p-8">

    <div class="max-w-7xl mx-auto space-y-6">
        
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Advanced Directory Engine</h1>
                <p class="text-sm text-slate-500">Filter, search, and sort records dynamically.</p>
            </div>
            <a href="users.php" class="text-indigo-600 font-medium hover:underline text-sm">&larr; Back to Users</a>
        </header>

        <!-- Advanced Search Filter Engine -->
        <section class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <form id="advancedSearchForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Keyword Search[cite: 1] -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Keyword Search</label>
                    <input type="text" name="keyword" placeholder="Name or Email..." class="w-full border border-slate-200 p-2.5 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Filter by Date Range[cite: 1] -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Joined After</label>
                    <input type="date" name="start_date" class="w-full border border-slate-200 p-2.5 rounded-lg text-slate-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Joined Before</label>
                    <input type="date" name="end_date" class="w-full border border-slate-200 p-2.5 rounded-lg text-slate-600">
                </div>

                <!-- Dynamic Sorting[cite: 1] -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Sort By</label>
                    <select name="sort_by" class="w-full border border-slate-200 p-2.5 rounded-lg text-slate-600 bg-white">
                        <option value="created_at DESC">Date (Newest First)</option>
                        <option value="created_at ASC">Date (Oldest First)</option>
                        <option value="full_name ASC">Name (A-Z)</option>
                        <option value="full_name DESC">Name (Z-A)</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-medium px-6 py-2.5 rounded-lg transition">Apply Filters</button>
                </div>
            </form>
        </section>

        <!-- Filtered Results Grid -->
        <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-indigo-50 border-b border-indigo-100">
                    <tr>
                        <th class="p-4 font-semibold text-indigo-900 text-sm">Account ID</th>
                        <th class="p-4 font-semibold text-indigo-900 text-sm">Full Name</th>
                        <th class="p-4 font-semibold text-indigo-900 text-sm">Email Reference</th>
                        <th class="p-4 font-semibold text-indigo-900 text-sm">Timestamp</th>
                    </tr>
                </thead>
                <tbody id="searchResultsBody" class="divide-y divide-slate-100">
                    <tr><td colspan="4" class="p-8 text-center text-slate-400">Apply filters to view dynamic results.</td></tr>
                </tbody>
            </table>
        </section>

    </div>

    <script>
        document.getElementById('advancedSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            
            fetch(`ajax/search_sort_ajax.php?${params}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('searchResultsBody');
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-slate-500">No records found matching your criteria.</td></tr>`;
                    return;
                }

                data.forEach(item => {
                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 text-sm text-slate-500">#${item.id}</td>
                            <td class="p-4 text-sm font-bold text-slate-800">${item.full_name}</td>
                            <td class="p-4 text-sm text-slate-600">${item.email}</td>
                            <td class="p-4 text-sm text-slate-500">${item.created_at}</td>
                        </tr>
                    `;
                });
            });
        });
    </script>
</body>
</html>