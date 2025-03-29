-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 29, 2025 at 08:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `discount_program`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 'Fashion', '2025-03-02 14:18:04', '2025-03-02 14:20:31'),
(2, 'Electronic', '2025-03-02 14:18:04', '2025-03-02 14:20:31'),
(3, 'Food & Beverage', '2025-03-02 14:18:04', '2025-03-02 14:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('product','category') NOT NULL,
  `product_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_quantity` int DEFAULT '1',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `name`, `type`, `product_id`, `category_id`, `discount_type`, `discount_value`, `min_quantity`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 'Diskon Ramadhan', 'product', 4, NULL, 'percentage', 10.00, 3, '2025-03-05', '2025-03-31', '2025-03-05 12:28:40', '2025-03-28 06:03:50'),
(2, 'Diskon Kategori', 'category', NULL, 1, 'fixed', 20000.00, 3, '2025-03-05', '2025-03-20', '2025-03-05 12:29:17', '2025-03-05 13:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `category_id` int NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `arrival_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `image`, `arrival_date`, `created_at`, `updated_at`) VALUES
(3, 'Anjay gurinjay', 'takunjay kunjay ahay ahaya', 280000.00, 33, 1, '1740935303_images (1).jpg', '2025-03-01', '2025-03-02 17:08:23', '2025-03-28 06:02:57'),
(4, 'Website Proses Login Java', 'membuat tugas yang disuruh untuk dinilai', 50500.00, 22, 2, '1740937044_2.png', '2025-02-25', '2025-03-02 17:37:24', '2025-03-28 06:02:57'),
(6, 'Pesawat Mainan', 'Pesawat kecil yang didisain untuk anak anak, berwarna hitam lekat', 300000.00, 3, 2, '1741079939_01.png', '2025-01-30', '2025-03-04 09:18:59', '2025-03-28 06:02:57');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2025-03-02 14:17:29', '2025-03-02 14:21:06'),
(2, 'cashier', '2025-03-02 14:17:29', '2025-03-02 14:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `sales_summary`
--

CREATE TABLE `sales_summary` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `total_sold` int NOT NULL DEFAULT '0',
  `sales_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sales_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `cashier_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `final_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `invoice_number`, `cashier_id`, `total_price`, `discount`, `final_price`, `created_at`) VALUES
(1, 'INV-1741963329', 1, 840000.00, 20000.00, 820000.00, '2025-03-14 14:42:09'),
(2, 'INV-1741963721', 1, 900000.00, 90000.00, 810000.00, '2025-03-14 14:48:41'),
(3, 'INV-1741969594', 1, 381000.00, 0.00, 381000.00, '2025-03-14 16:26:34'),
(4, 'INV-1742055437', 1, 482000.00, 20200.00, 461800.00, '2025-03-15 16:17:17'),
(5, 'INV-1742055438', 1, 482000.00, 20200.00, 461800.00, '2025-03-15 16:17:18'),
(6, 'INV-1742483574', 1, 650500.00, 0.00, 650500.00, '2025-03-20 15:12:54'),
(7, 'INV-1742483868', 1, 1120000.00, 20000.00, 1100000.00, '2025-03-20 15:17:48'),
(8, 'INV-1742669718', 1, 1271500.00, 0.00, 1271500.00, '2025-03-22 18:55:18'),
(9, 'INV-1742911352', 1, 630500.00, 0.00, 630500.00, '2025-03-25 14:02:32'),
(10, 'INV-1743058564', 1, 1581000.00, 0.00, 1581000.00, '2025-03-27 06:56:04'),
(11, 'INV-1743141777', 1, 930500.00, 0.00, 930500.00, '2025-03-28 06:02:57');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` int NOT NULL,
  `transaction_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(1, 1, 3, 3, 280000.00, 840000.00),
(2, 2, 6, 3, 300000.00, 900000.00),
(3, 3, 4, 2, 50500.00, 101000.00),
(4, 3, 3, 1, 280000.00, 280000.00),
(5, 4, 4, 4, 50500.00, 202000.00),
(6, 4, 3, 1, 280000.00, 280000.00),
(7, 5, 4, 4, 50500.00, 202000.00),
(8, 5, 3, 1, 280000.00, 280000.00),
(9, 6, 6, 2, 300000.00, 600000.00),
(10, 6, 4, 1, 50500.00, 50500.00),
(11, 7, 3, 4, 280000.00, 1120000.00),
(12, 8, 4, 3, 50500.00, 151500.00),
(13, 8, 3, 4, 280000.00, 1120000.00),
(14, 9, 6, 1, 300000.00, 300000.00),
(15, 9, 4, 1, 50500.00, 50500.00),
(16, 9, 3, 1, 280000.00, 280000.00),
(17, 10, 6, 4, 300000.00, 1200000.00),
(18, 10, 4, 2, 50500.00, 101000.00),
(19, 10, 3, 1, 280000.00, 280000.00),
(20, 11, 6, 2, 300000.00, 600000.00),
(21, 11, 4, 1, 50500.00, 50500.00),
(22, 11, 3, 1, 280000.00, 280000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 'cashier12@gmail.com', '$2y$10$vZq2WrudQAX3NBDfZWeaLua7lvmN/sjKBJGHuKvL0crnSKsi/7F/a', 'cashierr', 2, '2025-03-02 14:29:32', '2025-03-02 14:29:32'),
(2, 'admin32@gmail.com', '$2y$10$ByqM9PmGozNnllkcmFc.vuz8BvEUSS8gPKcfbBkzBOxRIC/P5FGmi', 'admiin', 1, '2025-03-02 14:29:32', '2025-03-02 14:29:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `sales_summary`
--
ALTER TABLE `sales_summary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `cashier_id` (`cashier_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales_summary`
--
ALTER TABLE `sales_summary`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `sales_summary`
--
ALTER TABLE `sales_summary`
  ADD CONSTRAINT `sales_summary_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;