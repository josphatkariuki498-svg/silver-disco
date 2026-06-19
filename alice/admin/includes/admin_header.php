<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Allyseasons Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f5f5f5; color: #222; }
        .admin-wrapper { display: flex; }
        .admin-sidebar { width: 260px; background: #000; color: white; min-height: 100vh; position: sticky; top: 0; }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid #333; }
        .sidebar-header h2 { color: #D4AF37; }
        .sidebar-nav { padding: 20px 0; }
        .sidebar-nav a { display: flex; align-items: center; gap: 12px; padding: 12px 20px; color: #aaa; text-decoration: none; transition: all 0.3s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: #D4AF37; color: #000; }
        .admin-main { flex: 1; padding: 20px; }
        .admin-header { background: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .admin-header h3 { color: #333; }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .logout-btn { background: #f44336; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; }
        .admin-container { background: transparent; }
        .page-title { margin-bottom: 20px; font-size: 28px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; display: flex; align-items: center; gap: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-icon { width: 60px; height: 60px; background: rgba(212, 175, 55, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #D4AF37; }
        .stat-number { font-size: 28px; font-weight: bold; color: #333; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .info-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .admin-table { width: 100%; background: white; border-collapse: collapse; border-radius: 8px; overflow: hidden; }
        .admin-table th, .admin-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .admin-table th { background: #f5f5f5; }
        .btn, .btn-sm { display: inline-block; padding: 8px 14px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #D4AF37; color: #000; }
        .btn-secondary { background: #666; color: white; }
        .btn-delete { background: #f44336; color: white; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .table-responsive { overflow-x: auto; }
        @media (max-width: 768px) { .admin-wrapper { flex-direction: column; } .admin-sidebar { width: 100%; min-height: auto; position: relative; } .admin-main { padding: 15px; } }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Allyseasons<span>Collection</span></h2>
        </div>
        <div class="sidebar-nav">
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' || basename($_SERVER['PHP_SELF']) == 'order_detail.php' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> <span>Orders</span></a>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' || basename($_SERVER['PHP_SELF']) == 'product_add.php' ? 'active' : ''; ?>"><i class="fas fa-box"></i> <span>Products</span></a>
            <a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>"><i class="fas fa-tags"></i> <span>Categories</span></a>
            <a href="customers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> <span>Customers</span></a>
            <a href="../index.php" target="_blank"><i class="fas fa-store"></i> <span>View Store</span></a>
        </div>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></h3>
            <div class="user-info">
                <span>Role: <?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'Admin'); ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
