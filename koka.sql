-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2020 at 03:27 PM
-- Server version: 10.4.11-MariaDB
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
-- Database: `koka`
--

-- --------------------------------------------------------

--
-- Table structure for table `imdb`
--

CREATE TABLE `imdb` (
  `id` int(11) NOT NULL,
  `writer` varchar(250) DEFAULT NULL,
  `stars` text DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `genre` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(250) DEFAULT NULL,
  `trailer` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `top_rated`
--

CREATE TABLE `top_rated` (
  `id` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `writer` varchar(250) DEFAULT NULL,
  `stars` text DEFAULT NULL,
  `grade` varchar(250) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `genre` varchar(250) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(250) DEFAULT NULL,
  `trailer` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `imdb`
--
ALTER TABLE `imdb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top_rated`
--
ALTER TABLE `top_rated`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `imdb`
--
ALTER TABLE `imdb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `top_rated`
--
ALTER TABLE `top_rated`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
