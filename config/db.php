<?php
// Database configuration
$host = 'localhost';
$username = 'root';  // default XAMPP username
$password = '';      // default XAMPP password is empty
$database = 'watch_cart';  // your database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?> 