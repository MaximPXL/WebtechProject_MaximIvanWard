<?php
include 'main.php';
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// Map product names to image filenames
$product_images = [
    'Green Hat' => 'pngs/hat.png',
    'Green Shirt' => 'pngs/shirt.png',
    'White Mug' => 'pngs/mug.png',
    'Green Bag' => 'pngs/bag.png',
    'Green Notebook' => 'pngs/notebook.png',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="font.css">
  <style>

  </style>
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
    <h1>Your Cart</h1>
    <?php if (isset($_GET['ordered'])): ?>
      <div class="order-success">Order placed successfully!</div>
    <?php endif; ?>
    <?php if (empty($cart)): ?>
      <p>Your cart is empty.</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Product</th>
          <th>Size</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($cart as $idx => $item): ?>
          <?php
            $img = '';
            foreach ($product_images as $name => $src) {
                if (stripos($item['name'], $name) !== false) {
                    $img = $src;
                    break;
                }
            }
            // Get current stock for this item
            $pdo = get_pdo();
            $stmt = $pdo->prepare("SELECT aantal FROM producten WHERE naam = ? AND maat = ?");
            $stmt->execute([$item['name'], $item['size']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $max_stock = $row ? (int)$row['aantal'] : 1;
          ?>
          <tr>
            <td class="cart-product-cell">
              <span style="display:inline-block; vertical-align:middle;">
                <?php if ($img): ?>
                  <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-img">
                <?php endif; ?>
              </span>
              <span style="display:inline-block; vertical-align:middle;">
                <?php echo htmlspecialchars($item['name']); ?>
              </span>
            </td>
            <td><?php echo isset($item['size']) ? htmlspecialchars($item['size']) : ''; ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td>
              <form method="post" style="display:inline;">
                <input type="hidden" name="item_index" value="<?php echo $idx; ?>">
                <input type="number" name="quantity"
                  value="<?php echo isset($item['quantity']) ? $item['quantity'] : 1; ?>"
                  min="1"
                  max="<?php echo $max_stock; ?>"
                  class="cart-qty-input">
                <button type="submit" name="update_qty">Update</button>
              </form>
            </td>
            <td>
              <?php
                $qty = isset($item['quantity']) ? $item['quantity'] : 1;
              ?>
              $<?php echo number_format($item['price'] * $qty, 2); ?>
              <?php $total += $item['price'] * $qty; ?>
            </td>
            <td>
              <form method="post" style="display:inline;">
                <input type="hidden" name="item_index" value="<?php echo $idx; ?>">
                <button type="submit" name="remove_item" class="remove-btn">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3"><strong>Total</strong></td>
          <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
        </tr>
      </table>
      <form method="post" style="margin:0;">
        <div style="display: flex; flex-direction: column; align-items: center; margin-top: 24px;">
          <button type="submit" name="place_order" class="order-btn">Order</button>
          <a href="store.php" class="continue-shopping-link" style="margin-top:16px;">Continue Shopping</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
  <footer>
    &copy; 2025 Purple & Green Store. All rights reserved.
  </footer>
</body>
</html>