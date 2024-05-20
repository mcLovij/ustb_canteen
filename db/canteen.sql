-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: canteen
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcement` (
  `staffUserName` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  KEY `fk_staffUserName` (`staffUserName`),
  CONSTRAINT `fk_staffUserName` FOREIGN KEY (`staffUserName`) REFERENCES `staff` (`staffUserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement`
--

LOCK TABLES `announcement` WRITE;
/*!40000 ALTER TABLE `announcement` DISABLE KEYS */;
INSERT INTO `announcement` VALUES ('staff1','Office Closed on Friday','The office will be closed this Friday for maintenance.'),('staff1','New Policy Update','Please review the new policy updates that have been emailed to all staff.'),('staff2','Staff Meeting','There will be a mandatory staff meeting on Monday at 10 AM in the conference room.');
/*!40000 ALTER TABLE `announcement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `food_list`
--

DROP TABLE IF EXISTS `food_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_list` (
  `foodId` int NOT NULL AUTO_INCREMENT,
  `foodName` varchar(255) NOT NULL,
  `foodDetail` text,
  `foodImage` varchar(255) DEFAULT NULL,
  `canteenId` int DEFAULT NULL,
  `floor` int DEFAULT NULL,
  `foodRate` float DEFAULT NULL,
  `foodPrice` decimal(10,2) NOT NULL,
  `bannerImg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`foodId`),
  KEY `canteenId` (`canteenId`),
  CONSTRAINT `food_list_ibfk_1` FOREIGN KEY (`canteenId`) REFERENCES `location` (`canteenId`),
  CONSTRAINT `food_list_chk_1` CHECK (((`foodRate` >= 0) and (`foodRate` <= 5)))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_list`
--

LOCK TABLES `food_list` WRITE;
/*!40000 ALTER TABLE `food_list` DISABLE KEYS */;
INSERT INTO `food_list` VALUES (1,'Pizza','Classic Italian pasta dish with creamy sauce and bacon.','img/food/pizza.jpg',1,1,4.5,12.50,'img/banner/pizza.jpg'),(2,'魔饭台式卤肉饭','魔饭台式卤肉饭魔饭台式卤肉饭魔饭台式卤肉饭','img/food/mofantaishi.png',2,2,4.9,9.90,'img.banner/mofantaishi.png'),(3,'burger','burgerrrrrrrrrrrr','img/food/burger.jpg',1,2,3,6.50,'img/banner/burger.jpg');
/*!40000 ALTER TABLE `food_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `location` (
  `canteenId` int NOT NULL AUTO_INCREMENT,
  `canteenName` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`canteenId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,'万秀园'),(2,'洪博园');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_history`
--

DROP TABLE IF EXISTS `order_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_history` (
  `historyId` int NOT NULL AUTO_INCREMENT,
  `orderId` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `orderTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `totalItem` int NOT NULL,
  PRIMARY KEY (`historyId`),
  KEY `orderId` (`orderId`),
  CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`orderId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_history`
--

LOCK TABLES `order_history` WRITE;
/*!40000 ALTER TABLE `order_history` DISABLE KEYS */;
INSERT INTO `order_history` VALUES (1,47273774,28.90,'2024-05-20 03:11:52',2),(2,53584309,28.90,'2024-05-20 03:12:12',3),(3,12342685,28.90,'2024-05-20 09:58:40',2);
/*!40000 ALTER TABLE `order_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `orderId` int NOT NULL,
  `userName` varchar(255) NOT NULL,
  `foodId` int NOT NULL,
  `canteenId` int NOT NULL,
  `status` tinyint(1) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`orderId`,`foodId`),
  KEY `userName` (`userName`),
  KEY `foodId` (`foodId`),
  KEY `canteenId` (`canteenId`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`),
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`canteenId`) REFERENCES `location` (`canteenId`),
  CONSTRAINT `orders_chk_1` CHECK ((`status` in (0,1)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (12342685,'student1',2,2,0,1),(12342685,'student1',3,1,0,1),(47273774,'student1',1,1,0,1),(47273774,'student1',2,2,0,1),(53584309,'student1',1,1,0,1),(53584309,'student1',2,2,0,1),(53584309,'student1',3,1,0,1);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `paymentId` int NOT NULL AUTO_INCREMENT,
  `paymentType` varchar(255) NOT NULL,
  PRIMARY KEY (`paymentId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (1,'微信'),(2,'支付宝'),(3,'银行卡');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recommendation`
--

DROP TABLE IF EXISTS `recommendation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recommendation` (
  `foodId` int NOT NULL,
  PRIMARY KEY (`foodId`),
  CONSTRAINT `recommendation_ibfk_1` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recommendation`
--

LOCK TABLES `recommendation` WRITE;
/*!40000 ALTER TABLE `recommendation` DISABLE KEYS */;
INSERT INTO `recommendation` VALUES (1),(3);
/*!40000 ALTER TABLE `recommendation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shopping_cart`
--

DROP TABLE IF EXISTS `shopping_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopping_cart` (
  `chooseId` int NOT NULL AUTO_INCREMENT,
  `userName` varchar(255) NOT NULL,
  `foodId` int NOT NULL,
  `canteenId` int NOT NULL,
  `chooseTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` int NOT NULL,
  PRIMARY KEY (`chooseId`),
  KEY `userName` (`userName`),
  KEY `foodId` (`foodId`),
  KEY `canteenId` (`canteenId`),
  CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`),
  CONSTRAINT `shopping_cart_ibfk_2` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`),
  CONSTRAINT `shopping_cart_ibfk_3` FOREIGN KEY (`canteenId`) REFERENCES `location` (`canteenId`)
) ENGINE=InnoDB AUTO_INCREMENT=93657106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopping_cart`
--

LOCK TABLES `shopping_cart` WRITE;
/*!40000 ALTER TABLE `shopping_cart` DISABLE KEYS */;
INSERT INTO `shopping_cart` VALUES (28361777,'student1',2,2,'2024-05-20 12:19:28',1);
/*!40000 ALTER TABLE `shopping_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `staffUserName` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`staffUserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES ('staff1','pw1'),('staff2','pw2');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_detail`
--

DROP TABLE IF EXISTS `staff_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_detail` (
  `staffUserName` varchar(50) DEFAULT NULL,
  `staffName` varchar(100) NOT NULL,
  `canteenId` int DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  KEY `staffUserName` (`staffUserName`),
  KEY `canteenId` (`canteenId`),
  CONSTRAINT `staff_detail_ibfk_1` FOREIGN KEY (`staffUserName`) REFERENCES `staff` (`staffUserName`),
  CONSTRAINT `staff_detail_ibfk_2` FOREIGN KEY (`canteenId`) REFERENCES `location` (`canteenId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_detail`
--

LOCK TABLES `staff_detail` WRITE;
/*!40000 ALTER TABLE `staff_detail` DISABLE KEYS */;
INSERT INTO `staff_detail` VALUES ('staff1','STAFF1',1,'img/profile/simg1.png'),('staff2','STAFF2',2,'img/profile/simg1.png');
/*!40000 ALTER TABLE `staff_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_detail`
--

DROP TABLE IF EXISTS `student_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_detail` (
  `userName` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`userName`),
  CONSTRAINT `fk_student_detail_student` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_detail`
--

LOCK TABLES `student_detail` WRITE;
/*!40000 ALTER TABLE `student_detail` DISABLE KEYS */;
INSERT INTO `student_detail` VALUES ('student1','John Doe','img/profile/img1.png'),('student2','Jane Smith','img/profile/img2.png'),('student3','Mike Johnson','Profile for student 3');
/*!40000 ALTER TABLE `student_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_favorite`
--

DROP TABLE IF EXISTS `student_favorite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_favorite` (
  `userName` varchar(255) NOT NULL,
  `foodId` int NOT NULL,
  PRIMARY KEY (`userName`,`foodId`),
  KEY `foodId` (`foodId`),
  CONSTRAINT `student_favorite_ibfk_1` FOREIGN KEY (`userName`) REFERENCES `students` (`userName`),
  CONSTRAINT `student_favorite_ibfk_2` FOREIGN KEY (`foodId`) REFERENCES `food_list` (`foodId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_favorite`
--

LOCK TABLES `student_favorite` WRITE;
/*!40000 ALTER TABLE `student_favorite` DISABLE KEYS */;
INSERT INTO `student_favorite` VALUES ('student1',2);
/*!40000 ALTER TABLE `student_favorite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `userName` varchar(50) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES ('student1','password1'),('student2','password2'),('student3','password3');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-20 20:28:59
