-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2026 at 10:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `on`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_assets`
--

CREATE TABLE `add_assets` (
  `id` int(225) NOT NULL,
  `assetid` varchar(100) NOT NULL,
  `categories` varchar(100) NOT NULL,
  `pod` date NOT NULL,
  `processor` varchar(100) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `hdd_sdd` varchar(50) DEFAULT NULL,
  `serial_no` varchar(100) NOT NULL,
  `model_no` varchar(100) NOT NULL,
  `screen_size` varchar(50) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `asset_condition` varchar(100) DEFAULT NULL,
  `warranty` varchar(100) DEFAULT NULL,
  `creat_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_assets`
--

INSERT INTO `add_assets` (`id`, `assetid`, `categories`, `pod`, `processor`, `ram`, `hdd_sdd`, `serial_no`, `model_no`, `screen_size`, `brand`, `asset_condition`, `warranty`, `creat_at`) VALUES
(2, '41', 'laptop', '2026-01-14', 'i5', '16gb', '226', '38', '33', '40px', NULL, NULL, NULL, '2026-01-17 18:48:05'),
(3, 'kbch009', 'laptop', '2026-01-18', 'i5', '16gb', '226', 'ijh1234', 'v14', '14\" ', NULL, NULL, NULL, '2026-01-18 12:41:12'),
(4, 'deskt', 'Desktop', '2026-01-17', 'i5', '16gb', '222', 'ju651', 'v14', '12', NULL, NULL, NULL, '2026-01-18 12:42:04'),
(5, 'prt01', 'printer', '2026-01-17', '', '', '', 'unb1234', 'hp128fn', '', NULL, NULL, NULL, '2026-01-18 12:42:51'),
(6, 'kbclap1', 'laptop', '2026-01-01', 'i5', '16gb', '226', 'rt321', 'erw123', '14\" ', NULL, NULL, NULL, '2026-01-18 12:45:42'),
(7, 'printer1', 'printer', '2026-01-22', '', '', '', '110', '011', '', 'hp', 'Used', 'no', '2026-01-21 17:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `created_at`) VALUES
(1, 'hp', '2026-01-21 17:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(2, 'laptop'),
(3, 'tab'),
(4, 'Mobile'),
(5, 'printer'),
(6, 'Desktop');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department`) VALUES
(5, 'Hr'),
(6, 'abc');

-- --------------------------------------------------------

--
-- Table structure for table `issue_asset`
--

CREATE TABLE `issue_asset` (
  `id` int(11) NOT NULL,
  `asset_id` varchar(100) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `department` varchar(100) NOT NULL,
  `categories` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `employee_code` varchar(50) NOT NULL,
  `doj` date NOT NULL,
  `status` enum('Active','Non-Active') DEFAULT 'Active',
  `is_returned` enum('No','Yes') DEFAULT 'No',
  `issue_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_asset`
--

INSERT INTO `issue_asset` (`id`, `asset_id`, `employee_name`, `department`, `categories`, `designation`, `employee_code`, `doj`, `status`, `is_returned`, `issue_date`) VALUES
(1, '41', NULL, 'empolay', 'laptop', '234234', '872387', '2026-01-13', 'Active', 'Yes', '2026-01-17 18:50:47'),
(2, 'kbclap1', NULL, 'Purchase', 'laptop', 'executive', 'jhy12', '2026-01-01', 'Active', 'No', '2026-01-18 12:47:06'),
(3, 'kbch009', NULL, 'Hr', 'laptop', 'jr', '0001', '2026-01-09', 'Active', 'No', '2026-01-18 12:49:40'),
(4, '41', 'Bablu', 'Hr', 'laptop', '23', 'em-12', '2026-01-21', 'Active', 'Yes', '2026-01-21 17:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `pending_requests`
--

CREATE TABLE `pending_requests` (
  `id` int(11) NOT NULL,
  `requester_id` int(11) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `request_type` enum('UPDATE','DELETE') DEFAULT NULL,
  `data_json` text DEFAULT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_requests`
--

INSERT INTO `pending_requests` (`id`, `requester_id`, `target_id`, `request_type`, `data_json`, `status`, `created_at`) VALUES
(1, 2, 1, 'UPDATE', '{\"name\":\"User\",\"email\":\"user1@gmail.com\",\"user_type\":\"admin\",\"password\":\"$2y$10$g5I6k11Gn0PmTF5YYzcFGO1GsxIkY0nv7TfMLGj4w69WGnbbPUCxy\"}', 'REJECTED', '2026-01-22 15:33:50'),
(2, 1, 4, 'UPDATE', '{\"name\":\"rahul1\",\"email\":\"koon011@gmial.com\",\"user_type\":\"admin\",\"password\":\"$2y$10$4RQjLRqCda0LvE5s7NfH1e3UmQtkZeZTVNpj20MY5UerDPj.jfvf2\"}', 'APPROVED', '2026-01-22 16:08:57');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','user') NOT NULL DEFAULT 'user',
  `creat_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `user_type`, `creat_time`) VALUES
(1, 'User', 'user1@gmail.com', '$2y$10$uDInUhrjW.8VtS7hNNNABuhFjzCmiujMzEQECSU0QaFxqyxAhZVDu', 'user', '2026-01-17 16:37:31'),
(2, 'admin', 'admin@gmail.com', '$2y$10$YtnG8IyzvYOmk6CH1FbwS.YNM1pgThrANMJzjzYgn72EMMLiyDjWi', 'admin', '2026-01-17 16:49:20'),
(3, 'Rahul', 'rahul@gmail.com', 'rahul@gmail.com', 'user', '2026-01-18 16:04:41'),
(4, 'rahul1', 'koon011@gmial.com', '$2y$10$4RQjLRqCda0LvE5s7NfH1e3UmQtkZeZTVNpj20MY5UerDPj.jfvf2', 'user', '2026-01-18 16:06:13'),
(5, 'RRahul@gmail.com', 'RRahul@gmail.com', '$2y$10$MLV8bFDBK6zFTeia2kt.l.DPo3bSGdHKeUbsSQJFAxfXQy8CteXu2', 'admin', '2026-01-21 16:33:55'),
(6, 'Bilu', 'Bilu@gmail.com', '$2y$10$EutpiHu/Rk9gJ3a/49q6K.hftHyBtOQgQu2/HnxQ3t3dfLo6GAOAm', 'admin', '2026-01-21 17:12:39');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_creation`
--

CREATE TABLE `vendor_creation` (
  `id` int(225) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_owner` varchar(100) NOT NULL,
  `mail_id` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `creat_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_creation`
--

INSERT INTO `vendor_creation` (`id`, `company_name`, `company_owner`, `mail_id`, `address`, `pin_code`, `mobile_no`, `creat_at`) VALUES
(1, 'anbc', 'ramu', 'sushantkoon501@gmail.com', 'isbt43', '6567576', '7018584903', '2026-01-17 17:45:38'),
(3, 'Ezee Computer', 'Devnder SIngh', 'eze@gmail.com', 'rose garden', '123123', '9876543211', '2026-01-18 12:33:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_assets`
--
ALTER TABLE `add_assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_asset`
--
ALTER TABLE `issue_asset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_requests`
--
ALTER TABLE `pending_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendor_creation`
--
ALTER TABLE `vendor_creation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_assets`
--
ALTER TABLE `add_assets`
  MODIFY `id` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `issue_asset`
--
ALTER TABLE `issue_asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pending_requests`
--
ALTER TABLE `pending_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor_creation`
--
ALTER TABLE `vendor_creation`
  MODIFY `id` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
