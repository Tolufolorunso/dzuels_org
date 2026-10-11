-- =====================================================================
-- Dzuels Educational Foundation - Production Database Export
-- Target: cPanel phpMyAdmin / MySQL 5.7+ / MariaDB 10.3+
-- Generated: 2026-10-11 16:48:12 UTC
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
-- Dumping data for table `migrations` (21 rows)
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
(21, '2026_10_11_170000_create_page_sections_table', 1);
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
('b0cfw2JDhdw9cdH2BC0qGtwOjDdN54px3dEY6Vtb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJIaTVuWDROQnJObWVkVnNXc3ZCMFk1WENndm1FeWRvbUplZWVteElHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1791737292);
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
('dzuels-cache-dzuels_site_settings', 'a:18:{s:17:"stat_ict_students";i:165;s:22:"stat_literacy_students";i:591;s:22:"stat_schools_supported";i:15;s:14:"stat_feed_mode";s:6:"manual";s:17:"stat_api_endpoint";s:0:"";s:15:"contact_phone_1";s:17:"+234 904 249 3883";s:15:"contact_phone_2";s:17:"+234 810 000 0000";s:13:"contact_email";s:25:"info@dzuelsfoundation.org";s:15:"contact_address";s:51:"12b Doherty Road, Ijero-Ekiti, Ekiti State, Nigeria";s:15:"flutterwave_url";s:40:"https://flutterwave.com/pay/j0byxgjs0nma";s:9:"bank_name";s:33:"Guaranty Trust Bank (GTBank) Plc.";s:17:"bank_account_name";s:29:"Dzuels Educational Foundation";s:19:"bank_account_number";s:10:"0450072850";s:19:"registration_number";s:16:"CAC/IT/NO 123456";s:15:"social_linkedin";s:20:"https://linkedin.com";s:15:"social_facebook";s:20:"https://facebook.com";s:14:"social_twitter";s:19:"https://twitter.com";s:16:"social_instagram";s:21:"https://instagram.com";}', 2107097007);
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
  `failed_at` timestamp NOT NULL DEFAULT 'current_timestamp()',
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
-- Dumping data for table `settings` (18 rows)
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `created_at`, `updated_at`) VALUES
(1, 'stat_ict_students', '165', 'integer', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'stat_literacy_students', '591', 'integer', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 'stat_schools_supported', '15', 'integer', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 'stat_feed_mode', 'manual', 'string', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(5, 'stat_api_endpoint', '', 'string', 'impact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(6, 'contact_phone_1', '+234 904 249 3883', 'string', 'contact', '2026-10-11 16:36:13', '2026-10-11 16:43:21'),
(7, 'contact_phone_2', '+234 810 000 0000', 'string', 'contact', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
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
(18, 'social_instagram', 'https://instagram.com', 'string', 'social', '2026-10-11 16:36:13', '2026-10-11 16:36:13');
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
(1, 'ASCEND Education Foundation', 'front/images/ascend.jpg', 'https://ascendedufoundation.org/', 1, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'Solape Adesuyi Associates', 'front/images/SAA.jpg', NULL, 2, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 'Help Meet A Need', 'front/images/helpmeetaneed.png', NULL, 3, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 'Int International', 'front/images/intinternational.png', NULL, 4, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
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
-- Dumping data for table `programmes` (4 rows)
--

LOCK TABLES `programmes` WRITE;
/*!40000 ALTER TABLE `programmes` DISABLE KEYS */;
INSERT INTO `programmes` (`id`, `title`, `slug`, `summary`, `body`, `cover_image`, `partner_name`, `gallery_album_id`, `is_featured`, `is_active`, `sort_order`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 'Digital Infrastructure & Literacy in Ijero-Ekiti', 'ijero-digital-literacy', 'Free literacy interventions, digital skills training for teachers and students, and computer lab provisioning across Ijero-Ekiti in Southwest Nigeria.', 'The Digital Infrastructure and Literacy project in Ijero-Ekiti is Dzuels Educational Foundation\'s flagship community technology intervention. Operated in close technical collaboration with the Information Technology Seed Development Initiative (SeedDev), this project addresses the stark digital divide experienced by secondary schools and educators in rural Ekiti State.\n\nThrough this multi-year initiative, we equip local non-profit and public community secondary schools with fully functional computer workstations, reliable power inverters, and offline educational repositories. Our trainers deliver structured weekend and holiday bootcamps covering foundational computer operations, typing skills, digital research, visual productivity suites, and basic coding paradigms for both pupils and teachers.\n\nBy empowering educators with digital lesson planning competencies and introducing young learners to modern technology early, this programme directly unlocks future career pathways in software engineering, digital entrepreneurship, and academic excellence.', 'front/images/dzuelsCollage.webp', 'Information Technology Seed Development Initiative (SeedDev)', NULL, 1, 1, 1, 'Digital Infrastructure & Literacy in Ijero-Ekiti | Dzuels Foundation', 'Equipping public schools with modern digital infrastructure, teacher upskilling, and computer literacy bootcamps for indigent students in Ijero-Ekiti.', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'Innovation and Tech Day (ITD)', 'innovation-and-tech-day', 'Our annual flagship technology festival celebrating youth innovation, hands-on digital exhibitions, robotics demos, and keynote mentorship from tech industry leaders.', 'Innovation and Tech Day (ITD) is our marquee annual showcase where young beneficiaries from across Ekiti State demonstrate their technological inventions, creative software projects, and digital literacy milestones before parents, community leaders, and visiting industry executives.\n\nEach edition features high-energy student hackathons, coding exhibitions, digital art showcases, and inspirational keynote panels hosted by software engineers, healthcare leaders, and academic consultants from Nigeria and the diaspora. Outstanding teams and student innovators receive educational scholarships, laptops, and direct mentorship placement to foster their continued growth.\n\nITD serves as both a celebration of community resilience and a launchpad for the next generation of African technology creators.', 'front/images/event_1.jpg', 'SeedDev & Corporate Tech Allies', NULL, 1, 1, 2, 'Innovation and Tech Day (ITD) | Dzuels Educational Foundation', 'Annual flagship technology exposition celebrating youth innovation, coding exhibitions, and creative problem-solving among Ekiti youth.', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 'Reading Competition', 'reading-competition', 'A grassroots literacy campaign designed to rekindle a passionate reading culture, vocabulary mastery, and textual comprehension among primary and junior secondary students.', 'Reading is the foundational cornerstone of all intellectual development. The Dzuels Reading Competition is an annual literacy advocacy programme that challenges primary and junior secondary pupils across underserved schools to dive deep into literature, African folklore, and contemporary educational texts.\n\nParticipating students receive curated book packs and participate in inter-school reading challenges that assess comprehension, phonetic pronunciation, textual summary, and expressive articulation. In tandem with the competition, our volunteer educators facilitate mobile reading clinics and donate class libraries to partnering schools to sustain reading habits throughout the academic calendar.\n\nBy transforming reading into an exciting, rewarded sport, we build lifelong learners who lead with intellect and curiosity.', 'front/images/about_1.webp', 'ASCEND Education Foundation', NULL, 0, 1, 3, 'Annual Reading Competition | Dzuels Educational Foundation', 'State-wide literacy intervention revitalizing reading culture and comprehension excellence among primary school pupils in Ekiti State.', '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 'Spelling Bee Contests', 'spelling-bee-contests', 'A high-impact inter-school spelling championship enhancing linguistic confidence, memory retention, public poise, and academic camaraderie for young pupils.', 'The Dzuels Spelling Bee Contest brings together hundreds of eager pupils from diverse public and community schools to test their mastery of the English lexicon in a supportive, spirited tournament.\n\nBeyond teaching orthography and etymology, the Spelling Bee builds crucial non-cognitive skills: composure under pressure, mental agility, sportsmanship, and fearless public speaking. Finalists compete on stage before cheering peers, families, and education inspectors, competing for coveted school fee grants, complete textbook suites, and gold medals.\n\nThis initiative proves that with structured practice and compassionate encouragement, every child can achieve extraordinary linguistic and academic heights.', 'front/images/gallery/optimized/spelling-01.webp', NULL, NULL, 0, 1, 4, 'Spelling Bee Contests | Dzuels Educational Foundation', 'Inter-school competition promoting vocabulary acquisition, public speaking poise, and cognitive development for young learners.', '2026-10-11 16:36:13', '2026-10-11 16:45:03');
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
-- Dumping data for table `gallery_albums` (5 rows)
--

LOCK TABLES `gallery_albums` WRITE;
/*!40000 ALTER TABLE `gallery_albums` DISABLE KEYS */;
INSERT INTO `gallery_albums` (`id`, `title`, `slug`, `kicker`, `description`, `cover_image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ITD 2025', 'itd-2025', 'Innovation and Tech Day', 'Highlights from ITD 2025: keynote talks, hands-on coding demonstrations, award moments, and student builders.', 'front/images/gallery/optimized/itd-2025-05.webp', 1, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'ITD 2024', 'itd-2024', 'Inaugural Tech Day', 'Highlights from the maiden ITD 2024 edition: bringing technology education and digital skills to Ijero-Ekiti.', 'front/images/gallery/optimized/itd-2024-01.webp', 2, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 'Reading Competition', 'reading-competition', 'Literacy Outreach', 'Encouraging early literacy, book reading culture, comprehension, and vocabulary among young students.', 'front/images/gallery/optimized/reading-01.webp', 3, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 'Spelling Bee', 'spelling-bee', 'Academic Excellence', 'Spelling Bee tournaments across secondary schools in Ekiti State promoting language mastery and confidence.', 'front/images/gallery/optimized/spelling-01.webp', 4, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(5, 'General', 'general', 'Everyday Impact', 'Mentorship sessions, classroom interventions, community outreach, and equipment distribution moments.', 'front/images/gallery/optimized/general-01.webp', 5, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
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
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `gallery_photos_gallery_album_id_sort_order_index` (`gallery_album_id`, `sort_order`),
  PRIMARY KEY (`id`),
  CONSTRAINT `gallery_photos_gallery_album_id_foreign` FOREIGN KEY (`gallery_album_id`) REFERENCES `gallery_albums` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_photos` (125 rows)
--

LOCK TABLES `gallery_photos` WRITE;
/*!40000 ALTER TABLE `gallery_photos` DISABLE KEYS */;
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'front/images/gallery/optimized/itd-2025-01.webp', 'ITD 2025 Highlight #1', 'ITD 2025 - ITD 2025 Highlight #1 in Ijero-Ekiti', 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 1, 'front/images/gallery/optimized/itd-2025-02.webp', 'ITD 2025 Highlight #2', 'ITD 2025 - ITD 2025 Highlight #2 in Ijero-Ekiti', 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 1, 'front/images/gallery/optimized/itd-2025-03.webp', 'ITD 2025 Highlight #3', 'ITD 2025 - ITD 2025 Highlight #3 in Ijero-Ekiti', 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 1, 'front/images/gallery/optimized/itd-2025-04.webp', 'ITD 2025 Highlight #4', 'ITD 2025 - ITD 2025 Highlight #4 in Ijero-Ekiti', 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(5, 1, 'front/images/gallery/optimized/itd-2025-05.webp', 'ITD 2025 Highlight #5', 'ITD 2025 - ITD 2025 Highlight #5 in Ijero-Ekiti', 5, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(6, 1, 'front/images/gallery/optimized/itd-2025-06.webp', 'ITD 2025 Highlight #6', 'ITD 2025 - ITD 2025 Highlight #6 in Ijero-Ekiti', 6, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(7, 1, 'front/images/gallery/optimized/itd-2025-07.webp', 'ITD 2025 Highlight #7', 'ITD 2025 - ITD 2025 Highlight #7 in Ijero-Ekiti', 7, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(8, 1, 'front/images/gallery/optimized/itd-2025-08.webp', 'ITD 2025 Highlight #8', 'ITD 2025 - ITD 2025 Highlight #8 in Ijero-Ekiti', 8, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(9, 1, 'front/images/gallery/optimized/itd-2025-09.webp', 'ITD 2025 Highlight #9', 'ITD 2025 - ITD 2025 Highlight #9 in Ijero-Ekiti', 9, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(10, 1, 'front/images/gallery/optimized/itd-2025-10.webp', 'ITD 2025 Highlight #10', 'ITD 2025 - ITD 2025 Highlight #10 in Ijero-Ekiti', 10, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(11, 1, 'front/images/gallery/optimized/itd-2025-11.webp', 'ITD 2025 Highlight #11', 'ITD 2025 - ITD 2025 Highlight #11 in Ijero-Ekiti', 11, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(12, 1, 'front/images/gallery/optimized/itd-2025-12.webp', 'ITD 2025 Highlight #12', 'ITD 2025 - ITD 2025 Highlight #12 in Ijero-Ekiti', 12, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(13, 1, 'front/images/gallery/optimized/itd-2025-13.webp', 'ITD 2025 Highlight #13', 'ITD 2025 - ITD 2025 Highlight #13 in Ijero-Ekiti', 13, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(14, 1, 'front/images/gallery/optimized/itd-2025-14.webp', 'ITD 2025 Highlight #14', 'ITD 2025 - ITD 2025 Highlight #14 in Ijero-Ekiti', 14, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(15, 1, 'front/images/gallery/optimized/itd-2025-15.webp', 'ITD 2025 Highlight #15', 'ITD 2025 - ITD 2025 Highlight #15 in Ijero-Ekiti', 15, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(16, 1, 'front/images/gallery/optimized/itd-2025-16.webp', 'ITD 2025 Highlight #16', 'ITD 2025 - ITD 2025 Highlight #16 in Ijero-Ekiti', 16, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(17, 1, 'front/images/gallery/optimized/itd-2025-17.webp', 'ITD 2025 Highlight #17', 'ITD 2025 - ITD 2025 Highlight #17 in Ijero-Ekiti', 17, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(18, 1, 'front/images/gallery/optimized/itd-2025-18.webp', 'ITD 2025 Highlight #18', 'ITD 2025 - ITD 2025 Highlight #18 in Ijero-Ekiti', 18, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(19, 1, 'front/images/gallery/optimized/itd-2025-19.webp', 'ITD 2025 Highlight #19', 'ITD 2025 - ITD 2025 Highlight #19 in Ijero-Ekiti', 19, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(20, 1, 'front/images/gallery/optimized/itd-2025-20.webp', 'ITD 2025 Highlight #20', 'ITD 2025 - ITD 2025 Highlight #20 in Ijero-Ekiti', 20, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(21, 1, 'front/images/gallery/optimized/itd-2025-21.webp', 'ITD 2025 Highlight #21', 'ITD 2025 - ITD 2025 Highlight #21 in Ijero-Ekiti', 21, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(22, 1, 'front/images/gallery/optimized/itd-2025-22.webp', 'ITD 2025 Highlight #22', 'ITD 2025 - ITD 2025 Highlight #22 in Ijero-Ekiti', 22, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(23, 1, 'front/images/gallery/optimized/itd-2025-23.webp', 'ITD 2025 Highlight #23', 'ITD 2025 - ITD 2025 Highlight #23 in Ijero-Ekiti', 23, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(24, 1, 'front/images/gallery/optimized/itd-2025-24.webp', 'ITD 2025 Highlight #24', 'ITD 2025 - ITD 2025 Highlight #24 in Ijero-Ekiti', 24, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(25, 1, 'front/images/gallery/optimized/itd-2025-25.webp', 'ITD 2025 Highlight #25', 'ITD 2025 - ITD 2025 Highlight #25 in Ijero-Ekiti', 25, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(26, 2, 'front/images/gallery/optimized/itd-2024-01.webp', 'ITD 2024 Highlight #1', 'ITD 2024 - ITD 2024 Highlight #1 in Ijero-Ekiti', 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(27, 2, 'front/images/gallery/optimized/itd-2024-02.webp', 'ITD 2024 Highlight #2', 'ITD 2024 - ITD 2024 Highlight #2 in Ijero-Ekiti', 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(28, 2, 'front/images/gallery/optimized/itd-2024-03.webp', 'ITD 2024 Highlight #3', 'ITD 2024 - ITD 2024 Highlight #3 in Ijero-Ekiti', 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(29, 2, 'front/images/gallery/optimized/itd-2024-04.webp', 'ITD 2024 Highlight #4', 'ITD 2024 - ITD 2024 Highlight #4 in Ijero-Ekiti', 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(30, 2, 'front/images/gallery/optimized/itd-2024-05.webp', 'ITD 2024 Highlight #5', 'ITD 2024 - ITD 2024 Highlight #5 in Ijero-Ekiti', 5, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(31, 2, 'front/images/gallery/optimized/itd-2024-06.webp', 'ITD 2024 Highlight #6', 'ITD 2024 - ITD 2024 Highlight #6 in Ijero-Ekiti', 6, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(32, 2, 'front/images/gallery/optimized/itd-2024-07.webp', 'ITD 2024 Highlight #7', 'ITD 2024 - ITD 2024 Highlight #7 in Ijero-Ekiti', 7, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(33, 2, 'front/images/gallery/optimized/itd-2024-08.webp', 'ITD 2024 Highlight #8', 'ITD 2024 - ITD 2024 Highlight #8 in Ijero-Ekiti', 8, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(34, 2, 'front/images/gallery/optimized/itd-2024-09.webp', 'ITD 2024 Highlight #9', 'ITD 2024 - ITD 2024 Highlight #9 in Ijero-Ekiti', 9, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(35, 2, 'front/images/gallery/optimized/itd-2024-10.webp', 'ITD 2024 Highlight #10', 'ITD 2024 - ITD 2024 Highlight #10 in Ijero-Ekiti', 10, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(36, 2, 'front/images/gallery/optimized/itd-2024-11.webp', 'ITD 2024 Highlight #11', 'ITD 2024 - ITD 2024 Highlight #11 in Ijero-Ekiti', 11, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(37, 2, 'front/images/gallery/optimized/itd-2024-12.webp', 'ITD 2024 Highlight #12', 'ITD 2024 - ITD 2024 Highlight #12 in Ijero-Ekiti', 12, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(38, 2, 'front/images/gallery/optimized/itd-2024-13.webp', 'ITD 2024 Highlight #13', 'ITD 2024 - ITD 2024 Highlight #13 in Ijero-Ekiti', 13, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(39, 2, 'front/images/gallery/optimized/itd-2024-14.webp', 'ITD 2024 Highlight #14', 'ITD 2024 - ITD 2024 Highlight #14 in Ijero-Ekiti', 14, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(40, 2, 'front/images/gallery/optimized/itd-2024-15.webp', 'ITD 2024 Highlight #15', 'ITD 2024 - ITD 2024 Highlight #15 in Ijero-Ekiti', 15, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(41, 2, 'front/images/gallery/optimized/itd-2024-16.webp', 'ITD 2024 Highlight #16', 'ITD 2024 - ITD 2024 Highlight #16 in Ijero-Ekiti', 16, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(42, 2, 'front/images/gallery/optimized/itd-2024-17.webp', 'ITD 2024 Highlight #17', 'ITD 2024 - ITD 2024 Highlight #17 in Ijero-Ekiti', 17, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(43, 2, 'front/images/gallery/optimized/itd-2024-18.webp', 'ITD 2024 Highlight #18', 'ITD 2024 - ITD 2024 Highlight #18 in Ijero-Ekiti', 18, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(44, 2, 'front/images/gallery/optimized/itd-2024-19.webp', 'ITD 2024 Highlight #19', 'ITD 2024 - ITD 2024 Highlight #19 in Ijero-Ekiti', 19, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(45, 2, 'front/images/gallery/optimized/itd-2024-20.webp', 'ITD 2024 Highlight #20', 'ITD 2024 - ITD 2024 Highlight #20 in Ijero-Ekiti', 20, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(46, 2, 'front/images/gallery/optimized/itd-2024-21.webp', 'ITD 2024 Highlight #21', 'ITD 2024 - ITD 2024 Highlight #21 in Ijero-Ekiti', 21, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(47, 2, 'front/images/gallery/optimized/itd-2024-22.webp', 'ITD 2024 Highlight #22', 'ITD 2024 - ITD 2024 Highlight #22 in Ijero-Ekiti', 22, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(48, 2, 'front/images/gallery/optimized/itd-2024-23.webp', 'ITD 2024 Highlight #23', 'ITD 2024 - ITD 2024 Highlight #23 in Ijero-Ekiti', 23, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(49, 2, 'front/images/gallery/optimized/itd-2024-24.webp', 'ITD 2024 Highlight #24', 'ITD 2024 - ITD 2024 Highlight #24 in Ijero-Ekiti', 24, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(50, 2, 'front/images/gallery/optimized/itd-2024-25.webp', 'ITD 2024 Highlight #25', 'ITD 2024 - ITD 2024 Highlight #25 in Ijero-Ekiti', 25, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(51, 3, 'front/images/gallery/optimized/reading-01.webp', 'Reading Competition Moment #1', 'Reading Competition - Reading Competition Moment #1 in Ijero-Ekiti', 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(52, 3, 'front/images/gallery/optimized/reading-02.webp', 'Reading Competition Moment #2', 'Reading Competition - Reading Competition Moment #2 in Ijero-Ekiti', 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(53, 3, 'front/images/gallery/optimized/reading-03.webp', 'Reading Competition Moment #3', 'Reading Competition - Reading Competition Moment #3 in Ijero-Ekiti', 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(54, 3, 'front/images/gallery/optimized/reading-04.webp', 'Reading Competition Moment #4', 'Reading Competition - Reading Competition Moment #4 in Ijero-Ekiti', 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(55, 3, 'front/images/gallery/optimized/reading-05.webp', 'Reading Competition Moment #5', 'Reading Competition - Reading Competition Moment #5 in Ijero-Ekiti', 5, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(56, 3, 'front/images/gallery/optimized/reading-06.webp', 'Reading Competition Moment #6', 'Reading Competition - Reading Competition Moment #6 in Ijero-Ekiti', 6, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(57, 3, 'front/images/gallery/optimized/reading-07.webp', 'Reading Competition Moment #7', 'Reading Competition - Reading Competition Moment #7 in Ijero-Ekiti', 7, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(58, 3, 'front/images/gallery/optimized/reading-08.webp', 'Reading Competition Moment #8', 'Reading Competition - Reading Competition Moment #8 in Ijero-Ekiti', 8, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(59, 3, 'front/images/gallery/optimized/reading-09.webp', 'Reading Competition Moment #9', 'Reading Competition - Reading Competition Moment #9 in Ijero-Ekiti', 9, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(60, 3, 'front/images/gallery/optimized/reading-10.webp', 'Reading Competition Moment #10', 'Reading Competition - Reading Competition Moment #10 in Ijero-Ekiti', 10, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(61, 3, 'front/images/gallery/optimized/reading-11.webp', 'Reading Competition Moment #11', 'Reading Competition - Reading Competition Moment #11 in Ijero-Ekiti', 11, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(62, 3, 'front/images/gallery/optimized/reading-12.webp', 'Reading Competition Moment #12', 'Reading Competition - Reading Competition Moment #12 in Ijero-Ekiti', 12, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(63, 3, 'front/images/gallery/optimized/reading-13.webp', 'Reading Competition Moment #13', 'Reading Competition - Reading Competition Moment #13 in Ijero-Ekiti', 13, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(64, 3, 'front/images/gallery/optimized/reading-14.webp', 'Reading Competition Moment #14', 'Reading Competition - Reading Competition Moment #14 in Ijero-Ekiti', 14, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(65, 3, 'front/images/gallery/optimized/reading-15.webp', 'Reading Competition Moment #15', 'Reading Competition - Reading Competition Moment #15 in Ijero-Ekiti', 15, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(66, 3, 'front/images/gallery/optimized/reading-16.webp', 'Reading Competition Moment #16', 'Reading Competition - Reading Competition Moment #16 in Ijero-Ekiti', 16, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(67, 3, 'front/images/gallery/optimized/reading-17.webp', 'Reading Competition Moment #17', 'Reading Competition - Reading Competition Moment #17 in Ijero-Ekiti', 17, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(68, 3, 'front/images/gallery/optimized/reading-18.webp', 'Reading Competition Moment #18', 'Reading Competition - Reading Competition Moment #18 in Ijero-Ekiti', 18, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(69, 3, 'front/images/gallery/optimized/reading-19.webp', 'Reading Competition Moment #19', 'Reading Competition - Reading Competition Moment #19 in Ijero-Ekiti', 19, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(70, 3, 'front/images/gallery/optimized/reading-20.webp', 'Reading Competition Moment #20', 'Reading Competition - Reading Competition Moment #20 in Ijero-Ekiti', 20, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(71, 3, 'front/images/gallery/optimized/reading-21.webp', 'Reading Competition Moment #21', 'Reading Competition - Reading Competition Moment #21 in Ijero-Ekiti', 21, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(72, 3, 'front/images/gallery/optimized/reading-22.webp', 'Reading Competition Moment #22', 'Reading Competition - Reading Competition Moment #22 in Ijero-Ekiti', 22, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(73, 3, 'front/images/gallery/optimized/reading-23.webp', 'Reading Competition Moment #23', 'Reading Competition - Reading Competition Moment #23 in Ijero-Ekiti', 23, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(74, 3, 'front/images/gallery/optimized/reading-24.webp', 'Reading Competition Moment #24', 'Reading Competition - Reading Competition Moment #24 in Ijero-Ekiti', 24, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(75, 3, 'front/images/gallery/optimized/reading-25.webp', 'Reading Competition Moment #25', 'Reading Competition - Reading Competition Moment #25 in Ijero-Ekiti', 25, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(76, 4, 'front/images/gallery/optimized/spelling-01.webp', 'Spelling Bee Contestant #1', 'Spelling Bee - Spelling Bee Contestant #1 in Ijero-Ekiti', 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(77, 4, 'front/images/gallery/optimized/spelling-02.webp', 'Spelling Bee Contestant #2', 'Spelling Bee - Spelling Bee Contestant #2 in Ijero-Ekiti', 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(78, 4, 'front/images/gallery/optimized/spelling-03.webp', 'Spelling Bee Contestant #3', 'Spelling Bee - Spelling Bee Contestant #3 in Ijero-Ekiti', 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(79, 4, 'front/images/gallery/optimized/spelling-04.webp', 'Spelling Bee Contestant #4', 'Spelling Bee - Spelling Bee Contestant #4 in Ijero-Ekiti', 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(80, 4, 'front/images/gallery/optimized/spelling-05.webp', 'Spelling Bee Contestant #5', 'Spelling Bee - Spelling Bee Contestant #5 in Ijero-Ekiti', 5, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(81, 4, 'front/images/gallery/optimized/spelling-06.webp', 'Spelling Bee Contestant #6', 'Spelling Bee - Spelling Bee Contestant #6 in Ijero-Ekiti', 6, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(82, 4, 'front/images/gallery/optimized/spelling-07.webp', 'Spelling Bee Contestant #7', 'Spelling Bee - Spelling Bee Contestant #7 in Ijero-Ekiti', 7, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(83, 4, 'front/images/gallery/optimized/spelling-08.webp', 'Spelling Bee Contestant #8', 'Spelling Bee - Spelling Bee Contestant #8 in Ijero-Ekiti', 8, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(84, 4, 'front/images/gallery/optimized/spelling-09.webp', 'Spelling Bee Contestant #9', 'Spelling Bee - Spelling Bee Contestant #9 in Ijero-Ekiti', 9, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(85, 4, 'front/images/gallery/optimized/spelling-10.webp', 'Spelling Bee Contestant #10', 'Spelling Bee - Spelling Bee Contestant #10 in Ijero-Ekiti', 10, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(86, 4, 'front/images/gallery/optimized/spelling-11.webp', 'Spelling Bee Contestant #11', 'Spelling Bee - Spelling Bee Contestant #11 in Ijero-Ekiti', 11, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(87, 4, 'front/images/gallery/optimized/spelling-12.webp', 'Spelling Bee Contestant #12', 'Spelling Bee - Spelling Bee Contestant #12 in Ijero-Ekiti', 12, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(88, 4, 'front/images/gallery/optimized/spelling-13.webp', 'Spelling Bee Contestant #13', 'Spelling Bee - Spelling Bee Contestant #13 in Ijero-Ekiti', 13, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(89, 4, 'front/images/gallery/optimized/spelling-14.webp', 'Spelling Bee Contestant #14', 'Spelling Bee - Spelling Bee Contestant #14 in Ijero-Ekiti', 14, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(90, 4, 'front/images/gallery/optimized/spelling-15.webp', 'Spelling Bee Contestant #15', 'Spelling Bee - Spelling Bee Contestant #15 in Ijero-Ekiti', 15, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(91, 4, 'front/images/gallery/optimized/spelling-16.webp', 'Spelling Bee Contestant #16', 'Spelling Bee - Spelling Bee Contestant #16 in Ijero-Ekiti', 16, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(92, 4, 'front/images/gallery/optimized/spelling-17.webp', 'Spelling Bee Contestant #17', 'Spelling Bee - Spelling Bee Contestant #17 in Ijero-Ekiti', 17, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(93, 4, 'front/images/gallery/optimized/spelling-18.webp', 'Spelling Bee Contestant #18', 'Spelling Bee - Spelling Bee Contestant #18 in Ijero-Ekiti', 18, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(94, 4, 'front/images/gallery/optimized/spelling-19.webp', 'Spelling Bee Contestant #19', 'Spelling Bee - Spelling Bee Contestant #19 in Ijero-Ekiti', 19, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(95, 4, 'front/images/gallery/optimized/spelling-20.webp', 'Spelling Bee Contestant #20', 'Spelling Bee - Spelling Bee Contestant #20 in Ijero-Ekiti', 20, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(96, 4, 'front/images/gallery/optimized/spelling-21.webp', 'Spelling Bee Contestant #21', 'Spelling Bee - Spelling Bee Contestant #21 in Ijero-Ekiti', 21, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(97, 4, 'front/images/gallery/optimized/spelling-22.webp', 'Spelling Bee Contestant #22', 'Spelling Bee - Spelling Bee Contestant #22 in Ijero-Ekiti', 22, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(98, 4, 'front/images/gallery/optimized/spelling-23.webp', 'Spelling Bee Contestant #23', 'Spelling Bee - Spelling Bee Contestant #23 in Ijero-Ekiti', 23, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(99, 4, 'front/images/gallery/optimized/spelling-24.webp', 'Spelling Bee Contestant #24', 'Spelling Bee - Spelling Bee Contestant #24 in Ijero-Ekiti', 24, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(100, 4, 'front/images/gallery/optimized/spelling-25.webp', 'Spelling Bee Contestant #25', 'Spelling Bee - Spelling Bee Contestant #25 in Ijero-Ekiti', 25, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
INSERT INTO `gallery_photos` (`id`, `gallery_album_id`, `image_path`, `caption`, `alt_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(101, 5, 'front/images/gallery/optimized/general-01.webp', 'Community Impact Moment #1', 'General - Community Impact Moment #1 in Ijero-Ekiti', 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(102, 5, 'front/images/gallery/optimized/general-02.webp', 'Community Impact Moment #2', 'General - Community Impact Moment #2 in Ijero-Ekiti', 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(103, 5, 'front/images/gallery/optimized/general-03.webp', 'Community Impact Moment #3', 'General - Community Impact Moment #3 in Ijero-Ekiti', 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(104, 5, 'front/images/gallery/optimized/general-04.webp', 'Community Impact Moment #4', 'General - Community Impact Moment #4 in Ijero-Ekiti', 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(105, 5, 'front/images/gallery/optimized/general-05.webp', 'Community Impact Moment #5', 'General - Community Impact Moment #5 in Ijero-Ekiti', 5, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(106, 5, 'front/images/gallery/optimized/general-06.webp', 'Community Impact Moment #6', 'General - Community Impact Moment #6 in Ijero-Ekiti', 6, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(107, 5, 'front/images/gallery/optimized/general-07.webp', 'Community Impact Moment #7', 'General - Community Impact Moment #7 in Ijero-Ekiti', 7, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(108, 5, 'front/images/gallery/optimized/general-08.webp', 'Community Impact Moment #8', 'General - Community Impact Moment #8 in Ijero-Ekiti', 8, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(109, 5, 'front/images/gallery/optimized/general-09.webp', 'Community Impact Moment #9', 'General - Community Impact Moment #9 in Ijero-Ekiti', 9, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(110, 5, 'front/images/gallery/optimized/general-10.webp', 'Community Impact Moment #10', 'General - Community Impact Moment #10 in Ijero-Ekiti', 10, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(111, 5, 'front/images/gallery/optimized/general-11.webp', 'Community Impact Moment #11', 'General - Community Impact Moment #11 in Ijero-Ekiti', 11, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(112, 5, 'front/images/gallery/optimized/general-12.webp', 'Community Impact Moment #12', 'General - Community Impact Moment #12 in Ijero-Ekiti', 12, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(113, 5, 'front/images/gallery/optimized/general-13.webp', 'Community Impact Moment #13', 'General - Community Impact Moment #13 in Ijero-Ekiti', 13, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(114, 5, 'front/images/gallery/optimized/general-14.webp', 'Community Impact Moment #14', 'General - Community Impact Moment #14 in Ijero-Ekiti', 14, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(115, 5, 'front/images/gallery/optimized/general-15.webp', 'Community Impact Moment #15', 'General - Community Impact Moment #15 in Ijero-Ekiti', 15, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(116, 5, 'front/images/gallery/optimized/general-16.webp', 'Community Impact Moment #16', 'General - Community Impact Moment #16 in Ijero-Ekiti', 16, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(117, 5, 'front/images/gallery/optimized/general-17.webp', 'Community Impact Moment #17', 'General - Community Impact Moment #17 in Ijero-Ekiti', 17, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(118, 5, 'front/images/gallery/optimized/general-18.webp', 'Community Impact Moment #18', 'General - Community Impact Moment #18 in Ijero-Ekiti', 18, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(119, 5, 'front/images/gallery/optimized/general-19.webp', 'Community Impact Moment #19', 'General - Community Impact Moment #19 in Ijero-Ekiti', 19, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(120, 5, 'front/images/gallery/optimized/general-20.webp', 'Community Impact Moment #20', 'General - Community Impact Moment #20 in Ijero-Ekiti', 20, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(121, 5, 'front/images/gallery/optimized/general-21.webp', 'Community Impact Moment #21', 'General - Community Impact Moment #21 in Ijero-Ekiti', 21, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(122, 5, 'front/images/gallery/optimized/general-22.webp', 'Community Impact Moment #22', 'General - Community Impact Moment #22 in Ijero-Ekiti', 22, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(123, 5, 'front/images/gallery/optimized/general-23.webp', 'Community Impact Moment #23', 'General - Community Impact Moment #23 in Ijero-Ekiti', 23, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(124, 5, 'front/images/gallery/optimized/general-24.webp', 'Community Impact Moment #24', 'General - Community Impact Moment #24 in Ijero-Ekiti', 24, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(125, 5, 'front/images/gallery/optimized/general-25.webp', 'Community Impact Moment #25', 'General - Community Impact Moment #25 in Ijero-Ekiti', 25, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
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
-- Dumping data for table `page_sections` (19 rows)
--

LOCK TABLES `page_sections` WRITE;
/*!40000 ALTER TABLE `page_sections` DISABLE KEYS */;
INSERT INTO `page_sections` (`id`, `page_key`, `section_key`, `headline`, `subheadline`, `body_text`, `image_path`, `button_text`, `button_url`, `meta_data`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'home', 'hero', 'Addressing poverty through quality education and skills acquisition', 'Dzuels Educational Foundation', 'Empowering indigent Nigerian children, youth, and educators with free digital literacy, school support, leadership mentoring, and community welfare.', 'front/images/home_1.webp', 'Donate Now', '/donate', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(2, 'home', 'who_we_are', 'Dedicated to Transforming Lives and Communities Through Education', 'Who We Are', 'Dzuels Educational Foundation is a non-profit organisation dedicated to addressing poverty by providing quality education and skills acquisition for indigent children and youth across Nigeria. Founded in 2015, we bridge educational inequality by equipping underserved communities with modern digital skills, literacy support, and vital infrastructure.\n\nOur initiatives ensure that children from low-income backgrounds have the tools, guidance, and environments required to thrive in a rapidly changing world.', 'front/images/home_about.webp', 'Learn More About Us', '/about', NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(3, 'home', 'vision_mission', 'Our Core Purpose & Guiding Principles', 'Vision & Mission', 'Vision: An equitable society where every child, irrespective of socio-economic background, has access to quality education, modern digital literacy, and the opportunity to achieve their highest human potential.\n\nMission: To dismantle educational barriers for indigent youth in Nigeria by deploying digital classrooms, scholarship support, literacy outreach, and mentorship that spark lifelong empowerment.', NULL, NULL, NULL, NULL, 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(4, 'home', 'values', 'Our DRNICER Core Values', 'Guiding Principles', 'Dynamism, Resilience, Novelty, Integrity, Compassion, Excellence, Responsibility — these principles define every classroom we build and partnership we form.', NULL, NULL, NULL, NULL, 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(5, 'home', 'causes', 'Programs That Bridge the Opportunity Divide', 'Our Impact Causes', 'From digital hubs in Ijero-Ekiti to annual literacy contests, our causes are designed to uplift communities from the ground up.', 'front/images/cause.webp', NULL, NULL, NULL, 5, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(6, 'home', 'featured_project', 'Ijero-Ekiti Digital Infrastructure and Literacy Initiative', 'Flagship Project', 'Establishing modern computer laboratories and solar-powered learning facilities in public schools across Ijero-Ekiti, equipping over 165 students with practical coding and computer skills.', 'front/images/dzuelsCollage.webp', 'Explore Our Programmes', '/programmes', NULL, 6, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(7, 'home', 'cta', 'Join Us in Shaping the Future of Nigeria\'s Youth', 'Take Action Today', 'Whether through corporate sponsorship, volunteer mentoring, or financial donations, your partnership empowers the next generation.', NULL, 'Get Involved Today', '/get-involved', NULL, 7, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(8, 'about', 'hero', 'A Decade of Impact, Education & Hope', 'About Dzuels Educational Foundation', 'Founded in 2015, Dzuels Educational Foundation has grown from a local literacy initiative in Ekiti State into an impactful non-profit organisation driving digital empowerment and community welfare across Nigeria.', 'front/images/about/story-banner.webp', 'Support Our Mission', '/donate', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(9, 'about', 'story', 'Our 10-Year Journey of Community Empowerment', 'Our Story', 'What started as an urgent response to learning resource shortages in rural schools has evolved into a comprehensive educational movement.\n\nOver the past ten years, we have trained hundreds of young Nigerians in modern computational thinking, distributed essential learning supplies, and renovated learning spaces in public secondary and primary schools.', 'front/images/about/approach-banner.webp', NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(10, 'about', 'approach', 'Grassroots Engagement & Sustainable Innovation', 'Our Approach', 'We partner directly with schools, community leaders, and local governments to identify critical gaps and deploy sustainable, high-impact educational solutions tailored to underserved youth.', NULL, NULL, NULL, NULL, 3, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(11, 'about', 'leadership', 'Board of Trustees, Management & Advisory Board', 'Foundation Governance', 'Guided by dedicated educators, technologists, and community leaders committed to absolute transparency, integrity, and social stewardship.', NULL, NULL, NULL, NULL, 4, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(12, 'programmes', 'hero', 'Sustainable Interventions Empowering Students and Schools', 'Our Programmes', 'Explore our targeted initiatives focused on digital literacy, STEM training, academic competitions, and teacher development across Ekiti State.', 'front/images/programmes/hero-banner.webp', 'Partner on a Programme', '/get-involved', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(13, 'programmes', 'overview', 'Targeted Initiatives for Lasting Community Impact', 'Empowering Next-Gen Leaders', 'Every programme is designed with measurable outcomes: improving student academic performance, fostering digital confidence, and preparing youth for future careers.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(14, 'contact', 'hero', 'We Would Love to Hear From You', 'Get in Touch', 'Have questions about our initiatives, want to partner, or need more information? Reach out to our dedicated team.', 'front/images/contact/hero-banner.webp', NULL, NULL, NULL, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(15, 'contact', 'office', 'Our Head Office & Learning Centers', 'Visit Our Center', 'AAoJ Memorial Learning Center, Ijero-Ekiti, Ekiti State, Nigeria. Open Monday through Friday, 8:00 AM to 5:00 PM.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(16, 'donate', 'hero', 'Every Contribution Changes a Child\'s Future', 'Invest in Education', '100% of your generous gifts directly fund classroom infrastructure, student learning kits, and free digital training for indigent youth.', 'front/images/donate/hero-banner.webp', 'Give Online via Flutterwave', 'https://flutterwave.com/pay/j0byxgjs0nma', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(17, 'donate', 'where_your_gift_goes', 'Transparent Allocation of Every Naira', 'Where Your Gift Goes', 'We ensure absolute financial accountability. Donations support digital lab computers, solar power backups, school supplies, scholarship grants, and teacher enablement.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(18, 'get_involved', 'hero', 'Lend Your Skills, Time, or Resources to Transform Education', 'Get Involved', 'Join our network of passionate volunteers and visionary corporate partners making an indelible mark on Nigerian education.', 'front/images/volunteer/hero-banner.webp', 'Apply to Volunteer', '#volunteer-form', NULL, 1, '2026-10-11 16:36:13', '2026-10-11 16:36:13'),
(19, 'get_involved', 'intro', 'Volunteer or Partner With Dzuels', 'Create Lasting Change', 'Whether you want to mentor young coders during Ijero Tech Day or sponsor a digital laboratory in a public school, there is a place for you in our mission.', NULL, NULL, NULL, NULL, 2, '2026-10-11 16:36:13', '2026-10-11 16:36:13');
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
