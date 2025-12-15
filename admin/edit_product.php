<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Get product details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
} else {
    header("Location: manage_products.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
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
            $query = "UPDATE products SET 
                     name = '$name', 
                     price = '$price', 
                     description = '$description', 
                     image = '$image' 
                     WHERE id = $id";
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {
        // Update without changing image
        $query = "UPDATE products SET 
                 name = '$name', 
                 price = '$price', 
                 description = '$description' 
                 WHERE id = $id";
    }
    
    if (mysqli_query($conn, $query)) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <h1>Edit Product</h1>
        
        <div class="admin-form-container">
            <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Current Image</label>
                    <img src="../images/products/<?php echo $product['image']; ?>" 
                         alt="Current Product Image" 
                         class="current-image">
                    <label for="new_image">Change Image (Optional)</label>
                    <input type="file" id="new_image" name="image" accept="image/*">
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn edit">Update Product</button>
                    <a href="manage_products.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
