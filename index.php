<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

if (isset($_POST['toggle_favorite'])) {
    $product_id = $_POST['product_id'];
    $index = array_search($product_id, $_SESSION['favorites']);
    if ($index !== false) {
        unset($_SESSION['favorites'][$index]);
    } else {
        $_SESSION['favorites'][] = $product_id;
    }
}

$cat_stmt = $conn->query("SELECT DISTINCT category FROM products");
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = [];
$params = [];

if ($selected_category) {
    $where[] = "category = :category";
    $params[':category'] = $selected_category;
}

if ($search_term) {
    $where[] = "(name LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search_term%";
}

$where_clause = $where ? "WHERE " . implode(" AND ", $where) : "";
$stmt = $conn->prepare("SELECT * FROM products $where_clause");
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cart_count = count($_SESSION['cart']);
$favorite_count = count($_SESSION['favorites']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodRift - Delicious Food Delivered</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php
    $page_title = "Home page";
    include 'header.php';
    ?>
    <main>
        <!-- Carousel Section -->
        <section class="carousel">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="https://plus.unsplash.com/premium_photo-1661964071015-d97428970584?q=80&w=1920&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="our hotel">
                        <div class="slide-content">
                            <h2>Delicious Food Delivered to Your Door</h2>
                            <p>Experience the finest cuisine from the comfort of your home. Order now and enjoy our special offers!</p>
                            <a href="#menu" class="cta-button">Explore Menu</a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="https://plus.unsplash.com/premium_photo-1733259677103-fbb5d70ef830?q=80&w=1940&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="our meal table">
                        <div class="slide-content">
                            <h2>Fresh Ingredients, Exceptional Taste</h2>
                            <p>We use only the freshest ingredients to create mouthwatering dishes that will satisfy your cravings.</p>
                            <a href="#menu" class="cta-button">View Menu</a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="https://plus.unsplash.com/premium_photo-1661526833843-248a2f8fe129?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="our delivery">
                        <div class="slide-content">
                            <h2>Fast & Reliable Delivery</h2>
                            <p>Our dedicated delivery team ensures your food arrives hot and fresh, right when you need it.</p>
                            <a href="#menu" class="cta-button">Order Now</a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="https://images.unsplash.com/photo-1616734755909-bb016ce64930?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="our kitchen">
                        <div class="slide-content">
                            <h2>Crafted with Passion</h2>
                            <p>Our chefs put their heart and soul into every dish, creating culinary masterpieces just for you.</p>
                            <a href="#menu" class="cta-button">Discover More</a>
                        </div>
                    </div>
                </div>
                <!-- Pagination dots -->
                <div class="swiper-pagination"></div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="features-container">
                <div class="features-title">
                    <h2>Why Choose FoodRift?</h2>
                    <p>We're committed to providing the best food delivery experience</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h3>Fast Delivery</h3>
                        <p>We deliver your food within 30 minutes of ordering, ensuring it arrives hot and fresh.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Quality Food</h3>
                        <p>Our dishes are prepared using the freshest ingredients for exceptional taste and quality.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Easy Ordering</h3>
                        <p>Order with just a few clicks through our user-friendly website or mobile app.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Our customer service team is available around the clock to assist you with any queries.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="menu">
            <div class="menu-header">
                <h2>Our Menu</h2>
                <div class="filter">
                    <form method="get">
                        <select name="category" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category; ?>" <?php echo $selected_category === $category ? 'selected' : ''; ?>>
                                    <?php echo $category; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="products">
                <?php if (empty($products)): ?>
                    <p class="empty-results">No items found. Try a different search or category.</p>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <div class="product-content">
                                <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h3>
                                <p class="description"><?php echo $product['description']; ?></p>
                                <p class="price">ETB <?php echo number_format($product['price'], 2); ?></p>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form method="post" class="button-group">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
                                        <button type="submit" name="toggle_favorite" class="favorite-btn <?php echo in_array($product['id'], $_SESSION['favorites']) ? 'favorited' : ''; ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <p><a href="login.php">Log in</a> to add to cart or favorites.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/index.js"></script>
</body>

</html>