<?php
include 'main.php';

if (!is_logged_in()) {
    header('Location: Login.php');
    exit;
}

$pdo = get_pdo();
$stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purple & Green Store - Account</title>
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
        $adminCheck = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($adminCheck && $adminCheck['rol'] === 'admin') {
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
        <h1>Account Details</h1>
        <div class="login-center">
            <div class="account-info-form">
                <div class="account-row">
                    <label>Email</label>
                    <span class="account-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="account-row">
                    <label>Username</label>
                    <span class="account-value"><?php echo htmlspecialchars($user['gebruikersnaam']); ?></span>
                </div>
                <div class="account-row">
                    <label>Telephone Number</label>
                    <span class="account-value"><?php echo htmlspecialchars($user['telefoon']); ?></span>
                </div>
                <div class="account-row">
                    <label>Country</label>
                    <span class="account-value"><?php echo htmlspecialchars($user['land']); ?></span>
                </div>
                <div class="account-row">
                    <label>State</label>
                    <span class="account-value"><?php echo htmlspecialchars($user['provincie']); ?></span>
                </div>
                <div class="account-row" style="display: flex; gap: 16px; margin-bottom: 8px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Street</label>
                        <span class="account-value"><?php echo htmlspecialchars($user['straatnaam']); ?></span>
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <label>Number</label>
                        <span class="account-value"><?php echo htmlspecialchars($user['huinummer']); ?></span>
                    </div>
                </div>
                <?php if (!empty($user['bus'])): ?>
                <div class="account-row">
                    <label>Bus</label>
                    <span class="account-value"><?php echo htmlspecialchars($user['bus']); ?></span>
                </div>
                <?php endif; ?>
                <div class="account-row">
                    <label>Zipcode</label>
                    <span class="account-value"><?php echo htmlspecialchars($user['postcode']); ?></span>
                </div>
                <form method="post" action="logout.php">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </div>
    <footer>
        &copy; 2025 Purple & Green Store. All rights reserved.
    </footer>
</body>

</html>