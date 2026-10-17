-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2023 at 02:44 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ayojn`
--

-- --------------------------------------------------------

--
-- Table structure for table `offers_order_item`
--

CREATE TABLE `offers_order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `offer_amt` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `final_amt` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `offers_order_item`
--

INSERT INTO `offers_order_item` (`id`, `order_id`, `offer_id`, `offer_amt`, `qty`, `final_amt`, `created_at`) VALUES
(15, 15, 26, 14, 4, 56, '2023-09-24 12:20:29'),
(16, 15, 24, 16, 10, 160, '2023-09-24 12:20:29'),
(17, 16, 33, 32, 20, 640, '2023-09-24 12:35:25'),
(18, 17, 30, 19, 4, 76, '2023-09-24 12:36:58'),
(19, 18, 24, 16, 1, 16, '2023-09-24 12:39:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `offers_order_item`
--
ALTER TABLE `offers_order_item`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `offers_order_item`
--
ALTER TABLE `offers_order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
