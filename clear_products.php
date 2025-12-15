<?php
include 'config/db.php';

// First disable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// Backup the current products
$backup_query = "CREATE TABLE IF NOT EXISTS products_backup LIKE products";
mysqli_query($conn, $backup_query);
$backup_data = "INSERT INTO products_backup SELECT * FROM products";
mysqli_query($conn, $backup_data);

// Clear related tables first
$clear_cart = "TRUNCATE TABLE cart";
mysqli_query($conn, $clear_cart);

$clear_order_items = "TRUNCATE TABLE order_items";
mysqli_query($conn, $clear_order_items);

// Now clear the products table
$truncate_query = "TRUNCATE TABLE products";
if (mysqli_query($conn, $truncate_query)) {
    echo "Products table has been cleared successfully.<br>";
    
    // Verify the count
    $count_query = "SELECT COUNT(*) as total FROM products";
    $result = mysqli_query($conn, $count_query);
    $row = mysqli_fetch_assoc($result);
    echo "Current number of products: " . $row['total'] . "<br>";
    
    echo "<br>Please now visit: <a href='reset_products.php'>reset_products.php</a> to add the correct products.";
} else {
    echo "Error clearing products table: " . mysqli_error($conn);
}

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

mysqli_close($conn);
?> 