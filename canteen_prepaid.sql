-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2025 at 07:18 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canteen_prepaid`
--

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_status` enum('Pending','Completed','Cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pizza` int(11) DEFAULT '0',
  `burger` int(11) DEFAULT '0',
  `milkshake` int(11) DEFAULT '0',
  `cake` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `item_name`, `quantity`, `total_price`, `order_status`, `order_date`, `pizza`, `burger`, `milkshake`, `cake`) VALUES
(1, 'Pizza', 2, '300.00', 'Pending', '2025-03-07 15:15:09', 0, 0, 0, 0),
(4, '', 0, '160.00', 'Pending', '2025-03-07 15:41:31', 0, 2, 0, 0),
(5, '', 0, '160.00', 'Pending', '2025-03-07 15:42:19', 0, 2, 0, 0),
(6, '', 0, '750.00', 'Pending', '2025-03-07 15:42:27', 0, 0, 0, 5),
(7, '', 0, '750.00', 'Pending', '2025-03-07 15:42:35', 0, 0, 0, 5),
(8, '', 0, '300.00', 'Pending', '2025-03-08 06:43:05', 0, 0, 3, 0),
(9, '', 0, '160.00', 'Pending', '2025-03-08 07:05:21', 0, 2, 0, 0),
(10, '', 0, '120.00', 'Pending', '2025-03-08 07:27:15', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `amount`, `item`, `type`, `created_at`) VALUES
(2, 2, '100.00', '', '', '2025-02-11 13:07:51'),
(3, 2, '50.00', '', '', '2025-02-11 13:49:55'),
(4, 2, '50.00', '', '', '2025-02-11 14:15:14'),
(5, 2, '50.00', '', 'credit', '2025-02-11 14:26:32'),
(6, 6, '50.00', '', 'debit', '2025-02-12 18:07:42'),
(7, 6, '500.00', '', '', '2025-02-12 18:24:33'),
(8, 2, '100.00', '', 'credit', '2025-02-19 13:22:36'),
(9, 6, '100.00', '', 'credit', '2025-02-19 13:25:46'),
(10, 6, '100.00', '', 'credit', '2025-02-19 13:26:20'),
(11, 6, '100.00', '', 'credit', '2025-02-19 13:27:12'),
(12, 2, '400.00', '', 'credit', '2025-02-19 13:32:05'),
(13, 6, '400.00', '', 'credit', '2025-02-19 13:33:21'),
(14, 5, '100.00', '', '', '2025-02-22 09:48:46'),
(15, 2, '100.00', 'Burger', 'debit', '2025-03-03 15:21:58'),
(16, 2, '100.00', 'Burger', 'debit', '2025-03-03 15:21:59'),
(17, 2, '100.00', 'Burger', 'debit', '2025-03-03 15:22:29');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `after_transaction_insert` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    IF NEW.type = 'credit' THEN
        -- Increase balance for deposits
        UPDATE users 
        SET balance = balance + NEW.amount 
        WHERE id = NEW.user_id;
    ELSEIF NEW.type = 'debit' THEN
        -- Decrease balance for purchases
        UPDATE users 
        SET balance = balance - NEW.amount 
        WHERE id = NEW.user_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `trigger_logs`
--

CREATE TABLE `trigger_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trigger_logs`
--

INSERT INTO `trigger_logs` (`id`, `user_id`, `amount`, `type`, `created_at`) VALUES
(1, 2, '50.00', '', '2025-02-11 14:15:14'),
(2, 2, '50.00', 'debit', '2025-02-11 14:26:32'),
(3, 6, '50.00', 'debit', '2025-02-12 18:07:42'),
(4, 6, '500.00', 'debit', '2025-02-12 18:24:33'),
(5, 2, '100.00', 'debit', '2025-02-19 13:22:36'),
(6, 6, '100.00', 'debit', '2025-02-19 13:25:46'),
(7, 6, '100.00', 'debit', '2025-02-19 13:26:20'),
(8, 6, '100.00', 'debit', '2025-02-19 13:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(10,2) DEFAULT '0.00',
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `history` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `balance`, `qr_code`, `history`) VALUES
(2, 'ragavi', 'ragavisekar2802@gmail.com', '2802', '350.00', 'qrcodes/ragavi.png', NULL),
(3, 'viji', 'vijisekar@gmail.com', '$2y$10$cvciP0S2660tzqIiGNTuqOKb0mLCrS0SEjgeswF8EQR7oPcPdN2rS', '450.00', 'qrcodes/viji.png', NULL),
(4, 'janasri', 'janasri@gmail.com', '$2y$10$cOzlIlbJ37Il5BmfgFgNwO/aX1ObsNlfjVJKPf4cGf3CJ3Hahw60O', '200.00', 'qrcodes/janasri.png', NULL),
(5, 'eva', 'eva@gmail.com', '$2y$10$WYYYIPyVotbn3uk/qFmmmOeCpZ9rXsg/R8eZeXnYi40hNa709XmkG', '1600.00', 'qrcodes/user_5.png', NULL),
(6, 'kowsi', 'kowsi@gmail.com', '$2y$10$Qbd8i3vRobavYhOaBcZ.6ON8gOuUmQeyv5VFOkMgoCWMiTvPIBvcK', '450.00', 'qrcodes/user_6.png', NULL),
(7, 'amee', 'amee@gmail.com', '$2y$10$2uAkLev00ljsqzbiX9bM3eAkCATKDjNYN7wioNIH1Rw8FXhBaSZDu', '1000.00', NULL, NULL),
(8, 'dharani', 'dharani@gmail.com', '$2y$10$THmmhpc0eFhAEwOzwZ4EUeY4zxozRdIE8gIHCm7EhcWLwi1HxjoFe', '0.00', 'qr_codes/8.png', NULL),
(9, 'ragavisekar', 'ragavisekar@gmail.com', '$2y$10$ty.qTYDhn8Q30X1dBbApL.aVLMWWeFzdTe17.Xov7hv0FRUtML8/y', '0.00', 'qr_codes/9.png', NULL),
(10, 'roji', 'roji@gmail.com', '$2y$10$rIoi8rfR8Y87QMnCsEENgOa3ubrSNFfFSbN0qu48U2/RDCf8oBOw2', '0.00', 'qr_codes/10.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `trigger_logs`
--
ALTER TABLE `trigger_logs`
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
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `trigger_logs`
--
ALTER TABLE `trigger_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
