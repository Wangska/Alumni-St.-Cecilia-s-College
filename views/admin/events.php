<?php
$currentPage = 'events';
$pageTitle = 'Events Management';
$title = 'Events Management';

ob_start();
?>

<style>
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
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.event-content {
    padding: 24px;
}

.event-date-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
}

.event-title {
    font-size: 20px;
    font-weight: 700;
    color: #2d3142;
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
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}
</style>

<!-- Stats & Actions Bar -->
<div class="search-filter-bar mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
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
                <div class="icon" style="background: linear-gradient(135deg, #198754, #146c43);">
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
            <a href="/scratch/events/new.php" class="btn-add-modern">
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
    color: #2d3142;
}

.stat-pill .label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
}

.btn-add-modern {
    padding: 12px 24px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
    background: linear-gradient(135deg, #b02a37, #991b1b);
    color: white;
}
</style>

<!-- Events Grid -->
<?php if (!empty($events)): ?>
    <div class="events-grid">
        <?php foreach ($events as $event): ?>
            <div class="event-card-modern">
                <?php if (!empty($event['banner'])): ?>
                    <img src="/scratch/uploads/<?= basename($event['banner']) ?>" alt="Event Banner" class="event-banner" onerror="this.style.background='linear-gradient(135deg, #dc3545, #c82333)'">
                <?php else: ?>
                    <div class="event-banner" style="display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-calendar-alt fa-4x"></i>
                    </div>
                <?php endif; ?>
                
                <div class="event-content">
                    <div class="event-date-badge">
                        <i class="far fa-calendar"></i>
                        <?= date('M d, Y - g:i A', strtotime($event['schedule'] ?? 'now')) ?>
                    </div>
                    
                    <h5 class="event-title"><?= htmlspecialchars($event['title'] ?? '') ?></h5>
                    
                    <p class="event-description">
                        <?= htmlspecialchars(strip_tags($event['content'] ?? '')) ?>
                    </p>
                    
                    <div class="event-footer">
                        <div class="text-muted small">
                            <i class="fas fa-user me-1"></i>
                            Created <?= date('M d, Y', strtotime($event['date_created'] ?? 'now')) ?>
                            <br>
                            <i class="fas fa-users me-1"></i>
                            <?php if (isset($event['participant_limit']) && $event['participant_limit']): ?>
                                <?= $event['participant_count'] ?? 0 ?> / <?= $event['participant_limit'] ?> participants
                            <?php else: ?>
                                <?= $event['participant_count'] ?? 0 ?> participants (unlimited)
                            <?php endif; ?>
                        </div>
                        <div class="event-actions">
                            <a href="/scratch/admin.php?page=event-participants&event_id=<?= $event['id'] ?>" 
                               class="btn-action btn-primary" 
                               title="View Participants">
                                <i class="fas fa-users"></i>
                            </a>
                            <a href="/scratch/events/edit.php?id=<?= $event['id'] ?>" 
                               class="btn-action btn-primary" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteEvent(<?= $event['id'] ?>, '<?= htmlspecialchars($event['title']) ?>')" 
                                    class="btn-action btn-danger" 
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No events yet</h5>
        <p class="text-muted">Create your first event to get started.</p>
        <a href="/scratch/events/new.php" class="btn btn-modern btn-modern-primary mt-3">
            <i class="fas fa-plus me-2"></i>Create First Event
        </a>
    </div>
<?php endif; ?>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Confirm Deletion</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/events/delete.php">
                <div class="modal-body" style="padding: 40px 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_event_id">
                    
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="fas fa-calendar-times" style="font-size: 40px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 12px;">Delete Event?</h5>
                        <p style="color: #6c757d; margin-bottom: 8px;">Are you sure you want to delete this event?</p>
                        <p style="font-weight: 600; color: #dc3545; font-size: 16px;" id="delete_event_name"></p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fff3cd, #fff8e1); border-left: 4px solid #ffc107; border-radius: 12px; padding: 16px; margin-top: 20px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle" style="color: #ff9800; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #856404; display: block; margin-bottom: 4px;">Warning</strong>
                                <span style="color: #856404; font-size: 14px;">This action cannot be undone. The event will be permanently removed.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
                        <i class="fas fa-trash-alt me-2"></i>Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#deleteModal .btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<script>
function deleteEvent(id, title) {
    document.getElementById('delete_event_id').value = id;
    document.getElementById('delete_event_name').textContent = title;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>
