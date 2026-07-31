-- ============================================
-- SQL Changes for Today (2025-01-20 & 2025-01-21)
-- Run these queries on your server database
-- ============================================

-- ============================================
-- 1. Add 'sort' column to categories table
-- ============================================
-- Migration: 2025_01_20_000000_add_sort_to_categories_table.php
-- Purpose: Add sort field for ordering categories on home page
ALTER TABLE `categories` 
ADD COLUMN `sort` INT(11) DEFAULT 0 AFTER `show_on_home`;

-- ============================================
-- 2. Add 'youtube' column to setting table
-- ============================================
-- Migration: 2025_01_21_000000_add_youtube_to_settings_table.php
-- Purpose: Add YouTube link field for social media settings
ALTER TABLE `setting` 
ADD COLUMN `youtube` VARCHAR(255) NULL AFTER `pinterest`;

-- ============================================
-- Verification Queries (Optional - to check if columns exist)
-- ============================================
-- Check if sort column exists in categories:
-- DESCRIBE `categories`;

-- Check if youtube column exists in setting:
-- DESCRIBE `setting`;

-- ============================================
-- Rollback Queries (if needed to undo changes)
-- ============================================
-- To remove sort column:
-- ALTER TABLE `categories` DROP COLUMN `sort`;

-- To remove youtube column:
-- ALTER TABLE `setting` DROP COLUMN `youtube`;

