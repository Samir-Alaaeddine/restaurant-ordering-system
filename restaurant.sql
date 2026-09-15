-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2026 at 12:43 AM
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
-- Database: `restaurant`
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

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `ID` int(11) NOT NULL,
  `governorateID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`ID`, `governorateID`, `Name`) VALUES
(1, 1, 'Halba'),
(2, 1, 'Al-Qoubaiyat'),
(3, 1, 'Bire Akkar'),
(4, 1, 'Bebnine'),
(5, 2, 'Baalbek'),
(6, 2, 'Hermel'),
(7, 2, 'Aarsal'),
(8, 2, 'Chmistar'),
(9, 3, 'Achrafieh'),
(10, 3, 'Hamra'),
(11, 3, 'Badaro'),
(12, 3, 'Verdun'),
(13, 4, 'Zahle'),
(14, 4, 'Anjar'),
(15, 4, 'Rachaya'),
(16, 4, 'Joub Jannine'),
(17, 5, 'Jounieh'),
(18, 5, 'Byblos'),
(19, 5, 'Faraya'),
(20, 5, 'Ghazir'),
(21, 6, 'Baabda'),
(22, 6, 'Aley'),
(23, 6, 'Judeideh'),
(24, 6, 'Deir al-Qamar'),
(25, 7, 'Nabatieh'),
(26, 7, 'Bint Jbeil'),
(27, 7, 'Marjayoun'),
(28, 7, 'Hasbaya'),
(29, 8, 'Tripoli'),
(30, 8, 'Batroun'),
(31, 8, 'Bcharre'),
(32, 8, 'Zgharta'),
(33, 9, 'Sidon'),
(34, 9, 'Tyre'),
(35, 9, 'Jezzine'),
(36, 9, 'Sarafand');

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
-- Table structure for table `governorate`
--

CREATE TABLE `governorate` (
  `ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `governorate`
--

INSERT INTO `governorate` (`ID`, `Name`) VALUES
(1, 'Akkar'),
(2, 'Baalbek-Hermel'),
(3, 'Beirut'),
(4, 'Bekaa'),
(5, 'Keserwan-Jbeil'),
(6, 'Mount Lebanon'),
(7, 'Nabatieh'),
(8, 'North Lebanon'),
(9, 'South Lebanon');

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
(16, 9, 1, 2, 6.00),
(17, 9, 2, 1, 4.00),
(18, 10, 1, 2, 6.00),
(19, 10, 2, 1, 4.00),
(20, 11, 1, 2, 6.00),
(21, 11, 2, 1, 4.00),
(22, 12, 1, 2, 6.00),
(23, 12, 2, 1, 4.00),
(24, 13, 2, 1, 4.00),
(25, 13, 3, 1, 12.00),
(26, 13, 6, 1, 8.00),
(27, 14, 1, 1, 6.00),
(28, 15, 1, 1, 6.00),
(29, 15, 2, 1, 4.00),
(30, 16, 1, 2, 6.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Method` varchar(50) DEFAULT NULL,
  `Status` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `userID`, `Type`, `Method`, `Status`, `total`, `created_at`) VALUES
(9, 1, 'delivery', 'Card', 'Preparing', 16.00, '2026-07-29 16:30:21'),
(10, 1, 'delivery', 'Card', 'Preparing', 16.00, '2026-07-29 16:34:55'),
(11, 1, 'delivery', 'Card', 'Preparing', 16.00, '2026-07-29 18:40:57'),
(12, 1, 'delivery', 'Card', 'Preparing', 16.00, '2026-07-29 18:54:24'),
(13, 1, 'delivery', 'Card', 'On the way', 24.00, '2026-07-30 18:39:52'),
(14, 1, 'delivery', 'Cash', 'Preparing', 6.00, '2026-07-30 18:43:22'),
(15, 1, 'pickup', NULL, 'Preparing', 10.00, '2026-08-04 22:56:26'),
(16, 1, 'delivery', 'Cash', 'Delivered', 12.00, '2026-08-04 22:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `ID` int(11) NOT NULL,
  `tableID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `reserved_at` datetime NOT NULL,
  `expire_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`ID`, `tableID`, `userID`, `reserved_at`, `expire_at`) VALUES
(27, 1, 1, '2026-07-30 18:38:55', '2026-07-30 21:38:55');

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

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `FullName` varchar(50) NOT NULL,
  `Phone` char(8) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Street` varchar(50) NOT NULL,
  `Area` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `FullName`, `Phone`, `Email`, `Password`, `Street`, `Area`) VALUES
(1, 'Test User', '70000000', 'test@example.com', '$2y$10$GSNKYyh7CFwpR.KAbX8dDe5FExQ8Dmwjjr//ac7QiDsw09i5NkOGm', 'Sample Street', 33);

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
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_area_governorate` (`governorateID`);

--
-- Indexes for table `dish`
--
ALTER TABLE `dish`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `governorate`
--
ALTER TABLE `governorate`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_constraint` (`orderID`),
  ADD KEY `fk_orderitems_dish` (`dishID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_orders_user` (`userID`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_reservation_table` (`tableID`),
  ADD KEY `fk_reservation_user` (`userID`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Phone` (`Phone`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `fk_user_area` (`Area`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `dish`
--
ALTER TABLE `dish`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `governorate`
--
ALTER TABLE `governorate`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `area`
--
ALTER TABLE `area`
  ADD CONSTRAINT `fk_area_governorate` FOREIGN KEY (`governorateID`) REFERENCES `governorate` (`ID`);

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `fk_constraint` FOREIGN KEY (`orderID`) REFERENCES `orders` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orderitems_dish` FOREIGN KEY (`dishID`) REFERENCES `dish` (`ID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`userID`) REFERENCES `user` (`ID`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_table` FOREIGN KEY (`tableID`) REFERENCES `tables` (`ID`),
  ADD CONSTRAINT `fk_reservation_user` FOREIGN KEY (`userID`) REFERENCES `user` (`ID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_area` FOREIGN KEY (`Area`) REFERENCES `area` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
