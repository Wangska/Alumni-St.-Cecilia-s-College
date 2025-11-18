-- Create messages table for alumni messaging system
-- Run this SQL script in your database to add messaging functionality

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sender` (`sender_id`),
  KEY `idx_receiver` (`receiver_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: Add some sample data for testing
-- INSERT INTO messages (sender_id, receiver_id, subject, message, is_read, date_created)
-- VALUES 
-- (3, 2, 'Account Verification Question', 'Hello, I need help verifying my alumni account. Can you assist?', 0, NOW()),
-- (2, 3, 'RE: Account Verification Question', 'Of course! I will help you with the verification process.', 1, NOW());

-- Instructions:
-- 1. Open phpMyAdmin or your MySQL client
-- 2. Select your 'sccalumni_db' database
-- 3. Run this SQL script
-- 4. The messages table will be created with proper indexes for performance

