<?php
require_once 'db.php';

// Create contacts table
$sql = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read', 'replied') DEFAULT 'new'
)";

if ($conn->query($sql) === TRUE) {
    echo "Contacts table created successfully";
} else {
    echo "Error creating contacts table: " . $conn->error;
}

$conn->close();
?> 