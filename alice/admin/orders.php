<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_received'], $_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = 'completed' WHERE id = ?");
    $stmt->execute([$order_id]);
    $success_message = 'Order marked as received and completed.';
}

$orders = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll();
include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Orders</h1>
    <?php if ($success_message): ?>
        <div style="background:#e8f5e9;color:#2e7d32;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <div class="info-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Order #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td>KES <?php echo number_format((float)$order['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($order['order_status'])); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                            <a href="order_detail.php?id=<?php echo (int)$order['id']; ?>" class="btn-sm btn-primary">View</a>
                            <?php if (($order['order_status'] ?? '') !== 'completed'): ?>
                            <form method="post" style="display:inline-block; margin-left:6px;">
                                <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                                <button type="submit" name="mark_received" class="btn-sm btn-success" onclick="return confirm('Confirm that the customer received this order?')">Received</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
