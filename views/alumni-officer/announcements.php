<!-- Stats & Actions Bar -->
<div class="search-filter-bar mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-auto">
            <div class="stat-pill">
                <div class="icon" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
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
                <div class="icon" style="background: linear-gradient(135deg, #059669, #047857);">
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
            <a href="/scratch/announcements/officer-new.php" class="btn-add-modern">
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
</style>

<!-- Announcements List -->
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
    border-left: 5px solid #dc2626;
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
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), transparent);
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
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
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
    color: #dc2626;
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
                            <h5 class="mb-2 fw-bold" style="color: #111827;">
                                <?= htmlspecialchars($announcement['title'] ?? 'Untitled') ?>
                            </h5>
                            <p class="text-muted mb-0" style="line-height: 1.6;">
                                <?= nl2br(htmlspecialchars(strip_tags($announcement['content'] ?? ''))) ?>
                            </p>
                        </div>
                    </div>
                    <div class="event-actions">
                        <a href="/scratch/announcements/officer-edit.php?id=<?= $announcement['id'] ?>" 
                           class="btn-action btn-primary" 
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" 
                                class="btn-action btn-danger" 
                                title="Delete"
                                onclick="deleteAnnouncement(<?= $announcement['id'] ?>, '<?= htmlspecialchars($announcement['title'] ?? 'Untitled', ENT_QUOTES) ?>')">
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
        <a href="/scratch/announcements/officer-new.php" class="btn btn-modern btn-modern-primary mt-3" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; padding: 12px 28px; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
            <i class="fas fa-plus me-2"></i>Create First Announcement
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-trash-alt" style="font-size: 24px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Delete Announcement</h5>
                        <p class="mb-0" style="font-size: 14px; opacity: 0.9;">Permanently remove this announcement</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="background: rgba(255,255,255,0.2); border-radius: 8px; padding: 8px;"></button>
            </div>
            <form method="POST" action="/scratch/alumni-officer.php?page=announcements&action=delete" id="deleteForm">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_announcement_id">
                    
                    <div class="text-center">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #dc2626;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #111827; margin-bottom: 10px;">Are you sure you want to delete?</h5>
                        <p style="color: #6b7280; margin-bottom: 20px; font-weight: 600;" id="delete_announcement_title"></p>
                        
                        <div style="background: linear-gradient(135deg, #fee2e2, #fecaca); border-left: 4px solid #dc2626; padding: 16px; border-radius: 12px; text-align: left;">
                            <div style="display: flex; align-items-start; gap: 12px;">
                                <i class="fas fa-exclamation-circle" style="color: #dc2626; font-size: 20px; margin-top: 2px;"></i>
                                <div style="flex: 1;">
                                    <h6 style="color: #991b1b; font-weight: 600; margin-bottom: 8px;">Warning: This action cannot be undone!</h6>
                                    <ul style="color: #b91c1c; margin: 0; padding-left: 20px; font-size: 14px;">
                                        <li>Announcement will be permanently deleted</li>
                                        <li>Alumni will no longer see this announcement</li>
                                        <li>This action is immediate and irreversible</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #dc2626, #b91c1c); border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);">
                        <i class="fas fa-trash me-2"></i>Yes, Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteAnnouncement(id, title) {
    document.getElementById('delete_announcement_id').value = id;
    document.getElementById('delete_announcement_title').textContent = title;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

