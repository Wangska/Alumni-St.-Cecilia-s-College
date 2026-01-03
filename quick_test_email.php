<?php
// Quick email test with inline credentials
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "Testing email with current credentials...\n\n";

// Read current credentials
require_once __DIR__ . '/inc/mailer.php';

echo "Current credentials:\n";
echo "Username: " . MAILTRAP_USERNAME . " (length: " . strlen(MAILTRAP_USERNAME) . ")\n";
echo "Password: " . str_repeat('*', strlen(MAILTRAP_PASSWORD)) . " (length: " . strlen(MAILTRAP_PASSWORD) . ")\n\n";

try {
    $mail = new PHPMailer(true);
    
    // Enable debugging
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'echo';
    
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = MAILTRAP_USERNAME;
    $mail->Password   = MAILTRAP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 2525;
    $mail->Timeout    = 30;
    
    // Recipients
    $mail->setFrom('test@stcecilia.edu.ph', 'Test System');
    $mail->addAddress('recipient@example.com', 'Test Recipient');
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = '<h1>Test</h1><p>This is a test email.</p>';
    $mail->AltBody = 'This is a test email.';
    
    $mail->send();
    echo "\n\n✅ SUCCESS! Email sent!\n";
    echo "Check your Mailtrap inbox at https://mailtrap.io/inboxes\n";
    
} catch (Exception $e) {
    echo "\n\n❌ FAILED!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "ErrorInfo: " . $mail->ErrorInfo . "\n\n";
    
    echo "Common issues:\n";
    echo "1. Username/password are incorrect or incomplete\n";
    echo "2. Username/password have extra spaces or characters\n";
    echo "3. You're using an old or expired API token\n\n";
    
    echo "Please verify your Mailtrap credentials:\n";
    echo "- Go to https://mailtrap.io/inboxes\n";
    echo "- Click on your inbox\n";
    echo "- Find 'SMTP Settings' or 'Integrations'\n";
    echo "- Copy the EXACT username and password\n";
}





