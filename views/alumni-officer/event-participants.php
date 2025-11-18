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
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-bottom: 2px solid #fca5a5;
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
    background-color: #fef2f2;
}

.participant-item:last-child {
    border-bottom: none;
}

.participant-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
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
    color: #111827;
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
    color: #dc2626;
}

.stats-card {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
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

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    color: #dc2626;
    border: 2px solid #dc2626;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.back-button:hover {
    background: #dc2626;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="/scratch/alumni-officer.php?page=events" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Back to Events
        </a>
    </div>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="color: #111827; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-users me-2" style="color: #dc2626;"></i>
                Event Participants
            </h2>
            <p class="text-muted mb-0">View and manage event registrations</p>
        </div>
    </div>

    <!-- Statistics Card -->
    <?php if (!empty($events)): ?>
        <?php
        $totalParticipants = array_sum(array_column($events, 'participant_count'));
        $totalEvents = count($events);
        $avgParticipants = $totalEvents > 0 ? round($totalParticipants / $totalEvents, 1) : 0;
        ?>
        <div class="stats-card">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value"><?= $totalEvents ?></span>
                    <span class="stat-label">Total Events</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?= $totalParticipants ?></span>
                    <span class="stat-label">Total Participants</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?= $avgParticipants ?></span>
                    <span class="stat-label">Avg. per Event</span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Participants Container -->
    <div class="participants-container">
        <div class="p-4">
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
                                    <h5 class="mb-1" style="color: #111827; font-weight: 700;">
                                        <?= htmlspecialchars($event['title']) ?>
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-calendar me-1" style="color: #dc2626;"></i>
                                        <?= date('M d, Y \a\t g:i A', strtotime($event['schedule'])) ?>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <div class="badge" style="background: linear-gradient(135deg, #dc2626, #b91c1c); font-size: 0.9rem; padding: 8px 16px;">
                                        <i class="fas fa-users me-1"></i>
                                        <?= $event['participant_count'] ?> participants
                                    </div>
                                    <?php if (isset($event['participant_limit']) && $event['participant_limit']): ?>
                                        <div class="text-muted small mt-1">
                                            <i class="fas fa-user-check me-1"></i>
                                            Limit: <?= $event['participant_limit'] ?>
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
                                                    <i class="fas fa-envelope me-1" style="color: #dc2626;"></i>
                                                    <?= htmlspecialchars($participant['email'] ?? $participant['username'] ?? 'N/A') ?>
                                                </div>
                                            </div>
                                            <div class="participant-date">
                                                <small class="text-muted">
                                                    <i class="fas fa-id-badge me-1"></i>
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

