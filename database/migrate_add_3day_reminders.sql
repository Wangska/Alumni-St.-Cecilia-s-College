-- Migration Script: Add 3-Day Reminder Support
-- This script updates the existing event_reminders table to support 3-day reminders
-- 
-- RUN THIS if you already have the event_reminders table in your database
-- and want to add support for 3-day reminders.
--
-- If you haven't created the table yet, just run event_reminders_table.sql instead.

-- Step 1: Modify the enum to include '3_days'
ALTER TABLE `event_reminders` 
MODIFY COLUMN `reminder_type` enum('3_days','2_days','1_day') NOT NULL DEFAULT '1_day' 
COMMENT 'Type of reminder: 3 days, 2 days, or 1 day before event';

-- Note: The unique constraint and indexes don't need to be changed
-- because they already reference the reminder_type column which now includes '3_days'

-- Verification: Check the updated structure
DESCRIBE `event_reminders`;

