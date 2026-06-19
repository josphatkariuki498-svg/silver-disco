<?php
require_once __DIR__ . '/../database.php';
$page_title = 'Category';

$slug = $_GET['slug'] ?? '';
$category = null;
$products = [];
$categories = $pdo->query("SELECT * FROM categories ORDER BY display_order")->fetchAll();

if ($slug) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    $category = $stmt->fetch();

    if ($category) {
        $page_title = $category['name'];
        $product_stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY created_at DESC");
        $product_stmt->execute([$category['id']]);
        $products = $product_stmt->fetchAll();
    }
}

include __DIR__ . '/../header.php';
?>

<div class="container" style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 30px;">
        <h1><?php echo $category ? htmlspecialchars($category['name']) : 'Category Not Found'; ?></h1>
        <select id="categoryFilter" onchange="location.href='category.php?slug='+this.value">
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat['slug']); ?>" <?php echo ($category && $category['id'] == $cat['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($category && $products): ?>
    <div class="products-grid">
        <?php foreach ($products as $index => $product): $sizeClass = ['small','medium','large'][$index % 3]; ?>
        <div class="product-card product-card--size-<?php echo $sizeClass; ?>">
            <?php if ($product['old_price']): ?><div class="product-badge">SALE</div><?php endif; ?>
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="product-img" onerror="this.src='https://placehold.co/300x300?text=Product'">
            <div class="product-info">
                <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="product-price">KES <?php echo number_format($product['price']); ?>
                    <?php if ($product['old_price']): ?><span class="old-price">KES <?php echo number_format($product['old_price']); ?></span><?php endif; ?>
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
                <button class="btn btn-primary" style="width:100%; margin-top:10px;" onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">Add to Cart</button>
                <a href="product.php?id=<?php echo $product['id']; ?>" style="display:block; margin-top:8px; font-size:12px; text-align:center;">View Details</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php elseif ($category): ?>
    <p>No products found in this category yet.</p>
    <?php else: ?>
    <p>Please select a valid category.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
