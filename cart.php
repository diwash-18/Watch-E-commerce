<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items with product details
$cart_query = "SELECT c.*, p.name, p.image, p.brand, p.price 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $conn->prepare($cart_query);

if ($stmt === false) {
    // If prepare fails, log the error but don't show it to the user
    error_log("Error preparing cart query: " . $conn->error);
    $items = [];
    $total = 0;
} else {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $cart_items = $stmt->get_result();
        
        // Calculate total
        $total = 0;
        $items = [];
        while ($item = $cart_items->fetch_assoc()) {
            // Use price from products table
            $item['subtotal'] = $item['price'] * $item['quantity'];
            $total += $item['subtotal'];
            $items[] = $item;
        }
    } else {
        error_log("Error executing cart query: " . $stmt->error);
        $items = [];
        $total = 0;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Watch Cart</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="cart-container">
        <h1>Shopping Cart</h1>
        
        <?php if (empty($items)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any watches to your cart yet.</p>
                <a href="shop.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach ($items as $item): ?>
                        <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                            <div class="item-image">
                                <img src="images/<?php echo $item['brand']; ?>/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo $item['name']; ?></h3>
                                <p class="price">Rs. <?php echo number_format($item['price'], 2); ?></p>
                            </div>
                            <div class="quantity-controls">
                                <button class="quantity-btn minus" data-id="<?php echo $item['id']; ?>">-</button>
                                <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>" 
                                       min="1" max="10" data-id="<?php echo $item['id']; ?>">
                                <button class="quantity-btn plus" data-id="<?php echo $item['id']; ?>">+</button>
                            </div>
                            <div class="subtotal">
                                Rs. <?php echo number_format($item['subtotal'], 2); ?>
                            </div>
                            <button class="remove-item" data-id="<?php echo $item['id']; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>Rs. <?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-item total">
                        <span>Total</span>
                        <span>Rs. <?php echo number_format($total, 2); ?></span>
                    </div>
                    <button class="checkout-btn" onclick="window.location.href='checkout.php'">
                        Proceed to Checkout
                    </button>
                    <a href="shop.php" class="continue-shopping">Continue Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Update quantity
        function updateQuantity(id, quantity) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${id}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        // Quantity controls
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                const newValue = this.classList.contains('plus') ? currentValue + 1 : currentValue - 1;
                
                if (newValue >= 1 && newValue <= 10) {
                    input.value = newValue;
                    updateQuantity(this.dataset.id, newValue);
                }
            });
        });

        // Quantity input
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                let value = parseInt(this.value);
                if (value < 1) value = 1;
                if (value > 10) value = 10;
                this.value = value;
                updateQuantity(this.dataset.id, value);
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this item?')) {
                    fetch('remove_from_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `cart_id=${this.dataset.id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
