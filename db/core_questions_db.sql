-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.21 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for corelifedb
DROP DATABASE IF EXISTS `corelifedb`;
CREATE DATABASE IF NOT EXISTS `corelifedb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `corelifedb`;

-- Dumping structure for table corelifedb.ref_core_points
DROP TABLE IF EXISTS `ref_core_points`;
CREATE TABLE IF NOT EXISTS `ref_core_points` (
  `points_id` int NOT NULL,
  `pointsA_not` int NOT NULL,
  `pointsB_only` int NOT NULL,
  `pointsC_sometimes` int NOT NULL,
  `pointsD_often` int NOT NULL,
  `pointsE_most` int NOT NULL,
  PRIMARY KEY (`points_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.ref_core_points: 2 rows
/*!40000 ALTER TABLE `ref_core_points` DISABLE KEYS */;
INSERT INTO `ref_core_points` (`points_id`, `pointsA_not`, `pointsB_only`, `pointsC_sometimes`, `pointsD_often`, `pointsE_most`) VALUES
	(123, 0, 1, 2, 3, 4),
	(321, 4, 3, 2, 1, 0);
/*!40000 ALTER TABLE `ref_core_points` ENABLE KEYS */;

-- Dumping structure for table corelifedb.ref_core_questions
DROP TABLE IF EXISTS `ref_core_questions`;
CREATE TABLE IF NOT EXISTS `ref_core_questions` (
  `q_id` int NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `gp_order` int NOT NULL,
  `do_points_ascend` int NOT NULL DEFAULT '0',
  `points_type` int NOT NULL DEFAULT '0',
  `domain` char(1) DEFAULT NULL,
  PRIMARY KEY (`q_id`) USING BTREE,
  KEY `points_type` (`points_type`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.ref_core_questions: 7 rows
/*!40000 ALTER TABLE `ref_core_questions` DISABLE KEYS */;
INSERT INTO `ref_core_questions` (`q_id`, `question`, `gp_order`, `do_points_ascend`, `points_type`, `domain`) VALUES
	(1, 'I have felt tense, anxious or nervous', 1, 1, 123, NULL),
	(2, 'I have felt I have someone to turn to for support when needed', 2, 0, 321, NULL),
	(3, 'I have felt ok about myself', 3, 0, 321, NULL),
	(4, 'I have felt able to cope when things go wrong', 4, 0, 321, NULL),
	(5, 'I have been troubled by aches, pains or other physical problems	', 5, 1, 123, NULL),
	(6, 'I have been happy with the things I have done', 6, 0, 321, NULL),
	(7, 'I have had difficulty getting to sleep or staying asleep', 7, 1, 123, NULL);
/*!40000 ALTER TABLE `ref_core_questions` ENABLE KEYS */;

-- Dumping structure for table corelifedb.ref_core_scale
DROP TABLE IF EXISTS `ref_core_scale`;
CREATE TABLE IF NOT EXISTS `ref_core_scale` (
  `scale_id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `points` int DEFAULT NULL,
  PRIMARY KEY (`scale_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.ref_core_scale: 5 rows
/*!40000 ALTER TABLE `ref_core_scale` DISABLE KEYS */;
INSERT INTO `ref_core_scale` (`scale_id`, `label`, `points`) VALUES
	(1, 'Not at all', NULL),
	(2, 'Only occasionally', NULL),
	(3, 'Sometimes', NULL),
	(4, 'Often', NULL),
	(5, 'Most or All the time', NULL);
/*!40000 ALTER TABLE `ref_core_scale` ENABLE KEYS */;

-- Dumping structure for table corelifedb.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_joined` date NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.users: 6 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `name`, `date_joined`) VALUES
	(1, 'Alvis', '2020-12-15'),
	(2, 'Pree', '2021-01-14'),
	(3, 'Turin', '2021-03-30'),
	(4, 'Johnny', '2021-04-04'),
	(5, 'Dutch', '2021-02-21'),
	(10, 'Davin', '2021-03-22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table corelifedb.user_core_answers
DROP TABLE IF EXISTS `user_core_answers`;
CREATE TABLE IF NOT EXISTS `user_core_answers` (
  `ucs_id` int NOT NULL,
  `q_id` int NOT NULL,
  `points` int DEFAULT NULL,
  KEY `FK1_ucs_id` (`ucs_id`),
  KEY `FK1_q_id` (`q_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.user_core_answers: 171 rows
/*!40000 ALTER TABLE `user_core_answers` DISABLE KEYS */;
INSERT INTO `user_core_answers` (`ucs_id`, `q_id`, `points`) VALUES
	(2, 1, 1),
	(2, 2, 3),
	(2, 3, 1),
	(2, 4, 2),
	(2, 5, 1),
	(3, 1, 1),
	(3, 2, 2),
	(3, 3, 1),
	(3, 4, 1),
	(3, 5, 0),
	(12, 1, 0),
	(12, 2, 4),
	(12, 3, 4),
	(12, 4, 4),
	(12, 5, 0),
	(12, 6, 4),
	(12, 7, 0),
	(13, 1, 4),
	(13, 2, 0),
	(13, 3, 0),
	(13, 4, 0),
	(13, 5, 4),
	(13, 6, 0),
	(13, 7, 4),
	(14, 1, 0),
	(14, 2, 4),
	(14, 3, 4),
	(14, 4, 4),
	(14, 5, 0),
	(14, 6, 4),
	(14, 7, 0),
	(15, 1, 4),
	(15, 2, 0),
	(15, 3, 0),
	(15, 4, 0),
	(15, 5, 4),
	(15, 6, 0),
	(15, 7, 4),
	(16, 1, 0),
	(16, 2, 4),
	(16, 3, 4),
	(16, 4, 4),
	(16, 5, 0),
	(16, 6, 4),
	(16, 7, 0),
	(17, 1, 0),
	(17, 2, 4),
	(17, 3, 4),
	(17, 4, 4),
	(17, 5, 0),
	(17, 6, 4),
	(17, 7, 0),
	(18, 1, 0),
	(18, 2, 4),
	(18, 3, 4),
	(18, 4, 4),
	(18, 5, 0),
	(18, 6, 4),
	(18, 7, 0),
	(19, 1, 0),
	(19, 2, 3),
	(19, 3, 2),
	(19, 4, 1),
	(19, 5, 4),
	(19, 6, 4),
	(19, 7, 1),
	(20, 1, 1),
	(20, 2, 3),
	(20, 3, 3),
	(20, 4, 3),
	(20, 5, 1),
	(20, 6, 3),
	(20, 7, 1),
	(21, 1, 1),
	(21, 2, 3),
	(21, 3, 3),
	(21, 4, 3),
	(21, 5, 1),
	(21, 6, 3),
	(21, 7, 1),
	(22, 1, 2),
	(22, 2, 2),
	(22, 3, 2),
	(22, 4, 2),
	(22, 5, 2),
	(22, 6, 2),
	(22, 7, 2),
	(23, 1, 3),
	(23, 2, 1),
	(23, 3, 1),
	(23, 4, 1),
	(23, 5, 3),
	(23, 6, 1),
	(23, 7, 3),
	(24, 1, 4),
	(24, 2, 0),
	(24, 3, 0),
	(24, 4, 0),
	(24, 5, 4),
	(24, 6, 0),
	(24, 7, 4),
	(25, 1, 0),
	(25, 2, 4),
	(25, 3, 4),
	(25, 4, 4),
	(25, 5, 0),
	(25, 6, 4),
	(25, 7, 0),
	(26, 1, 0),
	(26, 2, 3),
	(26, 3, 2),
	(26, 4, 1),
	(26, 5, 4),
	(26, 6, 4),
	(26, 7, 1),
	(27, 1, 2),
	(27, 2, 4),
	(27, 3, 1),
	(27, 4, 3),
	(27, 5, 2),
	(27, 7, 0),
	(28, 1, 4),
	(28, 2, 4),
	(28, 3, 4),
	(28, 4, 4),
	(28, 6, 4),
	(28, 7, 4),
	(29, 1, 0),
	(29, 2, 4),
	(29, 3, 4),
	(29, 4, 4),
	(29, 5, 0),
	(29, 6, 4),
	(29, 7, 0),
	(30, 1, 0),
	(30, 2, 4),
	(30, 3, 4),
	(30, 4, 4),
	(30, 5, 0),
	(30, 6, 4),
	(30, 7, 0),
	(31, 1, 3),
	(31, 2, 2),
	(31, 3, 1),
	(31, 4, 2),
	(31, 5, 1),
	(31, 6, 3),
	(31, 7, 0),
	(32, 1, 1),
	(32, 2, 3),
	(32, 3, 2),
	(32, 4, 2),
	(32, 5, 0),
	(32, 6, 0),
	(32, 7, 0),
	(33, 2, 3),
	(33, 4, 2),
	(33, 5, 0),
	(33, 6, 2),
	(33, 7, 0),
	(34, 1, 4),
	(34, 2, 3),
	(34, 3, 3),
	(34, 4, 3),
	(34, 5, 2),
	(34, 6, 3),
	(34, 7, 1);
/*!40000 ALTER TABLE `user_core_answers` ENABLE KEYS */;

-- Dumping structure for table corelifedb.user_core_score
DROP TABLE IF EXISTS `user_core_score`;
CREATE TABLE IF NOT EXISTS `user_core_score` (
  `ucs_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `score_date` date DEFAULT NULL,
  `overall_score` int DEFAULT NULL,
  PRIMARY KEY (`ucs_id`) USING BTREE,
  KEY `FK1_userid` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.user_core_score: 32 rows
/*!40000 ALTER TABLE `user_core_score` DISABLE KEYS */;
INSERT INTO `user_core_score` (`ucs_id`, `user_id`, `score_date`, `overall_score`) VALUES
	(1, 1, '2021-02-15', 20),
	(2, 2, '2021-01-14', 46),
	(3, 2, '2021-01-21', 34),
	(4, 2, '2021-03-04', 52),
	(7, 1, '2021-04-01', 16),
	(8, 1, '2021-02-20', 16),
	(9, 1, '2021-02-25', 18),
	(10, 1, '2021-03-05', 16),
	(11, 1, '2021-03-15', 22),
	(12, 1, '2021-03-18', 27),
	(13, 1, '2021-03-25', 25),
	(14, 1, '2021-04-05', 32),
	(15, 1, '2021-04-09', 20),
	(16, 1, '2021-04-12', 18),
	(17, 10, '2021-04-08', 16),
	(18, 5, '2021-02-28', 16),
	(19, 2, '2021-04-01', 38),
	(20, 2, '2021-04-05', 43),
	(21, 2, '2021-01-28', 30),
	(22, 10, '2021-04-09', 14),
	(23, 10, '2021-04-10', 13),
	(24, 10, '2021-03-29', 12),
	(25, 10, '2021-03-22', 16),
	(26, 10, '2021-04-12', 15),
	(27, 10, '2021-04-13', 12),
	(28, 2, '2021-04-11', 30),
	(29, 5, '2021-03-07', 24),
	(30, 5, '2021-03-10', 18),
	(31, 5, '2021-03-15', 12),
	(32, 10, '2021-04-14', 8),
	(33, 5, '2021-03-20', 26),
	(34, 5, '2021-02-22', 19);
/*!40000 ALTER TABLE `user_core_score` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
