<?php  
session_start();
$is_admin = isset($_SESSION['admin_id']) || (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true);
$is_user = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;

if (!$is_admin && !$is_user) {
    header("Location: users_login.php");
    exit();
}

include 'config/dbcon.php';

include 'includes/header.php';
if ($is_admin) {
    include 'includes/nav.php';
} else {
    include 'includes/user_nav.php';
}
?>

<main class="flex-1 p-8 overflow-y-auto md">
    <div class="max-w-7xl mx-auto space-y-8">
             
        <header class="flex justify-between items-center bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-700">Inventory Catalog</h1>
                <p class="text-sm text-slate-500">Browse available products and manage inventory.</p>
            </div>
            
            <?php if ($is_admin): ?>
                <button onclick="document.getElementById('addProductModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    + Add Product
                </button>
            <?php endif; ?>
        </header>

        <section class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h2 class="text-lg font-semibold mb-4">Search & Filter</h2>
            <form id="searchForm" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <input type="text" name="keyword"  class="border p-2 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <input type="date" name="start_date" class="border p-2 rounded-lg" title="Start Date">
                <input type="date" name="end_date" class="border p-2 rounded-lg" title="End Date">
                
                <select name="category" class="border p-2 rounded-lg bg-white">
                    <option value="">All Categories</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Accessories">Accessories</option>
                    <option value="phones">Phones</option>
                    <option value="laptops">Laptops</option>
                    <option value="watches">Watches</option>
                    <option value="others">Beauty</option>
                </select>

                <select name="sort_by" class="border p-2 rounded-lg bg-white">
                    <option value="created_date DESC">Date (Newest)</option>
                    <option value="created_date ASC">Date (Oldest)</option>
                    <option value="price ASC">Price (Low to High)</option>
                    <option value="price DESC">Price (High to Low)</option>
                    <option value="product_name ASC">Name (A-Z)</option>
                </select>

                <button type="submit" class="md:col-span-5 bg-slate-800 hover:bg-slate-900 text-white py-2 rounded-lg transition">Filter Results</button>
            </form>
        </section>

       
        <section class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-100 border-b">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600">Image</th>
                        <th class="p-4 font-semibold text-slate-600">Product Name</th>
                        <th class="p-4 font-semibold text-slate-600">Category</th>
                        <th class="p-4 font-semibold text-slate-600">Price</th>
                        <th class="p-4 font-semibold text-slate-600">Stock</th>
                        <th class="p-4 font-semibold text-slate-600">Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody" class="divide-y divide-slate-100">
                   
                </tbody>
            </table>
        </section>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</main>

<?php if ($is_admin): ?>
<div id="addProductModal" class="hidden fixed inset-0 bg-black/50 flex justify-center items-center backdrop-blur-sm z-50">
    <div class="bg-white p-8 rounded-xl w-96 shadow-xl">
        <h2 class="text-xl font-bold mb-4">Add New Product</h2>
        
        <form id="addProductForm" class="space-y-4">
            <input type="hidden" name="action" value="add_product">
           
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Product Image</label>
                <input type="file" name="product_image" accept="image/*" class="w-full border p-2 rounded-lg bg-white text-sm">
            </div>

            <input type="text" name="product_name" placeholder="Product Name" required class="w-full border p-2 rounded-lg">
            
            <select name="category" required class="w-full border p-2 rounded-lg bg-white">
                <option value="Electronics">Electronics</option>
                <option value="Clothing">Clothing</option>
                <option value="Accessories">Accessories</option>
                <option value="phones">Phones</option>
                <option value="laptops">Laptops</option>
                <option value="watches">Watches</option>
                <option value="others">Beauty</option>
            </select>
            
            <input type="number" name="price" placeholder="Price" step="0.01" required class="w-full border p-2 rounded-lg">
            <input type="number" name="available_stock" placeholder="Initial Stock" min="1" required class="w-full border p-2 rounded-lg">
            
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('addProductModal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Save Product</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetchProducts();

        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            fetchProducts(params);
        });

        const addProductForm = document.getElementById('addProductForm');
        if (addProductForm) {
            addProductForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                fetch('ajax/product_ajax.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.status === 'success') {
                        document.getElementById('addProductModal').classList.add('hidden');
                        document.getElementById('addProductForm').reset();
                        fetchProducts();
                    }
                })
                .catch(err => console.error('Error adding product:', err));
            });
        }
    });

    function fetchProducts(query = '') {
        fetch(`ajax/product_ajax.php?action=search&${query}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">No products found.</td></tr>`;
                return;
            }

            data.forEach(item => {
                // Replaced 'Order 1 Item' with 'Delete' action button
                let actionButtons = `<button onclick="deleteProduct(${item.id})" class="text-sm bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition">Delete</button>`;
                actionButtons += ` <a href="update_product.php?id=${item.id}" class="text-sm bg-indigo-100 text-indigo-700 px-3 py-1 rounded hover:bg-indigo-200 ml-2 transition">Edit</a>`;

                let imageHtml = item.image_path 
                    ? `<img src="${item.image_path}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm">`
                    : `<div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] text-slate-400 border border-slate-200">No Img</div>`;
                
                let stock = parseInt(item.available_stock);
                let stockBadgeClass = "bg-indigo-100 text-indigo-800"; 

                if (stock < 10) {
                    stockBadgeClass = "bg-red-100 text-red-800"; 
                } else if (stock < 20) {
                    stockBadgeClass = "bg-amber-100 text-amber-800"; 
                }
                
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50">
                        <td class="p-4">${imageHtml}</td>
                        <td class="p-4 font-medium">${item.product_name}</td>
                        <td class="p-4"><span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs">${item.category}</span></td>
                        <td class="p-4">$${item.price}</td>
                        <td class="p-4"><span class="${stockBadgeClass} px-2.5 py-1 rounded-full text-xs font-semibold">${item.available_stock} in stock</span></td>
                        <td class="p-4">${actionButtons}</td>
                    </tr>
                `;
            });
        });
    }

    // Replaced placeOrder function with deleteProduct API request handler
    function deleteProduct(productId) {
        if (confirm('Are you sure you want to permanently delete this product?')) {
            const formData = new FormData();
            formData.append('action', 'delete_product');
            formData.append('product_id', productId);

            fetch('ajax/product_ajax.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    fetchProducts(); 
                }
            })
            .catch(err => console.error('Error deleting product:', err));
        }
    }
</script>