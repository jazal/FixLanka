-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2025 at 07:13 PM
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
-- Database: `fixlanka`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location_lat` float DEFAULT NULL,
  `location_lng` float DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','In Progress','Resolved','Rejected') DEFAULT 'Pending',
  `ref_number` varchar(100) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `user_id`, `dept_id`, `title`, `description`, `location_lat`, `location_lng`, `media_path`, `status`, `ref_number`, `rejection_reason`, `created_at`) VALUES
(2, 2, 1, 'boken wire', 'fix this ', 7.17887, 80.5013, 'uploads/ChatGPT Image May 22, 2025, 09_10_42 PM.png', 'Resolved', 'FL-4885A983', NULL, '2025-05-27 14:29:41'),
(4, 2, NULL, 'abc', '123', 6.99597, 81.0451, 'uploads/Image20241217213249.png', 'In Progress', 'FL-1872A558', NULL, '2025-05-31 13:35:34'),
(5, 4, 1, 'aaa', 'asdasd', 6.46751, 80.6035, 'uploads/dining (2).jpg', 'Resolved', 'FL-60761EEF', NULL, '2025-06-03 14:03:40'),
(6, 5, NULL, 'abcd', 'ajsdiassbasknd', 7.47633, 80.3769, 'uploads/Trending T Rajendar Reaction Green Screen Transprent Video _ Tamil Comedy Memes.mp4', 'Pending', 'FL-6C1DE2B1', NULL, '2025-06-04 03:58:39'),
(11, 4, 1, 'zsda', 'asdasd', 6.90094, 79.8917, 'uploads/download (2).png', 'In Progress', 'FL-4D6A1ECD', NULL, '2025-06-04 04:18:10'),
(12, 6, 1, 'current ila', 'patta case madawalela', 6.90094, 79.8917, 'uploads/Trending T Rajendar Reaction Green Screen Transprent Video _ Tamil Comedy Memes.mp4', 'Rejected', 'FL-57D4D30A', 'sdfsdf', '2025-06-04 04:54:13'),
(13, 7, 1, 'light kanu case', 'fix thius quike ', 7.3377, 80.6414, 'uploads/WhatsApp Image 2025-06-19 at 18.29.56_0af0b39a.jpg', 'Pending', 'FL-AE44E7EB', NULL, '2025-06-19 16:31:05'),
(15, 2, 5, 'road broken ', 'fix the road ', 6.90422, 79.8949, 'uploads/WhatsApp Image 2025-06-04 at 09.26.43_770ce0a7.jpg', 'Rejected', 'FL-BC2C579D', 'this is not our fild', '2025-06-25 07:58:27'),
(17, 2, 5, 'asdsaxasx', 'asd', 0, 0, 'uploads/Image20241217213249.png', 'Pending', 'FL-73699DF8', NULL, '2025-06-30 16:47:40'),
(18, 2, 5, 'asas', 'asas', 6.82557, 79.8687, 'uploads/Image20241217213249.png', 'In Progress', 'FL-0490E603', NULL, '2025-06-30 16:49:43'),
(19, 2, 6, 'asdasdb', 'babshja', 6.98862, 81.0514, 'uploads/WhatsApp Image 2025-07-01 at 20.43.17_5abeb18c.jpg', 'Rejected', 'FL-1AE1DA67', 'jinj', '2025-07-01 16:32:43');

--
-- Triggers `complaints`
--
DELIMITER $$
CREATE TRIGGER `before_insert_ref_number` BEFORE INSERT ON `complaints` FOR EACH ROW BEGIN
  SET NEW.ref_number = CONCAT('FL-', UPPER(SUBSTRING(MD5(RAND()), 1, 8)));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'department'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`dept_id`, `dept_name`, `description`, `contact_email`, `password`, `logo`, `status`, `created_at`, `role`) VALUES
(1, 'Ceylon  Electric Board ', 'The Ceylon Electricity Board - CEB (Sinhala: ලංකා විදුලිබල මණ්ඩලය - ලංවිම, romanized: Lankā Vidulibala Mandalaya - Lanwima; Tamil: இலங்கை மின்சார சபை - இமிச), was the largest electricity company in Sri Lanka. With a market share of nearly 100%, it controlled all major functions of electricity generation, transmission, distribution and retailing in Sri Lanka.', 'CEB@gmail.com', '$2y$10$7E/lVsJy6vex6zrGW0bkouSyl3ipSaYchtgxjXYASgwbUXY0U3W8O', 'uploads/Ceylon-Electricity-Board.png', 'active', '2025-05-24 14:06:39', 'department'),
(5, 'Police SriLanka', 'Public safety, illegal activities & reporting', 'Srilanka.police@gmail.com', '$2y$10$gBuhO8Ga/Sp85ObTqOV6wurBLmq401YaD4M4FY/3Lqmmlm.2nLfZK', 'uploads/POLICE.png', 'active', '2025-06-23 00:24:00', 'department'),
(6, 'Road Development Authority (RDA)', 'Handles road repairs, maintenance & construction', 'rda.srilanka@gmail.com', '$2y$10$7oUKGModn2e1rDJZ8M1ZD.Fv.1RGwzFtP1bsezwnAImrhwElB8dJG', 'uploads/RDA.png', 'active', '2025-06-23 00:25:11', 'department'),
(7, 'Road Development Authority (RDA)', 'Handles road repairs, maintenance & construction', 'rda.srilanka@gmail.com', '$2y$10$r6.peQNlCwNL3ARaInD4DunLFQhSdttiv9l2uwecUwcBuZdseA8lm', 'uploads/RDA.png', 'active', '2025-06-23 00:25:14', 'department'),
(8, 'Police SriLanka', 'Public safety, illegal activities & reporting', 'Srilanka.police@gmail.com', '$2y$10$8lV43iokp4.fa2YNugfE0uT1VF1HY45a9fswSfRzU3DEHnMUeTYUa', 'uploads/POLICE.png', 'active', '2025-06-23 00:25:19', 'department'),
(9, 'National Water Supply & Drainage Board (NWSDB)', 'Water leaks, supply cuts, and pipe issues', 'nwsdb.srilanka@gmail.com', '$2y$10$IwC22Y9lW2mE0ts096GGDOfy6yGZsMDtHBliMIaZdQiIOyr3hWdWy', 'uploads/NWSDB.png', 'active', '2025-06-23 00:26:09', 'department'),
(10, 'Department of Motor Traffic (DMT)', 'Issues related to vehicle registration & license', 'dmt.gov.lk@gmail.com', '$2y$10$Hdfswc3DzmJ2/gryYo9En.ofnVP1OAvPLeSimsRq0tTKZUhsDgT6G', '', 'active', '2025-06-23 00:27:25', 'department'),
(11, 'Sri Lanka Railways', 'Railway track and station complaints', 'slr.railways@gmail.com', '$2y$10$4D08hUgBtYHCmAZYlnXBc.zqxwSGic6YTwgTgfdm9MCHvAbsk0Jv.', 'uploads/SLR.png', 'active', '2025-06-23 00:28:39', 'department'),
(12, 'Disaster Management Center (DMC)', 'Handles flood, landslide, and emergency cases', 'dmc.srilanka@gmail.com', '$2y$10$fEuv/aFVAcV00XUWnJZ.5.6fPdM/VLJTq1kZ5NNASgeiuM5DuQHRq', 'uploads/DMC.png', 'active', '2025-06-23 00:29:15', 'department'),
(13, 'Ministry of Health (MOH)', 'Health complaints, sanitation & dengue control', 'moh.srilanka@gmail.com', '$2y$10$loh0sX4JgdVxEP2KRDWgSeCOTBWUVtldhukQJlaAcyV0pTJEvNg9G', 'uploads/MOH.png', 'active', '2025-06-23 00:30:01', 'department');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_requests`
--

CREATE TABLE `password_reset_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requested_password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_requests`
--

INSERT INTO `password_reset_requests` (`id`, `user_id`, `username`, `email`, `description`, `requested_password`, `created_at`, `read`) VALUES
(4, 4, 'aathi', 'aathiyaaz@gmail.com', 'sdsd', 'umar@123', '2025-07-01 22:12:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ref_number` varchar(50) NOT NULL,
  `before_image` varchar(255) DEFAULT NULL,
  `after_image` varchar(255) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `ref_number`, `before_image`, `after_image`, `review_text`, `created_at`) VALUES
(12, 2, 'FL-1872A558', 'uploads/reviews/WhatsApp Image 2025-06-19 at 11.50.18_d6bc4b94.jpg', 'uploads/reviews/WhatsApp Image 2025-06-19 at 18.29.56_0af0b39a.jpg', 'axw', '2025-06-21 14:22:19'),
(13, 2, 'FL-1872A558', 'uploads/reviews/WhatsApp_Image_2025-06-16_at_20.08.15_c6085619-removebg-preview.png', 'uploads/reviews/WhatsApp Image 2025-06-19 at 17.58.21_99b9a86e.jpg', 'xsxs', '2025-06-21 14:27:59'),
(14, 2, 'FL-1872A558', 'uploads/reviews/WhatsApp Image 2025-06-19 at 11.50.17_3b7addcf.jpg', 'uploads/reviews/WhatsApp Image 2025-06-19 at 17.58.21_99b9a86e.jpg', 'sxsxsxsxsx', '2025-06-21 14:28:28'),
(15, 2, 'FL-1872A558', 'uploads/reviews/Black and Red Dynamic Boxing Class Instagram Post (3).png', 'uploads/reviews/c44b103c-eb66-4141-a6ec-1493a8eae14a.jpg', 'xsxsxsx', '2025-06-21 14:28:43'),
(16, 2, 'FL-1872A558', 'uploads/reviews/Black and Red Dynamic Boxing Class Instagram Post (3).png', 'uploads/reviews/c44b103c-eb66-4141-a6ec-1493a8eae14a.jpg', 'xsxsxsx', '2025-06-21 14:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('citizen','department','admin') DEFAULT 'citizen',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_otp` varchar(10) DEFAULT NULL,
  `reset_otp_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `mobile`, `district`, `password_hash`, `profile_picture`, `role`, `created_at`, `reset_otp`, `reset_otp_expires`) VALUES
(2, 'Umar Nashtah', 'Omar727221@gmail.com', '0760256618', 'Kegalle', '$2y$10$sw84ajEjFNCI2pxIOJq8TOxQJZk2A/lUMLHHNzxrCwHHFtKnv/F/6', 'uploads/WhatsApp Image 2025-05-05 at 16.36.20_2aad112e.jpg', 'citizen', '2025-05-27 14:27:37', '461028', '2025-06-29 20:27:03'),
(4, 'Aathi', 'aathiyaaz@gmail.com', '0771111223', 'Ratnapura', '$2y$10$BYJcz8K/AuW3kdRtQr7.w.Y7Mnv.ieESlL6dTbB6RDihAsnGwGJpy', 'uploads/spa.jpg', 'citizen', '2025-06-03 14:02:01', NULL, NULL),
(5, 'Shan Ahamed', 'shanahamedal20@gmail.com', '0762896449', 'Kurunegala', '$2y$10$b8ET40hUtAXZaFFC8/.UHu.TRC7.0f10RQUa3VBKYWkKmxM/yLq/C', 'uploads/WhatsApp Image 2025-06-04 at 09.26.43_770ce0a7.jpg', 'citizen', '2025-06-04 03:57:08', NULL, NULL),
(6, 'Thasmeer', 'fazilthasmeer@gmail.com', '0773418913', 'Matale', '$2y$10$GtRx3tIX0rx6NAd4fwX5LO5PDzYxSbbKq7b7SB7Uohb/j0Q1yFOrO', 'uploads/WhatsApp Image 2025-06-04 at 10.22.29_94f669cc.jpg', 'citizen', '2025-06-04 04:52:54', NULL, NULL),
(7, 'Aashik', 'aashikmohammed961@gmail.com', '0761565691', 'Kandy', '$2y$10$rSEwYtQFcHLOVxnLuX7h7.mVFt/VpGzE/jnjDNzUG6imRrTgtJD2m', 'uploads/Image20241217213249.png', 'citizen', '2025-06-19 16:27:24', NULL, NULL),
(8, 'Fixlanka_Admin', 'Fixlanka.Admin@gmail.com', '0112223334', 'Colombo', '$2y$10$.3Sh4lENCxRnKLOJty05L./Xhqc2ylBP4INCq2Yw5uSFEEOPG3tF6', 'uploads/Black and White Simple Minimalist Tailor Service Promotion Instagram Post.png', 'admin', '2025-06-23 00:12:35', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD UNIQUE KEY `ref_number` (`ref_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ref_number` (`ref_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`dept_id`);

--
-- Constraints for table `password_reset_requests`
--
ALTER TABLE `password_reset_requests`
  ADD CONSTRAINT `password_reset_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`ref_number`) REFERENCES `complaints` (`ref_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
