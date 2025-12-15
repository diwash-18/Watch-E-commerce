<?php
require_once 'db.php';

// Update all product prices to be below Rs.40
$sql = "UPDATE products SET 
        price = CASE 
            WHEN price >= 25000 THEN 39.99
            WHEN price >= 20000 THEN 35.99
            WHEN price >= 15000 THEN 29.99
            WHEN price >= 10000 THEN 24.99
            WHEN price >= 8000 THEN 19.99
            WHEN price >= 5000 THEN 14.99
            WHEN price >= 3000 THEN 9.99
            ELSE 4.99
        END";

if ($conn->query($sql) === TRUE) {
    echo "All product prices have been updated successfully to be below Rs.40";
} else {
    echo "Error updating prices: " . $conn->error;
}

$conn->close();
?> 