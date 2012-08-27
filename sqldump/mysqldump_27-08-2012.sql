-- MySQL dump 10.13  Distrib 5.5.27, for osx10.6 (i386)
--
-- Host: localhost    Database: impeesa2
-- ------------------------------------------------------
-- Server version	5.5.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `impeesa2`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `impeesa2` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `impeesa2`;

--
-- Table structure for table `impeesa2_debug`
--

DROP TABLE IF EXISTS `impeesa2_debug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impeesa2_debug` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `exception_id` varchar(5) NOT NULL,
  `trace` text NOT NULL,
  `exception_file` text NOT NULL,
  `exception_line` int(11) NOT NULL,
  `request_uri` text NOT NULL,
  `request_referer` text NOT NULL,
  `request_method` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impeesa2_debug`
--

LOCK TABLES `impeesa2_debug` WRITE;
/*!40000 ALTER TABLE `impeesa2_debug` DISABLE KEYS */;
/*!40000 ALTER TABLE `impeesa2_debug` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impeesa2_news`
--

DROP TABLE IF EXISTS `impeesa2_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impeesa2_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `headline` text NOT NULL,
  `content` text NOT NULL,
  `publish` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impeesa2_news`
--

LOCK TABLES `impeesa2_news` WRITE;
/*!40000 ALTER TABLE `impeesa2_news` DISABLE KEYS */;
INSERT INTO `impeesa2_news` VALUES (13,'Das ist ein Test','Was geht ab?! :D :)<div><br></div><div>Ich freue mich, dass es klappt!:)</div>',1346018400),(14,'Und der nächste Test','Klar geht das ;) Soooo nice!!',1346018400);
/*!40000 ALTER TABLE `impeesa2_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impeesa2_users`
--

DROP TABLE IF EXISTS `impeesa2_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `impeesa2_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `email` text NOT NULL,
  `session_key` varchar(6) NOT NULL,
  `session_fingerprint` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impeesa2_users`
--

LOCK TABLES `impeesa2_users` WRITE;
/*!40000 ALTER TABLE `impeesa2_users` DISABLE KEYS */;
INSERT INTO `impeesa2_users` VALUES (1,'DasLampe','a6fd86355dac03c168f46cf1aa8288c1e7bf57f43968faf2cf49a00c7208d6b60fe473e11f7c4cce2f9c902c24ceb0112c0f299b2e28e05df65a646ac3a62b12','c48c8ad6b1cd93188989b8e5eb0363ae','daslampe@lano-crew.org','34eeb1','593d2d6013408de721a9aaeae9b5f99e');
/*!40000 ALTER TABLE `impeesa2_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-27  2:36:55
