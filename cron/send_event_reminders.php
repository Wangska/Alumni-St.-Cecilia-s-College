<?php
/**
 * Event Reminder Cron Job
 * 
 * This script should be run daily (e.g., at 9:00 AM) to send email reminders
 * to participants for events happening in the next few days.
 * 
 * Sends THREE reminders per event:
 * - First reminder: 3 days before the event
 * - Second reminder: 2 days before the event
 * - Third reminder: 1 day before the event
 * 
 * Setup cron job (Linux/Mac):
 * 0 9 * * * /usr/bin/php /path/to/scratch/cron/send_event_reminders.php
 * 
 * Setup Task Scheduler (Windows):
 * Create a daily task that runs: php C:\xampp\htdocs\scratch\cron\send_event_reminders.php
 * Schedule it to run at 9:00 AM every day
 */

declare(strict_types=1);

// Set up error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/event_reminders_error.log');

// Load required files
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/mailer.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Create logs directory if it doesn't exist
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// Log file for tracking reminder execution
$logFile = $logDir . '/event_reminders.log';

/**
 * Log message to file
 */
function logMessage(string $message): void
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    echo $logEntry; // Also output to console for manual runs
}

/**
 * Get database connection
 */
function getConnection(): PDO
{
    return get_pdo();
}

// Start script execution
logMessage("========================================");
logMessage("Starting Event Reminder Cron Job");
logMessage("========================================");

try {
    $pdo = getConnection();
    
    $totalRemindersSent = 0;
    $totalRemindersFailed = 0;
    $totalSkipped = 0;
    
    // Define reminder intervals to check (3 days, 2 days, and 1 day before)
    $reminderIntervals = [
        ['days' => 3, 'type' => '3_days', 'label' => '3 days'],
        ['days' => 2, 'type' => '2_days', 'label' => '2 days'],
        ['days' => 1, 'type' => '1_day', 'label' => '1 day (tomorrow)']
    ];
    
    foreach ($reminderIntervals as $interval) {
        $days = $interval['days'];
        $reminderType = $interval['type'];
        $label = $interval['label'];
        
        // Calculate date range for this interval
        $targetStart = date('Y-m-d 00:00:00', strtotime("+{$days} day"));
        $targetEnd = date('Y-m-d 23:59:59', strtotime("+{$days} day"));
        
        logMessage("Checking for events scheduled in $label ($targetStart to $targetEnd)");
        
        // Find events for this interval
        $stmt = $pdo->prepare("
            SELECT 
                e.id as event_id,
                e.title as event_title,
                e.content as event_content,
                e.schedule as event_schedule
            FROM events e
            WHERE e.schedule >= ? 
              AND e.schedule <= ?
            ORDER BY e.schedule ASC
        ");
        $stmt->execute([$targetStart, $targetEnd]);
        $upcomingEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $eventCount = count($upcomingEvents);
        logMessage("  Found $eventCount event(s) for $label reminder");
        
        if ($eventCount === 0) {
            logMessage("  No events found for this interval.");
            continue;
        }
        
        // Process each upcoming event
        foreach ($upcomingEvents as $event) {
            $eventId = $event['event_id'];
            $eventTitle = $event['event_title'];
            
            logMessage("  Processing event #$eventId: \"$eventTitle\" ($label reminder)");
            
            // Get all participants for this event
            $stmt = $pdo->prepare("
                SELECT DISTINCT
                    u.id as user_id,
                    u.name as user_name,
                    ab.email as user_email,
                    ab.firstname,
                    ab.lastname
                FROM event_commits ec
                INNER JOIN users u ON ec.user_id = u.id
                INNER JOIN alumnus_bio ab ON u.alumnus_id = ab.id
                WHERE ec.event_id = ?
                  AND ab.email IS NOT NULL 
                  AND ab.email != ''
            ");
            $stmt->execute([$eventId]);
            $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $participantCount = count($participants);
            logMessage("    - Found $participantCount participant(s) for this event");
            
            if ($participantCount === 0) {
                logMessage("    - No participants found. Skipping.");
                continue;
            }
            
            // Send reminder to each participant
            foreach ($participants as $participant) {
                $userId = $participant['user_id'];
                $userName = $participant['user_name'];
                $userEmail = $participant['user_email'];
                
                // Check if this specific type of reminder was already sent
                $stmt = $pdo->prepare("
                    SELECT id FROM event_reminders 
                    WHERE event_id = ? AND user_id = ? AND reminder_type = ?
                ");
                $stmt->execute([$eventId, $userId, $reminderType]);
                
                if ($stmt->fetch()) {
                    logMessage("    - $label reminder already sent to $userName ($userEmail). Skipping.");
                    $totalSkipped++;
                    continue;
                }
                
                // Send the reminder email
                logMessage("    - Sending $label reminder to $userName ($userEmail)...");
                
                $emailSent = sendEventReminderEmail(
                    $userEmail,
                    $userName,
                    $event['event_title'],
                    $event['event_schedule'],
                    $event['event_content'],
                    $days // Pass the number of days before
                );
                
                // Record the reminder in database
                if ($emailSent) {
                    $stmt = $pdo->prepare("
                        INSERT INTO event_reminders 
                        (event_id, user_id, reminder_type, email_status) 
                        VALUES (?, ?, ?, 'sent')
                    ");
                    $stmt->execute([$eventId, $userId, $reminderType]);
                    
                    logMessage("    - ✓ $label reminder sent successfully to $userName");
                    $totalRemindersSent++;
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO event_reminders 
                        (event_id, user_id, reminder_type, email_status, error_message) 
                        VALUES (?, ?, ?, 'failed', ?)
                    ");
                    $stmt->execute([$eventId, $userId, $reminderType, 'Email sending failed']);
                    
                    logMessage("    - ✗ Failed to send $label reminder to $userName");
                    $totalRemindersFailed++;
                }
                
                // Small delay to avoid overwhelming the SMTP server
                usleep(500000); // 0.5 second delay
            }
        }
    }
    
    // Summary
    logMessage("========================================");
    logMessage("Event Reminder Cron Job Completed");
    logMessage("Summary:");
    logMessage("  - Total reminders sent: $totalRemindersSent");
    logMessage("  - Total reminders failed: $totalRemindersFailed");
    logMessage("  - Already sent (skipped): $totalSkipped");
    logMessage("========================================");
    
} catch (Exception $e) {
    logMessage("ERROR: " . $e->getMessage());
    logMessage("Stack trace: " . $e->getTraceAsString());
    logMessage("========================================");
    exit(1);
}
