<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$order_id = (int)($_GET['id'] ?? 0);
$order = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
$order->execute([$order_id]);
$order = $order->fetch();

if (!$order) {
    header('Location: orders.php');
    exit;
}

$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_received'])) {
    $pdo->prepare("UPDATE orders SET order_status = 'completed' WHERE id = ?")->execute([$order_id]);
    $order['order_status'] = 'completed';
    $success_message = 'Order marked as received and completed.';
}

$items = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
$items->execute([$order_id]);
$items = $items->fetchAll();

include __DIR__ . '/includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Order Details</h1>
    <?php if ($success_message): ?>
        <div style="background:#e8f5e9;color:#2e7d32;padding:10px 15px;border-radius:6px;margin-bottom:15px;">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <div class="info-card">
        <?php if (($order['order_status'] ?? '') !== 'completed'): ?>
        <form method="post" style="margin-bottom: 15px;">
            <button type="submit" name="mark_received" class="btn btn-primary" onclick="return confirm('Confirm that the customer received this order?')">Confirm Received</button>
        </form>
        <?php else: ?>
        <p style="margin-bottom: 15px; color:#2e7d32; font-weight:600;">This order has already been marked as received.</p>
        <?php endif; ?>
        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
        <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($order['order_status'])); ?></p>
        <p><strong>Total:</strong> KES <?php echo number_format((float)$order['total_amount']); ?></p>
    </div>

    <div class="info-card">
        <h3>Items</h3>
        <table class="admin-table">
            <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo (int)$item['quantity']; ?></td>
                    <td>KES <?php echo number_format((float)$item['price']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
