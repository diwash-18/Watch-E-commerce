<?php
include 'db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to remove items from cart']);
    exit;
}

if (!isset($_POST['cart_id'])) {
    echo json_encode(['success' => false, 'message' => 'Cart item ID is required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = $_POST['cart_id'];

// Remove item from cart
$delete = "DELETE FROM cart WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($delete);
$stmt->bind_param("ii", $cart_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove item from cart']);
}
?> 