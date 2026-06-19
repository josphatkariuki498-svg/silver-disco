<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$today = $pdo->query("SELECT COUNT(*) as total, COALESCE(SUM(total_amount), 0) as revenue FROM orders WHERE DATE(created_at) = CURDATE()")->fetch();
$month = $pdo->query("SELECT COUNT(*) as total, COALESCE(SUM(total_amount), 0) as revenue FROM orders WHERE MONTH(created_at) = MONTH(CURDATE())")->fetch();
$pending_orders = $pdo->query("SELECT COUNT(*) as total FROM orders WHERE order_status = 'pending'")->fetchColumn();
$total_products = $pdo->query("SELECT COUNT(*) as total FROM products")->fetchColumn();
$recent_orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10")->fetchAll();
$low_stock = $pdo->query("SELECT * FROM products WHERE stock < 5 ORDER BY stock ASC LIMIT 5")->fetchAll();

include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Dashboard</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div>
                <h3>Today's Orders</h3>
                <div class="stat-number"><?php echo (int)($today['total'] ?? 0); ?></div>
                <small>KES <?php echo number_format((float)($today['revenue'] ?? 0)); ?></small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar"></i></div>
            <div>
                <h3>This Month</h3>
                <div class="stat-number"><?php echo (int)($month['total'] ?? 0); ?></div>
                <small>KES <?php echo number_format((float)($month['revenue'] ?? 0)); ?></small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div>
                <h3>Pending Orders</h3>
                <div class="stat-number"><?php echo (int)$pending_orders; ?></div>
                <small>Need attention</small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-box"></i></div>
            <div>
                <h3>Total Products</h3>
                <div class="stat-number"><?php echo (int)$total_products; ?></div>
                <small>In catalog</small>
            </div>
        </div>
    </div>

    <?php if (!empty($low_stock)): ?>
    <div class="alert alert-warning">
        <strong>Low stock alert:</strong>
        <?php foreach ($low_stock as $product): ?>
            <?php echo htmlspecialchars($product['name']); ?> (<?php echo (int)$product['stock']; ?> left)<?php if ($product !== end($low_stock)) echo ', '; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="info-card">
        <h3>Recent Orders</h3>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td>KES <?php echo number_format((float)$order['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($order['order_status'])); ?></td>
                        <td><a href="order_detail.php?id=<?php echo (int)$order['id']; ?>" class="btn-sm btn-primary">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
