<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM products WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: manage_products.php");
    exit();
}

// Fetch all products
$query = "SELECT * FROM products ORDER BY brand, name";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <h1>Manage Products</h1>
        
        <!-- Add Product Form -->
        <div class="admin-form-container">
            <h2>Add New Product</h2>
            <form action="add_product.php" method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <select id="brand" name="brand" required>
                        <option value="">Select Brand</option>
                        <option value="Rolex">Rolex</option>
                        <option value="Omega">Omega</option>
                        <option value="Tissot">Tissot</option>
                        <option value="Seiko">Seiko</option>
                        <option value="Casio">Casio</option>
                        <option value="Fossil">Fossil</option>
                        <option value="Titan">Titan</option>
                        <option value="Apple">Apple</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (Max Rs. 40)</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" max="40" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                
                <button type="submit">Add Product</button>
            </form>
        </div>

        <!-- Products Table -->
        <div class="products-table">
            <h2>Current Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sn = 1;
                    while ($product = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td>
                                <img src="../images/<?php echo $product['brand']; ?>/<?php echo $product['image']; ?>" 
                                     alt="<?php echo $product['name']; ?>" 
                                     class="product-thumbnail">
                            </td>
                            <td><?php echo $product['name']; ?></td>
                            <td>Rs. <?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo substr($product['description'], 0, 100) . '...'; ?></td>
                            <td class="action-buttons">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" 
                                   class="btn edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?delete=<?php echo $product['id']; ?>" 
                                   class="btn delete"
                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
