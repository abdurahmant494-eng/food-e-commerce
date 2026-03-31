<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT role FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch all products and users for display
$products = $conn->query("SELECT id, name, price FROM products")->fetchAll(PDO::FETCH_ASSOC);
$users = $conn->query("SELECT id, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Handle product creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_product') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $image = trim($_POST['image']);
    $price = trim($_POST['price']);
    $ingredients = trim($_POST['ingredients']);
    $serving_size = trim($_POST['serving_size']);
    $preparation_time = trim($_POST['preparation_time']);
    $cooking_instructions = trim($_POST['cooking_instructions']);
    $spiciness_level = trim($_POST['spiciness_level']);
    $allergen_info = trim($_POST['allergen_info']);
    $nutrition_facts = trim($_POST['nutrition_facts']);
    $origin = trim($_POST['origin']);

    if (!empty($name) && !empty($description) && !empty($price)) {
        $stmt = $conn->prepare("INSERT INTO products (name, category, image, description, price, ingredients, serving_size, preparation_time, cooking_instructions, spiciness_level, allergen_info, nutrition_facts, origin) VALUES (:name, :category, :image, :description, :price, :ingredients, :serving_size, :preparation_time, :cooking_instructions, :spiciness_level, :allergen_info, :nutrition_facts, :origin)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':serving_size', $serving_size);
        $stmt->bindParam(':preparation_time', $preparation_time);
        $stmt->bindParam(':cooking_instructions', $cooking_instructions);
        $stmt->bindParam(':spiciness_level', $spiciness_level, PDO::PARAM_INT);
        $stmt->bindParam(':allergen_info', $allergen_info);
        $stmt->bindParam(':nutrition_facts', $nutrition_facts);
        $stmt->bindParam(':origin', $origin);

        if ($stmt->execute()) {
            $success = "Product created successfully!";
            header("Location: admin.php");
            exit;
        } else {
            $error = "Failed to create product. " . implode(" ", $stmt->errorInfo());
        }
    } else {
        $error = "Name, description, and price are required.";
    }
}

// Handle product update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_product') {
    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $ingredients = trim($_POST['ingredients']);
    $serving_size = trim($_POST['serving_size']);
    $preparation_time = trim($_POST['preparation_time']);
    $cooking_instructions = trim($_POST['cooking_instructions']);
    $spiciness_level = trim($_POST['spiciness_level']);
    $allergen_info = trim($_POST['allergen_info']);
    $nutrition_facts = trim($_POST['nutrition_facts']);
    $origin = trim($_POST['origin']);

    if (!empty($id) && !empty($name) && !empty($description) && !empty($price)) {
        $stmt = $conn->prepare("UPDATE products SET name = :name, description = :description, price = :price, ingredients = :ingredients, serving_size = :serving_size, preparation_time = :preparation_time, cooking_instructions = :cooking_instructions, spiciness_level = :spiciness_level, allergen_info = :allergen_info, nutrition_facts = :nutrition_facts, origin = :origin WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':serving_size', $serving_size);
        $stmt->bindParam(':preparation_time', $preparation_time);
        $stmt->bindParam(':cooking_instructions', $cooking_instructions);
        $stmt->bindParam(':spiciness_level', $spiciness_level, PDO::PARAM_INT);
        $stmt->bindParam(':allergen_info', $allergen_info);
        $stmt->bindParam(':nutrition_facts', $nutrition_facts);
        $stmt->bindParam(':origin', $origin);

        if ($stmt->execute()) {
            $success = "Product updated successfully!";
            header("Location: admin.php");
            exit;
        } else {
            $error = "Failed to update product.";
        }
    } else {
        $error = "ID, name, description, and price are required.";
    }
}

// Handle product/user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $type = $_POST['type'];
    $id = $_POST['id'];

    if ($type === 'product') {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    } elseif ($type === 'user') {
        if ($id == $_SESSION['user_id']) {
            $error = "You cannot delete your own account.";
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        }
    }

    if (!isset($error)) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $success = ($type === 'product' ? 'Product' : 'User') . " deleted successfully!";
            header("Location: admin.php");
            exit;
        } else {
            $error = "Failed to delete " . ($type === 'product' ? 'product' : 'user') . ".";
        }
    }
}

// Handle product edit (fetch data for form)
$edit_product = null;
if (isset($_GET['edit_product'])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $_GET['edit_product'], PDO::PARAM_INT);
    $stmt->execute();
    $edit_product = $stmt->fetch(PDO::FETCH_ASSOC);
}

$page_title = "Admin Dashboard";

include 'header.php';
?>

<link rel="stylesheet" href="css/admin.css">

<main>
    <div class="admin-container">
        <!-- Admin Header -->
        <div class="admin-header">
            <div>
                <h2><i class="fas fa-user-shield"></i> Admin Dashboard</h2>
                <p>Manage your products, users, and site settings</p>
            </div>
        </div>

        <!-- Admin Stats -->
        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="stat-number"><?php echo count($products); ?></div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo count($users); ?></div>
                <div class="stat-label">Registered Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-number">
                    <?php
                    $order_count = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
                    echo $order_count;
                    ?>
                </div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Admin Tabs -->
        <div class="admin-tabs">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="products"><i class="fas fa-utensils"></i> Products</button>
                <button class="tab-btn" data-tab="users"><i class="fas fa-users"></i> Users</button>
                <button class="tab-btn" data-tab="add-product"><i class="fas fa-plus-circle"></i> Add Product</button>
                <?php if ($edit_product): ?>
                    <button class="tab-btn" data-tab="edit-product"><i class="fas fa-edit"></i> Edit Product</button>
                <?php endif; ?>
                <button class="tab-btn" data-tab="delete"><i class="fas fa-trash-alt"></i> Delete Items</button>
            </div>

            <!-- Products Tab -->
            <div class="tab-content active" id="products-tab">
                <h3 class="section-title"><i class="fas fa-utensils"></i> Manage Products</h3>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($products) > 0): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td>ETB <?php echo number_format($product['price'], 2); ?></td>
                                        <td>
                                            <a href="admin.php?edit_product=<?php echo $product['id']; ?>" class="action-btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="product.php?id=<?php echo $product['id']; ?>" class="action-btn" style="background: #e8f5e9; color: #28a745;"><i class="fas fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No products found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Users Tab -->
            <div class="tab-content" id="users-tab">
                <h3 class="section-title"><i class="fas fa-users"></i> Manage Users</h3>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($users) > 0): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="meta-item" style="background: <?php echo $user['role'] === 'admin' ? '#e3f2fd' : '#f8f9fa'; ?>; color: <?php echo $user['role'] === 'admin' ? '#0d6efd' : '#6c757d'; ?>;">
                                                <?php echo ucfirst(htmlspecialchars($user['role'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="type" value="user">
                                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="meta-item" style="background: #f8f9fa; color: #6c757d;">Current User</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Product Tab -->
            <div class="tab-content" id="add-product-tab">
                <h3 class="section-title"><i class="fas fa-plus-circle"></i> Add New Product</h3>
                <form method="post" class="admin-form">
                    <input type="hidden" name="action" value="create_product">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-tag"></i> Product Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="price"><i class="fas fa-dollar-sign"></i> Price</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left"></i> Description</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ingredients"><i class="fas fa-list"></i> Ingredients</label>
                            <textarea id="ingredients" name="ingredients" placeholder="Comma separated list of ingredients"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cooking_instructions"><i class="fas fa-clipboard-list"></i> Cooking Instructions</label>
                            <textarea id="cooking_instructions" name="cooking_instructions" placeholder="Step by step instructions"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="serving_size"><i class="fas fa-utensils"></i> Serving Size</label>
                            <input type="text" id="serving_size" name="serving_size" placeholder="e.g., 2 people">
                        </div>
                        <div class="form-group">
                            <label for="preparation_time"><i class="fas fa-clock"></i> Preparation Time</label>
                            <input type="text" id="preparation_time" name="preparation_time" placeholder="e.g., 30 minutes">
                        </div>
                        <div class="form-group">
                            <label for="spiciness_level"><i class="fas fa-pepper-hot"></i> Spiciness Level (0-5)</label>
                            <input type="number" id="spiciness_level" name="spiciness_level" min="0" max="5" value="0">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="allergen_info"><i class="fas fa-exclamation-triangle"></i> Allergen Info</label>
                            <textarea id="allergen_info" name="allergen_info" placeholder="List potential allergens"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="nutrition_facts"><i class="fas fa-apple-alt"></i> Nutrition Facts</label>
                            <textarea id="nutrition_facts" name="nutrition_facts" placeholder="Nutritional information"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category"><i class="fas fa-arrow"></i> Category</label>
                            <input type="text" id="category" name="category" placeholder="desert ...">
                        </div>
                        <div class="form-group">
                            <label for="image"><i class="fas fa-camera"></i> Image URL</label>
                            <input type="text" id="image" name="image" placeholder="Enter the image URL: https://....">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="origin"><i class="fas fa-map-marker-alt"></i> Origin</label>
                        <input type="text" id="origin" name="origin" placeholder="e.g., Italian, Mexican, etc.">
                    </div>

                    <button type="submit" class="admin-btn">
                        <i class="fas fa-plus-circle"></i> Create Product
                    </button>
                </form>
            </div>

            <!-- Edit Product Tab -->
            <?php if ($edit_product): ?>
                <div class="tab-content" id="edit-product-tab">
                    <h3 class="section-title"><i class="fas fa-edit"></i> Edit Product #<?php echo $edit_product['id']; ?></h3>
                    <form method="post" class="admin-form">
                        <input type="hidden" name="action" value="update_product">
                        <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="update_name"><i class="fas fa-tag"></i> Product Name</label>
                                <input type="text" id="update_name" name="name" value="<?php echo htmlspecialchars($edit_product['name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="update_price"><i class="fas fa-dollar-sign"></i> Price</label>
                                <input type="number" id="update_price" name="price" step="0.01" value="<?php echo $edit_product['price']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="update_description"><i class="fas fa-align-left"></i> Description</label>
                            <textarea id="update_description" name="description" required><?php echo htmlspecialchars($edit_product['description']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="update_ingredients"><i class="fas fa-list"></i> Ingredients</label>
                                <textarea id="update_ingredients" name="ingredients" placeholder="Comma separated list of ingredients"><?php echo htmlspecialchars($edit_product['ingredients']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="update_cooking_instructions"><i class="fas fa-clipboard-list"></i> Cooking Instructions</label>
                                <textarea id="update_cooking_instructions" name="cooking_instructions" placeholder="Step by step instructions"><?php echo htmlspecialchars($edit_product['cooking_instructions']); ?></textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="update_serving_size"><i class="fas fa-utensils"></i> Serving Size</label>
                                <input type="text" id="update_serving_size" name="serving_size" placeholder="e.g., 2 people" value="<?php echo htmlspecialchars($edit_product['serving_size']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="update_preparation_time"><i class="fas fa-clock"></i> Preparation Time</label>
                                <input type="text" id="update_preparation_time" name="preparation_time" placeholder="e.g., 30 minutes" value="<?php echo htmlspecialchars($edit_product['preparation_time']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="update_spiciness_level"><i class="fas fa-pepper-hot"></i> Spiciness Level (0-5)</label>
                                <input type="number" id="update_spiciness_level" name="spiciness_level" min="0" max="5" value="<?php echo $edit_product['spiciness_level']; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="update_allergen_info"><i class="fas fa-exclamation-triangle"></i> Allergen Info</label>
                                <textarea id="update_allergen_info" name="allergen_info" placeholder="List potential allergens"><?php echo htmlspecialchars($edit_product['allergen_info']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="update_nutrition_facts"><i class="fas fa-apple-alt"></i> Nutrition Facts</label>
                                <textarea id="update_nutrition_facts" name="nutrition_facts" placeholder="Nutritional information"><?php echo htmlspecialchars($edit_product['nutrition_facts']); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="update_origin"><i class="fas fa-map-marker-alt"></i> Origin</label>
                            <input type="text" id="update_origin" name="origin" placeholder="e.g., Italian, Mexican, etc." value="<?php echo htmlspecialchars($edit_product['origin']); ?>">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="admin-btn">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="admin.php" class="admin-btn secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Delete Items Tab -->
            <div class="tab-content" id="delete-tab">
                <h3 class="section-title"><i class="fas fa-trash-alt"></i> Delete Items</h3>

                <div class="card-container">
                    <div class="action-card">
                        <h3><i class="fas fa-utensils"></i> Delete Product</h3>
                        <p>Remove a product from the database. This action cannot be undone.</p>
                        <form method="post" class="admin-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="type" value="product">
                            <div class="form-group">
                                <label for="product_id">Select Product</label>
                                <select id="product_id" name="id" required>
                                    <option value="">-- Select a product --</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?php echo $product['id']; ?>"><?php echo $product['id'] . ' - ' . htmlspecialchars($product['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="admin-btn" onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="fas fa-trash-alt"></i> Delete Product
                            </button>
                        </form>
                    </div>

                    <div class="action-card">
                        <h3><i class="fas fa-users"></i> Delete User</h3>
                        <p>Remove a user from the database. This action cannot be undone.</p>
                        <form method="post" class="admin-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="type" value="user">
                            <div class="form-group">
                                <label for="user_id">Select User</label>
                                <select id="user_id" name="id" required>
                                    <option value="">-- Select a user --</option>
                                    <?php foreach ($users as $user): ?>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <option value="<?php echo $user['id']; ?>"><?php echo $user['id'] . ' - ' . htmlspecialchars($user['email']); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="admin-btn" onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="fas fa-trash-alt"></i> Delete User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="js/admin.js"></script>