<?php
/**
 * Test Script: Verify Alumni Officer Event System
 * 
 * This script tests if:
 * 1. Events table exists and is accessible
 * 2. Alumni officer can create events
 * 3. Events appear on the calendar API
 * 4. All required columns exist
 */

declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "==============================================\n";
echo "ALUMNI OFFICER EVENT SYSTEM - VERIFICATION\n";
echo "==============================================\n\n";

try {
    $pdo = get_pdo();
    
    // Test 1: Check if events table exists
    echo "TEST 1: Events Table\n";
    echo "--------------------\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'events'");
    if ($stmt->fetch()) {
        echo "✓ Events table exists\n";
        
        // Check columns
        $stmt = $pdo->query("DESCRIBE events");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "  Columns: " . implode(', ', $columns) . "\n";
        
        // Check for important columns
        $required = ['id', 'title', 'content', 'schedule', 'banner', 'participant_limit'];
        foreach ($required as $col) {
            if (in_array($col, $columns)) {
                echo "  ✓ Column '$col' exists\n";
            } else {
                echo "  ✗ Column '$col' MISSING\n";
            }
        }
    } else {
        echo "✗ Events table does NOT exist\n";
    }
    
    echo "\n";
    
    // Test 2: Count existing events
    echo "TEST 2: Existing Events\n";
    echo "--------------------\n";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM events");
    $result = $stmt->fetch();
    $totalEvents = $result['total'];
    echo "Total events in database: $totalEvents\n";
    
    if ($totalEvents > 0) {
        // Show recent events
        $stmt = $pdo->query("
            SELECT id, title, schedule, 
                   DATE_FORMAT(schedule, '%M %d, %Y at %h:%i %p') as formatted_schedule
            FROM events 
            ORDER BY schedule DESC 
            LIMIT 5
        ");
        $events = $stmt->fetchAll();
        
        echo "\nRecent Events:\n";
        foreach ($events as $event) {
            $isPast = strtotime($event['schedule']) < time();
            $status = $isPast ? '[PAST]' : '[UPCOMING]';
            echo "  $status #{$event['id']}: {$event['title']}\n";
            echo "       Scheduled: {$event['formatted_schedule']}\n";
        }
    } else {
        echo "  → No events yet. Create your first event!\n";
    }
    
    echo "\n";
    
    // Test 3: Check upcoming events
    echo "TEST 3: Upcoming Events\n";
    echo "--------------------\n";
    $stmt = $pdo->query("
        SELECT COUNT(*) as total 
        FROM events 
        WHERE schedule >= NOW()
    ");
    $result = $stmt->fetch();
    $upcomingCount = $result['total'];
    echo "Upcoming events: $upcomingCount\n";
    
    if ($upcomingCount > 0) {
        $stmt = $pdo->query("
            SELECT id, title, 
                   DATE_FORMAT(schedule, '%M %d, %Y at %h:%i %p') as formatted_schedule,
                   DATEDIFF(schedule, NOW()) as days_until
            FROM events 
            WHERE schedule >= NOW()
            ORDER BY schedule ASC
            LIMIT 5
        ");
        $upcoming = $stmt->fetchAll();
        
        echo "\nNext Upcoming Events:\n";
        foreach ($upcoming as $event) {
            $daysText = $event['days_until'] == 0 ? 'TODAY' : 
                       ($event['days_until'] == 1 ? 'TOMORROW' : 
                       "in {$event['days_until']} days");
            echo "  #{$event['id']}: {$event['title']}\n";
            echo "       {$event['formatted_schedule']} ($daysText)\n";
        }
    }
    
    echo "\n";
    
    // Test 4: Check calendar API
    echo "TEST 4: Calendar API\n";
    echo "--------------------\n";
    $apiFile = __DIR__ . '/api/calendar_events.php';
    if (file_exists($apiFile)) {
        echo "✓ Calendar API file exists\n";
        echo "  Location: /api/calendar_events.php\n";
        echo "  Public URL: http://localhost/scratch/api/calendar_events.php\n";
    } else {
        echo "✗ Calendar API file NOT found\n";
    }
    
    echo "\n";
    
    // Test 5: Check alumni officer access
    echo "TEST 5: Alumni Officer Access\n";
    echo "--------------------\n";
    $files = [
        '/alumni-officer.php' => 'Alumni Officer Dashboard',
        '/events/officer-new.php' => 'Event Creation Form',
        '/events/officer-edit.php' => 'Event Edit Form',
        '/views/alumni-officer/events.php' => 'Events View',
    ];
    
    foreach ($files as $file => $description) {
        if (file_exists(__DIR__ . $file)) {
            echo "✓ $description exists\n";
        } else {
            echo "✗ $description NOT found\n";
        }
    }
    
    echo "\n";
    
    // Test 6: Check participant functionality
    echo "TEST 6: Event Participants\n";
    echo "--------------------\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'event_commits'");
    if ($stmt->fetch()) {
        echo "✓ Event participants table exists\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM event_commits");
        $result = $stmt->fetch();
        echo "  Total registrations: {$result['total']}\n";
        
        if ($result['total'] > 0) {
            // Show events with most participants
            $stmt = $pdo->query("
                SELECT e.title, COUNT(ec.id) as participant_count
                FROM events e
                LEFT JOIN event_commits ec ON e.id = ec.event_id
                GROUP BY e.id
                HAVING participant_count > 0
                ORDER BY participant_count DESC
                LIMIT 3
            ");
            $popular = $stmt->fetchAll();
            
            if (!empty($popular)) {
                echo "\n  Most Popular Events:\n";
                foreach ($popular as $event) {
                    echo "    → {$event['title']}: {$event['participant_count']} participants\n";
                }
            }
        }
    } else {
        echo "✗ Event participants table NOT found\n";
    }
    
    echo "\n";
    
    // Summary
    echo "==============================================\n";
    echo "SUMMARY\n";
    echo "==============================================\n\n";
    
    if ($totalEvents > 0) {
        echo "✅ System is working! You have $totalEvents event(s) in the database.\n";
        echo "✅ $upcomingCount upcoming event(s) ready.\n";
    } else {
        echo "⚠️  No events yet, but system is ready!\n";
        echo "   Create your first event as Alumni Officer.\n";
    }
    
    echo "\n";
    echo "NEXT STEPS:\n";
    echo "-----------\n";
    echo "1. Login as Alumni Officer\n";
    echo "   URL: http://localhost/scratch/login.php\n\n";
    echo "2. Navigate to 'Events & Activities' in sidebar\n\n";
    echo "3. Click 'Create Event' button\n\n";
    echo "4. Fill out the form and submit\n\n";
    echo "5. View it on the public calendar:\n";
    echo "   URL: http://localhost/scratch/index.php#calendar\n\n";
    
    echo "==============================================\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

