-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 18, 2021 at 10:12 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `karamspace`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `post_image` varchar(50) DEFAULT NULL,
  `post_content` text NOT NULL,
  `post_likes` int(100) DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `post_image`, `post_content`, `post_likes`, `post_date`) VALUES
(2, 9, 'images/uploads/xyc6w5u.jpg', 'Hello', 0, '2021-06-17 13:53:40'),
(5, 3, 'images/uploads/WIN_20200222_00_51_48_Pro.jpg', 'hussam', 0, '2021-06-17 15:09:54'),
(8, 5, 'images/uploads/WIN_20200222_00_51_55_Pro.jpg', 'Good morning :)', 0, '2021-06-18 07:24:49'),
(10, 3, 'images/uploads/WIN_20200222_00_51_42_Pro.jpg', 'WOW', 0, '2021-06-18 07:34:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `birthday` varchar(100) NOT NULL,
  `profile_picture` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `password`, `gender`, `birthday`, `profile_picture`) VALUES
(3, 'hussam', 'nassar', 'hussam@hotmail.com', '12345', 'male', '17/June/2021', NULL),
(5, 'karam', 'nassar', 'karam@hotmail.com', '12345', 'male', '16/April/2009', 'images/images.jpg'),
(6, 'nour', 'nassar', 'nour@hotmail.com', '12345', 'female', '1/January/1973', NULL),
(7, 'norma', 'nassar', 'nourma@hotmail.com', '12345', 'female', '1/January/1982', NULL),
(8, 'huda', 'issa', 'huda@hotmail.com', '12345', 'female', '1/January/1964', NULL),
(9, 'issa', 'nassar', 'issa@hotmail.com', '12345', 'male', '1/January/1949', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
