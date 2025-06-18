<?php include 'main.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact - Purple & Green Store</title>
  <link rel="stylesheet" href="font.css">
</head>
<body>
  <!-- Top black bar -->
  <div class="top-bar">
    <span class="logo">Purple & Green Store</span>
    <a href="<?php echo is_logged_in() ? 'account.php' : 'Login.php'; ?>" class="account-link">Account</a>
    <a href="<?php echo is_logged_in() ? 'store.php' : 'Login.php'; ?>" class="cart-link">Cart</a>
  </div>
  <!-- Gray navigation bar -->
  <header>
    <nav>
      <a href="home.php">Home</a>
      <a href="store.php">Shop</a>
      <a href="Contact.php">Contact</a>
    </nav>
  </header>
  <!-- Sub-navigation gray bar -->

  <div class="container">
    <h1>Contact Us</h1>
    <p style="font-size:1.1em; margin-bottom:25px;">
      Have a question, comment, or need help with your order? Fill out the form below and our team will get back to you as soon as possible!
    </p>
    <div style="text-align:center; margin-top:40px;">
      <p>Email: <a href="mailto:support@purplegreenstore.com" style="color:#39ff14;">Purple.Green@store.be</a></p>
      <p>Phone: <a href="tel:+32494949494" style="color:#39ff14;">+32 494 94 94 94</a></p>
    </div>
  </div>
  <footer>
    &copy; 2025 Purple & Green Store. All rights reserved.