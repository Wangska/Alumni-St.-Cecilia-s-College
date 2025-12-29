<?php
// API endpoint to fetch events for the calendar
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';

header('Content-Type: application/json');

try {
    $pdo = get_pdo();
    
    // Check if allow_registration column exists
    $hasAllowReg = false;
    try {
        $check = $pdo->query("SHOW COLUMNS FROM events LIKE 'allow_registration'")->fetch();
        $hasAllowReg = !empty($check);
    } catch (Exception $e) {
        $hasAllowReg = false;
    }
    
    // Fetch all upcoming events
    $allowRegColumn = $hasAllowReg ? 'e.allow_registration,' : '1 as allow_registration,';
    $stmt = $pdo->prepare("
        SELECT 
            e.id,
            e.title,
            e.content,
            e.schedule as start,
            COALESCE(e.end_date, e.schedule) as end,
            e.banner,
            e.participant_limit,
            $allowRegColumn
            COUNT(ec.id) as participant_count
        FROM events e
        LEFT JOIN event_commits ec ON e.id = ec.event_id
        WHERE e.schedule >= NOW() - INTERVAL 30 DAY
        GROUP BY e.id
        ORDER BY e.schedule ASC
    ");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format events for FullCalendar
    $calendarEvents = [];
    foreach ($events as $event) {
        $isFull = false;
        if ($event['participant_limit']) {
            $isFull = $event['participant_count'] >= $event['participant_limit'];
        }
        
        $allowRegistration = isset($event['allow_registration']) ? (int)$event['allow_registration'] : 1;
        $isInfoOnly = $allowRegistration === 0;
        
        // Color coding: Blue for info-only, Red for full, Green for available
        $backgroundColor = $isInfoOnly ? '#0ea5e9' : ($isFull ? '#ef4444' : '#10b981');
        $borderColor = $isInfoOnly ? '#0284c7' : ($isFull ? '#dc2626' : '#059669');
        
        $calendarEvents[] = [
            'id' => $event['id'],
            'title' => $event['title'],
            'start' => $event['start'],
            'end' => $event['end'],
            'description' => substr($event['content'], 0, 200),
            'participantCount' => (int)$event['participant_count'],
            'participantLimit' => $event['participant_limit'] ? (int)$event['participant_limit'] : null,
            'isFull' => $isFull,
            'allowRegistration' => $allowRegistration,
            'isInfoOnly' => $isInfoOnly,
            'backgroundColor' => $backgroundColor,
            'borderColor' => $borderColor,
            'textColor' => '#ffffff',
            'allDay' => false // Allow time to be shown
        ];
    }
    
    echo json_encode($calendarEvents);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch events']);
}
