<?php
session_start();
require_once 'config.php';

// Restrict access to authenticated users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please log in to view your cart.");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Handle remove item
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    $_SESSION['cart'] = array_diff($_SESSION['cart'], [$product_id]);
}

// Handle remove all
if (isset($_POST['remove_all'])) {
    $_SESSION['cart'] = [];
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

// Handle Stripe Checkout
if (isset($_POST['pay_out']) && !empty($_SESSION['cart'])) {
    // Fetch cart items
    $ids = implode(',', $_SESSION['cart']);
    $stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare line items for Stripe Checkout
    $line_items = [];
    foreach ($cart_items as $item) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'ETB',
                'product_data' => [
                    'name' => $item['name'],
                ],
                'unit_amount' => $item['price'] * 100, // Stripe expects amount in cents
            ],
            'quantity' => 1,
        ];
    }

    // Create Stripe Checkout session
    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost/gedion_php_food_ecommerce/success.php',
            'cancel_url' => 'http://localhost/food-ecommerce/cancel.php',
        ]);

        // Redirect to Stripe Checkout
        header("Location: " . $checkout_session->url);
        exit;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "Stripe Error: " . $e->getMessage();
        exit;
    }
}

// Calculate cart count, total price, and favorite count
$cart_count = count($_SESSION['cart']);
$total_price = 0;
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', $_SESSION['cart']);
    $stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cart_items as $item) {
        $total_price += $item['price'];
    }
}
$favorite_count = count($_SESSION['favorites']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - FoodRift</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php
    $page_title = "Cart";
    include 'header.php';
    ?>
    <main>
        <h2>Your Cart</h2>
        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <p>Your cart is empty.</p>
            </div>
        <?php else: ?>
            <form method="post" style="text-align: center; margin-bottom: 1rem;">
                <button type="submit" name="remove_all" class="remove-all">Remove All</button>
            </form>
            <div class="products">
                <?php foreach ($cart_items as $item): ?>
                    <div class="product">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        <h3><a href="product.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove_item" class="remove-item">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-total">
                <p>Total: ETB <span id="total-price"><?php echo number_format($total_price, 2); ?></span></p>
                <form method="post">
                    <button type="submit" name="pay_out" class="pay-out">Pay Out</button>
                </form>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['favorites'])): ?>
            <h2>Your Favorites</h2>
            <div class="products">
                <?php
                $favorite_ids = implode(',', $_SESSION['favorites']);
                $fav_stmt = $conn->query("SELECT * FROM products WHERE id IN ($favorite_ids)");
                $favorite_items = $fav_stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($favorite_items as $item): ?>
                    <div class="product">
                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                        <h3><a href="product.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                        <p class="description"><?php echo $item['description']; ?></p>
                        <p class="price">ETB <?php echo $item['price']; ?></p>
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
    <script src="js/cart.js"></script>
</body>

</html>