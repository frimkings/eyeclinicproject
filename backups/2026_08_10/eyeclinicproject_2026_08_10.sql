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
  `type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fas fa-bell',
  `icon_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text-primary',
  `action_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_notifs_user_read_idx` (`user_id`,`read_at`),
  CONSTRAINT `app_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_notifications`
--

LOCK TABLES `app_notifications` WRITE;
/*!40000 ALTER TABLE `app_notifications` DISABLE KEYS */;
INSERT INTO `app_notifications` VALUES (1,1,'clearance_revoke_requested','Clearance Revoke Request','Joselyne Bonsu requested revoke of clearance for Bernice Sarpong.','fas fa-undo','text-warning','http://192.168.100.67/admin/clearance-revoke-approvals',NULL,NULL,'2026-07-13 14:10:04','2026-07-13 14:10:04'),(2,1,'clearance_revoke_requested','Clearance Revoke Request','Joselyne Bonsu requested revoke of clearance for Mavis Sanahene.','fas fa-undo','text-warning','http://192.168.100.67/admin/clearance-revoke-approvals',NULL,NULL,'2026-07-13 14:10:11','2026-07-13 14:10:11'),(3,1,'clearance_revoke_requested','Clearance Revoke Request','Joselyne Bonsu requested revoke of clearance for Heroine Sanahene.','fas fa-undo','text-warning','http://192.168.100.67/admin/clearance-revoke-approvals',NULL,NULL,'2026-07-13 14:10:16','2026-07-13 14:10:16'),(4,1,'clearance_revoke_requested','Clearance Revoke Request','Joselyne Bonsu requested revoke of clearance for Matilda Arhin-Sey.','fas fa-undo','text-warning','http://192.168.100.67/admin/clearance-revoke-approvals',NULL,NULL,'2026-07-13 14:10:24','2026-07-13 14:10:24'),(5,1,'clearance_revoke_requested','Clearance Revoke Request','Joselyne Bonsu requested revoke of clearance for NANA SACKEY.','fas fa-undo','text-warning','http://192.168.100.67/admin/clearance-revoke-approvals',NULL,NULL,'2026-07-13 14:10:29','2026-07-13 14:10:29'),(6,3,'clearance_revoke_approved','Revoke Request Approved','System Administrator approved your clearance revoke request for NANA SACKEY.','fas fa-check-circle','text-success','http://192.168.100.67/secretary/patient-clearance',NULL,NULL,'2026-07-13 14:19:46','2026-07-13 14:19:46'),(7,3,'clearance_revoke_approved','Revoke Request Approved','System Administrator approved your clearance revoke request for Heroine Sanahene.','fas fa-check-circle','text-success','http://192.168.100.67/secretary/patient-clearance',NULL,NULL,'2026-07-13 14:19:48','2026-07-13 14:19:48'),(8,3,'clearance_revoke_approved','Revoke Request Approved','System Administrator approved your clearance revoke request for Matilda Arhin-Sey.','fas fa-check-circle','text-success','http://192.168.100.67/secretary/patient-clearance',NULL,NULL,'2026-07-13 14:19:50','2026-07-13 14:19:50'),(9,3,'clearance_revoke_approved','Revoke Request Approved','System Administrator approved your clearance revoke request for Mavis Sanahene.','fas fa-check-circle','text-success','http://192.168.100.67/secretary/patient-clearance',NULL,NULL,'2026-07-13 14:19:53','2026-07-13 14:19:53'),(10,3,'clearance_revoke_approved','Revoke Request Approved','System Administrator approved your clearance revoke request for Bernice Sarpong.','fas fa-check-circle','text-success','http://192.168.100.67/secretary/patient-clearance',NULL,NULL,'2026-07-13 14:19:56','2026-07-13 14:19:56'),(11,1,'low_stock','Low Stock: Tobralant','OUT OF STOCK — restock soon.','fas fa-exclamation-triangle','text-danger','http://192.168.100.67/admin/inventory-alerts',NULL,NULL,'2026-07-13 14:27:20','2026-07-13 14:27:20'),(12,3,'low_stock','Low Stock: Tobralant','OUT OF STOCK — restock soon.','fas fa-exclamation-triangle','text-danger','http://192.168.100.67/admin/inventory-alerts',NULL,NULL,'2026-07-13 14:27:20','2026-07-13 14:27:20'),(13,1,'refund_requested','Refund Request Submitted','Joselyne Bonsu requested a refund for transaction #13072026-2WA1VGQ7.','fas fa-undo','text-warning','http://192.168.100.67/admin/refund-approvals',NULL,NULL,'2026-07-13 14:32:57','2026-07-13 14:32:57'),(14,3,'refund_approved','Refund Request Approved','Your refund request for transaction #13072026-2WA1VGQ7 was approved by Joselyne Bonsu.','fas fa-check-circle','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-13 14:33:31','2026-07-13 14:33:31'),(15,3,'refund_processed','Refund Processed','The refund for transaction #13072026-2WA1VGQ7 has been executed by Joselyne Bonsu.','fas fa-check-double','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-14 09:37:35','2026-07-14 09:37:35'),(16,1,'spectacles_ready','Spectacles Ready: Jayder Adjei-Dwomoh','Order ORD-F3V4JQKT is ready for collection.','fas fa-glasses','text-info','http://192.168.100.67/secretary/spectacles','{\"order_id\": 1}',NULL,'2026-07-17 17:01:22','2026-07-17 17:01:22'),(17,1,'spectacles_ready','Spectacles Ready: Jayder Adjei-Dwomoh','Order ORD-F3V4JQKT is ready for collection.','fas fa-glasses','text-info','http://192.168.100.67/secretary/spectacles','{\"order_id\": 1}',NULL,'2026-07-17 17:01:25','2026-07-17 17:01:25'),(18,1,'low_stock','Low Stock: Dexatrol','1 unit left — restock soon.','fas fa-exclamation-triangle','text-warning','http://192.168.100.67/admin/inventory-alerts',NULL,NULL,'2026-07-18 14:30:52','2026-07-18 14:30:52'),(19,3,'low_stock','Low Stock: Dexatrol','1 unit left — restock soon.','fas fa-exclamation-triangle','text-warning','http://192.168.100.67/admin/inventory-alerts',NULL,NULL,'2026-07-18 14:30:52','2026-07-18 14:30:52'),(20,3,'refund_requested','Refund Request Submitted','System Administrator requested a refund for transaction #20072026-TKYW4CS9.','fas fa-undo','text-warning','http://192.168.100.67/admin/refund-approvals',NULL,NULL,'2026-07-20 09:58:59','2026-07-20 09:58:59'),(21,1,'refund_approved','Refund Request Approved','Your refund request for transaction #20072026-TKYW4CS9 was approved by System Administrator.','fas fa-check-circle','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-20 09:59:20','2026-07-20 09:59:20'),(22,1,'spectacles_ready','Spectacles Ready: Kwasi Korang Incoom','Order ORD-TTDPLYHO is ready for collection.','fas fa-glasses','text-info','http://192.168.100.67/secretary/spectacles','{\"order_id\": 4}',NULL,'2026-07-20 10:06:54','2026-07-20 10:06:54'),(23,1,'spectacles_ready','Spectacles Ready: Kwasi Korang Incoom','Order ORD-TTDPLYHO is ready for collection.','fas fa-glasses','text-info','http://192.168.100.67/secretary/spectacles','{\"order_id\": 4}',NULL,'2026-07-20 10:06:57','2026-07-20 10:06:57'),(24,1,'spectacles_ready','Spectacles Ready: Abena Yeboah Incoom','Order ORD-5MPB36UE is ready for collection.','fas fa-glasses','text-info','http://192.168.100.67/secretary/spectacles','{\"order_id\": 3}',NULL,'2026-07-20 10:09:44','2026-07-20 10:09:44'),(25,1,'refund_processed','Refund Processed','The refund for transaction #20072026-TKYW4CS9 has been executed by Joselyne Bonsu.','fas fa-check-double','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-20 15:43:52','2026-07-20 15:43:52'),(26,1,'refund_requested','Refund Request Submitted','Joselyne Bonsu requested a refund for transaction #25072026-BJGWZ1BY.','fas fa-undo','text-warning','http://192.168.100.67/admin/refund-approvals',NULL,NULL,'2026-07-25 10:43:43','2026-07-25 10:43:43'),(27,3,'refund_approved','Refund Request Approved','Your refund request for transaction #25072026-BJGWZ1BY was approved by Joselyne Bonsu.','fas fa-check-circle','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-25 10:44:03','2026-07-25 10:44:03'),(28,3,'refund_processed','Refund Processed','The refund for transaction #25072026-BJGWZ1BY has been executed by Joselyne Bonsu.','fas fa-check-double','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-25 10:44:49','2026-07-25 10:44:49'),(29,1,'spectacles_ready','Spectacles Ready: Stacy Morkor Quarshie','Order ORD-DHVE3AJR is ready for collection.','fas fa-glasses','text-info','http://192.168.100.67/secretary/spectacles','{\"order_id\": 5}',NULL,'2026-07-29 15:20:18','2026-07-29 15:20:18'),(30,1,'refund_requested','Refund Request Submitted','Joselyne Bonsu requested a refund for transaction #30072026-BWU7KKVT.','fas fa-undo','text-warning','http://192.168.100.67/admin/refund-approvals',NULL,NULL,'2026-07-30 10:52:26','2026-07-30 10:52:26'),(31,3,'refund_approved','Refund Request Approved','Your refund request for transaction #30072026-BWU7KKVT was approved by Joselyne Bonsu.','fas fa-check-circle','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-30 10:52:48','2026-07-30 10:52:48'),(32,3,'refund_processed','Refund Processed','The refund for transaction #30072026-BWU7KKVT has been executed by Joselyne Bonsu.','fas fa-check-double','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-30 10:52:53','2026-07-30 10:52:53'),(33,1,'refund_requested','Refund Request Submitted','Joselyne Bonsu requested a refund for transaction #30072026-RHLD3XBM.','fas fa-undo','text-warning','http://192.168.100.67/admin/refund-approvals',NULL,NULL,'2026-07-30 12:11:45','2026-07-30 12:11:45'),(34,3,'refund_approved','Refund Request Approved','Your refund request for transaction #30072026-RHLD3XBM was approved by Joselyne Bonsu.','fas fa-check-circle','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-30 12:12:05','2026-07-30 12:12:05'),(35,3,'refund_processed','Refund Processed','The refund for transaction #30072026-RHLD3XBM has been executed by Joselyne Bonsu.','fas fa-check-double','text-success','http://192.168.100.67/cashier/sales-records',NULL,NULL,'2026-07-30 12:12:12','2026-07-30 12:12:12');
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
  `doctor_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `recall_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reminder_channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'whatsapp',
  `reminder_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_sent',
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
  `missed_at` timestamp NULL DEFAULT NULL,
  `arrived_at` timestamp NULL DEFAULT NULL,
  `doctor_started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `duration_minutes` smallint unsigned NOT NULL DEFAULT '30',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_user_id_foreign` (`user_id`),
  KEY `appointments_scheduled_at_index` (`scheduled_at`),
  KEY `appointments_status_index` (`status`),
  KEY `appointments_scheduled_at_status_index` (`scheduled_at`,`status`),
  KEY `appointments_doctor_id_scheduled_at_index` (`doctor_id`,`scheduled_at`),
  KEY `appointments_patient_id_scheduled_at_index` (`patient_id`,`scheduled_at`),
  CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,25,NULL,4,'Follow-up Visit','Routine Review',NULL,'sms','not_sent',NULL,NULL,NULL,NULL,NULL,'2026-08-06 11:01:00',30,'Pending',NULL,'2026-07-30 11:01:25','2026-07-30 11:01:25');
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
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `event` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=288 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_trails`
--

LOCK TABLES `audit_trails` WRITE;
/*!40000 ALTER TABLE `audit_trails` DISABLE KEYS */;
INSERT INTO `audit_trails` VALUES (1,1,NULL,NULL,NULL,'license.activated','License activated successfully. Tier: pro',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 14:05:31','2026-07-06 14:05:31'),(2,3,1,'App\\Models\\Patient',1,'patient.created','Registered new patient: NANA SACKEY (PX-2929-26)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 10:37:04','2026-07-09 10:37:04'),(3,3,NULL,NULL,NULL,'patient.exported','Exported 1 selected patient(s).',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 11:14:55','2026-07-09 11:14:55'),(4,3,1,'App\\Models\\Patient',1,'patient.updated','Updated patient profile: NANA SACKEY (PX-2929-26)','{\"dob\": \"1945-05-06\", \"name\": \"NANA SACKEY\", \"email\": \"\", \"gender\": \"Male\", \"address\": \"ABOKOBI\", \"contact\": \"0243275084\", \"insurer_id\": null, \"occupation\": \"\", \"civil_status\": \"\", \"insurance_member_id\": \"\", \"insurance_policy_number\": \"\"}','{\"dob\": \"1945-05-06\", \"name\": \"NANA SACKEY\", \"email\": \"\", \"gender\": \"Male\", \"address\": \"ABOKOBI\", \"contact\": \"0243275084\", \"insurer_id\": null, \"occupation\": \"\", \"civil_status\": \"\", \"insurance_member_id\": \"\", \"insurance_policy_number\": \"\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 11:22:04','2026-07-09 11:22:04'),(5,1,NULL,'App\\Models\\Insurer',1,'insurer.created','Created insurer: Star Health Insurance',NULL,'{\"code\": \"\", \"name\": \"Star Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0508426224\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 15:59:41','2026-07-09 15:59:41'),(6,1,NULL,'App\\Models\\Insurer',2,'insurer.created','Created insurer: Acacia Health Insurance',NULL,'{\"code\": \"\", \"name\": \"Acacia Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0596921844\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:00:24','2026-07-09 16:00:24'),(7,1,NULL,'App\\Models\\Insurer',3,'insurer.created','Created insurer: Phoenix Health Insurance',NULL,'{\"code\": \"\", \"name\": \"Phoenix Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0243172646\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:01:11','2026-07-09 16:01:11'),(8,1,NULL,'App\\Models\\Insurer',4,'insurer.created','Created insurer: OctaPlus Health Insurance',NULL,'{\"code\": \"\", \"name\": \"OctaPlus Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:01:58','2026-07-09 16:01:58'),(9,1,NULL,'App\\Models\\Insurer',5,'insurer.created','Created insurer: Equity Health Insurance',NULL,'{\"code\": \"\", \"name\": \"Equity Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0202543316\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:02:49','2026-07-09 16:02:49'),(10,1,NULL,'App\\Models\\Insurer',6,'insurer.created','Created insurer: Glico Health Insurance',NULL,'{\"code\": \"\", \"name\": \"Glico Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0302746500\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:03:33','2026-07-09 16:03:33'),(11,1,NULL,'App\\Models\\Insurer',7,'insurer.created','Created insurer: Ace Medical Insurance',NULL,'{\"code\": \"\", \"name\": \"Ace Medical Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0257960860\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:04:28','2026-07-09 16:04:28'),(12,1,NULL,'App\\Models\\Insurer',8,'insurer.created','Created insurer: Cosmopolitan Health Insurance',NULL,'{\"code\": \"\", \"name\": \"Cosmopolitan Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0501529305\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:05:09','2026-07-09 16:05:09'),(13,1,NULL,'App\\Models\\Insurer',9,'insurer.created','Created insurer: emPle Health Insurance',NULL,'{\"code\": \"\", \"name\": \"emPle Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0509791510\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:05:51','2026-07-09 16:05:51'),(14,1,NULL,'App\\Models\\Insurer',10,'insurer.created','Created insurer: DOSH Health Insurance',NULL,'{\"code\": \"\", \"name\": \"DOSH Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:06:18','2026-07-09 16:06:18'),(15,1,NULL,'App\\Models\\Insurer',11,'insurer.created','Created insurer: GAB Health Insurance',NULL,'{\"code\": \"\", \"name\": \"GAB Health Insurance\", \"notes\": \"\", \"active\": true, \"scheme_type\": \"Private\", \"contact_phone\": \"0557722516\", \"contact_person\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 16:06:52','2026-07-09 16:06:52'),(16,3,1,'App\\Models\\CashierPatientClearance',1,'clearance.created','Created clearance for NANA SACKEY (Paid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 17:48:25','2026-07-09 17:48:25'),(17,3,1,'App\\Models\\Sales',1,'sale.created','Clearance sale: NANA SACKEY — Tonometry (GH₵ 100) | Cash GH₵100.00',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 17:48:25','2026-07-09 17:48:25'),(18,1,1,'App\\Models\\Consultations',1,'consultation.created','Created consultation #1',NULL,'{\"items\": 0, \"chiefComplaint\": \"To check Intraocular pressure (IOP)\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 18:26:36','2026-07-09 18:26:36'),(19,3,2,'App\\Models\\Patient',2,'patient.created','Registered new patient: Matilda Arhin-Sey (PX-5223-26)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-10 09:42:38','2026-07-10 09:42:38'),(20,3,2,'App\\Models\\CashierPatientClearance',2,'clearance.created','Created clearance for Matilda Arhin-Sey (Paid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-10 09:44:38','2026-07-10 09:44:38'),(21,3,2,'App\\Models\\Sales',2,'sale.created','Clearance sale: Matilda Arhin-Sey — Autorefraction (GH₵ 100) | Cash GH₵100.00',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-10 09:44:38','2026-07-10 09:44:38'),(22,3,1,'App\\Models\\CashierPatientClearance',3,'clearance.created','Created clearance for NANA SACKEY (Paid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 10:24:13','2026-07-10 10:24:13'),(23,3,1,'App\\Models\\Sales',3,'sale.created','Clearance sale: NANA SACKEY — Tonometry (GH₵ 100) | Cash GH₵100.00',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 10:24:14','2026-07-10 10:24:14'),(24,4,1,'App\\Models\\Consultations',2,'consultation.created','Created consultation #2',NULL,'{\"items\": 0, \"chiefComplaint\": \"To check IOP\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 10:37:17','2026-07-10 10:37:17'),(25,4,2,'App\\Models\\Consultations',3,'consultation.created','Created consultation #3',NULL,'{\"items\": 0, \"chiefComplaint\": \"To do IOP, Fundus Photo, Auto Refraction and OCT (Optic Nerve and Macula analysis)\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 10:40:54','2026-07-10 10:40:54'),(26,1,3,'App\\Models\\Patient',3,'patient.created','Registered new patient: Heroine Sanahene (PX-8903-26)',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:25:13','2026-07-10 11:25:13'),(27,1,3,'App\\Models\\Patient',3,'patient.updated','Updated patient profile: Heroine Sanahene (PX-8903-26)','{\"dob\": \"2012-10-17\", \"name\": \"Heroine Sanahene\", \"email\": \"\", \"gender\": \"Female\", \"address\": \"Abokobi\", \"contact\": \"0244169392\", \"insurer_id\": null, \"occupation\": \"Student\", \"civil_status\": \"Married\", \"insurance_member_id\": \"\", \"insurance_policy_number\": \"\"}','{\"dob\": \"2012-10-17\", \"name\": \"Heroine Sanahene\", \"email\": \"\", \"gender\": \"Female\", \"address\": \"Abokobi\", \"contact\": \"0244169392\", \"insurer_id\": null, \"occupation\": \"Student\", \"civil_status\": \"Single\", \"insurance_member_id\": \"\", \"insurance_policy_number\": \"\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:25:47','2026-07-10 11:25:47'),(28,1,3,'App\\Models\\CashierPatientClearance',4,'clearance.created','Created clearance for Heroine Sanahene (Unpaid)',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:26:05','2026-07-10 11:26:05'),(29,1,3,'App\\Models\\CashierPatientClearance',4,'clearance.status_updated','Updated payment status to Paid for clearance #4','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Paid\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:26:32','2026-07-10 11:26:32'),(30,1,4,'App\\Models\\Patient',4,'patient.created','Registered new patient: Mavis Sanahene (PX-2380-26)',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:29:42','2026-07-10 11:29:42'),(31,1,3,'App\\Models\\Consultations',4,'consultation.created','Created consultation #4',NULL,'{\"items\": 1, \"chiefComplaint\": \"For Fundus Photograph\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:43:15','2026-07-10 11:43:15'),(32,1,3,'App\\Models\\Consultations',4,'prescription.updated','Updated prescription for Consultation #4',NULL,'{\"items\": 1, \"total\": 200}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:43:35','2026-07-10 11:43:35'),(33,3,5,'App\\Models\\Patient',5,'patient.created','Registered new patient: Bernice Sarpong (PX-9681-26)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 15:01:40','2026-07-10 15:01:40'),(34,3,5,'App\\Models\\CashierPatientClearance',5,'clearance.created','Created clearance for Bernice Sarpong (Unpaid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 15:12:09','2026-07-10 15:12:09'),(35,4,5,'App\\Models\\Consultations',5,'consultation.created','Created consultation #5',NULL,'{\"items\": 0, \"chiefComplaint\": \"For IOP & Autorefraction\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 15:13:29','2026-07-10 15:13:29'),(36,4,5,'App\\Models\\Consultations',5,'prescription.updated','Updated prescription for Consultation #5',NULL,'{\"items\": 2, \"total\": 200}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 15:13:54','2026-07-10 15:13:54'),(37,3,5,'App\\Models\\Sales',4,'payment.received','Recorded full payment for sale 10072026-TYRB0RJX',NULL,'{\"items\": 2, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 15:14:44','2026-07-10 15:14:44'),(38,1,NULL,NULL,NULL,'license.activated','License activated successfully. Tier: pro',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 10:32:54','2026-07-13 10:32:54'),(39,3,6,'App\\Models\\Patient',6,'patient.created','Registered new patient: Treasure Mawuli Vadze (PX-1067-26)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 13:59:59','2026-07-13 13:59:59'),(40,3,6,'App\\Models\\CashierPatientClearance',6,'clearance.created','Created clearance for Treasure Mawuli Vadze (Paid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:00:23','2026-07-13 14:00:23'),(41,3,6,'App\\Models\\Sales',5,'sale.created','Clearance sale: Treasure Mawuli Vadze — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:00:23','2026-07-13 14:00:23'),(42,3,7,'App\\Models\\Patient',7,'patient.created','Registered new patient: Veronica Fafali Ami Adanusah (PX-1606-26)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:02:36','2026-07-13 14:02:36'),(43,3,7,'App\\Models\\CashierPatientClearance',7,'clearance.created','Created clearance for Veronica Fafali Ami Adanusah (Paid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:03:11','2026-07-13 14:03:11'),(44,3,7,'App\\Models\\Sales',6,'sale.created','Clearance sale: Veronica Fafali Ami Adanusah — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:03:11','2026-07-13 14:03:11'),(45,4,6,'App\\Models\\Consultations',6,'consultation.created','Created consultation #6',NULL,'{\"items\": 0, \"chiefComplaint\": \"Discharge and redness of both eyes. \\nStarted 3 days ago. No medication used. \\nPmhx: Nil\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:07:33','2026-07-13 14:07:33'),(46,3,5,'App\\Models\\CashierPatientClearance',8,'clearance.created','Created clearance for Bernice Sarpong (Unpaid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:07:39','2026-07-13 14:07:39'),(47,3,4,'App\\Models\\CashierPatientClearance',9,'clearance.created','Created clearance for Mavis Sanahene (Unpaid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:07:49','2026-07-13 14:07:49'),(48,3,3,'App\\Models\\CashierPatientClearance',10,'clearance.created','Created clearance for Heroine Sanahene (Unpaid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:07:59','2026-07-13 14:07:59'),(49,4,6,'App\\Models\\Consultations',6,'prescription.updated','Updated prescription for Consultation #6',NULL,'{\"items\": 1, \"total\": 85}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:08:06','2026-07-13 14:08:06'),(50,3,2,'App\\Models\\CashierPatientClearance',11,'clearance.created','Created clearance for Matilda Arhin-Sey (Unpaid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:08:10','2026-07-13 14:08:10'),(51,3,1,'App\\Models\\CashierPatientClearance',12,'clearance.created','Created clearance for NANA SACKEY (Unpaid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:08:16','2026-07-13 14:08:16'),(52,4,6,'App\\Models\\Consultations',6,'prescription.updated','Updated prescription for Consultation #6',NULL,'{\"items\": 1, \"total\": 85}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:08:31','2026-07-13 14:08:31'),(53,3,1,'App\\Models\\CashierPatientClearance',12,'clearance.status_updated','Updated payment status to Unpaid for clearance #12','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Unpaid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:08:32','2026-07-13 14:08:32'),(54,3,1,'App\\Models\\CashierPatientClearance',12,'clearance.status_updated','Updated payment status to Paid for clearance #12','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:08:46','2026-07-13 14:08:46'),(55,3,2,'App\\Models\\CashierPatientClearance',11,'clearance.status_updated','Updated payment status to Paid for clearance #11','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:08:51','2026-07-13 14:08:51'),(56,3,3,'App\\Models\\CashierPatientClearance',10,'clearance.status_updated','Updated payment status to Paid for clearance #10','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:08:55','2026-07-13 14:08:55'),(57,3,5,'App\\Models\\CashierPatientClearance',8,'clearance.revoke_requested','Revoke requested for clearance #8 (Bernice Sarpong): Double entry',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:10:04','2026-07-13 14:10:04'),(58,3,4,'App\\Models\\CashierPatientClearance',9,'clearance.revoke_requested','Revoke requested for clearance #9 (Mavis Sanahene): Double entry',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:10:11','2026-07-13 14:10:11'),(59,3,3,'App\\Models\\CashierPatientClearance',10,'clearance.revoke_requested','Revoke requested for clearance #10 (Heroine Sanahene): Double entry',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:10:16','2026-07-13 14:10:16'),(60,3,2,'App\\Models\\CashierPatientClearance',11,'clearance.revoke_requested','Revoke requested for clearance #11 (Matilda Arhin-Sey): Double entry',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:10:24','2026-07-13 14:10:24'),(61,3,1,'App\\Models\\CashierPatientClearance',12,'clearance.revoke_requested','Revoke requested for clearance #12 (NANA SACKEY): Double entry',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:10:29','2026-07-13 14:10:29'),(62,3,3,'App\\Models\\Sales',7,'payment.received','Recorded full payment for sale 13072026-2WA1VGQ7',NULL,'{\"items\": 1, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:13:51','2026-07-13 14:13:51'),(63,4,7,'App\\Models\\Consultations',7,'consultation.created','Created consultation #7',NULL,'{\"items\": 0, \"chiefComplaint\": \"PC: Sudden blurry vision in the left eye which resolve after few minutes\\nPohx: Srx+(3 mnths)\\nPmhx: Seeing a neurologist for an issue with the neck\\n\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:14:22','2026-07-13 14:14:22'),(64,4,7,'App\\Models\\Consultations',7,'prescription.updated','Updated prescription for Consultation #7',NULL,'{\"items\": 3, \"total\": 650}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:15:09','2026-07-13 14:15:09'),(65,3,8,'App\\Models\\Patient',8,'patient.created','Registered new patient: Destiny Omefe (PX-5987-26)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:23:05','2026-07-13 14:23:05'),(66,3,8,'App\\Models\\CashierPatientClearance',13,'clearance.created','Created clearance for Destiny Omefe (Unpaid)',NULL,NULL,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:23:26','2026-07-13 14:23:26'),(67,3,7,'App\\Models\\Sales',8,'payment.received','Recorded full payment for sale 13072026-QTOEDA2P',NULL,'{\"items\": 3, \"amount_paid\": 650, \"total_amount\": \"650.00\", \"payment_status\": \"paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:24:18','2026-07-13 14:24:18'),(68,3,6,'App\\Models\\Sales',9,'payment.received','Recorded full payment for sale 13072026-AR6GQZN2',NULL,'{\"items\": 1, \"amount_paid\": 85, \"total_amount\": \"85.00\", \"payment_status\": \"paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:27:20','2026-07-13 14:27:20'),(69,4,8,'App\\Models\\Consultations',8,'consultation.created','Created consultation #8',NULL,'{\"items\": 0, \"chiefComplaint\": \"To do OCT, Fundus Photography, VFT, IOP, Auto refraction & slitlamp examination\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 15:50:40','2026-07-13 15:50:40'),(70,1,NULL,NULL,NULL,'report.accessed','Accessed sales reports page',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 16:45:55','2026-07-13 16:45:55'),(71,1,NULL,NULL,NULL,'report.accessed','Accessed sales reports page',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 16:46:19','2026-07-13 16:46:19'),(72,1,8,'App\\Models\\Consultations',8,'prescription.updated','Updated prescription for Consultation #8',NULL,'{\"items\": 7, \"total\": 1100}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 17:02:48','2026-07-13 17:02:48'),(73,3,8,'App\\Models\\Sales',10,'payment.received','Recorded full payment for sale 13072026-GC32AKTF',NULL,'{\"items\": 7, \"amount_paid\": 1100, \"total_amount\": \"1100.00\", \"payment_status\": \"paid\"}','192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 17:04:31','2026-07-13 17:04:31'),(74,3,8,'App\\Models\\CashierPatientClearance',13,'clearance.status_updated','Updated payment status to Unpaid for clearance #13','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Unpaid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 09:31:53','2026-07-14 09:31:53'),(75,3,8,'App\\Models\\CashierPatientClearance',13,'clearance.status_updated','Updated payment status to Paid for clearance #13','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 09:32:01','2026-07-14 09:32:01'),(76,1,NULL,NULL,NULL,'report.accessed','Accessed sales reports page',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 12:13:55','2026-07-14 12:13:55'),(77,3,NULL,'App\\Models\\Expense',1,'expense.created','Recorded expense: Annual Business Operating permit (GH₵ 190.00)',NULL,'{\"notes\": \"Statutory payment\", \"amount\": \"190.00\", \"reference\": \"GCR20/0088193\", \"description\": \"Annual Business Operating permit\", \"recorded_by\": 3, \"expense_date\": \"2026-07-01\", \"expense_category_id\": \"8\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:35:16','2026-07-14 12:35:16'),(78,3,NULL,'App\\Models\\Expense',1,'expense.updated','Updated expense: Annual Business Operating permit (GH₵ 190.00)','{\"notes\": \"Statutory payment\", \"amount\": \"190.00\", \"reference\": \"GCR20/0088193\", \"description\": \"Annual Business Operating permit\", \"recorded_by\": 3, \"expense_date\": \"2026-07-01T00:00:00.000000Z\", \"expense_category_id\": 8}','{\"notes\": \"Statutory payment\", \"amount\": \"190.00\", \"reference\": \"GCR20/0088193\", \"description\": \"Annual Business Operating permit\", \"recorded_by\": 3, \"expense_date\": \"2026-07-01\", \"expense_category_id\": 8}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:35:51','2026-07-14 12:35:51'),(79,3,NULL,'App\\Models\\Expense',2,'expense.created','Recorded expense: 24V/2A Receipt Adapter (GH₵ 200.00)',NULL,'{\"notes\": \"\", \"amount\": \"200\", \"reference\": \"REC-260709-M4NP\", \"description\": \"24V/2A Receipt Adapter\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:39:16','2026-07-14 12:39:16'),(80,3,NULL,'App\\Models\\Expense',3,'expense.created','Recorded expense: Printer Cable 5m (GH₵ 100.00)',NULL,'{\"notes\": \"\", \"amount\": \"100\", \"reference\": \"REC-260709-M4NP\", \"description\": \"Printer Cable 5m\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:41:46','2026-07-14 12:41:46'),(81,3,NULL,'App\\Models\\Expense',3,'expense.updated','Updated expense: Printer Cable 5m (GH₵ 100.00)','{\"notes\": \"\", \"amount\": \"100.00\", \"reference\": \"REC-260709-M4NP\", \"description\": \"Printer Cable 5m\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14T00:00:00.000000Z\", \"expense_category_id\": 4}','{\"notes\": \"\", \"amount\": \"100.00\", \"reference\": \"REC-260709-M4NP\", \"description\": \"Printer Cable 5m\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": 4}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:41:59','2026-07-14 12:41:59'),(82,3,NULL,'App\\Models\\Expense',4,'expense.created','Recorded expense: Receipt Paper Roll (GH₵ 60.00)',NULL,'{\"notes\": \"\", \"amount\": \"60\", \"reference\": \"REC-260709-M4NP\", \"description\": \"Receipt Paper Roll\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:42:44','2026-07-14 12:42:44'),(83,3,NULL,'App\\Models\\Expense',5,'expense.created','Recorded expense: Consulting Room Door Handle (GH₵ 120.00)',NULL,'{\"notes\": \"\", \"amount\": \"120\", \"reference\": \"001\", \"description\": \"Consulting Room Door Handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": \"5\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:43:56','2026-07-14 12:43:56'),(84,3,NULL,'App\\Models\\Expense',5,'expense.updated','Updated expense: Consulting Room Door Handle (GH₵ 120.00)','{\"notes\": \"\", \"amount\": \"120.00\", \"reference\": \"001\", \"description\": \"Consulting Room Door Handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14T00:00:00.000000Z\", \"expense_category_id\": 5}','{\"notes\": \"\", \"amount\": \"120.00\", \"reference\": \"001\", \"description\": \"Consulting Room Door Handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": 5}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:44:14','2026-07-14 12:44:14'),(85,3,NULL,'App\\Models\\Expense',6,'expense.created','Recorded expense: Workmanship for fixing door handle (GH₵ 150.00)',NULL,'{\"notes\": \"\", \"amount\": \"150\", \"reference\": \"002\", \"description\": \"Workmanship for fixing door handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": \"5\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:44:49','2026-07-14 12:44:49'),(86,3,NULL,'App\\Models\\Expense',7,'expense.created','Recorded expense: Socket (GH₵ 40.00)',NULL,'{\"notes\": \"\", \"amount\": \"40.00\", \"reference\": \"003\", \"description\": \"Socket\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": \"5\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:45:29','2026-07-14 12:45:29'),(87,3,NULL,'App\\Models\\Expense',8,'expense.created','Recorded expense: T-roll for the washroom (GH₵ 40.00)',NULL,'{\"notes\": \"\", \"amount\": \"40\", \"reference\": \"005\", \"description\": \"T-roll for the washroom\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": \"3\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:46:12','2026-07-14 12:46:12'),(88,3,NULL,'App\\Models\\Expense',8,'expense.updated','Updated expense: T-roll for the washroom (GH₵ 40.00)','{\"notes\": \"\", \"amount\": \"40.00\", \"reference\": \"005\", \"description\": \"T-roll for the washroom\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10T00:00:00.000000Z\", \"expense_category_id\": 3}','{\"notes\": \"\", \"amount\": \"40.00\", \"reference\": \"004\", \"description\": \"T-roll for the washroom\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": 3}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:46:21','2026-07-14 12:46:21'),(89,3,NULL,'App\\Models\\Expense',5,'expense.updated','Updated expense: Consulting Room Door Handle (GH₵ 120.00)','{\"notes\": \"\", \"amount\": \"120.00\", \"reference\": \"001\", \"description\": \"Consulting Room Door Handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09T00:00:00.000000Z\", \"expense_category_id\": 5}','{\"notes\": \"\", \"amount\": \"120.00\", \"reference\": \"0001\", \"description\": \"Consulting Room Door Handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": 5}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:46:29','2026-07-14 12:46:29'),(90,3,NULL,'App\\Models\\Expense',6,'expense.updated','Updated expense: Workmanship for fixing door handle (GH₵ 150.00)','{\"notes\": \"\", \"amount\": \"150.00\", \"reference\": \"002\", \"description\": \"Workmanship for fixing door handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09T00:00:00.000000Z\", \"expense_category_id\": 5}','{\"notes\": \"\", \"amount\": \"150.00\", \"reference\": \"0002\", \"description\": \"Workmanship for fixing door handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": 5}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:46:36','2026-07-14 12:46:36'),(91,3,NULL,'App\\Models\\Expense',7,'expense.updated','Updated expense: Socket (GH₵ 40.00)','{\"notes\": \"\", \"amount\": \"40.00\", \"reference\": \"003\", \"description\": \"Socket\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10T00:00:00.000000Z\", \"expense_category_id\": 5}','{\"notes\": \"\", \"amount\": \"40.00\", \"reference\": \"7003\", \"description\": \"Socket\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": 5}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:46:49','2026-07-14 12:46:49'),(92,3,NULL,'App\\Models\\Expense',5,'expense.updated','Updated expense: Consulting Room Door Handle (GH₵ 120.00)','{\"notes\": \"\", \"amount\": \"120.00\", \"reference\": \"0001\", \"description\": \"Consulting Room Door Handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09T00:00:00.000000Z\", \"expense_category_id\": 5}','{\"notes\": \"\", \"amount\": \"120.00\", \"reference\": \"7001\", \"description\": \"Consulting Room Door Handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": 5}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:46:59','2026-07-14 12:46:59'),(93,3,NULL,'App\\Models\\Expense',6,'expense.updated','Updated expense: Workmanship for fixing door handle (GH₵ 150.00)','{\"notes\": \"\", \"amount\": \"150.00\", \"reference\": \"0002\", \"description\": \"Workmanship for fixing door handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09T00:00:00.000000Z\", \"expense_category_id\": 5}','{\"notes\": \"\", \"amount\": \"150.00\", \"reference\": \"7002\", \"description\": \"Workmanship for fixing door handle\", \"recorded_by\": 3, \"expense_date\": \"2026-07-09\", \"expense_category_id\": 5}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:47:30','2026-07-14 12:47:30'),(94,3,NULL,'App\\Models\\Expense',8,'expense.updated','Updated expense: T-roll for the washroom (GH₵ 40.00)','{\"notes\": \"\", \"amount\": \"40.00\", \"reference\": \"004\", \"description\": \"T-roll for the washroom\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10T00:00:00.000000Z\", \"expense_category_id\": 3}','{\"notes\": \"\", \"amount\": \"40.00\", \"reference\": \"7004\", \"description\": \"T-roll for the washroom\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": 3}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:47:40','2026-07-14 12:47:40'),(95,3,NULL,'App\\Models\\Expense',9,'expense.created','Recorded expense: Bine 20 for floor cleaning (GH₵ 25.00)',NULL,'{\"notes\": \"\", \"amount\": \"25\", \"reference\": \"7005\", \"description\": \"Bine 20 for floor cleaning\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": \"3\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:48:29','2026-07-14 12:48:29'),(96,3,NULL,'App\\Models\\Expense',10,'expense.created','Recorded expense: Printer Cable 5m (GH₵ 50.00)',NULL,'{\"notes\": \"\", \"amount\": \"50\", \"reference\": \"REC-260710-S8WF\", \"description\": \"Printer Cable 5m\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:50:04','2026-07-14 12:50:04'),(97,3,NULL,'App\\Models\\Expense',11,'expense.created','Recorded expense: Lan Cable 5m (GH₵ 30.00)',NULL,'{\"notes\": \"\", \"amount\": \"30\", \"reference\": \"REC-260710-S8WF\", \"description\": \"Lan Cable 5m\", \"recorded_by\": 3, \"expense_date\": \"2026-07-10\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:50:47','2026-07-14 12:50:47'),(98,3,NULL,'App\\Models\\Expense',12,'expense.created','Recorded expense: SSNIT Payment from June & July, 2026 (GH₵ 540.00)',NULL,'{\"notes\": \"\", \"amount\": \"540.00\", \"reference\": \"7006\", \"description\": \"SSNIT Payment from June & July, 2026\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": \"8\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:52:23','2026-07-14 12:52:23'),(99,3,NULL,'App\\Models\\Expense',12,'expense.updated','Updated expense: SSNIT Payment from June & July, 2026 (GH₵ 540.00)','{\"notes\": \"\", \"amount\": \"540.00\", \"reference\": \"7006\", \"description\": \"SSNIT Payment from June & July, 2026\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14T00:00:00.000000Z\", \"expense_category_id\": 8}','{\"notes\": \"Statutory payment\", \"amount\": \"540.00\", \"reference\": \"7006\", \"description\": \"SSNIT Payment from June & July, 2026\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": 8}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:52:37','2026-07-14 12:52:37'),(100,3,NULL,'App\\Models\\Expense',12,'expense.updated','Updated expense: SSNIT Payment from June & July, 2026 (GH₵ 540.00)','{\"notes\": \"Statutory payment\", \"amount\": \"540.00\", \"reference\": \"7006\", \"description\": \"SSNIT Payment from June & July, 2026\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14T00:00:00.000000Z\", \"expense_category_id\": 8}','{\"notes\": \"Statutory payment\", \"amount\": \"540.00\", \"reference\": \"7006\", \"description\": \"SSNIT Payment from June & July, 2026\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": 8}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:53:54','2026-07-14 12:53:54'),(101,3,NULL,'App\\Models\\Expense',13,'expense.created','Recorded expense: Electricity (GH₵ 100.00)',NULL,'{\"notes\": \"\", \"amount\": \"100\", \"reference\": \"7006\", \"description\": \"Electricity\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": \"2\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:54:44','2026-07-14 12:54:44'),(102,3,NULL,'App\\Models\\Expense',13,'expense.updated','Updated expense: Electricity (GH₵ 100.00)','{\"notes\": \"\", \"amount\": \"100.00\", \"reference\": \"7006\", \"description\": \"Electricity\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14T00:00:00.000000Z\", \"expense_category_id\": 2}','{\"notes\": \"\", \"amount\": \"100.00\", \"reference\": \"7007\", \"description\": \"Electricity\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": 2}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:54:53','2026-07-14 12:54:53'),(103,3,NULL,'App\\Models\\Expense',14,'expense.created','Recorded expense: Delivery fee (GH₵ 40.00)',NULL,'{\"notes\": \"From Lead Opticals to Accra\", \"amount\": \"40\", \"reference\": \"7008\", \"description\": \"Delivery fee\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": \"8\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:57:21','2026-07-14 12:57:21'),(104,3,NULL,'App\\Models\\Expense',15,'expense.created','Recorded expense: PAYE for June 2026 (GH₵ 56.13)',NULL,'{\"notes\": \"GRA\", \"amount\": \"56.13\", \"reference\": \"7009\", \"description\": \"PAYE for June 2026\", \"recorded_by\": 3, \"expense_date\": \"2026-07-14\", \"expense_category_id\": \"8\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:59:06','2026-07-14 12:59:06'),(105,3,NULL,'App\\Models\\Expense',16,'expense.created','Recorded expense: Thermal Printer B-F10 (GH₵ 649.00)',NULL,'{\"notes\": \"\", \"amount\": \"649\", \"reference\": \"26/3113037\", \"description\": \"Thermal Printer B-F10\", \"recorded_by\": 3, \"expense_date\": \"2026-07-08\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 13:02:45','2026-07-14 13:02:45'),(106,3,NULL,'App\\Models\\Expense',17,'expense.created','Recorded expense: Colour LaserJet WIY44 M454DN (GH₵ 3299.00)',NULL,'{\"notes\": \"OCT & Fundus photography\'s printer\", \"amount\": \"3299\", \"reference\": \"26/3113037\", \"description\": \"Colour LaserJet WIY44 M454DN\", \"recorded_by\": 3, \"expense_date\": \"2026-07-08\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 13:04:24','2026-07-14 13:04:24'),(107,3,NULL,'App\\Models\\Expense',18,'expense.created','Recorded expense: HP Laser Jet 425On (GH₵ 2500.00)',NULL,'{\"notes\": \"VFT\'s printer\", \"amount\": \"2500\", \"reference\": \"7009\", \"description\": \"HP Laser Jet 425On\", \"recorded_by\": 3, \"expense_date\": \"2026-07-08\", \"expense_category_id\": \"4\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 13:06:44','2026-07-14 13:06:44'),(108,3,NULL,'App\\Models\\Expense',18,'expense.updated','Updated expense: HP Laser Jet 425On (GH₵ 2500.00)','{\"notes\": \"VFT\'s printer\", \"amount\": \"2500.00\", \"reference\": \"7009\", \"description\": \"HP Laser Jet 425On\", \"recorded_by\": 3, \"expense_date\": \"2026-07-08T00:00:00.000000Z\", \"expense_category_id\": 4}','{\"notes\": \"VFT\'s printer\", \"amount\": \"2500.00\", \"reference\": \"7010\", \"description\": \"HP Laser Jet 425On\", \"recorded_by\": 3, \"expense_date\": \"2026-07-08\", \"expense_category_id\": 4}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 13:07:03','2026-07-14 13:07:03'),(109,3,9,'App\\Models\\Patient',9,'patient.created','Registered new patient: Agnes Brenya (PX-1738-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 15:20:05','2026-07-14 15:20:05'),(110,3,9,'App\\Models\\CashierPatientClearance',14,'clearance.created','Created clearance for Agnes Brenya (Unpaid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 15:20:22','2026-07-14 15:20:22'),(111,4,9,'App\\Models\\Consultations',15,'consultation.created','Created consultation #15',NULL,'{\"items\": 0, \"chiefComplaint\": \"For auto-refraction and IOP\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 15:35:41','2026-07-14 15:35:41'),(112,4,9,'App\\Models\\Consultations',15,'prescription.updated','Updated prescription for Consultation #15',NULL,'{\"items\": 2, \"total\": 200}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 15:36:08','2026-07-14 15:36:08'),(113,3,9,'App\\Models\\Sales',11,'payment.received','Recorded full payment for sale 14072026-WIILT7HQ',NULL,'{\"items\": 2, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 15:36:45','2026-07-14 15:36:45'),(114,3,10,'App\\Models\\Patient',10,'patient.created','Registered new patient: Jayder Adjei-Dwomoh (PX-2313-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 17:57:08','2026-07-14 17:57:08'),(115,3,10,'App\\Models\\CashierPatientClearance',15,'clearance.created','Created clearance for Jayder Adjei-Dwomoh (Unpaid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 17:58:04','2026-07-14 17:58:04'),(116,3,9,'App\\Models\\CashierPatientClearance',14,'clearance.status_updated','Updated payment status to Paid for clearance #14','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 17:58:23','2026-07-14 17:58:23'),(117,4,10,'App\\Models\\Consultations',16,'consultation.created','Created consultation #16',NULL,'{\"items\": 0, \"chiefComplaint\": \"To replace spectacles\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 18:21:20','2026-07-14 18:21:20'),(118,4,10,'App\\Models\\Consultations',16,'prescription.updated','Updated prescription for Consultation #16',NULL,'{\"items\": 1, \"total\": 700}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 18:26:27','2026-07-14 18:26:27'),(119,3,10,'App\\Models\\Sales',12,'payment.received','Recorded full payment for sale 14072026-ZQ6SFDFK',NULL,'{\"items\": 1, \"amount_paid\": 700, \"total_amount\": \"700.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 18:28:39','2026-07-14 18:28:39'),(120,3,10,'App\\Models\\LensOrder',1,'spectacles.created','spectacles.created - ORD-F3V4JQKT',NULL,'{\"order_id\": \"ORD-F3V4JQKT\", \"pickUpDate\": \"2026-07-21\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 18:34:28','2026-07-14 18:34:28'),(121,3,NULL,'App\\Models\\Expense',19,'expense.created','Recorded expense: Delivery fee (GH₵ 45.00)',NULL,'{\"notes\": \"From Accra to La Community Clinic\", \"amount\": \"45\", \"reference\": \"7010\", \"description\": \"Delivery fee\", \"recorded_by\": 3, \"expense_date\": \"2026-07-15\", \"expense_category_id\": \"8\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-15 12:05:43','2026-07-15 12:05:43'),(122,3,11,'App\\Models\\Patient',11,'patient.created','Registered new patient: Dominic Keteku (PX-8146-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 09:40:02','2026-07-16 09:40:02'),(123,3,11,'App\\Models\\CashierPatientClearance',16,'clearance.created','Created clearance for Dominic Keteku (Unpaid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 09:43:27','2026-07-16 09:43:27'),(124,4,11,'App\\Models\\Consultations',17,'consultation.created','Created consultation #17',NULL,'{\"items\": 0, \"chiefComplaint\": \"To fix lenses\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 09:45:58','2026-07-16 09:45:58'),(125,4,11,'App\\Models\\Consultations',17,'prescription.updated','Updated prescription for Consultation #17',NULL,'{\"items\": 1, \"total\": 1300}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 09:49:42','2026-07-16 09:49:42'),(126,4,11,'App\\Models\\Consultations',17,'prescription.updated','Updated prescription for Consultation #17',NULL,'{\"items\": 1, \"total\": 1400}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 09:51:42','2026-07-16 09:51:42'),(127,3,11,'App\\Models\\Sales',13,'payment.received','Recorded full payment for sale 16072026-7WAP3Q9U',NULL,'{\"items\": 2, \"amount_paid\": 2200, \"total_amount\": \"2200.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 09:53:05','2026-07-16 09:53:05'),(128,3,12,'App\\Models\\Patient',12,'patient.created','Registered new patient: Richard Tete Obu (PX-1493-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:29:47','2026-07-16 10:29:47'),(129,3,12,'App\\Models\\CashierPatientClearance',17,'clearance.created','Created clearance for Richard Tete Obu (Unpaid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:30:16','2026-07-16 10:30:16'),(130,3,10,'App\\Models\\CashierPatientClearance',18,'clearance.created','Created clearance for Jayder Adjei-Dwomoh (Unpaid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:34:47','2026-07-16 10:34:47'),(131,3,12,'App\\Models\\CashierPatientClearance',17,'clearance.status_updated','Updated payment status to Paid for clearance #17','{\"payment_status\": \"Unpaid\"}','{\"payment_status\": \"Paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:35:12','2026-07-16 10:35:12'),(132,4,10,'App\\Models\\Consultations',18,'consultation.created','Created consultation #18',NULL,'{\"items\": 3, \"chiefComplaint\": \"To fix glasses\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 10:37:33','2026-07-16 10:37:33'),(133,4,10,'App\\Models\\Consultations',18,'prescription.updated','Updated prescription for Consultation #18',NULL,'{\"items\": 3, \"total\": 250}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 10:38:29','2026-07-16 10:38:29'),(134,4,12,'App\\Models\\Consultations',19,'consultation.created','Created consultation #19',NULL,'{\"items\": 0, \"chiefComplaint\": \"For OCT (RNFL/GCC/MACULA)\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 10:40:30','2026-07-16 10:40:30'),(135,4,12,'App\\Models\\Consultations',19,'prescription.updated','Updated prescription for Consultation #19',NULL,'{\"items\": 3, \"total\": 700}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 10:41:02','2026-07-16 10:41:02'),(136,3,10,'App\\Models\\Sales',14,'payment.received','Recorded full payment for sale 16072026-OX2R2EFX',NULL,'{\"items\": 3, \"amount_paid\": 250, \"total_amount\": \"250.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:41:16','2026-07-16 10:41:16'),(137,3,12,'App\\Models\\Sales',15,'payment.received','Recorded full payment for sale 16072026-ZO4ZHAXS',NULL,'{\"items\": 3, \"amount_paid\": 700, \"total_amount\": \"700.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:42:14','2026-07-16 10:42:14'),(138,3,11,'App\\Models\\LensOrder',2,'spectacles.created','spectacles.created - ORD-9LUBWHSN',NULL,'{\"order_id\": \"ORD-9LUBWHSN\", \"pickUpDate\": \"2026-07-23\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:44:42','2026-07-16 10:44:42'),(139,3,11,'App\\Models\\LensOrder',2,'spectacles.status_changed','spectacles.status_changed - ORD-9LUBWHSN','{\"status\": \"Pending\"}','{\"status\": \"In Lab\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:44:54','2026-07-16 10:44:54'),(140,3,11,'App\\Models\\LensOrder',2,'spectacles.status_changed','spectacles.status_changed - ORD-9LUBWHSN','{\"status\": \"In Lab\"}','{\"status\": \"Pending\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:45:06','2026-07-16 10:45:06'),(141,3,10,'App\\Models\\LensOrder',1,'spectacles.status_changed','spectacles.status_changed - ORD-F3V4JQKT','{\"status\": \"Pending\"}','{\"status\": \"In Lab\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:45:42','2026-07-16 10:45:42'),(142,3,13,'App\\Models\\Patient',13,'patient.created','Registered new patient: Bright Kampewu (PX-2665-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 11:03:06','2026-07-16 11:03:06'),(143,3,13,'App\\Models\\CashierPatientClearance',19,'clearance.created','Created clearance for Bright Kampewu (Unpaid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 11:03:22','2026-07-16 11:03:22'),(144,4,13,'App\\Models\\Consultations',20,'consultation.created','Created consultation #20',NULL,'{\"items\": 0, \"chiefComplaint\": \"For OCT (Nerve fibre ananlysis & Ganglion cell analysis)\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 11:28:28','2026-07-16 11:28:28'),(145,4,13,'App\\Models\\Consultations',20,'prescription.updated','Updated prescription for Consultation #20',NULL,'{\"items\": 2, \"total\": 450}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 11:28:50','2026-07-16 11:28:50'),(146,3,13,'App\\Models\\Sales',16,'payment.received','Recorded full payment for sale 16072026-GOKFV3AP',NULL,'{\"items\": 2, \"amount_paid\": 450, \"total_amount\": \"450.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 11:30:33','2026-07-16 11:30:33'),(147,3,NULL,'App\\Models\\Expense',20,'expense.created','Recorded expense: Photopaper (GH₵ 625.00)',NULL,'{\"notes\": \"For OCT & Fundus Photography\", \"amount\": \"625.00\", \"reference\": \"7011\", \"description\": \"Photopaper\", \"recorded_by\": 3, \"expense_date\": \"2026-07-16\", \"expense_category_id\": \"3\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 12:25:24','2026-07-16 12:25:24'),(148,3,NULL,'App\\Models\\Expense',21,'expense.created','Recorded expense: Paper Tissue (GH₵ 25.00)',NULL,'{\"notes\": \"\", \"amount\": \"25\", \"reference\": \"7012\", \"description\": \"Paper Tissue\", \"recorded_by\": 3, \"expense_date\": \"2026-07-17\", \"expense_category_id\": \"3\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 09:18:29','2026-07-17 09:18:29'),(149,3,14,'App\\Models\\Patient',14,'patient.created','Registered new patient: Abigail Ampadu (PX-4289-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 11:43:24','2026-07-17 11:43:24'),(150,3,14,'App\\Models\\CashierPatientClearance',20,'clearance.created','Created clearance for Abigail Ampadu (Unpaid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 11:43:36','2026-07-17 11:43:36'),(151,4,14,'App\\Models\\Consultations',21,'consultation.created','Created consultation #21',NULL,'{\"items\": 0, \"chiefComplaint\": \"Referred from Abokobi Polyclinic for IOP, Fundus Photo and slitlamp examination\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 12:00:57','2026-07-17 12:00:57'),(152,4,14,'App\\Models\\Consultations',21,'prescription.updated','Updated prescription for Consultation #21',NULL,'{\"items\": 1, \"total\": 200}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 12:01:11','2026-07-17 12:01:11'),(153,3,14,'App\\Models\\Sales',17,'payment.received','Recorded full payment for sale 17072026-WXNNUTTN',NULL,'{\"items\": 1, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 12:39:34','2026-07-17 12:39:34'),(154,3,15,'App\\Models\\Patient',15,'patient.created','Registered new patient: Kwasi Korang Incoom (PX-6319-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 16:21:41','2026-07-17 16:21:41'),(155,3,16,'App\\Models\\Patient',16,'patient.created','Registered new patient: Abena Yeboah Incoom (PX-9087-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 16:22:59','2026-07-17 16:22:59'),(156,3,16,'App\\Models\\CashierPatientClearance',21,'clearance.created','Created clearance for Abena Yeboah Incoom (Paid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 16:25:00','2026-07-17 16:25:00'),(157,3,16,'App\\Models\\Sales',18,'sale.created','Clearance sale: Abena Yeboah Incoom — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 16:25:00','2026-07-17 16:25:00'),(158,3,15,'App\\Models\\CashierPatientClearance',22,'clearance.created','Created clearance for Kwasi Korang Incoom (Paid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 16:28:26','2026-07-17 16:28:26'),(159,3,15,'App\\Models\\Sales',19,'sale.created','Clearance sale: Kwasi Korang Incoom — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 16:28:26','2026-07-17 16:28:26'),(160,3,10,'App\\Models\\LensOrder',1,'spectacles.status_changed','spectacles.status_changed - ORD-F3V4JQKT','{\"status\": \"In Lab\"}','{\"status\": \"Ready\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:01:22','2026-07-17 17:01:22'),(161,3,10,'App\\Models\\LensOrder',1,'spectacles.status_changed','spectacles.status_changed - ORD-F3V4JQKT','{\"status\": \"Ready\"}','{\"status\": \"Ready\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:01:25','2026-07-17 17:01:25'),(162,4,16,'App\\Models\\Consultations',22,'consultation.created','Created consultation #22',NULL,'{\"items\": 0, \"chiefComplaint\": \"To replace spectacles\\nPohx: SRx+(2)\\nPmhx: Nil\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 17:02:12','2026-07-17 17:02:12'),(163,4,16,'App\\Models\\Consultations',22,'prescription.updated','Updated prescription for Consultation #22',NULL,'{\"items\": 1, \"total\": 600}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 17:02:40','2026-07-17 17:02:40'),(164,4,15,'App\\Models\\Consultations',23,'consultation.created','Created consultation #23',NULL,'{\"items\": 0, \"chiefComplaint\": \"To replace spectcles\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 17:18:43','2026-07-17 17:18:43'),(165,4,15,'App\\Models\\Consultations',23,'prescription.updated','Updated prescription for Consultation #23',NULL,'{\"items\": 1, \"total\": 600}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 17:19:00','2026-07-17 17:19:00'),(166,4,15,'App\\Models\\Consultations',23,'prescription.updated','Updated prescription for Consultation #23',NULL,'{\"items\": 1, \"total\": 600}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 17:25:53','2026-07-17 17:25:53'),(167,3,16,'App\\Models\\Sales',20,'payment.received','Recorded full payment for sale 17072026-3ADEHV3B',NULL,'{\"items\": 2, \"amount_paid\": 900, \"total_amount\": \"900.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:28:14','2026-07-17 17:28:14'),(168,3,15,'App\\Models\\Sales',21,'payment.received','Recorded full payment for sale 17072026-JTRX3U5V',NULL,'{\"items\": 2, \"amount_paid\": 900, \"total_amount\": \"900.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:32:49','2026-07-17 17:32:49'),(169,3,16,'App\\Models\\LensOrder',3,'spectacles.created','spectacles.created - ORD-5MPB36UE',NULL,'{\"order_id\": \"ORD-5MPB36UE\", \"pickUpDate\": \"2026-07-24\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:40:12','2026-07-17 17:40:12'),(170,3,15,'App\\Models\\LensOrder',4,'spectacles.created','spectacles.created - ORD-TTDPLYHO',NULL,'{\"order_id\": \"ORD-TTDPLYHO\", \"pickUpDate\": \"2026-07-24\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:40:25','2026-07-17 17:40:25'),(171,3,15,'App\\Models\\LensOrder',4,'spectacles.status_changed','spectacles.status_changed - ORD-TTDPLYHO','{\"status\": \"Pending\"}','{\"status\": \"In Lab\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:40:32','2026-07-17 17:40:32'),(172,3,16,'App\\Models\\LensOrder',3,'spectacles.status_changed','spectacles.status_changed - ORD-5MPB36UE','{\"status\": \"Pending\"}','{\"status\": \"In Lab\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:40:38','2026-07-17 17:40:38'),(173,3,11,'App\\Models\\LensOrder',2,'spectacles.status_changed','spectacles.status_changed - ORD-9LUBWHSN','{\"status\": \"Pending\"}','{\"status\": \"In Lab\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 17:40:40','2026-07-17 17:40:40'),(174,3,17,'App\\Models\\Patient',17,'patient.created','Registered new patient: Bertty Osei (PX-5432-26)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-18 14:02:00','2026-07-18 14:02:00'),(175,3,17,'App\\Models\\CashierPatientClearance',23,'clearance.created','Created clearance for Bertty Osei (Paid)',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-18 14:02:15','2026-07-18 14:02:15'),(176,3,17,'App\\Models\\Sales',22,'sale.created','Clearance sale: Bertty Osei — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-18 14:02:15','2026-07-18 14:02:15'),(177,4,17,'App\\Models\\Consultations',24,'consultation.created','Created consultation #24',NULL,'{\"items\": 0, \"chiefComplaint\": \"Burning sensation and redness.\\nIntermittent and gets worse when walking under the sun\\nPohx: Srx-, Surgery-\\nPmhx: DM-, HBP-\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-18 14:23:40','2026-07-18 14:23:40'),(178,4,17,'App\\Models\\Consultations',24,'prescription.updated','Updated prescription for Consultation #24',NULL,'{\"items\": 2, \"total\": 160}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-18 14:26:23','2026-07-18 14:26:23'),(179,3,17,'App\\Models\\Sales',23,'payment.received','Recorded full payment for sale 18072026-BGU3D39G',NULL,'{\"items\": 2, \"amount_paid\": 160, \"total_amount\": \"160.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-18 14:30:52','2026-07-18 14:30:52'),(180,3,NULL,'App\\Models\\Expense',22,'expense.created','Recorded expense: Tissue (GH₵ 11.00)',NULL,'{\"notes\": \"Consulting room use\", \"amount\": \"11\", \"reference\": \"7013\", \"description\": \"Tissue\", \"recorded_by\": 3, \"expense_date\": \"2026-07-20\", \"expense_category_id\": \"3\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 09:28:55','2026-07-20 09:28:55'),(181,1,NULL,'App\\Models\\Sales',24,'payment.received','Recorded full payment for sale 20072026-TKYW4CS9',NULL,'{\"items\": 1, \"amount_paid\": 80, \"total_amount\": \"80.00\", \"payment_status\": \"paid\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-20 09:55:36','2026-07-20 09:55:36'),(182,3,15,'App\\Models\\LensOrder',4,'spectacles.status_changed','spectacles.status_changed - ORD-TTDPLYHO','{\"status\": \"In Lab\"}','{\"status\": \"Ready\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 10:06:54','2026-07-20 10:06:54'),(183,3,15,'App\\Models\\LensOrder',4,'spectacles.status_changed','spectacles.status_changed - ORD-TTDPLYHO','{\"status\": \"Ready\"}','{\"status\": \"Ready\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 10:06:57','2026-07-20 10:06:57'),(184,3,16,'App\\Models\\LensOrder',3,'spectacles.status_changed','spectacles.status_changed - ORD-5MPB36UE','{\"status\": \"In Lab\"}','{\"status\": \"Ready\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 10:09:44','2026-07-20 10:09:44'),(185,3,16,'App\\Models\\LensOrder',3,'spectacles.status_changed','spectacles.status_changed - ORD-5MPB36UE','{\"status\": \"Ready\"}','{\"status\": \"Collected\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 12:17:56','2026-07-20 12:17:56'),(186,3,15,'App\\Models\\LensOrder',4,'spectacles.status_changed','spectacles.status_changed - ORD-TTDPLYHO','{\"status\": \"Ready\"}','{\"status\": \"Collected\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 12:18:02','2026-07-20 12:18:02'),(187,3,NULL,'App\\Models\\Sales',25,'payment.received','Recorded full payment for sale 20072026-9QQWDDYP',NULL,'{\"items\": 3, \"amount_paid\": 550, \"total_amount\": \"550.00\", \"payment_status\": \"paid\"}','192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 15:27:46','2026-07-20 15:27:46'),(188,1,NULL,NULL,NULL,'report.accessed','Accessed sales reports page',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 12:06:07','2026-07-22 12:06:07'),(189,3,18,'App\\Models\\Patient',18,'patient.created','Registered new patient: Jeremy Apaluk Avoka (PX-9723-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-23 11:51:42','2026-07-23 11:51:42'),(190,3,18,'App\\Models\\CashierPatientClearance',24,'clearance.created','Created clearance for Jeremy Apaluk Avoka (Paid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-23 11:52:01','2026-07-23 11:52:01'),(191,3,18,'App\\Models\\Sales',26,'sale.created','Clearance sale: Jeremy Apaluk Avoka — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-23 11:52:01','2026-07-23 11:52:01'),(192,4,18,'App\\Models\\Consultations',25,'consultation.created','Created consultation #25',NULL,'{\"items\": 0, \"chiefComplaint\": \"Referred for refraction. Blurry vision at distance.\\nPohx: Nil\\nPmhx: DM-, HBP-\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23 12:22:18','2026-07-23 12:22:18'),(193,3,NULL,'App\\Models\\Sales',27,'payment.received','Recorded full payment for sale 25072026-BJGWZ1BY',NULL,'{\"items\": 1, \"amount_paid\": 60, \"total_amount\": \"60.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:03:26','2026-07-25 10:03:26'),(194,3,19,'App\\Models\\Patient',19,'patient.created','Registered new patient: Stacy Morkor Quarshie (PX-2416-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:09:46','2026-07-25 10:09:46'),(195,3,19,'App\\Models\\CashierPatientClearance',25,'clearance.created','Created clearance for Stacy Morkor Quarshie (Unpaid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:10:03','2026-07-25 10:10:03'),(196,3,20,'App\\Models\\Patient',20,'patient.created','Registered new patient: Alice Peprah (PX-2554-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:15:01','2026-07-25 10:15:01'),(197,3,20,'App\\Models\\CashierPatientClearance',26,'clearance.created','Created clearance for Alice Peprah (Unpaid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:15:15','2026-07-25 10:15:15'),(198,2,20,'App\\Models\\Consultations',26,'consultation.created','Created consultation #26',NULL,'{\"items\": 0, \"chiefComplaint\": \"REDNESS, PAIN, DISCHARGE\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:35:22','2026-07-25 10:35:22'),(199,2,20,'App\\Models\\Consultations',26,'prescription.updated','Updated prescription for Consultation #26',NULL,'{\"items\": 1, \"total\": 60}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:36:20','2026-07-25 10:36:20'),(200,2,20,'App\\Models\\Consultations',26,'prescription.updated','Updated prescription for Consultation #26',NULL,'{\"items\": 2, \"total\": 140}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:36:47','2026-07-25 10:36:47'),(201,3,20,'App\\Models\\Sales',28,'payment.received','Recorded full payment for sale 25072026-BXPCTKPB',NULL,'{\"items\": 2, \"amount_paid\": 140, \"total_amount\": \"140.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:40:49','2026-07-25 10:40:49'),(202,2,20,'App\\Models\\Consultations',26,'prescription.updated','Updated prescription for Consultation #26',NULL,'{\"items\": 2, \"total\": 140}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:42:56','2026-07-25 10:42:56'),(203,2,19,'App\\Models\\Consultations',27,'consultation.created','Created consultation #27',NULL,'{\"items\": 0, \"chiefComplaint\": \"HEADACHE\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:47:06','2026-07-25 10:47:06'),(204,2,19,'App\\Models\\Consultations',27,'prescription.updated','Updated prescription for Consultation #27',NULL,'{\"items\": 1, \"total\": 700}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:49:04','2026-07-25 10:49:04'),(205,3,19,'App\\Models\\Sales',29,'payment.received','Recorded part payment for sale 25072026-EQI2GNPW',NULL,'{\"items\": 2, \"amount_paid\": 550, \"total_amount\": \"1300.00\", \"payment_status\": \"partial\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:55:59','2026-07-25 10:55:59'),(206,3,11,'App\\Models\\LensOrder',2,'spectacles.status_changed','spectacles.status_changed - ORD-9LUBWHSN','{\"status\": \"In Lab\"}','{\"status\": \"Collected\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 11:00:10','2026-07-25 11:00:10'),(207,3,10,'App\\Models\\LensOrder',1,'spectacles.status_changed','spectacles.status_changed - ORD-F3V4JQKT','{\"status\": \"Ready\"}','{\"status\": \"Collected\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 11:00:22','2026-07-25 11:00:22'),(208,3,19,'App\\Models\\LensOrder',5,'spectacles.created','spectacles.created - ORD-DHVE3AJR',NULL,'{\"order_id\": \"ORD-DHVE3AJR\", \"pickUpDate\": \"2026-08-01\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 11:00:46','2026-07-25 11:00:46'),(209,3,10,'App\\Models\\LensOrder',1,'spectacles.status_changed','spectacles.status_changed - ORD-F3V4JQKT','{\"status\": \"Collected\"}','{\"status\": \"Pending\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 11:00:52','2026-07-25 11:00:52'),(210,3,10,'App\\Models\\LensOrder',1,'spectacles.status_changed','spectacles.status_changed - ORD-F3V4JQKT','{\"status\": \"Pending\"}','{\"status\": \"In Lab\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 11:00:56','2026-07-25 11:00:56'),(211,3,10,'App\\Models\\LensOrder',1,'spectacles.status_changed','spectacles.status_changed - ORD-F3V4JQKT','{\"status\": \"In Lab\"}','{\"status\": \"Collected\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 11:09:50','2026-07-25 11:09:50'),(212,3,21,'App\\Models\\Patient',21,'patient.created','Registered new patient: Maame Efua Nhyira Amanku (PX-7026-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-26 16:31:42','2026-07-26 16:31:42'),(213,3,21,'App\\Models\\CashierPatientClearance',27,'clearance.created','Created clearance for Maame Efua Nhyira Amanku (Paid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-26 16:32:02','2026-07-26 16:32:02'),(214,3,21,'App\\Models\\Sales',30,'sale.created','Clearance sale: Maame Efua Nhyira Amanku — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-26 16:32:02','2026-07-26 16:32:02'),(215,4,21,'App\\Models\\Consultations',28,'consultation.created','Created consultation #28',NULL,'{\"items\": 1, \"chiefComplaint\": \"For refraction\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 16:53:58','2026-07-26 16:53:58'),(216,4,21,'App\\Models\\Consultations',28,'prescription.updated','Updated prescription for Consultation #28',NULL,'{\"items\": 1, \"total\": 700}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 16:54:14','2026-07-26 16:54:14'),(217,3,21,'App\\Models\\Sales',31,'payment.received','Recorded full payment for sale 26072026-IBAXLB8J',NULL,'{\"items\": 2, \"amount_paid\": 1150, \"total_amount\": \"1150.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-26 17:06:11','2026-07-26 17:06:11'),(218,1,NULL,NULL,NULL,'patient.exported','Exported patient registry.',NULL,NULL,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 09:47:45','2026-07-27 09:47:45'),(219,3,NULL,'App\\Models\\Expense',23,'expense.created','Recorded expense: Electricity (GH₵ 100.00)',NULL,'{\"notes\": \"Prepaid for office use\", \"amount\": \"100\", \"reference\": \"7014\", \"description\": \"Electricity\", \"recorded_by\": 3, \"expense_date\": \"2026-07-27\", \"expense_category_id\": \"2\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 12:08:41','2026-07-27 12:08:41'),(220,3,NULL,'App\\Models\\Expense',24,'expense.created','Recorded expense: Transportation (GH₵ 42.00)',NULL,'{\"notes\": \"Lead Opticals to Accra\", \"amount\": \"42\", \"reference\": \"7016\", \"description\": \"Transportation\", \"recorded_by\": 3, \"expense_date\": \"2026-07-27\", \"expense_category_id\": \"8\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 13:28:19','2026-07-27 13:28:19'),(221,3,NULL,'App\\Models\\Expense',24,'expense.updated','Updated expense: Transportation (GH₵ 42.00)','{\"notes\": \"Lead Opticals to Accra\", \"amount\": \"42.00\", \"reference\": \"7016\", \"description\": \"Transportation\", \"recorded_by\": 3, \"expense_date\": \"2026-07-27T00:00:00.000000Z\", \"expense_category_id\": 8}','{\"notes\": \"Lead Opticals to Accra\", \"amount\": \"42.00\", \"reference\": \"7015\", \"description\": \"Transportation\", \"recorded_by\": 3, \"expense_date\": \"2026-07-27\", \"expense_category_id\": 8}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 13:28:29','2026-07-27 13:28:29'),(222,3,NULL,'App\\Models\\Expense',24,'expense.updated','Updated expense: Delivery fee (GH₵ 42.00)','{\"notes\": \"Lead Opticals to Accra\", \"amount\": \"42.00\", \"reference\": \"7015\", \"description\": \"Transportation\", \"recorded_by\": 3, \"expense_date\": \"2026-07-27T00:00:00.000000Z\", \"expense_category_id\": 8}','{\"notes\": \"Lead Opticals to Accra\", \"amount\": \"42.00\", \"reference\": \"7015\", \"description\": \"Delivery fee\", \"recorded_by\": 3, \"expense_date\": \"2026-07-27\", \"expense_category_id\": 8}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 13:28:49','2026-07-27 13:28:49'),(223,3,22,'App\\Models\\Patient',22,'patient.created','Registered new patient: Neriah Naa Teiko Ayettey (PX-4436-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 11:48:43','2026-07-28 11:48:43'),(224,3,22,'App\\Models\\CashierPatientClearance',28,'clearance.created','Created clearance for Neriah Naa Teiko Ayettey (Unpaid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 11:49:03','2026-07-28 11:49:03'),(225,3,NULL,'App\\Models\\Sales',32,'payment.received','Recorded full payment for sale 28072026-V2J3G4HG',NULL,'{\"items\": 5, \"amount_paid\": 735, \"total_amount\": \"735.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 12:34:28','2026-07-28 12:34:28'),(226,3,NULL,'App\\Models\\Sales',33,'payment.received','Recorded full payment for sale 28072026-QPXGOGD7',NULL,'{\"items\": 1, \"amount_paid\": 150, \"total_amount\": \"150.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 13:05:07','2026-07-28 13:05:07'),(227,3,NULL,'App\\Models\\Sales',33,'direct_purchase.completed','Direct purchase completed for Walk-in by Joselyne Bonsu',NULL,'{\"total_amount\": \"150.00\", \"customer_name\": \"Walk-in\", \"purchase_type\": \"direct\", \"transaction_id\": \"28072026-QPXGOGD7\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 13:05:07','2026-07-28 13:05:07'),(228,3,23,'App\\Models\\Patient',23,'patient.created','Registered new patient: Kojo Asereba (PX-8954-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 15:11:41','2026-07-28 15:11:41'),(229,3,23,'App\\Models\\CashierPatientClearance',29,'clearance.created','Created clearance for Kojo Asereba (Paid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 15:11:54','2026-07-28 15:11:54'),(230,3,23,'App\\Models\\Sales',34,'sale.created','Clearance sale: Kojo Asereba — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 15:11:54','2026-07-28 15:11:54'),(231,4,23,'App\\Models\\Consultations',29,'consultation.created','Created consultation #29',NULL,'{\"items\": 0, \"chiefComplaint\": \"CC: Blurred vision in the left eye\\nOnset: Acute\\nODQ: Painless, No discharge\\nPohx: Trauma-\\nPmhx: DM-, HBP-\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 15:27:06','2026-07-28 15:27:06'),(232,4,23,'App\\Models\\Consultations',29,'consultation.updated','Updated consultation #29','{\"odq\": [], \"IOPOD\": \"14.00\", \"IOPOS\": \"33.00\", \"notes\": null, \"others\": null, \"vaOD6m\": \"20/20\", \"vaOS6m\": \"20/200\", \"chiefComplaint\": \"CC: Blurred vision in the left eye\\nOnset: Acute\\nODQ: Painless, No discharge\\nPohx: Trauma-\\nPmhx: DM-, HBP-\"}','{\"IOPOD\": \"14.00\", \"IOPOS\": \"33.00\", \"items\": 0, \"chiefComplaint\": \"CC: Blurred vision in the left eye\\nOnset: Acute\\nODQ: Painless, No discharge\\nPohx: Trauma-\\nPmhx: DM-, HBP-, Asthma-\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 16:05:46','2026-07-28 16:05:46'),(233,3,24,'App\\Models\\Patient',24,'patient.created','Registered new patient: Elike Kofi Caus-Siale (PX-1573-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:15:03','2026-07-29 15:15:03'),(234,3,24,'App\\Models\\CashierPatientClearance',30,'clearance.created','Created clearance for Elike Kofi Caus-Siale (Paid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:17:54','2026-07-29 15:17:54'),(235,3,24,'App\\Models\\Sales',35,'sale.created','Clearance sale: Elike Kofi Caus-Siale — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:17:54','2026-07-29 15:17:54'),(236,3,19,'App\\Models\\Sales',29,'payment.updated','Collected part payment for sale 25072026-EQI2GNPW','{\"amount_paid\": \"850.00\", \"payment_status\": \"partial\"}','{\"amount_paid\": 850, \"payment_status\": \"partial\", \"amount_collected\": 300}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:19:34','2026-07-29 15:19:34'),(237,3,19,'App\\Models\\LensOrder',5,'spectacles.status_changed','spectacles.status_changed - ORD-DHVE3AJR','{\"status\": \"Pending\"}','{\"status\": \"In Lab\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:20:16','2026-07-29 15:20:16'),(238,3,19,'App\\Models\\LensOrder',5,'spectacles.status_changed','spectacles.status_changed - ORD-DHVE3AJR','{\"status\": \"In Lab\"}','{\"status\": \"Ready\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:20:18','2026-07-29 15:20:18'),(239,3,NULL,'App\\Models\\Expense',25,'expense.created','Recorded expense: A pack of Envelope (GH₵ 25.00)',NULL,'{\"notes\": \"\", \"amount\": \"25\", \"reference\": \"7016\", \"description\": \"A pack of Envelope\", \"recorded_by\": 3, \"expense_date\": \"2026-07-29\", \"expense_category_id\": \"3\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:27:12','2026-07-29 15:27:12'),(240,3,NULL,'App\\Models\\Expense',26,'expense.created','Recorded expense: Delivery Fee (GH₵ 35.00)',NULL,'{\"notes\": \"Lead Opticals to La Community Clinic\", \"amount\": \"35\", \"reference\": \"7017\", \"description\": \"Delivery Fee\", \"recorded_by\": 3, \"expense_date\": \"2026-07-29\", \"expense_category_id\": \"8\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:28:08','2026-07-29 15:28:08'),(241,4,24,'App\\Models\\Consultations',30,'consultation.created','Created consultation #30',NULL,'{\"items\": 0, \"chiefComplaint\": \"Rubbing of the eyes\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-29 15:28:20','2026-07-29 15:28:20'),(242,4,24,'App\\Models\\Consultations',30,'prescription.updated','Updated prescription for Consultation #30',NULL,'{\"items\": 2, \"total\": 170}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-29 15:31:24','2026-07-29 15:31:24'),(243,4,24,'App\\Models\\Consultations',30,'prescription.updated','Updated prescription for Consultation #30',NULL,'{\"items\": 2, \"total\": 145}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-29 15:34:44','2026-07-29 15:34:44'),(244,3,24,'App\\Models\\Sales',36,'payment.received','Recorded full payment for sale 29072026-SALO08YX',NULL,'{\"items\": 2, \"amount_paid\": 145, \"total_amount\": \"145.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:35:17','2026-07-29 15:35:17'),(245,3,23,'App\\Models\\CashierPatientClearance',31,'clearance.created','Created clearance for Kojo Asereba (Paid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 10:42:30','2026-07-30 10:42:30'),(246,3,23,'App\\Models\\Sales',37,'sale.created','Clearance sale: Kojo Asereba — Review (GH₵ 100) | Cash GH₵100.00',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 10:42:30','2026-07-30 10:42:30'),(247,4,23,'App\\Models\\Consultations',31,'consultation.created','Created consultation #31',NULL,'{\"items\": 0, \"chiefComplaint\": \"For review of condition\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30 10:53:07','2026-07-30 10:53:07'),(248,4,23,'App\\Models\\Consultations',31,'prescription.updated','Updated prescription for Consultation #31',NULL,'{\"items\": 1, \"total\": 140}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30 10:53:52','2026-07-30 10:53:52'),(249,3,25,'App\\Models\\Patient',25,'patient.created','Registered new patient: Awodeji Oluwafemi (PX-7752-26)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 10:55:33','2026-07-30 10:55:33'),(250,3,25,'App\\Models\\CashierPatientClearance',32,'clearance.created','Created clearance for Awodeji Oluwafemi (Paid)',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 10:55:48','2026-07-30 10:55:48'),(251,3,25,'App\\Models\\Sales',38,'sale.created','Clearance sale: Awodeji Oluwafemi — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 10:55:48','2026-07-30 10:55:48'),(252,4,25,'App\\Models\\Consultations',32,'consultation.created','Created consultation #32',NULL,'{\"items\": 0, \"chiefComplaint\": \"Eye discharge and tearing\\nPohx: Lazy eye\\nPmhx: DM-, HBP-, SC-\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30 11:01:30','2026-07-30 11:01:30'),(253,4,25,'App\\Models\\Consultations',32,'prescription.updated','Updated prescription for Consultation #32',NULL,'{\"items\": 1, \"total\": 60}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30 11:02:07','2026-07-30 11:02:07'),(254,3,25,'App\\Models\\Sales',39,'payment.received','Recorded full payment for sale 30072026-O2QPAQPC',NULL,'{\"items\": 1, \"amount_paid\": 60, \"total_amount\": \"60.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 11:05:01','2026-07-30 11:05:01'),(255,3,NULL,'App\\Models\\Sales',40,'payment.received','Recorded full payment for sale 30072026-BYSQHDCN',NULL,'{\"items\": 2, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 12:04:05','2026-07-30 12:04:05'),(256,3,NULL,'App\\Models\\Sales',40,'direct_purchase.completed','Direct purchase completed for Walk-in by Joselyne Bonsu',NULL,'{\"total_amount\": \"200.00\", \"customer_name\": \"Walk-in\", \"purchase_type\": \"direct\", \"transaction_id\": \"30072026-BYSQHDCN\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 12:04:05','2026-07-30 12:04:05'),(257,3,NULL,'App\\Models\\Sales',41,'payment.received','Recorded full payment for sale 30072026-RHLD3XBM',NULL,'{\"items\": 1, \"amount_paid\": 100, \"total_amount\": \"100.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 12:10:48','2026-07-30 12:10:48'),(258,3,NULL,'App\\Models\\Sales',41,'direct_purchase.completed','Direct purchase completed for Joselyne Bonsu by Joselyne Bonsu',NULL,'{\"total_amount\": \"100.00\", \"customer_name\": \"Joselyne Bonsu\", \"purchase_type\": \"direct\", \"transaction_id\": \"30072026-RHLD3XBM\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 12:10:48','2026-07-30 12:10:48'),(259,3,NULL,'App\\Models\\Expense',27,'expense.created','Recorded expense: Delivery Fee (GH₵ 35.00)',NULL,'{\"notes\": \"Cananda Opticals to Lead Opticals\", \"amount\": \"35\", \"reference\": \"7018\", \"description\": \"Delivery Fee\", \"recorded_by\": 3, \"expense_date\": \"2026-07-30\", \"expense_category_id\": \"8\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 12:28:35','2026-07-30 12:28:35'),(260,3,NULL,'App\\Models\\Sales',42,'payment.received','Recorded full payment for sale 01082026-QEKFOYET',NULL,'{\"items\": 1, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-01 13:04:44','2026-08-01 13:04:44'),(261,3,NULL,'App\\Models\\Sales',42,'direct_purchase.completed','Direct purchase completed for Idibia Elbridge Nayram by Joselyne Bonsu',NULL,'{\"total_amount\": \"200.00\", \"customer_name\": \"Idibia Elbridge Nayram\", \"purchase_type\": \"direct\", \"transaction_id\": \"01082026-QEKFOYET\"}','192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-01 13:04:44','2026-08-01 13:04:44'),(262,3,NULL,'App\\Models\\Expense',28,'expense.created','Recorded expense: July\'s salary (GH₵ 1500.00)',NULL,'{\"notes\": \"Dr. Hillary Debrah\", \"amount\": \"1500\", \"reference\": \"7018\", \"description\": \"July\'s salary\", \"recorded_by\": 3, \"expense_date\": \"2026-07-31\", \"expense_category_id\": \"1\"}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 12:07:12','2026-08-04 12:07:12'),(263,3,NULL,'App\\Models\\Expense',28,'expense.updated','Updated expense: July\'s salary (GH₵ 1500.00)','{\"notes\": \"Dr. Hillary Debrah\", \"amount\": \"1500.00\", \"reference\": \"7018\", \"description\": \"July\'s salary\", \"recorded_by\": 3, \"expense_date\": \"2026-07-31T00:00:00.000000Z\", \"expense_category_id\": 1}','{\"notes\": \"Dr. Hillary Debrah\", \"amount\": \"1500.00\", \"reference\": \"7019\", \"description\": \"July\'s salary\", \"recorded_by\": 3, \"expense_date\": \"2026-07-31\", \"expense_category_id\": 1}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 12:07:43','2026-08-04 12:07:43'),(264,3,NULL,'App\\Models\\Sales',43,'payment.received','Recorded full payment for sale 04082026-V89FMOLC',NULL,'{\"items\": 2, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 12:38:11','2026-08-04 12:38:11'),(265,3,NULL,'App\\Models\\Sales',43,'direct_purchase.completed','Direct purchase completed for Vivian Ohui Gberbie by Joselyne Bonsu',NULL,'{\"total_amount\": \"200.00\", \"customer_name\": \"Vivian Ohui Gberbie\", \"purchase_type\": \"direct\", \"transaction_id\": \"04082026-V89FMOLC\"}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 12:38:11','2026-08-04 12:38:11'),(266,3,26,'App\\Models\\Patient',26,'patient.created','Registered new patient: Betty Aboah (PX-3539-26)',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:15:25','2026-08-04 14:15:25'),(267,3,26,'App\\Models\\CashierPatientClearance',33,'clearance.created','Created clearance for Betty Aboah (Paid)',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:18:22','2026-08-04 14:18:22'),(268,3,26,'App\\Models\\Sales',44,'sale.created','Clearance sale: Betty Aboah — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:18:22','2026-08-04 14:18:22'),(269,3,27,'App\\Models\\Patient',27,'patient.created','Registered new patient: Samuel Aboah (PX-8905-26)',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:33:41','2026-08-04 14:33:41'),(270,3,27,'App\\Models\\CashierPatientClearance',34,'clearance.created','Created clearance for Samuel Aboah (Paid)',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:33:56','2026-08-04 14:33:56'),(271,3,27,'App\\Models\\Sales',45,'sale.created','Clearance sale: Samuel Aboah — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:33:56','2026-08-04 14:33:56'),(272,1,26,'App\\Models\\Consultations',33,'consultation.created','Created consultation #33',NULL,'{\"items\": 0, \"chiefComplaint\": \"Blurry visoin at far and near\\nPohx: SXR+ broken\\nPmhx: DM-, HBP-\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 14:40:28','2026-08-04 14:40:28'),(273,1,26,'App\\Models\\Consultations',33,'prescription.updated','Updated prescription for Consultation #33',NULL,'{\"items\": 1, \"total\": 900}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 14:41:05','2026-08-04 14:41:05'),(274,1,27,'App\\Models\\Consultations',34,'consultation.created','Created consultation #34',NULL,'{\"items\": 0, \"chiefComplaint\": \"Routine examination\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 14:52:53','2026-08-04 14:52:53'),(275,1,27,'App\\Models\\Consultations',34,'prescription.updated','Updated prescription for Consultation #34',NULL,'{\"items\": 1, \"total\": 85}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 14:53:14','2026-08-04 14:53:14'),(276,3,27,'App\\Models\\Sales',46,'payment.received','Recorded full payment for sale 04082026-1RLKYOQL',NULL,'{\"items\": 1, \"amount_paid\": 85, \"total_amount\": \"85.00\", \"payment_status\": \"paid\"}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:58:00','2026-08-04 14:58:00'),(277,3,26,'App\\Models\\Sales',47,'payment.received','Recorded part payment for sale 04082026-A00O3IKO',NULL,'{\"items\": 2, \"amount_paid\": 380, \"total_amount\": \"980.00\", \"payment_status\": \"partial\"}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:08:31','2026-08-04 15:08:31'),(278,3,28,'App\\Models\\Patient',28,'patient.created','Registered new patient: Cecilia Kaka (PX-6398-26)',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:23:27','2026-08-04 15:23:27'),(279,3,28,'App\\Models\\Patient',28,'patient.updated','Updated patient profile: Cecilia Kaka (PX-6398-26)','{\"dob\": \"2026-08-03\", \"name\": \"Cecilia Kaka\", \"email\": \"\", \"gender\": \"Female\", \"address\": \"Kuottam Estate\", \"contact\": \"0539477792\", \"insurer_id\": null, \"occupation\": \"Retired Nurse\", \"civil_status\": \"Married\", \"insurance_member_id\": \"\", \"insurance_member_name\": \"\", \"insurance_policy_number\": \"\"}','{\"dob\": \"2026-05-11\", \"name\": \"Cecilia Kaka\", \"email\": \"\", \"gender\": \"Female\", \"address\": \"Kuottam Estate\", \"contact\": \"0539477792\", \"insurer_id\": null, \"occupation\": \"Retired Nurse\", \"civil_status\": \"Married\", \"insurance_member_id\": \"\", \"insurance_member_name\": \"\", \"insurance_policy_number\": \"\"}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:24:12','2026-08-04 15:24:12'),(280,3,28,'App\\Models\\CashierPatientClearance',35,'clearance.created','Created clearance for Cecilia Kaka (Paid)',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:27:09','2026-08-04 15:27:09'),(281,3,28,'App\\Models\\Sales',48,'sale.created','Clearance sale: Cecilia Kaka — Consultation Fee (GH₵ 200) | Cash GH₵200.00',NULL,NULL,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:27:09','2026-08-04 15:27:09'),(282,1,28,'App\\Models\\Consultations',35,'consultation.created','Created consultation #35',NULL,'{\"items\": 0, \"chiefComplaint\": \"Headache and blurry vison\\nPohx: Nil, LEE > 5yrs\\nPmhx: DM-, HBP+\"}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 15:51:19','2026-08-04 15:51:19'),(283,1,28,'App\\Models\\Consultations',35,'prescription.updated','Updated prescription for Consultation #35',NULL,'{\"items\": 1, \"total\": 900}','192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 15:51:31','2026-08-04 15:51:31'),(284,3,28,'App\\Models\\Sales',49,'payment.received','Recorded full payment for sale 04082026-6YWL2TRP',NULL,'{\"items\": 1, \"amount_paid\": 900, \"total_amount\": \"900.00\", \"payment_status\": \"paid\"}','192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:53:00','2026-08-04 15:53:00'),(285,3,NULL,'App\\Models\\Expense',29,'expense.created','Recorded expense: Prepaid (GH₵ 100.00)',NULL,'{\"notes\": \"ECG\", \"amount\": \"100\", \"reference\": \"8001\", \"description\": \"Prepaid\", \"recorded_by\": 3, \"expense_date\": \"2026-08-05\", \"expense_category_id\": \"2\"}','192.168.100.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-05 10:16:32','2026-08-05 10:16:32'),(286,3,NULL,'App\\Models\\Sales',50,'payment.received','Recorded full payment for sale 05082026-NKU6QZQ4',NULL,'{\"items\": 2, \"amount_paid\": 200, \"total_amount\": \"200.00\", \"payment_status\": \"paid\"}','192.168.100.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-05 11:27:42','2026-08-05 11:27:42'),(287,3,NULL,'App\\Models\\Sales',50,'direct_purchase.completed','Direct purchase completed for Evelyn Apreku by Joselyne Bonsu',NULL,'{\"total_amount\": \"200.00\", \"customer_name\": \"Evelyn Apreku\", \"purchase_type\": \"direct\", \"transaction_id\": \"05082026-NKU6QZQ4\"}','192.168.100.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-05 11:27:42','2026-08-05 11:27:42');
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
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint unsigned DEFAULT NULL,
  `event` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `frequency` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eye` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (2,3,3,4,19,1,200.00,200.00,NULL,NULL,'refunded',1,'2026-07-13 14:13:51',1,'2026-07-10 11:43:35','2026-07-14 09:37:35',NULL),(3,5,3,5,2,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-10 15:14:44',1,'2026-07-10 15:13:54','2026-07-10 15:14:44',NULL),(4,5,3,5,4,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-10 15:14:44',1,'2026-07-10 15:13:54','2026-07-10 15:14:44',NULL),(5,6,3,6,84,1,85.00,85.00,'Four Times Daily','Both Eyes','completed',1,'2026-07-13 14:27:20',1,'2026-07-13 14:08:06','2026-07-13 14:27:20',NULL),(6,7,3,7,11,1,200.00,200.00,NULL,NULL,'completed',1,'2026-07-13 14:24:18',1,'2026-07-13 14:15:09','2026-07-13 14:24:18',NULL),(7,7,3,7,9,1,250.00,250.00,NULL,NULL,'completed',1,'2026-07-13 14:24:18',1,'2026-07-13 14:15:09','2026-07-13 14:24:18',NULL),(8,7,3,7,19,1,200.00,200.00,NULL,NULL,'completed',1,'2026-07-13 14:24:18',1,'2026-07-13 14:15:09','2026-07-13 14:24:18',NULL),(9,8,3,8,4,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-13 17:04:31',1,'2026-07-13 17:02:48','2026-07-13 17:04:31',NULL),(10,8,3,8,2,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-13 17:04:31',1,'2026-07-13 17:02:48','2026-07-13 17:04:31',NULL),(11,8,3,8,9,1,250.00,250.00,NULL,NULL,'completed',1,'2026-07-13 17:04:31',1,'2026-07-13 17:02:48','2026-07-13 17:04:31',NULL),(12,8,3,8,11,1,200.00,200.00,NULL,NULL,'completed',1,'2026-07-13 17:04:31',1,'2026-07-13 17:02:48','2026-07-13 17:04:31',NULL),(13,8,3,8,335,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-13 17:04:31',1,'2026-07-13 17:02:48','2026-07-13 17:04:31',NULL),(14,8,3,8,5,1,150.00,150.00,NULL,NULL,'completed',1,'2026-07-13 17:04:31',1,'2026-07-13 17:02:48','2026-07-13 17:04:31',NULL),(15,8,3,8,19,1,200.00,200.00,NULL,NULL,'completed',1,'2026-07-13 17:04:31',1,'2026-07-13 17:02:48','2026-07-13 17:04:31',NULL),(16,9,3,15,2,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-14 15:36:45',1,'2026-07-14 15:36:08','2026-07-14 15:36:45',NULL),(17,9,3,15,4,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-14 15:36:45',1,'2026-07-14 15:36:08','2026-07-14 15:36:45',NULL),(18,10,3,16,46,1,700.00,700.00,NULL,NULL,'completed',1,'2026-07-14 18:28:39',1,'2026-07-14 18:26:27','2026-07-14 18:28:39',NULL),(21,11,3,17,66,1,1400.00,1400.00,NULL,NULL,'completed',1,'2026-07-16 09:53:05',1,'2026-07-16 09:51:42','2026-07-16 09:53:05',NULL),(22,11,3,17,42,1,800.00,800.00,NULL,NULL,'pending',0,NULL,0,'2026-07-16 09:52:05','2026-07-16 09:52:05',NULL),(26,10,3,18,378,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-16 10:41:16',1,'2026-07-16 10:38:29','2026-07-16 10:41:16',NULL),(27,10,3,18,377,1,100.00,100.00,NULL,NULL,'completed',1,'2026-07-16 10:41:16',1,'2026-07-16 10:38:29','2026-07-16 10:41:16',NULL),(28,10,3,18,376,1,50.00,50.00,NULL,NULL,'completed',1,'2026-07-16 10:41:16',1,'2026-07-16 10:38:29','2026-07-16 10:41:16',NULL),(29,12,3,19,9,1,250.00,250.00,NULL,NULL,'completed',1,'2026-07-16 10:42:14',1,'2026-07-16 10:41:02','2026-07-16 10:42:14',NULL),(30,12,3,19,11,1,200.00,200.00,NULL,NULL,'completed',1,'2026-07-16 10:42:14',1,'2026-07-16 10:41:02','2026-07-16 10:42:14',NULL),(31,12,3,19,10,1,250.00,250.00,NULL,NULL,'completed',1,'2026-07-16 10:42:14',1,'2026-07-16 10:41:02','2026-07-16 10:42:14',NULL),(32,13,3,20,11,1,200.00,200.00,NULL,NULL,'completed',1,'2026-07-16 11:30:33',1,'2026-07-16 11:28:50','2026-07-16 11:30:33',NULL),(33,13,3,20,9,1,250.00,250.00,NULL,NULL,'completed',1,'2026-07-16 11:30:33',1,'2026-07-16 11:28:50','2026-07-16 11:30:33',NULL),(34,14,3,21,19,1,200.00,200.00,NULL,NULL,'completed',1,'2026-07-17 12:39:34',1,'2026-07-17 12:01:11','2026-07-17 12:39:34',NULL),(35,16,3,22,45,1,600.00,600.00,NULL,NULL,'completed',1,'2026-07-17 17:28:14',1,'2026-07-17 17:02:40','2026-07-17 17:28:14',NULL),(36,16,3,22,416,1,400.00,400.00,NULL,NULL,'completed',1,'2026-07-17 17:28:14',1,'2026-07-17 17:03:44','2026-07-17 17:28:14',NULL),(38,15,3,23,45,1,600.00,600.00,NULL,NULL,'completed',1,'2026-07-17 17:32:49',1,'2026-07-17 17:19:00','2026-07-17 17:32:49',NULL),(39,15,3,23,417,1,300.00,300.00,NULL,NULL,'pending',0,NULL,0,'2026-07-17 17:32:38','2026-07-17 17:32:38',NULL),(40,17,3,24,81,1,80.00,80.00,'Four Times Daily','Both Eyes','completed',1,'2026-07-18 14:30:52',1,'2026-07-18 14:26:23','2026-07-18 14:30:52',NULL),(41,17,3,24,87,1,80.00,80.00,'Four Times Daily','Both Eyes','completed',1,'2026-07-18 14:30:52',1,'2026-07-18 14:26:23','2026-07-18 14:30:52',NULL),(42,20,3,26,82,1,60.00,60.00,'Three Times Daily','Both Eyes','completed',1,'2026-07-25 10:40:49',1,'2026-07-25 10:36:20','2026-07-25 10:40:49',NULL),(43,20,3,26,81,1,80.00,80.00,'Four Times Daily','Both Eyes','completed',1,'2026-07-25 10:40:49',1,'2026-07-25 10:36:47','2026-07-25 10:40:49',NULL),(44,19,3,27,46,1,700.00,700.00,NULL,NULL,'completed',0,NULL,1,'2026-07-25 10:49:04','2026-07-25 10:55:59',NULL),(45,19,3,27,234,1,600.00,600.00,NULL,NULL,'pending',0,NULL,0,'2026-07-25 10:54:24','2026-07-25 10:54:24',NULL),(47,21,3,28,46,1,700.00,700.00,NULL,NULL,'completed',1,'2026-07-26 17:06:11',1,'2026-07-26 16:54:14','2026-07-26 17:06:11',NULL),(48,21,3,28,324,1,450.00,450.00,NULL,NULL,'pending',0,NULL,0,'2026-07-26 17:05:50','2026-07-26 17:05:50',NULL),(49,24,3,30,80,1,85.00,85.00,'Twice Daily','Both Eyes','completed',1,'2026-07-29 15:35:17',1,'2026-07-29 15:31:24','2026-07-29 15:35:17',NULL),(52,24,3,30,82,1,60.00,60.00,'Four Times Daily','Both Eyes','completed',1,'2026-07-29 15:35:17',1,'2026-07-29 15:34:44','2026-07-29 15:35:17',NULL),(54,25,3,32,82,1,60.00,60.00,'Every 4 Hours','Both Eyes','completed',1,'2026-07-30 11:05:01',1,'2026-07-30 11:02:07','2026-07-30 11:05:01',NULL),(55,26,3,33,57,1,900.00,900.00,NULL,NULL,'completed',0,NULL,1,'2026-08-04 14:41:05','2026-08-04 15:08:31',NULL),(56,27,3,34,80,1,85.00,85.00,'Twice Daily','Both Eyes','completed',1,'2026-08-04 14:58:00',1,'2026-08-04 14:53:14','2026-08-04 14:58:00',NULL),(58,28,3,35,57,1,900.00,900.00,NULL,NULL,'completed',1,'2026-08-04 15:53:00',1,'2026-08-04 15:51:31','2026-08-04 15:53:00',NULL);
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
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `payment_status` enum('Paid','Unpaid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cashier_patient_clearances`
--

LOCK TABLES `cashier_patient_clearances` WRITE;
/*!40000 ALTER TABLE `cashier_patient_clearances` DISABLE KEYS */;
INSERT INTO `cashier_patient_clearances` VALUES (1,'a2d194a6-3f0c-4347-b6cd-245b0160cdea',3,1,4,'Paid',1,'2026-07-09',1,NULL,'2026-07-09 17:48:25','2026-07-09 18:26:36'),(2,'b9d173d8-296a-459d-ac9e-520ca41704ec',3,2,2,'Paid',1,'2026-07-10',2,NULL,'2026-07-10 09:44:38','2026-07-10 10:40:54'),(3,'86c3b2ad-ccdf-442b-b90e-70a8a046cabd',3,1,4,'Paid',1,'2026-07-10',3,NULL,'2026-07-10 10:24:13','2026-07-10 10:37:17'),(4,'f6cba343-3f71-42dc-b206-d5c0403637ed',1,3,NULL,'Paid',1,'2026-07-10',NULL,NULL,'2026-07-10 11:26:05','2026-07-10 11:43:15'),(5,'a9ee8e94-fd75-41be-8fba-16d48a14fa29',3,5,NULL,'Unpaid',1,'2026-07-10',NULL,NULL,'2026-07-10 15:12:09','2026-07-10 15:13:29'),(6,'f96aa34c-02d8-4687-a997-ce2c5ab4a6a4',3,6,23,'Paid',1,'2026-07-13',5,NULL,'2026-07-13 14:00:23','2026-07-13 14:07:33'),(7,'ec789df9-7e46-44b4-bf86-c92f3cfe452b',3,7,23,'Paid',1,'2026-07-13',6,NULL,'2026-07-13 14:03:11','2026-07-13 14:14:22'),(8,'f3263720-f069-4507-954a-a2cf756d8efa',3,5,NULL,'Unpaid',0,'2026-07-13',NULL,'2026-07-13 14:19:56','2026-07-13 14:07:39','2026-07-13 14:19:56'),(9,'d328504d-26e6-4701-9c35-f9dd0493f6de',3,4,NULL,'Unpaid',0,'2026-07-13',NULL,'2026-07-13 14:19:53','2026-07-13 14:07:49','2026-07-13 14:19:53'),(10,'80694382-30ba-4255-a51b-bd87aca7d1a1',3,3,NULL,'Paid',0,'2026-07-13',NULL,'2026-07-13 14:19:48','2026-07-13 14:07:59','2026-07-13 14:19:48'),(11,'2f48f248-a482-4baa-b092-9a7749f2363c',3,2,NULL,'Paid',0,'2026-07-13',NULL,'2026-07-13 14:19:50','2026-07-13 14:08:10','2026-07-13 14:19:50'),(12,'528dc509-7f41-42e2-a01c-8d9412a125cb',3,1,NULL,'Paid',0,'2026-07-13',NULL,'2026-07-13 14:19:46','2026-07-13 14:08:16','2026-07-13 14:19:46'),(13,'63f3ff6e-ff41-4bcc-928b-8f85f0f5fdc1',3,8,NULL,'Paid',1,'2026-07-13',NULL,NULL,'2026-07-13 14:23:26','2026-07-14 09:32:01'),(14,'0651f1c3-b60f-47ac-8ea5-543a9cff4c51',3,9,NULL,'Paid',1,'2026-07-14',NULL,NULL,'2026-07-14 15:20:22','2026-07-14 17:58:23'),(15,'2819dfd4-2f6b-4a2f-a6c4-8a28d4108298',3,10,NULL,'Unpaid',1,'2026-07-14',NULL,NULL,'2026-07-14 17:58:04','2026-07-14 18:21:20'),(16,'de0296f1-49dd-4613-9b0f-832993f0d4e2',3,11,NULL,'Unpaid',1,'2026-07-16',NULL,NULL,'2026-07-16 09:43:27','2026-07-16 09:45:58'),(17,'cb0af303-a0d9-4c32-8513-e1ed8cd9a461',3,12,NULL,'Paid',1,'2026-07-16',NULL,NULL,'2026-07-16 10:30:16','2026-07-16 10:40:30'),(18,'7c8e6cc6-4e18-47a6-943b-4c97a76bd5fa',3,10,NULL,'Unpaid',1,'2026-07-16',NULL,NULL,'2026-07-16 10:34:47','2026-07-16 10:37:33'),(19,'1e727098-3e91-428a-a8f8-0d834d3b2e1c',3,13,NULL,'Unpaid',1,'2026-07-16',NULL,NULL,'2026-07-16 11:03:22','2026-07-16 11:28:28'),(20,'9e8d17b7-7329-4aa0-acd8-6f8645fbb752',3,14,NULL,'Unpaid',1,'2026-07-17',NULL,NULL,'2026-07-17 11:43:36','2026-07-17 12:00:57'),(21,'a056da0a-cfd8-494f-aa83-d70bd69bb727',3,16,23,'Paid',1,'2026-07-17',18,NULL,'2026-07-17 16:25:00','2026-07-17 17:02:12'),(22,'8738e22b-027c-488d-938a-2860f9b6e584',3,15,23,'Paid',1,'2026-07-17',19,NULL,'2026-07-17 16:28:26','2026-07-17 17:18:43'),(23,'90dadbd1-ea87-41b4-bdae-b161b3aeaf11',3,17,23,'Paid',1,'2026-07-18',22,NULL,'2026-07-18 14:02:15','2026-07-18 14:23:40'),(24,'b92d80b7-ab73-41d0-b6c4-44dfac068be4',3,18,23,'Paid',1,'2026-07-23',26,NULL,'2026-07-23 11:52:01','2026-07-23 12:22:18'),(25,'8a87f43f-2270-4413-8ca7-961f46f841e3',3,19,NULL,'Unpaid',1,'2026-07-25',NULL,NULL,'2026-07-25 10:10:03','2026-07-25 10:47:06'),(26,'a3ee706f-b74f-4643-8e57-167b97e63357',3,20,NULL,'Unpaid',1,'2026-07-25',NULL,NULL,'2026-07-25 10:15:15','2026-07-25 10:35:22'),(27,'60e0e134-40c9-4cdf-8ce7-9bfd10897e22',3,21,23,'Paid',1,'2026-07-26',30,NULL,'2026-07-26 16:32:02','2026-07-26 16:53:58'),(28,'6d479761-7510-4e02-a267-76df89e13ea7',3,22,NULL,'Unpaid',0,'2026-07-28',NULL,NULL,'2026-07-28 11:49:03','2026-07-28 11:49:03'),(29,'7583c80d-ec0b-4c4d-bdd2-a2ddbf4c3a08',3,23,23,'Paid',1,'2026-07-28',34,NULL,'2026-07-28 15:11:54','2026-07-28 15:27:06'),(30,'01478f34-ab4c-4acd-be41-d5d055ff2c8c',3,24,23,'Paid',1,'2026-07-29',35,NULL,'2026-07-29 15:17:54','2026-07-29 15:28:20'),(31,'9828f51a-a441-4ce9-8629-db4fe0232737',3,23,421,'Paid',1,'2026-07-30',37,NULL,'2026-07-30 10:42:30','2026-07-30 10:53:07'),(32,'c72cc5cf-d753-4245-a660-589dd4e1852e',3,25,23,'Paid',1,'2026-07-30',38,NULL,'2026-07-30 10:55:48','2026-07-30 11:01:30'),(33,'6785bde4-3b38-4edc-b393-f8da29c23c31',3,26,23,'Paid',1,'2026-08-04',44,NULL,'2026-08-04 14:18:22','2026-08-04 14:40:28'),(34,'e8729414-9471-41a4-a07e-6c931f36ca7b',3,27,23,'Paid',1,'2026-08-04',45,NULL,'2026-08-04 14:33:56','2026-08-04 14:52:53'),(35,'39fbd130-7230-426e-9707-48a931750ca3',3,28,23,'Paid',1,'2026-08-04',48,NULL,'2026-08-04 15:27:09','2026-08-04 15:51:19');
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  KEY `categories_user_id_foreign` (`user_id`),
  CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,1,'Consultation','service','',1,'2026-07-09 15:15:54','2026-07-09 15:04:11','2026-07-09 15:15:54'),(2,1,'Autorefraction','service','',1,'2026-07-09 15:15:50','2026-07-09 15:05:30','2026-07-09 15:15:50'),(3,1,'Tonometry','service','',1,'2026-07-09 15:15:47','2026-07-09 15:05:58','2026-07-09 15:15:47'),(4,1,'VFT 24-2','service','',1,'2026-07-09 15:15:45','2026-07-09 15:07:05','2026-07-09 15:15:45'),(5,1,'VFT 30-2','service','',1,'2026-07-09 15:15:42','2026-07-09 15:07:42','2026-07-09 15:15:42'),(6,1,'VFT 10-2','service','',1,'2026-07-09 15:15:39','2026-07-09 15:07:55','2026-07-09 15:15:39'),(7,1,'Fundus Photograph','service','',1,'2026-07-09 15:15:36','2026-07-09 15:08:22','2026-07-09 15:15:36'),(8,1,'RNFL & ONH','service','',1,'2026-07-09 15:15:32','2026-07-09 15:09:13','2026-07-09 15:15:32'),(9,1,'3D Macula Map','service','',1,'2026-07-09 15:15:29','2026-07-09 15:09:39','2026-07-09 15:15:29'),(10,1,'GCC Layer Analysis','service','',1,'2026-07-09 15:15:26','2026-07-09 15:09:55','2026-07-09 15:15:26'),(11,1,'Macula 5-Line Raster','service','',1,'2026-07-09 15:15:23','2026-07-09 15:10:27','2026-07-09 15:15:23'),(12,1,'Pachymetry','service','',1,'2026-07-09 15:15:20','2026-07-09 15:10:43','2026-07-09 15:15:20'),(13,1,'Anterior 3D Angle','service','',1,'2026-07-09 15:15:17','2026-07-09 15:11:24','2026-07-09 15:15:17'),(14,1,'Drugs','drug','',1,NULL,'2026-07-09 15:16:34','2026-07-10 11:21:22'),(15,1,'Frames','frame','',1,NULL,'2026-07-09 15:16:46','2026-07-10 11:21:10'),(16,1,'Lenses','lens','',1,NULL,'2026-07-09 15:16:55','2026-07-10 11:21:03'),(17,1,'Optometrist Consultation','service','',1,'2026-07-09 15:18:05','2026-07-09 15:17:29','2026-07-09 15:18:05'),(18,1,'Visual Field Testing (VFT)','drug','',1,NULL,'2026-07-09 15:18:49','2026-07-10 11:20:50'),(19,1,'Optical Coherence Tomography (OCT)','drug','',1,NULL,'2026-07-09 15:22:21','2026-07-10 11:20:35'),(20,1,'Refraction','drug','',1,NULL,'2026-07-09 15:22:48','2026-07-10 11:20:28'),(21,1,'Fundus Photography','drug','',1,NULL,'2026-07-09 15:35:17','2026-07-10 11:20:21'),(22,1,'Eye Examination','service','',1,NULL,'2026-07-09 15:55:39','2026-07-10 11:27:56'),(23,1,'Accessories','product','',1,NULL,'2026-07-09 16:31:24','2026-07-09 16:31:24'),(24,1,'Single Vision Lenses','lens','',1,'2026-07-27 13:41:00','2026-07-10 12:42:23','2026-07-27 13:41:00'),(25,1,'Bifocal Lenses','lens','',1,'2026-07-27 13:40:57','2026-07-10 12:42:56','2026-07-27 13:40:57'),(26,1,'Progressive Lenses','lens','',1,'2026-07-27 13:40:53','2026-07-10 12:43:20','2026-07-27 13:40:53');
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
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_by` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clearance_revoke_logs`
--

LOCK TABLES `clearance_revoke_logs` WRITE;
/*!40000 ALTER TABLE `clearance_revoke_logs` DISABLE KEYS */;
INSERT INTO `clearance_revoke_logs` VALUES (1,8,'approved',3,1,NULL,'Double entry',NULL,'2026-07-13 14:10:04','2026-07-13 14:19:56',NULL,'2026-07-13 14:10:04','2026-07-13 14:19:56'),(2,9,'approved',3,1,NULL,'Double entry',NULL,'2026-07-13 14:10:11','2026-07-13 14:19:53',NULL,'2026-07-13 14:10:11','2026-07-13 14:19:53'),(3,10,'approved',3,1,NULL,'Double entry',NULL,'2026-07-13 14:10:16','2026-07-13 14:19:48',NULL,'2026-07-13 14:10:16','2026-07-13 14:19:48'),(4,11,'approved',3,1,NULL,'Double entry',NULL,'2026-07-13 14:10:24','2026-07-13 14:19:50',NULL,'2026-07-13 14:10:24','2026-07-13 14:19:50'),(5,12,'approved',3,1,NULL,'Double entry',NULL,'2026-07-13 14:10:29','2026-07-13 14:19:46',NULL,'2026-07-13 14:10:29','2026-07-13 14:19:46');
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultation_diagnosis`
--

LOCK TABLES `consultation_diagnosis` WRITE;
/*!40000 ALTER TABLE `consultation_diagnosis` DISABLE KEYS */;
INSERT INTO `consultation_diagnosis` VALUES (1,1,92,NULL,NULL),(2,2,92,NULL,NULL),(3,3,92,NULL,NULL),(4,4,92,NULL,NULL),(5,5,11,NULL,NULL),(6,6,29,NULL,NULL),(7,7,11,NULL,NULL),(8,8,11,NULL,NULL),(9,15,11,NULL,NULL),(10,16,4,NULL,NULL),(11,17,76,NULL,NULL),(12,18,4,NULL,NULL),(13,19,92,NULL,NULL),(14,20,92,NULL,NULL),(15,21,282,NULL,NULL),(16,22,3,NULL,NULL),(17,22,9,NULL,NULL),(18,23,14,NULL,NULL),(19,24,233,NULL,NULL),(20,25,14,NULL,NULL),(21,26,282,NULL,NULL),(22,27,8,NULL,NULL),(23,28,4,NULL,NULL),(24,29,284,NULL,NULL),(25,30,282,NULL,NULL),(26,30,283,NULL,NULL),(27,31,284,NULL,NULL),(28,32,282,NULL,NULL),(29,33,18,NULL,NULL),(30,33,8,NULL,NULL),(31,34,283,NULL,NULL),(32,35,8,NULL,NULL),(33,35,18,NULL,NULL);
/*!40000 ALTER TABLE `consultation_diagnosis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consultation_notes`
--

DROP TABLE IF EXISTS `consultation_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consultation_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `patient_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `note_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'clinical_addendum',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultation_notes_consultation_id_created_at_index` (`consultation_id`,`created_at`),
  KEY `consultation_notes_patient_id_created_at_index` (`patient_id`,`created_at`),
  KEY `consultation_notes_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `consultation_notes_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_notes_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultation_notes`
--

LOCK TABLES `consultation_notes` WRITE;
/*!40000 ALTER TABLE `consultation_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `consultation_notes` ENABLE KEYS */;
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
  `chiefComplaint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `others` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `odq` json DEFAULT NULL,
  `vaOD6m` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `vaOS6m` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lidsOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lidsOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `conjunctivaOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `conjunctivaOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `corneaOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `corneaOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `irisOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `irisOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pupilOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pupilOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lensOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lensOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `vitreousOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `vitreousOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fundusOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fundusOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cdrOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cdrOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `IOPOD` decimal(8,2) DEFAULT NULL,
  `IOPOS` decimal(8,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `prescribed_products` json DEFAULT NULL,
  `drug_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `drugs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `consultations_clearance_id_unique` (`clearance_id`),
  KEY `consultations_user_id_index` (`user_id`),
  KEY `consultations_patient_id_index` (`patient_id`),
  CONSTRAINT `consultations_clearance_id_foreign` FOREIGN KEY (`clearance_id`) REFERENCES `cashier_patient_clearances` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `consultations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consultations`
--

LOCK TABLES `consultations` WRITE;
/*!40000 ALTER TABLE `consultations` DISABLE KEYS */;
INSERT INTO `consultations` VALUES (1,1,1,1,'To check Intraocular pressure (IOP)',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14.00,18.00,NULL,NULL,'[]',NULL,'2026-07-09 18:26:36','2026-07-09 18:26:36',NULL),(2,4,1,3,'To check IOP',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14.00,18.00,NULL,NULL,'[]',NULL,'2026-07-10 10:37:17','2026-07-10 10:37:17',NULL),(3,4,2,2,'To do IOP, Fundus Photo, Auto Refraction and OCT (Optic Nerve and Macula analysis)',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,18.00,18.00,NULL,NULL,'[]',NULL,'2026-07-10 10:40:54','2026-07-10 10:40:54',NULL),(4,1,3,4,'For Fundus Photograph',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[{\"eye\": null, \"name\": \"Fundus Photo\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 2, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 19, \"batch_number\": \"FP001\", \"is_dispensed\": false, \"category_name\": \"Fundus Photography\"}]',NULL,'2026-07-10 11:43:15','2026-07-10 11:43:35',NULL),(5,4,5,5,'For IOP & Autorefraction',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14.00,14.00,NULL,NULL,'[{\"eye\": null, \"name\": \"Autorefraction\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 3, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 2, \"batch_number\": \"ATRF001\", \"is_dispensed\": false, \"category_name\": \"Refraction\"}, {\"eye\": null, \"name\": \"Tonometry\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 4, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 4, \"batch_number\": \"TNM001\", \"is_dispensed\": false, \"category_name\": \"Eye Examination\"}]',NULL,'2026-07-10 15:13:29','2026-07-10 15:13:54',NULL),(6,4,6,6,'Discharge and redness of both eyes. \nStarted 3 days ago. No medication used. \nPmhx: Nil',NULL,'[]','20/20','20/20','discharge on eyelids','discharge on eyelids','injected','injected','clear','clear','NAD','NAD','PERRLA','PERRLA','Clear','Clear','Clear','Clear','NAD','NAD','0.10','0.10',NULL,NULL,NULL,NULL,'[{\"eye\": \"Both Eyes\", \"name\": \"Tobralant\", \"price\": \"85.00\", \"total\": \"85.00\", \"status\": \"pending\", \"cart_id\": 5, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Four Times Daily\", \"purchased\": false, \"product_id\": 84, \"is_refunded\": false, \"batch_number\": \"TBL001\", \"dispensed_at\": null, \"is_dispensed\": false, \"category_name\": \"Drugs\"}]',NULL,'2026-07-13 14:07:32','2026-07-13 14:08:31',NULL),(7,4,7,7,'PC: Sudden blurry vision in the left eye which resolve after few minutes\nPohx: Srx+(3 mnths)\nPmhx: Seeing a neurologist for an issue with the neck\n',NULL,'[]','20/40','20/40','NAD','NAD','NAD','NAD','Clear','Clear','Brown','Clear','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.20','0.20',13.00,13.00,NULL,NULL,'[{\"eye\": null, \"name\": \"Ganglion Cell Complex\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 6, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 11, \"batch_number\": \"OCT002\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}, {\"eye\": null, \"name\": \"Nerve Fibre Analysis\", \"price\": \"250.00\", \"total\": \"250.00\", \"cart_id\": 7, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 9, \"batch_number\": \"OCT001\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}, {\"eye\": null, \"name\": \"Fundus Photo\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 8, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 19, \"batch_number\": \"FP001\", \"is_dispensed\": false, \"category_name\": \"Fundus Photography\"}]',NULL,'2026-07-13 14:14:22','2026-07-13 14:15:09',NULL),(8,4,8,13,'To do OCT, Fundus Photography, VFT, IOP, Auto refraction & slitlamp examination',NULL,'[]',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15.00,15.00,NULL,NULL,'[{\"eye\": null, \"name\": \"Tonometry\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 9, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 4, \"batch_number\": \"TNM001\", \"is_dispensed\": false, \"category_name\": \"Eye Examination\"}, {\"eye\": null, \"name\": \"Autorefraction\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 10, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 2, \"batch_number\": \"ATRF001\", \"is_dispensed\": false, \"category_name\": \"Refraction\"}, {\"eye\": null, \"name\": \"Nerve Fibre Analysis\", \"price\": \"250.00\", \"total\": \"250.00\", \"cart_id\": 11, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 9, \"batch_number\": \"OCT001\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}, {\"eye\": null, \"name\": \"Ganglion Cell Complex\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 12, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 11, \"batch_number\": \"OCT002\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}, {\"eye\": null, \"name\": \"Slitlamp Examination\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 13, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 335, \"batch_number\": \"SLT001\", \"is_dispensed\": false, \"category_name\": \"Eye Examination\"}, {\"eye\": null, \"name\": \"VFT 24-2\", \"price\": \"150.00\", \"total\": \"150.00\", \"cart_id\": 14, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 5, \"batch_number\": \"VFT001\", \"is_dispensed\": false, \"category_name\": \"Visual Field Testing (VFT)\"}, {\"eye\": null, \"name\": \"Fundus Photo\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 15, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 19, \"batch_number\": \"FP001\", \"is_dispensed\": false, \"category_name\": \"Fundus Photography\"}]',NULL,'2026-07-13 15:50:40','2026-07-13 17:02:48',NULL),(15,4,9,14,'For auto-refraction and IOP',NULL,'[]','20/20','20/20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15.00,15.00,NULL,NULL,'[{\"eye\": null, \"name\": \"Autorefraction\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 16, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 2, \"batch_number\": \"ATRF001\", \"is_dispensed\": false, \"category_name\": \"Refraction\"}, {\"eye\": null, \"name\": \"Tonometry\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 17, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 4, \"batch_number\": \"TNM001\", \"is_dispensed\": false, \"category_name\": \"Eye Examination\"}]',NULL,'2026-07-14 15:35:41','2026-07-14 15:36:08',NULL),(16,4,10,15,'To replace spectacles',NULL,'[]','20/30','20/30','NAD','NAD','Clear','Clear','Clear','Clear','Brown','Brown','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[{\"eye\": null, \"name\": \"SV Photo (+2.00 to -2.00, CYL -2.00) - Blue BC\", \"price\": \"700.00\", \"total\": \"700.00\", \"cart_id\": 18, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 46, \"batch_number\": \"SVP002\", \"is_dispensed\": false, \"category_name\": \"Single Vision Lenses\"}]',NULL,'2026-07-14 18:21:20','2026-07-14 18:26:27',NULL),(17,4,11,16,'To fix lenses',NULL,'[]','20/30','20/30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,17.00,18.00,NULL,NULL,'[{\"eye\": null, \"name\": \"Prog Photo (+2.00 to -2.00) - Blue BC\", \"price\": \"1400.00\", \"total\": \"1400.00\", \"cart_id\": 21, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 66, \"batch_number\": \"PGP002\", \"is_dispensed\": false, \"category_name\": \"Progressive Lenses\"}]',NULL,'2026-07-16 09:45:58','2026-07-16 09:51:42',NULL),(18,4,10,18,'To fix glasses',NULL,'[]','20/30','20/30',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[{\"eye\": null, \"name\": \"Dry Refraction (Empel)\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 26, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 378, \"batch_number\": \"DRF-EMP\", \"is_dispensed\": false, \"category_name\": \"Eye Examination\"}, {\"eye\": null, \"name\": \"Consultation (Empel)\", \"price\": \"100.00\", \"total\": \"100.00\", \"cart_id\": 27, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 377, \"batch_number\": \"CON-EMP\", \"is_dispensed\": false, \"category_name\": \"Eye Examination\"}, {\"eye\": null, \"name\": \"Registration (Empel)\", \"price\": \"50.00\", \"total\": \"50.00\", \"cart_id\": 28, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 376, \"batch_number\": \"REG-EMP\", \"is_dispensed\": false, \"category_name\": \"Eye Examination\"}]',NULL,'2026-07-16 10:37:33','2026-07-16 10:38:29',NULL),(19,4,12,17,'For OCT (RNFL/GCC/MACULA)',NULL,'[]','20/20','20/20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'VAs are with glasses',NULL,'[{\"eye\": null, \"name\": \"Nerve Fibre Analysis\", \"price\": \"250.00\", \"total\": \"250.00\", \"cart_id\": 29, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 9, \"batch_number\": \"OCT001\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}, {\"eye\": null, \"name\": \"Ganglion Cell Complex\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 30, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 11, \"batch_number\": \"OCT002\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}, {\"eye\": null, \"name\": \"Macula Analysis\", \"price\": \"250.00\", \"total\": \"250.00\", \"cart_id\": 31, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 10, \"batch_number\": \"OCT003\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}]',NULL,'2026-07-16 10:40:30','2026-07-16 10:41:02',NULL),(20,4,13,19,'For OCT (Nerve fibre ananlysis & Ganglion cell analysis)',NULL,'[]','20/20','20/20',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,27.00,27.00,NULL,NULL,'[{\"eye\": null, \"name\": \"Ganglion Cell Complex\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 32, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 11, \"batch_number\": \"OCT002\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}, {\"eye\": null, \"name\": \"Nerve Fibre Analysis\", \"price\": \"250.00\", \"total\": \"250.00\", \"cart_id\": 33, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 9, \"batch_number\": \"OCT001\", \"is_dispensed\": false, \"category_name\": \"Optical Coherence Tomography (OCT)\"}]',NULL,'2026-07-16 11:28:28','2026-07-16 11:28:50',NULL),(21,4,14,20,'Referred from Abokobi Polyclinic for IOP, Fundus Photo and slitlamp examination',NULL,'[]','20/25','20/40',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14.00,10.00,NULL,NULL,'[{\"eye\": null, \"name\": \"Fundus Photo\", \"price\": \"200.00\", \"total\": \"200.00\", \"cart_id\": 34, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 19, \"batch_number\": \"FP001\", \"is_dispensed\": false, \"category_name\": \"Fundus Photography\"}]',NULL,'2026-07-17 12:00:57','2026-07-17 12:01:11',NULL),(22,4,16,21,'To replace spectacles\nPohx: SRx+(2)\nPmhx: Nil',NULL,'[]','20/20','20/40','NAD','NAD','NAD','NAD','Clear','Clear','Brown','Brown','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.10','0.10',NULL,NULL,NULL,NULL,'[{\"eye\": null, \"name\": \"SV Photo (+2.00 to -2.00, CYL -2.00) - Green AR\", \"price\": \"600.00\", \"total\": \"600.00\", \"cart_id\": 35, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 45, \"batch_number\": \"SVP001\", \"is_dispensed\": false, \"category_name\": \"Single Vision Lenses\"}]',NULL,'2026-07-17 17:02:12','2026-07-17 17:02:40',NULL),(23,4,15,22,'To replace spectcles',NULL,'[]','20/40','20/60','NAD','NAD','NAD','NAD','Clear','Clear','Brown','Brown','PERRLA','PERRLA','Clear','Clear','NAD','NAD','NAD','NAD','0.10','0.10',NULL,NULL,NULL,NULL,'[{\"eye\": null, \"name\": \"SV Photo (+2.00 to -2.00, CYL -2.00) - Green AR\", \"price\": \"600.00\", \"total\": \"600.00\", \"status\": \"pending\", \"cart_id\": 38, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 45, \"is_refunded\": false, \"batch_number\": \"SVP001\", \"dispensed_at\": null, \"is_dispensed\": false, \"category_name\": \"Single Vision Lenses\"}]',NULL,'2026-07-17 17:18:43','2026-07-17 17:25:53',NULL),(24,4,17,23,'Burning sensation and redness.\nIntermittent and gets worse when walking under the sun\nPohx: Srx-, Surgery-\nPmhx: DM-, HBP-',NULL,'[\"Tearing\"]','20/20','20/20','NAD','NAD','Papillae','Papillae','Clear','Clear','Round','Round','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.10','0.10',14.00,15.00,NULL,NULL,'[{\"eye\": \"Both Eyes\", \"name\": \"Tearlant\", \"price\": \"80.00\", \"total\": \"80.00\", \"cart_id\": 40, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Four Times Daily\", \"purchased\": false, \"product_id\": 81, \"batch_number\": \"TLT001\", \"is_dispensed\": false, \"category_name\": \"Drugs\"}, {\"eye\": \"Both Eyes\", \"name\": \"Dexatrol\", \"price\": \"80.00\", \"total\": \"80.00\", \"cart_id\": 41, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Four Times Daily\", \"purchased\": false, \"product_id\": 87, \"batch_number\": \"DXT001\", \"is_dispensed\": false, \"category_name\": \"Drugs\"}]',NULL,'2026-07-18 14:23:40','2026-07-18 14:26:23',NULL),(25,4,18,24,'Referred for refraction. Blurry vision at distance.\nPohx: Nil\nPmhx: DM-, HBP-',NULL,'[]','20/50','20/60','NAD','NAD','NAD','NAD','Clear','Clear','Round and Brown','Round and Brown','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.1','0.1',14.00,14.00,NULL,NULL,'[]',NULL,'2026-07-23 12:22:18','2026-07-23 12:22:18',NULL),(26,2,20,26,'REDNESS, PAIN, DISCHARGE',NULL,'[\"Tearing\", \"Photophobia\", \"Foreign Body Sensation\"]','20/50','20/40','NAD','NAD','Injected','slightly injected','Transparent','Transparent','brown-NAD','Brown-nad','round and reactive','round and reactive','IOL','OPACIFICATION',NULL,NULL,NULL,NULL,'0.4','NOT IN VIEW',9.00,13.00,NULL,NULL,'[{\"eye\": \"Both Eyes\", \"name\": \"Novacip\", \"price\": \"60.00\", \"total\": \"60.00\", \"status\": \"pending\", \"cart_id\": 42, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Three Times Daily\", \"purchased\": false, \"product_id\": 82, \"is_refunded\": false, \"batch_number\": \"NVC001\", \"dispensed_at\": null, \"is_dispensed\": false, \"category_name\": \"Drugs\"}, {\"eye\": \"Both Eyes\", \"name\": \"Tearlant\", \"price\": \"80.00\", \"total\": \"80.00\", \"status\": \"pending\", \"cart_id\": 43, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Four Times Daily\", \"purchased\": false, \"product_id\": 81, \"is_refunded\": false, \"batch_number\": \"TLT001\", \"dispensed_at\": null, \"is_dispensed\": false, \"category_name\": \"Drugs\"}]',NULL,'2026-07-25 10:35:22','2026-07-25 10:42:56',NULL),(27,2,19,25,'HEADACHE',NULL,'[]','20/20','20/20','NAD','NAD','CLEAR','CLEAR','CLEAR','CLEAR','BROWN','BROWN','ROUND AND REACTIVE','ROUND AND REATIVE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[{\"eye\": null, \"name\": \"SV Photo (+2.00 to -2.00, CYL -2.00) - Blue BC\", \"price\": \"700.00\", \"total\": \"700.00\", \"cart_id\": 44, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 46, \"batch_number\": \"SVP002\", \"is_dispensed\": false, \"category_name\": \"Single Vision Lenses\"}]',NULL,'2026-07-25 10:47:06','2026-07-25 10:49:04',NULL),(28,4,21,27,'For refraction',NULL,'[]','20/200','20/200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[{\"eye\": null, \"name\": \"SV Photo (+2.00 to -2.00, CYL -2.00) - Blue BC\", \"price\": \"700.00\", \"total\": \"700.00\", \"cart_id\": 47, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 46, \"batch_number\": \"SVP002\", \"is_dispensed\": false, \"category_name\": \"Single Vision Lenses\"}]',NULL,'2026-07-26 16:53:58','2026-07-26 16:54:14',NULL),(29,4,23,29,'CC: Blurred vision in the left eye\nOnset: Acute\nODQ: Painless, No discharge\nPohx: Trauma-\nPmhx: DM-, HBP-, Asthma-',NULL,'[]','20/20','20/200','NAD','NAD','NAD','Mild II','Clear','Hazy','Brown  and normal','Brown  and normal','Responsive','Sluggish','Clear','Clear','NAD','NAD','NAD','NAD','0.30','0.30',14.00,33.00,'Drugs Prescribed\n1. Gutt Sodium Chloride 5% LE i qid * 10/7\n2. Gutt Latanoprost LE i nocte * 1/12\n3. Gutt Timolol LE i bid *1/12',NULL,'[]',NULL,'2026-07-28 15:27:06','2026-07-28 16:05:46',NULL),(30,4,24,30,'Rubbing of the eyes',NULL,'[]','20/50','20/50','NAD','NAD','injection II','injection II','Clear','Clear','Brown ','Brown','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.10','0.10',NULL,NULL,NULL,NULL,'[{\"eye\": \"Both Eyes\", \"name\": \"Olopatadine\", \"price\": \"85.00\", \"total\": \"85.00\", \"status\": \"pending\", \"cart_id\": 49, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Twice Daily\", \"purchased\": false, \"product_id\": 80, \"is_refunded\": false, \"batch_number\": \"OLO001\", \"dispensed_at\": null, \"is_dispensed\": false, \"category_name\": \"Drugs\"}, {\"eye\": \"Both Eyes\", \"name\": \"Novacip\", \"price\": \"60.00\", \"total\": \"60.00\", \"cart_id\": 52, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Four Times Daily\", \"purchased\": false, \"product_id\": 82, \"batch_number\": \"NVC001\", \"is_dispensed\": false, \"category_name\": \"Drugs\"}]',NULL,'2026-07-29 15:28:20','2026-07-29 15:34:44',NULL),(31,4,23,31,'For review of condition',NULL,'[]','20/20','20/200',NULL,'NAD',NULL,'mild injection',NULL,'hazy I',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'hazy I',NULL,NULL,NULL,NULL,14.00,28.00,NULL,NULL,'[{\"eye\": \"OS (Left Eye)\", \"name\": \"Predilant\", \"price\": \"140.00\", \"total\": \"140.00\", \"cart_id\": 53, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Every 4 Hours\", \"purchased\": false, \"product_id\": 85, \"batch_number\": \"PRED001\", \"is_dispensed\": false, \"category_name\": \"Drugs\"}]',NULL,'2026-07-30 10:53:07','2026-07-30 10:53:52',NULL),(32,4,25,32,'Eye discharge and tearing\nPohx: Lazy eye\nPmhx: DM-, HBP-, SC-',NULL,'[]','20/200','20/30','NAD','NAD','Mild injection','Mild injection','Clear','Clear','Brown','Brown','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.30','0.30',15.00,15.00,'Constant Right Exotropia ',NULL,'[{\"eye\": \"Both Eyes\", \"name\": \"Novacip\", \"price\": \"60.00\", \"total\": \"60.00\", \"cart_id\": 54, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Every 4 Hours\", \"purchased\": false, \"product_id\": 82, \"batch_number\": \"NVC001\", \"is_dispensed\": false, \"category_name\": \"Drugs\"}]',NULL,'2026-07-30 11:01:30','2026-07-30 11:02:07',NULL),(33,1,26,33,'Blurry visoin at far and near\nPohx: SXR+ broken\nPmhx: DM-, HBP-',NULL,'[]','20/40','20/50','NAD','NAD','NAD','NAD','Pterygium','Pterygium','Round & Brown','Round & Brown','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.10','0.10',12.00,12.00,NULL,NULL,'[{\"eye\": null, \"name\": \"BF Photo (+2.00 to -2.00) - Green AR\", \"price\": \"900.00\", \"total\": \"900.00\", \"cart_id\": 55, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 57, \"batch_number\": \"BFP001\", \"is_dispensed\": false, \"category_name\": \"Lenses\"}]',NULL,'2026-08-04 14:40:28','2026-08-04 14:41:05',NULL),(34,1,27,34,'Routine examination',NULL,'[\"Itching\"]','20/20','20/20','NAD','NAD','Papillae','Papillae','Clear','Clear','Brown & Round','Brown & Round','PERRLA','PERRLA','Clear','Clear','Quiet','Quiet','NAD','NAD','0.10','0.10',NULL,NULL,NULL,NULL,'[{\"eye\": \"Both Eyes\", \"name\": \"Olopatadine\", \"price\": \"85.00\", \"total\": \"85.00\", \"cart_id\": 56, \"is_drug\": true, \"quantity\": 1, \"frequency\": \"Twice Daily\", \"purchased\": false, \"product_id\": 80, \"batch_number\": \"OLO001\", \"is_dispensed\": false, \"category_name\": \"Drugs\"}]',NULL,'2026-08-04 14:52:53','2026-08-04 14:53:14',NULL),(35,1,28,35,'Headache and blurry vison\nPohx: Nil, LEE > 5yrs\nPmhx: DM-, HBP+',NULL,'[]','20/40','20/40','NAD','NAD','NAD','NAD','Clear','Clear','Brown & Round','Brown & Round','PERRLA','PERRLA','Brunescent Opacity','Brunescent Opacity','Quiet','Quiet','NAD','NAD','0.20','0.20',20.00,20.00,NULL,NULL,'[{\"eye\": null, \"name\": \"BF Photo (+2.00 to -2.00) - Green AR\", \"price\": \"900.00\", \"total\": \"900.00\", \"cart_id\": 58, \"is_drug\": false, \"quantity\": 1, \"frequency\": null, \"purchased\": false, \"product_id\": 57, \"batch_number\": \"BFP001\", \"is_dispensed\": false, \"category_name\": \"Lenses\"}]',NULL,'2026-08-04 15:51:19','2026-08-04 15:51:31',NULL);
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=285 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `diagnoses`
--

LOCK TABLES `diagnoses` WRITE;
/*!40000 ALTER TABLE `diagnoses` DISABLE KEYS */;
INSERT INTO `diagnoses` VALUES (1,'Myopia, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(2,'Myopia, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(3,'Myopia, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(4,'Myopia, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(5,'Hypermetropia, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(6,'Hypermetropia, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(7,'Hypermetropia, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(8,'Hypermetropia, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(9,'Astigmatism, unspecified, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(10,'Astigmatism, unspecified, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(11,'Astigmatism, unspecified, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(12,'Irregular astigmatism, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(13,'Irregular astigmatism, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(14,'Irregular astigmatism, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(15,'Regular astigmatism, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(16,'Regular astigmatism, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(17,'Regular astigmatism, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(18,'Presbyopia','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(19,'Anisometropia and aniseikonia','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(20,'Internal ophthalmoplegia (total) (complete)','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(21,'Paresis of accommodation, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(22,'Paresis of accommodation, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(23,'Unspecified disorder of refraction and accommodation','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(24,'Encounter for eye exam without abnormal findings','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(25,'Encounter for eye exam with abnormal findings','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(26,'Unspecified conjunctivitis','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(27,'Mucopurulent conjunctivitis, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(28,'Mucopurulent conjunctivitis, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(29,'Mucopurulent conjunctivitis, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(30,'Acute atopic conjunctivitis, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(31,'Acute follicular conjunctivitis, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(32,'Chronic conjunctivitis, unspecified, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(33,'Chronic conjunctivitis, unspecified, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(34,'Chronic conjunctivitis, unspecified, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(35,'Vernal conjunctivitis','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(36,'Blepharoconjunctivitis, unspecified, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(37,'Blepharoconjunctivitis, unspecified, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(38,'Blepharoconjunctivitis, unspecified, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(39,'Pterygium, unspecified, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(40,'Pterygium, unspecified, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(41,'Pterygium, unspecified, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(42,'Conjunctival degeneration, unspecified, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(43,'Conjunctival degeneration, unspecified, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(44,'Pinguecula, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(45,'Pinguecula, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(46,'Pinguecula, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(47,'Conjunctival hemorrhage, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(48,'Conjunctival hemorrhage, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(49,'Conjunctival hemorrhage, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(50,'Conjunctival hemorrhage, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(51,'Unspecified corneal ulcer, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(52,'Unspecified superficial keratitis, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(53,'Unspecified superficial keratitis, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(54,'Punctate keratitis, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(55,'Punctate keratitis, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(56,'Corneal abrasion, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(57,'Corneal abrasion, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(58,'Keratoconjunctivitis sicca, right eye, not specified as Sjogren','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(59,'Keratoconjunctivitis sicca, left eye, not specified as Sjogren','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(60,'Keratoconjunctivitis sicca, bilateral, not specified as Sjogren','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(61,'Bullous keratopathy, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(62,'Arcus senilis, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(63,'Arcus senilis, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(64,'Arcus senilis, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(65,'Unspecified hereditary corneal dystrophy','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(66,'Keratoconus, unspecified, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(67,'Keratoconus, unspecified, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(68,'Keratoconus, unspecified, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(69,'Keratoconus, stable, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(70,'Keratoconus, stable, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(71,'Keratoconus, unstable, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(72,'Keratoconus, unstable, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(73,'Age-related nuclear cataract, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(74,'Age-related nuclear cataract, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(75,'Age-related nuclear cataract, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(76,'Age-related nuclear cataract, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(77,'Cortical age-related cataract, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(78,'Cortical age-related cataract, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(79,'Cortical age-related cataract, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(80,'Anterior subcapsular polar age-related cataract, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(81,'Anterior subcapsular polar age-related cataract, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(82,'Combined forms of age-related cataract, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(83,'Combined forms of age-related cataract, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(84,'Unspecified age-related cataract','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(85,'Unspecified cataract','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(86,'Unspecified subluxation of lens','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(87,'Unspecified disorder of lens','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(88,'Presence of intraocular lens','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(89,'Cataract extraction status, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(90,'Preglaucoma, unspecified, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(91,'Preglaucoma, unspecified, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(92,'Preglaucoma, unspecified, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(93,'Open angle with borderline findings, low risk, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(94,'Open angle with borderline findings, low risk, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(95,'Open angle with borderline findings, low risk, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(96,'Open angle with borderline findings, high risk, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(97,'Open angle with borderline findings, high risk, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(98,'Open angle with borderline findings, high risk, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(99,'Anatomical narrow angle, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(100,'Anatomical narrow angle, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(101,'Anatomical narrow angle, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(102,'Unspecified open-angle glaucoma, stage unspecified','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(103,'Primary open-angle glaucoma, right eye, mild stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(104,'Primary open-angle glaucoma, right eye, moderate stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(105,'Primary open-angle glaucoma, right eye, severe stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(106,'Primary open-angle glaucoma, left eye, mild stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(107,'Primary open-angle glaucoma, left eye, moderate stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(108,'Primary open-angle glaucoma, left eye, severe stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(109,'Primary open-angle glaucoma, bilateral, mild stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(110,'Primary open-angle glaucoma, bilateral, moderate stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(111,'Primary open-angle glaucoma, bilateral, severe stage','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(112,'Unspecified primary angle-closure glaucoma, stage unspecified','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(113,'Acute angle-closure glaucoma, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(114,'Acute angle-closure glaucoma, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(115,'Acute angle-closure glaucoma, bilateral','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(116,'Glaucoma secondary to eye trauma, right eye, stage unspecified','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(117,'Glaucoma secondary to other eye disorders, right eye, stage unspecified','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(118,'Unspecified glaucoma','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(119,'Glaucoma in diseases classified elsewhere','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(120,'Congenital glaucoma','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(121,'Retinal detachment with retinal break, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(122,'Retinal detachment with retinal break, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(123,'Serous retinal detachment, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(124,'Unspecified retinal break, without detachment','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(125,'Horseshoe tear of retina without detachment, right eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(126,'Horseshoe tear of retina without detachment, left eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(127,'Unspecified retinal vascular occlusion','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(128,'Central retinal artery occlusion, unspecified eye','2026-07-09 18:23:42','2026-07-09 18:23:42',NULL),(129,'Central retinal vein occlusion, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(130,'Central retinal vein occlusion, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(131,'Tributary (branch) retinal vein occlusion, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(132,'Tributary (branch) retinal vein occlusion, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(133,'Unspecified background retinopathy','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(134,'Retinal microaneurysms, unspecified, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(135,'Unspecified degeneration of macula and posterior pole','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(136,'Nonexudative age-related macular degeneration, right eye, early stage','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(137,'Nonexudative age-related macular degeneration, right eye, intermediate stage','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(138,'Nonexudative age-related macular degeneration, right eye, advanced atrophic without subfoveal involvement','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(139,'Exudative age-related macular degeneration, right eye, stage unspecified','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(140,'Exudative age-related macular degeneration, right eye, with active choroidal neovascularization','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(141,'Exudative age-related macular degeneration, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(142,'Cystoid macular degeneration, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(143,'Cystoid macular degeneration, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(144,'Drusen (degenerative) of macula, bilateral','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(145,'Puckering of macula, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(146,'Puckering of macula, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(147,'Peripheral retinal degeneration, unspecified, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(148,'Peripheral retinal degeneration, unspecified, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(149,'Lattice degeneration of retina, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(150,'Lattice degeneration of retina, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(151,'Retinal edema','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(152,'Vitreous hemorrhage, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(153,'Vitreous degeneration, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(154,'Vitreous floaters, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(155,'Vitreous floaters, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(156,'Vitreous floaters, bilateral','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(157,'Vitreomacular adhesion, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(158,'Cystoid macular edema following cataract surgery, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(159,'Type 2 diabetes mellitus with unspecified diabetic retinopathy with macular edema','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(160,'Type 2 diabetes mellitus with unspecified diabetic retinopathy without macular edema','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(161,'Type 2 diabetes with mild nonproliferative diabetic retinopathy with macular edema, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(162,'Type 2 diabetes with moderate nonproliferative diabetic retinopathy with macular edema, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(163,'Type 2 diabetes with severe nonproliferative diabetic retinopathy with macular edema, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(164,'Type 2 diabetes with proliferative diabetic retinopathy with macular edema, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(165,'Type 2 diabetes with proliferative diabetic retinopathy without macular edema, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(166,'Type 2 diabetes mellitus with diabetic cataract','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(167,'Type 2 diabetes mellitus with other diabetic ophthalmic complication','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(168,'Type 1 diabetes mellitus with unspecified diabetic retinopathy with macular edema','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(169,'Type 1 diabetes with proliferative diabetic retinopathy with traction retinal detachment involving the macula, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(170,'Cataract in diseases classified elsewhere','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(171,'Retinal disorders in diseases classified elsewhere','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(172,'Unspecified optic neuritis','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(173,'Ischemic optic neuropathy, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(174,'Ischemic optic neuropathy, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(175,'Unspecified papilledema','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(176,'Primary optic atrophy, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(177,'Primary optic atrophy, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(178,'Glaucomatous optic atrophy, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(179,'Glaucomatous optic atrophy, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(180,'Coloboma of optic disc, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(181,'Disorder of optic chiasm, unspecified, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(182,'Diplopia','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(183,'Unspecified subjective visual disturbance','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(184,'Transient visual loss, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(185,'Visual discomfort','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(186,'Unspecified visual field defect','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(187,'Scotoma involving central area, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(188,'Homonymous bilateral field defects, right side','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(189,'Generalized contraction of visual field, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(190,'Unspecified visual disturbance','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(191,'Transient cerebral ischemic attack, unspecified','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(192,'Benign intracranial hypertension','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(193,'Third [oculomotor] nerve palsy, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(194,'Fourth [trochlear] nerve palsy, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(195,'Sixth [abducent] nerve palsy, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(196,'Unspecified esotropia','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(197,'Monocular esotropia, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(198,'Monocular esotropia, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(199,'Unspecified exotropia','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(200,'Unspecified heterotropia','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(201,'Esophoria','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(202,'Exophoria','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(203,'Vertical heterophoria','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(204,'Cyclophoria','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(205,'Unspecified mechanical strabismus','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(206,'Convergence insufficiency','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(207,'Convergence excess','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(208,'Unspecified internuclear ophthalmoplegia','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(209,'Other specified disorders of binocular movement','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(210,'Deprivation amblyopia, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(211,'Refractive amblyopia, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(212,'Refractive amblyopia, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(213,'Strabismic amblyopia, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(214,'Strabismic amblyopia, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(215,'Unspecified nystagmus','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(216,'Hordeolum externum, right upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(217,'Hordeolum externum, right lower eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(218,'Hordeolum externum, left upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(219,'Hordeolum externum, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(220,'Chalazion right upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(221,'Chalazion right lower eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(222,'Chalazion left upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(223,'Unspecified blepharitis, right upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(224,'Unspecified blepharitis, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(225,'Allergic dermatitis of right upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(226,'Unspecified entropion, right upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(227,'Unspecified ectropion, right upper eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(228,'Unspecified ptosis, right eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(229,'Unspecified ptosis, unspecified eyelid','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(230,'Lid retraction, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(231,'Dry eye syndrome, right lacrimal gland','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(232,'Dry eye syndrome, left lacrimal gland','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(233,'Dry eye syndrome, bilateral lacrimal glands','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(234,'Epiphora due to insufficient drainage, right lacrimal gland','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(235,'Unspecified dacryoadenitis, unspecified lacrimal gland','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(236,'Unspecified stenosis and insufficiency of lacrimal passages, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(237,'Unspecified acute inflammation of orbit, right orbit','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(238,'Unspecified exophthalmos, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(239,'Pseudopterygium, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(240,'Sjogren syndrome, unspecified','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(241,'Unspecified hyphema','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(242,'Other vascular disorders of iris and ciliary body','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(243,'Unspecified iridocyclitis','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(244,'Acute iridocyclitis, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(245,'Acute iridocyclitis, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(246,'Iris atrophy (essential) (progressive), right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(247,'Ocular pain, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(248,'Ocular pain, right eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(249,'Ocular pain, left eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(250,'Ocular photophobia, unspecified eye','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(251,'Injury of conjunctiva and corneal abrasion without foreign body, right eye, initial encounter','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(252,'Injury of conjunctiva and corneal abrasion without foreign body, unspecified eye, initial encounter','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(253,'Foreign body on external eye, right eye, initial encounter','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(254,'Foreign body in cornea, right eye, initial encounter','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(255,'Contusion of eyeball and orbital tissues, right eye, initial encounter','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(256,'Unspecified injury of right eye and orbit, initial encounter','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(257,'Foreign body entering into or through eye or natural orifice, initial encounter','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(258,'Blindness, both eyes','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(259,'Blindness, one eye, low vision other eye, unspecified eyes','2026-07-09 18:23:43','2026-07-09 18:23:43',NULL),(260,'Blindness, one eye, unspecified eye','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(261,'Low vision, right eye, category 1, left eye normal vision','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(262,'Low vision, one eye, unspecified eye','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(263,'Unspecified visual loss','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(264,'Unspecified disorder of binocular vision','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(265,'Encounter for screening for eye and ear disorders','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(266,'Encounter for examination of eyes and vision with abnormal findings','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(267,'Presence of artificial eye','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(268,'Family history of blindness and visual loss','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(269,'Family history of glaucoma','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(270,'Type 2 diabetes mellitus without complications','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(271,'Essential (primary) hypertension','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(272,'Tinnitus, bilateral','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(273,'Corneal edema due to contact lens wear, right eye','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(274,'Corneal edema due to contact lens wear, left eye','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(275,'Contact lens associated corneal ulcer, right eye','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(276,'Contact lens associated corneal ulcer, left eye','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(277,'Presence of unspecified functional implant','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(278,'Other mechanical complication of ophthalmic contact lens, initial encounter','2026-07-09 18:23:44','2026-07-09 18:23:44',NULL),(279,'Healthy Eyes','2026-07-14 12:11:18','2026-07-14 12:11:18',NULL),(282,'Bacterial Conjunctivitis','2026-07-14 12:13:18','2026-07-14 12:13:18',NULL),(283,'Allergic Conjunctivitis','2026-07-14 12:13:38','2026-07-14 12:13:38',NULL),(284,'Glaucoma Suspect / Ocular Hypertension','2026-07-16 11:46:18','2026-07-16 11:46:18',NULL);
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
  `discount_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cart_snapshot` json DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operating_expense',
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6c757d',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `expense_categories` VALUES (1,'Staff Salaries','operating_expense','#3490dc',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25'),(2,'Rent / Utilities','operating_expense','#f6993f',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25'),(3,'Supplies','operating_expense','#38c172',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25'),(4,'Equipment','operating_expense','#9561e2',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25'),(5,'Maintenance','operating_expense','#e3342f',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25'),(6,'Marketing','operating_expense','#ff6384',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25'),(7,'Bank Charges','operating_expense','#6574cd',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25'),(8,'Miscellaneous','operating_expense','#6c757d',NULL,1,'2026-07-06 13:42:25','2026-07-06 13:42:25');
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
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `receipt_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,8,'2026-07-01','Annual Business Operating permit',190.00,'GCR20/0088193','Statutory payment',NULL,3,'2026-07-14 12:35:16','2026-07-14 12:35:16',NULL),(2,4,'2026-07-09','24V/2A Receipt Adapter',200.00,'REC-260709-M4NP','',NULL,3,'2026-07-14 12:39:16','2026-07-14 12:39:16',NULL),(3,4,'2026-07-09','Printer Cable 5m',100.00,'REC-260709-M4NP','',NULL,3,'2026-07-14 12:41:46','2026-07-14 12:41:59',NULL),(4,4,'2026-07-09','Receipt Paper Roll',60.00,'REC-260709-M4NP','',NULL,3,'2026-07-14 12:42:44','2026-07-14 12:42:44',NULL),(5,5,'2026-07-09','Consulting Room Door Handle',120.00,'7001','',NULL,3,'2026-07-14 12:43:56','2026-07-14 12:46:59',NULL),(6,5,'2026-07-09','Workmanship for fixing door handle',150.00,'7002','',NULL,3,'2026-07-14 12:44:49','2026-07-14 12:47:30',NULL),(7,5,'2026-07-10','Socket',40.00,'7003','',NULL,3,'2026-07-14 12:45:29','2026-07-14 12:46:49',NULL),(8,3,'2026-07-10','T-roll for the washroom',40.00,'7004','',NULL,3,'2026-07-14 12:46:12','2026-07-14 12:47:40',NULL),(9,3,'2026-07-10','Bine 20 for floor cleaning',25.00,'7005','',NULL,3,'2026-07-14 12:48:29','2026-07-14 12:48:29',NULL),(10,4,'2026-07-10','Printer Cable 5m',50.00,'REC-260710-S8WF','',NULL,3,'2026-07-14 12:50:04','2026-07-14 12:50:04',NULL),(11,4,'2026-07-10','Lan Cable 5m',30.00,'REC-260710-S8WF','',NULL,3,'2026-07-14 12:50:47','2026-07-14 12:50:47',NULL),(12,8,'2026-07-14','SSNIT Payment from June & July, 2026',540.00,'7006','Statutory payment',NULL,3,'2026-07-14 12:52:23','2026-07-14 12:52:37',NULL),(13,2,'2026-07-14','Electricity',100.00,'7007','',NULL,3,'2026-07-14 12:54:44','2026-07-14 12:54:53',NULL),(14,8,'2026-07-14','Delivery fee',40.00,'7008','From Lead Opticals to Accra',NULL,3,'2026-07-14 12:57:21','2026-07-14 12:57:21',NULL),(15,8,'2026-07-14','PAYE for June 2026',56.13,'7009','GRA',NULL,3,'2026-07-14 12:59:06','2026-07-14 12:59:06',NULL),(16,4,'2026-07-08','Thermal Printer B-F10',649.00,'26/3113037','',NULL,3,'2026-07-14 13:02:45','2026-07-14 13:02:45',NULL),(17,4,'2026-07-08','Colour LaserJet WIY44 M454DN',3299.00,'26/3113037','OCT & Fundus photography\'s printer',NULL,3,'2026-07-14 13:04:24','2026-07-14 13:04:24',NULL),(18,4,'2026-07-08','HP Laser Jet 425On',2500.00,'7010','VFT\'s printer',NULL,3,'2026-07-14 13:06:44','2026-07-14 13:07:03',NULL),(19,8,'2026-07-15','Delivery fee',45.00,'7010','From Accra to La Community Clinic',NULL,3,'2026-07-15 12:05:43','2026-07-15 12:05:43',NULL),(20,3,'2026-07-16','Photopaper',625.00,'7011','For OCT & Fundus Photography',NULL,3,'2026-07-16 12:25:24','2026-07-16 12:25:24',NULL),(21,3,'2026-07-17','Paper Tissue',25.00,'7012','',NULL,3,'2026-07-17 09:18:29','2026-07-17 09:18:29',NULL),(22,3,'2026-07-20','Tissue',11.00,'7013','Consulting room use',NULL,3,'2026-07-20 09:28:55','2026-07-20 09:28:55',NULL),(23,2,'2026-07-27','Electricity',100.00,'7014','Prepaid for office use',NULL,3,'2026-07-27 12:08:41','2026-07-27 12:08:41',NULL),(24,8,'2026-07-27','Delivery fee',42.00,'7015','Lead Opticals to Accra',NULL,3,'2026-07-27 13:28:19','2026-07-27 13:28:49',NULL),(25,3,'2026-07-29','A pack of Envelope',25.00,'7016','',NULL,3,'2026-07-29 15:27:12','2026-07-29 15:27:12',NULL),(26,8,'2026-07-29','Delivery Fee',35.00,'7017','Lead Opticals to La Community Clinic',NULL,3,'2026-07-29 15:28:08','2026-07-29 15:28:08',NULL),(27,8,'2026-07-30','Delivery Fee',35.00,'7018','Cananda Opticals to Lead Opticals',NULL,3,'2026-07-30 12:28:35','2026-07-30 12:28:35',NULL),(28,1,'2026-07-31','July\'s salary',1500.00,'7019','Dr. Hillary Debrah',NULL,3,'2026-08-04 12:07:12','2026-08-04 12:07:43',NULL),(29,2,'2026-08-05','Prepaid',100.00,'8001','ECG',NULL,3,'2026-08-05 10:16:32','2026-08-05 10:16:32',NULL);
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
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `section` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `section` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage` decimal(5,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `member_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `claim_amount` decimal(10,2) NOT NULL,
  `approved_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('draft','submitted','approved','partially_approved','rejected','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submission_date` date DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pre_auth_status` enum('not_required','pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_required',
  `pre_auth_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_auth_amount` decimal(10,2) DEFAULT NULL,
  `pre_auth_date` date DEFAULT NULL,
  `pre_auth_expiry_date` date DEFAULT NULL,
  `pre_auth_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheme_type` enum('NHIS','Private','Corporate') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NHIS',
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insurers`
--

LOCK TABLES `insurers` WRITE;
/*!40000 ALTER TABLE `insurers` DISABLE KEYS */;
INSERT INTO `insurers` VALUES (1,'Star Health Insurance','','Private','','0508426224','',1,'2026-07-09 15:59:41','2026-07-09 15:59:41',NULL),(2,'Acacia Health Insurance','','Private','','0596921844','',1,'2026-07-09 16:00:24','2026-07-09 16:00:24',NULL),(3,'Phoenix Health Insurance','','Private','','0243172646','',1,'2026-07-09 16:01:11','2026-07-09 16:01:11',NULL),(4,'OctaPlus Health Insurance','','Private','','','',1,'2026-07-09 16:01:58','2026-07-09 16:01:58',NULL),(5,'Equity Health Insurance','','Private','','0202543316','',1,'2026-07-09 16:02:49','2026-07-09 16:02:49',NULL),(6,'Glico Health Insurance','','Private','','0302746500','',1,'2026-07-09 16:03:33','2026-07-09 16:03:33',NULL),(7,'Ace Medical Insurance','','Private','','0257960860','',1,'2026-07-09 16:04:27','2026-07-09 16:04:27',NULL),(8,'Cosmopolitan Health Insurance','','Private','','0501529305','',1,'2026-07-09 16:05:09','2026-07-09 16:05:09',NULL),(9,'emPle Health Insurance','','Private','','0509791510','',1,'2026-07-09 16:05:51','2026-07-09 16:05:51',NULL),(10,'DOSH Health Insurance','','Private','','','',1,'2026-07-09 16:06:18','2026-07-09 16:06:18',NULL),(11,'GAB Health Insurance','','Private','','0557722516','',1,'2026-07-09 16:06:52','2026-07-09 16:06:52',NULL);
/*!40000 ALTER TABLE `insurers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lens_options`
--

DROP TABLE IF EXISTS `lens_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lens_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `family` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lens_options_family_display_name_unique` (`family`,`display_name`),
  KEY `lens_options_family_display_name_index` (`family`,`display_name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lens_options`
--

LOCK TABLES `lens_options` WRITE;
/*!40000 ALTER TABLE `lens_options` DISABLE KEYS */;
INSERT INTO `lens_options` VALUES (1,'Single Vision','SV Clear','2026-07-14 12:03:24','2026-07-14 12:03:24'),(2,'Single Vision','SV Hard Coat','2026-07-14 12:03:24','2026-07-14 12:03:24'),(3,'Single Vision','SV AR','2026-07-14 12:03:24','2026-07-14 12:03:24'),(4,'Single Vision','SV Photo AR','2026-07-14 12:03:24','2026-07-14 12:03:24'),(5,'Single Vision','SV Blue AR','2026-07-14 12:03:24','2026-07-14 12:03:24'),(6,'Single Vision','SV Blue Block','2026-07-14 12:03:24','2026-07-14 12:03:24'),(7,'Single Vision','SV Blue Block Photo','2026-07-14 12:03:24','2026-07-14 12:03:24'),(8,'Single Vision','SV Special Order','2026-07-14 12:03:24','2026-07-14 12:03:24'),(9,'Bifocal','Bifocal Clear','2026-07-14 12:03:24','2026-07-14 12:03:24'),(10,'Bifocal','Bifocal AR','2026-07-14 12:03:24','2026-07-14 12:03:24'),(11,'Bifocal','Bifocal Photo AR','2026-07-14 12:03:24','2026-07-14 12:03:24'),(12,'Bifocal','Bifocal Blue Block','2026-07-14 12:03:24','2026-07-14 12:03:24'),(13,'Bifocal','Special Order Bifocal','2026-07-14 12:03:24','2026-07-14 12:03:24'),(14,'Progressive','Progressive Clear','2026-07-14 12:03:24','2026-07-14 12:03:24'),(15,'Progressive','Progressive AR','2026-07-14 12:03:24','2026-07-14 12:03:24'),(16,'Progressive','Progressive Photo AR','2026-07-14 12:03:24','2026-07-14 12:03:24'),(17,'Progressive','Progressive Blue Block','2026-07-14 12:03:24','2026-07-14 12:03:24'),(18,'Progressive','Progressive Blue Block Photo','2026-07-14 12:03:24','2026-07-14 12:03:24'),(19,'Progressive','Special Order Progressive','2026-07-14 12:03:24','2026-07-14 12:03:24');
/*!40000 ALTER TABLE `lens_options` ENABLE KEYS */;
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
  `order_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `frame_model_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `frame_product_id` bigint unsigned DEFAULT NULL,
  `lens_product_id` bigint unsigned DEFAULT NULL,
  `frame_price` decimal(22,2) NOT NULL,
  `lens_price` decimal(22,2) NOT NULL,
  `lab_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stock_reserved_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pickUpDate` date NOT NULL,
  `collected_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `renewal_date` date DEFAULT NULL,
  `renewal_reminder_sent_at` timestamp NULL DEFAULT NULL,
  `renewal_approval_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lens_orders`
--

LOCK TABLES `lens_orders` WRITE;
/*!40000 ALTER TABLE `lens_orders` DISABLE KEYS */;
INSERT INTO `lens_orders` VALUES (1,3,2,'ORD-F3V4JQKT','To be assigned',NULL,NULL,0.00,0.00,0.00,NULL,'Collected','[Sent to lab - 16 Jul 2026 10:45]\n[Ready for pickup - 17 Jul 2026 17:01]\n[Ready for pickup - 17 Jul 2026 17:01]\n[Collected by Joselyne Bonsu on 25 Jul 2026 11:00]\n[Sent to lab - 25 Jul 2026 11:00]\n[Collected by Joselyne Bonsu on 25 Jul 2026 11:09]',0.00,'2026-07-21','2026-07-25 11:00:22',NULL,'2027-07-25',NULL,NULL,NULL,'2026-07-14 18:34:28','2026-07-25 11:09:50',NULL,NULL),(2,3,3,'ORD-9LUBWHSN','To be assigned',NULL,NULL,0.00,0.00,0.00,NULL,'Collected','[Sent to lab - 16 Jul 2026 10:44]\n[Sent to lab - 17 Jul 2026 17:40]\n[Collected by Joselyne Bonsu on 25 Jul 2026 11:00]',0.00,'2026-07-23','2026-07-25 11:00:10',NULL,'2027-07-25',NULL,NULL,NULL,'2026-07-16 10:44:42','2026-07-25 11:00:10',NULL,NULL),(3,3,4,'ORD-5MPB36UE','To be assigned',NULL,NULL,0.00,0.00,0.00,NULL,'Collected','Pickup on 23/07/2026\n[Sent to lab - 17 Jul 2026 17:40]\n[Ready for pickup - 20 Jul 2026 10:09]\n[Collected by Joselyne Bonsu on 20 Jul 2026 12:17]',0.00,'2026-07-24','2026-07-20 12:17:56',NULL,'2027-07-20',NULL,NULL,NULL,'2026-07-17 17:40:12','2026-07-20 12:17:56',NULL,NULL),(4,3,5,'ORD-TTDPLYHO','To be assigned',NULL,NULL,0.00,0.00,0.00,NULL,'Collected','[Sent to lab - 17 Jul 2026 17:40]\n[Ready for pickup - 20 Jul 2026 10:06]\n[Ready for pickup - 20 Jul 2026 10:06]\n[Collected by Joselyne Bonsu on 20 Jul 2026 12:18]',0.00,'2026-07-24','2026-07-20 12:18:02',NULL,'2027-07-20',NULL,NULL,NULL,'2026-07-17 17:40:25','2026-07-20 12:18:02',NULL,NULL),(5,3,6,'ORD-DHVE3AJR','To be assigned',NULL,NULL,0.00,0.00,0.00,NULL,'Ready','[Sent to lab - 29 Jul 2026 15:20]\n[Ready for pickup - 29 Jul 2026 15:20]',0.00,'2026-08-01',NULL,NULL,NULL,NULL,NULL,NULL,'2026-07-25 11:00:46','2026-07-29 15:20:18',NULL,NULL);
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
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login_logs_user_login_at_index` (`user_id`,`login_at`),
  CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_logs`
--

LOCK TABLES `login_logs` WRITE;
/*!40000 ALTER TABLE `login_logs` DISABLE KEYS */;
INSERT INTO `login_logs` VALUES (1,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 13:57:01'),(2,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 15:53:14'),(3,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 16:15:17'),(4,4,'192.168.100.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 16:20:05'),(5,3,'192.168.100.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-06 16:28:59'),(6,3,'192.168.100.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-06 16:45:18'),(7,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 16:50:14'),(8,3,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 16:53:37'),(9,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 17:00:25'),(10,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-06 17:08:15'),(11,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-07 16:13:57'),(12,2,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-07 16:18:56'),(13,3,'192.168.100.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-07 16:37:10'),(14,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-07 16:40:21'),(15,3,'192.168.100.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-07 16:46:34'),(16,3,'192.168.100.66','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-07 16:49:34'),(17,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 09:01:44'),(18,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 09:16:47'),(19,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 09:25:43'),(20,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 09:25:43'),(21,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 10:25:42'),(22,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 10:32:14'),(23,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 10:36:02'),(24,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 11:11:17'),(25,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 11:13:05'),(26,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 12:21:22'),(27,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 12:23:09'),(28,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 12:35:54'),(29,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 13:33:05'),(30,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 13:33:56'),(31,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 14:26:11'),(32,1,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 14:48:50'),(33,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 15:02:46'),(34,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 17:35:59'),(35,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 17:46:30'),(36,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 17:47:59'),(37,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 18:02:09'),(38,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 18:52:17'),(39,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-09 19:02:30'),(40,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-09 19:38:13'),(41,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 09:26:02'),(42,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-10 09:34:35'),(43,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 09:53:35'),(44,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 10:36:03'),(45,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 10:57:26'),(46,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 11:53:35'),(47,3,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 13:26:36'),(48,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 14:30:09'),(49,4,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 15:12:39'),(50,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 15:14:07'),(51,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 17:32:53'),(52,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-11 11:40:07'),(53,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-11 11:54:47'),(54,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-11 14:11:15'),(55,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-11 15:38:29'),(56,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 09:23:08'),(57,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 09:26:23'),(58,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 09:33:12'),(59,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 09:35:17'),(60,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 09:40:44'),(61,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 10:30:05'),(62,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 11:51:36'),(63,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 12:06:22'),(64,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 13:44:50'),(65,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:17:24'),(66,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:21:07'),(67,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 14:21:55'),(68,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 14:29:32'),(69,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 15:10:01'),(70,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 15:48:05'),(71,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 15:52:15'),(72,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 15:53:21'),(73,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 16:02:43'),(74,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 16:42:27'),(75,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 16:48:39'),(76,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 16:49:39'),(77,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 17:01:19'),(78,3,'192.168.100.68','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-13 17:09:21'),(79,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-13 17:28:08'),(80,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 09:17:52'),(81,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 09:25:19'),(82,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 10:26:05'),(83,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 11:11:21'),(84,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 12:27:12'),(85,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 15:16:08'),(86,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 15:20:13'),(87,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-14 17:53:41'),(88,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 17:58:43'),(89,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-14 18:42:31'),(90,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-15 10:06:13'),(91,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-15 12:04:31'),(92,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-15 15:47:16'),(93,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 06:59:41'),(94,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 07:00:09'),(95,1,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 07:01:19'),(96,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 07:43:20'),(97,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 07:44:13'),(98,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 07:56:01'),(99,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 07:56:32'),(100,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 09:34:27'),(101,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 09:42:06'),(102,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 10:35:06'),(103,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 10:42:49'),(104,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-16 10:51:28'),(105,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 11:25:46'),(106,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 11:45:46'),(107,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 12:50:51'),(108,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-16 16:47:21'),(109,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 09:02:18'),(110,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 10:00:50'),(111,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 11:33:34'),(112,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 12:37:45'),(113,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-17 16:14:27'),(114,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-17 16:27:41'),(115,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-18 09:50:18'),(116,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-18 10:15:29'),(117,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-18 13:29:02'),(118,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-18 13:59:35'),(119,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-18 14:03:58'),(120,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-20 09:14:09'),(121,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 09:14:21'),(122,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-20 09:45:02'),(123,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-20 09:52:55'),(124,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 11:10:47'),(125,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 12:17:36'),(126,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 13:30:19'),(127,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 13:31:54'),(128,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 15:23:44'),(129,3,'192.168.100.70','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 16:45:24'),(130,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-20 18:08:14'),(131,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-20 18:25:23'),(132,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-22 10:27:52'),(133,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 11:08:56'),(134,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 12:05:45'),(135,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-22 17:37:08'),(136,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-22 17:37:50'),(137,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23 11:01:58'),(138,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-23 11:10:47'),(139,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-23 11:47:43'),(140,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23 11:48:55'),(141,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-23 12:51:24'),(142,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-23 14:16:54'),(143,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-23 14:17:46'),(144,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24 08:52:17'),(145,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-24 13:07:26'),(146,2,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 09:10:14'),(147,2,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 09:16:07'),(148,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 09:55:17'),(149,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:11:29'),(150,2,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-25 10:12:25'),(151,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 10:56:09'),(152,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 12:08:42'),(153,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-25 14:15:56'),(154,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 16:28:58'),(155,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-26 16:29:11'),(156,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 16:33:26'),(157,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-26 17:04:59'),(158,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-26 17:19:51'),(159,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 09:24:57'),(160,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 10:01:23'),(161,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 11:50:22'),(162,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 11:53:33'),(163,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 12:06:08'),(164,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 12:40:26'),(165,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 13:27:33'),(166,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 13:41:51'),(167,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 15:54:10'),(168,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 16:51:27'),(169,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 17:54:50'),(170,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-27 18:02:43'),(171,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 18:03:29'),(172,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-27 18:04:03'),(173,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 11:46:58'),(174,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 11:50:04'),(175,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 12:56:58'),(176,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 13:11:17'),(177,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 13:11:37'),(178,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-28 15:10:31'),(179,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 15:12:55'),(180,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 15:13:06'),(181,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-29 15:10:30'),(182,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:11:41'),(183,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-29 15:18:04'),(184,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-29 15:26:00'),(185,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-28 16:17:36'),(186,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30 10:38:13'),(187,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-30 10:39:28'),(188,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-30 12:02:06'),(189,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-07-30 19:04:14'),(190,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-07-31 09:12:21'),(191,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0','2026-07-31 09:26:29'),(192,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-07-31 11:32:59'),(193,3,'192.168.100.74','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-01 13:02:47'),(194,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-01 14:57:09'),(195,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-03 10:08:58'),(196,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-03 15:01:50'),(197,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 10:23:36'),(198,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 12:03:30'),(199,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 12:05:48'),(200,1,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 14:11:51'),(201,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 14:12:40'),(202,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:19:54'),(203,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 15:22:10'),(204,4,'192.168.100.67','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','2026-08-04 15:59:48'),(205,3,'192.168.100.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-04 16:01:05'),(206,3,'192.168.100.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','2026-08-05 10:14:39');
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
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2022_01_15_155928_create_patients_table',1),(6,'2022_01_18_093243_create_cashier_patient_clearances_table',1),(7,'2022_01_18_112002_create_drugs_table',1),(8,'2022_01_18_112009_create_diagnoses_table',1),(9,'2022_01_18_123029_create_consultations_table',1),(10,'2022_02_07_181517_create_refractions_table',1),(11,'2022_05_31_100830_create_categories_table',1),(12,'2022_09_29_072408_create_products_table',1),(13,'2022_09_29_072439_create_stocks_table',1),(14,'2022_11_05_212856_create_spectacles_table',1),(15,'2022_11_23_111505_create_lens_orders_table',1),(16,'2022_12_24_095005_add_drugs_consultatations_table',1),(17,'2025_11_04_093900_create_permission_tables',1),(18,'2025_11_25_082252_create_sales_table',1),(19,'2025_11_25_083623_create_sale_items_table',1),(20,'2025_11_27_065808_create_refund_logs_table',1),(21,'2025_12_09_200333_create_carts_table',1),(22,'2025_12_09_201352_create_orders_table',1),(23,'2025_12_20_195839_create_appointments_table',1),(24,'2026_01_01_201337_create_consultation_diagnosis_table',1),(25,'2026_01_02_164132_create_login_logs_table',1),(26,'2026_01_02_190611_create_settings_table',1),(27,'2026_05_02_000001_create_income_statement_entries_table',1),(28,'2026_05_02_000001_create_referrals_table',1),(29,'2026_05_02_000002_add_audit_fields_to_income_statement_entries_table',1),(30,'2026_05_02_000002_extend_referrals_for_letter_types',1),(31,'2026_05_02_000003_add_workflow_audit_to_referrals',1),(32,'2026_05_02_000003_create_income_statement_templates_table',1),(33,'2026_05_02_000004_create_income_statement_period_locks_table',1),(34,'2026_05_02_000004_create_referral_snippets_table',1),(35,'2026_05_02_000005_add_payment_status_to_sales',1),(36,'2026_05_02_000006_create_payment_transactions_table',1),(37,'2026_05_02_000007_add_odq_to_consultations_table',1),(38,'2026_05_02_000008_add_cart_id_to_sale_items_table',1),(39,'2026_05_03_000001_add_discount_to_sales_table',1),(40,'2026_05_03_000001_create_patient_documents_table',1),(41,'2026_05_03_000002_add_discount_approved_by_to_sales_table',1),(42,'2026_05_03_000002_create_audit_trails_table',1),(43,'2026_05_03_000003_add_recall_reminder_and_missed_tracking_to_appointments',1),(44,'2026_05_03_000004_add_metadata_to_categories_table',1),(45,'2026_05_03_000005_create_discount_approval_requests_table',1),(46,'2026_05_03_000006_create_stock_movements_table',1),(47,'2026_05_03_221708_create_suppliers_table',1),(48,'2026_05_04_000001_add_performance_indexes_to_pos_tables',1),(49,'2026_05_04_065959_add_performance_indexes_to_pos_tables',1),(50,'2026_05_04_100000_add_eye_to_sale_items_table',1),(51,'2026_05_06_000001_fix_referrals_status_enum',1),(52,'2026_05_07_000001_create_password_reset_requests_table',1),(53,'2026_05_07_100000_add_product_ids_to_lens_orders',1),(54,'2026_05_09_000001_create_app_notifications_table',1),(55,'2026_05_09_000002_create_staff_messages_table',1),(56,'2026_05_09_000003_add_profile_columns_to_users_table',1),(57,'2026_05_09_215659_add_backup_extra_paths_to_settings_table',1),(58,'2026_05_10_120000_add_report_settings_to_settings_table',1),(59,'2026_05_10_130000_add_mail_settings_to_settings_table',1),(60,'2026_05_11_121619_add_va_notation_to_settings_table',1),(61,'2026_05_11_125314_add_sms_settings_to_settings_table',1),(62,'2026_05_11_213037_add_sms_enabled_to_settings_table',1),(63,'2026_05_11_214454_create_sms_templates_table',1),(64,'2026_05_11_215746_add_birthday_sms_filter_to_settings_table',1),(65,'2026_05_11_221459_add_custom_broadcast_sms_template',1),(66,'2026_05_11_222135_add_recall_sms_sent_at_to_patients_table',1),(67,'2026_05_11_222142_add_recall_settings_to_settings_table',1),(68,'2026_05_11_222149_add_recall_sms_template',1),(69,'2026_05_11_223146_create_sms_logs_table',1),(70,'2026_05_14_115943_add_soft_deletes_to_refund_logs_table',1),(71,'2026_05_14_121207_add_workflow_columns_to_refund_logs_table',1),(72,'2026_05_14_131750_add_dashboard_route_to_roles_table',1),(73,'2026_05_15_000001_create_clearance_revoke_logs_table',1),(74,'2026_05_19_000001_add_renewal_fields_to_lens_orders',1),(75,'2026_05_19_000002_add_spectacle_renewal_to_settings',1),(76,'2026_05_19_000003_add_spectacle_renewal_sms_template',1),(77,'2026_05_19_000004_add_renewal_approval_to_lens_orders',1),(78,'2026_05_19_000010_add_performance_indexes',1),(79,'2026_05_19_000011_add_whatsapp_to_settings',1),(80,'2026_05_19_000012_add_channel_to_sms_logs',1),(81,'2026_05_19_000013_create_expense_categories_table',1),(82,'2026_05_19_000014_create_expenses_table',1),(83,'2026_05_19_000015_add_section_to_expense_categories',1),(84,'2026_05_20_101532_add_service_id_to_cashier_patient_clearances_table',1),(85,'2026_05_20_133002_add_performance_indexes',1),(86,'2026_05_20_133534_create_sessions_table',1),(87,'2026_05_21_000001_add_license_fields_to_settings_table',1),(88,'2026_05_22_000001_add_currency_symbol_to_settings_table',1),(89,'2026_05_22_000002_create_quotations_table',1),(90,'2026_05_22_000003_create_quotation_items_table',1),(91,'2026_05_22_000004_create_purchase_orders_table',1),(92,'2026_05_22_000005_create_purchase_order_items_table',1),(93,'2026_05_22_233138_add_receipt_to_expenses_table',1),(94,'2026_05_23_000505_add_sale_id_to_cashier_patient_clearances_table',1),(95,'2026_05_24_000001_add_scalability_indexes',1),(96,'2026_05_24_000002_create_archive_tables',1),(97,'2026_05_25_000001_add_uuid_to_patients_and_clearances',1),(98,'2026_05_29_114937_create_insurers_table',1),(99,'2026_05_29_115133_create_insurance_claims_table',1),(100,'2026_05_29_131341_add_unique_sale_id_to_insurance_claims_table',1),(101,'2026_05_29_134501_add_insurance_fields_to_patients_table',1),(102,'2026_06_01_000001_add_invoice_fields_to_purchase_orders_table',1),(103,'2026_06_01_000002_add_pre_auth_fields_to_insurance_claims_table',1),(104,'2026_06_28_000001_create_consultation_notes_table',1),(105,'2026_06_28_000002_create_report_deliveries_table',1),(106,'2026_06_28_000003_create_system_health_statuses_table',1),(107,'2026_07_07_000001_add_insurance_member_name_to_patients_table',2),(108,'2026_07_14_000001_create_consultation_notes_table',3),(109,'2026_07_14_000002_create_lens_options_table',3),(110,'2026_07_15_000001_add_scheduling_fields_to_appointments_table',4),(111,'2026_07_15_000002_add_workflow_fields_to_lens_orders_table',4),(112,'2026_07_23_000001_add_customer_name_to_sales_table',4);
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
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(4,'App\\Models\\User',3),(5,'App\\Models\\User',3),(6,'App\\Models\\User',3),(2,'App\\Models\\User',4);
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
  `status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `actioned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_reset_requests_approved_by_foreign` (`approved_by`),
  KEY `password_reset_requests_email_index` (`email`),
  CONSTRAINT `password_reset_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_requests`
--

LOCK TABLES `password_reset_requests` WRITE;
/*!40000 ALTER TABLE `password_reset_requests` DISABLE KEYS */;
INSERT INTO `password_reset_requests` VALUES (1,'aamoasi@gmail.com','rejected',1,NULL,'2026-07-13 14:20:25','2026-07-06 17:05:06','2026-07-13 14:20:25');
/*!40000 ALTER TABLE `password_reset_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `document_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `pxnumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recall_sms_sent_at` timestamp NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `civil_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurer_id` bigint unsigned DEFAULT NULL,
  `insurance_member_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_member_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_policy_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES (1,'dcbca76f-c112-4163-8145-0f36023260e4',3,'PX-2929-26','NANA SACKEY','1945-05-06','Male','0243275084','',NULL,'ABOKOBI','','',NULL,'',NULL,'',NULL,'2026-07-09 10:37:04','2026-07-09 10:37:04'),(2,'5812417b-3e6a-4aef-a8b3-6a5edb22ae42',3,'PX-5223-26','Matilda Arhin-Sey','1943-09-03','Female','0244871853','michellegyau@yahoo.com',NULL,'Teiman- Abokobi','Pensioner','',NULL,'',NULL,'',NULL,'2026-07-10 09:42:38','2026-07-10 09:42:38'),(3,'42908f3f-9e09-4167-96bb-dc4b5cf5dcfc',1,'PX-8903-26','Heroine Sanahene','2012-10-17','Female','0244169392','',NULL,'Abokobi','Student','Single',NULL,'',NULL,'',NULL,'2026-07-10 11:25:13','2026-07-10 11:25:47'),(4,'dcd83e16-b074-49fa-92f5-e86736c312f9',1,'PX-2380-26','Mavis Sanahene','1988-02-02','Female','0244169392','',NULL,'Abokobi','Prescriber','Married',NULL,'',NULL,'',NULL,'2026-07-10 11:29:42','2026-07-10 11:29:42'),(5,'f7f8be96-b2cd-4538-af09-e0dea2ad6dc4',3,'PX-9681-26','Bernice Sarpong','1995-10-14','Female','0548216773','',NULL,'Kuottam Estate','Nurse','Married',NULL,'',NULL,'',NULL,'2026-07-10 15:01:40','2026-07-10 15:01:40'),(6,'95c88c9a-a7ed-49ec-a25c-58c792815019',3,'PX-1067-26','Treasure Mawuli Vadze','2024-03-14','Female','0552255021','',NULL,'Oyarifa','Student','Single',NULL,'','','',NULL,'2026-07-13 13:59:59','2026-07-13 13:59:59'),(7,'9953ff8e-5a8c-4495-b3b1-8d8911661a15',3,'PX-1606-26','Veronica Fafali Ami Adanusah','1997-12-06','Female','0535274702','falysdecor@gmail.com',NULL,'Obibini Junction','Baker','Single',NULL,'','','',NULL,'2026-07-13 14:02:36','2026-07-13 14:02:36'),(8,'38f6888a-52ba-4fda-ab19-ee4abcfeed6c',3,'PX-5987-26','Destiny Omefe','2006-07-07','Male','0538145278','',NULL,'Abokobi','Cashier','Single',NULL,'','','',NULL,'2026-07-13 14:23:05','2026-07-13 14:23:05'),(9,'eb24a01d-dbd3-42fd-821f-2ac05098a681',3,'PX-1738-26','Agnes Brenya','1992-04-11','Female','0246731749','nanayaafrema93@gmail.com',NULL,'Abokobi','Nurse','Married',NULL,'','','',NULL,'2026-07-14 15:20:05','2026-07-14 15:20:05'),(10,'c7d62517-e6cc-4636-be39-293190ef2eae',3,'PX-2313-26','Jayder Adjei-Dwomoh','2019-05-29','Female','0248896362','marykel.ad@gmail.com',NULL,'Kuottam, Oyarifa','Student','Single',9,'23762053-03','Nana Akua Animah Adjei-Dwomoh','Burgundy Option',NULL,'2026-07-14 17:57:08','2026-07-14 17:57:08'),(11,'b9bb09b6-6303-47be-a136-41ae83aaef2f',3,'PX-8146-26','Dominic Keteku','1955-04-15','Male','0246118662','',NULL,'Accra','','Married',NULL,'','','',NULL,'2026-07-16 09:40:02','2026-07-16 09:40:02'),(12,'4595632a-d5a3-4c5b-b0e9-acb2e7847bda',3,'PX-1493-26','Richard Tete Obu','1983-01-03','Male','0243827534','richobu@gmail.com',NULL,'Adjangote','Logistician','Married',9,'1090224-01','Richard Obu','Turquoise Option',NULL,'2026-07-16 10:29:46','2026-07-16 10:29:46'),(13,'e7c61ed4-d000-4a59-8ebb-24a5d41a7c87',3,'PX-2665-26','Bright Kampewu','1994-04-30','Male','0545119279','kampewuharoldbright@gmail.com',NULL,'Dambai','Engineer','Single',5,'EQ26966907583-0','Bright Kampewu','Shea',NULL,'2026-07-16 11:03:06','2026-07-16 11:03:06'),(14,'81c305da-8227-49a4-bde8-8c89fd35c12b',3,'PX-4289-26','Abigail Ampadu','1993-11-02','Female','0555931006','',NULL,'Agbogba','Entrepeneur','Single',NULL,'','','',NULL,'2026-07-17 11:43:24','2026-07-17 11:43:24'),(15,'3034d762-4bd1-4868-8a6f-0ee40d9e51e0',3,'PX-6319-26','Kwasi Korang Incoom','2010-10-30','Male','0597847173','victorlegend9843@gmail.com',NULL,'Ayimensah','Student','Single',NULL,'','','',NULL,'2026-07-17 16:21:41','2026-07-17 16:21:41'),(16,'a9670d71-a636-477c-85e3-3b23710a18f8',3,'PX-9087-26','Abena Yeboah Incoom','2008-10-07','Female','0545507479','abenayeboahincoom@gmail.com',NULL,'Ayimensah','Student','Single',NULL,'','','',NULL,'2026-07-17 16:22:59','2026-07-17 16:22:59'),(17,'88954ba6-576d-487b-8f46-671f52a44a2c',3,'PX-5432-26','Bertty Osei','1996-01-01','Female','0553675881','',NULL,'Pantang West','Cook','Single',NULL,'','','',NULL,'2026-07-18 14:02:00','2026-07-18 14:02:00'),(18,'ba60d86c-3257-4fa6-a9a2-330caa47b2e6',3,'PX-9723-26','Jeremy Apaluk Avoka','2000-10-07','Male','0545867270','jeremyavoka@gmail.com',NULL,'Oyarifa','Unemployed','Single',NULL,'','','',NULL,'2026-07-23 11:51:42','2026-07-23 11:51:42'),(19,'bc43d484-1d28-4773-8910-f971f5783cf7',3,'PX-2416-26','Stacy Morkor Quarshie','2006-03-25','Female','0533922057','quarshiemorkorstacy@gmail.com',NULL,'Prampram','Student','Single',NULL,'','','',NULL,'2026-07-25 10:09:46','2026-07-25 10:09:46'),(20,'155c058d-4209-4dac-a185-9a891b3fd6d6',3,'PX-2554-26','Alice Peprah','1941-07-11','Female','0249578345','',NULL,'Adenta','Pensioner','Widowed',NULL,'','','',NULL,'2026-07-25 10:15:01','2026-07-25 10:15:01'),(21,'eb9169df-6b3a-45c7-8200-3ae1afb0a85f',3,'PX-7026-26','Maame Efua Nhyira Amanku','2014-08-24','Female','0244476707','jamanku@outlook.com',NULL,'Ayi Mensah','Student','Single',NULL,'','','',NULL,'2026-07-26 16:31:42','2026-07-26 16:31:42'),(22,'7fc2c258-c373-4224-9e8c-75b3ecaf4e9a',3,'PX-4436-26','Neriah Naa Teiko Ayettey','2013-08-11','Female','0244605925','michaelayettey@gmail.com',NULL,'Abokobi','Student','Single',NULL,'','','',NULL,'2026-07-28 11:48:43','2026-07-28 11:48:43'),(23,'097a39c9-09ab-4f41-9fb4-841d1bb6c1eb',3,'PX-8954-26','Kojo Asereba','1995-10-12','Male','0548910691','',NULL,'Pantang West','Construction Worker','Single',NULL,'','','',NULL,'2026-07-28 15:11:41','2026-07-28 15:11:41'),(24,'d7b7c166-64f1-4258-bb87-3b9d4ceeafec',3,'PX-1573-26','Elike Kofi Caus-Siale','2019-11-01','Male','0509980124','evesiale@gmail.com',NULL,'Oyarifa','Student','Single',NULL,'','','',NULL,'2026-07-29 15:15:03','2026-07-29 15:15:03'),(25,'5f1b2152-52ec-4dd3-8898-d8c9c4093f53',3,'PX-7752-26','Awodeji Oluwafemi','1999-04-24','Male','0532112600','',NULL,'Oyarifa','Self Employed','Single',NULL,'','','',NULL,'2026-07-30 10:55:33','2026-07-30 10:55:33'),(26,'5d9d830b-9d49-4132-8444-237ba050502b',3,'PX-3539-26','Betty Aboah','1975-01-15','Female','0244292830','bettyaboah108@gmail.com',NULL,'Kuottam Estate','Teacher','Married',NULL,'','','',NULL,'2026-08-04 14:15:25','2026-08-04 14:15:25'),(27,'b4774294-3300-4636-8a5b-cf4644159288',3,'PX-8905-26','Samuel Aboah','2018-08-27','Male','0244292830','bettyaboah108@gmail.com',NULL,'Kuottam Estate','Student','Single',NULL,'','','',NULL,'2026-08-04 14:33:41','2026-08-04 14:33:41'),(28,'fcd65714-caf6-4ce4-8135-ba7c52805dea',3,'PX-6398-26','Cecilia Kaka','2026-05-11','Female','0539477792','',NULL,'Kuottam Estate','Retired Nurse','Married',NULL,'','','',NULL,'2026-08-04 15:23:27','2026-08-04 15:24:12');
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
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_transactions`
--

LOCK TABLES `payment_transactions` WRITE;
/*!40000 ALTER TABLE `payment_transactions` DISABLE KEYS */;
INSERT INTO `payment_transactions` VALUES (1,1,100.00,'cash','Clearance payment',3,'2026-07-09 17:48:25','2026-07-09 17:48:25'),(2,2,100.00,'cash','Clearance payment',3,'2026-07-10 09:44:38','2026-07-10 09:44:38'),(3,3,100.00,'cash','Clearance payment',3,'2026-07-10 10:24:14','2026-07-10 10:24:14'),(4,4,200.00,'momo','Full payment',3,'2026-07-10 15:14:44','2026-07-10 15:14:44'),(5,5,200.00,'cash','Clearance payment',3,'2026-07-13 14:00:23','2026-07-13 14:00:23'),(6,6,200.00,'cash','Clearance payment',3,'2026-07-13 14:03:11','2026-07-13 14:03:11'),(7,7,200.00,'cash','Full payment',3,'2026-07-13 14:13:51','2026-07-13 14:13:51'),(8,8,650.00,'momo','Full payment',3,'2026-07-13 14:24:18','2026-07-13 14:24:18'),(9,9,85.00,'cash','Full payment',3,'2026-07-13 14:27:20','2026-07-13 14:27:20'),(10,10,1000.00,'momo','Full payment',3,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(11,10,100.00,'cash','Full payment',3,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(12,11,200.00,'cash','Full payment',3,'2026-07-14 15:36:45','2026-07-14 15:36:45'),(13,12,700.00,'card','Full payment',3,'2026-07-14 18:28:39','2026-07-14 18:28:39'),(14,13,2200.00,'momo','Full payment',3,'2026-07-16 09:53:05','2026-07-16 09:53:05'),(15,14,250.00,'card','Full payment',3,'2026-07-16 10:41:16','2026-07-16 10:41:16'),(16,15,700.00,'card','Full payment',3,'2026-07-16 10:42:14','2026-07-16 10:42:14'),(17,16,250.00,'card','Full payment',3,'2026-07-16 11:30:33','2026-07-16 11:30:33'),(18,16,200.00,'cash','Full payment',3,'2026-07-16 11:30:33','2026-07-16 11:30:33'),(19,17,200.00,'momo','Full payment',3,'2026-07-17 12:39:34','2026-07-17 12:39:34'),(20,18,200.00,'cash','Clearance payment',3,'2026-07-17 16:25:00','2026-07-17 16:25:00'),(21,19,200.00,'cash','Clearance payment',3,'2026-07-17 16:28:26','2026-07-17 16:28:26'),(22,20,900.00,'momo','Full payment',3,'2026-07-17 17:28:14','2026-07-17 17:28:14'),(23,21,900.00,'momo','Full payment',3,'2026-07-17 17:32:49','2026-07-17 17:32:49'),(24,22,200.00,'cash','Clearance payment',3,'2026-07-18 14:02:15','2026-07-18 14:02:15'),(25,23,160.00,'momo','Full payment',3,'2026-07-18 14:30:52','2026-07-18 14:30:52'),(26,24,80.00,'cash','Full payment',1,'2026-07-20 09:55:36','2026-07-20 09:55:36'),(27,25,550.00,'cash','Full payment',3,'2026-07-20 15:27:46','2026-07-20 15:27:46'),(28,26,200.00,'cash','Clearance payment',3,'2026-07-23 11:52:01','2026-07-23 11:52:01'),(29,27,60.00,'cash','Full payment',3,'2026-07-25 10:03:26','2026-07-25 10:03:26'),(30,28,140.00,'cash','Full payment',3,'2026-07-25 10:40:49','2026-07-25 10:40:49'),(31,29,550.00,'momo','Initial deposit',3,'2026-07-25 10:55:59','2026-07-25 10:55:59'),(32,30,200.00,'cash','Clearance payment',3,'2026-07-26 16:32:02','2026-07-26 16:32:02'),(33,31,1150.00,'cash','Full payment',3,'2026-07-26 17:06:11','2026-07-26 17:06:11'),(34,32,735.00,'momo','Full payment',3,'2026-07-28 12:34:28','2026-07-28 12:34:28'),(35,33,150.00,'momo','Full payment',3,'2026-07-28 13:05:07','2026-07-28 13:05:07'),(36,34,200.00,'cash','Clearance payment',3,'2026-07-28 15:11:54','2026-07-28 15:11:54'),(37,35,200.00,'cash','Clearance payment',3,'2026-07-29 15:17:54','2026-07-29 15:17:54'),(38,29,300.00,'cash',NULL,3,'2026-07-29 15:19:34','2026-07-29 15:19:34'),(39,36,145.00,'momo','Full payment',3,'2026-07-29 15:35:17','2026-07-29 15:35:17'),(40,37,100.00,'cash','Clearance payment',3,'2026-07-30 10:42:30','2026-07-30 10:42:30'),(41,38,200.00,'cash','Clearance payment',3,'2026-07-30 10:55:48','2026-07-30 10:55:48'),(42,39,60.00,'code','Full payment',3,'2026-07-30 11:05:01','2026-07-30 11:05:01'),(43,40,200.00,'cash','Full payment',3,'2026-07-30 12:04:05','2026-07-30 12:04:05'),(44,41,100.00,'cash','Full payment',3,'2026-07-30 12:10:48','2026-07-30 12:10:48'),(45,42,200.00,'momo','Full payment',3,'2026-08-01 13:04:44','2026-08-01 13:04:44'),(46,43,200.00,'cash','Full payment',3,'2026-08-04 12:38:11','2026-08-04 12:38:11'),(47,44,200.00,'cash','Clearance payment',3,'2026-08-04 14:18:22','2026-08-04 14:18:22'),(48,45,200.00,'cash','Clearance payment',3,'2026-08-04 14:33:56','2026-08-04 14:33:56'),(49,46,85.00,'cash','Full payment',3,'2026-08-04 14:58:00','2026-08-04 14:58:00'),(50,47,380.00,'cash','Initial deposit',3,'2026-08-04 15:08:31','2026-08-04 15:08:31'),(51,48,200.00,'cash','Clearance payment',3,'2026-08-04 15:27:09','2026-08-04 15:27:09'),(52,49,900.00,'cash','Full payment',3,'2026-08-04 15:53:00','2026-08-04 15:53:00'),(53,50,200.00,'cash','Full payment',3,'2026-08-05 11:27:42','2026-08-05 11:27:42');
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
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
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=424 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Consultation',22,'CNST001',10000,0.01,200.00,'2026-07-01','2040-01-01','2026-07-10 11:36:55','2026-07-09 16:10:46','2026-07-10 11:36:55'),(2,1,'Autorefraction',20,'ATRF001',9996,0.01,100.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:12:47','2026-07-28 12:34:28'),(3,1,'Cyclo-refraction',20,'CYRF001',10000,0.01,150.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:14:50','2026-07-09 16:14:50'),(4,1,'Tonometry',22,'TNM001',9992,0.01,100.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:16:16','2026-08-05 11:27:42'),(5,1,'VFT 24-2',18,'VFT001',9998,0.01,150.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:18:00','2026-07-28 13:05:07'),(6,1,'VFT 30-2',18,'VFT002',10000,0.01,150.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:18:57','2026-07-09 16:18:57'),(7,1,'VFT 10-2',18,'VFT003',10000,0.01,150.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:19:44','2026-07-09 16:19:44'),(8,1,'Pachymetry',19,'OCT005',10000,0.01,100.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:22:25','2026-08-04 13:04:21'),(9,1,'Nerve Fibre Analysis',19,'OCT001',9994,0.01,250.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:25:21','2026-07-28 12:34:28'),(10,1,'Macula Analysis',19,'OCT003',9999,0.01,250.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:26:29','2026-07-16 10:42:14'),(11,1,'Ganglion Cell Complex',19,'OCT002',9994,0.01,200.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:27:36','2026-07-28 12:34:28'),(12,1,'5-Line Raster',19,'OCT004',10000,0.01,200.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:28:39','2026-07-09 16:28:39'),(13,1,'Anterior 3D',19,'OCT006',10000,0.01,120.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:29:54','2026-07-09 16:29:54'),(14,1,'Frame Case',23,'FRMC001',1000,15.00,40.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:33:12','2026-07-09 16:33:12'),(15,1,'Lens Solution',23,'LNS001',1000,15.00,40.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 16:34:18','2026-07-09 16:34:18'),(16,1,'Lens Transfer',23,'LT001',10000,0.01,100.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 17:39:35','2026-07-09 17:39:35'),(17,1,'Frame Adjustment',23,'FAD001',10000,0.01,100.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 17:41:02','2026-07-09 17:41:02'),(18,1,'Frame Retainer',23,'FR001',10000,0.01,50.00,'2026-07-01','2040-01-01',NULL,'2026-07-09 17:42:19','2026-07-09 17:42:19'),(19,3,'Fundus Photo',21,'FP001',9996,0.01,200.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 11:01:47','2026-08-01 13:04:44'),(20,1,'Walk-In',22,'WI-CNST002',10000,0.01,0.01,'2026-07-01','2040-01-01','2026-07-10 11:34:38','2026-07-10 11:19:01','2026-07-10 11:34:38'),(21,1,'Eye Test',22,'EX001',10000,0.01,0.01,'2026-07-01','2040-01-01','2026-07-10 11:40:14','2026-07-10 11:37:40','2026-07-10 11:40:14'),(22,1,'Eye Examination',23,'EEX001',10000,0.01,200.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 11:41:33','2026-07-10 15:21:40'),(23,1,'Consultation Fee',22,'CNS001',10000,0.01,200.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 11:51:40','2026-07-10 11:51:40'),(24,3,'OCELOT',15,'OC1005 C58',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 11:58:26','2026-07-10 11:58:26'),(25,3,'OCELOT 01',15,'OC2537 C2',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:15:45','2026-07-10 12:15:45'),(26,3,'OCELOT 02',15,'OC1003 C27-1',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:17:11','2026-07-10 12:17:11'),(27,3,'OCELOT 03',15,'OC1007 C27-1',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:18:16','2026-07-10 12:18:16'),(28,3,'OCELOT 04',15,'OC10121 C03',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:19:19','2026-07-10 12:19:19'),(29,3,'PAUL HUEMAN',15,'PH1121 C6',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:21:07','2026-07-10 12:21:07'),(30,3,'PAUL HUEMAN 01',15,'PH1121 C1',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:23:35','2026-07-10 12:23:35'),(31,3,'PAUL HUEMAN 02',15,'PH1121 C2',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:25:06','2026-07-10 12:25:06'),(32,3,'POLAROID',15,'D426/G 04',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:28:08','2026-07-10 12:28:08'),(33,3,'OCELOT 05',15,'OC10117 C06',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:29:19','2026-07-10 12:29:19'),(34,3,'OCELOT 06',15,'OC10117 C01',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:30:29','2026-07-10 12:30:29'),(35,3,'REACTION KENNETH COLE',15,'0003',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:32:00','2026-07-10 12:32:00'),(36,3,'EYECROXX',15,'EC523A C3',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:33:29','2026-07-10 12:33:29'),(37,3,'POLAROID 01',15,'D440/G R81',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:34:40','2026-07-10 12:34:40'),(38,3,'CAZAL ',15,'MSD8045 A5003',1,600.00,1200.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:37:44','2026-07-10 12:37:44'),(39,3,'CAZAL 01',15,'MSD8045 A5001',1,600.00,1200.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:38:59','2026-07-10 12:38:59'),(40,3,'OKI',15,'OOKI',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:40:14','2026-07-10 12:40:14'),(41,3,'CAZAL 02',15,'MOD.6038 C001',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:41:51','2026-07-10 12:42:38'),(42,3,'TOM MILTON',15,'TM514 C3',0,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:43:47','2026-07-16 09:53:05'),(43,1,'SV White (+2.00 to -2.00, CYL -2.00) - Green AR',16,'SVW001',10000,250.00,500.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:00'),(44,1,'SV White (+2.00 to -2.00, CYL -2.00) - Blue BC',16,'SVW002',10000,250.00,550.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:08'),(45,1,'SV Photo (+2.00 to -2.00, CYL -2.00) - Green AR',16,'SVP001',9998,275.00,600.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:15'),(46,1,'SV Photo (+2.00 to -2.00, CYL -2.00) - Blue BC',16,'SVP002',9997,325.00,700.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:22'),(47,1,'SV White (+4.00 to -4.00, CYL -4.00) - Green AR',16,'SVW003',10000,400.00,850.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:28'),(48,1,'SV White (+4.00 to -4.00, CYL -4.00) - Blue BC',16,'SVW004',10000,425.00,900.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:35'),(49,1,'SV Photo (+4.00 to -4.00, CYL -4.00) - Green AR',16,'SVP003',10000,425.00,950.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:41'),(50,1,'SV Photo (+4.00 to -4.00, CYL -4.00) - Blue BC',16,'SVP004',10000,450.00,1000.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:49'),(51,1,'SV White (+10.00 to -10.00, CYL -4.00) - Green AR',16,'SVW005',10000,500.00,1050.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:40:55'),(52,1,'SV White (+10.00 to -10.00, CYL -4.00) - Blue BC',16,'SVW006',10000,550.00,1100.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:41:05'),(53,1,'SV Photo (+10.00 to -10.00, CYL -4.00) - Green AR',16,'SVP005',10000,550.00,1100.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:41:14'),(54,1,'SV Photo (+10.00 to -10.00, CYL -4.00) - Blue BC',16,'SVP006',10000,600.00,1200.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:41:21'),(55,1,'BF White (+2.00 to -2.00) - Green AR',16,'BFW001',10000,400.00,800.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:37:02'),(56,1,'BF White (+2.00 to -2.00) - Blue BC',16,'BFW002',10000,425.00,850.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:37:11'),(57,1,'BF Photo (+2.00 to -2.00) - Green AR',16,'BFP001',9998,450.00,900.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-08-04 15:53:00'),(58,1,'BF Photo (+2.00 to -2.00) - Blue BC',16,'BFP002',10000,475.00,950.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:37:28'),(59,1,'BF White (+4.00 to -4.00, CYL -2.00) - Green AR',16,'BFW003',10000,450.00,1000.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:37:53'),(60,1,'BF White (+4.00 to -4.00, CYL -2.00) - Blue BC',16,'BFW004',10000,475.00,1100.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:38:07'),(61,1,'BF Photo (+4.00 to -4.00, CYL -2.00) - Green AR',16,'BFP003',10000,475.00,1200.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:38:18'),(62,1,'BF Photo (+4.00 to -4.00, CYL -2.00) - Blue BC',16,'BFP004',10000,500.00,1300.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:39:13'),(63,1,'Prog White (+2.00 to -2.00) - Green AR',16,'PGW001',10000,500.00,1100.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:34:07'),(64,1,'Prog White (+2.00 to -2.00) - Blue BC',16,'PGW002',10000,500.00,1200.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:35:11'),(65,1,'Prog Photo (+2.00 to -2.00) - Green AR',16,'PGP001',10000,600.00,1300.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:34:18'),(66,1,'Prog Photo (+2.00 to -2.00) - Blue BC',16,'PGP002',9999,650.00,1400.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:35:04'),(67,1,'Prog White (+4.00 to -4.00, CYL -2.00) - Green AR',16,'PGW003',10000,700.00,1500.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:34:27'),(68,1,'Prog White (+4.00 to -4.00, CYL -2.00) - Blue BC',16,'PGW004',10000,750.00,1600.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:34:54'),(69,1,'Prog Photo (+4.00 to -4.00, CYL -2.00) - Green AR',16,'PGP003',10000,750.00,1700.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:34:34'),(70,1,'Prog Photo (+4.00 to -4.00, CYL -2.00) - Blue BC',16,'PGP004',10000,800.00,1800.00,'2026-07-01','2040-01-01',NULL,'2026-07-10 12:44:30','2026-07-27 12:34:44'),(71,3,'PROTOTYPE',15,'UBBE 01',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:44:46','2026-07-10 12:44:46'),(72,3,'CAZAL 03',15,'MOD6038 C001',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:46:46','2026-07-10 12:46:46'),(73,3,'OCELOT 07',15,'OC2536 C1',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:49:24','2026-07-10 12:49:24'),(74,3,'T91115 C4',15,'T91115 C4',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:50:38','2026-07-10 12:50:38'),(75,3,'TOM MILTON 01',15,'TM507 C5',1,400.02,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:51:48','2026-07-10 12:51:48'),(76,3,'OCELOT 08',15,'OC2537 C1',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:53:00','2026-07-10 12:53:00'),(77,3,'TM5024A C1',15,'TM5024A C1',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:54:10','2026-07-10 12:54:10'),(78,3,'SKECHERS',15,'HA1022 C2',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 12:55:19','2026-07-10 12:55:19'),(79,3,'SCOUT',15,'JENNY BLACK',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 13:01:36','2026-07-10 13:01:36'),(80,3,'Olopatadine',14,'OLO001',8,42.50,85.00,'2026-07-01','2027-09-01',NULL,'2026-07-10 13:32:17','2026-08-04 14:58:00'),(81,3,'Tearlant',14,'TLT001',9,40.00,80.00,'2026-07-01','2028-02-01',NULL,'2026-07-10 13:33:51','2026-08-04 15:08:31'),(82,3,'Novacip',14,'NVC001',14,30.00,60.00,'2026-07-10','2028-04-01',NULL,'2026-07-10 13:35:13','2026-07-30 11:05:01'),(83,3,'Fluoromet',14,'FLM001',2,35.00,70.00,'2026-07-10','2027-07-01',NULL,'2026-07-10 13:36:15','2026-07-10 13:36:15'),(84,3,'Tobralant',14,'TBL001',1,42.50,85.00,'2026-07-10','2027-10-01',NULL,'2026-07-10 13:37:23','2026-07-13 14:27:20'),(85,3,'Predilant',14,'PRED001',4,70.00,140.00,'2026-07-10','2027-09-01',NULL,'2026-07-10 13:39:26','2026-07-10 13:39:26'),(86,3,'Moxilant',14,'MXT001',2,60.00,120.00,'2026-07-10','2027-01-01',NULL,'2026-07-10 13:40:24','2026-07-10 13:40:24'),(87,3,'Dexatrol',14,'DXT001',0,40.00,80.00,'2026-07-10','2026-08-01','2026-08-03 15:02:14','2026-07-10 13:41:32','2026-08-03 15:02:14'),(88,3,'HY3004 001',15,'HY3004 001',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:15:42','2026-07-10 16:15:42'),(89,3,'VINEYARD C1',15,'VINEYARD C1',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:17:21','2026-07-10 16:17:21'),(90,3,'LAPO MAN C1',15,'LAPO MAN C1',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:18:24','2026-07-10 16:18:24'),(91,3,'OC1726 C02',15,'OC1726 C02',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:34:49','2026-07-10 16:34:49'),(92,3,'OC2537 C3',15,'OC2537 C3',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:35:50','2026-07-10 16:35:50'),(93,3,'TOMMY HILFIGER',15,'TOMMY HILFIGER',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:38:30','2026-07-10 16:38:30'),(94,3,'JA4062 001',15,'JA4062 001',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:39:39','2026-07-10 16:39:39'),(95,3,'OC2534 C2',15,'OC2534 C2',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:42:22','2026-07-10 16:42:22'),(96,3,'D440/G R81',15,'D440/G R81 145',1,400.00,800.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:45:09','2026-07-10 16:45:09'),(97,3,'CE07945 004',15,'CE07945 004',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:48:10','2026-07-10 16:48:10'),(98,3,'CRYJD',15,'CRYJD',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:49:32','2026-07-10 16:49:32'),(99,3,'G2126 C6',15,'G2126 C6',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:50:32','2026-07-10 16:50:32'),(100,3,'PIERCE C2',15,'PIERCE C2',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:51:40','2026-07-10 16:51:40'),(101,3,'AY6907 C7',15,'AY6907 C7',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:53:40','2026-07-10 16:53:40'),(102,3,'KC0340 094',15,'KC0340 094',1,300.01,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:54:34','2026-07-10 16:54:34'),(103,3,'OF087/22 06',15,'OF087/22 06',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:55:44','2026-07-10 16:55:44'),(104,3,'MICHAEL C01',15,'MICHAEL C01',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:56:46','2026-07-10 16:56:46'),(105,3,'K8094 C6A',15,'K8094 C6A',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:58:13','2026-07-10 16:58:13'),(106,3,'OC345 C4',15,'OC345 C4',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 16:59:54','2026-07-10 16:59:54'),(107,3,'633 C2',15,'633 C2',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:02:40','2026-07-10 17:02:40'),(108,3,'CB312XL 0086',15,'CB312XL 0086',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:03:55','2026-07-10 17:03:55'),(109,3,'AC082',15,'AC082',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:05:18','2026-07-10 17:05:18'),(110,3,'QY2206 C2',15,'QY2206 C2',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:06:54','2026-07-10 17:06:54'),(111,3,'W53052 C05',15,'W53052 C05',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:07:58','2026-07-10 17:07:58'),(112,3,'JOHN C3',15,'JOHN C3',2,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:08:48','2026-07-11 12:03:33'),(113,3,'RA22243H',15,'RA22243H',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:10:28','2026-07-10 17:10:28'),(114,3,'STRATA C2',15,'STRATA C2',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:11:30','2026-07-10 17:11:30'),(115,3,'QY2235 C02',15,'QY2235 C02',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:12:13','2026-07-10 17:12:13'),(116,3,'FG1617 C4',15,'FG1617 C4',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:13:27','2026-07-10 17:13:27'),(117,3,'MICHAEL C03',15,'MICHAEL C03',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:17:24','2026-07-10 17:17:24'),(118,3,'AZ0002 C11',15,'AZ0002 C11',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:18:26','2026-07-10 17:18:26'),(119,3,'TBC722 B',15,'TBC722 B',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:19:57','2026-07-10 17:19:57'),(120,3,'BG6612MG C02G',15,'BG6612MG C02G',1,300.00,600.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:20:51','2026-07-10 17:20:51'),(121,3,'ARCHIPELAGO X02',15,'ARCHIPELAGO X02',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:23:26','2026-07-10 17:23:26'),(122,3,'2248 C1',15,'2248 C1',1,250.00,500.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:29:55','2026-07-10 17:29:55'),(123,1,'CZ8021 C6',15,'CZ8021 C6',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:34:12','2026-07-10 17:34:12'),(124,1,'CZ8022 C7',15,'CZ8022 C7',1,200.00,400.00,'2026-07-10','2030-01-01',NULL,'2026-07-10 17:35:27','2026-07-10 17:35:27'),(125,3,'JB06196 C5',15,'JB06196 C5',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(126,3,'33471297',15,'33471297',1,300.00,600.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(127,3,'E13111 C3',15,'E13111 C3',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(128,3,'D4041 C4',15,'D4041 C4',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(129,3,'JEMSEN',15,'JEMSEN',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(130,3,'PEV28008 CO2',15,'PEV28008 CO2',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(131,3,'RVMO5171',15,'RVMO5171',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(132,3,'AC088',15,'AC088',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(133,3,'HMO133',15,'HMO133',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(134,3,'KC01',15,'KC01',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(135,3,'CB247',15,'CB247',1,300.00,600.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(136,3,'GLASEEL C2',15,'GLASEEL C2',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(137,3,'JOULES',15,'JOULES',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(138,3,'ANDRE MATTE',15,'ANDRE MATTE',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(139,3,'L.145MM',15,'L.145MM',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(140,3,'47017 C433',15,'47017 C433',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(141,3,'V2116',15,'V2116',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(142,3,'KMC12',15,'KMC12',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(143,3,'TEMPUS C1',15,'TEMPUS C1',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(144,3,'OM363 BLK',15,'OM363 BLK',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(145,3,'MM12603 001',15,'MM12603 001',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(146,3,'CAC3030',15,'CAC3030',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(147,3,'AW5018 C04',15,'AW5018 C04',2,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(148,3,'TA9622C',15,'TA9622C',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(149,3,'1011 C3',15,'1011 C3',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(150,3,'AC213 C3',15,'AC213 C3',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(151,3,'BM030',15,'BM030',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(152,3,'ZYLOWARE',15,'ZYLOWARE',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(153,3,'MU7335 C02',15,'MU7335 C02',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(154,3,'ELS367 C2',15,'ELS367 C2',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(155,3,'FO-1011 C2',15,'FO-1011 C2',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(156,3,'RA25435L',15,'RA25435L',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(157,3,'GA3130-3 009',15,'GA3130-3 009',1,300.00,600.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(158,3,'MAT402 SILVER',15,'MAT402 SILVER',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(159,3,'10083-3',15,'10083-3',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(160,3,'RA23954HZ',15,'RA23954HZ',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(161,3,'GOLD 1012',15,'GOLD 1012',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(162,3,'RA24278H',15,'RA24278H',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(163,3,'CG026 C1',15,'CG026 C1',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(164,3,'AZ31197 C02',15,'AZ31197 C02',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(165,3,'RA23189H',15,'RA23189H',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(166,3,'RA24493L',15,'RA24493L',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(167,3,'13138-001',15,'13138-001',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(168,3,'1858',15,'1858',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(169,3,'GUNMETAL',15,'GUNMETAL',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(170,3,'Z444 C01',15,'Z444 C01',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:00:16','2026-07-11 12:00:16'),(171,1,'T122 C4',15,'T122 C4',2,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(172,1,'T122 C5',15,'T122 C5',1,200.00,400.00,'2026-11-07','2030-01-01','2026-08-03 15:02:51','2026-07-11 12:07:31','2026-08-03 15:02:51'),(173,1,'CZ8021 C2',15,'CZ8021 C2',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(174,1,'CZ8024 C7',15,'CZ8024 C7',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(175,1,'CZ8019 C5',15,'CZ8019 C5',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(176,1,'T123 C8',15,'T123 C8',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(177,1,'T104 C2',15,'T104 C2',2,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(178,1,'CZ8017 C7',15,'CZ8017 C7',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(179,1,'T102 C2',15,'T102 C2',4,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(180,1,'CZ8020 C7',15,'CZ8020 C7',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(181,1,'CZ8021 C1',15,'CZ8021 C1',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(182,1,'T120 C5',15,'T120 C5',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(183,1,'T102 C5',15,'T102 C5',2,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-08-03 15:07:31'),(184,1,'T122 C8',15,'T122 C8',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(185,1,'CZ8018 C3',15,'CZ8018 C3',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(186,1,'T106 C8',15,'T106 C8',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(187,1,'T106 C2',15,'T106 C2',1,200.00,400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(188,1,'OS3737 C3',15,'OS3737 C3',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(189,1,'KL6199S',15,'KL6199S',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(190,1,'OS3761 C3',15,'OS3761 C3',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(191,1,'S 5005103 B71',15,'S 5005103 B71',1,650.00,1300.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(192,1,'S 5005103 A01',15,'S 5005103 A01',2,650.00,1300.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(193,1,'S 5005134 C41',15,'S 5005134 C41',1,600.00,1200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(194,1,'S 5005103 AB1',15,'S 5005103 AB1',1,600.00,1200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(195,1,'S 5005134 BB1',15,'S 5005134 BB1',1,600.00,1200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(196,1,'L250505 C3',15,'L250505 C3',3,750.00,1500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(197,1,'9315 561',15,'9315 561',2,750.00,1500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(198,1,'POL MV 251 C1',15,'POL MV 251 C1',2,600.00,1200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(199,1,'K1466 C2',15,'K1466 C2',2,900.00,1800.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-08-03 15:05:29'),(200,1,'C Zampatti 30',15,'C Zampatti 30',1,600.00,1200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(201,1,'Mod. 6511.300',15,'Mod. 6511.300',2,600.00,1200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-08-03 15:15:07'),(202,1,'Carla',15,'Carla',1,600.00,1200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(203,1,'SS3049',15,'SS3049',1,700.00,1400.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(204,1,'LOT N.0125',15,'LOT N.0125',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 12:07:31','2026-07-11 12:07:31'),(205,1,'AM-C101',15,'AM-C101',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(206,1,'73MH61 BRW9053',15,'73MH61 BRW9053',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(207,1,'PO3310S',15,'PO3310S',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(208,1,'EX1123 C3',15,'EX1123 C3',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(209,1,'CAT.3 PABLO 01',15,'CAT.3 PABLO 01',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(210,1,'73MH61 BRW9056',15,'73MH61 BRW9056',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(211,1,'88309 GR Cat.3',15,'88309 GR Cat.3',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(212,1,'Mod. 6320.300*3',15,'Mod. 6320.300*3',1,250.00,500.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(213,1,'OER1049 3028',15,'OER1049 3028',1,1600.00,3200.00,'2026-11-07','2030-01-01','2026-08-03 15:03:36','2026-07-11 14:17:13','2026-08-03 15:03:36'),(214,1,'VE2672/S COL.3',15,'VE2672/S COL.3',1,1600.00,3200.00,'2026-11-07','2030-01-01',NULL,'2026-07-11 14:17:13','2026-07-11 14:17:13'),(215,3,'PR18WV 16K-08ZB',15,'PR18WV 16K-08ZB',7,1200.00,2400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-25 12:10:46'),(216,3,'PR18WV 17N-90BS',15,'PR18WV 17N-90BS',1,1200.00,2400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-13 09:39:53'),(217,3,'TF5634 001',15,'TF5634 001',3,1500.00,3000.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-25 12:12:22'),(218,3,'TF5634 002',15,'TF5634 002',2,1500.00,3000.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-13 09:39:53'),(219,3,'TF5634 003',15,'TF5634 003',3,1500.00,3000.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-13 09:39:53'),(220,3,'TF5634 004',15,'TF5634 004',1,1500.00,3000.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-25 12:13:05'),(221,3,'TF0646 004',15,'TF0646 004',1,1500.00,3000.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-13 09:39:53'),(222,3,'TF0646 001',15,'TF0646 001',1,1500.00,3000.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 09:39:53','2026-07-13 09:39:53'),(223,3,'OC1722 CO3',15,'OC1722 CO3',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:23:11','2026-07-13 12:23:11'),(224,3,'TM 503 C2',15,'TM 503 C2',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:24:18','2026-07-13 12:24:18'),(225,3,'YD1101 C4',15,'YD1101 C4',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:24:57','2026-07-13 12:24:57'),(226,3,'OC10203 C04',15,'OC10203 C04',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:25:56','2026-07-13 12:25:56'),(227,3,'TM503 C5',15,'TM503 C5',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:26:35','2026-07-13 12:26:35'),(228,3,'OC1858 C5',15,'OC1858 C5',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:27:21','2026-07-13 12:27:21'),(229,3,'OC10158 C05',15,'OC10158 C05',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:28:21','2026-07-13 12:28:21'),(230,3,'TM532 C4',15,'TM532 C4',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:29:06','2026-07-13 12:29:06'),(231,3,'OC1728 C13',15,'OC1728 C13',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:29:47','2026-07-13 12:29:47'),(232,3,'OC10197 C07',15,'OC10197 C07',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:30:26','2026-07-13 12:30:26'),(233,3,'QY 2205 C4',15,'QY 2205 C4',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:31:14','2026-07-13 12:31:14'),(234,3,'OC10197 C03',15,'OC10197 C03',0,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:31:56','2026-07-25 10:55:59'),(235,3,'OC10131 C5',15,'OC10131 C5',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:32:48','2026-07-13 12:32:48'),(236,1,'OPAA160 C10',15,'OPAA160 C10',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:33:13','2026-07-13 12:33:13'),(237,3,'OC10131 C3',15,'OC10131 C3',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:33:24','2026-07-13 12:33:24'),(238,1,'PCL4485 C2',15,'PCL4485 C2',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:34:54','2026-07-13 12:34:54'),(239,1,'SV-2204 TOR HM',15,'SV-2204 TOR HM',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:35:54','2026-07-13 12:35:54'),(240,3,'DELUX006 C01',15,'DELUX006 C01',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:35:59','2026-07-13 12:35:59'),(241,1,'OC1888 C227',15,'OC1888 C227',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:36:55','2026-07-13 12:36:55'),(242,3,'PERTEGAZ GREEN',15,'PERTEGAZ GREEN',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:37:01','2026-07-13 12:37:01'),(243,3,'MILLER C02',15,'MILLER C02',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:37:41','2026-07-13 12:37:41'),(244,1,'EY586 C3',15,'EY586 C3',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:37:43','2026-07-13 12:37:43'),(245,3,'12449330002',15,'12449330002',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:38:33','2026-07-13 12:38:33'),(246,1,'503177 00',15,'503177 00',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:38:53','2026-07-13 12:38:53'),(247,3,'DELUX001 C03',15,'DELUX001 C03',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:39:28','2026-07-13 12:39:28'),(248,1,'TJ 0051 PJP',15,'TJ 0051 PJP',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:39:52','2026-07-13 12:39:52'),(249,3,'DELUX002 C01',15,'DELUX002 C01',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:40:15','2026-07-13 12:40:15'),(250,1,'OC10162 C7',15,'OC10162 C7',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:40:43','2026-07-13 12:40:43'),(251,3,'SV2204 PRP',15,'SV2204 PRP',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:40:59','2026-07-13 12:40:59'),(252,1,'SPK007 C2',15,'SPK007 C2',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:41:34','2026-07-13 12:41:34'),(253,3,'588187 50',15,'588187 50',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:41:46','2026-07-13 12:41:46'),(254,1,'OC10197 C6',15,'OC10197 C6',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:42:26','2026-07-13 12:42:26'),(255,3,'ZYLOWARE 097',15,'ZYLOWARE 097',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:42:35','2026-07-13 12:42:35'),(256,1,'OC1859 C6',15,'OC1859 C6',1,300.00,300.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:43:13','2026-07-13 12:43:13'),(257,3,'Q5005 C7',15,'Q5005 C7',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:43:41','2026-07-13 12:43:41'),(258,1,'OC 2514 C6',15,'OC 2514 C6',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:44:14','2026-07-13 12:44:14'),(259,3,'0521 C2',15,'0521 C2',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:44:17','2026-07-13 12:44:17'),(260,3,'Q5005 C5',15,'Q5005 C5',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:44:55','2026-07-13 12:44:55'),(261,1,'TM502 C5',15,'TM502 C5',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:45:01','2026-07-13 12:45:01'),(262,3,'Q5005 C4',15,'Q5005 C4',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:45:33','2026-07-13 12:45:33'),(263,1,'TM506 C3',15,'TM506 C3',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:45:50','2026-07-13 12:45:50'),(264,3,'2702',15,'2702',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:46:16','2026-07-13 12:46:16'),(265,3,'0189A E',15,'0189A E',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:48:05','2026-07-13 12:48:05'),(266,3,'506185 50',15,'506185 50',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:48:57','2026-07-13 12:48:57'),(267,3,'0322A B',15,'0322A B',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:49:46','2026-07-13 12:49:46'),(268,3,'AGATHA RUIZ',15,'AGATHA RUIZ',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:50:55','2026-07-13 12:50:55'),(269,3,'SIV010 C07',15,'SIV010 C07',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:52:04','2026-07-13 12:52:04'),(270,3,'V622-C154',15,'V622-C154',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:52:45','2026-07-13 12:52:45'),(271,3,'V622-C167',15,'V622-C167',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:53:21','2026-07-13 12:53:21'),(272,3,'V615-C155',15,'V615-C155',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:54:14','2026-07-13 12:54:14'),(273,3,'SV-2209 PRP',15,'SV-2209 PRP',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:55:07','2026-07-13 12:55:07'),(274,1,'FG1276 C4',15,'FG1276 C4',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:55:42','2026-07-13 12:55:42'),(275,3,'SV-2323 BLK',15,'SV-2323 BLK',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:55:50','2026-07-13 12:55:50'),(276,3,'HL50024 074',15,'HL50024 074',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:56:31','2026-07-13 12:56:31'),(277,1,'MISSMOE-7303',15,'MISSMOE-7303',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:57:05','2026-07-13 12:57:05'),(278,3,'0521 C5',15,'0521 C5',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:57:15','2026-07-13 12:57:15'),(279,1,'BM780 C3',15,'BM780 C3',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:57:57','2026-07-13 12:57:57'),(280,3,'NANCY C03',15,'NANCY C03',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:58:03','2026-07-13 12:58:03'),(281,3,'VLF26001 C4',15,'VLF26001 C4',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:58:42','2026-07-13 12:58:42'),(282,1,'LPI 2251 C5',15,'LPI 2251 C5',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:58:50','2026-07-13 12:58:50'),(283,1,'XZA825',15,'XZA825',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 12:59:50','2026-07-13 12:59:50'),(284,1,'FG6-2211 PNK',15,'FG6-2211 PNK',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:01:01','2026-07-13 13:01:01'),(285,1,'M RX T 0061A',15,'M RX T 0061A',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:02:11','2026-07-13 13:02:11'),(286,3,'33889160 01',15,'33889160 01',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:02:44','2026-07-13 13:02:44'),(287,3,'01-43710-01',15,'01-43710-01',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:03:39','2026-07-13 13:03:39'),(288,1,'506193 80 2066 C3',15,'506193 80 2066 ',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:03:48','2026-07-13 13:03:48'),(289,3,'DELUX002 C04',15,'DELUX002 C04',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:04:22','2026-07-13 13:04:22'),(290,1,'ZYLOWARE 002',15,'ZYLOWARE 002',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:04:36','2026-07-13 13:04:36'),(291,3,'HA1014 C4',15,'HA1014 C4',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:04:58','2026-07-13 13:04:58'),(292,1,'GL 81057 472',15,'GL 81057 472',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:05:25','2026-07-13 13:05:25'),(293,3,'OLIVE-7303',15,'OLIVE-7303',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:05:46','2026-07-13 13:05:46'),(294,1,'QY 2242 C01',15,'QY 2242 C01',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:06:16','2026-07-13 13:06:16'),(295,3,'SV-2209 TAN',15,'SV-2209 TAN',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:06:34','2026-07-13 13:06:34'),(296,1,'JGX948145-03',15,'JGX948145-03',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:07:18','2026-07-13 13:07:18'),(297,3,'6303 LIME',15,'6303 LIME',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:07:51','2026-07-13 13:07:51'),(298,1,'SV-2206 LAV HM',15,'SV-2206 LAV HM',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:08:07','2026-07-13 13:08:07'),(299,1,'MZ280C03',15,'MZ280C03',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:08:50','2026-07-13 13:08:50'),(300,3,'VGO005 WHT',15,'VGO005 WHT',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:10:29','2026-07-13 13:10:29'),(301,3,'DOLABANY',15,'DOLABANY',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:12:19','2026-07-13 13:12:19'),(302,3,'DE01-0185-AYDNP',15,'DE01-0185-AYDNP',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:14:00','2026-07-13 13:14:00'),(303,1,'CM 2027 PURPLE',15,'CM 2027 PURPLE',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:14:13','2026-07-13 13:14:13'),(304,3,'PERTEGAZ BLK',15,'PERTEGAZ BLK',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:14:45','2026-07-13 13:14:45'),(305,1,'503212 30',15,'503212 30',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:14:59','2026-07-13 13:14:59'),(306,3,'GL 81057 435',15,'GL 81057 435',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:15:28','2026-07-13 13:15:28'),(307,1,'LACER15-00',15,'LACER15-00',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:15:51','2026-07-13 13:15:51'),(308,3,'VS2481 C3',15,'VS2481 C3',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:16:06','2026-07-13 13:16:06'),(309,1,'FG1283 C1',15,'FG1283 C1',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:16:31','2026-07-13 13:16:31'),(310,3,'XZM 113',15,'XZM 113',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:16:48','2026-07-13 13:16:48'),(311,1,'QY 2240 C01',15,'QY 2240 C01',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:17:12','2026-07-13 13:17:12'),(312,1,'HL50024 020',15,'HL50024 020',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:17:57','2026-07-13 13:17:57'),(313,1,'BNA1248 C662',15,'BNA1248 C662',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:18:45','2026-07-13 13:18:45'),(314,1,'PRISCILLA BLACK',15,'PRISCILLA BLACK',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:19:52','2026-07-13 13:19:52'),(315,1,'IG42431 A',15,'IG42431 A',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:20:43','2026-07-13 13:20:43'),(316,3,'AGUA MARINA 001',15,'AGUA MARINA 001',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:21:21','2026-07-13 13:21:21'),(317,1,'PISAMORENA C3',15,'PISAMORENA C3',1,300.00,600.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:21:53','2026-07-13 13:21:53'),(318,3,'BATTATURA',15,'BATTATURA',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:22:30','2026-07-13 13:22:30'),(319,1,'F2111',15,'F2111',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:22:53','2026-07-13 13:22:53'),(320,3,'M34947 C1',15,'M34947 C1',1,250.00,500.00,'2026-07-13','2030-01-01','2026-08-03 15:04:47','2026-07-13 13:23:37','2026-08-03 15:04:47'),(321,1,'DB 2139 002',15,'DB 2139 002',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:23:40','2026-07-13 13:23:40'),(322,3,'ZYLOWARE 140MM',15,'ZYLOWARE 140MM',1,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:24:28','2026-07-13 13:24:28'),(323,1,'VIBRANT C1',15,'VIBRANT C1',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:24:51','2026-07-13 13:24:51'),(324,3,'AL30137 512',15,'AL30137 512',0,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:25:23','2026-07-26 17:06:11'),(325,1,'CLEARLY',15,'CLEARLY',1,250.00,500.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:25:29','2026-07-13 13:25:29'),(326,3,'FG1276 C1',15,'FG1276 C1',1,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:26:29','2026-07-13 13:26:29'),(327,3,'RV1624 04',15,'RV1624 04',1,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:27:13','2026-07-13 13:27:13'),(328,3,'PRE 019 C1',15,'PRE 019 C1',1,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:28:03','2026-07-13 13:28:03'),(329,3,'P167 C2',15,'P167 C2',1,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:30:43','2026-07-13 13:30:43'),(330,3,'6004 001',15,'6004 001',1,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:37:22','2026-07-13 13:37:22'),(331,3,'L223 IVO',15,'L223 IVO',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:38:00','2026-07-13 13:38:00'),(332,3,'FG1542 C4',15,'FG1542 C4',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:38:43','2026-07-13 13:38:43'),(333,3,'FLR6058',15,'FLR6058',1,225.00,450.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:39:32','2026-07-13 13:39:32'),(334,3,'M835 C3',15,'M835 C3',1,200.00,400.00,'2026-07-13','2030-01-01',NULL,'2026-07-13 13:40:24','2026-07-13 13:40:24'),(335,1,'Slitlamp Examination',22,'SLT001',9996,0.01,100.00,'2026-07-01','2040-01-01',NULL,'2026-07-13 15:53:12','2026-08-05 11:27:42'),(336,1,'Registration (Acacia)',22,'REG-ACA',10000,0.01,30.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:53','2026-07-16 07:42:53'),(337,1,'Consultation (Acacia)',22,'CON-ACA',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:53','2026-07-16 07:42:53'),(338,1,'Dry Refraction (Acacia)',22,'DRF-ACA',10000,0.01,60.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:53','2026-07-16 07:42:53'),(339,1,'Cyclo Refraction (Acacia)',22,'CRF-ACA',10000,0.01,120.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(340,1,'FBR (Acacia)',22,'FBR-ACA',10000,0.01,70.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(341,1,'Tonometry (Acacia)',22,'TON-ACA',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(342,1,'Dilation (Acacia)',22,'DIL-ACA',10000,0.01,30.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(343,1,'TBT (Acacia)',22,'TBT-ACA',10000,0.01,30.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(344,1,'Registration (Star Health)',22,'REG-STH',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(345,1,'Consultation (Star Health)',22,'CON-STH',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(346,1,'Dry Refraction (Star Health)',22,'DRF-STH',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(347,1,'Cyclo Refraction (Star Health)',22,'CRF-STH',10000,0.01,120.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(348,1,'FBR (Star Health)',22,'FBR-STH',10000,0.01,120.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(349,1,'Tonometry (Star Health)',22,'TON-STH',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(350,1,'Dilation (Star Health)',22,'DIL-STH',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(351,1,'TBT (Star Health)',22,'TBT-STH',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(352,1,'Registration (Premier)',22,'REG-PRM',10000,0.01,60.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(353,1,'Consultation (Premier)',22,'CON-PRM',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(354,1,'Dry Refraction (Premier)',22,'DRF-PRM',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(355,1,'Cyclo Refraction (Premier)',22,'CRF-PRM',10000,0.01,110.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(356,1,'FBR (Premier)',22,'FBR-PRM',10000,0.01,110.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(357,1,'Tonometry (Premier)',22,'TON-PRM',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(358,1,'Dilation (Premier)',22,'DIL-PRM',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(359,1,'TBT (Premier)',22,'TBT-PRM',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(360,1,'Registration (Cosmopolitan)',22,'REG-COS',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(361,1,'Consultation (Cosmopolitan)',22,'CON-COS',10000,0.01,120.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(362,1,'Dry Refraction (Cosmopolitan)',22,'DRF-COS',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(363,1,'Cyclo Refraction (Cosmopolitan)',22,'CRF-COS',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(364,1,'FBR (Cosmopolitan)',22,'FBR-COS',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(365,1,'Tonometry (Cosmopolitan)',22,'TON-COS',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(366,1,'Dilation (Cosmopolitan)',22,'DIL-COS',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(367,1,'TBT (Cosmopolitan)',22,'TBT-COS',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(368,1,'Registration (Ace Medical)',22,'REG-ACM',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(369,1,'Consultation (Ace Medical)',22,'CON-ACM',10000,0.01,150.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(370,1,'Dry Refraction (Ace Medical)',22,'DRF-ACM',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(371,1,'Cyclo Refraction (Ace Medical)',22,'CRF-ACM',10000,0.01,150.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(372,1,'FBR (Ace Medical)',22,'FBR-ACM',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(373,1,'Tonometry (Ace Medical)',22,'TON-ACM',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(374,1,'Dilation (Ace Medical)',22,'DIL-ACM',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(375,1,'TBT (Ace Medical)',22,'TBT-ACM',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(376,1,'Registration (Empel)',22,'REG-EMP',9999,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 10:41:16'),(377,1,'Consultation (Empel)',22,'CON-EMP',9999,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 10:41:16'),(378,1,'Dry Refraction (Empel)',22,'DRF-EMP',9999,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 10:41:16'),(379,1,'Cyclo Refraction (Empel)',22,'CRF-EMP',10000,0.01,150.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(380,1,'FBR (Empel)',22,'FBR-EMP',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(381,1,'Tonometry (Empel)',22,'TON-EMP',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(382,1,'Dilation (Empel)',22,'DIL-EMP',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(383,1,'TBT (Empel)',22,'TBT-EMP',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(384,1,'Registration (Equity)',22,'REG-EQT',10000,0.01,40.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(385,1,'Consultation (Equity)',22,'CON-EQT',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(386,1,'Dry Refraction (Equity)',22,'DRF-EQT',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(387,1,'Cyclo Refraction (Equity)',22,'CRF-EQT',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(388,1,'FBR (Equity)',22,'FBR-EQT',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(389,1,'Tonometry (Equity)',22,'TON-EQT',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(390,1,'Dilation (Equity)',22,'DIL-EQT',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(391,1,'TBT (Equity)',22,'TBT-EQT',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(392,1,'Registration (Phoenix)',22,'REG-PHX',10000,0.01,40.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(393,1,'Consultation (Phoenix)',22,'CON-PHX',10000,0.01,150.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(394,1,'Dry Refraction (Phoenix)',22,'DRF-PHX',10000,0.01,70.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(395,1,'Cyclo Refraction (Phoenix)',22,'CRF-PHX',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(396,1,'FBR (Phoenix)',22,'FBR-PHX',10000,0.01,110.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(397,1,'Tonometry (Phoenix)',22,'TON-PHX',10000,0.01,70.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(398,1,'Dilation (Phoenix)',22,'DIL-PHX',10000,0.01,40.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(399,1,'Registration (Glico)',22,'REG-GLC',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(400,1,'Consultation (Glico)',22,'CON-GLC',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(401,1,'Dry Refraction (Glico)',22,'DRF-GLC',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(402,1,'Cyclo Refraction (Glico)',22,'CRF-GLC',10000,0.01,70.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(403,1,'FBR (Glico)',22,'FBR-GLC',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(404,1,'Tonometry (Glico)',22,'TON-GLC',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(405,1,'Dilation (Glico)',22,'DIL-GLC',10000,0.01,30.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(406,1,'TBT (Glico)',22,'TBT-GLC',10000,0.01,30.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(407,1,'Registration (GAB HI)',22,'REG-GBH',10000,0.01,60.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(408,1,'Consultation (GAB HI)',22,'CON-GBH',10000,0.01,130.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(409,1,'Dry Refraction (GAB HI)',22,'DRF-GBH',10000,0.01,70.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(410,1,'Cyclo Refraction (GAB HI)',22,'CRF-GBH',10000,0.01,100.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(411,1,'FBR (GAB HI)',22,'FBR-GBH',10000,0.01,120.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(412,1,'Tonometry (GAB HI)',22,'TON-GBH',10000,0.01,80.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(413,1,'Dilation (GAB HI)',22,'DIL-GBH',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(414,1,'TBT (GAB HI)',22,'TBT-GBH',10000,0.01,50.00,'2026-07-16','2040-01-01',NULL,'2026-07-16 07:42:54','2026-07-16 07:42:54'),(415,1,'HT10922 03',15,'HT10922 03',1,250.00,500.00,'2026-07-16','2030-01-01',NULL,'2026-07-16 10:44:15','2026-07-16 10:44:15'),(416,3,'P14224 C1',15,'P14224 C1',0,150.00,300.00,'2026-07-17','2030-01-01',NULL,'2026-07-17 16:45:11','2026-07-17 17:28:14'),(417,3,'COLOURS BY ALEX',15,'COLOURS BY ALEX',0,150.00,300.00,'2026-07-17','2030-01-01',NULL,'2026-07-17 16:58:42','2026-07-17 17:32:49'),(418,3,'SV-2206 BLK HM',15,'SV-2206 BLK HM',1,250.00,500.00,'2026-07-18','2030-01-01',NULL,'2026-07-18 11:50:42','2026-07-18 11:50:42'),(419,3,'OLIVE-3528',15,'OLIVE-3528',1,300.00,600.00,'2026-07-25','2030-01-01',NULL,'2026-07-25 10:47:32','2026-07-25 10:47:32'),(420,3,'TD96064 C5',15,'TD96064 C5',1,200.00,400.00,'2026-07-27','2030-01-01',NULL,'2026-07-27 12:21:20','2026-07-27 12:21:20'),(421,3,'Review',22,'Review',10000,0.01,100.00,'2026-07-30','2030-01-01',NULL,'2026-07-30 10:42:08','2026-07-30 12:13:15'),(422,3,'KIT C2',15,'KIT C2',1,300.00,600.00,'2026-08-03','2030-01-01',NULL,'2026-08-03 15:17:43','2026-08-03 15:17:43'),(423,3,'MRXC0126A BLK',15,'MRXC0126A BLK',1,500.00,500.00,'2026-08-03','2030-01-01',NULL,'2026-08-03 15:29:38','2026-08-03 15:29:38');
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
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_ordered` decimal(10,2) NOT NULL,
  `quantity_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `batch_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `po_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `status` enum('draft','ordered','partial','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `order_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `invoice_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_due_date` date DEFAULT NULL,
  `invoice_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` date DEFAULT NULL,
  `invoice_status` enum('none','invoiced','partial','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
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
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `quotation_number` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `patient_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','sent','accepted','expired','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `issue_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `letter_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `referral_snippets` VALUES (1,'referral','reasonForReferral','Specialist Review','Kindly review for further ophthalmic evaluation and management.',1,NULL,'2026-07-06 13:42:16','2026-07-06 13:42:16'),(2,'referral','management','Initial Treatment Given','Initial treatment and counselling have been provided. Patient has been advised to report for specialist care.',1,NULL,'2026-07-06 13:42:16','2026-07-06 13:42:16'),(3,'medical_report','recommendation','Follow-up Recommended','The patient is advised to continue treatment and attend scheduled follow-up appointments.',1,NULL,'2026-07-06 13:42:16','2026-07-06 13:42:16'),(4,'medical_report','clinicalFindings','Clinical Summary','Clinical examination was performed and findings are consistent with the stated diagnosis.',1,NULL,'2026-07-06 13:42:16','2026-07-06 13:42:16'),(5,'excuse_duty','diagnosis','Medical Rest','Patient requires temporary rest from work or school duties for medical reasons.',1,NULL,'2026-07-06 13:42:16','2026-07-06 13:42:16');
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
  `letter_type` enum('referral','medical_report','excuse_duty') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'referral',
  `referred_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `issued_by` bigint unsigned DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT NULL,
  `printed_by` bigint unsigned DEFAULT NULL,
  `printed_at` timestamp NULL DEFAULT NULL,
  `patient_id` bigint unsigned DEFAULT NULL,
  `referral_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_date` date NOT NULL,
  `patient_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_age_sex` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaint` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `va_od` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_os` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refraction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anterior_segment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posterior_segment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iop` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinical_findings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `treatment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommendation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `excuse_from_date` date DEFAULT NULL,
  `excuse_to_date` date DEFAULT NULL,
  `diagnosis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reason_for_referral` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `management` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
  `refractionOD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `refractionOS` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `lensType` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pd` int DEFAULT NULL,
  `refractionOD_distance_va` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `refractionOD_ADD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refractionOD_near_va` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refractionOS_distance_va` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `refractionOS_ADD` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refractionOS_near_va` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `refractionnotes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refractions_consultation_id_unique` (`consultation_id`),
  KEY `refractions_user_id_index` (`user_id`),
  KEY `refractions_consultation_id_index` (`consultation_id`),
  CONSTRAINT `refractions_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `refractions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refractions`
--

LOCK TABLES `refractions` WRITE;
/*!40000 ALTER TABLE `refractions` DISABLE KEYS */;
INSERT INTO `refractions` VALUES (1,4,7,'0.00/-0.75*15','0.00/-1.00*165',NULL,'SV Blue Block Photo',69,'6/6',NULL,'6/5','6/6',NULL,'6/5',NULL,'2026-07-13 14:16:58','2026-07-13 14:16:58'),(2,4,16,'-0.75','-0.75',NULL,'SV Blue Block Photo',59,'6/6',NULL,'N5','6/6',NULL,'N5',NULL,'2026-07-14 18:22:24','2026-07-14 18:22:24'),(3,4,17,'+2.75/-1.25*90','+2.50/-0.75*90',NULL,'Progressive Photo AR',68,'6/6','2.00','N5','6/6','1.75','N5',NULL,'2026-07-16 09:48:31','2026-07-16 09:48:31'),(4,4,22,'0.00/-0.25*180','-0.75',NULL,'SV Photo AR',68,'6/6',NULL,'N5','6/6',NULL,'N5',NULL,'2026-07-17 17:03:24','2026-07-17 17:03:24'),(5,4,23,'-0.50/-0.50*180','-1.00/-0.25*180',NULL,'SV Photo AR',66,'6/6',NULL,'N5','6/6',NULL,'N5',NULL,'2026-07-17 17:20:18','2026-07-17 17:20:18'),(6,2,27,'+0.50','+0.50',NULL,'SV Blue Block Photo',65,'6/6',NULL,NULL,'6/6',NULL,NULL,NULL,'2026-07-25 10:47:56','2026-07-25 10:47:56'),(7,4,28,'-4.00/-0.50*005','-4.00/-0.50*175',NULL,'SV Blue Block Photo',64,'6/6',NULL,'N5','6/6',NULL,'N5',NULL,'2026-07-26 16:54:59','2026-07-26 16:54:59'),(8,1,33,'+1.25','+1.50',NULL,'Bifocal Photo AR',67,'6/6','+1.75','N5','6/6','+1.75','N5',NULL,'2026-08-04 14:41:47','2026-08-04 14:41:47'),(9,1,35,'+1.75','+1.50',NULL,'Bifocal Photo AR',64,'6/6','2.75','N5','6/6','2.75','N5',NULL,'2026-08-04 15:54:01','2026-08-04 15:54:01');
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
  `status` enum('pending','approved','rejected','processed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `initiated_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `processed_by` bigint unsigned DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund_logs`
--

LOCK TABLES `refund_logs` WRITE;
/*!40000 ALTER TABLE `refund_logs` DISABLE KEYS */;
INSERT INTO `refund_logs` VALUES (1,7,'processed',3,3,3,NULL,'Double entry',NULL,'2026-07-13 14:32:57','2026-07-13 14:33:31','2026-07-14 09:37:35',NULL,NULL,'2026-07-13 14:32:57','2026-07-14 09:37:35'),(2,24,'processed',1,1,3,NULL,'This was a trial test purchase for walk in services',NULL,'2026-07-20 09:58:59','2026-07-20 09:59:20','2026-07-20 15:43:52',NULL,NULL,'2026-07-20 09:58:59','2026-07-20 15:43:52'),(3,27,'processed',3,3,3,NULL,'No payment',NULL,'2026-07-25 10:43:43','2026-07-25 10:44:03','2026-07-25 10:44:49',NULL,NULL,'2026-07-25 10:43:43','2026-07-25 10:44:49'),(4,37,'processed',3,3,3,NULL,'Used as a trial test ',NULL,'2026-07-30 10:52:26','2026-07-30 10:52:47','2026-07-30 10:52:53',NULL,NULL,'2026-07-30 10:52:26','2026-07-30 10:52:53'),(5,41,'processed',3,3,3,NULL,'Used as a test trial',NULL,'2026-07-30 12:11:45','2026-07-30 12:12:05','2026-07-30 12:12:12',NULL,NULL,'2026-07-30 12:11:45','2026-07-30 12:12:12');
/*!40000 ALTER TABLE `refund_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_deliveries`
--

DROP TABLE IF EXISTS `report_deliveries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `delivery_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_start` timestamp NULL DEFAULT NULL,
  `period_end` timestamp NULL DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_payload` json NOT NULL,
  `recipients` json NOT NULL,
  `sent_recipients` json DEFAULT NULL,
  `failed_recipients` json DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `attempts` int unsigned NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `last_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_deliveries_delivery_key_unique` (`delivery_key`),
  KEY `report_deliveries_status_last_attempt_at_index` (`status`,`last_attempt_at`),
  KEY `report_deliveries_period_period_start_index` (`period`,`period_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_deliveries`
--

LOCK TABLES `report_deliveries` WRITE;
/*!40000 ALTER TABLE `report_deliveries` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_deliveries` ENABLE KEYS */;
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dashboard_route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `roles` VALUES (1,'Super Admin','web',NULL,'2026-07-06 13:42:56','2026-07-06 13:42:56'),(2,'Doctor','web',NULL,'2026-07-06 13:42:56','2026-07-06 13:42:56'),(3,'Cashier','web',NULL,'2026-07-06 13:42:56','2026-07-06 13:42:56'),(4,'Staff','web',NULL,'2026-07-06 13:42:56','2026-07-06 13:42:56'),(5,'Manager','web',NULL,'2026-07-06 13:42:56','2026-07-06 13:42:56'),(6,'Secretary','web',NULL,'2026-07-06 13:42:56','2026-07-06 13:42:56');
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
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `frequency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eye` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,NULL,4,1,1,100.00,100.00,'Clearance Service',NULL,NULL,NULL,'2026-07-09 17:48:25','2026-07-09 17:48:25'),(2,2,NULL,2,1,1,100.00,100.00,'Clearance Service',NULL,NULL,NULL,'2026-07-10 09:44:38','2026-07-10 09:44:38'),(3,3,NULL,4,1,1,100.00,100.00,'Clearance Service',NULL,NULL,NULL,'2026-07-10 10:24:13','2026-07-10 10:24:13'),(4,4,3,2,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-10 15:14:44','2026-07-10 15:14:44'),(5,4,4,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-10 15:14:44','2026-07-10 15:14:44'),(6,5,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-13 14:00:23','2026-07-13 14:00:23'),(7,6,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-13 14:03:11','2026-07-13 14:03:11'),(8,7,2,19,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 14:13:51','2026-07-13 14:13:51'),(9,8,6,11,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 14:24:18','2026-07-13 14:24:18'),(10,8,7,9,0,1,250.00,250.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 14:24:18','2026-07-13 14:24:18'),(11,8,8,19,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 14:24:18','2026-07-13 14:24:18'),(12,9,5,84,0,1,85.00,85.00,'Prescription Sale','Four Times Daily','OU',NULL,'2026-07-13 14:27:20','2026-07-13 14:27:20'),(13,10,9,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(14,10,10,2,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(15,10,11,9,0,1,250.00,250.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(16,10,12,11,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(17,10,13,335,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(18,10,14,5,0,1,150.00,150.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(19,10,15,19,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(20,11,16,2,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-14 15:36:45','2026-07-14 15:36:45'),(21,11,17,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-14 15:36:45','2026-07-14 15:36:45'),(22,12,18,46,0,1,700.00,700.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-14 18:28:39','2026-07-14 18:28:39'),(23,13,NULL,42,0,1,800.00,800.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 09:53:05','2026-07-16 09:53:05'),(24,13,21,66,0,1,1400.00,1400.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 09:53:05','2026-07-16 09:53:05'),(25,14,26,378,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 10:41:16','2026-07-16 10:41:16'),(26,14,27,377,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 10:41:16','2026-07-16 10:41:16'),(27,14,28,376,0,1,50.00,50.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 10:41:16','2026-07-16 10:41:16'),(28,15,29,9,0,1,250.00,250.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 10:42:14','2026-07-16 10:42:14'),(29,15,30,11,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 10:42:14','2026-07-16 10:42:14'),(30,15,31,10,0,1,250.00,250.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 10:42:14','2026-07-16 10:42:14'),(31,16,32,11,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 11:30:33','2026-07-16 11:30:33'),(32,16,33,9,0,1,250.00,250.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-16 11:30:33','2026-07-16 11:30:33'),(33,17,34,19,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-17 12:39:34','2026-07-17 12:39:34'),(34,18,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-17 16:25:00','2026-07-17 16:25:00'),(35,19,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-17 16:28:26','2026-07-17 16:28:26'),(36,20,35,45,0,1,600.00,600.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-17 17:28:14','2026-07-17 17:28:14'),(37,20,36,416,0,1,300.00,300.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-17 17:28:14','2026-07-17 17:28:14'),(38,21,NULL,417,0,1,300.00,300.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-17 17:32:49','2026-07-17 17:32:49'),(39,21,38,45,0,1,600.00,600.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-17 17:32:49','2026-07-17 17:32:49'),(40,22,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-18 14:02:15','2026-07-18 14:02:15'),(41,23,40,81,0,1,80.00,80.00,'Prescription Sale','Four Times Daily','OU',NULL,'2026-07-18 14:30:52','2026-07-18 14:30:52'),(42,23,41,87,0,1,80.00,80.00,'Prescription Sale','Four Times Daily','OU',NULL,'2026-07-18 14:30:52','2026-07-18 14:30:52'),(43,24,NULL,81,0,1,80.00,80.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-20 09:55:36','2026-07-20 09:55:36'),(44,25,NULL,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-20 15:27:46','2026-07-20 15:27:46'),(45,25,NULL,9,0,1,250.00,250.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-20 15:27:46','2026-07-20 15:27:46'),(46,25,NULL,11,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-20 15:27:46','2026-07-20 15:27:46'),(47,26,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-23 11:52:01','2026-07-23 11:52:01'),(48,27,NULL,82,0,1,60.00,60.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-25 10:03:26','2026-07-25 10:03:26'),(49,28,42,82,0,1,60.00,60.00,'Prescription Sale','Three Times Daily','OU',NULL,'2026-07-25 10:40:49','2026-07-25 10:40:49'),(50,28,43,81,0,1,80.00,80.00,'Prescription Sale','Four Times Daily','OU',NULL,'2026-07-25 10:40:49','2026-07-25 10:40:49'),(51,29,NULL,234,1,0,600.00,0.00,'On Hold - Part Payment',NULL,NULL,NULL,'2026-07-25 10:55:59','2026-07-25 10:55:59'),(52,29,44,46,1,0,700.00,0.00,'On Hold - Part Payment',NULL,NULL,NULL,'2026-07-25 10:55:59','2026-07-25 10:55:59'),(53,30,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-26 16:32:02','2026-07-26 16:32:02'),(54,31,NULL,324,0,1,450.00,450.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-26 17:06:11','2026-07-26 17:06:11'),(55,31,47,46,0,1,700.00,700.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-26 17:06:11','2026-07-26 17:06:11'),(56,32,NULL,2,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-28 12:34:28','2026-07-28 12:34:28'),(57,32,NULL,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-28 12:34:28','2026-07-28 12:34:28'),(58,32,NULL,9,0,1,250.00,250.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-28 12:34:28','2026-07-28 12:34:28'),(59,32,NULL,11,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-28 12:34:28','2026-07-28 12:34:28'),(60,32,NULL,80,0,1,85.00,85.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-28 12:34:28','2026-07-28 12:34:28'),(61,33,NULL,5,0,1,150.00,150.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-28 13:05:07','2026-07-28 13:05:07'),(62,34,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-28 15:11:54','2026-07-28 15:11:54'),(63,35,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-29 15:17:54','2026-07-29 15:17:54'),(64,36,49,80,0,1,85.00,85.00,'Prescription Sale','Twice Daily','OU',NULL,'2026-07-29 15:35:17','2026-07-29 15:35:17'),(65,36,52,82,0,1,60.00,60.00,'Prescription Sale','Four Times Daily','OU',NULL,'2026-07-29 15:35:17','2026-07-29 15:35:17'),(66,37,NULL,421,1,1,100.00,100.00,'Clearance Service',NULL,NULL,NULL,'2026-07-30 10:42:30','2026-07-30 10:42:30'),(67,38,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-07-30 10:55:48','2026-07-30 10:55:48'),(68,39,54,82,0,1,60.00,60.00,'Prescription Sale','Every 4 Hours','OU',NULL,'2026-07-30 11:05:01','2026-07-30 11:05:01'),(69,40,NULL,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-30 12:04:05','2026-07-30 12:04:05'),(70,40,NULL,335,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-30 12:04:05','2026-07-30 12:04:05'),(71,41,NULL,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-07-30 12:10:48','2026-07-30 12:10:48'),(72,42,NULL,19,0,1,200.00,200.00,'Direct POS Sale',NULL,NULL,NULL,'2026-08-01 13:04:44','2026-08-01 13:04:44'),(73,43,NULL,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-08-04 12:38:11','2026-08-04 12:38:11'),(74,43,NULL,335,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-08-04 12:38:11','2026-08-04 12:38:11'),(75,44,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-08-04 14:18:22','2026-08-04 14:18:22'),(76,45,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-08-04 14:33:56','2026-08-04 14:33:56'),(77,46,56,80,0,1,85.00,85.00,'Prescription Sale','Twice Daily','OU',NULL,'2026-08-04 14:58:00','2026-08-04 14:58:00'),(78,47,NULL,81,1,0,80.00,0.00,'On Hold - Part Payment',NULL,NULL,NULL,'2026-08-04 15:08:31','2026-08-04 15:08:31'),(79,47,55,57,1,0,900.00,0.00,'On Hold - Part Payment',NULL,NULL,NULL,'2026-08-04 15:08:31','2026-08-04 15:08:31'),(80,48,NULL,23,1,1,200.00,200.00,'Clearance Service',NULL,NULL,NULL,'2026-08-04 15:27:09','2026-08-04 15:27:09'),(81,49,58,57,0,1,900.00,900.00,'Direct POS Sale',NULL,NULL,NULL,'2026-08-04 15:53:00','2026-08-04 15:53:00'),(82,50,NULL,4,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-08-05 11:27:42','2026-08-05 11:27:42'),(83,50,NULL,335,0,1,100.00,100.00,'Direct POS Sale',NULL,NULL,NULL,'2026-08-05 11:27:42','2026-08-05 11:27:42');
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
  `customer_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consultation_id` bigint unsigned DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('paid','partial','unpaid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_approved_by` bigint unsigned DEFAULT NULL,
  `is_refunded` tinyint(1) NOT NULL DEFAULT '0',
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refunded_by` bigint unsigned DEFAULT NULL,
  `refund_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,3,1,NULL,NULL,'09072026-1YMUZGYX',100.00,100.00,'paid',99.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-09 17:48:25','2026-07-09 17:48:25'),(2,3,2,NULL,NULL,'10072026-7LQTNX9C',100.00,100.00,'paid',99.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-10 09:44:38','2026-07-10 09:44:38'),(3,3,1,NULL,NULL,'10072026-DS3CYSPD',100.00,100.00,'paid',99.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-10 10:24:13','2026-07-10 10:24:13'),(4,3,5,NULL,5,'10072026-TYRB0RJX',200.00,200.00,'paid',199.98,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-10 15:14:44','2026-07-10 15:14:44'),(5,3,6,NULL,NULL,'13072026-NZYD49C1',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-13 14:00:23','2026-07-13 14:00:23'),(6,3,7,NULL,NULL,'13072026-L0AOJIG9',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-13 14:03:11','2026-07-13 14:03:11'),(7,3,3,NULL,4,'13072026-2WA1VGQ7',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,1,'2026-07-14 09:37:35',3,'Double entry',NULL,'2026-07-13 14:13:51','2026-07-14 09:37:35'),(8,3,7,NULL,7,'13072026-QTOEDA2P',650.00,650.00,'paid',649.97,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-13 14:24:15','2026-07-13 14:24:15'),(9,3,6,NULL,6,'13072026-AR6GQZN2',85.00,85.00,'paid',42.50,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-13 14:27:20','2026-07-13 14:27:20'),(10,3,8,NULL,8,'13072026-GC32AKTF',1100.00,1100.00,'paid',1099.93,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-13 17:04:31','2026-07-13 17:04:31'),(11,3,9,NULL,15,'14072026-WIILT7HQ',200.00,200.00,'paid',199.98,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-14 15:36:42','2026-07-14 15:36:42'),(12,3,10,NULL,16,'14072026-ZQ6SFDFK',700.00,700.00,'paid',375.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-14 18:28:37','2026-07-14 18:28:37'),(13,3,11,NULL,17,'16072026-7WAP3Q9U',2200.00,2200.00,'paid',1150.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-16 09:53:05','2026-07-16 09:53:05'),(14,3,10,NULL,18,'16072026-OX2R2EFX',250.00,250.00,'paid',249.97,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-16 10:41:14','2026-07-16 10:41:14'),(15,3,12,NULL,19,'16072026-ZO4ZHAXS',700.00,700.00,'paid',699.97,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-16 10:42:12','2026-07-16 10:42:12'),(16,3,13,NULL,20,'16072026-GOKFV3AP',450.00,450.00,'paid',449.98,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-16 11:30:30','2026-07-16 11:30:30'),(17,3,14,NULL,21,'17072026-WXNNUTTN',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-17 12:39:34','2026-07-17 12:39:34'),(18,3,16,NULL,NULL,'17072026-PGL5LLWM',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-17 16:25:00','2026-07-17 16:25:00'),(19,3,15,NULL,NULL,'17072026-MCRPPIGJ',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-17 16:28:26','2026-07-17 16:28:26'),(20,3,16,NULL,22,'17072026-3ADEHV3B',900.00,900.00,'paid',475.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-17 17:28:11','2026-07-17 17:28:11'),(21,3,15,NULL,23,'17072026-JTRX3U5V',900.00,900.00,'paid',475.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-17 17:32:47','2026-07-17 17:32:47'),(22,3,17,NULL,NULL,'18072026-NOZNJKCW',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-18 14:02:15','2026-07-18 14:02:15'),(23,3,17,NULL,24,'18072026-BGU3D39G',160.00,160.00,'paid',80.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-18 14:30:52','2026-07-18 14:30:52'),(24,1,NULL,NULL,NULL,'20072026-TKYW4CS9',80.00,80.00,'paid',40.00,NULL,NULL,0.00,NULL,1,'2026-07-20 15:43:52',3,'This was a trial test purchase for walk in services',NULL,'2026-07-20 09:55:36','2026-07-20 15:43:52'),(25,3,NULL,NULL,NULL,'20072026-9QQWDDYP',550.00,550.00,'paid',549.97,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-20 15:27:46','2026-07-20 15:27:46'),(26,3,18,NULL,NULL,'23072026-G8VZRUXX',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-23 11:52:01','2026-07-23 11:52:01'),(27,3,NULL,NULL,NULL,'25072026-BJGWZ1BY',60.00,60.00,'paid',30.00,NULL,NULL,0.00,NULL,1,'2026-07-25 10:44:49',3,'No payment',NULL,'2026-07-25 10:03:26','2026-07-25 10:44:49'),(28,3,20,NULL,26,'25072026-BXPCTKPB',140.00,140.00,'paid',70.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-25 10:40:49','2026-07-25 10:40:49'),(29,3,19,NULL,27,'25072026-EQI2GNPW',1300.00,850.00,'partial',0.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-25 10:55:59','2026-07-29 15:19:34'),(30,3,21,NULL,NULL,'26072026-YFY21LQ9',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-26 16:32:02','2026-07-26 16:32:02'),(31,3,21,NULL,28,'26072026-IBAXLB8J',1150.00,1150.00,'paid',600.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-26 17:06:09','2026-07-26 17:06:09'),(32,3,NULL,NULL,NULL,'28072026-V2J3G4HG',735.00,735.00,'paid',692.46,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-28 12:34:28','2026-07-28 12:34:28'),(33,3,NULL,NULL,NULL,'28072026-QPXGOGD7',150.00,150.00,'paid',149.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-28 13:05:07','2026-07-28 13:05:07'),(34,3,23,NULL,NULL,'28072026-FZWJGJRS',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-28 15:11:54','2026-07-28 15:11:54'),(35,3,24,NULL,NULL,'29072026-RRVKOJPG',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-29 15:17:54','2026-07-29 15:17:54'),(36,3,24,NULL,30,'29072026-SALO08YX',145.00,145.00,'paid',72.50,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-29 15:35:15','2026-07-29 15:35:15'),(37,3,23,NULL,NULL,'30072026-BWU7KKVT',100.00,100.00,'paid',99.99,NULL,NULL,0.00,NULL,1,'2026-07-30 10:52:53',3,'Used as a trial test ',NULL,'2026-07-30 10:42:30','2026-07-30 10:52:53'),(38,3,25,NULL,NULL,'30072026-9AMZUC9A',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-30 10:55:48','2026-07-30 10:55:48'),(39,3,25,NULL,32,'30072026-O2QPAQPC',60.00,60.00,'paid',30.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-30 11:05:01','2026-07-30 11:05:01'),(40,3,NULL,NULL,NULL,'30072026-BYSQHDCN',200.00,200.00,'paid',199.98,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-07-30 12:04:05','2026-07-30 12:04:05'),(41,3,NULL,'Joselyne Bonsu',NULL,'30072026-RHLD3XBM',100.00,100.00,'paid',99.99,NULL,NULL,0.00,NULL,1,'2026-07-30 12:12:12',3,'Used as a test trial',NULL,'2026-07-30 12:10:48','2026-07-30 12:12:12'),(42,3,NULL,'Idibia Elbridge Nayram',NULL,'01082026-QEKFOYET',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-01 13:04:44','2026-08-01 13:04:44'),(43,3,NULL,'Vivian Ohui Gberbie',NULL,'04082026-V89FMOLC',200.00,200.00,'paid',199.98,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-04 12:38:11','2026-08-04 12:38:11'),(44,3,26,NULL,NULL,'04082026-2RUO9YB1',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-04 14:18:22','2026-08-04 14:18:22'),(45,3,27,NULL,NULL,'04082026-JBOLSUZE',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-04 14:33:56','2026-08-04 14:33:56'),(46,3,27,NULL,34,'04082026-1RLKYOQL',85.00,85.00,'paid',42.50,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-04 14:57:58','2026-08-04 14:57:58'),(47,3,26,NULL,33,'04082026-A00O3IKO',980.00,380.00,'partial',0.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-04 15:08:31','2026-08-04 15:08:31'),(48,3,28,NULL,NULL,'04082026-MMRX6TKW',200.00,200.00,'paid',199.99,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-04 15:27:09','2026-08-04 15:27:09'),(49,3,28,NULL,35,'04082026-6YWL2TRP',900.00,900.00,'paid',450.00,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-04 15:53:00','2026-08-04 15:53:00'),(50,3,NULL,'Evelyn Apreku',NULL,'05082026-NKU6QZQ4',200.00,200.00,'paid',199.98,NULL,NULL,0.00,NULL,0,NULL,NULL,NULL,NULL,'2026-08-05 11:27:42','2026-08-05 11:27:42');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('qRXtIOUCSEyoGBXLPUHrPvKmPfnpGghwdTGg5mGE',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','ZXlKcGRpSTZJalZEVVhWUWR6aFVOVWhPTDBNeE9XOTZNRXQzU1hjOVBTSXNJblpoYkhWbElqb2lha2xYTDAxR1RIUnlSekpzUzBFelZVTlhaQ3RGV1RsQ2QwWXlUVFJUUkc1MFlUUTNRVFJEVGpWUmFXMUVja1YwWVhKemVtTm9TV0pPY20xUlp6Uk9WMk13UVZaVVN6UXdURmQwUWt4RmNrcEVPRzVtUzBGRFltZHJZMWxpTjNKSmJYaE1OMDVtUVVRclZtTmllbVpSZUhOMVNtZDBNelpqTTNsbVFWTnNhMXA2WmpOSlJ5dDFSSEZxTkVnNVJrY3pVWFp2YUZoT05VcDVTV2xSYzIwck5UYzBUbGxIZGpoYWExUnZhV3huVFVWQ1lVeHJNVGQ2VWtWeFZrRTRWMGd6YWxjMGFrSlFNak00VjJKYVlVNWlZUzkwVUVvd2JUQlBTRk5vWXpsT1R6STBhVWhuZEdac1JXMVpUbmRGVEhac1NUaGhObXR3UmtreWFVODNjVkZpTkVOSWNVdHlXak5HUmtkd2VuQm9NVzgwUjFwc1lWUndMMU54Ym1JelpVTjJaVlZuYjJ4dFlsZElOblJRVWpablNuZE9NalJuY1ZrcmRGWnRjM2hTWkRRaUxDSnRZV01pT2lJMVpERXlPRGN6T0dGak9UVmpNMkZsWlRjMVpUQTRaVE14WWpJM01XWmlaV1poTkRabE1XVmhOekEyWmpFMU5tVTBPV1UxWTJNM1pURmpNakV3T0dWaUlpd2lkR0ZuSWpvaUluMD0=',1786362462),('RAwxIwf41NeyyWdRk90dVuaVzV1QOytAo3z8GAYd',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','ZXlKcGRpSTZJbFJSTVdaMkwwZExXR2xYTnpSclYyeFRaV2gxVkVFOVBTSXNJblpoYkhWbElqb2lWMnRCZWxVMmRqRkZhM3AxVkhKU2RrNDVZbEpIVmtkWWFYTk9kVGN3VlVFMlV6WnpibXcyVUhCdlVpc3dXREYzY0dOU01GZDVTbWREUzFRMVNWWm1RbWR0WXpNNE1FZzNaMmxIUmpRd1FreERWMWhRTWtsUFdDdFpRbEp3UTJ4UVdqRlJkV0puWjFGNFYyZDJiV0V3V2pSak9WbHdabTVIY25oUllVRlRUV1JoVW0xa09HMVNWRUpuY25Sb1EySkxSVlIzTVRWUU9VMXRhM1V5TkdkbGRrcHFjVms1Wm5CWGNVWlpRa2RaV0ZKeVNqbDZaSHBqTDBsRU15OUpaMlpJVG10QlVHeHJWVEZUT0RoNU5qTnBTa2RqYzI5eE5GcDFSV1pQVEROemEySklkbFl5V21sQk5VZHlaMDVsVUhaUVRESXpjbVZaWjBsMllYVjNORU5wYjJ0Sk5FaEhTSE5LTjJKV1kzTnRaek5rUjFsNFpUQlhVVFU1YUZGUGRsVkJZbXBCTkRCTFdpOW5PRzg5SWl3aWJXRmpJam9pWkRNeU1UWmlZV1kzTURJNU5qWXhOMlV3TURCbE9UZGhaV1kyTW1VMk1XWmtZbVkwWWpFeFpUSXpZalUyTnpVMk1tWTNZak5tTnpZeVlXUmlOakk1TVNJc0luUmhaeUk2SWlKOQ==',1786361264),('uDqAG7ZIyds47wwSdx4enU0BVyaESvdWkFcL5w01',NULL,'192.168.100.80','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0','ZXlKcGRpSTZJaTlIZFhwUWVrMDJjR296TmtGNlNIRjBWR0ZCU1djOVBTSXNJblpoYkhWbElqb2lhM1ZGUVhnck9VSlpPWE4zVFVwbGR6WnRRVkJSUVdVMk1ERnhkamd2ZFV4WVRqWjZPRUpSYmxKc1FqZDBSSEJtVFZsdFRXNVhjakJGZG0xVE1pOXBTSGhIYVd0MlJXNXNRM1Z5VmtVM1FXMWhVSEIzVGs1a1VYWjRVbkF2TjNKU01GSlRWRTQzYjI5clJUVnJlblp6YVZCTVpTdGFOV015T0U4dllXdFVZM3BSVlhWMldrNUNSVTFhTDAxT1NqbE5OVWgyVUdzelpIaHhjSFZhZFdWV01HaE9ZMnBCTTNKbk0xcDJRemx0WlhjcmFqVjBVbGhSWlROM1dGSkZVV1p6YW5OVE5FOXBkelZIZUhFMmVuaE5lblpzWldwYUwxVmlXakpZT0RSbFIzaExjelpCYVZBMmFtVlJNbTFNVDNwWU5UTlNhVmhsUW0xMlRXSnRhME42TldWM1NqZDBObGc1UzFsMGRGbHlLelZIVVVsME0wSk5aamxYYmt0NVIwWmhSVnBZTldwRE0wbE5RakUwVkVKVWIycEtTVXBJYlZCSlZ6TTFSM2xQZENzNVVGQmllbkJXWVhsWVRGWktOV040YlZCQ1pIQTJXRFEyT1RsemVUTXdhVlZSTVZwclRuRk5kVzlWUFNJc0ltMWhZeUk2SWpOaFl6aGlaamszTmpFd01ETTNOekZpT0dKbE9HVXhOems0WmpFMU5XRXlNRFppTXpsbE4yVm1PRGd5TUdSaU56Z3lZVEUzTldNNE1HTXhZak5qTjJFaUxDSjBZV2NpT2lJaWZRPT0=',1785950971),('WjHPAdwLOFofMGBfUQBGTZqeaT9MQx6RcxHog7z9',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','ZXlKcGRpSTZJa3B3WmtWbWFGVXpWSGRaV0RObmVuY3JPWE5tU1ZFOVBTSXNJblpoYkhWbElqb2lTWFZYY0VKbGFVUlNiSFF4ZVRoMFpFb3lSbk5qWjFkU1UwWXlZakZOZFhoSGJHWk9aa3RtVlZvdmJuVmpTUzlMYzI1eWVXdGpSRFZrVWxCamNuZEtUamxEYVV0NFQxVnhOMlUxS3pORkwyd3hkbHBGZGt3MVowUnpPVFZvUm5oMVRITTNSMWh4U25WMFozUjBkRzF2VHpOelpsb3dSRUpyWlZaTUszSlRjek55TWpaWEwzaGFUamRoVGpaeFJXWjZlSFJxZHpaR1FsWkxWVUZxUmpOc1VtdHdlR2swZDJoTGVIYzBQU0lzSW0xaFl5STZJbUUwWkRNd01qazFaV1JoWWpCa1pXVTBNakExTURoaU1EYzJNV0ZtTXpsaVl6azRNV1UzT1dGaU9XSTVNbVE0Wm1FNE56STVOelV4TUdKa1pEUmlNekVpTENKMFlXY2lPaUlpZlE9PQ==',1786010640);
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
  `clinic_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'My Eye Clinic',
  `clinic_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clinic_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `backup_extra_paths` json DEFAULT NULL,
  `report_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `report_frequency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily',
  `report_day` tinyint NOT NULL DEFAULT '1',
  `report_time` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '08:00',
  `report_recipients` json DEFAULT NULL,
  `smtp_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_port` smallint unsigned NOT NULL DEFAULT '587',
  `smtp_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `smtp_encryption` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_from_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_notation` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '6m',
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GH₵',
  `license_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `installation_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_last_seen` date DEFAULT NULL,
  `trial_started_at` date DEFAULT NULL,
  `sms_api_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_api_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_sender_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `whatsapp_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `whatsapp_phone_number_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `whatsapp_appt_template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'appointment_reminder',
  `whatsapp_appt_template_lang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en',
  `whatsapp_birthday_template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_recall_template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_renewal_template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_bulk_channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sms',
  `birthday_sms_filter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
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
INSERT INTO `settings` VALUES (1,'LEAD OPTICALS','logos/ios9WdQxo8VmCZogL7DOFxCxC7LvvXb8qs10epjL.png','Opp. Kuottam Estate, Oyarifa-Accra.\nGM-137-3773','0206181805',NULL,NULL,0,'daily',1,'08:00',NULL,NULL,587,NULL,NULL,NULL,NULL,NULL,'20ft','GH₵','EYECLINIC-PRO-eyJ0aWVyIjoicHJvIiwiaW5zdGFsbGF0aW9uX2lkIjoiM2I0M2U2MDYtYmY4Mi00NjZlLWIwNTAtZWQyMWM5YWM3OTk0IiwiY2xpbmljIjoiTEVBRFMgT1BUSUNBTFMiLCJpc3N1ZWQiOiIyMDI2LTA3LTEzIiwiZXhwaXJlcyI6IjIwMjYtMDgtMTMifQ.CZKM4vCJFnJzEs03CHD7SxL_r5_Lwbrk47st1wZ30pLT7qXl8YJhBuHqEH0Pp-TQ1QgbEEY6gAse19TDBLqmCA','3b43e606-bf82-466e-b050-ed21c9ac7994','2026-08-05','2026-07-06',NULL,NULL,NULL,1,0,NULL,NULL,'appointment_reminder','en',NULL,NULL,NULL,'sms','all',NULL,0,12,1,30,'2026-07-06 13:56:39','2026-08-05 09:39:32');
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
  `template_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sms',
  `recipient` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `template_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sms',
  `recipient` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `error` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sms_templates` VALUES (1,'appointment_booking','Appointment Booking Confirmation','Hello [NAME], your appointment at [CLINIC] is confirmed for [DATE] at [TIME] – [REASON].','[\"[NAME]\", \"[DATE]\", \"[TIME]\", \"[REASON]\", \"[CLINIC]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(2,'appointment_reminder','Appointment Reminder','Hello [NAME], this is a reminder of your appointment at [CLINIC] on [DATE] at [TIME] – [REASON]. Please be on time.','[\"[NAME]\", \"[DATE]\", \"[TIME]\", \"[REASON]\", \"[CLINIC]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(3,'spectacles_ready','Spectacles Ready for Pickup','Hello [NAME], your spectacles (Order [ORDER_ID]) are ready for collection at [CLINIC]. Please bring this message when you come in.','[\"[NAME]\", \"[ORDER_ID]\", \"[CLINIC]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(4,'spectacles_reminder','Spectacles Pickup Reminder','Hello [NAME], your spectacles (Order [ORDER_ID]) are still waiting for collection at [CLINIC]. Please come in at your earliest convenience.','[\"[NAME]\", \"[ORDER_ID]\", \"[CLINIC]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(5,'payment_receipt','Payment Receipt','Hello [NAME], payment of GHS [AMOUNT] received at [CLINIC]. Transaction: [TXN_ID]. Thank you!','[\"[NAME]\", \"[AMOUNT]\", \"[TXN_ID]\", \"[CLINIC]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(6,'birthday_wishes','Birthday Wishes','Happy Birthday [NAME]! Wishing you good health and clear vision. From all of us at [CLINIC].','[\"[NAME]\", \"[CLINIC]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(7,'custom_broadcast','Custom Broadcast','Dear [NAME], [CLINIC] wishes you a joyful [OCCASION]! Thank you for trusting us with your eye care.','[\"[NAME]\", \"[CLINIC]\", \"[OCCASION]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(8,'patient_recall','Patient Recall','Hello [NAME], it\'s been a while since your last visit to [CLINIC]. Your eyes deserve regular care — book your next check-up today. Call us anytime!','[\"[NAME]\", \"[CLINIC]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(9,'appointment_auto_reminder','Appointment Auto-Reminder','Hello [NAME], this is a reminder of your appointment at [CLINIC] tomorrow, [DATE] at [TIME]. Please call us if you need to reschedule.','[\"[NAME]\", \"[CLINIC]\", \"[DATE]\", \"[TIME]\"]','2026-07-06 13:42:22','2026-07-06 13:42:22'),(10,'spectacle_renewal','Spectacle Renewal Reminder','Dear [NAME], your spectacles are due for renewal on [DATE]. Please visit [CLINIC] for your annual eye review.','[\"[NAME]\", \"[DATE]\", \"[CLINIC]\"]','2026-07-06 13:42:23','2026-07-06 13:42:23');
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
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `reference_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `movement_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `supplier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_before` int NOT NULL DEFAULT '0',
  `quantity` int NOT NULL DEFAULT '0',
  `quantity_after` int NOT NULL DEFAULT '0',
  `cost_price` decimal(12,2) DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_time_days` smallint unsigned DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `system_health_statuses`
--

DROP TABLE IF EXISTS `system_health_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_health_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json DEFAULT NULL,
  `checked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_health_statuses_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_health_statuses`
--

LOCK TABLES `system_health_statuses` WRITE;
/*!40000 ALTER TABLE `system_health_statuses` DISABLE KEYS */;
INSERT INTO `system_health_statuses` VALUES (1,'scheduler','{\"host\": \"DESKTOP-N6STV3U\", \"source\": \"schedule:run\"}','2026-08-05 17:38:02','2026-07-06 13:59:03','2026-08-05 17:38:02');
/*!40000 ALTER TABLE `system_health_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `staff_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Male','Female','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_password_changed_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `users` VALUES (1,'System Administrator','admin@eyeclinic.com','0242036391',NULL,'Male','1994-10-16',NULL,NULL,NULL,'2026-07-06 16:04:44',1,'2026-07-06 13:42:57','$2y$10$z9d7lpniETyRjdoTEZByG.a0gQoPiIhpR4PT4jHPYH/sv5mbhi4wS','sAY8abbWZqh7x3HILZHbEzVPLOaJZwnxztvrBC8kvCXDpWNkKXE0SXgBpp5j','2026-07-06 13:42:57','2026-07-06 16:04:44'),(2,'Dr. Hillary Debrah','hilnek1000@gmail.com','+233245052890',NULL,'Female',NULL,'Optometry',NULL,NULL,NULL,1,'2026-07-06 13:42:57','$2y$10$n2q4BukZER.hAO6C5rDKVuuRncYYxhu4ckn.uuR7wqX3wE7sUniE.','sQOjYj30pmIF4nw36OWlsI9F4mhq508SprlB1pGcEHh5kh2bF11R5p40aJ1l','2026-07-06 13:42:57','2026-07-07 16:17:58'),(3,'Joselyne Bonsu','joselyneobonsu@gmail.com','+233546330463',NULL,'Female','1997-07-12','Adminstration',NULL,NULL,NULL,1,'2026-07-06 13:42:57','$2y$10$Ni6ULzLp95.1WCZHTSRFkem8.ililkUFg7lSrYJIt34bQ.UXWVPoW','govxAm0iSmnxpm1GvViOtaJCb6kxyKHRxrfoRNjrTLduV9QEgtLU9UyGT6eI','2026-07-06 13:42:57','2026-07-07 16:44:23'),(4,'Dr. Alphaeus Asamoah Amoasi','aamoasi@gmail.com','+233242036391',NULL,'Male','1994-10-16','Optometry',NULL,NULL,NULL,1,'2026-07-06 13:42:57','$2y$10$Y41J8HQDrRnHmsNjy5hZMuhVemNFDfEI.BGX/J3dm4MwaFtWFdRPe','fx2BMfLBTiZRfAhVUkuiQ69MnAqFnfO4pZZpY2pEA87BkzXnaoH6RUzkyk2s','2026-07-06 13:42:57','2026-07-13 14:21:44');
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

-- Dump completed on 2026-08-10 12:00:20
