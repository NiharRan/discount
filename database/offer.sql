-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2020 at 10:24 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_banner` varchar(50) NOT NULL,
  `category_slug` varchar(100) NOT NULL,
  `category_status` tinyint(1) NOT NULL DEFAULT '1',
  `category_doc` datetime NOT NULL,
  `category_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `restaurant_id`, `category_name`, `category_banner`, `category_slug`, `category_status`, `category_doc`, `category_dom`, `category_creator`) VALUES
(1, 1, 'Burgers', '', 'Burgers', 1, '2020-03-20 00:00:00', '2020-03-28 14:12:36', 2),
(2, 1, 'Pasta', '', 'Pasta', 1, '2020-03-20 00:00:00', '2020-03-28 14:12:54', 2),
(3, 1, 'Pizza', '', 'Pizza', 1, '2020-03-21 00:00:00', '2020-03-28 14:13:07', 2),
(4, 1, 'Sushi', '', 'Sushi', 1, '2020-03-28 20:13:24', '2020-03-28 19:13:24', 2),
(5, 1, 'Desserts', '', 'Desserts', 1, '2020-03-28 20:13:38', '2020-03-28 19:13:38', 2),
(6, 1, 'Drinks', '', 'Drinks', 1, '2020-03-28 20:13:51', '2020-03-28 19:13:51', 2),
(7, 4, 'Biriyani', '', 'Biriyani', 1, '2020-03-29 18:33:07', '2020-03-29 12:55:19', 2),
(8, 2, 'Misti', '', 'Misti', 1, '2020-03-29 18:33:14', '2020-03-29 16:33:14', 2);

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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_surname` varchar(20) NOT NULL,
  `customer_street_no` varchar(50) NOT NULL,
  `customer_city` varchar(20) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_status` tinyint(1) NOT NULL DEFAULT '1',
  `customer_doc` datetime NOT NULL,
  `customer_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_surname`, `customer_street_no`, `customer_city`, `customer_phone`, `customer_email`, `customer_status`, `customer_doc`, `customer_dom`) VALUES
(1, 'Demo Das', 'demodas', 'abc-len', 'Uhan', '0192873635', 'demo@hdud.com', 1, '0000-00-00 00:00:00', '2020-03-30 19:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `food_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `food_lowest_price` float NOT NULL,
  `food_banner` varchar(255) DEFAULT NULL,
  `food_modal_banner` varchar(255) DEFAULT NULL,
  `food_status` tinyint(1) NOT NULL DEFAULT '1',
  `food_doc` datetime NOT NULL,
  `food_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `food_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`food_id`, `food_name`, `category_id`, `food_lowest_price`, `food_banner`, `food_modal_banner`, `food_status`, `food_doc`, `food_dom`, `food_creator`) VALUES
(1, 'Chicken Burger', 1, 11, NULL, NULL, 1, '2020-03-21 05:40:06', '2020-03-28 14:14:13', 2),
(2, 'Beef Burger', 1, 9, NULL, NULL, 1, '2020-03-28 19:15:54', '2020-03-28 14:14:22', 2),
(3, 'Mouton Burger', 1, 73, 'banner-1585516111.jpg', 'logo-1585516114.jpg', 1, '2020-03-29 23:08:30', '2020-03-29 17:09:47', 2);

-- --------------------------------------------------------

--
-- Table structure for table `food_aditionals`
--

CREATE TABLE `food_aditionals` (
  `food_aditional_id` int(11) NOT NULL,
  `food_aditional_name` varchar(50) NOT NULL,
  `food_aditional_price` float NOT NULL,
  `food_aditional_weight` float NOT NULL,
  `food_aditional_status` tinyint(1) NOT NULL DEFAULT '1',
  `food_aditional_doc` datetime NOT NULL,
  `food_aditional_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `food_aditional_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `food_aditionals`
--

INSERT INTO `food_aditionals` (`food_aditional_id`, `food_aditional_name`, `food_aditional_price`, `food_aditional_weight`, `food_aditional_status`, `food_aditional_doc`, `food_aditional_dom`, `food_aditional_creator`) VALUES
(4, 'Tomato', 1, 2, 1, '2020-03-28 20:06:53', '2020-03-28 14:10:03', 2),
(5, 'Cheese', 1, 3, 1, '2020-03-28 20:08:43', '2020-03-28 19:08:43', 2),
(6, 'Bacon ', 1, 2, 1, '2020-03-28 20:09:04', '2020-03-28 19:09:04', 2),
(7, 'Ham', 1, 2, 1, '2020-03-28 20:09:14', '2020-03-28 19:09:14', 2),
(8, 'Chicken ', 1, 2, 1, '2020-03-28 20:09:35', '2020-03-28 19:09:35', 2);

-- --------------------------------------------------------

--
-- Table structure for table `food_prices`
--

CREATE TABLE `food_prices` (
  `food_price_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `food_size_id` int(11) NOT NULL,
  `food_price` float NOT NULL,
  `food_weight` float NOT NULL,
  `food_price_status` tinyint(1) NOT NULL DEFAULT '1',
  `food_price_doc` datetime NOT NULL,
  `food_price_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `food_price_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `food_prices`
--

INSERT INTO `food_prices` (`food_price_id`, `food_id`, `food_size_id`, `food_price`, `food_weight`, `food_price_status`, `food_price_doc`, `food_price_dom`, `food_price_creator`) VALUES
(1, 1, 1, 9.99, 100, 1, '2020-03-28 13:16:36', '2020-03-28 04:07:04', 2),
(2, 1, 2, 14.99, 200, 1, '2020-03-28 10:10:55', '2020-03-28 09:10:55', 2),
(3, 1, 3, 21.99, 350, 1, '2020-03-28 10:13:20', '2020-03-28 09:13:20', 2),
(4, 2, 1, 9.99, 100, 1, '2020-03-28 20:17:35', '2020-03-28 19:17:35', 2),
(5, 2, 2, 14.99, 200, 1, '2020-03-28 20:17:49', '2020-03-28 19:17:49', 2),
(6, 2, 3, 21.99, 350, 1, '2020-03-28 20:18:08', '2020-03-28 19:18:08', 2),
(7, 2, 3, 21.99, 350, 1, '2020-03-28 20:18:08', '2020-03-28 19:18:08', 2);

-- --------------------------------------------------------

--
-- Table structure for table `food_sizes`
--

CREATE TABLE `food_sizes` (
  `food_size_id` int(11) NOT NULL,
  `food_size_name` varchar(50) NOT NULL,
  `food_size_status` tinyint(1) NOT NULL DEFAULT '1',
  `food_size_doc` datetime NOT NULL,
  `food_size_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `food_size_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `food_sizes`
--

INSERT INTO `food_sizes` (`food_size_id`, `food_size_name`, `food_size_status`, `food_size_doc`, `food_size_dom`, `food_size_creator`) VALUES
(1, 'Small', 1, '2020-03-20 00:00:00', '2020-03-21 13:48:00', 2),
(2, 'Medium', 1, '2020-03-21 00:00:00', '2020-03-21 18:49:00', 2),
(3, 'Large', 1, '2020-03-21 00:00:00', '2020-03-21 18:49:52', 2);

-- --------------------------------------------------------

--
-- Table structure for table `food_tags`
--

CREATE TABLE `food_tags` (
  `food_tag_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `menu_tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `food_tags`
--

INSERT INTO `food_tags` (`food_tag_id`, `food_id`, `menu_tag_id`) VALUES
(11, 1, 1),
(12, 1, 3),
(13, 2, 1),
(14, 2, 3),
(15, 2, 2),
(20, 3, 1),
(21, 3, 3),
(22, 3, 4),
(23, 3, 5);

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

-- --------------------------------------------------------

--
-- Table structure for table `menu_tags`
--

CREATE TABLE `menu_tags` (
  `menu_tag_id` int(11) NOT NULL,
  `menu_tag_name` varchar(50) NOT NULL,
  `menu_tag_slug` varchar(30) NOT NULL,
  `visit_count` int(11) NOT NULL,
  `menu_tag_status` tinyint(1) NOT NULL DEFAULT '1',
  `menu_tag_created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_tags`
--

INSERT INTO `menu_tags` (`menu_tag_id`, `menu_tag_name`, `menu_tag_slug`, `visit_count`, `menu_tag_status`, `menu_tag_created_at`) VALUES
(1, 'cheese', 'cheese', 0, 1, '2020-03-28'),
(2, 'potato', 'potato', 0, 1, '2020-03-28'),
(3, 'onion', 'onion', 0, 1, '2020-03-28'),
(4, 'fries', 'fries', 0, 1, '2020-03-28'),
(5, 'chicken', 'chicken', 0, 1, '2020-03-28'),
(6, 'beef', 'beef', 0, 1, '2020-03-28');

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
(1, 'Damaka Offer', 'damaka-offer', '<h1>Damaka Offer</h1>\n<p>this is a damaka offer</p>', 35, '2020-02-06', '2020-02-28', 1, 3, 'OFFD1581016703', 3, 1, '2020-02-06 20:18:23', '2020-02-06 19:18:23', 2),
(2, 'Gorom Offer', 'gorom-offer', '<h1>Damaka Offer</h1>\n<p>this is a damaka offer</p>', 35, '2020-02-06', '2020-02-28', 2, 3, 'OFFD1581016742', 0, 1, '2020-02-06 20:19:02', '2020-02-06 19:19:02', 2),
(3, 'Damaka Offer', 'damaka-offer', '<h1>Damaka Offer</h1>\n<p>this is a damaka offer</p>', 35, '2020-02-06', '2020-02-28', 3, 3, 'OFFPD1581016762', 4, 1, '2020-02-06 20:19:22', '2020-02-06 19:19:22', 2),
(4, 'Special Offer', 'special-offer', '<h2>Special Offer Alert</h2><p>this offer is very special</p>', 50, '2020-03-01', '2020-03-26', 4, 2, 'OFFPS1581068744', 15, 1, '2020-02-07 10:45:44', '2020-02-07 09:45:44', 2),
(5, 'FataFati Offer', 'fatafati-offer', '<p>Fata-Fati Offer Alert</p><p>order now!!</p>', 75, '2020-04-01', '2020-04-30', 5, 3, 'OFFPF1581069103', 2, 1, '2020-02-07 10:51:43', '2020-02-07 09:51:43', 2),
(6, 'Heart Attack Offer', 'heart-attack-offer', '<p>This is a big offer</p>', 55, '2020-02-03', '2020-02-21', 1, 3, 'OFFFH1581103346', 0, 1, '2020-02-07 20:22:26', '2020-02-07 19:22:26', 2),
(7, 'Valentines day offer', 'valentines-day-offer', '<p>Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;Hello world Hello world&nbsp;</p>', 50, '2020-02-14', '2020-02-28', 2, 1, 'OFFFV1581411316', 79, 1, '2020-02-11 09:55:16', '2020-02-11 08:55:16', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_price` float NOT NULL,
  `order_description` text NOT NULL,
  `order_priority` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `order_status` tinyint(1) NOT NULL DEFAULT '0',
  `order_doc` datetime NOT NULL,
  `order_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `total_price`, `order_description`, `order_priority`, `payment_type`, `restaurant_id`, `order_status`, `order_doc`, `order_dom`) VALUES
(1, 1, 42.98, '', 1, 1, 1, 0, '2020-03-30 21:47:49', '2020-03-30 19:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_foods`
--

CREATE TABLE `order_foods` (
  `order_food_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `food_price_id` int(11) NOT NULL,
  `food_aditional_id` varchar(100) NOT NULL,
  `food_aditional_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_foods`
--

INSERT INTO `order_foods` (`order_food_id`, `order_id`, `food_price_id`, `food_aditional_id`, `food_aditional_price`) VALUES
(1, 1, 2, '4,5,6', 3),
(2, 1, 6, '4,5,7', 3);

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
(1, 2, 'restaurant', 'create', 1, '2020-01-07', '2020-03-20 12:16:38', 1),
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
(17, 1, 'template', 'delete', 1, '2020-02-06', '2020-02-06 11:31:10', 1),
(18, 1, 'feature-restaurant', 'list-view', 1, '2020-03-05', '2020-03-05 15:56:14', 1),
(19, 1, 'permission', 'create', 1, '2020-03-19', '2020-03-18 18:30:10', 1),
(20, 1, 'permission', 'edit', 1, '2020-03-19', '2020-03-18 18:30:10', 1),
(21, 1, 'permission', 'list-view', 1, '2020-03-19', '2020-03-18 18:30:35', 1),
(22, 1, 'permission', 'delete', 1, '2020-03-19', '2020-03-18 18:30:35', 1),
(23, 1, 'role', 'create', 1, '2020-03-19', '2020-03-18 19:15:09', 1),
(24, 1, 'role', 'update', 1, '2020-03-19', '2020-03-18 19:15:09', 1),
(25, 1, 'role', 'delete', 1, '2020-03-19', '2020-03-18 19:15:26', 1),
(26, 1, 'role', 'list-view', 1, '2020-03-19', '2020-03-18 19:15:26', 1),
(27, 2, 'menu-category', 'create', 1, '2020-03-20', '2020-03-20 12:17:06', 1),
(28, 2, 'menu-category', 'edit', 1, '2020-03-20', '2020-03-20 12:17:33', 1),
(29, 2, 'menu-category', 'delete', 1, '2020-03-20', '2020-03-20 12:17:42', 1),
(30, 2, 'menu-category', 'list-view', 1, '2020-03-20', '2020-03-20 12:17:53', 1),
(31, 2, 'menu-food', 'create', 1, '2020-03-20', '2020-03-20 12:19:07', 1),
(32, 2, 'menu-food', 'edit', 1, '2020-03-20', '2020-03-20 12:19:15', 1),
(33, 2, 'menu-food', 'delete', 1, '2020-03-20', '2020-03-20 12:19:24', 1),
(34, 2, 'menu-food', 'list-view', 1, '2020-03-20', '2020-03-20 12:19:34', 1),
(35, 2, 'menu-food-price', 'create', 1, '2020-03-20', '2020-03-20 12:29:32', 1),
(36, 2, 'menu-food-price', 'edit', 1, '2020-03-20', '2020-03-20 12:29:42', 1),
(37, 2, 'menu-food-price', 'delete', 1, '2020-03-20', '2020-03-20 12:21:03', 1),
(38, 2, 'menu-food-price', 'list-view', 1, '2020-03-20', '2020-03-20 12:21:25', 1),
(39, 2, 'menu-food-size', 'create', 1, '2020-03-20', '2020-03-20 12:21:48', 1),
(40, 2, 'menu-food-size', 'edit', 1, '2020-03-20', '2020-03-20 12:22:02', 1),
(41, 2, 'menu-food-size', 'delete', 1, '2020-03-20', '2020-03-20 12:22:25', 1),
(42, 2, 'menu-food-size', 'list-view', 1, '2020-03-20', '2020-03-20 12:22:40', 1),
(43, 2, 'menu-tag', 'create', 1, '2020-03-28', '2020-03-28 10:29:20', 1),
(44, 2, 'menu-tag', 'edit', 1, '2020-03-28', '2020-03-28 10:29:20', 1),
(45, 2, 'menu-tag', 'delete', 1, '2020-03-28', '2020-03-28 10:29:20', 1),
(46, 2, 'menu-tag', 'list-view', 1, '2020-03-28', '2020-03-28 10:29:20', 1),
(47, 2, 'menu-food-aditional', 'create', 1, '2020-03-28', '2020-03-28 18:50:55', 1),
(48, 2, 'menu-food-aditional', 'edit', 1, '2020-03-28', '2020-03-28 18:50:55', 1),
(49, 2, 'menu-food-aditional', 'delete', 1, '2020-03-28', '2020-03-28 18:50:55', 1),
(50, 2, 'menu-food-aditional', 'list-view', 1, '2020-03-28', '2020-03-28 18:50:55', 1),
(51, 2, 'order', 'create', 1, '2020-03-30', '2020-03-30 17:32:47', 1),
(52, 2, 'order', 'edit', 1, '2020-03-30', '2020-03-30 17:32:47', 1),
(53, 2, 'order', 'delete', 1, '2020-03-30', '2020-03-30 17:32:48', 1),
(54, 2, 'order', 'list-view', 1, '2020-03-30', '2020-03-30 17:32:48', 1);

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
  `feature_restaurant` tinyint(1) NOT NULL DEFAULT '0',
  `restaurant_created_at` datetime NOT NULL,
  `restaurant_modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `restaurant_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurant_id`, `restaurant_moto`, `restaurant_name`, `restaurant_contact_number`, `restaurant_email`, `restaurant_logo`, `restaurant_banner`, `restaurant_slug`, `restaurant_address`, `restaurant_open_at`, `restaurant_close_at`, `restaurant_establish_date`, `restaurant_status`, `restaurant_condition`, `feature_restaurant`, `restaurant_created_at`, `restaurant_modified_at`, `restaurant_creator`) VALUES
(1, '', 'Panshi Inn', '', '', 'logo-1581187191.jpg', 'banner-1581187106.jpg', 'panshi-inn', 'W Tower, North Jail Rd, Sylhet 3100', '01:00:00', '01:00:00', '2000-01-01', 1, 1, 1, '2020-02-06 21:11:36', '2020-01-22 11:52:37', 2),
(2, '', 'Food Bite Corner', '', '', 'logo-1581184268.jpg', '', 'food-bite-corner', 'Noyashorok, In front of Khajanchibari School and College, Sylhet, 3100 Sylhet', '01:00:00', '01:00:00', '2005-01-01', 1, 1, 1, '2020-01-23 18:14:08', '2020-01-22 17:11:47', 2),
(3, '', 'Demo Restaurant', '', '', 'logo-1581184226.jpg', 'banner-1581182040.jpg', 'demo-restaurant', 'Sylhet', '01:00:00', '01:00:00', '2006-02-08', 1, 1, 1, '2020-02-08 18:14:00', '2020-02-08 17:14:00', 2),
(4, '', 'Demo Restaurant Two', '', '', 'logo-1581184251.jpg', 'banner-1581184251.jpg', 'demo-restaurant-two', 'Sylhet', '01:00:00', '01:00:00', '1998-02-08', 1, 1, 0, '2020-02-08 18:43:08', '2020-02-08 17:21:51', 2),
(5, '', 'Restaurant Demo Three', '', '', 'logo-1581183717.jpg', 'banner-1581183716.jpg', 'restaurant-demo-three', 'Sylhet', '08:30:00', '20:00:00', '2005-02-02', 1, 1, 1, '2020-02-08 18:41:57', '2020-02-08 17:28:38', 2),
(6, '', 'Demo Restaurant ', '', '', 'logo-1581340083.jpg', 'banner-1581340083.jpeg', 'demo-restaurant', 'Sylhet', '05:30:00', '15:30:00', '1996-02-10', 1, 1, 1, '2020-02-10 14:08:03', '2020-02-10 13:08:03', 2);

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
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(15) NOT NULL,
  `role_status` tinyint(1) NOT NULL DEFAULT '1',
  `role_doc` datetime NOT NULL,
  `role_dom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_status`, `role_doc`, `role_dom`, `role_creator`) VALUES
(1, 'Super Admin', 1, '2020-01-19 20:55:41', '2020-01-19 14:58:49', 1),
(2, 'Admin', 1, '2020-01-19 20:55:41', '2020-01-19 14:59:14', 1),
(3, 'User', 1, '2020-01-19 21:02:51', '2020-01-19 15:00:02', 1);

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
(1, 'family package', 'family-package', 10, 1, '2020-01-08'),
(6, 'Indian food', 'Indian-food', 8, 1, '2020-01-09'),
(7, 'thai food', 'thai-food', 2, 1, '2020-01-09'),
(8, 'biriyani', 'biriyani', 0, 1, '2020-01-09'),
(9, 'chicken tanduri', 'chicken-tanduri', 6, 1, '2020-01-09'),
(10, 'chicken korai', 'chicken-korai', 4, 1, '2020-01-09'),
(11, 'bangladeshi food', 'bangladeshi-food', 19, 1, '2020-01-09'),
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
  `role` int(11) NOT NULL DEFAULT '3',
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

INSERT INTO `users` (`id`, `name`, `username`, `password`, `contact_number`, `email`, `avatar`, `avatar_thumb`, `banner`, `banner_thumb`, `address`, `city`, `country`, `postal_code`, `dob`, `role`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 'Akash Das', 'akashdas', '$2a$08$pzDvuKaQeLJJMH2cWF0Nou0ueVubDu1zAtxxin9jq/aab9/SQCzD2', '01761152186', 'demo@gmail.com', 'avatar-1581443757.jpg', 'avatar-1581443757_thumb.jpg', 'banner-1581443708.jpg', 'banner-1581443708_thumb.jpg', '', 'Sylhet', 'Bangladesh', '3030', '0000-00-00', 1, 1, 0, NULL, NULL, NULL, NULL, 'c208904663eb12f644d9fe22d5846325', '::1', '2020-03-30 19:32:23', '2020-01-06 14:56:59', '2020-03-30 17:32:23'),
(2, 'Demo Supervisor', 'nihardas', '$2a$08$8pwZSBkHI2mAWVI3u3LJPu35J8jw8l8N9n29IIOyi2N4BlHdeiDOy', '01623021319', 'akashdasmu@gmail.com', 'avatar-1582649401.jpg', 'avatar-1582649401_thumb.jpg', 'banner-1582648377.jpg', 'banner-1582648377_thumb.jpg', 'Sylhet', 'Sylhet', 'Bangladesh', '3030', '1998-07-25', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2020-03-30 19:33:10', '2020-01-20 00:00:00', '2020-03-30 17:33:10'),
(3, 'rgreg', 'prokashdas', '$2a$08$iVUTxc0mlIDPQ73aqYkvU.zRTuM.lxHoF2ICzq2Jkf5Zy7REXeAvS', '0192733654', '', '', '', '', '', '', '', '', '', '2001-01-01', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '2020-02-10 00:00:00', '2020-02-11 06:09:48'),
(4, 'demo admin', 'demodemo', '$2a$08$p829U2WNPEu.5g01HixmzO7AaUsDDzTR/cfdMcoAGv8fXva69WnYa', '01463747764', 'demo@demo,com', '', '', '', '', 'Sylhet', 'Sunamgong', 'Bangladesh', '3033', '1996-06-20', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2020-03-19 19:11:17', '2020-02-11 00:00:00', '2020-03-19 18:11:17'),
(5, 'Demo Admin', 'demodas', '$2a$08$36B6/3KU6rzLuB8BzRCHR.B.5XZ0dwnOju7LF6bOFP9g78uO3eQuy', '0104643438', 'akashdas@gmail.com', '', '', '', '', 'Sylhet', 'Sylhet', 'Bangladesh', '3033', '1994-08-18', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2020-03-19 19:11:34', '2020-02-11 00:00:00', '2020-03-19 18:11:34');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `food_aditionals`
--
ALTER TABLE `food_aditionals`
  ADD PRIMARY KEY (`food_aditional_id`);

--
-- Indexes for table `food_prices`
--
ALTER TABLE `food_prices`
  ADD PRIMARY KEY (`food_price_id`);

--
-- Indexes for table `food_sizes`
--
ALTER TABLE `food_sizes`
  ADD PRIMARY KEY (`food_size_id`);

--
-- Indexes for table `food_tags`
--
ALTER TABLE `food_tags`
  ADD PRIMARY KEY (`food_tag_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_tags`
--
ALTER TABLE `menu_tags`
  ADD PRIMARY KEY (`menu_tag_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_foods`
--
ALTER TABLE `order_foods`
  ADD PRIMARY KEY (`order_food_id`);

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
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `food_aditionals`
--
ALTER TABLE `food_aditionals`
  MODIFY `food_aditional_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `food_prices`
--
ALTER TABLE `food_prices`
  MODIFY `food_price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `food_sizes`
--
ALTER TABLE `food_sizes`
  MODIFY `food_size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `food_tags`
--
ALTER TABLE `food_tags`
  MODIFY `food_tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_tags`
--
ALTER TABLE `menu_tags`
  MODIFY `menu_tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_foods`
--
ALTER TABLE `order_foods`
  MODIFY `order_food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

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
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
