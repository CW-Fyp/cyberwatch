-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2025 at 06:07 PM
-- Server version: 10.3.39-MariaDB-log-cll-lve
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dramranc_cyberwatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_log`
--

CREATE TABLE `admin_activity_log` (
  `id` int(11) NOT NULL,
  `admin_username` varchar(100) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_activity_log`
--

INSERT INTO `admin_activity_log` (`id`, `admin_username`, `action`, `description`, `ip_address`, `timestamp`) VALUES
(1, 'qhuzairil', 'Login', 'Admin logged in successfully', '103.5.183.179', '2025-11-27 17:04:09'),
(2, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-27 17:04:09'),
(3, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-27 17:04:42'),
(4, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-27 17:04:46'),
(5, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-27 17:04:58'),
(6, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-27 17:05:06'),
(7, 'qhuzairil', 'Logout', 'Admin logged out', '103.5.183.179', '2025-11-27 17:05:13'),
(8, 'admin', 'Login', 'Admin logged in successfully', '103.5.183.179', '2025-11-27 17:05:25'),
(9, 'admin', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-27 17:05:25'),
(10, 'admin', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-27 17:12:32'),
(11, 'qhuzairil', 'Login', 'Admin logged in successfully', '103.5.183.179', '2025-11-28 00:14:28'),
(12, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-28 00:14:28'),
(13, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-28 00:19:29'),
(14, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-28 00:24:30'),
(15, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-28 00:26:04'),
(16, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.179', '2025-11-28 00:26:22'),
(17, 'qhuzairil', 'Login', 'Admin logged in successfully', '103.5.183.178', '2025-11-29 11:24:08'),
(18, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.178', '2025-11-29 11:24:08'),
(19, 'qhuzairil', 'Dashboard Access', 'Accessed admin dashboard', '103.5.183.178', '2025-11-29 11:25:15'),
(20, 'qhuzairil', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.178', '2025-11-29 11:51:56'),
(21, 'qhuzairil', 'Logout', 'Admin logged out', '103.5.183.178', '2025-11-29 11:52:05'),
(22, 'qhuzairil', 'Login', 'Admin logged in successfully', '103.5.183.178', '2025-11-29 12:05:49'),
(23, 'qhuzairil', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.178', '2025-11-29 12:05:49'),
(24, 'qhuzairil', 'Logout', 'Admin logged out', '103.5.183.178', '2025-11-29 12:05:53'),
(25, 'qhuzairil', 'Login', 'Admin logged in successfully', '103.5.183.178', '2025-11-29 12:06:28'),
(26, 'qhuzairil', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.178', '2025-11-29 12:06:28'),
(27, 'qhuzairil', 'Page Access', 'Accessed admin_logs.php', '103.5.183.178', '2025-11-29 12:06:55'),
(28, 'qhuzairil', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.178', '2025-11-29 12:07:04'),
(29, 'qhuzairil', 'Page Access', 'Accessed admin_actions.php', '103.5.183.178', '2025-11-29 12:07:31'),
(30, 'qhuzairil', 'Export Users', 'Exported users to CSV', '103.5.183.178', '2025-11-29 12:07:31'),
(31, 'qhuzairil', 'Logout', 'Admin logged out', '103.5.183.178', '2025-11-29 12:07:43'),
(32, 'qhuzairil', 'Login', 'Admin logged in successfully', '103.5.183.178', '2025-11-29 12:27:22'),
(33, 'qhuzairil', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.178', '2025-11-29 12:27:22'),
(34, 'qhuzairil', 'Logout', 'Admin logged out', '103.5.183.178', '2025-11-29 12:27:26'),
(35, 'qhuzairil', 'Login', 'Admin logged in successfully', '103.5.183.172', '2025-11-30 13:33:56'),
(36, 'qhuzairil', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.172', '2025-11-30 13:33:56'),
(37, 'admin', 'Login', 'Admin logged in successfully', '103.5.183.172', '2025-11-30 15:22:22'),
(38, 'admin', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.172', '2025-11-30 15:22:22'),
(39, 'admin', 'Page Access', 'Accessed admin_logs.php', '103.5.183.172', '2025-11-30 15:24:46'),
(40, 'admin', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.172', '2025-11-30 15:24:49'),
(41, 'admin', 'Login', 'Admin logged in successfully', '103.5.183.172', '2025-12-01 03:28:21'),
(42, 'admin', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.172', '2025-12-01 03:28:21'),
(43, 'admin', 'Logout', 'Admin logged out', '103.5.183.172', '2025-12-01 03:28:26'),
(44, 'admin', 'Login', 'Admin logged in successfully', '103.5.183.172', '2025-12-01 03:55:03'),
(45, 'admin', 'Page Access', 'Accessed admin_dashboard.php', '103.5.183.172', '2025-12-01 03:55:03'),
(46, 'admin', 'Logout', 'Admin logged out', '103.5.183.172', '2025-12-01 03:55:05'),
(47, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.172', '2025-12-01 04:03:36'),
(48, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.172', '2025-12-01 04:04:43'),
(49, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.172', '2025-12-01 06:42:17'),
(50, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.172', '2025-12-01 16:25:14'),
(51, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.177', '2025-12-01 16:33:23'),
(52, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.172', '2025-12-01 16:33:33'),
(53, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.172', '2025-12-01 16:34:16'),
(54, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.177', '2025-12-01 16:35:12'),
(55, 'admin', 'Logout', 'Admin logged out', '103.5.183.177', '2025-12-01 17:14:08'),
(56, 'admin', 'Login', 'Admin logged in through unified login', '103.5.183.172', '2025-12-01 17:14:43'),
(57, 'admin', 'Logout', 'Admin logged out', '103.5.183.172', '2025-12-01 17:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_name`, `user_email`, `subject`, `message`, `created_at`) VALUES
(1, 'khai', 'khai@gmail', 'hello', 'im being harrased', '2025-11-17 17:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_details` text NOT NULL,
  `date_reported` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','in_review','resolved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `user_id`, `report_details`, `date_reported`, `status`) VALUES
(1, 1, 'xasfdasfdhgfasd', '2025-11-08 12:34:28', 'pending'),
(2, 2, 'adsdafefef', '2025-11-08 12:48:24', 'in_review'),
(3, 3, 'bhbkhb', '2025-11-29 12:31:16', 'pending'),
(4, 3, 'sexual harrestment', '2025-11-30 11:33:53', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id` int(11) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `severity` enum('low','medium','high') DEFAULT 'low',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `security_logs`
--

INSERT INTO `security_logs` (`id`, `event_type`, `username`, `ip_address`, `description`, `severity`, `timestamp`) VALUES
(1, 'Failed Login', 'amir', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:32:15'),
(2, 'Failed Login', 'amir', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:32:21'),
(3, 'Failed Login', 'amir', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:33:11'),
(4, 'Failed Login', 'amir', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:34:22'),
(5, 'Failed Login', 'amir', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:34:49'),
(6, 'Failed Login', 'amir', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:35:07'),
(7, 'Failed Login', 'qhuzairil', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:37:10'),
(8, 'Failed Login', 'qhuzairil', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:38:00'),
(9, 'Failed Login', 'qhuzairil', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:41:39'),
(10, 'Failed Login', 'qhuzairil', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:44:20'),
(11, 'Failed Login', 'qhuzairil', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:52:09'),
(12, 'Failed Login', 'qhuzairil', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:52:26'),
(13, 'Failed Login', 'qhuzairil', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:52:31'),
(14, 'Failed Login', 'admin', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:53:04'),
(15, 'Failed Login', 'qhuzairil@gmail.com', '103.5.183.179', 'Failed admin login attempt', 'high', '2025-11-27 16:53:22'),
(16, 'Failed Login', 'qhuzairil', '103.5.183.178', 'Failed admin login attempt', 'high', '2025-11-29 11:52:29'),
(17, 'Failed Login', 'qhuzairil', '103.5.183.178', 'Failed admin login attempt', 'high', '2025-11-29 11:52:40'),
(18, 'Failed Login', 'qhuzairil', '103.5.183.178', 'Failed admin login attempt', 'high', '2025-11-29 11:53:09'),
(19, 'Failed Login', 'admin', '103.5.183.178', 'Failed admin login attempt', 'high', '2025-11-29 11:53:27'),
(20, 'Failed Login', 'amir', '103.5.183.178', 'Failed admin login attempt', 'high', '2025-11-29 12:06:14'),
(21, 'Failed Login', 'amir', '103.5.183.172', 'Failed admin login attempt', 'high', '2025-11-30 15:21:48'),
(22, 'Successful Login', 'admin', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 04:03:36'),
(23, 'Successful Login', 'emran', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 04:04:23'),
(24, 'Successful Login', 'admin', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 04:04:43'),
(25, 'Failed Login', 'admin', '103.5.183.172', 'Incorrect password entered', 'medium', '2025-12-01 06:41:58'),
(26, 'Failed Login', 'admin', '103.5.183.172', 'Incorrect password entered', 'medium', '2025-12-01 06:42:03'),
(27, 'Successful Login', 'admin', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 06:42:17'),
(28, 'Failed Login', 'admin', '103.5.183.172', 'Incorrect password entered', 'medium', '2025-12-01 06:42:34'),
(29, 'Successful Login', 'amir', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 06:42:49'),
(30, 'Successful Login', 'admin', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 16:25:14'),
(31, 'Successful Login', 'admin', '103.5.183.177', 'User logged in successfully', 'low', '2025-12-01 16:33:23'),
(32, 'Successful Login', 'admin', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 16:33:33'),
(33, 'Successful Login', 'admin', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 16:34:16'),
(34, 'Successful Login', 'admin', '103.5.183.177', 'User logged in successfully', 'low', '2025-12-01 16:35:12'),
(35, 'Successful Login', 'admin', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 17:14:43'),
(36, 'Successful Login', 'amir', '103.5.183.172', 'User logged in successfully', 'low', '2025-12-01 17:15:10');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_alerts`
--

CREATE TABLE `system_alerts` (
  `id` int(11) NOT NULL,
  `alert_type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `severity` enum('low','medium','high','critical') DEFAULT 'medium',
  `resolved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES
(1, 'site_name', 'CyberWatch', 'Website name', '2025-11-27 16:32:05'),
(2, 'maintenance_mode', '0', 'Enable maintenance mode', '2025-11-27 16:32:05'),
(3, 'session_timeout', '60', 'Session timeout in minutes', '2025-11-27 16:32:05'),
(4, 'max_login_attempts', '5', 'Maximum login attempts', '2025-11-27 16:32:05'),
(5, 'enable_registration', '1', 'Enable user registration', '2025-11-27 16:32:05'),
(6, 'enable_2fa', '0', 'Enable two-factor authentication', '2025-11-27 16:32:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','moderator','admin') DEFAULT 'user',
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `last_login`, `created_at`, `updated_at`, `is_admin`) VALUES
(1, 'admin', 'admin@cyberwatch.com', 'admin123', 'admin', 'active', '2025-12-01 17:14:43', '2025-11-27 16:50:28', '2025-12-01 17:14:43', 1),
(2, 'qhuzairil', 'qhuzairil@gmail.com', 'password123', 'admin', 'active', '2025-11-30 13:33:56', '2025-11-27 16:50:28', '2025-11-30 13:33:56', 1),
(3, 'amir', 'amir@gmail.com', 'amir123', 'moderator', 'active', '2025-12-01 17:15:10', '2025-11-29 12:25:57', '2025-12-01 17:15:10', 0),
(4, 'emran', 'emran@gmail.com', 'password123', 'user', 'active', '2025-12-01 04:04:23', '2025-11-30 13:37:20', '2025-12-01 04:04:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_backup`
--

CREATE TABLE `users_backup` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` enum('user','moderator','admin') DEFAULT 'user',
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_backup`
--

INSERT INTO `users_backup` (`id`, `username`, `email`, `role`, `status`, `last_login`, `password`, `created_at`, `updated_at`, `is_admin`) VALUES
(1, 'amir', NULL, 'admin', 'active', NULL, '$2y$10$.cOHzj3/oC9zke.b2Ye0rOJ4kSy9I2a/5bu0Jm3B50vbG2Esl/O8.', '2025-11-08 12:18:23', '2025-11-27 16:34:08', 1),
(2, 'emran', NULL, 'user', 'active', NULL, '$2y$10$HI6BFO.B205Xu512YsV0reYnnv3dgwIR21yw9eLBi8BzJVYboOdGy', '2025-11-08 12:47:59', '2025-11-27 16:30:36', 0),
(3, 'amran', NULL, 'user', 'active', NULL, '$2y$10$CtR7MUODrCh/udVL10SugeGIi8gd9rjRr5BYSnSa8OHK64OO7UTYu', '2025-11-09 06:09:49', '2025-11-27 16:30:36', 0),
(6, 'akmal', '', 'user', 'active', NULL, '$2y$10$gh1bfPiT.EHkq2NkfXq4H.L8TsKz.BXGQW08l66PFbtfCxaxvc9Ry', '2025-11-26 17:03:25', '2025-11-27 16:30:36', 0),
(7, 'qhuzairil', 'qhuzairil@gmail.com', 'admin', 'active', NULL, '$2y$10$nec6/gZZcFmHngivWsVYDeuSK6O/fTR0rMFnYFCOaOKIe0ZO3ts9e', '2025-11-27 16:35:54', '2025-11-27 16:36:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_clean`
--

CREATE TABLE `users_clean` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','moderator','admin') DEFAULT 'user',
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users_clean`
--

INSERT INTO `users_clean` (`id`, `username`, `email`, `password`, `role`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@cyberwatch.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NULL, '2025-11-27 16:50:50', '2025-11-27 16:50:50'),
(2, 'qhuzairil', 'qhuzairil@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NULL, '2025-11-27 16:50:50', '2025-11-27 16:50:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_date_reported` (`date_reported`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_alerts`
--
ALTER TABLE `system_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users_backup`
--
ALTER TABLE `users_backup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`);

--
-- Indexes for table `users_clean`
--
ALTER TABLE `users_clean`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `system_alerts`
--
ALTER TABLE `system_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1897;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users_backup`
--
ALTER TABLE `users_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_clean`
--
ALTER TABLE `users_clean`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users_backup` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_backup` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
