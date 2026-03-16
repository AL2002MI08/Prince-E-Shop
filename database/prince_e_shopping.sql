-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2026 at 09:47 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prince_e_shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Clothing'),
(2, 'Accessories'),
(3, 'Shoes'),
(4, 'Home');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `customer_id`, `product_id`, `comment_text`, `created_at`) VALUES
(1, 3, 27, 'yes', '2026-03-14 17:21:32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `product_id`, `order_date`) VALUES
(1, 3, 27, '2026-03-14 17:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  `photo_path` varchar(255) NOT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `category_id`, `product_name`, `price`, `discount_percent`, `photo_path`, `document_path`, `location`) VALUES
(5, 2, 3, 'Mens shoes', '1000.00', 20, 'uploads/1773505610_pexels-pixabay-267320.jpg', 'uploads/1773505610_1773480366_1773440797_H_39044136.pdf', 'M Peace Plaza Floor2, C21'),
(6, 2, 3, 'Men\'s Classic shoes', '4000.00', 20, 'uploads/1773505663_pexels-jonathanborba-12031204.jpg', 'uploads/1773505663_1773480366_1773440797_H_39044136.pdf', 'M Peace Plaza Floor2, C21'),
(7, 2, 3, 'Women Classic Shoes', '3000.00', 20, 'uploads/1773505721_pexels-solliefoto-273930.jpg', NULL, 'M Peace Plaza Floor2, C22'),
(8, 2, 3, 'Red Canvas', '20.00', 0, 'uploads/1773505762_pexels-mstudio-360817-1261005.jpg', NULL, 'M Peace Plaza Floor2, C23'),
(9, 2, 3, 'Black and White Canvas', '30.00', 2, 'uploads/1773505795_pexels-frans-van-heerden-201846-847371.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(10, 2, 3, 'Nike Air', '40.00', 0, 'uploads/1773505866_pexels-alokkd1-19577862.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(11, 2, 3, 'Nike Jordan One', '30.00', 0, 'uploads/1773505914_pexels-mnzoutfits-1598505.jpg', NULL, 'M Peace Plaza Floor2, C22'),
(12, 2, 3, 'Nike 360', '20.00', 0, 'uploads/1773505963_pexels-craytive-1537671.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(13, 2, 3, 'Adidas 456', '40.00', 0, 'uploads/1773506006_pexels-kowalievska-1040427.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(14, 2, 1, 'SkyBlue Jeans', '40.00', 2, 'uploads/1773506116_pexels-karola-g-4210866.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(15, 2, 1, 'Classic package', '70.00', 0, 'uploads/1773506194_pexels-godisable-jacob-226636-794062.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(16, 2, 1, 'Men Shirts', '10.00', 0, 'uploads/1773506236_pexels-thirdman-8484138.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(17, 2, 1, 'Black dotted girl\'s Shirt', '12.00', 0, 'uploads/1773506281_pexels-olenagoldman-1021693.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(18, 2, 1, 'Red 2-in-1 shirts', '23.00', 0, 'uploads/1773506320_pexels-lribeirofotografia-2249248.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(19, 2, 1, 'Sweater', '30.00', 0, 'uploads/1773506381_pexels-dom-j-7304-45982.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(20, 2, 1, 'Biege Sweater', '12.00', 0, 'uploads/1773506415_pexels-alena-shekhovtcova-6995902.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(21, 2, 1, 'Sweater for both men and women', '30.00', 0, 'uploads/1773506465_pexels-alex-green-6625897.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(22, 2, 2, 'I phone X', '200.00', 0, 'uploads/1773506526_pexels-jessbaileydesign-788946.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(23, 2, 2, 'LPV-20 Head Phones', '300.00', 10, 'uploads/1773506587_pexels-sound-on-3394650.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(24, 2, 2, 'Rolex 2026', '30.00', 0, 'uploads/1773506657_pexels-ferarcosn-190819.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(25, 2, 2, 'Meta Riban', '300.00', 20, 'uploads/1773506686_pexels-nurseryart-354103.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(26, 2, 2, 'Ribbans', '30.00', 0, 'uploads/1773506719_pexels-asim-razan-343720.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(27, 2, 2, 'Suit Case', '200.00', 0, 'uploads/1773506884_pexels-cottonbro-7128339.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(28, 2, 2, 'Gym Equipments', '30.00', 0, 'uploads/1773506921_pexels-pixabay-39671.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(29, 2, 4, 'Full Furnitured House', '15000.00', 10, 'uploads/1773506960_pexels-binyaminmellish-1396122.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(30, 2, 4, 'Dining table', '4000.00', 10, 'uploads/1773506999_pexels-falling4utah-1080721.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(31, 2, 4, 'A single seat with a lamp', '3000.00', 0, 'uploads/1773507037_pexels-kowalievska-1148955.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(32, 2, 4, '2 seats couch', '400.00', 0, 'uploads/1773507066_pexels-olly-3757055.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(33, 2, 4, 'One seat couch and table', '300.00', 0, 'uploads/1773507090_pexels-zvolskiy-2082090.jpg', NULL, 'M Peace Plaza Floor2, C21'),
(34, 2, 1, 'Jordan Future', '20.00', 2, 'uploads/1773511963_pexels-mnzoutfits-1598505.jpg', 'uploads/1773511963_1773480366_1773440797_H_39044136.pdf', 'M Peace Plaza Floor2, C21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Seller','Customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin123', 'Admin'),
(2, 'seller1', 'seller123', 'Seller'),
(3, 'customer1', 'customer123', 'Customer'),
(4, 'PrinceHymn', 'Stack-pop@123', 'Seller'),
(5, 'seller2', 'seller123', 'Seller'),
(6, 'remmy', '12345', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
