<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pdo = get_pdo();

echo "<h2>Test Event Participants</h2>";
echo "<style>
    .container { max-width: 800px; margin: 20px auto; padding: 20px; }
    .success { color: green; }
    .error { color: red; }
    .info { color: blue; }
</style>";

echo "<div class='container'>";

try {
    // Check events table
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule DESC");
    $stmt->execute();
    $events = $stmt->fetchAll();
    
    echo "<h3>Events in Database:</h3>";
    echo "<p>Found " . count($events) . " events</p>";
    
    foreach ($events as $event) {
        echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 8px;'>";
        echo "<h4>Event: " . htmlspecialchars($event['title']) . "</h4>";
        echo "<p><strong>ID:</strong> " . $event['id'] . "</p>";
        echo "<p><strong>Schedule:</strong> " . $event['schedule'] . "</p>";
        
        // Check participants for this event
        $stmt = $pdo->prepare("
            SELECT ec.*, u.username, ab.firstname, ab.lastname, ab.email
            FROM event_commits ec
            LEFT JOIN users u ON ec.user_id = u.id
            LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
            WHERE ec.event_id = ?
        ");
        $stmt->execute([$event['id']]);
        $participants = $stmt->fetchAll();
        
        echo "<p><strong>Participants:</strong> " . count($participants) . "</p>";
        
        if (!empty($participants)) {
            echo "<h5>Participant Details:</h5>";
            foreach ($participants as $participant) {
                echo "<div style='margin-left: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 10px;'>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars(($participant['firstname'] ?? '') . ' ' . ($participant['lastname'] ?? '')) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($participant['email'] ?? $participant['username'] ?? 'N/A') . "</p>";
                echo "<p><strong>User ID:</strong> " . $participant['user_id'] . "</p>";
                echo "<p><strong>Event ID:</strong> " . $participant['event_id'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p class='info'>No participants found for this event</p>";
        }
        
        echo "</div>";
    }
    
    // Test adding a participant
    if (!empty($events)) {
        $eventId = $events[0]['id'];
        echo "<h3>Test Adding Participant:</h3>";
        
        // Check if current user is already a participant
        $stmt = $pdo->prepare("SELECT * FROM event_commits WHERE event_id = ? AND user_id = ?");
        $stmt->execute([$eventId, $user['id']]);
        $existingParticipant = $stmt->fetch();
        
        if ($existingParticipant) {
            echo "<p class='info'>You are already a participant in this event</p>";
        } else {
            // Add current user as participant
            $stmt = $pdo->prepare("INSERT INTO event_commits (event_id, user_id) VALUES (?, ?)");
            $stmt->execute([$eventId, $user['id']]);
            echo "<p class='success'>✓ Added you as a participant to test the system</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";
?>
