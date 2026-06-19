<a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=Hello%20Allyseasons%20Collection%2C%20I%20need%20help%20with%20shopping" 
   class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h3>Allyseasons Collection</h3>
                <p>Premium fashion destination in Kimbo, Kiambu. Quality clothing, shoes, accessories, and more.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>Shop</h3>
                <a href="<?php echo BASE_URL; ?>shop.php">All Products</a>
                <a href="<?php echo BASE_URL; ?>category.php?slug=mens-clothing">Men's Collection</a>
                <a href="<?php echo BASE_URL; ?>category.php?slug=womens-clothing">Women's Collection</a>
                <a href="<?php echo BASE_URL; ?>category.php?slug=shoes">Shoes & Sneakers</a>
                <a href="<?php echo BASE_URL; ?>category.php?slug=accessories">Accessories</a>
            </div>
            <div class="footer-col">
                <h3>Support</h3>
                <a href="<?php echo BASE_URL; ?>about.php">About Us</a>
                <a href="<?php echo BASE_URL; ?>contact.php">Contact Us</a>
                <a href="<?php echo BASE_URL; ?>admin/login.php">Admin Login</a>
                <a href="#">Shipping Policy</a>
                <a href="#">Returns & Exchange</a>
                <a href="#">Privacy Policy</a>
            </div>
            <div class="footer-col">
                <h3>Contact</h3>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo SITE_ADDRESS; ?></p>
                <p><i class="fas fa-phone"></i> <?php echo SITE_PHONE; ?></p>
                <p><i class="fas fa-envelope"></i> <?php echo SITE_EMAIL; ?></p>
                <p><i class="fab fa-whatsapp"></i> WhatsApp: <?php echo WHATSAPP_NUMBER; ?></p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> Allyseasons Collection. All rights reserved. | Premium Fashion in Kimbo, Kiambu</p>
            <p><i class="fas fa-money-bill-wave"></i> M-Pesa Accepted | <i class="fas fa-truck"></i> Nationwide Delivery</p>
        </div>
    </div>
</footer>

<script>
$(document).mouseup(function(e) {
    var cart = $("#cartSidebar");
    if (!cart.is(e.target) && cart.has(e.target).length === 0 && !$(e.target).closest('.header-icons a').length) {
        if(cart.hasClass('open')) {
            cart.removeClass('open');
        }
    }
});
</script>

</body>
</html>