<?php
require_once __DIR__ . '/../database.php';
$page_title = 'Home';

$stmt = $pdo->prepare("SELECT * FROM products WHERE is_featured = 1 LIMIT 8");
$stmt->execute();
$featured = $stmt->fetchAll();

$stmt2 = $pdo->prepare("SELECT * FROM products WHERE is_new = 1 ORDER BY created_at DESC LIMIT 8");
$stmt2->execute();
$newArrivals = $stmt2->fetchAll();

$stmt3 = $pdo->prepare("SELECT * FROM products WHERE old_price IS NOT NULL LIMIT 4");
$stmt3->execute();
$flashSales = $stmt3->fetchAll();

$stmt4 = $pdo->prepare("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY created_at DESC LIMIT 4");
$stmt4->execute();
$testimonials = $stmt4->fetchAll();

include __DIR__ . '/../header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h2>Elegance Redefined</h2>
        <p>Discover the latest fashion trends at Allyseasons Collection - Premium quality, timeless style.</p>
        <a href="shop.php" class="btn btn-primary" style="margin-top: 20px;">Shop Now <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<div class="container" style="margin-top: 60px;">
    <h2 class="section-title">Featured Collection</h2>
    <div class="products-grid">
        <?php foreach($featured as $index => $product): $sizeClass = ['small','medium','large'][$index % 3]; ?>
        <div class="product-card product-card--size-<?php echo $sizeClass; ?>">
            <?php if($product['old_price']): ?><div class="product-badge">SALE</div><?php endif; ?>
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="product-img" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://placehold.co/300x300?text=Product'">
            <div class="product-info">
                <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="product-price">KES <?php echo number_format($product['price']); ?>
                    <?php if($product['old_price']): ?><span class="old-price">KES <?php echo number_format($product['old_price']); ?></span><?php endif; ?>
                </div>
                <?php $options = getProductOptionData($product); ?>
                <div class="product-options">
                    <div>Sizes: <?php echo htmlspecialchars(implode(', ', $options['sizes'])); ?></div>
                    <div class="product-size-pills">
                        <?php foreach(array_slice($options['sizes'], 0, 4) as $size): ?>
                        <span class="product-size-pill"><?php echo htmlspecialchars($size); ?></span>
                        <?php endforeach; ?>
                        <?php if(count($options['sizes']) > 4): ?><span class="product-size-pill">+</span><?php endif; ?>
                    </div>
                </div>
                <button class="btn btn-outline" style="margin-top: 10px; width:100%;" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">Add to Cart</button>
                <a href="product.php?id=<?php echo $product['id']; ?>" style="display:block; margin-top:8px; font-size:12px; text-align:center;">View Details</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container" style="margin-top: 60px;">
    <h2 class="section-title">New Arrivals</h2>
    <div class="products-grid">
        <?php foreach($newArrivals as $index => $product): $sizeClass = ['small','medium','large'][$index % 3]; ?>
        <div class="product-card product-card--size-<?php echo $sizeClass; ?>">
            <div class="product-badge" style="background:black; color:gold;">NEW</div>
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="product-img" onerror="this.src='https://placehold.co/300x300?text=New'">
            <div class="product-info">
                <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="product-price">KES <?php echo number_format($product['price']); ?></div>
                <?php $options = getProductOptionData($product); ?>
                <div class="product-options">
                    <div>Sizes: <?php echo htmlspecialchars(implode(', ', $options['sizes'])); ?></div>
                    <div class="product-size-pills">
                        <?php foreach(array_slice($options['sizes'], 0, 4) as $size): ?>
                        <span class="product-size-pill"><?php echo htmlspecialchars($size); ?></span>
                        <?php endforeach; ?>
                        <?php if(count($options['sizes']) > 4): ?><span class="product-size-pill">+</span><?php endif; ?>
                    </div>
                </div>
                <button class="btn btn-outline" style="margin-top: 10px; width:100%;" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">Add to Cart</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container" style="margin-top: 60px;">
    <h2 class="section-title">Flash Sales <i class="fas fa-bolt gold-text"></i></h2>
    <div class="products-grid">
        <?php foreach($flashSales as $index => $product): $sizeClass = ['small','medium','large'][$index % 3]; ?>
        <div class="product-card product-card--size-<?php echo $sizeClass; ?>">
            <?php if($product['old_price']): ?>
            <div class="product-badge">-<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>% OFF</div>
            <?php endif; ?>
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="product-img" onerror="this.src='https://placehold.co/300x300?text=Flash+Sale'">
            <div class="product-info">
                <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="product-price">KES <?php echo number_format($product['price']); ?>
                    <?php if($product['old_price']): ?><span class="old-price">KES <?php echo number_format($product['old_price']); ?></span><?php endif; ?>
                </div>
                <?php $options = getProductOptionData($product); ?>
                <div class="product-options">
                    <div>Sizes: <?php echo htmlspecialchars(implode(', ', $options['sizes'])); ?></div>
                    <div class="product-size-pills">
                        <?php foreach(array_slice($options['sizes'], 0, 4) as $size): ?>
                        <span class="product-size-pill"><?php echo htmlspecialchars($size); ?></span>
                        <?php endforeach; ?>
                        <?php if(count($options['sizes']) > 4): ?><span class="product-size-pill">+</span><?php endif; ?>
                    </div>
                </div>
                <button class="btn btn-primary" style="margin-top: 10px; width:100%;" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">Buy Now</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container" style="margin-top: 60px;">
    <h2 class="section-title">What Our Customers Say</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap: 30px;">
        <?php foreach($testimonials as $t): ?>
        <div style="background: var(--gray-light); padding: 25px; border-radius: 8px; text-align: center;">
            <i class="fas fa-star gold-text"></i><i class="fas fa-star gold-text"></i><i class="fas fa-star gold-text"></i><i class="fas fa-star gold-text"></i><i class="fas fa-star gold-text"></i>
            <p style="margin: 15px 0; font-style: italic;">"<?php echo htmlspecialchars($t['comment']); ?>"</p>
            <strong>- <?php echo htmlspecialchars($t['customer_name']); ?></strong>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div style="background: var(--black); color: white; margin-top: 60px; padding: 40px 5%; text-align: center;">
    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px;">
        <div><i class="fas fa-money-bill-wave" style="font-size: 30px; color: var(--gold);"></i><br>M-Pesa Accepted</div>
        <div><i class="fas fa-truck" style="font-size: 30px; color: var(--gold);"></i><br>Delivery Nationwide</div>
        <div><i class="fab fa-whatsapp" style="font-size: 30px; color: #25D366;"></i><br>Order on WhatsApp</div>
        <div><i class="fas fa-store" style="font-size: 30px; color: var(--gold);"></i><br>Kimbo, Kiambu</div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>