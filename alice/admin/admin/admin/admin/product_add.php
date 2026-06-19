<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $description = $_POST['description'] ?? '';
    $price = (float)$_POST['price'];
    $old_price = !empty($_POST['old_price']) ? (float)$_POST['old_price'] : null;
    $category_id = (int)$_POST['category_id'];
    $stock = (int)$_POST['stock'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    
    // Handle image upload
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $image_name = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $filename);
            $upload_path = '../images/' . $image_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Success
            } else {
                $error = "Failed to upload image";
            }
        } else {
            $error = "Invalid file type. Allowed: JPG, PNG, GIF, WEBP";
        }
    } else {
        $error = "Please select an image";
    }
    
    if (empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, old_price, category_id, image, stock, is_featured, is_new) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$name, $slug, $description, $price, $old_price, $category_id, $image_name, $stock, $is_featured, $is_new])) {
            $success = "Product added successfully!";
            // Clear form
            $_POST = [];
        } else {
            $error = "Failed to add product";
        }
    }
}

include 'includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Add New Product</h1>
    
    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="admin-form">
        <div class="form-grid">
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Category *</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price (KES) *</label>
                <input type="number" name="price" step="0.01" required value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Old Price (Optional)</label>
                <input type="number" name="old_price" step="0.01" value="<?php echo htmlspecialchars($_POST['old_price'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Stock Quantity *</label>
                <input type="number" name="stock" required value="<?php echo htmlspecialchars($_POST['stock'] ?? 10); ?>">
            </div>
            
            <div class="form-group">
                <label>Product Image *</label>
                <input type="file" name="image" accept="image/*" required>
                <small>Recommended: 600x600px JPG or PNG</small>
            </div>
            
            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_featured" value="1" <?php echo isset($_POST['is_featured']) ? 'checked' : ''; ?>>
                    Featured Product
                </label>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_new" value="1" <?php echo isset($_POST['is_new']) ? 'checked' : ''; ?>>
                    New Arrival
                </label>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>