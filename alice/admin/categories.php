<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['add_category'])) {
    $name = trim($_POST['name'] ?? '');
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)')->execute([$name, $slug]);
    header('Location: categories.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);
    header('Location: categories.php');
    exit;
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY display_order')->fetchAll();
include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Categories</h1>
    <div class="info-card">
        <h3>Add Category</h3>
        <form method="POST" style="margin-bottom:20px;">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <button type="submit" name="add_category" class="btn btn-primary">Save</button>
        </form>
    </div>
    <div class="info-card">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Slug</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><?php echo htmlspecialchars($category['slug']); ?></td>
                    <td><a href="categories.php?delete=<?php echo (int)$category['id']; ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this category?')">Delete</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
