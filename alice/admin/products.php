<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    header('Location: products.php');
    exit;
}

$products = $pdo->query('SELECT * FROM products ORDER BY created_at DESC')->fetchAll();
include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1 class="page-title">Products</h1>
        <a href="product_add.php" class="btn btn-primary">Add Product</a>
    </div>
    <div class="info-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo (int)$product['id']; ?></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;margin-right:8px;">
                            <?php endif; ?>
                            <?php echo htmlspecialchars($product['name']); ?>
                        </td>
                        <td>KES <?php echo number_format((float)$product['price']); ?></td>
                        <td><?php echo (int)$product['stock']; ?></td>
                        <td>
                            <a href="product_add.php?id=<?php echo (int)$product['id']; ?>" class="btn-sm btn-secondary">Edit</a>
                            <a href="products.php?delete=<?php echo (int)$product['id']; ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
