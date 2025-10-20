<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';

$pdo = get_pdo();

try {
    // Check if events table exists and create if needed
    $stmt = $pdo->query("SHOW TABLES LIKE 'events'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("
            CREATE TABLE `events` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(255) NOT NULL,
              `description` text NOT NULL,
              `event_date` datetime NOT NULL,
              `location` varchar(255) DEFAULT NULL,
              `image` varchar(255) DEFAULT NULL,
              `max_participants` int(11) DEFAULT NULL,
              `registration_deadline` datetime DEFAULT NULL,
              `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=inactive, 1=active',
              `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              KEY `event_date` (`event_date`),
              KEY `status` (`status`),
              KEY `created` (`created`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
    
    // Check if event_participants table exists and create if needed
    $stmt = $pdo->query("SHOW TABLES LIKE 'event_participants'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("
            CREATE TABLE `event_participants` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `event_id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL,
              `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=cancelled, 1=registered',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unique_participation` (`event_id`, `user_id`),
              KEY `event_id` (`event_id`),
              KEY `user_id` (`user_id`),
              KEY `status` (`status`),
              CONSTRAINT `event_participants_event_fk` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `event_participants_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
    
    // Check if there are already events
    $stmt = $pdo->query("SELECT COUNT(*) FROM events");
    $eventCount = $stmt->fetchColumn();
    
    if ($eventCount == 0) {
        // Add sample events using your existing table structure
        $sampleEvents = [
            [
                'title' => 'Alumni Homecoming 2024',
                'content' => 'Join us for our annual alumni homecoming event! Reconnect with old friends, meet new alumni, and celebrate our shared memories. This year we have special activities, food, and entertainment planned. Don\'t miss this opportunity to network and strengthen our alumni community.',
                'schedule' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'banner' => 'images/events/homecoming.jpg'
            ],
            [
                'title' => 'Career Development Workshop',
                'content' => 'Enhance your professional skills with our career development workshop. Learn about resume building, interview techniques, networking strategies, and career advancement tips. This workshop is designed for alumni at all career stages.',
                'schedule' => date('Y-m-d H:i:s', strtotime('+45 days')),
                'banner' => 'images/events/workshop.jpg'
            ],
            [
                'title' => 'Networking Mixer',
                'content' => 'Connect with fellow alumni in a relaxed networking environment. Share your experiences, learn about different career paths, and build valuable professional relationships. Light refreshments will be provided.',
                'schedule' => date('Y-m-d H:i:s', strtotime('+60 days')),
                'banner' => 'images/events/networking.jpg'
            ]
        ];
        
        $stmt = $pdo->prepare("
            INSERT INTO events (title, content, schedule, banner, date_created) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        foreach ($sampleEvents as $event) {
            $stmt->execute([
                $event['title'],
                $event['content'],
                $event['schedule'],
                $event['banner']
            ]);
        }
        
        echo "Sample events added successfully!";
    } else {
        echo "Events already exist in the database.";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
