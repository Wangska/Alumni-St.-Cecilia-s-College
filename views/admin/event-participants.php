<?php
$currentPage = 'event-participants';
$pageTitle = 'Event Participants';
$title = 'Event Participants Management';

ob_start();
?>

<style>
/* Event Participants Styles */
.participants-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}

.event-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.event-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.participant-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s;
}

.participant-item:hover {
    background-color: #f8fafc;
}

.participant-item:last-child {
    border-bottom: none;
}

.participant-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin-right: 1rem;
    flex-shrink: 0;
}

.participant-info {
    flex: 1;
}

.participant-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.participant-email {
    color: #6b7280;
    font-size: 0.9rem;
}

.participant-date {
    color: #9ca3af;
    font-size: 0.8rem;
    text-align: right;
}

.empty-participants {
    text-align: center;
    padding: 3rem 2rem;
    color: #6b7280;
}

.empty-participants i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.stats-card {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1" style="color: #2d3142; font-weight: 700;">
                        <i class="fas fa-users me-2" style="color: #3b82f6;"></i>Event Participants
                    </h2>
                    <p class="text-muted mb-0">Monitor alumni participation in events</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="stats-card">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?= count($events) ?></div>
                        <div class="stat-label">Total Events</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= array_sum(array_column($events, 'participant_count')) ?></div>
                        <div class="stat-label">Total Participants</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= count($events) > 0 ? round(array_sum(array_column($events, 'participant_count')) / count($events), 1) : 0 ?></div>
                        <div class="stat-label">Avg. per Event</div>
                    </div>
                </div>
            </div>

            <!-- Events List -->
            <?php if (empty($events)): ?>
                <div class="empty-participants">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Events Found</h4>
                    <p>There are no events to display participants for.</p>
                </div>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="event-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1" style="color: #2d3142; font-weight: 700;">
                                        <?= htmlspecialchars($event['title']) ?>
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('M d, Y \a\t g:i A', strtotime($event['schedule'])) ?>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-primary" style="font-size: 0.9rem;">
                                        <?= $event['participant_count'] ?> participants
                                    </div>
                                    <?php if (isset($event['max_participants']) && $event['max_participants']): ?>
                                        <div class="text-muted small mt-1">
                                            Max: <?= $event['max_participants'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="event-body">
                            <?php if (empty($eventParticipants[$event['id']])): ?>
                                <div class="empty-participants">
                                    <i class="fas fa-user-friends"></i>
                                    <h6>No Participants Yet</h6>
                                    <p class="text-muted">No alumni have joined this event.</p>
                                </div>
                            <?php else: ?>
                                <div class="participants-list">
                                    <?php foreach ($eventParticipants[$event['id']] as $participant): ?>
                                        <div class="participant-item">
                                            <div class="participant-avatar">
                                                <?= strtoupper(substr($participant['firstname'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div class="participant-info">
                                                <div class="participant-name">
                                                    <?= htmlspecialchars(($participant['firstname'] ?? '') . ' ' . ($participant['lastname'] ?? '')) ?>
                                                </div>
                                                <div class="participant-email">
                                                    <?= htmlspecialchars($participant['email'] ?? $participant['username'] ?? 'N/A') ?>
                                                </div>
                                            </div>
                                            <div class="participant-date">
                                                <small>
                                                    User ID: <?= (int)$participant['user_id'] ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin.php';
?>
