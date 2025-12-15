<?php
include 'db.php';

// Check if users table exists
$table_check = $conn->query("SHOW TABLES LIKE 'users'");
if ($table_check->num_rows === 0) {
    die("The users table does not exist!");
}

// Get all users
$result = $conn->query("SELECT id, name, email FROM users");
if ($result === false) {
    die("Error querying users: " . $conn->error);
}

echo "<h2>Users in Database:</h2>";
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No users found in the database.";
}

$conn->close();
?> 