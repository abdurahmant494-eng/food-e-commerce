<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

$cart_count = count($_SESSION['cart']);
$favorite_count = count($_SESSION['favorites']);

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Check if user is admin
require_once 'config.php';
$is_admin = false;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $is_admin = $user['role'] === 'admin';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - FoodRift' : 'FoodRift - Delicious Food Delivered'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/header.css">
    <?php if (isset($additional_styles)): ?>
        <link rel="stylesheet" href="css/admin.css">
    <?php endif; ?>
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <h1>FoodRift</h1>
            </div>
            <button class="hamburger" id="hamburger-btn">☰</button>
            <nav id="nav-menu">
                <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="about.php" class="<?php echo $current_page == 'about.php' ? 'active' : ''; ?>">
                    <i class="fas fa-info-circle"></i> About
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="cart.php" class="<?php echo $current_page == 'cart.php' ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i>
                        Cart <span class="cart-count"><?php echo $cart_count; ?></span>
                    </a>
                    <a href="favorites.php" class="<?php echo $current_page == 'favorites.php' ? 'active' : ''; ?>">
                        <i class="fas fa-heart"></i>
                        Favorites <span class="favorite-count"><?php echo $favorite_count; ?></span>
                    </a>
                    <a href="orders.php" class="<?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                        <i class="fas fa-list-alt"></i> Orders
                    </a>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <?php if ($is_admin): ?>
                        <a href="admin.php" class="<?php echo $current_page == 'admin.php' ? 'active' : ''; ?>">
                            <i class="fas fa-user-shield"></i> Admin Dashboard
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="<?php echo $current_page == 'login.php' ? 'active' : ''; ?>">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="register.php" class="<?php echo $current_page == 'register.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                <?php endif; ?>
            </nav>
            <div class="search-bar">
                <form method="get" action="index.php">
                    <input type="text" name="search" placeholder="Search food..."
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>
    <script>
        document.getElementById('hamburger-btn').addEventListener('click', function() {
            document.getElementById('nav-menu').classList.toggle('open');
        });
    </script>
</body>

</html>