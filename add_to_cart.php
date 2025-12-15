<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

try {
    // Get user details
    $user_sql = "SELECT name, email, phone FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Check if product exists
    $product_sql = "SELECT id FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    if (!$stmt->get_result()->fetch_assoc()) {
        throw new Exception('Product not found');
    }

    // Check if item already in cart
    $check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $existing_item = $stmt->get_result()->fetch_assoc();

    if ($existing_item) {
        // Update quantity if item exists
        $update_sql = "UPDATE cart SET quantity = quantity + ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $quantity, $existing_item['id']);
        $stmt->execute();
    } else {
        // Add new item to cart with user details
        $insert_sql = "INSERT INTO cart (user_id, user_name, user_email, user_phone, product_id, quantity) 
                      VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isssii", 
            $user_id,
            $user['name'],
            $user['email'],
            $user['phone'],
            $product_id,
            $quantity
        );
        $stmt->execute();
    }

    echo json_encode([
        'success' => true,
        'message' => 'Item added to cart successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error adding item to cart: ' . $e->getMessage()
    ]);
}

$conn->close();
?>
