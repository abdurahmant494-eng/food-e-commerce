<?php
// Database configuration
$host = 'localhost';
$dbname = 'food_ecommerce';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Stripe configuration
require_once 'vendor/autoload.php'; // Load Composer autoloader for Stripe

// Replace these with your Stripe API keys
$stripeSecretKey = 'sk_test_51RO6LwGhx5Rez3gHz3m8J6swTY8jMsUHNO34lOwWHOqZk9StdUbkDcqj1QYXOjUk7lazsAYEu8zyow6YW1SPieTi00REkJ3I1k'; // Your Stripe Secret Key

// Initialize Stripe
\Stripe\Stripe::setApiKey($stripeSecretKey);
?>