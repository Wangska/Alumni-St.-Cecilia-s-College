-- Add image_path column to gallery table for storing filenames
-- Use 'about' field for image descriptions/captions

ALTER TABLE `gallery` 
ADD COLUMN `image_path` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`;

-- Migrate existing data: Move filenames from 'about' to 'image_path'
-- Only migrate if 'about' looks like a filename (contains 'gallery_' or file extensions)
UPDATE `gallery` 
SET `image_path` = `about`, 
    `about` = '' 
WHERE `about` LIKE 'gallery_%' 
   OR `about` LIKE '%.jpg' 
   OR `about` LIKE '%.png' 
   OR `about` LIKE '%.jpeg' 
   OR `about` LIKE '%.gif';

