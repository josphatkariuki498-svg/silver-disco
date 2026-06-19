<?php
require_once __DIR__ . '/../database.php';
$page_title = 'Order Confirmation';

$order_number = $_GET['order'] ?? '';
$items = [];

if($order_number) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->execute([$order_number]);
    $order = $stmt->fetch();

    if($order && isset($order['id'])) {
        $items_stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $items_stmt->execute([$order['id']]);
        $items = $items_stmt->fetchAll();
    }
}

include __DIR__ . '/../header.php';
?>

<div class="container" style="padding: 60px 0; text-align: center;">
    <?php if(isset($order) && $order): ?>
        <div style="background: #4CAF50; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
            <i class="fas fa-check" style="font-size: 40px;"></i>
        </div>
        <h1 style="margin-bottom: 20px;">Thank You for Your Order!</h1>
        <p style="margin-bottom: 10px;">Order Number: <strong><?php echo $order['order_number']; ?></strong></p>
        <p style="margin-bottom: 30px;">We've received your order and will contact you within 24 hours.</p>
        
        <div style="max-width: 500px; margin: 0 auto; background: var(--gray-light); padding: 25px; border-radius: 8px; text-align: left;">
            <h3>Order Summary</h3>
            <?php foreach($items as $item): ?>
                <div style="display: flex; justify-content: space-between; margin: 10px 0;">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> x<?php echo $item['quantity']; ?></span>
                    <span>KES <?php echo number_format($item['price'] * $item['quantity']); ?></span>
                </div>
            <?php endforeach; ?>
            <div style="border-top: 1px solid #ddd; margin: 15px 0; padding-top: 15px;">
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px; margin-top: 10px;">
                    <span>Total:</span>
                    <span>KES <?php echo number_format($order['total_amount']); ?></span>
                </div>
            </div>
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd;">
                <p><strong>Delivery Address:</strong><br><?php echo htmlspecialchars($order['customer_address']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                <p><strong>Payment:</strong> <?php echo $order['payment_method'] == 'mpesa' ? 'M-Pesa' : 'Cash on Delivery'; ?></p>
            </div>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="shop.php" class="btn btn-primary">Continue Shopping</a>
            <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=Hi%20Alice%20Collection%2C%20I%20have%20an%20order%20question%3A%20<?php echo $order['order_number']; ?>" class="btn btn-outline" style="margin-left: 10px;">
                <i class="fab fa-whatsapp"></i> Track Order on WhatsApp
            </a>
        </div>
    <?php else: ?>
        <h2>Order Not Found</h2>
        <p>Please check your order number or contact us for assistance.</p>
        <a href="shop.php" class="btn btn-primary">Back to Shop</a>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>