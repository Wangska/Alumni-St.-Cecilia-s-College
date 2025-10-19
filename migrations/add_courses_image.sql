-- Add image column to courses table
ALTER TABLE `courses`
ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL AFTER `about`;
