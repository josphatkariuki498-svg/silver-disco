<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['order_status'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    header("Location: orders.php?msg=updated");
    exit;
}

// Filter by status
$status_filter = $_GET['status'] ?? 'all';
$sql = "SELECT * FROM orders";
if ($status_filter != 'all') {
    $sql .= " WHERE order_status = '$status_filter'";
}
$sql .= " ORDER BY created_at DESC";
$orders = $pdo->query($sql)->fetchAll();

include 'includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Manage Orders</h1>
    
    <div class="filter-tabs">
        <a href="?status=all" class="filter-tab <?php echo $status_filter == 'all' ? 'active' : ''; ?>">All</a>
        <a href="?status=pending" class="filter-tab <?php echo $status_filter == 'pending' ? 'active' : ''; ?>">Pending</a>
        <a href="?status=confirmed" class="filter-tab <?php echo $status_filter == 'confirmed' ? 'active' : ''; ?>">Confirmed</a>
        <a href="?status=shipped" class="filter-tab <?php echo $status_filter == 'shipped' ? 'active' : ''; ?>">Shipped</a>
        <a href="?status=delivered" class="filter-tab <?php echo $status_filter == 'delivered' ? 'active' : ''; ?>">Delivered</a>
        <a href="?status=cancelled" class="filter-tab <?php echo $status_filter == 'cancelled' ? 'active' : ''; ?>">Cancelled</a>
    </div>
    
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                <tr>
                    <td><strong><?php echo $order['order_number']; ?></strong></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td><?php echo $order['customer_phone']; ?></td>
                    <td>KES <?php echo number_format($order['total_amount']); ?></td>
                    <td>
                        <?php if($order['payment_method'] == 'mpesa'): ?>
                            <span class="badge-info">M-Pesa</span>
                        <?php else: ?>
                            <span class="badge-secondary">Cash on Delivery</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo $order['order_status']; ?>">
                            <?php echo ucfirst($order['order_status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, H:i', strtotime($order['created_at'])); ?></td>
                    <td>
                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn-sm">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>