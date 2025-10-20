-- Migration: Add participant_limit column to events table
-- Date: 2024

-- Add participant_limit column to events table
ALTER TABLE `events` 
ADD COLUMN `participant_limit` int(11) DEFAULT NULL COMMENT 'Maximum number of participants allowed for this event' 
AFTER `banner`;

-- Update existing events to have NULL participant_limit (unlimited participants)
UPDATE `events` SET `participant_limit` = NULL WHERE `participant_limit` IS NULL;
