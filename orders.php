<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please log in to view your orders.");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_count = count($_SESSION['cart'] ?? []);
$favorite_count = count($_SESSION['favorites'] ?? []);

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - FoodRift</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/orders.css">
</head>

<body>
    <?php
    $page_title = "My orders";
    include 'header.php';
    ?>
    <main>
        <div class="orders-container">
            <h2 class="page-title">Your Order History</h2>

            <?php if (empty($orders)): ?>
                <div class="empty-orders">
                    <i class="fas fa-shopping-bag"></i>
                    <p>You haven't placed any orders yet.</p>
                    <a href="index.php" class="shop-now-btn">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="orders-grid">
                    <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <h3>Order #<?php echo $order['id']; ?></h3>
                                <span class="status-badge <?php echo strtolower($order['payment_status']); ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                            <div class="order-info">
                                <p><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                                <p><i class="fas fa-clock"></i> <strong>Time:</strong> <?php echo date('g:i A', strtotime($order['created_at'])); ?></p>
                                <p><i class="fas fa-dollar-sign"></i> <strong>Total:</strong> ETB<?php echo number_format($order['total_amount'], 2); ?></p>
                            </div>
                            <div class="order-items-section">
                                <h4><i class="fas fa-utensils"></i> Items Ordered</h4>
                                <ul class="order-items-list">
                                    <?php
                                    $items_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
                                    $items_stmt->bindParam(':order_id', $order['id']);
                                    $items_stmt->execute();
                                    $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($items as $item): ?>
                                        <li>
                                            <?php echo $item['product_name']; ?>
                                            <span class="item-meta">ETB <?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="js/orders.js"></script>
</body>

</html>