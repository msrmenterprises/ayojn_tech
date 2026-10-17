-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2023 at 02:43 PM
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
-- Table structure for table `offers_order`
--

CREATE TABLE `offers_order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pay_amt` int(11) NOT NULL,
  `wallet` int(11) NOT NULL,
  `total_amt` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `offers_order`
--

INSERT INTO `offers_order` (`id`, `user_id`, `pay_amt`, `wallet`, `total_amt`, `created_at`) VALUES
(15, 1427, 216, 150, 66, '2023-09-24 12:20:29'),
(16, 1427, 640, 0, 640, '2023-09-24 12:35:25'),
(17, 1427, 76, 0, 76, '2023-09-24 12:36:58'),
(18, 1427, 16, 0, 16, '2023-09-24 12:39:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `offers_order`
--
ALTER TABLE `offers_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `offers_order`
--
ALTER TABLE `offers_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
