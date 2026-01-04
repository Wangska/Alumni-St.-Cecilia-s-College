<!DOCTYPE html>
<html>
<head>
    <title>Verify Mailtrap Credentials</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            max-width: 900px; 
            margin: 30px auto; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        h1 { 
            color: #dc2626; 
            margin-top: 0;
            font-size: 32px;
        }
        .error-box {
            background: #fef2f2;
            border-left: 6px solid #dc2626;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success-box {
            background: #f0fdf4;
            border-left: 6px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background: #eff6ff;
            border-left: 6px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box {
            background: #fffbeb;
            border-left: 6px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credential-box {
            background: #1f2937;
            color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            overflow-x: auto;
        }
        .step {
            background: #f9fafb;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }
        .step h3 {
            margin-top: 0;
            color: #374151;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #dc2626;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px 5px 10px 0;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #991b1b;
        }
        .btn-blue {
            background: #3b82f6;
        }
        .btn-blue:hover {
            background: #2563eb;
        }
        .btn-green {
            background: #10b981;
        }
        .btn-green:hover {
            background: #059669;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table td:first-child {
            font-weight: bold;
            width: 150px;
            color: #374151;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .badge-error {
            background: #fef2f2;
            color: #dc2626;
        }
        .badge-success {
            background: #f0fdf4;
            color: #10b981;
        }
        .badge-warning {
            background: #fffbeb;
            color: #f59e0b;
        }
        ol { line-height: 1.8; }
        ol li { margin: 10px 0; }
        .highlight {
            background: #fef3c7;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Mailtrap Credentials Checker</h1>
        
        <?php
        require_once __DIR__ . '/inc/mailer.php';
        
        $username = MAILTRAP_USERNAME;
        $password = MAILTRAP_PASSWORD;
        $usernameLen = strlen($username);
        $passwordLen = strlen($password);
        
        // Check if credentials look valid
        $credentialsLookValid = (
            $username !== 'YOUR_MAILTRAP_USERNAME' &&
            $password !== 'YOUR_MAILTRAP_PASSWORD' &&
            $usernameLen >= 16 &&
            $passwordLen >= 16
        );
        
        $credentialsConfigured = (
            $username !== 'YOUR_MAILTRAP_USERNAME' &&
            $password !== 'YOUR_MAILTRAP_PASSWORD'
        );
        
        if (!$credentialsConfigured) {
            echo '<div class="error-box">';
            echo '<h2 style="margin-top:0;">❌ Credentials Not Configured</h2>';
            echo '<p>Your Mailtrap credentials are still set to the default placeholder values.</p>';
            echo '</div>';
        } else if (!$credentialsLookValid) {
            echo '<div class="warning-box">';
            echo '<h2 style="margin-top:0;">⚠️ Credentials May Be Incomplete</h2>';
            echo '<p><strong>Your current credentials:</strong></p>';
            echo '<table>';
            echo '<tr><td>Username:</td><td>' . htmlspecialchars($username) . ' <span class="badge badge-warning">Length: ' . $usernameLen . ' chars</span></td></tr>';
            echo '<tr><td>Password:</td><td>' . str_repeat('•', $passwordLen) . ' <span class="badge badge-warning">Length: ' . $passwordLen . ' chars</span></td></tr>';
            echo '</table>';
            echo '<p><strong>⚠️ Warning:</strong> Mailtrap credentials are typically <strong>16 characters or longer</strong>.</p>';
            echo '<p>Your credentials appear to be shorter than expected, which is why authentication is failing.</p>';
            echo '</div>';
        } else {
            echo '<div class="success-box">';
            echo '<h2 style="margin-top:0;">✅ Credentials Look Valid</h2>';
            echo '<table>';
            echo '<tr><td>Username:</td><td>' . htmlspecialchars($username) . ' <span class="badge badge-success">Length: ' . $usernameLen . ' chars</span></td></tr>';
            echo '<tr><td>Password:</td><td>' . str_repeat('•', $passwordLen) . ' <span class="badge badge-success">Length: ' . $passwordLen . ' chars</span></td></tr>';
            echo '</table>';
            echo '<p>Your credentials appear to be the correct length.</p>';
            echo '</div>';
        }
        ?>
        
        <div class="error-box">
            <h2 style="margin-top:0;">🔍 Diagnostic Results</h2>
            <p><strong>Current Error:</strong> <code>535 5.7.0 Invalid credentials</code></p>
            <p>This means your Mailtrap username and/or password are <strong>incorrect</strong>.</p>
        </div>
        
        <div class="info-box">
            <h2 style="margin-top:0;">📋 How to Fix This</h2>
            
            <div class="step">
                <h3>Step 1: Get Your Correct Mailtrap Credentials</h3>
                <ol>
                    <li>Open your browser and go to: <a href="https://mailtrap.io/inboxes" target="_blank"><strong>https://mailtrap.io/inboxes</strong></a></li>
                    <li>Log in to your Mailtrap account</li>
                    <li>Click on your inbox (or create a new one)</li>
                    <li>Click the <span class="highlight">"SMTP Settings"</span> or <span class="highlight">"Integrations"</span> tab</li>
                    <li>You'll see credentials like this:</li>
                </ol>
                
                <div class="credential-box">
Host: sandbox.smtp.mailtrap.io<br>
Port: 2525<br>
<strong style="color: #fbbf24;">Username: a1b2c3d4e5f6g7h8</strong>  ← Copy this ENTIRE string<br>
<strong style="color: #fbbf24;">Password: 1a2b3c4d5e6f7g8h</strong>  ← Copy this ENTIRE string<br>
Auth: PLAIN, LOGIN and CRAM-MD5
                </div>
                
                <p><strong>⚠️ Important:</strong> Make sure you copy the <strong>COMPLETE</strong> username and password. They should be at least 16 characters long.</p>
            </div>
            
            <div class="step">
                <h3>Step 2: Update Your Configuration File</h3>
                <ol>
                    <li>Open the file: <code>C:\xampp\htdocs\scratch\inc\mailer.php</code></li>
                    <li>Find lines 11-12:</li>
                </ol>
                
                <div class="credential-box">
const MAILTRAP_USERNAME = '<strong style="color: #dc2626;"><?php echo htmlspecialchars($username); ?></strong>'; // ← REPLACE THIS<br>
const MAILTRAP_PASSWORD = '<strong style="color: #dc2626;"><?php echo str_repeat('•', strlen($password)); ?></strong>'; // ← REPLACE THIS
                </div>
                
                <ol start="3">
                    <li>Replace with your <strong>ACTUAL</strong> Mailtrap credentials</li>
                    <li><strong>Save the file</strong></li>
                </ol>
            </div>
            
            <div class="step">
                <h3>Step 3: Test Again</h3>
                <p>After updating the credentials, test your email system:</p>
                <a href="test_email_system.php" class="btn btn-green">Test Email System</a>
                <a href="quick_test_email.php" class="btn btn-blue">Run CLI Test</a>
            </div>
        </div>
        
        <div class="warning-box">
            <h3>⚠️ Common Mistakes</h3>
            <ul>
                <li>❌ Copying only part of the username or password</li>
                <li>❌ Including spaces before or after the credentials</li>
                <li>❌ Using your Mailtrap login email/password (those are different!)</li>
                <li>❌ Using credentials from a different inbox</li>
                <li>❌ Using an expired or old API token</li>
            </ul>
            
            <h3>✅ What to Do</h3>
            <ul>
                <li>✅ Copy the <strong>complete</strong> SMTP username and password from Mailtrap</li>
                <li>✅ Make sure there are no extra spaces or line breaks</li>
                <li>✅ Use the credentials from the inbox you want to test</li>
                <li>✅ Verify your Mailtrap account is still active</li>
            </ul>
        </div>
        
        <hr style="margin: 40px 0; border: none; border-top: 2px solid #e5e7eb;">
        
        <h2>📚 Additional Resources</h2>
        <p>
            <a href="FIX_EMAIL_CREDENTIALS.md" class="btn btn-blue">📖 Detailed Fix Guide</a>
            <a href="EMAIL_SETUP.md" class="btn btn-blue">📧 Email Setup Guide</a>
            <a href="test_email_debug.php" class="btn btn-blue">🔍 Advanced Debug Tool</a>
        </p>
        
        <p style="text-align: center; margin-top: 40px;">
            <a href="/" class="btn">← Back to Home</a>
        </p>
    </div>
</body>
</html>






