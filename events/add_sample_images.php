<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

echo "<h2>Add Sample Event Images</h2>";
echo "<style>
    .container { max-width: 800px; margin: 20px auto; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
</style>";

echo "<div class='container'>";

try {
    // Create images directory if it doesn't exist
    $imagesDir = __DIR__ . '/../images/events';
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
        echo "<p class='success'>✓ Created images/events directory</p>";
    }
    
    // Check existing events
    $stmt = $pdo->prepare("SELECT * FROM events WHERE banner IS NULL OR banner = ''");
    $stmt->execute();
    $eventsWithoutBanners = $stmt->fetchAll();
    
    echo "<p>Found " . count($eventsWithoutBanners) . " events without banner images</p>";
    
    if (empty($eventsWithoutBanners)) {
        echo "<p>All events already have banner images!</p>";
    } else {
        // Add sample banner images to events
        $sampleBanners = [
            'images/events/homecoming.jpg',
            'images/events/workshop.jpg', 
            'images/events/networking.jpg',
            'images/events/conference.jpg',
            'images/events/seminar.jpg'
        ];
        
        $stmt = $pdo->prepare("UPDATE events SET banner = ? WHERE id = ?");
        
        foreach ($eventsWithoutBanners as $index => $event) {
            $bannerPath = $sampleBanners[$index % count($sampleBanners)];
            $stmt->execute([$bannerPath, $event['id']]);
            echo "<p class='success'>✓ Added banner '{$bannerPath}' to event: " . htmlspecialchars($event['title']) . "</p>";
        }
        
        echo "<p class='success'>✓ Updated " . count($eventsWithoutBanners) . " events with sample banner images</p>";
    }
    
    // Show current events
    echo "<h3>Current Events:</h3>";
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule ASC");
    $stmt->execute();
    $allEvents = $stmt->fetchAll();
    
    foreach ($allEvents as $event) {
        echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 10px; border-radius: 5px;'>";
        echo "<h4>" . htmlspecialchars($event['title']) . "</h4>";
        echo "<p><strong>Banner:</strong> " . ($event['banner'] ? htmlspecialchars($event['banner']) : 'None') . "</p>";
        if ($event['banner']) {
            echo "<img src='/scratch/" . htmlspecialchars($event['banner']) . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
            echo "<p style='display:none; color:red;'>❌ Image not found</p>";
        }
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>
