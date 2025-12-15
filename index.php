<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>WRIST-WONDERS | Home</title>

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/footer.css">
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

  <?php include 'navbar.php'; ?>

  <?php
  // Display notification if present
  if (isset($_GET['status']) && isset($_GET['message'])) {
      $status = $_GET['status'];
      $message = $_GET['message'];
      echo '<div class="notification ' . htmlspecialchars($status) . '" id="notification">' . htmlspecialchars($message) . '</div>';
  }
  ?>

  <!-- Hero Section -->
  <header class="hero-section" id="hero">
    <div class="hero-content">
      <h1>Welcome to WRIST-WONDERS</h1>
      <p>Discover premium watches tailored to your style.</p>
      <div class="hero-buttons">
        <a href="shop.php" class="hero-btn primary">Explore Collection</a>
        <a href="#featured" class="hero-btn secondary">Featured Watches</a>
      </div>
    </div>
  </header>

  <!-- Featured Products Section -->
  <section class="featured-section" id="featured">
    <h2>Featured Watches</h2>
    <div class="featured-grid">
      <?php
      require_once 'db.php';
      
      // Check if database connection is successful
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Simple query to get products
      $sql = "SELECT id, name, price FROM products LIMIT 4";
      $result = $conn->query($sql);

      if ($result) {
          if ($result->num_rows > 0) {
              $featured_images = [
                  'featured1.jpg',
                  'featured2.jpg',
                  'featured3.avif',
                  'featured4.jpg'
              ];
              $index = 0;
              while($row = $result->fetch_assoc()) {
      ?>
              <div class="featured-card">
                <div class="featured-image">
                  <img src="images/featured_watches/<?php echo $featured_images[$index]; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                  <div class="featured-overlay">
                    <a href="shop.php?id=<?php echo $row['id']; ?>" class="view-btn">View Details</a>
                  </div>
                </div>
                <div class="featured-info">
                  <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                  <p class="price">Rs. <?php 
                    $prices = [35, 18, 23, 19];
                    echo number_format($prices[$index], 2); 
                  ?></p>
                </div>
              </div>
      <?php 
                  $index++;
              }
          } else {
              echo '<p class="no-products">No featured products available at the moment.</p>';
          }
      } else {
          echo '<p class="error-message">Error: ' . $conn->error . '</p>';
      }
      ?>
    </div>
  </section>

  <!-- Intro Section -->
  <section class="intro-section">
    <h2>Why Choose Us?</h2>

    <div class="intro-grid">
      <div class="intro-box">
        <i class="fas fa-truck-fast"></i>
        <h3>Fast Delivery</h3>
        <p>We ensure fast and secure delivery of your orders.</p>
      </div>

      <div class="intro-box">
        <i class="fas fa-shield-check"></i>
        <h3>100% Authentic</h3>
        <p>We offer only original and branded watches.</p>
      </div>

      <div class="intro-box">
        <i class="fas fa-headset"></i>
        <h3>24/7 Support</h3>
        <p>We're here to help you anytime you need.</p>
      </div>
    </div>

  </section>

  <!-- About Us Section -->
  <section class="about-section">
    <div class="about-content">
      <div class="about-text">
        <h2>About WRIST-WONDERS</h2>
        <p>Welcome to WRIST-WONDERS, your premier destination for luxury timepieces. With over a decade of experience, we've curated an exceptional collection of watches from the world's most prestigious brands.</p>
        <p>Our commitment to authenticity, quality, and customer satisfaction sets us apart. Each watch in our collection is carefully selected to ensure it meets our high standards of excellence.</p>
        <a href="about.php" class="about-btn">Learn More</a>
      </div>
      <div class="about-image">
        <img src="images/about-us.jpg" alt="About WRIST-WONDERS">
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="faq-section">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-container">
      <div class="faq-item">
        <div class="faq-question">
          <h3>How do I verify the authenticity of a watch?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>All our watches come with official warranty cards and serial numbers. You can verify authenticity through the brand's official website or by visiting an authorized dealer.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>What is your return policy?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>We offer a 30-day return policy for all watches. The watch must be in its original condition with all packaging and documentation.</p>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question">
          <h3>Do you offer international shipping?</h3>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="faq-answer">
          <p>Yes, we ship worldwide. Shipping costs and delivery times vary by location. You can check shipping rates during checkout.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-section" id="contact">
    <h2>Get in Touch</h2>
    <div class="contact-container">
      <div class="contact-info">
        <div class="contact-item">
          <i class="fas fa-map-marker-alt"></i>
          <h3>Visit Us</h3>
          <p>123 Watch Street<br>Kalanki, Kathmandu</p>
        </div>
        <div class="contact-item">
          <i class="fas fa-phone"></i>
          <h3>Call Us</h3>
          <p>01-4314414<br>9808054102</p>
        </div>
        <div class="contact-item">
          <i class="fas fa-envelope"></i>
          <h3>Email Us</h3>
          <p>info@wrist-wonders.com<br>support@wrist-wonders.com</p>
        </div>
      </div>
      <form class="contact-form" action="process_contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="tel" name="phone" placeholder="Your Phone">
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit" class="submit-btn">Send Message</button>
      </form>
    </div>
  </section>

  <!-- Social Media Section -->
  <section class="social-section">
    <h2>Follow Us</h2>
    <div class="social-links">
      <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
      <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
      <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
      <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
      <a href="#" class="social-link"><i class="fab fa-pinterest"></i></a>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script>
    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(question => {
      question.addEventListener('click', () => {
        const item = question.parentElement;
        const answer = question.nextElementSibling;
        const icon = question.querySelector('i');

        item.classList.toggle('active');
        if (item.classList.contains('active')) {
          answer.style.maxHeight = answer.scrollHeight + 'px';
          icon.style.transform = 'rotate(180deg)';
        } else {
          answer.style.maxHeight = '0';
          icon.style.transform = 'rotate(0)';
        }
      });
    });

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });

    // Add notification auto-hide
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 5000);
    }
  </script>

</body>
</html>
