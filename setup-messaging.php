<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';

// Only allow admin or alumni officer to run setup
require_login();
$user = current_user();
if (!in_array((int)($user['type'] ?? 3), [1, 2])) {
    http_response_code(403);
    exit('Only administrators and alumni officers can run setup.');
}

$pdo = get_pdo();
$success = false;
$error = null;
$tableExists = false;

// Check if messages table already exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
    $tableExists = $stmt->rowCount() > 0;
} catch (Exception $e) {
    $error = "Error checking table: " . $e->getMessage();
}

// Create table if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_table'])) {
    try {
        $sql = "
        CREATE TABLE IF NOT EXISTS `messages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `sender_id` int(11) NOT NULL,
          `receiver_id` int(11) NOT NULL,
          `subject` varchar(255) DEFAULT NULL,
          `message` text NOT NULL,
          `is_read` tinyint(1) DEFAULT 0,
          `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `idx_sender` (`sender_id`),
          KEY `idx_receiver` (`receiver_id`),
          KEY `idx_is_read` (`is_read`),
          KEY `idx_date_created` (`date_created`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        
        $pdo->exec($sql);
        $success = true;
        $tableExists = true;
        
        // Create a sample message
        $stmt = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, subject, message, is_read, date_created) VALUES (?, ?, ?, ?, 0, NOW())');
        $stmt->execute([
            $user['id'],
            $user['id'],
            'Welcome to Messaging!',
            'This is a test message. The messaging system is now set up and ready to use!'
        ]);
        
    } catch (Exception $e) {
        $error = "Error creating table: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging System Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .setup-card {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 30px;
        }
        .status-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .status-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.5);
        }
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <?php if ($success): ?>
            <div class="status-icon status-success">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="text-center mb-4" style="color: #11998e; font-weight: 700;">
                Setup Complete! 🎉
            </h2>
            <p class="text-center text-muted mb-4">
                The messaging system has been successfully installed and is ready to use.
            </p>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Messages table created successfully!</strong><br>
                A sample test message has been added to your inbox.
            </div>
            <div class="d-grid gap-2">
                <a href="/scratch/alumni-officer.php?page=messages" class="btn btn-success">
                    <i class="fas fa-envelope me-2"></i>Go to Messages
                </a>
                <a href="/scratch/alumni-officer.php" class="btn btn-secondary">
                    <i class="fas fa-home me-2"></i>Back to Dashboard
                </a>
            </div>
            
        <?php elseif ($tableExists): ?>
            <div class="status-icon status-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="text-center mb-4" style="color: #11998e; font-weight: 700;">
                Already Set Up ✓
            </h2>
            <p class="text-center text-muted mb-4">
                The messaging system is already installed and ready to use.
            </p>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                The <strong>messages</strong> table already exists in your database.
            </div>
            <div class="d-grid gap-2">
                <a href="/scratch/alumni-officer.php?page=messages" class="btn btn-success">
                    <i class="fas fa-envelope me-2"></i>Go to Messages
                </a>
                <a href="/scratch/alumni-officer.php" class="btn btn-secondary">
                    <i class="fas fa-home me-2"></i>Back to Dashboard
                </a>
            </div>
            
        <?php else: ?>
            <div class="status-icon status-warning">
                <i class="fas fa-database"></i>
            </div>
            <h2 class="text-center mb-4" style="color: #f5576c; font-weight: 700;">
                Messaging System Setup
            </h2>
            <p class="text-center text-muted mb-4">
                The messaging system needs to be installed. Click the button below to create the necessary database table.
            </p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                <strong>What this will do:</strong>
                <ul class="mb-0 mt-2">
                    <li>Create the <code>messages</code> table in your database</li>
                    <li>Add necessary indexes for performance</li>
                    <li>Create a sample test message</li>
                </ul>
            </div>
            
            <form method="POST" class="d-grid gap-2">
                <button type="submit" name="create_table" class="btn btn-primary">
                    <i class="fas fa-rocket me-2"></i>Install Messaging System
                </button>
                <a href="/scratch/alumni-officer.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Cancel
                </a>
            </form>
        <?php endif; ?>
        
        <hr class="my-4">
        <div class="text-center">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-2"></i>
                Logged in as <strong><?= htmlspecialchars($user['name']) ?></strong>
            </small>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

