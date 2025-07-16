-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2025 at 02:39 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci3`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(2);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(6, 6, 'ddddd', 'dddddddddd', NULL, NULL),
(9, 4, 'dsd', 'dsds', NULL, NULL),
(10, 4, 'gf', 'gfgdfg', NULL, NULL),
(11, 4, 'fdg', 'fgdgfdg', NULL, NULL),
(12, 4, 'ki', 'iiii', NULL, NULL),
(19, 6, '222', '222', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `created_at`, `status`) VALUES
(2, 'fefddfdfs', '', 'dharmesh.maladfdv2001@gmail.com', '$2y$10$7kmOQvd3LVthqKVbtx3AguPpzRGe.rAB5ZZXzZWhPysz58qnhcpEC', 'admin', '2025-07-13 18:28:47', 'active'),
(3, 'abcd', '', 'abcd@gmail.com', '$2y$10$zB9PAHzC.cSbVdCBOGRkb..fgPtTiqNiHnNQBSa5jM7NHDBqcfPLW', 'superadmin', '2025-07-13 18:29:52', 'active'),
(4, 'ewe', '', 'dharmesh.malav2001@gmail.com', '$2y$10$H52r2kZhyzsMoH2kOAqAPeEYkYGeXnEWTYAlWxjWK/s2YdGKb8f.q', 'user', '2025-07-13 18:31:15', 'active'),
(5, 'abcde', '', 'abcde@gmail.com', '$2y$10$N9gO5QpvvZztza/r2EqF/.Z263AP78azjWzSxUhkpvB0q/UQ5OAJW', 'admin', '2025-07-13 18:36:39', 'active'),
(6, 'alpha', '', 'alpha@gmail.com', '$2y$10$PgZaRpSK7HoM9fyCe07NO.OysW7kq4xgTpK6h6yyxClkJxanSyAyK', 'user', '2025-07-14 06:26:43', 'active'),
(7, 'dfd', '', 'dharmesh.malav200@gmail.com', '$2y$10$xxa7zqaO4ZaxAi.4h2BkJO633/o0iSiKhaeZDNNYa7oK08.RybIje', 'admin', '2025-07-14 06:38:42', 'active'),
(8, 'qqqq', '', 'qq@gmail.com', '$2y$10$kcitYB8B2Jxf/7I03RTOHuN2oFPhTFcz6inMfhMzTP.lJSsI05NnG', 'user', '2025-07-14 10:17:01', 'active'),
(9, 'hello', '', 'hello@gmail.com', '$2y$10$VrpChgx8homaLGo8l5.LP.VskdOPl0FeUwvC.QlU3tI8UoU9pL2iS', 'user', '2025-07-14 10:39:35', 'active'),
(10, 'fdf', '', 'ffd@gmail.com', '$2y$10$K5DOa1TahhCmcGeyuxwSk.JfmQ9WDeLE2f4tYEtZD5Hew5wHy1msq', 'user', '2025-07-14 11:00:53', 'active'),
(11, 'zzzzz', '', 'zzzzz@gmail.com', '$2y$10$TrICy5UPH.HoetXR5Au38O0nvAfmrlPr78zQ6/33U5SvSTkBEzUwO', 'user', '2025-07-14 11:12:45', 'active'),
(12, 'qwerty', '', 'qwerty@gmail.com', '$2y$10$xL8Q9/qbCx3OZusN426odup/ApOuz.n.qtLb5bd/O8fclEI3itprq', 'user', '2025-07-14 11:22:01', 'active'),
(13, 'ewer', '', 'rere@gmail.com', '$2y$10$h.q4saatSz4OR/vheHa0VOwuu/3BuRx8gVSpjyK1GBYwj4Tig1N1a', 'user', '2025-07-14 11:29:38', 'active'),
(14, 'dd', '', 'ddsd1@gmail.com', '$2y$10$jKZH2yS7xlpAuXiJ3nE0.uxsa/pdz.y1UqEVJGck3VpbP00xZOJhC', 'user', '2025-07-14 11:40:42', 'active'),
(15, 'qwwqw', '', 'wqwqw@gmail.com', '$2y$10$APLH.jW6gGmnrK1wExxqQ.aW3BYk6qer.g9bG2trOUshkSWwAJmWe', 'user', '2025-07-14 11:41:10', 'active'),
(16, 'gamaa', '', 'gamma@gmail.com', '$2y$10$xmguA5hNbAc3zYBQYl3BmOquRIn3wNDeN2QzfElxw73rtztGGmNli', 'user', '2025-07-14 11:43:50', 'active'),
(17, 'aaa', '', 'aaa@gmail.com', '$2y$10$xm7ZLVkyRe/o.oomuoZLDeoTOw5wSLS0u6VXeuWe86Gyiz2r42RXS', 'user', '2025-07-16 10:10:14', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
