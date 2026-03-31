-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 11:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('completed','cancelled','pending') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `payment_status`, `created_at`) VALUES
(1, 1, 5.99, 'completed', '2025-05-13 00:50:55'),
(2, 1, 36.96, 'completed', '2025-05-13 00:51:59'),
(3, 1, 15.98, 'completed', '2025-05-13 22:43:11'),
(4, 4, 9.99, 'completed', '2025-05-14 05:22:01'),
(5, 1, 1539.00, 'completed', '2025-05-15 18:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(1, 1, 5, 'Cheeseburger', 5.99, 1),
(2, 2, 4, 'Margherita Pizza', 9.99, 1),
(3, 2, 5, 'Cheeseburger', 5.99, 1),
(4, 2, 6, 'Fettuccine Alfredo', 7.99, 1),
(5, 2, 7, 'Sushi Platter', 12.99, 1),
(6, 3, 4, 'Margherita Pizza', 9.99, 1),
(7, 3, 5, 'Cheeseburger', 5.99, 1),
(8, 4, 4, 'Margherita Pizza update test', 9.99, 1),
(9, 5, 4, 'Margherita Pizza updated', 80.00, 1),
(10, 5, 5, 'Cheeseburger', 350.00, 1),
(11, 5, 14, 'Spicy Thai Chicken Curry', 230.00, 1),
(12, 5, 16, 'Creamy Italian Mushroom Risotto', 879.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` text DEFAULT NULL,
  `ingredients` text DEFAULT NULL,
  `serving_size` varchar(50) DEFAULT NULL,
  `preparation_time` varchar(20) DEFAULT NULL,
  `cooking_instructions` text DEFAULT NULL,
  `spiciness_level` int(11) DEFAULT NULL,
  `allergen_info` text DEFAULT NULL,
  `nutrition_facts` text DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category`, `ingredients`, `serving_size`, `preparation_time`, `cooking_instructions`, `spiciness_level`, `allergen_info`, `nutrition_facts`, `origin`) VALUES
(4, 'Margherita Pizza', 80.00, 'Margherita Pizza is a classic Italian dish known for its simple yet flavorful ingredients. It features a thin, crispy crust topped with a rich tomato sauce, fresh mozzarella cheese, and aromatic basil leaves. Drizzled with a touch of olive oil and baked to perfection, this pizza delivers a balanced taste of tangy tomatoes, creamy cheese, and fresh herbs. Inspired by the colors of the Italian flag, Margherita Pizza is a timeless favorite that highlights quality ingredients and traditional preparation. Perfect for any pizza lover seeking authenticity.', 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8TWFyZ2hlcml0YSUyMFBpenphfGVufDB8fDB8fHww', 'Pizza', 'Pasta, Parmesan, Eggs, Bacon, Black Pepper', 'Serves 2 (300g)', '20 mins', 'Cook pasta, mix with sauce over low heat, serve hot.', 0, 'Contains eggs, dairy', 'Calories: 600, Protein: 25g, Fat: 30g', 'Italian'),
(5, 'Cheeseburger', 350.00, 'Juicy beef patty with cheddar and lettuce', 'https://images.unsplash.com/photo-1572802419224-296b0aeee0d9?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Q2hlZXNlYnVyZ2VyfGVufDB8fDB8fHww', 'Burger', 'Lamb, Yogurt, Garlic, Cumin, Pita Bread', 'Serves 1 (250g)', '10 mins', 'Grill lamb for 5 mins each side, wrap in pita, serve.', 2, 'Contains dairy', 'Calories: 450, Protein: 28g, Fat: 15g', 'Greek'),
(6, 'Fettuccine Alfredo', 400.00, 'Creamy pasta with parmesan sauce', 'https://images.unsplash.com/photo-1645112411341-6c4fd023714a?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8RmV0dHVjY2luZSUyMEFsZnJlZG98ZW58MHx8MHx8fDA%3D', 'Pasta', 'Flour, Sugar, Cocoa, Eggs, Butter', 'Serves 8 (600g)', '40 mins', 'Bake at 180°C for 30 mins, let cool before serving.', 0, 'Contains gluten, eggs', 'Calories: 1200, Protein: 20g, Fat: 50g', 'American'),
(7, 'Sushi Platter', 200.00, 'Assorted sushi rolls with fresh fish', 'https://images.unsplash.com/photo-1663334038419-71e6f82e333f?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8U3VzaGklMjBQbGF0dGVyfGVufDB8fDB8fHww', 'Sushi', 'Tofu, Soy Sauce, Ginger, Garlic, Noodles', 'Serves 2 (400g)', '20 mins', 'Stir-fry tofu and veggies, add noodles, serve hot.', 1, 'Contains soy', 'Calories: 400, Protein: 18g, Fat: 12g', 'Chinese'),
(8, 'Chicken Tacos', 180.00, 'Spicy grilled chicken in soft tortillas', 'https://images.unsplash.com/photo-1570461226513-e08b58a52c53?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Q2hpY2tlbiUyMFRhY29zfGVufDB8fDB8fHww', 'Tacos', 'Potatoes, Chickpeas, Tomatoes, Garam Masala, Cilantro', 'Serves 4 (500g)', '35 mins', 'Cook on low heat for 25 mins, garnish with cilantro.', 4, 'None', 'Calories: 350, Protein: 10g, Fat: 8g', 'Indian'),
(10, 'BBQ Ribs', 90.00, 'Tender pork ribs with smoky sauce', 'https://plus.unsplash.com/premium_photo-1664478272084-532c1bfebd25?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8QkJRJTIwUmlic3xlbnwwfHwwfHx8MA%3D%3D', 'Grill', 'Mango, Coconut, Sugar, Gelatin, Cream', 'Serves 4 (400g)', '20 mins', 'Chill in fridge for 2 hours, serve cold.', 0, 'Contains dairy', 'Calories: 300, Protein: 5g, Fat: 20g', 'Tropical'),
(14, 'Spicy Thai Chicken Curry', 230.00, 'This Spicy Thai Chicken Curry is a flavorful dish inspired by authentic Thai cuisine, featuring tender chicken simmered in a rich, creamy coconut milk base infused with aromatic lemongrass, fiery red chilies, and a hint of galangal. The dish is perfectly balanced with sweet and tangy notes from palm sugar and lime juice, making it a delightful explosion of flavors in every bite. Served with fragrant jasmine rice, this curry is ideal for a cozy dinner or a special occasion, offering a warm and satisfying meal that transports you straight to the streets of Bangkok. Perfect for spice lovers looking to explore bold tastes!', NULL, NULL, 'Chicken, Coconut Milk, Lemongrass, Red Chilies, Galangal, Palm Sugar, Lime Juice, Jasmine Rice', 'Serves 2 person (400g)', '30 mins', '', 4, 'Contains coconut', 'Calories: 650, Protein: 25g, Fat: 35g', 'Thai'),
(16, 'Creamy Italian Mushroom Risotto', 879.00, 'This Creamy Italian Mushroom Risotto is a luxurious dish that embodies the heart of Italian comfort food, crafted with Arborio rice slowly cooked to perfection in a savory vegetable broth. Enhanced with a medley of wild mushrooms—porcini, cremini, and shiitake—sautéed in butter and white wine, this risotto offers an earthy depth complemented by the nutty richness of freshly grated Parmesan cheese. Finished with a sprinkle of parsley and a drizzle of truffle oil, it’s an elegant yet hearty option for a gourmet dinner at home. Ideal for food enthusiasts who crave a sophisticated, velvety texture with every spoonful!', 'https://cdn.loveandlemons.com/wp-content/uploads/2023/01/mushroom-risotto-500x500.jpg', 'pasta', 'Arborio Rice, Porcini Mushrooms, Cremini Mushrooms, Shiitake Mushrooms, Butter, White Wine, Parmesan Cheese, Vegetable Broth, Parsley, Truffle Oil', 'Serves 2 person (400g)', '50 minutes', 'Arborio Rice, Porcini Mushrooms, Cremini Mushrooms, Shiitake Mushrooms, Butter, White Wine, Parmesan Cheese, Vegetable Broth, Parsley, Truffle Oil', 3, 'Contains dairy', 'Calories: 700, Protein: 18g, Fat: 30g', 'Italian');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'abebe@gmail.com', '$2y$10$tIClI9F5M/beBfRt9ZNgQu0csJlibD529T9q/x.GLUvdygvnnNcE2', '2025-05-13 00:48:09', 'admin'),
(4, 'mamo@gmail.com', '$2y$10$kwS7mXsFuSUgJPoflo2wy.N.7..I8j92QAyW/r4l0FgdNCfMPj18W', '2025-05-14 05:13:21', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
