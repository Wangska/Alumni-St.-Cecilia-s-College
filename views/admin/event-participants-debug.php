<?php
$currentPage = 'event-participants';
$pageTitle = 'Event Participants Debug';
$title = 'Event Participants Debug';

ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2>Event Participants Debug</h2>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Debug Information</h5>
                </div>
                <div class="card-body">
                    <h6>Events Data:</h6>
                    <pre><?php print_r($events ?? 'No events data'); ?></pre>
                    
                    <h6>Event Participants Data:</h6>
                    <pre><?php print_r($eventParticipants ?? 'No participants data'); ?></pre>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>Raw Database Query Test</h5>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        require_once __DIR__ . '/../../inc/config.php';
                        $pdo = get_pdo();
                        
                        // Test events query
                        $stmt = $pdo->prepare("SELECT * FROM events ORDER BY schedule DESC LIMIT 5");
                        $stmt->execute();
                        $testEvents = $stmt->fetchAll();
                        
                        echo "<h6>Direct Events Query:</h6>";
                        echo "<pre>" . print_r($testEvents, true) . "</pre>";
                        
                        // Test participants query
                        if (!empty($testEvents)) {
                            $eventId = $testEvents[0]['id'];
                            $stmt = $pdo->prepare("
                                SELECT ec.*, u.username, ab.firstname, ab.lastname, ab.email
                                FROM event_commits ec
                                LEFT JOIN users u ON ec.user_id = u.id
                                LEFT JOIN alumnus_bio ab ON u.alumnus_id = ab.id
                                WHERE ec.event_id = ?
                            ");
                            $stmt->execute([$eventId]);
                            $testParticipants = $stmt->fetchAll();
                            
                            echo "<h6>Participants for Event ID {$eventId}:</h6>";
                            echo "<pre>" . print_r($testParticipants, true) . "</pre>";
                        }
                        
                    } catch (Exception $e) {
                        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin.php';
?>
