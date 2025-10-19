<?php
$currentPage = 'announcements';
$pageTitle = 'Announcements';
$title = 'Announcements Management';

ob_start();
?>

<style>
.announcement-list-modern {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.announcement-card-modern {
    background: white;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 5px solid #dc3545;
    position: relative;
    overflow: hidden;
}

.announcement-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), transparent);
    border-radius: 0 0 0 100%;
}

.announcement-card-modern:hover {
    transform: translateX(8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

.announcement-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 16px;
}

.announcement-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.announcement-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 2px solid #f1f3f5;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 13px;
}

.meta-item i {
    color: #dc3545;
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
                <div class="icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div>
                    <div class="number"><?= count($announcements ?? []) ?></div>
                    <div class="label">Total Announcements</div>
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
                    $recent = array_filter($announcements ?? [], fn($a) => strtotime($a['date_created'] ?? 'now') >= strtotime('-7 days'));
                    ?>
                    <div class="number"><?= count($recent) ?></div>
                    <div class="label">This Week</div>
                </div>
            </div>
        </div>
        <div class="col-md"></div>
        <div class="col-md-auto">
            <a href="/scratch/announcements/new.php" class="btn-add-modern">
                <i class="fas fa-plus me-2"></i>Create Announcement
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

<!-- Announcements List -->
<?php if (!empty($announcements)): ?>
    <div class="announcement-list-modern">
        <?php foreach ($announcements as $announcement): ?>
            <div class="announcement-card-modern">
                <div class="announcement-header">
                    <div class="d-flex gap-3 flex-grow-1">
                        <div class="announcement-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-2 fw-bold text-dark">
                                <?= htmlspecialchars($announcement['title'] ?? 'Untitled') ?>
                            </h5>
                            <p class="text-muted mb-0" style="line-height: 1.6;">
                                <?= nl2br(htmlspecialchars(strip_tags($announcement['content'] ?? ''))) ?>
                            </p>
                        </div>
                    </div>
                    <div class="event-actions">
                        <a href="/scratch/announcements/edit.php?id=<?= $announcement['id'] ?>" 
                           class="btn-action btn-primary" 
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteAnnouncement(<?= $announcement['id'] ?>, '<?= htmlspecialchars($announcement['title'] ?? 'Untitled') ?>')" 
                                class="btn-action btn-danger" 
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="announcement-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>Created: <?= date('M d, Y', strtotime($announcement['date_created'] ?? 'now')) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span><?= date('g:i A', strtotime($announcement['date_created'] ?? 'now')) ?></span>
                    </div>
                    <?php
                    $daysSince = floor((time() - strtotime($announcement['date_created'] ?? 'now')) / 86400);
                    ?>
                    <div class="meta-item">
                        <i class="fas fa-info-circle"></i>
                        <span>
                            <?php if ($daysSince == 0): ?>
                                Posted today
                            <?php elseif ($daysSince == 1): ?>
                                Posted yesterday
                            <?php else: ?>
                                Posted <?= $daysSince ?> days ago
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="content-card text-center py-5">
        <i class="fas fa-bullhorn fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">No announcements yet</h5>
        <p class="text-muted">Create your first announcement to get started.</p>
        <a href="/scratch/announcements/new.php" class="btn btn-modern btn-modern-primary mt-3">
            <i class="fas fa-plus me-2"></i>Create First Announcement
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
            <form method="POST" action="/scratch/announcements/delete.php">
                <div class="modal-body" style="padding: 40px 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_announcement_id">
                    
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="fas fa-bullhorn" style="font-size: 40px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 12px;">Delete Announcement?</h5>
                        <p style="color: #6c757d; margin-bottom: 8px;">Are you sure you want to delete this announcement?</p>
                        <p style="font-weight: 600; color: #dc3545; font-size: 16px;" id="delete_announcement_name"></p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fff3cd, #fff8e1); border-left: 4px solid #ffc107; border-radius: 12px; padding: 16px; margin-top: 20px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle" style="color: #ff9800; font-size: 20px; margin-right: 12px; margin-top: 2px;"></i>
                            <div>
                                <strong style="color: #856404; display: block; margin-bottom: 4px;">Warning</strong>
                                <span style="color: #856404; font-size: 14px;">This action cannot be undone. The announcement will be permanently removed.</span>
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
function deleteAnnouncement(id, title) {
    document.getElementById('delete_announcement_id').value = id;
    document.getElementById('delete_announcement_name').textContent = title;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>
