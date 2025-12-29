<?php
/**
 * Manual Test Script for Event Reminders
 * 
 * This script allows you to manually test the event reminder system
 * without waiting for the cron job to run.
 * 
 * Usage: Access this file via browser: http://localhost/scratch/test_event_reminders.php
 * Or run via command line: php test_event_reminders.php
 */

declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/mailer.php';
require_once __DIR__ . '/vendor/autoload.php';

// Set content type to plain text for better readability
if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "==============================================\n";
echo "EVENT REMINDER SYSTEM - MANUAL TEST\n";
echo "==============================================\n\n";

try {
    $pdo = get_pdo();
    
    // Show current date/time
    echo "Current Date/Time: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Check for events in the next 3 days
    echo "Checking for upcoming events that will receive reminders:\n";
    echo "----------------------------------------------\n\n";
    
    $allEvents = [];
    
    // Check 3 days ahead
    $threeDaysStart = date('Y-m-d 00:00:00', strtotime('+3 days'));
    $threeDaysEnd = date('Y-m-d 23:59:59', strtotime('+3 days'));
    
    echo "Events in 3 days ($threeDaysStart):\n";
    $stmt = $pdo->prepare("
        SELECT 
            e.id as event_id,
            e.title as event_title,
            e.content as event_content,
            e.schedule as event_schedule,
            DATE_FORMAT(e.schedule, '%W, %M %d, %Y at %h:%i %p') as formatted_schedule,
            '3_days' as reminder_interval
        FROM events e
        WHERE e.schedule >= ? 
          AND e.schedule <= ?
        ORDER BY e.schedule ASC
    ");
    $stmt->execute([$threeDaysStart, $threeDaysEnd]);
    $events3Days = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "  Found " . count($events3Days) . " event(s)\n\n";
    
    // Check 2 days ahead
    $twoDaysStart = date('Y-m-d 00:00:00', strtotime('+2 days'));
    $twoDaysEnd = date('Y-m-d 23:59:59', strtotime('+2 days'));
    
    echo "Events in 2 days ($twoDaysStart):\n";
    $stmt = $pdo->prepare("
        SELECT 
            e.id as event_id,
            e.title as event_title,
            e.content as event_content,
            e.schedule as event_schedule,
            DATE_FORMAT(e.schedule, '%W, %M %d, %Y at %h:%i %p') as formatted_schedule,
            '2_days' as reminder_interval
        FROM events e
        WHERE e.schedule >= ? 
          AND e.schedule <= ?
        ORDER BY e.schedule ASC
    ");
    $stmt->execute([$twoDaysStart, $twoDaysEnd]);
    $events2Days = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "  Found " . count($events2Days) . " event(s)\n\n";
    
    // Check 1 day ahead (tomorrow)
    $tomorrowStart = date('Y-m-d 00:00:00', strtotime('+1 day'));
    $tomorrowEnd = date('Y-m-d 23:59:59', strtotime('+1 day'));
    
    echo "Events tomorrow ($tomorrowStart):\n";
    $stmt = $pdo->prepare("
        SELECT 
            e.id as event_id,
            e.title as event_title,
            e.content as event_content,
            e.schedule as event_schedule,
            DATE_FORMAT(e.schedule, '%W, %M %d, %Y at %h:%i %p') as formatted_schedule,
            '1_day' as reminder_interval
        FROM events e
        WHERE e.schedule >= ? 
          AND e.schedule <= ?
        ORDER BY e.schedule ASC
    ");
    $stmt->execute([$tomorrowStart, $tomorrowEnd]);
    $events1Day = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "  Found " . count($events1Day) . " event(s)\n\n";
    
    // Combine all events
    $allEvents = array_merge($events3Days, $events2Days, $events1Day);
    
    if (count($allEvents) === 0) {
        echo "No events found in the next 3 days.\n\n";
        echo "TIP: To test this system, create an event in the admin panel\n";
        echo "     with a schedule date in the next 1-3 days.\n";
        exit;
    }
    
    echo "----------------------------------------------\n";
    
    // Display events and their participants
    foreach ($allEvents as $event) {
        $eventId = $event['event_id'];
        $reminderType = $event['reminder_interval'];
        $reminderLabel = $reminderType === '3_days' ? '(3-day reminder)' : ($reminderType === '2_days' ? '(2-day reminder)' : '(1-day reminder)');
        
        echo "\n========================================\n";
        echo "Event #$eventId: {$event['event_title']} $reminderLabel\n";
        echo "Scheduled: {$event['formatted_schedule']}\n";
        echo "----------------------------------------------\n";
        
        // Get participants with reminder status for ALL reminder types
        $stmt = $pdo->prepare("
            SELECT DISTINCT
                u.id as user_id,
                u.name as user_name,
                ab.email as user_email,
                er3.reminder_sent_date as reminder_3day_sent,
                er3.email_status as status_3day,
                er2.reminder_sent_date as reminder_2day_sent,
                er2.email_status as status_2day,
                er1.reminder_sent_date as reminder_1day_sent,
                er1.email_status as status_1day
            FROM event_commits ec
            INNER JOIN users u ON ec.user_id = u.id
            INNER JOIN alumnus_bio ab ON u.alumnus_id = ab.id
            LEFT JOIN event_reminders er3 ON er3.event_id = ec.event_id AND er3.user_id = u.id AND er3.reminder_type = '3_days'
            LEFT JOIN event_reminders er2 ON er2.event_id = ec.event_id AND er2.user_id = u.id AND er2.reminder_type = '2_days'
            LEFT JOIN event_reminders er1 ON er1.event_id = ec.event_id AND er1.user_id = u.id AND er1.reminder_type = '1_day'
            WHERE ec.event_id = ?
              AND ab.email IS NOT NULL 
              AND ab.email != ''
        ");
        $stmt->execute([$eventId]);
        $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($participants) === 0) {
            echo "  No participants registered for this event.\n";
            continue;
        }
        
        echo "Participants (" . count($participants) . "):\n";
        foreach ($participants as $i => $participant) {
            $num = $i + 1;
            echo "  $num. {$participant['user_name']} ({$participant['user_email']})\n";
            
            // Show 3-day reminder status
            if ($participant['reminder_3day_sent']) {
                $status = $participant['status_3day'] === 'sent' ? '✓ SENT' : '✗ FAILED';
                echo "     3-day reminder: $status on {$participant['reminder_3day_sent']}\n";
            } else {
                echo "     3-day reminder: Not sent yet\n";
            }
            
            // Show 2-day reminder status
            if ($participant['reminder_2day_sent']) {
                $status = $participant['status_2day'] === 'sent' ? '✓ SENT' : '✗ FAILED';
                echo "     2-day reminder: $status on {$participant['reminder_2day_sent']}\n";
            } else {
                echo "     2-day reminder: Not sent yet\n";
            }
            
            // Show 1-day reminder status
            if ($participant['reminder_1day_sent']) {
                $status = $participant['status_1day'] === 'sent' ? '✓ SENT' : '✗ FAILED';
                echo "     1-day reminder: $status on {$participant['reminder_1day_sent']}\n";
            } else {
                echo "     1-day reminder: Not sent yet\n";
            }
        }
    }
    
    echo "\n==============================================\n";
    echo "TEST OPTIONS\n";
    echo "==============================================\n";
    echo "1. Run the cron job manually:\n";
    echo "   php cron/send_event_reminders.php\n\n";
    echo "2. Send a test reminder to one participant:\n";
    echo "   (See send_test_reminder.php)\n\n";
    echo "3. View the reminder log:\n";
    echo "   Check: logs/event_reminders.log\n\n";
    
    // Check if event_reminders table exists
    echo "\n==============================================\n";
    echo "DATABASE CHECK\n";
    echo "==============================================\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'event_reminders'");
    if ($stmt->fetch()) {
        echo "✓ Table 'event_reminders' exists\n\n";
        
        // Count total reminders sent
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM event_reminders");
        $result = $stmt->fetch();
        echo "Total reminders sent: {$result['total']}\n";
        
        // Count by reminder type
        $stmt = $pdo->query("SELECT reminder_type, COUNT(*) as count FROM event_reminders GROUP BY reminder_type");
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($types as $type) {
            $label = $type['reminder_type'] === '2_days' ? '2-day reminders' : '1-day reminders';
            echo "  - $label: {$type['count']}\n";
        }
        
        echo "\nBy status:\n";
        $stmt = $pdo->query("SELECT email_status, COUNT(*) as total FROM event_reminders GROUP BY email_status");
        $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($statuses as $status) {
            echo "  - " . ucfirst($status['email_status']) . ": {$status['total']}\n";
        }
    } else {
        echo "✗ Table 'event_reminders' does NOT exist\n";
        echo "  Please run: database/event_reminders_table.sql\n";
    }
    
    echo "\n==============================================\n";
    
} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
