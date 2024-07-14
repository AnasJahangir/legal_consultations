-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 10, 2024 at 04:51 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 8.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `legal_consultations`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `photo` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user_default.png',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `first_name`, `last_name`, `email`, `phone`, `role`, `photo`, `password`, `created_at`) VALUES
(1, 'Johnn', 'Doe', 'admin@gmail.com', '43393099392', '1', '304976a29dc4303be9aca7524a103d3f.png', '$2y$10$Yj63KOtEpHH.W/rQDFBa4OJh4hlxGW5gxxM08KyrxUa8nw08kmEhe', '2024-01-17 00:00:00'),
(3, 'Cocolum', 'Makio', 'neolite90@gmail.com', '3333333333', '0', 'user_default.png', '$2y$10$QJe/2aZc77Zq8YO/NQ9Pf.HiC4pZnU5VYHNRDfw8HlFgXy.4HMs.C', '2024-01-20 06:38:00'),
(5, 'Spiner', 'Tali', 'tali@gmail.com', '0506328112', '0', 'user_default.png', NULL, '2024-07-10 12:43:21');

-- --------------------------------------------------------

--
-- Table structure for table `admin_messages`
--

DROP TABLE IF EXISTS `admin_messages`;
CREATE TABLE IF NOT EXISTS `admin_messages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `reservation_id` int(10) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_admin_message_reservation_id` (`reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_messages`
--

INSERT INTO `admin_messages` (`id`, `reservation_id`, `message`, `created_at`) VALUES
(8, 7, 'New Appointment booking for <b>Commercial issues</b> by  <b>Otaseino Hammed</b>', '2024-07-10 11:36:00'),
(10, 7, 'Appointment booking for <b>Commercial issues</b> has been reschedule by  <b>Otaseino Hammed</b>', '2024-07-10 12:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

DROP TABLE IF EXISTS `password_reset`;
CREATE TABLE IF NOT EXISTS `password_reset` (
  `email` varchar(50) NOT NULL,
  `time_stamp` int(10) UNSIGNED NOT NULL,
  KEY `fk_user_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `password_reset`
--

INSERT INTO `password_reset` (`email`, `time_stamp`) VALUES
('nyosasa@gmail.com', 1720266418),
('onazi@gmail.com', 1720271791);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `subservice` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `call_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_fee` decimal(10,2) NOT NULL,
  `service_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `modify_count` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`reservation_id`),
  UNIQUE KEY `unique_appointment` (`appointment_date`,`appointment_time`),
  KEY `fk_reservation_user_id` (`user_id`),
  KEY `fk_reservation_service_id` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `created_at`, `service_id`, `appointment_date`, `appointment_time`, `subservice`, `call_method`, `payment_method`, `service_fee`, `service_description`, `updated_at`, `modify_count`) VALUES
(7, 8, '2024-07-10 11:36:00', 9, '2024-07-23', '12:00:00', 'Attend a session', '', 'Bank Transfer', 120.00, 'Hi Sir, I kindly need a session with you. Please consider my availability at this time', '2024-07-10 12:29:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `reservation_changes`
--

DROP TABLE IF EXISTS `reservation_changes`;
CREATE TABLE IF NOT EXISTS `reservation_changes` (
  `reservation_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  KEY `fk_reservation_changes_id` (`reservation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservation_changes`
--

INSERT INTO `reservation_changes` (`reservation_id`, `label`, `changes`, `created_at`) VALUES
(7, 'Appointment modified', '<div>Payment method modified from <b>Apple Pay</b> to <b>Bank Transfer</b><div><div>Appointment time modified from <b>12:30:00</b> to <b>19:00:00</b><div>', '2024-07-10 12:29:00'),
(7, 'Appointment reschedule', '<div>Appointment time modified from <b>19:00:00</b> to <b>12:00:00</b><div>', '2024-07-10 12:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `service_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `service_description`, `option`, `default_price`) VALUES
(1, 'Corporate restructuring transformation ,merger and liquidation.', '', '', 120.00),
(2, 'Obtaining commercial registration and dealing with all governmental sectors.', '', '', 130.00),
(3, 'Providing legal support services for all installations', '', NULL, 100.00),
(4, 'Transformation from an establishment to a company', '', NULL, 100.00),
(5, 'Preparing work regulations and policies', '', NULL, 100.00),
(6, 'Annual legal consulting contract for installations', '', NULL, 100.00),
(7, 'Establishing and registering foreign companies and following up with th Ministry of Invest-men', '', NULL, 100.00),
(8, 'Establishing companies and articles of incorporation . incorporation contracts-company memorandum of association', '', NULL, 100.00),
(9, 'Commercial issues', 'All legal services are provided in commercial cases, including:\n- Disputes that arise between merchants due to their original or subsidiary business\nactivities.\n– Lawsuits brought against the merchant in commercial contract disputes, if the value of\nthe original claim in the lawsuit exceeds five hundred thousand riyals.\n– Disputes of partners in the Mudaraba company.\n– Suits and violations arising from the application of the provisions of the Companies Law.\n– Lawsuits and violations arising from the application of the provisions of the bankruptcy law.\n– Lawsuits and violations arising from the application of intellectual property systems.\n– Lawsuits and violations arising from the application of other commercial regulations.\n- Lawsuits and requests related to the judicial receiver, trustee, liquidator, appointed expert,\nand the like; Whenever the dispute is related to a lawsuit that the court has jurisdiction to\nhear.\n– Claims for compensation for damages arising from a lawsuit previously\nconsidered by the court', 'has_subservice', 100.00),
(20, 'Criminal cases', 'All legal services are provided in criminal cases, including: crimes of theft, defamation,\nproperty damage, magic, fortune-telling, and sorcery, defamation and defamation crimes,\nshooting crimes, blackmail, forgery, impersonation, violating the sanctity of place, and\nsabotage. Kidnapping, breach of trust, malicious complaints or claims, insults or cursing,\nperjury, murder, physical assault, fraudulent crimes, covering up a crime or criminal, harm\ncrimes, information crimes, harassment crimes, display crimes, and threats', 'has_subservice', 100.00),
(21, 'Personal status issues', 'All legal services are provided in personal status cases, including cases of proof of marriage,\ndivorce, divorce, annulment of marriage, return to marriage, custody, alimony, visitation,\ninheritance issues, division of estates, identification of heirs, and determination of the\nestate. In addition to the issues of proving lineage, absence, and death, the issues of proving\nan endowment or will, invalidating them, removing the administrator of the endowment or\nwill, and holding him accountable, and issues proving the appointment of guardians and the\nresidence of guardians and overseers, and authorizing them to act that require the court’s\npermission, removing them when necessary, and quarantining the foolish and removing it\nfrom them. . Our services also include cases of appointing a judicial guard, travel ban cases,\ngift or revocation cases, adhal cases (marrying someone who has no guardian, or to\nsomeone who has her guardians freed from her), and compensation cases for damages\nresulting from personal status matters.', 'has_subservice', 100.00),
(22, 'Labor issues', 'All legal services are provided in labor cases, including\n- Disputes related to employment contracts, wages, rights, work injuries and compensation\nfor them.\n- Disputes related to the employer imposing disciplinary penalties on the worker, or related\nto requesting exemption from them.\n– Lawsuits filed to impose penalties stipulated in the labor system.\n- Disputes resulting from dismissal from work. Complaints of employers and workers whose\nobjections were not accepted against any decision issued by any competent body in the\nGeneral Organization for Social Insurance, related to the obligation of registration,\ncontributions or compensation.\n– Disputes related to workers subject to the provisions of the labor system, including\ngovernment workers.\n– Disputes arising from the application of the labor system and the social insurance system.\n– Requesting documents related to the worker and deposited with the employer.\n– Requesting a service certificate.\n– Objection to the decisions of the domestic service workers’ committees and\nthose of the like .', 'has_subservice', 100.00),
(32, 'Administrative issues', 'All legal services are provided in administrative cases, including:\n- Lawsuits related to the rights established in the civil and military service and retirement\nsystems for government employees and employees and agencies with an independent public\nlegal personality or their heirs and beneficiaries.\n- Claims to cancel final administrative decisions submitted by concerned parties, when the\nbasis of the appeal is lack of jurisdiction, a defect in form, a defect in cause, violation of rules\nand regulations, error in their application or interpretation, or abuse of authority, including\ndecisions. Disciplinary decisions, decisions issued by quasi-judicial committees and\ndisciplinary councils, as well as decisions issued by public benefit associations - and the like -\nrelated to their activities. The administration’s refusal or abstention from taking a decision\nthat it should have taken in accordance with the rules and regulations is considered an\nadministrative decision.\n– Compensation claims submitted by interested parties for the decisions or actions of the\nadministration.\n– Lawsuits related to contracts to which the management entity is a party.\n– Disciplinary lawsuits filed by the competent authority.\n– Other administrative disputes.\n– Requests to implement foreign judgments and foreign arbitrators’ awards.\n– Appealing appealable rulings issued by administrative courts.\n- Objections before the Supreme Administrative Court against rulings issued by the\nAdministrative Courts of Appeals.', 'has_subservice', 100.00),
(44, 'Consultations', '- In-person legal advice\n- Remote legal advice', 'consultation', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `phone`, `password`, `created_at`) VALUES
(8, 'Otaseino', 'Hammed', 'hammed@gmail.com', '0506328112', '$2y$10$iSOwQs2Ho24iCou8KM3GYeVwco3zQp/IGF0IcDDSpsgYmBrKfnqtu', '2024-07-10 00:00:00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD CONSTRAINT `fk_admin_message_reservation_id` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_service_id` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservation_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation_changes`
--
ALTER TABLE `reservation_changes`
  ADD CONSTRAINT `fk_reservation_changes_id` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
