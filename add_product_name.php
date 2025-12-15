<?php
require_once 'db.php';

// Add product_name column to order_items table
$sql = "ALTER TABLE order_items 
        ADD COLUMN product_name VARCHAR(100) AFTER product_id";

if ($conn->query($sql) === TRUE) {
    echo "Product name column added successfully";
} else {
    echo "Error adding product name column: " . $conn->error;
}

$conn->close();
?> 