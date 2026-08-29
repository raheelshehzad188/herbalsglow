/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.8-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: u662960912_shopii_rdp
-- ------------------------------------------------------
-- Server version	11.8.8-MariaDB-log

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'store_admin',
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES
(1,'Super Admin','admin@herbalsglow.test','$2y$10$IAy5oWs1HcuHUkm8BTaUfeW3J7h1HvbKGKnSBB22APGVgUNBVGUxq','super_admin',NULL,'active','2026-08-24 16:30:02','2026-08-24 16:30:02'),
(2,'Classic Store Admin','classic@herbalsglow.test','$2y$10$/tL2ZT898VPETZfzmGdPr.8EOKMfCUmF26vyseh0HpWYFAhg7rXW2','store_admin',2,'active','2026-08-25 03:39:45','2026-08-25 03:39:45'),
(3,'Wellness Store Admin','wellness@herbalsglow.test','$2y$10$HgfIitvGdF3fGYDSZsdEXur0IiNwLfXHck4nxlRRCpBcNDmljlrb.','store_admin',3,'active','2026-08-25 03:39:45','2026-08-25 03:39:45'),
(4,'ShopUS Store Admin','shopus@herbalsglow.test','$2y$10$JsqTLbXQDQlEvZocptbUru1cqvHdOCPdulrTcRWh67BKGlrGE8y3W','store_admin',4,'active','2026-08-25 03:39:45','2026-08-25 03:39:45');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `blog_cum`
--

DROP TABLE IF EXISTS `blog_cum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_cum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_cum`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blog_cum` WRITE;
/*!40000 ALTER TABLE `blog_cum` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_cum` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `boxes`
--

DROP TABLE IF EXISTS `boxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `boxes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) DEFAULT NULL,
  `txt` text DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boxes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `boxes` WRITE;
/*!40000 ALTER TABLE `boxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `boxes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `s_schema` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brands_store_id_index` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES
(1,4,'Brand 1','shopus-brand-1',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-1.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(2,4,'Brand 2','shopus-brand-2',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-2.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(3,4,'Brand 3','shopus-brand-3',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-3.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(4,4,'Brand 4','shopus-brand-4',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-4.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(5,4,'Brand 5','shopus-brand-5',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-5.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(6,4,'Brand 6','shopus-brand-6',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-6.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(7,4,'Brand 7','shopus-brand-7',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-7.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(8,4,'Brand 8','shopus-brand-8',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-8.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(9,4,'Brand 9','shopus-brand-9',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-9.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(10,4,'Brand 10','shopus-brand-10',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-10.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(11,4,'Brand 11','shopus-brand-11',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-11.webp','2026-08-25 11:11:42','2026-08-25 12:03:34'),
(12,4,'Brand 12','shopus-brand-12',1,NULL,NULL,NULL,NULL,'theme4/assets/images/homepage-one/brand-img-12.webp','2026-08-25 11:11:42','2026-08-25 12:03:34');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(12,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES
(1,'127.0.0.1','GfBDaRukvYTYj22ueQBn1xkEypwzwT3T830ZT1k3',NULL,35,1,699.00,699.00,'2026-08-28 13:19:37','2026-08-28 13:19:37');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `image` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `s_schema` text DEFAULT NULL,
  `show_on_home` tinyint(4) DEFAULT 0,
  `sort` int(11) DEFAULT 0,
  `home_sort` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_store_id_index` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,1,'Herbal Care','herbal-care',1,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(2,2,'Herbal Care','herbal-care-2',1,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(3,3,'Herbal Care','herbal-care-3',1,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(4,4,'Herbal Care','herbal-care-4',1,NULL,NULL,NULL,NULL,NULL,NULL,0,1,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(5,4,'Dresses','dresses-shopus',1,'theme4/assets/images/homepage-one/category-img/dresses.webp',NULL,NULL,NULL,NULL,NULL,1,1,1,'2026-08-25 11:11:41','2026-08-25 12:03:34'),
(6,4,'Leather Bags','leather-bags-shopus',1,'theme4/assets/images/homepage-one/category-img/bags.webp',NULL,NULL,NULL,NULL,NULL,1,2,2,'2026-08-25 11:11:41','2026-08-25 12:03:34'),
(7,4,'Sweaters','sweaters-shopus',1,'theme4/assets/images/homepage-one/category-img/sweaters.webp',NULL,NULL,NULL,NULL,NULL,1,3,3,'2026-08-25 11:11:41','2026-08-25 12:03:34'),
(8,4,'Boots','boots-shopus',1,'theme4/assets/images/homepage-one/category-img/shoes.webp',NULL,NULL,NULL,NULL,NULL,1,4,4,'2026-08-25 11:11:41','2026-08-25 12:03:34'),
(9,4,'Gift for Him','gift-for-him-shopus',1,'theme4/assets/images/homepage-one/category-img/gift.webp',NULL,NULL,NULL,NULL,NULL,1,5,5,'2026-08-25 11:11:41','2026-08-25 12:03:34'),
(10,4,'Sneakers','sneakers-shopus',1,'theme4/assets/images/homepage-one/category-img/sneakers.webp',NULL,NULL,NULL,NULL,NULL,1,6,6,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(11,4,'Watch','watch-shopus',1,'theme4/assets/images/homepage-one/category-img/watch.webp',NULL,NULL,NULL,NULL,NULL,1,7,7,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(12,4,'Gold Rings','gold-rings-shopus',1,'theme4/assets/images/homepage-one/category-img/ring.webp',NULL,NULL,NULL,NULL,NULL,1,8,8,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(13,4,'Cap','cap-shopus',1,'theme4/assets/images/homepage-one/category-img/cap.webp',NULL,NULL,NULL,NULL,NULL,1,9,9,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(14,4,'Sunglass','sunglass-shopus',1,'theme4/assets/images/homepage-one/category-img/glass.webp',NULL,NULL,NULL,NULL,NULL,1,10,10,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(15,4,'Baby Shop','baby-shop-shopus',1,'theme4/assets/images/homepage-one/category-img/baby.webp',NULL,NULL,NULL,NULL,NULL,1,11,11,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(16,4,'Women Shoes','women-shoes-shopus',1,'theme4/assets/images/homepage-one/category-img/shoes.webp',NULL,NULL,NULL,NULL,NULL,1,12,12,'2026-08-25 11:15:06','2026-08-25 12:03:34'),
(17,4,'Makeup Box','makeup-box-shopus',1,'theme4/assets/images/homepage-one/category-img/gift.webp',NULL,NULL,NULL,NULL,NULL,1,13,13,'2026-08-25 11:15:06','2026-08-25 12:03:34'),
(18,4,'Floral Dresses','floral-dresses-shopus',1,'theme4/assets/images/homepage-one/category-img/dresses.webp',NULL,NULL,NULL,NULL,NULL,1,14,14,'2026-08-25 11:15:06','2026-08-25 12:03:34');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `categories_to_meta`
--

DROP TABLE IF EXISTS `categories_to_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories_to_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `cid` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_to_meta`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categories_to_meta` WRITE;
/*!40000 ALTER TABLE `categories_to_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories_to_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` text DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `colors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `discount` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `custom_order`
--

DROP TABLE IF EXISTS `custom_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_order` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT 0.00,
  `mobile_number` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `address` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_order_store_id_index` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_order`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `custom_order` WRITE;
/*!40000 ALTER TABLE `custom_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `custom_order` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `home_cats`
--

DROP TABLE IF EXISTS `home_cats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `home_cats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `sort` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home_cats`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `home_cats` WRITE;
/*!40000 ALTER TABLE `home_cats` DISABLE KEYS */;
/*!40000 ALTER TABLE `home_cats` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `learn_setting`
--

DROP TABLE IF EXISTS `learn_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `learn_setting` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `learn_img_1` text DEFAULT NULL,
  `learn_img_2` text DEFAULT NULL,
  `learn_img_3` text DEFAULT NULL,
  `p_1` text DEFAULT NULL,
  `p2` text DEFAULT NULL,
  `p3` text DEFAULT NULL,
  `p4` text DEFAULT NULL,
  `p5` text DEFAULT NULL,
  `p6` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learn_setting`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `learn_setting` WRITE;
/*!40000 ALTER TABLE `learn_setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `learn_setting` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2019_12_14_000001_create_personal_access_tokens_table',1),
(5,'2025_01_15_120000_add_homepage_image_details_to_settings_table',1),
(6,'2025_01_20_000000_add_sort_to_categories_table',1),
(7,'2025_01_21_000000_add_youtube_to_settings_table',1),
(8,'2025_10_07_131352_add_show_on_home_to_categories_table',1),
(9,'2025_10_07_203735_create_sessions_table',1),
(10,'2025_10_10_132503_add_dynamic_links_to_settings_table',1),
(11,'2026_07_30_000000_add_active_theme_to_settings_table',1),
(12,'2026_07_30_120000_add_theme3_fields_to_sliders_table',1),
(13,'2026_07_30_200800_add_payment_method_to_orders_table',1),
(14,'2026_08_13_000001_create_multi_store_platform_tables',1),
(15,'2026_08_13_000002_add_store_id_to_catalog_and_orders',1),
(16,'2026_08_24_100000_add_oauth_fields_to_integrations',2),
(17,'2026_08_25_000000_add_home_layout_to_settings_table',3),
(18,'2026_08_25_120000_create_theme_customizations_table',4),
(19,'2026_08_25_200000_create_shopify_import_tables',5),
(20,'2026_08_25_230000_add_is_read_to_orders_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT 0.00,
  `product_detail` longtext DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `is_read` tinyint(4) NOT NULL DEFAULT 0,
  `notify` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_store_id_index` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `menu_type` varchar(100) DEFAULT NULL,
  `position` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `content` longtext DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` text DEFAULT NULL,
  `section` text DEFAULT NULL,
  `page_image_status` tinyint(4) DEFAULT 0,
  `page_image` text DEFAULT NULL,
  `parent` bigint(20) unsigned DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES
(1,1,'Terms & Conditions','terms-conditions','footer_policies',1,1,'<p>Terms &amp; Conditions</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(2,1,'Privacy Policy','privacy-policy','footer_policies',2,1,'<p>Privacy Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(3,1,'Return & Refund Policy','returns-exchange','footer_policies',3,1,'<p>Return &amp; Refund Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(4,1,'Shipping & Delivery Policy','shipping','footer_policies',4,1,'<p>Shipping &amp; Delivery Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(5,1,'Contact Us','contact','footer_help',1,1,'<p>Contact Us</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(6,1,'FAQs','faqs','footer_help',2,1,'<p>FAQs</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(7,1,'Track Your Order','order-tracking','footer_help',3,1,'<p>Track Your Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(8,1,'How to Order','how-to-order','footer_help',4,1,'<p>How to Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(9,1,'About Us','about','footer_information',1,1,'<p>About Herbals Glow</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(10,1,'Authenticity Guarantee','authenticity-guarantee','footer_information',2,1,'<p>Authenticity Guarantee</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(11,1,'Payment Methods','payment-method','footer_information',3,1,'<p>Payment Methods</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(12,2,'Terms & Conditions','terms-conditions-2','footer_policies',1,1,'<p>Terms &amp; Conditions</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(13,2,'Privacy Policy','privacy-policy-2','footer_policies',2,1,'<p>Privacy Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(14,2,'Return & Refund Policy','returns-exchange-2','footer_policies',3,1,'<p>Return &amp; Refund Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(15,2,'Shipping & Delivery Policy','shipping-2','footer_policies',4,1,'<p>Shipping &amp; Delivery Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(16,2,'Contact Us','contact-2','footer_help',1,1,'<p>Contact Us</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(17,2,'FAQs','faqs-2','footer_help',2,1,'<p>FAQs</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(18,2,'Track Your Order','order-tracking-2','footer_help',3,1,'<p>Track Your Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(19,2,'How to Order','how-to-order-2','footer_help',4,1,'<p>How to Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(20,2,'About Us','about-2','footer_information',1,1,'<p>About Herbals Glow</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(21,2,'Authenticity Guarantee','authenticity-guarantee-2','footer_information',2,1,'<p>Authenticity Guarantee</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(22,2,'Payment Methods','payment-method-2','footer_information',3,1,'<p>Payment Methods</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(23,3,'Terms & Conditions','terms-conditions-3','footer_policies',1,1,'<p>Terms &amp; Conditions</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(24,3,'Privacy Policy','privacy-policy-3','footer_policies',2,1,'<p>Privacy Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(25,3,'Return & Refund Policy','returns-exchange-3','footer_policies',3,1,'<p>Return &amp; Refund Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(26,3,'Shipping & Delivery Policy','shipping-3','footer_policies',4,1,'<p>Shipping &amp; Delivery Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(27,3,'Contact Us','contact-3','footer_help',1,1,'<p>Contact Us</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(28,3,'FAQs','faqs-3','footer_help',2,1,'<p>FAQs</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(29,3,'Track Your Order','order-tracking-3','footer_help',3,1,'<p>Track Your Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(30,3,'How to Order','how-to-order-3','footer_help',4,1,'<p>How to Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(31,3,'About Us','about-3','footer_information',1,1,'<p>About Herbals Glow</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(32,3,'Authenticity Guarantee','authenticity-guarantee-3','footer_information',2,1,'<p>Authenticity Guarantee</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(33,3,'Payment Methods','payment-method-3','footer_information',3,1,'<p>Payment Methods</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(34,4,'Terms & Conditions','terms-conditions-4','footer_policies',1,1,'<p>Terms &amp; Conditions</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(35,4,'Privacy Policy','privacy-policy-4','footer_policies',2,1,'<p>Privacy Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(36,4,'Return & Refund Policy','returns-exchange-4','footer_policies',3,1,'<p>Return &amp; Refund Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(37,4,'Shipping & Delivery Policy','shipping-4','footer_policies',4,1,'<p>Shipping &amp; Delivery Policy</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(38,4,'Contact Us','contact-4','footer_help',1,1,'<p>Contact Us</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(39,4,'FAQs','faqs-4','footer_help',2,1,'<p>FAQs</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(40,4,'Track Your Order','order-tracking-4','footer_help',3,1,'<p>Track Your Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(41,4,'How to Order','how-to-order-4','footer_help',4,1,'<p>How to Order</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(42,4,'About Us','about-4','footer_information',1,1,'<p>About Herbals Glow</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(43,4,'Authenticity Guarantee','authenticity-guarantee-4','footer_information',2,1,'<p>Authenticity Guarantee</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(44,4,'Payment Methods','payment-method-4','footer_information',3,1,'<p>Payment Methods</p>',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES
(1,'Cash on Delivery','Pay when you receive your order',1,'2026-08-24 16:30:02','2026-08-24 16:30:02');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pfaqs`
--

DROP TABLE IF EXISTS `pfaqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pfaqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `question` text DEFAULT NULL,
  `ans` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pfaqs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pfaqs` WRITE;
/*!40000 ALTER TABLE `pfaqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `pfaqs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `platform_oauth_apps`
--

DROP TABLE IF EXISTS `platform_oauth_apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `platform_oauth_apps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `provider` varchar(20) NOT NULL,
  `app_id` varchar(255) DEFAULT NULL,
  `app_secret` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform_oauth_apps_provider_unique` (`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_oauth_apps`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `platform_oauth_apps` WRITE;
/*!40000 ALTER TABLE `platform_oauth_apps` DISABLE KEYS */;
/*!40000 ALTER TABLE `platform_oauth_apps` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `post_categories`
--

DROP TABLE IF EXISTS `post_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `post_categories` WRITE;
/*!40000 ALTER TABLE `post_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES
(1,'It’s official! The iPhone 14 Series is on its way! Rumors turned out','iphone-14-series-shopus','<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu.</p><p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel.</p>','theme4/assets/images/homepage-one/about/blog-img-1.webp',1,'2026-08-25 12:03:34','2026-08-25 12:03:34'),
(2,'Must-Have WordPress Plugins for Ecommerce Websites in 2022','wordpress-plugins-ecommerce-2022-shopus','<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu.</p><p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel.</p>','theme4/assets/images/homepage-one/about/blog-img-2.webp',1,'2026-08-25 12:03:34','2026-08-25 12:03:34'),
(3,'15 Best WordPress Newspaper Themes to Look Out for in 2022','wordpress-newspaper-themes-2022-shopus','<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu.</p><p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel.</p>','theme4/assets/images/homepage-one/about/blog-img-3.webp',1,'2026-08-25 12:03:34','2026-08-25 12:03:34'),
(4,'6 Best WordPress E-commerce Plugins for Online Stores in 2022','wordpress-ecommerce-plugins-2022-shopus','<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu.</p><p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel.</p>','theme4/assets/images/homepage-one/about/blog-img-2.webp',1,'2026-08-25 12:03:34','2026-08-25 12:03:34'),
(5,'Top 10 Best Professional Ecommerce Blogging Platforms for 2022','professional-ecommerce-blogging-platforms-shopus','<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu.</p><p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel.</p>','theme4/assets/images/homepage-one/about/blog-img-3.webp',1,'2026-08-25 12:03:34','2026-08-25 12:03:34'),
(6,'Business-to-consumer Ecommerce that involves selling fight products','b2c-ecommerce-selling-products-shopus','<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu.</p><p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel.</p>','theme4/assets/images/homepage-one/about/blog-img-1.webp',1,'2026-08-25 12:03:34','2026-08-25 12:03:34');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `old_id` bigint(20) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `subcategory_id` bigint(20) unsigned DEFAULT NULL,
  `brand` bigint(20) unsigned DEFAULT NULL,
  `product_details` longtext DEFAULT NULL,
  `short_discriiption` text DEFAULT NULL,
  `add_info` text DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `selling_price` decimal(12,2) DEFAULT 0.00,
  `discount_price` decimal(12,2) DEFAULT 0.00,
  `shipping_price` decimal(12,2) DEFAULT 0.00,
  `product_quantity` int(11) DEFAULT 0,
  `size` varchar(255) DEFAULT NULL,
  `made_in` varchar(255) DEFAULT NULL,
  `ptype` varchar(255) DEFAULT NULL,
  `home_cats` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `image_one` text DEFAULT NULL,
  `image_two` text DEFAULT NULL,
  `image_three` text DEFAULT NULL,
  `gallary_images` text DEFAULT NULL,
  `main_slider` tinyint(4) DEFAULT 0,
  `hot_deal` tinyint(4) DEFAULT 0,
  `New_Arrival` tinyint(4) DEFAULT 0,
  `Featured` tinyint(4) DEFAULT 0,
  `best_rated` tinyint(4) DEFAULT 0,
  `mid_slider` tinyint(4) DEFAULT 0,
  `hot_new` tinyint(4) DEFAULT 0,
  `Sale` tinyint(4) DEFAULT 0,
  `view` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_store_id_index` (`store_id`),
  KEY `products_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(1,1,NULL,'Herbal Glow Sample','herbal-glow-sample',1,NULL,NULL,'<p>Sample product for local setup.</p>','Starter herbal product',NULL,NULL,NULL,NULL,1999.00,1499.00,0.00,50,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,0,0,1,0,0,0,1,2,'2026-08-24 16:30:15','2026-08-28 00:38:50'),
(2,2,NULL,'Herbal Glow Sample','herbal-glow-sample-2',2,NULL,NULL,'<p>Sample product for local setup.</p>','Starter herbal product',NULL,NULL,NULL,NULL,1999.00,1499.00,0.00,50,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,0,0,1,0,0,0,1,1,'2026-08-24 16:30:15','2026-08-25 03:35:48'),
(3,3,NULL,'Herbal Glow Sample','herbal-glow-sample-3',3,NULL,NULL,'<p>Sample product for local setup.</p>','Starter herbal product',NULL,NULL,NULL,NULL,1999.00,1499.00,0.00,50,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,0,0,1,0,0,0,1,1,'2026-08-24 16:30:15','2026-08-25 03:35:48'),
(4,4,NULL,'Herbal Glow Sample','herbal-glow-sample-4',4,NULL,NULL,'<p>Sample product for local setup.</p>','Starter herbal product',NULL,NULL,NULL,NULL,1999.00,1499.00,0.00,50,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0,0,0,1,0,0,0,1,2,'2026-08-24 16:30:15','2026-08-25 04:12:30'),
(5,4,NULL,'Rainbow Sequin Dress','rainbow-sequin-dress-shopus',5,NULL,NULL,'<p>Rainbow Sequin Dress — ShopUS demo product for Home 1.</p>','Rainbow Sequin Dress',NULL,NULL,NULL,NULL,1299.00,699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-1.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,20,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(6,4,NULL,'Feminine Wrap Blouse','feminine-wrap-blouse-shopus',5,NULL,NULL,'<p>Feminine Wrap Blouse — ShopUS demo product for Home 1.</p>','Feminine Wrap Blouse',NULL,NULL,NULL,NULL,999.00,699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-2.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,19,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(7,4,NULL,'Trendy Bucket Hat','trendy-bucket-hat-shopus',13,NULL,NULL,'<p>Trendy Bucket Hat — ShopUS demo product for Home 1.</p>','Trendy Bucket Hat',NULL,NULL,NULL,NULL,1899.00,1099.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-3.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,18,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(8,4,NULL,'Boho Maxi Dress','boho-maxi-dress-shopus',5,NULL,NULL,'<p>Boho Maxi Dress — ShopUS demo product for Home 1.</p>','Boho Maxi Dress',NULL,NULL,NULL,NULL,2099.00,1099.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-4.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,17,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(9,4,NULL,'Casual Denim Jacket','casual-denim-jacket-shopus',7,NULL,NULL,'<p>Casual Denim Jacket — ShopUS demo product for Home 1.</p>','Casual Denim Jacket',NULL,NULL,NULL,NULL,2099.00,1099.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-5.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,16,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(10,4,NULL,'Stylish Statement Earrings','stylish-statement-earrings-shopus',12,NULL,NULL,'<p>Stylish Statement Earrings — ShopUS demo product for Home 1.</p>','Stylish Statement Earrings',NULL,NULL,NULL,NULL,2099.00,999.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-6.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,15,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(11,4,NULL,'Leather Dress Shoes','leather-dress-shoes-shopus',8,NULL,NULL,'<p>Leather Dress Shoes — ShopUS demo product for Home 1.</p>','Leather Dress Shoes',NULL,NULL,NULL,NULL,1999.00,1899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-7.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,14,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(12,4,NULL,'Wool Peacoat','wool-peacoat-shopus',7,NULL,NULL,'<p>Wool Peacoat — ShopUS demo product for Home 1.</p>','Wool Peacoat',NULL,NULL,NULL,NULL,2599.00,1399.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-8.webp',NULL,NULL,NULL,0,0,1,1,0,0,0,1,13,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(13,4,NULL,'Classic Party Dress','classic-party-dress-shopus',5,NULL,NULL,'<p>Classic Party Dress — ShopUS demo product for Home 1.</p>','Classic Party Dress',NULL,NULL,NULL,NULL,2999.00,1699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-9.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,12,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(14,4,NULL,'Rainbow Dress','rainbow-dress-shopus',18,NULL,NULL,'<p>Rainbow Dress — ShopUS demo product for Home 1.</p>','Rainbow Dress',NULL,NULL,NULL,NULL,1299.00,699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-10.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,11,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(15,4,NULL,'Red Sequin Hat','red-sequin-hat-shopus',13,NULL,NULL,'<p>Red Sequin Hat — ShopUS demo product for Home 1.</p>','Red Sequin Hat',NULL,NULL,NULL,NULL,1399.00,799.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-11.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,0,10,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(16,4,NULL,'Blue Suit','blue-suit-shopus',7,NULL,NULL,'<p>Blue Suit — ShopUS demo product for Home 1.</p>','Blue Suit',NULL,NULL,NULL,NULL,1099.00,599.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-12.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,9,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(17,4,NULL,'Gradient Party Shirt','gradient-party-shirt-shopus',5,NULL,NULL,'<p>Gradient Party Shirt — ShopUS demo product for Home 1.</p>','Gradient Party Shirt',NULL,NULL,NULL,NULL,1999.00,1099.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-13.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,0,8,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(18,4,NULL,'Slim-Fit Shirt','slim-fit-shirt-shopus',5,NULL,NULL,'<p>Slim-Fit Shirt — ShopUS demo product for Home 1.</p>','Slim-Fit Shirt',NULL,NULL,NULL,NULL,1499.00,699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-5.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,7,'2026-08-25 11:11:42','2026-08-25 12:03:34'),
(19,4,NULL,'Half Sleeve Dress','half-sleeve-dress-shopus',5,NULL,NULL,'<p>Half Sleeve Dress — ShopUS demo product for Home 1.</p>','Half Sleeve Dress',NULL,NULL,NULL,NULL,1299.00,699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-9.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,6,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(20,4,NULL,'Feminine Wrap Coat','feminine-wrap-coat-shopus',7,NULL,NULL,'<p>Feminine Wrap Coat — ShopUS demo product for Home 1.</p>','Feminine Wrap Coat',NULL,NULL,NULL,NULL,1899.00,1099.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-10.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,5,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(21,4,NULL,'Black Suit','black-suit-shopus',7,NULL,NULL,'<p>Black Suit — ShopUS demo product for Home 1.</p>','Black Suit',NULL,NULL,NULL,NULL,1099.00,899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-2.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,4,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(22,4,NULL,'Rainbow Party Dress','rainbow-party-dress-shopus',18,NULL,NULL,'<p>Rainbow Party Dress — ShopUS demo product for Home 1.</p>','Rainbow Party Dress',NULL,NULL,NULL,NULL,1999.00,899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-4.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,3,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(23,4,NULL,'Rainbow Sequin Skart','rainbow-sequin-skart-shopus',5,NULL,NULL,'<p>Rainbow Sequin Skart — ShopUS demo product for Home 1.</p>','Rainbow Sequin Skart',NULL,NULL,NULL,NULL,1599.00,799.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-1.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,2,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(24,4,NULL,'Sequin Dress','sequin-dress-shopus',5,NULL,NULL,'<p>Sequin Dress — ShopUS demo product for Home 1.</p>','Sequin Dress',NULL,NULL,NULL,NULL,3099.00,1599.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-3.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,1,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(25,4,NULL,'Red Sequin Dress','red-sequin-dress-shopus',5,NULL,NULL,'<p>Red Sequin Dress — ShopUS demo product for Home 1.</p>','Red Sequin Dress',NULL,NULL,NULL,NULL,2099.00,1399.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-6.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,0,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(26,4,NULL,'White Hat','white-hat-shopus',13,NULL,NULL,'<p>White Hat — ShopUS demo product for Home 1.</p>','White Hat',NULL,NULL,NULL,NULL,2999.00,2699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-6.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,0,-1,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(27,4,NULL,'White Checked Shirt','white-checked-shirt-shopus',5,NULL,NULL,'<p>White Checked Shirt — ShopUS demo product for Home 1.</p>','White Checked Shirt',NULL,NULL,NULL,NULL,1999.00,1699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-5.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-2,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(28,4,NULL,'Flower Design Dress','flower-design-dress-shopus',18,NULL,NULL,'<p>Flower Design Dress — ShopUS demo product for Home 1.</p>','Flower Design Dress',NULL,NULL,NULL,NULL,1999.00,899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-1.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-3,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(29,4,NULL,'Stylish Earrings','stylish-earrings-shopus',12,NULL,NULL,'<p>Stylish Earrings — ShopUS demo product for Home 1.</p>','Stylish Earrings',NULL,NULL,NULL,NULL,1799.00,999.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-6.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-4,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(30,4,NULL,'Classic Design Skart','classic-design-skart-shopus',5,NULL,NULL,'<p>Classic Design Skart — ShopUS demo product for Home 1.</p>','Classic Design Skart',NULL,NULL,NULL,NULL,2000.00,0.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-1.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,0,-5,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(31,4,NULL,'Blue Party Dress','blue-party-dress-shopus',5,NULL,NULL,'<p>Blue Party Dress — ShopUS demo product for Home 1.</p>','Blue Party Dress',NULL,NULL,NULL,NULL,1500.00,0.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-3.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-6,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(32,4,NULL,'Classic Red Dress','classic-red-dress-shopus',5,NULL,NULL,'<p>Classic Red Dress — ShopUS demo product for Home 1.</p>','Classic Red Dress',NULL,NULL,NULL,NULL,1800.00,0.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-4.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-7,'2026-08-25 11:15:07','2026-08-25 12:03:34'),
(33,4,NULL,'City Leather Tote','city-leather-tote-shopus',6,NULL,NULL,'<p>City Leather Tote — ShopUS demo product for Home 1.</p>','City Leather Tote',NULL,NULL,NULL,NULL,2499.00,1899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-2.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-8,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(34,4,NULL,'Weekend Crossbody Bag','weekend-crossbody-bag-shopus',6,NULL,NULL,'<p>Weekend Crossbody Bag — ShopUS demo product for Home 1.</p>','Weekend Crossbody Bag',NULL,NULL,NULL,NULL,1899.00,1299.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-8.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-9,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(35,4,NULL,'School Canvas Bag','school-canvas-bag-shopus',6,NULL,NULL,'<p>School Canvas Bag — ShopUS demo product for Home 1.</p>','School Canvas Bag',NULL,NULL,NULL,NULL,999.00,699.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-10.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,0,-10,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(36,4,NULL,'Cable Knit Sweater','cable-knit-sweater-shopus',7,NULL,NULL,'<p>Cable Knit Sweater — ShopUS demo product for Home 1.</p>','Cable Knit Sweater',NULL,NULL,NULL,NULL,2299.00,1499.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-8.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-11,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(37,4,NULL,'Chunky Winter Boots','chunky-winter-boots-shopus',8,NULL,NULL,'<p>Chunky Winter Boots — ShopUS demo product for Home 1.</p>','Chunky Winter Boots',NULL,NULL,NULL,NULL,3499.00,2499.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-7.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-12,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(38,4,NULL,'Gift Hamper Set','gift-hamper-set-shopus',9,NULL,NULL,'<p>Gift Hamper Set — ShopUS demo product for Home 1.</p>','Gift Hamper Set',NULL,NULL,NULL,NULL,1599.00,999.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-6.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-13,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(39,4,NULL,'Classic White Sneakers','classic-white-sneakers-shopus',10,NULL,NULL,'<p>Classic White Sneakers — ShopUS demo product for Home 1.</p>','Classic White Sneakers',NULL,NULL,NULL,NULL,2799.00,1999.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-7.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-14,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(40,4,NULL,'Street Runner Sneakers','street-runner-sneakers-shopus',10,NULL,NULL,'<p>Street Runner Sneakers — ShopUS demo product for Home 1.</p>','Street Runner Sneakers',NULL,NULL,NULL,NULL,2199.00,1599.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-5.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-15,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(41,4,NULL,'Classic Analog Watch','classic-analog-watch-shopus',11,NULL,NULL,'<p>Classic Analog Watch — ShopUS demo product for Home 1.</p>','Classic Analog Watch',NULL,NULL,NULL,NULL,4599.00,3299.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-14.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-16,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(42,4,NULL,'Gold Band Ring','gold-band-ring-shopus',12,NULL,NULL,'<p>Gold Band Ring — ShopUS demo product for Home 1.</p>','Gold Band Ring',NULL,NULL,NULL,NULL,1299.00,899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-6.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,0,-17,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(43,4,NULL,'Summer Cap','summer-cap-shopus',13,NULL,NULL,'<p>Summer Cap — ShopUS demo product for Home 1.</p>','Summer Cap',NULL,NULL,NULL,NULL,799.00,499.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-3.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-18,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(44,4,NULL,'Aviator Sunglasses','aviator-sunglasses-shopus',14,NULL,NULL,'<p>Aviator Sunglasses — ShopUS demo product for Home 1.</p>','Aviator Sunglasses',NULL,NULL,NULL,NULL,1499.00,999.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-11.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-18,'2026-08-25 11:38:54','2026-08-28 13:20:09'),
(45,4,NULL,'Baby Soft Romper','baby-soft-romper-shopus',15,NULL,NULL,'<p>Baby Soft Romper — ShopUS demo product for Home 1.</p>','Baby Soft Romper',NULL,NULL,NULL,NULL,899.00,599.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-10.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-20,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(46,4,NULL,'Kids Party Dress','kids-party-dress-shopus',15,NULL,NULL,'<p>Kids Party Dress — ShopUS demo product for Home 1.</p>','Kids Party Dress',NULL,NULL,NULL,NULL,1199.00,799.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-9.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-21,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(47,4,NULL,'Heeled Women Shoes','heeled-women-shoes-shopus',16,NULL,NULL,'<p>Heeled Women Shoes — ShopUS demo product for Home 1.</p>','Heeled Women Shoes',NULL,NULL,NULL,NULL,2699.00,1899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-7.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,1,-22,'2026-08-25 11:38:54','2026-08-25 12:03:34'),
(48,4,NULL,'Makeup Organizer Box','makeup-organizer-box-shopus',17,NULL,NULL,'<p>Makeup Organizer Box — ShopUS demo product for Home 1.</p>','Makeup Organizer Box',NULL,NULL,NULL,NULL,1399.00,899.00,0.00,50,NULL,NULL,NULL,NULL,1,'theme4/assets/images/homepage-one/product-img/product-img-6.webp',NULL,NULL,NULL,0,0,1,0,0,0,0,0,-23,'2026-08-25 11:38:54','2026-08-25 12:03:34');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `products_to_meta`
--

DROP TABLE IF EXISTS `products_to_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products_to_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `pid` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_to_meta`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `products_to_meta` WRITE;
/*!40000 ALTER TABLE `products_to_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_to_meta` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rating` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `pid` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `is_read` tinyint(4) DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `rate` int(11) DEFAULT 0,
  `question` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rating`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `rating` WRITE;
/*!40000 ALTER TABLE `rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `rating` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `saas_apps`
--

DROP TABLE IF EXISTS `saas_apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_apps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `category` varchar(80) DEFAULT 'Marketing',
  `description` text DEFAULT NULL,
  `icon` varchar(20) DEFAULT NULL,
  `color` varchar(20) DEFAULT '#111',
  `sort` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_apps`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `saas_apps` WRITE;
/*!40000 ALTER TABLE `saas_apps` DISABLE KEYS */;
INSERT INTO `saas_apps` VALUES
(1,'Courier','courier','Orders & Shipping','Ship, track and manage all in one place.','🚚','#0f766e',1,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(2,'Product Reviews','product-reviews','Marketing','Turn customer feedback into confidence.','★','#6d28d9',2,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(3,'WhatsApp Marketing','whatsapp-marketing','Marketing','Bring customers back to your store.','💬','#15803d',3,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(4,'Abandoned Checkout','abandoned-checkout','Marketing','Recover orders that almost happened.','↩','#b45309',4,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(5,'Meta Catalogue','meta-catalogue','Sales','Keep Facebook and Instagram in sync.','f','#1d4ed8',5,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(6,'Invoice & Receipt','invoice-receipt','Operations','Professional paperwork for every order.','🧾','#334155',6,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(7,'Product Bundles','product-bundles','Sales','Sell more products together.','▦','#0f172a',7,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(8,'Customer Loyalty','customer-loyalty','Marketing','Reward the customers who return.','♡','#be185d',8,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(9,'Search & Discovery','search-discovery','Store design','Help shoppers find the right product.','⌕','#0369a1',9,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(10,'COD Manager','cod-manager','Orders & Shipping','Keep cash-on-delivery orders under control.','₨','#065f46',10,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(11,'Stock Alerts','stock-alerts','Operations','Know what needs attention before it runs out.','⚠','#9a3412',11,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(12,'Discount Engine','discount-engine','Sales','Run promotions without messy workarounds.','%','#1e3a8a',12,1,'2026-08-25 02:54:59','2026-08-25 02:54:59');
/*!40000 ALTER TABLE `saas_apps` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `saas_faqs`
--

DROP TABLE IF EXISTS `saas_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_faqs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `saas_faqs` WRITE;
/*!40000 ALTER TABLE `saas_faqs` DISABLE KEYS */;
INSERT INTO `saas_faqs` VALUES
(1,'Is it a different product for each shop?','No. One platform runs online store, restaurant ordering, and POS. You pick the product you need and can add more later.',1,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(2,'What about shipping?','Connect local couriers from the Apps directory. Tracking and COD tools sit next to your orders.',2,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(3,'Does the platform take commission?','No order commission. You pay a fixed monthly PKR price after the free trial.',3,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(4,'Can I use my own domain?','Yes. Point your domain to the store and it stays on your brand.',4,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(5,'Are Meta and TikTok included?','Yes. Connect catalogues, pixels and events from the store admin without pasting tokens into the storefront.',5,1,'2026-08-25 02:54:59','2026-08-25 02:54:59');
/*!40000 ALTER TABLE `saas_faqs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `saas_features`
--

DROP TABLE IF EXISTS `saas_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_features` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(40) NOT NULL DEFAULT 'home',
  `title` varchar(190) NOT NULL,
  `body` text DEFAULT NULL,
  `icon` varchar(40) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_features`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `saas_features` WRITE;
/*!40000 ALTER TABLE `saas_features` DISABLE KEYS */;
INSERT INTO `saas_features` VALUES
(1,'local','Accept PK payments','JazzCash, EasyPaisa, cards and COD in one checkout.','pay',1,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(2,'local','Pakistan shipping','TCS, Leopards, PostEx and more from one connection.','ship',2,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(3,'local','PKR dashboard','Sales, orders and payouts in the currency you actually use.','pkr',3,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(4,'local','Support after sales','WhatsApp-first help for you and your customers.','chat',4,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(5,'dashboard','Manage inventory','Stock, collections and low-stock alerts in one place.','box',5,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(6,'dashboard','Deliver orders on time','Courier labels, COD and status without spreadsheet chaos.','truck',6,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(7,'dashboard','Save time with learning','Short guides for themes, apps and first sale.','book',7,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(8,'tools','Complete commerce CMS','Dashboard for products, orders and customers.','cms',8,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(9,'tools','Payments, Meta and couriers','Integration hub for the services you already use.','plug',9,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(10,'tools','Built for speed','Fast storefronts on local mobile networks.','bolt',10,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(11,'tools','Theme editor + 10 themes','Visual control over layout, logo and colours.','theme',11,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(12,'tools','Your brand, your domain','Custom domain on every paid plan.','globe',12,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(13,'tools','Local support','Talk to people who understand Pakistani retail.','support',13,1,'2026-08-25 02:54:59','2026-08-25 02:54:59');
/*!40000 ALTER TABLE `saas_features` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `saas_plans`
--

DROP TABLE IF EXISTS `saas_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `audience` varchar(190) DEFAULT NULL,
  `price_label` varchar(80) DEFAULT NULL,
  `price_amount` int(11) DEFAULT 0,
  `highlight` tinyint(1) NOT NULL DEFAULT 0,
  `features` longtext DEFAULT NULL,
  `button_text` varchar(80) DEFAULT 'Start 7-day free trial',
  `sort` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_plans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `saas_plans` WRITE;
/*!40000 ALTER TABLE `saas_plans` DISABLE KEYS */;
INSERT INTO `saas_plans` VALUES
(1,'Online Store','online-store','For ecommerce brands','Rs. 4,000 / month',4000,1,'Products, collections and inventory\nTheme editor with professional themes\nPayments & courier integrations\nMeta Pixel / conversion tracking\nCustom domain','Start 7-day free trial',1,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(2,'Restaurant Ordering','restaurant-ordering','For food businesses','Rs. 3,000 / month',3000,0,'Delivery, pickup and dine-in\nMenu management\nOrder alerts\nOpening hours control\nDelivery zones','Start 7-day free trial',2,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(3,'POS Software','pos-software','For shops and counters','Rs. 2,000 / month',2000,0,'Works offline, syncs on reconnect\nBarcode billing\nStock alerts\nThermal receipt printing\nCounter sales','Start 7-day free trial',3,1,'2026-08-25 02:54:59','2026-08-25 02:54:59');
/*!40000 ALTER TABLE `saas_plans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `saas_settings`
--

DROP TABLE IF EXISTS `saas_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `skey` varchar(120) NOT NULL,
  `svalue` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `saas_settings_skey` (`skey`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `saas_settings` WRITE;
/*!40000 ALTER TABLE `saas_settings` DISABLE KEYS */;
INSERT INTO `saas_settings` VALUES
(1,'site_name','Herbals Glow','2026-08-25 02:54:59'),
(2,'logo_text','Herbals Glow','2026-08-25 02:54:59'),
(3,'nav_signin','Sign in','2026-08-25 02:54:59'),
(4,'nav_start','Start free','2026-08-25 02:54:59'),
(5,'hero_title','Start selling online.','2026-08-25 02:54:59'),
(6,'hero_subtitle','7-day free trial, no card needed.','2026-08-25 02:54:59'),
(7,'hero_body','Launch an online store, restaurant ordering, or POS — one platform for Pakistani businesses. PKR pricing, local couriers, and Meta/TikTok built in.','2026-08-25 02:54:59'),
(8,'hero_cta_primary','Start for free','2026-08-25 02:54:59'),
(9,'hero_cta_secondary','Book a demo','2026-08-25 02:54:59'),
(10,'hero_image','https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1400&q=80','2026-08-25 02:54:59'),
(11,'badge_1','No hidden fees','2026-08-25 02:54:59'),
(12,'badge_2','Quick setup','2026-08-25 02:54:59'),
(13,'badge_3','24/7 support','2026-08-25 02:54:59'),
(14,'stat_trial','7 days','2026-08-25 02:54:59'),
(15,'stat_trial_label','Free trial','2026-08-25 02:54:59'),
(16,'stat_commission','0%','2026-08-25 02:54:59'),
(17,'stat_commission_label','Order commission','2026-08-25 02:54:59'),
(18,'stat_currency','PKR','2026-08-25 02:54:59'),
(19,'stat_currency_label','Fixed monthly price','2026-08-25 02:54:59'),
(20,'stat_support','Local','2026-08-25 02:54:59'),
(21,'stat_support_label','Expert support','2026-08-25 02:54:59'),
(22,'local_heading','International polish. Local by default.','2026-08-25 02:54:59'),
(23,'dashboard_heading','Run the business without chasing information.','2026-08-25 02:54:59'),
(24,'channels_heading','One platform. Three ways to sell.','2026-08-25 02:54:59'),
(25,'pricing_heading','Start free. Pay only when you continue.','2026-08-25 02:54:59'),
(26,'pricing_sub','Choose what your business needs. 7-day free trial and PKR-based pricing.','2026-08-25 02:54:59'),
(27,'tools_heading','Everything needed to run your store.','2026-08-25 02:54:59'),
(28,'apps_heading','Add what your business needs next.','2026-08-25 02:54:59'),
(29,'apps_sub','Your store stays at the centre. Every app connects to the products, customers and orders you already manage.','2026-08-25 02:54:59'),
(30,'themes_heading','Storefronts with a point of view.','2026-08-25 02:54:59'),
(31,'themes_sub','Complete ecommerce designs built for mobile shopping, clear product discovery, and your own brand. All themes included with your store.','2026-08-25 02:54:59'),
(32,'faq_heading','Clear answers before you start.','2026-08-25 02:54:59'),
(33,'final_heading','Build the store your business deserves.','2026-08-25 02:54:59'),
(34,'final_cta_primary','Start for free','2026-08-25 02:54:59'),
(35,'final_cta_secondary','Contact support','2026-08-25 02:54:59'),
(36,'footer_about','Omni-commerce platform for small brands, restaurants and sellers. Built for businesses in Pakistan.','2026-08-25 02:54:59'),
(37,'support_email','hello@herbalsglow.test','2026-08-25 02:54:59'),
(38,'whatsapp','923000000000','2026-08-25 02:54:59'),
(39,'products_heading','Choose what your business needs.','2026-08-25 02:54:59'),
(40,'products_sub','7-day free trial. PKR pricing. No commission on orders.','2026-08-25 02:54:59');
/*!40000 ALTER TABLE `saas_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `saas_themes`
--

DROP TABLE IF EXISTS `saas_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `saas_themes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `category` varchar(80) DEFAULT 'Fashion',
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `demo_url` varchar(255) DEFAULT NULL,
  `engine_theme` tinyint(3) unsigned DEFAULT 3,
  `sort` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `saas_themes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `saas_themes` WRITE;
/*!40000 ALTER TABLE `saas_themes` DISABLE KEYS */;
INSERT INTO `saas_themes` VALUES
(1,'Loom','loom','Fashion','Ethnic and traditional women’s clothing.','https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,1,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(2,'Meridian','meridian','Fashion','Minimalist men’s fashion with a clean grid.','https://images.unsplash.com/photo-1490114538077-0a7f8cb49891?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,2,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(3,'Voltage','voltage','Electronics','Dark, modern storefront for gadgets.','https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,3,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(4,'Vista','vista','Fashion','Lifestyle apparel with generous imagery.','https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,4,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(5,'Lucent','lucent','Food','Soft, airy layout for bakeries and groceries.','https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,5,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(6,'Foundry','foundry','Industrial','Professional look for hardware and supplies.','https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,6,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(7,'Fitment','fitment','Auto Parts','High-contrast design for automotive.','https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,7,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(8,'Remedy','remedy','Beauty','Clean cosmetics and skincare layout.','https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,8,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(9,'Keepsake','keepsake','Gifts','Warm storefront for gifts and hampers.','https://images.unsplash.com/photo-1513885535751-8b9238bd345a?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,9,1,'2026-08-25 02:54:59','2026-08-25 02:54:59'),
(10,'Interior','interior','Home','Spacious furniture and home decor theme.','https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=900&q=80','http://localhost/herbalsglow?theme=3',3,10,1,'2026-08-25 02:54:59','2026-08-25 02:54:59');
/*!40000 ALTER TABLE `saas_themes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `menu` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `menu_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `track_order_link` varchar(255) DEFAULT NULL,
  `about_us_link` varchar(255) DEFAULT NULL,
  `contact_us_link` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `phonetwo` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `welcome_message` text DEFAULT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `pinterest` varchar(255) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `wlogo` text DEFAULT NULL,
  `logo1` text DEFAULT NULL,
  `homepage_footer` text DEFAULT NULL,
  `homepage_img1d` text DEFAULT NULL,
  `homepage_img2d` text DEFAULT NULL,
  `homepage_img3d` text DEFAULT NULL,
  `homepage_img4d` text DEFAULT NULL,
  `homepage_img5d` text DEFAULT NULL,
  `homepage_img6d` text DEFAULT NULL,
  `homepage_image_one` text DEFAULT NULL,
  `homepage_image_two` text DEFAULT NULL,
  `homepage_image_3` text DEFAULT NULL,
  `homepage_image_4` text DEFAULT NULL,
  `homepage_image_5` text DEFAULT NULL,
  `homepage_image_6` text DEFAULT NULL,
  `shipping_charges` varchar(50) DEFAULT '0',
  `footer_text` text DEFAULT NULL,
  `news_text` text DEFAULT NULL,
  `dir_link` varchar(255) DEFAULT NULL,
  `primary_color` varchar(50) DEFAULT NULL,
  `navigation_color` varchar(50) DEFAULT NULL,
  `button_color` varchar(50) DEFAULT NULL,
  `theme_style` varchar(50) DEFAULT NULL,
  `active_theme` tinyint(3) unsigned DEFAULT 3,
  `home_layout` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `head_scripts` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES
(1,1,NULL,NULL,NULL,NULL,'admin@herbalsglow.test','03000000000',NULL,NULL,NULL,'Herbals Glow','Herbals Glow','Herbals Glow local store','herbals,glow',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','Herbals Glow',NULL,NULL,NULL,NULL,NULL,NULL,3,1,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(2,2,NULL,NULL,NULL,NULL,'admin@herbalsglow.test','03000000000',NULL,NULL,NULL,'Classic Store','Herbals Glow','Herbals Glow local store','herbals,glow',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','Herbals Glow',NULL,NULL,NULL,NULL,NULL,NULL,2,1,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(3,3,NULL,NULL,NULL,NULL,'admin@herbalsglow.test','03000000000',NULL,NULL,NULL,'Wellness Store','Herbals Glow','Herbals Glow local store','herbals,glow',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','Herbals Glow',NULL,NULL,NULL,NULL,NULL,NULL,3,1,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(4,4,NULL,NULL,NULL,NULL,'admin@herbalsglow.test','+ 00645 4568',NULL,NULL,NULL,'Shopus: Your One-Stop Destination for Fashion and Style','Shopus: Your One-Stop Destination for Fashion and Style','Herbals Glow local store','herbals,glow',NULL,NULL,NULL,NULL,NULL,'theme4/assets/images/logos/logo.webp','theme4/assets/images/logos/footer-logo.webp','theme4/assets/images/homepage-one/icon.png',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','4517 Washington Ave. Manchester, Kentucky 39495',NULL,NULL,'#ae1c9a','#111111','#ae1c9a',NULL,4,1,NULL,'2026-08-24 16:30:02','2026-08-25 11:58:30');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `shap`
--

DROP TABLE IF EXISTS `shap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shap` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shap`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `shap` WRITE;
/*!40000 ALTER TABLE `shap` DISABLE KEYS */;
/*!40000 ALTER TABLE `shap` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `shopify_connections`
--

DROP TABLE IF EXISTS `shopify_connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopify_connections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `shop_domain` varchar(255) NOT NULL,
  `shop_name` varchar(255) DEFAULT NULL,
  `shopify_shop_id` varchar(255) DEFAULT NULL,
  `access_token_encrypted` text NOT NULL,
  `connection_method` varchar(20) NOT NULL DEFAULT 'oauth',
  `status` varchar(20) NOT NULL DEFAULT 'connected',
  `scopes` varchar(255) DEFAULT NULL,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shopify_connections_store_id_unique` (`store_id`),
  KEY `shopify_connections_shop_domain_index` (`shop_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopify_connections`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `shopify_connections` WRITE;
/*!40000 ALTER TABLE `shopify_connections` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopify_connections` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `shopify_import_errors`
--

DROP TABLE IF EXISTS `shopify_import_errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopify_import_errors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `job_id` bigint(20) unsigned NOT NULL,
  `resource_type` varchar(40) NOT NULL,
  `shopify_id` varchar(64) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `retry_status` varchar(20) NOT NULL DEFAULT 'pending',
  `retried_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shopify_import_errors_store_id_job_id_index` (`store_id`,`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopify_import_errors`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `shopify_import_errors` WRITE;
/*!40000 ALTER TABLE `shopify_import_errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopify_import_errors` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `shopify_import_jobs`
--

DROP TABLE IF EXISTS `shopify_import_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopify_import_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `connection_id` bigint(20) unsigned NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `duplicate_mode` varchar(20) NOT NULL DEFAULT 'update',
  `config_json` longtext DEFAULT NULL,
  `counts_json` longtext DEFAULT NULL,
  `preview_json` longtext DEFAULT NULL,
  `cursor_json` longtext DEFAULT NULL,
  `cancel_requested` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shopify_import_jobs_store_id_status_index` (`store_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopify_import_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `shopify_import_jobs` WRITE;
/*!40000 ALTER TABLE `shopify_import_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopify_import_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `shopify_resource_maps`
--

DROP TABLE IF EXISTS `shopify_resource_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `shopify_resource_maps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `connection_id` bigint(20) unsigned NOT NULL,
  `resource_type` varchar(40) NOT NULL,
  `shopify_id` varchar(64) NOT NULL,
  `local_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shopify_map_unique` (`store_id`,`resource_type`,`shopify_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shopify_resource_maps`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `shopify_resource_maps` WRITE;
/*!40000 ALTER TABLE `shopify_resource_maps` DISABLE KEYS */;
/*!40000 ALTER TABLE `shopify_resource_maps` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `size`
--

DROP TABLE IF EXISTS `size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `size` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `size`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `size` WRITE;
/*!40000 ALTER TABLE `size` DISABLE KEYS */;
/*!40000 ALTER TABLE `size` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sliders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `slider_image` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `cid` bigint(20) unsigned DEFAULT NULL,
  `button` varchar(255) DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `title_size` varchar(20) DEFAULT '18px',
  `p` text DEFAULT NULL,
  `ga_id` varchar(50) DEFAULT NULL,
  `ga_name` varchar(120) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES
(1,1,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/campaign/5d233e1b5ac1406b941df36dd217492a.jpg',NULL,'#','15% Off Creatine','18px','Everyday, sport & recovery formulas','100794','WK30B_Creatine',1,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(2,1,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/cms/banners/BentoBG/WK28/Hero_WK28_Promo_Deals_en-us.jpg',NULL,'#','Up to 70% Off Deals','18px','Hundreds of picks across every aisle','101046','WK28B_70-Off_Deals',2,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(3,1,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/cms/banners/BentoBG/WK27/WK27-Slot2-V2-en.jpg',NULL,'#','Shop Active Wellness','18px','Protein, creatine, electrolytes & more','101046','WK28B_JulyCamp_Sports',3,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(4,2,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/campaign/5d233e1b5ac1406b941df36dd217492a.jpg',NULL,'#','15% Off Creatine','18px','Everyday, sport & recovery formulas','100794','WK30B_Creatine',1,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(5,2,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/cms/banners/BentoBG/WK28/Hero_WK28_Promo_Deals_en-us.jpg',NULL,'#','Up to 70% Off Deals','18px','Hundreds of picks across every aisle','101046','WK28B_70-Off_Deals',2,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(6,2,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/cms/banners/BentoBG/WK27/WK27-Slot2-V2-en.jpg',NULL,'#','Shop Active Wellness','18px','Protein, creatine, electrolytes & more','101046','WK28B_JulyCamp_Sports',3,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(7,3,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/campaign/5d233e1b5ac1406b941df36dd217492a.jpg',NULL,'#','15% Off Creatine','18px','Everyday, sport & recovery formulas','100794','WK30B_Creatine',1,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(8,3,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/cms/banners/BentoBG/WK28/Hero_WK28_Promo_Deals_en-us.jpg',NULL,'#','Up to 70% Off Deals','18px','Hundreds of picks across every aisle','101046','WK28B_70-Off_Deals',2,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(9,3,'','https://cloudinary.images-iherb.com/image/upload/c_fill,w_680/f_auto,q_auto:eco/images/cms/banners/BentoBG/WK27/WK27-Slot2-V2-en.jpg',NULL,'#','Shop Active Wellness','18px','Protein, creatine, electrolytes & more','101046','WK28B_JulyCamp_Sports',3,1,'2026-08-24 16:30:15','2026-08-24 16:30:15'),
(10,4,'theme4/assets/images/homepage-one/hero-slider-one.webp','/shop',NULL,'Shop Now','UP TO <span class=\"wrapper-inner-title\">70%</span> OFF','18px','Fashion Collection Summer Sale','100794','WK30B_Creatine',1,1,'2026-08-24 16:30:15','2026-08-25 12:03:34'),
(11,4,'theme4/assets/images/homepage-one/hero-slider-two.webp','/shop',NULL,'Shop Now','UP TO <span class=\"wrapper-inner-title\">70%</span> OFF','18px','Fashion Collection Summer Sale','101046','WK28B_70-Off_Deals',2,1,'2026-08-24 16:30:15','2026-08-25 12:03:34'),
(12,4,'theme4/assets/images/homepage-one/hero-slider-three.webp','/shop',NULL,'Shop Now','UP TO <span class=\"wrapper-inner-title\">70%</span> OFF','18px','Fashion Collection Summer Sale','101046','WK28B_JulyCamp_Sports',3,1,'2026-08-24 16:30:15','2026-08-25 12:03:34');
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_domains`
--

DROP TABLE IF EXISTS `store_domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_domains` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `domain` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_domains_domain_unique` (`domain`),
  KEY `store_domains_store_id_index` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_domains`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_domains` WRITE;
/*!40000 ALTER TABLE `store_domains` DISABLE KEYS */;
INSERT INTO `store_domains` VALUES
(1,1,'localhost',1,1,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(2,1,'127.0.0.1',0,1,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(3,2,'classic.herbalsglow.test',1,1,'2026-08-25 03:39:45','2026-08-25 03:39:45'),
(4,3,'wellness.herbalsglow.test',1,1,'2026-08-25 03:39:45','2026-08-25 03:39:45'),
(5,4,'shopus.herbalsglow.test',0,1,'2026-08-25 03:39:45','2026-08-25 03:39:45'),
(6,4,'maroon-alligator-633257.hostingersite.com',1,1,'2026-08-28 19:51:46','2026-08-28 19:51:46');
/*!40000 ALTER TABLE `store_domains` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `store_integrations`
--

DROP TABLE IF EXISTS `store_integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_integrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `provider` varchar(50) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `connection_type` varchar(20) DEFAULT NULL,
  `connection_status` varchar(40) DEFAULT NULL,
  `catalog_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `events_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `access_token` text DEFAULT NULL,
  `refresh_token` text DEFAULT NULL,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `catalog_id` text DEFAULT NULL,
  `pixel_id` text DEFAULT NULL,
  `ad_account_id` text DEFAULT NULL,
  `extra_json` text DEFAULT NULL,
  `connected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_integrations_store_provider` (`store_id`,`provider`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_integrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `store_integrations` WRITE;
/*!40000 ALTER TABLE `store_integrations` DISABLE KEYS */;
INSERT INTO `store_integrations` VALUES
(1,1,'meta',0,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(2,1,'tiktok',0,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(3,4,'meta',1,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-28 19:51:46','2026-08-28 19:51:46','2026-08-28 19:51:46');
/*!40000 ALTER TABLE `store_integrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `active_theme` tinyint(3) unsigned NOT NULL DEFAULT 3,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `currency` varchar(10) NOT NULL DEFAULT 'PKR',
  `timezone` varchar(100) NOT NULL DEFAULT 'Asia/Karachi',
  `logo` text DEFAULT NULL,
  `wlogo` text DEFAULT NULL,
  `meta_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `tiktok_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stores_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES
(1,'Herbals Glow','default','admin@herbalsglow.test',3,'active','PKR','Asia/Karachi',NULL,NULL,1,1,'2026-08-24 16:30:02','2026-08-24 16:30:02'),
(2,'Classic Store','classic-store','classic@herbalsglow.test',2,'active','PKR','Asia/Karachi',NULL,NULL,0,0,'2026-08-25 03:39:45','2026-08-25 03:39:45'),
(3,'Wellness Store','wellness-store','wellness@herbalsglow.test',3,'active','PKR','Asia/Karachi',NULL,NULL,0,0,'2026-08-25 03:39:45','2026-08-25 03:39:45'),
(4,'ShopUS Store','shopus-store','shopus@herbalsglow.test',4,'active','PKR','Asia/Karachi',NULL,NULL,1,0,'2026-08-25 03:39:45','2026-08-25 03:39:45');
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_categories_store_id_index` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `theme_customizations`
--

DROP TABLE IF EXISTS `theme_customizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `theme_customizations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned NOT NULL,
  `theme_id` tinyint(3) unsigned NOT NULL,
  `values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`values`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `theme_customizations_store_id_theme_id_unique` (`store_id`,`theme_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `theme_customizations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `theme_customizations` WRITE;
/*!40000 ALTER TABLE `theme_customizations` DISABLE KEYS */;
INSERT INTO `theme_customizations` VALUES
(1,4,4,'{\"hero.enabled\":false,\"hero.show_button\":true,\"categories.enabled\":true,\"homepage.show_featured\":true,\"homepage.show_sale\":true,\"header.announcement_bar\":true,\"header.show_logo\":true,\"header.show_search\":true,\"header.show_cart\":true,\"header.show_category_menu\":true,\"header.show_menu\":true,\"products.show_price\":true,\"products.show_compare_price\":true,\"products.show_add_to_cart\":true,\"footer.enabled\":true,\"footer.social_links\":true}','2026-08-25 07:12:47','2026-08-25 11:54:34');
/*!40000 ALTER TABLE `theme_customizations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wpdd_posts`
--

DROP TABLE IF EXISTS `wpdd_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wpdd_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_title` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wpdd_posts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wpdd_posts` WRITE;
/*!40000 ALTER TABLE `wpdd_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `wpdd_posts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-08-29 12:20:59
