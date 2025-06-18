<?php include 'main.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purple & Green Store</title>
  <link rel="stylesheet" href="font.css">
</head>
<body>
  <!-- Top black bar -->
  <div class="top-bar">
    <span class="logo">Purple & Green Store</span>
    <a href="<?php echo is_logged_in() ? 'account.php' : 'Login.php'; ?>" class="account-link">Account</a>
    <a href="<?php echo is_logged_in() ? 'cart.php' : 'Login.php'; ?>" class="cart-link">Cart</a>
  </div>
  <!-- Gray navigation bar -->
  <header>
    <nav>
      <a href="Home.php">Home</a>
      <a href="store.php">Shop</a>
      <a href="Contact.php">Contact</a>
    </nav>
  </header>
  <!-- Optional sub-navigation gray bar -->
  <div class="container">
    <h1>Welcome to the Purple & Green Store!</h1>
    <div class="store-items">
      <div class="item">
        <img src="pngs/hat.png" alt="green Hat">
        <h2>Green Hat</h2>
        <p>Stylish green hat with purple accents.</p>
        <span class="price">$15.99</span>
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
      <div class="item">
        <img src="pngs/shirt.png" alt="Green Shirt">
        <h2>Green Shirt</h2>
        <p>Bright green shirt with purple logo.</p>
        <span class="price">$22.50</span>
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
      <div class="item">
        <img src="pngs/mug.png" alt="White Mug">
        <h2>White Mug</h2>
        <p>Enjoy your drink in style with this mug.</p>
        <span class="price">$9.99</span>
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
      <div class="item">
        <img src="pngs/bag.png" alt="Green Bag">
        <h2>Green Bag</h2>
        <p>Eco-friendly green bag.</p>
        <span class="price">$12.99</span>
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
      <div class="item">
        <img src="pngs/notebook.png" alt="Green Notebook">
        <h2>Green Notebook</h2>
        <p>Take notes in this stylish green notebook.</p>
        <span class="price">$7.50</span>
        <button class="add-to-cart-btn">Add to Cart</button>
      </div>
    </div>
  </div>
  <footer>
    &copy; 2025 Purple & Green Store. All rights reserved.
  </footer>
  <script>
    const isLoggedIn = <?php echo is_logged_in() ? 'true' : 'false'; ?>;
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        if (!isLoggedIn) {
          e.preventDefault();
          window.location.href = 'Login.php';
        } else {
          alert('Item added to cart!');
        }
      });
    });
  </script>
</body>
</html>