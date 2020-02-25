-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2020 at 08:29 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `offer`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(1, '::1', 'admin', '2020-02-11 04:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `offer_name` varchar(100) NOT NULL,
  `offer_slug` varchar(255) NOT NULL,
  `offer_description` text NOT NULL,
  `offer_discount` int(11) NOT NULL,
  `offer_start` date NOT NULL,
  `offer_end` date NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `offer_barcode` varchar(25) NOT NULL,
  `visit_count` int(11) NOT NULL,
  `offer_status` tinyint(1) NOT NULL DEFAULT '1',
  `offer_created_at` datetime NOT NULL,
  `offer_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `offer_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`offer_id`, `offer_name`, `offer_slug`, `offer_description`, `offer_discount`, `offer_start`, `offer_end`, `restaurant_id`, `template_id`, `offer_barcode`, `visit_count`, `offer_status`, `offer_created_at`, `offer_modified_at`, `offer_creator`) VALUES
(1, 'Damaka Offer', 'damaka-offer', '<h1>Damaka Offer</h1>\n<p>this is a damaka offer</p>', 35, '2020-02-06', '2020-02-28', 1, 3, 'OFFD1581016703', 1, 1, '2020-02-06 20:18:23', '2020-02-06 19:18:23', 2),
(2, 'Gorom Offer', 'gorom-offer', '<h1>Damaka Offer</h1>\n<p>this is a damaka offer</p>', 35, '2020-02-06', '2020-02-28', 2, 3, 'OFFD1581016742', 0, 1, '2020-02-06 20:19:02', '2020-02-06 19:19:02', 2),
(3, 'Damaka Offer', 'damaka-offer', '<h1>Damaka Offer</h1>\n<p>this is a damaka offer</p>', 35, '2020-02-06', '2020-02-28', 3, 3, 'OFFPD1581016762', 2, 1, '2020-02-06 20:19:22', '2020-02-06 19:19:22', 2),
(4, 'Special Offer', 'special-offer', '<h2>Special Offer Alert</h2><p>this offer is very special</p>', 50, '2020-03-01', '2020-03-26', 4, 2, 'OFFPS1581068744', 3, 1, '2020-02-07 10:45:44', '2020-02-07 09:45:44', 2),
(5, 'FataFati Offer', 'fatafati-offer', '<p>Fata-Fati Offer Alert</p><p>order now!!</p>', 75, '2020-04-01', '2020-04-30', 5, 3, 'OFFPF1581069103', 0, 1, '2020-02-07 10:51:43', '2020-02-07 09:51:43', 2),
(6, 'Heart Attack Offer', 'heart-attack-offer', '<p>This is a big offer</p>', 55, '2020-02-03', '2020-02-21', 1, 3, 'OFFFH1581103346', 0, 1, '2020-02-07 20:22:26', '2020-02-07 19:22:26', 2),
(7, 'Valentines day offer', 'valentines-day-offer', '<p>Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;</p>', 50, '2020-02-14', '2020-02-28', 2, 1, 'OFFFV1581411316', 0, 1, '2020-02-11 09:55:16', '2020-02-11 08:55:16', 2);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `model_name` varchar(20) NOT NULL,
  `action` varchar(10) NOT NULL,
  `permission_status` tinyint(1) NOT NULL DEFAULT '1',
  `permission_created_at` date NOT NULL,
  `permission_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `permission_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `role_id`, `model_name`, `action`, `permission_status`, `permission_created_at`, `permission_updated_at`, `permission_creator`) VALUES
(1, 2, 'restaurant', 'create', 1, '2020-01-07', '2020-01-07 10:04:31', 1),
(2, 2, 'restaurant', 'list-view', 1, '2020-01-07', '2020-01-07 10:18:23', 1),
(3, 1, 'tag', 'list-view', 1, '2020-01-08', '2020-01-07 18:56:39', 1),
(4, 1, 'tag', 'create', 1, '2020-01-08', '2020-01-07 18:57:10', 1),
(5, 1, 'tag', 'edit', 1, '2020-01-08', '2020-01-07 18:57:23', 1),
(6, 1, 'tag', 'delete', 1, '2020-01-08', '2020-01-07 18:57:38', 1),
(7, 1, 'user', 'list-view', 1, '2020-01-19', '2020-01-19 15:28:19', 1),
(8, 1, 'user', 'create', 1, '2020-01-19', '2020-01-19 17:27:27', 1),
(9, 2, 'restaurant', 'edit', 1, '2020-01-23', '2020-01-23 12:18:54', 1),
(10, 2, 'offer', 'create', 1, '2020-01-25', '2020-01-25 17:33:34', 2),
(11, 2, 'offer', 'list-view', 1, '2020-01-25', '2020-01-25 17:33:34', 2),
(12, 2, 'offer', 'edit', 1, '2020-01-25', '2020-01-25 17:33:58', 2),
(13, 2, 'offer', 'delete', 1, '2020-01-25', '2020-01-25 17:33:58', 2),
(14, 1, 'template', 'create', 1, '2020-02-06', '2020-02-06 11:30:53', 1),
(15, 1, 'template', 'list-view', 1, '2020-02-06', '2020-02-06 11:30:53', 1),
(16, 1, 'template', 'edit', 1, '2020-02-06', '2020-02-06 11:31:10', 1),
(17, 1, 'template', 'delete', 1, '2020-02-06', '2020-02-06 11:31:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `restaurant_id` int(11) NOT NULL,
  `restaurant_moto` varchar(200) NOT NULL,
  `restaurant_name` varchar(200) NOT NULL,
  `restaurant_contact_number` varchar(200) NOT NULL,
  `restaurant_email` varchar(200) NOT NULL,
  `restaurant_logo` varchar(200) NOT NULL,
  `restaurant_banner` varchar(200) NOT NULL,
  `restaurant_slug` varchar(255) NOT NULL,
  `restaurant_address` text NOT NULL,
  `restaurant_open_at` time NOT NULL,
  `restaurant_close_at` time NOT NULL,
  `restaurant_establish_date` date NOT NULL,
  `restaurant_status` tinyint(1) NOT NULL DEFAULT '1',
  `restaurant_condition` tinyint(1) NOT NULL DEFAULT '1',
  `restaurant_created_at` datetime NOT NULL,
  `restaurant_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `restaurant_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurant_id`, `restaurant_moto`, `restaurant_name`, `restaurant_contact_number`, `restaurant_email`, `restaurant_logo`, `restaurant_banner`, `restaurant_slug`, `restaurant_address`, `restaurant_open_at`, `restaurant_close_at`, `restaurant_establish_date`, `restaurant_status`, `restaurant_condition`, `restaurant_created_at`, `restaurant_modified_at`, `restaurant_creator`) VALUES
(1, '', 'Panshi Inn', '', '', 'logo-1581187191.jpg', 'banner-1581187106.jpg', 'panshi-inn', 'W Tower, North Jail Rd, Sylhet 3100', '01:00:00', '01:00:00', '2000-01-01', 1, 1, '2020-02-06 21:11:36', '2020-01-22 11:52:37', 2),
(2, '', 'Food Bite Corner', '', '', 'logo-1581184268.jpg', '', 'food-bite-corner', 'Noyashorok, In front of Khajanchibari School and College, Sylhet, 3100 Sylhet', '01:00:00', '01:00:00', '2005-01-01', 1, 1, '2020-01-23 18:14:08', '2020-01-22 17:11:47', 2),
(3, '', 'Demo Restaurant', '', '', 'logo-1581184226.jpg', 'banner-1581182040.jpg', 'demo-restaurant', 'Sylhet', '01:00:00', '01:00:00', '2006-02-08', 1, 1, '2020-02-08 18:14:00', '2020-02-08 17:14:00', 2),
(4, '', 'Demo Restaurant Two', '', '', 'logo-1581184251.jpg', 'banner-1581184251.jpg', 'demo-restaurant-two', 'Sylhet', '01:00:00', '01:00:00', '1998-02-08', 1, 1, '2020-02-08 18:43:08', '2020-02-08 17:21:51', 2),
(5, '', 'Restaurant Demo Three', '', '', 'logo-1581183717.jpg', 'banner-1581183716.jpg', 'restaurant-demo-three', 'Sylhet', '08:30:00', '20:00:00', '2005-02-02', 1, 1, '2020-02-08 18:41:57', '2020-02-08 17:28:38', 2),
(6, '', 'Demo Restaurant ', '', '', 'logo-1581340083.jpg', 'banner-1581340083.jpeg', 'demo-restaurant', 'Sylhet', '05:30:00', '15:30:00', '1996-02-10', 1, 1, '2020-02-10 14:08:03', '2020-02-10 13:08:03', 2);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_tags`
--

CREATE TABLE `restaurant_tags` (
  `resturant_tag_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `restaurant_tag_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_tags`
--

INSERT INTO `restaurant_tags` (`resturant_tag_id`, `restaurant_id`, `tag_id`, `restaurant_tag_created_at`) VALUES
(67, 5, 1, '2020-02-08 17:47:15'),
(68, 5, 6, '2020-02-08 17:47:15'),
(69, 5, 11, '2020-02-08 17:47:15'),
(76, 3, 6, '2020-02-08 17:50:26'),
(77, 3, 7, '2020-02-08 17:50:26'),
(78, 3, 11, '2020-02-08 17:50:26'),
(79, 4, 11, '2020-02-08 17:50:51'),
(80, 4, 7, '2020-02-08 17:50:51'),
(81, 4, 1, '2020-02-08 17:50:51'),
(82, 2, 6, '2020-02-08 17:51:08'),
(83, 2, 7, '2020-02-08 17:51:08'),
(84, 2, 9, '2020-02-08 17:51:08'),
(85, 2, 11, '2020-02-08 17:51:08'),
(86, 2, 8, '2020-02-08 17:51:08'),
(87, 2, 10, '2020-02-08 17:51:08'),
(94, 1, 6, '2020-02-08 18:39:51'),
(95, 1, 11, '2020-02-08 18:39:51'),
(96, 6, 6, '2020-02-10 13:08:03'),
(97, 6, 11, '2020-02-10 13:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(50) NOT NULL,
  `tag_slug` varchar(30) NOT NULL,
  `visit_count` int(11) NOT NULL,
  `tag_status` tinyint(1) NOT NULL DEFAULT '1',
  `tag_created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tag_name`, `tag_slug`, `visit_count`, `tag_status`, `tag_created_at`) VALUES
(1, 'family package', 'family-package', 2, 1, '2020-01-08'),
(6, 'Indian food', 'Indian-food', 0, 1, '2020-01-09'),
(7, 'thai food', 'thai-food', 0, 1, '2020-01-09'),
(8, 'biriyani', 'biriyani', 0, 1, '2020-01-09'),
(9, 'chicken tanduri', 'chicken-tanduri', 0, 1, '2020-01-09'),
(10, 'chicken korai', 'chicken-korai', 0, 1, '2020-01-09'),
(11, 'bangladeshi food', 'bangladeshi-food', 0, 1, '2020-01-09'),
(12, 'demo project', 'demo-project', 0, 1, '2020-01-12'),
(13, 'demo tag', 'demo-tag', 0, 1, '2020-01-12'),
(14, 'demo resturant', 'demo-resturant', 0, 0, '2020-01-12');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_id` int(11) NOT NULL,
  `template_name` varchar(200) NOT NULL,
  `template_body` varchar(100) NOT NULL,
  `template_status` tinyint(1) NOT NULL DEFAULT '1',
  `template_created_at` datetime NOT NULL,
  `template_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `template_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`template_id`, `template_name`, `template_body`, `template_status`, `template_created_at`, `template_modified_at`, `template_creator`) VALUES
(1, 'Small', 'black-template.jpg', 1, '2020-01-26 00:54:27', '2020-01-25 18:57:52', 1),
(2, 'Medium', '', 1, '2020-02-06 00:00:00', '2020-02-06 16:06:56', 1),
(3, 'Large', '', 1, '2020-02-06 00:00:00', '2020-02-06 16:10:07', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `contact_number` varchar(15) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `avatar` varchar(255) COLLATE utf8_bin NOT NULL,
  `avatar_thumb` varchar(50) COLLATE utf8_bin NOT NULL,
  `banner` varchar(255) COLLATE utf8_bin NOT NULL,
  `banner_thumb` varchar(50) COLLATE utf8_bin NOT NULL,
  `address` text COLLATE utf8_bin NOT NULL,
  `city` varchar(255) COLLATE utf8_bin NOT NULL,
  `country` varchar(255) COLLATE utf8_bin NOT NULL,
  `postal_code` varchar(255) COLLATE utf8_bin NOT NULL,
  `dob` date NOT NULL,
  `user_type` int(11) NOT NULL DEFAULT '3',
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `contact_number`, `email`, `avatar`, `avatar_thumb`, `banner`, `banner_thumb`, `address`, `city`, `country`, `postal_code`, `dob`, `user_type`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 'Akash Das', 'akashdas', '$2a$08$pzDvuKaQeLJJMH2cWF0Nou0ueVubDu1zAtxxin9jq/aab9/SQCzD2', '01761152186', 'demo@gmail.com', 'avatar-1581443757.jpg', 'avatar-1581443757_thumb.jpg', 'banner-1581443708.jpg', 'banner-1581443708_thumb.jpg', '', 'Sylhet', 'Bangladesh', '3030', '0000-00-00', 1, 1, 0, NULL, NULL, NULL, NULL, 'c208904663eb12f644d9fe22d5846325', '::1', '2020-02-11 19:04:52', '2020-01-06 14:56:59', '2020-02-11 18:04:52'),
(2, 'Demo Admin', 'nihardas', '$2a$08$8pwZSBkHI2mAWVI3u3LJPu35J8jw8l8N9n29IIOyi2N4BlHdeiDOy', '01623021319', 'akashdasmu@gmail.com', '', '', '', '', '', 'Sylhet', 'Bangladesh', '3030', '1994-08-18', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2020-02-11 17:57:11', '2020-01-20 00:00:00', '2020-02-11 16:57:11'),
(3, 'rgreg', 'prokashdas', '$2a$08$iVUTxc0mlIDPQ73aqYkvU.zRTuM.lxHoF2ICzq2Jkf5Zy7REXeAvS', '0192733654', '', '', '', '', '', '', '', '', '', '2001-01-01', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '2020-02-10 00:00:00', '2020-02-11 06:09:48'),
(4, 'demo admin', 'demodemo', '$2a$08$p829U2WNPEu.5g01HixmzO7AaUsDDzTR/cfdMcoAGv8fXva69WnYa', '01463747764', 'demo@demo,com', '', '', '', '', 'Sylhet', 'Sunamgong', 'Bangladesh', '3033', '1996-06-20', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '2020-02-11 00:00:00', '2020-02-11 06:09:54'),
(5, 'Demo Admin', 'demodas', '$2a$08$36B6/3KU6rzLuB8BzRCHR.B.5XZ0dwnOju7LF6bOFP9g78uO3eQuy', '0104643438', 'akashdas@gmail.com', '', '', '', '', 'Sylhet', 'Sylhet', 'Bangladesh', '3033', '1994-08-18', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '2020-02-11 00:00:00', '2020-02-11 17:36:24');

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

CREATE TABLE `usertypes` (
  `user_type_id` int(11) NOT NULL,
  `user_type_name` varchar(15) NOT NULL,
  `user_type_status` tinyint(1) NOT NULL DEFAULT '1',
  `user_type_created_at` datetime NOT NULL,
  `user_type_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`user_type_id`, `user_type_name`, `user_type_status`, `user_type_created_at`, `user_type_modified_at`, `user_type_creator`) VALUES
(1, 'Super Admin', 1, '2020-01-19 20:55:41', '2020-01-19 14:58:49', 1),
(2, 'Admin', 1, '2020-01-19 20:55:41', '2020-01-19 14:59:14', 1),
(3, 'User', 1, '2020-01-19 21:02:51', '2020-01-19 15:00:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `restaurant_tags`
--
ALTER TABLE `restaurant_tags`
  ADD PRIMARY KEY (`resturant_tag_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usertypes`
--
ALTER TABLE `usertypes`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `user_autologin`
--
ALTER TABLE `user_autologin`
  ADD PRIMARY KEY (`key_id`,`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `restaurant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `restaurant_tags`
--
ALTER TABLE `restaurant_tags`
  MODIFY `resturant_tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usertypes`
--
ALTER TABLE `usertypes`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
