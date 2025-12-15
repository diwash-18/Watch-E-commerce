<?php
require_once 'db.php';

// Add user details columns to cart table
$sql = "ALTER TABLE cart 
        ADD COLUMN user_name VARCHAR(100) AFTER user_id,
        ADD COLUMN user_email VARCHAR(100) AFTER user_name,
        ADD COLUMN user_phone VARCHAR(20) AFTER user_email";

if ($conn->query($sql) === TRUE) {
    echo "User details columns added to cart table successfully";
} else {
    echo "Error adding user details columns: " . $conn->error;
}

$conn->close();
?> 