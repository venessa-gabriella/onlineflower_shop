-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2026 at 08:07 AM
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
-- Database: `onlineflower_sys`
--

-- --------------------------------------------------------

--
-- Table structure for table `flowers`
--

CREATE TABLE `flowers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flowers`
--

INSERT INTO `flowers` (`id`, `name`, `description`, `price`, `stock`, `created_at`) VALUES
(1, 'Red Roses', 'Beautiful red roses', 1500.00, 4, '2026-10-29 06:36:37'),
(2, 'Pink Tulips', 'Fresh pink tulips', 1200.00, 2, '2026-10-29 06:36:37'),
(3, 'Sunflower Bouquet', 'Bright sunflowers', 1800.00, 6, '2026-10-29 06:36:37'),
(4, 'Mixed Flowers', 'Colourful mixed flowers', 2000.00, 7, '2026-10-29 06:36:37'),
(5, 'White Lilies', 'Elegant white lilies', 1700.00, 1, '2026-10-29 06:36:37'),
(6, 'rose flower', 'cool one', 2900.00, 50, '2026-10-29 07:57:16'),
(7, 'Carnations', 'pink white', 1500.00, 50, '2026-10-30 06:23:39'),
(8, 'Orchids', 'purple', 1300.00, 35, '2026-10-30 06:24:26'),
(9, 'Baby\'s breath', 'red white', 1500.00, 55, '2026-10-30 06:26:07'),
(11, 'Baby\'s breath', 'red white', 1500.00, 55, '2026-10-30 06:30:36');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `delivery_address` text NOT NULL,
  `flower_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `phone`, `delivery_address`, `flower_id`, `quantity`, `total_price`, `status`, `created_at`) VALUES
(1, 'Nyambura', '0789231784', 'kilimni', 3, 4, 7200.00, 'Pending', '2026-10-29 20:29:33'),
(2, 'marylyne.G', '0129098234', '0012904', 1, 16, 24000.00, 'Pending', '2026-10-30 06:14:30'),
(3, 'Peris Dilha', '0789128936', '100932', 5, 7, 11900.00, 'Pending', '2026-10-30 06:16:36'),
(4, 'lydia Lila', '0732120927', '002119', 2, 13, 15600.00, 'Pending', '2026-10-30 06:18:44'),
(5, 'bobo', '0734567098', '900876', 4, 5, 10000.00, 'Pending', '2026-10-30 06:32:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `flowers`
--
ALTER TABLE `flowers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flower_id` (`flower_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `flowers`
--
ALTER TABLE `flowers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`flower_id`) REFERENCES `flowers` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
