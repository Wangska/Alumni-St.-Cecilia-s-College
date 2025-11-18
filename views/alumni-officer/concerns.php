<!-- Alumni Concerns / Forum Topics -->
<div class="stat-card">
    <h5 class="mb-4" style="color: #1e3a8a; font-weight: 700; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-comments" style="color: #f59e0b;"></i>
        Alumni Concerns & Forum Topics (<?= count($concerns) ?>)
    </h5>
    
    <?php if (empty($concerns)): ?>
        <div class="text-center py-5">
            <i class="fas fa-comments" style="font-size: 48px; color: #9ca3af; margin-bottom: 15px;"></i>
            <p class="text-muted mb-0">No concerns or topics posted yet</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover" style="border-radius: 12px; overflow: hidden;">
                <thead style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                    <tr>
                        <th style="width: 50%;">Topic / Concern</th>
                        <th>Posted By</th>
                        <th>Responses</th>
                        <th>Date Posted</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($concerns as $concern): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: #1e3a8a; margin-bottom: 4px;">
                                    Topic #<?= htmlspecialchars($concern['id']) ?>
                                </div>
                                <div style="color: #6b7280; font-size: 13px; line-height: 1.4;">
                                    <?= isset($concern['message']) ? htmlspecialchars(substr(strip_tags($concern['message']), 0, 100)) : 'View details' ?>
                                    <?= (isset($concern['message']) && strlen(strip_tags($concern['message'])) > 100) ? '...' : '' ?>
                                </div>
                            </td>
                            <td style="font-weight: 500; color: #3b82f6;">
                                <?= htmlspecialchars($concern['author_name'] ?? 'Unknown') ?>
                                <br>
                                <small class="text-muted">@<?= htmlspecialchars($concern['username'] ?? 'unknown') ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary" style="padding: 6px 12px; border-radius: 20px; font-size: 12px;">
                                    <i class="fas fa-comments me-1"></i><?= $concern['comment_count'] ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= isset($concern['created_at']) ? date('M d, Y', strtotime($concern['created_at'])) : 'Recent' ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <a href="/scratch/forum/view.php?id=<?= $concern['id'] ?>" 
                                    class="btn btn-sm btn-info text-white" 
                                    style="border-radius: 8px; padding: 6px 16px;"
                                    target="_blank">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.table {
    font-size: 14px;
}
.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 14px;
}
.table tbody td {
    padding: 16px 14px;
    vertical-align: middle;
}
.table tbody tr {
    transition: all 0.3s ease;
}
.table tbody tr:hover {
    background-color: rgba(245, 158, 11, 0.05);
    transform: scale(1.005);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
</style>

