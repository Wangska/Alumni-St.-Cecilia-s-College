<?php
$currentPage = 'forum';
$pageTitle = 'Forum Topics';
$title = 'Forum Topics Management';

ob_start();
?>

<style>
.forum-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid #6f42c1;
}

.forum-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.forum-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 12px;
}

.forum-title {
    font-size: 20px;
    font-weight: 700;
    color: #2d3142;
    margin-bottom: 8px;
}

.forum-description {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 16px;
}

.forum-meta {
    display: flex;
    gap: 24px;
    align-items: center;
    padding-top: 16px;
    border-top: 2px solid #f1f3f5;
}

.forum-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    font-size: 13px;
}

.forum-meta-item i {
    color: #6f42c1;
}

.filter-bar {
    background: white;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #6f42c1, #5a32a3);
    color: white;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
}

/* Comments List */
.comments-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-top: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.comment-row {
    display: grid;
    grid-template-columns: 1fr 1.4fr 0.8fr 0.6fr;
    gap: 12px;
    align-items: start;
    padding: 12px 0;
    border-bottom: 1px solid #f1f3f5;
}
.comment-row.header { font-weight: 600; color: #2d3142; }
.comment-row:last-child { border-bottom: none; }
.comment-text-cell { color: #6c757d; font-size: 14px; line-height: 1.5; }
.comment-author { font-weight: 600; color: #2d3142; }
.comment-topic { color: #4a4f63; }
.comment-date { color: #6c757d; font-size: 13px; white-space: nowrap; }

@media (max-width: 992px) {
    .comment-row { grid-template-columns: 1fr; }
    .comment-date { white-space: normal; }
}
</style>

<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="d-flex align-items-center gap-3">
        <div class="stat-pill">
            <i class="fas fa-comments"></i>
            <span>Total Topics: <?= count($forumTopics ?? []) ?></span>
        </div>
    </div>
    <button class="btn" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);" data-bs-toggle="modal" data-bs-target="#addTopicModal" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(111, 66, 193, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(111, 66, 193, 0.3)';">
        <i class="fas fa-plus me-2"></i>Add New Topic
    </button>
</div>

<!-- Forum Topics List -->
<?php if (!empty($forumTopics)): ?>
    <?php foreach ($forumTopics as $topic): ?>
    <div class="forum-card">
        <div class="forum-header">
            <div class="flex-grow-1">
                <h5 class="forum-title"><?= htmlspecialchars($topic['title']) ?></h5>
                <p class="forum-description"><?= nl2br(htmlspecialchars($topic['description'])) ?></p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-sm" href="/scratch/admin.php?page=forum-view&id=<?= (int)$topic['id'] ?>" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white; border: none; border-radius: 10px; padding: 8px 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);" title="View Topic" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(13,110,253,0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(13,110,253,0.3)';">
                    <i class="fas fa-eye"></i>
                </a>
                <button class="btn btn-sm" style="background: linear-gradient(135deg, #ffc107, #ffb300); color: white; border: none; border-radius: 10px; padding: 8px 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);" data-bs-toggle="modal" data-bs-target="#editTopicModal<?= $topic['id'] ?>" title="Edit" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(255, 193, 7, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(255, 193, 7, 0.3)';">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 10px; padding: 8px 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);" onclick="deleteTopic(<?= $topic['id'] ?>, '<?= htmlspecialchars($topic['title']) ?>')" title="Delete" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(220, 53, 69, 0.3)';">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        
        <div class="forum-meta">
            <div class="forum-meta-item">
                <i class="fas fa-user"></i>
                <span>By: <strong><?= htmlspecialchars($topic['author_name'] ?? 'Admin') ?></strong></span>
            </div>
            <div class="forum-meta-item">
                <i class="fas fa-comments"></i>
                <span><strong><?= $topic['comment_count'] ?? 0 ?></strong> Comments</span>
            </div>
            <div class="forum-meta-item">
                <i class="fas fa-calendar"></i>
                <span><?= date('M d, Y - g:i A', strtotime($topic['date_created'])) ?></span>
            </div>
        </div>
    </div>
    
    <!-- Edit Topic Modal -->
    <div class="modal fade" id="editTopicModal<?= $topic['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #ffc107, #ffb300); color: white; padding: 24px 30px; border: none;">
                    <div class="d-flex align-items-center">
                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-edit" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Edit Forum Topic</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/scratch/admin.php?page=forum&action=edit">
                    <div class="modal-body" style="padding: 30px;">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="id" value="<?= $topic['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #2d3142;">Topic Title</label>
                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($topic['title']) ?>" required style="border-radius: 12px; border: 2px solid #e9ecef; padding: 12px 16px;">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #2d3142;">Description</label>
                            <textarea class="form-control" name="description" rows="5" required style="border-radius: 12px; border: 2px solid #e9ecef; padding: 12px 16px;"><?= htmlspecialchars($topic['description']) ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #ffc107, #ffb300); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600;">
                            <i class="fas fa-save me-2"></i>Update Topic
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center py-5" style="background: white; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
        <p class="text-muted">No forum topics yet. Create your first topic!</p>
        <button class="btn mt-3" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 600;" data-bs-toggle="modal" data-bs-target="#addTopicModal">
            <i class="fas fa-plus me-2"></i>Add First Topic
        </button>
    </div>
<?php endif; ?>

<!-- Recent Comments -->
<div class="comments-card">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0" style="font-weight:700; color:#2d3142;">Recent Comments</h5>
        <span class="badge bg-secondary">Last 100</span>
    </div>
    <?php if (!empty($recentComments ?? [])): ?>
        <div class="comment-row header" style="border-bottom: 2px solid #eef1f5;">
            <div>Comment</div>
            <div>Topic</div>
            <div>Author</div>
            <div>Date</div>
        </div>
        <?php foreach ($recentComments as $comment): ?>
            <div class="comment-row">
                <div class="comment-text-cell"><?= nl2br(htmlspecialchars($comment['comment'] ?? '')) ?></div>
                <div class="comment-topic"><?= htmlspecialchars($comment['topic_title'] ?? 'Untitled Topic') ?></div>
                <div class="comment-author"><?= htmlspecialchars($comment['author_name'] ?? 'Anonymous') ?></div>
                <div class="comment-date"><?= date('M d, Y - g:i A', strtotime($comment['date_created'] ?? 'now')) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-4 text-muted">No comments yet.</div>
    <?php endif; ?>
    <div class="mt-2 small text-muted">Tip: Use your browser search (Ctrl/Cmd+F) to quickly find comments.</div>
    <div class="mt-2 small text-muted">Tables are scrollable on mobile.</div>
    
</div>

<!-- Add Topic Modal -->
<div class="modal fade" id="addTopicModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-comments" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Create New Forum Topic</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/admin.php?page=forum&action=add">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #2d3142;">Topic Title</label>
                        <input type="text" class="form-control" name="title" required style="border-radius: 12px; border: 2px solid #e9ecef; padding: 12px 16px;" placeholder="Enter topic title...">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #2d3142;">Description</label>
                        <textarea class="form-control" name="description" rows="5" required style="border-radius: 12px; border: 2px solid #e9ecef; padding: 12px 16px;" placeholder="Describe the topic..."></textarea>
                        <small class="text-muted">Provide a clear description of what this forum topic is about.</small>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600;">Cancel</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #6f42c1, #5a32a3); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600;">
                        <i class="fas fa-plus me-2"></i>Create Topic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
            <form method="POST" action="/scratch/admin.php?page=forum&action=delete">
                <div class="modal-body" style="padding: 40px 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="id" id="delete_topic_id">
                    
                    <div class="text-center mb-4">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05)); border: 3px solid rgba(220, 53, 69, 0.3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                            <i class="fas fa-comments" style="font-size: 40px; color: #dc3545;"></i>
                        </div>
                        <h5 style="font-weight: 700; color: #2d3142; margin-bottom: 12px;">Delete Forum Topic?</h5>
                        <p style="color: #6c757d; margin-bottom: 8px;">Are you sure you want to delete this forum topic?</p>
                        <p style="font-weight: 600; color: #2d3142; font-size: 16px;" id="delete_topic_name"></p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #fff5f5, #ffe5e5); border-left: 4px solid #dc3545; padding: 16px; border-radius: 12px;">
                        <p class="mb-0" style="color: #721c24; font-size: 14px;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Warning:</strong> This will also delete all comments associated with this topic. This action cannot be undone.
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 12px 28px; font-weight: 600; border: 2px solid #6c757d;">Cancel</button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 12px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
                        <i class="fas fa-trash me-2"></i>Yes, Delete It
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteTopic(id, title) {
    document.getElementById('delete_topic_id').value = id;
    document.getElementById('delete_topic_name').textContent = title;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>

