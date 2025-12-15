<?php
require_once 'db.php';

// Add payment_method column to orders table
$sql = "ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'cod' AFTER status";

if ($conn->query($sql) === TRUE) {
    echo "Payment method column added successfully";
} else {
    echo "Error adding payment method column: " . $conn->error;
}

$conn->close();
?> 