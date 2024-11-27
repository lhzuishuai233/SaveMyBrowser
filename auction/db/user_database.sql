-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2024-11-27 23:17:13
-- 服务器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `user_database`
--

-- --------------------------------------------------------

--
-- 表的结构 `login_attempts`
--

CREATE TABLE `login_attempts` (
  `attempt_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attempt_time` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(300) NOT NULL,
  `account_type` enum('buyer','seller') NOT NULL,
  `created_time` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `status` enum('active','paused','deleted') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `account_type`, `created_time`, `last_login`, `status`) VALUES
(1, 'RichardNonext@outlook.com', '$2y$10$JBWlpGPO1JjgZdB4rSObi.WVmkJzulUPpF0sry81kcj6n4HPYFZIu', 'buyer', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active'),
(2, 'Nonext@outlook.com', '$2y$10$t4BiVcFiBIQrzokjTEghk.CAmUTXO1QDom1nXLL0SkxHA4MKzX1.a', 'seller', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active'),
(3, '11111@outlook.com', '$2y$10$xaXXxfYWz.id.Aup9isMsO.EsoNmvPc1zsJXsoqrt85vWNO4eDJku', 'seller', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active'),
(4, 'wwwwww@ucl.com', '$2y$10$KaPaWgfm1ox5jMpkpfvmuOxFKUWGZJa/FEuxMhBLCiHY7ZH2w/nO2', 'buyer', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active'),
(5, 'wwwww@ucl.com', '$2y$10$uBIn4nlfTX0OlffFtYJFAOaMcFEPDbtpt8rVnM5uzqhpden2Vq4c6', 'buyer', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active'),
(6, '1@jmail.com', '$2y$10$a9QMkqVCHI8xIakLM7OuseE/A1vi1/uXlB37I5RXZoAq39WC3IfK.', 'buyer', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active');

-- --------------------------------------------------------

--
-- 表的结构 `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` int(255) NOT NULL,
  `adress` text DEFAULT NULL,
  `phone_number` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD KEY `email_id` (`user_id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 表的索引 `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `attempt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 限制导出的表
--

--
-- 限制表 `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);

--
-- 限制表 `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
