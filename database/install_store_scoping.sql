-- Phase 2: store_id on catalog + orders (run after install_multi_store.sql)
-- mysql -u USER -p DB < database/install_store_scoping.sql

ALTER TABLE `products` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `products` ADD INDEX `products_store_id_index` (`store_id`);

ALTER TABLE `categories` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `categories` ADD INDEX `categories_store_id_index` (`store_id`);

ALTER TABLE `sub_categories` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `sub_categories` ADD INDEX `sub_categories_store_id_index` (`store_id`);

ALTER TABLE `brands` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `brands` ADD INDEX `brands_store_id_index` (`store_id`);

ALTER TABLE `orders` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `orders` ADD INDEX `orders_store_id_index` (`store_id`);

ALTER TABLE `custom_order` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `custom_order` ADD INDEX `custom_order_store_id_index` (`store_id`);

ALTER TABLE `products_to_meta` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `categories_to_meta` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `galleries` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `faq` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `rating` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `sliders` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;
ALTER TABLE `pages` ADD COLUMN `store_id` bigint unsigned NULL AFTER `id`;

UPDATE `products` p
JOIN (SELECT id FROM stores ORDER BY id LIMIT 1) s
SET p.store_id = s.id
WHERE p.store_id IS NULL;

UPDATE `categories` c
JOIN (SELECT id FROM stores ORDER BY id LIMIT 1) s
SET c.store_id = s.id
WHERE c.store_id IS NULL;

UPDATE `sub_categories` c
JOIN (SELECT id FROM stores ORDER BY id LIMIT 1) s
SET c.store_id = s.id
WHERE c.store_id IS NULL;

UPDATE `brands` b
JOIN (SELECT id FROM stores ORDER BY id LIMIT 1) s
SET b.store_id = s.id
WHERE b.store_id IS NULL;

UPDATE `orders` o
JOIN (SELECT id FROM stores ORDER BY id LIMIT 1) s
SET o.store_id = s.id
WHERE o.store_id IS NULL;

UPDATE `setting` st
JOIN (SELECT id FROM stores ORDER BY id LIMIT 1) s
SET st.store_id = s.id
WHERE st.store_id IS NULL;
