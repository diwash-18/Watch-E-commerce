<?php
session_start();
include 'db.php'; // Replace with your actual DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $msg = "<p class='error'>New passwords do not match!</p>";
    } else {
        // Fetch current hashed password from DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($db_password);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (password_verify($current_password, $db_password)) {
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $update_stmt->bind_param("si", $new_hashed, $user_id);
            if ($update_stmt->execute()) {
                $msg = "<p class='success'>Password updated successfully!</p>";
            } else {
                $msg = "<p class='error'>Failed to update password.</p>";
            }
        } else {
            $msg = "<p class='error'>Current password is incorrect!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
  <link rel="stylesheet" href="css/change_password.css">
 
</head>
<body>

  <div class="password-form">
    <h2>Change Password</h2>
    <?php echo $msg; ?>
    <form method="POST" action="change_password.php">
      <input type="password" name="current_password" placeholder="Current Password" required>
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
      <button type="submit">Change Password</button>
    </form>
  </div>

</body>
</html>
