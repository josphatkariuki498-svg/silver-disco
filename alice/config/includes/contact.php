<?php
require_once __DIR__ . '/../database.php';
$page_title = 'Contact Us';

$message_sent = false;
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if(empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill all required fields.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        if($stmt->execute([$name, $email, $phone, $message])) {
            $message_sent = true;
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    }
}

include __DIR__ . '/../header.php';
?>

<div class="container" style="padding: 60px 0;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1>Contact Us</h1>
        <div style="width: 80px; height: 3px; background: var(--gold); margin: 15px auto;"></div>
        <p>We'd love to hear from you. Reach out for any inquiries or assistance.</p>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">
        <div>
            <div style="margin-bottom: 30px;">
                <h2><i class="fas fa-store"></i> Visit Our Store</h2>
                <p style="margin-top: 15px; line-height: 1.8;">
                    <strong>Allyseasons Collection</strong><br>
                    <?php echo SITE_ADDRESS; ?><br>
                    Kimbo, Kiambu County, Kenya
                </p>
            </div>
            
            <div style="margin-bottom: 30px;">
                <h2><i class="fas fa-phone-alt"></i> Call Us</h2>
                <p style="margin-top: 15px;">
                    Phone: <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a><br>
                    WhatsApp: <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>"><?php echo WHATSAPP_NUMBER; ?></a>
                </p>
            </div>
            
            <div style="margin-bottom: 30px;">
                <h2><i class="fas fa-envelope"></i> Email Us</h2>
                <p style="margin-top: 15px;">
                    <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
                </p>
            </div>
            
            <div style="margin-bottom: 30px;">
                <h2><i class="fas fa-clock"></i> Business Hours</h2>
                <p style="margin-top: 15px;">
                    Monday - Friday: 9:00 AM - 7:00 PM<br>
                    Saturday: 10:00 AM - 6:00 PM<br>
                    Sunday: 11:00 AM - 5:00 PM
                </p>
            </div>
            
            <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=Hello%20Allyseasons%20Collection%2C%20I%20need%20assistance%20with%20shopping" 
               class="btn btn-primary" target="_blank" style="display: inline-flex; align-items: center; gap: 10px;">
                <i class="fab fa-whatsapp"></i> Chat on WhatsApp
            </a>
        </div>
        
        <div>
            <div style="background: var(--gray-light); padding: 30px; border-radius: 8px;">
                <h2 style="margin-bottom: 20px;">Send us a Message</h2>
                
                <?php if($message_sent): ?>
                    <div style="background: #4CAF50; color: white; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                        Thank you! We'll get back to you soon.
                    </div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div style="background: #f44336; color: white; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;">Name *</label>
                        <input type="text" name="name" required style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;">Email *</label>
                        <input type="email" name="email" required style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;">Phone</label>
                        <input type="tel" name="phone" style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;">Message *</label>
                        <textarea name="message" rows="5" required style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 50px;">
        <h2 style="margin-bottom: 20px;">Find Us</h2>
        <div style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.694024443682!2d36.9187!3d-1.1874!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f3d6a6a6a6a6a%3A0x6a6a6a6a6a6a6a6a!2sKimbo%2C%20Kiambu!5e0!3m2!1sen!2ske!4v1700000000000!5m2!1sen!2ske" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
        <p style="margin-top: 15px; text-align: center;">
            <i class="fas fa-map-pin"></i> Located in Kimbo, Kiambu County - Near Kimbo Market
        </p>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>