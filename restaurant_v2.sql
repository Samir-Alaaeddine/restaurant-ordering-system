-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2026 at 11:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `Username`, `Password`) VALUES
(1, 'admin', '$2b$10$Gtz/aocgLO.XO4LKCVUu0OR7BbKBBVLQ4rdChb2iviKHjYMxenviC');

-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

CREATE TABLE `dish` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Category` varchar(50) NOT NULL,
  `ImgPath` text NOT NULL,
  `Available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`ID`, `Name`, `Description`, `Price`, `Category`, `ImgPath`, `Available`) VALUES
(1, 'Buffalo Wings', 'Crispy chicken wings tossed in spicy cayenne pepper sauce, served with celery sticks and blue cheese dip.', 6.00, 'Appetizers', 'images/Buffalo wings.jpg', 1),
(2, 'Mozzarella Sticks', 'Golden fried breaded mozzarella cheese strings, served with a warm zesty marinara dipping sauce.', 4.00, 'Appetizers', 'images/Mozzarella Sticks.jpg', 1),
(3, 'Caesar Salad', 'Crisp romaine lettuce tossed in creamy Caesar dressing, topped with crunchy garlic croutons and shaved parmesan cheese.', 12.00, 'Salads', 'images/Salad.jpg', 1),
(4, 'Mediterranean Quinoa Salad', 'Fluffy quinoa mixed with diced cucumbers, cherry tomatoes, kalamata olives, and feta cheese, drizzled with a lemon-herb vinaigrette.', 9.50, 'Salads', 'images/Feta salad.jpg', 1),
(5, 'Classic Club Sandwich', 'Toasted triple-decker bread layered with grilled chicken, smoked turkey, fried egg, lettuce, tomato, and mayonnaise, served with french fries.', 8.00, 'Sandwiches', 'images/Club Sandwich.jpg', 1),
(6, 'Turkey Cranberry Panini', 'Sliced smoked turkey breast, melted brie cheese, and sweet cranberry compote pressed flat in a crispy artisanal ciabatta bread.', 8.00, 'Sandwiches', 'images/Turkey Cranberry.jpg', 1),
(7, 'Classic Cheeseburger', 'Grilled flame-broiled beef patty topped with melted cheddar cheese, lettuce, fresh tomatoes, pickles, and house burger sauce on a toasted bun.', 7.50, 'Burgers', 'images/Cheeseburger.jpg', 1),
(8, 'Swiss Mushroom Burger', 'Juicy prime beef patty smothered in melted Swiss cheese and sautéed white button mushrooms with garlic herb mayo.', 10.00, 'Burgers', 'images/Mushroom burger.jpg', 1),
(9, 'Pepperoni', 'Stone-baked traditional crust topped with rich tomato passata, gooey mozzarella cheese, and generous slices of spicy beef pepperoni.', 14.00, 'Pizza', 'images/Peperoni.jpg', 1),
(10, 'Margherita', 'Authentic thin crust featuring fresh sliced mozzarella, crushed San Marzano tomatoes, extra virgin olive oil, and aromatic fresh basil leaves.', 10.00, 'Pizza', 'images/Margherita Pizza.jpg', 1),
(11, 'Chicken Mushroom', 'Tender grilled chicken breast smothered in a creamy wild mushroom sauce, served with golden potato wedges and butter-sautéed seasonal vegetables.', 13.00, 'Platters', 'images/Chicken Mushroom.jpg', 1),
(12, 'Classic Fish & Chips', 'Crispy beer-battered cod fillet fried to a golden brown, served with thick-cut rustic fries, mushy peas, and a side of creamy tartar sauce.', 11.50, 'Platters', 'images/Fish and Chips.jpg', 1),
(13, 'Chocolate Brownie', 'Fudgy rich chocolate brownie loaded with walnuts, served warm with a scoop of vanilla bean ice cream and hot chocolate fudge drizzle.', 6.00, 'Desserts', 'images/Brownie.jpg', 1),
(14, 'New York Cheesecake', 'Smooth and dense baked cream cheese cake on a buttery graham cracker crust, finished with a sweet strawberry coulis topping.', 7.50, 'Desserts', 'images/Cheesecake.jpg', 1),
(15, 'Classic Iced Tea', 'Refreshing freshly brewed black tea blend lightly sweetened and infused with fresh lemon slices, served chilled over crushed ice.', 3.00, 'Cold Beverages', 'images/Ice tea.jpg', 1),
(16, 'Passion Fruit Mojito', 'A refreshing muddled mix of fresh lime wedges, wild mint leaves, sweet passion fruit purée, and sparkling soda water.', 4.00, 'Cold Beverages', 'images/Mohito.jpg', 1),
(17, 'Cappuccino', 'A perfectly balanced shot of rich espresso topped with equal parts steamed velvety milk and a thick layer of smooth milk foam.', 4.00, 'Hot Beverages', 'images/Cappuccino.jpg', 1),
(18, 'Hot Chocolate', 'Rich premium Dutch cocoa dissolved in steamed whole milk, topped with fluffy mini marshmallows and a dust of cocoa powder.', 5.50, 'Hot Beverages', 'images/Hot Choclate.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `ID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `dishID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_order` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`ID`, `orderID`, `dishID`, `quantity`, `price_at_order`) VALUES
(1, 1, 1, 1, 6.00),
(2, 1, 9, 1, 14.00),
(3, 1, 3, 1, 12.00),
(4, 2, 1, 1, 6.00),
(5, 2, 3, 1, 12.00),
(6, 3, 1, 1, 6.00),
(7, 3, 2, 1, 4.00),
(8, 4, 3, 1, 12.00),
(9, 4, 5, 1, 8.00),
(10, 5, 2, 3, 4.00),
(11, 6, 1, 1, 6.00),
(12, 7, 3, 1, 12.00),
(13, 8, 2, 1, 4.00),
(14, 8, 9, 1, 14.00),
(15, 9, 2, 3, 4.00),
(16, 10, 1, 1, 6.00),
(17, 11, 4, 1, 9.50),
(18, 11, 5, 1, 8.00),
(19, 12, 7, 1, 7.50),
(20, 13, 1, 1, 6.00),
(21, 13, 2, 1, 4.00),
(22, 14, 1, 1, 6.00),
(23, 14, 2, 1, 4.00),
(24, 15, 1, 1, 6.00),
(25, 16, 1, 1, 6.00),
(26, 16, 2, 1, 4.00),
(27, 17, 1, 1, 6.00),
(28, 18, 1, 1, 6.00),
(29, 19, 1, 1, 6.00),
(30, 19, 2, 1, 4.00),
(31, 19, 3, 1, 12.00),
(32, 19, 4, 1, 9.50),
(33, 20, 2, 2, 4.00),
(34, 20, 3, 1, 12.00),
(35, 21, 3, 1, 12.00),
(36, 21, 5, 1, 8.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Method` varchar(50) DEFAULT NULL,
  `Status` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `CustomerName` varchar(50) NOT NULL,
  `CustomerPhone` char(8) NOT NULL,
  `Area` varchar(50) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `tracking_token` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `Type`, `Method`, `Status`, `total`, `created_at`, `CustomerName`, `CustomerPhone`, `Area`, `Address`, `tracking_token`) VALUES
(1, 'pickup', NULL, 'Preparing', 32.00, '2026-09-19 11:55:00', 'Test Customer', '70000000', NULL, NULL, '455f0ba7a64d4897e0646d3c67484d17'),
(2, 'delivery', 'Whish Money', 'Preparing', 18.00, '2026-09-19 11:57:15', 'Test Customer', '70000000', 'Barja', 'Main road', 'b59cf8aa3fab0e110f469e8b6ee1107a'),
(3, 'delivery', 'Cash', 'Preparing', 12.00, '2026-09-19 12:06:39', 'Test Customer', '70000000', 'Barja', 'Main road', 'ffc3ad15d5a545f3340f30fd1dfc4a15'),
(4, 'delivery', 'Whish Money', 'On the way', 22.00, '2026-09-19 12:20:45', 'Test Customer', '70000000', 'Mazboud', 'Near the square', '9c8339c314aa36e6d3900f83f66f0b8c'),
(5, 'delivery', 'Whish Money', 'Preparing', 14.00, '2026-09-19 12:42:28', 'Test Customer', '70000000', 'Barja', 'Main road', '8f668ac26c32fd47a4c1ff1a77195f8e'),
(6, 'pickup', NULL, 'Preparing', 6.00, '2026-09-19 13:28:54', 'Test Customer', '70000000', NULL, NULL, '99564d75fe9ec52c3ded659f1c15f9a1'),
(7, 'delivery', 'Whish Money', 'On the way', 14.00, '2026-09-19 13:30:39', 'Test Customer', '70000000', 'Barja', 'Main road', '5ed63dc027750d47672eb83039bec732'),
(8, 'delivery', 'Whish Money', 'Preparing', 20.00, '2026-09-19 13:36:56', 'Test Customer', '70000000', 'Barja', 'Main road', '0bf11497bf7ca4211795071f2ff801e3'),
(9, 'delivery', 'Whish Money', 'Preparing', 14.00, '2026-09-19 13:40:34', 'Test Customer', '70000000', 'Shhim', 'Main road', '636e897d05488754d7ab9c610d723b6c'),
(10, 'pickup', NULL, 'Preparing', 6.00, '2026-09-19 22:35:56', 'Test Customer', '70000000', NULL, NULL, '4da23d1634b4860940ec55bddcc1fae5'),
(11, 'pickup', NULL, 'Preparing', 17.50, '2026-09-19 22:36:06', 'Test Customer', '70000000', NULL, NULL, 'c1ad9c5865fe0b7cf22ac4008c5685df'),
(12, 'pickup', NULL, 'Preparing', 7.50, '2026-09-19 22:36:14', 'Test Customer', '70000000', NULL, NULL, 'd57f8814eda4aff940d5947c3acf4990'),
(13, 'pickup', NULL, 'Preparing', 10.00, '2026-09-19 22:57:54', 'Test Customer', '70000000', NULL, NULL, 'b06c325dd6ba3e0d6d8bf4bb2069d8d7'),
(14, 'pickup', NULL, 'Preparing', 10.00, '2026-09-19 23:00:32', 'Test Customer', '70000000', NULL, NULL, '82fe19a8ee01559542cdc23ce0424c09'),
(15, 'pickup', NULL, 'Preparing', 6.00, '2026-09-19 23:01:24', 'Test Customer', '70000000', NULL, NULL, '55994cf366c447ef07eeb4a46cf8a9f8'),
(16, 'pickup', NULL, 'Preparing', 10.00, '2026-09-19 23:03:53', 'Test Customer', '70000000', NULL, NULL, '4af1d3636b870d4acb3ff9c0b8b3fb2f'),
(17, 'pickup', NULL, 'Preparing', 6.00, '2026-09-19 23:05:29', 'Test Customer', '70000000', NULL, NULL, '2b3b4c1bfd33543a2a667ed490cfc274'),
(18, 'pickup', NULL, 'Preparing', 6.00, '2026-09-19 23:05:55', 'Test Customer', '70000000', NULL, NULL, '4959cc9d8f35c2a337ecacab4fcfaae5'),
(19, 'pickup', NULL, 'Preparing', 31.50, '2026-09-19 23:23:43', 'Test Customer', '70000000', NULL, NULL, 'b1aeb16ea92ed147a3d568e25f41acd8'),
(20, 'delivery', 'Whish Money', 'Delivered', 22.00, '2026-09-19 23:28:59', 'Test Customer', '70000000', 'Barja', 'Main road', 'd394a33f0640ac671755e3092708eacf'),
(21, 'pickup', NULL, 'Preparing', 20.00, '2026-09-19 23:41:46', 'Test Customer', '70000000', NULL, NULL, '8ffdd7e94a5e1ce8b0484d0be9a474b2');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `ID` int(11) NOT NULL,
  `tableID` int(11) NOT NULL,
  `CustomerName` varchar(50) NOT NULL,
  `CustomerPhone` char(8) NOT NULL,
  `reserved_at` datetime NOT NULL,
  `expire_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`ID`, `tableID`, `CustomerName`, `CustomerPhone`, `reserved_at`, `expire_at`) VALUES
(1, 1, 'Test Customer', '70000000', '2026-09-19 12:39:23', '2026-09-19 15:39:23'),
(2, 2, 'Test Customer', '70000000', '2026-09-19 12:46:43', '2026-09-19 15:46:43'),
(3, 1, 'Test Customer', '70000000', '2026-09-19 23:18:23', '2026-09-20 02:18:23');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `ID` int(11) NOT NULL,
  `Number` int(11) NOT NULL,
  `Capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`ID`, `Number`, `Capacity`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 3, 4),
(4, 4, 4),
(5, 5, 4),
(6, 6, 6),
(7, 7, 6),
(8, 8, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_orderitems_order` (`orderID`),
  ADD KEY `fk_orderitems_dish` (`dishID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `tracking_token` (`tracking_token`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_reservation_table` (`tableID`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dish`
--
ALTER TABLE `dish`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `fk_orderitems_dish` FOREIGN KEY (`dishID`) REFERENCES `dish` (`ID`),
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`orderID`) REFERENCES `orders` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_table` FOREIGN KEY (`tableID`) REFERENCES `tables` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
