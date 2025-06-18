<?php
include 'main.php';
$pdo = get_pdo();
function get_stock($name, $size = 'one size') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT aantal FROM producten WHERE naam = ? AND maat = ?");
    $stmt->execute([$name, $size]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['aantal'] : 0;
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $_POST['product_name']) {
            if ($item['quantity'] < 6) {
                $item['quantity'] += 1;
            }
            $found = true;
            break;
        }
    }
    unset($item);
    if (!$found) {
        $product = [
            'name' => $_POST['product_name'],
            'price' => $_POST['product_price'],
            'quantity' => 1
        ];
        $_SESSION['cart'][] = $product;
    }
    header('Location: cart.php');
    exit;
}
?>
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
    <div class="store-items">
      <!-- Green Hat -->
      <div class="item">
        <img src="pngs/hat.png" alt="Green Hat">
        <h2>Green Hat</h2>
        <p>Stylish black hat with green accents.</p>
        <span class="price">$15.99</span>
        <?php $hat_stock = get_stock('Green Hat', 'one size'); ?>
        <form method="post">
          <input type="hidden" name="product_name" value="Green Hat">
          <input type="hidden" name="product_price" value="15.99">
          <input type="hidden" name="product_size" value="one size">
          <button type="submit" class="add-to-cart-btn" <?php echo $hat_stock <= 0 ? 'disabled' : ''; ?>>
            <?php echo $hat_stock <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
          </button>
          <span style="margin-left:10px;">Stock: <?php echo $hat_stock; ?></span>
        </form>
      </div>

      <!-- Green Shirt -->
      <div class="item">
        <img src="pngs/shirt.png" alt="Green Shirt">
        <h2>Green Shirt</h2>
        <p>Black shirt covered with green accents.</p>
        <span class="price">$22.50</span>
        <form method="post">
          <input type="hidden" name="product_name" value="Green Shirt">
          <input type="hidden" name="product_price" value="22.50">
          <label for="shirt-size">Size:</label>
          <select name="product_size" id="shirt-size" required onchange="this.form.submit()">
            <?php foreach (['S', 'M', 'L'] as $size): ?>
              <option value="<?php echo $size; ?>" <?php if (isset($_POST['product_size']) && $_POST['product_size'] === $size) echo 'selected'; ?>>
                <?php echo $size; ?> (<?php echo get_stock('Green Shirt', $size); ?> in stock)
              </option>
            <?php endforeach; ?>
          </select>
          <?php
            $selected_size = isset($_POST['product_size']) ? $_POST['product_size'] : 'S';
            $shirt_stock = get_stock('Green Shirt', $selected_size);
          ?>
          <button type="submit" class="add-to-cart-btn" <?php echo $shirt_stock <= 0 ? 'disabled' : ''; ?>>
            <?php echo $shirt_stock <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
          </button>
          
        </form>
      </div>

      <!-- White Mug -->
      <div class="item">
        <img src="pngs/mug.png" alt="White Mug">
        <h2>White Mug</h2>
        <p>Enjoy your drink in style with this mug.</p>
        <span class="price">$9.99</span>
        <?php $mug_stock = get_stock('White Mug', 'one size'); ?>
        <form method="post">
          <input type="hidden" name="product_name" value="White Mug">
          <input type="hidden" name="product_price" value="9.99">
          <input type="hidden" name="product_size" value="one size">
          <button type="submit" class="add-to-cart-btn" <?php echo $mug_stock <= 0 ? 'disabled' : ''; ?>>
            <?php echo $mug_stock <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
          </button>
          <span style="margin-left:10px;">Stock: <?php echo $mug_stock; ?></span>
        </form>
      </div>

      <!-- Green Bag -->
      <div class="item">
        <img src="pngs/bag.png" alt="Green Bag">
        <h2>Green Bag</h2>
        <p>Eco-friendly green bag.</p>
        <span class="price">$12.99</span>
        <form method="post">
          <input type="hidden" name="product_name" value="Green Bag">
          <input type="hidden" name="product_price" value="12.99">
          <label for="bag-size">Size:</label>
          <select name="product_size" id="bag-size" required onchange="this.form.submit()">
            <?php foreach (['S', 'M'] as $size): ?>
              <option value="<?php echo $size; ?>" <?php if (isset($_POST['product_size']) && $_POST['product_size'] === $size) echo 'selected'; ?>>
                <?php echo $size; ?> (<?php echo get_stock('Green Bag', $size); ?> in stock)
              </option>
            <?php endforeach; ?>
          </select>
          <?php
            $selected_bag_size = isset($_POST['product_size']) ? $_POST['product_size'] : 'S';
            $bag_stock = get_stock('Green Bag', $selected_bag_size);
          ?>
          <button type="submit" class="add-to-cart-btn" <?php echo $bag_stock <= 0 ? 'disabled' : ''; ?>>
            <?php echo $bag_stock <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
          </button>
          
        </form>
      </div>

      <!-- Green Notebook -->
      <div class="item">
        <img src="pngs/notebook.png" alt="Green Notebook">
        <h2>Green Notebook</h2>
        <p>Take notes in this stylish green notebook.</p>
        <span class="price">$7.50</span>
        <?php $notebook_stock = get_stock('Notebook', 'one size'); ?>
        <form method="post">
          <input type="hidden" name="product_name" value="Green Notebook">
          <input type="hidden" name="product_price" value="7.50">
          <input type="hidden" name="product_size" value="one size">
          <button type="submit" class="add-to-cart-btn" <?php echo $notebook_stock <= 0 ? 'disabled' : ''; ?>>
            <?php echo $notebook_stock <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
          </button>
          <span style="margin-left:10px;">Stock: <?php echo $notebook_stock; ?></span>
        </form>
      </div>
    </div>
  </div>
  <footer>
    &copy; 2025 Purple & Green Store. All rights reserved.
  </footer>
</body>
</html>