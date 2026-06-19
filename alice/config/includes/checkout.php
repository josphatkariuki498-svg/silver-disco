<?php
require_once __DIR__ . '/../database.php';
$page_title = 'Checkout';

function generateOrderNumber() {
    return 'AC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

include __DIR__ . '/../header.php';
?>

<div class="container" style="padding: 40px 0;">
    <h1 style="text-align: center; margin-bottom: 40px;">Checkout</h1>
    
    <div id="checkoutContainer">
        <div id="cartSummary" style="margin-bottom: 30px; background: var(--gray-light); padding: 20px; border-radius: 8px;">
        </div>
        
        <form id="checkoutForm" method="POST" action="process_order.php">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <h3>Delivery Information</h3>
                    <div style="margin-top: 20px;">
                        <div style="margin-bottom: 15px;">
                            <label>Full Name *</label>
                            <input type="text" name="customer_name" id="customer_name" required style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label>Phone Number *</label>
                            <input type="tel" name="customer_phone" id="customer_phone" required style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label>Email</label>
                            <input type="email" name="customer_email" id="customer_email" style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label>Delivery Address *</label>
                            <textarea name="customer_address" id="customer_address" required rows="3" style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label>City/Town</label>
                            <input type="text" name="city" id="city" style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3>Payment Method</h3>
                    <div style="margin-top: 20px;">
                        <div style="margin-bottom: 15px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="radio" name="payment_method" value="mpesa" checked onchange="toggleMpesaCode(true)">
                                <i class="fas fa-money-bill-wave" style="color: var(--gold);"></i>
                                M-Pesa (Pay on delivery or via M-Pesa)
                            </label>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="radio" name="payment_method" value="cash_on_delivery" onchange="toggleMpesaCode(false)">
                                <i class="fas fa-hand-holding-usd"></i>
                                Cash on Delivery
                            </label>
                        </div>
                        
                        <div id="mpesaCodeField" style="margin-top: 20px; display: block;">
                            <label>M-Pesa Transaction Code (if already paid)</label>
                            <input type="text" name="mpesa_code" id="mpesa_code" style="width:100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; margin-top: 5px;" placeholder="Enter M-Pesa code if already paid">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                Pay via M-Pesa Paybill: 123456 | Account: ALICE
                            </p>
                        </div>
                        
                        <div style="margin-top: 30px; padding: 20px; background: var(--black); color: white; border-radius: 8px;">
                            <h4 style="color: var(--gold);">Order Summary</h4>
                            <div style="display: flex; justify-content: space-between; margin: 10px 0;">
                                <span>Subtotal:</span>
                                <span id="subtotal">KES 0</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin: 10px 0;">
                                <span>Delivery Fee:</span>
                                <span id="deliveryFee">KES 200</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin: 10px 0; font-size: 20px; font-weight: bold; border-top: 1px solid #333; padding-top: 10px;">
                                <span>Total:</span>
                                <span id="totalAmount">KES 0</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width:100%; margin-top: 20px;">
                            <i class="fas fa-check"></i> Place Order
                        </button>
                        
                        <button type="button" class="btn btn-outline" style="width:100%; margin-top: 10px;" onclick="sendWhatsAppOrder()">
                            <i class="fab fa-whatsapp"></i> Order via WhatsApp Instead
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function toggleMpesaCode(show) {
    document.getElementById('mpesaCodeField').style.display = show ? 'block' : 'none';
}

function loadCartSummary() {
    let cart = getCart();
    let container = document.getElementById('cartSummary');
    if(cart.length === 0) {
        container.innerHTML = '<p style="text-align:center; padding:20px;">Your cart is empty. <a href="shop.php">Continue Shopping</a></p>';
        document.getElementById('checkoutForm').style.display = 'none';
        return;
    }
    
    let html = '<h3>Your Order</h3><div style="margin-top:15px;">';
    let subtotal = 0;
    cart.forEach(item => {
        let itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        html += `
            <div style="display:flex; justify-content:space-between; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #ddd;">
                <div>
                    <strong>${item.name}</strong> x ${item.quantity}
                </div>
                <div>KES ${itemTotal.toLocaleString()}</div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
    
    let deliveryFee = subtotal > 10000 ? 0 : 200;
    let total = subtotal + deliveryFee;
    
    document.getElementById('subtotal').innerText = 'KES ' + subtotal.toLocaleString();
    document.getElementById('deliveryFee').innerText = 'KES ' + deliveryFee;
    document.getElementById('totalAmount').innerHTML = 'KES ' + total.toLocaleString();
    
    $('<input>').attr({type: 'hidden', name: 'total_amount', value: total}).appendTo('#checkoutForm');
    $('<input>').attr({type: 'hidden', name: 'delivery_fee', value: deliveryFee}).appendTo('#checkoutForm');
    $('<input>').attr({type: 'hidden', name: 'order_number', value: '<?php echo generateOrderNumber(); ?>'}).appendTo('#checkoutForm');
}

function sendWhatsAppOrder() {
    let cart = getCart();
    if(cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }
    
    let name = document.getElementById('customer_name').value;
    let phone = document.getElementById('customer_phone').value;
    let address = document.getElementById('customer_address').value;
    
    if(!name || !phone || !address) {
        alert("Please fill in your name, phone, and address first!");
        return;
    }
    
    let message = `Hello Allyseasons Collection, I'd like to place an order:%0A`;
    message += `Name: ${name}%0APhone: ${phone}%0AAddress: ${address}%0A%0A`;
    message += `ITEMS:%0A`;
    
    let subtotal = 0;
    cart.forEach(item => {
        message += `- ${item.name} x${item.quantity} = KES ${(item.price * item.quantity).toLocaleString()}%0A`;
        subtotal += item.price * item.quantity;
    });
    
    let deliveryFee = subtotal > 10000 ? 0 : 200;
    let total = subtotal + deliveryFee;
    message += `%0ASubtotal: KES ${subtotal.toLocaleString()}%0A`;
    message += `Delivery: KES ${deliveryFee}%0A`;
    message += `TOTAL: KES ${total.toLocaleString()}%0A%0A`;
    message += `Payment: M-Pesa on delivery`;
    
    window.open(`https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=${message}`, '_blank');
}

$(document).ready(function() {
    loadCartSummary();
    
    $('#checkoutForm').on('submit', function(e) {
        let cart = getCart();
        if(cart.length === 0) {
            e.preventDefault();
            alert("Your cart is empty!");
            return false;
        }
        
        $('<input>').attr({type: 'hidden', name: 'cart_items', value: JSON.stringify(cart)}).appendTo('#checkoutForm');
        return true;
    });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>