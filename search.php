<?php
require_once 'db.php';

// Get the search query
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Initialize results array
$results = [];
$message = '';

if (!empty($search)) {
    // Prepare the search query
    $search = "%{$search}%";
    $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Get all matching products
    while ($row = $result->fetch_assoc()) {
        // Ensure image path is properly set
        if (empty($row['image'])) {
            $row['image'] = 'default-watch.jpg';
        }
        $results[] = $row;
    }
    
    // Set message based on results
    if (empty($results)) {
        $message = "No products found matching your search.";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - WRIST-WONDERS</title>
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="search-container">
        <div class="search-header">
            <h1>Search Results</h1>
            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Search for watches...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <?php if (!empty($search)): ?>
            <div class="search-results">
                <?php if (!empty($message)): ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <p><?php echo htmlspecialchars($message); ?></p>
                    </div>
                <?php else: ?>
                    <div class="results-grid">
                        <?php foreach ($results as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <?php 
                                    $image_path = 'images/products/' . htmlspecialchars($product['image']);
                                    $default_image = 'images/default-watch.jpg';
                                    ?>
                                    <img src="<?php echo $image_path; ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         onerror="this.onerror=null; this.src='<?php echo $default_image; ?>';"
                                         loading="lazy">
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <p class="price">Rs. <?php echo number_format($product['price'], 2); ?></p>
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="view-btn">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html> 