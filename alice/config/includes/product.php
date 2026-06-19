<?php
require_once __DIR__ . '/../database.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug 
                       FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if(!$product) {
    header("Location: shop.php");
    exit;
}

$page_title = $product['name'];

$related = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4");
$related->execute([$product['category_id'], $product_id]);
$related_products = $related->fetchAll();

include __DIR__ . '/../header.php';
?>

<div class="container" style="padding: 40px 0;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">
        <div>
            <img src="images/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 class="product-detail-image"
                 onerror="this.src='https://placehold.co/600x600?text=Product'">
        </div>
        
        <div>
            <h1 style="font-size: 32px; margin-bottom: 10px;"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p style="color: #666; margin-bottom: 15px;">
                <a href="category.php?slug=<?php echo $product['category_slug']; ?>" style="color: var(--gold);">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </a>
            </p>
            <div style="margin-bottom: 20px;">
                <span class="product-price" style="font-size: 28px;">KES <?php echo number_format($product['price']); ?></span>
                <?php if($product['old_price']): ?>
                    <span class="old-price" style="font-size: 20px;">KES <?php echo number_format($product['old_price']); ?></span>
                    <span class="product-badge" style="position: relative; display: inline-block; margin-left: 10px;">
                        -<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>%
                    </span>
                <?php endif; ?>
            </div>
            
            <p style="margin-bottom: 30px; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <?php $options = getProductOptionData($product); ?>
            <div class="option-group">
                <label>Size</label>
                <select id="selected_size" onchange="updateStockInfo()">
                    <?php foreach ($options['sizes'] as $size): $stockCount = $options['stock'][$size] ?? 0; ?>
                    <option value="<?php echo htmlspecialchars($size); ?>" data-stock="<?php echo $stockCount; ?>" <?php echo $stockCount <= 0 ? 'disabled' : ''; ?>>
                        <?php echo htmlspecialchars($size); ?><?php echo $stockCount > 0 ? ' - ' . $stockCount . ' in stock' : ' - Out of stock'; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="option-group">
                <label>Color</label>
                <select id="selected_color">
                    <?php foreach ($options['colors'] as $color): ?>
                    <option value="<?php echo htmlspecialchars($color); ?>"><?php echo htmlspecialchars($color); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label>Quantity:</label>
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                    <button onclick="decrementQty()" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; cursor: pointer;">-</button>
                    <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width: 60px; height: 40px; text-align: center; border: 1px solid #ddd;">
                    <button onclick="incrementQty()" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; cursor: pointer;">+</button>
                    <span id="stockInfo" style="margin-left: 15px;">Stock: <?php echo $product['stock']; ?> available</span>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button class="btn btn-primary" onclick="addToCartWithQty()" style="flex:1;">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
                <button class="btn btn-outline" onclick="buyNow()" style="flex:1;">
                    <i class="fab fa-whatsapp"></i> Buy on WhatsApp
                </button>
            </div>
            
            <div style="margin-top: 40px; padding: 20px; background: var(--gray-light); border-radius: 8px;">
                <h3><i class="fas fa-truck"></i> Delivery Information</h3>
                <p>✓ Delivery throughout Kenya - 2-5 business days</p>
                <p>✓ Free delivery on orders over KES 10,000</p>
                <p>✓ M-Pesa payment accepted - Pay on delivery or via M-Pesa</p>
                <p>✓ Store pickup available at Kimbo, Kiambu</p>
            </div>
        </div>
    </div>
    
    <?php if(count($related_products) > 0): ?>
    <div style="margin-top: 70px;">
        <h2 class="section-title">You May Also Like</h2>
        <div class="products-grid">
            <?php foreach($related_products as $rel): ?>
            <div class="product-card">
                <img src="images/<?php echo htmlspecialchars($rel['image']); ?>" class="product-img" onerror="this.src='https://placehold.co/300x300?text=Product'">
                <div class="product-info">
                    <div class="product-title"><?php echo htmlspecialchars($rel['name']); ?></div>
                    <div class="product-price">KES <?php echo number_format($rel['price']); ?></div>
                    <button class="btn btn-outline" style="width:100%;" onclick="addToCart(<?php echo $rel['id']; ?>, '<?php echo addslashes($rel['name']); ?>', <?php echo $rel['price']; ?>, '<?php echo $rel['image']; ?>')">Add to Cart</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function updateStockInfo() {
    let sizeSelect = document.getElementById('selected_size');
    let selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
    let stock = parseInt(selectedOption.getAttribute('data-stock') || 0);
    let stockInfo = document.getElementById('stockInfo');
    let qty = document.getElementById('quantity');

    if(stockInfo) {
        stockInfo.textContent = stock > 0 ? `Stock: ${stock} available` : 'Stock: Out of stock';
    }

    if(qty) {
        qty.max = stock > 0 ? stock : 0;
        if(parseInt(qty.value) > qty.max) {
            qty.value = qty.max > 0 ? qty.max : 0;
        }
    }
}
function decrementQty() {
    let qty = document.getElementById('quantity');
    let val = parseInt(qty.value);
    if(val > 1) qty.value = val - 1;
}
function incrementQty() {
    let qty = document.getElementById('quantity');
    let val = parseInt(qty.value);
    let max = parseInt(qty.max || <?php echo $product['stock']; ?>);
    if(val < max) qty.value = val + 1;
}
function addToCartWithQty() {
    let qty = parseInt(document.getElementById('quantity').value);
    let size = document.getElementById('selected_size').value;
    let color = document.getElementById('selected_color').value;
    let cart = getCart();
    let existing = cart.find(item => item.id == <?php echo $product['id']; ?> && item.size == size && item.color == color);
    if(existing) {
        existing.quantity += qty;
    } else {
        cart.push({ 
            id: <?php echo $product['id']; ?>, 
            name: '<?php echo addslashes($product['name']); ?>', 
            price: <?php echo $product['price']; ?>, 
            image: '<?php echo $product['image']; ?>', 
            size: size,
            color: color,
            quantity: qty 
        });
    }
    saveCart(cart);
    alert('Added to cart!');
}
function buyNow() {
    let qty = parseInt(document.getElementById('quantity').value);
    let size = document.getElementById('selected_size').value;
    let color = document.getElementById('selected_color').value;
    let message = `Hello Allyseasons Collection, I'd like to order:%0A`;
    message += `- <?php echo addslashes($product['name']); ?> (${size}, ${color}) x${qty} = KES <?php echo $product['price'] * 1; ?>%0A`;
    message += `%0ATotal: KES <?php echo $product['price'] * 1; ?>%0A`;
    message += `%0ADelivery to: (Please provide address)`;
    window.open(`https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=${message}`, '_blank');
}
window.onload = updateStockInfo;
</script>

<?php include __DIR__ . '/footer.php'; ?>