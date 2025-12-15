<?php
require_once 'db.php';

// Add price column to cart table
$sql = "ALTER TABLE cart 
        ADD COLUMN price DECIMAL(10,2) AFTER quantity";

if ($conn->query($sql) === TRUE) {
    echo "Price column added to cart table successfully";
} else {
    echo "Error adding price column: " . $conn->error;
}

$conn->close();
?> 