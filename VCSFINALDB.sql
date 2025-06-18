-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 04:25 PM
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
-- Database: `vcsnew`
--

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mschatroom`
--

CREATE TABLE `mschatroom` (
  `id` bigint(20) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mschatroom`
--

INSERT INTO `mschatroom` (`id`, `student_id`, `tutor_id`, `last_activity`, `created_at`) VALUES
(4, 4, 14, '2025-02-23 07:26:58', '2025-02-23 07:26:55'),
(6, 4, 15, '2025-02-24 02:42:40', '2025-02-23 07:27:33'),
(8, 4, 20, '2025-06-07 19:14:34', '2025-03-21 13:20:45'),
(9, 4, 21, '2025-05-03 08:56:13', '2025-05-03 08:44:27'),
(10, 25, 20, '2025-06-08 16:32:37', '2025-06-08 16:28:10'),
(11, 27, 20, '2025-06-08 17:11:11', '2025-06-08 16:43:14'),
(12, 28, 20, '2025-06-08 18:52:08', '2025-06-08 18:51:44'),
(13, 29, 20, '2025-06-09 04:56:24', '2025-06-09 04:55:52');

-- --------------------------------------------------------

--
-- Table structure for table `msrole`
--

CREATE TABLE `msrole` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `msrole`
--

INSERT INTO `msrole` (`id`, `Name`) VALUES
(1, 'Tutor'),
(2, 'Pelajar'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `mssubject`
--

CREATE TABLE `mssubject` (
  `id` int(255) NOT NULL,
  `subjectName` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mssubject`
--

INSERT INTO `mssubject` (`id`, `subjectName`, `created_at`, `updated_at`) VALUES
(2, 'ASP.Nett', '2025-03-18 16:58:07', '2025-06-08 16:49:15'),
(4, 'Python', '2025-03-18 16:58:23', '2025-03-18 16:58:47'),
(5, 'Math', '2025-06-08 16:49:09', '2025-06-08 18:28:02'),
(6, 'English', '2025-06-08 18:27:49', '2025-06-08 18:27:56');

-- --------------------------------------------------------

--
-- Table structure for table `msuser`
--

CREATE TABLE `msuser` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `TeacherId` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `subjectClass` int(11) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `isAvailable` tinyint(1) DEFAULT 0,
  `rating` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `msuser`
--

INSERT INTO `msuser` (`id`, `username`, `password`, `email`, `role`, `TeacherId`, `created_at`, `updated_at`, `image`, `subjectClass`, `price`, `isAvailable`, `rating`) VALUES
(4, 'Vincent Gavrila', '$2y$12$EPTwucBV32Rrk7U/0xHI7.m8bWeLJwNZa671zXuP7z4fdp4BhRApa', 'vincent@gmail.com', 2, NULL, '2025-02-22 10:43:42', '2025-06-01 12:54:05', 'profile/Fio4loZFGgfaGjWhiTjh2DqBviUE90VGRJPWUqbU.jpg', NULL, NULL, 0, NULL),
(5, 'Admin', '$2y$12$oFD0ZDIgTS.vAGUEJ8m37O4FHSw9B6M6ApA.WXQULUal35yd8aL0C', 'admin@gmail.com', 3, NULL, '2025-02-17 14:42:06', '2025-02-17 07:41:55', NULL, NULL, NULL, 0, NULL),
(14, 'tutor2', '$2y$12$ZcT/6LFa9xpTEw/IRGB08..TnBy0OgwCpxLGSxfhbk1cYEZuhaBkC', 'tutor2@gmail.com', 1, 'T-08499', '2025-06-07 13:24:47', '2025-06-07 13:24:47', 'profile/26A7FdLpcwt9GQZLIgf2i7hDUJEDZzXzfxagiK0O.png', 2, 50000, 0, NULL),
(15, 'tutor3', '$2y$12$pmUT0C./9C6Jcv6YYLgureqQ5lv8F9R8h7Fx0jy1JHKB6Fwywe5tK', 'tutor3@gmail.com', 1, 'T-57624', '2025-06-07 13:25:06', '2025-06-07 13:31:54', 'profile/FgXcvLo9w5qYObKTyO1zFXjA9vGbvuLPKD9BQD3s.jpg', 4, 20000, 0, NULL),
(19, 'abel', '$2y$12$rJc6gilyfW3xY8tBubx3KeV5WXDkr6MyPcz5SF/51M1F.VPgBkqx6', 'abel@gmail.com', 2, NULL, '2025-03-17 15:02:24', '2025-03-17 15:02:24', NULL, NULL, NULL, 0, NULL),
(20, 'AhmadFaiz', '$2y$12$vvUYzDlpNPeTWg4iWch8FeJJnhDQ7X9Sst2CX1seL3n0Df3fsOimq', 'ahmad@gmail.com', 1, 'T-87592', '2025-03-07 13:24:03', '2025-06-09 04:58:09', 'profile/w6fNt4nPYwEYcOlgspLbXBpW9UWsVLaC3Y4HHyLF.jpg', 2, 70000, 0, 3.5),
(21, 'ving', '$2y$12$5h/x7FJO9DrpmGWhflIdTumQ4ox7Rzgo8LwLRax8FiFfsrjgusJL.', 'ving@gmail.com', 1, 'T-24980', '2025-06-01 16:48:05', '2025-06-01 16:48:05', 'profile/voqodd9vcazEPyT1cWag8olUdCv0YdvIIZC8xVNm.jpg', 4, 20000, 0, NULL),
(22, 'belmi', '$2y$12$3qH/yWiUIrP3qurM49KSH.9iowXggeB3XYloNVPIT4vF4wG3rjYAe', 'belmi@gmail.com', 2, NULL, '2025-03-19 16:32:45', '2025-03-19 16:32:45', NULL, NULL, NULL, 0, NULL),
(23, 'tespelajarvincent', '$2y$12$EUmaSwUumaPGfZNBn6hMDOfdNjoklwuSHmGd3Kd2dDEOu.URtpOei', 'tespelajarvincent@gmail.com', 2, NULL, '2025-06-01 14:48:44', '2025-06-01 14:59:19', 'profile/t9ab04urEmeiqSICUy9jO9AGnnRkhiqGTbhfZD2v.jpg', NULL, NULL, 0, NULL),
(24, 'dillon', '$2y$12$1Iqe2irs5d1k9YyPhSaxb.Av.1vM.q/ze7OMKxRDc0hKoh6hVZ/26', 'dillon@gmail.com', 2, NULL, '2025-06-08 16:13:24', '2025-06-08 16:14:10', 'profile/QzsaCUBOy3WDUtvSyCgCvD2MXdLft2xWe3YOJzhh.png', NULL, NULL, 0, NULL),
(25, 'Belmiro', '$2y$12$rKi5Z5F8Xc6De8eQhka9R.mqi/w8cwA94wmSwdTrntKx1Ut3ggoI6', 'belmiro@gmail.com', 2, NULL, '2025-06-08 16:20:38', '2025-06-08 16:28:49', 'profile/bQdChxs1hmZUePKzqzEV2Qh9DXwI6fP33XtfTGRd.png', NULL, NULL, 0, NULL),
(26, 'testutorr', '$2y$12$KDF2AASVy0qORRVxGfQzcupG47q73sCZLkK3Jfqib/UeBhrv/tqzW', 'testutorr@gmail.com', 1, 'T-95601', '2025-06-08 16:34:22', '2025-06-08 16:36:21', 'profile/wX3HB4qCV8onvwUWpri2zsnHNyLCFiF6ir5DvIaz.png', 2, 50000, 0, NULL),
(27, 'tesPelajarrr', '$2y$12$d2dUFzZ26tF2Ghdgd2WIk.LGnH7M1bTFcD4Ul3gSOyTPwUWupkQpq', 'tespelajar1@gmail.com', 2, NULL, '2025-06-08 16:40:56', '2025-06-08 16:45:11', 'profile/rRa2oB7HuBXAyflfsFoUPsvLLrRycUAuuv12N3x8.png', NULL, NULL, 0, NULL),
(28, 'AkuPelajar', '$2y$12$qmezaz6BtOaUuzC4J0IvQ..jK6bom7Ka.eM6M7uEysGy5lqmqOcEK', 'akupelajar@gmail.com', 2, NULL, '2025-06-08 18:48:40', '2025-06-09 03:39:50', 'profile/GawTdS1qjwviNe3Ry74txwaUzmx0UmhK7mRDQblE.jpg', NULL, NULL, 0, NULL),
(29, 'PresentStudent', '$2y$12$zFr8vRHWtQaUdTtJhNB0BuW/TMstXQdx4rooykaHFeHfiJA1D5xlu', 'PresentStudent@gmail.com', 2, NULL, '2025-06-09 04:52:40', '2025-06-09 04:53:36', 'profile/yARPsSuziO4JYG3I03N2Znz50bmSxoh9Pj0VqTIv.jpg', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mswithdraw`
--

CREATE TABLE `mswithdraw` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `status` enum('processing','canceled','done') NOT NULL DEFAULT 'processing',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mswithdraw`
--

INSERT INTO `mswithdraw` (`id`, `user_id`, `amount`, `bank_name`, `account_number`, `account_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 20, 45343.00, 'BCA', '241241', 'fsefsefse', 'done', '2025-03-18 17:56:39', '2025-03-18 18:46:53'),
(2, 20, 50000.00, 'BCA', '241241', 'Vincent Gavrila Aprilliano', 'done', '2025-03-18 17:59:26', '2025-03-21 13:29:09'),
(3, 20, 50000.00, 'BNI', '7511119999', 'Vincent Gavrila Aprilliano', 'done', '2025-03-18 18:00:33', '2025-06-07 17:39:34'),
(4, 20, 32432.00, 'BCA', '7511119999', 'Vincent Gavrila Aprilliano', 'processing', '2025-03-18 18:07:55', '2025-03-18 18:07:55'),
(5, 20, 50000.00, 'BCA', '7511119999', 'Vincent Gavrila Aprilliano', 'processing', '2025-03-18 19:03:29', '2025-03-18 19:03:29'),
(6, 20, 500000.00, 'Mandiri', '7511119999', 'Vincent Gavrila Aprilliano', 'processing', '2025-03-18 19:06:59', '2025-03-18 19:06:59'),
(7, 20, 55555.00, 'BCA', '7511119999', 'Vincent Gavrila Aprilliano', 'processing', '2025-03-18 19:11:17', '2025-03-18 19:11:17'),
(8, 4, 50000.00, 'BCA', '7511119999', 'Vincent Gavrila Aprilliano', 'processing', '2025-03-18 19:32:13', '2025-03-18 19:32:13'),
(9, 20, 50000.00, 'Mandiri', '7511119999', 'Vincent Gavrila Aprilliano', 'processing', '2025-03-18 19:33:03', '2025-03-18 19:33:03'),
(10, 4, 15000.00, 'BCA', '7511119999', 'Vincent Gavrila Aprilliano', 'processing', '2025-03-18 20:01:22', '2025-03-18 20:01:22'),
(11, 20, 1000000.00, 'BCA', '7511119999', 'Vincent Gavrila Aprilliano', 'done', '2025-03-19 10:45:36', '2025-03-19 10:46:24'),
(12, 21, 10000.00, 'BCA', '75111999', 'esfsefs', 'done', '2025-04-01 13:04:34', '2025-04-01 13:05:26'),
(13, 4, 500000.00, 'BCA', '7511119999', 'Vincent Gavrila Aprilliano', 'done', '2025-06-01 16:57:50', '2025-06-01 16:58:10'),
(14, 20, 400000.00, 'BCA', '7511119999', 'Vincent', 'done', '2025-06-07 16:29:24', '2025-06-07 16:30:46'),
(15, 20, 50000.00, 'BCA', '7511119999', 'Vincent', 'processing', '2025-06-07 17:39:25', '2025-06-07 17:39:25'),
(16, 20, 600000.00, 'BCA', '7511119999', 'Vincent', 'done', '2025-06-07 17:41:30', '2025-06-07 17:41:40'),
(17, 27, 200000.00, 'Mandiri', '7511119999', 'tesPelajarrr', 'done', '2025-06-08 16:46:54', '2025-06-08 16:49:48'),
(18, 27, 100000.00, 'BCA', '7511119999', 'tesPelajarrr', 'done', '2025-06-08 16:59:08', '2025-06-08 16:59:25'),
(19, 27, 130000.00, 'BCA', '7511119999', 'tesPelajarrr', 'done', '2025-06-08 17:48:56', '2025-06-08 17:49:05'),
(20, 20, 50000.00, 'Mandiri', '7511119999', 'Ahmad', 'done', '2025-06-08 18:28:37', '2025-06-08 18:29:39'),
(21, 20, 500000.00, 'Mandiri', '7511119999', 'Ahmad', 'done', '2025-06-08 18:55:14', '2025-06-08 19:01:15'),
(22, 20, 1000000.00, 'BCA', '7511119999', 'Ahmad', 'done', '2025-06-09 04:59:04', '2025-06-09 05:02:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `status`, `created_at`, `updated_at`, `transaction_id`) VALUES
(84, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-18 17:35:00', '2025-03-18 17:35:02', 84),
(85, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-18 19:51:28', '2025-03-18 19:51:29', 85),
(86, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 10:45:01', '2025-03-19 10:45:03', 86),
(87, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 10:57:24', '2025-03-19 10:57:26', 87),
(88, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 10:57:54', '2025-03-19 10:57:55', 88),
(89, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 10:59:39', '2025-03-19 10:59:42', 89),
(90, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 11:00:17', '2025-03-19 11:00:19', 90),
(91, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 11:01:05', '2025-03-19 11:01:07', 91),
(92, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 11:02:10', '2025-03-19 11:02:13', 92),
(93, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 11:03:20', '2025-03-19 11:03:24', 93),
(94, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 15:43:52', '2025-03-19 15:43:57', 94),
(95, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 15:46:47', '2025-03-19 15:46:48', 95),
(96, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 15:47:32', '2025-03-19 15:47:32', 96),
(97, 21, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 16:23:41', '2025-03-19 16:23:43', 97),
(98, 21, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-19 17:07:25', '2025-03-19 17:07:25', 98),
(99, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-21 13:19:03', '2025-03-21 13:19:06', 99),
(100, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-03-21 13:19:17', '2025-03-21 13:19:19', 100),
(101, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-04-01 13:07:18', '2025-04-01 13:07:20', 101),
(102, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-01 16:46:05', '2025-06-01 16:46:07', 102),
(103, 21, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-01 16:48:02', '2025-06-01 16:48:03', 103),
(104, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-02 16:50:25', '2025-06-02 16:50:29', 104),
(105, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:25:16', '2025-06-07 16:25:16', 105),
(106, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:54:39', '2025-06-07 16:54:39', 106),
(107, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:55:04', '2025-06-07 16:55:08', 107),
(108, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:55:44', '2025-06-07 16:55:47', 108),
(109, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:57:00', '2025-06-07 16:57:03', 109),
(110, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:57:43', '2025-06-07 16:57:46', 110),
(111, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:58:50', '2025-06-07 16:58:53', 111),
(112, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 16:59:46', '2025-06-07 16:59:48', 112),
(113, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 17:06:56', '2025-06-07 17:06:58', 113),
(114, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 17:07:41', '2025-06-07 17:07:42', 114),
(115, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 17:07:54', '2025-06-07 17:07:57', 115),
(116, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 17:08:18', '2025-06-07 17:08:19', 116),
(117, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 17:55:21', '2025-06-07 17:55:24', 117),
(118, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 17:57:48', '2025-06-07 17:57:50', 118),
(119, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 18:00:23', '2025-06-07 18:00:25', 119),
(120, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 18:07:09', '2025-06-07 18:07:21', 120),
(121, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-07 18:12:40', '2025-06-07 18:12:45', 121),
(122, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 16:15:31', '2025-06-08 16:15:35', 122),
(123, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 16:18:08', '2025-06-08 16:18:10', 123),
(124, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 16:21:14', '2025-06-08 16:21:16', 124),
(125, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 16:23:13', '2025-06-08 16:23:19', 125),
(126, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 16:26:55', '2025-06-08 16:26:55', 126),
(127, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 16:44:18', '2025-06-08 16:44:19', 127),
(128, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 17:50:24', '2025-06-08 17:51:15', 128),
(129, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-08 18:52:36', '2025-06-08 18:52:38', 129),
(130, 20, 'Anda sedang disewa oleh pelajar. Silakan konfirmasi dalam 10 detik.', 'read', '2025-06-09 04:56:44', '2025-06-09 04:56:49', 130);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `content`, `thread_id`, `user_id`, `created_at`, `updated_at`, `parent_id`) VALUES
(38, 'Yu sama saya, sini langsung orderr', 12, 20, '2025-06-02 16:52:37', '2025-06-02 16:52:37', NULL),
(43, '@AhmadFaiz hi', 12, 20, '2025-06-08 18:26:25', '2025-06-08 18:26:25', 38),
(44, '@AhmadFaiz hi', 12, 29, '2025-06-09 04:54:14', '2025-06-09 04:54:14', 38);

-- --------------------------------------------------------

--
-- Table structure for table `roomvideocall`
--

CREATE TABLE `roomvideocall` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roomzoomcall`
--

CREATE TABLE `roomzoomcall` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `meeting_url` varchar(255) DEFAULT NULL,
  `start_time` varchar(255) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'scheduled',
  `host_id` int(11) DEFAULT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `zoom_meeting_id` varchar(255) DEFAULT NULL,
  `zoom_password` varchar(255) DEFAULT NULL,
  `recording_url` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomzoomcall`
--

INSERT INTO `roomzoomcall` (`id`, `transaction_id`, `room_name`, `meeting_url`, `start_time`, `duration`, `status`, `host_id`, `participant_id`, `zoom_meeting_id`, `zoom_password`, `recording_url`, `notes`, `created_at`, `updated_at`) VALUES
(13, 84, '89630774665', 'https://us05web.zoom.us/j/89630774665?pwd=wL0aPnYOFG6vD78APuFXQbADL7wh67.1', '2025-03-19T00:40:05Z', 60, 'scheduled', 20, 4, '89630774665', '59NLV8', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-18 17:35:06', '2025-03-18 17:35:06'),
(14, 85, '84104306232', 'https://us05web.zoom.us/j/84104306232?pwd=FxUgUKxOmvB7WwMiiX5Y2PaVhppxC5.1', '2025-03-19T02:56:31Z', 60, 'scheduled', 20, 4, '84104306232', 'Rv2DTj', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-18 19:51:32', '2025-03-18 19:51:32'),
(15, 86, '87484660308', 'https://us05web.zoom.us/j/87484660308?pwd=IoAJNmScF4kMaIpL7EOzT9l2JXEow3.1', '2025-03-19T17:50:08Z', 60, 'scheduled', 20, 4, '87484660308', 'ny90bG', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 10:45:09', '2025-03-19 10:45:09'),
(16, 89, '86998697488', 'https://us05web.zoom.us/j/86998697488?pwd=rvbq7rmp4S9zB7h1aa2pNpYWoYP7zI.1', '2025-03-19T18:04:44Z', 60, 'scheduled', 20, 4, '86998697488', 'hE6c5m', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 10:59:45', '2025-03-19 10:59:45'),
(17, 90, '87182921754', 'https://us05web.zoom.us/j/87182921754?pwd=ba1r5Syr0UaFlwdYgS2UO5DbkpaOd2.1', '2025-03-19T18:05:21Z', 60, 'scheduled', 20, 4, '87182921754', 'ezS9td', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 11:00:22', '2025-03-19 11:00:22'),
(18, 91, '81866181001', 'https://us05web.zoom.us/j/81866181001?pwd=Eb5ltf164HLG3PyxI5fLB6hzSImHcP.1', '2025-03-19T18:06:14Z', 60, 'scheduled', 20, 4, '81866181001', '2b5hjt', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 11:01:15', '2025-03-19 11:01:15'),
(19, 92, '81166875159', 'https://us05web.zoom.us/j/81166875159?pwd=aRa0kfq74nHO1IFtVb4MMW0Zao5AAP.1', '2025-03-19T18:07:15Z', 60, 'scheduled', 20, 4, '81166875159', 'b0mTn0', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 11:02:16', '2025-03-19 11:02:16'),
(20, 93, '89836970911', 'https://us05web.zoom.us/j/89836970911?pwd=HrEPwodt5y1JWa4JxD6cT3piaWvbKK.1', '2025-03-19T18:08:28Z', 60, 'scheduled', 20, 4, '89836970911', 's83vNr', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 11:03:29', '2025-03-19 11:03:29'),
(21, 97, '84245801609', 'https://us05web.zoom.us/j/84245801609?pwd=Mhnd6ctHrBthJ4PuEGvmAb8bocvH4l.1', '2025-03-19T23:28:45Z', 60, 'scheduled', 21, 19, '84245801609', 'P8YCEq', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 16:23:46', '2025-03-19 16:23:46'),
(22, 98, '85453610467', 'https://us05web.zoom.us/j/85453610467?pwd=RILvdyL8Gz89dUKvKAnSKFDJIj9NaD.1', '2025-03-20T00:12:30Z', 60, 'scheduled', 21, 4, '85453610467', 'EkMup2', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-19 17:07:32', '2025-03-19 17:07:32'),
(23, 100, '85661263316', 'https://us05web.zoom.us/j/85661263316?pwd=2rq7a9YEyC63uEcxJtTzd9LNq4FB06.1', '2025-03-21T20:24:25Z', 60, 'scheduled', 20, 4, '85661263316', 'xHR4yA', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-03-21 13:19:26', '2025-03-21 13:19:26'),
(24, 101, '81128830827', 'https://us05web.zoom.us/j/81128830827?pwd=Kjt3OiEGzAUwaR6vO9oKLo6iD94tba.1', '2025-04-01T20:12:25Z', 60, 'scheduled', 20, 4, '81128830827', 'dhDRm0', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-04-01 13:07:26', '2025-04-01 13:07:26'),
(25, 102, '83760122706', 'https://us05web.zoom.us/j/83760122706?pwd=HS0M8xWbOZeb9zMpIxIQneAkWzJ8Yc.1', '2025-06-01T23:51:13Z', 60, 'scheduled', 20, 20, '83760122706', 'jm9y2P', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-01 16:46:14', '2025-06-01 16:46:14'),
(26, 103, '81661289254', 'https://us05web.zoom.us/j/81661289254?pwd=wWmV3aaHKpjtWWsSdQbLyzfpbfpXaa.1', '2025-06-01T23:53:07Z', 60, 'scheduled', 21, 21, '81661289254', 'UGWvz6', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-01 16:48:08', '2025-06-01 16:48:08'),
(27, 104, '83723617310', 'https://us05web.zoom.us/j/83723617310?pwd=X7QqcrUzQhG36whDiffd8qtyEn8bna.1', '2025-06-02T23:55:36Z', 60, 'scheduled', 20, 4, '83723617310', 'K0xbbH', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-02 16:50:37', '2025-06-02 16:50:37'),
(28, 105, '89122074201', 'https://us05web.zoom.us/j/89122074201?pwd=lEiErGSoeQdjTljtBOHVY04r4kWBJF.1', '2025-06-07T23:30:26Z', 60, 'scheduled', 20, 4, '89122074201', 'xTb2x7', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 16:25:27', '2025-06-07 16:25:27'),
(29, 108, '83215980738', 'https://us05web.zoom.us/j/83215980738?pwd=wb4uGQdtozUV7hn5pcjbtJVDN21dAZ.1', '2025-06-08T00:00:49Z', 60, 'scheduled', 20, 4, '83215980738', '5YU9bN', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 16:55:50', '2025-06-07 16:55:50'),
(30, 109, '88349208093', 'https://us05web.zoom.us/j/88349208093?pwd=DNNCllUymjvRMv80Q8GdxCVpdKYnfK.1', '2025-06-08T00:02:05Z', 60, 'scheduled', 20, 4, '88349208093', '0M1gNQ', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 16:57:06', '2025-06-07 16:57:06'),
(31, 110, '81066593623', 'https://us05web.zoom.us/j/81066593623?pwd=ZbpHZKmkmY708HK7MDoZQZzSTYBzpe.1', '2025-06-08T00:02:54Z', 60, 'scheduled', 20, 4, '81066593623', '7RgLNQ', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 16:57:55', '2025-06-07 16:57:55'),
(32, 112, '84386437455', 'https://us05web.zoom.us/j/84386437455?pwd=v7h69KbiRUJA2utbxJnsW9HSb6JTxG.1', '2025-06-08T00:04:59Z', 60, 'scheduled', 20, 4, '84386437455', 'BuNaY1', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 17:00:00', '2025-06-07 17:00:00'),
(33, 116, '82491746555', 'https://us05web.zoom.us/j/82491746555?pwd=8DZAO7ZuHcxKC5bkDeOiN75y3zIwga.1', '2025-06-08T00:13:27Z', 60, 'scheduled', 20, 4, '82491746555', 'Ym413z', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 17:08:28', '2025-06-07 17:08:28'),
(34, 117, '86038008338', 'https://us05web.zoom.us/j/86038008338?pwd=8kPROj8Pni6TNGEJ64qvP9CUbwmcfC.1', '2025-06-08T01:00:27Z', 60, 'scheduled', 20, 4, '86038008338', 'SK5VMj', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 17:55:28', '2025-06-07 17:55:28'),
(35, 118, '89175371555', 'https://us05web.zoom.us/j/89175371555?pwd=yfRWz70IguwtYbGPbG2qBSeb694OTu.1', '2025-06-08T01:02:53Z', 60, 'scheduled', 20, 4, '89175371555', 'N9uXD1', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 17:57:54', '2025-06-07 17:57:54'),
(36, 119, '86198804503', 'https://us05web.zoom.us/j/86198804503?pwd=WOpqS47dMPn8tgvzchdsKBm9UhStxy.1', '2025-06-08T01:05:28Z', 60, 'scheduled', 20, 4, '86198804503', '1rEb56', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 18:00:29', '2025-06-07 18:00:29'),
(37, 121, '82588863936', 'https://us05web.zoom.us/j/82588863936?pwd=z4oCPvbVPu0HGtV838A5nV6Wek7Koa.1', '2025-06-08T01:17:54Z', 60, 'scheduled', 20, 4, '82588863936', '9npf6A', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-07 18:12:55', '2025-06-07 18:12:55'),
(38, 126, '85325639776', 'https://us05web.zoom.us/j/85325639776?pwd=h7jzrlQtug8fnHEA0YQbPa2dmna6Ed.1', '2025-06-08T23:32:03Z', 60, 'scheduled', 20, 25, '85325639776', 'AyX0YS', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-08 16:27:04', '2025-06-08 16:27:04'),
(39, 127, '84442993784', 'https://us05web.zoom.us/j/84442993784?pwd=622vnOJWaDFnGAPHjgyXR5Wb0rbwQl.1', '2025-06-08T23:49:26Z', 60, 'scheduled', 20, 27, '84442993784', '3m5n5V', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-08 16:44:27', '2025-06-08 16:44:27'),
(40, 129, '84085373686', 'https://us05web.zoom.us/j/84085373686?pwd=7lfRUiRmesKu1an9zg4C2N8u2U9lAX.1', '2025-06-09T01:57:42Z', 60, 'scheduled', 20, 28, '84085373686', 'PAZd7M', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-08 18:52:43', '2025-06-08 18:52:43'),
(41, 130, '88301408402', 'https://us05web.zoom.us/j/88301408402?pwd=UEGbx3zxbokBGvULGaGiPtmGk9QE9N.1', '2025-06-09T12:01:52Z', 60, 'scheduled', 20, 29, '88301408402', 'q9pf3N', NULL, 'Diskusi tentang materi Matematika kelas 10.', '2025-06-09 04:56:53', '2025-06-09 04:56:53');

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`id`, `title`, `content`, `user_id`, `subject_id`, `created_at`, `updated_at`) VALUES
(11, 'Bagaimana sih biar kamu cepet paham Materi X', 'Tes', 4, 4, '2025-05-07 08:07:38', '2025-05-07 08:07:38'),
(12, 'Belajar ASp.net Bareng yuk, aku buka tutor', 'Tes Forum', 4, 2, '2025-06-02 16:52:18', '2025-06-02 16:52:18'),
(14, 'tes', 'tes', 29, 6, '2025-06-09 04:54:01', '2025-06-09 04:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','confirmed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `roomzoomcall_id` int(11) DEFAULT NULL,
  `rating` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `student_id`, `tutor_id`, `subject_id`, `amount`, `status`, `created_at`, `updated_at`, `roomzoomcall_id`, `rating`) VALUES
(84, 4, 20, 2, 40000.00, 'confirmed', '2025-03-18 17:35:00', '2025-06-07 18:17:15', NULL, NULL),
(85, 4, 20, 4, 40000.00, 'confirmed', '2025-03-18 19:51:28', '2025-06-07 18:17:15', NULL, 1),
(86, 4, 20, 2, 40000.00, 'confirmed', '2025-03-19 10:45:00', '2025-06-07 18:17:15', NULL, 1),
(87, 4, 20, 4, 40000.00, 'pending', '2025-03-19 10:57:24', '2025-06-07 18:17:15', NULL, NULL),
(88, 4, 20, 4, 40000.00, 'pending', '2025-03-19 10:57:54', '2025-06-07 18:17:15', NULL, NULL),
(89, 4, 20, 4, 40000.00, 'confirmed', '2025-03-19 10:59:39', '2025-06-07 18:17:15', NULL, 5),
(90, 4, 20, 2, 40000.00, 'confirmed', '2025-03-19 11:00:17', '2025-06-07 18:17:15', NULL, 1),
(91, 4, 20, 2, 40000.00, 'confirmed', '2025-03-19 11:01:05', '2025-06-07 18:17:15', NULL, 3),
(92, 4, 20, 2, 40000.00, 'confirmed', '2025-03-19 11:02:10', '2025-06-07 18:17:15', NULL, 5),
(93, 4, 20, 4, 40000.00, 'confirmed', '2025-03-19 11:03:20', '2025-06-07 18:17:15', NULL, 3),
(94, 4, 20, 2, 40000.00, 'canceled', '2025-03-19 15:43:52', '2025-06-07 18:17:15', NULL, NULL),
(95, 4, 20, 4, 40000.00, 'canceled', '2025-03-19 15:46:47', '2025-06-07 18:17:15', NULL, NULL),
(96, 4, 20, 4, 40000.00, 'canceled', '2025-03-19 15:47:32', '2025-06-07 18:17:15', NULL, NULL),
(97, 19, 21, 2, 20000.00, 'confirmed', '2025-03-19 16:23:41', '2025-06-07 18:17:15', NULL, NULL),
(98, 4, 21, 4, 20000.00, 'confirmed', '2025-03-19 17:07:25', '2025-06-07 18:17:15', NULL, NULL),
(99, 4, 20, 4, 40000.00, 'pending', '2025-03-21 13:19:03', '2025-06-07 18:17:15', NULL, NULL),
(100, 4, 20, 2, 40000.00, 'confirmed', '2025-03-21 13:19:17', '2025-06-07 18:17:15', NULL, 4),
(101, 4, 20, 4, 40000.00, 'confirmed', '2025-04-01 13:07:18', '2025-06-07 18:17:15', NULL, 5),
(102, 20, 20, 2, 50000.00, 'confirmed', '2025-06-01 16:46:05', '2025-06-07 18:17:15', NULL, NULL),
(103, 21, 21, 4, 20000.00, 'confirmed', '2025-06-01 16:48:02', '2025-06-07 18:17:15', NULL, NULL),
(104, 4, 20, 4, 50000.00, 'confirmed', '2025-06-02 16:50:25', '2025-06-07 18:17:15', NULL, 5),
(105, 4, 20, 2, 30000.00, 'confirmed', '2025-06-07 16:25:16', '2025-06-07 18:17:15', NULL, 5),
(106, 4, 20, 4, 30000.00, 'pending', '2025-06-07 16:54:39', '2025-06-07 18:17:15', NULL, NULL),
(107, 4, 20, 4, 30000.00, 'canceled', '2025-06-07 16:55:04', '2025-06-07 18:17:15', NULL, NULL),
(108, 4, 20, 2, 30000.00, 'confirmed', '2025-06-07 16:55:44', '2025-06-07 18:17:15', NULL, NULL),
(109, 4, 20, 2, 30000.00, 'confirmed', '2025-06-07 16:57:00', '2025-06-07 18:17:15', NULL, NULL),
(110, 4, 20, 4, 30000.00, 'confirmed', '2025-06-07 16:57:43', '2025-06-07 18:17:15', NULL, NULL),
(111, 4, 20, 2, 30000.00, 'pending', '2025-06-07 16:58:50', '2025-06-07 18:17:15', NULL, NULL),
(112, 4, 20, 2, 30000.00, 'confirmed', '2025-06-07 16:59:46', '2025-06-07 18:17:15', NULL, NULL),
(113, 4, 20, 4, 30000.00, 'pending', '2025-06-07 17:06:56', '2025-06-07 18:17:15', NULL, NULL),
(114, 4, 20, 4, 30000.00, 'canceled', '2025-06-07 17:07:41', '2025-06-07 18:17:15', NULL, NULL),
(115, 4, 20, 2, 30000.00, 'canceled', '2025-06-07 17:07:54', '2025-06-07 18:17:15', NULL, NULL),
(116, 4, 20, 2, 30000.00, 'confirmed', '2025-06-07 17:08:18', '2025-06-07 18:17:15', NULL, NULL),
(117, 4, 20, 4, 30000.00, 'confirmed', '2025-06-07 17:55:21', '2025-06-07 18:17:15', NULL, NULL),
(118, 4, 20, 4, 70000.00, 'confirmed', '2025-06-07 17:57:48', '2025-06-07 18:17:15', NULL, NULL),
(119, 4, 20, 2, 70000.00, 'confirmed', '2025-06-07 18:00:23', '2025-06-07 18:00:29', NULL, NULL),
(120, 4, 20, 2, 70000.00, 'pending', '2025-06-07 18:07:09', '2025-06-07 18:07:09', NULL, NULL),
(121, 4, 20, 2, 70000.00, 'confirmed', '2025-06-07 18:12:40', '2025-06-07 18:12:55', NULL, NULL),
(122, 24, 20, 2, 70000.00, 'pending', '2025-06-08 16:15:31', '2025-06-08 16:15:31', NULL, NULL),
(123, 24, 20, 2, 70000.00, 'pending', '2025-06-08 16:18:08', '2025-06-08 16:18:08', NULL, NULL),
(124, 25, 20, 2, 70000.00, 'pending', '2025-06-08 16:21:14', '2025-06-08 16:21:14', NULL, NULL),
(125, 25, 20, 2, 70000.00, 'pending', '2025-06-08 16:23:13', '2025-06-08 16:23:13', NULL, NULL),
(126, 25, 20, 2, 70000.00, 'confirmed', '2025-06-08 16:26:55', '2025-06-08 16:27:04', NULL, NULL),
(127, 27, 20, 2, 70000.00, 'confirmed', '2025-06-08 16:44:18', '2025-06-08 16:45:53', NULL, 1),
(128, 27, 20, 2, 70000.00, 'pending', '2025-06-08 17:50:24', '2025-06-08 17:50:24', NULL, NULL),
(129, 28, 20, 2, 70000.00, 'confirmed', '2025-06-08 18:52:36', '2025-06-08 18:54:39', NULL, 5),
(130, 29, 20, 2, 70000.00, 'confirmed', '2025-06-09 04:56:44', '2025-06-09 04:58:09', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `trmessages`
--

CREATE TABLE `trmessages` (
  `id` bigint(20) NOT NULL,
  `room_id` bigint(20) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `isRead` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trmessages`
--

INSERT INTO `trmessages` (`id`, `room_id`, `sender_id`, `message`, `created_at`, `image`, `file`, `isRead`) VALUES
(35, 4, 4, 'tes', '2025-02-23 07:26:58', NULL, NULL, 0),
(37, 6, 4, 'tess', '2025-02-23 07:27:39', NULL, NULL, 1),
(57, 8, 4, 'tes', '2025-04-01 13:06:14', NULL, NULL, 1),
(58, 8, 20, 'bisa', '2025-04-01 13:06:25', NULL, NULL, 1),
(59, 8, 4, 'tes', '2025-05-03 07:46:27', NULL, NULL, 1),
(60, 8, 4, 'tes', '2025-05-03 07:47:12', NULL, NULL, 1),
(61, 8, 4, 'tes', '2025-05-03 07:49:50', NULL, NULL, 1),
(62, 8, 4, 'tes', '2025-05-03 07:53:11', NULL, NULL, 1),
(63, 8, 4, 'tes c', '2025-05-03 07:59:35', NULL, NULL, 1),
(64, 8, 4, 'tes c', '2025-05-03 08:02:19', NULL, NULL, 1),
(65, 8, 4, 'hello', '2025-05-03 08:28:14', NULL, NULL, 1),
(66, 8, 4, 'hello', '2025-05-03 08:30:04', NULL, NULL, 1),
(67, 8, 4, 'hello', '2025-05-03 08:32:58', NULL, NULL, 1),
(68, 8, 4, 'hello', '2025-05-03 08:34:54', NULL, NULL, 1),
(69, 8, 4, 'hi', '2025-05-03 08:35:47', NULL, NULL, 1),
(70, 8, 20, 'tes', '2025-05-03 08:36:26', NULL, NULL, 1),
(71, 8, 20, 'tes', '2025-05-03 08:36:55', NULL, NULL, 1),
(72, 8, 4, 'bang', '2025-05-03 08:37:08', NULL, NULL, 1),
(73, 8, 20, 'iya', '2025-05-03 08:39:42', NULL, NULL, 1),
(74, 8, 20, 'tes', '2025-05-03 08:39:48', NULL, NULL, 1),
(75, 8, 4, 'iya bang', '2025-05-03 08:41:17', NULL, NULL, 1),
(76, 8, 20, 'tes', '2025-05-03 08:41:41', NULL, NULL, 1),
(78, 8, 20, '', '2025-05-03 08:42:13', 'chat_images/yZWy5xUkShinqxFnuX0nsKCTmKa00RnqysdmpBNW.png', NULL, 1),
(79, 8, 20, 'nih bang', '2025-05-03 08:42:23', 'chat_images/k6MBzc0EIBTndcUh2ZBkRw3YSwrig2kHuh70lhfg.png', NULL, 1),
(80, 9, 4, 'tes', '2025-05-03 08:44:34', NULL, NULL, 1),
(81, 9, 21, 'ya', '2025-05-03 08:44:57', NULL, NULL, 1),
(82, 9, 21, 'es', '2025-05-03 08:55:29', NULL, NULL, 1),
(83, 9, 4, 'ges', '2025-05-03 08:56:13', NULL, NULL, 1),
(84, 8, 4, 'tes', '2025-05-03 09:09:21', NULL, NULL, 1),
(86, 8, 4, 'lah elu', '2025-05-03 09:10:26', NULL, NULL, 1),
(87, 8, 4, 'lah eu', '2025-05-03 09:10:35', NULL, NULL, 1),
(88, 8, 20, 'iya bre', '2025-05-03 09:20:28', NULL, NULL, 1),
(89, 8, 4, 'oh iya', '2025-05-03 09:27:23', NULL, NULL, 1),
(90, 8, 4, 'tes', '2025-05-03 09:31:51', NULL, NULL, 1),
(91, 8, 4, 'tes', '2025-05-03 09:33:47', NULL, NULL, 1),
(92, 8, 4, 'tes', '2025-05-03 09:36:21', NULL, NULL, 1),
(93, 8, 4, 'tes', '2025-05-03 09:36:50', NULL, NULL, 1),
(94, 8, 4, 'tes', '2025-05-03 09:38:22', NULL, NULL, 1),
(95, 8, 20, 'tes', '2025-05-03 09:38:35', NULL, NULL, 1),
(96, 8, 4, 'tes', '2025-05-03 09:45:38', NULL, NULL, 1),
(97, 8, 4, 'halo kak', '2025-05-03 15:50:48', NULL, NULL, 1),
(98, 8, 20, 'jadi ga kak?', '2025-05-05 07:02:50', NULL, NULL, 1),
(99, 8, 20, 'halo ka?', '2025-05-05 07:03:16', NULL, NULL, 1),
(100, 8, 20, 'halo?', '2025-05-05 07:03:25', NULL, NULL, 1),
(101, 8, 20, 'hi', '2025-05-05 07:05:26', NULL, NULL, 1),
(102, 8, 4, 'iya', '2025-05-05 07:07:00', NULL, NULL, 1),
(103, 8, 4, 'tes', '2025-05-05 07:18:10', NULL, NULL, 1),
(104, 8, 20, 'tes', '2025-05-05 07:18:17', NULL, NULL, 1),
(105, 8, 20, ';p', '2025-05-05 07:18:30', NULL, NULL, 1),
(106, 8, 20, 'tes', '2025-05-05 07:18:48', NULL, NULL, 1),
(107, 8, 20, 'tess', '2025-05-05 07:19:37', NULL, NULL, 1),
(108, 8, 20, 'tes', '2025-05-05 07:19:58', NULL, NULL, 1),
(109, 8, 20, 'bang', '2025-05-05 07:20:04', NULL, NULL, 1),
(110, 8, 20, 'tes', '2025-05-05 07:20:15', NULL, NULL, 1),
(111, 8, 20, 'tes', '2025-05-05 07:20:35', NULL, NULL, 1),
(112, 8, 20, 'hai', '2025-05-05 07:24:11', NULL, NULL, 1),
(113, 8, 20, 'hai', '2025-05-05 07:24:17', NULL, NULL, 1),
(114, 8, 20, 'tes', '2025-05-05 07:24:21', NULL, NULL, 1),
(115, 8, 20, 'tes', '2025-05-05 07:24:22', NULL, NULL, 1),
(116, 8, 20, 'tes', '2025-05-05 07:24:22', NULL, NULL, 1),
(117, 8, 20, 'tes', '2025-05-05 07:24:23', NULL, NULL, 1),
(118, 8, 20, 'tes', '2025-05-05 07:24:32', NULL, NULL, 1),
(119, 8, 20, 'tes', '2025-05-05 07:24:51', NULL, NULL, 1),
(120, 8, 20, 'es', '2025-05-05 07:25:00', NULL, NULL, 1),
(121, 8, 20, 'tes', '2025-05-05 07:25:22', NULL, NULL, 1),
(122, 8, 20, 'bang', '2025-05-05 07:25:34', NULL, NULL, 1),
(123, 8, 20, 'tea', '2025-05-05 07:27:19', NULL, NULL, 1),
(124, 8, 20, 'tea', '2025-05-05 07:27:21', NULL, NULL, 1),
(125, 8, 20, 'tea', '2025-05-05 07:27:37', NULL, NULL, 1),
(126, 8, 20, 'tea', '2025-05-05 07:27:47', NULL, NULL, 1),
(127, 8, 20, 'tea', '2025-05-05 07:27:54', NULL, NULL, 1),
(128, 8, 20, 'tea', '2025-05-05 07:28:07', NULL, NULL, 1),
(129, 8, 20, 'tes', '2025-05-05 07:29:46', NULL, NULL, 1),
(130, 8, 20, 'tes', '2025-05-05 07:29:59', NULL, NULL, 1),
(131, 8, 20, 'tes', '2025-05-05 07:30:37', NULL, NULL, 1),
(132, 8, 20, 'tes', '2025-05-05 07:30:44', NULL, NULL, 1),
(133, 8, 20, 'bang', '2025-05-05 07:30:59', NULL, NULL, 1),
(134, 8, 20, 'iio', '2025-05-05 07:31:13', NULL, NULL, 1),
(135, 8, 20, 'bang', '2025-05-05 07:31:23', NULL, NULL, 1),
(136, 8, 4, 'bang', '2025-05-05 07:34:56', NULL, NULL, 1),
(137, 8, 4, 'bang', '2025-05-05 07:35:06', NULL, NULL, 1),
(138, 8, 4, 'tes', '2025-05-05 07:42:20', NULL, NULL, 1),
(139, 8, 4, 'tes', '2025-05-05 07:42:23', NULL, NULL, 1),
(140, 8, 4, 'tes', '2025-05-05 07:43:51', NULL, NULL, 1),
(141, 8, 4, 'tes', '2025-05-05 07:44:26', NULL, NULL, 1),
(142, 8, 4, 'hi', '2025-05-05 07:50:55', NULL, NULL, 1),
(143, 8, 4, 'tes', '2025-05-06 05:00:37', NULL, NULL, 1),
(144, 8, 4, 'tes', '2025-05-06 05:01:32', NULL, NULL, 1),
(145, 8, 4, 'p', '2025-05-06 05:11:55', NULL, NULL, 1),
(146, 8, 4, 'p', '2025-05-06 05:12:16', NULL, NULL, 1),
(147, 8, 4, 'p', '2025-05-06 05:13:29', NULL, NULL, 1),
(148, 8, 4, 'tes', '2025-05-06 05:13:47', NULL, NULL, 1),
(149, 8, 4, 'tes lg', '2025-05-06 05:14:01', NULL, NULL, 1),
(150, 8, 4, 'tes', '2025-05-06 05:14:37', NULL, NULL, 1),
(151, 8, 4, 'tes', '2025-05-06 05:14:39', NULL, NULL, 1),
(152, 8, 4, 'tes', '2025-05-06 05:14:55', NULL, NULL, 1),
(153, 8, 20, 'tes koneksi', '2025-05-06 05:15:04', NULL, NULL, 1),
(154, 8, 4, 'p', '2025-05-06 05:15:12', NULL, NULL, 1),
(155, 8, 4, 'tes', '2025-06-02 16:47:52', NULL, NULL, 1),
(156, 8, 4, 'tes pesan', '2025-06-02 16:48:12', NULL, NULL, 1),
(157, 8, 20, 'tes pesan', '2025-06-07 13:40:41', NULL, NULL, 1),
(158, 8, 20, 'p', '2025-06-07 13:50:37', NULL, NULL, 1),
(159, 8, 4, 'tes', '2025-06-07 13:50:56', NULL, NULL, 1),
(160, 8, 4, 'tes', '2025-06-07 13:55:35', NULL, NULL, 1),
(161, 8, 4, 'tes', '2025-06-07 13:58:17', NULL, NULL, 1),
(162, 8, 4, 'Tes Pesan DB', '2025-06-07 14:00:05', NULL, NULL, 1),
(163, 8, 4, 'tes', '2025-06-07 14:11:16', NULL, NULL, 1),
(164, 8, 20, 'tes', '2025-06-07 14:14:16', NULL, NULL, 1),
(165, 8, 4, 'tes pesan', '2025-06-07 14:14:46', NULL, NULL, 1),
(166, 8, 20, 'iya', '2025-06-07 14:16:00', NULL, NULL, 1),
(167, 8, 20, 'tes lagi', '2025-06-07 14:16:26', NULL, NULL, 1),
(168, 8, 4, 'tes', '2025-06-07 14:16:46', NULL, NULL, 1),
(169, 8, 4, 'kak', '2025-06-07 16:23:05', NULL, NULL, 1),
(170, 8, 4, 'tes', '2025-06-07 16:23:25', NULL, NULL, 1),
(171, 8, 20, 'tes', '2025-06-07 19:09:11', NULL, NULL, 1),
(172, 8, 4, 'hai', '2025-06-07 19:09:34', NULL, NULL, 1),
(173, 8, 4, 'p', '2025-06-07 19:14:32', NULL, NULL, 1),
(174, 10, 25, 'hai ka', '2025-06-08 16:29:13', NULL, NULL, 1),
(175, 10, 20, 'iya halo', '2025-06-08 16:29:36', NULL, NULL, 1),
(176, 10, 25, 'tes', '2025-06-08 16:31:24', NULL, NULL, 1),
(177, 10, 20, 'Tes juga', '2025-06-08 16:31:54', NULL, NULL, 1),
(178, 10, 25, 'Tes', '2025-06-08 16:32:36', 'chat_images/waKJhSPPJKNRl9vb1hN4UhRsGozejNXrnWdKQ4zB.png', NULL, 1),
(179, 11, 27, 'tes ka', '2025-06-08 16:43:31', NULL, NULL, 1),
(180, 11, 20, 'iya masuk', '2025-06-08 16:44:01', NULL, NULL, 1),
(181, 11, 27, 'Tes kakak, bisa crt?', '2025-06-08 17:10:40', NULL, NULL, 1),
(182, 11, 20, 'iyah bisa kok', '2025-06-08 17:11:10', NULL, NULL, 0),
(183, 12, 28, 'Permisi, bisa belajar bareng?', '2025-06-08 18:51:53', NULL, NULL, 1),
(184, 12, 20, 'iya bisa', '2025-06-08 18:52:06', NULL, NULL, 0),
(185, 13, 29, 'tes', '2025-06-09 04:55:59', NULL, NULL, 1),
(186, 13, 20, 'iya', '2025-06-09 04:56:23', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(15,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `user_id`, `balance`, `created_at`, `updated_at`) VALUES
(1, 4, 6772833.00, '2025-02-23 19:06:42', '2025-06-07 18:12:55'),
(4, 19, 10830000.00, '2025-03-17 15:02:33', '2025-03-19 16:23:46'),
(5, 20, 7496670.00, '2025-03-18 17:42:02', '2025-06-09 05:02:09'),
(6, 21, 50000.00, '2025-03-19 16:23:24', '2025-06-01 16:48:08'),
(7, 22, 0.00, '2025-03-19 16:34:02', '2025-03-19 16:34:02'),
(8, 23, 0.00, '2025-06-01 14:48:55', '2025-06-01 14:48:55'),
(11, 25, 430000.00, '2025-06-08 16:21:33', '2025-06-08 16:27:04'),
(12, 26, 0.00, '2025-06-08 16:36:02', '2025-06-08 16:36:02'),
(13, 27, 500000.00, '2025-06-08 16:41:44', '2025-06-08 17:50:09'),
(14, 28, 930000.00, '2025-06-08 18:50:04', '2025-06-09 03:15:55'),
(15, 29, 30000.00, '2025-06-09 04:54:39', '2025-06-09 04:56:53');

-- --------------------------------------------------------

--
-- Table structure for table `wallettransactions`
--

CREATE TABLE `wallettransactions` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','settlement','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallettransactions`
--

INSERT INTO `wallettransactions` (`id`, `user_id`, `order_id`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 18, 'DEPOSIT-1741455326', 55555.00, 'pending', '2025-03-08 17:35:26', '2025-03-08 17:35:26'),
(2, 18, 'DEPOSIT-1741455474', 50000.00, 'pending', '2025-03-08 17:37:55', '2025-03-08 17:37:55'),
(3, 18, 'DEPOSIT-1741455669', 55555.00, 'pending', '2025-03-08 17:41:09', '2025-03-08 17:41:09'),
(4, 18, 'DEPOSIT-1741456047', 100000.00, 'pending', '2025-03-08 17:47:28', '2025-03-08 17:47:28'),
(5, 18, 'DEPOSIT-1741456228', 33333.00, 'settlement', '2025-03-08 17:50:28', '2025-03-08 17:50:36'),
(6, 18, 'DEPOSIT-1741456276', 100000.00, 'pending', '2025-03-08 17:51:16', '2025-03-08 17:51:16'),
(7, 18, 'DEPOSIT-1741456334', 20000.00, 'pending', '2025-03-08 17:52:14', '2025-03-08 17:52:14'),
(8, 18, 'DEPOSIT-1741456388', 1000000.00, 'settlement', '2025-03-08 17:53:08', '2025-03-08 17:53:17'),
(9, 18, 'DEPOSIT-1741456586', 90000.00, 'settlement', '2025-03-08 17:56:27', '2025-03-08 17:56:36'),
(10, 18, 'DEPOSIT-1741456720', 50000.00, 'settlement', '2025-03-08 17:58:40', '2025-03-08 17:58:53'),
(11, 18, 'DEPOSIT-1741456845', 122222.00, 'settlement', '2025-03-08 18:00:45', '2025-03-08 18:00:53'),
(12, 18, 'DEPOSIT-1741456964', 111111.00, 'settlement', '2025-03-08 18:02:44', '2025-03-08 18:02:53'),
(13, 18, 'DEPOSIT-1741463858', 111111.00, 'settlement', '2025-03-08 19:57:40', '2025-03-08 19:57:49'),
(14, 18, 'DEPOSIT-1741536932', 20000000.00, 'settlement', '2025-03-09 16:15:33', '2025-03-09 16:16:05'),
(15, 4, 'DEPOSIT-1742221847', 2000000.00, 'pending', '2025-03-17 14:30:47', '2025-03-17 14:30:47'),
(16, 4, 'DEPOSIT-1742221940', 200000.00, 'pending', '2025-03-17 14:32:21', '2025-03-17 14:32:21'),
(17, 4, 'DEPOSIT-1742222067', 222222.00, 'settlement', '2025-03-17 14:34:27', '2025-03-17 14:34:37'),
(18, 4, 'DEPOSIT-1742222181', 200000.00, 'settlement', '2025-03-17 14:36:22', '2025-03-17 14:36:31'),
(19, 19, 'DEPOSIT-1742223757', 10900000.00, 'settlement', '2025-03-17 15:02:38', '2025-03-17 15:02:47'),
(20, 4, 'DEPOSIT-1742319049', 100000.00, 'pending', '2025-03-18 17:30:50', '2025-03-18 17:30:50'),
(21, 4, 'DEPOSIT-1742381254', 500000.00, 'pending', '2025-03-19 10:47:34', '2025-03-19 10:47:34'),
(22, 4, 'DEPOSIT-1742381285', 6000000.00, 'settlement', '2025-03-19 10:48:06', '2025-03-19 10:48:39'),
(23, 4, 'DEPOSIT-1742563659', 1000000.00, 'settlement', '2025-03-21 13:27:40', '2025-03-21 13:28:01'),
(24, 4, 'DEPOSIT-1743512968', 50000.00, 'settlement', '2025-04-01 13:09:28', '2025-04-01 13:09:54'),
(25, 4, 'DEPOSIT-1748791633', 35000.00, 'settlement', '2025-06-01 15:27:14', '2025-06-01 15:27:34'),
(26, 4, 'DEPOSIT-1748791667', 500000.00, 'settlement', '2025-06-01 15:27:48', '2025-06-01 15:27:58'),
(27, 4, 'DEPOSIT-1748791825', 10000000.00, 'pending', '2025-06-01 15:30:26', '2025-06-01 15:30:26'),
(28, 4, 'DEPOSIT-1748791932', 10000.00, 'pending', '2025-06-01 15:32:13', '2025-06-01 15:32:13'),
(29, 4, 'DEPOSIT-1748882566', 50000.00, 'pending', '2025-06-02 16:42:48', '2025-06-02 16:42:48'),
(30, 4, 'DEPOSIT-1749314448', 500000.00, 'settlement', '2025-06-07 16:40:49', '2025-06-07 16:41:43'),
(31, 4, 'DEPOSIT-1749314612', 50000.00, 'pending', '2025-06-07 16:43:33', '2025-06-07 16:43:33'),
(32, 4, 'DEPOSIT-1749314651', 100000.00, 'settlement', '2025-06-07 16:44:12', '2025-06-07 16:44:19'),
(33, 25, 'DEPOSIT-1749399876', 500000.00, 'settlement', '2025-06-08 16:24:37', '2025-06-08 16:26:09'),
(34, 27, 'DEPOSIT-1749400914', 50000.00, 'pending', '2025-06-08 16:41:54', '2025-06-08 16:41:54'),
(35, 27, 'DEPOSIT-1749400931', 500000.00, 'settlement', '2025-06-08 16:42:12', '2025-06-08 16:42:34'),
(36, 27, 'DEPOSIT-1749404995', 50000.00, 'pending', '2025-06-08 17:49:56', '2025-06-08 17:49:56'),
(37, 27, 'DEPOSIT-1749405000', 500000.00, 'settlement', '2025-06-08 17:50:01', '2025-06-08 17:50:09'),
(38, 28, 'DEPOSIT-1749408622', 500000.00, 'settlement', '2025-06-08 18:50:23', '2025-06-08 18:50:46'),
(39, 28, 'DEPOSIT-1749438924', 500000.00, 'settlement', '2025-06-09 03:15:26', '2025-06-09 03:15:55'),
(40, 29, 'DEPOSIT-1749444889', 100000.00, 'settlement', '2025-06-09 04:54:50', '2025-06-09 04:55:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `mschatroom`
--
ALTER TABLE `mschatroom`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `msrole`
--
ALTER TABLE `msrole`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mssubject`
--
ALTER TABLE `mssubject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `msuser`
--
ALTER TABLE `msuser`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_role` (`role`),
  ADD KEY `fk_subjectClass` (`subjectClass`);

--
-- Indexes for table `mswithdraw`
--
ALTER TABLE `mswithdraw`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_notifications_transaction_id` (`transaction_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_thread` (`thread_id`),
  ADD KEY `fk_posts_user` (`user_id`);

--
-- Indexes for table `roomvideocall`
--
ALTER TABLE `roomvideocall`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `roomzoomcall`
--
ALTER TABLE `roomzoomcall`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `host_id` (`host_id`),
  ADD KEY `participant_id` (`participant_id`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_threads_user` (`user_id`),
  ADD KEY `fk_threads_subject` (`subject_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `tutor_id` (`tutor_id`),
  ADD KEY `fk_roomzoomcall` (`roomzoomcall_id`),
  ADD KEY `fk_transactions_subject` (`subject_id`);

--
-- Indexes for table `trmessages`
--
ALTER TABLE `trmessages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `wallettransactions`
--
ALTER TABLE `wallettransactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mschatroom`
--
ALTER TABLE `mschatroom`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `msrole`
--
ALTER TABLE `msrole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mssubject`
--
ALTER TABLE `mssubject`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `msuser`
--
ALTER TABLE `msuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `mswithdraw`
--
ALTER TABLE `mswithdraw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `roomvideocall`
--
ALTER TABLE `roomvideocall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `roomzoomcall`
--
ALTER TABLE `roomzoomcall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `trmessages`
--
ALTER TABLE `trmessages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `wallettransactions`
--
ALTER TABLE `wallettransactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `mschatroom`
--
ALTER TABLE `mschatroom`
  ADD CONSTRAINT `mschatroom_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mschatroom_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `msuser`
--
ALTER TABLE `msuser`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role`) REFERENCES `msrole` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subjectClass` FOREIGN KEY (`subjectClass`) REFERENCES `mssubject` (`id`);

--
-- Constraints for table `mswithdraw`
--
ALTER TABLE `mswithdraw`
  ADD CONSTRAINT `mswithdraw_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_transaction_id` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_thread` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roomvideocall`
--
ALTER TABLE `roomvideocall`
  ADD CONSTRAINT `roomvideocall_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roomzoomcall`
--
ALTER TABLE `roomzoomcall`
  ADD CONSTRAINT `roomzoomcall_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roomzoomcall_ibfk_2` FOREIGN KEY (`host_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roomzoomcall_ibfk_3` FOREIGN KEY (`participant_id`) REFERENCES `msuser` (`id`);

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `fk_threads_subject` FOREIGN KEY (`subject_id`) REFERENCES `mssubject` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_threads_user` FOREIGN KEY (`user_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_roomzoomcall` FOREIGN KEY (`roomzoomcall_id`) REFERENCES `roomzoomcall` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_subject` FOREIGN KEY (`subject_id`) REFERENCES `mssubject` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `msuser` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trmessages`
--
ALTER TABLE `trmessages`
  ADD CONSTRAINT `trmessages_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `mschatroom` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trmessages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `msuser` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
