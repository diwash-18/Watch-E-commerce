<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit();
}

include '../db.php';

if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $brand = $_POST['brand'];
  $price = $_POST['price'];

  // Check if a new image is uploaded
  if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $target_dir = "../images/" . $image;
    move_uploaded_file($tmp_name, $target_dir);

    // Update with new image
    $sql = "UPDATE products SET name='$name', brand='$brand', price='$price', image='$image' WHERE id=$id";
  } else {
    // Update without changing image
    $sql = "UPDATE products SET name='$name', brand='$brand', price='$price' WHERE id=$id";
  }

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Product updated successfully!'); window.location.href='manage_products.php';</script>";
  } else {
    echo "Error updating product: " . mysqli_error($conn);
  }
}
?>
