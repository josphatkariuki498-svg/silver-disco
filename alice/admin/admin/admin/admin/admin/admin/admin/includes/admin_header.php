<?php
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
        }
        .admin-wrapper {
            display: flex;
        }
        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: #000;
            color: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #333;
        }
        .sidebar-header h2 {
            color: #D4AF37;
        }
        .sidebar-header span {
            color: white;
        }
        .sidebar-nav {
            padding: 20px 0;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #aaa;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: #D4AF37;
            color: #000;
        }
        .sidebar-nav a i {
            width: 20px;
        }
        /* Main Content */
        .admin-main {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 260px);
        }
        .admin-header {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .admin-header h3 {
            color: #333;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logout-btn {
            background: #f44336;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #D4AF37;
        }
        .stat-info h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        /* Tables */
        .admin-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border-collapse: collapse;
        }
        .admin-table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .btn-sm {
            padding: 5px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
            margin: 0 3px;
        }
        .btn-edit {
            background: #2196F3;
            color: white;
        }
        .btn-delete {
            background: #f44336;
            color: white;
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-pending { background: #ff9800; color: white; }
        .status-confirmed { background: #2196F3; color: white; }
        .status-shipped { background: #9c27b0; color: white; }
        .status-delivered { background: #4caf50; color: white; }
        .status-cancelled { background: #f44336; color: white; }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn-primary {
            background: #D4AF37;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary {
            background: #666;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .admin-form .form-group {
            margin-bottom: 15px;
        }
        .admin-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .admin-form input, .admin-form select, .admin-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .full-width {
            grid-column: span 2;
        }
        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-tab {
            padding: 8px 16px;
            background: white;
            border-radius: 20px;
            text-decoration: none;
            color: #666;
        }
        .filter-tab.active {
            background: #D4AF37;
            color: #000;
        }
        .order-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }
        .info-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .info-card h3 {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #D4AF37;
        }
        .info-card p {
            margin-bottom: 8px;
        }
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 70px;
            }
            .admin-sidebar span {
                display: none;
            }
            .admin-main {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Allyseasons<span>Collection</span></h2>
        </div>
        <div class="sidebar-nav">
            <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i> <span>Dashboard</span>
            </a>
            <a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' || basename($_SERVER['PHP_SELF']) == 'order_detail.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> <span>Orders</span>
            </a>
            <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' || basename($_SERVER['PHP_SELF']) == 'product_add.php' || basename($_SERVER['PHP_SELF']) == 'product_edit.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> <span>Products</span>
            </a>
            <a href="categories.php">
                <i class="fas fa-tags"></i> <span>Categories</span>
            </a>
            <a href="customers.php">
                <i class="fas fa-users"></i> <span>Customers</span>
            </a>
            <a href="../index.php" target="_blank">
                <i class="fas fa-store"></i> <span>View Store</span>
            </a>
        </div>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h3>Welcome, <?php echo $_SESSION['admin_username']; ?></h3>
            <div class="user-info">
                <span>Role: <?php echo ucfirst($_SESSION['admin_role']); ?></span>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>