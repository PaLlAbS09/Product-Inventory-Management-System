<?php
session_start();
include 'config/auth_check.php';
include 'config/dbcon.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: products.php');
    exit;
}

try {
    $stmt = $db->prepare("SELECT * FROM product_inventory WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found. <a href='products.php' class='text-indigo-600 underline'>Back to Products</a>");
    }
} catch (PDOException $e) {
    die("Error fetching product data.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? 0;
    $available_stock = $_POST['available_stock'] ?? 0;
    
   
    $image_path = $product['image_path'] ?? null; 

    
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
       
        $uploadDir = 'assets/product_images/'; 
        
        $fileExtension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('prod_') . '.' . $fileExtension;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
           
            if (!empty($product['image_path']) && file_exists($product['image_path'])) {
                unlink($product['image_path']);
            }
            
            
            $image_path = $targetFile;
        }
    }

    try {
        $updateStmt = $db->prepare("UPDATE product_inventory SET product_name = :name, category = :category, price = :price, available_stock = :stock, image_path = :image_path WHERE id = :id");
        $updateStmt->execute([
            ':name' => $product_name,
            ':category' => $category,
            ':price' => $price,
            ':stock' => $available_stock,
            ':image_path' => $image_path,
            ':id' => $id
        ]);

        header('Location: products.php?status=success&msg=Product updated successfully');
        exit;
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}
include 'includes/header.php';
include 'includes/nav.php';
?>

<main class="flex-1 p-8 overflow-y-auto md:ml-[280px]">
    <div class="max-w-3xl mx-auto space-y-6">
        
        
        <header class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Product</h1>
                <p class="text-sm text-slate-500">Update inventory details for #<?= htmlspecialchars($product['id']) ?></p>
            </div>
            <a href="products.php" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">&larr; Back to Products</a>
        </header>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        
        <section class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            
            <form action="update_product.php?id=<?= $product['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                
              
                <?php if (!empty($product['image_path'])): ?>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase">Current Image</label>
                        <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Product Image" class="w-32 h-32 object-cover rounded-xl border border-slate-200 shadow-sm">
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Replace Image (Optional)</label>
                    <input type="file" name="product_image" accept="image/*" class="w-full border border-slate-200 p-2 rounded-xl bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm">
                    <p class="text-xs text-slate-400 mt-1">Leave blank to keep the current image.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Product Name</label>
                    <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Category</label>
                    <select name="category" required class="w-full border border-slate-200 p-3 rounded-xl bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="Electronics" <?= $product['category'] === 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                        <option value="Clothing" <?= $product['category'] === 'Clothing' ? 'selected' : '' ?>>Clothing</option>
                        <option value="Accessories" <?= $product['category'] === 'Accessories' ? 'selected' : '' ?>>Accessories</option>
                        <option value="phones" <?= strtolower($product['category']) === 'phones' ? 'selected' : '' ?>>Phones</option>
                        <option value="laptops" <?= strtolower($product['category']) === 'laptops' ? 'selected' : '' ?>>Laptops</option>
                        <option value="watches" <?= strtolower($product['category']) === 'watches' ? 'selected' : '' ?>>Watches</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Price ($)</label>
                    <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Available Stock</label>
                    <input type="number" name="available_stock" min="0" value="<?= htmlspecialchars($product['available_stock']) ?>" required class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="products.php" class="px-6 py-3 bg-slate-100 text-slate-600 font-medium rounded-xl hover:bg-slate-200 transition">Cancel</a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition shadow-md shadow-indigo-500/20">Update Product</button>
                </div>
            </form>
        </section>
    </div>
</main>

<?php 
include 'includes/footer.php'; 
?>