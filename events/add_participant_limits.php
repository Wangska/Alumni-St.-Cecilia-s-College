<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

echo "<h2>Add Participant Limits to Events</h2>";
echo "<style>
    .container { max-width: 800px; margin: 20px auto; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
</style>";

echo "<div class='container'>";

try {
    // Check if max_participants column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM events LIKE 'max_participants'");
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        // Add max_participants column to events table
        $pdo->exec("ALTER TABLE events ADD COLUMN max_participants INT(11) DEFAULT NULL AFTER banner");
        echo "<p class='success'>✓ Added max_participants column to events table</p>";
    } else {
        echo "<p class='info'>✓ max_participants column already exists</p>";
    }
    
    // Update existing events with default participant limits
    $stmt = $pdo->prepare("UPDATE events SET max_participants = ? WHERE max_participants IS NULL");
    $stmt->execute([50]); // Default limit of 50 participants
    echo "<p class='success'>✓ Set default participant limit of 50 for existing events</p>";
    
    // Show current events with their limits
    $stmt = $pdo->prepare("
        SELECT e.*, 
               COUNT(ec.id) as current_participants
        FROM events e 
        LEFT JOIN event_commits ec ON e.id = ec.event_id
        GROUP BY e.id 
        ORDER BY e.schedule ASC
    ");
    $stmt->execute();
    $events = $stmt->fetchAll();
    
    echo "<h3>Current Events with Participant Limits:</h3>";
    
    foreach ($events as $event) {
        $isFull = $event['max_participants'] && $event['current_participants'] >= $event['max_participants'];
        $statusClass = $isFull ? 'error' : 'success';
        $statusText = $isFull ? 'FULL' : 'Available';
        
        echo "<div style='border: 1px solid #ddd; margin: 15px 0; padding: 15px; border-radius: 8px;'>";
        echo "<h4>" . htmlspecialchars($event['title']) . "</h4>";
        echo "<p><strong>Schedule:</strong> " . $event['schedule'] . "</p>";
        echo "<p><strong>Max Participants:</strong> " . ($event['max_participants'] ?: 'No limit') . "</p>";
        echo "<p><strong>Current Participants:</strong> " . $event['current_participants'] . "</p>";
        echo "<p class='{$statusClass}'><strong>Status:</strong> {$statusText}</p>";
        
        if ($event['max_participants']) {
            $percentage = ($event['current_participants'] / $event['max_participants']) * 100;
            echo "<div style='background: #f0f0f0; height: 20px; border-radius: 10px; overflow: hidden;'>";
            echo "<div style='background: " . ($percentage >= 100 ? '#ef4444' : ($percentage >= 80 ? '#f59e0b' : '#10b981')) . "; height: 100%; width: {$percentage}%; transition: width 0.3s;'></div>";
            echo "</div>";
            echo "<p><small>Capacity: " . round($percentage, 1) . "%</small></p>";
        }
        
        echo "</div>";
    }
    
    echo "<h3>Features Added:</h3>";
    echo "<div class='info'>";
    echo "<p><strong>✓ Participant Limits:</strong> Events can now have maximum participant limits</p>";
    echo "<p><strong>✓ Capacity Tracking:</strong> Shows current vs maximum participants</p>";
    echo "<p><strong>✓ Full Event Detection:</strong> Prevents joining when event is full</p>";
    echo "<p><strong>✓ Visual Indicators:</strong> Progress bars and status indicators</p>";
    echo "<p><strong>✓ Admin Visibility:</strong> Admins can see participant counts</p>";
    echo "</div>";
    
    echo "<h3>Test the Features:</h3>";
    echo "<p><a href='index.php' style='background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View Events Page</a></p>";
    echo "<p><a href='../dashboard.php' style='background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>
