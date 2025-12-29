<?php
/**
 * Send a Single Test Reminder
 * 
 * This script allows you to send a test event reminder email to yourself
 * for testing purposes without affecting the actual reminder system.
 * 
 * Usage: Access via browser: http://localhost/scratch/send_test_reminder.php
 */

declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/mailer.php';
require_once __DIR__ . '/vendor/autoload.php';

// Set content type to plain text for better readability
header('Content-Type: text/plain; charset=utf-8');

echo "==============================================\n";
echo "SEND TEST EVENT REMINDER\n";
echo "==============================================\n\n";

// Configuration - CHANGE THESE VALUES FOR TESTING
$testEmail = 'your-test-email@example.com'; // Change this to your test email
$testName = 'Test User';
$testEventTitle = 'Sample Alumni Reunion';
$testEventSchedule = date('Y-m-d H:i:s', strtotime('+2 days')); // 2 days from now
$testEventContent = 'This is a test event for the alumni reunion. Join us for a day of reconnecting with old friends, sharing memories, and celebrating our shared experiences at St. Cecilia\'s College. Food and refreshments will be provided.';

// Choose which reminder type to test: 1 = 1-day reminder, 2 = 2-day reminder
$reminderType = 2; // Change this to 1 or 2

echo "Test Configuration:\n";
echo "  To: $testEmail\n";
echo "  Name: $testName\n";
echo "  Event: $testEventTitle\n";
echo "  Schedule: $testEventSchedule\n";
echo "  Reminder Type: " . ($reminderType === 2 ? '2 days before' : '1 day before') . "\n\n";

echo "Sending test " . ($reminderType === 2 ? '2-day' : '1-day') . " reminder email...\n\n";

try {
    $result = sendEventReminderEmail(
        $testEmail,
        $testName,
        $testEventTitle,
        $testEventSchedule,
        $testEventContent,
        $reminderType // Pass the reminder type
    );
    
    if ($result) {
        echo "✓ SUCCESS!\n\n";
        echo "Test reminder email sent successfully.\n";
        echo "Please check your email inbox (and spam folder).\n\n";
        echo "NOTE: If you're using Mailtrap, check your Mailtrap inbox at:\n";
        echo "https://mailtrap.io/inboxes\n";
    } else {
        echo "✗ FAILED!\n\n";
        echo "Failed to send test reminder email.\n";
        echo "Please check:\n";
        echo "  1. Your email configuration in inc/mailer.php\n";
        echo "  2. Your SMTP credentials\n";
        echo "  3. PHP error log for detailed error messages\n";
    }
    
} catch (Exception $e) {
    echo "✗ ERROR!\n\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n==============================================\n";
