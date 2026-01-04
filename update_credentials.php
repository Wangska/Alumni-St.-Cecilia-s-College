<!DOCTYPE html>
<html>
<head>
    <title>Update Mailtrap Credentials</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 700px; 
            margin: 50px auto; 
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        h1 { color: #dc2626; margin-top: 0; }
        .form-group {
            margin: 20px 0;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #374151;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Courier New', monospace;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .btn {
            background: #dc2626;
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .btn:hover {
            background: #991b1b;
        }
        .success {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .error {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .current {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        .help-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 5px;
        }
        .link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: bold;
        }
        .link:hover {
            text-decoration: underline;
        }
        ol {
            line-height: 1.8;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #6b7280;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Update Mailtrap Credentials</h1>
        
        <?php
        $configFile = __DIR__ . '/inc/mailer.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newUsername = trim($_POST['username'] ?? '');
            $newPassword = trim($_POST['password'] ?? '');
            
            if (empty($newUsername) || empty($newPassword)) {
                echo '<div class="error">';
                echo '<strong>❌ Error:</strong> Both username and password are required.';
                echo '</div>';
            } else if (strlen($newUsername) < 10 || strlen($newPassword) < 10) {
                echo '<div class="error">';
                echo '<strong>⚠️ Warning:</strong> Your credentials seem too short.<br>';
                echo 'Username length: ' . strlen($newUsername) . ' characters<br>';
                echo 'Password length: ' . strlen($newPassword) . ' characters<br><br>';
                echo 'Mailtrap credentials are typically 16+ characters. Please verify you copied the complete credentials.';
                echo '</div>';
            } else {
                // Read the current file
                $content = file_get_contents($configFile);
                
                if ($content === false) {
                    echo '<div class="error">';
                    echo '<strong>❌ Error:</strong> Could not read the configuration file.';
                    echo '</div>';
                } else {
                    // Replace the username and password
                    $content = preg_replace(
                        "/const MAILTRAP_USERNAME = '[^']*';/",
                        "const MAILTRAP_USERNAME = '" . addslashes($newUsername) . "';",
                        $content
                    );
                    $content = preg_replace(
                        "/const MAILTRAP_PASSWORD = '[^']*';/",
                        "const MAILTRAP_PASSWORD = '" . addslashes($newPassword) . "';",
                        $content
                    );
                    
                    // Write back to file
                    if (file_put_contents($configFile, $content)) {
                        echo '<div class="success">';
                        echo '<h2 style="margin-top:0;">✅ Credentials Updated Successfully!</h2>';
                        echo '<p><strong>New Username:</strong> ' . htmlspecialchars($newUsername) . '</p>';
                        echo '<p><strong>New Password:</strong> ' . str_repeat('•', strlen($newPassword)) . ' (' . strlen($newPassword) . ' characters)</p>';
                        echo '<br>';
                        echo '<p><strong>Next Step:</strong> Test your email system!</p>';
                        echo '<a href="test_email_system.php" class="btn">Test Email System Now</a>';
                        echo '</div>';
                    } else {
                        echo '<div class="error">';
                        echo '<strong>❌ Error:</strong> Could not write to the configuration file.<br>';
                        echo 'Please make sure the file is writable or update it manually.';
                        echo '</div>';
                    }
                }
            }
        }
        
        // Show current credentials
        require_once $configFile;
        echo '<div class="info">';
        echo '<h3 style="margin-top:0;">📋 Current Credentials in System:</h3>';
        echo '<div class="current">';
        echo '<strong>Username:</strong> ' . htmlspecialchars(MAILTRAP_USERNAME) . '<br>';
        echo '<strong>Password:</strong> ' . str_repeat('•', strlen(MAILTRAP_PASSWORD)) . '<br>';
        echo '<strong>Username Length:</strong> ' . strlen(MAILTRAP_USERNAME) . ' characters<br>';
        echo '<strong>Password Length:</strong> ' . strlen(MAILTRAP_PASSWORD) . ' characters';
        echo '</div>';
        
        if (strlen(MAILTRAP_USERNAME) < 16 || strlen(MAILTRAP_PASSWORD) < 16) {
            echo '<p><strong>⚠️ These credentials look incomplete!</strong> Mailtrap credentials should be 16+ characters.</p>';
        }
        echo '</div>';
        ?>
        
        <div class="info">
            <h3 style="margin-top:0;">📝 How to Get Your Correct Credentials:</h3>
            <ol>
                <li>Go to <a href="https://mailtrap.io/inboxes" target="_blank" class="link">mailtrap.io/inboxes</a></li>
                <li>Log in (or sign up for free if you don't have an account)</li>
                <li>Click on your inbox (or create a new one)</li>
                <li>Click <strong>"SMTP Settings"</strong> or <strong>"Integrations"</strong> tab</li>
                <li>Find the <strong>Username</strong> and <strong>Password</strong> fields</li>
                <li>Copy the COMPLETE strings (they should be 16+ characters each)</li>
                <li>Paste them into the form below</li>
            </ol>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">🔑 Mailtrap Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="e.g., a1b2c3d4e5f6g7h8"
                    required
                    autocomplete="off"
                >
                <div class="help-text">Paste the complete username from Mailtrap SMTP Settings</div>
            </div>
            
            <div class="form-group">
                <label for="password">🔐 Mailtrap Password:</label>
                <input 
                    type="text" 
                    id="password" 
                    name="password" 
                    placeholder="e.g., 8h7g6f5e4d3c2b1a"
                    required
                    autocomplete="off"
                >
                <div class="help-text">Paste the complete password from Mailtrap SMTP Settings</div>
            </div>
            
            <button type="submit" class="btn">💾 Update Credentials</button>
        </form>
        
        <p style="text-align: center; margin-top: 30px;">
            <a href="check_credentials.php" class="back-link">← Back to Credential Checker</a>
            <a href="test_email_system.php" class="back-link" style="background: #3b82f6; margin-left: 10px;">Test Email System</a>
        </p>
    </div>
</body>
</html>






