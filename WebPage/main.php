<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

// --- DATABASE CONNECTION ---
function get_pdo() {
    $db_path = __DIR__ . '/database.sqlite';
    try {
        $pdo = new PDO('sqlite:' . $db_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// --- LOGIN HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password']) && basename($_SERVER['PHP_SELF']) === 'Login.php') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT wachtwoord FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['wachtwoord'])) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: store.php'); // Redirect to shop after login
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid username or password.";
        header('Location: Login.php');
        exit;
    }
}

// --- SIGNUP HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && basename($_SERVER['PHP_SELF']) === 'SignUp.php') {
    $required = ['email', 'username', 'password', 'telephone', 'country', 'state', 'zipcode', 'street', 'number'];
    $missing = false;
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $missing = true;
            break;
        }
    }
    if ($missing) {
        header("Location: SignUp.php?error=" . urlencode("Please fill in all required fields."));
        exit;
    } else {
        $pdo = get_pdo();
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO gebruikers 
                (gebruikersnaam, wachtwoord, email, telefoon, land, provincie, postcode, straatnaam, huinummer, bus) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['username'],
                $hashed_password,
                $_POST['email'],
                $_POST['telephone'],
                $_POST['country'],
                $_POST['state'],
                $_POST['zipcode'],
                $_POST['street'],
                $_POST['number'],
                $_POST['bus'] ?? ''
            ]);
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = $_POST['username'];
            header("Location: home.php");
            exit;
        } catch (PDOException $e) {
            header("Location: SignUp.php?error=" . urlencode("Signup failed: " . $e->getMessage()));
            exit;
        }
    }
}

// --- CART HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantity
    if (isset($_POST['update_qty'], $_POST['item_index'])) {
        $idx = (int)$_POST['item_index'];
        $qty = max(1, min(6, (int)$_POST['quantity']));
        if (isset($_SESSION['cart'][$idx])) {
            $item = $_SESSION['cart'][$idx];
            $pdo = get_pdo();
            $stmt = $pdo->prepare("SELECT aantal FROM producten WHERE naam = ? AND maat = ?");
            $stmt->execute([$item['name'], $item['size']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $max_stock = $row ? (int)$row['aantal'] : 1;
            $qty = min($qty, $max_stock);
            $_SESSION['cart'][$idx]['quantity'] = $qty;
        }
        header('Location: cart.php');
        exit;
    }
    // Remove item
    if (isset($_POST['remove_item'], $_POST['item_index'])) {
        $idx = (int)$_POST['item_index'];
        if (isset($_SESSION['cart'][$idx])) {
            array_splice($_SESSION['cart'], $idx, 1);
        }
        header('Location: cart.php');
        exit;
    }
    // Place order
    if (isset($_POST['place_order'])) {
        $pdo = get_pdo();
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        foreach ($cart as $item) {
            $stmt = $pdo->prepare("SELECT aantal FROM producten WHERE naam = ? AND maat = ?");
            $stmt->execute([$item['name'], $item['size']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $qty = isset($item['quantity']) ? $item['quantity'] : 1;
            if ($row) {
                $new_qty = max(0, $row['aantal'] - $qty);
                $update = $pdo->prepare("UPDATE producten SET aantal = ? WHERE naam = ? AND maat = ?");
                $update->execute([$new_qty, $item['name'], $item['size']]);
            }
        }
        $_SESSION['cart'] = [];
        header('Location: cart.php?ordered=1');
        exit;
    }
}

// --- ADD TO CART HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name']) && basename($_SERVER['PHP_SELF']) === 'store.php') {
    if (!is_logged_in()) {
        header('Location: Login.php');
        exit;
    }
    $size = isset($_POST['product_size']) ? $_POST['product_size'] : '';
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT aantal FROM producten WHERE naam = ? AND maat = ?");
    $stmt->execute([$_POST['product_name'], $size]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || $row['aantal'] <= 0) {
        // Optionally, set a session message to show "Out of stock"
        $_SESSION['cart_error'] = "This item is out of stock.";
        header('Location: store.php');
        exit;
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $_POST['product_name'] && $item['size'] === $size) {
            if ($item['quantity'] < 6 && $item['quantity'] < $row['aantal']) {
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
            'size' => $size,
            'quantity' => 1
        ];
        $_SESSION['cart'][] = $product;
    }
    header('Location: cart.php');
    exit;
}

// --- ADMIN INVENTORY HANDLING ---
function is_admin() {
    if (!is_logged_in()) return false;
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT rol FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user && $user['rol'] === 'admin';
}

function get_all_products() {
    $pdo = get_pdo();
    return $pdo->query("SELECT * FROM producten")->fetchAll(PDO::FETCH_ASSOC);
}

function restock_products() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restock'])) {
        $pdo = get_pdo();
        if (isset($_POST['stock']) && is_array($_POST['stock'])) {
            foreach ($_POST['stock'] as $product_id => $new_stock) {
                $new_stock = max(0, (int)$new_stock);
                $update = $pdo->prepare("UPDATE producten SET aantal = ? WHERE id = ?");
                $update->execute([$new_stock, $product_id]);
            }
            return true;
        }
    }
    return false;
}
?>