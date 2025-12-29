-- Add support for Information-Only Events (No Registration Required)
-- 
-- This migration adds a column to allow events that are informational only
-- and don't require participant registration.
--
-- Use cases:
-- - School holidays
-- - Important dates (Foundation Day, Graduation, etc.)
-- - General announcements
-- - Reminders (Exam schedules, Enrollment periods, etc.)

-- Add the column
ALTER TABLE `events` 
ADD COLUMN `allow_registration` TINYINT(1) NOT NULL DEFAULT 1 
COMMENT '1 = Allow registration, 0 = Information only (no registration)' 
AFTER `participant_limit`;

-- Verification
DESCRIBE `events`;

-- Example: Mark an event as information-only
-- UPDATE events SET allow_registration = 0 WHERE id = YOUR_EVENT_ID;

