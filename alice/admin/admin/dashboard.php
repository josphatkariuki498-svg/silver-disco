<?php
session_start();
require_once '../config/database.php';

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Get statistics
// Total orders
$stmt = $pdo->query("SELECT COUNT(*) as total, SUM(total_amount) as revenue FROM orders WHERE DATE(created_at) = CURDATE()");
$today = $stmt->fetch();

$stmt = $pdo->query("SELECT COUNT(*) as total, SUM(total_amount) as revenue FROM orders WHERE MONTH(created_at) = MONTH(CURDATE())");
$month = $stmt->fetch();

$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders WHERE order_status = 'pending'");
$pending_orders = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
$total_products = $stmt->fetchColumn();

// Recent orders
$recent_orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10")->fetchAll();

// Low stock products
$low_stock = $pdo->query("SELECT * FROM products WHERE stock < 5 ORDER BY stock ASC LIMIT 5")->fetchAll();

// Sales chart data (last 7 days)
$chart_data = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as total 
    FROM orders 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
")->fetchAll();

$dates = [];
$sales = [];
foreach ($chart_data as $row) {
    $dates[] = date('M d', strtotime($row['date']));
    $sales[] = $row['total'];
}

include 'includes/admin_header.php';
?>

<div class="admin-container">
    <h1 class="page-title">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-info">
                <h3>Today's Orders</h3>
                <div class="stat-number"><?php echo $today['total'] ?? 0; ?></div>
                <small>KES <?php echo number_format($today['revenue'] ?? 0); ?></small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar"></i></div>
            <div class="stat-info">
                <h3>This Month</h3>
                <div class="stat-number"><?php echo $month['total'] ?? 0; ?></div>
                <small>KES <?php echo number_format($month['revenue'] ?? 0); ?></small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h3>Pending Orders</h3>
                <div class="stat-number"><?php echo $pending_orders; ?></div>
                <small>Need attention</small>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-box"></i></div>
            <div class="stat-info">
                <h3>Total Products</h3>
                <div class="stat-number"><?php echo $total_products; ?></div>
                <small>In catalog</small>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    <?php if(count($low_stock) > 0): ?>
    <div class="alert alert-warning">
        <h4><i class="fas fa-exclamation-triangle"></i> Low Stock Alert</h4>
        <p>The following products have low inventory:</p>
        <ul>
            <?php foreach($low_stock as $product): ?>
            <li><?php echo htmlspecialchars($product['name']); ?> - Only <?php echo $product['stock']; ?> left</li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <!-- Sales Chart -->
    <div class="chart-container">
        <h3>Last 7 Days Sales</h3>
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>
    
    <!-- Recent Orders -->
    <div class="recent-orders">
        <h3>Recent Orders</h3>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_number']; ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td>KES <?php echo number_format($order['total_amount']); ?></td>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Sales (KES)',
                data: <?php echo json_encode($sales); ?>,
                borderColor: '#D4AF37',
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>

<?php include 'includes/admin_footer.php'; ?>