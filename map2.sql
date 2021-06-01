-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 01, 2021 at 07:27 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `map2`
--

-- --------------------------------------------------------

--
-- Table structure for table `anfragen`
--

CREATE TABLE `anfragen` (
  `id` int(11) NOT NULL,
  `helfer_id` int(11) NOT NULL,
  `hilfesuchender_id` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `anfragen`
--

INSERT INTO `anfragen` (`id`, `helfer_id`, `hilfesuchender_id`, `status`) VALUES
(5, 26, 6, 1),
(8, 26, 15, 1),
(9, 26, 7, 1),
(11, 15, 16, 2),
(13, 24, 19, 1),
(15, 29, 16, 1),
(16, 29, 19, 1),
(105, 8, 8, 1),
(106, 8, 19, 1),
(107, 8, 16, 1),
(108, 8, 15, 2),
(109, 8, 14, 2),
(110, 8, 13, 2),
(111, 8, 12, 2),
(112, 8, 11, 2),
(113, 8, 9, 2),
(114, 8, 7, 2),
(115, 8, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `helfer`
--

CREATE TABLE `helfer` (
  `id` int(11) NOT NULL,
  `vn` varchar(255) NOT NULL,
  `nn` varchar(255) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `hilfeart` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `helfer`
--

INSERT INTO `helfer` (`id`, `vn`, `nn`, `longitude`, `latitude`, `hilfeart`) VALUES
(8, 'Max', 'Mustermann', 15, 15, 'Garten'),
(13, 'Max', 'Mustermann', 12, 13, 'Garten'),
(15, 'Max', 'Mustermann', 31.3656, 30.0719, 'Garten'),
(16, 'Max', 'Mustermann', 31.3656, 30.0719, 'Garten'),
(18, 'Max', 'Mustermann', 31.3656, 30.0719, 'Garten'),
(19, 'Max', 'Mustermann', 31.3656, 30.0719, 'Garten'),
(20, 'Max1', 'Mustermann', 31.3656, 30.0719, 'Garten'),
(21, 'Max', 'Mustermann', 12.01, 12.06, 'Garten'),
(22, 'here', 'is', 31.537, 29.9538, 'Garten'),
(23, 'Max', 'Mustermann', 15.01, 12.05, 'Garten'),
(24, 'Max', 'Mustermann', 31.537, 29.9538, 'Garten'),
(25, 'hello', 'world', 31.537, 29.9538, 'Garten'),
(26, 'Python', 'King', 12.01, 13.02, 'Garten, Elnkaufen, Haushalt'),
(27, 'Haushalt', 'helfer', 15, 13, 'Haushalt'),
(28, 'Max', 'Mustermann', 31.3851, 30.045, 'Garten'),
(29, 'jone', 'doe', 12, 13, 'Elnkaufen');

-- --------------------------------------------------------

--
-- Table structure for table `hilfesuchender`
--

CREATE TABLE `hilfesuchender` (
  `id` int(11) NOT NULL,
  `nn` varchar(250) NOT NULL,
  `vn` varchar(250) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL,
  `dringlichkeit` int(11) NOT NULL,
  `hilfeart` varchar(250) NOT NULL,
  `avail_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hilfesuchender`
--

INSERT INTO `hilfesuchender` (`id`, `nn`, `vn`, `longitude`, `latitude`, `dringlichkeit`, `hilfeart`, `avail_status`) VALUES
(1, 'Magdy', 'Mahmoud', 9, 9, 0, 'Garten', 0),
(6, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(7, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(8, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(9, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(11, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(12, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(13, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(14, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(15, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(16, 'Mustermann', 'Max', 31.3656, 30.0719, 0, 'Garten', 1),
(17, 'Haushalt', 'new', 15, 12, 0, 'Haushalt', 0),
(18, 'doe', 'some', 12.01, 12.06, 0, 'Haushalt', 0),
(19, 'Mustermann', 'Max', 31.3851, 30.045, 0, 'Garten', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anfragen`
--
ALTER TABLE `anfragen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hilfer_relation` (`helfer_id`),
  ADD KEY `hilfesuchender_relation` (`hilfesuchender_id`);

--
-- Indexes for table `helfer`
--
ALTER TABLE `helfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hilfesuchender`
--
ALTER TABLE `hilfesuchender`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anfragen`
--
ALTER TABLE `anfragen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `helfer`
--
ALTER TABLE `helfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `hilfesuchender`
--
ALTER TABLE `hilfesuchender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anfragen`
--
ALTER TABLE `anfragen`
  ADD CONSTRAINT `hilfer_relation` FOREIGN KEY (`helfer_id`) REFERENCES `helfer` (`id`),
  ADD CONSTRAINT `hilfesuchender_relation` FOREIGN KEY (`hilfesuchender_id`) REFERENCES `hilfesuchender` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
