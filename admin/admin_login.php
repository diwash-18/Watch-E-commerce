<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // For demonstration, static login
  if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['admin_logged_in'] = true;
    header("Location: dashboard.php");
    exit();
  } else {
    $error = "Invalid credentials";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Watch Cart</title>
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="login-page">
  <div class="login-wrapper">
    <div class="login-container">
      <div class="login-header">
        <i class="fas fa-user-shield login-icon"></i>
        <h2>Admin Dashboard</h2>
        <p class="login-subtitle">Welcome back! Please login to your account.</p>
      </div>
      
      <?php if(isset($error)): ?>
        <div class="error-message">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="" class="login-form">
        <div class="form-group">
          <div class="input-group">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="username" placeholder="Username" required>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" placeholder="Password" required>
          </div>
        </div>
        <button type="submit" class="login-button">
          <i class="fas fa-sign-in-alt"></i>
          Login
        </button>
      </form>
    </div>
  </div>
</body>
</html>
