<?php
declare(strict_types=1);

// Enhanced email debugging script
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Email Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 20px; background: #f5f5f5; }
        .box { background: white; border-radius: 8px; padding: 20px; margin: 15px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { border-left: 4px solid #10b981; background: #f0fdf4; }
        .error { border-left: 4px solid #dc2626; background: #fef2f2; }
        .warning { border-left: 4px solid #f59e0b; background: #fffbeb; }
        .info { border-left: 4px solid #3b82f6; background: #eff6ff; }
        h1 { color: #1f2937; margin-top: 0; }
        h2 { color: #374151; font-size: 18px; margin-top: 0; }
        pre { background: #1f2937; color: #f9fafb; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #dc2626; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; border: none; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        table td:first-child { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <h1>🔍 Email System Debug Tool</h1>
";

// Check 1: PHP Version
echo "<div class='box info'>";
echo "<h2>1️⃣ PHP Environment</h2>";
echo "<table>";
echo "<tr><td>PHP Version:</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Error Reporting:</td><td>" . (error_reporting() ? 'Enabled' : 'Disabled') . "</td></tr>";
echo "<tr><td>Display Errors:</td><td>" . ini_get('display_errors') . "</td></tr>";
echo "</table>";
echo "</div>";

// Check 2: Composer/Autoload
echo "<div class='box " . (file_exists(__DIR__ . '/vendor/autoload.php') ? 'success' : 'error') . "'>";
echo "<h2>2️⃣ Composer & PHPMailer</h2>";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✅ vendor/autoload.php found<br>";
    
    try {
        $testMailer = new PHPMailer\PHPMailer\PHPMailer(true);
        echo "✅ PHPMailer class loaded successfully<br>";
        echo "✅ PHPMailer version: " . PHPMailer\PHPMailer\PHPMailer::VERSION;
    } catch (Exception $e) {
        echo "❌ Error loading PHPMailer: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "❌ vendor/autoload.php NOT found<br>";
    echo "Run: <pre>cd C:\\xampp\\htdocs\\scratch\ncomposer install</pre>";
}
echo "</div>";

// Check 3: Configuration
echo "<div class='box info'>";
echo "<h2>3️⃣ Email Configuration</h2>";
if (file_exists(__DIR__ . '/inc/mailer.php')) {
    require_once __DIR__ . '/inc/mailer.php';
    echo "<table>";
    echo "<tr><td>SMTP Host:</td><td>" . MAILTRAP_HOST . "</td></tr>";
    echo "<tr><td>SMTP Port:</td><td>" . MAILTRAP_PORT . "</td></tr>";
    echo "<tr><td>Username:</td><td>" . htmlspecialchars(MAILTRAP_USERNAME) . "</td></tr>";
    echo "<tr><td>Password:</td><td>" . str_repeat('*', strlen(MAILTRAP_PASSWORD)) . " (length: " . strlen(MAILTRAP_PASSWORD) . ")</td></tr>";
    echo "<tr><td>From Email:</td><td>" . MAIL_FROM_EMAIL . "</td></tr>";
    echo "<tr><td>From Name:</td><td>" . MAIL_FROM_NAME . "</td></tr>";
    echo "</table>";
    
    // Check for default values
    if (MAILTRAP_USERNAME === 'YOUR_MAILTRAP_USERNAME' || MAILTRAP_PASSWORD === 'YOUR_MAILTRAP_PASSWORD') {
        echo "<div class='box error' style='margin-top: 10px;'>";
        echo "⚠️ <strong>Credentials not configured!</strong> Please update inc/mailer.php";
        echo "</div>";
    }
} else {
    echo "❌ inc/mailer.php not found!";
}
echo "</div>";

// Check 4: OpenSSL Support
echo "<div class='box " . (extension_loaded('openssl') ? 'success' : 'error') . "'>";
echo "<h2>4️⃣ SSL/TLS Support</h2>";
if (extension_loaded('openssl')) {
    echo "✅ OpenSSL extension is loaded<br>";
    echo "✅ OpenSSL version: " . OPENSSL_VERSION_TEXT;
} else {
    echo "❌ OpenSSL extension is NOT loaded<br>";
    echo "SMTP with TLS requires OpenSSL. Please enable it in php.ini";
}
echo "</div>";

// Check 5: Socket Support
echo "<div class='box " . (function_exists('fsockopen') ? 'success' : 'error') . "'>";
echo "<h2>5️⃣ Socket Functions</h2>";
if (function_exists('fsockopen')) {
    echo "✅ fsockopen is available<br>";
    
    // Try to connect to Mailtrap
    echo "Testing connection to " . MAILTRAP_HOST . ":" . MAILTRAP_PORT . "...<br>";
    $errno = 0;
    $errstr = '';
    $socket = @fsockopen(MAILTRAP_HOST, MAILTRAP_PORT, $errno, $errstr, 10);
    
    if ($socket) {
        echo "✅ Successfully connected to Mailtrap SMTP server!<br>";
        fclose($socket);
    } else {
        echo "❌ Failed to connect: ($errno) $errstr<br>";
        echo "This could be a firewall or network issue.";
    }
} else {
    echo "❌ fsockopen is not available (might be disabled)";
}
echo "</div>";

// Check 6: Send Test Email with Detailed Debugging
if (file_exists(__DIR__ . '/vendor/autoload.php') && 
    file_exists(__DIR__ . '/inc/mailer.php') &&
    defined('MAILTRAP_USERNAME') && 
    MAILTRAP_USERNAME !== 'YOUR_MAILTRAP_USERNAME') {
    
    echo "<div class='box'>";
    echo "<h2>6️⃣ Send Test Email</h2>";
    
    if (isset($_POST['send_test'])) {
        $testEmail = trim($_POST['test_email'] ?? '');
        
        if ($testEmail && filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            echo "<h3>📤 Sending test email to: " . htmlspecialchars($testEmail) . "</h3>";
            
            try {
                // Create a new PHPMailer instance with detailed error reporting
                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\Exception;
                
                $mail = new PHPMailer(true);
                
                // Enable verbose debug output
                $mail->SMTPDebug = 3; // 0=off, 1=client, 2=client+server, 3=client+server+connection
                $mail->Debugoutput = function($str, $level) {
                    echo "<div style='background:#f9fafb; padding:5px; margin:2px 0; border-left:3px solid #6b7280; font-family:monospace; font-size:11px;'>";
                    echo htmlspecialchars($str);
                    echo "</div>";
                };
                
                // Server settings
                $mail->isSMTP();
                $mail->Host       = MAILTRAP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = MAILTRAP_USERNAME;
                $mail->Password   = MAILTRAP_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = MAILTRAP_PORT;
                $mail->Timeout    = 10; // 10 second timeout
                
                // Recipients
                $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
                $mail->addAddress($testEmail, 'Test User');
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Test Email from Alumni System';
                $mail->Body    = '<h1>Test Email</h1><p>If you see this, your email system is working correctly!</p>';
                $mail->AltBody = 'Test Email - If you see this, your email system is working!';
                
                echo "<div style='background:#f9fafb; padding:10px; margin:10px 0; border-radius:4px;'>";
                echo "<strong>SMTP Debug Output:</strong><br><br>";
                
                $mail->send();
                
                echo "</div>";
                
                echo "<div class='box success' style='margin-top:15px;'>";
                echo "<h3>✅ Email Sent Successfully!</h3>";
                echo "<p>Check your Mailtrap inbox at <a href='https://mailtrap.io/inboxes' target='_blank'>mailtrap.io/inboxes</a></p>";
                echo "</div>";
                
            } catch (Exception $e) {
                echo "</div>";
                
                echo "<div class='box error' style='margin-top:15px;'>";
                echo "<h3>❌ Failed to Send Email</h3>";
                echo "<strong>Error Message:</strong><br>";
                echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
                
                if (isset($mail)) {
                    echo "<br><strong>Mailer Error Info:</strong><br>";
                    echo "<pre>" . htmlspecialchars($mail->ErrorInfo) . "</pre>";
                }
                
                echo "<br><strong>Possible Solutions:</strong><ul>";
                echo "<li>Verify your Mailtrap username and password are correct</li>";
                echo "<li>Check if your firewall is blocking port " . MAILTRAP_PORT . "</li>";
                echo "<li>Make sure OpenSSL is enabled in PHP</li>";
                echo "<li>Try generating new Mailtrap credentials</li>";
                echo "</ul>";
                echo "</div>";
            }
        } else {
            echo "<div class='box error'>";
            echo "❌ Please enter a valid email address";
            echo "</div>";
        }
    }
    
    echo "<form method='POST' style='margin-top:20px;'>";
    echo "<p><strong>Send a test email:</strong></p>";
    echo "<input type='email' name='test_email' placeholder='test@example.com' required style='padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;'> ";
    echo "<button type='submit' name='send_test' class='btn'>Send Test Email</button>";
    echo "<p style='font-size:12px; color:#666;'>The email will appear in your Mailtrap inbox, not in a real inbox.</p>";
    echo "</form>";
    echo "</div>";
}

echo "<hr style='margin: 40px 0;'>";
echo "<p style='text-align:center;'>";
echo "<a href='test_email_system.php' class='btn' style='background:#6b7280;'>← Back to Simple Test</a> ";
echo "<a href='test_email_debug.php' class='btn' style='background:#3b82f6;'>🔄 Refresh</a>";
echo "</p>";

echo "</body></html>";

