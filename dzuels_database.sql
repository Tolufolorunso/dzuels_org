-- =====================================================================
-- Dzuels Educational Foundation - Production Database Export
-- Target: cPanel phpMyAdmin / MySQL 5.7+ / MariaDB 10.3+
-- Generated: 2026-10-14 05:01:58 UTC
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
-- Dumping data for table `migrations` (22 rows)
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
(22, '2026_10_13_061932_add_year_to_gallery_photos_table', 2);
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
('Wck1qOvNXI4r2yB2tI378zoGnn3grIIn6bTmjZPK', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI4aThrdE1HcGxBZEhVWDZOQVIzV3NHZ0VhZHlCWHFuZWlpZHc2aXdFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hZG1pblwvcGFnZXNcL2hvbWUiLCJyb3V0ZSI6ImFkbWluLnBhZ2VzLmVkaXQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiJkZTFkMjcwNDlmZGJjODdmOGE5MzQyYzVkNmM4N2IyYmE3YTk1NWI4NWM3MTEzMmViNWY4Y2E5YmQ1ZDA1NzgxIn0=', 1791929474);
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `gallery_albums_is_active_sort_order_index` (`is_active`, `sort_order`),
  UNIQUE KEY `gallery_albums_slug_unique` (`slug`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_albums` (6 rows)
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` (`id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ITD 2025', 'itd-2025', 'Innovation and Tech Day', 'Highlights from ITD 2025: keynote talks, hands-on coding demonstrations, award moments, and student builders.', 'front/images/gallery/optimized/itd-2025-05.webp', 2, 1, '2026-10-11 16:36:13', '2026-10-13 14:10:21'),
(2, 'ITD 2024', 'itd-2024', 'Inaugural Tech Day', 'Highlights from the maiden ITD 2024 edition: bringing technology education and digital skills to Ijero-Ekiti.', 'front/images/gallery/optimized/itd-2024-01.webp', 3, 1, '2026-10-11 16:36:13', '2026-10-13 19:54:22'),
(3, 'Reading Competition', 'reading-competition', 'Literacy Outreach', 'Encouraging early literacy, book reading culture, comprehension, and vocabulary among young students.', 'front/images/gallery/optimized/reading-01.webp', 4, 1, '2026-10-11 16:36:13', '2026-10-13 14:10:21'),
(4, 'Spelling Bee', 'spelling-bee', 'Academic Excellence', 'Spelling Bee tournaments across secondary schools in Ekiti State promoting language mastery and confidence.', 'front/images/gallery/optimized/spelling-01.webp', 5, 1, '2026-10-11 16:36:13', '2026-10-13 14:10:21'),
(5, 'General', 'general', 'Everyday Impact', 'Mentorship sessions, classroom interventions, community outreach, and equipment distribution moments.', 'front/images/gallery/optimized/general-01.webp', 6, 1, '2026-10-11 16:36:13', '2026-10-13 14:10:21'),
(6, 'Famoyinka Memorial Library', 'famoyinka-library', 'Children\'s Literacy & Creative Hub', 'Action moments from our community children\'s library in Ijero-Ekiti: story circles, book loans, creative crafts, transformational leadership games, and educational excursions.', 'front/images/gallery/optimized/famoyinka-01.webp', 1, 1, '2026-10-13 14:10:21', '2026-10-13 14:10:21');
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
-- Dumping data for table `gallery_photos` (61 rows)
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
(51, 3, 'front/images/gallery/optimized/reading-01.webp', 'Reading Competition Moment #1', 'Reading Competition - Reading Competition Moment #1 in Ijero-Ekiti', '2024', 1, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(52, 3, 'front/images/gallery/optimized/reading-02.webp', 'Reading Competition Moment #2', 'Reading Competition - Reading Competition Moment #2 in Ijero-Ekiti', '2024', 2, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(53, 3, 'front/images/gallery/optimized/reading-03.webp', 'Reading Competition Moment #3', 'Reading Competition - Reading Competition Moment #3 in Ijero-Ekiti', '2024', 3, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(54, 3, 'front/images/gallery/optimized/reading-04.webp', 'Reading Competition Moment #4', 'Reading Competition - Reading Competition Moment #4 in Ijero-Ekiti', '2024', 4, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(55, 3, 'front/images/gallery/optimized/reading-05.webp', 'Reading Competition Moment #5', 'Reading Competition - Reading Competition Moment #5 in Ijero-Ekiti', '2024', 5, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(56, 3, 'front/images/gallery/optimized/reading-06.webp', 'Reading Competition Moment #6', 'Reading Competition - Reading Competition Moment #6 in Ijero-Ekiti', '2024', 6, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(57, 3, 'front/images/gallery/optimized/reading-07.webp', 'Reading Competition Moment #7', 'Reading Competition - Reading Competition Moment #7 in Ijero-Ekiti', '2024', 7, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(58, 3, 'front/images/gallery/optimized/reading-08.webp', 'Reading Competition Moment #8', 'Reading Competition - Reading Competition Moment #8 in Ijero-Ekiti', '2024', 8, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
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
(101, 5, 'front/images/gallery/optimized/general-01.webp', 'Community Impact Moment #1', 'General - Community Impact Moment #1 in Ijero-Ekiti', '2024', 1, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(102, 5, 'front/images/gallery/optimized/general-02.webp', 'Community Impact Moment #2', 'General - Community Impact Moment #2 in Ijero-Ekiti', '2024', 2, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(103, 5, 'front/images/gallery/optimized/general-03.webp', 'Community Impact Moment #3', 'General - Community Impact Moment #3 in Ijero-Ekiti', '2024', 3, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(104, 5, 'front/images/gallery/optimized/general-04.webp', 'Community Impact Moment #4', 'General - Community Impact Moment #4 in Ijero-Ekiti', '2024', 4, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(105, 5, 'front/images/gallery/optimized/general-05.webp', 'Community Impact Moment #5', 'General - Community Impact Moment #5 in Ijero-Ekiti', '2024', 5, '2026-10-11 16:36:13', '2026-10-13 06:21:31');
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `year`, `sort_order`, `created_at`, `updated_at`) VALUES
(106, 5, 'front/images/gallery/optimized/general-06.webp', 'Community Impact Moment #6', 'General - Community Impact Moment #6 in Ijero-Ekiti', '2024', 6, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(107, 5, 'front/images/gallery/optimized/general-07.webp', 'Community Impact Moment #7', 'General - Community Impact Moment #7 in Ijero-Ekiti', '2024', 7, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(108, 5, 'front/images/gallery/optimized/general-08.webp', 'Community Impact Moment #8', 'General - Community Impact Moment #8 in Ijero-Ekiti', '2024', 8, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(109, 5, 'front/images/gallery/optimized/general-09.webp', 'Community Impact Moment #9', 'General - Community Impact Moment #9 in Ijero-Ekiti', '2024', 9, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(110, 5, 'front/images/gallery/optimized/general-10.webp', 'Community Impact Moment #10', 'General - Community Impact Moment #10 in Ijero-Ekiti', '2024', 10, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(111, 5, 'front/images/gallery/optimized/general-11.webp', 'Community Impact Moment #11', 'General - Community Impact Moment #11 in Ijero-Ekiti', '2024', 11, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(112, 5, 'front/images/gallery/optimized/general-12.webp', 'Community Impact Moment #12', 'General - Community Impact Moment #12 in Ijero-Ekiti', '2024', 12, '2026-10-11 16:36:13', '2026-10-13 06:21:31'),
(126, 6, 'front/images/gallery/optimized/famoyinka-01.webp', 'Children\'s Book Lending & Fiction Shelf', 'Student selecting books at Famoyinka Memorial Children\'s Library', '2024', 1, '2026-10-13 14:10:21', '2026-10-13 14:10:21'),
(133, 6, 'front/images/gallery/optimized/famoyinka-08.webp', 'Holistic Education Support, Arts & Values Pillars', 'Foundation education support and values curriculum', '2024', 8, '2026-10-13 14:10:21', '2026-10-13 14:10:21'),
(138, 6, 'uploads/gallery/photos/1791914698_iuIliqYsTVTp.jpg', NULL, 'Famoyinka Memorial Library', '2026', 9, '2026-10-13 18:04:58', '2026-10-13 18:04:58'),
(139, 2, 'uploads/gallery/photos/1791920685_ocrrwda9tM4N.webp', NULL, 'ITD 2024', '2026', 13, '2026-10-13 19:44:46', '2026-10-13 19:44:46');
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
(22, 'home', 'famoyinka_library', 'Famoyinka Memorial Children\'s Library', 'Permanent Literacy Sanctuary', 'An active, community-anchored hub dedicated to childhood literacy, creative self-expression, and transformational leadership in Southwest Nigeria.', NULL, 'Explore Library Programme', '/programmes/famoyinka-memorial-library', '{"photos":[{"image":"front\\/images\\/gallery\\/optimized\\/famoyinka-01.webp","label":"Free Book Loans","alt":"Student selecting books at Famoyinka Memorial Children\'s Library"},{"image":null,"label":"Creative Arts","alt":"Creative watercolor art workshop"},{"image":null,"label":"Leadership Games","alt":"Leadership teamwork games and sports"},{"image":"uploads\\/media\\/1791915201_LilvyZhc6aTE.jpg","label":"Ikogosi Excursion","alt":"Ikogosi Warm Springs educational excursion"}]}', 6, '2026-10-13 18:00:55', '2026-10-13 18:13:24');
/*!40000 ALTER TABLE `page_sections` ENABLE KEYS */;
UNLOCK TABLES;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET FOREIGN_KEY_CHECKS=1;

-- =====================================================================
-- End of Database Export
-- =====================================================================
