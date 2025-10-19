-- Create alumni_documents table for storing verification documents
CREATE TABLE IF NOT EXISTS `alumni_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alumnus_id` int(11) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) DEFAULT 0,
  `verified_by` int(11) DEFAULT NULL,
  `verified_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_documents_alumnus` (`alumnus_id`),
  KEY `fk_documents_verifier` (`verified_by`),
  CONSTRAINT `fk_documents_alumnus` FOREIGN KEY (`alumnus_id`) REFERENCES `alumnus_bio` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_documents_verifier` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
