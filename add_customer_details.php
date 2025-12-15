<?php
require_once 'db.php';

// Add customer details columns to orders table
$sql = "ALTER TABLE orders 
        ADD COLUMN customer_name VARCHAR(100) AFTER user_id,
        ADD COLUMN customer_email VARCHAR(100) AFTER customer_name,
        ADD COLUMN customer_phone VARCHAR(20) AFTER customer_email,
        ADD COLUMN customer_address TEXT AFTER customer_phone";

if ($conn->query($sql) === TRUE) {
    echo "Customer details columns added successfully";
} else {
    echo "Error adding customer details columns: " . $conn->error;
}

$conn->close();
?> 