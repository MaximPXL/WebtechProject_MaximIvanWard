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
        header('Location: home.php');
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
?>