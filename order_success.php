<?php
session_start();
require_once 'db.php';

if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Get order details with user information
$order_sql = "SELECT o.*, u.name as customer_name, u.email, u.phone, u.address 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: index.php");
    exit();
}

// Get order items
$items_sql = "SELECT oi.*, p.name, p.image, p.brand 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = ?";
$stmt = $conn->prepare($items_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Watch Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        .success-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            text-align: center;
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .success-message {
            text-align: center;
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .order-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .order-details h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .order-info p {
            margin: 0.5rem 0;
        }

        .order-info strong {
            color: #2c3e50;
        }

        .order-items {
            margin-top: 2rem;
        }

        .order-items h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            gap: 1.5rem;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 1rem;
        }

        .order-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .order-item h4 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .order-item p {
            color: #6c757d;
            margin: 0.3rem 0;
        }

        .btn-continue {
            display: block;
            width: 100%;
            padding: 1rem;
            background: #3498db;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .success-container {
                margin: 1rem;
                padding: 1rem;
            }

            .order-info {
                grid-template-columns: 1fr;
            }

            .order-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .order-item img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Order Placed Successfully!</h1>
        <p class="success-message">Thank you for your purchase. Your order has been received and is being processed.</p>
        
        <div class="order-details">
            <h2>Order Details</h2>
            <div class="order-info">
                <div>
                    <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                    <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                    <p><strong>Total Amount:</strong> Rs.<?php echo number_format($order['total_amount'], 2); ?></p>
                </div>
                <div>
                    <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                </div>
            </div>
            
            <div class="order-items">
                <h3>Order Items</h3>
                <?php while ($item = $items_result->fetch_assoc()): ?>
                    <div class="order-item">
                        <img src="images/<?php echo htmlspecialchars($item['brand']); ?>/<?php echo htmlspecialchars($item['image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div>
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                            <p>Price: Rs.<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <a href="index.php" class="btn-continue">Continue Shopping</a>
    </div>
</body>
</html> 