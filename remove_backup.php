<?php
include 'config/db.php';

// Drop the backup table
$drop_backup = "DROP TABLE IF EXISTS products_backup";
if (mysqli_query($conn, $drop_backup)) {
    echo "Products backup table has been removed successfully.";
} else {
    echo "Error removing backup table: " . mysqli_error($conn);
}

mysqli_close($conn);
?> 