<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

echo "<h2>Event Images Debug</h2>";
echo "<style>
    .debug-container { max-width: 800px; margin: 20px auto; padding: 20px; }
    .event-debug { border: 1px solid #ddd; margin: 15px 0; padding: 15px; border-radius: 8px; }
    .image-test { margin: 10px 0; }
    .image-test img { max-width: 200px; max-height: 150px; border: 1px solid #ccc; margin: 5px; }
    .error { color: red; }
    .success { color: green; }
</style>";

echo "<div class='debug-container'>";

try {
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule ASC");
    $stmt->execute();
    $events = $stmt->fetchAll();
    
    echo "<h3>Found " . count($events) . " events</h3>";
    
    if (empty($events)) {
        echo "<p class='error'>No events found in database!</p>";
        echo "<p>Run <a href='sample_events.php'>sample_events.php</a> to add sample events.</p>";
    } else {
        foreach ($events as $event) {
            echo "<div class='event-debug'>";
            echo "<h4>Event: " . htmlspecialchars($event['title']) . "</h4>";
            echo "<p><strong>ID:</strong> " . $event['id'] . "</p>";
            echo "<p><strong>Schedule:</strong> " . $event['schedule'] . "</p>";
            echo "<p><strong>Banner field:</strong> " . ($event['banner'] ? htmlspecialchars($event['banner']) : 'NULL/EMPTY') . "</p>";
            
            if (!empty($event['banner'])) {
                $imagePath = "/scratch/" . $event['banner'];
                echo "<p><strong>Full image path:</strong> " . htmlspecialchars($imagePath) . "</p>";
                
                // Check if file exists
                $fullPath = __DIR__ . '/../' . $event['banner'];
                if (file_exists($fullPath)) {
                    echo "<p class='success'>✓ Image file exists on server</p>";
                    echo "<div class='image-test'>";
                    echo "<p><strong>Image preview:</strong></p>";
                    echo "<img src='" . htmlspecialchars($imagePath) . "' alt='Event banner' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
                    echo "<p style='display:none; color:red;'>❌ Image failed to load</p>";
                    echo "</div>";
                } else {
                    echo "<p class='error'>❌ Image file does not exist at: " . htmlspecialchars($fullPath) . "</p>";
                }
            } else {
                echo "<p class='error'>❌ No banner image set for this event</p>";
            }
            
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>Database error: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>
