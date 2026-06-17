-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: eyeclinicproject
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
-- Table structure for table `app_notifications`
--

DROP TABLE IF EXISTS `app_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `app_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fas fa-bell',
  `icon_color` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text-primary',
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_notifs_user_read_idx` (`user_id`,`read_at`),
  CONSTRAINT `app_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_notifications`
--

LOCK TABLES `app_notifications` WRITE;
/*!40000 ALTER TABLE `app_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recall_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `reminder_channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'whatsapp',
  `reminder_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_sent',
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
  `missed_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_patient_id_foreign` (`patient_id`),
  KEY `appointments_user_id_foreign` (`user_id`),
  KEY `appointments_scheduled_at_index` (`scheduled_at`),
  KEY `appointments_status_index` (`status`),
  KEY `appointments_scheduled_at_status_index` (`scheduled_at`,`status`),
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_trails`
--

DROP TABLE IF EXISTS `audit_trails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_trails` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `event` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_trails_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audit_trails_patient_id_created_at_index` (`patient_id`,`created_at`),
  KEY `audit_trails_event_created_at_index` (`event`,`created_at`),
  KEY `audit_trails_user_id_index` (`user_id`),
  KEY `audit_trails_created_at_index` (`created_at`),
  CONSTRAINT `audit_trails_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `audit_trails_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_trails`
--

LOCK TABLES `audit_trails` WRITE;
/*!40000 ALTER TABLE `audit_trails` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_trails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_trails_archive`
--

DROP TABLE IF EXISTS `audit_trails_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_trails_archive` (
  `id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `event` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_trails_archive_created_at_index` (`created_at`),
  KEY `audit_trails_archive_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_trails_archive`
--

LOCK TABLES `audit_trails_archive` WRITE;
/*!40000 ALTER TABLE `audit_trails_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_trails_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `dispensed_by` bigint unsigned NOT NULL,
  `consultation_id` bigint unsigned NOT NULL DEFAULT '0',
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `frequency` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eye` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_dispensed` tinyint(1) NOT NULL DEFAULT '0',
  `dispensed_at` timestamp NULL DEFAULT NULL,
  `purchased` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_patient_id_index` (`patient_id`),
  KEY `carts_consultation_id_index` (`consultation_id`),
  KEY `carts_status_index` (`status`),
  KEY `carts_is_dispensed_index` (`is_dispensed`),
  KEY `carts_purchased_index` (`purchased`),
  KEY `carts_patient_id_status_index` (`patient_id`,`status`),
  KEY `carts_dispensed_by_index` (`dispensed_by`),
  KEY `carts_product_id_index` (`product_id`),
  KEY `carts_patient_purchased_status_index` (`patient_id`,`purchased`,`status`),
  CONSTRAINT `carts_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_dispensed_by_foreign` FOREIGN KEY (`dispensed_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `carts_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cashier_patient_clearances`
--

DROP TABLE IF EXISTS `cashier_patient_clearances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cashier_patient_clearances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `payment_status` enum('Paid','Unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `doctor_status` tinyint(1) NOT NULL DEFAULT '0',
  `clearance_date` date NOT NULL,
  `sale_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cashier_patient_clearances_patient_id_clearance_date_unique` (`patient_id`,`clearance_date`),
  UNIQUE KEY `cashier_patient_clearances_uuid_unique` (`uuid`),
  KEY `cashier_patient_clearances_user_id_foreign` (`user_id`),
  KEY `cashier_patient_clearances_service_id_foreign` (`service_id`),
  KEY `clearances_status_date_index` (`doctor_status`,`clearance_date`),
  KEY `cashier_patient_clearances_sale_id_foreign` (`sale_id`),
  CONSTRAINT `cashier_patient_clearances_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `cashier_patient_clearances_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_patient_clearances_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cashier_patient_clearances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cashier_patient_clearances`
--

LOCK TABLES `cashier_patient_clearances` WRITE;
/*!40000 ALTER TABLE `cashier_patient_clearances` DISABLE KEYS */;
/*!40000 ALTER TABLE `cashier_patient_clearances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  KEY `categories_user_id_foreign` (`user_id`),
  CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clearance_revoke_logs`
--

DROP TABLE IF EXISTS `clearance_revoke_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clearance_revoke_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clearance_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_by` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `requested_at` timestamp NOT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clearance_revoke_logs_clearance_id_foreign` (`clearance_id`),
  KEY `clearance_revoke_logs_requested_by_foreign` (`requested_by`),
  KEY `clearance_revoke_logs_approved_by_foreign` (`approved_by`),
  KEY `clearance_revoke_logs_rejected_by_foreign` (`rejected_by`),
  CONSTRAINT `clearance_revoke_logs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `clearance_revoke_logs_clearance_id_foreign` FOREIGN KEY (`clearance_id`) REFERENCES `cashier_patient_clearances` (`id`) ON DELETE CASCADE,
  CONSTRAINT `clearance_revoke_logs_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`),
  CONSTRAINT `clearance_revoke_logs_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clearance_revoke_logs`
--

LOCK TABLES `clearance_revoke_logs` WRITE;
/*!40000 ALTER TABLE `clearance_revoke_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `clearance_revoke_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultation_diagnosis`
--

DROP TABLE IF EXISTS `consultation_diagnosis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultation_diagnosis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `diagnosis_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultation_diagnosis_consultation_id_foreign` (`consultation_id`),
  KEY `consultation_diagnosis_diagnosis_id_foreign` (`diagnosis_id`),
  CONSTRAINT `consultation_diagnosis_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_diagnosis_diagnosis_id_foreign` FOREIGN KEY (`diagnosis_id`) REFERENCES `diagnoses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultation_diagnosis`
--

LOCK TABLES `consultation_diagnosis` WRITE;
/*!40000 ALTER TABLE `consultation_diagnosis` DISABLE KEYS */;
/*!40000 ALTER TABLE `consultation_diagnosis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `clearance_id` bigint unsigned NOT NULL,
  `chiefComplaint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `others` text COLLATE utf8mb4_unicode_ci,
  `odq` json DEFAULT NULL,
  `vaOD6m` text COLLATE utf8mb4_unicode_ci,
  `vaOS6m` text COLLATE utf8mb4_unicode_ci,
  `lidsOD` text COLLATE utf8mb4_unicode_ci,
  `lidsOS` text COLLATE utf8mb4_unicode_ci,
  `conjunctivaOD` text COLLATE utf8mb4_unicode_ci,
  `conjunctivaOS` text COLLATE utf8mb4_unicode_ci,
  `corneaOD` text COLLATE utf8mb4_unicode_ci,
  `corneaOS` text COLLATE utf8mb4_unicode_ci,
  `irisOD` text COLLATE utf8mb4_unicode_ci,
  `irisOS` text COLLATE utf8mb4_unicode_ci,
  `pupilOD` text COLLATE utf8mb4_unicode_ci,
  `pupilOS` text COLLATE utf8mb4_unicode_ci,
  `lensOD` text COLLATE utf8mb4_unicode_ci,
  `lensOS` text COLLATE utf8mb4_unicode_ci,
  `vitreousOD` text COLLATE utf8mb4_unicode_ci,
  `vitreousOS` text COLLATE utf8mb4_unicode_ci,
  `fundusOD` text COLLATE utf8mb4_unicode_ci,
  `fundusOS` text COLLATE utf8mb4_unicode_ci,
  `cdrOD` text COLLATE utf8mb4_unicode_ci,
  `cdrOS` text COLLATE utf8mb4_unicode_ci,
  `IOPOD` decimal(8,2) DEFAULT NULL,
  `IOPOS` decimal(8,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `review` text COLLATE utf8mb4_unicode_ci,
  `prescribed_products` json DEFAULT NULL,
  `drug_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `drugs` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `consultations_clearance_id_unique` (`clearance_id`),
  KEY `consultations_user_id_index` (`user_id`),
  KEY `consultations_patient_id_index` (`patient_id`),
  CONSTRAINT `consultations_clearance_id_foreign` FOREIGN KEY (`clearance_id`) REFERENCES `cashier_patient_clearances` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `consultations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultations`
--

LOCK TABLES `consultations` WRITE;
/*!40000 ALTER TABLE `consultations` DISABLE KEYS */;
/*!40000 ALTER TABLE `consultations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `diagnoses`
--

DROP TABLE IF EXISTS `diagnoses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnoses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnoses`
--

LOCK TABLES `diagnoses` WRITE;
/*!40000 ALTER TABLE `diagnoses` DISABLE KEYS */;
INSERT INTO `diagnoses` VALUES (1,'Seasonal Allergic Conjunctivitis (SAC)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(2,'Perennial Allergic Conjunctivitis (PAC)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(3,'Vernal Keratoconjunctivitis (VKC)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(4,'Atopic Keratoconjunctivitis (AKC)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(5,'Giant Papillary Conjunctivitis (GPC)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(6,'Contact Dermatoblepharitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(7,'Phlyctenular Keratoconjunctivitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(8,'Superior Limbic Keratoconjunctivitis (SLK)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(9,'Toxic Keratoconjunctivitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(10,'Ocular Cicatricial Pemphigoid','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(11,'Stevens-Johnson Syndrome (Ocular)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(12,'Graft vs Host Disease (Ocular)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(13,'Bacterial Keratitis (Pseudomonas)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(14,'Bacterial Keratitis (Staphylococcal)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(15,'Fungal Keratitis (Filamentous)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(16,'Fungal Keratitis (Candida)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(17,'Acanthamoeba Keratitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(18,'Herpes Simplex Keratitis (Epithelial)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(19,'Herpes Simplex Keratitis (Stromal)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(20,'Herpes Zoster Ophthalmicus','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(21,'Interstitual Keratitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(22,'Fuchs Endothelial Dystrophy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(23,'Lattice Corneal Dystrophy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(24,'Granular Corneal Dystrophy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(25,'Macular Corneal Dystrophy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(26,'Keratoconus','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(27,'Keratoglobus','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(28,'Pellucid Marginal Degeneration','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(29,'Terrien Marginal Degeneration','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(30,'Pterygium','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(31,'Pseudopterygium','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(32,'Pinguecula','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(33,'Dry Eye (Evaporative/MGD)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(34,'Dry Eye (Sjogren Syndrome)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(35,'Neurotrophic Keratopathy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(36,'Band Keratopathy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(37,'Corneal Abrasion','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(38,'Corneal Laceration','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(39,'Recurrent Corneal Erosion','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(40,'Primary Open Angle Glaucoma (POAG)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(41,'Normal Tension Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(42,'Acute Angle Closure Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(43,'Chronic Angle Closure Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(44,'Pseudoexfoliation Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(45,'Pigmentary Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(46,'Neovascular Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(47,'Uveitic Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(48,'Steroid-Induced Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(49,'Ocular Hypertension','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(50,'Anatomical Narrow Angles','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(51,'Iridocorneal Endothelial (ICE) Syndrome','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(52,'Plateau Iris Syndrome','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(53,'Juvenile Open Angle Glaucoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(54,'Mild Non-Proliferative Diabetic Retinopathy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(55,'Moderate NPDR','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(56,'Severe NPDR','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(57,'Proliferative Diabetic Retinopathy (PDR)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(58,'Diabetic Macular Edema (DME)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(59,'Dry Age-Related Macular Degeneration','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(60,'Wet Age-Related Macular Degeneration','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(61,'Retinal Detachment (Rhegmatogenous)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(62,'Retinal Detachment (Exudative)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(63,'Retinal Detachment (Tractional)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(64,'Retinoschisis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(65,'Central Retinal Vein Occlusion (CRVO)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(66,'Branch Retinal Vein Occlusion (BRVO)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(67,'Central Retinal Artery Occlusion (CRAO)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(68,'Branch Retinal Artery Occlusion (BRAO)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(69,'Cystoid Macular Edema','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(70,'Central Serous Chorioretinopathy (CSCR)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(71,'Macular Hole','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(72,'Epiretinal Membrane','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(73,'Retinitis Pigmentosa','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(74,'Stargardt Disease','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(75,'Best Disease','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(76,'Choroidal Nevus','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(77,'Choroidal Melanoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(78,'Vitreous Hemorrhage','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(79,'Asteroid Hyalosis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(80,'Posterior Vitreous Detachment (PVD)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(81,'Retinal Lattice Degeneration','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(82,'Nuclear Sclerotic Cataract','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(83,'Cortical Cataract','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(84,'Posterior Subcapsular Cataract','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(85,'Congenital Cataract','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(86,'Traumatic Cataract','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(87,'Posterior Capsule Opacification (PCO)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(88,'Ectopia Lentis (Lens Subluxation)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(89,'Aphakia','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(90,'Pseudophakia','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(91,'Acute Anterior Uveitis (Iritis)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(92,'Intermediate Uveitis (Pars Planitis)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(93,'Posterior Uveitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(94,'Panuveitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(95,'Scleritis (Necrotizing)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(96,'Scleritis (Non-necrotizing)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(97,'Episcleritis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(98,'Sympathetic Ophthalmia','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(99,'Behcet Disease (Ocular)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(100,'Sarcoidosis (Ocular)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(101,'Toxoplasmosis Chorioretinitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(102,'Optic Neuritis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(103,'Non-Arteritic Ischemic Optic Neuropathy (NAION)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(104,'Arteritic Ischemic Optic Neuropathy (GCA)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(105,'Papilledema','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(106,'Optic Atrophy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(107,'Horner Syndrome','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(108,'Third Nerve Palsy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(109,'Fourth Nerve Palsy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(110,'Sixth Nerve Palsy','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(111,'Myasthenia Gravis (Ocular)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(112,'Idiopathic Intracranial Hypertension (IIH)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(113,'Blepharitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(114,'Meibomian Gland Dysfunction (MGD)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(115,'Chalazion','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(116,'Hordeolum','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(117,'Ectropion','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(118,'Entropion','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(119,'Trichiasis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(120,'Ptosis (Involutional)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(121,'Dermatochalasis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(122,'Orbital Cellulitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(123,'Preseptal Cellulitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(124,'Thyroid Eye Disease','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(125,'Dacryocystitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(126,'Canaliculitis','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(127,'Orbital Floor Fracture (Blowout)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(128,'Amblyopia (Strabismic)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(129,'Amblyopia (Anisometropic)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(130,'Infantile Esotropia','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(131,'Accommodative Esotropia','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(132,'Exotropia','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(133,'Congenital Nystagmus','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(134,'Retinopathy of Prematurity (ROP)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(135,'Retinoblastoma','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(136,'Coats Disease','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL),(137,'Persistent Fetal Vasculature (PFV)','2026-06-04 10:53:36','2026-06-04 10:53:36',NULL);
/*!40000 ALTER TABLE `diagnoses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount_approval_requests`
--

DROP TABLE IF EXISTS `discount_approval_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discount_approval_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cashier_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cart_snapshot` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_approval_requests_cashier_id_foreign` (`cashier_id`),
  KEY `discount_approval_requests_patient_id_foreign` (`patient_id`),
  KEY `discount_approval_requests_approved_by_foreign` (`approved_by`),
  KEY `discount_approval_requests_rejected_by_foreign` (`rejected_by`),
  KEY `discount_approval_requests_status_index` (`status`),
  CONSTRAINT `discount_approval_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `discount_approval_requests_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `discount_approval_requests_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `discount_approval_requests_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount_approval_requests`
--

LOCK TABLES `discount_approval_requests` WRITE;
/*!40000 ALTER TABLE `discount_approval_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `discount_approval_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drugs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(22,2) NOT NULL,
  `expiryDate` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drugs`
--

LOCK TABLES `drugs` WRITE;
/*!40000 ALTER TABLE `drugs` DISABLE KEYS */;
/*!40000 ALTER TABLE `drugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operating_expense',
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6c757d',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_categories`
--

LOCK TABLES `expense_categories` WRITE;
/*!40000 ALTER TABLE `expense_categories` DISABLE KEYS */;
INSERT INTO `expense_categories` VALUES (1,'Staff Salaries','operating_expense','#3490dc',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32'),(2,'Rent / Utilities','operating_expense','#f6993f',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32'),(3,'Supplies','operating_expense','#38c172',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32'),(4,'Equipment','operating_expense','#9561e2',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32'),(5,'Maintenance','operating_expense','#e3342f',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32'),(6,'Marketing','operating_expense','#ff6384',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32'),(7,'Bank Charges','operating_expense','#6574cd',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32'),(8,'Miscellaneous','operating_expense','#6c757d',NULL,1,'2026-06-04 10:53:32','2026-06-04 10:53:32');
/*!40000 ALTER TABLE `expense_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expense_category_id` bigint unsigned DEFAULT NULL,
  `expense_date` date NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `receipt_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recorded_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_recorded_by_foreign` (`recorded_by`),
  KEY `expenses_expense_date_index` (`expense_date`),
  KEY `expenses_expense_category_id_index` (`expense_category_id`),
  KEY `expenses_date_category_index` (`expense_date`,`expense_category_id`),
  CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
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
-- Table structure for table `income_statement_entries`
--

DROP TABLE IF EXISTS `income_statement_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `income_statement_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `income_statement_entries_section_entry_date_index` (`section`,`entry_date`),
  KEY `income_statement_entries_is_active_index` (`is_active`),
  KEY `income_statement_entries_created_by_foreign` (`created_by`),
  KEY `income_statement_entries_deleted_by_foreign` (`deleted_by`),
  KEY `ise_active_date_index` (`is_active`,`entry_date`),
  CONSTRAINT `income_statement_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `income_statement_entries_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `income_statement_entries`
--

LOCK TABLES `income_statement_entries` WRITE;
/*!40000 ALTER TABLE `income_statement_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `income_statement_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `income_statement_period_locks`
--

DROP TABLE IF EXISTS `income_statement_period_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `income_statement_period_locks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `locked_by` bigint unsigned DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `income_statement_period_locks_from_date_to_date_unique` (`from_date`,`to_date`),
  KEY `income_statement_period_locks_locked_by_foreign` (`locked_by`),
  CONSTRAINT `income_statement_period_locks_locked_by_foreign` FOREIGN KEY (`locked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `income_statement_period_locks`
--

LOCK TABLES `income_statement_period_locks` WRITE;
/*!40000 ALTER TABLE `income_statement_period_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `income_statement_period_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `income_statement_templates`
--

DROP TABLE IF EXISTS `income_statement_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `income_statement_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `income_statement_templates_created_by_foreign` (`created_by`),
  KEY `income_statement_templates_section_is_active_index` (`section`,`is_active`),
  CONSTRAINT `income_statement_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `income_statement_templates`
--

LOCK TABLES `income_statement_templates` WRITE;
/*!40000 ALTER TABLE `income_statement_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `income_statement_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insurance_claims`
--

DROP TABLE IF EXISTS `insurance_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insurance_claims` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `insurer_id` bigint unsigned NOT NULL,
  `sale_id` bigint unsigned DEFAULT NULL,
  `member_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `claim_amount` decimal(10,2) NOT NULL,
  `approved_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('draft','submitted','approved','partially_approved','rejected','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submission_date` date DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `pre_auth_status` enum('not_required','pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_required',
  `pre_auth_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_auth_amount` decimal(10,2) DEFAULT NULL,
  `pre_auth_date` date DEFAULT NULL,
  `pre_auth_expiry_date` date DEFAULT NULL,
  `pre_auth_notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `insurance_claims_sale_id_unique` (`sale_id`),
  KEY `insurance_claims_insurer_id_foreign` (`insurer_id`),
  KEY `insurance_claims_created_by_foreign` (`created_by`),
  KEY `insurance_claims_updated_by_foreign` (`updated_by`),
  KEY `insurance_claims_patient_id_status_index` (`patient_id`,`status`),
  KEY `insurance_claims_status_index` (`status`),
  KEY `insurance_claims_submission_date_index` (`submission_date`),
  CONSTRAINT `insurance_claims_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `insurance_claims_insurer_id_foreign` FOREIGN KEY (`insurer_id`) REFERENCES `insurers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `insurance_claims_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `insurance_claims_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurance_claims`
--

LOCK TABLES `insurance_claims` WRITE;
/*!40000 ALTER TABLE `insurance_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `insurance_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insurers`
--

DROP TABLE IF EXISTS `insurers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insurers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheme_type` enum('NHIS','Private','Corporate') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NHIS',
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurers`
--

LOCK TABLES `insurers` WRITE;
/*!40000 ALTER TABLE `insurers` DISABLE KEYS */;
/*!40000 ALTER TABLE `insurers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lens_orders`
--

DROP TABLE IF EXISTS `lens_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lens_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `refraction_id` bigint unsigned NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frame_model_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frame_product_id` bigint unsigned DEFAULT NULL,
  `lens_product_id` bigint unsigned DEFAULT NULL,
  `frame_price` decimal(22,2) NOT NULL,
  `lens_price` decimal(22,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pickUpDate` date NOT NULL,
  `collected_at` timestamp NULL DEFAULT NULL,
  `renewal_date` date DEFAULT NULL,
  `renewal_reminder_sent_at` timestamp NULL DEFAULT NULL,
  `renewal_approval_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `renewal_approved_by` bigint unsigned DEFAULT NULL,
  `renewal_actioned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lens_orders_refraction_id_unique` (`refraction_id`),
  UNIQUE KEY `lens_orders_order_id_unique` (`order_id`),
  KEY `lens_orders_user_id_foreign` (`user_id`),
  KEY `lens_orders_frame_product_id_foreign` (`frame_product_id`),
  KEY `lens_orders_lens_product_id_foreign` (`lens_product_id`),
  KEY `lens_orders_renewal_approved_by_foreign` (`renewal_approved_by`),
  CONSTRAINT `lens_orders_frame_product_id_foreign` FOREIGN KEY (`frame_product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lens_orders_lens_product_id_foreign` FOREIGN KEY (`lens_product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lens_orders_refraction_id_foreign` FOREIGN KEY (`refraction_id`) REFERENCES `refractions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lens_orders_renewal_approved_by_foreign` FOREIGN KEY (`renewal_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lens_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lens_orders`
--

LOCK TABLES `lens_orders` WRITE;
/*!40000 ALTER TABLE `lens_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `lens_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login_logs_user_login_at_index` (`user_id`,`login_at`),
  CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_logs`
--

LOCK TABLES `login_logs` WRITE;
/*!40000 ALTER TABLE `login_logs` DISABLE KEYS */;
INSERT INTO `login_logs` VALUES (1,2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-06-04 11:12:45'),(2,2,'192.168.1.111','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1','2026-06-04 13:00:02');
/*!40000 ALTER TABLE `login_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_logs_archive`
--

DROP TABLE IF EXISTS `login_logs_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_logs_archive` (
  `id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_logs_archive_login_at_index` (`login_at`),
  KEY `login_logs_archive_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_logs_archive`
--

LOCK TABLES `login_logs_archive` WRITE;
/*!40000 ALTER TABLE `login_logs_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_logs_archive` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2022_01_15_155928_create_patients_table',1),(6,'2022_01_18_093243_create_cashier_patient_clearances_table',1),(7,'2022_01_18_112002_create_drugs_table',1),(8,'2022_01_18_112009_create_diagnoses_table',1),(9,'2022_01_18_123029_create_consultations_table',1),(10,'2022_02_07_181517_create_refractions_table',1),(11,'2022_05_31_100830_create_categories_table',1),(12,'2022_09_29_072408_create_products_table',1),(13,'2022_09_29_072439_create_stocks_table',1),(14,'2022_11_05_212856_create_spectacles_table',1),(15,'2022_11_23_111505_create_lens_orders_table',1),(16,'2022_12_24_095005_add_drugs_consultatations_table',1),(17,'2025_11_04_093900_create_permission_tables',1),(18,'2025_11_25_082252_create_sales_table',1),(19,'2025_11_25_083623_create_sale_items_table',1),(20,'2025_11_27_065808_create_refund_logs_table',1),(21,'2025_12_09_200333_create_carts_table',1),(22,'2025_12_09_201352_create_orders_table',1),(23,'2025_12_20_195839_create_appointments_table',1),(24,'2026_01_01_201337_create_consultation_diagnosis_table',1),(25,'2026_01_02_164132_create_login_logs_table',1),(26,'2026_01_02_190611_create_settings_table',1),(27,'2026_05_02_000001_create_income_statement_entries_table',1),(28,'2026_05_02_000001_create_referrals_table',1),(29,'2026_05_02_000002_add_audit_fields_to_income_statement_entries_table',1),(30,'2026_05_02_000002_extend_referrals_for_letter_types',1),(31,'2026_05_02_000003_add_workflow_audit_to_referrals',1),(32,'2026_05_02_000003_create_income_statement_templates_table',1),(33,'2026_05_02_000004_create_income_statement_period_locks_table',1),(34,'2026_05_02_000004_create_referral_snippets_table',1),(35,'2026_05_02_000005_add_payment_status_to_sales',1),(36,'2026_05_02_000006_create_payment_transactions_table',1),(37,'2026_05_02_000007_add_odq_to_consultations_table',1),(38,'2026_05_02_000008_add_cart_id_to_sale_items_table',1),(39,'2026_05_03_000001_add_discount_to_sales_table',1),(40,'2026_05_03_000001_create_patient_documents_table',1),(41,'2026_05_03_000002_add_discount_approved_by_to_sales_table',1),(42,'2026_05_03_000002_create_audit_trails_table',1),(43,'2026_05_03_000003_add_recall_reminder_and_missed_tracking_to_appointments',1),(44,'2026_05_03_000004_add_metadata_to_categories_table',1),(45,'2026_05_03_000005_create_discount_approval_requests_table',1),(46,'2026_05_03_000006_create_stock_movements_table',1),(47,'2026_05_03_221708_create_suppliers_table',1),(48,'2026_05_04_000001_add_performance_indexes_to_pos_tables',1),(49,'2026_05_04_065959_add_performance_indexes_to_pos_tables',1),(50,'2026_05_04_100000_add_eye_to_sale_items_table',1),(51,'2026_05_06_000001_fix_referrals_status_enum',1),(52,'2026_05_07_000001_create_password_reset_requests_table',1),(53,'2026_05_07_100000_add_product_ids_to_lens_orders',1),(54,'2026_05_09_000001_create_app_notifications_table',1),(55,'2026_05_09_000002_create_staff_messages_table',1),(56,'2026_05_09_000003_add_profile_columns_to_users_table',1),(57,'2026_05_09_215659_add_backup_extra_paths_to_settings_table',1),(58,'2026_05_10_120000_add_report_settings_to_settings_table',1),(59,'2026_05_10_130000_add_mail_settings_to_settings_table',1),(60,'2026_05_11_121619_add_va_notation_to_settings_table',1),(61,'2026_05_11_125314_add_sms_settings_to_settings_table',1),(62,'2026_05_11_213037_add_sms_enabled_to_settings_table',1),(63,'2026_05_11_214454_create_sms_templates_table',1),(64,'2026_05_11_215746_add_birthday_sms_filter_to_settings_table',1),(65,'2026_05_11_221459_add_custom_broadcast_sms_template',1),(66,'2026_05_11_222135_add_recall_sms_sent_at_to_patients_table',1),(67,'2026_05_11_222142_add_recall_settings_to_settings_table',1),(68,'2026_05_11_222149_add_recall_sms_template',1),(69,'2026_05_11_223146_create_sms_logs_table',1),(70,'2026_05_14_115943_add_soft_deletes_to_refund_logs_table',1),(71,'2026_05_14_121207_add_workflow_columns_to_refund_logs_table',1),(72,'2026_05_14_131750_add_dashboard_route_to_roles_table',1),(73,'2026_05_15_000001_create_clearance_revoke_logs_table',1),(74,'2026_05_19_000001_add_renewal_fields_to_lens_orders',1),(75,'2026_05_19_000002_add_spectacle_renewal_to_settings',1),(76,'2026_05_19_000003_add_spectacle_renewal_sms_template',1),(77,'2026_05_19_000004_add_renewal_approval_to_lens_orders',1),(78,'2026_05_19_000010_add_performance_indexes',1),(79,'2026_05_19_000011_add_whatsapp_to_settings',1),(80,'2026_05_19_000012_add_channel_to_sms_logs',1),(81,'2026_05_19_000013_create_expense_categories_table',1),(82,'2026_05_19_000014_create_expenses_table',1),(83,'2026_05_19_000015_add_section_to_expense_categories',1),(84,'2026_05_20_101532_add_service_id_to_cashier_patient_clearances_table',1),(85,'2026_05_20_133002_add_performance_indexes',1),(86,'2026_05_20_133534_create_sessions_table',1),(87,'2026_05_21_000001_add_license_fields_to_settings_table',1),(88,'2026_05_22_000001_add_currency_symbol_to_settings_table',1),(89,'2026_05_22_000002_create_quotations_table',1),(90,'2026_05_22_000003_create_quotation_items_table',1),(91,'2026_05_22_000004_create_purchase_orders_table',1),(92,'2026_05_22_000005_create_purchase_order_items_table',1),(93,'2026_05_22_233138_add_receipt_to_expenses_table',1),(94,'2026_05_23_000505_add_sale_id_to_cashier_patient_clearances_table',1),(95,'2026_05_24_000001_add_scalability_indexes',1),(96,'2026_05_24_000002_create_archive_tables',1),(97,'2026_05_25_000001_add_uuid_to_patients_and_clearances',1),(98,'2026_05_29_114937_create_insurers_table',1),(99,'2026_05_29_115133_create_insurance_claims_table',1),(100,'2026_05_29_131341_add_unique_sale_id_to_insurance_claims_table',1),(101,'2026_05_29_134501_add_insurance_fields_to_patients_table',1),(102,'2026_06_01_000001_add_invoice_fields_to_purchase_orders_table',1),(103,'2026_06_01_000002_add_pre_auth_fields_to_insurance_claims_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',2),(6,'App\\Models\\User',3),(4,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_patient_id_foreign` (`patient_id`),
  CONSTRAINT `orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_requests`
--

DROP TABLE IF EXISTS `password_reset_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `actioned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_requests_approved_by_foreign` (`approved_by`),
  KEY `password_reset_requests_email_index` (`email`),
  CONSTRAINT `password_reset_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_requests`
--

LOCK TABLES `password_reset_requests` WRITE;
/*!40000 ALTER TABLE `password_reset_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_documents`
--

DROP TABLE IF EXISTS `patient_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `consultation_id` bigint unsigned DEFAULT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `document_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_documents_uploaded_by_foreign` (`uploaded_by`),
  KEY `patient_documents_patient_id_document_type_index` (`patient_id`,`document_type`),
  KEY `patient_documents_consultation_id_index` (`consultation_id`),
  CONSTRAINT `patient_documents_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patient_documents_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patient_documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_documents`
--

LOCK TABLES `patient_documents` WRITE;
/*!40000 ALTER TABLE `patient_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `pxnumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recall_sms_sent_at` timestamp NULL DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `civil_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurer_id` bigint unsigned DEFAULT NULL,
  `insurance_member_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_policy_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_pxnumber_unique` (`pxnumber`),
  UNIQUE KEY `patients_uuid_unique` (`uuid`),
  KEY `patients_name_index` (`name`),
  KEY `patients_user_id_index` (`user_id`),
  KEY `patients_created_at_index` (`created_at`),
  KEY `patients_insurer_id_foreign` (`insurer_id`),
  CONSTRAINT `patients_insurer_id_foreign` FOREIGN KEY (`insurer_id`) REFERENCES `insurers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_transactions`
--

DROP TABLE IF EXISTS `payment_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `collected_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_transactions_sale_id_foreign` (`sale_id`),
  KEY `payment_transactions_collected_by_foreign` (`collected_by`),
  KEY `pt_created_at_index` (`created_at`),
  KEY `pt_payment_method_index` (`payment_method`),
  KEY `pt_created_method_index` (`created_at`,`payment_method`),
  CONSTRAINT `payment_transactions_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`),
  CONSTRAINT `payment_transactions_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_transactions`
--

LOCK TABLES `payment_transactions` WRITE;
/*!40000 ALTER TABLE `payment_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'manage users','web','2026-06-04 10:53:35','2026-06-04 10:53:35'),(2,'view consultations','web','2026-06-04 10:53:35','2026-06-04 10:53:35'),(3,'perform refraction','web','2026-06-04 10:53:35','2026-06-04 10:53:35'),(4,'manage billing','web','2026-06-04 10:53:35','2026-06-04 10:53:35'),(5,'approve clearance revoke','web','2026-06-04 10:53:35','2026-06-04 10:53:35');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `cost_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `manufacture_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_name_unique` (`name`),
  UNIQUE KEY `products_batch_number_unique` (`batch_number`),
  KEY `products_user_id_foreign` (`user_id`),
  KEY `products_expiry_date_index` (`expiry_date`),
  KEY `products_category_id_index` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_ordered` decimal(10,2) NOT NULL,
  `quantity_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `batch_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','ordered','partial','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `order_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `invoice_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_due_date` date DEFAULT NULL,
  `invoice_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` date DEFAULT NULL,
  `invoice_status` enum('none','invoiced','partial','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint unsigned NOT NULL,
  `received_by` bigint unsigned DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_created_by_foreign` (`created_by`),
  KEY `purchase_orders_received_by_foreign` (`received_by`),
  CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_orders_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation_items`
--

DROP TABLE IF EXISTS `quotation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotation_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '1.00',
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_items_quotation_id_foreign` (`quotation_id`),
  KEY `quotation_items_product_id_foreign` (`product_id`),
  CONSTRAINT `quotation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation_items`
--

LOCK TABLES `quotation_items` WRITE;
/*!40000 ALTER TABLE `quotation_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `patient_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','sent','accepted','expired','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `issue_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotations_quotation_number_unique` (`quotation_number`),
  KEY `quotations_patient_id_foreign` (`patient_id`),
  KEY `quotations_created_by_foreign` (`created_by`),
  CONSTRAINT `quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `quotations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

LOCK TABLES `quotations` WRITE;
/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referral_snippets`
--

DROP TABLE IF EXISTS `referral_snippets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referral_snippets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `letter_type` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referral_snippets_created_by_foreign` (`created_by`),
  CONSTRAINT `referral_snippets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referral_snippets`
--

LOCK TABLES `referral_snippets` WRITE;
/*!40000 ALTER TABLE `referral_snippets` DISABLE KEYS */;
INSERT INTO `referral_snippets` VALUES (1,'referral','reasonForReferral','Specialist Review','Kindly review for further ophthalmic evaluation and management.',1,NULL,'2026-06-04 10:53:27','2026-06-04 10:53:27'),(2,'referral','management','Initial Treatment Given','Initial treatment and counselling have been provided. Patient has been advised to report for specialist care.',1,NULL,'2026-06-04 10:53:27','2026-06-04 10:53:27'),(3,'medical_report','recommendation','Follow-up Recommended','The patient is advised to continue treatment and attend scheduled follow-up appointments.',1,NULL,'2026-06-04 10:53:27','2026-06-04 10:53:27'),(4,'medical_report','clinicalFindings','Clinical Summary','Clinical examination was performed and findings are consistent with the stated diagnosis.',1,NULL,'2026-06-04 10:53:27','2026-06-04 10:53:27'),(5,'excuse_duty','diagnosis','Medical Rest','Patient requires temporary rest from work or school duties for medical reasons.',1,NULL,'2026-06-04 10:53:27','2026-06-04 10:53:27');
/*!40000 ALTER TABLE `referral_snippets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referrals`
--

DROP TABLE IF EXISTS `referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referrals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `letter_type` enum('referral','medical_report','excuse_duty') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'referral',
  `referred_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `issued_by` bigint unsigned DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `printed_by` bigint unsigned DEFAULT NULL,
  `printed_at` timestamp NULL DEFAULT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `referral_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_date` date NOT NULL,
  `patient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_age_sex` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaint` text COLLATE utf8mb4_unicode_ci,
  `va_od` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_os` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refraction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anterior_segment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posterior_segment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinical_findings` text COLLATE utf8mb4_unicode_ci,
  `treatment` text COLLATE utf8mb4_unicode_ci,
  `recommendation` text COLLATE utf8mb4_unicode_ci,
  `excuse_from_date` date DEFAULT NULL,
  `excuse_to_date` date DEFAULT NULL,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `reason_for_referral` text COLLATE utf8mb4_unicode_ci,
  `management` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referrals_referred_by_foreign` (`referred_by`),
  KEY `referrals_patient_id_foreign` (`patient_id`),
  KEY `referrals_updated_by_foreign` (`updated_by`),
  KEY `referrals_issued_by_foreign` (`issued_by`),
  KEY `referrals_printed_by_foreign` (`printed_by`),
  CONSTRAINT `referrals_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referrals_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referrals_printed_by_foreign` FOREIGN KEY (`printed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `referrals_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `referrals_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referrals`
--

LOCK TABLES `referrals` WRITE;
/*!40000 ALTER TABLE `referrals` DISABLE KEYS */;
/*!40000 ALTER TABLE `referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refractions`
--

DROP TABLE IF EXISTS `refractions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refractions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `consultation_id` bigint unsigned NOT NULL,
  `refractionOD` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `refractionOS` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `lensType` text COLLATE utf8mb4_unicode_ci,
  `pd` int DEFAULT NULL,
  `refractionOD_distance_va` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `refractionOD_ADD` text COLLATE utf8mb4_unicode_ci,
  `refractionOD_near_va` text COLLATE utf8mb4_unicode_ci,
  `refractionOS_distance_va` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `refractionOS_ADD` text COLLATE utf8mb4_unicode_ci,
  `refractionOS_near_va` text COLLATE utf8mb4_unicode_ci,
  `refractionnotes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refractions_consultation_id_unique` (`consultation_id`),
  KEY `refractions_user_id_index` (`user_id`),
  KEY `refractions_consultation_id_index` (`consultation_id`),
  CONSTRAINT `refractions_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `refractions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refractions`
--

LOCK TABLES `refractions` WRITE;
/*!40000 ALTER TABLE `refractions` DISABLE KEYS */;
/*!40000 ALTER TABLE `refractions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refund_logs`
--

DROP TABLE IF EXISTS `refund_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refund_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected','processed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `initiated_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `processed_by` bigint unsigned DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `initiated_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refund_logs_sale_id_foreign` (`sale_id`),
  KEY `refund_logs_initiated_by_foreign` (`initiated_by`),
  KEY `refund_logs_approved_by_foreign` (`approved_by`),
  KEY `refund_logs_processed_by_foreign` (`processed_by`),
  KEY `refund_logs_rejected_by_foreign` (`rejected_by`),
  KEY `refund_logs_status_created_index` (`status`,`created_at`),
  CONSTRAINT `refund_logs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refund_logs_initiated_by_foreign` FOREIGN KEY (`initiated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refund_logs_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refund_logs_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `refund_logs_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund_logs`
--

LOCK TABLES `refund_logs` WRITE;
/*!40000 ALTER TABLE `refund_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dashboard_route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','web',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(2,'Doctor','web',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(3,'Cashier','web',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(4,'Staff','web',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(5,'Manager','web',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(6,'Secretary','web',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `cart_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `prescribed_quantity` int NOT NULL DEFAULT '0',
  `dispensed_quantity` int NOT NULL,
  `selling_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `frequency` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eye` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  KEY `sale_items_cart_id_foreign` (`cart_id`),
  KEY `sale_items_sale_product_index` (`sale_id`,`product_id`),
  CONSTRAINT `sale_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `consultation_id` bigint unsigned DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('paid','partial','unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_approved_by` bigint unsigned DEFAULT NULL,
  `is_refunded` tinyint(1) NOT NULL DEFAULT '0',
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refunded_by` bigint unsigned DEFAULT NULL,
  `refund_reason` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_transaction_id_unique` (`transaction_id`),
  KEY `sales_refunded_by_foreign` (`refunded_by`),
  KEY `sales_discount_approved_by_foreign` (`discount_approved_by`),
  KEY `sales_patient_id_index` (`patient_id`),
  KEY `sales_user_id_index` (`user_id`),
  KEY `sales_user_created_at_index` (`user_id`,`created_at`),
  KEY `sales_patient_created_at_index` (`patient_id`,`created_at`),
  KEY `sales_consultation_id_index` (`consultation_id`),
  KEY `sales_created_refunded_index` (`created_at`,`is_refunded`),
  KEY `sales_payment_status_created_index` (`payment_status`,`created_at`),
  CONSTRAINT `sales_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_discount_approved_by_foreign` FOREIGN KEY (`discount_approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_refunded_by_foreign` FOREIGN KEY (`refunded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('92JBo4MBU2vg0INI0Sncl88KEjU4TjUignacyPfN',NULL,'192.168.1.249','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','ZXlKcGRpSTZJbUZKV1hoaGVXazBhVnBYTkVoMVJscHZaVkJCTDNjOVBTSXNJblpoYkhWbElqb2lTSFZwVW5OSk1YZGpNVEV2VW1KR2FXRkZhRFF4WjBaUmVqRkpaMFJzZFdoalYySlZiVkpTTkRKV1RUTnFiR2w0ZVdaSlNXVlVSMkZyU1ROS00zWkRabE5aY1RGUFYwcEpZMUZqV1docGJYSnJWRFF4TjFrNU5sSlZiV0l3YldVcldVaHRjMWcxYzNKSUwwSlBSbGRZWlM5VllVWlhWa2xPWkROak1YVXJRVU53V2pJdmVYbFFhRGg2V0VFeGJDdFJMMnQ1T0ZWU05uY3lWVVV4THpSV1pITnROMDF4ZWpWaFFXMDNOVTVMVlhwcloydFpNMjh6Wm1Jek5XOXdjVlJXUzNkdFZYSlFTME5TYjJNck1UWlZSbG9yYTNGTGVYRmliVWx0V1VaQlMydFZTMkpNSzNCaU9GWlNjR2d5UTFCUVVqRjNNSGcwUm1SMVJqSlNWREJITlhkWVYwOXhZV3cwUmpCVFdWSm9ObFZ0ZEVGaGVubEdhR1p5YlV4c01GWk9NR1Z4ZGt4R1JVSnlRVms5SWl3aWJXRmpJam9pTldaa056TTRNVEF3T1RGaU5ERTVZMlkwTkRBeVptSXlNMlJsWlRVM01UZ3hZMlJsTkRGa01EWTBZVGM1TURBNFpERmlZVEppTVROa01EUm1ZelprWXlJc0luUmhaeUk2SWlKOQ==',1780577894),('bWatpBJp99oMDBJ1VsESBpUcrFsjBQlCjZXV2Bof',NULL,'192.168.0.103','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','ZXlKcGRpSTZJbmRRVm5kVGRHZzNaaTkwTTBrd2JVOWtXRGhxTUZFOVBTSXNJblpoYkhWbElqb2lUV2cyVmpCbmNGTmFXV2h0TVZSblNVUnJia3RUTnpabVZDdEVVREJsVVdOdmFsWTNOM0kzYVhCYVN6UTNTSGw1YjNWQ1FrOTVhRnB4YkRkQmMwUndSRUZ6T0hWcGVuWnNabk5PU0hkSWNUbGpSWGxsT1dKalJEZENPRWRyUm1obVNGcHRWV3B0WlZaNVYwTXhhRmcyZVdWdU5VVlNjRTFMVXk4cmRVSlBlblUyU0d4a2NUTjFVbVZGTlV4RU1sVk5WM0JwV1RSbVFqUk5RWEJ0YWtoclNteDVWblZJV1M5dGEzQlVSbmRoU1RKSGRsZE5RMGMyVDA1TGRpODRkelYwVGtsdVVUZENhbmsxVldsaWVtOUtabE5zUVRKblZUTXllWGh1TkdVd2Vrd3JUWGxPU1cxTFZsZFZibkZrWm5vM1ZrVTRUM1pITkRoV05IUnBLelZPVlZJck5HcHpTREZhVkdWa2NVOVVLemd3WVdoV04zcFFOMGgxY1N0dU1rRkROMlZ3YWpoU1luZ3ZSV3M5SWl3aWJXRmpJam9pWVRkbE1UQXhOelpoTVRkbU9UUTFORFE1WVdRNE16Um1ZVFl6Tnpaa05UY3laV05tTVRCa00yWXpNbVJpT1RjeE9UUmhNRGd5TW1FeU16RTVNR1l4WXlJc0luUmhaeUk2SWlKOQ==',1780578821),('DLgcuCT3UTorkgVJpAkYRfmkPrDUn3rJC7TL4zH4',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','ZXlKcGRpSTZJa2xxWmtac2JtOU5hekF2V0VaQ2VEZ3dVVlJDWlVFOVBTSXNJblpoYkhWbElqb2lVek5DSzFrdlMydFJibGRtUmpCQk1YQnlZazgxVmpremFuaGphWFJZY0djdlNHVkJiMVpFVDNKTU9ISkdLMHhMUjFOTVpXOTRRbmM0UTBZMk4xQmtUemt3V2pJcldHcHVaSGhLU2padmNrVnhOMHR0ZEdSSlFtSlhjWHBIY21vNFZGZE1kVzl3YUZkamFWVktWbHA1Vmk4eVpVZzRTa0ZLUVhFdlpYcGFkVVJXZVdaMFNFNVllVXBES3pkNlkwZ3JkQzlCWTJaT0wzRlBOV1JUYVZVMWNFcExRamxVTDNVM2QwaFRhRkEyUVhSelRVaFFlV3RuVEhsR2NXTmhUekpSTkZwT1JIUkdTV0pLWTNoTlpFbDBXV2h5VmxaUU1FTlpNVlUzUlhwaFFWTk5ZMHM1ZW5ndmJFVlRVMUJ5ZUdSbmQwYzFSa0o0SzBwemRUSlpiRUZJWlN0d1FuQnBRMEZyVGxkSk1YZHVUaTlFUW14NmFtMTNjakZXV0U5bFZtcEhUbEJJVERGYWNWVXhia2xvYldORmNFODFUWHBMYUhKUWFuQlFPR3RZUlhScVIwRkVUVEZKU1RoQk9HcExOME5HUkdKV05tODBLMVpYVWpORVlXaGlhSFYzSzJWT016UmpTVlJFWmsxYWIzZEdjSGhxYjA1Rk56ZHBiamRGYkdSRGFsZ3ZUMk5XVmpFNU9IUm1ZalZCU0c1VVpIVXJkazh2TmxNMlFXeDJhVWcxUkRVNVVVdDNkU3MzU1cxUmRYWXlSRFI0Wm1oelltRlplVWhZZG1WM1ltUmxkVkIwZG00d1VVWmlhM0V2TDNwRlVsSkpOa1J0WkVsMlRIWkhPRnBETTJKbVRXZ3pNbXRpTTFoTFVERXlSMVJqUmk4eU1rSm9lbEpQTTNkcFIybEhaMHhKWW5WeGEyOUdUek1yU1dOQk0ybzVPRlkyTDFZeVMyWnNSekpKUmtOb1VrVkhabkJWWlVZNUwydDFlVk5QV1VNclFuUnhibUZhUjA1TGRWVndJaXdpYldGaklqb2lPVEEwWVRrMU56UTBZV1psTURjd01qRXdNalpqWVdGbE9UVTNNRFEyTkRnNFltWXdaR00xWVRRd05qVTFPVEl6WTJNME1XWXhOV1psTlRReVptTXpaaUlzSW5SaFp5STZJaUo5',1780571896),('mNc5OlXFL1Q2sOk2P6Qdu8msDp2m9v8AiDQ1ajQQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','ZXlKcGRpSTZJbWg0YlZOa1UyMUdSWGhET1Rac1FXNXNjWGN4T1ZFOVBTSXNJblpoYkhWbElqb2lkRkZYYzJKbVRWWkxSbkJSSzB0NWNUWjJSM05aUzBGM09ITkthbHBESzJacVFXUnlNa05IYm5KVGNFSTJlRFpWUW01TVRtNUxkaXR2YVcxcVltZElSMmM1ZFV3MWFIWlFZbVZUTkZkdWVqUk5WRmx2TVhSMGVGRmhlREptVlRsSlVsRlpUME0xVlZSdVQzRllUbTFVYjBSUmRVZDFZV2gzUXl0V1ZteFlkMnMyWTJFM1drOUxSWGhYV21GSlVUQlVVRGhrUkZOdksybGlNbHBwZUZsNllqaDRhbmxyUWxoTFdFUmhhVzgzUkVwWFlra3ZSekk0UVVSTkwzRmplRmQzTTBOWk16RlpZVm92VGs5NUt6RkxZa3RST1VKUlJsZHJSMnhOTkZkQ1JYSnpTVEpGSzJkQ1lXSnhXV000TjJzeWFXOVZTblZHTmpCcGNsVnFNblZ4TDBSaWJYUlJSelJ4Vm5adVMweGtZM2h2U21KalRVRTlQU0lzSW0xaFl5STZJbVU0T0RNMVpUWTBPVFV5TW1Zek0yWTBOakk0T1daa1pHTTJNVEV5TmpSaE5qWmlNalF6TlRVMFlUWmxORGxtTTJRME5UTmxOelEyWmpVNVpqVTFZbVVpTENKMFlXY2lPaUlpZlE9PQ==',1780572270),('mSCQAlYbUQEkLr3Sr2TGjTZiAGeJ7V0DM91FUy0z',NULL,'fe80::8b8:7925:8d68:64b0','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1','ZXlKcGRpSTZJa2RSUzNsbVYyWXlWVUozZVROUksyMW1WRGxYWjFFOVBTSXNJblpoYkhWbElqb2lPVzF5T1UxNGNuaEtPWGhHVldoVGNuVkRaMWhIZEZWTFVXVnNkM1pqYlhGTlFXcEdSMkZ3ZEVSdFNHRjZOa1J1T0ROQ05GTkZSRUoyWkhBeVdFNHJhbGxyTDJSNVUwNXVXVlV4UVhGTGEybENRMUp1TUVWTGJWVkRkVkZPV20xelUxazJPR1o2UTJaemJsZEtVV2M0U1hkMUswUm5OSFF4TjI1YVJtZGpjMUJDWTJsc2RteEtWbmhrZUhWMGNtOHZiV1pvYjJ0dlpEVnFWRTVyUms1WmFXWXdSR1Z6U21OUWRWcDBUMU40WlhkbWJFdFhRVEE0VlRGc2FuWkhSblExTTFZeFZEVkxSeXRwVjFRMU5GaFJOaXRMVW5WV1IwSlFhSEZWYVc5MVVVbFJXRXBNV2xaMVJGSkRNV1YzWTBOR09FcHpTMHg0T0dOUVYwUnpWa0ZhWkRaWGFXRmlVRkV2WjBGTFptZHhRVGRwUnpjMFZYVTBTSGRSV0dSRlJrODNVVzU1TTA1Q1pWbERXRzg5SWl3aWJXRmpJam9pTVRReFlUZ3hNRFpoT1RneE56aGpOalpqTVdFM01qWmhPVFpsWlRKbVptVTVZekkxTXpKaE5tRmhNVGRpWkRNM016VmpOVE00WXpnNU1tUTNabU13WlNJc0luUmhaeUk2SWlKOQ==',1780579098),('oyxAR2Pe7UqmDWilrnrVeGi3nOuuwFCWSuZ7o3AB',NULL,'fe80::8b8:7925:8d68:64b0','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1','ZXlKcGRpSTZJbkJCZEVONWNscDZZMmM1ZEROc2FIVmtWVFZTWlVFOVBTSXNJblpoYkhWbElqb2lPV2RGZVZwT05qZHpiSE5XUW5OdldrRktUa0p5UzFNMkswdGhjWGhDWkhSYVJIVXZXVE5xYXk5UGNEazBPRmhKYkhCNGFHSTBVelJrWWs4dk0ya3pkMmxvYWtGa1pGRk9XbUZxVURZclJYaFhUazE1TlcxdGFIVXhiVVo0ZFhKWlJFOXlibmg1TlU5VVN6STBObWhyUVdJMFlYUnFlRlprYmtSQllXWk9iVGxhVGpaTGVrUXdjbmRqYlcxdGNUQlVaVmQ0V1ZWSU5WUlBTVU0xVjA5MlJGaHBNamxvTW1GaWRWRnVVREZZYVRGbFlrNU1VbUpsUXpGS1N6RTBhakZ2VjJ0cVQyRk9NbmhNZVdWU01Fa3JObTlKUkZZMVZqZGFja05IYjA5TWQyWmFkMVEwY3pkME1tRnJVMUZwUVd4YU5tTkhWbk5VUzFOd0wyVktOQ3QwWmtWblVYWnJkMWRqYmxKdFlVSm5lVGx2V2xscGVGWkZkR0kyTm5odGRsQkJUekJNV2pCd2RWUm5jekE5SWl3aWJXRmpJam9pTjJFek4yVXpOVGd3TldGa056QXdZV1UzTVRjd1pEa3dPRFk1TTJFMU16TTVOV0V4TkdJM01URmhZek5rT1dFNFl6SmhObVptTkdNNE5EQmtOVGMxTmlJc0luUmhaeUk2SWlKOQ==',1780579112),('sYX0pxfKEs5sNmop6T5DYURhZ9b1HJ2CH9Ge6tcy',2,'192.168.1.111','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1','ZXlKcGRpSTZJbTFXVTFsaUsxY3diREJXUjFSMFZHSk1ORGRTUWtFOVBTSXNJblpoYkhWbElqb2lWV2QzUzBWUGFHbEhjV2M1Wlc1eldHNWFiMGhZZFVwQk5qVkRiRFUyYjBOSlVYSkJORkpJYm1KNmJHeHhjamhGVGxJd1RXbElSVEJSUXpaVlRUa3JWbGQ2TW5Cek5GVjNjVVJ3VVRka2VFVjBaVTQzYVRSUFJHMVZSVXgxTHpNdmMyMHJXVXhxZG1WUlUwVnhObWQ1YkhZNFZFdDFlbnB5YTJ4a1ZWTXJOVmgwYzNoVVJXOTVVM1pWU25KQlRXOUxaeXRTWWxoQ1FrcDFRa0UzV1ZJeUswSkpUWE41UWxZdmJUaGlUV0pyTW1jMU1YVjRWM1ZpTHprclVIY3JkemRyVERGMFZqRk5ORnBsU25WWU9HWnJSVlozVVRGak1sZDRMemw1VFdGa01GRk9kSEJMTUZOU1pHZzJSVmRGWm5FMWNEVlRNemh6Y21zNE9FTXJUa1YxTkd0ck5FcFZUV3czV25WMk9GZHVkMVp2YkM5WlJsZzVRbHB6TnpKcEwwOTNSelpFVm1wNmNUa3pjSEI1U0dsUVRrZDRkMGxSYW1SUWRUTnNZWGMwUTJkb1FrTkVWbVU0ZGpkV2RsUnZVa3c0TTFGMkwybDBVRnBPTW5OWFQzb3hXSGcwUVVaRlEyRnZPR1pEVGtweFl6QktSWGNyZDJ0UlZWVmxjRGRwVFhKd1dUSkZhSEV2WXpFNWNYSmpRbFJVTWxaUlpESm5WVEpLWm1sUVJHdHBTMHhDWkRScVdrVXJhMjloTmtKTlV6UnVVbWh6TkZCaVlXZEhWRlEzU25Wck9VUnFXbTFSUkM5NmJrMUJZMFpOUTI4M1JpdFFkRWhUVkdzdmNESXdiVVJyVDJZNEsxTmFUMDUzVVZOeVUzQmxlRVJCTWt4M1VFMXNhVk5sTWtWWFRYUjZTRTFsUlhScVdXNVZSelpEVmxSWlpIZ3JWQzkzYWk5d1NrbHBPV05NUzFCWlNuWmlOR1JpZFdaVGNucDVURVl4YzI1Q1owWkNZalpvUmxOaWVGVnNURGd6VG5vMlRHdzFUVFp1T0djNE4zRjVRbkJyUVQwOUlpd2liV0ZqSWpvaU5EWTVZak15TXpkbFpHVmlOakF5WTJZME5qSmtZekF6T0RNNE1qSXhaVEZtTm1NMk5HRmpZMlkxWlRGaE5qaG1ObVEwWmpNNE9ETmhNVEEwWm1NMU1DSXNJblJoWnlJNklpSjk=',1780578454),('xJF5IKOSUz1Qd2zf142KQd5aql7J6gacBDz8KjNR',NULL,'fe80::8b8:7925:8d68:64b0','Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/149.0.7827.45 Mobile/15E148 Safari/604.1','ZXlKcGRpSTZJazV1TTBsaFVIaFpVbmxUTUd0VFFrcFJWMGRHVDFFOVBTSXNJblpoYkhWbElqb2lXalZKYjNGblFWSnVlbll6YWpCRU1sQmhlbTVCYVhCamVUVjFjWEJhUmt3MFExZ3JZbE16ZWtKUVl6UjBUR2xYUTJKRVdXRmxTM1pNYkhWTU1rRlJXR05JZEhSaWVIWkpaREJYTmpkbFVqSlRObEZ0TjBFMFJHVjFWbmxwWVdNME0xbGFZVmx5ZGxrcmJISjRjbWcyU1ZvMkwyNXFSU3RuTTFWak4zSnNRVVJUVVcxUmJFeEtaM3BPYUdwaVlrSTBiR1JXTXpWaVQzaExNbUZDUjFkdk9VNWthMFJQVG5BMU9FcG9VRGRKUVRsSFprMVplVXRxV1hsM2VuaFBhRGxGT0U1T1UyZGtUWEZEV0dKblZFTmhNR28wY1RsdmVUbDJia2xwTmtjMllVRnVVMU5NT1VScFVYcEZhamRLZVVoWWRXdFpTMkZ1YTNGcVIySkNOMDlKTDB0dFkweHdSRGxDT1hwUEwzQjJUemcyV1VWS2VYaGtUWFZUYWxkR1YxVlhUVVV2TWpWT1JHNXVNbXBXTld4alFuZGxMMGR5TjFGWmJFNUtXRnBEVFdSc1ZXZDZjbFF6Unl0RFpIWnBRMGN6VTBGTFZVdG1UR2R0T1dGeFZDdDNMM1F2VXpnd1dXeHhWREJTZEdOdU5uRjVRazUxT1dOSk4wRTJlVnA1VW5ocVRuVm5UMEkyTjNRMlNYbGtaRUp0VmtKT2JqWnlZMFJ2VkhwWlpXa3JaemhqYzNoNVNTdG1kSFp3VVQwaUxDSnRZV01pT2lJd1lUTmtaalZrWVRkaVl6UTVNREZtTVRrek56QTNZVFkxTVRFeE5qRXpZall5T1RVeFpqQXlPVEU1WWpBMk9UY3lOR1ExTURKaU1qZzBORE0wTm1aaElpd2lkR0ZuSWpvaUluMD0=',1780579097);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clinic_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'My Eye Clinic',
  `clinic_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `backup_extra_paths` json DEFAULT NULL,
  `report_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `report_frequency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily',
  `report_day` tinyint NOT NULL DEFAULT '1',
  `report_time` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '08:00',
  `report_recipients` json DEFAULT NULL,
  `smtp_host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_port` smallint unsigned NOT NULL DEFAULT '587',
  `smtp_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_password` text COLLATE utf8mb4_unicode_ci,
  `smtp_encryption` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_from_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_notation` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '6m',
  `currency_symbol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GH₵',
  `license_key` text COLLATE utf8mb4_unicode_ci,
  `installation_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_last_seen` date DEFAULT NULL,
  `trial_started_at` date DEFAULT NULL,
  `sms_api_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_api_key` text COLLATE utf8mb4_unicode_ci,
  `sms_sender_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `whatsapp_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `whatsapp_phone_number_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_access_token` text COLLATE utf8mb4_unicode_ci,
  `whatsapp_appt_template` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'appointment_reminder',
  `whatsapp_appt_template_lang` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'en',
  `whatsapp_birthday_template` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_recall_template` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_renewal_template` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_bulk_channel` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'sms',
  `birthday_sms_filter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `birthday_sms_custom_months` int unsigned DEFAULT NULL,
  `recall_sms_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `recall_months` int unsigned NOT NULL DEFAULT '12',
  `spectacle_renewal_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `spectacle_renewal_reminder_days` tinyint unsigned NOT NULL DEFAULT '30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'My Eye Clinic',NULL,'Your Address Here','Your Contact Number','info@clinic.com',NULL,0,'daily',1,'08:00',NULL,NULL,587,NULL,NULL,NULL,NULL,NULL,'6m','GH₵',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL,NULL,'appointment_reminder','en',NULL,NULL,NULL,'sms','all',NULL,0,12,1,30,'2026-06-04 10:54:02','2026-06-04 10:54:02');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_logs`
--

DROP TABLE IF EXISTS `sms_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned DEFAULT NULL,
  `template_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sms',
  `recipient` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `error` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sms_logs_patient_id_index` (`patient_id`),
  KEY `sms_logs_template_key_index` (`template_key`),
  KEY `sms_logs_created_at_index` (`created_at`),
  CONSTRAINT `sms_logs_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_logs`
--

LOCK TABLES `sms_logs` WRITE;
/*!40000 ALTER TABLE `sms_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_logs_archive`
--

DROP TABLE IF EXISTS `sms_logs_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_logs_archive` (
  `id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `template_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sms',
  `recipient` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `error` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sms_logs_archive_created_at_index` (`created_at`),
  KEY `sms_logs_archive_patient_id_index` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_logs_archive`
--

LOCK TABLES `sms_logs_archive` WRITE;
/*!40000 ALTER TABLE `sms_logs_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `sms_logs_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sms_templates`
--

DROP TABLE IF EXISTS `sms_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `placeholders` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sms_templates_key_unique` (`key`),
  KEY `sms_templates_key_index` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms_templates`
--

LOCK TABLES `sms_templates` WRITE;
/*!40000 ALTER TABLE `sms_templates` DISABLE KEYS */;
INSERT INTO `sms_templates` VALUES (1,'appointment_booking','Appointment Booking Confirmation','Hello [NAME], your appointment at [CLINIC] is confirmed for [DATE] at [TIME] – [REASON].','[\"[NAME]\", \"[DATE]\", \"[TIME]\", \"[REASON]\", \"[CLINIC]\"]','2026-06-04 10:53:30','2026-06-04 10:53:30'),(2,'appointment_reminder','Appointment Reminder','Hello [NAME], this is a reminder of your appointment at [CLINIC] on [DATE] at [TIME] – [REASON]. Please be on time.','[\"[NAME]\", \"[DATE]\", \"[TIME]\", \"[REASON]\", \"[CLINIC]\"]','2026-06-04 10:53:30','2026-06-04 10:53:30'),(3,'spectacles_ready','Spectacles Ready for Pickup','Hello [NAME], your spectacles (Order [ORDER_ID]) are ready for collection at [CLINIC]. Please bring this message when you come in.','[\"[NAME]\", \"[ORDER_ID]\", \"[CLINIC]\"]','2026-06-04 10:53:30','2026-06-04 10:53:30'),(4,'spectacles_reminder','Spectacles Pickup Reminder','Hello [NAME], your spectacles (Order [ORDER_ID]) are still waiting for collection at [CLINIC]. Please come in at your earliest convenience.','[\"[NAME]\", \"[ORDER_ID]\", \"[CLINIC]\"]','2026-06-04 10:53:30','2026-06-04 10:53:30'),(5,'payment_receipt','Payment Receipt','Hello [NAME], payment of GHS [AMOUNT] received at [CLINIC]. Transaction: [TXN_ID]. Thank you!','[\"[NAME]\", \"[AMOUNT]\", \"[TXN_ID]\", \"[CLINIC]\"]','2026-06-04 10:53:30','2026-06-04 10:53:30'),(6,'birthday_wishes','Birthday Wishes','Happy Birthday [NAME]! Wishing you good health and clear vision. From all of us at [CLINIC].','[\"[NAME]\", \"[CLINIC]\"]','2026-06-04 10:53:30','2026-06-04 10:53:30'),(7,'custom_broadcast','Custom Broadcast','Dear [NAME], [CLINIC] wishes you a joyful [OCCASION]! Thank you for trusting us with your eye care.','[\"[NAME]\", \"[CLINIC]\", \"[OCCASION]\"]','2026-06-04 10:53:31','2026-06-04 10:53:31'),(8,'patient_recall','Patient Recall','Hello [NAME], it\'s been a while since your last visit to [CLINIC]. Your eyes deserve regular care — book your next check-up today. Call us anytime!','[\"[NAME]\", \"[CLINIC]\"]','2026-06-04 10:53:31','2026-06-04 10:53:31'),(9,'appointment_auto_reminder','Appointment Auto-Reminder','Hello [NAME], this is a reminder of your appointment at [CLINIC] tomorrow, [DATE] at [TIME]. Please call us if you need to reschedule.','[\"[NAME]\", \"[CLINIC]\", \"[DATE]\", \"[TIME]\"]','2026-06-04 10:53:31','2026-06-04 10:53:31'),(10,'spectacle_renewal','Spectacle Renewal Reminder','Dear [NAME], your spectacles are due for renewal on [DATE]. Please visit [CLINIC] for your annual eye review.','[\"[NAME]\", \"[DATE]\", \"[CLINIC]\"]','2026-06-04 10:53:31','2026-06-04 10:53:31');
/*!40000 ALTER TABLE `sms_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spectacles`
--

DROP TABLE IF EXISTS `spectacles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spectacles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spectacles`
--

LOCK TABLES `spectacles` WRITE;
/*!40000 ALTER TABLE `spectacles` DISABLE KEYS */;
/*!40000 ALTER TABLE `spectacles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_messages`
--

DROP TABLE IF EXISTS `staff_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `recipient_id` bigint unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_msg_recipient_read_idx` (`recipient_id`,`read_at`),
  KEY `staff_messages_sender_id_index` (`sender_id`),
  KEY `staff_messages_parent_id_index` (`parent_id`),
  CONSTRAINT `staff_messages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `staff_messages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_messages_recipient_id_foreign` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `staff_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_messages`
--

LOCK TABLES `staff_messages` WRITE;
/*!40000 ALTER TABLE `staff_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_before` int NOT NULL DEFAULT '0',
  `quantity` int NOT NULL DEFAULT '0',
  `quantity_after` int NOT NULL DEFAULT '0',
  `cost_price` decimal(12,2) DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_movements_reference_no_unique` (`reference_no`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  KEY `stock_movements_movement_type_created_at_index` (`movement_type`,`created_at`),
  KEY `stock_movements_product_id_created_at_index` (`product_id`,`created_at`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_time_days` smallint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
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
  `phone` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `department` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_password_changed_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_staff_id_unique` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator','admin@eyeclinic.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-06-04 10:53:35','$2y$10$3SMRUSDAjoJts.mY4P6Rt.dbYG/W/OFq1H9t7NcF4/CdGmLPf9AAu',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(2,'Dr. Kingsford','frimkings@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-06-04 10:53:35','$2y$10$nJDwqPB4G6I9eVcUw8kzUeafucknSgzi9NlY/GSmc6v..yV52VRe6',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(3,'Secretary','secretary@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-06-04 10:53:35','$2y$10$1V04Zs2rGEqPebGl4b2b3OF.q8da.GKNoD0VssbqnxhqS7LRsK4z.',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35'),(4,'Front Desk Staff','staff@eyeclinic.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-06-04 10:53:35','$2y$10$mcCTuidsswwQJqqHachz4eW.vjpoTX2OPxQ8jW2TRq2auvVpkyqQ.',NULL,'2026-06-04 10:53:35','2026-06-04 10:53:35');
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

-- Dump completed on 2026-06-04 13:28:07
