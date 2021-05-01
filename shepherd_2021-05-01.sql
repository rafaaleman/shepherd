# ************************************************************
# Sequel Pro SQL dump
# Versión 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.30)
# Base de datos: shepherd
# Tiempo de Generación: 2021-05-01 10:19:56 p.m. +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Volcado de tabla careteams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `careteams`;

CREATE TABLE `careteams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `loveone_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `relationship_id` int(11) NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `status` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `careteams` WRITE;
/*!40000 ALTER TABLE `careteams` DISABLE KEYS */;

INSERT INTO `careteams` (`id`, `loveone_id`, `user_id`, `relationship_id`, `role`, `permissions`, `status`, `created_at`, `updated_at`)
VALUES
	(1,1,1,2,'admin','a:4:{s:7:\"carehub\";i:1;s:7:\"lockbox\";i:1;s:7:\"medlist\";i:1;s:9:\"resources\";i:1;}','1','2021-04-25 00:42:19','2021-04-25 00:42:19'),
	(2,1,2,7,'medical','a:4:{s:7:\"carehub\";i:0;s:7:\"lockbox\";i:0;s:7:\"medlist\";i:1;s:9:\"resources\";i:0;}','1','2021-04-27 17:29:15','2021-04-27 20:12:04'),
	(3,1,4,6,'member','a:4:{s:7:\"carehub\";i:1;s:7:\"lockbox\";i:1;s:7:\"medlist\";i:0;s:9:\"resources\";i:0;}','1','2021-04-27 17:41:31','2021-05-01 01:29:30'),
	(8,1,9,3,'legal','a:4:{s:7:\"carehub\";i:0;s:7:\"lockbox\";i:0;s:7:\"medlist\";i:1;s:9:\"resources\";i:0;}','1','2021-04-27 18:18:27','2021-04-27 20:14:59'),
	(9,1,15,4,'member','a:4:{s:7:\"carehub\";i:0;s:7:\"lockbox\";i:0;s:7:\"medlist\";i:0;s:9:\"resources\";i:0;}','1','2021-05-01 00:05:11','2021-05-01 00:05:11'),
	(10,1,16,3,'associate','a:4:{s:7:\"carehub\";i:1;s:7:\"lockbox\";i:1;s:7:\"medlist\";i:1;s:9:\"resources\";i:0;}','1','2021-05-01 00:06:47','2021-05-01 16:03:15'),
	(11,2,1,5,'admin','a:4:{s:7:\"carehub\";i:1;s:7:\"lockbox\";i:1;s:7:\"medlist\";i:1;s:9:\"resources\";i:1;}','1','2021-05-01 19:45:27','2021-05-01 19:45:27'),
	(12,3,1,5,'admin','a:4:{s:7:\"carehub\";i:1;s:7:\"lockbox\";i:1;s:7:\"medlist\";i:1;s:9:\"resources\";i:1;}','1','2021-05-01 19:47:17','2021-05-01 19:47:17');

/*!40000 ALTER TABLE `careteams` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla conditions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `conditions`;

CREATE TABLE `conditions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `conditions` WRITE;
/*!40000 ALTER TABLE `conditions` DISABLE KEYS */;

INSERT INTO `conditions` (`id`, `name`, `status`, `created_at`, `updated_at`)
VALUES
	(1,'Alzheimer Disease',1,NULL,NULL),
	(2,'Arthritis',1,NULL,NULL),
	(3,'Asthma',1,NULL,NULL),
	(4,'Cancer',1,NULL,NULL),
	(5,'Diabetes',1,NULL,NULL),
	(6,'Heart Disease',1,NULL,NULL),
	(7,'Osteoporosis',1,NULL,NULL),
	(8,'Other',1,NULL,NULL);

/*!40000 ALTER TABLE `conditions` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Volcado de tabla loveones
# ------------------------------------------------------------

DROP TABLE IF EXISTS `loveones`;

CREATE TABLE `loveones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medication_ids` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loveones_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `loveones` WRITE;
/*!40000 ALTER TABLE `loveones` DISABLE KEYS */;

INSERT INTO `loveones` (`id`, `firstname`, `lastname`, `slug`, `email`, `phone`, `address`, `dob`, `photo`, `condition_ids`, `medication_ids`, `status`, `created_at`, `updated_at`)
VALUES
	(1,'Grandpa','Lastname','grandpa-lastname-1619311339','rafa.aleman@gmail.com','525541869725','LAURELES 88, JARDINES DE ATIZAPAN','2021-04-21',NULL,'3,5',NULL,1,'2021-04-25 00:42:19','2021-04-25 00:42:19'),
	(3,'Grand Ma','Lastname','grand-ma-lastname-1619898437','mail@mail.com','9098098098','lkjlkjlkjlkjlkj','2021-05-13','/loveones/3/9098098098.png','4',NULL,1,'2021-05-01 19:47:17','2021-05-01 19:47:17');

/*!40000 ALTER TABLE `loveones` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(35,'2021_04_22_190343_create_realtionships_table',1),
	(37,'2014_10_12_000000_create_users_table',2),
	(38,'2014_10_12_100000_create_password_resets_table',2),
	(39,'2019_08_19_000000_create_failed_jobs_table',2),
	(40,'2021_04_22_164621_add_extra_to_users_table',2),
	(41,'2021_04_22_173951_create_careteams_table',2),
	(42,'2021_04_22_184457_create_loveones_table',2),
	(43,'2021_04_22_190529_create_conditions_table',3);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Volcado de tabla relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `relationships`;

CREATE TABLE `relationships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `relationships` WRITE;
/*!40000 ALTER TABLE `relationships` DISABLE KEYS */;

INSERT INTO `relationships` (`id`, `name`, `status`, `created_at`, `updated_at`)
VALUES
	(1,'Grandparent',1,'2021-04-22 00:00:00','2021-04-22 00:00:00'),
	(2,'Parent',1,'2021-04-22 00:00:00','2021-04-22 00:00:00'),
	(3,'Relative',1,'2021-04-22 00:00:00','2021-04-22 00:00:00'),
	(4,'Spouse/Partner',1,'2021-04-22 00:00:00','2021-04-22 00:00:00'),
	(5,'Friend',1,'2021-04-22 00:00:00','2021-04-22 00:00:00'),
	(6,'Child',1,'2021-04-22 00:00:00','2021-04-22 00:00:00'),
	(7,'Other',1,'2021-04-22 00:00:00','2021-04-22 00:00:00');

/*!40000 ALTER TABLE `relationships` ENABLE KEYS */;
UNLOCK TABLES;


# Volcado de tabla users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `lastname`, `phone`, `address`, `photo`, `status`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1,'Rafa','Ale','525541869725','LAURELES 88, JARDINES DE ATIZAPAN','',1,'rafa.aleman@gmail.com',NULL,'$2y$10$OkY9Bm5HHdyXaPZG/OZPUOztacf9QVF/c8vPr.X1l.w9vuyouXjEu',NULL,'2021-04-25 00:19:55','2021-04-25 00:19:55'),
	(2,'Dr. House','Harris','123456789','Address','',1,'drhouse@mail.com',NULL,'$2y$10$5YZifheAeNp9RJaHgVGIcutCk8Gqg3./3CRirSZYOBNv.gaFpVyd6',NULL,'2021-04-27 17:29:15','2021-04-27 17:29:15'),
	(4,'Son','One','123456789','some addrs','',1,'son@mail.com',NULL,'$2y$10$qAz0vdUvfZ00lfS7fVitXesuJeVb32e43j5QsXRowdzEA2ADGBqWW',NULL,'2021-04-27 17:41:31','2021-04-27 17:41:31'),
	(16,'Rafathree','Ale','3123122222','767 5th Avenue','/loveones/1/members/rafa.3__gmail.com.png',1,'rafa.3@gmail.com',NULL,'$2y$10$DuA7tV84ZrzQcn.AceFVA.pwuePCDSDxVlF.3xHVn.OiBQ0CyL1I.',NULL,'2021-05-01 00:06:47','2021-05-01 00:06:47');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
