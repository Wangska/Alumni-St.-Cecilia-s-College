-- Migration script to add reminder_type to existing event_reminders table
-- Run this if you already created the event_reminders table without reminder_type

-- Step 1: Add the reminder_type column
ALTER TABLE `event_reminders` 
ADD COLUMN `reminder_type` enum('2_days','1_day') NOT NULL DEFAULT '1_day' 
COMMENT 'Type of reminder: 2 days or 1 day before event' 
AFTER `user_id`;

-- Step 2: Drop the old unique constraint
ALTER TABLE `event_reminders` 
DROP INDEX `unique_reminder`;

-- Step 3: Add the new unique constraint with reminder_type
ALTER TABLE `event_reminders` 
ADD UNIQUE KEY `unique_reminder` (`event_id`, `user_id`, `reminder_type`);

-- Step 4: Add index for reminder_type
ALTER TABLE `event_reminders` 
ADD KEY `reminder_type` (`reminder_type`);
