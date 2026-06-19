<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: products.php?msg=deleted");
    exit;
}

// Get all products with category names
$products = $pdo->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
")->fetchAll();

include 'includes/admin_header.php';
?>

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="page-title">Manage Products</h1>
        <a href="product_add.php" class="btn btn-primary">+ Add New Product</a>
    </div>
    
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success">Product <?php echo $_GET['msg']; ?> successfully!</div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td>
                        <img src="../images/<?php echo $product['image']; ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                             onerror="this.src='https://placehold.co/50x50?text=No+Image'">
                    </td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo $product['category_name'] ?? 'Uncategorized'; ?></td>
                    <td>KES <?php echo number_format($product['price']); ?></td>
                    <td>
                        <span class="stock-badge stock-<?php echo $product['stock'] < 5 ? 'low' : ($product['stock'] < 10 ? 'medium' : 'high'); ?>">
                            <?php echo $product['stock']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($product['is_featured']): ?>
                            <span class="badge-success">Yes</span>
                        <?php else: ?>
                            <span class="badge-secondary">No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn-sm btn-edit">Edit</a>
                        <a href="?delete=<?php echo $product['id']; ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>