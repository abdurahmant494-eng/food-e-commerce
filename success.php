<?php
session_start();
require_once 'config.php';

// Restrict access to authenticated users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please log in to complete your purchase.");
    exit;
}

$user_id = $_SESSION['user_id'];

// Save order history
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', $_SESSION['cart']);
    $stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_amount = 0;
    foreach ($cart_items as $item) {
        $total_amount += $item['price'];
    }

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_status) VALUES (:user_id, :total_amount, 'completed')");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->execute();
    $order_id = $conn->lastInsertId();

    // Insert order items
    foreach ($cart_items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (:order_id, :product_id, :product_name, :price, 1)");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':product_id', $item['id']);
        $stmt->bindParam(':product_name', $item['name']);
        $stmt->bindParam(':price', $item['price']);
        $stmt->execute();
    }

    // Clear the cart
    $_SESSION['cart'] = [];
}

$cart_count = count($_SESSION['cart']);
$favorite_count = count($_SESSION['favorites']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - FoodRift</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php
    $page_title = "Success";
    include 'header.php';
    ?>
    <main>
        <div class="payment-message">
            <h2>Payment Successful!</h2>
            <p>Thank you for your purchase. Your order has been processed successfully.</p>
            <a href="orders.php" class="back-to-home">View Orders</a>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        document.getElementById('hamburger-btn').addEventListener('click', function() {
            document.getElementById('nav-menu').classList.toggle('open');
        });
    </script>
</body>

</html>