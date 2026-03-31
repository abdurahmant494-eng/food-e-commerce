<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// Get product ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?error=Please log in to add items to your cart.");
        exit;
    }
    $product_id = $_POST['product_id'];
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

// Handle remove from cart
if (isset($_POST['remove_item'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?error=Please log in to manage your cart.");
        exit;
    }
    $product_id = $_POST['product_id'];
    $_SESSION['cart'] = array_diff($_SESSION['cart'], [$product_id]);
}

// Handle toggle favorite
if (isset($_POST['toggle_favorite'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?error=Please log in to manage your favorites.");
        exit;
    }
    $product_id = $_POST['product_id'];
    $index = array_search($product_id, $_SESSION['favorites']);
    if ($index !== false) {
        unset($_SESSION['favorites'][$index]);
    } else {
        $_SESSION['favorites'][] = $product_id;
    }
}

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindParam(':id', $product_id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if product not found
if (!$product) {
    header("Location: index.php");
    exit;
}

// Calculate cart and favorite counts
$cart_count = count($_SESSION['cart']);
$favorite_count = count($_SESSION['favorites']);

// Check if product is in cart
$is_in_cart = in_array($product_id, $_SESSION['cart']);
$is_favorite = in_array($product_id, $_SESSION['favorites']);

// Fetch related products (same category)
$stmt = $conn->prepare("SELECT * FROM products WHERE category = :category AND id != :id LIMIT 4");
$stmt->bindParam(':category', $product['category']);
$stmt->bindParam(':id', $product_id);
$stmt->execute();
$related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set page title for header
$page_title = $product['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title . ' - FoodRift'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/product.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="product-container">
            <!-- Breadcrumb Navigation -->
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <span>/</span>
                <a href="index.php?category=<?php echo urlencode($product['category']); ?>"><?php echo $product['category']; ?></a>
                <span>/</span>
                <span><?php echo $product['name']; ?></span>
            </div>

            <!-- Product Detail Section -->
            <section class="product-detail">
                <div class="product-image-container">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                </div>
                <div class="product-info">
                    <h2><?php echo $product['name']; ?></h2>
                    <div class="category-badge">
                        <i class="fas fa-tag"></i> <?php echo $product['category']; ?>
                    </div>

                    <?php if (!empty($product['origin'])): ?>
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i> Origin: <?php echo $product['origin']; ?>
                        </div>
                    <?php endif; ?>

                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>

                    <?php if (isset($product['spiciness_level']) && $product['spiciness_level'] > 0): ?>
                        <div class="spiciness-level">
                            <span>Spiciness:</span>
                            <div class="spiciness-indicator">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-pepper-hot <?php echo $i <= $product['spiciness_level'] ? 'active' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="product-meta">
                        <?php if (!empty($product['preparation_time'])): ?>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i> Prep Time: <?php echo $product['preparation_time']; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($product['serving_size'])): ?>
                            <div class="meta-item">
                                <i class="fas fa-utensils"></i> Serving: <?php echo $product['serving_size']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-description">
                        <?php echo $product['description']; ?>
                    </div>

                    <?php if (!empty($product['allergen_info'])): ?>
                        <div class="allergen-info">
                            <h4><i class="fas fa-exclamation-triangle"></i> Allergen Information</h4>
                            <p><?php echo $product['allergen_info']; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="action-buttons">
                            <?php if (!$is_in_cart): ?>
                                <form method="post" style="flex-grow: 1;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" name="add_to_cart" class="add-to-cart">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="post" style="flex-grow: 1;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" name="remove_item" class="remove-item">
                                        <i class="fas fa-trash-alt"></i> Remove from Cart
                                    </button>
                                </form>
                            <?php endif; ?>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="toggle_favorite" class="favorite-btn <?php echo $is_favorite ? 'favorited' : ''; ?>">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="login-prompt">
                            <p><i class="fas fa-lock"></i> <a href="login.php">Log in</a> to add this item to your cart or favorites.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Product Details Tabs -->
            <section class="product-details-tabs">
                <div class="tabs-nav">
                    <button class="tab-btn active" data-tab="ingredients">Ingredients</button>
                    <button class="tab-btn" data-tab="nutrition">Nutrition Facts</button>
                    <button class="tab-btn" data-tab="instructions">Cooking Instructions</button>
                </div>

                <!-- Ingredients Tab -->
                <div class="tab-content active" id="ingredients-tab">
                    <?php if (!empty($product['ingredients'])): ?>
                        <h3>Ingredients</h3>
                        <ul class="ingredients-list">
                            <?php
                            $ingredients = explode(',', $product['ingredients']);
                            foreach ($ingredients as $ingredient):
                                $ingredient = trim($ingredient);
                                if (!empty($ingredient)):
                            ?>
                                    <li><i class="fas fa-check-circle"></i> <?php echo $ingredient; ?></li>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    <?php else: ?>
                        <p>Ingredients information not available for this product.</p>
                    <?php endif; ?>
                </div>

                <!-- Nutrition Facts Tab -->
                <div class="tab-content" id="nutrition-tab">
                    <?php if (!empty($product['nutrition_facts'])): ?>
                        <h3>Nutrition Facts</h3>
                        <?php
                        // Check if nutrition facts is in JSON format
                        $nutrition = json_decode($product['nutrition_facts'], true);
                        if (is_array($nutrition)):
                        ?>
                            <table class="nutrition-table">
                                <thead>
                                    <tr>
                                        <th>Nutrient</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($nutrition as $nutrient => $value): ?>
                                        <tr>
                                            <td><?php echo $nutrient; ?></td>
                                            <td><?php echo $value; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p><?php echo nl2br($product['nutrition_facts']); ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Nutrition information not available for this product.</p>
                    <?php endif; ?>
                </div>

                <!-- Cooking Instructions Tab -->
                <div class="tab-content" id="instructions-tab">
                    <?php if (!empty($product['cooking_instructions'])): ?>
                        <h3>Cooking Instructions</h3>
                        <?php
                        // Check if instructions are in a list format
                        $instructions = explode("\n", $product['cooking_instructions']);
                        if (count($instructions) > 1):
                        ?>
                            <ol class="cooking-steps">
                                <?php foreach ($instructions as $step):
                                    $step = trim($step);
                                    if (!empty($step)):
                                ?>
                                        <li><?php echo $step; ?></li>
                                <?php
                                    endif;
                                endforeach; ?>
                            </ol>
                        <?php else: ?>
                            <p><?php echo nl2br($product['cooking_instructions']); ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Cooking instructions not available for this product.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Related Products Section -->
            <?php if (!empty($related_products)): ?>
                <section class="related-products">
                    <h3 class="section-title">You May Also Like</h3>
                    <div class="products-grid">
                        <?php foreach ($related_products as $related): ?>
                            <div class="product-card">
                                <img src="<?php echo $related['image']; ?>" alt="<?php echo $related['name']; ?>">
                                <div class="product-card-content">
                                    <h3><?php echo $related['name']; ?></h3>
                                    <div class="price">$<?php echo number_format($related['price'], 2); ?></div>
                                    <a href="product.php?id=<?php echo $related['id']; ?>" class="view-btn">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="js/product.js"></script>
</body>

</html>