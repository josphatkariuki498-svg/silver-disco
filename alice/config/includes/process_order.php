<?php
require_once __DIR__ . '/../database.php';

if($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: checkout.php");
    exit;
}

$order_number = $_POST['order_number'] ?? '';
$customer_name = $_POST['customer_name'] ?? '';
$customer_phone = $_POST['customer_phone'] ?? '';
$customer_email = $_POST['customer_email'] ?? '';
$customer_address = $_POST['customer_address'] ?? '';
$city = $_POST['city'] ?? '';
$delivery_fee = $_POST['delivery_fee'] ?? 0;
$total_amount = $_POST['total_amount'] ?? 0;
$payment_method = $_POST['payment_method'] ?? 'mpesa';
$mpesa_code = $_POST['mpesa_code'] ?? '';
$cart_items = json_decode($_POST['cart_items'] ?? '[]', true);

if(empty($order_number) || empty($customer_name) || empty($customer_phone) || empty($customer_address) || empty($cart_items)) {
    header("Location: checkout.php?error=missing_fields");
    exit;
}

try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("INSERT INTO orders (order_number, customer_name, customer_phone, customer_email, customer_address, city, delivery_fee, total_amount, payment_method, mpesa_code, order_status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')");
    
    $stmt->execute([
        $order_number, $customer_name, $customer_phone, $customer_email, 
        $customer_address, $city, $delivery_fee, $total_amount, 
        $payment_method, $mpesa_code
    ]);
    
    $order_id = $pdo->lastInsertId();
    
    $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
    
    foreach($cart_items as $item) {
        $item_stmt->execute([
            $order_id,
            $item['id'],
            $item['name'],
            $item['quantity'],
            $item['price']
        ]);
    }
    
    $pdo->commit();
    
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="3;url=order_confirmation.php?order=<?php echo $order_number; ?>">
        <title>Order Placed - Allyseasons Collection</title>
        <style>
            body { font-family: 'Poppins', sans-serif; text-align: center; padding: 50px; }
            .success { color: #4CAF50; font-size: 50px; }
        </style>
    </head>
    <body>
        <div class="success">✓</div>
        <h2>Order Placed Successfully!</h2>
        <p>Order Number: <strong><?php echo $order_number; ?></strong></p>
        <p>We'll contact you shortly to confirm delivery.</p>
        <p>Redirecting to confirmation page...</p>
        <script>
            localStorage.removeItem('aliceCart');
        </script>
    </body>
    </html>
    <?php
    
} catch(Exception $e) {
    $pdo->rollBack();
    echo "Error processing order: " . $e->getMessage();
}
?>