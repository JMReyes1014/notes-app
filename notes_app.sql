-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 06:09 AM
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
-- Database: `notes_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_password` varchar(255) DEFAULT NULL,
  `admin_username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_password`, `admin_username`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `notes_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`notes_id`, `user_id`, `content`, `date`) VALUES
(74, 19, 'U01pS3Z3KytGOEo1NUhsNFRKVlFmUT09', '2025-01-18 12:33:09'),
(75, 19, 'M0RDZHE2bk95cEFZWHJDNEFRVFBFUT09', '2025-01-18 12:36:13'),
(76, 19, 'UUJ2SHlmNUZSenpIOHc5anlmdWVqQT09', '2025-01-18 12:36:48'),
(77, 19, 'a2FvaGhSRHZkSm9nMVlSNUttVnJ1Zz09', '2025-01-18 12:49:29'),
(80, 19, 'N0NXSU5nM2ZiR0I4OWN1Y3l4YUtsMEkwOXdYOXJYbzQyanhPL2NNc09rZz0=', '2025-01-18 13:00:48'),
(81, 28, 'dW4wVFV1QWtRcktDMFQxL3JkcEtzcGFuR2RST1I4bU5tdVVYd3JGclN3UT0=', '2025-01-18 13:07:24'),
(82, 28, 'YjBtZnVrWHlHbDJZK1QzYUxkN1NnRHNPYklNTEY4ODZmQm1BNW5td0c5VT0=', '2025-01-18 13:07:34'),
(83, 28, 'YjBtZnVrWHlHbDJZK1QzYUxkN1NnSUkzdk15UHpVbTM1QzhiTjdnaTFPOD0=', '2025-01-18 13:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_password`) VALUES
(19, 'jm', '$2y$10$QdqaIFmMcbjMwOv6uDBOD.oWwwsug5g7d1LX82065VGlLIT9oLvMa'),
(20, 'acc', '$2y$10$gkP/VrRINRgU4L/5l04j/eB3KeHKaidpgfsKnO2zgoJ5.7esgkL7S'),
(21, 'acc2', '$2y$10$0Z.xMPtUvb1JT058HArcf.IqCwP8DM1PUPmFky4.ATjX/trtsEZle'),
(22, 'acc4', '$2y$10$s1p/knBX/nSN0JL7MskA5evXjS/Y6fn8tZZCw3niBg9sW2i910r66'),
(23, 'acc3', '$2y$10$j6nnAQ1fjRaLbuhK8zYSMeZExmE7sp4d2xgzfqUFMUOUrvZ5.FHpS'),
(24, 'acc6', '$2y$10$JOKH/ArFVp7GrZcXg3is9.x2RuA6ufiHfP9MC7E53wYASjI.sa/K.'),
(25, 'acc8', '$2y$10$oTZOyIoa4rGgygAABDr5R.i0BrYkAGgxbyJS6ytCHLlqy5cpba2su'),
(26, 'acc10', '$2y$10$G2Jbq6BmxIH7.QUuQb/rouZe4Ja10R/gH4tU5DcT4QMz.hBjNB25W'),
(28, 'testing', '$2y$10$eHbf8XMYtt8HvDdKmwrb1es.rZ94ewnX.aThJl2qzRrohf5ehj7ee'),
(29, 'accounttest', '$2y$10$26HyMUqGrvuIhIDF19kj8.3nuehXBO/GKIAbPC8RnfHWg9NcwPeXO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`notes_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `notes_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
