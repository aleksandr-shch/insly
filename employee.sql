-- MySQL dump 10.17  Distrib 10.3.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: Insly
-- ------------------------------------------------------
-- Server version	10.3.11-MariaDB-1:10.3.11+maria~cosmic-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `birthdate` date DEFAULT '1970-01-01',
  `ssn` varchar(50) DEFAULT NULL,
  `current_employee` tinyint(1) NOT NULL DEFAULT 0,
  `contacts` text DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'mr. first','2000-01-01','54g66nu4568nutu34345t64k564k',1,'a:3:{s:5:\"email\";s:14:\"admin@mail.com\";s:5:\"phone\";s:16:\"+1(800)100-20-30\";s:7:\"address\";s:23:\"NY, RP, 5th avenue, 265\";}','me','me','2019-01-03 12:00:00','2019-01-03 13:00:00');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees_info`
--

DROP TABLE IF EXISTS `employees_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL DEFAULT 0,
  `language_code` char(2) NOT NULL,
  `introduction` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `translation_id` (`employee_id`),
  KEY `language_code` (`language_code`),
  CONSTRAINT `employees_info_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees_info`
--

LOCK TABLES `employees_info` WRITE;
/*!40000 ALTER TABLE `employees_info` DISABLE KEYS */;
INSERT INTO `employees_info` VALUES (1,1,'en','Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo','Sed ut perspiciatis unde omnis iste natus error sit voluptatem','But I must explain to you how all this mistaken'),(2,1,'es','Pero debo explicarte cómo toda esta idea errónea de denunciar placer.','Nadie rechaza, disgusta o evita','Pero quien tiene derecho a encontrar faltas en un hombre.'),(3,1,'fr','Mais qui a le droit de critiquer un homme?','Personne ne rejette naime pas ou évite','Mais je dois vous expliquer comment toute cette idée erronée de dénoncer le plaisir');
/*!40000 ALTER TABLE `employees_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees_language`
--

DROP TABLE IF EXISTS `employees_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees_language` (
  `code` char(2) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees_language`
--

LOCK TABLES `employees_language` WRITE;
/*!40000 ALTER TABLE `employees_language` DISABLE KEYS */;
INSERT INTO `employees_language` VALUES ('en','English'),('es','Spanish'),('fr','French');
/*!40000 ALTER TABLE `employees_language` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-03 19:15:30
