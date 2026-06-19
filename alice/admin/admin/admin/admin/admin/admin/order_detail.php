<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: orders.php");
    exit;
}

$items = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$items->execute([$order_id]);
$order_items = $items->fetchAll();

// Update status
if (isset($_POST['update_status'])) {
    $status = $_POST['order_status'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    header("Location: order_detail.php?id=$order_id&msg=updated");
    exit;
}

include 'includes/admin_header.php';
?>

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 class="page-title">Order #<?php echo $order['order_number']; ?></h1>
        <a href="orders.php" class="btn btn-secondary">← Back to Orders</a>
    </div>
    
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success">Order status updated!</div>
    <?php endif; ?>
    
    <div class="order-detail-grid">
        <!-- Customer Info -->
        <div class="info-card">
            <h3><i class="fas fa-user"></i> Customer Information</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Phone:</strong> <?php echo $order['customer_phone']; ?></p>
            <p><strong>Email:</strong> <?php echo $order['customer_email'] ?? 'N/A'; ?></p>
            <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
            <p><strong>City:</strong> <?php echo $order['city'] ?? 'N/A'; ?></p>
        </div>
        
        <!-- Order Info -->
        <div class="info-card">
            <h3><i class="fas fa-info-circle"></i> Order Information</h3>
            <p><strong>Order Date:</strong> <?php echo date('F d, Y H:i', strtotime($order['created_at'])); ?></p>
            <p><strong>Payment Method:</strong> <?php echo $order['payment_method'] == 'mpesa' ? 'M-Pesa' : 'Cash on Delivery'; ?></p>
            <?php if($order['mpesa_code']): ?>
            <p><strong>M-Pesa Code:</strong> <?php echo $order['mpesa_code']; ?></p>
            <?php endif; ?>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge status-<?php echo $order['payment_status']; ?>">
                    <?php echo ucfirst($order['payment_status']); ?>
                </span>
            </p>
        </div>
        
        <!-- Update Status -->
        <div class="info-card">
            <h3><i class="fas fa-truck"></i> Update Order Status</h3>
            <form method="POST">
                <select name="order_status" class="status-select">
                    <option value="pending" <?php echo $order['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="confirmed" <?php echo $order['order_status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="shipped" <?php echo $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                    <option value="delivered" <?php echo $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                    <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
                <button type="submit" name="update_status" class="btn btn-primary" style="margin-top: 10px;">Update Status</button>
            </form>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="info-card" style="margin-top: 20px;">
        <h3><i class="fas fa-boxes"></i> Order Items</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($order_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>KES <?php echo number_format($item['price']); ?></td>
                    <td>KES <?php echo number_format($item['price'] * $item['quantity']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Subtotal:</strong></td>
                    <td>KES <?php echo number_format($order['total_amount'] - $order['delivery_fee']); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Delivery Fee:</strong></td>
                    <td>KES <?php echo number_format($order['delivery_fee']); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>KES <?php echo number_format($order['total_amount']); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <!-- WhatsApp Contact -->
    <div class="info-card" style="margin-top: 20px; background: #25D366; color: white;">
        <h3><i class="fab fa-whatsapp"></i> Contact Customer</h3>
        <a href="https://wa.me/<?php echo $order['customer_phone']; ?>?text=Hello%20<?php echo urlencode($order['customer_name']); ?>%2C%20Your%20order%20%23<?php echo $order['order_number']; ?>%20status%20is%20now%20<?php echo $order['order_status']; ?>.%20Thank%20you%20for%20shopping%20at%20Alice%20Collection" 
           target="_blank" class="btn" style="background: white; color: #25D366; margin-top: 10px;">
            <i class="fab fa-whatsapp"></i> Send WhatsApp Message
        </a>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>