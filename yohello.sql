-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2021 at 05:56 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_body` text NOT NULL,
  `posted_by` varchar(60) NOT NULL,
  `posted_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `removed` varchar(3) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_body`, `posted_by`, `posted_to`, `date_added`, `removed`, `post_id`) VALUES
(1, 'It\'s okay jack, just dab thorugh the pain :)', 'manoj_singh', 'jack_septiceye', '2021-06-03 12:08:13', 'no', 3),
(2, 'hey man! nothing much!', 'jack_septiceye', 'manoj_singh', '2021-06-03 12:55:33', 'no', 4);

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `user_from` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `username`, `post_id`) VALUES
(1, 'manoj_singh', 3),
(2, 'manoj_singh', 1),
(3, 'jack_septiceye', 4),
(4, 'jack_septiceye', 5),
(5, 'jack_septiceye', 7);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `date` datetime NOT NULL,
  `opened` varchar(3) NOT NULL,
  `viewed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_to`, `user_from`, `body`, `date`, `opened`, `viewed`, `deleted`) VALUES
(1, 'jack_septiceye', 'manoj_singh', 'hello', '2021-06-03 13:14:28', 'yes', 'yes', 'no'),
(2, 'manoj_singh', 'jack_septiceye', 'hey', '2021-06-03 14:01:51', 'yes', 'yes', 'no'),
(3, 'test_case', 'jack_septiceye', 'hey nice to have you', '2021-06-04 15:27:19', 'no', 'yes', 'no'),
(4, 'test_case', 'jack_septiceye', 'hey great', '2021-06-04 15:46:56', 'no', 'yes', 'no'),
(5, 'test_case', 'manoj_singh', 'hey test case', '2021-06-04 16:06:28', 'no', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(100) NOT NULL,
  `datetime` datetime NOT NULL,
  `opened` varchar(3) NOT NULL,
  `viewed` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_to`, `user_from`, `message`, `link`, `datetime`, `opened`, `viewed`) VALUES
(1, 'jack_septiceye', 'manoj_singh', 'Manoj Singh liked your post!', 'post.php?id=3', '2021-06-03 12:07:41', 'yes', 'yes'),
(2, 'jack_septiceye', 'manoj_singh', 'Manoj Singh liked your post!', 'post.php?id=1', '2021-06-03 12:07:45', 'yes', 'yes'),
(3, 'jack_septiceye', 'manoj_singh', 'Manoj Singh commented on your post!', 'post.php?id=3', '2021-06-03 12:08:13', 'yes', 'yes'),
(4, 'jack_septiceye', 'manoj_singh', 'Manoj Singh posted on your profile!', 'post.php?id=4', '2021-06-03 12:08:54', 'yes', 'yes'),
(5, 'manoj_singh', 'jack_septiceye', 'Jack Septiceye commented on your post!', 'post.php?id=4', '2021-06-03 12:55:33', 'yes', 'yes'),
(6, 'manoj_singh', 'jack_septiceye', 'Jack Septiceye liked your post!', 'post.php?id=4', '2021-06-03 12:55:42', 'yes', 'yes'),
(7, 'manoj_singh', 'jack_septiceye', 'Jack Septiceye liked your post!', 'post.php?id=5', '2021-06-03 13:52:19', 'yes', 'yes'),
(8, 'manoj_singh', 'test_case', 'Test Case posted on your profile!', 'post.php?id=6', '2021-06-04 15:08:00', 'yes', 'yes'),
(9, 'test_case', 'jack_septiceye', 'Jack Septiceye liked your post!', 'post.php?id=7', '2021-06-04 15:46:43', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `added_by` varchar(60) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL,
  `likes` int(11) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `added_by`, `user_to`, `date_added`, `user_closed`, `deleted`, `likes`, `image`) VALUES
(1, 'Hey i love this song! <br /><iframe width=\'420\' height=\'315\' src=\'https://www.youtube.com/embed/hzo1_maqV_w\'></iframe><br />', 'jack_septiceye', 'none', '2021-06-03 10:39:48', 'no', 'no', 1, ''),
(2, 'No thoughts head empty!', 'jack_septiceye', 'none', '2021-06-03 10:40:03', 'no', 'no', 0, ''),
(3, 'mood :)', 'jack_septiceye', 'none', '2021-06-03 10:40:36', 'no', 'no', 1, 'assets/images/posts/60b8644c13d14anime.jpg'),
(4, 'Hey Jack, what\'s up?', 'manoj_singh', 'jack_septiceye', '2021-06-03 12:08:54', 'no', 'no', 1, ''),
(5, 'Jack your taste in music is really good!', 'manoj_singh', 'none', '2021-06-03 13:51:32', 'no', 'no', 1, ''),
(6, 'We\'re all going to die anyway! ', 'test_case', 'manoj_singh', '2021-06-04 15:07:59', 'no', 'no', 0, ''),
(7, 'I think i might have to go for this!', 'test_case', 'none', '2021-06-04 15:09:06', 'no', 'yes', 1, 'assets/images/posts/60b9f4ba073b8shadow-hunter-deadline-games-9.jpg'),
(8, 'love is a lie', 'manoj_singh', 'none', '2021-06-04 22:22:12', 'no', 'yes', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `trends`
--

CREATE TABLE `trends` (
  `title` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trends`
--

INSERT INTO `trends` (`title`, `hits`) VALUES
('Jack', 1),
('Taste', 1),
('Music', 1),
('Die', 1),
('Anyway', 1),
('Love', 1),
('Lie', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `signup_date` date NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_likes` int(11) NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `friend_array` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `signup_date`, `profile_pic`, `num_posts`, `num_likes`, `user_closed`, `friend_array`) VALUES
(1, 'Jack', 'Septiceye', 'jack_septiceye', 'Jack@example.com', '5d41402abc4b2a76b9719d911017c592', '2021-06-03', 'assets/images/profile_pics/jack_septiceyee95f671d5bc7e28745a9a2b02e90bba8n.jpeg', 3, 2, 'no', ',manoj_singh,test_case,'),
(2, 'Manoj', 'Singh', 'manoj_singh', 'Manoj@example.com', '5d41402abc4b2a76b9719d911017c592', '2021-06-03', 'assets/images/profile_pics/manoj_singh44017aad50c3284793fe875bacf66794n.jpeg', 3, 2, 'no', ',jack_septiceye,test_case,'),
(3, 'Test', 'Case', 'test_case', 'Test@example.com', '5d41402abc4b2a76b9719d911017c592', '2021-06-04', 'assets/images/profile_pics/defaults/head_wet_asphalt.png', 2, 1, 'no', ',jack_septiceye,manoj_singh,');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
