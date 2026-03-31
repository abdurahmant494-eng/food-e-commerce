<?php
session_start();
require_once 'config.php';

// Restrict access to authenticated users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please log in to view your favorites.");
    exit;
}

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Handle toggle favorite
if (isset($_POST['toggle_favorite'])) {
    $product_id = $_POST['product_id'];
    $index = array_search($product_id, $_SESSION['favorites']);
    if ($index !== false) {
        unset($_SESSION['favorites'][$index]);
    } else {
        $_SESSION['favorites'][] = $product_id;
    }
}

$cart_count = count($_SESSION['cart']);
$favorite_count = count($_SESSION['favorites']);
$favorite_items = [];
if (!empty($_SESSION['favorites'])) {
    $ids = implode(',', $_SESSION['favorites']);
    $stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    $favorite_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites - FoodRift</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php
    $page_title = "Favorites";
    include 'header.php';
    ?>
    <main>
        <h2>Your Favorites</h2>
        <?php if (empty($favorite_items)): ?>
            <div class="empty-cart">
                <p>Your favorites list is empty.</p>
            </div>
        <?php else: ?>
            <div class="products">
                <?php foreach ($favorite_items as $item): ?>
                    <div class="product">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        <h3><a href="product.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                        <form method="post" class="button-group">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="toggle_favorite" class="favorite-btn <?php echo in_array($item['id'], $_SESSION['favorites']) ? 'favorited' : ''; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
    <script src="js/favorites.js"></script>
</body>

</html>