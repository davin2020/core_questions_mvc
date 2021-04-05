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
  PRIMARY KEY (`q_id`) USING BTREE,
  KEY `points_type` (`points_type`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.ref_core_questions: 7 rows
/*!40000 ALTER TABLE `ref_core_questions` DISABLE KEYS */;
INSERT INTO `ref_core_questions` (`q_id`, `question`, `gp_order`, `do_points_ascend`, `points_type`) VALUES
	(1, 'I have felt tense, anxious or nervous', 1, 1, 123),
	(2, 'I have felt I have someone to turn to for support when needed', 2, 0, 321),
	(3, 'I have felt ok about myself', 3, 0, 321),
	(4, 'I have felt able to cope when things go wrong', 4, 0, 321),
	(5, 'I have been troubled by aches, pains or other physical problems	', 5, 1, 123),
	(6, 'I have been happy with the things I have done', 6, 0, 321),
	(7, 'I have had difficulty getting to sleep or staying asleep', 7, 1, 123);
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.users: 3 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `name`, `date_joined`) VALUES
	(1, 'Alvis', '2020-12-15'),
	(2, 'Pree', '2021-01-20'),
	(3, 'Turin', '2021-03-30'),
	(4, 'johnny', '2021-04-04'),
	(5, 'dutch', '2021-04-06'),
	(6, 'zephyur jones', '2021-03-29');
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

-- Dumping data for table corelifedb.user_core_answers: 10 rows
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
	(3, 5, 0);
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table corelifedb.user_core_score: 3 rows
/*!40000 ALTER TABLE `user_core_score` DISABLE KEYS */;
INSERT INTO `user_core_score` (`ucs_id`, `user_id`, `score_date`, `overall_score`) VALUES
	(1, 1, '2021-02-15', NULL),
	(2, 2, '2021-01-14', 65),
	(3, 2, '2021-01-21', 45);
/*!40000 ALTER TABLE `user_core_score` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
