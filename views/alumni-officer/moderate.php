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

<!-- Header with Create Button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="color: #111827; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-comments" style="color: #dc2626;"></i>
            Forum Management
        </h2>
        <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">Create, moderate, and manage forum topics and comments</p>
    </div>
    <button type="button" class="btn-add-modern" data-bs-toggle="modal" data-bs-target="#createTopicModal" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 12px 28px; border-radius: 12px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3); border: none; transition: all 0.3s ease;">
        <i class="fas fa-plus"></i>
        Create Forum Topic
    </button>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-pills mb-4" style="background: white; border-radius: 12px; padding: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#topics" style="border-radius: 8px; font-weight: 600;">
            <i class="fas fa-comments me-2"></i>Forum Topics
            <span class="badge bg-primary ms-2"><?= count($forumTopics) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#comments" style="border-radius: 8px; font-weight: 600;">
            <i class="fas fa-comment-dots me-2"></i>Recent Comments
            <span class="badge bg-info ms-2"><?= count($recentComments) ?></span>
        </a>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    <!-- Forum Topics Tab -->
    <div class="tab-pane fade show active" id="topics">
        <?php if (empty($forumTopics)): ?>
            <div class="stat-card">
                <div class="text-center py-5">
                    <i class="fas fa-comments" style="font-size: 64px; color: #d1d5db; margin-bottom: 20px;"></i>
                    <h5 style="color: #6b7280; font-weight: 600;">No forum topics yet</h5>
                    <p style="color: #9ca3af; margin-bottom: 20px;">Create your first forum topic to get discussions started</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTopicModal" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border: none; padding: 12px 30px; border-radius: 10px; font-weight: 600;">
                        <i class="fas fa-plus me-2"></i>Create Forum Topic
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($forumTopics as $topic): 
                    $currentUserId = $_SESSION['user']['id'] ?? 0;
                    $isOwnTopic = (int)($topic['user_id'] ?? 0) === (int)$currentUserId;
                ?>
                    <div class="col-12">
                        <div class="forum-topic-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; border: 1px solid #e5e7eb;">
                            <div class="forum-card-header" style="padding: 24px; border-bottom: 1px solid #e5e7eb;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 style="font-weight: 700; color: #111827; margin-bottom: 12px; font-size: 18px;">
                                            <?= htmlspecialchars($topic['title']) ?>
                                            <?php if ($isOwnTopic): ?>
                                                <span class="badge bg-success ms-2" style="font-size: 11px; padding: 4px 10px;">Your Topic</span>
                                            <?php endif; ?>
                                        </h5>
                                        <p style="color: #6b7280; margin: 0; line-height: 1.6; font-size: 14px;">
                                            <?= nl2br(htmlspecialchars($topic['description'])) ?>
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2 ms-3">
                                        <a href="/scratch/alumni-officer.php?page=forum-view&id=<?= (int)$topic['id'] ?>" 
                                           class="btn btn-sm btn-info text-white" 
                                           style="border-radius: 10px; padding: 8px 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);"
                                           title="View Topic">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($isOwnTopic): ?>
                                            <button class="btn btn-sm btn-warning text-white" 
                                                    style="border-radius: 10px; padding: 8px 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTopicModal<?= $topic['id'] ?>" 
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-danger" 
                                                style="border-radius: 10px; padding: 8px 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);"
                                                onclick="confirmDelete(<?= $topic['id'] ?>, '<?= htmlspecialchars($topic['title'], ENT_QUOTES) ?>')" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="forum-card-meta" style="padding: 16px 24px; background: #f9fafb; display: flex; gap: 24px; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 8px; color: #6b7280; font-size: 13px;">
                                    <i class="fas fa-user" style="color: #dc2626;"></i>
                                    <span><strong><?= htmlspecialchars($topic['author_name'] ?? 'Unknown') ?></strong></span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; color: #6b7280; font-size: 13px;">
                                    <i class="fas fa-comments" style="color: #dc2626;"></i>
                                    <span><strong><?= $topic['comment_count'] ?? 0 ?></strong> Comments</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; color: #6b7280; font-size: 13px;">
                                    <i class="fas fa-calendar" style="color: #dc2626;"></i>
                                    <span><?= isset($topic['date_created']) ? date('M d, Y - g:i A', strtotime($topic['date_created'])) : 'Recent' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($isOwnTopic): ?>
                    <!-- Edit Topic Modal for Own Topics -->
                    <div class="modal fade" id="editTopicModal<?= $topic['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
                                <div class="modal-header" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 24px 30px; border: none;">
                                    <div class="d-flex align-items-center">
                                        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                            <i class="fas fa-edit" style="font-size: 24px;"></i>
                                        </div>
                                        <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Edit Forum Topic</h5>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/scratch/alumni-officer.php?page=moderate&action=edit-topic&id=<?= $topic['id'] ?>">
                                    <div class="modal-body" style="padding: 30px;">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        
                                        <div class="mb-4">
                                            <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px;">Topic Title</label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($topic['title']) ?>" required style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 12px 16px;">
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px;">Description</label>
                                            <textarea class="form-control" name="description" rows="6" required style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 12px 16px; resize: vertical;"><?= htmlspecialchars($topic['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 12px 28px; font-weight: 600;">Cancel</button>
                                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; border: none; border-radius: 10px; padding: 12px 28px; font-weight: 600;">
                                            <i class="fas fa-save me-2"></i>Update Topic
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Comments Tab -->
    <div class="tab-pane fade" id="comments">
        <div class="stat-card">
            <h5 class="mb-4" style="color: #1e3a8a; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-comment-slash" style="color: #dc2626;"></i>
                Recent Comments
            </h5>
            
            <?php if (empty($recentComments)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-check-circle" style="font-size: 48px; color: #10b981; margin-bottom: 15px;"></i>
                    <p class="text-muted mb-0">No recent comments to moderate</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($recentComments as $comment): ?>
                        <div class="col-md-6">
                            <div class="card h-100" style="border-radius: 12px; border-left: 4px solid #dc2626; transition: all 0.3s ease;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1" style="color: #1e3a8a; font-weight: 700;">
                                                <?= htmlspecialchars($comment['author_name'] ?? 'Unknown') ?>
                                            </h6>
                                            <small class="text-muted">
                                                @<?= htmlspecialchars($comment['username'] ?? 'unknown') ?>
                                            </small>
                                        </div>
                                        <small class="text-muted">
                                            <?= isset($comment['created_at']) ? date('M d, Y - g:i A', strtotime($comment['created_at'])) : 'Recent' ?>
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3 p-3" style="background: #f8f9fa; border-radius: 8px;">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-comment-alt"></i> Comment ID: 
                                            <strong>#<?= htmlspecialchars($comment['id']) ?></strong>
                                        </small>
                                    </div>
                                    
                                    <p class="card-text" style="color: #374151; font-size: 14px; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                                    </p>
                                    
                                    <div class="d-flex justify-content-end gap-2 mt-3 pt-3" style="border-top: 1px solid #e5e7eb;">
                                        <a href="/scratch/forum/view.php?id=<?= $comment['topic_id'] ?>" 
                                            class="btn btn-sm btn-info text-white" 
                                            style="border-radius: 8px;"
                                            target="_blank">
                                            <i class="fas fa-eye me-1"></i>View Topic
                                        </a>
                                        <form method="post" style="display: inline-block;" onsubmit="return confirm('Delete this comment?');">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                            <input type="hidden" name="action" value="delete-comment">
                                            <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </button>
                                        </form>
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

<!-- Create Forum Topic Modal -->
<div class="modal fade" id="createTopicModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; padding: 24px 30px; border: none;">
                <div class="d-flex align-items-center">
                    <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                        <i class="fas fa-plus-circle" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="modal-title mb-0" style="font-weight: 700; font-size: 20px;">Create New Forum Topic</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/scratch/alumni-officer.php?page=moderate&action=create-topic">
                <div class="modal-body" style="padding: 30px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 10px;">
                            <i class="fas fa-heading me-2" style="color: #dc2626;"></i>
                            Topic Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="title" required 
                               style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 12px 16px; font-size: 14px; transition: all 0.3s ease;" 
                               placeholder="Enter a clear and descriptive title...">
                        <small class="text-muted">Make the title clear and engaging to encourage discussion</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 10px;">
                            <i class="fas fa-align-left me-2" style="color: #dc2626;"></i>
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" name="description" rows="6" required 
                                  style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 12px 16px; font-size: 14px; resize: vertical; transition: all 0.3s ease;" 
                                  placeholder="Provide details about this forum topic..."></textarea>
                        <small class="text-muted">Describe what this forum topic is about and what kind of discussions it should contain</small>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 30px; border-top: 1px solid #f1f3f5; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 12px 28px; font-weight: 600;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; border-radius: 10px; padding: 12px 28px; font-weight: 600; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);">
                        <i class="fas fa-check me-2"></i>Create Topic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.nav-pills .nav-link {
    color: #6b7280;
    transition: all 0.3s ease;
}
.nav-pills .nav-link:hover {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}
.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}
.table tbody tr {
    transition: all 0.3s ease;
}
.table tbody tr:hover {
    background-color: rgba(220, 38, 38, 0.05);
    transform: scale(1.005);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4) !important;
}
.form-control:focus {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}
.forum-topic-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(220, 38, 38, 0.15) !important;
    border-color: #dc2626 !important;
}
</style>

<script>
function confirmDelete(topicId, topicTitle) {
    if (confirm('Are you sure you want to delete this forum topic?\n\nTopic: ' + topicTitle + '\n\nThis will also delete all comments. This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/scratch/alumni-officer.php?page=moderate';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = 'csrf_token';
        csrfToken.value = '<?= csrf_token() ?>';
        form.appendChild(csrfToken);
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete-topic';
        form.appendChild(actionInput);
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = topicId;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

