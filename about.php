<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

$cart_count = count($_SESSION['cart']);
$favorite_count = count($_SESSION['favorites']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - FoodRift</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php
    $page_title = "About";
    include 'header.php';
    ?>
    <main>
        <section class="about-hero">
            <h1>About Assela Grand Hotel</h1>
            <p>Experience the taste of Ethiopia in the heart of Assela City.</p>
        </section>
        <section class="about-content">
            <div class="about-section">
                <h2>Our Story</h2>
                <p>
                    Nestled in the vibrant town of Assela City, Ethiopia, Assela Grand Hotel has been a beacon of hospitality since its founding in 1995.
                    Established by the visionary Ato Kebede Alemu, the hotel started as a small family-run eatery, serving traditional Ethiopian dishes like Doro Wat and Injera to locals.
                    Over the years, it has grown into a premier destination, blending authentic Ethiopian cuisine with modern hospitality.
                </p>
                <p>
                    Our mission is to bring the rich flavors of Ethiopia to your table while providing a warm, welcoming environment.
                    Located in the heart of Assela, a city known for its lush landscapes and cultural heritage, Assela Grand Hotel is more than just a place to eat—it's a place to connect, celebrate, and create memories.
                </p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Hotel Front View">
            </div>
            <div class="about-section">
                <h2>Our Vision</h2>
                <p>
                    We aim to be the leading culinary destination in Assela City, offering an unforgettable dining experience that celebrates Ethiopia's culinary heritage.
                    By sourcing fresh, local ingredients from farmers in the Oromia Region, we ensure every dish reflects the authenticity and quality of our traditions.
                </p>
            </div>
        </section>
        <section class="testimonials">
            <h2 class="testimonial-meet-header">What Our Guests Say</h2>
            <div class="testimonial-grid">
                <div class="testimonial">
                    <p>"The best Doro Wat I’ve ever had! The staff at Assela Grand Hotel made us feel like family. Can’t wait to come back!"</p>
                    <h4>- Selamawit Tadesse, Addis Ababa</h4>
                </div>
                <div class="testimonial">
                    <p>"A hidden gem in Assela! The ambiance, the food, and the hospitality are unmatched. Highly recommend the Kitfo."</p>
                    <h4>- James Carter, UK</h4>
                </div>
                <div class="testimonial">
                    <p>"I hosted my wedding dinner here, and it was perfect. The team went above and beyond to make our day special."</p>
                    <h4>- Fatuma Ahmed, Assela</h4>
                </div>
            </div>
        </section>
        <section class="staff">
            <h2 class="testimonial-meet-header">Meet Our Team</h2>
            <div class="staff-grid">
                <div class="staff-member">
                    <h3>Ato Kebede Alemu</h3>
                    <p class="role">Founder & Owner</p>
                    <p>With over 30 years in the hospitality industry, Ato Kebede ensures every guest experiences the true essence of Ethiopian hospitality.</p>
                </div>
                <div class="staff-member">
                    <h3>W/ro Muluwork Tsegaye</h3>
                    <p class="role">Head Chef</p>
                    <p>Chef Muluwork brings her passion for traditional recipes to life, crafting dishes that honor Ethiopia’s culinary heritage.</p>
                </div>
                <div class="staff-member">
                    <h3>Ato Yohannes Bekele</h3>
                    <p class="role">General Manager</p>
                    <p>Yohannes oversees daily operations, ensuring every detail is perfect for our guests.</p>
                </div>
                <div class="staff-member">
                    <h3>W/t Eyerusalem Desta</h3>
                    <p class="role">Customer Relations</p>
                    <p>Eyerusalem is dedicated to making every guest’s experience memorable with her warm smile and attentive service.</p>
                </div>
            </div>
        </section>
        <section class="location">
            <h2>Visit Us</h2>
            <p>Assela Grand Hotel is located at the heart of Assela City, Oromia Region, Ethiopia.</p>
            <p><strong>Address:</strong> Main Street, Assela City, Ethiopia</p>
            <p><strong>Email:</strong> contact@asselagrandhotel.com</p>
            <p><strong>Phone:</strong> +251 912 345 678</p>
            <div class="map-placeholder">
                <p>[Map Placeholder: Assela City, Ethiopia]</p>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    <script>
        document.getElementById('hamburger-btn').addEventListener('click', function() {
            document.getElementById('nav-menu').classList.toggle('open');
        });
    </script>
</body>

</html>