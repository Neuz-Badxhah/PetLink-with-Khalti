-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2024 at 10:48 AM
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
-- Database: `pet_link_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `buy_form_data`
--

CREATE TABLE `buy_form_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `do_you_work` enum('yes','no') NOT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `owned_pets` enum('yes','no') NOT NULL,
  `pet_types` varchar(255) DEFAULT NULL,
  `ownership_duration` varchar(255) DEFAULT NULL,
  `reason_no_pet` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buy_form_data`
--

INSERT INTO `buy_form_data` (`id`, `user_id`, `pet_id`, `name`, `email`, `contact`, `address`, `do_you_work`, `occupation`, `owned_pets`, `pet_types`, `ownership_duration`, `reason_no_pet`, `status`) VALUES
(30, 42, 39, 'Neuz Badxhah', 'jiban@gmail.com', '986658645', 'Nepal', 'no', NULL, 'no', NULL, NULL, NULL, 'approved'),
(31, 42, 39, 'Neuz Badxhah', 'jiban@gmail.com', '986658645', 'Nepal', 'no', NULL, 'no', NULL, NULL, NULL, 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pet_id`, `quantity`, `created_at`) VALUES
(31, 42, 39, 1, '2024-07-04 02:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `petdetails`
--

CREATE TABLE `petdetails` (
  `id` int(11) NOT NULL,
  `pet_image` varchar(255) NOT NULL,
  `pet_video` varchar(255) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `pet_age` int(11) NOT NULL,
  `pet_size` varchar(50) NOT NULL,
  `pet_type` enum('cat','dog','other') NOT NULL,
  `pet_breed` varchar(100) NOT NULL,
  `health_status` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('approved','pending','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petdetails`
--

INSERT INTO `petdetails` (`id`, `pet_image`, `pet_video`, `pet_name`, `pet_age`, `pet_size`, `pet_type`, `pet_breed`, `health_status`, `description`, `upload_date`, `price`, `user_id`, `status`) VALUES
(39, 'uploads/trial image.jpg', 'uploads/trial vide.mp4', 'Lucy', 2, 'medium', 'dog', 'Husky', 'good', 'Friendly nature', '2024-07-04 02:56:23', 6000, 42, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `purchase_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `address`, `contact`, `email`, `password`, `profile_picture`) VALUES
(42, 'Neuz Badxhah', 'Nepal', '986658645', 'jiban@gmail.com', '$2y$10$1L3cwM9lvmoMduT/VO8F2.awIR.jsPL2A8PjkR26vDi.liv./3ur6', 'profile_66851a6ae719b2.23005093.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buy_form_data`
--
ALTER TABLE `buy_form_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `petdetails`
--
ALTER TABLE `petdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_petdetails_user` (`user_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buy_form_data`
--
ALTER TABLE `buy_form_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `petdetails`
--
ALTER TABLE `petdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buy_form_data`
--
ALTER TABLE `buy_form_data`
  ADD CONSTRAINT `buy_form_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `petdetails` (`id`);

--
-- Constraints for table `petdetails`
--
ALTER TABLE `petdetails`
  ADD CONSTRAINT `fk_petdetails_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `petdetails` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
