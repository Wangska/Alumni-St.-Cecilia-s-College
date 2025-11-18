<?php
$currentPage = 'moderate';
?>

<style>
.thread-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.thread-header {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    padding: 24px;
}

.thread-body {
    padding: 20px;
}

.meta {
    color: rgba(255,255,255,0.9);
    font-size: 14px;
}

.comments {
    background: #f8f9fa;
    border-top: 1px solid #eef1f5;
}

.comment-item {
    padding: 16px 20px;
    border-bottom: 1px solid #eef1f5;
    display: grid;
    grid-template-columns: 1fr 200px;
    gap: 16px;
}

.comment-item:last-child {
    border-bottom: none;
}

.comment-text {
    color: #374151;
    line-height: 1.6;
}

.comment-side {
    color: #6b7280;
    font-size: 13px;
}

@media (max-width: 768px) {
    .comment-item {
        grid-template-columns: 1fr;
    }
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 6px 18px rgba(220, 38, 38, 0.35);
    transition: transform .2s ease, box-shadow .2s ease;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(220, 38, 38, 0.45);
    color: #ffffff;
}

.back-btn:active {
    transform: translateY(0);
}

.page-offset {
    padding-top: 16px;
}

@media (max-width: 992px) {
    .page-offset {
        padding-top: 20px;
    }
}
</style>

<div class="page-offset">
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0" style="font-weight:700; color:#2d3142;">View Topic</h4>
    <a href="/scratch/alumni-officer.php?page=moderate" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Forum
    </a>
</div>

<div class="thread-card mb-4">
    <div class="thread-header">
        <h5 class="mb-1" style="font-weight:700;">
            <?= htmlspecialchars($topic['title']) ?>
        </h5>
        <div class="meta">
            <span><i class="fas fa-user me-1"></i><?= htmlspecialchars($topic['author_name'] ?? 'Alumni Officer') ?></span>
            <span class="ms-3"><i class="fas fa-calendar me-1"></i><?= date('M d, Y - g:i A', strtotime($topic['date_created'])) ?></span>
        </div>
    </div>
    <div class="thread-body">
        <div class="mb-0" style="white-space: pre-wrap;"><?= nl2br(htmlspecialchars($topic['description'] ?? '')) ?></div>
    </div>
    <div class="comments">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $c): ?>
                <div class="comment-item">
                    <div class="comment-text"><?= nl2br(htmlspecialchars($c['comment'] ?? '')) ?></div>
                    <div class="comment-side">
                        <div><i class="fas fa-user me-1"></i><?= htmlspecialchars($c['author_name'] ?? 'Anonymous') ?></div>
                        <div><i class="fas fa-clock me-1"></i><?= date('M d, Y - g:i A', strtotime($c['date_created'] ?? 'now')) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted py-4">No comments yet.</div>
        <?php endif; ?>
    </div>
</div>

</div>

