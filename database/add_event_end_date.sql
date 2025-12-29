-- Add Event End Date Support
-- 
-- This migration adds an end date/time field to events table
-- allowing events to span multiple days.
--
-- Use cases:
-- - Multi-day seminars (3-day workshop)
-- - Week-long events (Sports Week, Science Fair Week)
-- - Extended activities (Tree Planting Campaign - 5 days)
-- - Holiday periods (Christmas Break: Dec 20 - Jan 5)

-- Add the end_date column
ALTER TABLE `events` 
ADD COLUMN `end_date` DATETIME NULL 
COMMENT 'End date/time of the event. NULL means single-day event (ends same day as schedule)' 
AFTER `schedule`;

-- Update existing events to have end_date same as schedule (single-day events)
-- This ensures existing events still work correctly
UPDATE `events` 
SET `end_date` = `schedule` 
WHERE `end_date` IS NULL;

-- Verification
DESCRIBE `events`;

-- Example: Create a 3-day workshop
-- INSERT INTO events (title, content, schedule, end_date, allow_registration) 
-- VALUES ('Leadership Workshop', 'A comprehensive 3-day leadership training...', 
--         '2026-03-15 08:00:00', '2026-03-17 17:00:00', 1);

