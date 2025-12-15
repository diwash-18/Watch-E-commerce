<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $_POST['total_amount'];
    
    // Update user's shipping details
    $update_user_sql = "UPDATE users SET 
                       name = ?, 
                       email = ?, 
                       phone = ?, 
                       address = ? 
                       WHERE id = ?";
    $stmt = $conn->prepare($update_user_sql);
    $stmt->bind_param("ssssi", 
        $_POST['full_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $user_id
    );
    $stmt->execute();

    // Start transaction
    $conn->begin_transaction();

    // Create order with customer details
    $order_sql = "INSERT INTO orders (
                    user_id, 
                    customer_name, 
                    customer_email, 
                    customer_phone, 
                    customer_address,
                    total_amount, 
                    status, 
                    payment_method
                ) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("issssds", 
        $user_id,
        $_POST['full_name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $total_amount,
        $payment_method
    );
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Get cart items with product details
    $cart_sql = "SELECT c.*, p.price, p.name as product_name 
                 FROM cart c 
                 JOIN products p ON c.product_id = p.id 
                 WHERE c.user_id = ?";
    $stmt = $conn->prepare($cart_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();

    // Insert order items with product name
    $order_item_sql = "INSERT INTO order_items (
                        order_id, 
                        product_id, 
                        product_name,
                        quantity, 
                        price
                    ) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($order_item_sql);

    while ($item = $cart_result->fetch_assoc()) {
        $stmt->bind_param("iisid", 
            $order_id, 
            $item['product_id'],
            $item['product_name'],
            $item['quantity'], 
            $item['price']
        );
        $stmt->execute();
    }

    // Clear cart
    $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($clear_cart_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'message' => 'Order placed successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Error processing order: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
