-- MySQL dump 10.13  Distrib 8.0.43, for macos15 (arm64)
--
-- Host: 10.211.55.4    Database: restaurant_db
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.1

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'М\'ясо',1),(2,'Гарніри',1),(3,'Напої',1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dishes`
--

DROP TABLE IF EXISTS `dishes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dishes` (
  `dish_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `dish_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint NOT NULL DEFAULT '1',
  `category` varchar(50) DEFAULT NULL,
  `description` text,
  `is_recommended` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`dish_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `dishes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dishes`
--

LOCK TABLES `dishes` WRITE;
/*!40000 ALTER TABLE `dishes` DISABLE KEYS */;
INSERT INTO `dishes` VALUES (1,1,'Стейк Рібай (Prime)',750.00,1,'М’ясо','Соковитий стейк із преміальної яловичини сухого дозрівання, обсмажений із розмарином.',0),(2,1,'Філе-міньйон',680.00,1,'М’ясо','Ніжне філе лосося, приготоване на грилі з ароматними травами та лимоном.',1),(3,1,'Стейк Ті-Боун',820.00,1,'М’ясо','Традиційний салат із соковитим курячим філе, хрусткими сухариками та соусом Цезар.',1),(4,1,'Стейк Стріплойн',590.00,1,'М’ясо','Освіжаючий класичний напій із натуральним лимонним соком та м’ятою.',0),(5,1,'Каре ягняти з травами',590.00,1,'М’ясо','Ніжні реберця молочного ягняти, мариновані у розмарині та чебреці',1),(6,1,'Телячий бріскет BBQ',420.00,1,'М’ясо','Теляча грудинка, що готувалася 12 годин у смокері на вишневих дровах',0),(7,1,'Свинячі ребра в медовій глазурі',390.00,1,'М’ясо','Запечені ребра з пікантним соусом BBQ та димним ароматом',1),(8,1,'Стейк з качиної грудки',410.00,1,'М’ясо','Смажена качина грудка з ягідним кюлі та карамелізованим яблуком',0),(9,2,'Картопля фрі з пармезаном',95.00,1,'Гарніри','Золотиста картопля з додаванням пармезану та трюфельної олії',0),(10,2,'Овочі гриль',150.00,1,'Гарніри','Печериці, болгарський перець, цукіні та кукурудза, підсмажені на вогні',0),(11,2,'Картопляне пюре з васабі',85.00,1,'Гарніри','Ніжне пюре з вершками та легким пікантним відтінком',0),(12,2,'Спаржа на грилі',180.00,1,'Гарніри','Молода зелена спаржа з лимонною заправкою',0),(13,2,'Рис Жасмин з овочами',90.00,1,'Гарніри','Ароматний рис з додаванням дрібно нарізаних овочів та соєвого соусу',0),(14,3,'Лимонад Класичний',75.00,1,'Напої','Освіжаючий напій на основі натурального лимонного соку та м’яти',0),(15,3,'Лимонад Базилік-Полуниця',85.00,1,'Напої','Авторський лимонад з яскравим смаком літа',0),(16,3,'Морс Журавлиний',45.00,1,'Напої','Традиційний домашній напій із лісових ягід',0),(17,3,'Кава Лате',65.00,1,'Напої','Класична кава з великою кількістю ніжного збитого молока',0),(18,3,'Чай Гірські Трави',80.00,1,'Напої','Натуральний збір карпатських трав: чебрець, м’ята, звіробій',0),(19,3,'Вино Мерло (келих)',120.00,1,'Напої','Червоне сухе вино, що ідеально підкреслює смак червоного м’яса',0),(20,3,'Крафтове пиво (Dark)',95.00,1,'Напої','Темне пиво з шоколадно-кавовими нотками',0);
/*!40000 ALTER TABLE `dishes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `position` enum('manager','waiter','cook') NOT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Олена','Коваленко','admin@gmail.com','380685746343','$2y$10$G4wlOgY/Ywk9WV2nbwakx.yU34IvfSejJ9tFEuLZF7B4ssGuM0N4S','manager'),(2,'Максим','Бойко','chef@gmail.com','380786543676','$2y$10$xKHd5UE.UcmCvaNcG2thV./VP/QVy4NMx8lEv.p/k5wVRV3W6aoem','cook'),(3,'Анна','Лисенко','waiter@gmail.com','380684546876','$2y$10$PNEKjL66PUuG4KaAssPh.ePU19fssIxJmYWHlspu8yqeTuaTU7kEW','waiter');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `detail_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `dish_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `order_id` (`order_id`),
  KEY `dish_id` (`dish_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`dish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (5,6,1,1,750.00),(6,6,9,1,95.00),(7,7,4,1,590.00),(8,7,10,1,150.00),(9,8,9,2,95.00),(10,8,4,1,590.00),(11,8,3,1,820.00),(12,8,14,2,75.00),(13,9,9,1,95.00),(14,9,12,2,180.00),(15,9,7,1,390.00),(16,9,3,1,820.00),(17,10,1,2,750.00),(18,10,19,2,120.00),(19,15,1,1,500.00),(20,15,3,1,200.00),(21,15,4,1,50.00),(22,16,2,1,410.00),(23,16,4,1,50.00),(24,17,13,1,90.00),(25,17,12,1,180.00),(26,17,16,2,45.00),(27,18,8,1,410.00),(28,18,18,2,80.00),(29,19,9,2,95.00),(30,19,20,1,95.00),(31,19,15,1,85.00),(32,20,12,1,180.00),(33,20,3,1,820.00),(34,20,14,2,75.00),(35,21,13,1,90.00),(36,21,1,2,750.00),(37,21,15,1,85.00),(38,22,5,2,590.00),(39,22,1,1,750.00),(40,23,9,3,95.00),(41,23,10,1,150.00),(42,24,9,1,95.00),(43,24,12,1,180.00),(44,24,8,1,410.00),(45,24,19,2,120.00);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'new',
  PRIMARY KEY (`order_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (6,NULL,'2026-05-07 16:38:52',845.00,'completed'),(7,NULL,'2026-05-13 16:38:52',745.00,'completed'),(8,NULL,'2026-05-14 20:07:19',1750.00,'completed'),(9,NULL,'2026-05-14 21:12:22',1665.00,'completed'),(10,NULL,'2026-05-14 21:32:38',1740.00,'completed'),(15,6,'2026-04-10 14:30:00',750.00,'completed'),(16,6,'2026-05-12 18:45:00',460.00,'completed'),(17,NULL,'2026-05-20 08:27:36',360.00,'completed'),(18,NULL,'2026-05-20 08:46:16',570.00,'completed'),(19,NULL,'2026-05-20 22:47:57',370.00,'completed'),(20,NULL,'2026-05-20 23:08:15',1150.00,'completed'),(21,NULL,'2026-05-20 23:09:13',1675.00,'completed'),(22,NULL,'2026-05-20 23:29:55',1930.00,'completed'),(23,NULL,'2026-05-21 08:48:57',435.00,'completed'),(24,NULL,'2026-05-24 13:16:26',925.00,'completed');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `city` varchar(100) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `house_number` varchar(10) DEFAULT NULL,
  `apartment_number` varchar(10) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (5,6,2,845.00,'2026-05-07 16:38:52','Львів',NULL,NULL,NULL,'card'),(6,7,2,745.00,'2026-05-13 16:38:52','Львів',NULL,NULL,NULL,'card'),(7,8,4,1750.00,'2026-05-14 20:07:19','Львів',NULL,NULL,NULL,'cash'),(8,9,8,1665.00,'2026-05-14 21:12:22','Львів',NULL,NULL,NULL,'card'),(9,10,9,1740.00,'2026-05-14 21:32:38','Львів',NULL,NULL,NULL,'cash'),(10,15,1,750.00,'2026-04-10 14:35:00','Львів','вул. Франка','12',NULL,NULL),(11,16,2,460.00,'2026-05-12 18:50:00','Львів','вул. Шевченка','45',NULL,NULL),(12,17,15,360.00,'2026-05-20 08:27:36','Львів',NULL,NULL,NULL,'cash'),(13,18,16,570.00,'2026-05-20 08:46:16','Львів',NULL,NULL,NULL,'cash'),(14,19,9,370.00,'2026-05-20 22:47:57','Львів',NULL,NULL,NULL,'cash'),(15,20,16,1150.00,'2026-05-20 23:08:15','Львів',NULL,NULL,NULL,'card'),(16,21,8,1675.00,'2026-05-20 23:09:13','Львів',NULL,NULL,NULL,'card'),(17,22,18,1930.00,'2026-05-20 23:29:55','Львів',NULL,NULL,NULL,'card'),(18,23,16,435.00,'2026-05-21 08:48:57','Львів',NULL,NULL,NULL,'cash'),(19,24,19,925.00,'2026-05-24 13:16:26','Львів',NULL,NULL,NULL,'card');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `guest_phone` varchar(20) DEFAULT NULL,
  `reservation_date` datetime NOT NULL,
  `guests_count` int NOT NULL,
  `table_number` int DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  PRIMARY KEY (`reservation_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (1,NULL,'Таня','380676068565','2026-01-15 16:30:00',4,NULL,'confirmed'),(2,NULL,'Андрій Бойко','380676068565','2026-05-15 15:40:00',4,NULL,'confirmed'),(3,NULL,'Степан','380976457832','2026-05-16 17:30:00',4,NULL,'pending'),(4,NULL,'Степан','380976457832','2026-05-16 17:30:00',4,NULL,'confirmed'),(5,NULL,'Богдан','380584683944','2026-05-15 17:30:00',2,NULL,'confirmed'),(6,NULL,'Катерина','380685647318','2026-05-22 11:00:00',3,NULL,'pending'),(7,NULL,'Петро','380694637483','2026-05-18 15:15:00',1,NULL,'pending'),(8,NULL,'Євген','380685947328','2026-05-16 21:20:00',5,NULL,'pending'),(9,NULL,'Олександр','380695847521','2026-05-20 20:00:00',4,NULL,'pending'),(10,NULL,'Міша','380695754364','2026-07-27 14:30:00',15,NULL,'confirmed');
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `author_name` varchar(255) DEFAULT 'Анонім',
  `review_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,NULL,'Анонім','Все чудово! Мені було смачно) Але багато людей в неділю','2026-05-14 18:27:37'),(2,8,'Володя','Доставка швидка! Все дуже смачне! Особливо мʼясо, рекомендую.','2026-05-14 18:28:54'),(3,2,'Андрій','Улюблений ресторан!! Обожнюю стейк Рібай, всім раджу спробувати!!!!','2026-05-14 18:37:07');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `loyalty_points` int DEFAULT '0',
  `city` varchar(100) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `house_number` varchar(10) DEFAULT NULL,
  `apartment_number` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Тетяна','Дідух','taniaadidukh@gmail.com','380676068565','$2y$10$51VIv1GJdgGWgwqcdnpbv.nts2DUR8a1/srS9Wn/XZBwBWsu28G6O',0,NULL,NULL,NULL,NULL),(2,'Андрій','Бойко','andriyb@gmail.com','380695746575','$2y$10$fVucmwf5fzOrqkjr5XCGLOZyyexUteIIQKsQDwuHDVyn.IP2W95t6',159,'Львів','Плужника','2','301'),(3,'Галина','Бець','galiab@gmail.com','380469864987','$2y$10$Brb8Gs3WfEU8uKy9WTfUJeVhSw9nXa4ZyKCyQVNVPZeyhsfD75roK',0,'Львів','Суботівська','5','3'),(4,'Богдан','Циганюк','bogdant@gmail.com','380584850987','$2y$10$C27ESHYP7Fh8GUcLNEoI6.5hMbohEn5s16rpIaDKmdG9PUQ0FGoZ.',175,'Львів','Івана Мазепи','2','25'),(8,'Володя','Сторожинський','vovas@gmail.com','380684274019','$2y$10$RxTbxMEVD7YENSyR/o7h.epWM0Mb0jkCrOR0.dk4zwNVbp7TbLg6y',334,'Львів','Зелена','5',''),(9,'Ольга','Столярчук','olgas@gmail.com','380574856484','$2y$10$8SsswTT1PS6bLes8/P3UZexgNUBwb.gAGbXgX7HJXWEJO0KDAPm7O',211,'Львів','Під Дубом','29',''),(10,'Олена','Коваленко','olena@gmail.com','0981112233','hash123',150,NULL,NULL,NULL,NULL),(11,'Андрій','Шевченко','andriy@gmail.com','0972223344','hash123',46,NULL,NULL,NULL,NULL),(12,'Максим','Ткачук','makss@rest.com','0501112233','hash123',0,NULL,NULL,NULL,NULL),(13,'Василь','Поваренко','vasilp@rest.com','0502223344','hash123',0,NULL,NULL,NULL,NULL),(14,'Ірина','Дідух','irad@rest.com','0503334455','hash123',0,NULL,NULL,NULL,NULL),(15,'Степан','Димид','stepand@ukr.net','380976062345','$2y$10$8nGflKbWDEbgFkXF4GwwxOV2v/nvhtXtG0Fx4spzT/pNVKDBuFvIi',36,'Львів','Зелена','3','13'),(16,'Богдан','Депутат','bogdand@gmail.com','380657674373','$2y$10$xYlE.8gMmSssJ5omIuNbhOVo6TqyCEuTFtcQ2nCAO.8oxC.QpKUa6',215,'Львів','Мазепи','3',''),(17,'Катерина','Пятницька ','katyap@ukr.net','380976856432','$2y$10$wYzt8PAGyrGZDMkvp2K/JOCZWGlcxQFUfg1tbmbxtasaq7B0llOi6',0,'Львів','Випасова','6','4'),(18,'Микита','Турчин','mukutat@gmail.com','380685432156','$2y$10$qSLcEeubO/WgnH3O7gFVdellwSbayCiT5CH62UWC8MzenrqAEvkwm',193,'Львів','Варшавська','5','7'),(19,'Ірина','Шевчук','shevchyki@gmail.com','380685432757','$2y$10$e1qWQHMwxSfpHuJcwXvWUuil1culrUv65QYZV2uqeXZvdtIrE.Ihi',92,'Львів','Коперника','2','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-24 14:24:42
