<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_login();

$user = current_user();
$pageTitle = 'Events - SCC Alumni';

$pdo = get_pdo();

// Using your existing table structure

// Handle event participation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $eventId = (int)($_POST['event_id'] ?? 0);
    
    if ($eventId > 0) {
        try {
            if ($action === 'join') {
                // Check if user is already registered
                $stmt = $pdo->prepare("SELECT id FROM event_commits WHERE event_id = ? AND user_id = ?");
                $stmt->execute([$eventId, $user['id']]);
                
                if ($stmt->fetch()) {
                    $_SESSION['error'] = 'You are already registered for this event.';
                } else {
                    // Check participant limit
                    $stmt = $pdo->prepare("SELECT participant_limit, (SELECT COUNT(*) FROM event_commits WHERE event_id = ?) as current_count FROM events WHERE id = ?");
                    $stmt->execute([$eventId, $eventId]);
                    $eventData = $stmt->fetch();
                    
                    if ($eventData['participant_limit'] && $eventData['current_count'] >= $eventData['participant_limit']) {
                        $_SESSION['error'] = 'This event is full. No more participants can be registered.';
                    } else {
                        // Register for event
                        $stmt = $pdo->prepare("INSERT INTO event_commits (event_id, user_id) VALUES (?, ?)");
                        $stmt->execute([$eventId, $user['id']]);
                        $_SESSION['success'] = 'Successfully registered for the event!';
                    }
                }
            } elseif ($action === 'leave') {
                // Unregister from event
                $stmt = $pdo->prepare("DELETE FROM event_commits WHERE event_id = ? AND user_id = ?");
                $stmt->execute([$eventId, $user['id']]);
                $_SESSION['success'] = 'Successfully unregistered from the event.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'An error occurred. Please try again.';
        }
    }
    
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

        // Fetch events with participation status using your existing table structure
        try {
            $stmt = $pdo->prepare("
                SELECT e.*, 
                       COUNT(ec.id) as participant_count,
                       CASE WHEN EXISTS(
                           SELECT 1 FROM event_commits ec2 
                           WHERE ec2.event_id = e.id AND ec2.user_id = ?
                       ) THEN 1 ELSE 0 END as is_registered
                FROM events e 
                LEFT JOIN event_commits ec ON e.id = ec.event_id AND ec.user_id != 1
                GROUP BY e.id 
                ORDER BY e.schedule ASC
            ");
            $stmt->execute([$user['id']]);
            $events = $stmt->fetchAll();
        } catch (Exception $e) {
            $events = [];
        }

include __DIR__ . '/../inc/header.php';
?>

<style>
/* Events Page Styles */
.events-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}

.event-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.event-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.event-image {
    height: 250px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.event-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.event-body {
    padding: 1.5rem;
}

.event-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.btn-join {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-join:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-leave {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-leave:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 16px;
    margin: 2rem 0;
}

.empty-state i {
    font-size: 4rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #374151;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 2rem;
}

.event-status {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-upcoming {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.status-registration-open {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.status-registration-closed {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-full {
    background: linear-gradient(135deg, #fecaca, #fca5a5);
    color: #991b1b;
}

.status-registration-open {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}
</style>

<div class="container main-content">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="events-container">
                <div class="event-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1" style="color: #2d3142; font-weight: 700;">
                                <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>Upcoming Events
                            </h2>
                            <p class="text-muted mb-0">Join exciting events and connect with fellow alumni</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (empty($events)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>No Events Available</h3>
                <p>Check back later for upcoming alumni events and activities!</p>
            </div>
            <?php else: ?>
                <!-- Events Grid -->
                <div class="row">
                    <?php foreach ($events as $event): ?>
                        <?php
                        $eventDate = new DateTime($event['schedule']);
                        $now = new DateTime();
                        $isUpcoming = $eventDate > $now;
                        
                        $isFull = isset($event['participant_limit']) && $event['participant_limit'] && $event['participant_count'] >= $event['participant_limit'];
                        
                        $statusClass = 'status-upcoming';
                        $statusText = 'Upcoming';
                        
                        if (!$isUpcoming) {
                            $statusClass = 'status-registration-closed';
                            $statusText = 'Past Event';
                        } elseif ($isFull) {
                            $statusClass = 'status-full';
                            $statusText = 'Event Full';
                        } else {
                            $statusClass = 'status-registration-open';
                            $statusText = 'Registration Open';
                        }
                        ?>
                        <div class="col-lg-6 mb-4">
                            <div class="event-card">
                                <div class="event-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1" style="color: #2d3142; font-weight: 700; font-size: 1.25rem;">
                                                <?= htmlspecialchars($event['title']) ?>
                                            </h5>
                                            <small class="text-muted" style="font-size: 0.9rem;">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= $eventDate->format('M d, Y \a\t g:i A') ?>
                                            </small>
                                        </div>
                                        <div>
                                            <span class="event-status <?= $statusClass ?>"><?= $statusText ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="event-body">
                                    <div class="mb-3">
                                        <?php if (!empty($event['banner']) && file_exists(__DIR__ . '/../uploads/' . $event['banner'])): ?>
                                            <img src="/scratch/uploads/<?= htmlspecialchars($event['banner']) ?>" 
                                                 alt="<?= htmlspecialchars($event['title']) ?>" 
                                                 class="event-image" 
                                                 style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px;">
                                        <?php else: ?>
                                            <div style="height: 250px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                <div class="text-center text-white">
                                                    <i class="fas fa-calendar-alt" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                                    <h5 class="mb-0"><?= htmlspecialchars($event['title']) ?></h5>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="card-text" style="color: #4b5563; line-height: 1.6; font-size: 0.95rem;">
                                        <?= htmlspecialchars(substr($event['content'], 0, 200)) ?><?= strlen($event['content']) > 200 ? '...' : '' ?>
                                    </p>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            <strong><?= $event['participant_count'] ?></strong>
                                            <?php if (isset($event['participant_limit']) && $event['participant_limit']): ?>
                                                / <strong><?= $event['participant_limit'] ?></strong> participants
                                                <span class="badge bg-info ms-2"><?= $event['participant_limit'] - $event['participant_count'] ?> spots left</span>
                                            <?php else: ?>
                                                participants
                                            <?php endif; ?>
                                        </small>
                                        
                                        <?php if (isset($event['participant_limit']) && $event['participant_limit']): ?>
                                            <?php 
                                            $percentage = ($event['participant_count'] / $event['participant_limit']) * 100;
                                            $barColor = $percentage >= 100 ? '#ef4444' : ($percentage >= 80 ? '#f59e0b' : '#10b981');
                                            ?>
                                            <div class="mt-2">
                                                <div style="background: #f0f0f0; height: 8px; border-radius: 4px; overflow: hidden;">
                                                    <div style="background: <?= $barColor ?>; height: 100%; width: <?= min($percentage, 100) ?>%; transition: width 0.3s;"></div>
                                                </div>
                                                <small class="text-muted"><?= round($percentage, 1) ?>% capacity</small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="event-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Event created: <?= (new DateTime($event['date_created']))->format('M d, Y') ?>
                                        </small>
                                        
                                        <?php if ($isUpcoming && !$isFull): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                                <?php if ($event['is_registered']): ?>
                                                    <button type="submit" name="action" value="leave" class="btn btn-leave">
                                                        <i class="fas fa-user-minus me-1"></i>Leave Event
                                                    </button>
                                                <?php else: ?>
                                                    <button type="submit" name="action" value="join" class="btn btn-join">
                                                        <i class="fas fa-user-plus me-1"></i>Join Event
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        <?php elseif ($isFull): ?>
                                            <button class="btn btn-secondary" disabled>
                                                <i class="fas fa-users me-1"></i>Event Full
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../inc/footer.php'; ?>