<?php
$currentPage = 'success-stories';
$pageTitle = 'Success Stories Management';
$title = 'Success Stories Management';

ob_start();
?>

<style>
/* Success Stories Management Styles */
.success-stories-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    overflow: hidden;
}

.story-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.story-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.story-card.pending {
    border-left: 5px solid #f59e0b;
    background: linear-gradient(135deg, #fefbf3, #ffffff);
}

.story-card.approved {
    border-left: 5px solid #10b981;
    background: linear-gradient(135deg, #f0fdf4, #ffffff);
}

.story-image {
    height: 400px;
    width: 100%;
    object-fit: cover;
    object-position: top center;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stats-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #1f2937, #374151);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stats-label {
    color: #6b7280;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-story-action {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-story-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.btn-approve {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-approve:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}

.btn-unapprove {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.btn-unapprove:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    color: white;
}

/* Modal Styles */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #1f2937, #374151);
    color: white;
    border-bottom: none;
    padding: 1.5rem 2rem;
}

.modal-header .btn-close {
    filter: invert(1);
    opacity: 0.8;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-body {
    padding: 2rem;
    background: #f8fafc;
}

.modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem 2rem;
}

.confirmation-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
}

.confirmation-icon.approve {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #059669;
}

.confirmation-icon.reject {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
}

.confirmation-icon.unapprove {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #d97706;
}

.confirmation-icon.delete {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    color: #6b7280;
}

.confirmation-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    text-align: center;
}

.confirmation-message {
    color: #6b7280;
    font-size: 1rem;
    line-height: 1.6;
    text-align: center;
    margin-bottom: 0;
}

.btn-modal {
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 120px;
}

.btn-modal:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.btn-modal-confirm {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-modal-confirm:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
}

.btn-modal-cancel {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.btn-modal-cancel:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    color: white;
}

.btn-modal-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-modal-danger:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
}

/* Enhanced Card Styles */
.story-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
}

/* Keep image rows aligned even when titles/meta vary */
.story-title {
    color: #2d3142;
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: .25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* show up to 2 lines */
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.8rem; /* reserve space for two lines */
}

.story-meta {
    font-size: 0.9rem;
    min-height: 1.2rem; /* reserve consistent meta height */
    display: block;
}

.story-body {
    padding: 1.5rem;
}

.story-footer {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-approved {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

/* Removed modal sliding animations to prevent page jumping */

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1" style="color: #2d3142; font-weight: 700;">Success Stories Management</h2>
                    <p class="text-muted mb-0">Review and manage alumni success stories</p>
                </div>
            </div>
            
            
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #fef3c7, #fde68a); border-left: 4px solid #f59e0b;">
                        <div class="stats-number" style="color: #92400e;"><?= count(array_filter($stories ?? [], function($story) { return !$story['status']; })) ?></div>
                        <div class="stats-label" style="color: #92400e;">Pending Stories</div>
                        <i class="fas fa-clock mt-2" style="color: #f59e0b; font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-left: 4px solid #10b981;">
                        <div class="stats-number" style="color: #065f46;"><?= count(array_filter($stories ?? [], function($story) { return $story['status']; })) ?></div>
                        <div class="stats-label" style="color: #065f46;">Approved Stories</div>
                        <i class="fas fa-check-circle mt-2" style="color: #10b981; font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); border-left: 4px solid #3b82f6;">
                        <div class="stats-number" style="color: #1e40af;"><?= count($stories ?? []) ?></div>
                        <div class="stats-label" style="color: #1e40af;">Total Stories</div>
                        <i class="fas fa-star mt-2" style="color: #3b82f6; font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="background: linear-gradient(135deg, #fed7aa, #fdba74); border-left: 4px solid #f97316;">
                        <div class="stats-number" style="color: #c2410c;"><?= count(array_filter($stories ?? [], function($story) { return !empty($story['image']); })) ?></div>
                        <div class="stats-label" style="color: #c2410c;">With Images</div>
                        <i class="fas fa-image mt-2" style="color: #f97316; font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
            
            <?php if (empty($stories ?? [])): ?>
                <div class="text-center py-5" style="background: white; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
                    <i class="fas fa-star text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3" style="color: #6b7280;">No Success Stories</h4>
                    <p class="text-muted">No success stories have been submitted yet.</p>
                </div>
            <?php else: ?>
                <!-- Stories List -->
                <div class="row">
                    <?php foreach ($stories ?? [] as $story): ?>
                        <div class="col-lg-3 col-md-4 mb-4">
                            <div class="story-card <?= $story['status'] ? 'approved' : 'pending' ?>">
                                <div class="story-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="story-title mb-1"><?= htmlspecialchars($story['title']) ?></h5>
                                            <small class="text-muted story-meta">
                                                <i class="fas fa-user me-1"></i>
                                                <?= htmlspecialchars($story['firstname'] . ' ' . $story['lastname']) ?>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('M d, Y', strtotime($story['created'])) ?>
                                            </small>
                                        </div>
                                        <div>
                                            <span class="status-badge <?= $story['status'] ? 'status-approved' : 'status-pending' ?>">
                                                <?= $story['status'] ? 'Approved' : 'Pending' ?>
                                            </span>
                                            <?php if (!empty($story['image'])): ?>
                                                <i class="fas fa-image text-info ms-2" title="Has Image" style="font-size: 1.2rem;"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="story-body">
                                    <?php if (!empty($story['image'])): ?>
                                        <div class="mb-3">
                                            <img src="/scratch/<?= htmlspecialchars($story['image']) ?>" 
                                                 alt="<?= htmlspecialchars($story['title']) ?>" 
                                                 class="story-image" 
                                                 style="width: 100%;">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <p class="card-text" style="color: #4b5563; line-height: 1.6; font-size: 0.95rem;">
                                        <?= htmlspecialchars(substr($story['content'], 0, 200)) ?><?= strlen($story['content']) > 200 ? '...' : '' ?>
                                    </p>
                                </div>
                                
                                <div class="story-footer">
                                    <div class="d-flex gap-2">
                                        <?php if (!$story['status']): ?>
                                            <button type="button" class="btn-story-action btn-approve" 
                                                    data-bs-toggle="modal" data-bs-target="#approveModal" 
                                                    data-story-id="<?= $story['id'] ?>" 
                                                    data-story-title="<?= htmlspecialchars($story['title']) ?>">
                                                <i class="fas fa-check me-1"></i>Approve
                                            </button>
                                            
                                            <button type="button" class="btn-story-action btn-reject" 
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal" 
                                                    data-story-id="<?= $story['id'] ?>" 
                                                    data-story-title="<?= htmlspecialchars($story['title']) ?>">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn-story-action btn-unapprove" 
                                                    data-bs-toggle="modal" data-bs-target="#unapproveModal" 
                                                    data-story-id="<?= $story['id'] ?>" 
                                                    data-story-title="<?= htmlspecialchars($story['title']) ?>">
                                                <i class="fas fa-eye-slash me-1"></i>Unapprove
                                            </button>
                                            
                                            <button type="button" class="btn-story-action btn-delete" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                    data-story-id="<?= $story['id'] ?>" 
                                                    data-story-title="<?= htmlspecialchars($story['title']) ?>">
                                                <i class="fas fa-trash me-1"></i>Delete
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Approve Success Story
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-icon approve">
                    <i class="fas fa-check"></i>
                </div>
                <h4 class="confirmation-title">Approve This Story?</h4>
                <p class="confirmation-message">
                    This success story will be approved and become visible on the alumni dashboard. 
                    The story will inspire other alumni and showcase their achievements.
                </p>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Story:</strong> <span id="approveStoryTitle"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form method="POST" class="d-inline" id="approveForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="story_id" id="approveStoryId" value="">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-modal btn-modal-confirm">
                        <i class="fas fa-check me-1"></i>Approve Story
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-times-circle me-2"></i>Reject Success Story
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-icon reject">
                    <i class="fas fa-times"></i>
                </div>
                <h4 class="confirmation-title">Reject This Story?</h4>
                <p class="confirmation-message">
                    This success story will be rejected and permanently removed from the system. 
                    This action cannot be undone.
                </p>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Story:</strong> <span id="rejectStoryTitle"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form method="POST" class="d-inline" id="rejectForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="story_id" id="rejectStoryId" value="">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-modal btn-modal-danger">
                        <i class="fas fa-times me-1"></i>Reject Story
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Unapprove Modal -->
<div class="modal fade" id="unapproveModal" tabindex="-1" aria-labelledby="unapproveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unapproveModalLabel">
                    <i class="fas fa-eye-slash me-2"></i>Unapprove Success Story
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-icon unapprove">
                    <i class="fas fa-eye-slash"></i>
                </div>
                <h4 class="confirmation-title">Unapprove This Story?</h4>
                <p class="confirmation-message">
                    This success story will be unapproved and hidden from the alumni dashboard. 
                    It can be approved again later if needed.
                </p>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Story:</strong> <span id="unapproveStoryTitle"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form method="POST" class="d-inline" id="unapproveForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="story_id" id="unapproveStoryId" value="">
                    <input type="hidden" name="action" value="unapprove">
                    <button type="submit" class="btn btn-modal btn-modal-confirm">
                        <i class="fas fa-eye-slash me-1"></i>Unapprove Story
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash me-2"></i>Delete Success Story
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="confirmation-icon delete">
                    <i class="fas fa-trash"></i>
                </div>
                <h4 class="confirmation-title">Delete This Story?</h4>
                <p class="confirmation-message">
                    This success story will be permanently deleted from the system. 
                    This action cannot be undone and the story will be lost forever.
                </p>
                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Story:</strong> <span id="deleteStoryTitle"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form method="POST" class="d-inline" id="deleteForm">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="story_id" id="deleteStoryId" value="">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-modal btn-modal-danger">
                        <i class="fas fa-trash me-1"></i>Delete Story
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Modal event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Prevent scroll jumping when submitting forms inside modals by using AJAX
    function ajaxifyForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(form);
            fd.append('ajax', '1');
            fetch(window.location.href, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(res => res.json()).then(() => { window.location.reload(); })
                .catch(() => { window.location.reload(); });
        });
    }
    ajaxifyForm('approveForm');
    ajaxifyForm('rejectForm');
    ajaxifyForm('unapproveForm');
    ajaxifyForm('deleteForm');
    // Approve Modal
    const approveModal = document.getElementById('approveModal');
    if (approveModal) {
        approveModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const storyId = button.getAttribute('data-story-id');
            const storyTitle = button.getAttribute('data-story-title');
            
            document.getElementById('approveStoryId').value = storyId;
            document.getElementById('approveStoryTitle').textContent = storyTitle;
        });
    }
    
    // Reject Modal
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const storyId = button.getAttribute('data-story-id');
            const storyTitle = button.getAttribute('data-story-title');
            
            document.getElementById('rejectStoryId').value = storyId;
            document.getElementById('rejectStoryTitle').textContent = storyTitle;
        });
    }
    
    // Unapprove Modal
    const unapproveModal = document.getElementById('unapproveModal');
    if (unapproveModal) {
        unapproveModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const storyId = button.getAttribute('data-story-id');
            const storyTitle = button.getAttribute('data-story-title');
            
            document.getElementById('unapproveStoryId').value = storyId;
            document.getElementById('unapproveStoryTitle').textContent = storyTitle;
        });
    }
    
    // Delete Modal
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const storyId = button.getAttribute('data-story-id');
            const storyTitle = button.getAttribute('data-story-title');
            
            document.getElementById('deleteStoryId').value = storyId;
            document.getElementById('deleteStoryTitle').textContent = storyTitle;
        });
    }
});

</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>
