<?php include 'main.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purple & Green Store - Home</title>
  <link rel="stylesheet" href="font.css">
</head>
<body>
  <!-- Top black bar -->
  <div class="top-bar">
    <span class="logo">Purple & Green Store</span>
    <a href="<?php echo is_logged_in() ? 'Account.php' : 'Login.php'; ?>" class="account-link">Account</a>
    <a href="cart.php" class="cart-link">Cart</a>
    <?php
    if (is_logged_in()) {
        $pdo = get_pdo();
        $stmt = $pdo->prepare("SELECT rol FROM gebruikers WHERE gebruikersnaam = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['rol'] === 'admin') {
            echo '<a href="admin.php" class="admin-link" style="color:#39ff14;">Admin</a>';
        }
    }
    ?>
  </div>
  <!-- Gray navigation bar -->
  <header>
    <nav>
            <a href="Home.php">Home</a>
            <a href="store.php">Shop</a>
            <a href="Contact.php">Contact</a>
    </nav>
  </header>
  <div class="container">
    <h1>Welcome to the Purple & Green Store!</h1>
    <p style="font-size:1.2em; margin-bottom:30px;">
      At Purple & Green Store, we celebrate bold colors and unique style! Our shop is dedicated to offering fun, vibrant products that stand out from the crowd. Whether you're looking for a gift or something special for yourself, you'll find hats, shirts, mugs, bags, and more—all in our signature purple and green theme.
    </p>
    <div class="about-section" style="margin-bottom:40px;">
      <h2>About Us</h2>
      <p>
        Founded by color enthusiasts, our mission is to bring a splash of creativity and joy to everyday items. We believe that life is better in color, and our products are designed to make you smile. Enjoy fast shipping, friendly service, and a shopping experience as unique as our products!
      </p>
    </div>
    <div style="text-align:center; margin-top:40px;">
      <a href="store.php">
        <button style="font-size:1.1em; padding:15px 40px; background:#39ff14; color:#6a0dad; border-radius:6px; border:none; font-weight:bold; cursor:pointer;">
          Enter the Shop
        </button>
      </a>
    </div>
  </div>
  <footer>
    &copy; 2025 Purple & Green Store. All rights reserved.
  </footer>
</body>
</html>