-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: us-cdbr-azure-central-a.cloudapp.net    Database: kander
-- ------------------------------------------------------
-- Server version	5.5.40-log

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
-- Table structure for table `business`
--

DROP TABLE IF EXISTS `business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `info` varchar(256) DEFAULT NULL,
  `target_market` int(10) unsigned DEFAULT NULL,
  `funding_needed` int(10) unsigned DEFAULT NULL,
  `funding_use` varchar(256) DEFAULT NULL,
  `acquisition_amount` int(10) unsigned DEFAULT NULL,
  `exit_strategy` varchar(256) DEFAULT NULL,
  `exit_time` int(11) DEFAULT NULL,
  `regulatory_issues` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business`
--

LOCK TABLES `business` WRITE;
/*!40000 ALTER TABLE `business` DISABLE KEYS */;
INSERT INTO `business` VALUES (1,'Test','this is a test business',5000000,1000000,'We will use these funds to cover the operational\r\ncosts during launch, while also providing capital\r\nfor future R&amp;D. These intial funds will allow\r\nShotTrackerÂ® to place the product in the hands of\r\nprominent coaches and teams.',100000000,'sell to google',5,'none');
/*!40000 ALTER TABLE `business` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comparable_exits`
--

DROP TABLE IF EXISTS `comparable_exits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comparable_exits` (
  `id` int(11) NOT NULL DEFAULT '0',
  `ceID` int(11) NOT NULL DEFAULT '0',
  `company` varchar(32) DEFAULT NULL,
  `exit_amount` int(10) unsigned DEFAULT NULL,
  `acquirer` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`,`ceID`),
  CONSTRAINT `comparable_exits_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comparable_exits`
--

LOCK TABLES `comparable_exits` WRITE;
/*!40000 ALTER TABLE `comparable_exits` DISABLE KEYS */;
/*!40000 ALTER TABLE `comparable_exits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `competitive_analysis`
--

DROP TABLE IF EXISTS `competitive_analysis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competitive_analysis` (
  `id` int(11) NOT NULL DEFAULT '0',
  `entry` int(11) NOT NULL AUTO_INCREMENT,
  `behavior` varchar(256) NOT NULL,
  `advantage` varchar(256) NOT NULL,
  PRIMARY KEY (`id`,`entry`),
  UNIQUE KEY `entry` (`entry`),
  CONSTRAINT `competitive_analysis_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=891 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `competitive_analysis`
--

LOCK TABLES `competitive_analysis` WRITE;
/*!40000 ALTER TABLE `competitive_analysis` DISABLE KEYS */;
INSERT INTO `competitive_analysis` VALUES (1,1,'Not recording practice data or attempting to record by hand','Significantly less time intensive and can track data in and out of practice'),(1,821,'Video Analysis software - reviews video clips of games and creates reports of statistics (Synergy & Hudl)','ShotTracker® data is real time (vs. waiting 1 week) and much more affordable/less time intensive to generate in-practice stats vs. just game stats.'),(1,831,'The Gun - large device that is placed below the net to track shots.','ShotTracker® provides the position of the player on the court when the shot is taken and is significantly less expensive than this product.'),(1,841,'Hoop Tracker (still in development) - wearable device to track shots','ShotTracker® allows you to track shots taken from anywhere on the court vs. Hoop Tracker requires you follow a preprogramed set of locations to shoot.'),(1,851,'Future Competitors','If someone attempts to copy our process, we will be first to market for coaching teams and rely on an unwillingness to change.');
/*!40000 ALTER TABLE `competitive_analysis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financial_projections`
--

DROP TABLE IF EXISTS `financial_projections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `financial_projections` (
  `id` int(11) NOT NULL DEFAULT '0',
  `entry` int(11) NOT NULL DEFAULT '0',
  `income` int(10) unsigned DEFAULT NULL,
  `expenses` int(10) unsigned DEFAULT NULL,
  `net` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`,`entry`),
  CONSTRAINT `financial_projections_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financial_projections`
--

LOCK TABLES `financial_projections` WRITE;
/*!40000 ALTER TABLE `financial_projections` DISABLE KEYS */;
INSERT INTO `financial_projections` VALUES (1,1,2000000,1500000,500000),(1,2,3500000,1750000,1500000),(1,3,6500000,3000000,2500000);
/*!40000 ALTER TABLE `financial_projections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intellectual_property`
--

DROP TABLE IF EXISTS `intellectual_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intellectual_property` (
  `id` int(11) NOT NULL DEFAULT '0',
  `entry` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(256) NOT NULL,
  PRIMARY KEY (`id`,`entry`),
  UNIQUE KEY `entry` (`entry`),
  CONSTRAINT `intellectual_property_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2061 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intellectual_property`
--

LOCK TABLES `intellectual_property` WRITE;
/*!40000 ALTER TABLE `intellectual_property` DISABLE KEYS */;
INSERT INTO `intellectual_property` VALUES (1,1,'We have registered a trademark on the ShotTracker® name.'),(1,2011,'We have filed a non-provisional patent on the interaction between the net sensor, wrist sensor, and mobile app (currently pending).'),(1,2021,'Two provisional patents have also been filed on fututere technologies to be integrated into ShotTracker®.'),(1,2051,'Two provisional patents have also been filed on fututere technologies to be integrated into ShotTracker®.');
/*!40000 ALTER TABLE `intellectual_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `market`
--

DROP TABLE IF EXISTS `market`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market` (
  `id` int(11) NOT NULL DEFAULT '0',
  `groups` varchar(32) NOT NULL DEFAULT '',
  `amount` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`,`groups`),
  CONSTRAINT `market_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `market`
--

LOCK TABLES `market` WRITE;
/*!40000 ALTER TABLE `market` DISABLE KEYS */;
INSERT INTO `market` VALUES (1,'Amateur basketball teams',50000),(1,'College basketball teams',3984),(1,'High School basketball teams',35880),(1,'NBA',10);
/*!40000 ALTER TABLE `market` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `market_efforts`
--

DROP TABLE IF EXISTS `market_efforts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market_efforts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `entry` int(11) NOT NULL DEFAULT '0',
  `when_quarter` enum('Q1','Q2','Q3','Q4') DEFAULT NULL,
  `when_year` int(11) DEFAULT NULL,
  `what` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`,`entry`),
  CONSTRAINT `market_efforts_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `market_efforts`
--

LOCK TABLES `market_efforts` WRITE;
/*!40000 ALTER TABLE `market_efforts` DISABLE KEYS */;
INSERT INTO `market_efforts` VALUES (1,1,'Q2',2025,'first market effort37'),(1,2,'Q3',2021,'next market effort4'),(1,3,'Q1',2015,'asdf');
/*!40000 ALTER TABLE `market_efforts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `milestones` (
  `id` int(11) NOT NULL DEFAULT '0',
  `entry` int(11) NOT NULL DEFAULT '0',
  `when_quarter` enum('Q1','Q2','Q3','Q4') DEFAULT NULL,
  `when_year` int(11) DEFAULT NULL,
  `what` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`,`entry`),
  CONSTRAINT `milestones_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `milestones`
--

LOCK TABLES `milestones` WRITE;
/*!40000 ALTER TABLE `milestones` DISABLE KEYS */;
INSERT INTO `milestones` VALUES (1,0,'Q1',2015,'Provide ShotTrackerÂ® to major coaches and teams for positive reviews'),(1,1,'Q3',2015,'Sales associates will attend basketball tournaments, coaching conferences, and coaches clinics.'),(1,2,'Q1',2016,'Get NBA players to talk about ShotTrackerÂ® via social media'),(1,3,'Q3',2016,'Advertising at regional basketball associations, coaches clinics, and amateur league tournaments.'),(1,4,'Q1',2017,'Pursue strategic partnerships with Vendors such as Dickâ€™s, Finish Line, and Foot Locker and with Brands such as Nike.');
/*!40000 ALTER TABLE `milestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opportunity`
--

DROP TABLE IF EXISTS `opportunity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opportunity` (
  `id` int(11) NOT NULL,
  `problem` varchar(256) DEFAULT NULL,
  `problem_valid` varchar(256) DEFAULT NULL,
  `solution` varchar(256) DEFAULT NULL,
  `solution_valid` varchar(256) DEFAULT NULL,
  `solution_status` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `opportunity_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opportunity`
--

LOCK TABLES `opportunity` WRITE;
/*!40000 ALTER TABLE `opportunity` DISABLE KEYS */;
INSERT INTO `opportunity` VALUES (1,'this is the problem','this is how the problem was validated','this is how the problem was validated','this is the solution','this is the status of the solution');
/*!40000 ALTER TABLE `opportunity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `partnersID` int(11) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `info` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`partnersID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,'Josh5','person88','stuff11');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners_business`
--

DROP TABLE IF EXISTS `partners_business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners_business` (
  `id` int(11) NOT NULL DEFAULT '0',
  `partnersID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`partnersID`),
  KEY `partnersID` (`partnersID`),
  CONSTRAINT `partners_business_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partners_business_ibfk_2` FOREIGN KEY (`partnersID`) REFERENCES `partners` (`partnersID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners_business`
--

LOCK TABLES `partners_business` WRITE;
/*!40000 ALTER TABLE `partners_business` DISABLE KEYS */;
INSERT INTO `partners_business` VALUES (1,1);
/*!40000 ALTER TABLE `partners_business` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rounds_anticipated`
--

DROP TABLE IF EXISTS `rounds_anticipated`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rounds_anticipated` (
  `id` int(11) NOT NULL DEFAULT '0',
  `round_entry` int(11) NOT NULL DEFAULT '0',
  `amount` int(10) unsigned DEFAULT NULL,
  `years` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`round_entry`),
  UNIQUE KEY `round_entry` (`round_entry`),
  CONSTRAINT `rounds_anticipated_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rounds_anticipated`
--

LOCK TABLES `rounds_anticipated` WRITE;
/*!40000 ALTER TABLE `rounds_anticipated` DISABLE KEYS */;
/*!40000 ALTER TABLE `rounds_anticipated` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_channels`
--

DROP TABLE IF EXISTS `sales_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales_channels` (
  `id` int(11) NOT NULL DEFAULT '0',
  `entry` int(11) NOT NULL DEFAULT '0',
  `info` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`,`entry`),
  CONSTRAINT `sales_channels_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_channels`
--

LOCK TABLES `sales_channels` WRITE;
/*!40000 ALTER TABLE `sales_channels` DISABLE KEYS */;
INSERT INTO `sales_channels` VALUES (1,1,'This is the first sales channels entry1'),(1,2,'this is number 23');
/*!40000 ALTER TABLE `sales_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `name` varchar(32) NOT NULL,
  `title` varchar(32) DEFAULT NULL,
  `info` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES ('John Smith','The Dude','kind of a big deal');
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_business`
--

DROP TABLE IF EXISTS `team_business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_business` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`name`),
  KEY `name` (`name`),
  CONSTRAINT `team_business_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_business_ibfk_2` FOREIGN KEY (`name`) REFERENCES `team` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_business`
--

LOCK TABLES `team_business` WRITE;
/*!40000 ALTER TABLE `team_business` DISABLE KEYS */;
INSERT INTO `team_business` VALUES (1,'John Smith');
/*!40000 ALTER TABLE `team_business` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `updates`
--

DROP TABLE IF EXISTS `updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(32) DEFAULT NULL,
  `info` varchar(256) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `updates`
--

LOCK TABLES `updates` WRITE;
/*!40000 ALTER TABLE `updates` DISABLE KEYS */;
/*!40000 ALTER TABLE `updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(32) NOT NULL,
  `hashed_password` varchar(256) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `name` varchar(32) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `experience` enum('low','medium','high') DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `zipcode` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','$2y$10$h60FTYEPMll6DNNwpdD3a.aL/IxG6YJEHCW1t39Chgcg1r5s2o7t2',1,'admin',99,'low','male','65202'),('tester','$2y$10$h60FTYEPMll6DNNwpdD3a.aL/IxG6YJEHCW1t39Chgcg1r5s2o7t2',0,'The Test',98,'high','male','65240');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_business`
--

DROP TABLE IF EXISTS `users_business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_business` (
  `id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`username`),
  KEY `username` (`username`),
  CONSTRAINT `users_business_ibfk_1` FOREIGN KEY (`id`) REFERENCES `business` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_business_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_business`
--

LOCK TABLES `users_business` WRITE;
/*!40000 ALTER TABLE `users_business` DISABLE KEYS */;
INSERT INTO `users_business` VALUES (1,'tester');
/*!40000 ALTER TABLE `users_business` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-13 20:02:21
