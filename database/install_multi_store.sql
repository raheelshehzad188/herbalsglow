-- Multi-store platform install (run on MySQL after backup)
-- Usage: mysql -u USER -p DATABASE < database/install_multi_store.sql

CREATE TABLE IF NOT EXISTS `stores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `active_theme` tinyint unsigned NOT NULL DEFAULT 3,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `currency` varchar(10) NOT NULL DEFAULT 'PKR',
  `timezone` varchar(100) NOT NULL DEFAULT 'Asia/Karachi',
  `logo` text,
  `wlogo` text,
  `meta_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `tiktok_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stores_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_domains` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint unsigned NOT NULL,
  `domain` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_domains_domain_unique` (`domain`),
  KEY `store_domains_store_id_index` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `store_integrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint unsigned NOT NULL,
  `provider` varchar(50) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `catalog_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `events_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `access_token` text,
  `catalog_id` text,
  `pixel_id` text,
  `ad_account_id` text,
  `extra_json` text,
  `connected_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_integrations_store_provider` (`store_id`,`provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admins columns (ignore errors if already exist)
ALTER TABLE `admins` ADD COLUMN `name` varchar(255) NULL AFTER `id`;
ALTER TABLE `admins` ADD COLUMN `role` varchar(50) NOT NULL DEFAULT 'store_admin' AFTER `password`;
ALTER TABLE `admins` ADD COLUMN `store_id` bigint unsigned NULL AFTER `role`;
ALTER TABLE `admins` ADD COLUMN `status` varchar(50) NOT NULL DEFAULT 'active' AFTER `store_id`;
ALTER TABLE `setting` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;

UPDATE `admins` SET `role`='super_admin', `name`=COALESCE(`name`,'Super Admin') WHERE `id`=1;
