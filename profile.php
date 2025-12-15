<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug information
error_log("Profile page accessed. Session ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("No user_id in session, redirecting to login");
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "watch_cart");

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
error_log("Fetching data for user ID: " . $user_id);

// Get user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    error_log("No user found with ID: " . $user_id);
    session_destroy();
    header("Location: login.php?error=invalid_session");
    exit();
}

$user = $result->fetch_assoc();
error_log("User data retrieved successfully");

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    
    <script>
        // Prevent page refresh
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_RELOAD) {
            window.location.href = 'profile.php';
        }
    </script>
</head>
<body>
    <div class="profile-container">
        <h2>Welcome (<?php echo htmlspecialchars($user['name']); ?>)</h2>
        <div class="profile-pic">
            <?php 
            $profile_pic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png';
            ?>
            <img src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" onerror="this.src='uploads/default.png'">
            <form action="upload_pic.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_pic" accept="image/*" required>
                <button type="submit">Upload New Picture</button>
            </form>
        </div>
        <div class="info">
            <h3>Your Information</h3>
            <form action="update_profile.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                        <option value="Male" <?php echo ($user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($user['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">
                </div>
                <button type="submit">Update Profile</button>
            </form>
        </div>
        <div class="change-pass">
            <a href="change_password.php">Change Password</a>
        </div>
    </div>
</body>
</html>