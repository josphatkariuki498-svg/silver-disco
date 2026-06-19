<?php
require_once __DIR__ . '/../database.php';
$page_title = 'About Us';

include __DIR__ . '/../header.php';
?>

<div class="container" style="padding: 60px 0;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h1 style="font-size: 48px; margin-bottom: 20px;">About Allyseasons Collection</h1>
        <div style="width: 80px; height: 3px; background: var(--gold); margin: 0 auto 30px;"></div>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center;">
        <div>
            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600" alt="Fashion Store" style="width:100%; border-radius: 8px;">
        </div>
        <div>
            <h2 style="margin-bottom: 20px;">Premium Fashion Destination in Kiambu</h2>
            <p style="margin-bottom: 20px; line-height: 1.8;">Allyseasons Collection was founded with a vision to bring premium, high-quality fashion to Kiambu County and all of Kenya. Located in the heart of Kimbo, we've become a trusted name for fashion-conscious individuals seeking elegance and style.</p>
            <p style="margin-bottom: 20px; line-height: 1.8;">We curate the finest collections of men's and women's clothing, shoes, handbags, watches, and accessories from around the world, ensuring our customers get the best value for their money.</p>
            <p style="line-height: 1.8;">With convenient M-Pesa payments, WhatsApp ordering, and nationwide delivery, shopping at Allyseasons Collection is simple, fast, and secure.</p>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 60px;">
        <div style="text-align: center; padding: 30px; background: var(--gray-light); border-radius: 8px;">
            <i class="fas fa-star" style="font-size: 40px; color: var(--gold); margin-bottom: 15px;"></i>
            <h3>Quality First</h3>
            <p>We source only the highest quality products for our customers.</p>
        </div>
        <div style="text-align: center; padding: 30px; background: var(--gray-light); border-radius: 8px;">
            <i class="fas fa-smile" style="font-size: 40px; color: var(--gold); margin-bottom: 15px;"></i>
            <h3>Customer Satisfaction</h3>
            <p>Your happiness is our priority. We ensure a seamless shopping experience.</p>
        </div>
        <div style="text-align: center; padding: 30px; background: var(--gray-light); border-radius: 8px;">
            <i class="fas fa-truck" style="font-size: 40px; color: var(--gold); margin-bottom: 15px;"></i>
            <h3>Fast Delivery</h3>
            <p>Nationwide delivery with real-time tracking and support.</p>
        </div>
    </div>
    
    <div style="margin-top: 60px; background: var(--black); color: white; padding: 40px; border-radius: 8px; text-align: center;">
        <h2 style="color: var(--gold); margin-bottom: 20px;">Visit Our Store</h2>
        <p style="margin-bottom: 10px;"><i class="fas fa-map-marker-alt"></i> <?php echo SITE_ADDRESS; ?></p>
        <p><i class="fas fa-clock"></i> Monday - Saturday: 9:00 AM - 7:00 PM | Sunday: 11:00 AM - 5:00 PM</p>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>