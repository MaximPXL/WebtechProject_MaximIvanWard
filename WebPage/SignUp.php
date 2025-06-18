<?php include 'main.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purple & Green Store - Sign Up</title>
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
            <a href="Shop.php">Shop</a>
            <a href="Contact.php">Contact</a>
        </nav>
    </header>
    <div class="container">
        <h1>Sign Up</h1>
        <div class="login-center">
            <?php if (isset($_GET['success'])): ?>
                <p style="color:#39ff14; text-align:center;">Account created successfully! You can now <a href='login.php' style='color:#39ff14;'>log in</a>.</p>
            <?php elseif (isset($_GET['error'])): ?>
                <p style="color:red; text-align:center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <form class="login-form" method="post" action="SignUp.php" autocomplete="on">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="telephone">Telephone Number</label>
                <input type="tel" id="telephone" name="telephone" required>

                <label for="country">Country</label>
                <input type="text" id="country" name="country" required>

                <label for="state">State</label>
                <input type="text" id="state" name="state" required>

                <label for="zipcode">Zipcode</label>
                <input type="text" id="zipcode" name="zipcode" required>

                <div style="display: flex; gap: 30px; width: 90%; margin-bottom: 8px;">
                    <div style="flex: 2;">
                        <label for="street">Street</label>
                        <input type="text" id="street" name="street" required style="width: 100%;">
                    </div>
                    <div style="flex: 1;">
                        <label for="number">Number</label>
                        <input type="text" id="number" name="number" required style="width: 100%;">
                    </div>
                </div>
                <div style="width: 90%; margin-bottom: 8px;">
                    <label for="bus">Bus <span style="font-weight: normal; color: #888;">(optional)</span></label>
                    <input type="text" id="bus" name="bus" style="width: 100%;">
                </div>
                <button type="submit" >Sign Up</button>
            </form>
            <button type="button" class="signup-btn" onclick="window.location.href='login.php'">Already have an account? Log in</button>
        </div>
    </div>
    <footer>
        &copy; 2025 Purple & Green Store. All rights reserved.
    </footer>
</body>

</html>