<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$product = null;
$error = '';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $product = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $sizeOptions = trim($_POST['size_options'] ?? '');
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $imageName = $product['image'] ?? null;

    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            $error = 'Only JPG, PNG, and WebP images are allowed.';
        } else {
            $safeName = time() . '-' . preg_replace('/[^A-Za-z0-9._-]/', '-', basename($_FILES['image']['name']));
            $targetPath = __DIR__ . '/../images/' . $safeName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imageName = $safeName;
            } else {
                $error = 'Image upload failed.';
            }
        }
    }

    if (empty($error)) {
        if ($product) {
            $stmt = $pdo->prepare('UPDATE products SET name = ?, slug = ?, description = ?, price = ?, stock = ?, category_id = ?, image = ?, size_options = ? WHERE id = ?');
            $stmt->execute([$name, $slug, $description, $price, $stock, $category_id, $imageName, $sizeOptions, $product['id']]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (name, slug, description, price, stock, category_id, image, size_options, is_featured, is_new) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0)');
            $stmt->execute([$name, $slug, $description, $price, $stock, $category_id, $imageName ?? '', $sizeOptions]);
        }

        header('Location: products.php');
        exit;
    }
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY display_order')->fetchAll();
include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title"><?php echo $product ? 'Edit Product' : 'Add Product'; ?></h1>
    <div class="info-card">
        <?php if (!empty($error)): ?>
            <div class="alert alert-warning" style="margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" class="form-group" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price'] ?? '0'); ?>" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo (int)$category['id']; ?>" <?php echo (($product['category_id'] ?? 0) == $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Shoe Sizes (optional)</label>
                <input type="text" name="size_options" value="<?php echo htmlspecialchars($product['size_options'] ?? ''); ?>" placeholder="39, 40, 41, 42">
                <p style="margin-top:6px; color:#666; font-size:13px;">For shoes, enter sizes as numbers separated by commas.</p>
            </div>
            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                <p style="margin-top:6px; color:#666; font-size:13px;">Leave blank to keep the current image.</p>
                <?php if (!empty($product['image'])): ?>
                    <div style="margin-top:10px;">
                        <strong>Current image:</strong><br>
                        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="Current product image" style="max-width:180px; max-height:180px; object-fit:cover; border-radius:8px; margin-top:8px;">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
