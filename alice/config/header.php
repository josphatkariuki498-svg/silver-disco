<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/database.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <meta name="description" content="Premium fashion store in Kimbo, Kiambu. Men's and women's clothing, shoes, handbags, watches and accessories. Shop the latest trends in Kenya with M-Pesa and nationwide delivery.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            line-height: 1.6;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }
        :root {
            --black: #000000;
            --white: #ffffff;
            --gold: #D4AF37;
            --gold-dark: #B8960C;
            --gray-light: #f5f5f5;
            --gray: #e0e0e0;
            --gray-dark: #333333;
        }
        .top-bar {
            background: var(--black);
            color: var(--white);
            padding: 8px 0;
            font-size: 13px;
            text-align: center;
        }
        .main-header {
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--gray);
            background: var(--white);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .logo h1 {
            font-size: 28px;
            letter-spacing: 2px;
        }
        .logo span {
            color: var(--gold);
        }
        .nav-menu {
            display: flex;
            gap: 30px;
            list-style: none;
        }
        .nav-menu a {
            text-decoration: none;
            color: var(--black);
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-menu a:hover, .nav-menu a.active {
            color: var(--gold);
        }
        .header-icons {
            display: flex;
            gap: 20px;
            font-size: 20px;
        }
        .header-icons a {
            color: var(--black);
            text-decoration: none;
        }
        .cart-badge {
            position: relative;
        }
        .cart-count {
            position: absolute;
            top: -10px;
            right: -12px;
            background: var(--gold);
            color: var(--black);
            font-size: 11px;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .mobile-menu-btn {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 380px;
            height: 100%;
            background: var(--white);
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
            z-index: 2000;
            transition: right 0.3s ease;
            padding: 20px;
            overflow-y: auto;
        }
        .cart-sidebar.open {
            right: 0;
        }
        .cart-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid var(--gold);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray);
        }
        .cart-item-img {
            width: 70px;
            height: 70px;
            background: var(--gray-light);
            object-fit: cover;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-primary {
            background: var(--gold);
            color: var(--black);
        }
        .btn-primary:hover {
            background: var(--gold-dark);
        }
        .btn-outline {
            border: 2px solid var(--gold);
            background: transparent;
            color: var(--black);
        }
        .btn-outline:hover {
            background: var(--gold);
            color: var(--black);
        }
        .btn-black {
            background: var(--black);
            color: var(--white);
        }
        footer {
            background: #0a0a0a;
            color: #aaa;
            padding: 50px 5% 20px;
            margin-top: 60px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-col h3 {
            color: var(--gold);
            margin-bottom: 20px;
            font-size: 18px;
        }
        .footer-col a {
            color: #aaa;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
        }
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        .social-links a {
            font-size: 20px;
        }
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #222;
        }
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 80px;
                left: 0;
                width: 100%;
                background: var(--white);
                flex-direction: column;
                padding: 20px;
                text-align: center;
                box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            }
            .nav-menu.active {
                display: flex;
            }
            .mobile-menu-btn {
                display: block;
            }
            .cart-sidebar {
                width: 100%;
                right: -100%;
            }
            .main-header {
                padding: 15px;
            }
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .gold-text {
            color: var(--gold);
        }
        .section-title {
            font-size: 32px;
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        .section-title:after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: var(--gold);
            margin: 10px auto 0;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 30px;
        }
        .product-card {
            background: var(--white);
            border: 1px solid var(--gray);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        .product-card--size-small {
            transform: scale(0.95);
            min-height: 420px;
        }
        .product-card--size-medium {
            min-height: 450px;
        }
        .product-card--size-large {
            transform: scale(1.03);
            min-height: 480px;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .product-card--size-small .product-img {
            height: 240px;
        }
        .product-card--size-large .product-img {
            height: 340px;
        }
        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--gold);
            color: var(--black);
            padding: 4px 10px;
            font-size: 12px;
            font-weight: bold;
            z-index: 2;
        }
        .product-img,
        .product-detail-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            background: var(--gray-light);
            display: block;
        }

        .product-detail-image {
            height: 450px;
            border-radius: 8px;
        }
        .product-info {
            padding: 15px;
        }
        .product-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .product-price {
            color: var(--gold);
            font-weight: bold;
            font-size: 18px;
        }
        .product-options {
            margin: 10px 0 12px;
            font-size: 13px;
            color: #666;
        }
        .product-size-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 6px;
        }
        .product-size-pill {
            padding: 3px 8px;
            border: 1px solid var(--gray);
            border-radius: 999px;
            font-size: 12px;
            background: #fafafa;
        }
        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 14px;
            margin-left: 8px;
        }
        .option-group {
            margin-bottom: 15px;
        }
        .option-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .option-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--gray);
            border-radius: 6px;
            background: var(--white);
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1445205170230-053b83016050?w=1920');
            background-size: cover;
            background-position: center;
            height: 550px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        .hero-content h2 {
            font-size: 48px;
            margin-bottom: 15px;
        }
        @media (max-width: 768px) {
            .hero {
                height: 400px;
            }
            .hero-content h2 {
                font-size: 32px;
            }
        }
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 100;
            transition: transform 0.3s;
        }
        .whatsapp-float:hover {
            transform: scale(1.1);
            color: white;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="top-bar">
    <div class="container">
        <i class="fas fa-map-marker-alt"></i> <?php echo SITE_ADDRESS; ?> | 
        <i class="fas fa-phone"></i> <?php echo SITE_PHONE; ?> | 
        <i class="fas fa-shipping-fast"></i> Nationwide Delivery
    </div>
</div>

<div class="main-header">
    <div class="logo">
        <h1>Allyseasons<span>Collection</span></h1>
    </div>
    <div class="mobile-menu-btn" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </div>
    <ul class="nav-menu" id="navMenu">
        <li><a href="<?php echo BASE_URL; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
        <li><a href="<?php echo BASE_URL; ?>shop.php">Shop</a></li>
        <li><a href="<?php echo BASE_URL; ?>category.php?slug=mens-clothing">Men</a></li>
        <li><a href="<?php echo BASE_URL; ?>category.php?slug=womens-clothing">Women</a></li>
        <li><a href="<?php echo BASE_URL; ?>category.php?slug=shoes">Shoes</a></li>
        <li><a href="<?php echo BASE_URL; ?>category.php?slug=accessories">Accessories</a></li>
        <li><a href="<?php echo BASE_URL; ?>about.php">About</a></li>
        <li><a href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
    </ul>
    <div class="header-icons">
        <a href="#" onclick="openCart(); return false;"><i class="fas fa-shopping-bag"></i><span class="cart-count" id="cartCount">0</span></a>
        <a href="<?php echo BASE_URL; ?>contact.php"><i class="fas fa-headset"></i></a>
    </div>
</div>

<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h3>Your Cart <span class="gold-text">(<span id="cartItemCount">0</span> items)</span></h3>
        <i class="fas fa-times" style="cursor:pointer; font-size:24px;" onclick="closeCart()"></i>
    </div>
    <div id="cartItemsContainer"></div>
    <div class="cart-total" style="margin-top:20px; border-top:2px solid var(--gold); padding-top:15px;">
        <strong>Total: KES <span id="cartTotal">0</span></strong>
    </div>
    <a href="<?php echo BASE_URL; ?>checkout.php" class="btn btn-primary" style="width:100%; margin-top:15px; text-align:center;">Checkout</a>
    <a href="#" onclick="sendWhatsAppCart(); return false;" class="btn btn-outline" style="width:100%; margin-top:10px; text-align:center;"><i class="fab fa-whatsapp"></i> Order via WhatsApp</a>
</div>

<script>
function getCart() {
    let cart = localStorage.getItem('aliceCart');
    if(cart) {
        return JSON.parse(cart);
    }
    return [];
}

function saveCart(cart) {
    localStorage.setItem('aliceCart', JSON.stringify(cart));
    updateCartUI();
}

function updateCartUI() {
    let cart = getCart();
    let count = cart.reduce((sum, item) => sum + item.quantity, 0);
    let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('cartCount').innerText = count;
    if(document.getElementById('cartItemCount')) document.getElementById('cartItemCount').innerText = count;
    if(document.getElementById('cartTotal')) document.getElementById('cartTotal').innerText = total.toLocaleString();
    
    let container = document.getElementById('cartItemsContainer');
    if(container) {
        if(cart.length === 0) {
            container.innerHTML = '<p>Your cart is empty.</p>';
        } else {
            container.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <img src="images/${item.image}" class="cart-item-img" onerror="this.src='https://placehold.co/200x200?text=Product'">
                    <div style="flex:1">
                        <strong>${item.name}</strong><br>
                        KES ${item.price.toLocaleString()}<br>
                        <div style="display:flex; gap:10px; margin-top:5px;">
                            <button onclick="updateQuantity(${item.id}, -1)">-</button>
                            <span>${item.quantity}</span>
                            <button onclick="updateQuantity(${item.id}, 1)">+</button>
                            <button onclick="removeFromCart(${item.id})" style="color:red;">Remove</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }
}

function addToCart(id, name, price, image) {
    let cart = getCart();
    let existing = cart.find(item => item.id == id);
    if(existing) {
        existing.quantity++;
    } else {
        cart.push({ id, name, price, image, quantity: 1 });
    }
    saveCart(cart);
    alert(name + " added to cart!");
}

function updateQuantity(id, delta) {
    let cart = getCart();
    let item = cart.find(i => i.id == id);
    if(item) {
        item.quantity += delta;
        if(item.quantity <= 0) {
            cart = cart.filter(i => i.id != id);
        }
        saveCart(cart);
    }
}

function removeFromCart(id) {
    let cart = getCart();
    cart = cart.filter(item => item.id != id);
    saveCart(cart);
}

function openCart() {
    document.getElementById('cartSidebar').classList.add('open');
}

function closeCart() {
    document.getElementById('cartSidebar').classList.remove('open');
}

function toggleMobileMenu() {
    document.getElementById('navMenu').classList.toggle('active');
}

function sendWhatsAppCart() {
    let cart = getCart();
    if(cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }
    let message = "Hello Allyseasons Collection, I'd like to order:%0A";
    cart.forEach(item => {
        message += `- ${item.name} x${item.quantity} = KES ${(item.price * item.quantity).toLocaleString()}%0A`;
    });
    let total = cart.reduce((sum, i) => sum + (i.price * i.quantity), 0);
    message += `%0ATotal: KES ${total.toLocaleString()}%0A%0ADelivery to: (Please provide address)`;
    window.open(`https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=${message}`, '_blank');
}

$(document).ready(function() {
    updateCartUI();
});
</script>