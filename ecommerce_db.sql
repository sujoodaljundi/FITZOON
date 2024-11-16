-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2024 at 01:58 PM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(70) DEFAULT NULL,
  `address_line_1` varchar(100) DEFAULT NULL,
  `address_line_2` varchar(100) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `title`, `address_line_1`, `address_line_2`, `country`, `city`, `postal_code`) VALUES
(1, 10, 'amman', 'shafabadran', NULL, 'jordan', 'amman', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`) VALUES
(2, 1),
(1, 6),
(3, 7),
(4, 8),
(5, 15),
(6, 16),
(7, 17);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `product_price` decimal(10,2) NOT NULL,
  `size` varchar(4) NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `product_id`, `quantity`, `product_price`, `size`) VALUES
(118, 4, 21, 2, 37.00, 'S'),
(119, 4, 20, 1, 22.00, 'S'),
(120, 4, 19, 2, 42.00, 'S'),
(124, 3, 17, 2, 30.00, 'S'),
(125, 6, 18, 1, 17.00, 'S'),
(128, 7, 18, 2, 17.00, 'S');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `user_id`, `name`, `contact_email`, `message`) VALUES
(1, 10, 'Mohammad Mahmoud', 'mohammad.mahmoud@gmail.com', 'The this products is very good and the website is fast');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `status` enum('valid','invalid') NOT NULL,
  `expiry_date` date NOT NULL,
  `discount_percentage` int(3) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`id`, `code`, `status`, `expiry_date`, `discount_percentage`, `deleted`) VALUES
(1, 'DISC55', 'valid', '2024-11-28', 55, 0),
(3, 'DISC58', 'invalid', '2024-10-29', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leagues`
--

CREATE TABLE `leagues` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `description` varchar(500) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leagues`
--

INSERT INTO `leagues` (`id`, `name`, `description`, `deleted`) VALUES
(1, 'Premier League', 'Premier Leag', 0),
(2, 'La Liga', '', 0),
(3, 'Bundesliga', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_method` enum('paypal','cash_on_delivery') NOT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `coupon_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `payment_method`, `payment_status`, `order_status`, `coupon_id`, `created_at`) VALUES
(1, 10, 100.20, 'cash_on_delivery', 'pending', 'delivered', 0, '2024-10-26 08:43:56'),
(7, 17, 17.00, 'cash_on_delivery', 'pending', 'pending', 0, '2024-11-01 12:27:33'),
(8, 17, 54.00, 'cash_on_delivery', 'pending', 'pending', 0, '2024-11-01 12:55:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` enum('S','M','L','XL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `size`) VALUES
(3, 7, 18, 1, 'S'),
(4, 8, 4, 3, 'S');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `quantity` int(7) NOT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `league_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `summary`, `quantity`, `cover`, `category_id`, `league_id`, `team_id`, `price`, `deleted`) VALUES
(4, 'T-shirt Real Madrid player courtois 1', 'Celebrate the legacy of Real Madrid with our official t-shirts. Crafted for comfort and style, these shirts feature the iconic crest and are perfect for game days or everyday wear.', NULL, 130, '../admin_dashboard/images/cover.PNG', 0, 2, 2, 40.00, 0),
(17, 'T-shirt Real Madrid player bellingham 5', 'Cheer on Real Madrid in style with our official LA LIGA t-shirts. Made from moisture-wicking fabric, they keep you cool and comfortable whether you\'re at the match or working out.', NULL, 163, 'co_be.PNG', 0, 2, 2, 30.00, 0),
(18, 'T-shirt Real Madrid player Bellingham orange 5', 'Display your passion for Real Madrid with our premium t-shirts. Featuring the famous club crest, these lightweight and breathable shirts are ideal \nfor sports or casual outings.', NULL, 128, '../admin_dashboard/images/cov_or.PNG', 0, 2, 2, 17.00, 0),
(19, 'T-shirt Real Madrid player VINI JR black 7', 'Support your favorite team with our stylish Real Madrid t-shirts. Designed for both performance and comfort, they are perfect for fans who wantto look great while cheering on their team.', NULL, 250, 'coo_b.PNG', 0, 2, 2, 42.00, 0),
(20, 'T-shirt Real Madrid player Mbappe 9', 'Join the ranks of Real Madrid supporters with our official t-shirts. Combining comfort and functionality, these shirts are perfect for every fan, you\'re in the stands or at the gym.', NULL, 64, '../admin_dashboard/images/voc_9.PNG', 0, 2, 2, 22.00, 0),
(21, 'T-shirt barcelona player Messi 10', 'Show your allegiance to FC Barcelona with our stylish La Liga T-Shirt. Crafted from soft, breathable cotton,making it the ideal choice for game day or casual outings. Available in a variety of sizes for the perfect fit.', NULL, 52, '../admin_dashboard/images/me_co.PNG', 0, 2, 8, 37.00, 0),
(22, 'T-shirt barcelona player Lamine yamal 19', 'Elevate your game day look with our La Liga Barcelona T-Shirt. Made from 100% lightweight cotton, this comfortable tee proudly displays the Barcelona crest, \nperfect for fans of all ages.', NULL, 166, '../admin_dashboard/images/la_c19.PNG', 0, 2, 8, 15.00, 0),
(23, 'T-shirt barcelona player Black', 'Celebrate your love for FC Barcelona with our officially licensed La Liga T-Shirt. This premium cotton tee offers a comfortable fit and features the \niconic Barcelona crest front and center.Great for any occasion, it’s a must-have for every devoted fan!', NULL, 153, '../admin_dashboard/images/ck_c.PNG', 0, 2, 8, 24.00, 0),
(24, 'T-shirt barcelona player Blue', 'Join the ranks of passionate Barcelona supporters with our T-Shirt. Designed for comfort with 100% breathable cotton, this tee showcases the famous \nBarcelona crest, making it a versatile addition to your wardrobe .', NULL, 243, '../admin_dashboard/images/ue_co.PNG', 0, 2, 8, 33.00, 0),
(25, 'T-shirt barcelona player Red', 'Represent FC Barcelona in style with ourT-Shirt, made from soft, breathable cotton. This classic tee features the iconic Barcelona crest, making it\n a perfect choice for cheering on your team at the stadium ', NULL, 62, '../admin_dashboard/images/re_c.PNG', 0, 2, 8, 13.00, 0),
(26, 'T-shirt atletico madrid player Griezmann 7 red', 'Express your passion for Atlético Madrid with our  T-Shirt. This comfortable tee, crafted from soft, breathable cotton, features the iconic club crest\nand is perfect for both match days and everyday wear. Available in a range of sizes to suit every fan!', NULL, 116, '../admin_dashboard/images/gr_c.PNG', 0, 2, 9, 43.00, 0),
(27, 'T-shirt atletico madrid player Griezmann 7 green ', 'Show your Atlético Madrid spirit with our fashionable  T-Shirt, made from soft, breathable cotton. This classic tee features the iconic crest, making it perfect for both cheering at the stadium and casual hangouts. Sizes available for everyone!', NULL, 335, '../admin_dashboard/images/zm_c.PNG', 0, 2, 9, 20.00, 0),
(28, 'T-shirt atletico madrid player Blue ', 'Proudly represent Atlético Madrid with our officially licensed T-Shirt. This high-quality cotton tee features the celebrated crest and is designed for\n a comfortable fit, making it a staple for any true fan’s wardrobe!', NULL, 248, '../admin_dashboard/images/pol_c.PNG', 0, 2, 9, 10.00, 0),
(29, 'T-shirt Liverpool player Virgil 4 ', 'Get ready to cheer for Liverpool FC with our t-shirt! Made from soft, this tee showcases the legendary Liver bird logo. Perfect for fans of all ages, it’s designed for comfort whether you’re at the match.\n', NULL, 180, '../admin_dashboard/images/vvr_co.PNG', 0, 1, 10, 45.00, 0),
(30, 'T-shirt Liverpool player Luis diaz 7', 'Support Liverpool FC in comfort and style with our exclusive t-shirt! Crafted from soft,  this shirt highlights the famous Liver bird and \nthe Premier League badge. Ideal for fans who want to stand out .\n', NULL, 130, '../admin_dashboard/images/sl_c.PNG', 0, 1, 10, 34.00, 0),
(31, 'T-shirt Liverpool player Mac Allister 10 red ', 'Unleash your inner Red with our Liverpool FC T-Shirt! ensuring you stay cool and stylish. Featuring \nthe iconic Liver bird logo, it’s perfect for showcasing your team pride during matches or casual gatherings.', NULL, 120, '../admin_dashboard/images/mc_c.PNG', 0, 1, 10, 28.00, 0),
(32, 'T-shirt Liverpool player Black ', 'Embrace your love for Liverpool FC with our lightweight t-shirt! This classic design, featuring the iconic Liver bird, offers ultimate comfort and style.', NULL, 142, '../admin_dashboard/images/xx_cc.PNG', 0, 1, 10, 18.00, 0),
(33, 'T-shirt Liverpool player Black&Blue', 'Wear your passion for Liverpool FC with pride in our stylish t-shirt. Made from high-quality cotton, this tee features a bold Liver bird design, making it a standout choice for any supporter. ', NULL, 165, '../admin_dashboard/images/bf_c.PNG', 0, 1, 10, 25.00, 0),
(34, 'T-shirt Manchester city player De Bruyne 17 black&yellow', 'Show your glory for Manchester City with our premium Premier League T-Shirt! Crafted from soft, ensuring you stand out as a true supporter. Perfect fo', NULL, 110, '../admin_dashboard/images/qx_c.PNG', 0, 1, 11, 18.00, 0),
(35, 'T-shirt Manchester city player De Bruyne 17 Blue', 'Support Manchester City in style with our lightweight Premier League T-Shirt! Made from 100% cotton, this comfortable tee showcases the  famous club c', NULL, 165, '../admin_dashboard/images/de_c17.PNG', 0, 1, 11, 38.00, 0),
(36, 'T-shirt Manchester city player Haaland 9 Blue', 'Celebrate your loyalty to Manchester City with our officially licensed Premier League T-Shirt. This high-quality cotton tee provides a \nrelaxed and prominently displays the legendary club crest. ', NULL, 155, '../admin_dashboard/images/ha_cc.PNG', 0, 1, 11, 17.00, 0),
(37, 'T-shirt Manchester city player Ederson M. orange 31', 'Join the Manchester City fan base with our stylish Premier League T-Shirt. Designed for ultimate comfort, this cotton tee features\n the iconic club crest, making it perfect for cheering on your team .', NULL, 80, '../admin_dashboard/images/er_c.PNG', 0, 1, 11, 27.00, 0),
(38, 'T-shirt Manchester city player Foden 47 perple', 'Represent Manchester City proudly with our classic Premier League T-Shirt! This  displays the club crest, perfect for showing off your team spirit at the \nstadium.', NULL, 90, '../admin_dashboard/images/fd_c.PNG', 0, 1, 11, 19.00, 0),
(39, 'T-shirt Manchester United player Garnacho 17 red ', 'Wear the  for Manchester United with our premium Premier League T-Shirt! Crafted from soft cotton, this tee features the iconic club\n crest prominently on the chest. Ideal for match days  it’s available in various sizes for every fan.', NULL, 180, '../admin_dashboard/images/tx_c.PNG', 0, 1, 1, 30.00, 0),
(40, 'T-shirt Manchester United player Black', 'Support Manchester United in style with our lightweight Premier League T-Shirt! Made from  cotton, this comfortable tee showcases the legendary club crest, making it perfect for fans of all ages. ', NULL, 33, '../admin_dashboard/images/ry_c.PNG', 0, 1, 1, 15.00, 0),
(41, 'T-shirt Manchester United player black&red', 'Celebrate your loyalty to Manchester United with our officially licensed Premier League T-Shirt. This offers a relaxed  and  prominently displays the iconic club crest. Essential for every dedicated fan!', NULL, 266, '../admin_dashboard/images/lbb_c.PNG', 0, 1, 1, 12.00, 0),
(42, 'T-shirt Manchester United player Blue ', 'Join the Manchester United fan community with our stylish Premier League T-Shirt. Designed for ultimate comfort, this  cotton tee features the classic club crest, perfect for cheering on your team , Available in multiple sizes!', NULL, 80, '../admin_dashboard/images/kk_cc.PNG', 0, 1, 1, 18.00, 0),
(43, 'T-shirt Manchester United player Red', 'Represent Manchester United proudly with our red Premier League T-Shirt! This soft,  cotton tee displays the club crest, ideal for showcasing your team spirit at the stadium Available in a variety of sizes for all fans!', NULL, 120, '../admin_dashboard/images/jk_cc.PNG', 0, 1, 1, 25.00, 0),
(44, 'T-shirt Bayern Munich player Kane 9 black', 'Cheer on Bayern Munich with our Sport T-Shirt! Crafted from fabric, this tee showcases the iconic club crest . Perfect for match\n days , it’s a must-have for any true fan!', NULL, 163, '../admin_dashboard/images/ip_cc.PNG', 0, 3, 3, 46.00, 0),
(45, 'T-shirt Bayern Munich player MÜller 25 red ', 'Support Bayern Munich in style with our comfortable Sport T-Shirt! Made from high-quality, breathable material, it features classic club crest, ideal for workouts.', NULL, 110, '../admin_dashboard/images/MÜ_c.PNG', 0, 3, 3, 41.00, 0),
(46, 'T-shirt Bayern Munich player black', 'Show your loyalty to Bayern Munich with our stylish Sport T-Shirt! This tee highlights the famous club crest and is perfect for cheering on your team or for everyday wear', NULL, 40, '../admin_dashboard/images/bm_c.PNG', 0, 3, 3, 22.00, 0),
(47, 'T-shirt Bayern Munich player navy blue', 'Represent Bayern Munich proudly in our Sport T-Shirt! Featuring the iconic crest and crafted from  fabric, this tee is great for both workouts and casual wear. Ideal for every fan!', NULL, 30, '../admin_dashboard/images/fg_c.PNG', 0, 3, 3, 18.00, 0),
(48, 'T-shirt Bayern Munich player RED', 'Join the ranks of Bayern Munich supporters with our Sport T-Shirt! Made from soft, breathable material, this shirt displays the club crest , making it perfect for match days .', NULL, 250, '../admin_dashboard/images/vbc_c.PNG', 0, 3, 3, 38.00, 0),
(49, 'T-shirt Dortmund player BLACK', 'Demonstrate your allegiance to Dortmund with this trendy fan t-shirt.  it’s perfect for game days . Designed for ultimate comfort, this shirt is essential for any devoted supporter!', NULL, 180, '../admin_dashboard/images/dor_c.PNG', 0, 3, 6, 19.00, 0),
(50, 'T-shirt Dortmund player Yellow', 'Celebrate your love for Dortmund with this timeless sport t-shirt. Made from premium,  fabric, , making it agreat choice for any fan. Whether at a match or hanging out, this tee is perfect for showing your team spirit.', NULL, 230, '../admin_dashboard/images/yel_cc.PNG', 0, 3, 6, 26.00, 0),
(51, 'T-shirt Dortmund player white', 'Unleash your potential with the Dortmund performance t-shirt! Featuring advanced moisture-wicking technology, this tee keeps you dry and comfortable during workouts or while supporting your team at the stadium.', NULL, 36, '../admin_dashboard/images/wh_c.PNG', 0, 3, 6, 20.00, 0),
(52, 'T-shirt Dortmund player YELLOW', 'Stay fit and fashionable with the Dortmund training t-shirt! Constructed from lightweight materials, this shirt keeps you comfortable while proudly displaying your team glory. Perfect for the gym or everyday adventures.', NULL, 247, '../admin_dashboard/images/tpp_c.PNG', 0, 3, 6, 34.00, 0),
(53, 'T-shirt Leverkusen player BLUE', 'Elevate your game day style with our Leverkusen t-shirt! Made from premium, breathable cotton, this tee features the legendary club crest. Perfect for the stadium , it combines comfort and flair. Available in various sizes and colors.', NULL, 190, '../admin_dashboard/images/qx_cc.PNG', 0, 3, 7, 38.00, 0),
(54, 'T-shirt Leverkusen player RED', 'Join the  Leverkusen fanbase with our essential Bundesliga t-shirt! Crafted from soft, high-quality cotton, it’s ideal for cheering from the stands . ', NULL, 278, '../admin_dashboard/images/bar_cc.PNG', 0, 3, 7, 42.00, 0),
(55, 'T-shirt Leverkusen player white', 'Get ready for the season with our exclusive  Leverkusen t-shirt! Crafted from durable, breathable cotton, this stylish tee showcases the renowned club', NULL, 240, '../admin_dashboard/images/ll_cc.PNG', 0, 3, 7, 33.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` enum('S','M','L','XL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`) VALUES
(1, 6, '../admin_dashboard/images/pic 1-1.PNG'),
(2, 6, '../admin_dashboard/images/pic 2-1.PNG'),
(3, 7, '../admin_dashboard/images/pic 1-1.PNG'),
(4, 7, '../admin_dashboard/images/pic 2-1.PNG'),
(5, 8, '../admin_dashboard/images/pic 5-1.PNG'),
(6, 8, '../admin_dashboard/images/pic 5-2.PNG'),
(7, 9, '../admin_dashboard/images/5-1.PNG'),
(8, 9, '../admin_dashboard/images/5-2.PNG'),
(9, 10, '../admin_dashboard/images/pic 7-1.PNG'),
(10, 10, '../admin_dashboard/images/pic 7-2.PNG'),
(11, 11, '../admin_dashboard/images/pic 9-1.PNG'),
(12, 11, '../admin_dashboard/images/pic 9-2.PNG'),
(13, 12, '../admin_dashboard/images/pic 10-1.PNG'),
(14, 12, '../admin_dashboard/images/pic 10-2.PNG'),
(15, 13, '../admin_dashboard/images/pic 19-1.PNG'),
(16, 13, '../admin_dashboard/images/pic 19-2.PNG'),
(17, 14, '../admin_dashboard/images/pic1.PNG'),
(18, 14, '../admin_dashboard/images/pic2.PNG'),
(19, 15, '../admin_dashboard/images/pic1.PNG'),
(20, 16, '../admin_dashboard/images/picb_1.PNG'),
(21, 16, '../admin_dashboard/images/picb_2.PNG'),
(22, 17, '../admin_dashboard/images/co1.PNG'),
(23, 17, '../admin_dashboard/images/co2.PNG'),
(24, 18, '../admin_dashboard/images/or_1.PNG'),
(25, 18, '../admin_dashboard/images/or_2.PNG'),
(26, 19, '../admin_dashboard/images/ov_b1.PNG'),
(27, 19, '../admin_dashboard/images/ov_b2.PNG'),
(28, 20, '../admin_dashboard/images/v_1.PNG'),
(29, 20, '../admin_dashboard/images/v_2.PNG'),
(30, 21, '../admin_dashboard/images/me_1.PNG'),
(31, 21, '../admin_dashboard/images/me_2.PNG'),
(32, 22, '../admin_dashboard/images/la_1.PNG'),
(33, 22, '../admin_dashboard/images/la_2.PNG'),
(34, 23, '../admin_dashboard/images/n_1.PNG'),
(35, 23, '../admin_dashboard/images/n_2.PNG'),
(36, 24, '../admin_dashboard/images/cb_1.PNG'),
(37, 25, '../admin_dashboard/images/r_1.PNG'),
(38, 26, '../admin_dashboard/images/g_1.PNG'),
(39, 26, '../admin_dashboard/images/g_2.PNG'),
(40, 27, '../admin_dashboard/images/z_1.PNG'),
(41, 27, '../admin_dashboard/images/z_2.PNG'),
(42, 28, '../admin_dashboard/images/po_11.PNG'),
(43, 29, '../admin_dashboard/images/wq_1.PNG'),
(44, 29, '../admin_dashboard/images/wq_2.PNG'),
(45, 30, '../admin_dashboard/images/si_1.PNG'),
(46, 30, '../admin_dashboard/images/si_2.PNG'),
(47, 31, '../admin_dashboard/images/mj_1.PNG'),
(48, 31, '../admin_dashboard/images/mj_2.PNG'),
(49, 32, '../admin_dashboard/images/xx_c.PNG'),
(50, 33, '../admin_dashboard/images/bh_1.PNG'),
(51, 33, '../admin_dashboard/images/bh_2.PNG'),
(52, 34, '../admin_dashboard/images/qa_1.PNG'),
(53, 34, '../admin_dashboard/images/qa_2.PNG'),
(54, 35, '../admin_dashboard/images/de_1.PNG'),
(55, 35, '../admin_dashboard/images/de_2.PNG'),
(56, 36, '../admin_dashboard/images/ha_1.PNG'),
(57, 36, '../admin_dashboard/images/ha_2.PNG'),
(58, 37, '../admin_dashboard/images/jd_1.PNG'),
(59, 37, '../admin_dashboard/images/jd_2.PNG'),
(60, 38, '../admin_dashboard/images/fa_1.PNG'),
(61, 38, '../admin_dashboard/images/fa_2.PNG'),
(62, 39, '../admin_dashboard/images/tt_1.PNG'),
(63, 39, '../admin_dashboard/images/tt_2.PNG'),
(64, 40, '../admin_dashboard/images/rr_1.PNG'),
(65, 41, '../admin_dashboard/images/bb_11.PNG'),
(66, 42, '../admin_dashboard/images/kk_1.PNG'),
(67, 43, '../admin_dashboard/images/jk_1.PNG'),
(68, 43, '../admin_dashboard/images/jk_2.PNG'),
(69, 44, '../admin_dashboard/images/iop_1.PNG'),
(70, 44, '../admin_dashboard/images/iop_2.PNG'),
(71, 45, '../admin_dashboard/images/sÜ_1.PNG'),
(72, 45, '../admin_dashboard/images/sÜ_2.PNG'),
(73, 46, '../admin_dashboard/images/bm_1.PNG'),
(74, 47, '../admin_dashboard/images/fg_11.PNG'),
(75, 48, '../admin_dashboard/images/vbb_11.PNG'),
(76, 49, '../admin_dashboard/images/dop_1.PNG'),
(77, 50, '../admin_dashboard/images/yeo_1.PNG'),
(78, 51, '../admin_dashboard/images/wh_11.PNG'),
(79, 52, '../admin_dashboard/images/tp_11.PNG'),
(80, 52, '../admin_dashboard/images/tp_22.PNG'),
(81, 53, '../admin_dashboard/images/qz_11.PNG'),
(82, 54, '../admin_dashboard/images/bar_11.PNG'),
(83, 55, '../admin_dashboard/images/lm_11.PNG');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `description` varchar(500) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `league_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `description`, `deleted`, `league_id`) VALUES
(1, 'Manchester United', 'Manchester United, founded in 1878 as Newton Heath LYR F.C., is a prestigious club in Old Trafford, Greater Manchester, playing at Old Trafford Stadium, known as the \"Theatre of Dreams,\" in red, white, and black.\r\nThe club has a record number of Premier League titles, multiple FA Cups, and three UEFA Champions League victories.', 0, 1),
(2, 'Real Madrid', 'Real Madrid, founded in 1902, is a prestigious football club in Madrid, Spain, playing at the Santiago Bernabéu Stadium. Known as \"Los Blancos\" for their all-white kits, they hold a record number of UEFA Champions League titles and have featured legends like Alfredo Di Stéfano and Cristiano Ronaldo. Their rivalry with FC Barcelona, known as \"El Clásico,\" is a major highlight. Real Madrid remains a dominant force in Spanish and European football.', 0, 2),
(3, 'Bayern Munich', 'Bayern Munich, officially known as FC Bayern München, is one of the most successful and prominent football clubs in the world. Based in Munich, Germany, the club was founded in 1900 and has a rich history marked by numerous domestic and international achievements.', 0, 3),
(6, 'Dortmund', 'Dortmund, founded in 1909, is a professional football club in Dortmund, Germany. Known for its passionate fans and yellow and black colors, the team plays at Signal Iduna Park, famous for the \"Yellow Wall.\" Competing in the Bundesliga, Dortmund has a strong rivalry with Bayern Munich and has won multiple league titles, DFB-Pokal trophies, and the UEFA Champions League in 1997. Renowned for developing young talent, the club is celebrated for its attacking, team-oriented style of play.', 0, 3),
(7, 'Leverkusen', 'Leverkusen, founded in 1904, is a professional football club in Leverkusen, Germany. Competing in the Bundesliga, they play at the BayArena, with a capacity of around 30,000. Known for their red and black colors, the club has a strong attacking tradition and a focus on player development. While nicknamed \"Neverkusen\" for near misses in finals, they have won the DFB-Pokal and regularly qualify for European competitions, solidifying their status in German football.', 0, 3),
(8, 'barcelona', 'FC Barcelona, commonly known as Barça, is a prestigious football club founded in 1899 in Barcelona, Spain. Renowned for its blue and garnet colors, the club has amassed numerous titles, including multiple UEFA Champions League and La Liga championships.\r\nBarça has produced iconic stars, most notably Lionel Messi, and plays its home games at Camp Nou, one of Europe’s largest stadiums.The club\'s rivalry with Real Madrid, known as \"El Clásico,\" is legendary.', 0, 2),
(9, 'atletico madrid', 'Club Atlético de Madrid, founded in 1903, is a professional football club in Madrid, Spain, playing at the Cívitas Metropolitano stadium (68,000 capacity). Known for their red and white colors and emblem featuring a bear and a strawberry tree, they thrive under manager Diego Simeone with a strong defensive style. Atlético has won La Liga, the Copa del Rey, and the UEFA Europa League, and reached the UEFA Champions League final several times. Their main rival is Real Madrid.', 0, 2),
(10, 'Liverpool', 'Liverpool, founded in 1892, is a leading football club in England, renowned for its passionate fans and the anthem \"You\'ll Never Walk Alone.\" The club has a rich history with multiple league titles and UEFA Champions League victories.\r\n\r\nKnown for its attacking style, Liverpool has had legends like Bill Shankly and Steven Gerrard, alongside stars like Mohamed Salah. Intense rivalries with Manchester United and Everton add to its competitive spirit.', 0, 1),
(11, 'Manchester city', 'Manchester City Football Club, founded in 1880 as St. Mark\'s, is based in Manchester, England. The team, recognized for its sky blue colors, plays at the Etihad Stadium, which seats over 53,000.\r\n\r\nAcquired by the Abu Dhabi United Group in 2008, City has enjoyed great success, winning numerous Premier League titles, FA Cups, and League Cups. Under Pep Guardiola since 2016, the club is known for its possession-based play and commitment to youth development.', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(200) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone_number` varchar(14) DEFAULT NULL,
  `role` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `address_line_1` varchar(200) NOT NULL,
  `address_line_2` varchar(200) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `email`, `password`, `phone_number`, `role`, `created_at`, `updated_at`, `deleted`, `address_line_1`, `address_line_2`, `country`, `city`) VALUES
(1, 'Shadi', 'shadi@email.com', '$2y$10$skttGLah1TgJkTEdR0CQo.1czYGvWFCOgnNGbmfECb7D1jbgEkyPC', '0788136963', 1, '2024-10-18 14:48:40', '2024-10-29 12:20:40', 1, 'amman shafabadran', '', 'Jordan', ''),
(2, 'Shadi', 'shadi2@email.com', '$2y$10$NhY73BnpxS5DAjwIj1au2efTQgDu9rgfBSF0YAtJislDoiYn2UxJ2', '0788136963', 2, '2024-10-18 14:51:33', '2024-10-25 17:19:17', 0, 'amman shafabadran', '', 'Jordan', ''),
(3, 'Zaid', 'Zaid@gmail.com', '$2y$10$KMo4x61nA2G8CtNhLGYx0eCeH1BBzUu70FXLQdEP7N1nMpGQZNIvy', NULL, 1, '2024-10-23 13:03:22', '2024-10-24 15:53:36', 0, '', '', '', ''),
(4, 'ahmad', 'ahmad2433@gmail.com', '$2y$10$MMqB.idr9sSIEHUhXMGwtOIW8xwBAfUYasg3Vn2RjhsSIDPGywl3q', NULL, 3, '2024-10-23 13:10:10', '2024-10-24 12:56:46', 0, '', '', '', ''),
(5, 'test', 'test@gmail.com', '$2y$10$X5Rh5Q09vwCi0t.4xiQXCO5nzDk.HVPKFk14XJbza9IVL6vhrJZzW', NULL, 1, '2024-10-24 07:52:50', '2024-10-24 07:52:50', 0, '', '', '', ''),
(6, 'ahmad', 'farrarjeh@gmail.com', '$2y$10$8nO5z/x4GioCK0pUmwqOE.xVaP51KwHI/oZXPayv.KyzLqy5D9q0O', NULL, 2, '2024-10-24 07:59:18', '2024-10-24 12:56:33', 0, '', '', '', ''),
(7, 'test2', 'hello@gmail.com', '$2y$10$WE4ymNbTimgKnTpwpDy3BePyoRiwV0KsHTX7zTY7qLHvg/aQZBLTy', NULL, 2, '2024-10-24 12:55:03', '2024-10-24 20:13:12', 0, '', '', '', ''),
(8, 'ahmad', 'ahmad@shammas.com', '$2y$10$aM3D54ZwTw.CWWOko03BSOYzuJOGOsyM7EVHtczXcmvofnNbBVHzO', NULL, 1, '2024-10-24 12:56:05', '2024-10-25 11:13:30', 0, '', '', '', ''),
(9, 'test3', 'test3@gmail.com', '$2y$10$MYGHIbSCcGGXqNdjZRmDWOh/SogvJeCjIhqmf9MuGjTEZ4t90IT4m', NULL, 1, '2024-10-24 13:11:55', '2024-10-24 16:28:14', 0, '', '', '', ''),
(10, 'mohammad mahmoud', 'moha@gmail.com', '$2y$10$Hd/xVFSWhkXBtcJkFNpIe.YFvhwhm/KLwrEE5MEpnlFi6KVhp24RC', '+962795567803', 1, '2024-10-25 14:32:27', '2024-10-26 11:52:56', 0, 'amman shafabadran', '3 motee mehyar street', 'Jordan', ''),
(12, 'Zaid', 'Zaid2222@gmail.com', '$2y$10$mUywrorw6BGPvWftFU/2MOjVm3OAy3bzvRARYMMe.aLPGsSHzWARC', '+962795567803', 3, '2024-10-25 17:04:29', '2024-10-25 17:04:29', 0, 'amman shafabadran', '3 motee mehyar street', 'Jordan', ''),
(13, 'Ahmad Alazzam', 'Ahmad@gmail.com', '$2y$10$lI2tfu1kygYg0kvrJmH6ju//HnZGMu/uJBe0WSYtSvzlFxy/IY04m', '+962795567803', 1, '2024-10-27 06:51:46', '2024-10-27 06:51:46', 0, 'amman shafabadran', '3 motee mehyar street', 'Jordan', ''),
(14, 'test', 'testt@gmail.com', '$2y$10$7C/IJbvRl712jn9/UU./fOzXe7H2QHxi1bbeqJcplgbX4za30fIoK', '+962795567803', 1, '2024-10-29 09:10:37', '2024-10-29 09:10:37', 0, 'amman shafabadran', '3 motee mehyar street', 'India', ''),
(15, 'shadi', 'shadi11@gmail.com', '$2y$10$IHjiaHPp4HsVJZsnVd60ReScr4tViH8vIEYjK1O4UQS4We/Q4W4pW', '0788136963', 1, '2024-10-31 17:25:59', '2024-10-31 17:25:59', 0, '', NULL, 'Jordan', 'amman'),
(16, 'shadi', 'shadi33@email.com', '$2y$10$UR.bYHiasWr1UgRBe93Doe2h5AjueLZsbwhzUmOPelSH1fSY5pvVu', '0788136963', 1, '2024-11-01 11:15:37', '2024-11-01 11:15:37', 0, '', NULL, 'Jordan', 'Amman'),
(17, 'shadi', 'shadi7@email.com', '$2y$10$9DP9K1wcY7DKdYSEaFcSSeqOoaCqGvCBTD/VAtvDbAHEumMcivzt.', '0788136963', 1, '2024-11-01 12:23:59', '2024-11-01 12:23:59', 0, '', NULL, 'Jordan', 'Amman');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 8, 2, '2024-10-31 12:35:19'),
(2, 16, 18, '2024-11-01 11:36:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk` (`user_id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `leagues`
--
ALTER TABLE `leagues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `league_id` (`league_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `league_id` (`league_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leagues`
--
ALTER TABLE `leagues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

--
-- Constraints for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
