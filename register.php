<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - WATCH-CART</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- CSS Files -->
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/register.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <div class="animated-bg"></div>

  <div class="register-container">
    <form class="register-form" action="register_process.php" method="POST" onsubmit="return validateForm();">

      <h2>Create an Account</h2>

      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" required>

      <label for="gender">Gender</label>
      <select id="gender" name="gender" required>
        <option value="">Select Gender</option>
        <option>Male</option>
        <option>Female</option>
        <option>Other</option>
      </select>

      <label for="dob">Date of Birth</label>
      <input type="date" id="dob" name="dob" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Sign Up</button>

      <p class="bottom-text">
        Already have an account? <a href="login.php">Login</a>
      </p>
    </form>
  </div>

  <?php include 'footer.php'; ?>

  <!-- JS Validation -->
  <script>
    function validateForm() {
      const name = document.getElementById("fullname").value.trim();
      const gender = document.getElementById("gender").value;
      const dob = document.getElementById("dob").value;
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;

      const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

      if (name.length < 3) {
        alert("Full Name must be at least 3 characters.");
        return false;
      }

      if (gender === "") {
        alert("Please select your gender.");
        return false;
      }

      if (dob === "") {
        alert("Please enter your date of birth.");
        return false;
      }

      if (!email.match(emailPattern)) {
        alert("Please enter a valid email address.");
        return false;
      }

      if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
      }

      return true;
    }
  </script>

</body>
</html>
 