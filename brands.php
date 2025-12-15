<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>WATCH-CART | Brands</title>

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/brands.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

  <?php include 'navbar.php'; ?>

  <section class="brands">
    <h2>Browse by Brand</h2>
    <div class="brand-buttons">
      <?php
      $brands = ['Rolex', 'Fossil', 'Casio', 'Titan', 'Seiko', 'Tissot', 'Apple', 'Omega'];
      foreach ($brands as $brand) {
        echo "<a href='brands.php?brand=$brand' class='brand-btn'>$brand</a>";
      }
      ?>
    </div>
  </section>

  <section class="product-showcase">
    <?php
    if (isset($_GET['brand'])) {
      $brand = $_GET['brand'];
      echo "<h2>$brand Watches</h2>";
      echo "<div class='product-grid'>";

      if ($brand == 'Rolex') {
        ?>

        <div class="product-card">
          <img src="images/Rolex1.webp" alt="Rolex Watch 1">
          <h3>Air King</h3>
          <p>Rs. 2500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Rolex Watch 1">
            <input type="hidden" name="price" value="2500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Rolex2.jpg" alt="Rolex Watch 2">
          <h3>Oyster Perpetual</h3>
          <p>Rs. 3000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Rolex Watch 2">
            <input type="hidden" name="price" value="3000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Rolex3.webp" alt="Rolex Watch 3">
          <h3>Gmt-Master Li</h3>
          <p>Rs. 3500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Rolex Watch 3">
            <input type="hidden" name="price" value="3500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Rolex4.jpg" alt="Rolex Watch 4">
          <h3>Date Just</h3>
          <p>Rs. 4000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Rolex Watch 4">
            <input type="hidden" name="price" value="4000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Rolex5.jpg" alt="Rolex Watch 5">
          <h3>Sub-Mariner</h3>
          <p>Rs. 4500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Rolex Watch 5">
            <input type="hidden" name="price" value="4500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Rolex6.jpg" alt="Rolex Watch 6">
          <h3>Yacht-Master</h3>
          <p>Rs. 5000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Rolex Watch 6">
            <input type="hidden" name="price" value="5000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>
        <?php
      } else

        if ($brand == 'Fossil') {
          ?>

          <div class="product-card">
            <img src="images/Fossil1.jpg" alt="Fossil Watch 1">
            <h3>Machine Chronograph</h3>
            <p>Rs. 2500</p>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="name" value="Fossil Watch 1">
              <input type="hidden" name="price" value="2500">
              <button type="submit">Add to Cart</button>
            </form>
          </div>

          <div class="product-card">
            <img src="images/Fossil2.jpg" alt="Fossil Watch 2">
            <h3>Townsman Skeleton</h3>
            <p>Rs. 3000</p>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="name" value="Fossil Watch 2">
              <input type="hidden" name="price" value="3000">
              <button type="submit">Add to Cart</button>
            </form>
          </div>

          <div class="product-card">
            <img src="images/Fossil3.jpg" alt="Fossil Watch 3">
            <h3>Neutra Chronograph</h3>
            <p>Rs. 3500</p>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="name" value="Fossil Watch 3">
              <input type="hidden" name="price" value="3500">
              <button type="submit">Add to Cart</button>
            </form>
          </div>

          <div class="product-card">
            <img src="images/Fossil4.webp" alt="Fossil Watch 4">
            <h3>Everett Chronograph</h3>
            <p>Rs. 4000</p>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="name" value="Fossil Watch 4">
              <input type="hidden" name="price" value="4000">
              <button type="submit">Add to Cart</button>
            </form>
          </div>

          <div class="product-card">
            <img src="images/Fossil5.jpg" alt="Fossil Watch 5">
            <h3>Carraway</h3>
            <p>Rs. 4500</p>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="name" value="Fossil Watch 5">
              <input type="hidden" name="price" value="4500">
              <button type="submit">Add to Cart</button>
            </form>
          </div>

          <div class="product-card">
            <img src="images/Fossil6.webp" alt="Fossil Watch 6">
            <h3>Bronson</h3>
            <p>Rs. 5000</p>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="name" value="Fossil Watch 6">
              <input type="hidden" name="price" value="5000">
              <button type="submit">Add to Cart</button>
            </form>
          </div>
          <?php
        }


      if ($brand == 'Casio') {
        ?>

        <div class="product-card">
          <img src="images/Casio1.webp" alt="Casio Watch 1">
          <h3>G-Shock></h3>
          <p>Rs. 2500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Casio Watch 1">
            <input type="hidden" name="price" value="2500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Casio2.webp" alt="Casio Watch 2">
          <h3>Baby-G</h3>
          <p>Rs. 3000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Casio Watch 2">
            <input type="hidden" name="price" value="3000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Casio3.avif" alt="Casio Watch 3">
          <h3>Sheen</h3>
          <p>Rs. 3500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Casio Watch 3">
            <input type="hidden" name="price" value="3500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Casio4.webp" alt="Casio Watch 4">
          <h3> Pro Trek</h3>
          <p>Rs. 4000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Casio Watch 4">
            <input type="hidden" name="price" value="4000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Casio5.jpg" alt="Casio Watch 5">
          <h3>Edifice</h3>
          <p>Rs. 4500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Casio Watch 5">
            <input type="hidden" name="price" value="4500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Casio6.jpg" alt="Casio Watch 6">
          <h3>Casio Vintage</h3>
          <p>Rs. 5000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Casio Watch 6">
            <input type="hidden" name="price" value="5000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>
        <?php
      }


      if ($brand == 'Titan') {
        ?>

        <div class="product-card">
          <img src="images/Titan1.webp" alt="Titan Watch 1">
          <h3>Titan Minimals</h3>
          <p>Rs. 2500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Titan Watch 1">
            <input type="hidden" name="price" value="2500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Titan2.avif" alt="Titan Watch 2">
          <h3>Titan Karishma</h3>
          <p>Rs. 3000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Titan Watch 2">
            <input type="hidden" name="price" value="3000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Titan3.webp" alt="Titan Watch 3">
          <h3>Titan Neo</h3>
          <p>Rs. 3500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Titan Watch 3">
            <input type="hidden" name="price" value="3500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Titan4.jpg" alt="Titan Watch 4">
          <h3>Titan</h3>
          <p>Rs. 4000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Titan Watch 4">
            <input type="hidden" name="price" value="4000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Titan5.webp" alt="Titan Watch 5">
          <h3>Titan</h3>
          <p>Rs. 4500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Titan Watch 5">
            <input type="hidden" name="price" value="4500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Titan6.webp" alt="Titan Watch 6">
          <h3>Titan Quartz</h3>
          <p>Rs. 5000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Titan Watch 6">
            <input type="hidden" name="price" value="5000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>
        <?php
      }


      if ($brand == 'Seiko') {
        ?>

        <div class="product-card">
          <img src="images/Seiko1.jpg" alt="Seiko Watch 1">
          <h3>Seiko Watch 1</h3>
          <p>Rs. 2500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Seiko Watch 1">
            <input type="hidden" name="price" value="2500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Seiko2.jpg" alt="Seiko Watch 2">
          <h3>Seiko Watch 2</h3>
          <p>Rs. 3000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Seiko Watch 2">
            <input type="hidden" name="price" value="3000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Seiko3.jpg" alt="Seiko Watch 3">
          <h3>Seiko Watch 3</h3>
          <p>Rs. 3500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Seiko Watch 3">
            <input type="hidden" name="price" value="3500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Seiko4.jpg" alt="Seiko Watch 4">
          <h3>Seiko Watch 4</h3>
          <p>Rs. 4000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Seiko Watch 4">
            <input type="hidden" name="price" value="4000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Seiko5.jpg" alt="Seiko Watch 5">
          <h3>Seiko Watch 5</h3>
          <p>Rs. 4500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Seiko Watch 5">
            <input type="hidden" name="price" value="4500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Seiko6.jpg" alt="Seiko Watch 6">
          <h3>Seiko Watch 6</h3>
          <p>Rs. 5000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Seiko Watch 6">
            <input type="hidden" name="price" value="5000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>
        <?php
      }


      if ($brand == 'Tissot') {
        ?>

        <div class="product-card">
          <img src="images/Tissot1.jpg" alt="Tissot Watch 1">
          <h3>Tissot Watch 1</h3>
          <p>Rs. 2500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Tissot Watch 1">
            <input type="hidden" name="price" value="2500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Tissot2.jpg" alt="Tissot Watch 2">
          <h3>Tissot Watch 2</h3>
          <p>Rs. 3000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Tissot Watch 2">
            <input type="hidden" name="price" value="3000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Tissot3.jpg" alt="Tissot Watch 3">
          <h3>Tissot Watch 3</h3>
          <p>Rs. 3500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Tissot Watch 3">
            <input type="hidden" name="price" value="3500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Tissot4.jpg" alt="Tissot Watch 4">
          <h3>Tissot Watch 4</h3>
          <p>Rs. 4000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Tissot Watch 4">
            <input type="hidden" name="price" value="4000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Tissot5.jpg" alt="Tissot Watch 5">
          <h3>Tissot Watch 5</h3>
          <p>Rs. 4500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Tissot Watch 5">
            <input type="hidden" name="price" value="4500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Tissot6.jpg" alt="Tissot Watch 6">
          <h3>Tissot Watch 6</h3>
          <p>Rs. 5000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Tissot Watch 6">
            <input type="hidden" name="price" value="5000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <?php
      }


      if ($brand == 'Apple') {
        ?>

        <div class="product-card">
          <img src="images/Apple1.jpg" alt="Apple Watch 1">
          <h3>Apple Watch 1</h3>
          <p>Rs. 2500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Apple Watch 1">
            <input type="hidden" name="price" value="2500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Apple2.jpg" alt="Apple Watch 2">
          <h3>Apple Watch 2</h3>
          <p>Rs. 3000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Apple Watch 2">
            <input type="hidden" name="price" value="3000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Apple3.jpg" alt="Apple Watch 3">
          <h3>Apple Watch 3</h3>
          <p>Rs. 3500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Apple Watch 3">
            <input type="hidden" name="price" value="3500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Apple4.jpg" alt="Apple Watch 4">
          <h3>Apple Watch 4</h3>
          <p>Rs. 4000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Apple Watch 4">
            <input type="hidden" name="price" value="4000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Apple5.jpg" alt="Apple Watch 5">
          <h3>Apple Watch 5</h3>
          <p>Rs. 4500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Apple Watch 5">
            <input type="hidden" name="price" value="4500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Apple6.jpg" alt="Apple Watch 6">
          <h3>Apple Watch 6</h3>
          <p>Rs. 5000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Apple Watch 6">
            <input type="hidden" name="price" value="5000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <?php
      }


      if ($brand == 'Omega') {
        ?>

        <div class="product-card">
          <img src="images/Omega1.jpg" alt="Omega Watch 1">
          <h3>Omega Watch 1</h3>
          <p>Rs. 2500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Omega Watch 1">
            <input type="hidden" name="price" value="2500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Omega2.jpg" alt="Omega Watch 2">
          <h3>Omega Watch 2</h3>
          <p>Rs. 3000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Omega Watch 2">
            <input type="hidden" name="price" value="3000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Omega3.jpg" alt="Omega Watch 3">
          <h3>Omega Watch 3</h3>
          <p>Rs. 3500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Omega Watch 3">
            <input type="hidden" name="price" value="3500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Omega4.jpg" alt="Omega Watch 4">
          <h3>Omega Watch 4</h3>
          <p>Rs. 4000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Omega Watch 4">
            <input type="hidden" name="price" value="4000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Omega5.jpg" alt="Omega Watch 5">
          <h3>Omega Watch 5</h3>
          <p>Rs. 4500</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Omega Watch 5">
            <input type="hidden" name="price" value="4500">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <div class="product-card">
          <img src="images/Omega6.jpg" alt="Omega Watch 6">
          <h3>Omega Watch 6</h3>
          <p>Rs. 5000</p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="name" value="Omega Watch 6">
            <input type="hidden" name="price" value="5000">
            <button type="submit">Add to Cart</button>
          </form>
        </div>

        <?php
      } {
        echo "<p>Other products for $brand are not added yet.</p>";
      }

      echo "</div>";
    } else {
      echo "<h3>Select a brand above to view available watches.</h3>";
    }
    ?>
  </section>

  <?php include 'footer.php'; ?>

</body>

</html>