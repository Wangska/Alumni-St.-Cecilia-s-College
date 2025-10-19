-- Add title field to announcements table
ALTER TABLE `announcements` 
ADD COLUMN `title` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`,
ADD COLUMN `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `date_posted`;

-- Update existing announcements with a default title
UPDATE `announcements` SET `title` = CONCAT('Announcement #', `id`) WHERE `title` = '';

