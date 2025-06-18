<?php include 'main.php';
$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purple & Green Store - Login</title>
  <link rel="stylesheet" href="font.css">
</head>
<body>
  <!-- Top black bar -->
  <div class="top-bar">
    <span class="logo">Purple & Green Store</span>
    <a href="#" class="account-link">Account</a>
    <a href="#" class="cart-link">Cart</a>
  </div>
  <!-- Gray navigation bar -->
  <header>
    <nav>
      <a href="home.php">Home</a>
      <a href="store.php">Shop</a>
      <a href="Contact.php">Contact</a>
    </nav>
  </header>
  <div class="container">
    <h1>Login</h1>
    <div class="login-center">
      <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
      <?php endif; ?>
      <form class="login-form" method="post">
        <h4 for="username">Username:</h4>
        <input type="text" id="username" name="username" required>

        <h4 for="password">Password:</h4>
        <input type="password" id="password" name="password" required>

        <button type="submit">Submit</button>
      </form>
    </div>
    <button type="button" class="signup-btn" onclick="window.location.href='SignUp.php'">Sign up</button>
  </div>
  <footer>
    &copy; 2025 Purple & Green Store. All rights reserved.
  </footer>
</body>
</html>