<!-- Create New Newsletter Button -->
<div class="mb-4">
    <button type="button" class="btn btn-lg" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border: none; border-radius: 12px; padding: 14px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);" data-bs-toggle="modal" data-bs-target="#createNewsletterModal">
        <i class="fas fa-newspaper me-2"></i> Create Newsletter
    </button>
</div>

<!-- Newsletters List -->
<div class="stat-card">
    <h5 class="mb-4" style="color: #1e3a8a; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-newspaper" style="color: #8b5cf6;"></i>
        All Newsletters (<?= count($newsletters) ?>)
    </h5>
    
    <?php if (empty($newsletters)): ?>
        <div class="text-center py-5">
            <i class="fas fa-newspaper" style="font-size: 48px; color: #9ca3af; margin-bottom: 15px;"></i>
            <p class="text-muted mb-0">No newsletters created yet</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($newsletters as $newsletter): ?>
                <div class="col-md-4">
                    <div class="card h-100" style="border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s ease; overflow: hidden;">
                        <?php if (!empty($newsletter['image_path'])): ?>
                            <img src="/scratch/<?= htmlspecialchars($newsletter['image_path']) ?>" 
                                class="card-img-top" alt="Newsletter" 
                                style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div style="height: 200px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-newspaper" style="font-size: 48px; color: white;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body p-4">
                            <p class="card-text text-muted" style="font-size: 14px; line-height: 1.6;">
                                <?= nl2br(htmlspecialchars(substr($newsletter['about'], 0, 120))) ?>
                                <?= strlen($newsletter['about']) > 120 ? '...' : '' ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid #e5e7eb;">
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> <?= date('M d, Y', strtotime($newsletter['created_at'])) ?>
                                </small>
                                <form method="post" style="display: inline-block;" onsubmit="return confirm('Delete this newsletter?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $newsletter['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;">
                                        <i class="fas fa-trash"></i>
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

<!-- Create Newsletter Modal -->
<div class="modal fade" id="createNewsletterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 8px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title"><i class="fas fa-newspaper me-2"></i>Create Newsletter</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #1e3a8a;">Newsletter Image</label>
                        <input type="file" class="form-control form-control-lg" name="image" accept="image/*" 
                            style="border-radius: 10px; border: 2px solid #e5e7eb;">
                        <small class="text-muted">Optional: Upload an image for the newsletter</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600; color: #1e3a8a;">Description</label>
                        <textarea class="form-control" name="about" rows="6" required 
                            placeholder="Enter newsletter description..." 
                            style="border-radius: 10px; border: 2px solid #e5e7eb;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #e5e7eb;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border: none; border-radius: 10px;">
                        <i class="fas fa-plus me-2"></i>Create Newsletter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
</style>

