<?php
/**
 * Quick Add Event API Endpoint
 * 
 * Allows alumni officers to quickly create events via AJAX
 * Events will automatically appear on the calendar
 */

declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/logger.php';

header('Content-Type: application/json');

// Check if user is logged in as alumni officer
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] != 2) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized. Only alumni officers can create events.'
    ]);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

// Verify CSRF token
$sentToken = $_POST['csrf_token'] ?? '';
$sessionToken = $_SESSION['csrf_token'] ?? '';
if (!$sentToken || !hash_equals($sessionToken, $sentToken)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid CSRF token. Please refresh the page and try again.'
    ]);
    exit;
}

try {
    $pdo = get_pdo();
    
    // Validate and sanitize input
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $schedule = trim($_POST['schedule'] ?? '');
    $participantLimit = !empty($_POST['participant_limit']) ? (int)$_POST['participant_limit'] : null;
    
    // Validation
    $errors = [];
    
    if (empty($title)) {
        $errors[] = 'Event title is required';
    } elseif (strlen($title) > 200) {
        $errors[] = 'Event title is too long (max 200 characters)';
    }
    
    if (empty($content)) {
        $errors[] = 'Event description is required';
    } elseif (strlen($content) > 5000) {
        $errors[] = 'Event description is too long (max 5000 characters)';
    }
    
    if (empty($schedule)) {
        $errors[] = 'Event date and time is required';
    } else {
        // Validate date format
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $schedule);
        if (!$dateTime) {
            $errors[] = 'Invalid date and time format';
        }
    }
    
    if ($participantLimit !== null && $participantLimit < 1) {
        $errors[] = 'Participant limit must be at least 1';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode('. ', $errors)
        ]);
        exit;
    }
    
    // Check if events table has banner and participant_limit columns
    $hasBanner = false;
    $hasParticipantLimit = false;
    
    try {
        $check = $pdo->query("SHOW COLUMNS FROM events LIKE 'banner'")->fetch();
        $hasBanner = !empty($check);
        
        $check = $pdo->query("SHOW COLUMNS FROM events LIKE 'participant_limit'")->fetch();
        $hasParticipantLimit = !empty($check);
    } catch (Exception $e) {
        // Ignore column check errors
    }
    
    // Insert event into database
    if ($hasBanner && $hasParticipantLimit) {
        $stmt = $pdo->prepare('
            INSERT INTO events (title, content, schedule, banner, participant_limit, date_created) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ');
        $stmt->execute([$title, $content, $schedule, '', $participantLimit]);
    } elseif ($hasParticipantLimit) {
        $stmt = $pdo->prepare('
            INSERT INTO events (title, content, schedule, participant_limit, date_created) 
            VALUES (?, ?, ?, ?, NOW())
        ');
        $stmt->execute([$title, $content, $schedule, $participantLimit]);
    } elseif ($hasBanner) {
        $stmt = $pdo->prepare('
            INSERT INTO events (title, content, schedule, banner, date_created) 
            VALUES (?, ?, ?, ?, NOW())
        ');
        $stmt->execute([$title, $content, $schedule, '']);
    } else {
        $stmt = $pdo->prepare('
            INSERT INTO events (title, content, schedule, date_created) 
            VALUES (?, ?, ?, NOW())
        ');
        $stmt->execute([$title, $content, $schedule]);
    }
    
    $eventId = $pdo->lastInsertId();
    
    // Log the activity
    try {
        ActivityLogger::logCreate('Event', $title);
    } catch (Exception $e) {
        // Ignore logging errors
    }
    
    // Format the event date for response
    $eventDateTime = new DateTime($schedule);
    $formattedDate = $eventDateTime->format('F j, Y \a\t g:i A');
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Event created successfully!',
        'event' => [
            'id' => $eventId,
            'title' => $title,
            'schedule' => $schedule,
            'formatted_date' => $formattedDate,
            'participant_limit' => $participantLimit
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: Failed to create event. Please try again.'
    ]);
    error_log('Quick add event error: ' . $e->getMessage());
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again.'
    ]);
    error_log('Quick add event error: ' . $e->getMessage());
}

