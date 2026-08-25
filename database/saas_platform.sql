CREATE TABLE IF NOT EXISTS `saas_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `skey` varchar(120) NOT NULL,
  `svalue` longtext,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `saas_settings_skey` (`skey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `saas_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `audience` varchar(190) DEFAULT NULL,
  `price_label` varchar(80) DEFAULT NULL,
  `price_amount` int DEFAULT 0,
  `highlight` tinyint(1) NOT NULL DEFAULT 0,
  `features` longtext,
  `button_text` varchar(80) DEFAULT 'Start 7-day free trial',
  `sort` int DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `saas_themes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `category` varchar(80) DEFAULT 'Fashion',
  `description` text,
  `image` text,
  `demo_url` varchar(255) DEFAULT NULL,
  `engine_theme` tinyint unsigned DEFAULT 3,
  `sort` int DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `saas_apps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `slug` varchar(190) NOT NULL,
  `category` varchar(80) DEFAULT 'Marketing',
  `description` text,
  `icon` varchar(20) DEFAULT NULL,
  `color` varchar(20) DEFAULT '#111',
  `sort` int DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `saas_faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text,
  `sort` int DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `saas_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(40) NOT NULL DEFAULT 'home',
  `title` varchar(190) NOT NULL,
  `body` text,
  `icon` varchar(40) DEFAULT NULL,
  `sort` int DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
