<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

echo "<h2>Check Banner Image Path</h2>";
echo "<style>
    .container { max-width: 800px; margin: 20px auto; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
    .event-debug { border: 1px solid #ddd; margin: 15px 0; padding: 15px; border-radius: 8px; }
</style>";

echo "<div class='container'>";

try {
    // Get all events with their banner info
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule ASC");
    $stmt->execute();
    $events = $stmt->fetchAll();
    
    echo "<h3>Checking " . count($events) . " events</h3>";
    
    foreach ($events as $event) {
        echo "<div class='event-debug'>";
        echo "<h4>Event: " . htmlspecialchars($event['title']) . "</h4>";
        echo "<p><strong>ID:</strong> " . $event['id'] . "</p>";
        echo "<p><strong>Banner in DB:</strong> " . ($event['banner'] ? htmlspecialchars($event['banner']) : 'NULL') . "</p>";
        
        if (!empty($event['banner'])) {
            // Check different possible paths
            $possiblePaths = [
                $event['banner'], // As stored in DB
                'images/' . $event['banner'], // In images folder
                'images/events/' . $event['banner'], // In events subfolder
                'uploads/' . $event['banner'], // In uploads folder
                'uploads/events/' . $event['banner'] // In uploads/events folder
            ];
            
            echo "<h5>Checking possible image locations:</h5>";
            
            foreach ($possiblePaths as $path) {
                $fullPath = __DIR__ . '/../' . $path;
                $webPath = '/scratch/' . $path;
                
                echo "<p><strong>Path:</strong> " . htmlspecialchars($path) . "</p>";
                echo "<p><strong>Full server path:</strong> " . htmlspecialchars($fullPath) . "</p>";
                echo "<p><strong>Web URL:</strong> " . htmlspecialchars($webPath) . "</p>";
                
                if (file_exists($fullPath)) {
                    echo "<p class='success'>✓ File exists!</p>";
                    echo "<p><strong>Image preview:</strong></p>";
                    echo "<img src='" . htmlspecialchars($webPath) . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
                    echo "<p style='display:none; color:red;'>❌ Image failed to load</p>";
                    
                    // This is the correct path - update the database
                    echo "<p class='info'>This is the correct path! Updating database...</p>";
                    $updateStmt = $pdo->prepare("UPDATE events SET banner = ? WHERE id = ?");
                    $updateStmt->execute([$path, $event['id']]);
                    echo "<p class='success'>✓ Updated database with correct path</p>";
                    break;
                } else {
                    echo "<p class='error'>❌ File not found</p>";
                }
                echo "<hr>";
            }
        } else {
            echo "<p class='info'>No banner set for this event</p>";
        }
        
        echo "</div>";
    }
    
    echo "<h3>Summary:</h3>";
    echo "<p>If any images were found, the database has been updated with the correct paths.</p>";
    echo "<p>Now check your <a href='index.php'>Events page</a> and <a href='../dashboard.php'>Dashboard</a> to see if the images display correctly.</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>
