-- Add occupation field to alumnus_bio table
-- This migration adds a current occupation field for alumni profiles

USE `sccalumni_db`;

-- Add occupation column to alumnus_bio table
ALTER TABLE `alumnus_bio` 
ADD COLUMN `occupation` varchar(255) DEFAULT NULL COMMENT 'Current occupation/job title' 
AFTER `address`;

-- Add index for occupation field for better search performance
ALTER TABLE `alumnus_bio` 
ADD INDEX `idx_occupation` (`occupation`);
