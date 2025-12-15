<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get cart items
$user_id = $_SESSION['user_id'];
$cart_query = "SELECT c.*, p.name, p.price, p.image, p.brand 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

$total_amount = 0;
$cart_items = [];

while ($item = $cart_result->fetch_assoc()) {
    $cart_items[] = $item;
    $total_amount += $item['price'] * $item['quantity'];
}

// If cart is empty, prevent rendering the checkout page
if (empty($cart_items)) {
    header("Location: shop.php");
    exit();
}

// Get user details
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Generate unique tid
$tid = uniqid();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Watch Cart</title>
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="esewa/payment-methods.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .btn-checkout { display: none !important; }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h1>Checkout</h1>
        
        <div class="checkout-grid">
            <!-- Order Summary and Shipping Details -->
            <div class="order-summary">
                <h2>Order Summary</h2>
                <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <img src="images/<?php echo htmlspecialchars($item['brand']); ?>/<?php echo htmlspecialchars($item['image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div>
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                            <p>Price: Rs.<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="order-total">
                    <h3>Total Amount: Rs.<?php echo number_format($total_amount, 2); ?></h3>
                </div>

                <div class="shipping-details">
                    <h2>Shipping Details</h2>
                    <form id="shipping-form">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Delivery Address</label>
                            <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="payment-methods">
                <h2>Payment Method</h2>
                
                <!-- eSewa Payment -->
                <div class="payment-method" id="esewa-payment">

                <?php
                $product_code = "EPAYTEST";
                $signature_data = "total_amount=$total_amount,transaction_uuid=$tid,product_code=$product_code";
                
                $hash = hash_hmac('sha256', $signature_data, "8gBm/:&EnhH.1/q", true);
                $hashInBase64 = base64_encode($hash);
                ?>

                <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                <input type="hidden" id="amount" name="amount" value="<?php echo $total_amount; ?>" required>
                <input type="hidden" id="tax_amount" name="tax_amount" value ="0" required>
                <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $total_amount; ?>" required>
                <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="<?php echo $tid ?>" required>
                <input type="hidden" id="product_code" name="product_code" value ="<?php echo $product_code; ?>" required>
                <input type="hidden" id="product_service_charge" name="product_service_charge" value="0" required>
                <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
                <input type="hidden" id="success_url" name="success_url" value="http://localhost/watch-cart/esewa/success.php" required>
                <input type="hidden" id="failure_url" name="failure_url" value="http://localhost/watch-cart/esewa/failure.php" required>
                <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>
                <input type="hidden" id="signature" name="signature" value="<?php echo $hashInBase64; ?>" required>
                
                <?php
                try {
                    // Create initial order for eSewa
                    $create_order = $conn->prepare("INSERT INTO orders (
                        user_id,
                        customer_name,
                        customer_email,
                        customer_phone,
                        customer_address,
                        total_amount,
                        status,
                        payment_method,
                        created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, 'pending', 'esewa', NOW())");
                    
                    $create_order->bind_param("issssd",
                        $user_id,
                        $user['name'],
                        $user['email'],
                        $user['phone'],
                        $user['address'],
                        $total_amount
                    );
                    
                    $create_order->execute();
                    $order_id = $conn->insert_id;
                    
                    // Store eSewa transaction details after order creation
                    $store_transaction = $conn->prepare("INSERT INTO esewa_transactions (
                        transaction_uuid,
                        amount,
                        order_id,
                        user_id,
                        status,
                        created_at
                    ) VALUES (?, ?, ?, ?, 'PENDING', NOW())");
                    
                    $store_transaction->bind_param("sdii", 
                        $tid,
                        $total_amount,
                        $order_id,
                        $user_id
                    );
                    
                    $store_transaction->execute();
                    $transaction_id = $conn->insert_id;
                    
                    // Store order items
                    $store_items = $conn->prepare("INSERT INTO order_items (
                        order_id,
                        product_id,
                        product_name,
                        quantity,
                        price
                    ) VALUES (?, ?, ?, ?, ?)");
                    
                    foreach ($cart_items as $item) {
                        $store_items->bind_param("iisid",
                            $order_id,
                            $item['product_id'],
                            $item['name'],
                            $item['quantity'],
                            $item['price']
                        );
                        $store_items->execute();
                    }
                    
                } catch (Exception $e) {
                    error_log("Error storing eSewa transaction: " . $e->getMessage());
                }
                ?>
                
                <button type="submit" onclick="return validateEsewaPayment()">
                    <img src="images/esewa-logo.png" alt="eSewa">
                    <div>
                        <h3>Pay with eSewa</h3>
                        <p>Pay securely using eSewa</p>
                    </div>
                </button>
            </form>
                </div>

                <!-- Cash on Delivery -->
                <div class="payment-method" id="cod-payment">
                    <button type="button" onclick="processCashOnDelivery()">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>
                            <h3>Cash on Delivery</h3>
                            <p>Pay when you receive your order</p>
                        </div>
                    </button>
                </div>

                
            </div>
        </div>
    </div>

    <script>
    function processCashOnDelivery() {
        // Get shipping form data
        const shippingForm = document.getElementById('shipping-form');
        const formData = new FormData(shippingForm);
        
        // Add payment method and total amount
        formData.append('payment_method', 'cod');
        formData.append('total_amount', '<?php echo $total_amount; ?>');

        // Send order to server
        fetch('process_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to order success page
                window.location.href = `order_success.php?order_id=${data.order_id}`;
            } else {
                // Show error message
                alert(data.message || 'Error processing your order. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error processing your order. Please try again.');
        });
    }

    function validateEsewaPayment() {
        const shippingForm = document.getElementById('shipping-form');
        const formElements = shippingForm.elements;
        
        // Check if all required fields are filled
        for (let element of formElements) {
            if (element.hasAttribute('required') && !element.value.trim()) {
                alert('Please fill in all required shipping details before proceeding with payment.');
                element.focus();
                return false;
            }
        }
        
        return true;
    }
    </script>       
</body>
</html>
