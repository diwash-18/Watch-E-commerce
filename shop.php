<?php
require_once 'session.php';
require_once 'db.php';

// Get selected brand
$selected_brand = isset($_GET['brand']) ? $_GET['brand'] : null;

// Get all brands for the brand grid
$brands = [];
$brands_query = "SELECT name FROM brands ORDER BY name";
$brands_result = $conn->query($brands_query);

if ($brands_result === false) {
    die("Error fetching brands: " . $conn->error);
}

while ($row = $brands_result->fetch_assoc()) {
    $brands[] = $row['name'];
}

// Get products for selected brand
$products = [];
if ($selected_brand) {
    $products_query = "SELECT * FROM products WHERE brand = ? ORDER BY name";
    $stmt = $conn->prepare($products_query);
    
    if ($stmt === false) {
        die("Error preparing products query: " . $conn->error);
    }
    
    $stmt->bind_param("s", $selected_brand);
    
    if (!$stmt->execute()) {
        die("Error executing products query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Watch Cart</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="shop-container">
        <?php if (!$selected_brand): ?>
            <!-- Brands Section -->
            <div class="brands-section">
                <h1>Explore Our Watch Brands</h1>
                <div class="brands-grid">
                    <?php foreach ($brands as $brand_name): ?>
                        <a href="?brand=<?php echo urlencode($brand_name); ?>" class="brand-card">
                            <div class="brand-info">
                                <h3><?php echo htmlspecialchars($brand_name); ?></h3>
                                <span class="explore-btn">View Collection <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Products Section -->
            <div class="products-section">
                <div class="brand-header">
                    <a href="shop.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Brands</a>
                    <h1><?php echo htmlspecialchars($selected_brand); ?> Collection</h1>
                </div>

                <div class="filters-section">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search <?php echo htmlspecialchars($selected_brand); ?> watches...">
                        <i class="fas fa-search"></i>
                    </div>

                    <div class="price-filter">
                        <h3>Price Range</h3>
                        <div class="price-inputs">
                            <input type="number" id="minPrice" placeholder="Min">
                            <input type="number" id="maxPrice" placeholder="Max">
                            <button id="applyPriceFilter">Apply</button>
                        </div>
                    </div>

                    <div class="sort-options">
                        <h3>Sort By</h3>
                        <select id="sortSelect">
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>
                </div>

                <div class="products-grid">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $watch): ?>
                            <div class="product-card" data-price="<?php echo $watch['price']; ?>" data-name="<?php echo strtolower($watch['name']); ?>">
                                <div class="product-image">
                                    <img src="images/<?php echo $watch['brand']; ?>/<?php echo $watch['image']; ?>" 
                                         alt="<?php echo htmlspecialchars($watch['name']); ?>">
                                    <div class="quick-view" data-product-id="<?php echo $watch['id']; ?>">
                                        <i class="fas fa-eye"></i> Quick View
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($watch['name']); ?></h3>
                                    <p class="price">Rs. <?php echo number_format($watch['price'], 2); ?></p>
                                    <button class="add-to-cart" data-product-id="<?php echo $watch['id']; ?>">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-products">
                            <i class="fas fa-box-open"></i>
                            <p>No products found for this brand</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick View Modal -->
    <div id="quickViewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="quickViewContent"></div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                const productName = product.dataset.name;
                if (productName.includes(searchTerm)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        });

        // Price filter functionality
        document.getElementById('applyPriceFilter').addEventListener('click', function() {
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                const price = parseFloat(product.dataset.price);
                if (price >= minPrice && price <= maxPrice) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        });

        // Sort functionality
        document.getElementById('sortSelect').addEventListener('change', function(e) {
            const sortBy = e.target.value;
            const productsGrid = document.querySelector('.products-grid');
            const products = Array.from(document.querySelectorAll('.product-card'));
            
            products.sort((a, b) => {
                switch(sortBy) {
                    case 'price_low':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price_high':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'name_asc':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    case 'name_desc':
                        return b.dataset.name.localeCompare(a.dataset.name);
                    default:
                        return 0;
                }
            });
            
            products.forEach(product => productsGrid.appendChild(product));
        });

        // Quick View functionality
        const modal = document.getElementById('quickViewModal');
        const quickViewButtons = document.querySelectorAll('.quick-view');
        const closeBtn = document.querySelector('.close');

        quickViewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const productCard = this.closest('.product-card');
                const productName = productCard.querySelector('h3').textContent;
                const productPrice = productCard.querySelector('.price').textContent;
                const productImage = productCard.querySelector('img').src;

                document.getElementById('quickViewContent').innerHTML = `
                    <div class="quick-view-product">
                        <img src="${productImage}" alt="${productName}">
                        <div class="quick-view-details">
                            <h2>${productName}</h2>
                            <p class="price">${productPrice}</p>
                            <p class="description">Experience the perfect blend of style and functionality with this exquisite timepiece.</p>
                            <button class="add-to-cart" data-product-id="${productId}">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                `;
                modal.style.display = 'block';
            });
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Add to Cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const price = this.dataset.price;
                console.log('Adding product to cart:', productId);
                
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&price=${price}`
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert(data.message);
                        // Update cart count in navbar
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            const currentCount = parseInt(cartCount.textContent) || 0;
                            cartCount.textContent = currentCount + 1;
                        }
                    } else {
                        if (data.message === 'Please login to add items to cart') {
                            window.location.href = 'login.php';
                        } else {
                            alert(data.message || 'Failed to add item to cart');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to add item to cart. Please try again.');
                });
            });
        });
    </script>
</body>
</html>

