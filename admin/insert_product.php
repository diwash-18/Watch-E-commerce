<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit();
}

include '../db.php'; // adjust if needed

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $brand = $_POST['brand'];
  $price = $_POST['price'];

  $image = $_FILES['image']['name'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $target_dir = "../images/" . $image;

  if (move_uploaded_file($tmp_name, $target_dir)) {
    $sql = "INSERT INTO products (name, brand, price, image) VALUES ('$name', '$brand', '$price', '$image')";
    if (mysqli_query($conn, $sql)) {
      echo "<script>alert('Product Added Successfully!'); window.location.href='manage_products.php';</script>";
    } else {
      echo "Database Error: " . mysqli_error($conn);
    }
  } else {
    echo "Image Upload Failed!";
  }
}
?>
