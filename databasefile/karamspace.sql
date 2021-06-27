-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 27, 2021 at 09:57 AM
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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(20) NOT NULL,
  `post_id` int(10) NOT NULL,
  `comment_content` varchar(100) NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `comment_content`, `comment_date`) VALUES
(4, 8, 'allah ma3ak ya 7lo', '2021-06-18 16:37:43'),
(6, 15, 'a7la shabab', '2021-06-18 21:14:41'),
(7, 15, 'hala walah', '2021-06-18 21:15:03'),
(13, 16, 'last but no least', '2021-06-19 12:05:48'),
(15, 16, 'Hi', '2021-06-19 12:05:59'),
(16, 17, 'يا هلااااا', '2021-06-19 14:51:13'),
(17, 17, ' كيفك يا زلمي اشتقنالك ولو', '2021-06-19 13:51:44'),
(20, 32, 'Hello', '2021-06-27 07:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `friend_request`
--

CREATE TABLE `friend_request` (
  `request_id` int(11) NOT NULL,
  `request_from_id` int(11) NOT NULL,
  `request_to_id` int(11) NOT NULL,
  `request_status` enum('pending','confirm') NOT NULL,
  `request_notify_status` enum('yes','no') NOT NULL DEFAULT 'no',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `friend_request`
--

INSERT INTO `friend_request` (`request_id`, `request_from_id`, `request_to_id`, `request_status`, `request_notify_status`, `request_date`) VALUES
(30, 17, 16, 'confirm', 'yes', '2021-06-26 08:54:27'),
(32, 5, 16, 'confirm', 'yes', '2021-06-27 07:00:08');

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
(10, 3, 'images/uploads/WIN_20200222_00_51_42_Pro.jpg', 'WOW', 0, '2021-06-18 07:34:30'),
(11, 10, 'images/uploads/WIN_20200222_00_51_52_Pro.jpg', 'Hello', 0, '2021-06-18 13:12:47'),
(12, 10, 'images/uploads/WIN_20200222_00_51_42_Pro.jpg', 'Crazy', 0, '2021-06-18 13:13:05'),
(14, 5, 'images/uploads/xyc6w5u.jpg', 'fdasfas', 0, '2021-06-18 13:21:29'),
(16, 6, 'images/uploads/ACER Background (3).jpg', 'Hello I am new here', 0, '2021-06-19 05:51:32'),
(20, 5, 'images/uploads/IMG_33178.jpg', 'lkjglkd', 0, '2021-06-19 17:45:26'),
(21, 6, 'images/uploads/IMG_33145.jpg', 'karam', 0, '2021-06-19 17:56:19'),
(22, 6, 'images/uploads/WIN_20200222_00_51_48_Pro.jpg', 'fjkasdjlfdjaf', 0, '2021-06-19 17:56:52'),
(23, 6, 'images/uploads/WIN_20200222_00_51_52_Pro.jpg', 'vkljfzlkgd', 0, '2021-06-19 17:57:10'),
(24, 6, 'images/uploads/WIN_20200222_00_51_53_Pro.jpg', 'klgjfss', 0, '2021-06-19 17:57:29'),
(25, 6, 'images/uploads/WIN_20200222_00_51_50_Pro.jpg', 'hhh', 0, '2021-06-19 17:58:47'),
(27, 12, 'images/uploads/ACER Background (6).jpg', 'hello', 0, '2021-06-19 18:59:24'),
(29, 17, 'images/uploads/ACER Background (6).jpg', 'hey', 0, '2021-06-22 06:37:45'),
(30, 5, 'images/uploads/ACER Background (4).jpg', '', 0, '2021-06-22 18:27:42'),
(31, 29, 'images/uploads/ACER Background (3).jpg', '', 0, '2021-06-24 08:14:31'),
(32, 16, 'images/uploads/ACER Background (4).jpg', 'Hello guys', 0, '2021-06-27 07:51:42');

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
(5, 'karam', 'nassar', 'karam@hotmail.com', '12345', 'male', '16/April/2009', 'images/profile_pictures/ACER Background (9).jpg'),
(16, 'hussam', 'nassar', 'hussam@hotmail.com', '12345', 'male', '21/January/1998', 'images/profile_pictures/default.jpg'),
(17, 'nour', 'nassar', 'nour@hotmail.com', '12345', 'female', '1/January/2021', 'images/profile_pictures/ACER Background (9).jpg'),
(18, 'hassan', 'jfkad', 'hassan@hotmail.com', '12345', 'female', '1/January/2021', 'images/profile_pictures/default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `friend_request`
--
ALTER TABLE `friend_request`
  ADD PRIMARY KEY (`request_id`);

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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `friend_request`
--
ALTER TABLE `friend_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
