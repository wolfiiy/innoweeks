/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.4.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: db_greenhabits
-- ------------------------------------------------------
-- Server version	11.4.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `Befriend`
--

DROP TABLE IF EXISTS `Befriend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Befriend` (
  `idAccount` int(11) NOT NULL,
  `idAccount_1` int(11) NOT NULL,
  PRIMARY KEY (`idAccount`,`idAccount_1`),
  KEY `idAccount_1` (`idAccount_1`),
  CONSTRAINT `Befriend_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `t_Account` (`idAccount`),
  CONSTRAINT `Befriend_ibfk_2` FOREIGN KEY (`idAccount_1`) REFERENCES `t_Account` (`idAccount`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`idAccount` <> `idAccount_1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Befriend`
--

LOCK TABLES `Befriend` WRITE;
/*!40000 ALTER TABLE `Befriend` DISABLE KEYS */;
/*!40000 ALTER TABLE `Befriend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Complete`
--

DROP TABLE IF EXISTS `Complete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Complete` (
  `idAccount` int(11) NOT NULL,
  `idTask` int(11) NOT NULL,
  `comState` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idAccount`,`idTask`),
  KEY `idAccount` (`idAccount`),
  KEY `idTask` (`idTask`),
  CONSTRAINT `Complete_ibfk_1` FOREIGN KEY (`idAccount`) REFERENCES `t_Account` (`idAccount`),
  CONSTRAINT `Complete_ibfk_2` FOREIGN KEY (`idTask`) REFERENCES `t_Task` (`idTask`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Complete`
--

LOCK TABLES `Complete` WRITE;
/*!40000 ALTER TABLE `Complete` DISABLE KEYS */;
INSERT INTO `Complete` VALUES
(2,1,1),
(2,4,1),
(4,2,1);
/*!40000 ALTER TABLE `Complete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_Account`
--

DROP TABLE IF EXISTS `t_Account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_Account` (
  `idAccount` int(11) NOT NULL AUTO_INCREMENT,
  `accEmail` varchar(320) NOT NULL,
  `accUsername` varchar(64) NOT NULL,
  `accPassword` varchar(64) NOT NULL,
  `accAge` tinyint(4) DEFAULT NULL,
  `accScore` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAccount`),
  UNIQUE KEY `accEmail` (`accEmail`),
  UNIQUE KEY `accUsername` (`accUsername`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_Account`
--

LOCK TABLES `t_Account` WRITE;
/*!40000 ALTER TABLE `t_Account` DISABLE KEYS */;
INSERT INTO `t_Account` VALUES
(1,'admin@admin.com','admin','$2y$10$BhcQrOOt1SwLpivcWtuiCeh1mcOZFzn..BuFjG8IIqoQFxn.HqI6y',1,NULL),
(2,'kita@kessoku.co.jp','Kita','$2y$10$eSAb5T1.Yc6svnMqqpoYNu83EkpH/2EKy3Kpi/1V/KOfL6uO6Zlmm',16,15),
(3,'bocchi@kessoku.co.jp','Bocchi','$2y$10$ICU26MNnqoOBZ1gLN0aszeyz6CQ8ZoPbhKp2LZGjU12Tafo4tlc2C',16,NULL),
(4,'nijika@kessoku.co.jp','Dorito','$2y$10$3Y40WyXYO/.2E5JOXFsBg.u0IjuPtxzXoV.t1bK.3PcVsk9uFsCFO',16,8),
(5,'ryou@kessoku.co.jp','Ryou','$2y$10$ih/9N9ZsHGR3cUMu.uulOO0E9j9WR5i8MHzzDBvxeGSeBA/K6/tzu',17,NULL),
(6,'seika@starry.co.jp','Seika','$2y$10$SnkHqxx.iSeWI6qzicqvuek63splsjyn0LgJdSt5L9OYYhVPU5C.a',29,NULL),
(7,'pa@starry.co.jp','PA','$2y$10$tfu4wJBS9FkAy14.IkZhmesr6Znaef.S/8nvr3K.XTO58McDBmwz6',24,NULL),
(8,'kikuri@sickhack.co.jp','Kikuri','$2y$10$2f.7oKou1MPseOeVj5iSEupOtXGHAhxdnBqcxNVkGLbrSChLrbDL6',25,NULL);
/*!40000 ALTER TABLE `t_Account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_Task`
--

DROP TABLE IF EXISTS `t_Task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_Task` (
  `idTask` int(11) NOT NULL AUTO_INCREMENT,
  `tasName` varchar(256) NOT NULL,
  `tasDescription` varchar(1024) NOT NULL,
  `tasScore` smallint(6) NOT NULL,
  `tasState` tinyint(1) NOT NULL,
  PRIMARY KEY (`idTask`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_Task`
--

LOCK TABLES `t_Task` WRITE;
/*!40000 ALTER TABLE `t_Task` DISABLE KEYS */;
INSERT INTO `t_Task` VALUES
(1,'Sac réutilisable','Utiliser un sac réutilisable pour faire ses courses.',5,0),
(2,'Tri','Jeter ses déchets en n\'oubliant pas de correctement trier les déchets.',8,0),
(3,'Chauffage','Baisser le chauffage / diminuer la climatisation.',10,0),
(4,'Marcher','Se déplacer à pieds ou en vélo pour les trajets courts.',10,0),
(5,'Nettoyage','Participer à une opération de nettoyage.',20,0);
/*!40000 ALTER TABLE `t_Task` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2024-06-24 15:24:18
