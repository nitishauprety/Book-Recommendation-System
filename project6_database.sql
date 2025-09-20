-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 19, 2025 at 03:25 PM
-- Server version: 11.2.2-MariaDB
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project6`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `password`, `email`) VALUES
(1, 'Admin@123', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `pdflink` varchar(225) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `isbn` varchar(17) NOT NULL,
  `description` varchar(250) NOT NULL,
  `access_type` int(10) NOT NULL,
  `admin_Id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`book_id`),
  KEY `user_idfk` (`admin_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `pdflink`, `title`, `author`, `isbn`, `description`, `access_type`, `admin_Id`, `price`) VALUES
(18, 'ouabh.pdf', 'Once Upon A Broken Heart', 'Stephanie Garber', '9783570167069', 'Desperate to stop her true love wedding, Evangeline strikes a dangerous bargain with the enigmatic Prince of Hearts. Magic, curses, and twisted romance unfold in this captivating tale of love and betrayal. Will she get her happily ever after?', 1, 1, 455.00),
(20, 'twisted love.pdf', 'Twisted Love', 'Ana Huang', '9783736319127', 'Twisted Love by Ana Huang is a gripping romance about Alex Volkov, a brooding billionaire, and Ava Chen, his best friend sister. Their relationship evolves from a forbidden attraction to an passionate love, filled with twists, and emotional turmoil.', 1, 1, 500.00),
(22, 'acotar.pdf', 'A Court Of Thorns And Roses', 'Sarah J. Mass', '9781526605399', 'In A Court of Thorns and Roses, Feyre is thrust into a world of magic, danger, and desire after killing a faerie. Bound to a fae lord, she discovers dark secrets and a love that could change everything.', 1, 1, 600.00),
(23, 'verity.pdf', 'Verity', 'Collen Hoover', '9781538724736', 'Verity by Colleen Hoover is a dark, twisted thriller that will haunt your thoughts. When a writer uncovers shocking secrets hidden in an unfinished manuscript, reality and lies collide. How far would you go to uncover the truth?', 0, 1, 0.00),
(26, 'onyxstorm.pdf', 'Onyx Storm', 'Rebecca Yarros', '9789510514993', 'Onyx Storm is a thrilling fantasy novel where mystical forces collide with intense action. The protagonist uncovers secrets, and dangerous enemies, in a world on the brink of a powerful storm. A journey of power and resilience.', 1, 1, 750.00),
(27, 'diaryofa.pdf', 'The Diary Of A Young Girl', 'Anne Frank', '9780307807533', 'The Diary of a Young Girl is Anne Frank heartfelt account of life in hiding during WW. Through her words, we see her hopes, fears, and growth, capturing the human spirit amidst tragedy and war.', 0, 1, 0.00),
(28, 'thinkmonk.pdf', 'Think Like A Monk', 'Jay Shetty', '9781982149819', 'Think Like a Monk by Jay Shetty offers practical wisdom to reduce stress, improve focus, and find peace by applying timeless monk principles to modern life.', 0, 1, 0.00),
(30, 'fourthwing.pdf', 'Fourth Wing', 'Rebecca Yarros', '9783423284127', 'Fourth Wing follows Violet Sorrengail, forced into a brutal dragon rider academy where danger, romance, and rebellion ignite. With war looming, trust is deadly and dragons do not bond easily.', 1, 1, 800.00),
(31, 'twistedlies.pdf', 'Twisted Lies', 'Ana Huang', '9783736320482', 'In Twisted Lies, a fake relationship turns dangerously real. Secrets, slow-burn tension, and unexpected love collide as billionaire Christian Harper and Stella unravel lies and their guarded hearts.', 1, 1, 570.00),
(32, 'acowar.pdf', 'A Court Of Wings And Ruin', 'Sarah J Mass', '9781619634497', 'A Court of Wings and Ruin follows Feyre Archeron as she navigates a world at love, and vengeance. With deadly battles, political intrigue, and a fight for survival, Feyre must make impossible choices to protect those she loves.', 0, 1, 0.00),
(34, 'igniteme.pdf', 'Ignite Me', 'Tahereh Mafi', '9788581634401', 'In Ignite Me, Juliette is done hiding. With her powers stronger than ever, she joins forces with Warner to take down the oppressive Reestablishment. Love, rebellion, and self-empowerment ignite in this explosive finale.', 1, 1, 470.00),
(35, 'defyme.pdf', 'The Cruel Prince', 'Holly Black', '9788711982457', 'The book is about the magic world shdsidi nfbs sdjid sdgdyusa sdbjhgdc sgdeywu kjrgj vbfvjdf sdvhase bdfh hgdu bh nsdjja.', 0, 1, 0.00),
(36, 'ouabh.pdf', 'How to Win Friends and Influence People', 'Dale Carnegie', '9780307807538', 'How to Win Friends and Influence People is a 1936 self-help book written by Dale Carnegie. Over 30 million copies have  Carnegie had been conducting business education courses in New York since 1912.', 1, 1, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `book_genre`
--

DROP TABLE IF EXISTS `book_genre`;
CREATE TABLE IF NOT EXISTS `book_genre` (
  `book_genre_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL,
  PRIMARY KEY (`book_genre_id`),
  KEY `genre_id` (`genre_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `book_genre`
--

INSERT INTO `book_genre` (`book_genre_id`, `book_id`, `genre_id`) VALUES
(89, 23, 18),
(91, 20, 15),
(94, 18, 12),
(95, 18, 14),
(96, 22, 14),
(109, 26, 12),
(110, 26, 14),
(115, 27, 16),
(116, 28, 19),
(118, 30, 12),
(127, 34, 13),
(128, 32, 12),
(129, 32, 14),
(130, 31, 15),
(132, 35, 14),
(134, 36, 19);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE IF NOT EXISTS `genre` (
  `genre_id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`genre_id`),
  KEY `admin_idfk` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genre_id`, `genre_name`, `description`, `admin_id`) VALUES
(12, 'Romantasy', 'Where magic and passion collide! Enter a world of fierce warriors, enchanted kingdoms, and forbidden love. From star-crossed fates to daring quests, romantasy weave heart-pounding romance with epic adventure. Will love conquer all, or will dark forces tear them apart?', 1),
(13, 'Distopian', 'Step into a world on the edge of survival! Dystopian stories take you to societies ruled by fear, corruption, and rebellion. From crumbling cities and oppressive regimes to resilient heroes fighting for freedom, every twist will keep you on the edge of your seat. If you crave heart-stopping suspense, moral dilemmas, and the fight for a better future â€” the dystopian genre is calling. Are you ready to face the unknown?', 1),
(14, 'Fantasy', 'A fantasy book is a genre of fiction that takes readers on a journey to magical worlds filled with mythical creatures, legendary heroes, and epic adventures. Often set in imaginative realms, these stories include elements like powerful wizards, ancient prophecies, enchanted objects, and battles between good and evil. Fantasy offers a captivating escape from reality, sparking the imagination with tales of wonder and bravery.', 1),
(15, 'Romance', 'Fall in love with every turn of the page! Romance books whisk you away on heartfelt journeys of passion, longing, and unforgettable connections. From sweet small-town love stories to steamy, whirlwind affairs, each story will tug at your heartstrings and leave you swooning. Whether you believe in soulmates or love at first sight, the romance genre is your perfect escape. Get ready to feel all the feels!', 1),
(16, 'Autobiography', 'An inspiring journey through the ups and downs of life, this autobiography takes you inside the heart and mind of someone who has faced challenges, triumphs, and everything in between. Through raw honesty and reflection, the author shares pivotal moments, lessons learned, and the resilience that shaped their path. A powerful tale of growth, perseverance, and self-discovery that proves anything is possible with determination and heart.', 1),
(17, 'Sports', 'Get ready for a thrilling ride through the world of sports! This book captures the heart-pounding action, intense rivalries, and inspiring journeys of athletes who defy the odds. Whether it is a tale of triumph, defeat, or the relentless pursuit of greatness, every page will ignite your passion for the game. A must-read for sports fans and anyone who believes in the power of determination and teamwork.', 1),
(18, 'Thriller', 'Experience heart-pounding suspense and unexpected twists with a thrilling read! From gripping mysteries and psychological mind games to high-stakes action and dark secrets, thriller books will keep you guessing until the very last page. Dive in for a pulse-racing adventure.', 1),
(19, 'Romanceeeeeee', 'hjfh hgf jbfduwf nbdh nbdfhfd bdfhgds dsfdfu bh huduw bsjiuas busdhu ndhiwe nfjdhf bdu hbdfiw ncbcv bduw udiwegr hbfhdcb hbdiuhdwe bduwdiuw hbdue hdwu.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `image_id` int(50) NOT NULL AUTO_INCREMENT,
  `image_name` varchar(100) NOT NULL,
  `book_id` int(10) NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`image_id`, `image_name`, `book_id`) VALUES
(12, 'Once Upon A Broken Heart.jpg', 18),
(14, 'twistedlove.jpg', 20),
(16, 'A Court of Thorns and Roses.jpg', 22),
(17, 'verity.jpg', 23),
(20, '1746544371_onyx.jpeg', 26),
(21, '1746589710_auto1.jpg', 27),
(22, '1746589892_thinklikeamonk.jpg', 28),
(24, '1746593666_fourthwing.jpeg', 30),
(25, '1746632270_twisted lies.jpg', 31),
(26, '1746632840_acowar.jpg', 32),
(28, '1746666750_ignite me.jpg', 34),
(29, '1755103198_The+Cruel+Prince1000.png', 35),
(30, '1755482034_selfhelp2.jpg', 36);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `method` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 1,
  `book_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `user_idfk` (`user_id`),
  KEY `admin_idfk` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `method`, `payment_status`, `time`, `amount`, `user_id`, `admin_id`, `book_id`) VALUES
(67, 'Stripe', 'Completed', '2025-05-06 09:38:52', 450.00, 47, 1, 14),
(68, 'Stripe', 'Completed', '2025-05-06 10:50:37', 600.00, 47, 1, 22),
(70, 'Stripe', 'Completed', '2025-05-06 11:15:22', 600.00, 48, 1, 22),
(74, 'Stripe', 'Completed', '2025-05-06 17:21:58', 455.00, 47, 1, 18),
(77, 'Stripe', 'Completed', '2025-05-06 21:05:54', 750.00, 48, 1, 26),
(78, 'Stripe', 'Completed', '2025-05-07 07:22:25', 500.00, 48, 1, 20),
(79, 'Stripe', 'Completed', '2025-05-07 08:23:36', 600.00, 54, 1, 22),
(80, 'Stripe', 'Completed', '2025-05-07 11:03:49', 750.00, 47, 1, 26),
(81, 'Stripe', 'Completed', '2025-05-07 21:26:09', 570.00, 48, 1, 31),
(83, 'Stripe', 'Completed', '2025-05-08 09:18:24', 470.00, 56, 1, 34),
(84, 'Stripe', 'Completed', '2025-05-08 09:22:02', 800.00, 56, 1, 30),
(85, 'stripe', 'paid', '2025-05-08 09:30:19', 5.00, 58, 1, NULL),
(86, 'stripe', 'paid', '2025-05-08 09:31:59', 5.00, 59, 1, NULL),
(91, 'stripe', 'paid', '2025-05-08 15:45:50', 2000.00, 66, 1, NULL),
(92, 'Stripe', 'Completed', '2025-05-10 13:40:16', 450.00, 48, 1, 14),
(93, 'Stripe', 'Completed', '2025-08-13 21:59:58', 470.00, 48, 1, 34),
(95, 'stripe', 'paid', '2025-08-13 22:27:15', 2000.00, 67, 1, NULL),
(102, 'stripe', 'paid', '2025-08-14 21:46:34', 2000.00, 71, 1, NULL),
(103, 'Stripe', 'Completed', '2025-08-14 21:48:31', 570.00, 72, 1, 31),
(104, 'Stripe', 'Completed', '2025-08-14 21:51:51', 470.00, 72, 1, 34),
(105, 'stripe', 'paid', '2025-08-18 19:14:24', 2000.00, 73, 1, NULL),
(106, 'Stripe', 'Completed', '2025-08-18 19:15:35', 800.00, 72, 1, 30);

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

DROP TABLE IF EXISTS `subscription`;
CREATE TABLE IF NOT EXISTS `subscription` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('active','expired') DEFAULT 'active',
  `plan_type` varchar(50) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`subscription_id`),
  KEY `user_id` (`user_id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`subscription_id`, `user_id`, `start_time`, `end_time`, `status`, `plan_type`, `payment_id`) VALUES
(30, 73, '2025-08-18 13:29:24', '2025-09-17 13:29:24', 'active', 'monthly', 105),
(29, 71, '2025-08-14 16:01:34', '2025-09-13 16:01:34', 'active', 'monthly', 102),
(27, 67, '2025-08-13 16:42:15', '2025-09-12 16:42:15', 'active', 'monthly', 95),
(26, 66, '2025-05-08 10:00:50', '2025-06-07 10:00:50', 'active', 'monthly', 91),
(21, 59, '2025-05-08 03:46:59', '2025-06-07 03:46:59', 'active', 'monthly', 86),
(20, 58, '2025-05-08 03:45:19', '2025-06-07 03:45:19', 'active', 'monthly', 85),
(19, 53, '2025-05-06 14:03:21', '2025-06-05 14:03:21', 'active', 'monthly', 76),
(23, 63, '2025-05-08 05:30:24', '2025-06-07 05:30:24', 'active', 'monthly', 88),
(14, 41, '2025-05-06 01:17:14', '2025-06-05 01:17:14', 'active', 'monthly', 45);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(50) NOT NULL AUTO_INCREMENT,
  `user_status` int(10) NOT NULL,
  `subscription_status` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `Phone` bigint(50) DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `admin_idfk` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_status`, `subscription_status`, `username`, `email`, `password`, `Phone`, `admin_id`) VALUES
(47, 1, 0, 'Youshan', 'youshan@gmail.com', '2e136b5daf192fe30e4788b81ddbb050', 9807654780, 1),
(48, 1, 0, 'Yogesh', 'yogesh@gmail.com', '2172c56e67f5004d0d0cb74588a320a0', 9807657600, 1),
(54, 1, 0, 'Atithi', 'atithi@gmail.com', '77d77d5eb346437af6a64dda03bfcc4f', 9807645869, 1),
(56, 1, 0, 'Riddhi', 'riddhi@gmail.com', 'b6cadda732671ebf62d14cac43be0994', 9807656789, 1),
(58, 1, 1, 'Manju', 'manju@gmail.com', 'c0aecb366d9ce7e998bc31fcf353df38', 9835243780, 1),
(59, 1, 1, 'Ganga', 'ganga@gmail.com', '70ee897d7899c51a78133ba34b0a2cc6', 9806754808, 1),
(66, 1, 1, 'Samip', 'samip@gmail.com', '5393c503e8f1cce7e39ee7749c4b4ea4', 9825454047, 1),
(67, 1, 1, 'Nicshal', 'nischal@gmail.com', '58a3dd60b8b25433766e1de8f28d8e16', 9875439029, 1),
(71, 1, 1, 'Nitisha', 'nitisha@gmail.com', '6fe19ed6cb75de639d35437780605e9d', 9873647280, 1),
(72, 1, 0, 'Puja', 'puja@gmail.com', '1e468be07654b616e0a40c3e5f40caee', 9806538703, 1),
(73, 1, 1, 'Prajwal', 'prajwal@gmail.com', '5722db8ceaba3852e3027b8812e5c224', 9845670975, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

DROP TABLE IF EXISTS `user_history`;
CREATE TABLE IF NOT EXISTS `user_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `interaction_time` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`history_id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_history`
--

INSERT INTO `user_history` (`history_id`, `user_id`, `book_id`, `interaction_time`) VALUES
(1, 47, 14, '2025-05-07 07:19:19'),
(2, 47, 22, '2025-05-07 07:19:19'),
(3, 47, 20, '2025-05-07 07:19:19'),
(4, 47, 18, '2025-05-07 07:19:19'),
(5, 48, 22, '2025-05-07 07:22:34'),
(6, 48, 26, '2025-05-07 07:22:34'),
(7, 48, 20, '2025-05-07 07:22:34'),
(8, 54, 22, '2025-05-07 09:08:44'),
(9, 47, 26, '2025-05-07 11:05:23'),
(10, 53, 28, '2025-05-07 17:21:54'),
(11, 53, 27, '2025-05-07 17:22:49'),
(12, 53, 29, '2025-05-07 20:58:00'),
(13, 53, 26, '2025-05-07 21:17:32'),
(14, 53, 30, '2025-05-07 21:18:34'),
(15, 53, 31, '2025-05-07 21:23:53'),
(16, 48, 31, '2025-05-07 21:26:17'),
(17, 47, 27, '2025-05-07 21:46:50'),
(18, 47, 32, '2025-05-07 21:48:48'),
(19, 55, 32, '2025-05-08 08:06:04'),
(20, 55, 31, '2025-05-08 08:10:09'),
(21, 56, 34, '2025-05-08 09:18:29'),
(22, 56, 30, '2025-05-08 09:22:06'),
(23, 66, 14, '2025-05-08 15:48:32'),
(24, 66, 32, '2025-05-08 16:21:14'),
(25, 48, 14, '2025-08-13 21:56:39'),
(26, 48, 34, '2025-08-13 22:00:08'),
(27, 67, 31, '2025-08-13 22:28:30'),
(28, 67, 30, '2025-08-13 22:29:13'),
(29, 67, 28, '2025-08-13 23:19:51'),
(30, 67, 34, '2025-08-13 23:19:56'),
(31, 68, 34, '2025-08-14 08:21:18'),
(32, 68, 32, '2025-08-14 08:22:12'),
(33, 68, 23, '2025-08-14 10:07:13'),
(34, 68, 31, '2025-08-14 10:15:42'),
(35, 68, 35, '2025-08-14 10:19:27'),
(36, 70, 35, '2025-08-14 21:10:39'),
(37, 70, 34, '2025-08-14 21:13:05'),
(38, 69, 35, '2025-08-14 21:13:39'),
(39, 69, 34, '2025-08-14 21:17:42'),
(40, 69, 31, '2025-08-14 21:17:51'),
(41, 71, 31, '2025-08-14 21:47:33'),
(42, 72, 35, '2025-08-14 21:48:08'),
(43, 72, 31, '2025-08-14 21:48:36'),
(44, 71, 35, '2025-08-14 21:50:51'),
(45, 72, 32, '2025-08-14 21:51:27'),
(46, 72, 28, '2025-08-18 15:45:35'),
(47, 72, 34, '2025-08-18 15:48:21'),
(48, 71, 36, '2025-08-18 18:54:52'),
(49, 72, 30, '2025-08-18 19:15:47');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`admin_Id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE;

--
-- Constraints for table `book_genre`
--
ALTER TABLE `book_genre`
  ADD CONSTRAINT `book_genre_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`genre_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `book_genre_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON UPDATE CASCADE;

--
-- Constraints for table `genre`
--
ALTER TABLE `genre`
  ADD CONSTRAINT `genre_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
