<?php
require_once 'session.php';
include 'db.php';

// Get cart count from database if user is logged in
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $cart_query = "SELECT SUM(quantity) as total FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($cart_query);
    
    if ($stmt === false) {
        // If prepare fails, log the error but don't show it to the user
        error_log("Error preparing cart query: " . $conn->error);
    } else {
        $stmt->bind_param("i", $_SESSION['user_id']);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $cart_count = $row['total'] ?? 0;
        } else {
            error_log("Error executing cart query: " . $stmt->error);
        }
        $stmt->close();
    }
}
?>

<nav class="navbar">
    <!-- Left Section: Search -->
    <div class="nav-left">
        <form action="search.php" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search Watches..." required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- Center Section: Logo -->
    <div class="logo">
        <a href="index.php" class="brand-link">WRIST-WONDERS</a>
    </div>

    <!-- Right Section: Navigation Links -->
    <ul class="nav-links">
        <li><a href="shop.php">Shop</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropbtn"><i class="fas fa-user-circle"></i> Profile</a>
                <div class="dropdown-content">
                    <a href="profile.php"><i class="fas fa-id-card"></i> View Profile</a>
                    <a href="upload_pic.php"><i class="fas fa-image"></i> Upload Picture</a>
                    <a href="update_profile.php"><i class="fas fa-edit"></i> Edit Info</a>
                    <a href="change_password.php"><i class="fas fa-lock"></i> Change Password</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </li>
        <?php else: ?>
            <li><a href="login.php" class="login-btn"><i class="fas fa-sign-in-alt"></i> Login</a></li>
        <?php endif; ?>
        <li><a href="admin/admin_login.php" class="admin-btn"><i class="fas fa-user-shield"></i> Admin</a></li>
    </ul>
</nav>

<!-- Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
