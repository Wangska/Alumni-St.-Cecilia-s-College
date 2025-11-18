<!-- Stats & Actions Bar -->
<div class="search-filter-bar mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <div class="number"><?= count($events ?? []) ?></div>
                    <div class="label">Total Events</div>
                </div>
            </div>
        </div>
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #059669, #047857);">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <?php
                    $upcoming = array_filter($events ?? [], fn($e) => strtotime($e['schedule'] ?? 'now') >= time());
                    ?>
                    <div class="number"><?= count($upcoming) ?></div>
                    <div class="label">Upcoming</div>
                </div>
            </div>
        </div>
        <div class="col-md"></div>
        <div class="col-md-auto">
            <a href="/scratch/events/officer-new.php" class="btn-add-modern">
                <i class="fas fa-plus me-2"></i>Create Event
            </a>
        </div>
    </div>
</div>

<style>
.search-filter-bar {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.stat-pill {
    background: white;
    padding: 16px 24px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-pill .icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-pill .number {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
}

.stat-pill .label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
}

.btn-add-modern {
    padding: 12px 24px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
    background: linear-gradient(135deg, #b91c1c, #991b1b);
    color: white;
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.event-card-modern {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.05);
}

.event-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.15);
}

.event-banner {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
}

.event-content {
    padding: 24px;
}

.event-date-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}

.event-date-badge.past {
    background: linear-gradient(135deg, #6b7280, #4b5563);
}

.event-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 12px;
    line-height: 1.3;
}

.event-description {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.event-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 2px solid #f1f3f5;
}

.event-actions {
    display: flex;
    gap: 8px;
}

.btn-action {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    font-size: 14px;
    position: relative;
    overflow: hidden;
}

.btn-action::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-action:hover::before {
    opacity: 1;
}

.btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

.btn-action.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    color: white;
}

.btn-action.btn-danger {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}
</style>

<!-- Events List -->
<?php if (!empty($events)): ?>
    <div class="events-grid">
        <?php foreach ($events as $event): ?>
            <?php $isUpcoming = strtotime($event['schedule']) >= time(); ?>
            <div class="event-card-modern">
                <?php if (!empty($event['banner'])): ?>
                    <img src="/scratch/uploads/<?= htmlspecialchars($event['banner']) ?>" alt="Event Banner" class="event-banner">
                <?php else: ?>
                    <div class="event-banner"></div>
                <?php endif; ?>
                
                <div class="event-content">
                    <div class="event-date-badge <?= $isUpcoming ? '' : 'past' ?>">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?= date('M d, Y @ g:i A', strtotime($event['schedule'])) ?></span>
                    </div>
                    
                    <h5 class="event-title"><?= htmlspecialchars($event['title']) ?></h5>
                    
                    <p class="event-description">
                        <?= nl2br(htmlspecialchars($event['content'])) ?>
                    </p>
                    
                    <!-- Participant Information -->
                    <div class="mb-3">
                        <div class="text-muted small">
                            <i class="fas fa-users me-1" style="color: #dc2626;"></i>
                            <?php if (isset($event['participant_limit']) && $event['participant_limit']): ?>
                                <strong><?= $event['participant_count'] ?? 0 ?> / <?= $event['participant_limit'] ?></strong> participants
                            <?php else: ?>
                                <strong><?= $event['participant_count'] ?? 0 ?></strong> participants (unlimited)
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="event-footer">
                        <div>
                            <?php if ($isUpcoming): ?>
                                <span class="badge" style="background: linear-gradient(135deg, #059669, #047857); padding: 6px 12px; border-radius: 20px;">
                                    <i class="fas fa-circle" style="font-size: 8px;"></i> Upcoming
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary" style="padding: 6px 12px; border-radius: 20px;">
                                    <i class="fas fa-circle" style="font-size: 8px;"></i> Past
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="event-actions">
                            <a href="/scratch/alumni-officer.php?page=event-participants&event_id=<?= $event['id'] ?>" 
                               class="btn-action btn-primary" 
                               title="View Participants">
                                <i class="fas fa-users"></i>
                            </a>
                            <a href="/scratch/events/officer-edit.php?id=<?= $event['id'] ?>" 
                               class="btn-action btn-primary" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="post" style="display: inline-block;" onsubmit="return confirm('Delete this event?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                                <button type="submit" class="btn-action btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No events scheduled yet</h5>
        <p class="text-muted">Create your first event to get started.</p>
        <a href="/scratch/events/officer-new.php" class="btn btn-modern btn-modern-primary mt-3" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; padding: 12px 28px; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
            <i class="fas fa-plus me-2"></i>Create First Event
        </a>
    </div>
<?php endif; ?>

<style>
.content-card {
    background: white;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.5) !important;
}
</style>

