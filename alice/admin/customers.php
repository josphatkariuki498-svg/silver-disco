<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$customers = $pdo->query("SELECT customer_name, customer_phone, customer_email, customer_address, COUNT(*) as order_count, SUM(total_amount) as total_spent, MAX(created_at) as last_order FROM orders GROUP BY customer_phone ORDER BY last_order DESC")->fetchAll();
include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Customers</h1>
    <div class="info-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Phone</th><th>Email</th><th>Orders</th><th>Total Spent</th><th>Last Order</th></tr></thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_phone']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_email'] ?? 'N/A'); ?></td>
                        <td><?php echo (int)$customer['order_count']; ?></td>
                        <td>KES <?php echo number_format((float)$customer['total_spent']); ?></td>
                        <td><?php echo htmlspecialchars($customer['last_order']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
