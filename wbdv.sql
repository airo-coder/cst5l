-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 06:09 PM
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
-- Database: `wbdv`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `timeslot` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `date`, `timeslot`, `subject`, `purpose`, `status`, `created_at`) VALUES
(6, 5, 1, '2025-03-15', '8:00 AM - 9:00 AM', 'GE 11', 'PRACTICE FOR THESES/DISSERTATION DEFENSE', 'pending', '2025-03-13 16:35:39'),
(7, 5, 3, '2025-03-29', '11:00 AM - 12:00 PM', 'GE 8', 'GROUP STUDY', 'pending', '2025-03-13 16:35:50'),
(8, 5, 8, '2025-03-31', '8:30 PM - 9:30 PM', 'GE 11', 'PREPARATION FOR EXAM', 'pending', '2025-03-13 16:36:05'),
(9, 5, 10, '2025-04-24', '5:30 PM - 6:30 PM', 'BSM 312', 'DISCUSSION', 'pending', '2025-03-13 16:36:23');

-- --------------------------------------------------------

--
-- Table structure for table `purposes`
--

CREATE TABLE `purposes` (
  `id` int(11) NOT NULL,
  `purpose_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purposes`
--

INSERT INTO `purposes` (`id`, `purpose_name`, `created_at`) VALUES
(1, 'GROUP STUDY', '2025-03-08 15:54:50'),
(2, 'PROBLEM SOLVING', '2025-03-08 15:54:50'),
(3, 'PROJECT DISCUSSION', '2025-03-08 15:54:50'),
(4, 'RESEARCH PAPER', '2025-03-08 15:54:50'),
(5, 'REPORTING', '2025-03-08 15:54:50'),
(6, 'DISCUSSION', '2025-03-08 15:54:50'),
(7, 'CONDUCT REVIEW', '2025-03-08 15:54:50'),
(8, 'PREPARATION FOR EXAM', '2025-03-08 15:54:50'),
(9, 'PRACTICE FOR THESES/DISSERTATION DEFENSE', '2025-03-08 15:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `equipment` text DEFAULT NULL,
  `floor` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `capacity`, `equipment`, `floor`, `description`, `image`, `created_at`) VALUES
(1, '101', 6, 'LCD Screen, Whiteboard', '1st', 'Ideal for small group discussions and brainstorming sessions', 'room-101.jpg', '2025-03-13 14:27:24'),
(2, '102', 6, 'Projector, Conference Table', '1st', 'Perfect for presentations and team meetings', 'room-102.jpg', '2025-03-13 14:27:55'),
(3, '103', 6, 'LCD Screen, Whiteboard', '1st', 'Compact space with collaborative technology', 'room-103.jpg', '2025-03-13 14:28:24'),
(4, '104', 6, 'Projector, Conference Table', '1st', 'Meeting room with professional presentation setup', 'room-104.jpg', '2025-03-13 14:29:01'),
(5, '105', 6, 'LCD Screen, Whiteboard', '1st', 'Interactive space for creative collaborations', 'room-105.jpg', '2025-03-13 14:29:27'),
(6, '106', 6, 'Projector, Conference Table', '1st', 'Tech-enabled collaboration space with smart board', 'room-106.jpg', '2025-03-13 14:29:51'),
(7, '201', 10, 'Projector, Conference Table', '2nd', 'Large conference-style meeting room', 'room-201.jpg', '2025-03-13 14:30:48'),
(8, '202', 10, 'LCD Screen, Whiteboard', '2nd', 'Spacious collaborative environment with dual displays', 'room-202.jpg', '2025-03-13 14:31:13'),
(9, '203', 10, 'Projector, Conference Table', '2nd', 'Executive meeting room with video conferencing', 'room-203.jpg', '2025-03-13 14:31:36'),
(10, '204', 10, 'LCD Screen, Whiteboard', '2nd', 'Innovation lab with writable walls', 'room-204.jpg', '2025-03-13 14:32:00'),
(11, '205', 10, 'Projector, Conference Table', '2nd', 'Multi-purpose large group collaboration space', 'room-205.jpg', '2025-03-13 14:32:28'),
(12, '206', 10, 'LCD Screen, Whiteboard', '2nd', 'Interactive space for creative collaborations', 'room-206.jpg', '2025-03-13 14:33:16');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `created_at`) VALUES
(1, 'CSE 7', 'CS Professional Elective 1', '2025-03-08 15:54:45'),
(2, 'CS 6', 'Algorithms and Complexity', '2025-03-08 15:54:45'),
(3, 'PAHF 4', 'Dance and Sports 2', '2025-03-08 15:54:45'),
(4, 'BSM 222', 'Linear Algebra', '2025-03-08 15:54:45'),
(5, 'GE 8', 'Readings in Philippine History', '2025-03-08 15:54:45'),
(6, 'CST 5', 'CS Professional Track 2', '2025-03-08 15:54:45'),
(7, 'BSM 312', 'Differential Equations', '2025-03-08 15:54:45'),
(8, 'GE 6', 'Rizal\'s Life and Works', '2025-03-08 15:54:45'),
(9, 'GE 11', 'The Entrepreneurial Mind', '2025-03-08 15:54:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `profile_image`) VALUES
(1, 'Dranreb Jay Arzadon', 'd.arzadon.548554@umindanao.edu.ph', '$2y$10$2w1lo.zXKRiXBkgiUPLHkeWdMC9.T/mdMq5rG3YZKF8u0qrMlrgrK', 'admin', '2025-03-08 18:09:45', 'ajakteman-com (1).png'),
(2, 'Airo Parondo', 'a.parondo.549789@umindanao.edu.ph', '$2y$10$Ca383sYLMa1Cabq/SWoBg.sLHKhLmKpXEmTi8O5RbICt4WmumYdzK', 'admin', '2025-03-09 05:11:02', 'ajakteman-com.png'),
(4, 'Gwendolyn Lianna Peralta', 'g.peralta.548124@umindanao.edu.ph', '$2y$10$O..AZ7JJsPBl7j0xcXPHiOcjgkLFDFBL92nG/MQbU17Q/jIkLwioq', 'admin', '2025-03-13 15:37:43', 'ajakteman-com (2).png'),
(5, 'Yosh Batula', 'y.batula.111111@umindanao.edu.ph', '$2y$10$w4zgJcmlLnycUkETNuCTCu063i1HPYnhFnDjOCe2WHSXcokSQFYrq', 'user', '2025-03-13 15:38:59', 'ajakteman-com (9).png'),
(6, 'Bea Gwyneth', 'b.gwyneth.222222@umindanao.edu.ph', '$2y$10$vZBJmSqvzW0zaDSHw4l7T.mwJVdHCXLd9hRRM43W6KbqBN.POkY5K', 'user', '2025-03-13 15:39:32', 'ajakteman-com (3).png'),
(7, 'Annika Lois', 'a.lois.333333@umindanao.edu.ph', '$2y$10$7Br5bD4AlXqkkWu58w5GUOxSGAYGMmGoFuEWYTYOadtYW1JG/NQoK', 'user', '2025-03-13 15:39:59', 'ajakteman-com (4).png'),
(8, 'Evan Pogi', 'e.pogi.444444@umindanao.edu.ph', '$2y$10$iTm6pXN7bV4wt8I7V5geIeBfY68wCdjVdqqKJMZfYmDfDoaJYc3ra', 'user', '2025-03-13 15:40:41', 'ajakteman-com (5).png'),
(9, 'John Benedict', 'j.benedict.555555@umindanao.edu.ph', '$2y$10$nB2E4mzDtI6DUKDZ5DHIo.j7ncLOr7FLve4xGntLguB3baayCEMra', 'user', '2025-03-13 15:41:13', 'ajakteman-com (6).png'),
(10, 'Charisse Priego', 'c.priego.666666@umindanao.edu.ph', '$2y$10$VK1zq/4AYL4.YNcYs2tpU.qfm6BOzyMPnbWzn1qGh0ctvK0b/PiN.', 'user', '2025-03-13 15:42:00', 'ajakteman-com (7).png'),
(11, 'Candace Ball', 'c.ball.777777@umindanao.edu.ph', '$2y$10$nAQXE9Ngdgin0CvPaE7km..CQGfa0wBNpNJP5HKPq.PjM7oZ0OtUi', 'user', '2025-03-13 15:42:48', 'ajakteman-com (8).png'),
(12, 'Dio Rad', 'd.rad.888888@umindanao.edu.ph', '$2y$10$tHmvgc2HGwGCAbap0v8gQOfXs9Qef4L2R859rHq5wQ.B1nuKedMAa', 'user', '2025-03-13 15:44:20', 'ajakteman-com (10).png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `purposes`
--
ALTER TABLE `purposes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purpose_name` (`purpose_name`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `purposes`
--
ALTER TABLE `purposes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
