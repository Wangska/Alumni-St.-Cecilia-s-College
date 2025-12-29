-- Table to track sent event reminder notifications
-- This prevents sending duplicate reminders to participants
-- Supports multiple reminder types (2 days before, 1 day before, etc.)

CREATE TABLE IF NOT EXISTS `event_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reminder_type` enum('3_days','2_days','1_day') NOT NULL DEFAULT '1_day' COMMENT 'Type of reminder: 3 days, 2 days, or 1 day before event',
  `reminder_sent_date` datetime NOT NULL DEFAULT current_timestamp(),
  `email_status` enum('sent','failed') NOT NULL DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_reminder` (`event_id`, `user_id`, `reminder_type`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`),
  KEY `reminder_type` (`reminder_type`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Index for efficient querying
CREATE INDEX idx_reminder_date ON event_reminders(reminder_sent_date);
CREATE INDEX idx_email_status ON event_reminders(email_status);
