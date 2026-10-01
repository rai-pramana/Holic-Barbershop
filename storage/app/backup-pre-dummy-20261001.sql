-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: holic_barbershop
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barbers`
--

DROP TABLE IF EXISTS `barbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barbers_branch_id_foreign` (`branch_id`),
  CONSTRAINT `barbers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbers`
--

LOCK TABLES `barbers` WRITE;
/*!40000 ALTER TABLE `barbers` DISABLE KEYS */;
INSERT INTO `barbers` VALUES (4,1,'Joko','088','Potong burung',NULL,NULL,1,'2026-07-31 10:29:02','2026-09-28 13:38:53');
/*!40000 ALTER TABLE `barbers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `open_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '09:00',
  `close_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '21:00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `queue_prefix` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_queue_prefix_unique` (`queue_prefix`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,'HOLIC Barbershop - Pusat','Jl. Sudirman No. 1, Jakarta Pusat','021-1234567','Jakarta','Cabang utama HOLIC Barbershop dengan 5 barber profesional.','09:00','21:00',1,'0','2026-07-31 06:14:47','2026-09-30 14:20:14'),(2,'HOLIC Barbershop - Selatan','Jl. TB Simatupang No. 25, Jakarta Selatan','021-7654321','Jakarta','Cabang HOLIC di Jakarta Selatan.','09:00','21:00',1,'1','2026-07-31 06:14:47','2026-07-31 06:14:47');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('5c785c036466adea360111aa28563bfd556b5fba','i:3;',1790824934),('5c785c036466adea360111aa28563bfd556b5fba:timer','i:1790824934;',1790824934),('checkin-qr:1:29846223','s:16:\"7dbcc7dc22ffc1ab\";',1790773527),('checkin-qr:1:29846224','s:16:\"3891a44181f6173e\";',1790773592),('checkin-qr:1:29846426','s:16:\"a2c8570e92504938\";',1790785725),('checkin-qr:1:29847073','s:16:\"cbc99ab4198c3381\";',1790824541),('checkin-qr:1:29847081','s:16:\"37761d2cfaf87fe4\";',1790825006),('checkin-qr:2:29846223','s:16:\"80cc5a807469bbda\";',1790773612),('checkin-qr:2:29846224','s:16:\"c4eaf8c770bb5844\";',1790773612),('fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f','i:1;',1790578870),('fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f:timer','i:1790578870;',1790578870);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (23,'default','{\"uuid\":\"1c35d7c8-5ce0-4bde-8edc-dc518133542f\",\"displayName\":\"App\\\\Jobs\\\\SendPasswordResetOtp\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":60,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendPasswordResetOtp\",\"command\":\"O:29:\\\"App\\\\Jobs\\\\SendPasswordResetOtp\\\":5:{s:35:\\\"\\u0000App\\\\Jobs\\\\SendPasswordResetOtp\\u0000code\\\";s:6:\\\"871979\\\";s:36:\\\"\\u0000App\\\\Jobs\\\\SendPasswordResetOtp\\u0000email\\\";s:17:\\\"customer@demo.com\\\";s:36:\\\"\\u0000App\\\\Jobs\\\\SendPasswordResetOtp\\u0000phone\\\";N;s:35:\\\"\\u0000App\\\\Jobs\\\\SendPasswordResetOtp\\u0000name\\\";s:15:\\\"Customer Nyanko\\\";s:38:\\\"\\u0000App\\\\Jobs\\\\SendPasswordResetOtp\\u0000purpose\\\";s:5:\\\"reset\\\";}\",\"batchId\":null},\"createdAt\":1790607171,\"delay\":null}',0,NULL,1790607171,1790607171);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2024_01_01_000001_create_users_table',1),(2,'2024_01_01_000002_create_cache_table',1),(3,'2024_01_01_000003_create_jobs_table',1),(4,'2024_01_01_000004_create_branches_table',1),(5,'2024_01_01_000005_create_barbers_table',1),(6,'2024_01_01_000006_create_services_table',1),(7,'2024_01_01_000007_create_queues_table',1),(8,'2024_01_01_000008_add_validation_token_to_queues_table',2),(9,'2024_01_01_000009_add_queue_prefix_to_branches_table',3),(10,'2024_01_01_000010_refactor_barbers_remove_user_id',4),(11,'2024_01_01_000020_create_push_subscriptions_table',5),(12,'2026_08_05_125414_add_name_phone_to_barbers_table',6),(13,'2026_08_11_000001_add_walkin_fields_to_queues_table',7),(14,'2026_09_28_000001_create_password_reset_otps_table',8),(15,'2026_09_30_000001_add_notified_near_at_to_queues_table',9),(16,'2026_09_30_000002_add_notified_near_level_to_queues_table',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_otps`
--

DROP TABLE IF EXISTS `password_reset_otps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL DEFAULT '0',
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_otps_email_index` (`email`),
  KEY `password_reset_otps_phone_index` (`phone`),
  KEY `password_reset_otps_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_otps`
--

LOCK TABLES `password_reset_otps` WRITE;
/*!40000 ALTER TABLE `password_reset_otps` DISABLE KEYS */;
INSERT INTO `password_reset_otps` VALUES (1,'barberholic0@gmail.com','6281234567890','$2y$12$E.11pEdWL10.9zFk5kJwz.BlooTLzDocmiFZFV6z0mhByT5rURnUy',0,'2026-09-28 05:51:16','2026-09-28 05:41:16','2026-09-28 05:41:16'),(4,'rai.pramana46@gmail.com','6288236053449','$2y$12$g7Syv/Ix1kuF6gLALR7uxu1UFZYh2yqJpbe9fdiHRc2rZwDCvxRZu',0,'2026-09-28 06:09:38','2026-09-28 05:59:38','2026-09-28 05:59:38'),(5,'rai.pramana46@gmail.com','6288236053449','$2y$12$BXWdydeQQejE1c7ZeClcm.JK/dNevCuXSI8VBv6uVIjO.UN0NiVEK',0,'2026-09-28 06:10:10','2026-09-28 06:00:10','2026-09-28 06:00:10'),(6,'rai.pramana46@gmail.com','6288236053449','$2y$12$YMZzoRhaN1RrIwzl.ig7ju60ykvNZWMWs0bDIJfilI3hhJ8L6le0K',1,'2026-09-28 06:46:57','2026-09-28 06:36:57','2026-09-28 06:37:23'),(7,'tesverify18604@gmail.com',NULL,'$2y$12$hMnT0u/4TQ4XZfglG4xwIOPh.Tj0halVwKDdya0le2/DqznmlrsnC',0,'2026-09-28 07:10:10','2026-09-28 07:00:10','2026-09-28 07:00:10');
/*!40000 ALTER TABLE `password_reset_otps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('customer@demo.com','$2y$12$x3JO6Qud3lmnqcPtMmOP/eWNcEEyVBEX/FgcTJvJ42NbiTf6TYZJy','2026-09-28 05:41:36'),('rai.pramana46@gmail.com','$2y$12$7BInPuB.8zAGk8KdoCzqCuhPq9ZMqWRl7jDkFr2eahS5CgMM/HziW','2026-09-28 05:41:45');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_subscriptions`
--

DROP TABLE IF EXISTS `push_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `endpoint` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_key` text COLLATE utf8mb4_unicode_ci,
  `auth_token` text COLLATE utf8mb4_unicode_ci,
  `content_encoding` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aes128gcm',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `push_sub_user_endpoint` (`user_id`,`endpoint`),
  CONSTRAINT `push_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_subscriptions`
--

LOCK TABLES `push_subscriptions` WRITE;
/*!40000 ALTER TABLE `push_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queues`
--

DROP TABLE IF EXISTS `queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `barber_id` bigint unsigned DEFAULT NULL,
  `service_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `status` enum('pending','active','called','completed','skipped','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `guest_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validation_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_start` timestamp NULL DEFAULT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `called_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `notified_near_at` timestamp NULL DEFAULT NULL,
  `notified_near_level` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `queues_validation_token_unique` (`validation_token`),
  KEY `queues_service_id_foreign` (`service_id`),
  KEY `queues_branch_id_status_index` (`branch_id`,`status`),
  KEY `queues_barber_id_status_index` (`barber_id`,`status`),
  KEY `queues_customer_id_status_index` (`customer_id`,`status`),
  CONSTRAINT `queues_barber_id_foreign` FOREIGN KEY (`barber_id`) REFERENCES `barbers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `queues_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `queues_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `queues_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queues`
--

LOCK TABLES `queues` WRITE;
/*!40000 ALTER TABLE `queues` DISABLE KEYS */;
INSERT INTO `queues` VALUES (1,'Q0001',5,NULL,1,1,'completed','Bebas',NULL,NULL,'qYFxi98F2tR5XczCIRVux7vf4fjezjz6',NULL,'2026-07-31 06:19:53','2026-07-31 06:20:48','2026-07-31 06:20:58','2026-07-31 07:19:34',NULL,NULL,'2026-07-31 06:19:34','2026-07-31 07:14:48'),(2,'Q0002',5,NULL,4,1,'skipped',NULL,NULL,NULL,'TRwk6TTvdeCrKcvcnExGIZMzTde4JSuu',NULL,'2026-07-31 06:22:26',NULL,NULL,'2026-07-31 07:22:11',NULL,NULL,'2026-07-31 06:22:11','2026-07-31 07:14:48'),(3,'Q1001',5,NULL,8,2,'completed',NULL,NULL,NULL,'J8TRbOKOdEWTGwvgzKtlM1dQXeGnVAtc',NULL,'2026-07-31 07:06:50','2026-07-31 07:07:06','2026-07-31 07:07:08','2026-07-31 07:46:01',NULL,NULL,'2026-07-31 06:46:01','2026-07-31 07:14:48'),(4,'Q0003',5,NULL,3,1,'completed',NULL,NULL,NULL,'LzeKpPgoLsXdzsw0L5N5mcuRvMuCsE2o',NULL,'2026-07-31 06:57:10','2026-07-31 06:57:41','2026-07-31 06:57:43','2026-07-31 07:56:51',NULL,NULL,'2026-07-31 06:56:51','2026-07-31 07:14:48'),(5,'Q0004',5,NULL,3,1,'completed',NULL,NULL,NULL,'eIvze5Wde12g7FZVRi17XWsw3g7sckd7',NULL,'2026-07-31 07:30:40','2026-07-31 07:31:27','2026-07-31 07:31:28','2026-07-31 08:07:36',NULL,NULL,'2026-07-31 07:07:36','2026-07-31 07:31:28'),(6,'Q1002',5,NULL,8,2,'completed',NULL,NULL,NULL,'f8eIJuMWKgoi5Gvpa9wohjfnCtDcdAnH',NULL,'2026-07-31 07:32:38','2026-07-31 07:33:28','2026-07-31 07:33:31','2026-07-31 08:31:50',NULL,NULL,'2026-07-31 07:31:50','2026-07-31 07:33:31'),(7,'Q0005',5,NULL,3,1,'completed',NULL,NULL,NULL,'40WRxlVHEhmxtE5dsGfwov5h6UJE3kVp',NULL,'2026-07-31 07:32:30','2026-07-31 07:34:11','2026-07-31 07:34:13','2026-07-31 08:32:06',NULL,NULL,'2026-07-31 07:32:06','2026-07-31 07:34:13'),(8,'Q0006',5,NULL,1,1,'completed',NULL,NULL,NULL,'JKyvROVbzl6GvvkaR0drrCb3y3wjqx9N',NULL,'2026-07-31 10:10:12','2026-07-31 10:11:23','2026-07-31 10:11:31','2026-07-31 11:08:00',NULL,NULL,'2026-07-31 10:08:00','2026-07-31 10:11:31'),(9,'Q0007',5,4,3,1,'completed',NULL,NULL,NULL,'LfS4saRbED9HVcuH7bT3uiYV7KP2Yu19',NULL,'2026-07-31 10:31:53','2026-07-31 10:32:09','2026-07-31 10:32:27','2026-07-31 11:30:50',NULL,NULL,'2026-07-31 10:30:50','2026-07-31 10:32:27'),(10,'Q0008',5,4,1,1,'skipped',NULL,NULL,NULL,'S32zz0Bb4TNKkD0chlF61kA64APQ7bkH',NULL,'2026-07-31 10:34:45','2026-07-31 10:35:02',NULL,'2026-07-31 11:33:28',NULL,NULL,'2026-07-31 10:33:28','2026-07-31 10:35:40'),(11,'Q0001',5,4,1,1,'completed',NULL,NULL,NULL,'5CfOqierlQjUBSP0Z0Efv82jrxOrUj2m',NULL,'2026-08-05 04:51:19','2026-08-05 04:51:25','2026-08-05 04:52:07','2026-08-05 05:50:17',NULL,NULL,'2026-08-05 04:50:17','2026-08-05 04:52:07'),(15,'Q0001',6,4,1,1,'completed',NULL,NULL,NULL,'emagBwaPzTC2KvoAW4omMu6ZYHbgpent',NULL,'2026-09-27 08:20:24','2026-09-27 08:20:47','2026-09-27 08:21:24','2026-09-27 09:10:16',NULL,NULL,'2026-09-27 08:10:16','2026-09-27 08:21:24'),(16,'Q0002',7,4,1,1,'completed',NULL,'sss',NULL,'rKEQcqQ1oWT02Ph2RiPayXtBV3X1YS9C',NULL,'2026-09-27 08:20:57','2026-09-27 08:24:32','2026-09-27 08:24:39',NULL,NULL,NULL,'2026-09-27 08:20:57','2026-09-27 08:24:39'),(20,'Q0003',6,4,3,1,'expired',NULL,NULL,NULL,'BuRHMAgXdp0753qcD5JKF44zVwN4SLWC',NULL,NULL,NULL,NULL,'2026-09-27 12:45:02',NULL,NULL,'2026-09-27 11:45:02','2026-09-27 12:45:31'),(23,'Q0001',6,4,3,1,'expired',NULL,NULL,NULL,'vUcTzdazT1GOIYlxyLqZCiG7C5MOU3XO',NULL,'2026-09-27 16:48:00','2026-09-27 16:48:01','2026-09-27 16:48:06','2026-09-28 14:38:03',NULL,NULL,'2026-09-27 16:47:28','2026-09-28 13:38:53'),(50,'Q0001',5,4,1,1,'completed','Potong pendek samping, atas sisakan',NULL,NULL,'tok-dummy-01',NULL,'2026-10-01 01:05:00','2026-10-01 01:10:00','2026-10-01 01:40:00',NULL,NULL,NULL,'2026-10-01 01:02:00','2026-10-01 01:40:00'),(51,'Q0002',7,4,3,1,'completed',NULL,'Budi Santoso','081234567890','tok-dummy-02',NULL,'2026-10-01 01:35:00','2026-10-01 01:45:00','2026-10-01 02:30:00',NULL,NULL,NULL,'2026-10-01 01:30:00','2026-10-01 02:30:00'),(52,'Q0003',6,4,4,1,'active','Creambath sekalian, jangan terlalu pendek',NULL,NULL,'tok-dummy-03',NULL,'2026-10-01 02:35:00',NULL,NULL,NULL,NULL,NULL,'2026-10-01 02:20:00','2026-10-01 02:35:00'),(53,'Q0004',7,4,1,1,'skipped',NULL,'Andi Pratama','082345678901','tok-dummy-04',NULL,'2026-10-01 02:40:00','2026-10-01 03:05:00',NULL,NULL,NULL,NULL,'2026-10-01 02:25:00','2026-10-01 03:13:39'),(54,'Q0005',5,NULL,2,1,'pending',NULL,NULL,NULL,'tok-dummy-05',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-01 02:55:00','2026-10-01 02:55:00'),(55,'Q0006',7,NULL,3,1,'pending','2 orang (bapak dan anak)','Dewi Lestari','083456789012','tok-dummy-06',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-10-01 03:00:00','2026-10-01 03:00:00'),(56,'Q0007',7,4,1,1,'skipped',NULL,'Rudi Hartono','084567890123','tok-dummy-07',NULL,'2026-10-01 01:50:00','2026-10-01 02:00:00',NULL,NULL,NULL,NULL,'2026-10-01 01:45:00','2026-10-01 02:00:00'),(57,'D0001',6,4,5,1,'skipped',NULL,NULL,NULL,'tok-seed-1',NULL,'2026-09-24 07:31:00','2026-09-24 07:39:00',NULL,NULL,NULL,NULL,'2026-09-24 07:31:00','2026-09-24 07:31:00'),(58,'D0002',6,4,1,1,'completed',NULL,NULL,NULL,'tok-seed-2',NULL,'2026-09-24 01:13:00','2026-09-24 01:21:00','2026-09-24 01:45:00',NULL,NULL,NULL,'2026-09-24 01:13:00','2026-09-24 01:13:00'),(59,'D0003',7,4,4,1,'completed',NULL,'Rudi Hartono','086789012345','tok-seed-3',NULL,'2026-09-24 02:53:00','2026-09-24 03:01:00','2026-09-24 03:51:00',NULL,NULL,NULL,'2026-09-24 02:53:00','2026-09-24 02:53:00'),(60,'D0004',5,4,1,1,'completed',NULL,NULL,NULL,'tok-seed-4',NULL,'2026-09-24 11:48:00','2026-09-24 11:56:00','2026-09-24 12:33:00',NULL,NULL,NULL,'2026-09-24 11:48:00','2026-09-24 11:48:00'),(61,'D0005',6,4,3,1,'completed','Creambath sekalian',NULL,NULL,'tok-seed-5',NULL,'2026-09-24 09:16:00','2026-09-24 09:24:00','2026-09-24 10:11:00',NULL,NULL,NULL,'2026-09-24 09:16:00','2026-09-24 09:16:00'),(62,'D0006',7,4,1,1,'completed',NULL,'Rina Marlina','081234567890','tok-seed-6',NULL,'2026-09-25 10:48:00','2026-09-25 10:56:00','2026-09-25 11:22:00',NULL,NULL,NULL,'2026-09-25 10:48:00','2026-09-25 10:48:00'),(63,'D0007',5,4,1,1,'completed',NULL,NULL,NULL,'tok-seed-7',NULL,'2026-09-25 01:29:00','2026-09-25 01:37:00','2026-09-25 02:19:00',NULL,NULL,NULL,'2026-09-25 01:29:00','2026-09-25 01:29:00'),(64,'D0008',5,4,4,1,'skipped',NULL,NULL,NULL,'tok-seed-8',NULL,'2026-09-25 09:35:00','2026-09-25 09:43:00',NULL,NULL,NULL,NULL,'2026-09-25 09:35:00','2026-09-25 09:35:00'),(65,'D0009',7,4,1,1,'completed',NULL,'Agus Wijaya','081234567890','tok-seed-9',NULL,'2026-09-25 10:58:00','2026-09-25 11:06:00','2026-09-25 11:25:00',NULL,NULL,NULL,'2026-09-25 10:58:00','2026-09-25 10:58:00'),(66,'D0010',7,4,5,1,'completed','Potong pendek samping','Siti Aminah','084567890123','tok-seed-10',NULL,'2026-09-25 04:32:00','2026-09-25 04:40:00','2026-09-25 04:53:00',NULL,NULL,NULL,'2026-09-25 04:32:00','2026-09-25 04:32:00'),(67,'D0011',6,4,4,1,'completed',NULL,NULL,NULL,'tok-seed-11',NULL,'2026-09-25 09:25:00','2026-09-25 09:33:00','2026-09-25 10:16:00',NULL,NULL,NULL,'2026-09-25 09:25:00','2026-09-25 09:25:00'),(68,'D0012',7,4,1,1,'completed','Anak kecil, sabar ya','Putri Ayu','082345678901','tok-seed-12',NULL,'2026-09-26 06:25:00','2026-09-26 06:33:00','2026-09-26 07:26:00',NULL,NULL,NULL,'2026-09-26 06:25:00','2026-09-26 06:25:00'),(69,'D0013',5,4,5,1,'completed',NULL,NULL,NULL,'tok-seed-13',NULL,'2026-09-26 07:05:00','2026-09-26 07:13:00','2026-09-26 08:06:00',NULL,NULL,NULL,'2026-09-26 07:05:00','2026-09-26 07:05:00'),(70,'D0014',5,4,4,1,'skipped','Potong pendek samping',NULL,NULL,'tok-seed-14',NULL,'2026-09-26 08:27:00','2026-09-26 08:35:00',NULL,NULL,NULL,NULL,'2026-09-26 08:27:00','2026-09-26 08:27:00'),(71,'D0015',5,4,2,1,'expired','Jangan terlalu pendek',NULL,NULL,'tok-seed-15',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-09-26 01:54:00','2026-09-26 01:54:00'),(72,'D0016',6,4,1,1,'completed','Jangan terlalu pendek',NULL,NULL,'tok-seed-16',NULL,'2026-09-27 05:57:00','2026-09-27 06:05:00','2026-09-27 06:43:00',NULL,NULL,NULL,'2026-09-27 05:57:00','2026-09-27 05:57:00'),(73,'D0017',7,4,1,1,'completed','Creambath sekalian','Rudi Hartono','086789012345','tok-seed-17',NULL,'2026-09-27 06:30:00','2026-09-27 06:38:00','2026-09-27 07:01:00',NULL,NULL,NULL,'2026-09-27 06:30:00','2026-09-27 06:30:00'),(74,'D0018',7,4,3,1,'completed',NULL,'Budi Santoso','084567890123','tok-seed-18',NULL,'2026-09-27 08:17:00','2026-09-27 08:25:00','2026-09-27 09:09:00',NULL,NULL,NULL,'2026-09-27 08:17:00','2026-09-27 08:17:00'),(75,'D0019',7,4,5,1,'completed','Potong pendek samping','Dewi Lestari','082345678901','tok-seed-19',NULL,'2026-09-28 03:45:00','2026-09-28 03:53:00','2026-09-28 04:17:00',NULL,NULL,NULL,'2026-09-28 03:45:00','2026-09-28 03:45:00'),(76,'D0020',7,4,1,1,'completed',NULL,'Budi Santoso','085678901234','tok-seed-20',NULL,'2026-09-28 09:15:00','2026-09-28 09:23:00','2026-09-28 10:18:00',NULL,NULL,NULL,'2026-09-28 09:15:00','2026-09-28 09:15:00'),(77,'D0021',7,4,1,1,'expired','Creambath sekalian','Siti Aminah','084567890123','tok-seed-21',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-09-28 01:42:00','2026-09-28 01:42:00'),(78,'D0022',6,4,2,1,'completed','Anak kecil, sabar ya',NULL,NULL,'tok-seed-22',NULL,'2026-09-28 08:40:00','2026-09-28 08:48:00','2026-09-28 09:19:00',NULL,NULL,NULL,'2026-09-28 08:40:00','2026-09-28 08:40:00'),(79,'D0023',6,4,5,1,'completed',NULL,NULL,NULL,'tok-seed-23',NULL,'2026-09-28 01:04:00','2026-09-28 01:12:00','2026-09-28 02:05:00',NULL,NULL,NULL,'2026-09-28 01:04:00','2026-09-28 01:04:00'),(80,'D0024',7,4,2,1,'completed','Jangan terlalu pendek','Agus Wijaya','085678901234','tok-seed-24',NULL,'2026-09-29 10:02:00','2026-09-29 10:10:00','2026-09-29 11:04:00',NULL,NULL,NULL,'2026-09-29 10:02:00','2026-09-29 10:02:00'),(81,'D0025',5,4,1,1,'completed',NULL,NULL,NULL,'tok-seed-25',NULL,'2026-09-29 08:39:00','2026-09-29 08:47:00','2026-09-29 09:07:00',NULL,NULL,NULL,'2026-09-29 08:39:00','2026-09-29 08:39:00'),(82,'D0026',7,4,3,1,'completed',NULL,'Maya Sari','081234567890','tok-seed-26',NULL,'2026-09-29 01:11:00','2026-09-29 01:19:00','2026-09-29 01:31:00',NULL,NULL,NULL,'2026-09-29 01:11:00','2026-09-29 01:11:00'),(83,'D0027',7,4,3,1,'completed','Creambath sekalian','Maya Sari','083456789012','tok-seed-27',NULL,'2026-09-29 02:50:00','2026-09-29 02:58:00','2026-09-29 03:51:00',NULL,NULL,NULL,'2026-09-29 02:50:00','2026-09-29 02:50:00'),(84,'D0028',7,4,1,1,'completed',NULL,'Siti Aminah','084567890123','tok-seed-28',NULL,'2026-09-29 08:24:00','2026-09-29 08:32:00','2026-09-29 09:21:00',NULL,NULL,NULL,'2026-09-29 08:24:00','2026-09-29 08:24:00'),(85,'D0029',7,4,1,1,'completed','Creambath sekalian','Maya Sari','081234567890','tok-seed-29',NULL,'2026-09-29 08:23:00','2026-09-29 08:31:00','2026-09-29 09:25:00',NULL,NULL,NULL,'2026-09-29 08:23:00','2026-09-29 08:23:00'),(86,'D0030',7,NULL,1,1,'skipped',NULL,'Rina Marlina','084567890123','tok-seed-30',NULL,'2026-09-30 05:52:00','2026-09-30 06:00:00',NULL,NULL,NULL,NULL,'2026-09-30 05:52:00','2026-09-30 05:52:00'),(87,'D0031',7,4,5,1,'completed',NULL,'Putri Ayu','082345678901','tok-seed-31',NULL,'2026-09-30 07:42:00','2026-09-30 07:50:00','2026-09-30 08:11:00',NULL,NULL,NULL,'2026-09-30 07:42:00','2026-09-30 07:42:00'),(88,'D0032',7,4,1,1,'completed','Potong pendek samping','Dewi Lestari','083456789012','tok-seed-32',NULL,'2026-09-30 07:10:00','2026-09-30 07:18:00','2026-09-30 07:35:00',NULL,NULL,NULL,'2026-09-30 07:10:00','2026-09-30 07:10:00'),(89,'D0033',6,4,4,1,'completed','Creambath sekalian',NULL,NULL,'tok-seed-33',NULL,'2026-09-30 08:43:00','2026-09-30 08:51:00','2026-09-30 09:35:00',NULL,NULL,NULL,'2026-09-30 08:43:00','2026-09-30 08:43:00'),(90,'D0034',5,4,5,1,'skipped','Potong pendek samping',NULL,NULL,'tok-seed-34',NULL,'2026-09-30 05:05:00','2026-09-30 05:13:00',NULL,NULL,NULL,NULL,'2026-09-30 05:05:00','2026-09-30 05:05:00'),(91,'D0035',6,4,3,1,'completed',NULL,NULL,NULL,'tok-seed-35',NULL,'2026-09-30 11:49:00','2026-09-30 11:57:00','2026-09-30 12:16:00',NULL,NULL,NULL,'2026-09-30 11:49:00','2026-09-30 11:49:00'),(92,'D0036',6,4,5,1,'completed','Creambath sekalian',NULL,NULL,'tok-seed-36',NULL,'2026-09-30 07:05:00','2026-09-30 07:13:00','2026-09-30 07:53:00',NULL,NULL,NULL,'2026-09-30 07:05:00','2026-09-30 07:05:00');
/*!40000 ALTER TABLE `queues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration_minutes` int NOT NULL DEFAULT '30',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `services_branch_id_foreign` (`branch_id`),
  CONSTRAINT `services_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,1,'Potong Rambut','Potong rambut standar dengan konsultasi gaya.',30,35000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48'),(2,1,'Cukur Jenggot','Cukur dan rapikan jenggot dengan teknik barbershop klasik.',20,25000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48'),(3,1,'Potong + Cukur','Paket lengkap potong rambut dan cukur jenggot.',45,55000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48'),(4,1,'Creambath & Potong','Creambath relaxing ditambah potong rambut.',60,80000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48'),(5,1,'Warna Rambut','Pewarnaan rambut dengan cat berkualitas.',90,150000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48'),(6,2,'Potong Rambut','Potong rambut standar.',30,35000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48'),(7,2,'Cukur Jenggot','Cukur jenggot presisi.',20,25000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48'),(8,2,'Potong + Cukur','Paket lengkap.',45,55000.00,1,'2026-07-31 06:14:48','2026-07-31 06:14:48');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('7qABVuP0lB8wO2oolw4NdHc6EgDUFMeY9Vkq47ja',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMUtLT2F3eHVEbjQySXhwNThWdENHSXhKWTVueTRkUHVlekYwU09BNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTg6ImN1c3RvbWVyLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==',1790824897),('CBa9asKi1qyts2TU580d9ZICvlUDwuLwC2XSrTge',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYXpHTGlPZG5vclBrN2R1cU5PZTJ1Q1ZVV2I2aUExMXVWalhKa0s5OSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9saXZlLXN0YXR1cz90PTE3OTA4MjQ0NDU5NDAiO3M6NToicm91dGUiO3M6OToiaG9tZS5saXZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790824446),('fXpIeXRSQtcFtLk2CMjxARZct4k5x8OsOgPpL1lw',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ29VajlSTTVZSXkweGpEQzZGa0FlUEx0bVk0SWpsMTh1VkRYaThFciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yZWthcCI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ucmVrYXAuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTY6Im1hbmFnZV9icmFuY2hfaWQiO2k6MTt9',1790824890),('L5fGSZIyaqCdF2Ul7Y8UCXzfgtjWzEst75OJ6F8n',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRU5WY1NVQXJETTNNazVYTVpZN05ubDhFNUp2T1VSeVlLOEdvOG1BUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9saXZlLXN0YXR1cz90PTE3OTA4MjQ5MDEyNjAiO3M6NToicm91dGUiO3M6OToiaG9tZS5saXZlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790824901),('lUrORixMmmm0fAnCzz9y917bY7MAO77eq7nrSSWI',NULL,'127.0.0.1','curl/8.18.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1RoTFV4MnlneWNKRjREMTA0SGlYWTZWS250TzVBOUpsckY3cEdRdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790824397),('LvqWilAjpRedKHdWnLCFtbC3SIse6KOYihjN8qtw',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMFJleHNOQlJUQ0hPY01YNTJxSWxKUFBkWE1nWlNyUVVLV2pnVnNzNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9xdWV1ZS9oaXN0b3J5IjtzOjU6InJvdXRlIjtzOjIyOiJjdXN0b21lci5xdWV1ZS5oaXN0b3J5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1790824880),('N3P1JdEGtzCisBSSxilu1sSOH2BpYeQFAf2AmPiL',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibUJEajBoalpSZEZJWG5NSzZzR05ROXd6OGdpSTBiSW9McElyZk1mMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9xdWV1ZS9oaXN0b3J5IjtzOjU6InJvdXRlIjtzOjIyOiJjdXN0b21lci5xdWV1ZS5oaXN0b3J5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1790824415),('nrGZB7WNcm5zslOGMOsZ68gNnqh5IAQwbtGfQKUz',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibEJHSlE5a2c0MnhFaXV1VTVkUGtzb1N6aGt6SEhmNmZzM3FONTlkOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTg6ImN1c3RvbWVyLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==',1790824441),('XFjpqRy8agL4TmMR5davd43XmllpWWRrCsrnWnWx',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/154.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYnBYSU5JZDZBRW4zV2NSbHZjZDJxNXdTWUl3RHZIRmdYNDZsOFMyaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yZWthcCI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ucmVrYXAuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTY6Im1hbmFnZV9icmFuY2hfaWQiO2k6MTt9',1790824426),('zmnDeW1SXnGAjQP3fNdZufUiXibcrGwITymcZOmB',NULL,'127.0.0.1','curl/8.18.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZENBSW54Q1V2cTJ5WGFCZkZXMVE4NEZYSFNERFB4eEs2Slh5cGpuSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1790824360);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','barber','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin HOLIC','admin@holic.com',NULL,'$2y$12$/KmorUV.d4wHHz1U75mRD.7oPXk6LDp19URh3LY3RZhb/r5gDLq9a','admin','081200000001',NULL,'2026-07-31 06:14:47','2026-09-27 06:38:26'),(5,'Customer Nyanko','customer@demo.com','2026-09-28 07:18:17','$2y$12$YSO7Uxf57TMeqYBRTSEHAO4daS58yidKojBQh25XMvcb3lN3ozjgm','customer','081234567890','il7nZXOCW4Bi6k2uqeCN5euISWPkAsPgxA3EJv3zK3TyDdvmUKlqQsd64bO5','2026-07-31 06:14:48','2026-09-28 07:18:17'),(6,'Rai Pramana','rai.pramana46@gmail.com',NULL,'$2y$12$gfbYeAog5W/NlFQMocdxnujUhwwFJiQrP/URhzM84NCBMV7PMhz/.','customer','088236053449','iG5FXjcjJFcDZNTb268xTWpnXXEy5zC3X6sImVNaZVrVrLdz3VLJpKPu79vB','2026-09-26 06:18:16','2026-09-27 04:58:51'),(7,'Walk-in Guest','walkin@system.local',NULL,'$2y$12$8HJNkyAuhgeA8FTZyaC0GuPbo9p8JJYQ8rOuuxNcuIAAz6CMOAQj6','customer',NULL,NULL,'2026-09-27 08:20:57','2026-09-27 08:20:57'),(8,'Tes Verify','tesverify18604@gmail.com',NULL,'$2y$12$2ijhUS8f7f/HtTUg/nmKsOdTloD1PUPwgUpQ.jfx/x7WyZDI5emUe','customer','081234567899',NULL,'2026-09-28 06:59:59','2026-09-28 06:59:59');
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

-- Dump completed on 2026-10-01 11:35:42
