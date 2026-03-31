<?php
// Get categories for footer if not already available
if (!isset($categories) || empty($categories)) {
    require_once 'config.php';
    $cat_stmt = $conn->query("SELECT DISTINCT category FROM products LIMIT 5");
    $categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>
<link rel="stylesheet" href="css/footer.css">
<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>FoodRift</h3>
            <p>Your one-stop shop for delicious meals delivered right to your doorstep.</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <a href="index.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="cart.php">Cart</a>
            <a href="favorites.php">Favorites</a>
            <a href="orders.php">Orders</a>
        </div>
        <div class="footer-section">
            <h3>Categories</h3>
            <?php foreach ($categories as $category): ?>
                <a href="index.php?category=<?php echo urlencode($category); ?>"><?php echo $category; ?></a>
            <?php endforeach; ?>
        </div>
        <div class="footer-section">
            <h3>Contact Us</h3>
            <p><i class="fas fa-envelope"></i> support@foodrift.com</p>
            <p><i class="fas fa-phone"></i> (123) 456-7890</p>
            <p><i class="fas fa-map-marker-alt"></i> around police station</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2025 FoodRift. All rights reserved.</p>
    </div>
</footer>
<script>
    // Hamburger menu toggle
    document.getElementById('hamburger-btn').addEventListener('click', function() {
        document.getElementById('nav-menu').classList.toggle('open');
    });
</script>
</body>

</html>