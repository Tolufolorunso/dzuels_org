-- =====================================================================
-- Dzuels Educational Foundation - Production Database Export
-- Target: cPanel phpMyAdmin / MySQL 5.7+ / MariaDB 10.3+
-- Generated: 2026-10-14 22:06:16 UTC
-- Laravel: 13.35.0 | PHP: 8.4.7
-- =====================================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations` (24 rows)
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_10_163906_add_role_and_is_active_to_users_table', 1),
(5, '2026_10_10_163908_create_settings_table', 1),
(6, '2026_10_10_180900_create_team_members_table', 1),
(7, '2026_10_10_180901_create_partners_table', 1),
(8, '2026_10_10_184500_create_programmes_table', 1),
(9, '2026_10_10_191000_create_events_table', 1),
(10, '2026_10_10_191001_create_event_registrations_table', 1),
(11, '2026_10_10_200000_create_gallery_albums_table', 1),
(12, '2026_10_10_200001_create_gallery_photos_table', 1),
(13, '2026_10_10_210000_create_volunteer_applications_table', 1),
(14, '2026_10_10_210001_create_partner_enquiries_table', 1),
(15, '2026_10_10_220000_create_contact_messages_table', 1),
(16, '2026_10_10_230000_create_posts_table', 1),
(17, '2026_10_10_240000_create_newsletter_subscribers_table', 1),
(18, '2026_10_11_070000_add_theme_to_events_table', 1),
(19, '2026_10_11_160000_add_objective_to_events_table', 1),
(20, '2026_10_11_160001_create_event_sponsors_table', 1),
(21, '2026_10_11_170000_create_page_sections_table', 1),
(22, '2026_10_13_061932_add_year_to_gallery_photos_table', 2),
(23, '2026_10_14_190000_create_media_items_table', 3),
(24, '2026_10_14_220000_add_dynamic_collection_support_to_albums_and_media', 4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users` (1 rows)
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Dzuels Administrator', 'admin@dzuelsfoundation.org', NULL, '$2y$12$7nAoBPIekSCnf5ks3l2B/eTdg5hc5E3m1F3OD9xMVBUCwSdskmR2a', 'super_admin', 1, NULL, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `password_reset_tokens`: 0 rows (empty)

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned NULL DEFAULT NULL,
  `ip_address` varchar(45) NULL DEFAULT NULL,
  `user_agent` text NULL DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  KEY `sessions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions` (1 rows)
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ziRlanGLRgBFLUziiaq3wWyKLJqGkuOK9gcWXspE', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJFT3M0cUhSaVozT2lMRzlHRENURkdDdmVBT2NaU3BPQUJmanl3N3NiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hZG1pblwvcGFnZXNcL2hvbWUiLCJyb3V0ZSI6ImFkbWluLnBhZ2VzLmVkaXQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOltdLCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiJkZTFkMjcwNDlmZGJjODdmOGE5MzQyYzVkNmM4N2IyYmE3YTk1NWI4NWM3MTEzMmViNWY4Y2E5YmQ1ZDA1NzgxIn0=', 1792010776);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `expiration` bigint unsigned NOT NULL,
  KEY `cache_expiration_index` (`expiration`),
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache` (1 rows)
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dzuels-cache-dzuels_site_settings', 'a:26:{s:17:"stat_ict_students";i:165;s:22:"stat_literacy_students";i:591;s:22:"stat_schools_supported";i:15;s:14:"stat_feed_mode";s:6:"manual";s:17:"stat_api_endpoint";s:0:"";s:15:"contact_phone_1";s:17:"+234 904 249 3883";s:15:"contact_phone_2";s:17:"+234 810 000 0000";s:13:"contact_email";s:25:"info@dzuelsfoundation.org";s:15:"contact_address";s:51:"12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria";s:15:"flutterwave_url";s:40:"https://flutterwave.com/pay/j0byxgjs0nma";s:9:"bank_name";s:33:"Guaranty Trust Bank (GTBank) Plc.";s:17:"bank_account_name";s:29:"Dzuels Educational Foundation";s:19:"bank_account_number";s:10:"0450072850";s:19:"registration_number";s:16:"CAC/IT/NO 123456";s:15:"social_linkedin";s:20:"https://linkedin.com";s:15:"social_facebook";s:20:"https://facebook.com";s:14:"social_twitter";s:19:"https://twitter.com";s:16:"social_instagram";s:21:"https://instagram.com";s:15:"contact_phone_3";s:0:"";s:15:"contact_phone_4";s:0:"";s:15:"contact_phone_5";s:0:"";s:21:"contact_working_hours";s:32:"Mon – Fri: 8:00 AM – 5:00 PM";s:16:"contact_timezone";s:6:"GMT +1";s:25:"contact_working_days_note";s:25:"Closed on public holidays";s:9:"site_logo";s:21:"front/images/logo.png";s:14:"givebutter_url";s:40:"https://givebutter.com/dzuels-foundation";}', 2107270282);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint unsigned NOT NULL,
  KEY `cache_locks_expiration_index` (`expiration`),
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `cache_locks`: 0 rows (empty)

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint NOT NULL,
  `reserved_at` bigint unsigned NULL DEFAULT NULL,
  `available_at` bigint unsigned NOT NULL,
  `created_at` bigint unsigned NOT NULL,
  KEY `jobs_queue_index` (`queue`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `jobs`: 0 rows (empty)

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` varchar(255) NULL DEFAULT NULL,
  `cancelled_at` bigint unsigned NULL DEFAULT NULL,
  `created_at` bigint unsigned NOT NULL,
  `finished_at` bigint unsigned NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `job_batches`: 0 rows (empty)

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`, `queue`, `failed_at`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `failed_jobs`: 0 rows (empty)

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text NULL DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_group_index` (`group`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings` (26 rows)
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
(1, 'stat_ict_students', '165', 'integer', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'stat_literacy_students', '591', 'integer', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 'stat_schools_supported', '15', 'integer', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 'stat_feed_mode', 'manual', 'string', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(5, 'stat_api_endpoint', '', 'string', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(6, 'contact_phone_1', '+234 904 249 3883', 'string', 'contact', '2026-10-11 16:36:13', '2026-10-13 16:51:02'),
(7, 'contact_phone_2', '+234 810 000 0000', 'string', 'contact', '2026-10-11 16:36:13', '2026-10-13 16:51:02'),
(8, 'contact_email', 'info@dzuelsfoundation.org', 'string', 'contact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(9, 'contact_address', '12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria', 'string', 'contact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(10, 'flutterwave_url', 'https://flutterwave.com/pay/j0byxgjs0nma', 'string', 'banking', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(11, 'bank_name', 'Guaranty Trust Bank (GTBank) Plc.', 'string', 'banking', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(12, 'bank_account_name', 'Dzuels Educational Foundation', 'string', 'banking', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(13, 'bank_account_number', '0450072850', 'string', 'banking', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(14, 'registration_number', 'CAC/IT/NO 123456', 'string', 'social', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(15, 'social_linkedin', 'https://linkedin.com', 'string', 'social', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(16, 'social_facebook', 'https://facebook.com', 'string', 'social', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(17, 'social_twitter', 'https://twitter.com', 'string', 'social', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(18, 'social_instagram', 'https://instagram.com', 'string', 'social', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(19, 'contact_phone_3', '', 'string', 'contact', '2026-10-13 14:30:55', '2026-10-13 16:51:02'),
(20, 'contact_phone_4', '', 'string', 'contact', '2026-10-13 14:30:55', '2026-10-13 14:30:55'),
(21, 'contact_phone_5', '', 'string', 'contact', '2026-10-13 14:30:55', '2026-10-13 14:30:55'),
(22, 'contact_working_hours', 'Mon – Fri: 8:00 AM – 5:00 PM', 'string', 'contact', '2026-10-13 14:30:55', '2026-10-13 16:51:02'),
(23, 'contact_timezone', 'GMT +1', 'string', 'contact', '2026-10-13 14:30:55', '2026-10-13 14:30:55'),
(24, 'contact_working_days_note', 'Closed on public holidays', 'string', 'contact', '2026-10-13 14:30:55', '2026-10-13 14:30:55'),
(25, 'site_logo', 'front/images/logo.png', 'string', 'general', '2026-10-13 16:51:02', '2026-10-13 16:51:02'),
(26, 'givebutter_url', 'https://givebutter.com/dzuels-foundation', 'string', 'banking', '2026-10-13 16:51:02', '2026-10-13 16:51:02');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_members`
--

DROP TABLE IF EXISTS `team_members`;
CREATE TABLE `team_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `field` varchar(255) NULL DEFAULT NULL,
  `bio` text NULL DEFAULT NULL,
  `photo` varchar(255) NULL DEFAULT NULL,
  `linkedin_url` varchar(255) NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members` (15 rows)
--

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` (`id`, `name`, `role`, `group`, `field`, `bio`, `photo`, `linkedin_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Anna John-Yakubu', 'Trustee', 'trustee', NULL, NULL, 'front/images/anna.jpg', NULL, 1, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'Kate Ojiogo', 'Trustee', 'trustee', NULL, NULL, 'front/images/kate.jpg', NULL, 2, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 'Olawale Dada', 'Trustee', 'trustee', NULL, NULL, 'front/images/olawale.jpg', NULL, 3, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 'Bolaji Agbelusi (Founder)', 'Trustee', 'trustee', NULL, NULL, 'front/images/bolaji.jpg', NULL, 4, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(5, 'Mrs. Fadekemi Humphries', 'Country Manager (USA)', 'management', NULL, NULL, 'front/images/mgt_fadekemi.webp', NULL, 1, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(6, 'Ms. Michelle Sarumi', 'Country Manager (Nigeria)', 'management', NULL, NULL, 'front/images/mgt_michelle.png', NULL, 2, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(7, 'Mrs. Fola Oloyede', 'Advisor', 'advisor', 'Education Services', 'Education Consultant | Founder, Just4Teachers Organization', NULL, NULL, 1, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(8, 'Solape Adesuyi', 'Advisor', 'advisor', 'Corporate Legal Services', 'Principal Partner, Solape Adesuyi Associates', NULL, 'http://www.linkedin.com/in/solape-adesuyi-4103b44a', 2, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(9, 'Mrs. Toyin Idowu', 'Advisor', 'advisor', 'Education Services', 'Education Administrator', NULL, NULL, 3, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(10, 'Dr. Eyitayo Abejide', 'Advisor', 'advisor', 'Medical Services', 'Public Health Practitioner', NULL, 'http://www.linkedin.com/in/eyitayo-abejide-69867538', 4, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(11, 'Dr. Olukemi Alegi', 'Advisor', 'advisor', 'Medical Services', 'Consultant Oncologist', NULL, 'https://www.linkedin.com/in/olukemi-alegi-5610641a6', 5, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(12, 'Mr. Odunayo Ayegbusi', 'Advisor', 'advisor', 'Project Management-Energy Solutions', 'Nuclear Engineer', NULL, 'https://www.linkedin.com/in/ayo-ayegbusi-18725349', 6, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(13, 'Ms. Kofoworola Tubi', 'Advisor', 'advisor', 'Architectural Services', 'Architect', NULL, 'https://www.linkedin.com/in/kofoworola-tubi', 7, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(14, 'Mr. David Jayeola', 'Advisor', 'advisor', 'ICT Services', 'Software Engineer', NULL, 'https://www.linkedin.com/in/david-opeyemi-jaiyeola-20401a11/', 8, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(15, 'Mr. Tolu Talabi', 'Advisor', 'advisor', 'ICT Services', 'Software Engineer, System Support & Integration', NULL, NULL, 9, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `website_url` varchar(255) NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners` (4 rows)
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` (`id`, `name`, `logo`, `website_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ASCEND Education Foundation', 'front/images/ascend.webp', 'https://ascendedufoundation.org/', 1, 1, '2026-10-11 16:36:13', '2026-10-13 14:47:21'),
(2, 'Solape Adesuyi Associates', 'front/images/SAA.webp', NULL, 2, 1, '2026-10-11 16:36:13', '2026-10-13 14:47:21'),
(3, 'Help Meet A Need', 'front/images/helpmeetaneed.webp', NULL, 3, 1, '2026-10-11 16:36:13', '2026-10-13 14:47:21'),
(4, 'Int International', 'front/images/intinternational.webp', NULL, 4, 1, '2026-10-11 16:36:13', '2026-10-13 14:47:21');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
CREATE TABLE `programmes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `body` longtext NOT NULL,
  `cover_image` varchar(255) NULL DEFAULT NULL,
  `partner_name` varchar(255) NULL DEFAULT NULL,
  `gallery_album_id` bigint unsigned NULL DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `seo_title` varchar(255) NULL DEFAULT NULL,
  `seo_description` text NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `programmes_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programmes` (5 rows)
--

LOCK TABLES `programmes` WRITE;
/*!40000 ALTER TABLE `programmes` DISABLE KEYS */;
INSERT INTO `programmes` (`id`, `title`, `slug`, `summary`, `body`, `cover_image`, `partner_name`, `gallery_album_id`, `is_featured`, `is_active`, `sort_order`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 'Digital Infrastructure & Literacy in Ijero-Ekiti', 'ijero-digital-literacy', 'Free literacy interventions, digital skills training for teachers and students, and computer lab provisioning across Ijero-Ekiti in Southwest Nigeria.', 'The Digital Infrastructure and Literacy project in Ijero-Ekiti is Dzuels Educational Foundation\'s flagship community technology intervention. Operated in close technical collaboration with the Information Technology Seed Development Initiative (SeedDev), this project addresses the stark digital divide experienced by secondary schools and educators in rural Ekiti State.\n\nThrough this multi-year initiative, we equip local non-profit and public community secondary schools with fully functional computer workstations, reliable power inverters, and offline educational repositories. Our trainers deliver structured weekend and holiday bootcamps covering foundational computer operations, typing skills, digital research, visual productivity suites, and basic coding paradigms for both pupils and teachers.\n\nBy empowering educators with digital lesson planning competencies and introducing young learners to modern technology early, this programme directly unlocks future career pathways in software engineering, digital entrepreneurship, and academic excellence.', 'front/images/dzuelsCollage.webp', 'Information Technology Seed Development Initiative (SeedDev)', NULL, 1, 1, 2, 'Digital Infrastructure & Literacy in Ijero-Ekiti | Dzuels Foundation', 'Equipping public schools with modern digital infrastructure, teacher upskilling, and computer literacy bootcamps for indigent students in Ijero-Ekiti.', '2026-10-11 16:36:13', '2026-10-13 14:07:52'),
(2, 'Innovation and Tech Day (ITD)', 'innovation-and-tech-day', 'Our annual flagship technology festival celebrating youth innovation, hands-on digital exhibitions, robotics demos, and keynote mentorship from tech industry leaders.', 'Innovation and Tech Day (ITD) is our marquee annual showcase where young beneficiaries from across Ekiti State demonstrate their technological inventions, creative software projects, and digital literacy milestones before parents, community leaders, and visiting industry executives.\n\nEach edition features high-energy student hackathons, coding exhibitions, digital art showcases, and inspirational keynote panels hosted by software engineers, healthcare leaders, and academic consultants from Nigeria and the diaspora. Outstanding teams and student innovators receive educational scholarships, laptops, and direct mentorship placement to foster their continued growth.\n\nITD serves as both a celebration of community resilience and a launchpad for the next generation of African technology creators.', 'front/images/gallery/optimized/itd-2025-05.webp', 'SeedDev & Corporate Tech Allies', NULL, 1, 1, 3, 'Innovation and Tech Day (ITD) | Dzuels Educational Foundation', 'Annual flagship technology exposition celebrating youth innovation, coding exhibitions, and creative problem-solving among Ekiti youth.', '2026-10-11 16:36:13', '2026-10-13 14:07:52'),
(3, 'Reading Competition', 'reading-competition', 'A grassroots literacy campaign designed to rekindle a passionate reading culture, vocabulary mastery, and textual comprehension among primary and junior secondary students.', 'Reading is the foundational cornerstone of all intellectual development. The Dzuels Reading Competition is an annual literacy advocacy programme that challenges primary and junior secondary pupils across underserved schools to dive deep into literature, African folklore, and contemporary educational texts.\n\nParticipating students receive curated book packs and participate in inter-school reading challenges that assess comprehension, phonetic pronunciation, textual summary, and expressive articulation. In tandem with the competition, our volunteer educators facilitate mobile reading clinics and donate class libraries to partnering schools to sustain reading habits throughout the academic calendar.\n\nBy transforming reading into an exciting, rewarded sport, we build lifelong learners who lead with intellect and curiosity.', 'front/images/about_1.webp', 'ASCEND Education Foundation', NULL, 0, 1, 4, 'Annual Reading Competition | Dzuels Educational Foundation', 'State-wide literacy intervention revitalizing reading culture and comprehension excellence among primary school pupils in Ekiti State.', '2026-10-11 16:36:13', '2026-10-13 14:07:52'),
(4, 'Spelling Bee Contests', 'spelling-bee-contests', 'A high-impact inter-school spelling championship enhancing linguistic confidence, memory retention, public poise, and academic camaraderie for young pupils.', 'The Dzuels Spelling Bee Contest brings together hundreds of eager pupils from diverse public and community schools to test their mastery of the English lexicon in a supportive, spirited tournament.\n\nBeyond teaching orthography and etymology, the Spelling Bee builds crucial non-cognitive skills: composure under pressure, mental agility, sportsmanship, and fearless public speaking. Finalists compete on stage before cheering peers, families, and education inspectors, competing for coveted school fee grants, complete textbook suites, and gold medals.\n\nThis initiative proves that with structured practice and compassionate encouragement, every child can achieve extraordinary linguistic and academic heights.', 'front/images/gallery/optimized/spelling-01.webp', NULL, NULL, 0, 1, 5, 'Spelling Bee Contests | Dzuels Educational Foundation', 'Inter-school competition promoting vocabulary acquisition, public speaking poise, and cognitive development for young learners.', '2026-10-11 16:36:13', '2026-10-13 14:07:52'),
(5, 'Famoyinka Memorial Children\'s Library', 'famoyinka-memorial-library', 'A community-centered children\'s reading sanctuary and creative learning hub in Ijero-Ekiti providing free book lending, weekend study circles, creative arts and crafts, transformational leadership games, and educational excursions.', 'The Famoyinka Memorial Children\'s Library is Dzuels Educational Foundation\'s dedicated literacy and youth enrichment sanctuary in Ijero-Ekiti. Established in loving memory of a lifelong community champion for child welfare and learning, the library provides a safe, welcoming, and vibrant environment where young learners from indigent backgrounds can discover the joy of books, cultivate imaginative thinking, and build foundational leadership qualities.\n\nOur extensive collection spans African literature, classic folklore, beginner phonics, science and mathematics workbooks, reference encyclopedias, and engaging fiction. Beyond daily book loans, the library hosts structured weekend reading circles, interactive storytelling sessions, creative arts and crafts workshops, and transformational leadership board challenges that instill discipline, teamwork, and fearless curiosity.\n\nIn addition, our library beneficiaries participate in fully sponsored educational excursions — such as nature and environmental science discovery field trips to Ikogosi Warm Springs — expanding their horizons beyond classroom walls. Every service, book loan, and mentorship activity is offered completely free of charge to all children in Ijero-Ekiti and surrounding communities.', 'front/images/gallery/optimized/famoyinka-01.webp', 'Famoyinka Family & Community Literacy Patrons', NULL, 1, 1, 1, 'Famoyinka Memorial Children\'s Library | Dzuels Educational Foundation', 'Community children\'s library providing free book borrowing, creative crafts, leadership games, and literacy circles in Ijero-Ekiti.', '2026-10-13 14:07:52', '2026-10-13 14:07:52');
/*!40000 ALTER TABLE `programmes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `description` longtext NOT NULL,
  `event_date` datetime NULL DEFAULT NULL,
  `end_date` datetime NULL DEFAULT NULL,
  `venue` varchar(255) NULL DEFAULT NULL,
  `theme` varchar(255) NULL DEFAULT NULL,
  `objective` text NULL DEFAULT NULL,
  `banner_image` varchar(255) NULL DEFAULT NULL,
  `schedule` longtext NULL DEFAULT NULL,
  `is_registration_open` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `seo_title` varchar(255) NULL DEFAULT NULL,
  `seo_description` text NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `events_is_active_sort_order_event_date_index` (`is_active`, `sort_order`, `event_date`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events` (2 rows)
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`id`, `title`, `slug`, `summary`, `description`, `event_date`, `end_date`, `venue`, `theme`, `objective`, `banner_image`, `schedule`, `is_registration_open`, `is_active`, `sort_order`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 'Ijero Tech-Day 2026 (ITD 2026)', 'ijero-tech-day-2026', 'Empowering 300 children with practical AI tools across 10 rotating batches of 30, inside interactive labs and garden booths, plus 22 cohort graduate certificates.', 'Ijero Tech-Day 2026 (ITD 2026) is the flagship community technology festival organized by Dzuels Educational Foundation under the theme "Learning Today, Creating Tomorrow". Held at the AAoJ Memorial Learning Center in Ijero-Ekiti, this high-impact gathering brings together 300 selected primary and secondary school students, dedicated educators, and community mentors for a transformative full-day digital literacy and AI immersion experience.\n\nStudents participate in synchronized 10-batch rotations of 30 participants each across two experiential learning zones:\n1. Inside Practical Lab: 20-minute intensive hands-on sessions introducing practical Artificial Intelligence tools, prompt thinking, and digital creativity on modern computing equipment.\n2. Garden Innovation Pavilion: Outdoor creative stations featuring an AI Image Creation Booth, interactive Tech Quizzes, student digital logic challenges, and prize giveaways.\n\nThe event also features the milestone Cohort 2026 Certificate Presentations, honoring 22 outstanding students who completed our rigorous digital literacy training, alongside community educational keynotes and educator recognition.', '2026-10-03 08:00:00', '2026-10-03 17:00:00', 'AAoJ Memorial Learning Center, Ijero-Ekiti (inside + garden)', 'Learning Today, Creating Tomorrow', 'Empowering children with AI tools to learn smarter, think creatively, and build the future.', 'front/images/events/itd-2026-backdrop.webp', '[{"time":"08:00 AM - 09:00 AM","title":"Accreditation, Batch Allocation & Welcome Assembly","speaker":"Dzuels Foundation Coordination Team"},{"time":"09:00 AM - 09:30 AM","title":"Opening Ceremony: \\"Learning Today, Creating Tomorrow\\"","speaker":"Board of Trustees & Community Leaders"},{"time":"09:30 AM - 01:00 PM","title":"Morning AI Lab & Garden Rotation Sessions (Batches 1 \\u2013 5)","speaker":"Tech Instructors & Mentors"},{"time":"01:00 PM - 02:00 PM","title":"Lunch Break, Networking & Image Creation Showcase","speaker":"Students & Guest Exhibitors"},{"time":"02:00 PM - 04:30 PM","title":"Afternoon AI Lab & Garden Rotation Sessions (Batches 6 \\u2013 10)","speaker":"Tech Instructors & Mentors"},{"time":"04:30 PM - 05:00 PM","title":"Cohort Graduation: Presentation of 22 Certificates & Grand Finale","speaker":"Foundation Leadership & Dignitaries"}]', 0, 1, 1, 'Ijero Tech-Day 2026 (ITD 2026) | Dzuels Educational Foundation', 'Join 300 students and educators for Ijero Tech-Day 2026 on Saturday, October 3, 2026. Theme: Learning Today, Creating Tomorrow. Objective: Empowering children with AI tools to learn smarter, think creatively, and build the future.', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'Ijero Tech Day 2024 (Inaugural Edition)', 'ijero-tech-day-2024', 'The inaugural gathering that connected over 150 local students to digital literacy tools, robotics kits, and mentorship.', 'The 2024 edition of Ijero Tech Day set a new benchmark for grassroots technology engagement in Ekiti State. Facilitated in collaboration with Information Technology Seed Development Initiative (SeedDev), the gathering welcomed 150+ students from 15 secondary schools across Ijero-Ekiti for practical digital workshops and competitions.\n\nHighlights included interactive coding basics, typing mastery awards, and the official handover of modern computing equipment to participating schools.', '2024-11-15 09:00:00', '2024-11-15 15:00:00', 'Ijero-Ekiti Civic Centre, Ekiti State', NULL, NULL, 'front/images/techday.webp', '[{"time":"09:00 AM - 10:00 AM","title":"Opening Ceremony & Keynote Address","speaker":"Dzuels Team & Education Officials"},{"time":"10:00 AM - 12:30 PM","title":"Digital Literacy Workshops & Coding Clinic","speaker":"SeedDev Instructors"},{"time":"01:00 PM - 02:30 PM","title":"Student Competitions & Award Presentations","speaker":"Guest Evaluators"}]', 0, 1, 2, 'Ijero Tech Day 2024 | Dzuels Foundation', 'Highlights and outcomes from the inaugural Ijero Tech Day 2024 in Ekiti State.', '2026-10-11 16:36:13', '2026-10-11 16:36:13');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_sponsors`
--

DROP TABLE IF EXISTS `event_sponsors`;
CREATE TABLE `event_sponsors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo_path` varchar(255) NULL DEFAULT NULL,
  `website_url` varchar(255) NULL DEFAULT NULL,
  `tier` varchar(255) NOT NULL DEFAULT 'supporter',
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `event_sponsors_event_id_is_active_sort_order_index` (`event_id`, `is_active`, `sort_order`),
  PRIMARY KEY (`id`),
  CONSTRAINT `event_sponsors_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_sponsors` (3 rows)
--

LOCK TABLES `event_sponsors` WRITE;
/*!40000 ALTER TABLE `event_sponsors` DISABLE KEYS */;
INSERT INTO `event_sponsors` (`id`, `event_id`, `name`, `logo_path`, `website_url`, `tier`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Solape Adesuyi Associates', 'front/images/SAA.jpg', NULL, 'lead', 1, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 1, 'Help Meet A Need', 'front/images/helpmeetaneed.png', NULL, 'partner', 2, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 1, 'Int International', 'front/images/intinternational.png', NULL, 'supporter', 3, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
/*!40000 ALTER TABLE `event_sponsors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_registrations`
--

DROP TABLE IF EXISTS `event_registrations`;
CREATE TABLE `event_registrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `attendee_type` varchar(255) NULL DEFAULT NULL,
  `notes` text NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `event_registrations_event_id_email_unique` (`event_id`, `email`),
  KEY `event_registrations_event_id_index` (`event_id`),
  PRIMARY KEY (`id`),
  CONSTRAINT `event_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `event_registrations`: 0 rows (empty)

--
-- Table structure for table `gallery_albums`
--

DROP TABLE IF EXISTS `gallery_albums`;
CREATE TABLE `gallery_albums` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `kicker` varchar(255) NULL DEFAULT NULL,
  `description` longtext NULL DEFAULT NULL,
  `cover_image` varchar(255) NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `gallery_albums_is_active_sort_order_index` (`is_active`, `sort_order`),
  UNIQUE KEY `gallery_albums_slug_unique` (`slug`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_albums` (10 rows)
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` (`id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `is_active`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'ITD 2025', 'itd-2025', 'Innovation and Tech Day', 'Highlights from ITD 2025: keynote talks, hands-on coding demonstrations, award moments, and student builders.', 'front/images/gallery/optimized/itd-2025-05.webp', 2, 1, 1, '2026-10-11 16:36:13', '2026-10-13 14:10:21'),
(2, 'ITD 2024', 'itd-2024', 'Inaugural Tech Day', 'Highlights from the maiden ITD 2024 edition: bringing technology education and digital skills to Ijero-Ekiti.', 'front/images/gallery/optimized/itd-2024-01.webp', 3, 1, 1, '2026-10-11 16:36:13', '2026-10-13 19:54:22'),
(3, 'Reading & Children’s Library', 'reading-competition', 'Early Childhood Literacy', 'Encouraging early literacy, book reading culture, comprehension, and vocabulary among young students across Ekiti schools.', 'front/images/gallery/optimized/reading-01.webp', 5, 1, 1, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(4, 'Spelling Bee Championship', 'spelling-bee', 'Academic Precision & Stage Confidence', 'High-energy inter-school spelling bee celebrating linguistic talent, spelling precision, stage confidence, and academic camaraderie.', 'front/images/gallery/optimized/spelling-bee-01.webp', 6, 1, 1, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(5, 'General Community & Cohorts', 'general', 'Youth Mentorship & Excursions', 'Cohort digital training graduations, student excursions to Ikogosi Warm Springs, charity love sales, and volunteer outreach across Ekiti State.', 'front/images/gallery/optimized/general-01.webp', 8, 1, 1, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(6, 'Famoyinka Memorial Library', 'famoyinka-library', 'Children\'s Literacy & Creative Hub', 'Action moments from our community children\'s library in Ijero-Ekiti: story circles, book loans, creative crafts, transformational leadership games, and educational excursions.', 'front/images/gallery/optimized/famoyinka-01.webp', 1, 1, 1, '2026-10-13 14:10:21', '2026-10-13 14:10:21'),
(7, 'Essay Competition', 'essay-competition', 'Academic Literacy & Critical Thinking', 'Annual student essay competition fostering critical thinking, literature appreciation, and writing skills among youth in Ijero-Ekiti and Ekiti State.', 'front/images/gallery/optimized/essay-competition-01.webp', 2, 1, 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(8, 'Girls in Tech & Leadership', 'girls-day', 'Empowerment & Digital Inclusion', 'Dedicated workshops inspiring young female students to embrace computer science, coding, creative digital design, and career leadership.', 'front/images/gallery/optimized/girls-day-01.webp', 3, 1, 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(9, 'Innovation and Tech Day (ITD)', 'itd', 'Flagship Yearly Festival', 'Flagship yearly celebration uniting innovators, students, tech enthusiasts, educators, and community leaders for digital empowerment in Ijero-Ekiti.', 'front/images/gallery/optimized/itd-01.webp', 4, 1, 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(10, 'WDADA Community Outreach', 'wdada-community-outreach', 'Grassroots Relief & Welfare', 'Grassroots community development, nutritional relief, and education welfare reaching indigent children and families across Ekiti communities.', 'front/images/gallery/optimized/wdada-01.webp', 7, 1, 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36');
/*!40000 ALTER TABLE `gallery_albums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_photos`
--

DROP TABLE IF EXISTS `gallery_photos`;
CREATE TABLE `gallery_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gallery_album_id` bigint unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) NULL DEFAULT NULL,
  `alt_text` varchar(255) NULL DEFAULT NULL,
  `year` varchar(10) NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `gallery_photos_gallery_album_id_sort_order_index` (`gallery_album_id`, `sort_order`),
  PRIMARY KEY (`id`),
  CONSTRAINT `gallery_photos_gallery_album_id_foreign` FOREIGN KEY (`gallery_album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_photos` (151 rows)
--

LOCK TABLES `gallery_photos` WRITE;
/*!40000 ALTER TABLE `gallery_photos` DISABLE KEYS */;
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `year`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'front/images/gallery/optimized/itd-2025-01.webp', 'ITD 2025 Highlight #1', 'ITD 2025 - ITD 2025 Highlight #1 in Ijero-Ekiti', '2025', 1, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(2, 1, 'front/images/gallery/optimized/itd-2025-02.webp', 'ITD 2025 Highlight #2', 'ITD 2025 - ITD 2025 Highlight #2 in Ijero-Ekiti', '2025', 2, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(3, 1, 'front/images/gallery/optimized/itd-2025-03.webp', 'ITD 2025 Highlight #3', 'ITD 2025 - ITD 2025 Highlight #3 in Ijero-Ekiti', '2025', 3, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(4, 1, 'front/images/gallery/optimized/itd-2025-04.webp', 'ITD 2025 Highlight #4', 'ITD 2025 - ITD 2025 Highlight #4 in Ijero-Ekiti', '2025', 4, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(5, 1, 'front/images/gallery/optimized/itd-2025-05.webp', 'ITD 2025 Highlight #5', 'ITD 2025 - ITD 2025 Highlight #5 in Ijero-Ekiti', '2025', 5, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(6, 1, 'front/images/gallery/optimized/itd-2025-06.webp', 'ITD 2025 Highlight #6', 'ITD 2025 - ITD 2025 Highlight #6 in Ijero-Ekiti', '2025', 6, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(7, 1, 'front/images/gallery/optimized/itd-2025-07.webp', 'ITD 2025 Highlight #7', 'ITD 2025 - ITD 2025 Highlight #7 in Ijero-Ekiti', '2025', 7, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(8, 1, 'front/images/gallery/optimized/itd-2025-08.webp', 'ITD 2025 Highlight #8', 'ITD 2025 - ITD 2025 Highlight #8 in Ijero-Ekiti', '2025', 8, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(9, 1, 'front/images/gallery/optimized/itd-2025-09.webp', 'ITD 2025 Highlight #9', 'ITD 2025 - ITD 2025 Highlight #9 in Ijero-Ekiti', '2025', 9, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(10, 1, 'front/images/gallery/optimized/itd-2025-10.webp', 'ITD 2025 Highlight #10', 'ITD 2025 - ITD 2025 Highlight #10 in Ijero-Ekiti', '2025', 10, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(11, 1, 'front/images/gallery/optimized/itd-2025-11.webp', 'ITD 2025 Highlight #11', 'ITD 2025 - ITD 2025 Highlight #11 in Ijero-Ekiti', '2025', 11, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(12, 1, 'front/images/gallery/optimized/itd-2025-12.webp', 'ITD 2025 Highlight #12', 'ITD 2025 - ITD 2025 Highlight #12 in Ijero-Ekiti', '2025', 12, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(13, 1, 'front/images/gallery/optimized/itd-2025-13.webp', 'ITD 2025 Highlight #13', 'ITD 2025 - ITD 2025 Highlight #13 in Ijero-Ekiti', '2025', 13, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(14, 1, 'front/images/gallery/optimized/itd-2025-14.webp', 'ITD 2025 Highlight #14', 'ITD 2025 - ITD 2025 Highlight #14 in Ijero-Ekiti', '2025', 14, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(15, 1, 'front/images/gallery/optimized/itd-2025-15.webp', 'ITD 2025 Highlight #15', 'ITD 2025 - ITD 2025 Highlight #15 in Ijero-Ekiti', '2025', 15, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(26, 2, 'front/images/gallery/optimized/itd-2024-01.webp', 'ITD 2024 Highlight #1', 'ITD 2024 - ITD 2024 Highlight #1 in Ijero-Ekiti', '2024', 1, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(27, 2, 'front/images/gallery/optimized/itd-2024-02.webp', 'ITD 2024 Highlight #2', 'ITD 2024 - ITD 2024 Highlight #2 in Ijero-Ekiti', '2024', 2, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(28, 2, 'front/images/gallery/optimized/itd-2024-03.webp', 'ITD 2024 Highlight #3', 'ITD 2024 - ITD 2024 Highlight #3 in Ijero-Ekiti', '2024', 3, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(29, 2, 'front/images/gallery/optimized/itd-2024-04.webp', 'ITD 2024 Highlight #4', 'ITD 2024 - ITD 2024 Highlight #4 in Ijero-Ekiti', '2024', 4, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(30, 2, 'front/images/gallery/optimized/itd-2024-05.webp', 'ITD 2024 Highlight #5', 'ITD 2024 - ITD 2024 Highlight #5 in Ijero-Ekiti', '2024', 5, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(31, 2, 'front/images/gallery/optimized/itd-2024-06.webp', 'ITD 2024 Highlight #6', 'ITD 2024 - ITD 2024 Highlight #6 in Ijero-Ekiti', '2024', 6, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(32, 2, 'front/images/gallery/optimized/itd-2024-07.webp', 'ITD 2024 Highlight #7', 'ITD 2024 - ITD 2024 Highlight #7 in Ijero-Ekiti', '2024', 7, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(33, 2, 'front/images/gallery/optimized/itd-2024-08.webp', 'ITD 2024 Highlight #8', 'ITD 2024 - ITD 2024 Highlight #8 in Ijero-Ekiti', '2024', 8, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(34, 2, 'front/images/gallery/optimized/itd-2024-09.webp', 'ITD 2024 Highlight #9', 'ITD 2024 - ITD 2024 Highlight #9 in Ijero-Ekiti', '2024', 9, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(35, 2, 'front/images/gallery/optimized/itd-2024-10.webp', 'ITD 2024 Highlight #10', 'ITD 2024 - ITD 2024 Highlight #10 in Ijero-Ekiti', '2024', 10, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(36, 2, 'front/images/gallery/optimized/itd-2024-11.webp', 'ITD 2024 Highlight #11', 'ITD 2024 - ITD 2024 Highlight #11 in Ijero-Ekiti', '2024', 11, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(37, 2, 'front/images/gallery/optimized/itd-2024-12.webp', 'ITD 2024 Highlight #12', 'ITD 2024 - ITD 2024 Highlight #12 in Ijero-Ekiti', '2024', 12, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(51, 3, 'front/images/gallery/optimized/reading-01.webp', 'Reading Competition Moment #1', 'Reading & Children’s Library - Reading Competition Moment #1 in Ijero-Ekiti', '2024', 1, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(52, 3, 'front/images/gallery/optimized/reading-02.webp', 'Reading Competition Moment #2', 'Reading & Children’s Library - Reading Competition Moment #2 in Ijero-Ekiti', '2024', 2, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(53, 3, 'front/images/gallery/optimized/reading-03.webp', 'Reading Competition Moment #3', 'Reading & Children’s Library - Reading Competition Moment #3 in Ijero-Ekiti', '2024', 3, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(54, 3, 'front/images/gallery/optimized/reading-04.webp', 'Reading Competition Moment #4', 'Reading & Children’s Library - Reading Competition Moment #4 in Ijero-Ekiti', '2024', 4, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(55, 3, 'front/images/gallery/optimized/reading-05.webp', 'Reading Competition Moment #5', 'Reading & Children’s Library - Reading Competition Moment #5 in Ijero-Ekiti', '2024', 5, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(56, 3, 'front/images/gallery/optimized/reading-06.webp', 'Reading Competition Moment #6', 'Reading & Children’s Library - Reading Competition Moment #6 in Ijero-Ekiti', '2024', 6, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(57, 3, 'front/images/gallery/optimized/reading-07.webp', 'Reading Competition Moment #7', 'Reading & Children’s Library - Reading Competition Moment #7 in Ijero-Ekiti', '2024', 7, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(58, 3, 'front/images/gallery/optimized/reading-08.webp', 'Reading Competition Moment #8', 'Reading & Children’s Library - Reading Competition Moment #8 in Ijero-Ekiti', '2024', 8, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(76, 4, 'front/images/gallery/optimized/spelling-01.webp', 'Spelling Bee Contestant #1', 'Spelling Bee - Spelling Bee Contestant #1 in Ijero-Ekiti', '2024', 1, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(77, 4, 'front/images/gallery/optimized/spelling-02.webp', 'Spelling Bee Contestant #2', 'Spelling Bee - Spelling Bee Contestant #2 in Ijero-Ekiti', '2024', 2, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(78, 4, 'front/images/gallery/optimized/spelling-03.webp', 'Spelling Bee Contestant #3', 'Spelling Bee - Spelling Bee Contestant #3 in Ijero-Ekiti', '2024', 3, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(79, 4, 'front/images/gallery/optimized/spelling-04.webp', 'Spelling Bee Contestant #4', 'Spelling Bee - Spelling Bee Contestant #4 in Ijero-Ekiti', '2024', 4, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(80, 4, 'front/images/gallery/optimized/spelling-05.webp', 'Spelling Bee Contestant #5', 'Spelling Bee - Spelling Bee Contestant #5 in Ijero-Ekiti', '2024', 5, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(81, 4, 'front/images/gallery/optimized/spelling-06.webp', 'Spelling Bee Contestant #6', 'Spelling Bee - Spelling Bee Contestant #6 in Ijero-Ekiti', '2024', 6, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(82, 4, 'front/images/gallery/optimized/spelling-07.webp', 'Spelling Bee Contestant #7', 'Spelling Bee - Spelling Bee Contestant #7 in Ijero-Ekiti', '2024', 7, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(83, 4, 'front/images/gallery/optimized/spelling-08.webp', 'Spelling Bee Contestant #8', 'Spelling Bee - Spelling Bee Contestant #8 in Ijero-Ekiti', '2024', 8, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(84, 4, 'front/images/gallery/optimized/spelling-09.webp', 'Spelling Bee Contestant #9', 'Spelling Bee - Spelling Bee Contestant #9 in Ijero-Ekiti', '2024', 9, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(85, 4, 'front/images/gallery/optimized/spelling-10.webp', 'Spelling Bee Contestant #10', 'Spelling Bee - Spelling Bee Contestant #10 in Ijero-Ekiti', '2024', 10, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(101, 5, 'front/images/gallery/optimized/general-01.webp', 'Community Impact Moment #1', 'General Community & Cohorts - Community Impact Moment #1 in Ijero-Ekiti', '2024', 1, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(102, 5, 'front/images/gallery/optimized/general-02.webp', 'Community Impact Moment #2', 'General Community & Cohorts - Community Impact Moment #2 in Ijero-Ekiti', '2024', 2, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(103, 5, 'front/images/gallery/optimized/general-03.webp', 'Community Impact Moment #3', 'General Community & Cohorts - Community Impact Moment #3 in Ijero-Ekiti', '2024', 3, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(104, 5, 'front/images/gallery/optimized/general-04.webp', 'Community Impact Moment #4', 'General Community & Cohorts - Community Impact Moment #4 in Ijero-Ekiti', '2024', 4, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(105, 5, 'front/images/gallery/optimized/general-05.webp', 'Community Impact Moment #5', 'General Community & Cohorts - Community Impact Moment #5 in Ijero-Ekiti', '2024', 5, '2026-10-11 16:36:13', '2026-10-14 22:04:36');
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `year`, `sort_order`, `created_at`, `updated_at`) VALUES
(106, 5, 'front/images/gallery/optimized/general-06.webp', 'Community Impact Moment #6', 'General Community & Cohorts - Community Impact Moment #6 in Ijero-Ekiti', '2024', 6, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(107, 5, 'front/images/gallery/optimized/general-07.webp', 'Community Impact Moment #7', 'General Community & Cohorts - Community Impact Moment #7 in Ijero-Ekiti', '2024', 7, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(108, 5, 'front/images/gallery/optimized/general-08.webp', 'Community Impact Moment #8', 'General Community & Cohorts - Community Impact Moment #8 in Ijero-Ekiti', '2024', 8, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(109, 5, 'front/images/gallery/optimized/general-09.webp', 'Community Impact Moment #9', 'General Community & Cohorts - Community Impact Moment #9 in Ijero-Ekiti', '2024', 9, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(110, 5, 'front/images/gallery/optimized/general-10.webp', 'Community Impact Moment #10', 'General Community & Cohorts - Community Impact Moment #10 in Ijero-Ekiti', '2024', 10, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(111, 5, 'front/images/gallery/optimized/general-11.webp', 'Community Impact Moment #11', 'General Community & Cohorts - Community Impact Moment #11 in Ijero-Ekiti', '2024', 11, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(112, 5, 'front/images/gallery/optimized/general-12.webp', 'Community Impact Moment #12', 'General Community & Cohorts - Community Impact Moment #12 in Ijero-Ekiti', '2024', 12, '2026-10-11 16:36:13', '2026-10-14 22:04:36'),
(126, 6, 'front/images/gallery/optimized/famoyinka-01.webp', 'Children\'s Book Lending & Fiction Shelf', 'Student selecting books at Famoyinka Memorial Children\'s Library', '2024', 1, '2026-10-13 14:10:21', '2026-10-13 14:10:21'),
(133, 6, 'front/images/gallery/optimized/famoyinka-08.webp', 'Holistic Education Support, Arts & Values Pillars', 'Foundation education support and values curriculum', '2024', 8, '2026-10-13 14:10:21', '2026-10-13 14:10:21'),
(138, 6, 'uploads/gallery/photos/1791914698_iuIliqYsTVTp.jpg', NULL, 'Famoyinka Memorial Library', '2026', 9, '2026-10-13 18:04:58', '2026-10-13 18:04:58'),
(139, 2, 'uploads/gallery/photos/1791920685_ocrrwda9tM4N.webp', NULL, 'ITD 2024', '2026', 13, '2026-10-13 19:44:46', '2026-10-13 19:44:46'),
(140, 6, 'front/images/gallery/optimized/famoyinka-02.webp', 'Transformational Leadership & Teamwork Games', 'Outdoor youth leadership and character-building games', '2024', 2, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(141, 6, 'front/images/gallery/optimized/famoyinka-03.webp', 'Creative Arts, Painting & Crafts Workshop', 'Children painting and creating paper crafts in the library', '2025', 3, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(142, 6, 'front/images/gallery/optimized/famoyinka-04.webp', 'Ikogosi Warm Springs Educational Nature Excursion', 'Pupils exploring nature boardwalk at Ikogosi Warm Springs', '2025', 4, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(143, 6, 'front/images/gallery/optimized/famoyinka-05.webp', 'Attentive Early Childhood Storybook Reading Circle', 'Young pupil reading attentively in classroom circle', '2024', 5, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(144, 6, 'front/images/gallery/optimized/famoyinka-06.webp', 'Digital Literacy & Weekend Study Workstations', 'Students working on computers in the study lab', '2025', 6, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(145, 6, 'front/images/gallery/optimized/famoyinka-07.webp', 'Smiling Beneficiaries in Community Peer Learning', 'Smiling schoolchildren participating in library club', '2024', 7, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(146, 7, 'front/images/gallery/optimized/essay-competition-01.webp', 'Essay Competition Moment #1', 'Essay Competition - Essay Competition Moment #1 in Ijero-Ekiti', '2026', 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(147, 7, 'front/images/gallery/optimized/essay-competition-02.webp', 'Essay Competition Moment #2', 'Essay Competition - Essay Competition Moment #2 in Ijero-Ekiti', '2026', 2, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(148, 7, 'front/images/gallery/optimized/essay-competition-03.webp', 'Essay Competition Moment #3', 'Essay Competition - Essay Competition Moment #3 in Ijero-Ekiti', '2026', 3, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(149, 7, 'front/images/gallery/optimized/essay-competition-04.webp', 'Essay Competition Moment #4', 'Essay Competition - Essay Competition Moment #4 in Ijero-Ekiti', '2026', 4, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(150, 7, 'front/images/gallery/optimized/essay-competition-05.webp', 'Essay Competition Moment #5', 'Essay Competition - Essay Competition Moment #5 in Ijero-Ekiti', '2026', 5, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(151, 7, 'front/images/gallery/optimized/essay-competition-06.webp', 'Essay Competition Moment #6', 'Essay Competition - Essay Competition Moment #6 in Ijero-Ekiti', '2026', 6, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(152, 7, 'front/images/gallery/optimized/essay-competition-07.webp', 'Essay Competition Moment #7', 'Essay Competition - Essay Competition Moment #7 in Ijero-Ekiti', '2026', 7, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(153, 7, 'front/images/gallery/optimized/essay-competition-08.webp', 'Essay Competition Moment #8', 'Essay Competition - Essay Competition Moment #8 in Ijero-Ekiti', '2026', 8, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(154, 7, 'front/images/gallery/optimized/essay-competition-09.webp', 'Essay Competition Moment #9', 'Essay Competition - Essay Competition Moment #9 in Ijero-Ekiti', '2026', 9, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(155, 7, 'front/images/gallery/optimized/essay-competition-10.webp', 'Essay Competition Moment #10', 'Essay Competition - Essay Competition Moment #10 in Ijero-Ekiti', '2026', 10, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(156, 7, 'front/images/gallery/optimized/essay-competition-11.webp', 'Essay Competition Moment #11', 'Essay Competition - Essay Competition Moment #11 in Ijero-Ekiti', '2026', 11, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(157, 7, 'front/images/gallery/optimized/essay-competition-12.webp', 'Essay Competition Moment #12', 'Essay Competition - Essay Competition Moment #12 in Ijero-Ekiti', '2026', 12, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(158, 7, 'front/images/gallery/optimized/essay-competition-13.webp', 'Essay Competition Moment #13', 'Essay Competition - Essay Competition Moment #13 in Ijero-Ekiti', '2026', 13, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(159, 7, 'front/images/gallery/optimized/essay-competition-14.webp', 'Essay Competition Moment #14', 'Essay Competition - Essay Competition Moment #14 in Ijero-Ekiti', '2026', 14, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(160, 7, 'front/images/gallery/optimized/essay-competition-15.webp', 'Essay Competition Moment #15', 'Essay Competition - Essay Competition Moment #15 in Ijero-Ekiti', '2026', 15, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(161, 7, 'front/images/gallery/optimized/essay-competition-16.webp', 'Essay Competition Moment #16', 'Essay Competition - Essay Competition Moment #16 in Ijero-Ekiti', '2026', 16, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(162, 8, 'front/images/gallery/optimized/girls-day-01.webp', 'Girls in Tech Session #1', 'Girls in Tech & Leadership - Girls in Tech Session #1 in Ijero-Ekiti', '2026', 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(163, 8, 'front/images/gallery/optimized/girls-day-02.webp', 'Girls in Tech Session #2', 'Girls in Tech & Leadership - Girls in Tech Session #2 in Ijero-Ekiti', '2026', 2, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(164, 8, 'front/images/gallery/optimized/girls-day-03.webp', 'Girls in Tech Session #3', 'Girls in Tech & Leadership - Girls in Tech Session #3 in Ijero-Ekiti', '2026', 3, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(165, 8, 'front/images/gallery/optimized/girls-day-04.webp', 'Girls in Tech Session #4', 'Girls in Tech & Leadership - Girls in Tech Session #4 in Ijero-Ekiti', '2026', 4, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(166, 8, 'front/images/gallery/optimized/girls-day-05.webp', 'Girls in Tech Session #5', 'Girls in Tech & Leadership - Girls in Tech Session #5 in Ijero-Ekiti', '2026', 5, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(167, 8, 'front/images/gallery/optimized/girls-day-06.webp', 'Girls in Tech Session #6', 'Girls in Tech & Leadership - Girls in Tech Session #6 in Ijero-Ekiti', '2026', 6, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(168, 8, 'front/images/gallery/optimized/girls-day-07.webp', 'Girls in Tech Session #7', 'Girls in Tech & Leadership - Girls in Tech Session #7 in Ijero-Ekiti', '2026', 7, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(169, 8, 'front/images/gallery/optimized/girls-day-08.webp', 'Girls in Tech Session #8', 'Girls in Tech & Leadership - Girls in Tech Session #8 in Ijero-Ekiti', '2026', 8, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(170, 8, 'front/images/gallery/optimized/girls-day-09.webp', 'Girls in Tech Session #9', 'Girls in Tech & Leadership - Girls in Tech Session #9 in Ijero-Ekiti', '2026', 9, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(171, 8, 'front/images/gallery/optimized/girls-day-10.webp', 'Girls in Tech Session #10', 'Girls in Tech & Leadership - Girls in Tech Session #10 in Ijero-Ekiti', '2026', 10, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(172, 8, 'front/images/gallery/optimized/girls-day-11.webp', 'Girls in Tech Session #11', 'Girls in Tech & Leadership - Girls in Tech Session #11 in Ijero-Ekiti', '2026', 11, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(173, 8, 'front/images/gallery/optimized/girls-day-12.webp', 'Girls in Tech Session #12', 'Girls in Tech & Leadership - Girls in Tech Session #12 in Ijero-Ekiti', '2026', 12, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(174, 8, 'front/images/gallery/optimized/girls-day-13.webp', 'Girls in Tech Session #13', 'Girls in Tech & Leadership - Girls in Tech Session #13 in Ijero-Ekiti', '2026', 13, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(175, 8, 'front/images/gallery/optimized/girls-day-14.webp', 'Girls in Tech Session #14', 'Girls in Tech & Leadership - Girls in Tech Session #14 in Ijero-Ekiti', '2026', 14, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(176, 8, 'front/images/gallery/optimized/girls-day-15.webp', 'Girls in Tech Session #15', 'Girls in Tech & Leadership - Girls in Tech Session #15 in Ijero-Ekiti', '2026', 15, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(177, 8, 'front/images/gallery/optimized/girls-day-16.webp', 'Girls in Tech Session #16', 'Girls in Tech & Leadership - Girls in Tech Session #16 in Ijero-Ekiti', '2026', 16, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(178, 9, 'front/images/gallery/optimized/itd-01.webp', 'ITD Festival Highlight #1', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #1 in Ijero-Ekiti', '2025', 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36');
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `year`, `sort_order`, `created_at`, `updated_at`) VALUES
(179, 9, 'front/images/gallery/optimized/itd-02.webp', 'ITD Festival Highlight #2', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #2 in Ijero-Ekiti', '2025', 2, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(180, 9, 'front/images/gallery/optimized/itd-03.webp', 'ITD Festival Highlight #3', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #3 in Ijero-Ekiti', '2025', 3, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(181, 9, 'front/images/gallery/optimized/itd-04.webp', 'ITD Festival Highlight #4', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #4 in Ijero-Ekiti', '2025', 4, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(182, 9, 'front/images/gallery/optimized/itd-05.webp', 'ITD Festival Highlight #5', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #5 in Ijero-Ekiti', '2025', 5, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(183, 9, 'front/images/gallery/optimized/itd-06.webp', 'ITD Festival Highlight #6', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #6 in Ijero-Ekiti', '2025', 6, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(184, 9, 'front/images/gallery/optimized/itd-07.webp', 'ITD Festival Highlight #7', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #7 in Ijero-Ekiti', '2025', 7, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(185, 9, 'front/images/gallery/optimized/itd-08.webp', 'ITD Festival Highlight #8', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #8 in Ijero-Ekiti', '2025', 8, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(186, 9, 'front/images/gallery/optimized/itd-09.webp', 'ITD Festival Highlight #9', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #9 in Ijero-Ekiti', '2025', 9, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(187, 9, 'front/images/gallery/optimized/itd-10.webp', 'ITD Festival Highlight #10', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #10 in Ijero-Ekiti', '2025', 10, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(188, 9, 'front/images/gallery/optimized/itd-11.webp', 'ITD Festival Highlight #11', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #11 in Ijero-Ekiti', '2025', 11, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(189, 9, 'front/images/gallery/optimized/itd-12.webp', 'ITD Festival Highlight #12', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #12 in Ijero-Ekiti', '2025', 12, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(190, 9, 'front/images/gallery/optimized/itd-13.webp', 'ITD Festival Highlight #13', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #13 in Ijero-Ekiti', '2025', 13, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(191, 9, 'front/images/gallery/optimized/itd-14.webp', 'ITD Festival Highlight #14', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #14 in Ijero-Ekiti', '2025', 14, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(192, 9, 'front/images/gallery/optimized/itd-15.webp', 'ITD Festival Highlight #15', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #15 in Ijero-Ekiti', '2025', 15, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(193, 9, 'front/images/gallery/optimized/itd-16.webp', 'ITD Festival Highlight #16', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #16 in Ijero-Ekiti', '2025', 16, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(194, 4, 'front/images/gallery/optimized/spelling-bee-01.webp', 'Spelling Bee Contestant #1', 'Spelling Bee Championship - Spelling Bee Contestant #1 in Ijero-Ekiti', '2026', 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(195, 4, 'front/images/gallery/optimized/spelling-bee-02.webp', 'Spelling Bee Contestant #2', 'Spelling Bee Championship - Spelling Bee Contestant #2 in Ijero-Ekiti', '2026', 2, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(196, 4, 'front/images/gallery/optimized/spelling-bee-03.webp', 'Spelling Bee Contestant #3', 'Spelling Bee Championship - Spelling Bee Contestant #3 in Ijero-Ekiti', '2026', 3, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(197, 4, 'front/images/gallery/optimized/spelling-bee-04.webp', 'Spelling Bee Contestant #4', 'Spelling Bee Championship - Spelling Bee Contestant #4 in Ijero-Ekiti', '2026', 4, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(198, 4, 'front/images/gallery/optimized/spelling-bee-05.webp', 'Spelling Bee Contestant #5', 'Spelling Bee Championship - Spelling Bee Contestant #5 in Ijero-Ekiti', '2026', 5, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(199, 4, 'front/images/gallery/optimized/spelling-bee-06.webp', 'Spelling Bee Contestant #6', 'Spelling Bee Championship - Spelling Bee Contestant #6 in Ijero-Ekiti', '2026', 6, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(200, 4, 'front/images/gallery/optimized/spelling-bee-07.webp', 'Spelling Bee Contestant #7', 'Spelling Bee Championship - Spelling Bee Contestant #7 in Ijero-Ekiti', '2026', 7, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(201, 4, 'front/images/gallery/optimized/spelling-bee-08.webp', 'Spelling Bee Contestant #8', 'Spelling Bee Championship - Spelling Bee Contestant #8 in Ijero-Ekiti', '2026', 8, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(202, 4, 'front/images/gallery/optimized/spelling-bee-09.webp', 'Spelling Bee Contestant #9', 'Spelling Bee Championship - Spelling Bee Contestant #9 in Ijero-Ekiti', '2026', 9, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(203, 4, 'front/images/gallery/optimized/spelling-bee-10.webp', 'Spelling Bee Contestant #10', 'Spelling Bee Championship - Spelling Bee Contestant #10 in Ijero-Ekiti', '2026', 10, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(204, 4, 'front/images/gallery/optimized/spelling-bee-11.webp', 'Spelling Bee Contestant #11', 'Spelling Bee Championship - Spelling Bee Contestant #11 in Ijero-Ekiti', '2026', 11, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(205, 4, 'front/images/gallery/optimized/spelling-bee-12.webp', 'Spelling Bee Contestant #12', 'Spelling Bee Championship - Spelling Bee Contestant #12 in Ijero-Ekiti', '2026', 12, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(206, 4, 'front/images/gallery/optimized/spelling-bee-13.webp', 'Spelling Bee Contestant #13', 'Spelling Bee Championship - Spelling Bee Contestant #13 in Ijero-Ekiti', '2026', 13, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(207, 4, 'front/images/gallery/optimized/spelling-bee-14.webp', 'Spelling Bee Contestant #14', 'Spelling Bee Championship - Spelling Bee Contestant #14 in Ijero-Ekiti', '2026', 14, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(208, 4, 'front/images/gallery/optimized/spelling-bee-15.webp', 'Spelling Bee Contestant #15', 'Spelling Bee Championship - Spelling Bee Contestant #15 in Ijero-Ekiti', '2026', 15, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(209, 4, 'front/images/gallery/optimized/spelling-bee-16.webp', 'Spelling Bee Contestant #16', 'Spelling Bee Championship - Spelling Bee Contestant #16 in Ijero-Ekiti', '2026', 16, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(210, 10, 'front/images/gallery/optimized/wdada-01.webp', 'Community Relief Outreach #1', 'WDADA Community Outreach - Community Relief Outreach #1 in Ijero-Ekiti', '2026', 1, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(211, 10, 'front/images/gallery/optimized/wdada-02.webp', 'Community Relief Outreach #2', 'WDADA Community Outreach - Community Relief Outreach #2 in Ijero-Ekiti', '2026', 2, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(212, 10, 'front/images/gallery/optimized/wdada-03.webp', 'Community Relief Outreach #3', 'WDADA Community Outreach - Community Relief Outreach #3 in Ijero-Ekiti', '2026', 3, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(213, 10, 'front/images/gallery/optimized/wdada-04.webp', 'Community Relief Outreach #4', 'WDADA Community Outreach - Community Relief Outreach #4 in Ijero-Ekiti', '2026', 4, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(214, 10, 'front/images/gallery/optimized/wdada-05.webp', 'Community Relief Outreach #5', 'WDADA Community Outreach - Community Relief Outreach #5 in Ijero-Ekiti', '2026', 5, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(215, 10, 'front/images/gallery/optimized/wdada-06.webp', 'Community Relief Outreach #6', 'WDADA Community Outreach - Community Relief Outreach #6 in Ijero-Ekiti', '2026', 6, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(216, 10, 'front/images/gallery/optimized/wdada-07.webp', 'Community Relief Outreach #7', 'WDADA Community Outreach - Community Relief Outreach #7 in Ijero-Ekiti', '2026', 7, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(217, 10, 'front/images/gallery/optimized/wdada-08.webp', 'Community Relief Outreach #8', 'WDADA Community Outreach - Community Relief Outreach #8 in Ijero-Ekiti', '2026', 8, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(218, 10, 'front/images/gallery/optimized/wdada-09.webp', 'Community Relief Outreach #9', 'WDADA Community Outreach - Community Relief Outreach #9 in Ijero-Ekiti', '2026', 9, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(219, 10, 'front/images/gallery/optimized/wdada-10.webp', 'Community Relief Outreach #10', 'WDADA Community Outreach - Community Relief Outreach #10 in Ijero-Ekiti', '2026', 10, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(220, 10, 'front/images/gallery/optimized/wdada-11.webp', 'Community Relief Outreach #11', 'WDADA Community Outreach - Community Relief Outreach #11 in Ijero-Ekiti', '2026', 11, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(221, 10, 'front/images/gallery/optimized/wdada-12.webp', 'Community Relief Outreach #12', 'WDADA Community Outreach - Community Relief Outreach #12 in Ijero-Ekiti', '2026', 12, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(222, 10, 'front/images/gallery/optimized/wdada-13.webp', 'Community Relief Outreach #13', 'WDADA Community Outreach - Community Relief Outreach #13 in Ijero-Ekiti', '2026', 13, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(223, 10, 'front/images/gallery/optimized/wdada-14.webp', 'Community Relief Outreach #14', 'WDADA Community Outreach - Community Relief Outreach #14 in Ijero-Ekiti', '2026', 14, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(224, 10, 'front/images/gallery/optimized/wdada-15.webp', 'Community Relief Outreach #15', 'WDADA Community Outreach - Community Relief Outreach #15 in Ijero-Ekiti', '2026', 15, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(225, 10, 'front/images/gallery/optimized/wdada-16.webp', 'Community Relief Outreach #16', 'WDADA Community Outreach - Community Relief Outreach #16 in Ijero-Ekiti', '2026', 16, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(226, 5, 'front/images/gallery/optimized/general-13.webp', 'Community Impact Moment #13', 'General Community & Cohorts - Community Impact Moment #13 in Ijero-Ekiti', '2024', 13, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(227, 5, 'front/images/gallery/optimized/general-14.webp', 'Community Impact Moment #14', 'General Community & Cohorts - Community Impact Moment #14 in Ijero-Ekiti', '2024', 14, '2026-10-14 22:04:36', '2026-10-14 22:04:36'),
(228, 5, 'front/images/gallery/optimized/general-15.webp', 'Community Impact Moment #15', 'General Community & Cohorts - Community Impact Moment #15 in Ijero-Ekiti', '2024', 15, '2026-10-14 22:04:36', '2026-10-14 22:04:36');
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `year`, `sort_order`, `created_at`, `updated_at`) VALUES
(229, 5, 'front/images/gallery/optimized/general-16.webp', 'Community Impact Moment #16', 'General Community & Cohorts - Community Impact Moment #16 in Ijero-Ekiti', '2024', 16, '2026-10-14 22:04:36', '2026-10-14 22:04:36');
/*!40000 ALTER TABLE `gallery_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer_applications`
--

DROP TABLE IF EXISTS `volunteer_applications`;
CREATE TABLE `volunteer_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `age_band` varchar(255) NOT NULL,
  `work_status` varchar(255) NOT NULL,
  `skills` text NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `volunteer_applications`: 0 rows (empty)

--
-- Table structure for table `partner_enquiries`
--

DROP TABLE IF EXISTS `partner_enquiries`;
CREATE TABLE `partner_enquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NULL DEFAULT NULL,
  `partnership_type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `partner_enquiries`: 0 rows (empty)

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NULL DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `contact_messages`: 0 rows (empty)

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text NULL DEFAULT NULL,
  `body` longtext NOT NULL,
  `cover_image` varchar(255) NULL DEFAULT NULL,
  `programme_id` bigint unsigned NULL DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `published_at` datetime NULL DEFAULT NULL,
  `seo_title` varchar(255) NULL DEFAULT NULL,
  `seo_description` text NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `posts_programme_id_foreign` (`programme_id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  PRIMARY KEY (`id`),
  CONSTRAINT `posts_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `posts`: 0 rows (empty)

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE `newsletter_subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `subscribed_at` datetime NOT NULL,
  `unsubscribed_at` datetime NULL DEFAULT NULL,
  `unsubscribe_token` varchar(64) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  UNIQUE KEY `newsletter_subscribers_unsubscribe_token_unique` (`unsubscribe_token`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `newsletter_subscribers`: 0 rows (empty)

--
-- Table structure for table `page_sections`
--

DROP TABLE IF EXISTS `page_sections`;
CREATE TABLE `page_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_key` varchar(255) NOT NULL,
  `section_key` varchar(255) NOT NULL,
  `headline` varchar(255) NULL DEFAULT NULL,
  `subheadline` varchar(255) NULL DEFAULT NULL,
  `body_text` text NULL DEFAULT NULL,
  `image_path` varchar(255) NULL DEFAULT NULL,
  `button_text` varchar(255) NULL DEFAULT NULL,
  `button_url` varchar(255) NULL DEFAULT NULL,
  `meta_data` longtext NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `page_sections_page_key_index` (`page_key`),
  UNIQUE KEY `page_sections_page_key_section_key_unique` (`page_key`, `section_key`),
  KEY `page_sections_section_key_index` (`section_key`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_sections` (22 rows)
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` (`id`, `page_key`, `section_key`, `headline`, `subheadline`, `body_text`, `image_path`, `button_text`, `button_url`, `meta_data`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'home', 'hero', 'Addressing poverty through quality education and skills acquisition', 'Dzuels Educational Foundation', 'Empowering indigent Nigerian children, youth, and educators with free digital literacy, school support, leadership mentoring, and community welfare.', 'front/images/home_1.webp', 'Donate Now', '/donate', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'home', 'who_we_are', 'Dedicated to Transforming Lives and Communities Through Education', 'Who We Are', 'Dzuels Educational Foundation is a non-profit organisation dedicated to addressing poverty by providing quality education and skills acquisition for indigent children and youth across Nigeria. Founded in 2015, we bridge educational inequality by equipping underserved communities with modern digital skills, literacy support, and vital infrastructure.\n\nOur initiatives ensure that children from low-income backgrounds have the tools, guidance, and environments required to thrive in a rapidly changing world.', 'front/images/about_1.webp', 'Learn More About Us', '/about', NULL, 2, '2026-10-11 16:36:13', '2026-10-11 17:10:32'),
(3, 'home', 'vision_mission', 'Our Core Purpose & Guiding Principles', 'Vision & Mission', 'Vision: An equitable society where every child, irrespective of socio-economic background, has access to quality education, modern digital literacy, and the opportunity to achieve their highest human potential.\r\n\r\nMission: To dismantle educational barriers for indigent youth in Nigeria by deploying digital classrooms, scholarship support, literacy outreach, and mentorship that spark lifelong empowerment.', 'front/images/gallery/optimized/famoyinka-08.webp', NULL, NULL, NULL, 3, '2026-10-11 16:36:13', '2026-10-13 20:19:06'),
(4, 'home', 'values', 'Our DRNICER Core Values', 'Guiding Principles', 'Dynamism, Resilience, Novelty, Integrity, Compassion, Excellence, Responsibility — these principles define every classroom we build and partnership we form.', NULL, NULL, NULL, NULL, 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(5, 'home', 'causes', 'Holistic support for youth & community development', 'Our 6 Impact Causes', 'We combine direct education sponsorship with skills acquisition, advocacy, family stability, transformative leadership, and community health.', 'front/images/cause.webp', NULL, NULL, '{"tagline":"AN INTEGRATED APPROACH TO THE TOTAL WELL BEING OF THE CHILD","pillars":[{"title":"Education Support","ministry":"Paul\'s academy","description":"Paying school fees, supplying curriculum textbooks, notebooks, uniforms, and educational kits to keep children in classrooms.","icon":"\\ud83c\\udf93"},{"title":"Skills Acquisition","ministry":"Peter\'s net","description":"Hands-on digital literacy, computer coding, and practical vocational training equipping youth with 21st-century self-reliance.","icon":"\\ud83d\\udcbb"},{"title":"Advocacy","ministry":"Deborah congress","description":"Championing child protection, education policy awareness, equal opportunity for indigent girls, and youth rights.","icon":"\\ud83d\\udce2"},{"title":"Feeding families","ministry":"Naomi\'s kitchen","description":"Targeted nutritional relief, food assistance, and hunger alleviation ensuring indigent pupils have the energy to learn.","icon":"\\ud83c\\udf72"},{"title":"Transformative leadership Training","ministry":"Priscilla\'s upper room","description":"Values-driven mentorship, ethical leadership training, and mindset transformation for emerging community leaders.","icon":"\\ud83c\\udf1f"},{"title":"Health awareness and support","ministry":"Luke\'s Tent","description":"Health education, medical screenings, personal hygiene kits, and basic medical support for vulnerable children and families.","icon":"\\ud83e\\ude7a"}]}', 5, '2026-10-11 16:36:13', '2026-10-13 16:50:56'),
(6, 'home', 'featured_project', 'Ijero-Ekiti Digital Infrastructure and Literacy Initiative', 'Flagship Project', 'Establishing modern computer laboratories and solar-powered learning facilities in public schools across Ijero-Ekiti, equipping over 165 students with practical coding and computer skills.', 'front/images/dzuelsCollage.webp', 'Explore Our Programmes', '/programmes', NULL, 7, '2026-10-11 16:36:13', '2026-10-13 18:00:55'),
(7, 'home', 'cta', 'Join Us in Shaping the Future of Nigeria\'s Youth', 'Take Action Today', 'Whether through corporate sponsorship, volunteer mentoring, or financial donations, your partnership empowers the next generation.', NULL, 'Get Involved Today', '/get-involved', NULL, 8, '2026-10-11 16:36:13', '2026-10-13 18:00:55'),
(8, 'about', 'hero', 'A Decade of Impact, Education & Hope', 'About Dzuels Educational Foundation', 'Founded in 2015, Dzuels Educational Foundation has grown from a local literacy initiative in Ekiti State into an impactful non-profit organisation driving digital empowerment and community welfare across Nigeria.', 'front/images/gallery/optimized/general-01.webp', 'Support Our Mission', '/donate', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 17:10:32'),
(9, 'about', 'story', 'Our 10-Year Journey of Community Empowerment', 'Our Story', 'What started as an urgent response to learning resource shortages in rural schools has evolved into a comprehensive educational movement.\n\nOver the past ten years, we have trained hundreds of young Nigerians in modern computational thinking, distributed essential learning supplies, and renovated learning spaces in public secondary and primary schools.', 'front/images/about_1.webp', NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 17:10:32'),
(10, 'about', 'approach', 'Grassroots Engagement & Sustainable Innovation', 'Our Approach', 'We partner directly with schools, community leaders, and local governments to identify critical gaps and deploy sustainable, high-impact educational solutions tailored to underserved youth.', NULL, NULL, NULL, NULL, 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(11, 'about', 'leadership', 'Board of Trustees, Management & Advisory Board', 'Foundation Governance', 'Guided by dedicated educators, technologists, and community leaders committed to absolute transparency, integrity, and social stewardship.', NULL, NULL, NULL, NULL, 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(12, 'programmes', 'hero', 'Sustainable Interventions Empowering Students and Schools', 'Our Programmes', 'Explore our targeted initiatives focused on digital literacy, STEM training, academic competitions, and teacher development across Ekiti State.', 'front/images/gallery/optimized/itd-2025-05.webp', 'Partner on a Programme', '/get-involved', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 17:10:32'),
(13, 'programmes', 'overview', 'Targeted Initiatives for Lasting Community Impact', 'Empowering Next-Gen Leaders', 'Every programme is designed with measurable outcomes: improving student academic performance, fostering digital confidence, and preparing youth for future careers.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(14, 'contact', 'hero', 'We Are Always Available to Connect With You', 'Get in Touch', 'Have a question regarding our youth literacy initiatives, school computer labs, or community partnerships? Send us a message or visit our headquarters in Ijero-Ekiti.', 'front/images/gallery/optimized/general-05.webp', NULL, NULL, NULL, 1, '2026-10-11 16:36:13', '2026-10-13 18:00:55'),
(15, 'contact', 'office', 'Our Head Office & Learning Centers', 'Visit Our Office', 'Our doors are open to students, teachers, volunteers, and partners working towards educational equity.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-13 18:00:55'),
(16, 'donate', 'hero', 'Every Contribution Changes a Child\'s Future', 'Invest in Education', '100% of your generous gifts directly fund classroom infrastructure, student learning kits, and free digital training for indigent youth.', 'front/images/gallery/optimized/reading-01.webp', 'Give Online via Flutterwave', 'https://flutterwave.com/pay/j0byxgjs0nma', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 17:10:32'),
(17, 'donate', 'where_your_gift_goes', 'Transparent Allocation of Every Naira', 'Where Your Gift Goes', 'We ensure absolute financial accountability. Donations support digital lab computers, solar power backups, school supplies, scholarship grants, and teacher enablement.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(18, 'get_involved', 'hero', 'Lend Your Skills, Time, or Resources to Transform Education', 'Get Involved', 'Join our network of passionate volunteers and visionary corporate partners making an indelible mark on Nigerian education.', 'front/images/gallery/optimized/general-02.webp', 'Apply to Volunteer', '#volunteer-form', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 17:10:32'),
(19, 'get_involved', 'intro', 'Volunteer or Partner With Dzuels', 'Create Lasting Change', 'Whether you want to mentor young coders during Ijero Tech Day or sponsor a digital laboratory in a public school, there is a place for you in our mission.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(20, 'contact', 'form', 'Send Us a Message', 'Online Inquiry', 'Fill out the form below and a representative from Dzuels Educational Foundation will get in touch with you promptly.', NULL, NULL, NULL, NULL, 3, '2026-10-13 14:39:14', '2026-10-13 14:39:14'),
(21, 'contact', 'map', 'Headquarters Location', 'Physical visits by appointment are warmly welcomed. Please call in advance so our field coordinators can assist with your schedule.', 'Our central educational center and community operations are based in Ijero-Ekiti, Ekiti State, Nigeria.', NULL, NULL, NULL, NULL, 4, '2026-10-13 14:39:14', '2026-10-13 14:39:14'),
(22, 'home', 'famoyinka_library', 'Famoyinka Memorial Children\'s Library', 'Permanent Literacy Sanctuary', 'An active, community-anchored hub dedicated to childhood literacy, creative self-expression, and transformational leadership in Southwest Nigeria.', NULL, 'Explore Library Programme', '/programmes/famoyinka-memorial-library', '{"photos":[{"image":"front\\/images\\/gallery\\/optimized\\/famoyinka-01.webp","label":"Free Book Loans","alt":"Student selecting books at Famoyinka Memorial Children\'s Library"},{"image":"front\\/images\\/techday.webp","label":"Creative Arts","alt":"Creative watercolor art workshop"},{"image":null,"label":"Leadership Games","alt":"Leadership teamwork games and sports"},{"image":"uploads\\/media\\/1791915201_LilvyZhc6aTE.jpg","label":"Ikogosi Excursion","alt":"Ikogosi Warm Springs educational excursion"}]}', 6, '2026-10-13 18:00:55', '2026-10-14 20:46:16');
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_items`
--

DROP TABLE IF EXISTS `media_items`;
CREATE TABLE `media_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` bigint unsigned NOT NULL DEFAULT 0,
  `mime_type` varchar(255) NOT NULL DEFAULT 'image/webp',
  `width` smallint NULL DEFAULT NULL,
  `height` smallint NULL DEFAULT NULL,
  `title` varchar(255) NULL DEFAULT NULL,
  `caption` text NULL DEFAULT NULL,
  `alt_text` text NULL DEFAULT NULL,
  `source_type` varchar(30) NOT NULL DEFAULT 'general',
  `gallery_album_id` bigint unsigned NULL DEFAULT NULL,
  `gallery_photo_id` bigint unsigned NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `media_items_file_path_index` (`file_path`),
  KEY `media_items_gallery_album_id_foreign` (`gallery_album_id`),
  KEY `media_items_gallery_photo_id_foreign` (`gallery_photo_id`),
  KEY `media_items_source_type_index` (`source_type`),
  KEY `media_items_title_index` (`title`),
  PRIMARY KEY (`id`),
  CONSTRAINT `media_items_gallery_album_id_foreign` FOREIGN KEY (`gallery_album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `media_items_gallery_photo_id_foreign` FOREIGN KEY (`gallery_photo_id`) REFERENCES `gallery_photos` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media_items` (284 rows)
--

LOCK TABLES `media_items` WRITE;
/*!40000 ALTER TABLE `media_items` DISABLE KEYS */;
INSERT INTO `media_items` (`id`, `filename`, `file_path`, `file_size`, `mime_type`, `width`, `height`, `title`, `caption`, `alt_text`, `source_type`, `gallery_album_id`, `gallery_photo_id`, `created_at`, `updated_at`) VALUES
(1, '1792010291_4jrTAk5Uhury.webp', 'uploads/media/1792010291_4jrTAk5Uhury.webp', 157986, 'image/webp', 1920, 1280, 'Img 0026', NULL, 'Img 0026', 'general', NULL, NULL, '2026-10-14 20:38:13', '2026-10-14 20:38:13'),
(2, '1792010294_uVvaCs2V49yj.webp', 'uploads/media/1792010294_uVvaCs2V49yj.webp', 158704, 'image/webp', 1920, 1280, 'Img 0027', NULL, 'Img 0027', 'general', NULL, NULL, '2026-10-14 20:38:16', '2026-10-14 20:38:16'),
(3, '1792010460_LufIi40Sb2dX.webp', 'uploads/media/1792010460_LufIi40Sb2dX.webp', 152988, 'image/webp', 1920, 1280, 'Img 0037', NULL, 'Img 0037', 'general', NULL, NULL, '2026-10-14 20:41:01', '2026-10-14 20:41:01'),
(4, 'itd-2025-01.webp', 'front/images/gallery/optimized/itd-2025-01.webp', 50918, 'image/webp', 1280, 853, 'ITD 2025 Highlight #1', 'ITD 2025 Highlight #1', 'ITD 2025 - ITD 2025 Highlight #1 in Ijero-Ekiti', 'gallery', 1, 1, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(5, 'itd-2025-02.webp', 'front/images/gallery/optimized/itd-2025-02.webp', 51896, 'image/webp', 1280, 853, 'ITD 2025 Highlight #2', 'ITD 2025 Highlight #2', 'ITD 2025 - ITD 2025 Highlight #2 in Ijero-Ekiti', 'gallery', 1, 2, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(6, 'itd-2025-03.webp', 'front/images/gallery/optimized/itd-2025-03.webp', 71524, 'image/webp', 1280, 853, 'ITD 2025 Highlight #3', 'ITD 2025 Highlight #3', 'ITD 2025 - ITD 2025 Highlight #3 in Ijero-Ekiti', 'gallery', 1, 3, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(7, 'itd-2025-04.webp', 'front/images/gallery/optimized/itd-2025-04.webp', 60314, 'image/webp', 1280, 853, 'ITD 2025 Highlight #4', 'ITD 2025 Highlight #4', 'ITD 2025 - ITD 2025 Highlight #4 in Ijero-Ekiti', 'gallery', 1, 4, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(8, 'itd-2025-05.webp', 'front/images/gallery/optimized/itd-2025-05.webp', 113536, 'image/webp', 1280, 723, 'ITD 2025 Highlight #5', 'ITD 2025 Highlight #5', 'ITD 2025 - ITD 2025 Highlight #5 in Ijero-Ekiti', 'gallery', 1, 5, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(9, 'itd-2025-06.webp', 'front/images/gallery/optimized/itd-2025-06.webp', 94202, 'image/webp', 1260, 900, 'ITD 2025 Highlight #6', 'ITD 2025 Highlight #6', 'ITD 2025 - ITD 2025 Highlight #6 in Ijero-Ekiti', 'gallery', 1, 6, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(10, 'itd-2025-07.webp', 'front/images/gallery/optimized/itd-2025-07.webp', 108682, 'image/webp', 1260, 900, 'ITD 2025 Highlight #7', 'ITD 2025 Highlight #7', 'ITD 2025 - ITD 2025 Highlight #7 in Ijero-Ekiti', 'gallery', 1, 7, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(11, 'itd-2025-08.webp', 'front/images/gallery/optimized/itd-2025-08.webp', 111420, 'image/webp', 1280, 853, 'ITD 2025 Highlight #8', 'ITD 2025 Highlight #8', 'ITD 2025 - ITD 2025 Highlight #8 in Ijero-Ekiti', 'gallery', 1, 8, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(12, 'itd-2025-09.webp', 'front/images/gallery/optimized/itd-2025-09.webp', 49628, 'image/webp', 1260, 900, 'ITD 2025 Highlight #9', 'ITD 2025 Highlight #9', 'ITD 2025 - ITD 2025 Highlight #9 in Ijero-Ekiti', 'gallery', 1, 9, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(13, 'itd-2025-10.webp', 'front/images/gallery/optimized/itd-2025-10.webp', 77328, 'image/webp', 1260, 900, 'ITD 2025 Highlight #10', 'ITD 2025 Highlight #10', 'ITD 2025 - ITD 2025 Highlight #10 in Ijero-Ekiti', 'gallery', 1, 10, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(14, 'itd-2025-11.webp', 'front/images/gallery/optimized/itd-2025-11.webp', 105446, 'image/webp', 1260, 900, 'ITD 2025 Highlight #11', 'ITD 2025 Highlight #11', 'ITD 2025 - ITD 2025 Highlight #11 in Ijero-Ekiti', 'gallery', 1, 11, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(15, 'itd-2025-12.webp', 'front/images/gallery/optimized/itd-2025-12.webp', 89360, 'image/webp', 1260, 900, 'ITD 2025 Highlight #12', 'ITD 2025 Highlight #12', 'ITD 2025 - ITD 2025 Highlight #12 in Ijero-Ekiti', 'gallery', 1, 12, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(16, 'itd-2025-13.webp', 'front/images/gallery/optimized/itd-2025-13.webp', 53546, 'image/webp', 1260, 900, 'ITD 2025 Highlight #13', 'ITD 2025 Highlight #13', 'ITD 2025 - ITD 2025 Highlight #13 in Ijero-Ekiti', 'gallery', 1, 13, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(17, 'itd-2025-14.webp', 'front/images/gallery/optimized/itd-2025-14.webp', 78000, 'image/webp', 1260, 900, 'ITD 2025 Highlight #14', 'ITD 2025 Highlight #14', 'ITD 2025 - ITD 2025 Highlight #14 in Ijero-Ekiti', 'gallery', 1, 14, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(18, 'itd-2025-15.webp', 'front/images/gallery/optimized/itd-2025-15.webp', 64506, 'image/webp', 1280, 853, 'ITD 2025 Highlight #15', 'ITD 2025 Highlight #15', 'ITD 2025 - ITD 2025 Highlight #15 in Ijero-Ekiti', 'gallery', 1, 15, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(19, 'itd-2024-01.webp', 'front/images/gallery/optimized/itd-2024-01.webp', 53280, 'image/webp', 1280, 853, 'ITD 2024 Highlight #1', 'ITD 2024 Highlight #1', 'ITD 2024 - ITD 2024 Highlight #1 in Ijero-Ekiti', 'gallery', 2, 26, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(20, 'itd-2024-02.webp', 'front/images/gallery/optimized/itd-2024-02.webp', 114532, 'image/webp', 1280, 853, 'ITD 2024 Highlight #2', 'ITD 2024 Highlight #2', 'ITD 2024 - ITD 2024 Highlight #2 in Ijero-Ekiti', 'gallery', 2, 27, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(21, 'itd-2024-03.webp', 'front/images/gallery/optimized/itd-2024-03.webp', 62078, 'image/webp', 1280, 853, 'ITD 2024 Highlight #3', 'ITD 2024 Highlight #3', 'ITD 2024 - ITD 2024 Highlight #3 in Ijero-Ekiti', 'gallery', 2, 28, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(22, 'itd-2024-04.webp', 'front/images/gallery/optimized/itd-2024-04.webp', 55300, 'image/webp', 1280, 853, 'ITD 2024 Highlight #4', 'ITD 2024 Highlight #4', 'ITD 2024 - ITD 2024 Highlight #4 in Ijero-Ekiti', 'gallery', 2, 29, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(23, 'itd-2024-05.webp', 'front/images/gallery/optimized/itd-2024-05.webp', 48234, 'image/webp', 1280, 853, 'ITD 2024 Highlight #5', 'ITD 2024 Highlight #5', 'ITD 2024 - ITD 2024 Highlight #5 in Ijero-Ekiti', 'gallery', 2, 30, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(24, 'itd-2024-06.webp', 'front/images/gallery/optimized/itd-2024-06.webp', 45650, 'image/webp', 1280, 853, 'ITD 2024 Highlight #6', 'ITD 2024 Highlight #6', 'ITD 2024 - ITD 2024 Highlight #6 in Ijero-Ekiti', 'gallery', 2, 31, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(25, 'itd-2024-07.webp', 'front/images/gallery/optimized/itd-2024-07.webp', 79542, 'image/webp', 1280, 853, 'ITD 2024 Highlight #7', 'ITD 2024 Highlight #7', 'ITD 2024 - ITD 2024 Highlight #7 in Ijero-Ekiti', 'gallery', 2, 32, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(26, 'itd-2024-08.webp', 'front/images/gallery/optimized/itd-2024-08.webp', 69850, 'image/webp', 1280, 853, 'ITD 2024 Highlight #8', 'ITD 2024 Highlight #8', 'ITD 2024 - ITD 2024 Highlight #8 in Ijero-Ekiti', 'gallery', 2, 33, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(27, 'itd-2024-09.webp', 'front/images/gallery/optimized/itd-2024-09.webp', 103388, 'image/webp', 1280, 853, 'ITD 2024 Highlight #9', 'ITD 2024 Highlight #9', 'ITD 2024 - ITD 2024 Highlight #9 in Ijero-Ekiti', 'gallery', 2, 34, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(28, 'itd-2024-10.webp', 'front/images/gallery/optimized/itd-2024-10.webp', 62228, 'image/webp', 1280, 853, 'ITD 2024 Highlight #10', 'ITD 2024 Highlight #10', 'ITD 2024 - ITD 2024 Highlight #10 in Ijero-Ekiti', 'gallery', 2, 35, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(29, 'itd-2024-11.webp', 'front/images/gallery/optimized/itd-2024-11.webp', 86854, 'image/webp', 1280, 853, 'ITD 2024 Highlight #11', 'ITD 2024 Highlight #11', 'ITD 2024 - ITD 2024 Highlight #11 in Ijero-Ekiti', 'gallery', 2, 36, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(30, 'itd-2024-12.webp', 'front/images/gallery/optimized/itd-2024-12.webp', 89584, 'image/webp', 1280, 853, 'ITD 2024 Highlight #12', 'ITD 2024 Highlight #12', 'ITD 2024 - ITD 2024 Highlight #12 in Ijero-Ekiti', 'gallery', 2, 37, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(31, 'reading-01.webp', 'front/images/gallery/optimized/reading-01.webp', 126348, 'image/webp', 1280, 958, 'Reading Competition Moment #1 (2024)', 'Reading Competition Moment #1', 'Reading & Children’s Library - Reading Competition Moment #1 in Ijero-Ekiti', 'gallery', 3, 51, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(32, 'reading-02.webp', 'front/images/gallery/optimized/reading-02.webp', 92838, 'image/webp', 1080, 808, 'Reading Competition Moment #2 (2024)', 'Reading Competition Moment #2', 'Reading & Children’s Library - Reading Competition Moment #2 in Ijero-Ekiti', 'gallery', 3, 52, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(33, 'reading-03.webp', 'front/images/gallery/optimized/reading-03.webp', 116960, 'image/webp', 1600, 1067, 'Reading Competition Moment #3 (2024)', 'Reading Competition Moment #3', 'Reading & Children’s Library - Reading Competition Moment #3 in Ijero-Ekiti', 'gallery', 3, 53, '2026-10-14 20:41:45', '2026-10-14 22:04:42'),
(34, 'reading-04.webp', 'front/images/gallery/optimized/reading-04.webp', 99204, 'image/webp', 1600, 1067, 'Reading Competition Moment #4 (2024)', 'Reading Competition Moment #4', 'Reading & Children’s Library - Reading Competition Moment #4 in Ijero-Ekiti', 'gallery', 3, 54, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(35, 'reading-05.webp', 'front/images/gallery/optimized/reading-05.webp', 117674, 'image/webp', 1600, 1067, 'Reading Competition Moment #5 (2024)', 'Reading Competition Moment #5', 'Reading & Children’s Library - Reading Competition Moment #5 in Ijero-Ekiti', 'gallery', 3, 55, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(36, 'reading-06.webp', 'front/images/gallery/optimized/reading-06.webp', 111956, 'image/webp', 1067, 1600, 'Reading Competition Moment #6 (2024)', 'Reading Competition Moment #6', 'Reading & Children’s Library - Reading Competition Moment #6 in Ijero-Ekiti', 'gallery', 3, 56, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(37, 'reading-07.webp', 'front/images/gallery/optimized/reading-07.webp', 183730, 'image/webp', 1600, 1067, 'Reading Competition Moment #7 (2024)', 'Reading Competition Moment #7', 'Reading & Children’s Library - Reading Competition Moment #7 in Ijero-Ekiti', 'gallery', 3, 57, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(38, 'reading-08.webp', 'front/images/gallery/optimized/reading-08.webp', 184268, 'image/webp', 1600, 1067, 'Reading Competition Moment #8 (2024)', 'Reading Competition Moment #8', 'Reading & Children’s Library - Reading Competition Moment #8 in Ijero-Ekiti', 'gallery', 3, 58, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(39, 'spelling-01.webp', 'front/images/gallery/optimized/spelling-01.webp', 85378, 'image/webp', 1200, 900, 'Spelling Bee Contestant #1 (2024)', 'Spelling Bee Contestant #1', 'Spelling Bee - Spelling Bee Contestant #1 in Ijero-Ekiti', 'gallery', 4, 76, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(40, 'spelling-02.webp', 'front/images/gallery/optimized/spelling-02.webp', 88476, 'image/webp', 1200, 900, 'Spelling Bee Contestant #2 (2024)', 'Spelling Bee Contestant #2', 'Spelling Bee - Spelling Bee Contestant #2 in Ijero-Ekiti', 'gallery', 4, 77, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(41, 'spelling-03.webp', 'front/images/gallery/optimized/spelling-03.webp', 86868, 'image/webp', 1200, 900, 'Spelling Bee Contestant #3 (2024)', 'Spelling Bee Contestant #3', 'Spelling Bee - Spelling Bee Contestant #3 in Ijero-Ekiti', 'gallery', 4, 78, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(42, 'spelling-04.webp', 'front/images/gallery/optimized/spelling-04.webp', 66860, 'image/webp', 1200, 900, 'Spelling Bee Contestant #4 (2024)', 'Spelling Bee Contestant #4', 'Spelling Bee - Spelling Bee Contestant #4 in Ijero-Ekiti', 'gallery', 4, 79, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(43, 'spelling-05.webp', 'front/images/gallery/optimized/spelling-05.webp', 48072, 'image/webp', 1200, 900, 'Spelling Bee Contestant #5 (2024)', 'Spelling Bee Contestant #5', 'Spelling Bee - Spelling Bee Contestant #5 in Ijero-Ekiti', 'gallery', 4, 80, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(44, 'spelling-06.webp', 'front/images/gallery/optimized/spelling-06.webp', 45698, 'image/webp', 1200, 900, 'Spelling Bee Contestant #6 (2024)', 'Spelling Bee Contestant #6', 'Spelling Bee - Spelling Bee Contestant #6 in Ijero-Ekiti', 'gallery', 4, 81, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(45, 'spelling-07.webp', 'front/images/gallery/optimized/spelling-07.webp', 91828, 'image/webp', 1280, 853, 'Spelling Bee Contestant #7 (2024)', 'Spelling Bee Contestant #7', 'Spelling Bee - Spelling Bee Contestant #7 in Ijero-Ekiti', 'gallery', 4, 82, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(46, 'spelling-08.webp', 'front/images/gallery/optimized/spelling-08.webp', 72802, 'image/webp', 1280, 853, 'Spelling Bee Contestant #8 (2024)', 'Spelling Bee Contestant #8', 'Spelling Bee - Spelling Bee Contestant #8 in Ijero-Ekiti', 'gallery', 4, 83, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(47, 'spelling-09.webp', 'front/images/gallery/optimized/spelling-09.webp', 98954, 'image/webp', 1280, 853, 'Spelling Bee Contestant #9 (2024)', 'Spelling Bee Contestant #9', 'Spelling Bee - Spelling Bee Contestant #9 in Ijero-Ekiti', 'gallery', 4, 84, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(48, 'spelling-10.webp', 'front/images/gallery/optimized/spelling-10.webp', 99212, 'image/webp', 1280, 853, 'Spelling Bee Contestant #10 (2024)', 'Spelling Bee Contestant #10', 'Spelling Bee - Spelling Bee Contestant #10 in Ijero-Ekiti', 'gallery', 4, 85, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(49, 'general-01.webp', 'front/images/gallery/optimized/general-01.webp', 180346, 'image/webp', 1600, 1067, 'Community Impact Moment #1 (2024)', 'Community Impact Moment #1', 'General Community & Cohorts - Community Impact Moment #1 in Ijero-Ekiti', 'gallery', 5, 101, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(50, 'general-02.webp', 'front/images/gallery/optimized/general-02.webp', 48096, 'image/webp', 1600, 900, 'Community Impact Moment #2 (2024)', 'Community Impact Moment #2', 'General Community & Cohorts - Community Impact Moment #2 in Ijero-Ekiti', 'gallery', 5, 102, '2026-10-14 20:41:45', '2026-10-14 22:04:43');
INSERT INTO `media_items` (`id`, `filename`, `file_path`, `file_size`, `mime_type`, `width`, `height`, `title`, `caption`, `alt_text`, `source_type`, `gallery_album_id`, `gallery_photo_id`, `created_at`, `updated_at`) VALUES
(51, 'general-03.webp', 'front/images/gallery/optimized/general-03.webp', 119530, 'image/webp', 1360, 907, 'Community Impact Moment #3 (2024)', 'Community Impact Moment #3', 'General Community & Cohorts - Community Impact Moment #3 in Ijero-Ekiti', 'gallery', 5, 103, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(52, 'general-04.webp', 'front/images/gallery/optimized/general-04.webp', 133206, 'image/webp', 1360, 907, 'Community Impact Moment #4 (2024)', 'Community Impact Moment #4', 'General Community & Cohorts - Community Impact Moment #4 in Ijero-Ekiti', 'gallery', 5, 104, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(53, 'general-05.webp', 'front/images/gallery/optimized/general-05.webp', 119506, 'image/webp', 1600, 1067, 'Community Impact Moment #5 (2024)', 'Community Impact Moment #5', 'General Community & Cohorts - Community Impact Moment #5 in Ijero-Ekiti', 'gallery', 5, 105, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(54, 'general-06.webp', 'front/images/gallery/optimized/general-06.webp', 108748, 'image/webp', 1600, 1067, 'Community Impact Moment #6 (2024)', 'Community Impact Moment #6', 'General Community & Cohorts - Community Impact Moment #6 in Ijero-Ekiti', 'gallery', 5, 106, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(55, 'general-07.webp', 'front/images/gallery/optimized/general-07.webp', 157634, 'image/webp', 1067, 1600, 'Community Impact Moment #7 (2024)', 'Community Impact Moment #7', 'General Community & Cohorts - Community Impact Moment #7 in Ijero-Ekiti', 'gallery', 5, 107, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(56, 'general-08.webp', 'front/images/gallery/optimized/general-08.webp', 57276, 'image/webp', 1067, 1600, 'Community Impact Moment #8 (2024)', 'Community Impact Moment #8', 'General Community & Cohorts - Community Impact Moment #8 in Ijero-Ekiti', 'gallery', 5, 108, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(57, 'general-09.webp', 'front/images/gallery/optimized/general-09.webp', 101806, 'image/webp', 1600, 1067, 'Community Impact Moment #9 (2024)', 'Community Impact Moment #9', 'General Community & Cohorts - Community Impact Moment #9 in Ijero-Ekiti', 'gallery', 5, 109, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(58, 'general-10.webp', 'front/images/gallery/optimized/general-10.webp', 86406, 'image/webp', 1067, 1600, 'Community Impact Moment #10 (2024)', 'Community Impact Moment #10', 'General Community & Cohorts - Community Impact Moment #10 in Ijero-Ekiti', 'gallery', 5, 110, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(59, 'general-11.webp', 'front/images/gallery/optimized/general-11.webp', 102092, 'image/webp', 1067, 1600, 'Community Impact Moment #11 (2024)', 'Community Impact Moment #11', 'General Community & Cohorts - Community Impact Moment #11 in Ijero-Ekiti', 'gallery', 5, 111, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(60, 'general-12.webp', 'front/images/gallery/optimized/general-12.webp', 154438, 'image/webp', 1360, 907, 'Community Impact Moment #12 (2024)', 'Community Impact Moment #12', 'General Community & Cohorts - Community Impact Moment #12 in Ijero-Ekiti', 'gallery', 5, 112, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(61, 'famoyinka-01.webp', 'front/images/gallery/optimized/famoyinka-01.webp', 126348, 'image/webp', 1280, 958, 'Children\'s Book Lending & Fiction Shelf (2024)', 'Children\'s Book Lending & Fiction Shelf', 'Student selecting books at Famoyinka Memorial Children\'s Library', 'gallery', 6, 126, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(62, 'famoyinka-08.webp', 'front/images/gallery/optimized/famoyinka-08.webp', 71546, 'image/webp', 1600, 1067, 'Holistic Education Support, Arts & Values Pillars (2024)', 'Holistic Education Support, Arts & Values Pillars', 'Foundation education support and values curriculum', 'gallery', 6, 133, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(63, '1791914698_iuIliqYsTVTp.jpg', 'uploads/gallery/photos/1791914698_iuIliqYsTVTp.jpg', 0, 'image/webp', NULL, NULL, 'Famoyinka Memorial Library (2026)', NULL, 'Famoyinka Memorial Library', 'gallery', 6, 138, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(64, '1791920685_ocrrwda9tM4N.webp', 'uploads/gallery/photos/1791920685_ocrrwda9tM4N.webp', 0, 'image/webp', NULL, NULL, 'ITD 2024 (2026)', NULL, 'ITD 2024', 'gallery', 2, 139, '2026-10-14 20:41:45', '2026-10-14 22:04:43'),
(65, '1791920928_lrJ5GNpD4uhy.webp', 'uploads/media/1791920928_lrJ5GNpD4uhy.webp', 124532, 'image/webp', 1920, 1280, '1791920928 Lrj5Gnpd4Uhy', NULL, '1791920928 Lrj5Gnpd4Uhy', 'general', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(66, '1792002964_zqFaRrezQN0L.webp', 'uploads/media/1792002964_zqFaRrezQN0L.webp', 104734, 'image/webp', 1920, 1280, '1792002964 Zqfarrezqn0L', NULL, '1792002964 Zqfarrezqn0L', 'general', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(67, '1792002973_UdX09JtmDlMc.webp', 'uploads/media/1792002973_UdX09JtmDlMc.webp', 179314, 'image/webp', 1920, 1280, '1792002973 Udx09Jtmdlmc', NULL, '1792002973 Udx09Jtmdlmc', 'general', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(68, 'SAA.jpg', 'front/images/SAA.jpg', 5903, 'image/jpeg', 127, 58, 'Saa', NULL, 'Saa', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(69, 'SAA.png', 'front/images/SAA.png', 2504, 'image/png', 90, 28, 'Saa', NULL, 'Saa', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(70, 'SAA.webp', 'front/images/SAA.webp', 1242, 'image/webp', 127, 58, 'Saa', NULL, 'Saa', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(71, 'STEAM.jpg', 'front/images/STEAM.jpg', 297730, 'image/jpeg', 1920, 1080, 'Steam', NULL, 'Steam', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(72, 'about.jpg', 'front/images/about.jpg', 32090, 'image/jpeg', 739, 900, 'About', NULL, 'About', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(73, 'about_1.jpg', 'front/images/about_1.jpg', 148797, 'image/jpeg', 1200, 800, 'About 1', NULL, 'About 1', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(74, 'about_1.webp', 'front/images/about_1.webp', 58992, 'image/webp', 1200, 800, 'About 1', NULL, 'About 1', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(75, 'adeyemi.jpg', 'front/images/adeyemi.jpg', 54715, 'image/jpeg', 1080, 1680, 'Adeyemi', NULL, 'Adeyemi', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(76, 'anna.jpg', 'front/images/anna.jpg', 86759, 'image/jpeg', 540, 1204, 'Anna', NULL, 'Anna', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(77, 'ascend.jpg', 'front/images/ascend.jpg', 24589, 'image/jpeg', 307, 138, 'Ascend', NULL, 'Ascend', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(78, 'ascend.webp', 'front/images/ascend.webp', 3414, 'image/webp', 307, 138, 'Ascend', NULL, 'Ascend', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(79, 'bolaji.jpeg', 'front/images/bolaji.jpeg', 11339, 'image/jpeg', 320, 320, 'Bolaji', NULL, 'Bolaji', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(80, 'bolaji.jpg', 'front/images/bolaji.jpg', 60150, 'image/jpeg', 1080, 1436, 'Bolaji', NULL, 'Bolaji', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(81, 'cause.jpg', 'front/images/cause.jpg', 59279, 'image/jpeg', 1080, 810, 'Cause', NULL, 'Cause', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(82, 'cause.png', 'front/images/cause.png', 31018, 'image/png', 1024, 768, 'Cause', NULL, 'Cause', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(83, 'cause.webp', 'front/images/cause.webp', 52952, 'image/webp', 1080, 810, 'Cause', NULL, 'Cause', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(84, 'cause_icon.webp', 'front/images/cause_icon.webp', 0, 'image/webp', NULL, NULL, 'Cause Icon', NULL, 'Cause Icon', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(85, 'classroom.jpg', 'front/images/classroom.jpg', 43081, 'image/jpeg', 1200, 800, 'Classroom', NULL, 'Classroom', 'cms', NULL, NULL, '2026-10-14 20:41:45', '2026-10-14 20:41:45'),
(86, 'cohort_1.jpg', 'front/images/cohort_1.jpg', 225621, 'image/jpeg', 1200, 800, 'Cohort 1', NULL, 'Cohort 1', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(87, 'congress.png', 'front/images/congress.png', 758, 'image/png', 32, 32, 'Congress', NULL, 'Congress', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(88, 'contact.jpg', 'front/images/contact.jpg', 81743, 'image/jpeg', 1920, 1280, 'Contact', NULL, 'Contact', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(89, 'donate.png', 'front/images/donate.png', 1473, 'image/png', 64, 64, 'Donate', NULL, 'Donate', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(90, 'dzuelsCollage.jpg', 'front/images/dzuelsCollage.jpg', 1045358, 'image/jpeg', 2000, 1414, 'Dzuelscollage', NULL, 'Dzuelscollage', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(91, 'dzuelsCollage.webp', 'front/images/dzuelsCollage.webp', 415816, 'image/webp', 2000, 1414, 'Dzuelscollage', NULL, 'Dzuelscollage', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(92, 'education.png', 'front/images/education.png', 669, 'image/png', 32, 32, 'Education', NULL, 'Education', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(93, 'itd-2026-backdrop.png', 'front/images/events/itd-2026-backdrop.png', 1939078, 'image/png', 1390, 1132, 'Itd 2026 Backdrop', NULL, 'Itd 2026 Backdrop', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(94, 'itd-2026-backdrop.webp', 'front/images/events/itd-2026-backdrop.webp', 255724, 'image/webp', 1390, 1132, 'Itd 2026 Backdrop', NULL, 'Itd 2026 Backdrop', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(95, 'familycenter.png', 'front/images/familycenter.png', 8195, 'image/png', 186, 93, 'Familycenter', NULL, 'Familycenter', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(96, 'food.png', 'front/images/food.png', 744, 'image/png', 32, 32, 'Food', NULL, 'Food', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(97, 'famoyinka-02.webp', 'front/images/gallery/optimized/famoyinka-02.webp', 116960, 'image/webp', 1600, 1067, 'Transformational Leadership & Teamwork Games (2024)', 'Transformational Leadership & Teamwork Games', 'Outdoor youth leadership and character-building games', 'gallery', 6, 140, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(98, 'famoyinka-03.webp', 'front/images/gallery/optimized/famoyinka-03.webp', 117674, 'image/webp', 1600, 1067, 'Creative Arts, Painting & Crafts Workshop (2025)', 'Creative Arts, Painting & Crafts Workshop', 'Children painting and creating paper crafts in the library', 'gallery', 6, 141, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(99, 'famoyinka-04.webp', 'front/images/gallery/optimized/famoyinka-04.webp', 183730, 'image/webp', 1600, 1067, 'Ikogosi Warm Springs Educational Nature Excursion (2025)', 'Ikogosi Warm Springs Educational Nature Excursion', 'Pupils exploring nature boardwalk at Ikogosi Warm Springs', 'gallery', 6, 142, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(100, 'famoyinka-05.webp', 'front/images/gallery/optimized/famoyinka-05.webp', 85994, 'image/webp', 1600, 1067, 'Attentive Early Childhood Storybook Reading Circle (2024)', 'Attentive Early Childhood Storybook Reading Circle', 'Young pupil reading attentively in classroom circle', 'gallery', 6, 143, '2026-10-14 20:41:46', '2026-10-14 22:04:43');
INSERT INTO `media_items` (`id`, `filename`, `file_path`, `file_size`, `mime_type`, `width`, `height`, `title`, `caption`, `alt_text`, `source_type`, `gallery_album_id`, `gallery_photo_id`, `created_at`, `updated_at`) VALUES
(101, 'famoyinka-06.webp', 'front/images/gallery/optimized/famoyinka-06.webp', 80032, 'image/webp', 1600, 1067, 'Digital Literacy & Weekend Study Workstations (2025)', 'Digital Literacy & Weekend Study Workstations', 'Students working on computers in the study lab', 'gallery', 6, 144, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(102, 'famoyinka-07.webp', 'front/images/gallery/optimized/famoyinka-07.webp', 173204, 'image/webp', 1600, 1067, 'Smiling Beneficiaries in Community Peer Learning (2024)', 'Smiling Beneficiaries in Community Peer Learning', 'Smiling schoolchildren participating in library club', 'gallery', 6, 145, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(103, 'general-13.webp', 'front/images/gallery/optimized/general-13.webp', 150624, 'image/webp', 1360, 907, 'Community Impact Moment #13 (2024)', 'Community Impact Moment #13', 'General Community & Cohorts - Community Impact Moment #13 in Ijero-Ekiti', 'gallery', 5, 226, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(104, 'general-14.webp', 'front/images/gallery/optimized/general-14.webp', 139402, 'image/webp', 1360, 907, 'Community Impact Moment #14 (2024)', 'Community Impact Moment #14', 'General Community & Cohorts - Community Impact Moment #14 in Ijero-Ekiti', 'gallery', 5, 227, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(105, 'general-15.webp', 'front/images/gallery/optimized/general-15.webp', 163706, 'image/webp', 1600, 1067, 'Community Impact Moment #15 (2024)', 'Community Impact Moment #15', 'General Community & Cohorts - Community Impact Moment #15 in Ijero-Ekiti', 'gallery', 5, 228, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(106, 'general-16.webp', 'front/images/gallery/optimized/general-16.webp', 118654, 'image/webp', 1600, 1067, 'Community Impact Moment #16 (2024)', 'Community Impact Moment #16', 'General Community & Cohorts - Community Impact Moment #16 in Ijero-Ekiti', 'gallery', 5, 229, '2026-10-14 20:41:46', '2026-10-14 22:04:43'),
(107, 'general-17.webp', 'front/images/gallery/optimized/general-17.webp', 42122, 'image/webp', 1280, 720, 'General 17', NULL, 'General 17', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(108, 'general-18.webp', 'front/images/gallery/optimized/general-18.webp', 110542, 'image/webp', 1280, 853, 'General 18', NULL, 'General 18', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(109, 'general-19.webp', 'front/images/gallery/optimized/general-19.webp', 73286, 'image/webp', 1200, 900, 'General 19', NULL, 'General 19', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(110, 'general-20.webp', 'front/images/gallery/optimized/general-20.webp', 110664, 'image/webp', 1280, 853, 'General 20', NULL, 'General 20', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(111, 'general-21.webp', 'front/images/gallery/optimized/general-21.webp', 58928, 'image/webp', 1280, 853, 'General 21', NULL, 'General 21', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(112, 'general-22.webp', 'front/images/gallery/optimized/general-22.webp', 95958, 'image/webp', 1280, 853, 'General 22', NULL, 'General 22', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(113, 'general-23.webp', 'front/images/gallery/optimized/general-23.webp', 21786, 'image/webp', 1280, 853, 'General 23', NULL, 'General 23', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(114, 'general-24.webp', 'front/images/gallery/optimized/general-24.webp', 80118, 'image/webp', 1280, 853, 'General 24', NULL, 'General 24', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(115, 'general-25.webp', 'front/images/gallery/optimized/general-25.webp', 142358, 'image/webp', 1280, 853, 'General 25', NULL, 'General 25', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(116, 'itd-2024-13.webp', 'front/images/gallery/optimized/itd-2024-13.webp', 103836, 'image/webp', 1280, 853, 'Itd 2024 13', NULL, 'Itd 2024 13', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(117, 'itd-2024-14.webp', 'front/images/gallery/optimized/itd-2024-14.webp', 68932, 'image/webp', 1280, 853, 'Itd 2024 14', NULL, 'Itd 2024 14', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(118, 'itd-2024-15.webp', 'front/images/gallery/optimized/itd-2024-15.webp', 69784, 'image/webp', 1280, 853, 'Itd 2024 15', NULL, 'Itd 2024 15', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(119, 'itd-2024-16.webp', 'front/images/gallery/optimized/itd-2024-16.webp', 64958, 'image/webp', 1280, 853, 'Itd 2024 16', NULL, 'Itd 2024 16', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(120, 'itd-2024-17.webp', 'front/images/gallery/optimized/itd-2024-17.webp', 145640, 'image/webp', 1280, 853, 'Itd 2024 17', NULL, 'Itd 2024 17', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(121, 'itd-2024-18.webp', 'front/images/gallery/optimized/itd-2024-18.webp', 144298, 'image/webp', 1280, 853, 'Itd 2024 18', NULL, 'Itd 2024 18', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(122, 'itd-2024-19.webp', 'front/images/gallery/optimized/itd-2024-19.webp', 66598, 'image/webp', 1280, 853, 'Itd 2024 19', NULL, 'Itd 2024 19', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(123, 'itd-2024-20.webp', 'front/images/gallery/optimized/itd-2024-20.webp', 101908, 'image/webp', 1280, 853, 'Itd 2024 20', NULL, 'Itd 2024 20', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(124, 'itd-2024-21.webp', 'front/images/gallery/optimized/itd-2024-21.webp', 46582, 'image/webp', 1280, 853, 'Itd 2024 21', NULL, 'Itd 2024 21', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(125, 'itd-2024-22.webp', 'front/images/gallery/optimized/itd-2024-22.webp', 46000, 'image/webp', 1280, 853, 'Itd 2024 22', NULL, 'Itd 2024 22', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(126, 'itd-2024-23.webp', 'front/images/gallery/optimized/itd-2024-23.webp', 81230, 'image/webp', 1280, 853, 'Itd 2024 23', NULL, 'Itd 2024 23', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(127, 'itd-2024-24.webp', 'front/images/gallery/optimized/itd-2024-24.webp', 67178, 'image/webp', 1280, 853, 'Itd 2024 24', NULL, 'Itd 2024 24', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(128, 'itd-2024-25.webp', 'front/images/gallery/optimized/itd-2024-25.webp', 68456, 'image/webp', 1280, 853, 'Itd 2024 25', NULL, 'Itd 2024 25', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(129, 'itd-2025-16.webp', 'front/images/gallery/optimized/itd-2025-16.webp', 126248, 'image/webp', 1260, 900, 'Itd 2025 16', NULL, 'Itd 2025 16', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(130, 'itd-2025-17.webp', 'front/images/gallery/optimized/itd-2025-17.webp', 73992, 'image/webp', 1260, 900, 'Itd 2025 17', NULL, 'Itd 2025 17', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(131, 'itd-2025-18.webp', 'front/images/gallery/optimized/itd-2025-18.webp', 54504, 'image/webp', 1280, 853, 'Itd 2025 18', NULL, 'Itd 2025 18', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(132, 'itd-2025-19.webp', 'front/images/gallery/optimized/itd-2025-19.webp', 100804, 'image/webp', 1280, 853, 'Itd 2025 19', NULL, 'Itd 2025 19', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(133, 'itd-2025-20.webp', 'front/images/gallery/optimized/itd-2025-20.webp', 146606, 'image/webp', 1260, 900, 'Itd 2025 20', NULL, 'Itd 2025 20', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(134, 'itd-2025-21.webp', 'front/images/gallery/optimized/itd-2025-21.webp', 110240, 'image/webp', 1260, 900, 'Itd 2025 21', NULL, 'Itd 2025 21', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(135, 'itd-2025-22.webp', 'front/images/gallery/optimized/itd-2025-22.webp', 104322, 'image/webp', 1260, 900, 'Itd 2025 22', NULL, 'Itd 2025 22', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(136, 'itd-2025-23.webp', 'front/images/gallery/optimized/itd-2025-23.webp', 96924, 'image/webp', 1260, 900, 'Itd 2025 23', NULL, 'Itd 2025 23', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(137, 'itd-2025-24.webp', 'front/images/gallery/optimized/itd-2025-24.webp', 105468, 'image/webp', 1260, 900, 'Itd 2025 24', NULL, 'Itd 2025 24', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(138, 'itd-2025-25.webp', 'front/images/gallery/optimized/itd-2025-25.webp', 96656, 'image/webp', 1260, 900, 'Itd 2025 25', NULL, 'Itd 2025 25', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(139, 'reading-09.webp', 'front/images/gallery/optimized/reading-09.webp', 43486, 'image/webp', 600, 900, 'Reading 09', NULL, 'Reading 09', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(140, 'reading-10.webp', 'front/images/gallery/optimized/reading-10.webp', 31442, 'image/webp', 600, 900, 'Reading 10', NULL, 'Reading 10', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(141, 'reading-11.webp', 'front/images/gallery/optimized/reading-11.webp', 87178, 'image/webp', 1280, 853, 'Reading 11', NULL, 'Reading 11', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(142, 'reading-12.webp', 'front/images/gallery/optimized/reading-12.webp', 69016, 'image/webp', 1280, 853, 'Reading 12', NULL, 'Reading 12', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(143, 'reading-13.webp', 'front/images/gallery/optimized/reading-13.webp', 101374, 'image/webp', 1280, 853, 'Reading 13', NULL, 'Reading 13', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(144, 'reading-14.webp', 'front/images/gallery/optimized/reading-14.webp', 92928, 'image/webp', 1280, 853, 'Reading 14', NULL, 'Reading 14', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(145, 'reading-15.webp', 'front/images/gallery/optimized/reading-15.webp', 77224, 'image/webp', 1280, 853, 'Reading 15', NULL, 'Reading 15', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(146, 'reading-16.webp', 'front/images/gallery/optimized/reading-16.webp', 149074, 'image/webp', 1280, 853, 'Reading 16', NULL, 'Reading 16', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(147, 'reading-17.webp', 'front/images/gallery/optimized/reading-17.webp', 114848, 'image/webp', 1280, 853, 'Reading 17', NULL, 'Reading 17', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(148, 'reading-18.webp', 'front/images/gallery/optimized/reading-18.webp', 35696, 'image/webp', 600, 900, 'Reading 18', NULL, 'Reading 18', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(149, 'reading-19.webp', 'front/images/gallery/optimized/reading-19.webp', 149074, 'image/webp', 1280, 853, 'Reading 19', NULL, 'Reading 19', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(150, 'reading-20.webp', 'front/images/gallery/optimized/reading-20.webp', 57656, 'image/webp', 1280, 853, 'Reading 20', NULL, 'Reading 20', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46');
INSERT INTO `media_items` (`id`, `filename`, `file_path`, `file_size`, `mime_type`, `width`, `height`, `title`, `caption`, `alt_text`, `source_type`, `gallery_album_id`, `gallery_photo_id`, `created_at`, `updated_at`) VALUES
(151, 'reading-21.webp', 'front/images/gallery/optimized/reading-21.webp', 85362, 'image/webp', 1280, 853, 'Reading 21', NULL, 'Reading 21', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(152, 'reading-22.webp', 'front/images/gallery/optimized/reading-22.webp', 42738, 'image/webp', 1280, 853, 'Reading 22', NULL, 'Reading 22', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(153, 'reading-23.webp', 'front/images/gallery/optimized/reading-23.webp', 159320, 'image/webp', 1280, 853, 'Reading 23', NULL, 'Reading 23', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(154, 'reading-24.webp', 'front/images/gallery/optimized/reading-24.webp', 86880, 'image/webp', 600, 900, 'Reading 24', NULL, 'Reading 24', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(155, 'reading-25.webp', 'front/images/gallery/optimized/reading-25.webp', 39864, 'image/webp', 600, 900, 'Reading 25', NULL, 'Reading 25', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(156, 'spelling-11.webp', 'front/images/gallery/optimized/spelling-11.webp', 102100, 'image/webp', 1280, 853, 'Spelling 11', NULL, 'Spelling 11', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(157, 'spelling-12.webp', 'front/images/gallery/optimized/spelling-12.webp', 127044, 'image/webp', 1280, 853, 'Spelling 12', NULL, 'Spelling 12', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(158, 'spelling-13.webp', 'front/images/gallery/optimized/spelling-13.webp', 71350, 'image/webp', 1280, 853, 'Spelling 13', NULL, 'Spelling 13', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(159, 'spelling-14.webp', 'front/images/gallery/optimized/spelling-14.webp', 83906, 'image/webp', 1280, 853, 'Spelling 14', NULL, 'Spelling 14', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(160, 'spelling-15.webp', 'front/images/gallery/optimized/spelling-15.webp', 87630, 'image/webp', 1280, 853, 'Spelling 15', NULL, 'Spelling 15', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(161, 'spelling-16.webp', 'front/images/gallery/optimized/spelling-16.webp', 58100, 'image/webp', 1280, 853, 'Spelling 16', NULL, 'Spelling 16', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(162, 'spelling-17.webp', 'front/images/gallery/optimized/spelling-17.webp', 87916, 'image/webp', 1280, 853, 'Spelling 17', NULL, 'Spelling 17', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(163, 'spelling-18.webp', 'front/images/gallery/optimized/spelling-18.webp', 65862, 'image/webp', 1280, 853, 'Spelling 18', NULL, 'Spelling 18', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(164, 'spelling-19.webp', 'front/images/gallery/optimized/spelling-19.webp', 50948, 'image/webp', 600, 900, 'Spelling 19', NULL, 'Spelling 19', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(165, 'spelling-20.webp', 'front/images/gallery/optimized/spelling-20.webp', 80206, 'image/webp', 1280, 853, 'Spelling 20', NULL, 'Spelling 20', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(166, 'spelling-21.webp', 'front/images/gallery/optimized/spelling-21.webp', 82608, 'image/webp', 1280, 853, 'Spelling 21', NULL, 'Spelling 21', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(167, 'spelling-22.webp', 'front/images/gallery/optimized/spelling-22.webp', 101402, 'image/webp', 1280, 853, 'Spelling 22', NULL, 'Spelling 22', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(168, 'spelling-23.webp', 'front/images/gallery/optimized/spelling-23.webp', 91016, 'image/webp', 1280, 853, 'Spelling 23', NULL, 'Spelling 23', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(169, 'spelling-24.webp', 'front/images/gallery/optimized/spelling-24.webp', 69240, 'image/webp', 1280, 853, 'Spelling 24', NULL, 'Spelling 24', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(170, 'spelling-25.webp', 'front/images/gallery/optimized/spelling-25.webp', 72280, 'image/webp', 1280, 853, 'Spelling 25', NULL, 'Spelling 25', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(171, 'helpmeetaneed.png', 'front/images/helpmeetaneed.png', 4903, 'image/png', 189, 94, 'Helpmeetaneed', NULL, 'Helpmeetaneed', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(172, 'helpmeetaneed.webp', 'front/images/helpmeetaneed.webp', 1554, 'image/webp', 189, 94, 'Helpmeetaneed', NULL, 'Helpmeetaneed', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(173, 'home_1.jpg', 'front/images/home_1.jpg', 166871, 'image/jpeg', 1200, 800, 'Home 1', NULL, 'Home 1', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(174, 'home_1.webp', 'front/images/home_1.webp', 68674, 'image/webp', 1200, 800, 'Home 1', NULL, 'Home 1', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(175, 'intinternational.png', 'front/images/intinternational.png', 8204, 'image/png', 166, 94, 'Intinternational', NULL, 'Intinternational', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(176, 'intinternational.webp', 'front/images/intinternational.webp', 2118, 'image/webp', 166, 94, 'Intinternational', NULL, 'Intinternational', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(177, 'itd-2026-backdrop.png', 'front/images/itd-2026-backdrop.png', 1939078, 'image/png', 1390, 1132, 'Itd 2026 Backdrop', NULL, 'Itd 2026 Backdrop', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(178, 'itd-2026-backdrop.webp', 'front/images/itd-2026-backdrop.webp', 255724, 'image/webp', 1390, 1132, 'Itd 2026 Backdrop', NULL, 'Itd 2026 Backdrop', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(179, 'kate.jpg', 'front/images/kate.jpg', 65439, 'image/jpeg', 1080, 1680, 'Kate', NULL, 'Kate', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(180, 'learning.jpg', 'front/images/learning.jpg', 74599, 'image/jpeg', 1200, 800, 'Learning', NULL, 'Learning', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(181, 'library_1.jpg', 'front/images/library_1.jpg', 272460, 'image/jpeg', 1136, 743, 'Library 1', NULL, 'Library 1', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(182, 'location.png', 'front/images/location.png', 2888, 'image/png', 64, 64, 'Location', NULL, 'Location', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(183, 'logo.jpg', 'front/images/logo.jpg', 33436, 'image/jpeg', 1024, 593, 'Logo', NULL, 'Logo', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(184, 'logo.png', 'front/images/logo.png', 108741, 'image/png', 657, 380, 'Logo', NULL, 'Logo', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(185, 'mail.png', 'front/images/mail.png', 3057, 'image/png', 64, 64, 'Mail', NULL, 'Mail', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(186, 'map.png', 'front/images/map.png', 28603, 'image/png', 539, 463, 'Map', NULL, 'Map', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(187, 'mgt_fadekemi.jpeg', 'front/images/mgt_fadekemi.jpeg', 2075217, 'image/jpeg', 2933, 3157, 'Mgt Fadekemi', NULL, 'Mgt Fadekemi', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(188, 'mgt_fadekemi.webp', 'front/images/mgt_fadekemi.webp', 605716, 'image/webp', 2933, 3157, 'Mgt Fadekemi', NULL, 'Mgt Fadekemi', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(189, 'mgt_michelle.png', 'front/images/mgt_michelle.png', 198608, 'image/jpeg', 1024, 1024, 'Mgt Michelle', NULL, 'Mgt Michelle', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(190, 'mission.png', 'front/images/mission.png', 1167, 'image/png', 64, 64, 'Mission', NULL, 'Mission', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(191, 'naomi.jpg', 'front/images/naomi.jpg', 69476, 'image/jpeg', 662, 606, 'Naomi', NULL, 'Naomi', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(192, 'olawale.jpg', 'front/images/olawale.jpg', 27894, 'image/jpeg', 504, 678, 'Olawale', NULL, 'Olawale', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(193, 'package.png', 'front/images/package.png', 1237, 'image/png', 64, 64, 'Package', NULL, 'Package', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(194, 'partner.png', 'front/images/partner.png', 2825, 'image/png', 64, 64, 'Partner', NULL, 'Partner', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(195, 'phone.png', 'front/images/phone.png', 2583, 'image/png', 64, 64, 'Phone', NULL, 'Phone', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(196, 'running.jpg', 'front/images/running.jpg', 159122, 'image/jpeg', 1200, 800, 'Running', NULL, 'Running', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(197, 'students.jpg', 'front/images/students.jpg', 78351, 'image/jpeg', 1200, 800, 'Students', NULL, 'Students', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(198, 'teaching.png', 'front/images/teaching.png', 853, 'image/png', 32, 32, 'Teaching', NULL, 'Teaching', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(199, 'team.jpg', 'front/images/team.jpg', 113892, 'image/jpeg', 395, 291, 'Team', NULL, 'Team', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(200, 'techday.png', 'front/images/techday.png', 20808476, 'image/png', 9216, 3456, 'Techday', NULL, 'Techday', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46');
INSERT INTO `media_items` (`id`, `filename`, `file_path`, `file_size`, `mime_type`, `width`, `height`, `title`, `caption`, `alt_text`, `source_type`, `gallery_album_id`, `gallery_photo_id`, `created_at`, `updated_at`) VALUES
(201, 'techday.webp', 'front/images/techday.webp', 1016432, 'image/webp', 9216, 3456, 'Techday', NULL, 'Techday', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(202, 'values.png', 'front/images/values.png', 1550, 'image/png', 32, 32, 'Values', NULL, 'Values', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(203, 'vision.png', 'front/images/vision.png', 2415, 'image/png', 64, 64, 'Vision', NULL, 'Vision', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(204, 'what.jpg', 'front/images/what.jpg', 80197, 'image/jpeg', 328, 219, 'What', NULL, 'What', 'cms', NULL, NULL, '2026-10-14 20:41:46', '2026-10-14 20:41:46'),
(205, 'essay-competition-01.webp', 'front/images/gallery/optimized/essay-competition-01.webp', 148070, 'image/webp', 1280, 958, 'Essay Competition Moment #1 (2026)', 'Essay Competition Moment #1', 'Essay Competition - Essay Competition Moment #1 in Ijero-Ekiti', 'gallery', 7, 146, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(206, 'essay-competition-02.webp', 'front/images/gallery/optimized/essay-competition-02.webp', 141110, 'image/webp', 1280, 958, 'Essay Competition Moment #2 (2026)', 'Essay Competition Moment #2', 'Essay Competition - Essay Competition Moment #2 in Ijero-Ekiti', 'gallery', 7, 147, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(207, 'essay-competition-03.webp', 'front/images/gallery/optimized/essay-competition-03.webp', 144296, 'image/webp', 1280, 958, 'Essay Competition Moment #3 (2026)', 'Essay Competition Moment #3', 'Essay Competition - Essay Competition Moment #3 in Ijero-Ekiti', 'gallery', 7, 148, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(208, 'essay-competition-04.webp', 'front/images/gallery/optimized/essay-competition-04.webp', 158614, 'image/webp', 808, 1080, 'Essay Competition Moment #4 (2026)', 'Essay Competition Moment #4', 'Essay Competition - Essay Competition Moment #4 in Ijero-Ekiti', 'gallery', 7, 149, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(209, 'essay-competition-05.webp', 'front/images/gallery/optimized/essay-competition-05.webp', 111968, 'image/webp', 808, 1080, 'Essay Competition Moment #5 (2026)', 'Essay Competition Moment #5', 'Essay Competition - Essay Competition Moment #5 in Ijero-Ekiti', 'gallery', 7, 150, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(210, 'essay-competition-06.webp', 'front/images/gallery/optimized/essay-competition-06.webp', 71268, 'image/webp', 958, 1280, 'Essay Competition Moment #6 (2026)', 'Essay Competition Moment #6', 'Essay Competition - Essay Competition Moment #6 in Ijero-Ekiti', 'gallery', 7, 151, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(211, 'essay-competition-07.webp', 'front/images/gallery/optimized/essay-competition-07.webp', 154494, 'image/webp', 1280, 720, 'Essay Competition Moment #7 (2026)', 'Essay Competition Moment #7', 'Essay Competition - Essay Competition Moment #7 in Ijero-Ekiti', 'gallery', 7, 152, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(212, 'essay-competition-08.webp', 'front/images/gallery/optimized/essay-competition-08.webp', 119244, 'image/webp', 1280, 720, 'Essay Competition Moment #8 (2026)', 'Essay Competition Moment #8', 'Essay Competition - Essay Competition Moment #8 in Ijero-Ekiti', 'gallery', 7, 153, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(213, 'essay-competition-09.webp', 'front/images/gallery/optimized/essay-competition-09.webp', 146338, 'image/webp', 1280, 720, 'Essay Competition Moment #9 (2026)', 'Essay Competition Moment #9', 'Essay Competition - Essay Competition Moment #9 in Ijero-Ekiti', 'gallery', 7, 154, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(214, 'essay-competition-10.webp', 'front/images/gallery/optimized/essay-competition-10.webp', 93520, 'image/webp', 1280, 720, 'Essay Competition Moment #10 (2026)', 'Essay Competition Moment #10', 'Essay Competition - Essay Competition Moment #10 in Ijero-Ekiti', 'gallery', 7, 155, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(215, 'essay-competition-11.webp', 'front/images/gallery/optimized/essay-competition-11.webp', 97416, 'image/webp', 1280, 720, 'Essay Competition Moment #11 (2026)', 'Essay Competition Moment #11', 'Essay Competition - Essay Competition Moment #11 in Ijero-Ekiti', 'gallery', 7, 156, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(216, 'essay-competition-12.webp', 'front/images/gallery/optimized/essay-competition-12.webp', 177090, 'image/webp', 1600, 1200, 'Essay Competition Moment #12 (2026)', 'Essay Competition Moment #12', 'Essay Competition - Essay Competition Moment #12 in Ijero-Ekiti', 'gallery', 7, 157, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(217, 'essay-competition-13.webp', 'front/images/gallery/optimized/essay-competition-13.webp', 140872, 'image/webp', 1360, 1020, 'Essay Competition Moment #13 (2026)', 'Essay Competition Moment #13', 'Essay Competition - Essay Competition Moment #13 in Ijero-Ekiti', 'gallery', 7, 158, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(218, 'essay-competition-14.webp', 'front/images/gallery/optimized/essay-competition-14.webp', 144164, 'image/webp', 1360, 1020, 'Essay Competition Moment #14 (2026)', 'Essay Competition Moment #14', 'Essay Competition - Essay Competition Moment #14 in Ijero-Ekiti', 'gallery', 7, 159, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(219, 'essay-competition-15.webp', 'front/images/gallery/optimized/essay-competition-15.webp', 114392, 'image/webp', 1360, 1020, 'Essay Competition Moment #15 (2026)', 'Essay Competition Moment #15', 'Essay Competition - Essay Competition Moment #15 in Ijero-Ekiti', 'gallery', 7, 160, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(220, 'essay-competition-16.webp', 'front/images/gallery/optimized/essay-competition-16.webp', 162652, 'image/webp', 1360, 1020, 'Essay Competition Moment #16 (2026)', 'Essay Competition Moment #16', 'Essay Competition - Essay Competition Moment #16 in Ijero-Ekiti', 'gallery', 7, 161, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(221, 'girls-day-01.webp', 'front/images/gallery/optimized/girls-day-01.webp', 94700, 'image/webp', 1600, 1067, 'Girls in Tech Session #1 (2026)', 'Girls in Tech Session #1', 'Girls in Tech & Leadership - Girls in Tech Session #1 in Ijero-Ekiti', 'gallery', 8, 162, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(222, 'girls-day-02.webp', 'front/images/gallery/optimized/girls-day-02.webp', 93086, 'image/webp', 1600, 1067, 'Girls in Tech Session #2 (2026)', 'Girls in Tech Session #2', 'Girls in Tech & Leadership - Girls in Tech Session #2 in Ijero-Ekiti', 'gallery', 8, 163, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(223, 'girls-day-03.webp', 'front/images/gallery/optimized/girls-day-03.webp', 89932, 'image/webp', 1600, 1067, 'Girls in Tech Session #3 (2026)', 'Girls in Tech Session #3', 'Girls in Tech & Leadership - Girls in Tech Session #3 in Ijero-Ekiti', 'gallery', 8, 164, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(224, 'girls-day-04.webp', 'front/images/gallery/optimized/girls-day-04.webp', 99496, 'image/webp', 1600, 1067, 'Girls in Tech Session #4 (2026)', 'Girls in Tech Session #4', 'Girls in Tech & Leadership - Girls in Tech Session #4 in Ijero-Ekiti', 'gallery', 8, 165, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(225, 'girls-day-05.webp', 'front/images/gallery/optimized/girls-day-05.webp', 178090, 'image/webp', 1600, 1067, 'Girls in Tech Session #5 (2026)', 'Girls in Tech Session #5', 'Girls in Tech & Leadership - Girls in Tech Session #5 in Ijero-Ekiti', 'gallery', 8, 166, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(226, 'girls-day-06.webp', 'front/images/gallery/optimized/girls-day-06.webp', 174006, 'image/webp', 1600, 1067, 'Girls in Tech Session #6 (2026)', 'Girls in Tech Session #6', 'Girls in Tech & Leadership - Girls in Tech Session #6 in Ijero-Ekiti', 'gallery', 8, 167, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(227, 'girls-day-07.webp', 'front/images/gallery/optimized/girls-day-07.webp', 164174, 'image/webp', 1600, 1067, 'Girls in Tech Session #7 (2026)', 'Girls in Tech Session #7', 'Girls in Tech & Leadership - Girls in Tech Session #7 in Ijero-Ekiti', 'gallery', 8, 168, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(228, 'girls-day-08.webp', 'front/images/gallery/optimized/girls-day-08.webp', 88658, 'image/webp', 1067, 1600, 'Girls in Tech Session #8 (2026)', 'Girls in Tech Session #8', 'Girls in Tech & Leadership - Girls in Tech Session #8 in Ijero-Ekiti', 'gallery', 8, 169, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(229, 'girls-day-09.webp', 'front/images/gallery/optimized/girls-day-09.webp', 110110, 'image/webp', 1600, 1067, 'Girls in Tech Session #9 (2026)', 'Girls in Tech Session #9', 'Girls in Tech & Leadership - Girls in Tech Session #9 in Ijero-Ekiti', 'gallery', 8, 170, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(230, 'girls-day-10.webp', 'front/images/gallery/optimized/girls-day-10.webp', 86866, 'image/webp', 1067, 1600, 'Girls in Tech Session #10 (2026)', 'Girls in Tech Session #10', 'Girls in Tech & Leadership - Girls in Tech Session #10 in Ijero-Ekiti', 'gallery', 8, 171, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(231, 'girls-day-11.webp', 'front/images/gallery/optimized/girls-day-11.webp', 64730, 'image/webp', 1067, 1600, 'Girls in Tech Session #11 (2026)', 'Girls in Tech Session #11', 'Girls in Tech & Leadership - Girls in Tech Session #11 in Ijero-Ekiti', 'gallery', 8, 172, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(232, 'girls-day-12.webp', 'front/images/gallery/optimized/girls-day-12.webp', 134604, 'image/webp', 1080, 810, 'Girls in Tech Session #12 (2026)', 'Girls in Tech Session #12', 'Girls in Tech & Leadership - Girls in Tech Session #12 in Ijero-Ekiti', 'gallery', 8, 173, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(233, 'girls-day-13.webp', 'front/images/gallery/optimized/girls-day-13.webp', 131508, 'image/webp', 1280, 960, 'Girls in Tech Session #13 (2026)', 'Girls in Tech Session #13', 'Girls in Tech & Leadership - Girls in Tech Session #13 in Ijero-Ekiti', 'gallery', 8, 174, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(234, 'girls-day-14.webp', 'front/images/gallery/optimized/girls-day-14.webp', 155746, 'image/webp', 1280, 960, 'Girls in Tech Session #14 (2026)', 'Girls in Tech Session #14', 'Girls in Tech & Leadership - Girls in Tech Session #14 in Ijero-Ekiti', 'gallery', 8, 175, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(235, 'girls-day-15.webp', 'front/images/gallery/optimized/girls-day-15.webp', 152770, 'image/webp', 1280, 960, 'Girls in Tech Session #15 (2026)', 'Girls in Tech Session #15', 'Girls in Tech & Leadership - Girls in Tech Session #15 in Ijero-Ekiti', 'gallery', 8, 176, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(236, 'girls-day-16.webp', 'front/images/gallery/optimized/girls-day-16.webp', 165558, 'image/webp', 1280, 960, 'Girls in Tech Session #16 (2026)', 'Girls in Tech Session #16', 'Girls in Tech & Leadership - Girls in Tech Session #16 in Ijero-Ekiti', 'gallery', 8, 177, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(237, 'itd-01.webp', 'front/images/gallery/optimized/itd-01.webp', 64082, 'image/webp', 1600, 1067, 'ITD Festival Highlight #1 (2025)', 'ITD Festival Highlight #1', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #1 in Ijero-Ekiti', 'gallery', 9, 178, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(238, 'itd-02.webp', 'front/images/gallery/optimized/itd-02.webp', 163454, 'image/webp', 1600, 1067, 'ITD Festival Highlight #2 (2025)', 'ITD Festival Highlight #2', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #2 in Ijero-Ekiti', 'gallery', 9, 179, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(239, 'itd-03.webp', 'front/images/gallery/optimized/itd-03.webp', 130750, 'image/webp', 1600, 1067, 'ITD Festival Highlight #3 (2025)', 'ITD Festival Highlight #3', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #3 in Ijero-Ekiti', 'gallery', 9, 180, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(240, 'itd-04.webp', 'front/images/gallery/optimized/itd-04.webp', 81466, 'image/webp', 1600, 1067, 'ITD Festival Highlight #4 (2025)', 'ITD Festival Highlight #4', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #4 in Ijero-Ekiti', 'gallery', 9, 181, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(241, 'itd-05.webp', 'front/images/gallery/optimized/itd-05.webp', 126976, 'image/webp', 1600, 1067, 'ITD Festival Highlight #5 (2025)', 'ITD Festival Highlight #5', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #5 in Ijero-Ekiti', 'gallery', 9, 182, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(242, 'itd-06.webp', 'front/images/gallery/optimized/itd-06.webp', 87966, 'image/webp', 1600, 1067, 'ITD Festival Highlight #6 (2025)', 'ITD Festival Highlight #6', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #6 in Ijero-Ekiti', 'gallery', 9, 183, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(243, 'itd-07.webp', 'front/images/gallery/optimized/itd-07.webp', 144234, 'image/webp', 1600, 1067, 'ITD Festival Highlight #7 (2025)', 'ITD Festival Highlight #7', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #7 in Ijero-Ekiti', 'gallery', 9, 184, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(244, 'itd-08.webp', 'front/images/gallery/optimized/itd-08.webp', 168634, 'image/webp', 1600, 1067, 'ITD Festival Highlight #8 (2025)', 'ITD Festival Highlight #8', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #8 in Ijero-Ekiti', 'gallery', 9, 185, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(245, 'itd-09.webp', 'front/images/gallery/optimized/itd-09.webp', 106782, 'image/webp', 1600, 1067, 'ITD Festival Highlight #9 (2025)', 'ITD Festival Highlight #9', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #9 in Ijero-Ekiti', 'gallery', 9, 186, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(246, 'itd-10.webp', 'front/images/gallery/optimized/itd-10.webp', 124216, 'image/webp', 1600, 1067, 'ITD Festival Highlight #10 (2025)', 'ITD Festival Highlight #10', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #10 in Ijero-Ekiti', 'gallery', 9, 187, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(247, 'itd-11.webp', 'front/images/gallery/optimized/itd-11.webp', 153380, 'image/webp', 1600, 1067, 'ITD Festival Highlight #11 (2025)', 'ITD Festival Highlight #11', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #11 in Ijero-Ekiti', 'gallery', 9, 188, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(248, 'itd-12.webp', 'front/images/gallery/optimized/itd-12.webp', 134016, 'image/webp', 1600, 1067, 'ITD Festival Highlight #12 (2025)', 'ITD Festival Highlight #12', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #12 in Ijero-Ekiti', 'gallery', 9, 189, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(249, 'itd-13.webp', 'front/images/gallery/optimized/itd-13.webp', 165318, 'image/webp', 1600, 1067, 'ITD Festival Highlight #13 (2025)', 'ITD Festival Highlight #13', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #13 in Ijero-Ekiti', 'gallery', 9, 190, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(250, 'itd-14.webp', 'front/images/gallery/optimized/itd-14.webp', 82544, 'image/webp', 1600, 1067, 'ITD Festival Highlight #14 (2025)', 'ITD Festival Highlight #14', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #14 in Ijero-Ekiti', 'gallery', 9, 191, '2026-10-14 22:04:43', '2026-10-14 22:04:43');
INSERT INTO `media_items` (`id`, `filename`, `file_path`, `file_size`, `mime_type`, `width`, `height`, `title`, `caption`, `alt_text`, `source_type`, `gallery_album_id`, `gallery_photo_id`, `created_at`, `updated_at`) VALUES
(251, 'itd-15.webp', 'front/images/gallery/optimized/itd-15.webp', 88168, 'image/webp', 1600, 1067, 'ITD Festival Highlight #15 (2025)', 'ITD Festival Highlight #15', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #15 in Ijero-Ekiti', 'gallery', 9, 192, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(252, 'itd-16.webp', 'front/images/gallery/optimized/itd-16.webp', 93780, 'image/webp', 1600, 1067, 'ITD Festival Highlight #16 (2025)', 'ITD Festival Highlight #16', 'Innovation and Tech Day (ITD) - ITD Festival Highlight #16 in Ijero-Ekiti', 'gallery', 9, 193, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(253, 'spelling-bee-01.webp', 'front/images/gallery/optimized/spelling-bee-01.webp', 93680, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #1 (2026)', 'Spelling Bee Contestant #1', 'Spelling Bee Championship - Spelling Bee Contestant #1 in Ijero-Ekiti', 'gallery', 4, 194, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(254, 'spelling-bee-02.webp', 'front/images/gallery/optimized/spelling-bee-02.webp', 98624, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #2 (2026)', 'Spelling Bee Contestant #2', 'Spelling Bee Championship - Spelling Bee Contestant #2 in Ijero-Ekiti', 'gallery', 4, 195, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(255, 'spelling-bee-03.webp', 'front/images/gallery/optimized/spelling-bee-03.webp', 160780, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #3 (2026)', 'Spelling Bee Contestant #3', 'Spelling Bee Championship - Spelling Bee Contestant #3 in Ijero-Ekiti', 'gallery', 4, 196, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(256, 'spelling-bee-04.webp', 'front/images/gallery/optimized/spelling-bee-04.webp', 134868, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #4 (2026)', 'Spelling Bee Contestant #4', 'Spelling Bee Championship - Spelling Bee Contestant #4 in Ijero-Ekiti', 'gallery', 4, 197, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(257, 'spelling-bee-05.webp', 'front/images/gallery/optimized/spelling-bee-05.webp', 145236, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #5 (2026)', 'Spelling Bee Contestant #5', 'Spelling Bee Championship - Spelling Bee Contestant #5 in Ijero-Ekiti', 'gallery', 4, 198, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(258, 'spelling-bee-06.webp', 'front/images/gallery/optimized/spelling-bee-06.webp', 138628, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #6 (2026)', 'Spelling Bee Contestant #6', 'Spelling Bee Championship - Spelling Bee Contestant #6 in Ijero-Ekiti', 'gallery', 4, 199, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(259, 'spelling-bee-07.webp', 'front/images/gallery/optimized/spelling-bee-07.webp', 177998, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #7 (2026)', 'Spelling Bee Contestant #7', 'Spelling Bee Championship - Spelling Bee Contestant #7 in Ijero-Ekiti', 'gallery', 4, 200, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(260, 'spelling-bee-08.webp', 'front/images/gallery/optimized/spelling-bee-08.webp', 161460, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #8 (2026)', 'Spelling Bee Contestant #8', 'Spelling Bee Championship - Spelling Bee Contestant #8 in Ijero-Ekiti', 'gallery', 4, 201, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(261, 'spelling-bee-09.webp', 'front/images/gallery/optimized/spelling-bee-09.webp', 167436, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #9 (2026)', 'Spelling Bee Contestant #9', 'Spelling Bee Championship - Spelling Bee Contestant #9 in Ijero-Ekiti', 'gallery', 4, 202, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(262, 'spelling-bee-10.webp', 'front/images/gallery/optimized/spelling-bee-10.webp', 137412, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #10 (2026)', 'Spelling Bee Contestant #10', 'Spelling Bee Championship - Spelling Bee Contestant #10 in Ijero-Ekiti', 'gallery', 4, 203, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(263, 'spelling-bee-11.webp', 'front/images/gallery/optimized/spelling-bee-11.webp', 127920, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #11 (2026)', 'Spelling Bee Contestant #11', 'Spelling Bee Championship - Spelling Bee Contestant #11 in Ijero-Ekiti', 'gallery', 4, 204, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(264, 'spelling-bee-12.webp', 'front/images/gallery/optimized/spelling-bee-12.webp', 143348, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #12 (2026)', 'Spelling Bee Contestant #12', 'Spelling Bee Championship - Spelling Bee Contestant #12 in Ijero-Ekiti', 'gallery', 4, 205, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(265, 'spelling-bee-13.webp', 'front/images/gallery/optimized/spelling-bee-13.webp', 160486, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #13 (2026)', 'Spelling Bee Contestant #13', 'Spelling Bee Championship - Spelling Bee Contestant #13 in Ijero-Ekiti', 'gallery', 4, 206, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(266, 'spelling-bee-14.webp', 'front/images/gallery/optimized/spelling-bee-14.webp', 152268, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #14 (2026)', 'Spelling Bee Contestant #14', 'Spelling Bee Championship - Spelling Bee Contestant #14 in Ijero-Ekiti', 'gallery', 4, 207, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(267, 'spelling-bee-15.webp', 'front/images/gallery/optimized/spelling-bee-15.webp', 130714, 'image/webp', 1600, 1067, 'Spelling Bee Contestant #15 (2026)', 'Spelling Bee Contestant #15', 'Spelling Bee Championship - Spelling Bee Contestant #15 in Ijero-Ekiti', 'gallery', 4, 208, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(268, 'spelling-bee-16.webp', 'front/images/gallery/optimized/spelling-bee-16.webp', 154494, 'image/webp', 1067, 1600, 'Spelling Bee Contestant #16 (2026)', 'Spelling Bee Contestant #16', 'Spelling Bee Championship - Spelling Bee Contestant #16 in Ijero-Ekiti', 'gallery', 4, 209, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(269, 'wdada-01.webp', 'front/images/gallery/optimized/wdada-01.webp', 169328, 'image/webp', 1067, 1600, 'Community Relief Outreach #1 (2026)', 'Community Relief Outreach #1', 'WDADA Community Outreach - Community Relief Outreach #1 in Ijero-Ekiti', 'gallery', 10, 210, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(270, 'wdada-02.webp', 'front/images/gallery/optimized/wdada-02.webp', 110088, 'image/webp', 1360, 907, 'Community Relief Outreach #2 (2026)', 'Community Relief Outreach #2', 'WDADA Community Outreach - Community Relief Outreach #2 in Ijero-Ekiti', 'gallery', 10, 211, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(271, 'wdada-03.webp', 'front/images/gallery/optimized/wdada-03.webp', 152824, 'image/webp', 1600, 1067, 'Community Relief Outreach #3 (2026)', 'Community Relief Outreach #3', 'WDADA Community Outreach - Community Relief Outreach #3 in Ijero-Ekiti', 'gallery', 10, 212, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(272, 'wdada-04.webp', 'front/images/gallery/optimized/wdada-04.webp', 107464, 'image/webp', 1600, 1067, 'Community Relief Outreach #4 (2026)', 'Community Relief Outreach #4', 'WDADA Community Outreach - Community Relief Outreach #4 in Ijero-Ekiti', 'gallery', 10, 213, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(273, 'wdada-05.webp', 'front/images/gallery/optimized/wdada-05.webp', 153632, 'image/webp', 1600, 1067, 'Community Relief Outreach #5 (2026)', 'Community Relief Outreach #5', 'WDADA Community Outreach - Community Relief Outreach #5 in Ijero-Ekiti', 'gallery', 10, 214, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(274, 'wdada-06.webp', 'front/images/gallery/optimized/wdada-06.webp', 134208, 'image/webp', 1600, 1067, 'Community Relief Outreach #6 (2026)', 'Community Relief Outreach #6', 'WDADA Community Outreach - Community Relief Outreach #6 in Ijero-Ekiti', 'gallery', 10, 215, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(275, 'wdada-07.webp', 'front/images/gallery/optimized/wdada-07.webp', 143852, 'image/webp', 1600, 1067, 'Community Relief Outreach #7 (2026)', 'Community Relief Outreach #7', 'WDADA Community Outreach - Community Relief Outreach #7 in Ijero-Ekiti', 'gallery', 10, 216, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(276, 'wdada-08.webp', 'front/images/gallery/optimized/wdada-08.webp', 109750, 'image/webp', 1600, 1067, 'Community Relief Outreach #8 (2026)', 'Community Relief Outreach #8', 'WDADA Community Outreach - Community Relief Outreach #8 in Ijero-Ekiti', 'gallery', 10, 217, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(277, 'wdada-09.webp', 'front/images/gallery/optimized/wdada-09.webp', 111564, 'image/webp', 1600, 1067, 'Community Relief Outreach #9 (2026)', 'Community Relief Outreach #9', 'WDADA Community Outreach - Community Relief Outreach #9 in Ijero-Ekiti', 'gallery', 10, 218, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(278, 'wdada-10.webp', 'front/images/gallery/optimized/wdada-10.webp', 120314, 'image/webp', 1360, 907, 'Community Relief Outreach #10 (2026)', 'Community Relief Outreach #10', 'WDADA Community Outreach - Community Relief Outreach #10 in Ijero-Ekiti', 'gallery', 10, 219, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(279, 'wdada-11.webp', 'front/images/gallery/optimized/wdada-11.webp', 161864, 'image/webp', 1600, 1067, 'Community Relief Outreach #11 (2026)', 'Community Relief Outreach #11', 'WDADA Community Outreach - Community Relief Outreach #11 in Ijero-Ekiti', 'gallery', 10, 220, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(280, 'wdada-12.webp', 'front/images/gallery/optimized/wdada-12.webp', 160900, 'image/webp', 1600, 1067, 'Community Relief Outreach #12 (2026)', 'Community Relief Outreach #12', 'WDADA Community Outreach - Community Relief Outreach #12 in Ijero-Ekiti', 'gallery', 10, 221, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(281, 'wdada-13.webp', 'front/images/gallery/optimized/wdada-13.webp', 98158, 'image/webp', 1600, 1067, 'Community Relief Outreach #13 (2026)', 'Community Relief Outreach #13', 'WDADA Community Outreach - Community Relief Outreach #13 in Ijero-Ekiti', 'gallery', 10, 222, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(282, 'wdada-14.webp', 'front/images/gallery/optimized/wdada-14.webp', 122538, 'image/webp', 1600, 1067, 'Community Relief Outreach #14 (2026)', 'Community Relief Outreach #14', 'WDADA Community Outreach - Community Relief Outreach #14 in Ijero-Ekiti', 'gallery', 10, 223, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(283, 'wdada-15.webp', 'front/images/gallery/optimized/wdada-15.webp', 164960, 'image/webp', 1600, 1067, 'Community Relief Outreach #15 (2026)', 'Community Relief Outreach #15', 'WDADA Community Outreach - Community Relief Outreach #15 in Ijero-Ekiti', 'gallery', 10, 224, '2026-10-14 22:04:43', '2026-10-14 22:04:43'),
(284, 'wdada-16.webp', 'front/images/gallery/optimized/wdada-16.webp', 125818, 'image/webp', 1600, 1067, 'Community Relief Outreach #16 (2026)', 'Community Relief Outreach #16', 'WDADA Community Outreach - Community Relief Outreach #16 in Ijero-Ekiti', 'gallery', 10, 225, '2026-10-14 22:04:43', '2026-10-14 22:04:43');
/*!40000 ALTER TABLE `media_items` ENABLE KEYS */;
UNLOCK TABLES;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET FOREIGN_KEY_CHECKS=1;

-- =====================================================================
-- End of Database Export
-- =====================================================================
