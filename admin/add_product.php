<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload
    $target_dir = "../images/products/";
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        die("File is not an image.");
    }
    
    // Check file size (5MB max)
    if ($_FILES["image"]["size"] > 5000000) {
        die("Sorry, your file is too large.");
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }
    
    // Upload file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into database
        $query = "INSERT INTO products (name, price, description, image) 
                 VALUES ('$name', '$price', '$description', '$image')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: manage_products.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - Admin Panel</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-container">
  <h1>Add New Product</h1>

  <form action="add_product.php" method="POST" enctype="multipart/form-data" class="admin-form">
    <label>Product Name:</label>
    <input type="text" name="name" required>

    <label>Brand:</label>
    <select name="brand" required>
      <option value="">Select Brand</option>
      <option value="Rolex">Rolex</option>
      <option value="Fossil">Fossil</option>
      <option value="Casio">Casio</option>
      <option value="Titan">Titan</option>
      <option value="Seiko">Seiko</option>
      <option value="Tissot">Tissot</option>
      <option value="Apple">Apple</option>
      <option value="Omega">Omega</option>
    </select>

    <label>Price (Rs.):</label>
    <input type="number" name="price" required>

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" name="submit">Add Product</button>
  </form>
</div>

</body>
</html>
