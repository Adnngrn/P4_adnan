-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 08, 2025 at 08:59 PM
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
(1, 'Pakaian & Aksesoris', '2025-03-02 14:18:04', '2025-04-06 07:44:55'),
(2, 'Elektronik', '2025-03-02 14:18:04', '2025-04-06 07:44:29'),
(3, 'Sembako', '2025-03-02 14:18:04', '2025-04-06 07:45:07');

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
(1, 'Diskon 10%', 'product', 4, NULL, 'percentage', 10.00, 3, '2025-03-05', '2025-04-07', '2025-03-05 12:28:40', '2025-04-07 16:40:50'),
(2, 'Diskon Kategori', 'category', NULL, 1, 'fixed', 20000.00, 3, '2025-04-01', '2025-04-10', '2025-03-05 12:29:17', '2025-04-07 16:42:19'),
(3, 'lebaran', 'category', NULL, 3, 'percentage', 10.00, 2, '2025-03-31', '2025-04-08', '2025-03-31 06:30:30', '2025-04-07 08:40:01'),
(4, 'Cobain Kon', 'product', 8, NULL, 'percentage', 5.00, 2, '2025-04-01', '2025-04-08', '2025-03-31 19:29:26', '2025-03-31 19:29:26');

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
(3, 'Cardinal Casual Jaket Kulit Collar Tab', 'Warna cokelat klasik, menggunakan kulit Domba asli. 4 kantung terletak pada sisi kanan dan kiri bagian perut, dan 2 kantong pada sisi kanan kiri bagian dada, kancing tekan besar pada bagian kerah jaket, dan kain berbahan karet pada ujung bagian bawah dan lengan jaket yang menyesuaikan pada pemakainya.', 280000.00, 9, 1, '1744026445_JaketKulit.jpg', '2025-03-01', '2025-03-02 17:08:23', '2025-04-07 12:40:45'),
(4, 'Mouse Gaming USB RGB Aula S50', 'Mouse Gaming USB RGB Aula S50. Konektivitas - USB, 6 tombol, Resolusi: 800/1200/1600/2400 DPI, Sensor optik, Lampu latar RGB, Panjang kabel: 1,5m , Warna hitam', 50500.00, 17, 2, '1744025037_Mouse.jpg', '2025-02-25', '2025-03-02 17:37:24', '2025-04-07 11:23:57'),
(6, 'Keyboard Digital Alliance Meca Fighter ICE RGB', 'Model	Keyboard Gaming DA Meca Fighter Ice RGB.\r\nWarna	Putih,\r\nBerat	1.100 gram, Ukuran 440x139x37mm, USB 2.0 berlapis emas,\r\nPanjang Kabel	150 ±5cm,\r\nFitur Khusus: Tahan Air Percikan,\r\nLampu latar	: RGB\r\n', 300000.00, 10, 2, '1744023004_KeyboardGaming.png', '2025-01-30', '2025-03-04 09:18:59', '2025-04-07 10:50:04'),
(7, 'Quaker Oats 3in1 Vanila Bag 8 x 28 g', 'Minuman sereal oats yang terbuat dari 100% oat dan dapat menjadi sumber serat. Mengandung 7 vitamin (Vitamin B1, B2, B3, B9, B12, Vitamin D) yang dapat berperan sebagai koenzim perubahan karbohidrat menjadi energi. Hadir dengan kandungan asam folat yang berperan dalam pembentukan sel darah merah. Oats ini juga mengandung kalsium yang tinggi dan dapat membantu pembentukan dan mempertahankan kepadatan tulang dan gigi. ', 36500.00, 20, 3, '1744027278_QuakerOat.jpg', '2025-03-31', '2025-03-31 06:29:08', '2025-04-07 12:01:52'),
(8, 'Sirup ABC Squash Delight', 'Sirup Rasa : Jeruk. Terbuat dari bahan-bahan berkualitas, Rasa buah yang nyata, murni dan sangat menyegarkan. Volume : 525 mL', 30000.00, 20, 3, '1744023497_Sirup.png', '2025-04-01', '2025-03-31 19:28:07', '2025-04-07 10:58:17'),
(9, 'Beras Sania 5 Kg', 'Sania Beras Premium 5000 g merupakan beras pulen yang terbuat dari padi segar pilihan varietas padi IR-64. Diolah dengan teknologi dari Jepang sehingga menghasilkan nasi yang pulen dan enak. Cocok dan aman untuk konsumsi sehari-hari karena tanpa tambahan pemutih.', 74500.00, 20, 3, '1743925976_BerasSania5KG.jpg', '2025-04-06', '2025-04-06 07:52:56', '2025-04-06 08:21:48'),
(10, 'Rice Cooker 1 Liter Sanken SJ-130SP', 'Sanken SJ-130SP Black Rice Cooker [1 liter] hadir dengan desain mewah dan elegan, body terbuat dari stainless tebal, wadah nasi terbuat dari stainless steel anti gores, anti karat dan anti penyok yang tidak akan mengelupas seiring berjalannya waktu dan menjadikan makanan lebih higienis dan sehat. Magic com ini memiliki berbagai fungsi seperti memasak, mengukus dan menghangatkan, juga dapat digunakan untuk menggoreng nasi dan masakan rumah lainnya. Sanken SJ-130SP dilengkapi dengan teknologi pemanas 3D Warming yang mampu menghasilkan kehangatan nasi yang merata dan masak dengan sempurna.', 405000.00, 10, 2, '1743926323_RiceCookerSanken.jpg', '2025-04-06', '2025-04-06 07:58:43', '2025-04-06 07:58:43'),
(11, 'Jaket Gorpcore Pria ', 'Size: L (Panjang: 70 Lebar: 59), Ada serut di hoodie, Saku luar 2, Dua kombinasi warna, Lengan karet cocok dipakai sehari hari', 245000.00, 0, 1, '1743926670_JaketGopcore.jpg', '2025-04-06', '2025-04-06 08:04:30', '2025-04-08 15:02:33'),
(12, 'Minyak Goreng Bimoli Pouch 1 L', '- Minyak goreng berkualitas tinggi\r\n- Terbuat dari biji kelapa sawit pilihan\r\n- Diproduksi dengan tahap Pemurnian Multi Proses (PMP)\r\n- Bebas kolesterol\r\n- Hasil masakan lebih renyah\r\n- Tersedia dalam kemasan pouch refill 1 liter', 21000.00, 20, 3, '1743927001_MinyakBimoli.jpg', '2025-04-06', '2025-04-06 08:10:01', '2025-04-06 08:10:01'),
(13, 'Nestle Dancow Fortigro Susu Bubuk Cokelat 1000 g', 'DANCOW FORTIGRO adalah susu bubuk yang mengandung nutrisi untuk Siap Sekolah.\r\nDibuat dengan Susu Segar dan merupakan sumber Protein Susu, Kaya Kalsium, Vitamin, Zat Besi dan Zink.\r\nMengandung kolin berperan untuk membantu memenuhi gizi anak.', 100000.00, 10, 3, '1743927410_SusuDancowCoklat1Kg.jpg', '2025-04-06', '2025-04-06 08:16:50', '2025-04-06 08:16:50');

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
(11, 'INV-1743141777', 1, 930500.00, 0.00, 930500.00, '2025-03-28 06:02:57'),
(12, 'INV-1743402362', 1, 350500.00, 0.00, 350500.00, '2025-03-31 06:26:02'),
(13, 'INV-1743402685', 1, 255500.00, 63875.00, 191625.00, '2025-03-31 06:31:25'),
(14, 'INV-1743448773', 1, 202000.00, 0.00, 202000.00, '2025-03-31 19:19:33'),
(15, 'INV-1743597560', 1, 600000.00, 0.00, 600000.00, '2025-04-02 12:39:20'),
(16, 'INV-1744029645', 1, 280000.00, 0.00, 280000.00, '2025-04-07 12:40:45'),
(17, 'INV-1744124553', 1, 1225000.00, 20000.00, 1205000.00, '2025-04-08 15:02:33');

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
(22, 11, 3, 1, 280000.00, 280000.00),
(23, 12, 4, 1, 50500.00, 50500.00),
(24, 12, 6, 1, 300000.00, 300000.00),
(25, 13, 7, 7, 36500.00, 255500.00),
(26, 14, 4, 4, 50500.00, 202000.00),
(27, 15, 6, 2, 300000.00, 600000.00),
(28, 16, 3, 1, 280000.00, 280000.00),
(29, 17, 11, 5, 245000.00, 1225000.00);

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
(1, 'cashier12@gmail.com', '$2y$10$vZq2WrudQAX3NBDfZWeaLua7lvmN/sjKBJGHuKvL0crnSKsi/7F/a', 'cashier1', 2, '2025-03-02 14:29:32', '2025-04-06 16:02:48'),
(2, 'admin32@gmail.com', '$2y$10$ByqM9PmGozNnllkcmFc.vuz8BvEUSS8gPKcfbBkzBOxRIC/P5FGmi', 'admiin1', 1, '2025-03-02 14:29:32', '2025-04-06 16:13:16');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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