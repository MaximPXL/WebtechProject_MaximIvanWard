<?php
include 'main.php';

if (!is_logged_in()) {
    header('Location: Login.php');
    exit;
}
if (!is_admin()) {
    header('Location: Home.php');
    exit;
}

$restock_success = restock_products();
$products = get_all_products();

$product_images = [
    'Green Hat' => 'pngs/hat.png',
    'Green Shirt' => 'pngs/shirt.png',
    'White Mug' => 'pngs/mug.png',
    'Green Bag' => 'pngs/bag.png',
    'Green Notebook' => 'pngs/notebook.png',
    'Notebook' => 'pngs/notebook.png',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Inventory - Purple & Green Store</title>
    <link rel="stylesheet" href="font.css">

</head>
<body>
    <div class="top-bar">
        <span class="logo">Purple & Green Store</span>
        <a href="<?php echo is_logged_in() ? 'Account.php' : 'Login.php'; ?>" class="account-link">Account</a>
        <a href="cart.php" class="cart-link">Cart</a>
        <?php if (is_admin()): ?>
            <a href="admin.php" class="admin-link" style="color:#39ff14;">Admin</a>
        <?php endif; ?>
    </div>
    <header>
        <nav>
            <a href="Home.php">Home</a>
            <a href="store.php">Shop</a>
            <a href="Contact.php">Contact</a>
        </nav>
    </header>
    <div class="container">
        <h1 class="admin-title">Inventory Overview</h1>
        <?php if ($restock_success): ?>
            <div class="restock-success">Stock updated successfully!</div>
        <?php endif; ?>
        <form method="post">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Stock</th>
                    <th>Price</th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="cart-product-cell">
                            <?php
                                $img = '';
                                foreach ($product_images as $name => $src) {
                                    if (stripos($product['naam'], $name) !== false) {
                                        $img = $src;
                                        break;
                                    }
                                }
                                if ($img):
                            ?>
                                <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($product['naam']); ?>" class="cart-img">
                            <?php endif; ?>
                            <?php echo htmlspecialchars($product['naam']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($product['maat']); ?></td>
                        <td>
                            <input type="number" name="stock[<?php echo $product['id']; ?>]" value="<?php echo htmlspecialchars($product['aantal']); ?>" min="0" class="stock-input">
                        </td>
                        <td>$<?php echo number_format($product['prijs_per_stuk'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit" name="restock" class="restock-btn">Restock</button>
        </form>
    </div>
    <footer>
        &copy; 2025 Purple & Green Store. All rights reserved.
    </footer>
</body>
</html>