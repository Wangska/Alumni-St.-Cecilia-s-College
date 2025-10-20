<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

echo "<h2>Events Debug Information</h2>";

try {
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule ASC");
    $stmt->execute();
    $events = $stmt->fetchAll();
    
    echo "<h3>Total Events: " . count($events) . "</h3>";
    
    foreach ($events as $event) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<h4>Event ID: " . $event['id'] . "</h4>";
        echo "<p><strong>Title:</strong> " . htmlspecialchars($event['title']) . "</p>";
        echo "<p><strong>Schedule:</strong> " . $event['schedule'] . "</p>";
        echo "<p><strong>Banner:</strong> " . ($event['banner'] ? htmlspecialchars($event['banner']) : 'NULL') . "</p>";
        
        if (!empty($event['banner'])) {
            echo "<p><strong>Banner Image:</strong></p>";
            echo "<img src='/scratch/" . htmlspecialchars($event['banner']) . "' style='max-width: 200px; max-height: 150px; border: 1px solid #ccc;'>";
        } else {
            echo "<p><strong>No banner image</strong></p>";
        }
        
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
