-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2025 at 08:55 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpcrm_free`
--

-- --------------------------------------------------------

--
-- Table structure for table `phpcrm_customers`
--

CREATE TABLE `phpcrm_customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_code` varchar(50) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `company_reg_no` varchar(100) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `employees` varchar(20) DEFAULT NULL,
  `user_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phpcrm_customers`
--

INSERT INTO `phpcrm_customers` (`id`, `customer_code`, `name`, `email`, `phone`, `company_name`, `company_reg_no`, `company_address`, `country`, `role`, `employees`, `user_status`, `created_at`, `updated_at`) VALUES
(3, 'CUST-0182', 'Test Customer', 'test@gmail.com', '(888) 888-8888', 'Test', 'Kobu', 'c-81, kv2 Sector 82 Noida', 'India', 'Sr Manager', '1-10', 'active', '2025-11-29 14:31:44', '2025-12-01 07:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `phpcrm_leads`
--

CREATE TABLE `phpcrm_leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `lead_source` varchar(100) DEFAULT NULL,
  `status` enum('new','contacted','qualified','lost') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phpcrm_leads`
--

INSERT INTO `phpcrm_leads` (`id`, `name`, `email`, `phone`, `company_name`, `lead_source`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Test Lead', 'test@gmail.com', '(888) 888-8888', 'Kobu', 'dd', 'contacted', '2025-11-29 15:25:13', '2025-12-01 07:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `phpcrm_notes`
--

CREATE TABLE `phpcrm_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `note` text NOT NULL,
  `added_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phpcrm_tasks`
--

CREATE TABLE `phpcrm_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `followup_date` datetime NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phpcrm_users`
--

CREATE TABLE `phpcrm_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phpcrm_users`
--

INSERT INTO `phpcrm_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `status`, `created_at`) VALUES
(1, 'admin@phpcrm.com', 'admin@phpcrm.com', '$2y$10$Hu4FiITudYgpDGVA8/u5yeFSzIG3xifeA38Lsk8RACYkxASL4.JcW', 'Administrator', 1, '2025-11-29 12:19:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `phpcrm_customers`
--
ALTER TABLE `phpcrm_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `phpcrm_leads`
--
ALTER TABLE `phpcrm_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpcrm_notes`
--
ALTER TABLE `phpcrm_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpcrm_tasks`
--
ALTER TABLE `phpcrm_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpcrm_users`
--
ALTER TABLE `phpcrm_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `phpcrm_customers`
--
ALTER TABLE `phpcrm_customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phpcrm_leads`
--
ALTER TABLE `phpcrm_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phpcrm_notes`
--
ALTER TABLE `phpcrm_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phpcrm_tasks`
--
ALTER TABLE `phpcrm_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phpcrm_users`
--
ALTER TABLE `phpcrm_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
