-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 03:58 PM
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
-- Database: `user_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('Project Manager','Team Lead','Developer') DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `deleted_at`, `created_at`) VALUES
(1, 'Alex', 'alex@example.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Project Manager', NULL, '2025-05-21 05:17:08'),
(6, 'Joel Jangam', 'joel@example.com', 'c52331dd6fae697dbfa3954c00600b46', 'Team Lead', NULL, '2025-05-21 05:49:11'),
(7, 'Praveen Jangam', 'praveen@example.com', '015e09fda27aee6e76a6067ad2839d43', 'Developer', '2025-05-21 11:30:48', '2025-05-21 05:49:11'),
(8, 'Durga Pratap', 'durga@example.com', '2904a936068a124774e77463ac472ffc', 'Developer', '2025-05-21 11:30:48', '2025-05-21 05:49:11'),
(9, 'Shannon Jangam', 'shannon@example.com', '00521f3fd616a11163aec04b2ba96830', 'Developer', NULL, '2025-05-21 06:01:48'),
(10, 'Ram', 'ram@example.com', '6a557ed1005dddd940595b8fc6ed47b2', 'Developer', '2025-05-21 12:13:59', '2025-05-21 06:16:10'),
(11, 'Vinay', 'vinay@example.com', '78ffb54cea01b365797d0b883eba44fc', 'Developer', '2025-05-21 12:13:59', '2025-05-21 06:16:10'),
(12, 'Viviana', 'viviana@example.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Developer', NULL, '2025-05-21 11:15:03'),
(13, 'Joe Doe', 'jane@example.com', '81dc9bdb52d04dc20036dbd8313ed055', 'Team Lead', '2025-05-21 18:29:07', '2025-05-21 12:54:19');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
