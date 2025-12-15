<?php
require_once 'db.php';

// Get table structure
$result = $conn->query("DESCRIBE products");
if ($result) {
    echo "Products table structure:\n";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error: " . $conn->error;
}

// Get sample product data
$result = $conn->query("SELECT * FROM products LIMIT 1");
if ($result) {
    echo "\nSample product data:\n";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?> 