<?php
declare(strict_types=1);

// Test email system
echo "<!DOCTYPE html>
<html>
<head>
    <title>Email System Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .test-box { border: 2px solid #dc2626; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .success { background: #f0fdf4; border-color: #10b981; }
        .error { background: #fef2f2; border-color: #dc2626; }
        .warning { background: #fffbeb; border-color: #fbbf24; }
        h1 { color: #dc2626; }
        .step { margin: 10px 0; padding: 10px; background: #f9fafb; border-radius: 4px; }
        pre { background: #1f2937; color: #fff; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .btn { display: inline-block; padding: 10px 20px; background: #dc2626; color: white; text-decoration: none; border-radius: 4px; margin: 10px 5px; }
    </style>
</head>
<body>
    <h1>📧 Email System Test</h1>
    <p>This page will help you test if the email notification system is set up correctly.</p>
";

// Step 1: Check if vendor/autoload.php exists
echo "<div class='test-box " . (file_exists(__DIR__ . '/vendor/autoload.php') ? 'success' : 'error') . "'>";
echo "<h2>Step 1: PHPMailer Installation</h2>";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✅ <strong>vendor/autoload.php found!</strong><br>";
    echo "PHPMailer appears to be installed correctly.";
} else {
    echo "❌ <strong>vendor/autoload.php NOT found!</strong><br>";
    echo "PHPMailer is not installed. Please follow these steps:<br><br>";
    echo "<ol>";
    echo "<li>Install Composer from <a href='https://getcomposer.org' target='_blank'>getcomposer.org</a></li>";
    echo "<li>Open Command Prompt and run:<br><pre>cd C:\\xampp\\htdocs\\scratch\ncomposer install</pre></li>";
    echo "</ol>";
    echo "Or see <a href='INSTALL_COMPOSER.md'><strong>INSTALL_COMPOSER.md</strong></a> for manual installation.";
}
echo "</div>";

// Step 2: Check if PHPMailer class can be loaded
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    
    echo "<div class='test-box success'>";
    echo "<h2>Step 2: PHPMailer Class</h2>";
    try {
        $testMailer = new PHPMailer\PHPMailer\PHPMailer(true);
        echo "✅ <strong>PHPMailer class loaded successfully!</strong><br>";
        echo "Version: " . PHPMailer\PHPMailer\PHPMailer::VERSION;
    } catch (Exception $e) {
        echo "❌ <strong>Error loading PHPMailer:</strong> " . $e->getMessage();
    }
    echo "</div>";
}

// Step 3: Check mailer.php
echo "<div class='test-box " . (file_exists(__DIR__ . '/inc/mailer.php') ? 'success' : 'error') . "'>";
echo "<h2>Step 3: Mailer Configuration</h2>";
if (file_exists(__DIR__ . '/inc/mailer.php')) {
    echo "✅ <strong>inc/mailer.php found!</strong><br>";
    require_once __DIR__ . '/inc/mailer.php';
    
    // Check if credentials are configured
    if (defined('MAILTRAP_USERNAME') && defined('MAILTRAP_PASSWORD')) {
        echo "✅ Mailer constants defined<br>";
        
        $username = MAILTRAP_USERNAME;
        $password = MAILTRAP_PASSWORD;
        
        if ($username === 'YOUR_MAILTRAP_USERNAME' || $password === 'YOUR_MAILTRAP_PASSWORD') {
            echo "<div style='margin-top: 10px; padding: 10px; background: #fffbeb; border-left: 4px solid #fbbf24;'>";
            echo "⚠️ <strong>Credentials not configured!</strong><br>";
            echo "Please update your Mailtrap credentials in <code>inc/mailer.php</code>:<br><br>";
            echo "<ol>";
            echo "<li>Get your credentials from <a href='https://mailtrap.io' target='_blank'>mailtrap.io</a></li>";
            echo "<li>Open <code>inc/mailer.php</code></li>";
            echo "<li>Replace <code>YOUR_MAILTRAP_USERNAME</code> with your actual username</li>";
            echo "<li>Replace <code>YOUR_MAILTRAP_PASSWORD</code> with your actual password</li>";
            echo "</ol>";
            echo "See <a href='EMAIL_SETUP.md'><strong>EMAIL_SETUP.md</strong></a> for detailed instructions.";
            echo "</div>";
        } else {
            echo "✅ Credentials configured (Username: " . htmlspecialchars(substr($username, 0, 4)) . "****)";
        }
    }
} else {
    echo "❌ <strong>inc/mailer.php NOT found!</strong>";
}
echo "</div>";

// Step 4: Send test email
if (file_exists(__DIR__ . '/vendor/autoload.php') && 
    file_exists(__DIR__ . '/inc/mailer.php') &&
    defined('MAILTRAP_USERNAME') && 
    MAILTRAP_USERNAME !== 'YOUR_MAILTRAP_USERNAME') {
    
    echo "<div class='test-box'>";
    echo "<h2>Step 4: Send Test Email</h2>";
    
    if (isset($_POST['send_test'])) {
        $testEmail = trim($_POST['test_email'] ?? '');
        $testName = trim($_POST['test_name'] ?? 'Test User');
        
        if ($testEmail && filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            echo "<div class='step'>";
            echo "📤 Sending test email to: <strong>" . htmlspecialchars($testEmail) . "</strong><br>";
            
            try {
                $result = sendRegistrationEmail($testEmail, $testName, 'testuser');
                
                if ($result) {
                    echo "<div style='margin-top: 10px; padding: 15px; background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 4px;'>";
                    echo "✅ <strong>Email sent successfully!</strong><br><br>";
                    echo "Now check your Mailtrap inbox:<br>";
                    echo "1. Go to <a href='https://mailtrap.io/inboxes' target='_blank'>mailtrap.io/inboxes</a><br>";
                    echo "2. Open your inbox<br>";
                    echo "3. You should see the test email!";
                    echo "</div>";
                } else {
                    echo "<div style='margin-top: 10px; padding: 15px; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px;'>";
                    echo "❌ <strong>Failed to send email</strong><br>";
                    echo "Check your PHP error logs for details.";
                    echo "</div>";
                }
            } catch (Exception $e) {
                echo "<div style='margin-top: 10px; padding: 15px; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px;'>";
                echo "❌ <strong>Error:</strong> " . htmlspecialchars($e->getMessage());
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div style='padding: 10px; background: #fef2f2; border-left: 4px solid #dc2626;'>";
            echo "❌ Please enter a valid email address";
            echo "</div>";
        }
    }
    
    echo "<form method='POST' style='margin-top: 20px;'>";
    echo "<p><strong>Send a test registration email:</strong></p>";
    echo "<div style='margin: 10px 0;'>";
    echo "<input type='text' name='test_name' placeholder='Test Name' value='Test User' style='padding: 8px; width: 200px; border: 1px solid #ccc; border-radius: 4px;'> ";
    echo "<input type='email' name='test_email' placeholder='any@email.com' required style='padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 4px;'> ";
    echo "<button type='submit' name='send_test' class='btn'>Send Test Email</button>";
    echo "</div>";
    echo "<p style='font-size: 12px; color: #666;'>Note: The email won't actually be sent to this address. It will appear in your Mailtrap inbox.</p>";
    echo "</form>";
    echo "</div>";
}

echo "<hr style='margin: 40px 0;'>";
echo "<h2>📚 Documentation</h2>";
echo "<p>For complete setup instructions, see:</p>";
echo "<ul>";
echo "<li><a href='INSTALL_COMPOSER.md'><strong>INSTALL_COMPOSER.md</strong></a> - How to install Composer and PHPMailer</li>";
echo "<li><a href='EMAIL_SETUP.md'><strong>EMAIL_SETUP.md</strong></a> - Complete email setup guide with Mailtrap</li>";
echo "</ul>";

echo "<hr style='margin: 40px 0;'>";
echo "<p style='text-align: center; color: #666;'>
    <a href='/' class='btn'>← Back to Home</a>
    <a href='test_email_system.php' class='btn' style='background: #6b7280;'>🔄 Refresh Test</a>
</p>";

echo "</body></html>";

